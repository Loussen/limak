<?php

namespace App\Http\Controllers\Tr;

use App\Invoice;
use App\Invoiceless;
use App\Libraries\Upload\Uploader;
use App\ModelProduct\Product;
use App\ModelProduct\ProductType;
use App\ModelUser\User;
use App\Packages;
use App\RelUserProduct;
use App\Sack;
use App\SackInvoice;
use App\Status;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Validator;
use Illuminate\Support\Facades\DB;
use Artisan;
use Illuminate\Support\Facades\Session;


class SackController extends Controller
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

        $productTypes = ProductType::all();

        $sack = DB::table('sack')
            ->select('sack.*',DB::raw('COUNT(sack_invoices.sack_id) as count'))
            ->leftJoin('sack_invoices', 'sack.id', '=', 'sack_invoices.sack_id')
            ->groupBy('sack.id');
        $dispatch = null;
        if($request->has("dispatch_id")){
            $id = intval($request->dispatch_id);

            $dispatch = DB::table("dispatch")->where("id","=",$id)->first();
            if($dispatch!=null){
                $array = false;
                $dispatch_sacks = DB::table('dispatch_sacks')->where('dispatch_id','=',$id)->get();
                foreach ($dispatch_sacks as $ds){
                    $array[] = $ds->sack_id;
                }
                if($array){
                    $sack->whereIn("sack.id",$array);
                }
            }

        }else{
            $sack->where("status",1);
        }
        $sack  = $sack->get();

        $response = [
            'data' => $sack,
            'productTypes' => $productTypes,
            'dispatch' => $dispatch
         ];

        return view('tr.sack.index', $response);
    }


    public function invoices(Request $request,$id)
    {
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        $data = [];
        $data = DB::table('sack_invoices')
            ->select("sack_invoices.id","sack_invoices.invoice_id","sack_invoices.sack_id","invoices.file","invoices.created_at","invoices.product_id","rel_user_products.user_id","products.product_type_name","users.name","users.surname","users.uniqid","products.price","sack.invoice_no")
            ->where("sack_id","=",$id)
            ->leftJoin('sack', 'sack_invoices.sack_id', '=', 'sack.id')
            ->leftJoin('invoices', 'sack_invoices.invoice_id', '=', 'invoices.id')
            ->leftJoin('products', 'invoices.product_id', '=', 'products.id')
            ->leftJoin('rel_user_products', 'invoices.rel_user_product_id', '=', 'rel_user_products.id')
            ->leftJoin('users', 'rel_user_products.user_id', '=', 'users.id')
            ->get();

        $response = [
            'data' => $data,
        ];
        return view('tr.sack.invoices', $response);

    }

    public function removeInvoices(Request $request)
    {
        foreach ($request->ids_array as $id){
            $invoice = SackInvoice::find($id);
            $invoice->delete();
        }
        $data='ok';
        if($data) {
            return response()->json([
                'status' => 200,
                'data' => $data,
            ]);
        }
    }


    public function addInvoice(Request $request)
    {
        $sack_id=$request->post('sack_id',0);
        $invoice_id=$request->post('invoice_id',0);
        $count=$request->post('count',0);

        $invoice = Invoice::where("purchase_no","=",$invoice_id)->where("status_id",2)->where("active","=",1)->first();
        if($invoice!=null){
            $s_invoice = SackInvoice::where("invoice_id","=",$invoice->id)->first();
            if($s_invoice==null){
                $sackInvoice = new SackInvoice();
                $sackInvoice->sack_id = $sack_id;
                $sackInvoice->invoice_id = $invoice->id;
                $sackInvoice->save();
                $sack = DB::table('sack')
                    ->select(DB::raw('COUNT(sack_invoices.sack_id) as count'))
                    ->leftJoin('sack_invoices', 'sack.id', '=', 'sack_invoices.sack_id')
                    ->where('sack.id','=',$sack_id)
                    ->groupBy('sack.id')->first();

                return response()->json([
                    'status'=>200,
                    'data'=>$sack->count
                ]);

            }else{
//                Session::flash('message', 'İnvoice artıq istifadə olunub!');
//                Session::flash('alert-class', 'alert-danger');
            }

        }else{
//            Session::flash('message', 'İnvoice düzgün deyil!');
//            Session::flash('alert-class', 'alert-danger');
        }

        return response()->json([
            'status'=>404,
            'data'=>$count
        ]);
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
        if($request->code!=null){
            $sack = new Sack();
            $sack->invoice_no = $request->code;
            $sack->category = 1;
            $sack->save();
        }


        return redirect('/sack');
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
            ->leftJoin('products as p', 'i.product_id', '=', 'p.id')
            ->where('i.id', '=', $id)
            ->first();


        return view('admin.invoice.waybill', ['data'=>$invoice]);
    }
}
