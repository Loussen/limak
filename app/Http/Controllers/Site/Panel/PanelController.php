<?php

namespace App\Http\Controllers\Site\Panel;
use App\Currencies;
use App\Currency;
use App\Contact;
use App\Courier;
use App\Invoice;
use App\ModelLogs\LogBalance;
use App\ModelLogs\LogPaymentCouriers;
use App\ModelLogs\LogPaymentDeliveryInvoices;
use App\ModelUser\Client;
use App\ModelUser\PremiumDates;
use App\ModelUser\User;
use App\ModelUser\UserContact;
use App\Status;
use App\UserBalanceImpExpLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Lang;
use Carbon\Carbon;

class PanelController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexOld()
    {
        return view('front.new.panel.index');
    }

    public function premium(Request $request)
    {
        \Artisan::call('cache:clear');
        \Artisan::call('view:clear');
        $is_premium = Auth()->user()->is_premium;
        $is_corporate = Auth()->user()->corporate;
        $error_data = [
            "status" => 200,
            "message" => "",
            "class"   => "alert-danger"
        ];

        $currency = Currencies::where("name","usd-azn")->first();
        $month_1 = 9;
        $month_12 = 100;

        if($currency!=null){
            $month_1 = $month_1 * $currency->val;
            $month_12 = $month_12 * $currency->val;
        }

        if($is_premium == 0 and $is_corporate==0){
            $prices = [
                1 => $month_1,12=>$month_12
            ];
            if(isset($request->month)){
                $month = $request->get("month",0);
                if($month>0 and in_array($month,[1,12])){
                    $money = $prices[$month];
                    $balance = Auth()->user()->balance;
                    $date = date("Y-m-d H:i:s");
                    if($balance>=$money){
                        $user_id = Auth()->user()->id;
                        $user = User::find($user_id);
                        $user->is_premium = 1;
                        $new_balance = $balance-$money;
                        $user->balance = $new_balance;
                        if($user->save()){
                            LogBalance::create([
                                'user_id' => $user_id,
                                'old_balance' => $balance,
                                'new_balance' => $new_balance,
                                'money' => $money,
                                'created_at' => $date,
                                'updated_at' => $date,
                                'message' => 'Premium etmək üçün ödəniş',
                                'note' => 'Premium'
                            ]);
                            $endDate = date("Y-m-d H:i:s", strtotime( date( "Y-m-d", strtotime( date("Y-m-d") ) ) . "+$month month" ) );

                            $premium_dates = new PremiumDates();
                            $premium_dates->user_id = $user_id;
                            $premium_dates->month = $month;
                            $premium_dates->money = $prices[$month];
                            $premium_dates->end_date = $endDate;
                            $premium_dates->created_at = $date;
                            $premium_dates->updated_at = $date;
                            $premium_dates->status = 1;
                            $premium_dates->save();
                            $error_data["status"] = 200;
                            $error_data["message"] = 'Siz profilinizi uğurla premium hesaba dəyişdiz';
                            $error_data["class"] = 'alert-success';
                            $is_premium = 1;
                        }
                    }else{
                        $error_data["status"] = 500;
                        $error_data["message"] = 'AZN Balansınız kifayət qədər deyil. Azn balansınızı artırın və yenidən cəhd edin';
                    }

                }else{
                    $error_data["status"] = 5000;
                    $error_data["message"] = 'Premium ay seçimini düzgün qeyd edin';
                }
            }
        }else{
            if($is_corporate==1){
                $error_data["status"] = 500;
                $error_data["message"] = 'Bu istifadəçi korporativdir. Premium etmək mümkün deyil.';
            }else{
                $error_data["status"] = 500;
                $error_data["message"] = 'Bu istifadəçi artıq premiumdur';
            }

        }

          return response()->json($error_data);


    }

    public function index()
    {
        $error_message = '';
        $active = Auth()->user()->activated;


        if($active==1){
            return view('front.new.panel.index');

            if((Auth()->user()->id==663) and Auth()->user()->is_premium==0){
                return view('front.new.panel.premium');
            }else{
                return view('front.new.panel.index');
            }
        }else{

            $user_id = Auth()->user()->id;
            $user = User::find($user_id);
            $contact = UserContact::where("user_id",$user_id)->first();
            $phone = $contact->name;
            $step = 1;
            $activations = DB::table('user_activations')->select(DB::raw("count(id) as sms_count"),DB::raw("sum(count_try) as try_count"))->where("user_id",$user_id)->where("status",0)->first();


            if(isset($_POST["phone"])){
                if(intval($activations->sms_count)<=4 and intval($activations->try_count)<=10){

                    $new_phone = str_replace([' ', ')','('], '',$_POST["phone"]);

                    $issetPhone = DB::table("user_contacts as uc")->select("u.id")->leftJoin("users as u",'u.id','uc.user_id')->where("uc.name",$new_phone)->where("uc.user_id",'!=',$user_id)->where("u.activated",1)->first();
                    if($issetPhone!=null){
                        $error_message =  Lang::get('panel-errors.isset_number_error');
                    }else{
                        $contact->name = $new_phone;
                        $contact->save();
                        $phone = $new_phone;
                        $step = 2;
                        $code = rand(100000,999999);
                        $user->activation_code = $code;
                        $user->save();

                        DB::table("user_activations")->insert(
                            ['user_id' => $user->id, 'code' => $code,'count_try' =>0,'status' => 0,'phone' => $phone,'created_at' => date("Y-m-d H:i:s")]
                        );

                        $text = "Sizin tesdiq kodunuz: ".$code;
                        $data = (object) ['text' => $text];
                        sms($data, str_replace(['+', ' ', ')','('], '',$phone));
                    }


                }else{
                    $error_message = Lang::get('panel-errors.code_limit_error');
                    $user->is_blocked = 1;
                    $user->save();

                    DB::table("blocked_users")->insert(
                        ['user_id' => $user->id,'reason' => 'Nömrə təsdiq zamanı limiti keçdiyindən blok olunub','status' => 1 ,'created_at' => date("Y-m-d H:i:s")]
                    );

                }

            }elseif(isset($_POST["code"])){
                if($activations->sms_count<=4 and $activations->try_count<=10){

                    $post_code = htmlspecialchars($_POST["code"]);
                    $step = 2;

                    $activation = DB::table("user_activations")->where("user_id",$user_id)->where("status",0)->where("code",$user->activation_code)->where("phone",$phone)->orderBy("id","DESC")->first();
                    if($user->activation_code == $post_code and $activation!=null){
                        $user->activated = 1;
                        $user->save();

                        $text = "Sizin qeydiyyatiniz tesdiqlendi.Musteri kodunuz:".$user->uniqid;
                        $data = (object) ['text' => $text];
                        sms($data, str_replace(['+', ' ', ')','('], '',$phone));
                        $step = 3;

                        $new_count = $activation->count_try+1;
                        $actiovation_update = DB::table("user_activations")->where("id",$activation->id)->update(["count_try" => $new_count,'status' => 1]);
                        return redirect('success');
                    }elseif($activation!=null){
                        $error_message = Lang::get('panel-errors.code_error');
                        $new_count = $activation->count_try+1;
                        $actiovation_update = DB::table("user_activations")->where("id",$activation->id)->update(["count_try" => $new_count]);

                    }else{

                        $error_message = Lang::get("panel-errors.code_error_restart_page");
                    }
                }else{
                    $error_message = Lang::get("panel-errors.code_error_webmaster");
                    $user->is_blocked = 1;
                    $user->save();

                    DB::table("blocked_users")->insert(
                        ['user_id' => $user->id,'reason' => 'Nömrə təsdiq zamanı limiti keçdiyindən blok olunub','status' => 1 ,'created_at' => date("Y-m-d H:i:s")]
                    );
                }

            }

            return view('front.new.panel.index2',["phone" => $phone,'step' => $step,'error_message' => $error_message]);
        }

    }


    public function userData () {
        $data = Auth::user();
        $currency = Currency::find(1);
        $tryToUsd = 1/$currency->tl * $currency->usd;
        $data['last30days']=0;
        /*$last30days=DB::table('invoices as i')
            ->select(DB::raw('SUM(i.shipping_price) as shipping_price'), DB::raw('SUM(i.price) as price'), 'r.user_id')
            ->leftJoin('rel_user_products as r', 'r.id', '=', 'i.rel_user_product_id')
            ->leftJoin('products as p', 'i.product_id', '=', 'p.id')
            ->where('i.order_date', '>', $minus30)
            ->where('r.user_id', '=' , $data->id)
            ->groupBy('r.user_id')
            ->first();*/

        $last30days = DB::table('invoices')
            ->select(DB::raw("sum(shipping_price) as sh_price"),DB::raw("sum(price) as s_price"))
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
            $data['last30days_data'] = $last30days;
        }
        return $data;
    }

    public function getCorporateClients(Request $request)
    {
        if(Auth::user()->corporate==1){
            $data = [];
            $user_id = Auth::user()->id;
            $clientsRows = DB::table("clients")->select("*")->where("user_id",Auth::user()->id)->where("status_id",1)->orderBy("id","DESC")->get();

            $clients = [];

            $currency = Currency::find(1);
            $tryToUsd = 1/$currency->tl * $currency->usd;

            foreach ($clientsRows as $client){
                $clients[$client->id]["client_id"] = $client->id;
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

            return response()->json(['data' => $data, 'code' => 200]);
        }else{
            return response()->json(['data' => "Korporativ müştəri", 'code' => 500]);
        }
    }

    public function addClient(Request $request)
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
            $client->user_id = Auth::user()->id;
            $client->uniqid = Auth::user()->uniqid;
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
            return response()->json(['message' => (object) $error_message, 'code' => 200]);

        }else{
            return response()->json(['message' => (object) $error_message, 'code' => 422]);
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

    public function editClient(Request $request)
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
            })->where("id","!=",Auth::user()->id)->get();

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

        $client = Client::where("user_id",Auth::user()->id)->where("id",$client_id)->first();

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
            $error_message[] = Lang::get("panel-errors.added");
            return response()->json(['message' => (object) $error_message, 'code' => 200]);

        }else{
            return response()->json(['message' => (object) $error_message, 'code' => 422]);
        }
    }


    public function getCorporateClients2(Request $request)
    {
        if(Auth::user()->corporate==1){
            $user_id = Auth::user()->id;
            $data = DB::table("clients")->select("*")->where("status_id",1)->orderBy("id","DESC")->get();


            $currency = Currency::find(1);
            $tryToUsd = 1/$currency->tl * $currency->usd;

            $clients = [];



            $status = Status::where('label', '=', 'on_the_way')->where('type', '=', 'invoice')->first();
            $last30days = DB::table('invoices')
                ->select("clients.*",DB::raw("sum(invoices.shipping_price) as sh_price"),"invoices.client_id",DB::raw("sum(invoices.price) as s_price"))
                ->leftJoin("clients","clients.id","invoices.client_id")
                ->whereIn('invoices.id', function($query)
                {
                    $status = Status::where('label', '=', 'on_the_way')->where('type', '=', 'invoice')->first();
                    $query->select('invoice_id')
                        ->from('invoice_dates')
                        ->where('action_date', '>', Carbon::now()->subDays(30)->toDateTimeString())
                        ->where("status_id","=",$status->sid);
                })
                ->where('invoices.user_id','=',$user_id)
                //->where('invoices.corporate','=',1)
                //->where('invoices.client_id','!=',0)
                ->where('invoices.status_id','!=',777)
                ->where('invoices.status_id','>',$status->sid)
                ->groupBy('invoices.client_id')
                ->get();

            $last30days_2 = DB::table('invoices')
                ->select("clients.*",DB::raw("sum(invoices.shipping_price) as sh_price"),"invoices.client_id",DB::raw("sum(invoices.price) as s_price"))
                ->leftJoin("clients","clients.id","invoices.client_id")
                ->whereIn('invoices.id', function($query)
                {
                    $status = Status::where('label', '=', 'on_the_way')->where('type', '=', 'invoice')->first();
                    $query->select('invoice_id')
                        ->from('invoice_dates')
                        ->where('action_date', '>', Carbon::now()->subDays(30)->toDateTimeString())
                        ->where("status_id","=",$status->sid);
                })
                ->where('invoices.user_id','=',$user_id)
                //->where('invoices.corporate','=',1)
                //->where('invoices.client_id','!=',0)
                ->where('invoices.country_id','!=',1)
                ->where('invoices.status_id','!=',777)
                ->where('invoices.status_id','>',$status->sid)
                ->groupBy('invoices.client_id')
                ->groupBy('invoices.country_id')
                ->get();
            //var_dump($last30days); exit;

            if($last30days){
                foreach ($last30days as $key=>$row){
                    $client[$row->id]["client_id"] = $row->id;
                    $client[$row->id]["user_id"] = $row->user_id;
                    $client[$row->id]["uniqid"] = $row->uniqid;
                    $client[$row->id]["name"] = $row->name;
                    $client[$row->id]["surname"] = $row->surname;
                    $client[$row->id]["serial_number"] = $row->serial_number;
                    $client[$row->id]["pin"] = $row->pin;
                    $client[$row->id]["email"] = $row->email;
                    $client[$row->id]["phone"] = $row->phone;
                    $client[$row->id]["address"] = $row->address;
                    $client[$row->id]["gender"] = $row->gender;
                    $client[$row->id]["price"] = $row->gender;

                    $last30day = number_format($last30days->price / $tryToUsd + $last30days->shipping_price, 2);
                }
            }


            if($last30days){
                foreach ($last30days as $key=>$row){
                    $client[$row->id]["client_id"] = $row->id;
                    $client[$row->id]["user_id"] = $row->user_id;
                    $client[$row->id]["uniqid"] = $row->uniqid;
                    $client[$row->id]["name"] = $row->name;
                    $client[$row->id]["surname"] = $row->surname;
                    $client[$row->id]["serial_number"] = $row->serial_number;
                    $client[$row->id]["pin"] = $row->pin;
                    $client[$row->id]["email"] = $row->email;
                    $client[$row->id]["phone"] = $row->phone;
                    $client[$row->id]["address"] = $row->address;
                    $client[$row->id]["gender"] = $row->gender;
                    $client[$row->id]["price"] = $row->gender;

                    if(isset($client[$row->id]["last30day"])){

                    }
                    $last30day = number_format($last30days->price / $tryToUsd + $last30days->shipping_price, 2);
                }
            }

            if($last30days!=null){
                $data['last30days']=number_format($last30days->price / $tryToUsd + $last30days->shipping_price, 2);
                $data['last30days_data'] = $last30days;
            }


            return response()->json(['data' => $data, 'code' => 200]);
        }else{
            return response()->json(['data' => "Korporativ müştəri", 'code' => 500]);
        }
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
        $price = $invoiceCash->delivery_price;

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


    public function payOnlyCourier(Request $request) {
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
                $invoice_id =
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

    public function getProfit() {
        $data = UserBalanceImpExpLog::where('user_id', '=', Auth::user()->id)
            ->where('type', '=', getBalanceLogType('profit'))
            ->orderBy('id', 'desc')
            ->paginate(10);
        return response()->json(["data" => $data]);
    }

    public function getUserExpense() {
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

            ->orderBy('log_payment_delivery_invoices.created_at', 'desc')
            ->paginate(10);
        return response()->json($data);
    }
}
