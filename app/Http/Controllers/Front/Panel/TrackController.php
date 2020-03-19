<?php

namespace App\Http\Controllers\Front\Panel;

use App\Contact;
use App\Invoice;
use App\RelUserProduct;
use App\Status;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use phpDocumentor\Reflection\Types\Integer;
use function PHPSTORM_META\type;

class TrackController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $userId = Auth::user()->id;
       $statuses = Status::where('type', '=', 'invoice')->where('label', '<>', 'completed')->get();
       $collectedIds = $statuses->map(function ($data) {
            return $data->sid;
        });
        $data = Invoice::with(['invoiceStatus', 'products.extras', 'products.productsType', 'products.relUserProducts', 'courier'])->whereHas('invoiceStatus', function($query) use($collectedIds) {
            $query->whereIn('status_id', $collectedIds);
        })/*->whereHas('products.relUserProducts', function($query) use($userId) {
                $query->whereIn('user_id', [$userId]);
            })*/
            ->where('user_id',$userId)
            ->orderBy('id', 'desc')->get();
        $balance = Auth::user()->balance;
       return response()->json(['data' => $data, 'balance' => $balance]);
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
