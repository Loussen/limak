<?php

namespace App\Http\Controllers\Site;

use App\Invoice;
use App\Libraries\Upload\Uploader;
use App\ModelProduct\Product;
use App\ModelProduct\ProductType;
use App\ModelUser\User;
use App\RelUserProduct;
use App\Status;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Packages;

use Validator;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $productTypes = ProductType::all();
        return response()->json([
            "status" => 200,
            "data" => $productTypes
        ]);
    }

    public function productTypes()
    {
        //$productTypes = ProductType::select("id as name","name as slug")->get();
        $productTypes  = ProductType::all()->pluck('name', 'id');
        return response()->json([
            "data" => $productTypes
        ]);
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//        $rules = [
//            'product_type' => 'required',
//            'price' => 'required|integer',
//            'quantity' => 'required|integer',
//            'shop_name' => 'required|string',
//            'order_number' => 'required',
//            'order_date' => 'required',
//        ];
//
//        $messages = [
//            'price.integer' => 'Məhsulun qiyməti xanasına rəqəm daxil edin',
//            'quantity.integer' => 'Məhsulun qiyməti xanasına rəqəm daxil edin',
//            'product_type_id' => 'Məhsulun qiyməti xanasını düzgün seçin',
//            'required' => 'Bütün xanaları doldurun',
//        ];
//
//        Validator::make($request->all(), $rules, $messages);
        $request->validate([
           "shop_name" => 'required',
            "price" => 'required',
            "product_type" => 'required',
            "quantity" => 'required',
            "order_number" => 'required',
            "order_date" => 'required',
            //'file' => 'mimes:jpg,png,jpeg,bmp,pdf,docx,doc,html'
        ]);
        $lastPurchase_no = Invoice::select('id')->orderBy('id', 'desc')->first();
        if($lastPurchase_no) {
            $lastPurchase_no =$lastPurchase_no->id;
        }else {
            $lastPurchase_no = 0;
        }

        $package= new Packages();
        $package->status = 1;
        $package->save();

        $invoiceAddStatus = getStatusByLabel('invoice_added', 'transaction');

        $status = getStatusByLabel('with_invoice', 'product');
        $statusInvoice = getStatusByLabel('waiting', 'invoice');

        $newRelUserProduct = new RelUserProduct();
        $newRelUserProduct->status_id = $invoiceAddStatus;
        $newRelUserProduct->user_id = Auth()->guard('web')->user()->id;
        $newRelUserProduct->is_paid = 1;
        $newRelUserProduct->is_ordered = 1;
        $newRelUserProduct->save();
        //
        $newProduct = new Product();
        $newProduct->product_type_id = $request->product_type_id;
        $newProduct->product_type_name = $request->product_type;
        $newProduct->rel_user_product_id = $newRelUserProduct->id;
        $newProduct->price = $request->price;
        $newProduct->region_id = $request->region;
        $newProduct->country_id = $request->country_id;
        $newProduct->user_id = Auth()->guard('web')->user()->id;
        $newProduct->is_premium = Auth()->guard('web')->user()->is_premium;
        $newProduct->quantity = $request->quantity;
        $newProduct->shop_name = $request->shop_name;
        $newProduct->description = $request->description;
        $newProduct->is_ordered = 1;
        $newProduct->is_paid = 1;
        $newProduct->status_id = $status;
        $newProduct->save();

        if($request->file){
            $file = Uploader::upload($request['file'], 'public/invoice/', 'invoice', false, true);
            $file = '/storage/invoice/'.$file;
        }

        $newInvoice = new Invoice();
        $newInvoice->user_id = Auth()->guard('web')->user()->id;
        $newInvoice->price = $request->price;
        $newInvoice->product_id = $newProduct->id;
        $newInvoice->region_id = $request->region;
        $newInvoice->client_id = $request->get("client_id",0);
        if(Auth()->guard('web')->user()->corporate==1){
            $newInvoice->corporate = 1;
        }
        $newInvoice->is_premium = Auth()->guard('web')->user()->is_premium;

        $newInvoice->country_id = $request->country_id;
        $newInvoice->rel_user_product_id = $newRelUserProduct->id;
        $newInvoice->status_id = $statusInvoice;
        $newInvoice->purchase_no = str_pad($lastPurchase_no + 1, 9, 0, STR_PAD_LEFT);
        $newInvoice->order_tracking_number = $request->order_number;
        $newInvoice->order_date = $request->order_date;
        if($request->file){
            $newInvoice->file = $file;
        }
        $newInvoice->package_id = $package->id;

        if ($newInvoice->save()) {
            if(Auth()->guard('web')->user()->region_id!=$newInvoice->region_id){
                $user = User::find(Auth()->guard('web')->user()->id);
                if($user!=null){
                    $user->region_id = $newInvoice->region_id;
                    $user->save();
                }
            }
            return response()->json(['status' => 200, 'message' => 'Bəyənnamə müvəffəqiyyətlə yükləndi']);
        }
    }




    public function edit(Request $request)
    {
        $user_id = Auth()->guard('web')->user()->id;
        $invoice_id = intval($request->invoice_id);
        if($invoice_id>0){
            $invoice = Invoice::where("id",$invoice_id)->where("user_id",$user_id)->first();
            if($invoice!=null){
                if($invoice->status_id==1){
                    if($invoice->added_by=='user' || $invoice->added_by=='depo' ){

                        $post_shop_name = $request->shop_name;
                        $post_product_type = $request->product_type;
                        $post_product_id = $request->product_id;
                        $post_quantity = $request->quantity;
                        $post_region = $request->region;
                        //$post_country_id = $request->country_id;
                        $post_price = $request->price;
                        $post_order_number = $request->order_number;
                        $post_order_date = $request->order_date;
                        $post_description = $request->description;

                        if($request->file){
                            $file = Uploader::upload($request['file'], 'public/invoice/', 'invoice', false, true);
                            $file = '/storage/invoice/'.$file;
                            $invoice->file = $file;
                        }

                        $invoice->price = $post_price;
                        $invoice->region_id = $post_region;
                        $invoice->order_date = $post_order_date;
                        $invoice->order_tracking_number = $post_order_number;

                        $invoice->save();

                        //$product_id = $invoice->product_id;
                        $product = Product::where("id",$post_product_id)->where("user_id",$user_id)->first();
                        if($product!=null){
                            $product->quantity = $post_quantity;
                            $product->product_type_name = $post_product_type;
                            $product->description = $post_description;
                            $product->	shop_name = $post_shop_name;
                            $product->save();
                        }

                        return response()->json(['status' => 200, 'message' => $request->invoice_id]);

                    }else{
                        return response()->json(['status' => 200, 'message' => 'Bəyan etdən əlavə olunan bağlamaları redakttə edə bilməzsiz']);
                    }
                }else{
                    return response()->json(['status' => 200, 'message' => 'Sifariş verildi statusunda olmayan bağlamaları redaktə edə bilməzsiz']);
                }
            }else{
                return response()->json(['status' => 200, 'message' => 'Bağlama tapılmadı']);
            }
        }



    }

}
