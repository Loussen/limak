<?php

namespace App\Http\Controllers\Admin\Accountant;

use App\ModelLogs\LogProductRejection;
use App\ModelProduct\Product;
use App\ModelProduct\ProductRejection;
use App\RelUserProduct;
use App\Status;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class InsufficiencyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $pin = Input::get('pin');
        $tel = Input::get('tel');
        $status = Status::where('label', '=', 'rejection')
        ->where('type', '=', 'product')
        ->get();
        if($pin || $tel) {
            $data = ProductRejection::where('to_admin_id', '=', Auth()->guard('admin')->user()->id)
                ->with('buyer')
                ->with('products')->whereHas('products', function ($query) use ($status) {
                    $query->where('status_id', '=', $status[0]->id);
                })->with('products.relUserProducts.users.userContacts')->whereHas('products.relUserProducts.users.userContacts', function ($query) use ($tel, $pin) {
                    if ($pin) {
                        $query->where('pin', '=', $pin)->where('name', 'like', '%' . $tel .'%');
                    } else {
                        $query->where('name', 'like', '%' . $tel .'%');
                    }
                })->paginate(30);
        } else {
            $data = ProductRejection::where('to_admin_id', '=', Auth()->guard('admin')->user()->id)
                ->with('buyer')
                ->with('products')
                ->whereHas('products', function ($query) use ($status) {
                $query->where('status_id', '=', $status[0]->id);
            })->with('products.relUserProducts.users')->paginate(30);;
        }

        if($request->ajax()) {
            return view('admin.accountant.insufficiency.ajax', compact('data'));
        } else {
            return view('admin.accountant.insufficiency.index', compact('data'));
        }
    }

    public function showProducts($id, $productId) {
        $data = RelUserProduct::with('products')->find($id);
        $products = $data->products;
        return view('admin.accountant.insufficiency.list', compact('products', 'productId'));

    }

    public function acceptRefusial (Request $request) {
        $status = Status::where('label', '=', 'rejection_accepted')
            ->where('type', '=', 'product')
            ->get();
        $data = Product::find($request->id);
        $data->status_id = $status[0]->id;
        $data->save();
        $loggedData = ProductRejection::where('product_id', '=', $request->id);
        $tempLoggedData = $loggedData->get()[0];
        $logData = new LogProductRejection();
        $logData->from_admin_id = $tempLoggedData->from_admin_id;
        $logData->to_admin_id = $tempLoggedData->to_admin_id;
        $logData->product_id = $tempLoggedData->product_id;
        $logData->note = $tempLoggedData->note;
        $logData->save();
        $loggedData->delete();
        return response()->json(['code' => 200, 'data' => null]);
    }
}
