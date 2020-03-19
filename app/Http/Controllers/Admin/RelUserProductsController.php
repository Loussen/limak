<?php
/**
 * Created by PhpStorm.
 * User: Rashad
 * Date: 25.11.2018
 * Time: 20:05
 */

namespace App\Http\Controllers\Admin;


use App\Currency;
use App\Http\Controllers\Controller;
use App\ModelProduct\Product;
use App\ModelProduct\ProductRejection;
use App\RelUserProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RelUserProductsController extends Controller
{
    public function refusal(Request $request) {
        $relUserProductId = $request['id'];
        $toAdminId = $request['accountantId'];
        $note = $request['note'];
        $fromAdminId = Auth::guard('admin')->user()->id;
        $statusTransaction = getStatusByLabel('rejection', 'transaction');
        $relUserProduct = RelUserProduct::find($relUserProductId);

        $relUserProduct->status_id = $statusTransaction;

        $relUserProduct->save();

        $model = new ProductRejection();

        $model->from_admin_id = $fromAdminId;
        $model->to_admin_id = $toAdminId;
        $model->rel_user_product_id = $relUserProductId;
        $model->note = $note;


        if ($model->save()) {
            return response()->json([
                'status' => 200
            ]);
        }
    }

    public function myOrders() {
        //$adminId = Auth::guard('admin')->user()->id;
        $transactionWaiting = getStatusByLabel('waiting', 'transaction');
        $result = RelUserProduct::with('users.userContacts', 'products.extras.countries.translates', 'products.productsType', 'products.invoices', 'products.statuses', 'statuses')
            //->where('admin_id', '=', $adminId)
            ->where('is_ordered', '<>', RelUserProduct::ORDERED)
            ->where('is_paid', '=', 1)
            ->where('status_id', '=', $transactionWaiting)->get();

        $currnecy = Currency::find(1);

        if($result) {
            return response()->json([
                'status' => 200,
                'data' => $result,
                'tl' => $currnecy->tl
            ]);
        }
    }

    public function willUploadInvoices () {
        $transactionWaiting = getStatusByLabel('waiting', 'transaction');
        $result = RelUserProduct::with('users.userContacts', 'products.extras.countries.translates', 'products.productsType', 'products.invoices', 'products.statuses', 'statuses')
            ->where('admin_id', '<>', NULL)
            ->where('is_ordered', '=', RelUserProduct::ORDERED)
            ->where('status_id', '=', $transactionWaiting)->get();

        if($result) {
            return response()->json([
                'status' => 200,
                'data' => $result
            ]);
        }
    }

    public function ordered(Request $request) {
        $transactionId = $request['id'];

        $model = RelUserProduct::find($transactionId);

        $model->is_ordered = RelUserProduct::ORDERED;

        if ($model->save()) {
            notify((object)['template' => 'order-placed'], (object)['phone' => $model->users->userContacts[0]->name, 'email' => $model->users->email]);
            return response()->json([
                'status' => 200,
                'message' => 'Sifariş verilməsi ilə bağlı sms və mail müştəriyə göndərildi!'
            ]);
        }
    }

    public function calcAddPrice (Request $request) {
        $price = $request['additionalPrice'];
        $currency = Currency::find(1);

        $price = $price;

        return response()->json([
            'withTl'  => $price,
            'withAzn' => number_format($price * $currency->tl, 2)
        ]);
    }

// new method orders copied myOrders method
    public function orders() {
        //$adminId = Auth::guard('admin')->user()->id;
        $transactionWaiting = getStatusByLabel('waiting', 'transaction');
        $result = Product::with('users.userContacts', 'extras.countries.translates', 'productsType', 'invoices', 'statuses')
            //->where('admin_id', '=', $adminId)
            ->where('is_ordered', '<>', Product::ORDERED)
            ->where('is_paid', '=', 1)
            ->where('status_id', '=', $transactionWaiting)
            ->groupBy('user_id')
            ->get();

        $currnecy = Currency::find(1);

        if($result) {
            return response()->json([
                'status' => 200,
                'data' => $result,
                'tl' => $currnecy->tl
            ]);
        }
    }
}
