<?php

namespace App\Http\Controllers\Admin;

use App\Currency;
use App\Invoice;
use App\InvoiceDates;
use App\Invoiceless;
use App\Libraries\Upload\Uploader;
use App\ModelAccount\Account;
use App\ModelLogs\AccountLog;
use App\ModelProduct\Product;
use App\ModelProduct\ProductType;
use App\ModelUser\User;
use App\Packages;
use App\RelUserProduct;
use App\Status;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;


class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( Request $request)
    {
        $tab = Input::get('tab')?Input::get('tab') : 'all';
        $pin = Input::get('pin');
        $tel = Input::get('tel');
        $user_code = Input::get('user_code');
        $params = Input::get();
        $dataStorage = [];
        $dataInRoad = [];
        $dataWaiting = [];
        $foreignPermission = $this->checkRole('storage_foreign');
        $homePermission = $this->checkRole('storage_home');

        $superAdminPermission = $this->checkRole('super_admin');
        $label = false;

        if($foreignPermission || $superAdminPermission) {
            $label = 'foreign';
        }
        /*if($homePermission || $superAdminPermission) {
            $label = 'home';
        }*/

        $invoice = ($request->has('invoice') && $request->get('invoice') == 1) ? '<>' : '=';

        if($foreignPermission || $superAdminPermission) {
            if($tab == 'all') {
                $dataStorage = $this->inStorage($pin, $tel, $label, $user_code);
                $dataInRoad = $this->inRoad($pin, $tel, $user_code);
                if($label === 'foreign') {
                    $dataWaiting = $this->waiting($pin, $tel, $user_code, $invoice);
                }
            } else if ($tab === 'storage') {
                $dataStorage = $this->inStorage($pin, $tel, $label, $user_code);
            } else if($tab === 'road') {
                $dataInRoad = $this->inRoad($pin, $tel, $user_code);
            }else if($tab === 'waiting') {

                $dataWaiting = $this->waiting($pin, $tel, $user_code, $invoice);
            }
        }

        $productTypes = ProductType::all();

        if($request->ajax()) {
            return view('admin.invoice.ajax', compact('dataStorage', 'dataInRoad', 'dataWaiting','label' ,'productTypes'));
        } else {
            return view('admin.invoice.index', compact('dataStorage', 'dataInRoad', 'dataWaiting','label' ,'productTypes'));
        }
    }

    public function test(Request $request)
    {

        $fullname = Input::get('fullname', '');
        $uniqid = Input::get('user_code','');
        $status = Input::get('status', 2);
        $last30type = Input::get('type', '' );


        $fullname=explode(' ',$fullname);
//var_dump($fullname);
        \Artisan::call('cache:clear');
        \Artisan::call('view:clear');
        $data = DB::table('invoices as i')
            ->select('i.width', 'i.height', 'i.length', 'i.weight' , 'i.shipping_price','i.purchase_no','i.waybill','i.file','i.id','i.status_id',
                'u.uniqid', 'u.name', 'u.surname', 'u.address','u.email','u.pin',
                'c.name as phone', 'p.price', 'p.quantity', 'p.shop_name','p.id as product_id', 'product_type_name','s.name as invoice_status'
            )
            ->leftJoin('users as u', 'i.user_id', '=', 'u.id')
            ->leftJoin('user_contacts as c', 'c.user_id', '=', 'u.id')
            ->leftJoin('products as p', 'i.product_id', '=', 'p.id')
            ->leftJoin('product_types as pt', 'p.product_type_id', '=', 'pt.id')
            ->leftJoin('statuses as s', 'i.status_id', '=', 's.id')
            ->where('i.status_id', '=', $status)
            ->where('i.active','=',1);
            if(!empty($uniqid)) $data=$data->where('u.uniqid','=', $uniqid);
            if(!empty($fullname[0])) $data = $data->where('u.name' ,'like','%' . $fullname[0] . '%')->orWhere('u.surname' ,'like','%' . $fullname[0] . '%');
            if(isset($fullname[1]) || !empty($fullname[1])) $data = $data->where('u.name' ,'like','%' . $fullname[1] . '%')->orWhere('u.surname' ,'like','%' . $fullname[1] . '%');
            $data=$data->get();
//            if($data) {
//                return response()->json([
//                    'status' => 200,
//                    'data' => $data,
//                ]);
//            }


        $productTypes = ProductType::all();

//        $User = User::where('uniqid','=', $user_code);
//        if(!empty($fullname[0])) $User = $User->orWhere('name' ,'like','%' . $fullname[0] . '%')->orWhere('surname' ,'like','%' . $fullname[0] . '%');
//        if(isset($fullname[1]) || !empty($fullname[1])) $User = $User->orWhere('name' ,'like','%' . $fullname[1] . '%')->orWhere('surname' ,'like','%' . $fullname[1] . '%');
//        $User=$User->get();

//        $user_ids = [];
//        $data = Invoice::where('active', '=', '1')->where('status_id','=',$status);

//        if(count($User)>0){
//            foreach ($User as $item){
//                $user_ids[] = $item->id;
//            }
//            $data = $data->whereIn('user_id', $user_ids);
//        }
//        $data = $data
//            ->with('invoiceStatus')
//            ->with('products.productTypes')
//            ->with('products.users')->with('products.users.userContacts')->orderBy('created_at', 'DESC')->get();

        $currency = Currency::find(1);
        $tryToUsd = 1/$currency->tl * $currency->usd;

        if($request->ajax()) {
            return view('admin.invoice.ajax1',  compact('data' ,'status'));
        } else {
            return view('admin.invoice.test1',  compact('data','status','productTypes','tryToUsd','last30type'));
        }

    }
    public function test111(Request $request)
    {

        $fullname = Input::get('fullname', '');
        $uniqid = Input::get('user_code','');
        $status = Input::get('status', 2);
        $query = Input::get('query', 0);

        \Artisan::call('cache:clear');
        \Artisan::call('view:clear');
        $data = DB::table('invoices as i')
            ->select('i.width', 'i.height', 'i.length', 'i.weight' , 'i.shipping_price','i.purchase_no','i.waybill','i.file','i.id','i.status_id',
                'u.uniqid', 'u.name', 'u.surname', 'u.address','u.email','u.pin',
                'c.name as phone', 'p.price', 'p.quantity', 'p.shop_name','p.id as product_id', 'product_type_name','s.name as invoice_status'
            )
            ->leftJoin('users as u', 'i.user_id', '=', 'u.id')
            ->leftJoin('user_contacts as c', 'c.user_id', '=', 'u.id')
            ->leftJoin('products as p', 'i.product_id', '=', 'p.id')
            ->leftJoin('product_types as pt', 'p.product_type_id', '=', 'pt.id')
            ->leftJoin('statuses as s', 'i.status_id', '=', 's.id')
            ->where('i.status_id', '=', $status)
            ->where('i.active','=',1)
            ->where('u.uniqid', 'like', '%0'.$uniqid);
            $data=$data->where(function($data) use ($fullname){
                $fullname=explode(' ',$fullname);
                if(!empty($fullname[0])) $data->orWhere('u.name' ,'like','%' . $fullname[0] . '%')->orWhere('u.surname' ,'like','%' . $fullname[0] . '%');
                if(isset($fullname[1]) || !empty($fullname[1])) $data->orWhere('u.name' ,'like','%' . $fullname[1] . '%')->orWhere('u.surname' ,'like','%' . $fullname[1] . '%');
            });

            if($query==1) {
                $data = $data->toSql();
            }else
                $data=$data->get();

        if($data) {
                return response()->json([
                    'status' => 200,
                    'data' => $data,
                ]);
            }

    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $uniqid = $request->get('uniqid', false);
        $fullname = $request->get('fullname', false);
        $User = User::with('userContacts','foreignStock','foreignStock.products','foreignStock.products.productTypes')->where('uniqid','=', $uniqid);
        $fullname=explode(' ',$fullname);
        if(!empty($fullname[0])) $User = $User->orWhere('name' ,'like','%' . $fullname[0] . '%')->orWhere('surname' ,'like','%' . $fullname[0] . '%');
        if(isset($fullname[1]) || !empty($fullname[1])) $User = $User->orWhere('name' ,'like','%' . $fullname[1] . '%')->orWhere('surname' ,'like','%' . $fullname[1] . '%');

        $result = $User->first();

        if($result) {
            return response()->json([
                'status' => 200,
                'data' => $result,
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'product_type_id' => 'required|integer',
            'price' => 'required|integer',
            'quantity' => 'required|integer',
            'shop_name' => 'required|string',
            'invoice' => 'required',
            'order_number' => 'required',
            'order_date' => 'required',
        ];

        $messages = [
            'price.integer' => 'Məhsulun qiyməti xanasına rəqəm daxil edin',
            'quantity.integer' => 'Məhsulun qiyməti xanasına rəqəm daxil edin',
            'product_type_id' => 'Məhsulun qiyməti xanasını düzgün seçin',
            'required' => 'Bütün xanaları doldurun',
        ];

        Validator::make($request->all(), $rules, $messages);
        $lastPurchase_no = Invoice::select('id')->orderBy('id', 'desc')->first();
        if($lastPurchase_no) {
            $lastPurchase_no =$lastPurchase_no->id;
        }else {
            $lastPurchase_no = 0;
        }


        $User = User::where('uniqid', '=', $request->user_code)->first();

        $newRelUserProduct = new RelUserProduct();
        $newRelUserProduct->status_id = 1;
        $newRelUserProduct->user_id = $User->id;
        $newRelUserProduct->is_paid = 1;
        $newRelUserProduct->is_ordered = 1;
        $newRelUserProduct->save();
        //
        $newProduct = new Product();
        $newProduct->product_type_id = $request->product_type_id;
        $newProduct->rel_user_product_id = $newRelUserProduct->id;
        $newProduct->price = $request->price;
        $newProduct->quantity = $request->quantity;
        $newProduct->shop_name = $request->shop_name;
        $newProduct->description = '';
        $newProduct->status_id = 2;
        $newProduct->save();

        $file = Uploader::upload($request['invoice'], 'public/invoice/', 'invoice', false, true);
        $file = '/storage/invoice/'.$file;

        $newInvoice = new Invoice();
        $newInvoice->user_id = $User->id;
        $newInvoice->product_id = $newProduct->id;
        $newInvoice->rel_user_product_id = $newRelUserProduct->id;
        $newInvoice->status_id = 1;
        $newInvoice->purchase_no = str_pad($lastPurchase_no + 1, 9, 0, STR_PAD_LEFT);
        $newInvoice->order_tracking_number = $request->order_number;
        $newInvoice->order_date = $request->order_date;
        $newInvoice->file = $file;


        if ($newInvoice->save()) {
            return response()->json(['status' => 200, 'message' => 'Bəyənnamə müvəffəqiyyətlə yükləndi']);
        }
    }

    public function changeStatus(Request $request)
    {
        $invoice= Invoice::find($request->id);
        $invoice->status_id = $request->status_id + 1;
        $invoice->save();

        $invoice_date=new InvoiceDates();
        $invoice_date->status_id=2;
        $invoice_date->invoice_id=$request->id;
        $invoice_date->action_date=date('Y-m-d H:i:s');
        $invoice_date->save();

        if($invoice) {
            return response()->json([
                'status' => 200,
                'data' => 'ok'
            ]);
        }
    }

    public function changeAllStatuses(Request $request)
    {
        foreach ($request->ids_array as $id){
            $invoice = Invoice::find($id);
            $invoice->status_id=$invoice->status_id+1;
            $invoice->save();
        }
        $data='ok';
        if($data) {
            return response()->json([
                'status' => 200,
                'data' => $data,
            ]);
        }
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

    private function checkRole ($label) {
        $status = false;
        foreach (Auth::guard('admin')->user()->relAdminRoles as $value) {
            if($value->relRole->label === $label) {
                $status = true;
            }
        }
        return $status;
    }

    private function inStorage($pin, $tel, $label, $user_code) {

        if($tel || $pin || $user_code) {
            $data = Invoice::with('invoiceStatus')->whereHas('invoiceStatus', function ($query) use ($label) {
                $query->where('label', '=', $label);
            })->with('products.productTypes')->with('relUserProducts.users')->whereHas('relUserProducts.users', function($query) use($user_code) {
                if($user_code) {
                    $query->where('uniqid', 'like','%' . $user_code .'%');
                }
            })->where('active', '=', '1')->with('relUserProducts.users.userContacts')->whereHas('relUserProducts.users.userContacts', function ($query) use ($tel, $pin) {
                if ($pin) {
                    $query->where('pin', '=', $pin)->where('name', 'like', '%' . $tel .'%');
                } else {
                    $query->where('name', 'like', '%' . $tel .'%');
                }
            })->orderBy('created_at', 'DESC')->paginate(50);
        } else{
            $data = Invoice::with('invoiceStatus')->whereHas('invoiceStatus', function ($query) use ($label) {
                $query->where('label', '=', $label);
            })->where('active', '=', '1')->with('products.productTypes')->with('relUserProducts.users.userContacts')->orderBy('created_at', 'DESC')->paginate(50);
        }
        return $data;
    }

    private function waiting($pin, $tel, $user_code, $invoice) {
        $label = 'waiting';
        if($tel || $pin || $user_code) {
            $data = Invoice::with('invoiceStatus')->whereHas('invoiceStatus', function ($query) use ($label) {
                $query->where('label', '=', $label);
            })->where('active', '=', '1')->with('products.productTypes')->with('relUserProducts.users')->whereHas('relUserProducts.users', function($query) use($user_code) {
                if($user_code) {
                    $query->where('uniqid', 'like','%' . $user_code .'%');
                }
            })->with('relUserProducts.users.userContacts')->whereHas('relUserProducts.users.userContacts', function ($query) use ($tel, $pin) {
                if ($pin) {
                    $query->where('pin', '=', $pin)->where('name', 'like', '%' . $tel .'%');
                } else {
                    $query->where('name', 'like', '%' . $tel .'%');
                }
            })->where('file', $invoice, NULL)->orderBy('created_at', 'DESC')->paginate(50);
        } else{
            $data = Invoice::with('invoiceStatus')->whereHas('invoiceStatus', function ($query) use ($label) {
                $query->where('label', '=', $label);
            })->where('active', '=', '1')->where('file', $invoice, NULL)->with('products.productTypes')->with('relUserProducts.users.userContacts')->orderBy('created_at', 'DESC')->paginate(50);
        }
        return $data;
    }

    private function inRoad($pin, $tel, $user_code) {
        $label = 'on_the_way';
        if($tel || $pin || $user_code) {
            $data = Invoice::with('invoiceStatus')->whereHas('invoiceStatus', function ($query) use ($label) {
                $query->where('label', '=', $label);
            })->where('active', '=', '1')->with('products.productTypes')->with('relUserProducts.users')->whereHas('relUserProducts.users', function($query) use($user_code) {
                if($user_code) {
                    $query->where('uniqid', 'like','%' . $user_code .'%');
                }
            })
                ->with('relUserProducts.users.userContacts')->whereHas('relUserProducts.users.userContacts', function ($query) use ($tel, $pin) {
                if ($pin) {
                    $query->where('pin', '=', $pin)->where('name', 'like', '%' . $tel .'%');
                } else {
                    $query->where('name', 'like', '%' . $tel .'%');
                }
            })->orderBy('created_at', 'DESC')->paginate(50);
        } else{
            $data = Invoice::with('invoiceStatus')->whereHas('invoiceStatus', function ($query) use ($label) {
                $query->where('label', '=', $label);
            })->where('active', '=', '1')->with('products.productTypes')->with('relUserProducts.users.userContacts')->orderBy('created_at', 'DESC')->paginate(50);
        }
        return $data;
    }

    public function setInvoiceDataByBuyer (Request $request) {
        $productIds = $request['productIds'];
        $package= new Packages();
        $package->status = 1;
        $package->save();
        $i = 0;
        $adminId = Auth::guard('admin')->user()->id;

        foreach($productIds as $productId){
            $productModel = Product::find($productId);
            /*$lastPurchase_no = Invoice::select('id')->orderBy('id', 'desc')->first();
            if($lastPurchase_no) {
                $lastPurchase_no =$lastPurchase_no->id;
            }else {
                $lastPurchase_no = 0;
            }*/

            $productModel->shop_name          = $request['shop'];
            $productModel->quantity           = $request['packageCount'];
            $productModel->product_type_name  = $request['productType'];
            //$productModel->price            = $request['productPrice'];
            $productModel->status_id          = 2;
            $productModel->is_ordered         = 1;
            $productModel->admin_id           = $adminId;
            $productModel->save();


            $invoiceModel = new Invoice();
            $invoiceModel->user_id               = $productModel->user_id;
            $invoiceModel->region_id             = $productModel->region_id;
            $invoiceModel->price                 = $request['productPrice'];
//            $invoiceModel->price                 = $request['expenses'];
            $invoiceModel->product_id            = $productModel->id;
            $invoiceModel->rel_user_product_id   = $productModel->rel_user_product_id;
            $invoiceModel->status_id             = 1;
            $invoiceModel->promo_code            = $productModel->promo_code;
            //$invoiceModel->purchase_no           = str_pad($lastPurchase_no + 1, 9, 0, STR_PAD_LEFT);
            $invoiceModel->order_tracking_number = $request['orderTrackingNumber'];
            $invoiceModel->delivery_number       = $request['deliveryNumber'];
            $invoiceModel->order_date            = $request['orderDate'];
            $invoiceModel->package_id            = $package->id;
            $invoiceModel->added_by              = 'admin';
            $invoiceModel->corporate             = $productModel->corporate;
            $invoiceModel->is_premium            = $productModel->is_premium;
            $invoiceModel->client_id             = $productModel->client_id;
            $invoiceModel->account_id            = (int) $request['account'];


            if($i != 0) $invoiceModel->active = 0;
            $invoiceModel->save();

            if($invoiceModel->save()){
                $invoiceModel->purchase_no = str_pad($invoiceModel->id, 9, 0, STR_PAD_LEFT);
                $invoiceModel->save();
            }

            $invoice_date=new InvoiceDates();
            $invoice_date->status_id=1;
            $invoice_date->invoice_id=$invoiceModel->id;
            $invoice_date->action_date=$invoiceModel->order_date;
            $invoice_date->save();

            $i++;
        }

        $account = Account::find((int) $request['account']);
        $before_payment = $account->balance;
        $account->balance = $account->balance - $request['expenses'];
        if($account->save()){
            AccountLog::create([
                'account_id' => $account->id,
                'type' => 'minus',
                'payment' => $request['expenses'],
                'before_payment' => $before_payment,
                'after_payment' => $account->balance,
                'admin_id' => $adminId,
                'user_id' => $invoiceModel->user_id,
                'invoice_id' =>$invoiceModel->id,
                'comment' => ' Müştəri sifarişi'
            ]);
        }

        if (true) {
            return response()->json([
                'status'  => 200,
                'message' => 'Bəyənnamə yükləndi!'
            ]);
        } else {
            return response()->json([
                'status'  => 500,
                'message' => 'Server xətası!'
            ]);
        }
    }

    public function updateInvoiceDataByBuyer (Request $request) {
        $productId = $request['productId'];
        $invoiceId = $request['invoiceId'];

        $productModel = Product::find($productId);
        $invoiceModel_save = Invoice::find($invoiceId);
        $invoiceModel_save->price=$request['productPrice'];
        $invoiceModel_save->save();

        $productModel->shop_name          = $request['shop'];
        $productModel->quantity           = $request['packageCount'];
        $productModel->product_type_name  = $request['productType'];
        $productModel->comment  = $request['comment'];
//        $productModel->price              = $request['productPrice'];
        $productModel->save();

        $invoiceModel = $productModel->invoices[0];

        $invoiceModel->order_tracking_number = $request['orderTrackingNumber'];
        $invoiceModel->order_date            = $request['orderDate'];

        if ($invoiceModel->save()) {
            return response()->json([
                'status'  => 200,
                'message' => 'Bəyənnamə müvəffəqiyyətlə dəyişdirildi!'
            ]);
        } else {
            return response()->json([
                'status'  => 500,
                'message' => 'Server xətası!'
            ]);
        }
    }

    public function addToOnTheWay (Request $request) {
        $status = Status::where('label', '=', 'on_the_way')->where('type', '=', 'invoice')->get();
        $invoice = Invoice::find($request->id);
        $invoice->status_id = $status[0]->id;
        $invoice->save();
        return response()->json(['status' => 'ok', 'code' => 200]);
    }

    public function addToForeignStorage (Request $request) {
        $status = Status::where('label', '=', 'foreign')->where('type', '=', 'invoice')->get();
        $invoice = Invoice::find($request->id);
        $invoice->status_id = $status[0]->id;
        $invoice->save();
        return response()->json(['status' => 'ok', 'code' => 200]);
    }

    public function addToStorage (Request $request) {
        $status = Status::where('label', '=', 'home')->where('type', '=', 'invoice')->get();
        $invoice = Invoice::find($request->id);
        $invoice->status_id = $status[0]->id;
        $invoice->save();
        return response()->json(['status' => 'ok', 'code' => 200]);
    }

    public function addToCompleted (Request $request) {
        $status = Status::where('label', '=', 'completed')->where('type', '=', 'invoice')->get();
        $invoice = Invoice::find($request->id);
        $invoice->status_id = $status[0]->id;
        $invoice->save();
        return response()->json(['status' => 'ok', 'code' => 200]);
    }

    public function sendInvoicelessMessage(Request $request) {

        $user = User::where('uniqid', $request->user_uid)->first();
        notify((object)['template' => 'invoice-id-absent'], (object)['phone' => $user->userContacts[0]->name, 'email' => $user->email]);

        if($request->ajax()) {
            return response()->json('OK', 200);
        } else {
            return back();
        }


        $data = new Invoiceless();
        $data->note = $note;
        $data->user_uid = $user_uid;
        $data->save();
        return response()->json(["data" => "ok", "code" => 200]);
    }

    public function waybill($id){

        $invoice = DB::table('invoices as i')
            ->select('i.width', 'i.height', 'i.length', 'i.weight' , 'i.shipping_price','i.purchase_no',
                'u.uniqid', 'u.name', 'u.surname', 'u.address',
                'c.name as phone', 'p.price', 'p.quantity', 'p.shop_name', 'product_type_name'
            )
            ->leftJoin('rel_user_products as r', 'r.id', '=', 'i.rel_user_product_id')
            ->leftJoin('users as u', 'r.user_id', '=', 'u.id')
            ->leftJoin('user_contacts as c', 'c.user_id', '=', 'u.id')
            ->leftJoin('products as p', 'i.product_id', '=', 'p.id')
            ->where('i.id', '=', $id)
            ->first();

        $currency = Currency::find(1);

        $tryToUsd = 1/$currency->tl * $currency->usd;

        return view('admin.invoice.waybill', ['data'=>$invoice, 'tryToUsd' => $tryToUsd]);
    }

    public function xml(){

        \Artisan::call('cache:clear');
        \Artisan::call('view:clear');
        $invoice = DB::table('invoices as i')
            ->select('i.width', 'i.height', 'i.length', 'i.weight' , 'i.shipping_price','i.purchase_no','i.waybill','i.order_tracking_number',
                'u.uniqid', 'u.name', 'u.surname', 'u.address', 'u.pin', 'c.name as phone',
                'c.name as phone', 'p.price', 'p.quantity', 'p.shop_name', 'product_type_name'
            )
            ->leftJoin('users as u', 'i.user_id', '=', 'u.id')
            ->leftJoin('user_contacts as c', 'c.user_id', '=', 'u.id')
            ->leftJoin('products as p', 'i.product_id', '=', 'p.id')
            ->where('i.status_id', '=', 3)
            ->where('i.active', '=', '1')
            ->get();

        $currency = Currency::find(1);

        $tryToUsd = 1/$currency->tl * $currency->usd;

        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="yes"?><GoodsInfo></GoodsInfo>');
        //$xml->addAttribute('version', '1.0');
        foreach ($invoice as $item){
            $Goods = $xml->addChild('GOODS');
            $Goods->addChild('TR_NUMBER', $item->purchase_no);
            $Goods->addChild('DIRECTION', 1);
            $Goods->addChild('QUANTITY_OF_GOODS', $item->quantity);
            $Goods->addChild('WEIGHT_GOODS', $item->weight);
            $Goods->addChild('INVOYS_PRICE', round($item->price / $tryToUsd, 2) + $item->shipping_price);
            $Goods->addChild('CURRENCY_TYPE', 840);
            $Goods->addChild('NAME_OF_GOODS', htmlspecialchars($item->product_type_name));
            $Goods->addChild('IDXAL_NAME', $item->name." ".$item->surname);
            $Goods->addChild('IDXAL_ADRESS', $item->address);
            $Goods->addChild('IXRAC_NAME', htmlspecialchars($item->shop_name));
            $Goods->addChild('IXRAC_ADRESS', 'Istanbul');
            $Goods->addChild('GOODS_TRAFFIC_FR', 792);
            $Goods->addChild('GOODS_TRAFFIC_TO', '031');
            $Goods->addChild('QAIME', $item->waybill);
            $Goods->addChild('TRACKING_NO', $item->order_tracking_number);
            $Goods->addChild('FIN', $item->pin);
            $Goods->addChild('PHONE', $item->phone);
        }


        $response = Response::make($xml->asXML(), 200);
        $response->header('Content-Description', 'File Transfer');
        $response->header('Content-Disposition', 'attachment; filename=Inv-'.date('d-m-Y').'.xml');
        $response->header('Content-Type', 'text/xml');

        return $response;
    }

    public function manifest(){

        \Artisan::call('cache:clear');
        \Artisan::call('view:clear');
        $invoice = DB::table('invoices as i')
            ->select('i.width', 'i.height', 'i.length', 'i.weight' , 'i.shipping_price','i.purchase_no','i.waybill','i.order_tracking_number',
                'u.uniqid', 'u.name', 'u.surname', 'u.address', 'u.serial_number','u.birthdate','u.id as user_id',
                'c.name as phone', 'p.price', 'p.quantity', 'p.shop_name', 'product_type_name','p.description'
            )
            ->leftJoin('users as u', 'i.user_id', '=', 'u.id')
            ->leftJoin('user_contacts as c', 'c.user_id', '=', 'u.id')
            ->leftJoin('products as p', 'i.product_id', '=', 'p.id')
            ->where('i.status_id', '=', 3)
            ->where('i.active', '=', 1)
            ->orderBy('i.user_id', 'DESC')
            ->get();

        $data_users=[];
        foreach ($invoice as $item){
            $data_users[] = $item->user_id;
        }


        $currency = Currency::find(1);

        $tryToUsd = 1/$currency->tl * $currency->usd;

        $minus30=date('Y-m-d', strtotime('-30 day', strtotime(date('Y-m-d'))));
        $last30days=DB::table('invoices as i')
            ->select(DB::raw('SUM(i.shipping_price) as shipping_price'), DB::raw('SUM(p.price) as price'), 'i.user_id')
            ->leftJoin('products as p', 'i.product_id', '=', 'p.id')
            ->where('i.order_date', '>', $minus30)
            ->where('i.active', '=', 1)
            ->whereIN('i.user_id', array_unique($data_users))
            ->groupBy('i.user_id')
            ->get();



        foreach ($last30days as $item){
            $data_users[$item->user_id] = number_format($item->price / $tryToUsd + $item->shipping_price, 2);
        }
/*
        var_dump($invoice);

        var_dump($data_users); die;*/

//        dd($last30days);

        return view('admin.invoice.manifest', [
            'invoices'=>$invoice,
            'tryToUsd' => $tryToUsd,
            'last30days' => $data_users
        ]);
    }


    public function manifest2(){


        \Artisan::call('cache:clear');
        \Artisan::call('view:clear');
        $invoice = DB::table('invoices as i')
            ->select('i.width', 'i.height', 'i.length', 'i.weight' , 'i.shipping_price','i.purchase_no','i.waybill','i.order_tracking_number',
                'u.id','u.uniqid', 'u.name', 'u.surname', 'u.pin', 'u.address', 'u.serial_number','u.birthdate','u.id as user_id',
                'c.name as phone', 'p.price', 'p.quantity', 'p.shop_name', 'p.product_type_name','p.description'
            )
            ->leftJoin('users as u', 'i.user_id', '=', 'u.id')
            ->leftJoin('user_contacts as c', 'c.user_id', '=', 'u.id')
            ->leftJoin('products as p', 'i.product_id', '=', 'p.id')
            ->where('i.status_id', '=', 3)
            ->where('i.active', '=', 1)
            ->orderBy('user_id', 'DESC')
            ->get();

        $data_users=[];
        foreach ($invoice as $item){
            $data_users[] = $item->user_id;
        }


        $currency = Currency::find(1);

        $tryToUsd = 1/$currency->tl * $currency->usd;

        $minus30=date('Y-m-d', strtotime('-30 day', strtotime(date('Y-m-d'))));
        $last30days=DB::table('invoices as i')
            ->select(DB::raw('SUM(i.shipping_price) as shipping_price'), DB::raw('SUM(p.price) as price'), 'i.user_id')
            ->leftJoin('products as p', 'i.product_id', '=', 'p.id')
            ->where('i.order_date', '>', $minus30)
            ->whereIN('r.user_id', array_unique($data_users))
            ->groupBy('r.user_id')
            ->get();


        foreach ($last30days as $item){
            $data_users[$item->user_id] = number_format($item->price / $tryToUsd + $item->shipping_price, 2);
        }

//        dd($last30days);

        return view('admin.invoice.manifest2', [
            'invoices'=>$invoice,
            'tryToUsd' => $tryToUsd,
            'last30days' => $data_users
        ]);
    }
}
