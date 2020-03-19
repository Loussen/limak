<?php

namespace App\Http\Controllers\Front;

use App\Invoice;
use App\UserPromoCodes;
use App\Libraries\Upload\Uploader;
use App\ModelProduct\Product;
use App\ModelProduct\ProductType;
use App\RelUserProduct;
use App\Status;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Packages;
use Artisan;

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
        return view('front.invoice', compact('productTypes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        $rules = [
            'product_type_id' => 'required|integer',
            'price' => 'required|integer',
            'quantity' => 'required|integer',
            'shop_name' => 'required|string',
            'description' => 'required',
            'order_number' => 'required',
            'order_date' => 'required',
            //'invoice' => 'mimes:jpg,png,jpeg,bmp,pdf,docx,doc,html'

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

        $package= new Packages();
        $package->status = 1;
        $package->save();

        $invoiceAddStatus = getStatusByLabel('invoice_added', 'transaction');

        $status = getStatusByLabel('with_invoice', 'product');
        $statusInvoice = getStatusByLabel('waiting', 'invoice');
        $user_id = Auth()->guard('web')->user()->id;
        $newRelUserProduct = new RelUserProduct();
        $newRelUserProduct->status_id = $invoiceAddStatus;
        $newRelUserProduct->user_id = $user_id;
        $newRelUserProduct->is_paid = 1;
        $newRelUserProduct->is_ordered = 1;
        $newRelUserProduct->save();
        //
        $newProduct = new Product();
        $newProduct->product_type_id = $request->product_type_id;
        $newProduct->rel_user_product_id = $newRelUserProduct->id;
        $newProduct->user_id = $user_id;
        $newProduct->price = $request->price;
        $newProduct->region_id = $request->region;
        $newProduct->quantity = $request->quantity;
        $newProduct->shop_name = $request->shop_name;
        $newProduct->description = $request->description;
        $newProduct->status_id = $status;
        $newProduct->promo_code = $request->promo_code;
        $newProduct->save();




        $file = '';
        if($request->hasFile('invoice')){
            $file = Uploader::upload($request['invoice'], 'public/invoice/', 'invoice', false, true);
            $file = '/storage/invoice/'.$file;
        }


        $newInvoice = new Invoice();
        $newInvoice->product_id = $newProduct->id;
        $newInvoice->rel_user_product_id = $newRelUserProduct->id;
        $newInvoice->user_id = $user_id;
        $newInvoice->region_id = $request->region;
        $newInvoice->status_id = $statusInvoice;
        $newInvoice->purchase_no = str_pad($lastPurchase_no + 1, 9, 0, STR_PAD_LEFT);
        $newInvoice->order_tracking_number = $request->order_number;
        $newInvoice->order_date = $request->order_date;
        $newInvoice->file = $file; 
        $newInvoice->price = $request->price;
        $newInvoice->promo_code = $request->promo_code;
        $newInvoice->package_id = $package->id;
        $newInvoice->save();


        if($request->promo_code!='') {
            $userPromoCode = new UserPromoCodes();
            $userPromoCode->status = 0;
            $userPromoCode->invoice_id = $newInvoice->id;
            $userPromoCode->campaign_id = 1;
            $userPromoCode->promo_code = $request->promo_code;
            $userPromoCode->user_id = $user_id;
            $userPromoCode->save();
        }

        if ($newInvoice->save()) {
            return response()->json(['status' => 200, 'message' => 'Bəyənnamə müvəffəqiyyətlə yükləndi']);
        }
    }


}
