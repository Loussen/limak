<?php

namespace App\Http\Controllers\Front\Panel;

use App\Contact;
use App\ModelProduct\Extras;
use App\ModelProduct\Product;
use App\RelUserProduct;
use App\Status;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use function PHPSTORM_META\type;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


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
            }else if($type === 'paid'){
                $response = $this->getPaidOrders($userId);
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


    public function updateProduct(Request $request){

        $product = Product::find($request->id);
        if($product->user_id === Auth::id() && ($product->is_ordered === 0 || $product->is_problem === 1))
        {
            $product->admin_id = null;
            $product->is_problem = 0;
            $product->quantity = $request->quantity;
            $product->description = $request->description;


            $extra = Extras::find($product->extras_id);
            $extra->link = $request->link;
            $extra->link2 = $request->link;

            $product->save();
            $extra->save();
            return response()->json('Sifariş dəyişildi', 200);

        }else{
            return response()->json('Sifariş icradadır', 200);
        }

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

    private function getPaidOrders ($userId) {
        $data = Product::with(['extras'])
            ->where('user_id', '=', $userId)
            ->where('is_paid', '=', 1)
            ->where('status_id', '=', 1)
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



    /**
    Butun sorgulari deyisdik RelUserProducts -> Products ile evezlendi kohne funksiyalar asagidadi
     */


    public function index_old($type)
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

    private function getAllOrders_old ($userId) {
        $notIn[0] = getStatusByLabel("rejection_accepted", 'transaction');
        $data = RelUserProduct::with(['products' => function($q) {
            $q->orderBy('id', 'desc');
        }, 'products.extras', 'products.statuses'])
            ->where('user_id', '=', $userId)
            ->whereNotIn('status_id', $notIn)
            ->whereNotNull('transaction_id')
            ->where('is_paid', '=', 1)
            ->orderBy('id', 'desc')
            ->get();
        return $data;
    }


    private function getTypedOrders_old ($userId, $type) {
        if($type == "waiting") {
            $statusId = Status::where('label', '=', $type)->where('type', '=', 'product')->get();
            $transStatusId = Status::where('label', '=', 'rejection')->where('type', '=', 'transaction')->get();
            $transStatusId2 = Status::where('label', '=', 'rejection_accepted')->where('type', '=', 'transaction')->get();

            $notInArry = [$transStatusId[0]->id, $transStatusId2[0]->id];

            $callback = function ($query) use ($statusId) {
                $query->where('status_id', '=', $statusId[0]->id)->orderBy('id', 'desc');
            };
            $data = RelUserProduct::with(['products.extras', 'products.statuses', 'products' => $callback])
                ->whereHas('products', $callback)
                ->where('user_id', '=', $userId)
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
                ->where('user_id', '=', $userId)
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
                ->where('user_id', '=', $userId)
                ->whereNotIn('status_id', $notInArry)
                ->whereNotNull('transaction_id')
                ->where('is_paid', '=', 1)
                ->orderBy('id', 'desc')
                ->get();
            return $data;
        }
    }


    private function getTransactionRejectOrders_old ($userId) {
        $transStatusId = Status::where('label', '=', 'rejection')->where('type', '=', 'transaction')->get();
        $data = RelUserProduct::with(['products.extras','products'])
            ->where('user_id', '=', $userId)
            ->where('status_id', '=', $transStatusId[0]->id)
            ->orderBy('id', 'desc')
            ->get();
        return $data;
    }

    private function getNotPaidOrders_old ($userId) {
        $data = RelUserProduct::with(['products.extras','products'])
            ->where('user_id', '=', $userId)
            ->where('is_paid', '=', 0)
            ->orderBy('id', 'desc')
            ->get();
        return $data;
    }

    private function getTransactionInvoiceAddedOrders_old ($userId) {
        $status = getStatusByLabel('rejection', 'product');
        $transStatusId = Status::where('label', '=', 'invoice_added')->where('type', '=', 'transaction')->get();
        $data = RelUserProduct::with(['products.extras','products' => function($query) use($status) {
            $query->where('status_id', '<>', $status);
        }])
            ->where('user_id', '=', $userId)
            ->where('status_id', '=', $transStatusId[0]->id)
            ->where('is_paid', '=', 1)
            ->whereNotNull('transaction_id')
            ->orderBy('id', 'desc')
            ->get();
        return $data;
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
     * @param  \Illuminate\Http\Request $request
     * @param Contact $contact
     * @return void
     */
    public function store(Request $request)
    {

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
}
