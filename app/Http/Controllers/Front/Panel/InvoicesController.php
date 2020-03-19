<?php

namespace App\Http\Controllers\Front\Panel;

use App\Contact;
use App\Http\Controllers\Front\PayTrController;
use App\InvoicePayment;
use App\ModelLogs\LogBalance;
use App\ModelUser\User;
use App\Currency;
use App\Invoice;
use App\RelUserProduct;
use App\Status;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use App\UserPromoCodes;

use function PHPSTORM_META\type;

class InvoicesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index($type)
    {
//        $belongsToCourier = Input::get('courier');
        $ifInvoiceHasCourierId = Input::get('invoice_courier');
        $response = null;
        $userId = Auth()->user()->id;
        $statusId = null;
        $country_id = Input::get('country_id',0);



        if(isset($type) && $type != 'undefined') {
            $response = $this->getTypedInvoices($userId, $type,$ifInvoiceHasCourierId,$country_id);
        } else {
            $response = $this->getAllInvoices($userId,$country_id);
        }
        $balance = Auth::user()->balance;
        $index = Currency::first();
        return response()->json(['data' => $response, 'balance' => $balance, 'index' => $index,'type' =>$type]);
    }

    public function index2($type) // kimin ne mehsulu var test ucun acilib bu funksiya
    {
//        $belongsToCourier = Input::get('courier');
        $ifInvoiceHasCourierId = Input::get('invoice_courier');
        $response = null;
        $userId = 1130; //Auth()->user()->id;
        $statusId = null;

        if(isset($type) && $type != 'undefined') {
            $response = $this->getTypedInvoices($userId, $type,$ifInvoiceHasCourierId);
        } else {
            $response = $this->getAllInvoices($userId);
        }
        $balance = Auth::user()->balance;
        $index = Currency::first();
        return response()->json(['data' => $response, 'balance' => $balance, 'index' => $index,'type' =>$type]);
    }

    public function allInvoicesCount(Request $request)
    {
        $userId = Auth()->user()->id;
        $country_id = $request->get('country_id',1);

        $data = DB::table('invoices as i')
            ->select( DB::raw('count(i.id) as  count'))
            //->select(DB::raw("(select COUNT(i1.id) from invoices as i1 where i1.package_id=i.package_id ) as count"))
            ->where('i.user_id', '=', $userId)
            ->whereBetween('i.status_id',[1,8])
            ->where('i.active', '=', 1)
            ->where('i.country_id', '=', $country_id)
            ->first();
        return response()->json([
            'data' => $data
        ]);
    }
    public function invoiceCount(Request $request){
        $userId = Auth()->user()->id;
        $country_id = $request->get('country_id',1);

        $statusId1 = Status::where('label', '=', 'waiting')->where('type', '=', 'invoice')->first();
        $data1 = DB::table('invoices as i')
            ->select( DB::raw('count(i.id) as  count'))
            //->select('i.package_id',DB::raw("(select COUNT(i1.id) from invoices as i1 where i1.package_id=i.package_id ) as count"))
            ->where('i.user_id', '=', $userId)
            ->where('i.status_id','=',$statusId1->id)
            ->where('i.active', '=', 1)
            ->where('i.country_id', '=', $country_id)
            ->first();
        if($data1!=null){
            $data['waiting']=$data1->count;
        }else{
            $data['waiting'] = 0;
        }

        $statusId2 = Status::where('label', '=', 'foreign')->where('type', '=', 'invoice')->first();
        $data2 = DB::table('invoices as i')
            ->select( DB::raw('count(i.id) as  count'))
            //->select(DB::raw("(select COUNT(i1.id) from invoices as i1 where i1.package_id=i.package_id ) as count"))
            ->where('i.user_id', '=', $userId)
            ->where('i.status_id','=',$statusId2->id)
            ->where('i.active', '=', 1)
            ->where('i.country_id', '=', $country_id)
            ->first();
        if($data2!=null){
            $data['foreign']=$data2->count;
        }else{
            $data['foreign']=0;
        }


        $statusId3 = Status::where('label', '=', 'on_the_way')->where('type', '=', 'invoice')->first();
        $data3 = DB::table('invoices as i')
            ->select( DB::raw('count(i.id) as  count'))
            //->select(DB::raw("(select COUNT(i1.id) from invoices as i1 where i1.package_id=i.package_id ) as count"))
            ->where('i.user_id', '=', $userId)
            ->where('i.status_id','=',$statusId3->id)
            ->where('i.active', '=', 1)
            ->where('i.country_id', '=', $country_id)
            ->first();
        if($data3!=null){
            $data['on_the_way']=$data3->count;
        }else{
            $data['on_the_way']=0;
        }


        $statusId4 = Status::where('label', '=', 'home')->where('type', '=', 'invoice')->first();
        $data4 = DB::table('invoices as i')
            ->select( DB::raw('count(i.id) as  count'))
            //->select(DB::raw("(select COUNT(i1.id) from invoices as i1 where i1.package_id=i.package_id ) as count"))
            ->where('i.user_id', '=', $userId)
            ->where('i.status_id','=',$statusId4->id)
            ->where('i.active', '=', 1)
            ->where('i.country_id', '=', $country_id)
            ->first();
        if($data4!=null){
            $data['home']=$data4->count;
        }else{
            $data['home']=0;
        }


        $statusId5 = Status::where('label', '=', 'completed')->where('type', '=', 'invoice')->first();
        $data5 = DB::table('invoices as i')
            ->select( DB::raw('count(i.id) as  count'))
            //->select(DB::raw("(select COUNT(i1.id) from invoices as i1 where i1.package_id=i.package_id ) as count"))
            ->where('i.user_id', '=', $userId)
            ->where('i.status_id','=',$statusId5->id)
            ->where('i.active', '=', 1)
            ->where('i.country_id', '=', $country_id)
            ->first();
        if($data5!=null){
            $data['completed']=$data5->count;
        }else{
            $data['completed']=0;
        }


        $statusId6 = Status::where('label', '=', 'has_courier')->where('type', '=', 'invoice')->first();
        $data6 = DB::table('invoices as i')
            ->select( DB::raw('count(i.id) as  count'))
            //->select(DB::raw("(select COUNT(i1.id) from invoices as i1 where i1.package_id=i.package_id ) as count"))
            ->where('i.user_id', '=', $userId)
            ->where('i.status_id','=',$statusId6->id)
            ->where('i.active', '=', 1)
            ->where('i.country_id', '=', $country_id)
            ->first();
        if($data6!=null){
            $data['has_courier']=$data6->count;
        }else{
            $data['has_courier']=0;
        }

        return response()->json([
            'data' => $data
        ]);
    }
    public function invoiceCount_old(){
        $userId = Auth()->user()->id;

        $statusId1 = Status::where('label', '=', 'waiting')->where('type', '=', 'invoice')->first();
        $data['waiting'] = DB::table('invoices as i')->select( 'i.id')->where('i.user_id', '=', $userId)->where('i.active','=',1)->where('i.status_id','=',$statusId1->id)->count();

        $statusId2 = Status::where('label', '=', 'foreign')->where('type', '=', 'invoice')->first();
        $data['foreign'] = DB::table('invoices as i')->select( 'i.id')->where('i.user_id', '=', $userId)->where('i.active','=',1)->where('i.status_id','=',$statusId2->id)->count();

        $statusId3 = Status::where('label', '=', 'on_the_way')->where('type', '=', 'invoice')->first();
        $data['on_the_way'] = DB::table('invoices as i')->select( 'i.id')->where('i.user_id', '=', $userId)->where('i.active','=',1)->where('i.status_id','=',$statusId3->id)->count();

        $statusId4 = Status::where('label', '=', 'home')->where('type', '=', 'invoice')->first();
        $data['home'] = DB::table('invoices as i')->select( 'i.id')->where('i.user_id', '=', $userId)->where('i.active','=',1)->where('i.status_id','=',$statusId4->id)->count();

        $statusId5 = Status::where('label', '=', 'completed')->where('type', '=', 'invoice')->first();
        $data['completed'] = DB::table('invoices as i')->select( 'i.id')->where('i.user_id', '=', $userId)
            ->where('i.active','=',1)
            ->where('i.status_id','=',$statusId5->id)
//            ->groupBy('i.package_id')
            ->count();

        $statusId6 = Status::where('label', '=', 'has_courier')->where('type', '=', 'invoice')->first();
        $data['has_courier'] = DB::table('invoices as i')->select( 'i.id')->where('i.user_id', '=', $userId)->where('i.active','=',1)->where('i.status_id','=',$statusId6->id)->count();


        return response()->json([
            'data' => $data
        ]);
    }

    public function countries(){
        $data = DB::table('countries')->select( '*')->where('status','=',1)->get();
        return response()->json([
            'data'=> $data
        ]);
    }
    private function getAllInvoices ($userId,$country_id=1) {
        /*$data = Invoice::with('products', 'courier', 'products.extras', 'products.productsType', 'products.statuses', 'relUserProducts.products.extras', 'relUserProducts.products.statuses')
            ->whereHas('relUserProducts', function ($query) use ($userId) {
                $query->where('user_id', '=', $userId)->where('is_paid', '=', 1);
            })->where('active',1)
            ->get();*/
        $data = DB::table('invoices as i')
            ->select(
                'i.id','i.shipping_price','i.weight','i.created_at','i.order_tracking_number','i.is_paid','i.status_id',
                'p.shop_name', 'p.quantity','p.price','p.product_type_name',
                'c.name as country_name',
                'st.name as status_name',
                /*DB::raw('GROUP_CONCAT(p.product_type_name) as products'),
                DB::raw('SUM(p.price) as sum_p_price'),
                DB::raw('SUM(i.shipping_price) as sum_i_shprice'),
                DB::raw('COUNT(i.id) as i_count'),
                DB::raw('SUM(i.is_paid) as i_is_paid')*/

                DB::raw("(select COUNT(i1.id) from invoices as i1 where i1.package_id=i.package_id ) as i_count"),
                DB::raw("(select GROUP_CONCAT(p1.product_type_name) from invoices as i1 left join products as p1 on p1.id=i1.product_id where i1.package_id=i.package_id) as products"),
                //DB::raw("(select GROUP_CONCAT(p1.shop_name) from invoices as i1 left join products as p1 on p1.id=i1.product_id where i1.package_id=i.package_id) as shop_names"),
                DB::raw("(select SUM(p1.price) from invoices as i1 left join products as p1 on p1.id=i1.product_id where i1.package_id=i.package_id) as sum_p_price"),
                DB::raw("(select SUM(i1.is_paid) from invoices as i1  where i1.package_id=i.package_id) as i_is_paid"),
                DB::raw("(select SUM(i1.shipping_price) from invoices as i1  where i1.package_id=i.package_id) as sum_i_shprice")




//                DB::raw('GROUP_CONCAT(p.product_type_name) as products')
            )
            //->leftJoin('statuses as s', 'i.status_id', '=', 's.id')
            //->leftJoin('couriers as c', 'i.courier_id', '=', 'c.id')
            ->leftJoin('products as p', 'i.product_id', '=', 'p.id')
            ->leftJoin('countries as c', 'i.country_id', '=', 'c.id')
            //->leftJoin('extras as e', 'p.extras_id', '=', 'e.id')
            ->leftJoin('product_types as pt', 'p.product_type_id', '=', 'pt.id')
            ->leftJoin('statuses as st', 'st.sid', '=', 'i.status_id')

            ->where('i.user_id', '=', $userId)
            //->where('p.is_paid', '=', 1)
            ->where('i.active','=',1)
            ->where('st.type','=','invoice')
            ->whereBetween('i.status_id',[1,9])
            ->orderBy('i.created_at','desc')
            ->groupBy('i.package_id');
/*            ->where('i.status_id','=',$statusId[0]->id)*/
            //->where('i.courier_id','=',null)
        if($country_id>=1){
            $data = $data->where('i.country_id','=',$country_id);

                    }

        $data = $data->get();
        return $data;
    }

    private function getTypedInvoices ($userId, $type, $ifInvoiceHasCourierId=null,$country_id=0) {
        $statusId = Status::where('label', '=', $type)->where('type', '=', 'invoice')->get();
//        $data = Invoice::with([
//            'courier',
//            'products.extras',
//            'products.productsType',
//            'products.statuses',
//            'relUserProducts.products.extras',
//            'relUserProducts.products.statuses'
//        ])
//            ->whereHas('relUserProducts', function ($query) use ($userId) {
//                $query->where('user_id', '=', $userId)->where('is_paid', '=', 1);
//            })
//            ->where('status_id', '=', $statusId[0]->id)->where('active',1);
//        if($belongsToCourier) {
//            $data->whereNull('courier_id')->where('is_paid', '=', '1');
//        }
//        $data = $data->get();

        $data = DB::table('invoices as i')
            ->select( 'i.id','i.shipping_price','i.weight','i.created_at','i.order_tracking_number','i.is_paid','i.status_id',
                'p.shop_name', 'p.quantity','p.price','p.product_type_name',
                'c.name as country_name',
                'st.name as status_name',
//                DB::raw('COUNT(i.id) as i_count'),
                /*DB::raw('GROUP_CONCAT(p.product_type_name) as products'),
                DB::raw('SUM(p.price) as sum_p_price'),
                DB::raw('SUM(i.shipping_price) as sum_i_shprice'),
                DB::raw('COUNT(i.id) as i_count'),
                DB::raw('SUM(i.is_paid) as i_is_paid'),*/

                DB::raw("(select COUNT(i1.id) from invoices as i1 where i1.package_id=i.package_id ) as i_count"),
                DB::raw("(select GROUP_CONCAT(p1.product_type_name) from invoices as i1 left join products as p1 on p1.id=i1.product_id where i1.package_id=i.package_id) as products"),
                //DB::raw("(select GROUP_CONCAT(p1.shop_name) from invoices as i1 left join products as p1 on p1.id=i1.product_id where i1.package_id=i.package_id) as shop_names"),
                DB::raw("(select SUM(p1.price) from invoices as i1 left join products as p1 on p1.id=i1.product_id where i1.package_id=i.package_id) as sum_p_price"),
                DB::raw("(select SUM(i1.is_paid) from invoices as i1  where i1.package_id=i.package_id) as i_is_paid"),
                DB::raw("(select SUM(i1.shipping_price) from invoices as i1  where i1.package_id=i.package_id) as sum_i_shprice")




            )
            //->leftJoin('statuses as s', 'i.status_id', '=', 's.id')
            //->leftJoin('couriers as c', 'i.courier_id', '=', 'c.id')
            ->leftJoin('products as p', 'i.product_id', '=', 'p.id')
            //->leftJoin('extras as e', 'p.extras_id', '=', 'e.id')
            ->leftJoin('product_types as pt', 'p.product_type_id', '=', 'pt.id')
            ->leftJoin('countries as c', 'i.country_id', '=', 'c.id')
            ->leftJoin('statuses as st', 'st.sid', '=', 'i.status_id')
            //->where('c.user_id', '=', $userId)
            ->where('i.user_id', '=', $userId)
            //->where('p.is_paid', '=', 1)
            ->where('i.region_id','=',1)
            ->where('i.active','=',1)
            ->where('st.type','=','invoice')
            ->orderBy('i.created_at','desc')
            ->groupBy('i.package_id');


                /*if($type=='completed'){

                    $data = $data->where(function(){
                        global $data;
                        global $statusId;
                        $statusId2 = Status::where('label', '=', 'delivered_by_courier')->where('type', '=', 'invoice')->get();

                        $data = $data->where('i.status_id','=',$statusId[0]->id)
                            ->orWhere('i.status_id','=',$statusId2[0]->id);
                    });

                }else{
                }*/

                    $data = $data->where('i.status_id','=',$statusId[0]->id);
                    $data=$data->whereNull('i.courier_id');
                    $data=$data->whereNull('i.transfer_id');

                    if($country_id>=1){
                        $data = $data->where('i.country_id','=',$country_id);

                    }

//            if($ifInvoiceHasCourierId==1)$data=$data->where('i.courier_id','!=',null);
//            else $data=$data->where('i.courier_id','=',null);


            //->where('i.courier_id','=',null)
            $data=$data->get();
        return $data;
    }

    public function payBasket(Request $request)
    {
        $uniqueId = time().Auth::user()->id;

        if (!empty($request->products) && count($request->products) > 0) {
            $validation = [
                'price' => 'required|numeric',
                'products' => 'required',
            ];
            $request->validate($validation);
            $promo_code = $request->get("promo_code",false);

            $newRel=new RelUserProduct();
            $newRel->price = $request->price;
            $newRel->transaction_id = $uniqueId;
            $newRel->is_paid = 0;
            $newRel->is_ordered = 0;
            $newRel->user_id = Auth::user()->id;
            $newRel->status_id = 1;
            $newRel->admin_id = null;
            $newRel->created_at = date("Y-m-d H:i:s");
            $newRel->save();

            if($request->products!=null){
                for($i=0;$i<count($request->products);$i++){
                    DB::table('products')
                        ->where('id', $request->products[$i])
                        ->update(['rel_user_product_id' => $newRel->id]);
                }
            }

            $pay = new PayTrController();
            $token = $pay->payByTr($request->price, $request->products, $uniqueId);
            if($promo_code){
                UserPromoCodes::create([
                    "user_id" => auth()->id(),
                    "campaign_id" => 1,
                    "promo_code" => $promo_code,
                    "invoice_id" => 0,
                    "status" => 0
                ]);
            }
            return view('paymentTr', compact('token'));
        } else {
            return response()->json('Məlumatlar tam deyildir', 500);
        }
    }

    public function payInvoice(Request $request)
    {
        $invoices = $request->invoices;
        $price = Input::get('price', 0);
        $currency = Input::get('currency', 'azn');
        $user=User::where('id',Auth()->user()->id)->first();
        if($user->balance>=$price){
            for ($i = 0; $i < count($invoices); $i++) {
                $payment = new InvoicePayment();
                $payment->invoice_id = $invoices[$i];
                $payment->price = $price;
                $payment->currency = $currency;
                $payment->save();

                if($invoices[$i]>0){
                    $invoice = Invoice::where('id',$invoices[$i])->first();
                    $invoice->is_paid=1;
                    $invoice->save();
                }
            }
            $old_balance = $user->balance;
            $user->balance = $old_balance - $price;
            $user->save();



            $message = 'Balansdan çatdırılma haqqı ödənişi';

            LogBalance::create([
                'user_id' => $user->id,
                'old_balance' => $old_balance,
                'new_balance' => $user->balance,
                'money' => $price,
                'message' => $message,
                'type' => 'azn'
            ]);

            return response()->json([
                'data'=>'ok'
            ]);
        }else{
            return response()->json(['data' => 'Balansınız çatmır', 'code' => 1601]);
        }


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
     * @param  \Illuminate\Http\Request $request
     * @param Contact $contact
     * @return void
     */
    public function store(Request $request)
    {

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
}
