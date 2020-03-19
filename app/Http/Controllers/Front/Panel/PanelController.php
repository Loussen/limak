<?php

namespace App\Http\Controllers\Front\Panel;
use App\Currencies;
use App\Currency;
use App\Contact;
use App\ModelLogs\LogBalance;
use App\Status;
use App\Courier;
use App\Invoice;
use App\ModelLogs\LogPaymentCouriers;
use App\ModelLogs\LogPaymentDeliveryInvoices;
use App\ModelUser\User;
use App\UserBalanceImpExpLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon ;
use Cookie;
class PanelController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Cookie::queue('kampaniya'.date('Ymd'),'test',1440);
//        $cookie = cookie('kampaniya', 'test', 1440);

        return view('front.panel.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
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

    public function userData () {
        $status = Status::where('label', '=', 'on_the_way')->where('type', '=', 'invoice')->first();
        $data = Auth::user();

        $currency = Currencies::where("name","try-usd")->first();
        $tryToUsd = $currency->val;
        $data['last30days']=0;
        $clients_data = null;
        $clients = null;

        if($data->corporate==1){
            $clientsRows = DB::table("clients")->select("*")->where("user_id",$data->id)->where("status_id",1)->orderBy("id","DESC")->get();

            $clients = [];

            $currency = Currencies::where("name","try-usd")->first();
            $tryToUsd = $currency->val;

            foreach ($clientsRows as $client){
                $clients[$client->id]["id"] = $client->id;
                $clients[$client->id]["user_id"] = $client->user_id;
                $clients[$client->id]["name"] = $client->name;
                $clients[$client->id]["surname"] = $client->surname;
                $clients[$client->id]["last30"] = 0;

            }

            $status = Status::where('label', '=', 'on_the_way')->where('type', '=', 'invoice')->first();
            $last30days = DB::table('invoices')
                ->select("invoices.shipping_price","invoices.client_id","invoices.price","invoices.country_id")
                ->whereIn('invoices.id', function($query)
                {
                    $status = Status::where('label', '=', 'on_the_way')->where('type', '=', 'invoice')->first();
                    $query->select('invoice_id')
                        ->from('invoice_dates')
                        ->where('action_date', '>', Carbon::now()->subDays(30)->toDateTimeString())
                        ->where("status_id","=",$status->sid);
                })
                ->where('invoices.user_id','=',$data->id)
                ->where('invoices.corporate','=',1)
                ->where('invoices.client_id','!=',0)
                ->where('invoices.status_id','!=',777)
                ->where('invoices.status_id','>',$status->sid)
                ->get();
            foreach ($last30days as $row){
                if($row->client_id>0 and isset($clients[$row->client_id])){
                    if($row->country_id==1){
                         $clients[$row->client_id]["last30"] =  number_format(( (float)$clients[$row->client_id]["last30"] + (float)$row->price*$tryToUsd+ (float)$row->shipping_price), 2);
                    }elseif($row->country_id==2){
                        $clients[$row->client_id]["last30"] =  number_format( ((float)$clients[$row->client_id]["last30"] + (float)($row->price) + (float)($row->shipping_price)), 2);
                    }
                }
            }
        }
        $data["clients"] = $clients;
        $last30days_price = userLast30($data->id);

        $data['last30days']=number_format($last30days_price, 2);
        $data["last30days_trytousd"] = $tryToUsd;

        return $data;
    }

    public function userDataNew () {
        $status = Status::where('label', '=', 'on_the_way')->where('type', '=', 'invoice')->first();
        $data = Auth::user();

        $currency = Currency::find(1);
        $tryToUsd = 1/$currency->tl * $currency->usd;
        $data['last30days']=0;


        $last30days = DB::table('invoices')
            ->select(DB::raw("sum(shipping_price) as shipping_price"),DB::raw("sum(price) as price"))
            ->whereIn('id', function($query)
            {
                $status = Status::where('label', '=', 'on_the_way')->where('type', '=', 'invoice')->first();
                $query->select('invoice_id')
                    ->from('invoice_dates')
                    ->where('action_date', '>', Carbon::now()->subDays(30)->toDateTimeString())
                    ->where("status_id","=",$status->sid);
            })
            ->where('invoices.user_id','=',$data->id)
            ->first();

        if($last30days!=null){
            $data['last30days']=number_format($last30days->price / $tryToUsd + $last30days->shipping_price, 2);
            $data["last30days_data"] = $last30days;
            $data["last30days_trytousd"] = $tryToUsd;
        }
        return $data;
    }
    
    public function userData1(){
        $data = Auth::user();
        $currency = Currency::find(1);
        $tryToUsd = 1/$currency->tl * $currency->usd;
        $minus30=date('Y-m-d', strtotime('-30 day', strtotime(date('Y-m-d'))));
        $data['last30days']=0;
        $last30days=DB::table('invoices as i')
            ->select(DB::raw('SUM(i.shipping_price) as shipping_price'), DB::raw('SUM(p.price) as price'), 'r.user_id')
            ->leftJoin('rel_user_products as r', 'r.id', '=', 'i.rel_user_product_id')
            ->leftJoin('products as p', 'i.product_id', '=', 'p.id')
            ->leftJoin('invoice_dates as id','id.invoice_id','=','i.id')
            ->where('id.action_date', '>', $minus30)
            ->where('id.status_id', '=', 3)
//            ->where('i.order_date', '>', $minus30)
            ->where('r.user_id', '=' , $data->id)
            ->groupBy('r.user_id')
            ->toSql();
        var_dump($minus30);
        var_dump($last30days);
        if($last30days!=null)
            $data['last30days']=number_format($last30days->price / $tryToUsd + $last30days->shipping_price, 2);
        return response()->json(['data'=>$data]);
    }
    public function pay (Request $request) {
        $userId = Auth::user()->id;
        $userBalance = Auth::user()->balance;
        $price = 0;
        $invoiceCash = Invoice::select('delivery_price', 'courier_id')
        ->where('id', '=', $request->invoiceId)
        ->where('is_paid', '=', 0)
        ->get();

        if(count($invoiceCash) === 0) {
            return response()->json(['data' => 'artıq ödənilib', 'code' => 1602]);
        }
        $invoiceCash = $invoiceCash[0];
        $courier_id = $invoiceCash->courier_id;

        //$usd_to_azn = Config::get('constants.usd_to_azn');
        $price = 1.70*$invoiceCash->delivery_price;

        if($request->withCourier) {
            $courierType = Courier::select('delivery_type', 'is_paid')
                ->where('id', '=', $courier_id)
                ->where('user_id', '=', $userId)
                ->get()[0];
            $courierCash =  $courierType->delivery_type == 1? Courier::COURIER_PRICE_NORMAL : Courier::COURIER_PRICE_FAST;
            if(!$courierType->is_paid) {
                $price += $courierCash;
            }
        }
        if($userBalance >= $price) {
            $newUserBalance = $userBalance - $price;

            $changeBalance = User::find($userId);
            $changeBalance->balance = $newUserBalance;
            $changeBalance->save();

            $changeInvoice = Invoice::find($request->invoiceId);
            $changeInvoice->is_paid = 1;
            $changeInvoice->payment_type = 1;
            $changeInvoice->save();
            $logPayment = new LogPaymentDeliveryInvoices();
            $logPayment->user_id = Auth::user()->id;
            $logPayment->user_balance = $newUserBalance;
            $logPayment->delivery_cash = $price;
            $logPayment->invoice_id = $request->invoiceId;
            $logPayment->save();
            $logBalance = new UserBalanceImpExpLog();
            $logBalance->user_id = Auth()->user()->id;
            $logBalance->amount =$price;
            $logBalance->type = getBalanceLogType('expenditure');
            $logBalance->save();
            if ($request->withCourier && !$courierType->is_paid) {
                $changeCourier = Courier::find($courier_id);
                $changeCourier->is_paid = 1;
                $changeCourier->save();
            }
            return response()->json(['data' => 'Ödənildi', 'code' => 200]);
        } else {
            return response()->json(['data' => 'Balansınız çatmır', 'code' => '1601']);
        }
    }


    public function payOnlyCourier_old(Request $request) {
        $userId = Auth::user()->id;
        $userBalance = Auth::user()->balance;
        $price = 0;

        if($request->courierId) {
            $courierType = Courier::select('delivery_type', 'is_paid')
                ->where('id', '=', $request->courierId)
                ->where('user_id', '=', $userId)
                ->get()[0];
            $courierCash =  $courierType->delivery_type == 1? Courier::COURIER_PRICE_NORMAL : Courier::COURIER_PRICE_FAST;
            if(!$courierType->is_paid) {
                $price += $courierCash;
            }
        }
        if($userBalance >= $price) {
            $newUserBalance = $userBalance - $price;

            $changeBalance = User::find($userId);
            $changeBalance->balance = $newUserBalance;
            $changeBalance->save();

            if (!$courierType->is_paid) {
                $changeCourier = Courier::find($request->courierId);
                $changeCourier->is_paid = 1;
                $changeCourier->save();
                $logPayment = new LogPaymentDeliveryInvoices();
                $logPayment->user_id = Auth::user()->id;
                $logPayment->user_balance = $newUserBalance;
                $logPayment->delivery_cash = $price;
                $logPayment->courier_id = $request->courierId;
                $logPayment->save();
                $logBalance = new UserBalanceImpExpLog();
                $logBalance->user_id = Auth()->user()->id;
                $logBalance->amount =$price;
                $logBalance->type = getBalanceLogType('expenditure');
                $logBalance->save();
            }
            return response()->json(['data' => 'Ödənildi', 'code' => 200]);
        } else {
            return response()->json(['data' => 'Balansınız çatmır', 'code' => 1601]);
        }
    }

    public function payOnlyCourier(Request $request) {
        $userId = Auth::user()->id;
        $userBalance = Auth::user()->balance;
        $price = 0;

        if($request->courierId) {
            $courierType = Courier::select('delivery_type', 'is_paid','price' )
                ->where('id', '=', $request->courierId)
                ->where('user_id', '=', $userId)
                ->get()[0];
            $courierCash =  $courierType->price; //$courierType->delivery_type == 1? Courier::COURIER_PRICE_NORMAL : Courier::COURIER_PRICE_FAST;
            if(!$courierType->is_paid) {
                $price += $courierCash;
            }
        }
        if($userBalance >= $price) {
            $newUserBalance = $userBalance - $price;

           /* $changeBalance = User::find($userId);
            $changeBalance->balance = $newUserBalance;
            $changeBalance->save();*/

            if (!$courierType->is_paid) {

                $changeCourier = Courier::find($request->courierId);
                $changeCourier->is_paid = 1;
                $changeCourier->save();


                $logPayment = new LogPaymentDeliveryInvoices();
                $logPayment->user_id = Auth::user()->id;
                $logPayment->user_balance = $newUserBalance;
                $logPayment->delivery_cash = $price;
                $logPayment->courier_id = $request->courierId;
                $logPayment->save();


                $amount = $price - 2*$price;
                $user = User::find(Auth::user()->id);
                $balance = $user->balance;
                $user->balance = $balance + $amount;
                $user->save();

                $message = 'Balansdan Kuryer ödənişi';

                LogBalance::create([
                    'user_id' => $user->id,
                    'old_balance' => $balance,
                    'new_balance' => $user->balance,
                    'money' => $amount,
                    'message' => $message,
                    'type' => 'azn'
                ]);

            }
            return response()->json(['data' => 'Ödənildi', 'code' => 200]);
        } else {
            return response()->json(['data' => 'Balansınız çatmır', 'code' => 1601]);
        }
    }

    public function cancelOnlyCourier(Request $request) {
        $userId = Auth::user()->id;

        if($request->courierId) {
            $courierType = Courier::select('delivery_type', 'is_paid','price' )
                ->where('id', '=', $request->courierId)
                ->where('user_id', '=', $userId)
                ->get()[0];
            if(!$courierType->is_paid and $courierType->has_courier!=1) {
                $invoices = Invoice::where("courier_id",$request->courierId)->where("status_id",4)->get();
                foreach ($invoices as $invoice) {
                    $invoice->courier_id = null;
                    $invoice->save();
                }
                $courierType->delete();

                return response()->json(['data' => 'Kuryer sifarişi ləğv edildi', 'code' => 200]);


            }else{
                return response()->json(['data' => 'Bu kuryer sifarişini imtina ede bilməzsiz', 'code' => 501]);
            }
        }

    }


    public function getProfit() {
        $data = UserBalanceImpExpLog::where('user_id', '=', Auth::user()->id)
            ->where('type', '=', getBalanceLogType('profit'))
            ->orderBy('id', 'desc')
            ->paginate(10);
        return response()->json(["data" => $data]);
    }

    public function getUserExpense() {
        $userId = Auth::user()->id;
        $data = DB::table('log_payment_delivery_invoices')
            ->select('user_balance',
                'delivery_cash',
                'invoice_id',
                'log_payment_delivery_invoices.created_at',
                'products.product_type_name',
                'product_types.name',
                'couriers.city',
                'couriers.district',
                'couriers.street',
                'couriers.home'
            )
            ->leftJoin('invoices', function($join) {
                $join->on('log_payment_delivery_invoices.invoice_id', '=', 'invoices.id')
                    ->join('products', function ($join) {
                        $join->on('invoices.product_id', '=', 'products.id')
                            ->join('product_types', 'products.product_type_id', '=', 'product_types.id');
                    });
            })
            ->Leftjoin('couriers','log_payment_delivery_invoices.courier_id', '=', 'couriers.id' )
            ->where('log_payment_delivery_invoices.user_id', '=', $userId)
            ->orderBy('log_payment_delivery_invoices.created_at', 'desc')
            ->paginate(10);
        return response()->json($data);
    }

    public function getLogBalance(Request $request)
    {
        $userId = Auth::user()->id;
        $type = $request->get("type",'0');
        $balance_type = $request->get("balance_type",'azn');
        $result = Db::table("log_balances")
            ->where("user_id",$userId)->where("type",$balance_type);
            if($type==1){
                $result = $result->where("money","<",0);
            }elseif($type==2){
                $result = $result->where("money",">",0);
            }

        $result = $result->orderBy("created_at","DESC")->get()->toArray();
        array_map(function ($r){
            $message = explode("||",$r->message);
            $r->message = $message[0];

        },$result);

        return response()->json($result);
    }

    public function getLogPremium(Request $request)
    {
        $userId = Auth::user()->id;

        $data = [];
        $data["days"] = 0;
        $data["end"] = 'Yoxdur';
        $result = Db::table("premium_dates")
            ->where("user_id",$userId)
            ->orderBy("created_at","DESC")
            ->get()
            ->toArray();

        $data["result"] = $result;

        if(isset($result[0])){
            $now = new \DateTime(date("Y-m-d H:i:s"));
            $ref = new \DateTime($result[0]->end_date);
            $diff = $now->diff($ref);
            $data["day"] = $diff->m>0?$diff->m.' ay ':'';
            $data["day"] = $data["day"].$diff->d;
            $data["end"] = date("Y-m-d",strtotime($result[0]->end_date));

            //printf('%d days, %d hours, %d minutes', $diff->d, $diff->h, $diff->i);
        }

        return response()->json($data);
    }

    public function getPaymentData(Request $request)
    {
        $data = [];
        $userId = Auth::user()->id;
        $id  = intval($request->id);
        if($id>0){
            $data = DB::table('rel_user_products')
                ->where("id",$id)
                ->where("user_id",$userId)
                ->first();
        }
        return response()->json($data);
    }

    public function getUserCode()
    {
        $userId = Auth::user()->id;
        $hash = generateHashId($userId);
        return response()->json($hash);

    }
}
