<?php

namespace App\Http\Controllers\Admin\Accountant;

use App\ModelLogs\LogProductRejection;
use App\ModelProduct\ProductRejection;
use App\RelUserProduct;
use App\Status;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class RejectionController extends Controller
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
            ->where('type', '=', 'transaction')
            ->get();
        if($pin || $tel) {
            $data = ProductRejection::where('to_admin_id', '=', Auth()->guard('admin')->user()->id)
                ->with('buyer')
                ->with('relUserProducts')->whereHas('relUserProducts', function ($query) use ($status) {
                    $query->where('status_id', '=', $status[0]->id);
                })->with('relUserProducts.users.userContacts')
                ->whereHas('relUserProducts.users.userContacts', function ($query) use ($tel, $pin) {
                    if ($pin) {
                        $query->where('pin','=' , $pin)->where('name', 'like', '%'.$tel.'%' );
                    } else {
                        $query->where('name', 'like', '%' . $tel .'%');
                    }
                })
                ->paginate(30);
        } else {
            $data = ProductRejection::where('to_admin_id', '=', Auth()->guard('admin')->user()->id)
                ->with('buyer')
                ->with('relUserProducts')->whereHas('relUserProducts', function ($query) use ($status) {
                    $query->where('status_id', '=', $status[0]->id);
                })->paginate(30);
        }

//        dd($data[0]->relUserProducts->products[0]->extras, $data[0]->buyer);


        if($request->ajax()) {
            return view('admin.accountant.rejection.ajax', compact('data'));
        } else {
            return view('admin.accountant.rejection.index', compact('data'));
        }
    }

    public function showProducts($id) {
        $data = RelUserProduct::with('products')->find($id);
        $products = $data->products;
        return view('admin.accountant.rejection.list', compact('products'));

    }

    public function acceptRefusial (Request $request) {
        $status = Status::where('label', '=', 'rejection_accepted')
            ->where('type', '=', 'transaction')
            ->get();
        $data = RelUserProduct::find($request->id);
        $data->status_id = $status[0]->id;
        $data->save();
        $loggedData = ProductRejection::where('rel_user_product_id', '=', $request->id);
        $tempLoggedData = $loggedData->get()[0];
        $logData = new LogProductRejection();
        $logData->from_admin_id = $tempLoggedData->from_admin_id;
        $logData->to_admin_id = $tempLoggedData->to_admin_id;
        $logData->rel_user_product_id = $tempLoggedData->rel_user_product_id;
        $logData->note = $tempLoggedData->note;
        $logData->save();
        $loggedData->delete();
        return response()->json(['code' => 200, 'data' => null]);
    }
}
