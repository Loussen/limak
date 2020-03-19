<?php
/**
 * Created by PhpStorm.
 * User: Rashad
 * Date: 25.11.2018
 * Time: 17:42
 */

namespace App\Http\Controllers\Us;


use App\Http\Controllers\Controller;
use App\Invoice;
use App\Libraries\Upload\Uploader;
use App\ModelCountry\Regions;
use App\ModelProduct\Extras;
use App\ModelProduct\Product;
use App\ModelProduct\ProductRejection;
use App\ModelUser\Client;
use App\ModelUser\User;
use App\Persons;
use App\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use \Milon\Barcode\DNS1D;

class ProductsController extends Controller
{
//    public function rejection(Request $request) {
//        $productId = $request['id'];
//        $toAdminId = $request['accountantId'];
//        $note = $request['note'];
//        $fromAdminId = Auth::guard('admin')->user()->id;
//        $statusProduct = getStatusByLabel('rejection', 'product');
//        $productModel = Product::find($productId);
//
//        $productModel->status_id = $statusProduct;
//
//        $productModel->save();
//
//        $model = new ProductRejection();
//
//        $model->from_admin_id = $fromAdminId;
//        $model->to_admin_id = $toAdminId;
//        $model->product_id = $productId;
//        $model->note = $note;
//
//
//        if ($model->save()) {
//            return response()->json([
//                'status' => 200
//            ]);
//        }
//
//    }
//
//    public function invoiceUpload(Request $request) {
//
//        $file = Uploader::upload($request['file'], 'public/invoices/', 'invoice', false, true);
//
//        $model = Product::find($request['productId'])->invoices[0];
//
//        $model->file = '/storage/invoices/'.$file;
//
//        if ($model->save()) {
//            return response()->json(['status' => 200, 'message' => 'Success']);
//        } else {
//            return response()->json(['status' => 500, 'message' => 'Server error']);
//        }
//
//    }
//
//    public function invoiceUpload_new(Request $request) {
//        $file = Uploader::upload($request['file'], 'public/invoices/', 'invoice', false, true);
//        var_dump($file);
////        die;
//
//        $model = Invoice::find($request['invoiceId']);
//        $model->file = '/storage/invoices/'.$file;
//
//        if ($model->save()) {
//            return response()->json(['status' => 200, 'message' => 'Success']);
//        } else {
//            return response()->json(['status' => 500, 'message' => 'Server error']);
//        }
//
//    }
//
//    public function updateInvoice(Request $request) {
//        $model = Invoice::find($request['invoiceId']);
//
//        Storage::delete($model->file);
//
//        $file = Uploader::upload($request['file'], 'public/invoices/', 'invoice', false, true);
//
//        $model->file = '/storage/invoices/'.$file;
//
//        if ($model->save()) {
//            return response()->json(['status' => 200, 'message' => 'Success']);
//        } else {
//            return response()->json(['status' => 500, 'message' => 'Server error']);
//        }
//    }
//
//    public function changeExtrasLink(Request $request)
//    {
//        $extras = Extras::findOrFail($request->id);
//        $extras->link = $request->link;
//        $extras->update();
//
//        return response()->json('OK', 200);
//    }
//
//    public function getProductDataById($id) {
//        $data = Product::find($id);
//
//        $data->invoices[0]->order_date = explode(' ', $data->invoices[0]->order_date)[0];
//
//        return view('admin.invoice.hawb-ajax', compact('data'));
//    }
//
//    public function comment(Request $request) {
//
//        $Product = Product::find((int)$request->id);
//        $Product->comment = $request->comment;
//        $Product->save();
//
//        return response()->json(['status' => 200, 'data' => $Product]);
//    }
//
//    public function saveRealPrice(Request $request) {
//
//        $Product = Product::find((int)$request->product_id);
//        $Product->real_price = $request->real_price;
//        $Product->over_price = $request->over_price;
//        $Product->income = $request->income;
//        $Product->expenses = $request->expenses;
//        $Product->save();
//
//        return response()->json(['status' => 200, 'data' => $Product]);
//    }

    public function printHawb_old($productId) {
        $product = Product::find($productId);

        $purchaseNo = '';
        $barcode = DNS1D::getBarcodeSVG($product->invoices[0]->purchase_no, "C128",1.5,64);

        for ($i = 0; $i < strlen($product->invoices[0]->purchase_no ); $i++) {
            $purchaseNo = $purchaseNo.$product->invoices[0]->purchase_no[$i].' - ';
        }

        $purchaseNo = rtrim($purchaseNo,' - ');

        return view('usa.invoice.pdf', compact( 'product', 'barcode', 'purchaseNo'));
    }

    public function printHawb($productId) {

        \Artisan::call('cache:clear');
        \Artisan::call('view:clear');
        $invoice = Invoice::find($productId);
        $product = Product::find($invoice->product_id);
        $regions = Regions::pluck('name','id')->toArray();
        if($invoice->corporate==1 and $invoice->client_id>0){
            $user = Client::where("user_id",$invoice->user_id)->where("id",$invoice->client_id)->first();
        }elseif($invoice->person_id>0){
            $user = Persons::where("id",$invoice->person_id)->where("user_id",$invoice->user_id)->first();
        }
        else{
            $user = User::find($invoice->user_id);

        }
        $purchaseNo = '';
        $barcode = DNS1D::getBarcodeSVG($invoice->purchase_no, "C128",1.5,64);

        for ($i = 0; $i < strlen($invoice->purchase_no ); $i++) {
            $purchaseNo = $purchaseNo.$invoice->purchase_no[$i].' - ';
        }

        $purchaseNo = rtrim($purchaseNo,' - ');
        return view('usa.invoice.pdf', compact( 'product', 'barcode', 'purchaseNo', 'invoice','regions','user'));
    }
}
