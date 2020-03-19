<?php

namespace App\Http\Controllers\Us;

use App\Currencies;
use App\Currency;
use App\ModelCountry\Country;
use App\Invoice;
use App\InvoiceDates;
use App\Invoiceless;
use App\Libraries\Upload\Uploader;
use App\ModelProduct\Product;
use App\ModelProduct\ProductType;
use App\ModelUser\Client;
use App\ModelUser\User;
use App\ModelUser\UserContact;
use App\Packages;
use App\Persons;
use App\RelUserProduct;
use App\Status;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;
use \Milon\Barcode\DNS1D;

class InvoiceController extends BaseController
{
    public function __construct()
    {
        $data = Country::where('prefix','usa')->first();
        $this->country=$data->id;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function test(Request $request)
    {

        $fullname = Input::get('fullname', '');
        $uniqid = Input::get('user_code','');
        $status = Input::get('status', 1);
        $last30type = Input::get('type', '' );

        $fullname=explode(' ',$fullname);
        $client_id = 0;
        if(substr($uniqid,0,1)==1 and strlen(trim($uniqid))==7){
            $client_id = intval(substr($uniqid,1,6));
        }

        $person_id = 0;
        if(substr($uniqid,0,1)==1 and strlen(trim($uniqid))==6){
            $company_user_id = intval(trim($uniqid));
            $person = Persons::where("auto_id",$company_user_id)->first();
            if($person!=null){
                $person_id = $person->id;
            }
        }


        \Artisan::call('cache:clear');
        \Artisan::call('view:clear');
        $data = DB::table('invoices as i')
            ->select('i.width', 'i.height', 'i.length', 'i.weight' , 'i.shipping_price','i.purchase_no','i.waybill','i.file','i.id','i.status_id','i.by_bus','i.order_tracking_number',
                'u.uniqid', 'u.name', 'u.surname', 'u.address','u.email','u.pin',
                'c.name as phone', 'i.price', 'p.quantity', 'p.shop_name','p.id as product_id', 'p.comment as comment', 'product_type_name','s.name as invoice_status',
                'cl.name as client_name','cl.surname as client_surname','i.corporate','i.client_id',
                'pe.name as person_name','pe.surname as person_surname','pe.auto_id as person_id','pe.company as person_company'
//                DB::raw('SUM(i.weight) as i_weight')
            )
            ->leftJoin('users as u', 'i.user_id', '=', 'u.id')
            ->leftJoin('clients as cl', 'i.client_id', '=', 'cl.id')
            ->leftJoin('persons as pe', 'i.person_id', '=', 'pe.id')
            ->leftJoin('user_contacts as c', 'c.user_id', '=', 'u.id')
            ->leftJoin('products as p', 'i.product_id', '=', 'p.id')
            ->leftJoin('product_types as pt', 'p.product_type_id', '=', 'pt.id')
            ->leftJoin('statuses as s', 'i.status_id', '=', 's.id')
            ->where('i.status_id', '=', $status)
            ->where('i.country_id', '=', $this->country)
            ->where('i.active','=',1);
        if(!empty($uniqid) and $client_id==0 and $person_id==0) $data=$data->where(function($query) use($uniqid){
            $query->where('u.uniqid','=', $uniqid)->orWhere('u.id','=', $uniqid);
        });

        if($client_id>0){
            $data = $data->where("i.client_id",$client_id);
        }

        if($person_id>0){
            $data = $data->where("i.person_id",$person_id);
        }

        if(!empty($fullname[0])) $data = $data->where('u.name' ,'like','%' . $fullname[0] . '%')->orWhere('u.surname' ,'like','%' . $fullname[0] . '%');
        if(isset($fullname[1]) || !empty($fullname[1])) $data = $data->where('u.name' ,'like','%' . $fullname[1] . '%')->orWhere('u.surname' ,'like','%' . $fullname[1] . '%');
        $data=$data->orderBy('i.user_id','DESC')->get();

        $productTypes = ProductType::all();

        $currency = Currency::find(1);
        $tryToUsd = 1/$currency->tl * $currency->usd;

        if($request->ajax()) {
            return view('usa.invoice.ajax1',  compact('data' ,'status'));
        } else {
            return view('usa.invoice.test1',  compact('data','status','productTypes','tryToUsd','last30type'));
        }

    }
    public function changePurchase($purchase_no){
        $invoice=Invoice::select('*')->where('purchase_no','=', $purchase_no)->first();

        if($invoice){
            $invoice->added_by='foreign';
            $invoice->save();
            return response()->json([
                'status' => 200,
                'data' => 'ok',
            ]);
        }else{
            return response()->json([
                'status' => 404,
                'data' => 'error',
            ]);
        }
    }
    public function changeVehicle(Request $request)
    {
        $invoice=Invoice::find($request->id);
        if($request->type==0) $invoice->by_bus=1;
        else $invoice->by_bus=0;
        $invoice->save();
        if($invoice) {
            return response()->json([
                'status' => 200,
                'data' => 'ok',
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
            'product_type_name' => 'required',
            'price' => 'required|integer',
            'quantity' => 'required|integer',
            'shop_name' => 'required|string',
            //'invoice' => 'required',
            'order_number' => 'required',
            'order_date' => 'required',
        ];

        $messages = [
            'price.integer' => 'Price field is required',
            'quantity.integer' => 'Quantity is required',
            'product_type_id' => 'Select product type',
            'required' => 'Required',
        ];

        Validator::make($request->all(), $rules, $messages);
        $lastPurchase_no = Invoice::select('id')->orderBy('id', 'desc')->first();
        if($lastPurchase_no) {
            $lastPurchase_no =$lastPurchase_no->id;
        }else {
            $lastPurchase_no = 0;
        }

        $client_id = 0;
        if(substr($request->user_code,0,1)==1 and strlen(trim($request->user_code))==7){
            $client_id = intval(substr($request->user_code,1,6));
            $Client = Client::where('id', '=', $client_id)->first();
            if($Client!=null){
                $request->user_code = $Client->uniqid;
            }
        }


        $User = User::where('uniqid', '=', $request->user_code)->first();

        $newRelUserProduct = new RelUserProduct();
        $newRelUserProduct->status_id = 1;
        $newRelUserProduct->client_id = $client_id;
        $newRelUserProduct->user_id = $User->id;
        $newRelUserProduct->is_paid = 1;
        $newRelUserProduct->is_ordered = 1;
        $newRelUserProduct->save();
        //
        $newProduct = new Product();
        $newProduct->user_id = $User->id;
        $newProduct->product_type_id = 0;
        $newProduct->client_id = $client_id;
        $newProduct->region_id = $User->region_id;
        $newProduct->admin_id = 25; //Gunay Memmedova
        $newProduct->product_type_name = $request->product_type_name;
        $newProduct->rel_user_product_id = $newRelUserProduct->id;
        $newProduct->price = $request->price;
        $newProduct->quantity = $request->quantity;
        $newProduct->shop_name = $request->shop_name;
        $newProduct->description = '';
        $newProduct->status_id = 2;
        $newProduct->country_id = 2;
        $newProduct->save();
        $file = '';
        if($request['invoice']!=null and $request['invoice']!='undefined'){
            $file = Uploader::upload($request['invoice'], 'public/invoice/', 'invoice', false, true);
            $file = '/storage/invoice/'.$file;
        }


        $newPackage = new Packages();
        $newPackage->status=1;
        $newPackage->save();

        $newInvoice = new Invoice();
        $newInvoice->user_id = $User->id;
        $newInvoice->country_id = 2;
        $newInvoice->region_id = $User->region_id;
        $newInvoice->client_id = $client_id;
        $newInvoice->corporate = $User->corporate;
        $newInvoice->product_id = $newProduct->id;
        $newInvoice->price = $request->price;
        $newInvoice->rel_user_product_id = $newRelUserProduct->id;
        $newInvoice->status_id = 1;
        $newInvoice->package_id = $newPackage->id;
        $newInvoice->purchase_no = str_pad($lastPurchase_no + 1, 9, 0, STR_PAD_LEFT);
        $newInvoice->order_tracking_number = $request->order_number;
        $newInvoice->order_date = $request->order_date;
        $newInvoice->added_by = 'depo';
        $newInvoice->file = $file;


        if ($newInvoice->save()) {
            return response()->json(['status' => 200, 'message' => 'Invoice is uploaded']);
        }
    }


    public function store_old(Request $request)
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
            'price.integer' => 'Price field is required',
            'quantity.integer' => 'Quantity is required',
            'product_type_id' => 'Select product type',
            'required' => 'Required',
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
        $newInvoice->price = $request->price;
        $newInvoice->rel_user_product_id = $newRelUserProduct->id;
        $newInvoice->status_id = 1;
        $newInvoice->purchase_no = str_pad($lastPurchase_no + 1, 9, 0, STR_PAD_LEFT);
        $newInvoice->order_tracking_number = $request->order_number;
        $newInvoice->order_date = $request->order_date;
        $newInvoice->file = $file;


        if ($newInvoice->save()) {
            return response()->json(['status' => 200, 'message' => 'Invoice is uploaded']);
        }
    }

    public function stored(Request $request)
    {
        $fullname = Input::get('fullname', '');
        $uniqid = Input::get('user_code','');
        $last30type = Input::get('type', '' );
        $status_id = 12;
        $fullname=explode(' ',$fullname);
        \Artisan::call('cache:clear');
        \Artisan::call('view:clear');
        $data = DB::table('invoices as i')
            ->select('i.user_id','i.width', 'i.height','i.liquid_type', 'i.length', 'i.weight' , 'i.shipping_price','i.purchase_no','i.waybill','i.file','i.id','i.status_id','i.by_bus','i.order_tracking_number',
                'u.uniqid', 'u.name', 'u.surname', 'u.address','u.email','u.pin',
                'c.name as phone', 'i.price', 'p.quantity', 'p.shop_name','p.id as product_id', 'p.comment as comment', 'product_type_name','s.name as invoice_status',
                'cl.name as client_name','cl.surname as client_surname','i.corporate','i.client_id','i.person_id',
                'pe.name as person_name','pe.surname as person_surname','pe.auto_id'
            )
            ->leftJoin('users as u', 'i.user_id', '=', 'u.id')
            ->leftJoin('clients as cl', 'i.client_id', '=', 'cl.id')
            ->leftJoin('persons as pe', 'i.person_id', '=', 'pe.id')
            ->leftJoin('user_contacts as c', 'c.user_id', '=', 'u.id')
            ->leftJoin('products as p', 'i.product_id', '=', 'p.id')
            ->leftJoin('product_types as pt', 'p.product_type_id', '=', 'pt.id')
            ->leftJoin('statuses as s', 'i.status_id', '=', 's.id')

            ->whereIn('i.status_id',[12,13])
            ->where('i.country_id', '=', 2)
            ->where('i.active','=',1);
        if(!empty($uniqid)) $data=$data->where(function($query) use($uniqid){
            $query->where('u.uniqid','=', $uniqid)->orWhere('u.id','=', $uniqid);
        });
        if(!empty($fullname[0])) $data = $data->where('u.name' ,'like','%' . $fullname[0] . '%')->orWhere('u.surname' ,'like','%' . $fullname[0] . '%');
        if(isset($fullname[1]) || !empty($fullname[1])) $data = $data->where('u.name' ,'like','%' . $fullname[1] . '%')->orWhere('u.surname' ,'like','%' . $fullname[1] . '%');
        $data=$data->orderBy('i.user_id','DESC')->get();

        $productTypes = ProductType::all();

        $currency = Currencies::where("name","try-usd")->first();
        $tryToUsd = $currency->val;

        $user_balances_ids = [];
        foreach ($data as $k=>$inv){
            $status = Status::where('label', '=', 'stored_auto')->where('type', '=', 'invoice')->first();
            if(1){
                if(!isset($user_balances_ids[$inv->user_id][intval($inv->client_id)][intval($inv->person_id)])){

                    $invoices = Invoice::where("user_id",$inv->user_id)->where("client_id",$inv->client_id)->where("person_id",$inv->person_id)->where("status_id",2)->where("id","!=",$inv->id)->get();

                    $depo_price = 0;
                    foreach ($invoices as $item){
                        $depo_price += $item->shipping_price;
                        if($item->country_id==2){
                            $depo_price += $item->price;
                        }elseif($item->country_id==1){
                            if($currency!=null){
                                $depo_price += $item->price*$tryToUsd;
                            }
                        }
                    }

                    $userLast30 = userLast30($inv->user_id,intval($inv->client_id),intval($inv->person_id));


                    $user_last30_balance = $depo_price + $userLast30;
                    $inv->user_last30 = $user_last30_balance;
                    $data[$k] = $inv;

                    $user_balances_ids[$inv->user_id][$inv->client_id][$inv->person_id] = $user_last30_balance;
                }else{
                    $inv->user_last30 = $user_balances_ids[$inv->user_id][intval($inv->client_id)][intval($inv->person_id)];
                    $data[$k] = $inv;
                }
            }
        }
        $status = $status_id;
        if($request->ajax()) {
            return view('usa.invoice.ajax1',  compact('data' ,'status'));
        } else {
            return view('usa.invoice.stored',  compact('data','status','productTypes','tryToUsd','last30type'));
        }

    }

    public function changeStatus(Request $request)
    {
        $admin_id = Auth::guard('usa')->user()->id;

        $invoice= Invoice::find($request->id);
        $invoice->status_id = 2;
        $invoice->save();

        $invoice_date=new InvoiceDates();
        $invoice_date->status_id=2;
        $invoice_date->invoice_id=$request->id;
        $invoice_date->action_date=date('Y-m-d H:i:s');
        $invoice_date->note = "Admin id ".$admin_id;
        $invoice_date->save();

        $currency = Currencies::where("name","=","try-usd")->first();

        $invoices = Invoice::where("user_id",$invoice->user_id)->where("status_id",2)->where("id","!=",$request->id)->get();

        $depo_price = 0;
        foreach ($invoices as $item){
            $depo_price += $item->shipping_price;
            if($item->country_id==2){
                $depo_price += $item->price;
            }elseif($item->country_id==1){
                if($currency!=null){
                    $depo_price += $item->price*$currency->val;
                }
            }
        }

        if($invoice->status_id == 2){
            $contact = UserContact::where('user_id', '=', $invoice->user_id)->first();
            $data = (object) ['text' => 'Baglamaniz Amerika anbarimiza daxil olub. Baglamanizi https://limak.az vasitesi ile izleye bilersiniz.', "user_id" =>$invoice->user_id];
            smsSend($data, $contact->name);
        }

        if($invoice->shipping_price>0){

            $user_id = $invoice->user_id;
            $client_id = intval($invoice->client_id);
            $person_id = intval($invoice->person_id);
            $user_price = userLast30($user_id,$client_id,$person_id);

            $all_price = $invoice->price + $invoice->shipping_price + $user_price;

            $all_price += $depo_price;

            if($all_price>1000){
                $status = Status::where('label', '=', 'stored_auto')->where('type', '=', 'invoice')->first();
                $invoice->status_id = $status->sid;
                $invoice->save();

                $invoice_date=new InvoiceDates();
                $invoice_date->status_id=$status->sid;
                $invoice_date->invoice_id=$request->id;
                $invoice_date->action_date=date('Y-m-d H:i:s');
                $invoice_date->note = 'Admin '.$admin_id;
                $invoice_date->save();

                return response()->json([
                    'status' => 201,
                    'data' => 'ok'
                ]);
            }
        }

        if($invoice) {
            return response()->json([
                'status' => 200,
                'data' => 'ok'
            ]);
        }
    }

    public function changeStatusBack(Request $request)
    {
        $admin_id = Auth::guard('usa')->user()->id;


        $invoice= Invoice::find($request->id);
        $status = Status::where('label', '=', 'stored_auto')->where('type', '=', 'invoice')->first();

        if($invoice->shipping_price>0 and $invoice->status_id==$status->sid){

            $user_id = $invoice->user_id;
            $client_id = intval($invoice->client_id);
            $person_id = intval($invoice->person_id);
            $user_price = userLast30($user_id,$client_id,$person_id);
            $currency = Currencies::where("name","=","try-usd")->first();
            $all_price = $user_price;
            if($currency!=null){
                $all_price = $invoice->price + $invoice->shipping_price + $user_price;
            }
            if($all_price<=1000){
                $status = Status::where('label', '=', 'foreign')->where('type', '=', 'invoice')->first();
                $invoice->status_id = $status->sid;
                $invoice->save();

                $invoice_date=new InvoiceDates();
                $invoice_date->status_id=$status->sid;
                $invoice_date->invoice_id=$request->id;
                $invoice_date->action_date=date('Y-m-d H:i:s');
                $invoice_date->note = "Admin ".$admin_id;
                $invoice_date->save();

                return response()->json([
                    'status' => 201,
                    'data' => 'ok'
                ]);
            }
        }

        if($invoice) {
            return response()->json([
                'status' => 200,
                'data' => 'ok'
            ]);
        }
    }

    public function changeStatus_old(Request $request)
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

    public function addCustomStatus(Request $request)
    {
        foreach ($request->ids_array as $id){
            $invoice = Invoice::find($id);
            $invoice->status_id=11;
            $invoice->save();

            $invoice_date=new InvoiceDates();
            $invoice_date->status_id=11;
            $invoice_date->invoice_id=$id;
            $invoice_date->action_date=date('Y-m-d H:i:s');
            $invoice_date->save();
        }
        $data='ok';
        if($data) {
            return response()->json([
                'status' => 200,
                'data' => $data,
            ]);
        }
    }

    public function changeAllStatuses(Request $request)
    {
        foreach ($request->ids_array as $id){
            $invoice = Invoice::find($id);
            $invoice->status_id=$invoice->status_id+1;
            $invoice->save();

            $invoice_date=new InvoiceDates();
            $invoice_date->status_id=3;
            $invoice_date->invoice_id=$id;
            $invoice_date->action_date=date('Y-m-d H:i:s');
            $invoice_date->save();
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

//    private function checkRole ($label) {
//        $status = false;
//        foreach (Auth::guard('admin')->user()->relAdminRoles as $value) {
//            if($value->relRole->label === $label) {
//                $status = true;
//            }
//        }
//        return $status;
//    }
//
//    private function inStorage($pin, $tel, $label, $user_code) {
//
//        if($tel || $pin || $user_code) {
//            $data = Invoice::with('invoiceStatus')->whereHas('invoiceStatus', function ($query) use ($label) {
//                $query->where('label', '=', $label);
//            })->with('products.productTypes')->with('relUserProducts.users')->whereHas('relUserProducts.users', function($query) use($user_code) {
//                if($user_code) {
//                    $query->where('uniqid', 'like','%' . $user_code .'%');
//                }
//            })->where('active', '=', '1')->with('relUserProducts.users.userContacts')->whereHas('relUserProducts.users.userContacts', function ($query) use ($tel, $pin) {
//                if ($pin) {
//                    $query->where('pin', '=', $pin)->where('name', 'like', '%' . $tel .'%');
//                } else {
//                    $query->where('name', 'like', '%' . $tel .'%');
//                }
//            })->orderBy('created_at', 'DESC')->paginate(50);
//        } else{
//            $data = Invoice::with('invoiceStatus')->whereHas('invoiceStatus', function ($query) use ($label) {
//                $query->where('label', '=', $label);
//            })->where('active', '=', '1')->with('products.productTypes')->with('relUserProducts.users.userContacts')->orderBy('created_at', 'DESC')->paginate(50);
//        }
//        return $data;
//    }
//
//    private function waiting($pin, $tel, $user_code, $invoice) {
//        $label = 'waiting';
//        if($tel || $pin || $user_code) {
//            $data = Invoice::with('invoiceStatus')->whereHas('invoiceStatus', function ($query) use ($label) {
//                $query->where('label', '=', $label);
//            })->where('active', '=', '1')->with('products.productTypes')->with('relUserProducts.users')->whereHas('relUserProducts.users', function($query) use($user_code) {
//                if($user_code) {
//                    $query->where('uniqid', 'like','%' . $user_code .'%');
//                }
//            })->with('relUserProducts.users.userContacts')->whereHas('relUserProducts.users.userContacts', function ($query) use ($tel, $pin) {
//                if ($pin) {
//                    $query->where('pin', '=', $pin)->where('name', 'like', '%' . $tel .'%');
//                } else {
//                    $query->where('name', 'like', '%' . $tel .'%');
//                }
//            })->where('file', $invoice, NULL)->orderBy('created_at', 'DESC')->paginate(50);
//        } else{
//            $data = Invoice::with('invoiceStatus')->whereHas('invoiceStatus', function ($query) use ($label) {
//                $query->where('label', '=', $label);
//            })->where('active', '=', '1')->where('file', $invoice, NULL)->with('products.productTypes')->with('relUserProducts.users.userContacts')->orderBy('created_at', 'DESC')->paginate(50);
//        }
//        return $data;
//    }
//
//    private function inRoad($pin, $tel, $user_code) {
//        $label = 'on_the_way';
//        if($tel || $pin || $user_code) {
//            $data = Invoice::with('invoiceStatus')->whereHas('invoiceStatus', function ($query) use ($label) {
//                $query->where('label', '=', $label);
//            })->where('active', '=', '1')->with('products.productTypes')->with('relUserProducts.users')->whereHas('relUserProducts.users', function($query) use($user_code) {
//                if($user_code) {
//                    $query->where('uniqid', 'like','%' . $user_code .'%');
//                }
//            })
//                ->with('relUserProducts.users.userContacts')->whereHas('relUserProducts.users.userContacts', function ($query) use ($tel, $pin) {
//                    if ($pin) {
//                        $query->where('pin', '=', $pin)->where('name', 'like', '%' . $tel .'%');
//                    } else {
//                        $query->where('name', 'like', '%' . $tel .'%');
//                    }
//                })->orderBy('created_at', 'DESC')->paginate(50);
//        } else{
//            $data = Invoice::with('invoiceStatus')->whereHas('invoiceStatus', function ($query) use ($label) {
//                $query->where('label', '=', $label);
//            })->where('active', '=', '1')->with('products.productTypes')->with('relUserProducts.users.userContacts')->orderBy('created_at', 'DESC')->paginate(50);
//        }
//        return $data;
//    }

    public function setInvoiceDataByBuyer (Request $request) {
        $productIds = $request['productIds'];
        $package= new Packages();
        $package->status = 1;
        $package->save();
        $i = 0;

        foreach($productIds as $productId){
            $productModel = Product::find($productId);

            $lastPurchase_no = Invoice::select('id')->orderBy('id', 'desc')->first();
            if($lastPurchase_no) {
                $lastPurchase_no =$lastPurchase_no->id;
            }else {
                $lastPurchase_no = 0;
            }

            $productModel->shop_name          = $request['shop'];
            $productModel->quantity           = $request['packageCount'];
            $productModel->product_type_name  = $request['productType'];
            $productModel->price              = $request['productPrice'];
            $productModel->status_id          = 2;
            $productModel->is_ordered         = 1;
            $productModel->save();


            $invoiceModel = new Invoice();
            $invoiceModel->user_id               = $productModel->user_id;
            $invoiceModel->product_id            = $productModel->id;
            $invoiceModel->rel_user_product_id   = $productModel->rel_user_product_id;
            $invoiceModel->status_id             = 1;
            $invoiceModel->purchase_no           = str_pad($lastPurchase_no + 1, 9, 0, STR_PAD_LEFT);
            $invoiceModel->order_tracking_number = $request['orderTrackingNumber'];
            $invoiceModel->order_date            = $request['orderDate'];
            $invoiceModel->package_id            = $package->id;
            if($i != 0) $invoiceModel->active = 0;
            $invoiceModel->save();

            $i++;
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

        $productModel = Product::find($productId);

        $productModel->shop_name          = $request['shop'];
        $productModel->quantity           = $request['packageCount'];
        $productModel->product_type_name  = $request['productType'];
        $productModel->price              = $request['productPrice'];
        $productModel->save();

        $invoiceModel = $productModel->invoices[0];

        $invoiceModel->order_tracking_number = $request['orderTrackingNumber'];
        $invoiceModel->order_date            = $request['orderDate'];

        if ($invoiceModel->save()) {
            return response()->json([
                'status'  => 200,
                'message' => 'Invoice has been updated!'
            ]);
        } else {
            return response()->json([
                'status'  => 500,
                'message' => 'Server error!'
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
        if($user)
            notify((object)['template' => 'invoice-id-absent-us','user_id' => $user->id], (object)['phone' => $user->userContacts[0]->name, 'email' => $user->email]);
        else
            return response()->json(['data'=>'error', 'code'=>404]);

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

        return view('usa.invoice.waybill', ['data'=>$invoice, 'tryToUsd' => $tryToUsd]);
    }

    public function waybillNew($id){

        $invoice = DB::table('invoices as i')
            ->select('i.width', 'i.height', 'i.length', 'i.weight' , 'i.shipping_price','i.purchase_no',
                'u.uniqid', 'u.name', 'u.surname', 'u.address',
                'c.name as phone', 'p.price', 'p.quantity', 'p.shop_name', 'product_type_name',
                'cl.name as client_name','cl.surname as client_surname','cl.id as client_id','cl.phone as client_phone','cl.address as client_address',
                'pe.name as person_name','pe.surname as person_surname','pe.auto_id as person_id','pe.phone as person_phone','pe.address as person_address'
            )
            ->leftJoin('rel_user_products as r', 'r.id', '=', 'i.rel_user_product_id')
            ->leftJoin('users as u', 'r.user_id', '=', 'u.id')
            ->leftJoin('clients as cl', 'i.client_id', '=', 'cl.id')
            ->leftJoin('persons as pe', 'i.person_id', '=', 'pe.id')
            ->leftJoin('user_contacts as c', 'c.user_id', '=', 'u.id')
            ->leftJoin('products as p', 'i.product_id', '=', 'p.id')
            ->where('i.id', '=', $id)
            ->first();

        $currency = Currency::find(1);

        $tryToUsd = 1/$currency->tl * $currency->usd;
        $barcode_code = "uslmk".$id;
        $barcode = DNS1D::getBarcodeSVG($barcode_code, "C128",1.5,64);
        return view('usa.invoice.waybillNew', ['data'=>$invoice, 'tryToUsd' => $tryToUsd,'barcode' => $barcode, 'barcode_code' => $barcode_code,]);
    }

    public function waybillAll(){
        $invoices = DB::table('invoices as i')
            ->select('i.id','i.width', 'i.height', 'i.length', 'i.weight' , 'i.shipping_price','i.purchase_no',
                'u.uniqid', 'u.name', 'u.surname', 'u.address',
                'c.name as phone', 'i.price', 'p.quantity', 'p.shop_name', 'product_type_name'
            )
            ->leftJoin('rel_user_products as r', 'r.id', '=', 'i.rel_user_product_id')
            ->leftJoin('users as u', 'r.user_id', '=', 'u.id')
            ->leftJoin('user_contacts as c', 'c.user_id', '=', 'u.id')
            ->leftJoin('products as p', 'i.product_id', '=', 'p.id')
            ->where('i.country_id', '=', 2)
            ->where('i.status_id', '=', 2)
            ->get();

        $currency = Currency::find(1);

        $tryToUsd = 1/$currency->tl * $currency->usd;

        return view('usa.invoice.waybillAll', ['invoices'=>$invoices, 'tryToUsd' => $tryToUsd]);
    }

    public function xml(){

        \Artisan::call('cache:clear');
        \Artisan::call('view:clear');
        $invoice = DB::table('invoices as i')
            ->select('i.width', 'i.height', 'i.length', 'i.weight' , 'i.shipping_price','i.purchase_no','i.waybill',
                'u.uniqid', 'u.name', 'u.surname', 'u.address',
                'c.name as phone', 'p.price', 'p.quantity', 'p.shop_name', 'product_type_name'
            )
            ->leftJoin('rel_user_products as r', 'r.id', '=', 'i.rel_user_product_id')
            ->leftJoin('users as u', 'r.user_id', '=', 'u.id')
            ->leftJoin('user_contacts as c', 'c.user_id', '=', 'u.id')
            ->leftJoin('products as p', 'i.product_id', '=', 'p.id')
            ->where('i.status_id', '=', 3)
            ->where('i.by_bus', '=', 0)
            ->where('i.country_id', '=', $this->country)
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
            $Goods->addChild('INVOYS_PRICE', round($item->price / $tryToUsd, 2));
            $Goods->addChild('CURRENCY_TYPE', 840);
            $Goods->addChild('NAME_OF_GOODS', htmlspecialchars($item->product_type_name));
            $Goods->addChild('IDXAL_NAME', $item->name." ".$item->surname);
            $Goods->addChild('IDXAL_ADRESS', $item->address);
            $Goods->addChild('IXRAC_NAME', htmlspecialchars($item->shop_name));
            $Goods->addChild('IXRAC_ADRESS', 'Istanbul');
            $Goods->addChild('GOODS_TRAFFIC_FR', 792);
            $Goods->addChild('GOODS_TRAFFIC_TO', '031');
            $Goods->addChild('QAIME', $item->waybill);
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
                'u.uniqid', 'u.name', 'u.pin','u.surname', 'u.address', 'u.serial_number','u.birthdate','u.id as user_id',
                'c.name as phone', 'p.price', 'p.quantity', 'p.shop_name', 'product_type_name','p.description'
            )
            ->leftJoin('rel_user_products as r', 'r.id', '=', 'i.rel_user_product_id')
            ->leftJoin('users as u', 'r.user_id', '=', 'u.id')
            ->leftJoin('user_contacts as c', 'c.user_id', '=', 'u.id')
            ->leftJoin('products as p', 'i.product_id', '=', 'p.id')
            ->leftJoin('product_types as pt', 'p.product_type_id', '=', 'pt.id')
            ->where('i.status_id', '=', 3)
            ->where('i.country_id', '=', $this->country)
            ->get();

        $data_users=[];
        foreach ($invoice as $item){
            $data_users[] = $item->user_id;
        }


        $currency = Currency::find(1);

        $tryToUsd = 1/$currency->tl * $currency->usd;

        $minus30=date('Y-m-d', strtotime('-30 day', strtotime(date('Y-m-d'))));
        $last30days=DB::table('invoices as i')
            ->select(DB::raw('SUM(i.shipping_price) as shipping_price'), DB::raw('SUM(p.price) as price'), 'r.user_id')
            ->leftJoin('rel_user_products as r', 'r.id', '=', 'i.rel_user_product_id')
            ->leftJoin('products as p', 'i.product_id', '=', 'p.id')
            ->where('i.order_date', '>', $minus30)
            ->where('i.country_id', '=', $this->country)
            ->whereIN('r.user_id', array_unique($data_users))
            ->groupBy('r.user_id')
            ->get();


        foreach ($last30days as $item){
            $data_users[$item->user_id] = number_format($item->price / $tryToUsd + $item->shipping_price, 2);
        }

//        dd($last30days);

        return view('usa.manifest', [
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
                'u.uniqid', 'u.name', 'u.surname', 'u.pin', 'u.address', 'u.serial_number','u.birthdate','u.id as user_id',
                'c.name as phone', 'p.price', 'p.quantity', 'p.shop_name', 'p.product_type_name','p.description',
                'pt.name as pt_name'
            )
            ->leftJoin('rel_user_products as r', 'r.id', '=', 'i.rel_user_product_id')
            ->leftJoin('users as u', 'r.user_id', '=', 'u.id')
            ->leftJoin('user_contacts as c', 'c.user_id', '=', 'u.id')
            ->leftJoin('products as p', 'i.product_id', '=', 'p.id')
            ->leftJoin('product_types as pt', 'p.product_type_id', '=', 'pt.id')
            ->where('i.status_id', '=', 3)
            ->where('i.country_id', '=', $this->country)
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
            ->select(DB::raw('SUM(i.shipping_price) as shipping_price'), DB::raw('SUM(p.price) as price'), 'r.user_id')
            ->leftJoin('rel_user_products as r', 'r.id', '=', 'i.rel_user_product_id')
            ->leftJoin('products as p', 'i.product_id', '=', 'p.id')
            ->where('i.order_date', '>', $minus30)
            ->where('i.country_id', '=', $this->country)
            ->whereIN('r.user_id', array_unique($data_users))
            ->groupBy('r.user_id')
            ->get();


        foreach ($last30days as $item){
            $data_users[$item->user_id] = number_format($item->price / $tryToUsd + $item->shipping_price, 2);
        }

        return view('usa.manifest', [
            'invoices'=>$invoice,
            'tryToUsd' => $tryToUsd,
            'last30days' => $data_users
        ]);
    }

    public function logout(){
        if(isset($_SERVER['PHP_AUTH_USER']))
            unset($_SERVER['PHP_AUTH_USER']);

        if (isset($_SERVER['PHP_AUTH_PW']))
            unset($_SERVER['PHP_AUTH_PW']);
        return redirect()->to('https://www.limak.az/');
    }
}
