<?php

namespace App\Http\Controllers\Admin;

use App\RelUserProduct;
use App\Status;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class OrderController extends Controller
{
    public function index()
    {
        $type = Input::get('type');
        $response = null;
        $statusId = null;

        if(isset($type) && $type != 'undefined') {
            if($type === 'transaction') {
                $response = $this->getTransactionRejectOrders();
            } else {
                $response = $this->getTypedOrders($type);
            }
        } else {
            $response = $this->getAllOrders();
        }
        return view('admin.order.index' , compact('response'));
    }

    private function getAllOrders () {
        $notIn[0] = getStatusByLabel("rejection", 'transaction');
        $notIn[1] = getStatusByLabel("rejection_accepted", 'transaction');
        $uniqid = Input::get('uniqid');
        $name = Input::get('name');
//        dd($notIn);
        $data = RelUserProduct::with('products', 'products.extras', 'products.statuses', 'users')
            ->whereNotIn('status_id', $notIn)
            ->whereNotNull('transaction_id')
            ->where('is_paid', '=', 1)
            ->whereHas('users', function ($query) use ($uniqid, $name) {
                if(!empty($uniqid)) {
                    $query->where('uniqid', '=', $uniqid);
                }
                if (!empty($name)) {
                    $query->where('name', 'like', '%'.$name.'%');
                    $query->orWhere('surname', '=', '%'.$name.'%');
                }
            })
            ->orderBy('id', 'desc')
            ->paginate(15);
        return $data;
    }

    private function getTypedOrders ($type) {
        if($type == "waiting") {
            $statusId = Status::where('label', '=', $type)->where('type', '=', 'product')->get();
            $transStatusId = Status::where('label', '=', 'rejection')->where('type', '=', 'transaction')->get();
            $transStatusId2 = Status::where('label', '=', 'rejection_accepted')->where('type', '=', 'transaction')->get();

            $notInArry = [$transStatusId[0]->id, $transStatusId2[0]->id];

            $callback = function ($query) use ($statusId) {
                $query->where('status_id', '=', $statusId[0]->id)->orderBy('id', 'desc');
            };
            $data = RelUserProduct::with(['products.extras', 'products' => $callback])
                ->whereHas('products', $callback)
                ->whereNotIn('status_id', $notInArry)
                ->whereNotNull('transaction_id')
                ->where('is_paid', '=', 1)
                ->whereNotIn('is_ordered', [1])
                ->orderBy('id', 'desc')
                ->get();
            return $data;
        }
        if ($type == "ordered") {
            $statusId = Status::where('label', '=', 'waiting')->where('type', '=', 'product')->get();
            $transStatusId = Status::where('label', '=', 'rejection')->where('type', '=', 'transaction')->get();
            $transStatusId2 = Status::where('label', '=', 'rejection_accepted')->where('type', '=', 'transaction')->get();

            $notInArry = [$transStatusId[0]->id, $transStatusId2[0]->id];

            $callback = function ($query) use ($statusId) {
                $query->where('status_id', '=', $statusId[0]->id)->orderBy('id', 'desc');
            };
            $data = RelUserProduct::with(['products.extras', 'products' => $callback])
                    ->whereHas('products', $callback)
                    ->whereNotIn('status_id', $notInArry)
                    ->whereNotNull('transaction_id')
                    ->where('is_paid', '=', 1)
                    ->where('is_ordered', 1)
                    ->orderBy('id', 'desc')
                    ->get();
            return $data;
        } else {
            $statusId = Status::where('label', '=', $type)->where('type', '=', 'product')->get();
            $transStatusId = Status::where('label', '=', 'rejection')->where('type', '=', 'transaction')->get();
            $transStatusId2 = Status::where('label', '=', 'rejection_accepted')->where('type', '=', 'transaction')->get();

            $notInArry = [$transStatusId[0]->id, $transStatusId2[0]->id];

            $callback = function ($query) use ($statusId) {
                $query->where('status_id', '=', $statusId[0]->id)->orderBy('id', 'desc');
            };
            $data = RelUserProduct::with(['products.extras', 'products' => $callback])
                ->whereHas('products', $callback)
                ->whereNotIn('status_id', $notInArry)
                ->whereNotNull('transaction_id')
                ->where('is_paid', '=', 1)
                ->orderBy('id', 'desc')
                ->get();
            return $data;
        }
    }
    private function getTransactionRejectOrders () {
        $transStatusId = Status::where('label', '=', 'rejection')->where('type', '=', 'transaction')->get();
        $data = RelUserProduct::with(['products.extras','products'])
            ->where('status_id', '=', $transStatusId[0]->id)
            ->orderBy('id', 'desc')
            ->get();
        return $data;
    }
}
