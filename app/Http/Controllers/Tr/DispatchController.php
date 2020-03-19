<?php

namespace App\Http\Controllers\Tr;

use App\Dispatch;
use App\DispatchSacks;
use App\Invoice;
use App\Invoiceless;
use App\Libraries\Upload\Uploader;
use App\ModelProduct\Product;
use App\ModelProduct\ProductType;
use App\ModelUser\User;
use App\Packages;
use App\RelUserProduct;
use App\Sack;
use App\Status;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Validator;
use Illuminate\Support\Facades\DB;
use Artisan;
use Illuminate\Support\Facades\Session;

class DispatchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( Request $request)
    {
        Artisan::call('cache:clear');
        Artisan::call('view:clear');

        $data = Dispatch::where('status', '=', 1)->get();


        $sacks = DB::table("sack")->where("status",1)->get();

        $data = DB::table('dispatch')
            ->select('dispatch.*',DB::raw('COUNT(dispatch_sacks.sack_id) as count'))
            ->where("dispatch.status",1)
            ->leftJoin('dispatch_sacks', 'dispatch.id', '=', 'dispatch_sacks.dispatch_id')
            ->groupBy('dispatch.id')
            ->get();


        return view('tr.dispatch.index', ['data' => $data,"sacks" => $sacks]);
    }

    public function addFile(Request $request)
    {
        $request->validate([
            "dispatch_id" => 'required',
            'file' => 'mimes:jpg,png,jpeg,bmp,pdf,docx,doc,html'
        ]);
        $dispatch_id = intval($request->get("dispatch_id",0));
        if($dispatch_id>0){
            $dispatch = Dispatch::where("id",$dispatch_id)->where("status",1)->first();
            if($dispatch!=null){

                $file = Uploader::upload($request['file'], 'public/dispatch/', 'dispatch', false, true);
                $file = '/storage/dispatch/'.$file;


                $dispatch->file = $file;
                $dispatch->save();

                Session::flash('message', 'Əlavə edildi!');
                Session::flash('alert-class', 'alert-success');
            }else{
                Session::flash('message', 'Sevkiyyat düzgün deyil!');
                Session::flash('alert-class', 'alert-danger');
            }
        }else{
            Session::flash('message', 'Sevkiyyat düzgün deyil!');
            Session::flash('alert-class', 'alert-danger');
        }

        return redirect('/dispatch');

    }

    public function addSack(Request $request)
    {
        $sack = Sack::where("invoice_no","=",$request->invoice_no)->where("status",1)->first();
        if($sack!=null){
            $s_dispatch = DispatchSacks::where("sack_id","=",$sack->id)->first();

            if($s_dispatch==null){
                $dispatchSack = new DispatchSacks();
                $dispatchSack->dispatch_id = $request->dispatch_id;
                $dispatchSack->sack_id = $sack->id;
                $dispatchSack->save();

                $sack->status = 0;
                $sack->save();


                Session::flash('message', 'Əlavə edildi!');
                Session::flash('alert-class', 'alert-success');
            }else{
                Session::flash('message', 'Çuval artıq istifadə olunub!');
                Session::flash('alert-class', 'alert-danger');
            }

        }else{
            Session::flash('message', 'Çuval düzgün deyil!');
            Session::flash('alert-class', 'alert-danger');
        }


        return redirect('/dispatch');
    }

    public function exportFile(Request $request)
    {
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        $d_id = $request->get("id",0);
        $array = [];
        $heading = ["h" => ["Baglama nomresi","Istifadeci",'Ad','Soyad',"Icerik",'Cuval']];
        if($d_id>0){
            //SELECT si.* FROM `dispatch_sacks` as ds LEFT JOIN `sack_invoices` as si on ds.sack_id=si.sack_id WHERE ds.`dispatch_id`=2
            $array = DB::table('dispatch_sacks as ds')
                ->select('i.purchase_no','i.user_id','p.product_type_name','u.name','u.surname','s.invoice_no')
                ->leftJoin("sack_invoices as si",'ds.sack_id','=','si.sack_id')
                ->leftJoin("sack as s",'ds.sack_id','=','s.id')
                ->leftJoin("invoices as i",'si.invoice_id','=','i.id')
                ->leftJoin("products as p",'i.product_id','=','p.id')
                ->leftJoin("users as u",'i.user_id','=','u.id')
                ->where('ds.dispatch_id',$d_id)
                ->get()
                ->toArray();
        }

        $allData = $heading;
        foreach ($array as $key=>$value){
            $allData[$key] = (array) $value;
        }


        header("Content-Disposition: attachment; filename=sevkiyyat_".$d_id.".xls");
        header("Content-Type: application/vnd.ms-excel; charset=utf-8");
        header("Pragma: no-cache");
        header("Expires: 0");
        $out = fopen("php://output", 'w');
        foreach ($allData as $data)
        {
            fputcsv($out, $data,"\t");
        }
        fclose($out);
    }


    public function sendDispatch(Request $request)
    {
        $dispatch_id = $request->dispatch_id;
        $dispatch = Dispatch::where("id",$dispatch_id)->where("status",1)->first();
        if($dispatch!=null){
            $error_message = '';
            if($dispatch->consignment==""){
                $error_message .= 'Konşimento numarası ekle, ';
            }

            if($dispatch->total_price==""){
                $error_message .= 'Toplam fiyat ekle, ';
            }

            if($dispatch->dispatch_date==""){
                $error_message .= 'Gönderim tarihi ekle, ';
            }

            if($dispatch->total_weight==""){
                $error_message .= 'Toplam ağırlık ekle, ';
            }

            if($dispatch->total_volume==""){
                $error_message .= 'Toplam hacim ekle, ';
            }

            $dispatch_sack = DispatchSacks::where("dispatch_id",$dispatch_id)->count();
            if($dispatch_sack==0){
                $error_message .= 'Çuval ekle, ';
            }

            if($error_message!=""){
                Session::flash('message', $error_message);
                Session::flash('alert-class', 'alert-danger');
            }else{
                $dispatch->status = 0;
                $dispatch->save();

                Session::flash('message', "Sevkiyyat gönderildi");
                Session::flash('alert-class', 'alert-success');
            }





        }else{
            Session::flash('message', 'Sevkiyyat numarasi dogru deyil!');
            Session::flash('alert-class', 'alert-danger');
        }


        return redirect('/dispatch');

    }

    public function sacks(Request $request,$id){
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        $data = [];
        $data = DB::table('dispatch_sacks')
            ->select('dispatch_sacks.dispatch_id','dispatch_sacks.sack_id')
            ->where("dispatch_id","=",$id)
            ->leftJoin('sack', 'dispatch_sacks.sack_id', '=', 'sack.id')
            ->get();

        $response = [
            'data' => $data,
        ];
        return view('tr.dispatch.sacks', $response);
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
        $sack = new Dispatch();
        $sack->consignment = $request->consignment;
        $sack->total_price = $request->total_price;
        $sack->total_volume = $request->total_volume;
        $sack->dispatch_date = $request->dispatch_date;
        $sack->total_weight = $request->total_weight;
        $sack->save();

        return redirect('/dispatch');
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

    private function waiting($pin, $tel, $user_code) {
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
            })->orderBy('created_at', 'DESC')->paginate(50);
        } else{
            $data = Invoice::with('invoiceStatus')->whereHas('invoiceStatus', function ($query) use ($label) {
                $query->where('label', '=', $label);
            })->where('active', '=', '1')->with('products.productTypes')->with('relUserProducts.users.userContacts')->orderBy('created_at', 'DESC')->paginate(50);
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
            $productModel->save();


            $invoiceModel = new Invoice();

            $invoiceModel->product_id            = $productModel->id;
            $invoiceModel->rel_user_product_id   = $productModel->rel_user_product_id;
            $invoiceModel->status_id             = getStatusByLabel('waiting', 'invoice');
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
        $user_uid = $request->user_uid;
        $note = $request->note;

        $data = new Invoiceless();
        $data->note = $note;
        $data->user_uid = $user_uid;
        $data->save();
        return response()->json(["data" => "ok", "code" => 200]);
    }

    public function waybill($id){

        $invoice = DB::table('invoices as i')
            ->select('i.width', 'i.height', 'i.length', 'i.weight' , 'i.shipping_price',
                'u.uniqid', 'u.name', 'u.surname', 'u.address',
                'c.name as phone', 'p.price', 'p.quantity', 'p.shop_name', 'product_type_name'
            )
            ->leftJoin('rel_user_products as r', 'r.id', '=', 'i.rel_user_product_id')
            ->leftJoin('users as u', 'r.user_id', '=', 'u.id')
            ->leftJoin('user_contacts as c', 'c.user_id', '=', 'u.id')
            ->leftJoin('produc ts as p', 'i.product_id', '=', 'p.id')
            ->where('i.id', '=', $id)
            ->first();


        return view('admin.invoice.waybill', ['data'=>$invoice]);
    }
}
