<?php

namespace App\Http\Controllers\Front\Panel;

use App\Contact;
use App\Courier;
use App\Invoice;
use App\ModelLogs\LogBalance;
use App\ModelUser\User;
use App\RelUserProduct;
use App\Status;
use App\Transfer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use function PHPSTORM_META\type;

class CourierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexOld()
    {
        $balance = Auth::user()->balance;
        $userId = Auth::user()->id;
//        $status = getStatusByLabel('completed', 'invoice');
        $status = getStatusByLabel('home', 'invoice');
//        $data = Courier::with(['invoices', 'invoices.relUserProducts.products', 'invoices.relUserProducts.products.productsType'])->where('user_id', '=', $userId)->whereHas('invoices', function($query) use($status){
//            $query->where('status_id', '<>', $status );
//
//        })->get();
        $data = DB::table('couriers as c')
            ->select( 'c.*','c.price as c_price',
                 'p.shop_name', 'product_type_name',
                DB::raw('SUM(p.quantity) as quantity'),
                DB::raw('SUM(p.price) as price'),
                DB::raw('GROUP_CONCAT(p.product_type_name) as product_name'),
                DB::raw('COUNT(p.quantity) as count_product')
            )
            
//            ->leftJoin('users as u', 'i.user_id', '=', 'u.id')
            ->leftJoin('invoices as i', 'i.courier_id', '=', 'c.id')
            ->leftJoin('products as p', 'i.product_id', '=', 'p.id')
            ->leftJoin('product_types as pt', 'p.product_type_id', '=', 'pt.id')
            ->where('c.user_id', '=', $userId)
            //->where('i.status_id','=',$status)
            ->where('i.active','=',1)
            //->groupBy('c.user_id')
            ->get();

        return response()->json(["data" => $data, "balance" => $balance]);
    }

    public function index()
    {

        $balance = Auth::user()->balance;
        $userId = Auth::user()->id;

        $data = DB::table('invoices as i')
            ->select('c.*','i.status_id',DB::raw("count('i.id') as count"),
                DB::raw('GROUP_CONCAT(p.product_type_name) as product_name'),
                DB::raw('GROUP_CONCAT(p.shop_name) as shop_name')
)
            ->leftJoin("couriers as c",'i.courier_id','c.id')
            ->leftJoin('products as p', 'i.product_id', '=', 'p.id')
            ->leftJoin('product_types as pt', 'p.product_type_id', '=', 'pt.id')

            ->where("i.courier_id","!=",null)
            ->where('c.user_id', '=', $userId)
            ->groupBy('i.courier_id')
            ->orderBy("c.id",'DESC')
            ->get();




        return response()->json(["data" => $data, "balance" => $balance]);
    }

    public function indexTransfer()
    {
        $balance = Auth::user()->balance;
        $userId = Auth::user()->id;
//        $status = getStatusByLabel('completed', 'invoice');
        $status = getStatusByLabel('home', 'invoice');
//        $data = Courier::with(['invoices', 'invoices.relUserProducts.products', 'invoices.relUserProducts.products.productsType'])->where('user_id', '=', $userId)->whereHas('invoices', function($query) use($status){
//            $query->where('status_id', '<>', $status );
//
//        })->get();
        $data = DB::table('transfers as t')
            ->select( 't.*',
                'p.shop_name', 'product_type_name',
                DB::raw('SUM(p.quantity) as quantity'),
                DB::raw('SUM(p.price) as price'),
                DB::raw('GROUP_CONCAT(p.product_type_name) as product_name'),
                DB::raw('COUNT(p.quantity) as count_product')
            )

//            ->leftJoin('users as u', 'i.user_id', '=', 'u.id')
            ->leftJoin('invoices as i', 'i.transfer_id', '=', 't.id')
            ->leftJoin('products as p', 'i.product_id', '=', 'p.id')
            ->leftJoin('product_types as pt', 'p.product_type_id', '=', 'pt.id')
            ->where('t.user_id', '=', $userId)
            ->whereIn('i.status_id',[4,9])
            ->where('i.active','=',1)
            ->groupBy('t.user_id')
            ->get();

        return response()->json(["data" => $data, "balance" => $balance]);
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
    public function store_old(Request $request)
    {

        if($request->city=='' || $request->phone=='' || $request->time=='' || $request->region=='' ||  empty($request->products)){
            return response()->json(["data" => "Ulduzlanmış xanaları doldurun", "code" => 500]);
        }
        $data = new Courier();
        $data->user_id = Auth::user()->id;
        $data->is_paid = 0;
        $data->delivery_type = 1;
        $data->has_courier = 0;
        $data->phone = $request->phone;
        $data->city = $request->city;
        $data->district = $request->region;
        $data->village = $request->village;
        $data->street = $request->street;
        $data->home = $request->home;
        $data->description = $request->description;
        $data->save();
        foreach($request->products as $value){
            $insertData = Invoice::find($value['i_id']);
            var_dump($insertData->id);
//            if(is_null($insertData->courier_id)) {
                $insertData->courier_id = $data->id;
                $insertData->save();
//            }
        }
        return response()->json(["data" => "ok", "code" => 200]);

    }


    public function store(Request $request)
    {

        $locations =
            [
                1=> ["name" => "Bakı","price" =>4],
                2=> ["name" => "Sumqayıt","price" =>8],
                3=> ["name" => "Xırdalan","price" =>6],
                4=> ["name" => "Bakı kəndləri","price" =>8],
            ];

        if($request->city=='' || $request->phone=='' || $request->time=='' || $request->region=='' ||  empty($request->products)){
            return response()->json(["data" => "Ulduzlanmış xanaları doldurun", "code" => 500]);
        }
        $data = new Courier();
        $data->user_id = Auth::user()->id;
        $data->is_paid = 0;
        $data->delivery_type = 1;
        $data->has_courier = 0;
        $data->phone = $request->phone;
        if(isset($locations[$request->city])){
            $data->city = $locations[$request->city]["name"];
            $data->price = $locations[$request->city]["price"];
        }else{
            $data->city = 'Bakı '.$request->city;
            $data->price = 5;
        }

        $data->district = $request->region;
        $data->village = $request->village;
        $data->street = $request->street;
        $data->home = $request->home;
        $data->description = $request->description;
        $data->save();
        foreach($request->products as $value){
            $insertData = Invoice::find($value['i_id']);
            var_dump($insertData->id);
//            if(is_null($insertData->courier_id)) {
            $insertData->courier_id = $data->id;
            $insertData->save();
//            }
        }
        return response()->json(["data" => "ok", "code" => 200]);

    }

    public function transferStore(Request $request)
    {

        if($request->city=='' || $request->phone=='' || $request->time=='' || $request->region=='' ||  empty($request->products)){
            return response()->json(["data" => "Ulduzlanmış xanaları doldurun", "code" => 500]);
        }

        $userId = Auth::user()->id;
        $userBalance = Auth::user()->balance;
        $sumPrice = $request->sumPrice;
        if($userBalance<$sumPrice){
            return response()->json(["data" => "Balansiniz kifayet deyil", "code" => 300]);
        }

        $i=1;
        $invoices = '';
        foreach($request->products as $value){
            if($i>1){
                $invoices .= ",";
            }
            $invoices .= $value['i_id'];
            $i++;
        }

        $newUserBalance = $userBalance - $sumPrice;

        $changeBalance = User::find($userId);
        $changeBalance->balance = $newUserBalance;
        if($changeBalance->balance>0 and $changeBalance->save()){

            LogBalance::create([
                'user_id' => $userId,
                'old_balance' => $userBalance,
                'new_balance' => $newUserBalance,
                'money' => $sumPrice,
                'message' => 'Poçt xidməti ilə çatdırılma ödənişi'
            ]);


            $post_index =$request->post_index;
            $city = $request->city;
            $data = new Transfer();
            $data->user_id = Auth::user()->id;
            $data->is_paid = 1;
            $data->phone = $request->phone;
            $data->city = $city["id"];
            $data->village = $request->village;
            $data->street = $request->street;
            $data->home = $request->home;

            $data->sum_price = $request->sumPrice;
            $data->transfer_price = $request->transferPrice;
            $data->shipping_price = $request->shippingPrice;
            $data->sum_weight = $request->sumWeight;

            $data->post_index = $post_index["id"];
            $data->invoice_id = $invoices;
            $data->save();

            foreach($request->products as $value){
                $invoice = Invoice::find($value['i_id']);
                $invoice->transfer_id = $data->id;
                $invoice->save();
            }

            return response()->json(["data" => "ok", "code" => 200,"transfer_id" => $data->id]);

        }else{
            return response()->json(["data" => "Xəta baş verdi", "code" => 500]);
        }



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
