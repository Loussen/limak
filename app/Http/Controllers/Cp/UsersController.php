<?php

namespace App\Http\Controllers\Cp;
use App\Currencies;
use App\Currency;
use App\ModelLogs\LogBalance;
use App\Email;
use App\Http\Controllers\Controller;
use App\Invoice;
use App\ModelCountry\Country;
use App\ModelProduct\Product;
use App\ModelUser\Client;
use App\Sms;
use App\Status;
use App\UserActivations;
use App\UserPromoCodes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use App\ModelUser\User;
use Carbon\Carbon;

class UsersController extends BaseController
{
    public function index()
    {
        $name = Input::get('name');
        $uniqid = Input::get('uniqid');
        $surname = Input::get('surname');
        $email = Input::get('email');
        $pin = Input::get('pin');
        $balance = Input::get('balance');
        $serial_number = Input::get('serial_number');
        $address = Input::get('address');
        $birthdate = Input::get('birthdate');
        $type = Input::get('type','all');

        $users = User::with('userContacts')->orderBy('id', 'desc');

        if(!empty($name)) {
            $users = $users->where('name', 'like', '%'.$name.'%');
        }
        if(!empty($surname)) {
            $users = $users->where('surname', 'like', '%'.$surname.'%');
        }
        if(!empty($email)) {
            $users = $users->where('email', '=', $email);
        }
        if(!empty($uniqid)) {
            $users = $users->where('uniqid', '=', $uniqid);
        }
        if(!empty($pin)) {
            $users = $users->where('pin', '=', $pin);
        }
        if(!empty($balance)) {
            $users = $users->where('balance', '=', $balance);
        }
        if(!empty($serial_number)) {
            $users = $users->where('serial_number', '=', $serial_number);
        }
        if(!empty($address)) {
            $users = $users->where('address', 'like', '%'.$address.'%');
        }
        if(!empty($birthdate)) {

            $users = $users->where('birthdate', '=', date('d.m.Y', strtotime($birthdate)));
        }

        if($type=='man'){
            $users = $users->where('gender' , 1)->paginate(15);
        }elseif($type == 'woman'){
            $users = $users->where('gender' , 2)->paginate(15);
        }elseif($type == 'is_premium'){
            $users = $users->where('is_premium' , 1)->paginate(15);
        }elseif($type == 'corporate'){
            $users = $users->where('corporate' , 1)->paginate(15);
        }elseif($type == 'is_blacklist'){
            $users = $users->where('is_blacklist' , 1)->paginate(15);
        }elseif($type == 'is_blocked'){
            $users = $users->where('is_blocked' , 1)->paginate(15);
        }elseif($type == 'month'){
            $users = $users->where('created_at', '>', date('Y:m:'.'01'))->paginate(15);
        }elseif($type == 'day'){
            $users = $users->where('created_at', '>', date('Y:m:d'))->paginate(15);
        }
        else{
            $users = $users->paginate(15);
        }



        $usersLast = User::orderBy("id","DESC")->first();
        $usersCount = $usersLast->id;
        $usersGender = User::where('gender' , 1)->count();
        $usersCorporate = User::where('corporate' , 1)->count();
        $usersPremium = User::where('is_premium' , 1)->count();
        $usersBlackList = User::where('is_blacklist' , 1)->count();
        $usersBlocked = User::where('is_blocked' , 1)->count();
        /*$usersMonth = User::whereMonth('created_at', '>', date('m'))->count();
        $usersDay = User::whereDay('created_at', '>', date('d'))->count();*/
        $usersMonth = User::where('created_at', '>', date('Y:m:'.'01'))->count();
        $usersDay = User::where('created_at', '>', date('Y:m:d'))->count();
        $usersBalance = User::sum('balance');
        $data = compact('users', 'usersCount' ,'usersGender', 'usersMonth' , 'usersDay', 'usersBalance','usersCorporate','usersPremium','usersBlackList','usersBlocked');
        return response()->json(['status' => 200, 'data' => $data ]);

    }

