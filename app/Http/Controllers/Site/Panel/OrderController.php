<?php

namespace App\Http\Controllers\Site\Panel;

use App\Contact;
use App\ModelProduct\Product;
use App\RelUserProduct;
use App\Status;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use function PHPSTORM_META\type;

class OrderController extends Controller
{
    public function index($type)
    {

        $response = null;
        $userId = Auth()->user()->id;
        $statusId = null;
        if(isset($type) && $type != 'undefined') {
            if($type === 'transaction') {
                $response = $this->getTransactionRejectOrders($userId);
            } else if($type === 'invoice_added') {
                $response = $this->getTransactionInvoiceAddedOrders($userId);
            }else if($type === 'not-paid'){
                $response = $this->getNotPaidOrders($userId);
            }
            else {
                $response = $this->getTypedOrders($userId, $type);
            }
        } else {
            $response = $this->getAllOrders($userId);
        }
        return response()->json($response);
    }

    public function deleteBasket(Request $request)
    {
        $response = false;
        if(isset($request->id)){
            $id = intval($request->id);
            $user_id = Auth::user()->id;
            $product = Product::where(['id' => $id,"user_id" => $user_id])->first();
            $product->status_id = 777;
            $product->save();
        }
        return response()->json(['success' => true]);
    }

    private function getAllOrders ($userId) {
        $notIn[0] = getStatusByLabel("rejection_accepted", 'transaction');

        $data = Product::with(['extras', 'statuses'])
            ->where('user_id', '=', $userId)
            ->whereNotIn('status_id', $notIn)
            //->whereNotNull('transaction_id')
            ->where('is_paid', '=', 1)
            ->orderBy('id', 'desc')
            ->get();

        return $data;
    }

    private function getTypedOrders ($userId, $type) {
        if($type == "waiting") {
            $statusId = Status::where('label', '=', $type)->where('type', '=', 'product')->get();
            $data = Product::with(['extras', 'statuses'])
                ->where('status_id', '=', $statusId[0]->sid)
                ->where('user_id', '=', $userId)
                ->where('is_paid', '=', 1)
                ->where('is_ordered', 1)
                ->orderBy('id', 'desc')
                ->get();
            return $data;
        }
        if ($type == "ordered") {
            $statusId = Status::where('label', '=', 'completed')->where('type', '=', 'product')->get();
            $data = Product::with(['extras'])
                ->where('user_id', '=', $userId)
                ->where('status_id', '=', $statusId[0]->sid)
                ->where('is_paid', '=', 1)
                ->where('is_ordered', 1)
                ->orderBy('id', 'desc')
                ->get();
            return $data;
        } else {
            $statusId = Status::where('label', '=', $type)->where('type', '=', 'product')->get();

            $data = Product::with(['extras'])
                ->where('user_id', '=', $userId)
                ->where('status_id', '=', $statusId[0]->sid)
                ->where('is_paid', '=', 1)
                ->orderBy('id', 'desc')
                ->get();
            return $data;
        }
    }

    private function getTransactionRejectOrders ($userId) {
        $transStatusId = Status::where('label', '=', 'rejection')->where('type', '=', 'transaction')->get();
        $data = Product::with(['extras'])
            ->where('user_id', '=', $userId)
            ->where('status_id', '=', $transStatusId[0]->sid)
            ->orderBy('id', 'desc')
            ->get();
        return $data;
    }

    private function getNotPaidOrders ($userId) {
        $data = Product::with(['extras'])
            ->where('user_id', '=', $userId)
            ->where('is_paid', '=', 0)
            ->where('not_basket', '=', 0)
            ->where('status_id', '=', 3)
            ->orderBy('id', 'desc')
            ->get();
        return $data;
    }

    private function getTransactionInvoiceAddedOrders ($userId) {
        $status = getStatusByLabel('rejection', 'product');
        $transStatusId = Status::where('label', '=', 'invoice_added')->where('type', '=', 'transaction')->get();
        $data = Product::with(['extras'])
            ->where('user_id', '=', $userId)
            ->where('status_id', '=', $transStatusId[0]->sid)
            ->where('status_id', '<>', $status)
            ->where('is_paid', '=', 1)
            ->orderBy('id', 'desc')
            ->get();
        return $data;
    }



}
