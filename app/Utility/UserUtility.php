<?php

namespace App\Utility;

use App\Currency;
use App\ModelUser\Client;
use App\Status;
use App\Libraries\Upload\Uploader;
use App\ModelCountry\Regions;
use App\ModelLogs\LogBalance;
use App\ModelProduct\Extras;
use App\ModelProduct\Product;
use App\Models\Invoice;
use App\Models\Packages;
use App\Models\RelUserProduct;
use App\ModelUser\User;
use App\ModelUser\UserContact;
use App\UserPromoCodes;
use Illuminate\Support\Facades\DB;
use App\Models\Currencies;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Illuminate\Support\Facades\Lang;



class UserUtility
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getUserInfo()
    {
        $user = $this->user;
        $contact = DB::table("user_contacts")->select("name as phone")->where("user_id",$user->id)->first();
        $user->birthdate = date("Y-m-d",strtotime($user->birthdate));
        $user->phone =  $contact->phone;
        return $user;

    }


    public function last30Days(){

        $last30day_payment =  userLast30($this->user->id);
       /* $last30day_payment_row = DB::table('invoices')
            ->select(DB::raw("sum(shipping_price) as sh_price"),DB::raw("sum(price) as s_price"))
            ->whereIn('id', function($query)
            {
                $status = Status::where('label', '=', 'on_the_way')->where('type', '=', 'invoice')->first();
                $query->select('invoice_id')
                    ->from('invoice_dates')
                    ->where('action_date', '>', Carbon::now()->subDays(30)->toDateTimeString())
                    ->where("status_id","=",$status->sid)
                ;
            })
            ->where('invoices.user_id','=',$this->user->id)
            ->first();
        $currency = Currencies::where("name","=","try-usd")->first();
        $last30day_payment = 0;
        if($last30day_payment_row!=null){
            $last30day_payment = $last30day_payment_row->sh_price;
            if($currency!=null){
                $last30day_payment += $currency->val*$last30day_payment_row->s_price;
            }
        }*/

        return $last30day_payment;

    }

    public function lastBalanceOperation(){
        $result = DB::table("log_balances")
            ->where("user_id",$this->user->id)
            ->orderBy("created_at","DESC")->first();

        if($result!=null){
            $message = explode("||",$result->message);
            $result->message = $message[0];
        }

        return $result;
    }

    public function notifications(){
        $result = DB::table("notifications")
            ->where("user_id",$this->user->id)
            ->orderBy("created_at","DESC")->get();

        return $result;
    }

    public function clients()
    {
        $data = [];
        $user_id = $this->user->id;
        $clientsRows = DB::table("clients")->select("*")->where("user_id",$user_id)->where("status_id",1)->orderBy("id","DESC")->get();

        $clients = [];

        $currency = Currency::find(1);
        $tryToUsd = 1/$currency->tl * $currency->usd;

        foreach ($clientsRows as $client){
            $clients[$client->id]["id"] = $client->id;
            $clients[$client->id]["client_id"] = "1".str_pad($client->id, 6, "0", STR_PAD_LEFT);;
            $clients[$client->id]["user_id"] = $client->user_id;
            $clients[$client->id]["uniqid"] = $client->uniqid;
            $clients[$client->id]["name"] = $client->name;
            $clients[$client->id]["surname"] = $client->surname;
            $clients[$client->id]["serial_number"] = $client->serial_number;
            $clients[$client->id]["pin"] = $client->pin;
            $clients[$client->id]["email"] = $client->email;
            $clients[$client->id]["phone"] = $client->phone;
            $clients[$client->id]["address"] = $client->address;
            $clients[$client->id]["gender"] = $client->gender;
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
            ->where('invoices.user_id','=',$user_id)
            ->where('invoices.corporate','=',1)
            ->where('invoices.client_id','!=',0)
            ->where('invoices.status_id','!=',777)
            ->where('invoices.status_id','>',$status->sid)
            ->get();

        foreach ($last30days as $row){
            if($row->client_id>0 and isset($clients[$row->client_id])){
                if($row->country_id==2){
                    $clients[$row->client_id]["last30"] =  number_format(((float)$clients[$row->client_id]["last30"] + (float)$row->price + (float)$row->shipping_price), 2);
                }elseif($row->country_id==1){
                    $clients[$row->client_id]["last30"] =  number_format(((float)$clients[$row->client_id]["last30"] + (float)($row->price / $tryToUsd) + (float)$row->shipping_price), 2);
                }
            }
        }

        $data = $clients;
        $array = [];
        foreach ($data as $c){
            $array[] = $c;
        }
        return $array;
    }

    public function balanceLogs(){
        $result = DB::table("log_balances")
            ->where("user_id",$this->user->id)
            ->orderBy("created_at","DESC")->paginate(15)->toArray();

        array_map(function ($r){
            $message = explode("||",$r->message);
            $r->message = $message[0];

        },$result);

        return $result;
    }

    public function basket(){
        $data = DB::table("products as p")
            ->leftJoin('countries as c', 'p.country_id', '=', 'c.id')
            ->leftJoin('extras as e', 'e.id', '=', 'p.extras_id')
            ->select('e.link', 'p.created_at', 'c.name', 'p.quantity', 'p.price' , 'p.id' )
            ->where("p.user_id",$this->user->id)
            ->where('p.is_paid', '=', 0)
            ->where('p.status_id', '=', 1)
            ->orderBy("p.created_at","DESC")
            ->paginate(15);
        return $data;
    }

    public function balance(){
        $data =[];
        $data['logs'] = LogBalance::where('user_id', '=', $this->user->id)->where("type","azn")->orderBy('id', 'desc')->get();
        $data['balance'] = $this->user->balance;
        return $data;
    }

    public function balanceTry(){
        $data =[];
        $data['logs'] = LogBalance::where('user_id', '=', $this->user->id)->where("type","try")->orderBy('id', 'desc')->get();
        $data['balance'] = $this->user->balance_try;
        return $data;
    }

    public function statuses($country_id,$lang){
        $userId = $this->user->id;

        if($lang == 'az'){
            $name = 'name';
        }else{
            $name = 'name_'.$lang;
        }

        $statuses = [];
        $statusRows = DB::table("statuses")->select($name." as name","label","sid")->where("type","invoice")->where("active",1)->orderBy("sira","ASC")->get();
        foreach ($statusRows as $status){
            $nameStatus = mb_strtoupper($status->name,"UTF-8");
            $statuses[] = ["id" => $status->sid, "label" => $status->label,"name" => $nameStatus];//,"count" => 0
        }


       /* $data1 = DB::table('invoices as i')
            ->select('i.status_id', DB::raw('count(i.id) as  count'))
            ->where('i.user_id', '=', $userId)
            ->whereBetween('i.status_id',[1,8])
            ->where('i.active', '=', 1)
            ->where('i.country_id', '=', $country_id)
            ->groupBy("i.status_id")
            ->get();


        $all = 0;

        foreach($data1 as $data){
            if(isset($statuses[$data->status_id])){
                $statuses[][$data->status_id]["count"] = $data->count;
            }
            $all = $all + $data->count;
        }

        $statuses["all"] = $all;*/
        return response()->json([
            'data' => $statuses
        ]);
    }

    public function track($invoice_id,$lang)
    {
        $result = [];
        $data = DB::table("invoice_dates as ind")
            ->select("i.order_date","ind.status_id","ind.action_date")
            ->leftJoin("invoices as i","i.id","ind.invoice_id")
            ->where("ind.invoice_id",$invoice_id)
            ->where("i.user_id",$this->user->id)
            ->get();

        if($data!=null){
            $i = 1;
            foreach ($data as $item){
                if($i==1){
                    $result["order_date"] = $item->order_date;
                }
                $result["dates"][] =  ["status_id" => $item->status_id,"action_date" => $item->action_date];
                $i++;
            }

        }

        return $result;
    }

    public function orders($country_id,$status_id,$lang)
    {

        LaravelLocalization::setLocale($lang);

        $suf = '';
        if($lang == 'ru'){
            $suf = '_ru';
        }

        $regions = DB::table("regions")->where("active",1)->pluck("name".$suf,"id");
        $countries = DB::table("countries")->where("status",1)->pluck("name".$suf,"id");
        $statuses = DB::table("statuses")->select("id","name".$suf,"label","sid")->where("type","invoice")->pluck("name".$suf,"sid");

        $statuses["0"] = __('panel-errors.all');
        $data = DB::table("invoices as i")
            ->join('products as p', 'p.id', '=', 'i.product_id')
            ->select('i.id','i.status_id', 'i.country_id','i.region_id','i.price','i.is_paid' ,'i.created_at' , 'i.shipping_price', 'i.order_tracking_number', 'i.weight', 'p.product_type_name' , 'p.shop_name' , 'p.quantity' )
            ->where("i.user_id",$this->user->id)
            ->where("i.country_id",$country_id)
            ->where("i.status_id",'!=',777);

        if($status_id>=1){
            $data = $data->where('i.status_id', '=', $status_id);
        }

        $data = $data->orderBy("i.created_at","DESC")->paginate(15);
        foreach($data as $item){
            $result["invoices"][] =  [
                'id' => $item->id,
                'country' => $countries[$item->country_id],
                'region' => $regions[$item->region_id],
                'price' => $item->price,
                'status_name' => $statuses[$item->status_id],
                'created_at' => $item->created_at,
                'shipping_price' => $item->shipping_price,
                'order_tracking_number' => $item->order_tracking_number,
                'weight' => $item->weight,
                'product_type_name' => $item->product_type_name,
                'shop_name' => $item->shop_name,
                'quantity' => $item->quantity,
                'is_paid' => $item->is_paid,
            ];
        }

       $json = json_encode($data);
       $json_array = json_decode($json,true);
       $result["current_page"] = $json_array["current_page"];
       $result["from"] =$json_array["current_page"];
       $result["last_page"] = $json_array["last_page"];
       $result["per_page"] = $json_array["per_page"];
       $result["to"] = $json_array["to"];
       $result["total"] = $json_array["total"];

        return $result;
    }

    public function ordersOld($status_id,$lang){
        $data = DB::table("invoices as i")
            ->join('products as p', 'p.id', '=', 'i.product_id')
            ->join('countries as c', 'c.id', '=', 'i.country_id')
            ->join('regions as r', 'r.id', '=', 'i.region_id')
            ->join('statuses as s', 'c.id', '=', 'i.status_id')
            ->select('i.id', 'c.name as country', 'i.price', 's.name as status_name', 'i.created_at' , 'i.shipping_price', 'i.order_tracking_number', 'i.weight', 'p.product_type_name' , 'p.shop_name' , 'p.quantity' )
            ->where("i.user_id",$this->user->id)
            ->where('i.status_id', '=', 1)
            ->orderBy("i.created_at","DESC")
            ->paginate(50);

        $result = [
            'hamisi' => [],
            'Sifariş verildi' => [],
            'xaricdeki anbardadir' => [],
            'yoldadir' => [],
            'azerbaycandaki anbardadir' => [],
            'Kuryerdədir' => [],
            'Gömrükdə' => [],
            'Tamamlanmışdır' => [],
        ];
        foreach($data as $item){
              $result['hamisi'][] = $result[$item->status_name][] = [
                'id' => $item->id,
                'country' => $item->country,
                'price' => $item->price,
                'status_name' => $item->status_name,
                'created_at' => $item->created_at,
                'shipping_price' => $item->shipping_price,
                'order_tracking_number' => $item->order_tracking_number,
                'weight' => $item->weight,
                'product_type_name' => $item->product_type_name,
                'shop_name' => $item->shop_name,
                'quantity' => $item->quantity,
            ];
        }
        return $result;
    }

    // Corporate clients begin
    public function addClient($request)
    {
        $error = false;
        $error_message = [];
        $name = $request->get("name",null);
        $surname = $request->get("surname",null);
        $serial_number = $request->get("serial_number",null);
        $pin = $request->get("pin",null);
        $phone = $request->get("phone",null);
        $address = $request->get("address",null);
        $email = $request->get("email",null);

        if($name == null){
            $error = true;
            $error_message[] = Lang::get("panel-errors.client_firstname").' '.Lang::get("validation.required");
        }

        if($surname == null){
            $error = true;
            $error_message[] = Lang::get("panel-errors.client_surname").' '.Lang::get("validation.required");
        }

        if($serial_number == null){
            $error = true;
            $error_message[] = Lang::get("panel-errors.client_serial").' '.Lang::get("validation.required");
        }

        if($pin == null){
            $error = true;
            $error_message[] = Lang::get("panel-errors.client_pin").' '.Lang::get("validation.required");
        }

        if($phone == null){
            $error = true;
            $error_message[] = Lang::get("panel-errors.client_phone").' '.Lang::get("validation.required");
        }

        if($email == null){
            $error = true;
            $error_message[] = Lang::get("panel-errors.client_email").' '.Lang::get("validation.required");
        }

        if($address == null){
            $error = true;
            $error_message[] = Lang::get("panel-errors.client_address").' '.Lang::get("validation.required");
        }

        $rows_users = DB::table("users")->select("email","serial_number","pin")
            ->where(function($q) use ($email,$serial_number,$pin) {
                $q->where("email",$email)
                    ->orWhere("serial_number",$serial_number)
                    ->orWhere("pin",$pin);
            })->get();

        $check_array = $this->checkUser($rows_users,$serial_number,$pin,$email,$error,$error_message,$type='users');
        $error = $check_array["error"];
        $error_message = $check_array["error_message"];

        $rows_clients = DB::table("clients")->select("email","serial_number","pin")
            ->where(function($q) use ($email,$serial_number,$pin) {
                $q->where("email",$email)
                    ->orWhere("serial_number",$serial_number)
                    ->orWhere("pin",$pin);
            })->get();

        $check_array = $this->checkUser($rows_clients,$serial_number,$pin,$email,$error,$error_message,$type='clients');
        $error = $check_array["error"];
        $error_message = $check_array["error_message"];

        if($error==false){
            $client = new Client();
            $client->user_id = $this->user->id;
            $client->uniqid = $this->user->uniqid;
            $client->name = $name;
            $client->surname = $surname;
            $client->serial_number = $serial_number;
            $client->pin = $pin;
            $client->email = $email;
            $client->phone = $phone;
            $client->address = $address;
            $client->gender = 1;
            $client->status_id = 1;
            $client->save();
            $error_message[] = Lang::get("panel-errors.added");
            return ['message' => $error_message, 'code' => 200];

        }else{
            return ['message' => $error_message, 'code' => 422];
        }

    }

    private function checkUser($rows,$serial_number,$pin,$email,$error,$error_message,$type){
        $i = 1;
        if($type=='users'){
            $error_heading = Lang::get("panel-errors.user_base");
        }else{
            $error_heading = Lang::get("panel-errors.client_base");
        }

        foreach ($rows as $row){
            if($row->serial_number==$serial_number){
                $error = true;
                $error_message[] = $error_heading.Lang::get("panel-errors.serial_number_isset");
            }

            if($row->pin==$pin){
                $error = true;
                $error_message[] = $error_heading.Lang::get("panel-errors.pin_isset");
            }

            if($row->email==$email){
                $error = true;
                $error_message[] = $error_heading.Lang::get("panel-errors.email_isset");
            }
            $i++;
        }

        return ["error"=> $error,"error_message" => $error_message];
    }

    public function editClient($request)
    {
        $error = false;
        $error_message = [];
        $name = $request->get("name",null);
        $client_id = $request->get("client_id",null);
        $surname = $request->get("surname",null);
        $serial_number = $request->get("serial_number",null);
        $pin = $request->get("pin",null);
        $phone = $request->get("phone",null);
        $address = $request->get("address",null);
        $email = $request->get("email",null);


        if($client_id == null){
            $error = true;
            $error_message[] = Lang::get("panel-errors.code_error_restart_page");
        }


        if($name == null){
            $error = true;
            $error_message[] = Lang::get("panel-errors.client_firstname").' '.Lang::get("validation.required");
        }

        if($surname == null){
            $error = true;
            $error_message[] = Lang::get("panel-errors.client_surname").' '.Lang::get("validation.required");
        }

        if($serial_number == null){
            $error = true;
            $error_message[] = Lang::get("panel-errors.client_serial").' '.Lang::get("validation.required");
        }

        if($pin == null){
            $error = true;
            $error_message[] = Lang::get("panel-errors.client_pin").' '.Lang::get("validation.required");
        }

        if($phone == null){
            $error = true;
            $error_message[] = Lang::get("panel-errors.client_phone").' '.Lang::get("validation.required");
        }

        if($email == null){
            $error = true;
            $error_message[] = Lang::get("panel-errors.client_email").' '.Lang::get("validation.required");
        }

        if($address == null){
            $error = true;
            $error_message[] = Lang::get("panel-errors.client_address").' '.Lang::get("validation.required");
        }



        $rows_users = DB::table("users")->select("email","serial_number","pin")
            ->where(function($q) use ($email,$serial_number,$pin) {
                $q->where("email",$email)
                    ->orWhere("serial_number",$serial_number)
                    ->orWhere("pin",$pin);
            })->where("id","!=",$this->user->id)->get();

        $check_array = $this->checkUser($rows_users,$serial_number,$pin,$email,$error,$error_message,$type='users');
        $error = $check_array["error"];
        $error_message = $check_array["error_message"];

        $rows_clients = DB::table("clients")->select("email","serial_number","pin")
            ->where(function($q) use ($email,$serial_number,$pin) {
                $q->where("email",$email)
                    ->orWhere("serial_number",$serial_number)
                    ->orWhere("pin",$pin);
            })->where("id","!=",$client_id)->get();

        $check_array = $this->checkUser($rows_clients,$serial_number,$pin,$email,$error,$error_message,$type='clients');
        $error = $check_array["error"];
        $error_message = $check_array["error_message"];

        $client = Client::where("user_id",$this->user->id)->where("id",$client_id)->first();

        if($client==null){

            $error = true;
            $error_message[] = Lang::get("panel-errors.code_error_restart_page");
        }

        if($error==false){
            $client->name = $name;
            $client->surname = $surname;
            $client->serial_number = $serial_number;
            $client->pin = $pin;
            $client->email = $email;
            $client->phone = $phone;
            $client->address = $address;
            $client->gender = 1;
            $client->status_id = 1;
            $client->save();
            $error_message[] = Lang::get("panel-errors.updated");
            return ['message' => $error_message, 'code' => 200];

        }else{
            return ['message' =>  $error_message, 'code' => 422];
        }
    }
    // Corporate clients end

    public function addInvoice($request)
    {

        $validator =  Validator::make($request->all(), [
            "shop_name" => 'required',
            "price" => 'required',
            "product_name" => 'required',
            "quantity" => 'required',
            "tracking" => 'required',
            "order_date" => 'required',
            "region_id" => 'required',
            "country_id" => 'required',
        ]);
        if ($validator->fails()) {
            return ['success'=> false,'message'=>$validator->errors()];
        }

        $lastPurchase_no = Invoice::select('id')->orderBy('id', 'desc')->first();

        $lastPurchase_no = $lastPurchase_no->id;
        $package= new Packages();
        $package->status = 1;
        $package->save();

        $invoiceAddStatus = getStatusByLabel('invoice_added', 'transaction');

        $status = getStatusByLabel('with_invoice', 'product');
        $statusInvoice = getStatusByLabel('waiting', 'invoice');

        $newRelUserProduct = new RelUserProduct();
        $newRelUserProduct->status_id = $invoiceAddStatus;
        $newRelUserProduct->user_id = $this->user->id;
        $newRelUserProduct->is_paid = 1;
        $newRelUserProduct->is_ordered = 1;
        $newRelUserProduct->save();
        //
        $newProduct = new Product();
        $newProduct->product_type_id = $request->get('product_type_id', 1);
        $newProduct->product_type_name = $request->product_name;
        $newProduct->rel_user_product_id = $newRelUserProduct->id;
        $newProduct->price = $request->price;
        $newProduct->region_id = $request->get('region_id', 1);
        $newProduct->country_id = $request->country_id;
        $newProduct->user_id = $this->user->id;
        $newProduct->corporate = $this->user->corporate;
        $newProduct->client_id = $request->get('client_id', 0);
        $newProduct->quantity = $request->quantity;
        $newProduct->shop_name = $request->shop_name;
        $newProduct->description = $request->description;
        $newProduct->is_ordered = 1;
        $newProduct->is_paid = 1;
        $newProduct->status_id = $status;
        $newProduct->save();

        if($request->file){
            $file = Uploader::upload($request['file'], 'public/invoice/', 'invoice', false, true);
            $file = '/storage/invoice/'.$file;
        }

        $newInvoice = new Invoice();
        $newInvoice->user_id = $this->user->id;
        $newInvoice->corporate = $this->user->corporate;
        $newInvoice->client_id = $request->get('client_id', 0);
        $newInvoice->price = $request->price;
        $newInvoice->product_id = $newProduct->id;
        $newInvoice->region_id = $request->get('region_id', 1);
        $newInvoice->country_id = $request->get("country_id",1);
        $newInvoice->rel_user_product_id = $newRelUserProduct->id;
        $newInvoice->status_id = $statusInvoice;
        $newInvoice->purchase_no = str_pad($lastPurchase_no + 1, 9, 0, STR_PAD_LEFT);
        $newInvoice->order_tracking_number = $request->tracking;
        $newInvoice->order_date = $request->order_date;
        if($request->file){
            $newInvoice->file = $file;
        }
        $newInvoice->package_id = $package->id;

        if ($newInvoice->save()) {
            return ['status' => 200, 'message' => 'Bəyənnamə müvəffəqiyyətlə yükləndi'];
        }

        return ['status' => 504, 'message' => 'Bəyənnamə yüklənmadi'];
    }

    public function clientAddresses($client_id){
        $client = Client::where("id",$client_id)->where("user_id",$this->user->id)->first();
        if($client==null){
            return ['status' => false, 'message' => 'Müştəri tapılmadı'];
        }else{
            return  [
                "status" => true,
                'tr' => [
                    'company' => 'LİMAK İTHALAT VE İHRACAT LİMİTED şirketi',
                    'name' => 'LİMAK İTHALAT VE İHRACAT LİMİTED şirketi',
                    'addressTitle' => 'LİMAK',
                    'addressLine1' => 'Halkalı Merkez Mahellesi1. Tuna caddesi. Üzümlü SK. 5/7 kod:  '. "1".str_pad($client->id, 6, "0", STR_PAD_LEFT).' '.$client->name.' '.$client->surname,
                    'addressLine2' => '',
                    'city'  => 'Istanbul',
                    'region'    => 'Küçükçekmece',
                    'district' => 'Halkalı',
                    'tax_center' => 'Halkalı',
                    'zip' => '34303',
                    'country' => 'Türkiye',
                    'passport' => '35650276048',
                    'mobile' => '5323575535',
                    'taxNum' => '6081089593'
                ],
                'usa' => [
                    'company' => '',
                    'name' => 'AZ'."1".str_pad($client->id, 6, "0", STR_PAD_LEFT)." ".$client->name.' '.$client->surname,
                    'addressTitle' => '',
                    'addressLine1' => '1200 Interchange Blvd',
                    'addressLine2' => 'Suite#AZ'."1".str_pad($client->id, 6, "0", STR_PAD_LEFT),
                    'city'  => 'Newark',
                    'region'    => 'DE',
                    'district' => '',
                    'tax_center' => '',
                    'zip' => '19711',
                    'country' => 'United States',
                    'passport' => '',
                    'mobile' => '800-4315119',
                    'taxNum' => ''
                ]
            ];
        }

    }

    public function addresses(){
        return  [
            'tr' => [
                'company' => 'LİMAK İTHALAT VE İHRACAT LİMİTED şirketi',
                'name' => 'LİMAK İTHALAT VE İHRACAT LİMİTED şirketi',
                'addressTitle' => 'LİMAK',
                'addressLine1' => 'Halkalı Merkez Mahellesi1. Tuna caddesi. Üzümlü SK. 5/7 kod:  '. $this->user->id.' '.$this->user->name.' '.$this->user->surname,
                'addressLine2' => '',
                'city'  => 'Istanbul',
                'region'    => 'Küçükçekmece',
                'district' => 'Halkalı',
                'tax_center' => 'Halkalı',
                'zip' => '34303',
                'country' => 'Türkiye',
                'passport' => '35650276048',
                'mobile' => '5323575535',
                'taxNum' => '6081089593'
            ],
            'usa' => [
                'company' => '',
                'name' => 'AZ'.$this->user->uniqid." ".$this->user->name.' '.$this->user->surname,
                'addressTitle' => '',
                'addressLine1' => '1200 Interchange Blvd',
                'addressLine2' => 'Suite#AZ'.$this->user->uniqid,
                'city'  => 'Newark',
                'region'    => 'DE',
                'district' => '',
                'tax_center' => '',
                'zip' => '19711',
                'country' => 'United States',
                'passport' => '',
                'mobile' => '800-4315119',
                'taxNum' => ''
            ]
        ];
    }

    public function order($request){
        $uniqueId = time().$this->user->id;
            if (!empty($request->data) && count($request->data) > 0) {
            $relUserProduct = new RelUserProduct();
            $relUserProduct->user_id = $this->user->id;
            $relUserProduct->status_id = getStatusByLabel('waiting', 'transaction');
            $relUserProduct->transaction_id = $uniqueId;
            $relUserProduct->is_paid = '0';
            $relUserProduct->price = $request->total;
            $relUserProduct->delivery_type = $request->deliveryType;
            $relUserProduct->save();
            $promo_code = $request->promo_code;
            $total = 0;
            foreach($request->data as $index => $order) {

                $order = (object)$order;
                $extra = new Extras();
                $product = new Product();
                $extra->country_id = $request->country;
                $extra->link = $order->link;
                $extra->cargo_price = $order->cargo;
                $extra->save();

                $product->rel_user_product_id = $relUserProduct->id;
                $product->extras_id = $extra->id;
                $product->price = $order->cost*$order->quantity;
                $product->user_id = $this->user->id;
                $product->corporate = $this->user->corporate;
                $product->client_id = $request->get('client_id', 0);
                $product->region_id = $request->region_id;
                $product->quantity = $order->quantity;
                $product->description = !empty($order->note) ? $order->note: '-';
                $product->status_id = 1;//getStatusByLabel('waiting', 'transaction');
                $product->promo_code = $promo_code;
                $product->save();

                $total = $total + $extra->cargo_price + $product->price;

            }

            $relUserProduct->price = 1.05*$total;
            $relUserProduct->save();


            if($promo_code){
                UserPromoCodes::create([
                    "user_id" => $this->user->id,
                    "campaign_id" => 1,
                    "promo_code" => $promo_code,
                    "invoice_id" => 0,
                    "status" => 0

                ]);
            }
            return ['success' => true, 'message' => 'Sifaris muveffeqiyetle verildi', 'transaction_id' => $uniqueId];
        } else {
            return ['success' => false, 'message' => 'Məlumatlar tam deyildir'];
        }
    }




    public function orderBalance($request)
    {
        $uniqueId = time().$this->user->id;

        $user = User::find($this->user->id);

        if (!empty($request->data) && count($request->data) > 0) {
            $balance_try =$user->balance_try;
            $try = $request->total;
            if($balance_try>=$try and $try>1){
                $relUserProduct = new RelUserProduct();
                $relUserProduct->user_id = $user->id;
                $relUserProduct->status_id = getStatusByLabel('waiting', 'transaction');
                $relUserProduct->transaction_id = $uniqueId;
                $relUserProduct->is_paid = 1;
                $relUserProduct->price = $request->total;
                $relUserProduct->delivery_type = $request->deliveryType;
                $relUserProduct->response_payment = 'Payed with balance';
                $relUserProduct->save();
                $promo_code = $request->promo_code;
                foreach($request->data as $index => $order) {
                    $order = (object)$order;
                    $extra = new Extras();
                    $product = new Product();
                    /**
                     * Feed Extra
                     */
                    $extra->country_id = $request->country; // TODO: make it dynamic
                    $extra->link = $order->link;
                    $extra->cargo_price = $order->cargo;
                    $extra->save();

                    //$product->product_type_name = $order->type;
                    $product->rel_user_product_id = $relUserProduct->id;
                    $product->extras_id = $extra->id;
                    $product->price = $order->cost*$order->quantity;
                    $product->user_id = $user->id;
                    $product->quantity = $order->quantity;
                    $product->region_id = $request->region_id;
                    $product->description = !empty($order->note) ? $order->note: '-';
                    $product->status_id = 1;//getStatusByLabel('waiting', 'transaction');
                    $product->promo_code = $promo_code;
                    $product->not_basket = 1;
                    $product->is_paid = 1;
                    $product->save();
                }
                /*$pay = new PayTrController();
                $token = $pay->payByTr($request->ytl, $request->data, $uniqueId);*/
                if($promo_code){
                    UserPromoCodes::create([
                        "user_id" => $this->user->id,
                        "campaign_id" => 1,
                        "promo_code" => $promo_code,
                        "invoice_id" => 0,
                        "status" => 0

                    ]);
                }

                $user->balance_try = $balance_try - $try;
                $user->save();
                $message = 'Balansdan sifariş';
                $amount = $try - 2*$try;
                $user->save();
                LogBalance::create([
                    'user_id' => $user->id,
                    'old_balance' => $balance_try,
                    'new_balance' => $user->balance_try,
                    'money' => $amount,
                    'message' => $message,
                    'type' => 'try',
                ]);

                return ["data" => 'Uğurlu ödəniş',"status" =>  200];

            }else{
                if($balance_try<$try){
                    return ["data" => 'TL balansınızda yetərli məbləğ yoxdur. Balansınızı artırıb ödəniş edə bilərsiniz.', "status" => 500];
                }else{
                    return ["data" => 'Məbləğ düzgün deyil', "status" => 500];
                }
            }
        } else {
            return ["data" => 'Məlumatlar tam deyildir', "status" => 500];
        }

    }



    public function forCourier(){
        $result = [];
        $result['invoices'] = DB::table("invoices as i")->select('i.id', 'p.product_type_name as name')->join('products as p', 'p.id', '=', 'i.product_id')->where('i.user_id', '=', $this->user->id)->where('i.active', 1)->where('i.status_id', 4)->get();
        //$result['regions'] = DB::table("regions as r")->select('r.id', 'r.name')->get();
        $result['regions'] = [
            ["name" => "Bakı","price" =>4, "id"=>0],
            //["name" => "Sumqayıt","price" =>8, "id"=>1],
            ["name" => "Xırdalan","price" =>6, "id"=>2],
            ["name" => "Bakı kəndləri","price" =>8, "id"=>3],
        ];
        return $result;
    }

    public function getCourierData()
    {
        $userId = $this->user->id;
        $balance = $this->user->balance;


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

}