    public function getUsers()
    {
        $name = Input::get('name');
        $uniqid = Input::get('uniqid');
        $client_id = Input::get('client_id');
        $surname = Input::get('surname');
        $email = Input::get('email');
        $pin = Input::get('pin');
        $balance = Input::get('balance');
        $serial_number = Input::get('serial_number');
        $address = Input::get('address');
        $birthdate = Input::get('birthdate');

        $users = User::with('userContacts')->orderBy('id', 'desc');

        if(!empty($name)) {
            $users = $users->where('name', 'like', '%'.$name.'%');
        }
        if(!empty($surname)) {
            $users = $users->where('surname', 'like', '%'.$surname.'%');
        }
        if(!empty($email)) {
            $users = $users->where('email', '=', $email);
        }
        if(!empty($uniqid)) {
            $users = $users->where('uniqid', '=', $uniqid);
        }

        if(!empty($client_id)) {
            $client_id = intval(substr($client_id,1,6));
            $client = Client::find($client_id);
            if($client!=null){
                $users = $users->where('id', '=', $client->user_id);
            }
        }

        if(!empty($pin)) {
            $users = $users->where('pin', '=', $pin);
        }
        if(!empty($balance)) {
            $users = $users->where('balance', '=', $balance);
        }
        if(!empty($serial_number)) {
            $users = $users->where('serial_number', '=', $serial_number);
        }
        if(!empty($address)) {
            $users = $users->where('address', 'like', '%'.$address.'%');
        }
        if(!empty($birthdate)) {

            $users = $users->where('birthdate', '=', date('d.m.Y', strtotime($birthdate)));
        }

        $users = $users->get();
        $data["users"] = $users;
        return response()->json(['status' => 200, 'data' => $data ]);;

    }

    public function getUser()
    {
        $id = Input::get('id');
        $user = User::with('userContacts')->where("id",$id)->first();
        if($user!=null){
            $incoming_count = Product::select('id')
                ->where('products.user_id', '=', $id)
                ->where('products.admin_id', '=', null)
                ->where('products.is_ordered', '=', 0)
                ->where('products.is_paid', '=', 1)
                ->where('products.status_id', '=', 1)
                ->count();

            $executing_count = Product::select('id')
                ->where('products.user_id', '=', $id)
                ->where('products.is_ordered', '<>', Product::ORDERED)
                ->where('products.is_paid', '=', 1)
                ->where('products.status_id', '=', 1)
                ->count();

            $basket_count = Product::select('id')
                ->where('products.user_id', '=', $id)
                ->where('products.is_ordered', '<>', Product::ORDERED)
                ->where('products.is_paid', '=', 0)
                ->where('products.status_id', '=', 1)
                ->count();

            $reject = Product::select('id')
                ->where('products.user_id', '=', $id)
                ->where('products.is_ordered', '<>', Product::ORDERED)
                ->where('products.is_paid', '=', 1)
                ->where('products.status_id', '=', 777)
                ->count();

            $status = Status::where('label', '=', 'waiting')->where('type', '=', 'invoice')->first();
            $waiting_count = Invoice::select('id')
                ->where('user_id', '=', $id)
                ->where('active','=',1)
                ->where('status_id','=',$status->sid)
                ->count();

            $sms_count = Sms::select("id")->where("user_id",$id)->count();
           /*


            $status = Status::where('label', '=', 'foreign')->where('type', '=', 'invoice')->first();
            $foreign_count = Invoice::select('id')
                ->where('user_id', '=', $id)
                ->where('active','=',1)
                ->where('status_id','=',$status->sid)
                ->count();

            $status = Status::where('label', '=', 'on_the_way')->where('type', '=', 'invoice')->first();
            $on_the_way_count = Invoice::select('id')
                ->where('user_id', '=', $id)
                ->where('active','=',1)
                ->where('status_id','=',$status->sid)
                ->count();

            $status = Status::where('label', '=', 'home')->where('type', '=', 'invoice')->first();
            $home_count = Invoice::select('id')
                ->where('user_id', '=', $id)
                ->where('active','=',1)
                ->where('status_id','=',$status->sid)
                ->count();

            $status = Status::where('label', '=', 'completed')->where('type', '=', 'invoice')->first();
            $completed_count = Invoice::select('id')
                ->where('user_id', '=', $id)
                ->where('active','=',1)
                ->where('status_id','=',$status->sid)
                ->count();

            $status = Status::where('label', '=', 'has_courier')->where('type', '=', 'invoice')->first();
            $has_courier = Invoice::select('id')
                ->where('user_id', '=', $id)
                ->where('active','=',1)
                ->where('status_id','=',$status->sid)
                ->count();
            $in_stock =  DB::table('depot_invoices as di')
                ->leftJoin('invoices','di.invoice_id','=','invoices.id')
                ->where('di.created_at', '<', Carbon::now()->subDays(15)->toDateTimeString())
                ->where('invoices.user_id','=',$id)
                ->select('di.id,di.invoice_id')
                ->count();*/

            $all_invoices = Invoice::select('id')
                ->where('user_id', '=', $id)
                ->where('active','=',1)
                ->where('status_id','!=',777)
                ->count();



            $status = Status::where('label', '=', 'on_the_way')->where('type', '=', 'invoice')->first();
            /* $last30day_payment_row = DB::table("invoice_dates as in_d")
                 ->selectRaw("sum(invoices.shipping_price) as sh_price,sum(invoices.price) as s_price")
                 ->where("in_d.status_id","=",$status->sid)
                 ->leftJoin('invoices','in_d.invoice_id','=','invoices.id')
                 ->leftJoin('products','invoices.product_id','=','products.id')
                 ->where('in_d.action_date', '>', Carbon::now()->subDays(30)->toDateTimeString())
                 ->where('invoices.user_id','=',$id)
                 ->first();*/

            $last30day_payment_row = DB::table('invoices')
                ->select("invoices.country_id",DB::raw("sum(shipping_price) as sh_price"),DB::raw("sum(price) as s_price"))
                ->whereIn('id', function($query)
                {
                    $status = Status::where('label', '=', 'on_the_way')->where('type', '=', 'invoice')->first();
                    $query->select('invoice_id')
                        ->from('invoice_dates')
                        ->where('action_date', '>', Carbon::now()->subDays(30)->toDateTimeString())
                        ->where("client_id",0)
                        ->where("status_id","=",$status->sid)
                    ;
                })
                ->where('invoices.user_id','=',$id)
                ->groupBy("invoices.country_id")
                ->get();
            $currency = Currencies::where("name","=","try-usd")->first();
            $last30day_payment = 0;
            $last30day_delivery_price = 0;
            if($last30day_payment_row!=null){

                foreach ($last30day_payment_row as $row){
                    $last30day_payment += $row->sh_price;
                    $last30day_delivery_price += $row->sh_price;
                    if($currency!=null and $row->country_id==1){
                        $last30day_payment += $currency->val*$row->s_price;
                    }else{
                        $last30day_payment += $row->s_price;
                    }
                }
            }
            $all = $incoming_count + $executing_count;
            //$invoices = $waiting_count + $foreign_count + $on_the_way_count + $home_count + $completed_count + $has_courier;
            $invoices = $all_invoices;
            $payment_type = 4;
            $repayments_cash = DB::table('repayments')
                ->selectRaw('sum(payment_amount) as price')
                ->where('payment_type','=',$payment_type)
                ->where('user_id','=',$id)
                ->first();
            $return_to_cash = 0;
            if($repayments_cash!=null){
                $return_to_cash = $repayments_cash->price;
            }

            $repayments_card = DB::table('repayments')
                ->selectRaw('sum(payment_amount) as price')
                ->whereIn('payment_type',[1,2,3])
                ->where('user_id','=',$id)
                ->first();
            $return_to_card = 0;
            if($repayments_card!=null){
                $return_to_card = $repayments_card->price;
            }

            $clients = 0;
            if($user->corporate==1){
                $clients = DB::table("clients")->where("user_id",$user->id)->count();
            }


            $delivered_by_courier = ' - ';
            $lost_invoices = ' - ';
            $sms = ' - ';
            $email = ' - ';
            $calls = ' - ';
            $query = ' - ';
            $messages = $sms_count;
        }

        $data = compact('user', 'all','incoming_count' ,'executing_count', 'basket_count',
            'delivered_by_courier','reject','invoices','lost_invoices','return_to_card','return_to_cash','last30day_payment','last30day_delivery_price',
            'sms','email','calls','query','messages','clients');

        return response()->json(['status' => 200, 'data' => $data ]);

    }

