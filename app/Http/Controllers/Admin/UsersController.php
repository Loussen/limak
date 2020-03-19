<?php

namespace App\Http\Controllers\Admin;

use App\ModelMessages\Message;
use App\ModelProduct\Product;
use App\ModelUser\User;
use App\ModelUser\UserContact;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\RelUserProduct;
use App\Status;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $name = Input::get('name');
        $uniqid = Input::get('uniqid');
        $surname = Input::get('surname');
        $email = Input::get('email');
        $pin = Input::get('pin');
        $balance = Input::get('balance');
        $serial_number = Input::get('serial_number');
        $address = Input::get('address');
        $birthdate = Input::get('birthdate');
        $users = User::orderBy('id', 'desc');

        if(!empty($name)) {
            $users = $users->where('name', 'like', '%'.$name.'%');
        }
        if(!empty($surname)) {
            $users = $users->where('surname', 'like', '%'.$surname.'%');
        }
        if(!empty($email)) {
            $users = $users->where('email', '=', $email);
        }
        if(!empty($uniqid)) {
            $users = $users->where('uniqid', '=', $uniqid);
        }
        if(!empty($pin)) {
            $users = $users->where('pin', '=', $pin);
        }
        if(!empty($balance)) {
            $users = $users->where('balance', '=', $balance);
        }
        if(!empty($serial_number)) {
            $users = $users->where('serial_number', '=', $serial_number);
        }
        if(!empty($address)) {
            $users = $users->where('address', 'like', '%'.$address.'%');
        }
        if(!empty($birthdate)) {

            $users = $users->where('birthdate', '=', date('d.m.Y', strtotime($birthdate)));
        }
        $users = $users->paginate(15);
        $usersCount = User::count();
        $usersGender = User::where('gender' , 1)->count();
        $usersMonth = User::whereMonth('created_at', '=', date('m'))->count();
        $usersDay = User::whereDay('created_at', '=', date('d'))->count();
        $usersBalance = User::sum('balance');
        return view('admin.users', compact('users', 'usersCount' ,'usersGender', 'usersMonth' , 'usersDay', 'usersBalance'));
    }

    public function allData(Request $request,$id = false)
    {
        $response = [
            'user_id' => false,
            'info'  => false,
            'response'  => false,
            'statuses'  => false,
            'messages'  => false,
        ];

        $uniqid = $request->get('uniqid', '');
        $phone = $request->get('phone', '');
        if(!empty($uniqid))
            $User = User::where('uniqid', '=', $request->uniqid)->first();

        if((empty($uniqid) || $User === null) && !empty($phone)){
            $UserContacts = UserContact::where('name', 'like', '%'.$phone.'%' )->first();
            if($UserContacts !== null)
                return redirect('/admin/user/allData/'.$UserContacts->user_id);
        }

        $User = ($id && !$request->has('uniqid'))? User::find($id): User::where('uniqid', '=', $request->uniqid)->first();

        if($User === null) return view('admin.users.allData', $response);

        if(!$id) return redirect('/admin/user/allData/'.$User->id);

        $UserContacts = UserContact::where('user_id', '=', $id)->first();

        $response['user_id'] = $User->id;
        $response['info'] = $User;
        $response['contact'] = $UserContacts;

        $type = Input::get('type');
        $orders = null;
        $statusId = null;

        if(isset($type) && $type != 'undefined' && $type != 14) {
            $orders = $this->getTypedOrders($id,$type);
        } elseif($type == 14){
            $orders = $this->getNotPaidOrders($id);
        }else{
            $orders = $this->getAllOrders($id);
        }

        $response['response'] = $orders;

        $response['statuses'] = Status::all();
        $response['messages'] = Message::where(function($query) use ($User){
            $query->where('from_user_id', '=', $User->uniqid)
                ->orWhere('to_user_id', '=', $User->uniqid);
        })->get();


        return view('admin.users.allData', $response);
    }

    public function addBalance(Request $request){

        $User = User::find((int) $request->user_id);

        $User->balance = ($request->type == 'minus')? $User->balance - $request->money : $User->balance + $request->money;
        $User->save();

        return response()->json(['success' => true]);
    }

    private function getAllOrders ($id) {
        $notIn[0] = getStatusByLabel("rejection", 'transaction');
        $notIn[1] = getStatusByLabel("rejection_accepted", 'transaction');
//        dd($notIn);
        $data = RelUserProduct::with('products', 'products.extras', 'products.statuses')
            ->whereNotIn('status_id', $notIn)
            ->whereNotNull('transaction_id')
            ->where('is_paid', '=', 1)
            ->where('user_id', '=', $id)
            ->orderBy('id', 'desc')
            ->get();
        return $data;
    }

    private function getNotPaidOrders ($id) {
        $data = RelUserProduct::with('products', 'products.extras', 'products.statuses')
            ->where('is_paid', '=', 0)
            ->where('user_id', '=', $id)
            ->orderBy('id', 'desc')
            ->get();
        return $data;
    }

    private function getTypedOrders ($id,$status_id) {

        $callback = function ($query) use ($status_id) {
            $query->where('status_id', '=', $status_id)->orderBy('id', 'desc');
        };
        $data = RelUserProduct::with(['products.extras', 'products' => $callback])
            ->whereHas('products', $callback)
            ->where('is_paid', '=', 1)
            ->where('is_ordered', '=', 1)
            ->where('user_id', '=', $id)
            ->orderBy('id', 'desc')
            ->get();

        return $data;

    }

    private function getTransactionRejectOrders ($id) {
        $transStatusId = Status::where('label', '=', 'rejection')->where('type', '=', 'transaction')->get();
        $data = RelUserProduct::with(['products.extras','products'])
            ->where('status_id', '=', $transStatusId[0]->id)
            ->where('user_id', '=', $id)
            ->orderBy('id', 'desc')
            ->get();
        return $data;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    public function sendEmail(Request $request){
        $email = $request->email;
        $notification = $request->notification;

        $data = (object) ['text' => $notification];
        email($data, $email);
        return $this->json(['success' => true]);
    }

    public function sendEmailForAll(Request $request){

        $users = User::all();

        $data = (object) ['text' => 'Hörmətli istifadəçi zəhmət olmasa saytımıza daxil olaraq tənzimləmələr hissəsindən FİN kodunuzu doğru daxil edin.'];
        foreach ($users as $user){
            email($data, $user->email);
        }

        return $this->json(['success' => true]);
    }


    public function sendSmsForAll(Request $request){

        $invoice = DB::table('invoices as i')
            ->select('i.width', 'i.height', 'i.length', 'i.weight' , 'i.shipping_price','i.purchase_no','i.waybill','i.order_tracking_number',
                'u.uniqid', 'u.name', 'u.surname', 'u.address', 'u.serial_number','u.birthdate','u.id as user_id',
                'c.name as phone', 'p.price', 'p.quantity', 'p.shop_name', 'product_type_name','p.description'
            )
            ->leftJoin('rel_user_products as r', 'r.id', '=', 'i.rel_user_product_id')
            ->leftJoin('users as u', 'r.user_id', '=', 'u.id')
            ->leftJoin('user_contacts as c', 'c.user_id', '=', 'u.id')
            ->leftJoin('products as p', 'i.product_id', '=', 'p.id')
            ->where('i.status_id', '=', 4)
            ->where('i.updated_at', '<', '2019-03-12 23:59:59')
            ->where('i.updated_at', '>', '2019-03-12 00:00:01')
            ->get();

        //var_dump($invoice); die;

        $users = [];
        $data = (object) ['text' => 'Hormetli musteri, baglamanız artıq Bakı ofisimizdedir. Unvan: Lermontov kuc. 113/117. Iceriseher m/st yaxınlıgı. https://limak.az'];
        $data2 = (object) ['text' => 'Xatırladaq ki, kuryer xidmetimizdən yararlana bilersiniz. Baki və Sumqayıtda istenilən unvana catdirilir xidmet haqqi 3 AZN'];
        foreach($invoice as $item){
            $phone = str_replace(' ','', $item->phone);
            if(!in_array($phone, $users)){
                $users[] = $phone;
                sms($data, $phone);
                sms($data2, $phone);
            }
        }
        return response()->json(['success' => true]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function block($id)
    {
        $user = User::findOrFail($id);
        $user->is_blocked = !$user->is_blocked;
        $user->update();
        return back();
    }
}