    /**
    Sebet -> basket
     */
    public function basket (Request $request) {
        $data = [];
        $user_id =$request->get('user_id', false);
        $pageLimit = 10;
        $result = Product::select(DB::raw("products.id,products.user_id,e.country_id,e.link,e.size,e.color,products.price,products.shop_name,products.created_at,products.is_urgent,u.name as name,u.surname as surname,u.uniqid as uniqid"))
            ->leftJoin('product_types as pt', function($join) {
                $join->on('pt.id', '=', 'products.product_type_id');
            })
            ->leftJoin('users as u', function($join) {
                $join->on('products.user_id', '=', 'u.id');
            })
            ->leftJoin('user_contacts as uc', function($join) {
                $join->on('uc.user_id', '=', 'u.id');
            })
            ->leftJoin('extras as e', function($join) {
                $join->on('products.extras_id', '=', 'e.id');
            })
            ->where('products.user_id', '=', $user_id)
            ->where('products.is_ordered', '<>', Product::ORDERED)
            ->where('products.is_paid', '=', 0)
            ->where('products.status_id', '=', 1)
            ->paginate($pageLimit);
        $data["orders"] = $result;
        if($result) {
            return response()->json([
                'status' => 200,
                'data' => $data
            ]);
        }
    }

    /**
    Imtina -> reject
     */
    public function reject (Request $request) {
        $data = [];
        $user_id =$request->get('user_id', false);
        $pageLimit = 10;
        $result = Product::select(DB::raw("products.id,products.user_id,e.country_id,a.username as admin_name,e.link,e.size,e.color,e.cargo_price,products.price,products.shop_name,products.created_at,products.is_urgent,u.name as name,u.surname as surname,u.uniqid as uniqid"))
            ->leftJoin('product_types as pt', function($join) {
                $join->on('pt.id', '=', 'products.product_type_id');
            })->leftJoin('admins as a', function($join) {
                $join->on('products.admin_id', '=', 'a.id');
            })
            ->leftJoin('users as u', function($join) {
                $join->on('products.user_id', '=', 'u.id');
            })
            ->leftJoin('user_contacts as uc', function($join) {
                $join->on('uc.user_id', '=', 'u.id');
            })
            ->leftJoin('extras as e', function($join) {
                $join->on('products.extras_id', '=', 'e.id');
            })
            ->where('products.user_id', '=', $user_id)
            ->where('products.is_ordered', '<>', Product::ORDERED)
            ->where('products.is_paid', '=', 1)
            ->where('products.status_id', '=', 777)
            ->paginate($pageLimit);
        $data["orders"] = $result;
        if($result) {
            return response()->json([
                'status' => 200,
                'data' => $data
            ]);
        }
    }

    /**
    Gelen Sifarisler
     */
    public function incoming (Request $request) {
        $data = [];
        $user_id =$request->get('user_id', false);
        $pageLimit = 10;
        $result = Product::select(DB::raw("products.id,products.user_id,e.country_id,e.link,e.size,e.color,products.price,products.shop_name,products.created_at,products.is_urgent,u.name as name,u.surname as surname,u.uniqid as uniqid"))
            ->leftJoin('product_types as pt', function($join) {
                $join->on('pt.id', '=', 'products.product_type_id');
            })
            ->leftJoin('users as u', function($join) {
                $join->on('products.user_id', '=', 'u.id');
            })
            ->leftJoin('user_contacts as uc', function($join) {
                $join->on('uc.user_id', '=', 'u.id');
            })
            ->leftJoin('extras as e', function($join) {
                $join->on('products.extras_id', '=', 'e.id');
            })
            ->where('products.user_id', '=', $user_id)
            ->where('products.admin_id', '=', null)
            ->where('products.is_ordered', '=', 0)
            ->where('products.is_paid', '=', 1)
            ->where('products.status_id', '=', 1)
            ->orderBy('products.is_urgent', 'desc')
            ->paginate($pageLimit);
        $data["orders"] = $result;

        if($result) {
            return response()->json([
                'status' => 200,
                'data' => $data
            ]);
        }
    }

    /**
    icra olunan sifarsiler
     **/

    public function executing(Request $request)
    {
        $data = [];
        $user_id =$request->get('user_id', false);
        $pageLimit = 10;
        $result = Product::select(DB::raw("products.id,products.is_problem,products.description,products.problem_text,e.country_id,e.link,e.size,e.color,products.product_type_name,products.user_id,products.price,products.shop_name,products.created_at,products.is_urgent,u.name as name,u.surname as surname,u.uniqid as uniqid,uc.name as phone,a.name as admin_name,a.surname as admin_surname"))
            ->leftJoin('product_types as pt', function($join) {
                $join->on('pt.id', '=', 'products.product_type_id');
            })
            ->leftJoin('admins as a', function($join) {
                $join->on('products.admin_id', '=', 'a.id');
            })
            ->leftJoin('users as u', function($join) {
                $join->on('products.user_id', '=', 'u.id');
            })
            ->leftJoin('user_contacts as uc', function($join) {
                $join->on('uc.user_id', '=', 'u.id');
            })
            ->leftJoin('extras as e', function($join) {
                $join->on('products.extras_id', '=', 'e.id');
            })
            ->where('products.is_ordered', '<>', Product::ORDERED)
            ->where('products.is_paid', '=', 1)
            ->where('products.status_id', '=', 1)
            ->where("products.user_id",'=',$user_id)
            ->orderBy('products.is_urgent', 'desc')
            ->orderBy('products.id', 'asc')
            ->paginate($pageLimit);
        $data["orders"] = $result;

        if($result) {
            return response()->json([
                'status' => 200,
                'data' => $data
            ]);
        }
    }

    /**
    Sifaris verildi -> waiting
     */
    public function waiting (Request $request) {
        $pageLimit = 10;
        $status = Status::where('label', '=', 'waiting')->where('type', '=', 'invoice')->first();

        $user_id =$request->get('user_id', false);
        $result = Invoice::select(DB::raw("invoices.id as id,invoices.shipping_price,invoices.purchase_no,invoices.weight,e.link,e.cargo_price,p.shop_name,a.name as admin_name,a.surname as admin_surname,p.price as price,p.product_type_name,p.quantity"))
            ->leftJoin('products as p', function($join) {
                $join->on('p.id', '=', 'invoices.product_id');
            })
            ->leftJoin('extras as e', function($join) {
                $join->on('p.extras_id', '=', 'e.id');
            })
            ->leftJoin('admins as a', function($join) {
                $join->on('p.admin_id', '=', 'a.id');
            })
            ->where("invoices.user_id",'=',$user_id)
            ->where("invoices.status_id",'=',$status->sid)
            ->where('invoices.active', '=', '1');

        $result = $result->orderBy('invoices.id', 'desc')
            ->paginate($pageLimit);

        $data["invoices"]  = $result;

        if($result) {
            return response()->json([
                'status' => 200,
                'data' => $data
            ]);
        }
    }

    public function saveComment(Request $request){
        $comment = $request->get('comment', '');
        $product_id = $request->get('product_id', 0);

        $product = Product::find($product_id);
        $product->comment=$comment;
        $product->save();

        return response()->json([
            'status' => 200,
            'data' => $product
        ]);
    }

    public function balanceInfo(Request $request){
        $id = $request->id;
        $type = $request->get("type",'azn');
        $log=LogBalance::where('user_id',$id)->where("type",$type)->orderBy("id","DESC")->get();
        return response()->json([
            'status' => 200,
            'data' => $log
        ]);
    }
    public function all_invoices (Request $request) {
        $pageLimit = 10;
        //$status = Status::where('label', '=', 'foreign')->where('type', '=', 'invoice')->first();
        $currency = Currencies::where("name","=","usd-azn")->first();

        $user_id =$request->get('user_id', false);
        $result = Invoice::select(DB::raw("invoices.id as id,invoices.is_premium,ac.name as account_id,invoices.region_id,invoices.package_id as package_id,invoices.is_paid,invoices.order_date,invoices.country_id,d.barcode_id,invoices.status_id,invoices.shipping_price,invoices.price,invoices.purchase_no,invoices.weight,invoices.width,invoices.height,invoices.length,e.link,e.cargo_price,invoices.price,invoices.order_tracking_number,p.shop_name,a.name as admin_name,a.surname as admin_surname,p.product_type_name,p.quantity,p.shop_name,p.product_type_name,p.comment,p.id as p_id"))
            ->leftJoin('products as p', function($join) {
                $join->on('p.id', '=', 'invoices.product_id');
            })
            ->leftJoin('extras as e', function($join) {
                $join->on('p.extras_id', '=', 'e.id');
            })
            ->leftJoin('admins as a', function($join) {
                $join->on('p.admin_id', '=', 'a.id');
            })
            ->leftJoin('depot_invoices as di', function($join) {
                $join->on('invoices.id', '=', 'di.invoice_id');
            })
            ->leftJoin('depots as d', function($join) {
                $join->on('d.id', '=', 'di.depot_id');
            })
            ->leftJoin('accounts as ac', function($join) {
                $join->on('invoices.account_id', '=', 'ac.id');
            })
            ->where("invoices.user_id",'=',$user_id)
            ->where("invoices.status_id",'!=',777)
            ->where('invoices.active', '=', '1');


        $begin_date = $request->get('begin_date', false);
        $end_date = $request->get('end_date', false);
        $country_id = $request->get('country_id', false);
        $status_id = $request->get('status_id', false);
        if($begin_date) $result = $result->where("invoices.order_date",'>',date("Y-m-d 00:00:00",strtotime($begin_date)));
        if($end_date) $result = $result->where("invoices.order_date",'<',date("Y-m-d 23:59:59",strtotime($end_date)));
        if($country_id) $result = $result->where("invoices.country_id",'=',$country_id);
        if($status_id) $result = $result->where("invoices.status_id",'=',$status_id);



        $result = $result->orderBy('invoices.id', 'desc')
            ->paginate($pageLimit);

        $data["invoices"]  = $result;
        $data["currency"]  = $currency;

        $countries = Country::selectRaw('id,name,prefix')->get();
        foreach ($countries as $country){
            $data['countries'][$country->id] = $country;
        }


        if($result) {
            return response()->json([
                'status' => 200,
                'data' => $data
            ]);
        }
    }

    public function getRePayments(Request $request)
    {
        $data = [];
        $user_id =$request->get('user_id', false);
        $rePaymentsRows = DB::table("repayments")->select("*")->where("user_id",$user_id)->orderBy("id","DESC")->paginate(30);

        $data["repayments"] = $rePaymentsRows;

        return response()->json(['data' => $data, 'code' => 200]);

    }

    public function getClients(Request $request)
    {
        $data = [];
        $user_id =$request->get('user_id', false);
        $clientsRows = DB::table("clients")->select("*")->where("user_id",$user_id)->where("status_id",1)->orderBy("id","DESC")->get();

        $clients = [];

        $currency = Currencies::where("name","try-usd")->first();
        $tryToUsd = $currency->val;

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
                    $clients[$row->client_id]["last30"] =  number_format(((float)$clients[$row->client_id]["last30"] + (float)($row->price * $tryToUsd) + (float)$row->shipping_price), 2);
                }
            }
        }

        $data["clients"] = $clients;

        return response()->json(['data' => $data, 'code' => 200]);

    }



    public function addBalance(Request $request){

        $desc = $request->desc." || Adminid: ~~".auth()->id();
        $desc2 = $request->desc2;
        $this->handleUserBalance($request->user_id,$request->money,$request->type,$desc,$desc2);
        return response()->json(['success' => true]);
    }


    public function addBalanceTry(Request $request){

        $desc = $request->desc." || Adminid: ~~".auth()->id();
        $desc2 = $request->desc2;
        $this->handleUserBalanceTry($request->user_id,$request->money,$request->type,$desc,$desc2);
        return response()->json(['success' => true]);
    }

    public function getSmsTemplates()
    {
        $result = null;
        $templates = DB::table("sms_templates")->get();
        if($templates){
            foreach ($templates as $template){
                $result[$template->id] = $template;
            }
            return response()->json([
                'status' => 200,
                'data' => $result
            ]);
        }
    }

    // smsler ilk 1000 nefere
    /*
        public function testYusif (Request $request)
        {
            echo $_SERVER["REMOTE_ADDR"]."<br />";
            echo $_SERVER["HTTP_CF_CONNECTING_IP"];
            $users = User::with("userContacts")->where("id","<=",1000)->get();
            foreach($users as $user){
                if(count($user->userContacts)>0){
                    $phone_str = $user->userContacts[0]->name;

                    $phone = phoneNumber($phone_str);
                    if($phone){
                        $hash = ownHash($user->id);
                        $text = 'Tebrikler! Ilk 1000 neferden biri de sen oldun, 50% endirim qazandin! Promo kod: i_'.$hash.'. Vaxtini itirme, sifarish et! https://limak.az *9595';
                        $sms = new Sms();
                        $sms->user_id = $user->id;
                        $sms->phone = $phone;
                        $sms->text = $text;
                        $sms->created_at = date("Y-m-d H:i:s");
                        $sms->priority = 0;
                        $sms->status = 0;
                        if($sms->save()){
                            echo $user->id." Elave edildi<br />";
                        }else{
                            echo $user->id." Sehv <br />";
                        }
                    }

                }

            }
        }
        // emailler ilk 1000 nefere
        public function testYusif2 (Request $request)
        {
            $users = User::with("userContacts")->where("id","<=",1000)->get();
            foreach($users as $user){
                if (filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
                    $email = $user->email;
                    $hash = ownHash($user->id);
                    $text = '
                    Təbriklər! Şanslı 1000 nəfərdən biri də sən oldun, 12-17 mart
    tarixlərində etdiyin sifarişlərinin 3 kiloqrama qədərinin çatdırılma qiymətinə 50%
    endirim qazandın! Sifariş etdiyin zaman sənə göndərilmiş promo kodunu
    www.limak.az saytında “bəyannamə əlavə et” və ya “sifariş et” bölmələrinə daxil
    et. Promo kod yalniz bir dəfə istifadə edilə bilər. Vaxtını itirmə, sifariş et, kampaniyadan yararlan! Promo kod: i_'.$hash;
                    $sms = new Email();
                    $sms->user_id = $user->id;
                    $sms->email = $email;
                    $sms->text = $text;
                    $sms->created_at = date("Y-m-d H:i:s");
                    $sms->priority = 0;
                    $sms->status = 0;
                    if($sms->save()){
                        echo $user->id." Elave edildi<br />";
                    }else{
                        echo $user->id." Sehv <br />";
                    }

                }

            }
        }*/

    ///////////////////////////////////////

    public function sendSmsForAll(Request $request){

        $region_id = $request->region_id;
        //$region_id = 1;
        $invoice = DB::table('invoices as i')
            ->select('i.id','i.width', 'i.height', 'i.length', 'i.weight' , 'i.shipping_price','i.purchase_no','i.waybill','i.order_tracking_number','i.country_id',
                'u.uniqid', 'u.name', 'u.surname', 'u.address', 'u.serial_number','u.birthdate','u.id as user_id',
                'c.name as phone', 'p.price', 'p.quantity', 'p.shop_name', 'product_type_name','p.description'
            )
            ->leftJoin('rel_user_products as r', 'r.id', '=', 'i.rel_user_product_id')
            ->leftJoin('users as u', 'r.user_id', '=', 'u.id')
            ->leftJoin('user_contacts as c', 'c.user_id', '=', 'u.id')
            ->leftJoin('products as p', 'i.product_id', '=', 'p.id')
            ->where('i.status_id', '=', 4)
            ->where('i.s_id', '=', 0)
            ->where('i.region_id', '=', $region_id)
            //->where('i.updated_at', '<', date("Y-m-d 23:59:59"))
            //->where('i.updated_at', '>', date("Y-m-d  00:00:01"))
            ->get();

        //return response()->json(['invoices' => $invoice]); exit;

        $users = [];
        if($region_id==1){
            $data_tr = (object) ['text' => 'Hormetli musteri,Turkiyeden olan baglamanız artıq Bakı ofisimizdedir. Unvan: Lermontov kuc. 113/117. Iceriseher m/st yaxınlıgı. www.limak.az', "user_id" =>null];
            $data_usa = (object) ['text' => 'Hormetli musteri,Amerikadan olan baglamanız artıq Bakı ofisimizdedir. Unvan: Lermontov kuc. 113/117. Iceriseher m/st yaxınlıgı. www.limak.az', "user_id" =>null];
            $data2 = (object) ['text' => 'Xatırladaq ki, kuryer xidmetimizdən yararlana bilersiniz. Baki və Sumqayıtda istenilən unvana catdirilir.',"user_id" => null];
        }elseif($region_id==2){
            $data_tr = (object) ['text' => 'Hormetli musteri,Turkiyeden olan baglamanız Gence ofisimizdedir.Unvan: Gence seher, kohne Yevlax avtovagzalinin yani, N.Narimanov parki ile uzbeuz. www.limak.az', "user_id" =>null];
            $data_usa = (object) ['text' => 'Hormetli musteri,Turkiyeden olan baglamanız Gence ofisimizdedir.Unvan: Gence seher, kohne Yevlax avtovagzalinin yani, N.Narimanov parki ile uzbeuz. www.limak.az', "user_id" =>null];
        }elseif($region_id==3){
            $data_tr = (object) ['text' => 'Hormetli musteri,Turkiyeden olan baglamanız artıq Sumqayit ofisimizdedir. Unvan: Sumqayit seheri, 36-ci mehelle 7/11-5. www.limak.az', "user_id" =>null];
            $data_usa = (object) ['text' => 'Hormetli musteri,Amerikadan olan baglamanız artıq Sumqayit ofisimizdedir. Unvan: Sumqayit seheri, 36-ci mehelle 7/11-5. www.limak.az', "user_id" =>null];
        }elseif($region_id==4){
            $data_tr = (object) ['text' => 'Hormetli musteri,Turkiyeden olan baglamanız artıq Zaqatala ofisimizdedir. Unvan: Zaqatala seheri, Heyder Eliyev prospekti www.limak.az', "user_id" =>null];
            $data_usa = (object) ['text' => 'Hormetli musteri,Amerikadan olan baglamanız artıq Zaqatala ofisimizdedir. Unvan: Zaqatala seheri, Heyder Eliyev prospekti 145A www.limak.az', "user_id" =>null];
        }elseif($region_id==5){

            $data_tr = (object) ['text' => 'Hormetli musteri,Turkiyeden olan baglamanız artıq ofisimizdedir.', "user_id" =>null];
            $data_usa = (object) ['text' => 'Hormetli musteri,Amerikadan olan baglamanız artıq ofisimizdedir.', "user_id" =>null];
        }

        foreach($invoice as $item){
            $phone = str_replace(' ','', $item->phone);
            if(!in_array($phone, $users)){
                if($item->country_id==1){
                    $data = $data_tr;
                }elseif($item->country_id==2){
                    $data = $data_usa;
                }
                $users[] = $phone;
                $data->user_id = $item->user_id;
                smsSend($data, $phone);

                $user = User::find($item->user_id);
                if($user!=null and strlen($user->fcm_token)>6){
                    notificationSendDirect($data,$user->fcm_token);
                }

//                if($region_id==1){
//                    $data2->user_id = $item->user_id;
//                    smsSend($data2, $phone);
//                }

            }
        }

        DB::table('invoices')
            ->where('status_id', '=', 4)
            ->where('s_id', '=', 0)
            ->where('region_id', '=', $region_id)
            ->update(['s_id' => 1]);

        return response()->json(['success' => true]);
    }

    public function blockUser(Request $request){
        $user_id = (int) $request->get('user_id', 0);
        $is_blocked = (int) $request->get('is_blocked', 0);

        $User = User::find($user_id);
        if($User === null) return response()->json(['status' => 404, 'message' => 'User not found']);

        if($User->is_blocked==1){
            DB::table("user_activations")->where("user_id",$user_id)->delete();
        }

        $User->is_blocked = $is_blocked;
        $User->save();
        return response()->json(['status' => 200, 'message' => 'Data has been changed']);
    }

    public function blackListUser(Request $request){
        $user_id = (int) $request->get('user_id', 0);
        $is_blacklist = (int) $request->get('is_blacklist', 0);

        $User = User::find($user_id);
        if($User === null) return response()->json(['status' => 404, 'message' => 'User not found']);

        $User->is_blacklist = $is_blacklist;
        $User->save();
        return response()->json(['status' => 200, 'message' => 'Data has been changed']);
    }

    public function changePin(Request $request)
    {
        $user_id = (int) $request->get('user_id', 0);
        $pin =  $request->get('pin', '');

        $User = User::find($user_id);
        if($User === null) return response()->json(['status' => 404, 'message' => 'User not found']);

        if(strlen($pin)>3){
            $User->pin = $pin;
            $User->save();
        }else{
            return response()->json(['status' => 300, 'message' => "Pin is not correct"]);
        }

        return response()->json(['status' => 200, 'message' => 'Data has been changed']);
    }

    public function changeEmail(Request $request)
    {
        $user_id = (int) $request->get('user_id', 0);
        $email =  $request->get('email', '');

        $User = User::find($user_id);
        if($User === null) return response()->json(['status' => 404, 'message' => 'User not found']);

        if(strlen($email)>3 and filter_var($email, FILTER_VALIDATE_EMAIL)){
            $User->email = $email;
            $User->save();
        }else{
            return response()->json(['status' => 300, 'message' => "Email is not correct"]);
        }

        return response()->json(['status' => 200, 'message' => 'Data has been changed']);
    }

    public function hesabat(Request $request)
    {
        $user_id = $request->user_id;
        $user = User::find($user_id);
        $begin_date = date('Y-m-d',strtotime($request->begin_date));
        $end_date = date('Y-m-d',strtotime($request->end_date));
        $client_id = 0;

        $currency = \App\Currencies::where("name","try-usd")->first();
        $tryToUsd = $currency->val;

        $last30days = DB::table('invoices')
            ->select("invoices.id","invoices.purchase_no","invoices.shipping_price","invoices.weight","invoices.client_id","invoices.price","invoices.country_id",'p.shop_name','p.product_type_name')
            //->select(DB::raw("sum(shipping_price) as shipping_price"),DB::raw("sum(price) as price"))
            ->leftJoin("products as p","p.id","invoices.product_id")
            ->whereIn('invoices.id', function($query) use($begin_date,$end_date)
            {
                $status = Status::where('label', '=', 'on_the_way')->where('type', '=', 'invoice')->first();
                $query->select('invoice_id')
                    ->from('invoice_dates')
                    ->where('action_date', '>=', $begin_date)
                    ->where('action_date', '<=', $end_date)
                    ->where("status_id","=",$status->sid);
            })

            ->where('invoices.user_id','=',$user_id)
            ->where('invoices.client_id','=',$client_id)
            ->where('invoices.status_id','!=',777)
            ->get();

        $invoices = [];

        foreach ($last30days as $row){
            if($row->country_id==1){
                $invoices[$row->id]["price"] = round((float)$row->price*$tryToUsd,2);
                $invoices[$row->id]["all_price"] =  round((float)((float)$row->price*$tryToUsd+ (float)$row->shipping_price), 2);
            }elseif($row->country_id==2){
                $invoices[$row->id]["price"] = round((float)$row->price,2);
                $invoices[$row->id]["all_price"] =  round((float)((float)$row->price + (float)$row->shipping_price), 2);
            }
            $invoices[$row->id]["shipping_price"] = $row->shipping_price;
            $invoices[$row->id]["weight"] = $row->weight;
            $invoices[$row->id]["shop_name"] = $row->shop_name;
            $invoices[$row->id]["product_type_name"] = $row->product_type_name;
            $invoices[$row->id]["purchase_no"] = $row->purchase_no;
        }




        return view('cp.admins.report', compact('invoices','user','begin_date','end_date'));

    }
}
