<?php

namespace App\Http\Controllers\Tr;

use App\Currency;
use App\Currencies;
use App\DepoPackages;
use App\Fatura;
use App\ModelCountry\Country;
use App\Invoice;
use App\InvoiceDates;
use App\Invoiceless;
use App\Libraries\Upload\Uploader;
use App\ModelProduct\Product;
use App\ModelProduct\ProductType;
use App\ModelUser\Client;
use App\ModelUser\User;
use App\ModelUser\UserContact;
use App\Packages;
use App\RelUserProduct;
use App\Status;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;
use \Milon\Barcode\DNS1D;



class InvoiceController extends BaseController
{
    public function __construct()
    {

        $data = Country::where('prefix','tr')->first();
        $this->country=$data->id;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( Request $request)
    {
        $tab = Input::get('tab')?Input::get('tab') : 'all';
        $pin = Input::get('pin');
        $tel = Input::get('tel');
        $user_code = Input::get('user_code');
        $params = Input::get();
        $dataStorage = [];
        $dataInRoad = [];
        $dataWaiting = [];
        $foreignPermission = $this->checkRole('storage_foreign');
        $homePermission = $this->checkRole('storage_home');

        $superAdminPermission = $this->checkRole('super_admin');
        $label = false;

        if($foreignPermission || $superAdminPermission) {
            $label = 'foreign';
        }
        /*if($homePermission || $superAdminPermission) {
            $label = 'home';
        }*/

        $invoice = ($request->has('invoice') && $request->get('invoice') == 1) ? '<>' : '=';

        if($foreignPermission || $superAdminPermission) {
            if($tab == 'all') {
                $dataStorage = $this->inStorage($pin, $tel, $label, $user_code);
                $dataInRoad = $this->inRoad($pin, $tel, $user_code);
                if($label === 'foreign') {
                    $dataWaiting = $this->waiting($pin, $tel, $user_code, $invoice);
                }
            } else if ($tab === 'storage') {
                $dataStorage = $this->inStorage($pin, $tel, $label, $user_code);
            } else if($tab === 'road') {
                $dataInRoad = $this->inRoad($pin, $tel, $user_code);
            }else if($tab === 'waiting') {

                $dataWaiting = $this->waiting($pin, $tel, $user_code, $invoice);
            }
        }

        $productTypes = ProductType::all();

        if($request->ajax()) {
            return view('admin.invoice.ajax', compact('dataStorage', 'dataInRoad', 'dataWaiting','label' ,'productTypes'));
        } else {
            return view('admin.invoice.index', compact('dataStorage', 'dataInRoad', 'dataWaiting','label' ,'productTypes'));
        }
    }

    public function test(Request $request)
    {
        \Artisan::call('cache:clear');
        \Artisan::call('view:clear');
        $fullname = Input::get('fullname', '');
        $uniqid = Input::get('user_code','');
        $status = Input::get('status', 1);
        $last30type = Input::get('type', '' );
//        string(818) "select `i`.`width`, `i`.`height`, `i`.`length`, `i`.`weight`, `i`.`shipping_price`, `i`.`purchase_no`, `i`.`waybill`, `i`.`file`, `i`.`id`, `i`.`status_id`, `i`.`by_bus`, `u`.`uniqid`, `u`.`name`, `u`.`surname`, `u`.`address`, `u`.`email`, `u`.`pin`, `c`.`name` as `phone`, `p`.`price`, `p`.`quantity`, `p`.`shop_name`, `p`.`id` as `product_id`, `product_type_name`, `s`.`name` as `invoice_status` from `invoices` as `i` left join `users` as `u` on `i`.`user_id` = `u`.`id` left join `user_contacts` as `c` on `c`.`user_id` = `u`.`id` left join `products` as `p` on `i`.`product_id` = `p`.`id` left join `product_types` as `pt` on `p`.`product_type_id` = `pt`.`id` left join `statuses` as `s` on `i`.`status_id` = `s`.`id` where `i`.`status_id` = ? and `i`.`active` = ? and `u`.`uniqid` = ? order by `i`.`user_id` desc
        $client_id = 0;
        if(substr($uniqid,0,1)==1 and strlen(trim($uniqid))==7){
            $client_id = intval(substr($uniqid,1,6));
        }
        $fullname=explode(' ',$fullname);
//var_dump($fullname);

        $allData = DB::table("invoices")->selectRaw("sum(weight) as all_weight,count(id) as total_count")->where('country_id', '=', $this->country)->where("status_id",2)->where('active',1)->first();
        $data = DB::table('invoices as i')
            ->select('i.width', 'i.liquid_type','i.height', 'i.length', 'i.weight' , 'i.shipping_price','i.purchase_no','i.waybill','i.file','i.id','i.status_id','i.by_bus','i.order_tracking_number',
                'u.uniqid', 'u.name', 'u.surname', 'u.address','u.email','u.pin',
                'c.name as phone', 'i.price', 'p.quantity', 'p.shop_name','p.id as product_id', 'p.comment as comment', 'p.product_type_name','s.name as invoice_status',
                'cl.name as client_name','cl.surname as client_surname','i.corporate','i.client_id','i.return_id','r.comment',
//                DB::raw('SUM(i.weight) as i_weight')
            )
            ->leftJoin('users as u', 'i.user_id', '=', 'u.id')
            ->leftJoin('clients as cl', 'i.client_id', '=', 'cl.id')
            ->leftJoin('user_contacts as c', 'c.user_id', '=', 'u.id')
            ->leftJoin('products as p', 'i.product_id', '=', 'p.id')
            ->leftJoin('product_types as pt', 'p.product_type_id', '=', 'pt.id')
            ->leftJoin('statuses as s', 'i.status_id', '=', 's.id')
            ->leftJoin('returns as r', 'i.return_id', '=', 'r.id')
            //->where('i.status_id', '=', $status)
            ->where('i.country_id', '=', $this->country)
            ->where('i.active','=',1);
        if($status==1){
            $data=$data->where(function($query){
                $query->where('i.status_id','=', 1)->orWhere('i.status_id','=', 16);
            })->where('u.is_blocked', '=', 0)
            ;
        }else{
            $data = $data->where('i.status_id', '=', $status);
        }

        if(!empty($uniqid) and $client_id==0) $data=$data->where(function($query) use($uniqid){
            $query->where('u.uniqid','=', $uniqid)->orWhere('u.id','=', $uniqid);
        });

        if($client_id>0){
            $data = $data->where("i.client_id",$client_id);
        }

        if(!empty($fullname[0])) $data = $data->where('u.name' ,'like','%' . $fullname[0] . '%')->orWhere('u.surname' ,'like','%' . $fullname[0] . '%');
        if(isset($fullname[1]) || !empty($fullname[1])) $data = $data->where('u.name' ,'like','%' . $fullname[1] . '%')->orWhere('u.surname' ,'like','%' . $fullname[1] . '%');
        $data=$data->orderBy('i.user_id','DESC')->get();

        $productTypes = ProductType::all();

        $currency = Currency::find(1);
        $tryToUsd = 1/$currency->tl * $currency->usd;

        if($request->ajax()) {
            return view('tr.invoice.ajax1',  compact('data' ,'status'));
        } else {
            return view('tr.invoice.test1',  compact('data','status','productTypes','tryToUsd','last30type','allData'));
        }

    }


    public function anbar(Request $request)
    {
        \Artisan::call('cache:clear');
        \Artisan::call('view:clear');
        $fullname = Input::get('fullname', '');
        $uniqid = Input::get('user_code','');
        $status = Input::get('status', 1);
        $status = 2;
        $faturalar = Fatura::where("status_id","=",0)->where("liquid_type",0)->get();
        $client_id = 0;
        if(substr($uniqid,0,1)==1 and strlen(trim($uniqid))==7){
            $client_id = intval(substr($uniqid,1,6));
        }
        $last30type = Input::get('type', '' );
//        string(818) "select `i`.`width`, `i`.`height`, `i`.`length`, `i`.`weight`, `i`.`shipping_price`, `i`.`purchase_no`, `i`.`waybill`, `i`.`file`, `i`.`id`, `i`.`status_id`, `i`.`by_bus`, `u`.`uniqid`, `u`.`name`, `u`.`surname`, `u`.`address`, `u`.`email`, `u`.`pin`, `c`.`name` as `phone`, `p`.`price`, `p`.`quantity`, `p`.`shop_name`, `p`.`id` as `product_id`, `product_type_name`, `s`.`name` as `invoice_status` from `invoices` as `i` left join `users` as `u` on `i`.`user_id` = `u`.`id` left join `user_contacts` as `c` on `c`.`user_id` = `u`.`id` left join `products` as `p` on `i`.`product_id` = `p`.`id` left join `product_types` as `pt` on `p`.`product_type_id` = `pt`.`id` left join `statuses` as `s` on `i`.`status_id` = `s`.`id` where `i`.`status_id` = ? and `i`.`active` = ? and `u`.`uniqid` = ? order by `i`.`user_id` desc
        $allData = DB::table("invoices")->selectRaw("sum(weight) as all_weight,count(id) as total_count")->where('country_id', '=', $this->country)->where("status_id",2)->where('active',1)->first();

        $fullname=explode(' ',$fullname);
//var_dump($fullname);
        $data = DB::table('invoices as i')
            ->select('i.width', 'i.height','i.liquid_type' ,'i.length', 'i.weight' , 'i.shipping_price','i.purchase_no','i.waybill','i.file','i.id','i.status_id','i.by_bus','i.order_tracking_number',
                'u.uniqid', 'u.name', 'u.surname', 'u.address','u.email','u.pin',
                'c.name as phone', 'i.price', 'p.quantity', 'p.shop_name','p.id as product_id', 'p.comment as comment', 'product_type_name','s.name as invoice_status',
                'cl.name as client_name','cl.surname as client_surname','i.corporate','i.client_id'
            //                DB::raw('SUM(i.weight) as i_weight')
            )
            ->leftJoin('users as u', 'i.user_id', '=', 'u.id')
            ->leftJoin('user_contacts as c', 'c.user_id', '=', 'u.id')
            ->leftJoin('products as p', 'i.product_id', '=', 'p.id')
            ->leftJoin('product_types as pt', 'p.product_type_id', '=', 'pt.id')
            ->leftJoin('clients as cl', 'i.client_id', '=', 'cl.id')
            ->leftJoin('statuses as s', 'i.status_id', '=', 's.id')
            ->where('i.status_id', '=', $status)
            ->where('i.country_id', '=', $this->country)
            ->where('i.fatura_id','=',0)
            ->where('i.active','=',1);
        if(!empty($uniqid)) $data=$data->where(function($query) use($uniqid){
            $query->where('u.uniqid','=', $uniqid)->orWhere('u.id','=', $uniqid);
        });

        if($client_id>0){
            $data = $data->where("i.client_id",$client_id);
        }

        if(!empty($fullname[0])) $data = $data->where('u.name' ,'like','%' . $fullname[0] . '%')->orWhere('u.surname' ,'like','%' . $fullname[0] . '%');
        if(isset($fullname[1]) || !empty($fullname[1])) $data = $data->where('u.name' ,'like','%' . $fullname[1] . '%')->orWhere('u.surname' ,'like','%' . $fullname[1] . '%');
        $data=$data->orderBy('i.user_id','DESC')->get();

        $productTypes = ProductType::all();

        $currency = Currency::find(1);
        $tryToUsd = 1/$currency->tl * $currency->usd;

        if($request->ajax()) {
            return view('tr.invoice.ajax1',  compact('data' ,'status'));
        } else {
            return view('tr.invoice.anbar',  compact('data','status','productTypes','tryToUsd','last30type','faturalar','allData'));
        }
    }

    public function faturalar(Request $request)
    {

        \Artisan::call('cache:clear');
        \Artisan::call('view:clear');
        $fullname = Input::get('fullname', '');
        $uniqid = Input::get('user_code','');
        $status = Input::get('status', 1);
        $status = 2;
        $fatura_id = Input::get('fatura_id', 0);

        $client_id = 0;
        if(substr($uniqid,0,1)==1 and strlen(trim($uniqid))==7){
            $client_id = intval(substr($uniqid,1,6));
        }
        $faturalar = Fatura::where("status_id","==",0)->orderBy("liquid_type","DESC")->orderBy("id","ASC")->get();
        $last30type = Input::get('type', '' );
//        string(818) "select `i`.`width`, `i`.`height`, `i`.`length`, `i`.`weight`, `i`.`shipping_price`, `i`.`purchase_no`, `i`.`waybill`, `i`.`file`, `i`.`id`, `i`.`status_id`, `i`.`by_bus`, `u`.`uniqid`, `u`.`name`, `u`.`surname`, `u`.`address`, `u`.`email`, `u`.`pin`, `c`.`name` as `phone`, `p`.`price`, `p`.`quantity`, `p`.`shop_name`, `p`.`id` as `product_id`, `product_type_name`, `s`.`name` as `invoice_status` from `invoices` as `i` left join `users` as `u` on `i`.`user_id` = `u`.`id` left join `user_contacts` as `c` on `c`.`user_id` = `u`.`id` left join `products` as `p` on `i`.`product_id` = `p`.`id` left join `product_types` as `pt` on `p`.`product_type_id` = `pt`.`id` left join `statuses` as `s` on `i`.`status_id` = `s`.`id` where `i`.`status_id` = ? and `i`.`active` = ? and `u`.`uniqid` = ? order by `i`.`user_id` desc

        $fullname=explode(' ',$fullname);
//var_dump($fullname);
        $data = DB::table('invoices as i')
            ->select('i.width', 'i.height', 'i.length','i.liquid_type', 'i.weight' , 'i.shipping_price','i.purchase_no','i.waybill','i.file','i.id','i.status_id','i.by_bus','i.order_tracking_number','i.fatura_id',
                'u.uniqid', 'u.name', 'u.surname', 'u.address','u.email','u.pin',
                'c.name as phone', 'i.price', 'p.quantity', 'p.shop_name','p.id as product_id', 'p.comment as comment', 'product_type_name','s.name as invoice_status',
                'cl.name as client_name','cl.surname as client_surname','i.corporate','i.client_id'
//                DB::raw('SUM(i.weight) as i_weight')
            )
            ->leftJoin('users as u', 'i.user_id', '=', 'u.id')
            ->leftJoin('fatura as f', 'i.fatura_id', '=', 'f.id')
            ->leftJoin('user_contacts as c', 'c.user_id', '=', 'u.id')
            ->leftJoin('products as p', 'i.product_id', '=', 'p.id')
            ->leftJoin('product_types as pt', 'p.product_type_id', '=', 'pt.id')
            ->leftJoin('clients as cl', 'i.client_id', '=', 'cl.id')
            ->leftJoin('statuses as s', 'i.status_id', '=', 's.id')
            ->where('i.status_id', '=', $status)
            ->where('f.status_id', '!=', 2)
            ->where('i.country_id', '=', $this->country);

        if($fatura_id>0){
            $data =  $data ->where('i.fatura_id','=',$fatura_id);
        }

        $data =  $data ->where('i.active','=',1);
        if(!empty($uniqid)) $data=$data->where(function($query) use($uniqid){
            $query->where('u.uniqid','=', $uniqid)->orWhere('u.id','=', $uniqid);
        });

        if($client_id>0){
            $data = $data->where("i.client_id",$client_id);
        }

        if(!empty($fullname[0])) $data = $data->where('u.name' ,'like','%' . $fullname[0] . '%')->orWhere('u.surname' ,'like','%' . $fullname[0] . '%');
        if(isset($fullname[1]) || !empty($fullname[1])) $data = $data->where('u.name' ,'like','%' . $fullname[1] . '%')->orWhere('u.surname' ,'like','%' . $fullname[1] . '%');
        $data=$data->orderBy('i.user_id','DESC')->get();

        $productTypes = ProductType::all();

        $currency = Currency::find(1);
        $tryToUsd = 1/$currency->tl * $currency->usd;

        if($request->ajax()) {
            return view('tr.invoice.ajax1',  compact('data' ,'status'));
        } else {
            return view('tr.invoice.faturalar',  compact('data','status','productTypes','tryToUsd','last30type','faturalar','fatura_id'));
        }
    }

    public function faturalarExcel(Request $request)
    {

        \Artisan::call('cache:clear');
        \Artisan::call('view:clear');
        $fullname = Input::get('fullname', '');
        $uniqid = Input::get('user_code','');
        $status = Input::get('status', 1);
        $status = 2;
        $fatura_id = Input::get('fatura_id', 0);

        $client_id = 0;
        if(substr($uniqid,0,1)==1 and strlen(trim($uniqid))==7){
            $client_id = intval(substr($uniqid,1,6));
        }
        $faturalar = Fatura::where("status_id","==",0)->orderBy("liquid_type","DESC")->orderBy("id","ASC")->get();
        $last30type = Input::get('type', '' );
//        string(818) "select `i`.`width`, `i`.`height`, `i`.`length`, `i`.`weight`, `i`.`shipping_price`, `i`.`purchase_no`, `i`.`waybill`, `i`.`file`, `i`.`id`, `i`.`status_id`, `i`.`by_bus`, `u`.`uniqid`, `u`.`name`, `u`.`surname`, `u`.`address`, `u`.`email`, `u`.`pin`, `c`.`name` as `phone`, `p`.`price`, `p`.`quantity`, `p`.`shop_name`, `p`.`id` as `product_id`, `product_type_name`, `s`.`name` as `invoice_status` from `invoices` as `i` left join `users` as `u` on `i`.`user_id` = `u`.`id` left join `user_contacts` as `c` on `c`.`user_id` = `u`.`id` left join `products` as `p` on `i`.`product_id` = `p`.`id` left join `product_types` as `pt` on `p`.`product_type_id` = `pt`.`id` left join `statuses` as `s` on `i`.`status_id` = `s`.`id` where `i`.`status_id` = ? and `i`.`active` = ? and `u`.`uniqid` = ? order by `i`.`user_id` desc

        $fullname=explode(' ',$fullname);
//var_dump($fullname);
        $data = DB::table('invoices as i')
            ->select('i.width', 'i.height', 'i.length','i.liquid_type', 'i.weight' , 'i.shipping_price','i.purchase_no','i.waybill','i.file','i.id','i.status_id','i.by_bus','i.order_tracking_number','i.fatura_id',
                'u.uniqid', 'u.name', 'u.surname', 'u.address','u.email','u.pin',
                'c.name as phone', 'i.price', 'p.quantity', 'p.shop_name','p.id as product_id', 'p.comment as comment', 'product_type_name','s.name as invoice_status',
                'cl.name as client_name','cl.surname as client_surname','i.corporate','i.client_id',
                'ind.action_date as depo_date'
//                DB::raw('SUM(i.weight) as i_weight')
            )
            ->leftJoin('users as u', 'i.user_id', '=', 'u.id')
            ->leftJoin('fatura as f', 'i.fatura_id', '=', 'f.id')
            ->leftJoin('user_contacts as c', 'c.user_id', '=', 'u.id')
            ->leftJoin('products as p', 'i.product_id', '=', 'p.id')
            ->leftJoin('product_types as pt', 'p.product_type_id', '=', 'pt.id')
            ->leftJoin('clients as cl', 'i.client_id', '=', 'cl.id')
            ->leftJoin('statuses as s', 'i.status_id', '=', 's.id')
            ->leftJoin('invoice_dates as ind', 'ind.invoice_id', '=', 'i.id')

            ->where('i.status_id', '=', $status)
            ->where('f.status_id', '!=', 2)
            ->where('ind.status_id', '=', 2)

            ->where('i.country_id', '=', $this->country);

        if($fatura_id>0){
            $data =  $data ->where('i.fatura_id','=',$fatura_id);
        }

        $data =  $data ->where('i.active','=',1);
        if(!empty($uniqid)) $data=$data->where(function($query) use($uniqid){
            $query->where('u.uniqid','=', $uniqid)->orWhere('u.id','=', $uniqid);
        });

        if($client_id>0){
            $data = $data->where("i.client_id",$client_id);
        }

        if(!empty($fullname[0])) $data = $data->where('u.name' ,'like','%' . $fullname[0] . '%')->orWhere('u.surname' ,'like','%' . $fullname[0] . '%');
        if(isset($fullname[1]) || !empty($fullname[1])) $data = $data->where('u.name' ,'like','%' . $fullname[1] . '%')->orWhere('u.surname' ,'like','%' . $fullname[1] . '%');
        $data=$data->orderBy('i.user_id','DESC')->get();

        $productTypes = ProductType::all();

        $currency = Currency::find(1);
        $tryToUsd = 1/$currency->tl * $currency->usd;
        return view('tr.invoice.faturaExcel',  compact('data','status','productTypes','tryToUsd','last30type','faturalar','fatura_id'));

    }


    public function sendFatura(Request $request)
    {

        $fatura_id = $request->get("fatura_id",0);
        $fatura = Fatura::where("id",$fatura_id)->where("status_id",0)->first();
        if($fatura!=null){
            $fatura->status_id = 1;
            if($fatura->save()){
                $update = DB::table('invoices')
                    ->where('status_id', 2)
                    ->where('fatura_id', $fatura_id)
                    ->update(['status_id' => 3]);


                $invoices = Db::table('invoices')->where("fatura_id",$fatura_id)->get();
                foreach ($invoices as $invoice){

                    $invoice_date=new InvoiceDates();
                    $invoice_date->status_id=3;
                    $invoice_date->invoice_id=$invoice->id;
                    $invoice_date->action_date=date('Y-m-d H:i:s');
                    $invoice_date->save();

                    $user = User::find($invoice->user_id);
                    if($user!=null and strlen($user->fcm_token)>6){
                        $data = (object) ['text' => 'Bağlamanız Türkiyə anbarımızdan yola çıxdı. Bağlamanızı Bağlamalarım bölməsindən izləyə bilərsiniz.', "user_id" =>$invoice->user_id];
                        notificationSendDirect($data,$user->fcm_token);
                    }
                }

                return redirect('/invoices/get/faturalar');


            }
        }else{
            return redirect('/invoices/get/faturalar');
        }
    }


    public function stored(Request $request)
    {
        $fullname = Input::get('fullname', '');
        $uniqid = Input::get('user_code','');
        $last30type = Input::get('type', '' );
        $status = 12;
        $fullname=explode(' ',$fullname);
        \Artisan::call('cache:clear');
        \Artisan::call('view:clear');
        $data = DB::table('invoices as i')
            ->select('i.user_id','i.width', 'i.height','i.liquid_type', 'i.length', 'i.weight' , 'i.shipping_price','i.purchase_no','i.waybill','i.file','i.id','i.status_id','i.by_bus','i.order_tracking_number',
                'u.uniqid', 'u.name', 'u.surname', 'u.address','u.email','u.pin',
                'c.name as phone', 'i.price', 'p.quantity', 'p.shop_name','p.id as product_id', 'p.comment as comment', 'product_type_name','s.name as invoice_status',
                'cl.name as client_name','cl.surname as client_surname','i.corporate','i.client_id'
            )
            ->leftJoin('users as u', 'i.user_id', '=', 'u.id')
            ->leftJoin('clients as cl', 'i.client_id', '=', 'cl.id')
            ->leftJoin('user_contacts as c', 'c.user_id', '=', 'u.id')
            ->leftJoin('products as p', 'i.product_id', '=', 'p.id')
            ->leftJoin('product_types as pt', 'p.product_type_id', '=', 'pt.id')
            ->leftJoin('statuses as s', 'i.status_id', '=', 's.id')

            ->whereIn('i.status_id',[12,13])
            ->where('i.country_id', '=', $this->country)
            ->where('i.active','=',1);
        if(!empty($uniqid)) $data=$data->where(function($query) use($uniqid){
            $query->where('u.uniqid','=', $uniqid)->orWhere('u.id','=', $uniqid);
        });
        if(!empty($fullname[0])) $data = $data->where('u.name' ,'like','%' . $fullname[0] . '%')->orWhere('u.surname' ,'like','%' . $fullname[0] . '%');
        if(isset($fullname[1]) || !empty($fullname[1])) $data = $data->where('u.name' ,'like','%' . $fullname[1] . '%')->orWhere('u.surname' ,'like','%' . $fullname[1] . '%');
        $data=$data->orderBy('i.user_id','DESC')->get();

        $productTypes = ProductType::all();

        $currency = Currencies::where("name","try-usd")->first();
        $tryToUsd = $currency->val;

        $user_balances_ids = [];
        foreach ($data as $k=>$inv){
                $status = Status::where('label', '=', 'stored_auto')->where('type', '=', 'invoice')->first();
                if(1){
                    if(!isset($user_balances_ids[$inv->user_id][intval($inv->client_id)])){

                        $invoices = Invoice::where("user_id",$inv->user_id)->where("client_id",$inv->client_id)->where("status_id",2)->where("id","!=",$inv->id)->get();

                        $depo_price = 0;
                        foreach ($invoices as $item){
                            $depo_price += $item->shipping_price;
                            if($item->country_id==2){
                                $depo_price += $item->price;
                            }elseif($item->country_id==1){
                                if($currency!=null){
                                    $depo_price += $item->price*$tryToUsd;
                                }
                            }
                        }

                        $userLast30 = userLast30($inv->user_id,intval($inv->client_id));


                        $user_last30_balance = $depo_price + $userLast30;
                        $inv->user_last30 = $user_last30_balance;
                        $data[$k] = $inv;

                        $user_balances_ids[$inv->user_id][$inv->client_id] = $user_last30_balance;
                    }else{
                        $inv->user_last30 = $user_balances_ids[$inv->user_id][intval($inv->client_id)];
                        $data[$k] = $inv;
                    }
                }
        }

        if($request->ajax()) {
            return view('tr.invoice.ajax1',  compact('data' ,'status'));
        } else {
            return view('tr.invoice.stored',  compact('data','status','productTypes','tryToUsd','last30type'));
        }

    }

    public function changePurchase($purchase_no){
        $invoice=Invoice::select('*')->where('purchase_no','=', $purchase_no)->first();

        if($invoice){
            $invoice->added_by='foreign';
            $invoice->save();
            return response()->json([
                'status' => 200,
                'data' => 'ok',
            ]);
        }else{
            return response()->json([
                'status' => 404,
                'data' => 'error',
            ]);
        }
    }
    public function changeVehicle(Request $request)
    {
        $invoice=Invoice::find($request->id);
        if($request->type==0) $invoice->by_bus=1;
        else $invoice->by_bus=0;
        $invoice->save();
        if($invoice) {
            return response()->json([
                'status' => 200,
                'data' => 'ok',
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $uniqid = $request->get('uniqid', false);
        $fullname = $request->get('fullname', false);
        $User = User::with('userContacts','foreignStock','foreignStock.products','foreignStock.products.productTypes')->where('uniqid','=', $uniqid);
        $fullname=explode(' ',$fullname);
        if(!empty($fullname[0])) $User = $User->orWhere('name' ,'like','%' . $fullname[0] . '%')->orWhere('surname' ,'like','%' . $fullname[0] . '%');
        if(isset($fullname[1]) || !empty($fullname[1])) $User = $User->orWhere('name' ,'like','%' . $fullname[1] . '%')->orWhere('surname' ,'like','%' . $fullname[1] . '%');

        $result = $User->first();

        if($result) {
            return response()->json([
                'status' => 200,
                'data' => $result,
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'product_type_name' => 'required',
            'price' => 'required|integer',
            'quantity' => 'required|integer',
            'shop_name' => 'required|string',
            //'invoice' => 'required',
            'order_number' => 'required',
            'order_date' => 'required',
        ];

        $messages = [
            'price.integer' => 'Məhsulun qiyməti xanasına rəqəm daxil edin',
            'quantity.integer' => 'Məhsulun qiyməti xanasına rəqəm daxil edin',
            'product_type_name' => 'Məhsulun tipi xanasını düzgün seçin',
            'required' => 'Bütün xanaları doldurun',
        ];

        Validator::make($request->all(), $rules, $messages);
        $lastPurchase_no = Invoice::select('id')->orderBy('id', 'desc')->first();
        if($lastPurchase_no) {
            $lastPurchase_no =$lastPurchase_no->id;
        }else {
            $lastPurchase_no = 0;
        }

        $client_id = 0;
        if(substr($request->user_code,0,1)==1 and strlen(trim($request->user_code))==7){
            $client_id = intval(substr($request->user_code,1,6));
            $Client = Client::where('id', '=', $client_id)->first();
            if($Client!=null){
                $request->user_code = $Client->uniqid;
            }
        }

        $User = User::where('uniqid', '=', $request->user_code)->where("is_blocked",0)->first();


        $newRelUserProduct = new RelUserProduct();
        $newRelUserProduct->status_id = 1;
        $newRelUserProduct->client_id = $client_id;
        $newRelUserProduct->user_id = $User->id;
        $newRelUserProduct->is_paid = 1;
        $newRelUserProduct->is_ordered = 1;
        $newRelUserProduct->save();
        //
        $newProduct = new Product();
        $newProduct->user_id = $User->id;
        $newProduct->client_id = $client_id;
        $newProduct->region_id = $User->region_id;
        $newProduct->product_type_id = 0;
        $newProduct->admin_id = 16; //Gunay Memmedova
        $newProduct->product_type_name = $request->product_type_name;
        $newProduct->rel_user_product_id = $newRelUserProduct->id;
        $newProduct->price = $request->price;
        $newProduct->quantity = $request->quantity;
        $newProduct->shop_name = $request->shop_name;
        $newProduct->description = '';
        $newProduct->status_id = 2;
        $newProduct->save();
        $file = '';
        if($request['invoice']!=null and $request['invoice']!='undefined'){
            $file = Uploader::upload($request['invoice'], 'public/invoice/', 'invoice', false, true);
            $file = '/storage/invoice/'.$file;
        }


        $newPackage = new Packages();
        $newPackage->status=1;
        $newPackage->save();

        $newInvoice = new Invoice();
        $newInvoice->user_id = $User->id;
        $newInvoice->client_id = $client_id;
        $newInvoice->corporate = $User->corporate;
        $newInvoice->region_id = $User->region_id;
        $newInvoice->product_id = $newProduct->id;
        $newInvoice->price = $request->price;
        $newInvoice->rel_user_product_id = $newRelUserProduct->id;
        $newInvoice->status_id = 1;
        $newInvoice->package_id = $newPackage->id;
        //$newInvoice->purchase_no = str_pad($lastPurchase_no + 1, 9, 0, STR_PAD_LEFT);
        $newInvoice->order_tracking_number = $request->order_number;
        $newInvoice->order_date = $request->order_date;
        $newInvoice->file = $file;
        $newInvoice->added_by = 'depo';



        if ($newInvoice->save()) {
            $newInvoice->purchase_no = str_pad($newInvoice->id, 9, 0, STR_PAD_LEFT);
            $newInvoice->save();

            $depo_package_id = $request->get("depo_package_id",0);
            if($depo_package_id>0){
                $depo_package = DepoPackages::where("id",$depo_package_id)->where("status_id",0)->first();
                if($depo_package!=null){
                    $depo_package->invoice_id = $newInvoice->purchase_no;
                    $depo_package->status_id = 1;
                    $depo_package->save();
                }

            }
            return response()->json(['status' => 200, 'message' => 'Bəyənnamə müvəffəqiyyətlə yükləndi']);
        }
    }

    public function changeStatus_old(Request $request)
    {
        $invoice= Invoice::find($request->id);
        $invoice->status_id = $request->status_id + 1;
        $invoice->save();
        $invoice_date=new InvoiceDates();
        $invoice_date->status_id=2;
        $invoice_date->invoice_id=$request->id;
        $invoice_date->action_date=date('Y-m-d H:i:s');
        $invoice_date->save();

        if($invoice->status_id == 2){
            $contact = UserContact::where('user_id', '=', $invoice->user_id)->first();
            $data = (object) ['text' => 'Baglamaniz Turkiye anbarimiza daxil olub. Baglamanizi https://limak.az vasitesi ile izleye bilersiniz.', "user_id" =>$invoice->user_id];
            smsSend($data, $contact->name);
        }

        if($invoice) {
            return response()->json([
                'status' => 200,
                'data' => 'ok'
            ]);
        }
    }



    public function changeStatus(Request $request)
    {
        $invoice= Invoice::find($request->id);

        if($invoice->liquid_type==1){
            $liquid_type = 1;
            $fatura = Fatura::where("liquid_type",1)->where("status_id",0)->first();

            if($fatura == null){
                $fatura = new Fatura();
                $fatura->liquid_type = $liquid_type;
                $fatura->save();
            }

            $total_count = $fatura->total_count;
            $total_weight = $fatura->total_weight;

            $invoice->fatura_id = $fatura->id;

            $fatura->total_count = $total_count+1;
            $fatura->total_weight = $total_weight+$invoice->weight;
            $fatura->save();
        }

        $invoice->status_id = $request->status_id + 1;
        $invoice->save();
        $invoice_date=new InvoiceDates();
        $invoice_date->status_id=2;
        $invoice_date->invoice_id=$request->id;
        $invoice_date->action_date=date('Y-m-d H:i:s');
        $invoice_date->save();
        $currency = Currencies::where("name","=","try-usd")->first();

        $invoices = Invoice::where("user_id",$invoice->user_id)->where("client_id",$invoice->client_id)->where("status_id",2)->where("id","!=",$request->id)->get();

        $depo_price = 0;
        foreach ($invoices as $item){
            $depo_price += $item->shipping_price;
            if($item->country_id==2){
                $depo_price += $item->price;
            }elseif($item->country_id==1){
                if($currency!=null){
                    $depo_price += $item->price*$currency->val;
                }
            }
        }

        if($invoice->status_id == 2){
            $contact = UserContact::where('user_id', '=', $invoice->user_id)->first();
            $data = (object) ['text' => 'Baglamaniz Turkiye anbarimiza daxil olub. Baglamanizi https://limak.az vasitesi ile izleye bilersiniz.', "user_id" =>$invoice->user_id];
            smsSend($data, $contact->name);

            $user = User::find($invoice->user_id);
            if($user!=null and strlen($user->fcm_token)>6){
                $data = (object) ['text' => 'Bağlamanız Türkiyə anbarımıza daxil olub. Bağlamanızı Bağlamalarım bölməsindən izləyə bilərsiniz.', "user_id" =>$invoice->user_id];
                notificationSendDirect($data,$user->fcm_token);
            }
        }elseif($invoice->status_id==3){
            $user = User::find($invoice->user_id);
            if($user!=null and strlen($user->fcm_token)>6){
                $data = (object) ['text' => 'Bağlamanız Türkiyə anbarımızdan yola çıxdı. Bağlamanızı Bağlamalarım bölməsindən izləyə bilərsiniz.', "user_id" =>$invoice->user_id];
                notificationSendDirect($data,$user->fcm_token);
            }
        }elseif($invoice->status_id==11){
            $user = User::find($invoice->user_id);
            if($user!=null and strlen($user->fcm_token)>6){
                $data = (object) ['text' => 'Bağlamanız gömrük yoxlanışındadır.  Bağlamanızı Bağlamalarım bölməsindən izləyə bilərsiniz.', "user_id" =>$invoice->user_id];
                notificationSendDirect($data,$user->fcm_token);
            }
        }




        if($invoice->shipping_price>0){

            $user_id = $invoice->user_id;
            $client_id = intval($invoice->client_id);
            $user_price = userLast30($user_id,$client_id);
            $all_price = $user_price;

            if($currency!=null){
                $all_price = $invoice->price*$currency->val + $invoice->shipping_price + $user_price;
            }
            $all_price += $depo_price;

            if($all_price>1000){
                $status = Status::where('label', '=', 'stored_auto')->where('type', '=', 'invoice')->first();
                $invoice->status_id = $status->sid;
                $invoice->save();

                $invoice_date=new InvoiceDates();
                $invoice_date->status_id=$status->sid;
                $invoice_date->invoice_id=$request->id;
                $invoice_date->action_date=date('Y-m-d H:i:s');
                $invoice_date->save();

                return response()->json([
                    'status' => 201,
                    'data' => 'ok'
                ]);
            }
        }

        if($invoice) {
            return response()->json([
                'status' => 200,
                'data' => 'ok'
            ]);
        }
    }


    public function changeStatusBack(Request $request)
    {
        $invoice= Invoice::find($request->id);
        /*  $invoice->status_id = $request->status_id + 1;
          $invoice->save();
          $invoice_date=new InvoiceDates();
          $invoice_date->status_id=2;
          $invoice_date->invoice_id=$request->id;
          $invoice_date->action_date=date('Y-m-d H:i:s');
          $invoice_date->save();*/

        /* if($invoice->status_id == 2){
             $contact = UserContact::where('user_id', '=', $invoice->user_id)->first();
             $data = (object) ['text' => 'Baglamaniz Turkiye anbarimiza daxil olub. Baglamanizi https://limak.az vasitesi ile izleye bilersiniz.', "user_id" =>$invoice->user_id];
             smsSend($data, $contact->name);
         }*/
        $status = Status::where('label', '=', 'stored_auto')->where('type', '=', 'invoice')->first();

        if($invoice->shipping_price>0 and $invoice->status_id==$status->sid){

            $user_id = $invoice->user_id;
            $client_id = intval($invoice->client_id);
            $user_price = userLast30($user_id,$client_id);
            $currency = Currencies::where("name","=","try-usd")->first();
            $all_price = $user_price;
            if($currency!=null){
                $all_price = $invoice->price*$currency->val + $invoice->shipping_price + $user_price;
            }
            if($all_price<=1000){
                $status = Status::where('label', '=', 'foreign')->where('type', '=', 'invoice')->first();
                $invoice->status_id = $status->sid;
                $invoice->save();

                $invoice_date=new InvoiceDates();
                $invoice_date->status_id=$status->sid;
                $invoice_date->invoice_id=$request->id;
                $invoice_date->action_date=date('Y-m-d H:i:s');
                $invoice_date->save();
            }
        }

        if($invoice) {
            return response()->json([
                'status' => 200,
                'data' => 'ok'
            ]);
        }
    }

    public function user(Request $request)
    {
        $user_id = $request->id;
        echo $this->last30DaysBalance($user_id); exit;
    }

    protected function last30DaysBalance($user_id,$client_id=0)
    {
        $last30day_payment_row = DB::table('invoices')
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
            ->where('invoices.user_id','=',$user_id)
            ->where('invoices.client_id','=',$client_id)
            ->first();
        $currency = Currencies::where("name","=","try-usd")->first();
        $last30day_payment = 0;
        if($last30day_payment_row!=null){
            $last30day_payment = $last30day_payment_row->sh_price;
            if($currency!=null){
                $last30day_payment += $currency->val*$last30day_payment_row->s_price;
            }
        }

        return  $last30day_payment;
    }

    public function changeAllStatuses(Request $request)
    {
        foreach ($request->ids_array as $id){
            $invoice = Invoice::find($id);
            $invoice->status_id=$invoice->status_id+1;
            $invoice->save();

            $invoice_date=new InvoiceDates();
            $invoice_date->status_id=3;
            $invoice_date->invoice_id=$id;
            $invoice_date->action_date=date('Y-m-d H:i:s');
            $invoice_date->save();
        }
        $data='ok';
        if($data) {
            return response()->json([
                'status' => 200,
                'data' => $data,
            ]);
        }
    }

    public function addCustomStatus(Request $request)
    {
        foreach ($request->ids_array as $id){
            $invoice = Invoice::find($id);
            $invoice->status_id=11;
            $invoice->save();

            $invoice_date=new InvoiceDates();
            $invoice_date->status_id=11;
            $invoice_date->invoice_id=$id;
            $invoice_date->action_date=date('Y-m-d H:i:s');
            $invoice_date->save();
        }
        $data='ok';
        if($data) {
            return response()->json([
                'status' => 200,
                'data' => $data,
            ]);
        }
    }


    public function addFatura(Request $request)
    {
        if(count($request->ids_array)>0){
            $fatura_id = $request->fatura_id;
            $limit = 145;
            $liquid_type = 0;

            $fatura = Fatura::where("id",$fatura_id)->where("status_id",0)->first();

            if($fatura_id==-1){
                $liquid_type = 1;
                $fatura = Fatura::where("liquid_type",1)->where("status_id",0)->first();
            }

            if($fatura == null){
                $fatura = new Fatura();
                $fatura->liquid_type = $liquid_type;
                $fatura->save();
            }

            $total_count = $fatura->total_count;
            $total_weight = $fatura->total_weight;

            foreach ($request->ids_array as $id){
                $invoice = Invoice::find($id);
                $total_count = $total_count + 1;
                $total_weight = $total_weight + $invoice->weight;
                $invoice->fatura_id= $fatura->id;
                $invoice->save();
            }

            $fatura->total_count = $total_count;
            $fatura->total_weight = $total_weight;
            $fatura->save();
        }


        $data='ok';
        if($data) {
            return response()->json([
                'status' => 200,
                'data' => $data,
            ]);
        }
    }


    public function removeFatura(Request $request)
    {
        foreach ($request->ids_array as $id){
            $invoice = Invoice::where("id",$id)->where("fatura_id",">",0)->first();
            if($invoice!=null){
                $fatura = Fatura::where("id",$invoice->fatura_id)->first();
                if($fatura!=null){
                    $fatura->total_count = $fatura->total_count-1;
                    $fatura->total_weight = $fatura->total_weight-$invoice->weight;
                    $fatura->save();

                    if($fatura->total_count==0){
                        $fatura->delete();
                    }

                }
                $invoice->fatura_id= 0;
                $invoice->save();
            }

        }

        $data='ok';
        if($data) {
            return response()->json([
                'status' => 200,
                'data' => $data,
            ]);
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

    private function checkRole ($label) {
        $status = false;
        foreach (Auth::guard('admin')->user()->relAdminRoles as $value) {
            if($value->relRole->label === $label) {
                $status = true;
            }
        }
        return $status;
    }

    private function inStorage($pin, $tel, $label, $user_code) {

        if($tel || $pin || $user_code) {
            $data = Invoice::with('invoiceStatus')->whereHas('invoiceStatus', function ($query) use ($label) {
                $query->where('label', '=', $label);
            })->with('products.productTypes')->with('relUserProducts.users')->whereHas('relUserProducts.users', function($query) use($user_code) {
                if($user_code) {
                    $query->where('uniqid', 'like','%' . $user_code .'%');
                }
            })->where('active', '=', '1')->with('relUserProducts.users.userContacts')->whereHas('relUserProducts.users.userContacts', function ($query) use ($tel, $pin) {
                if ($pin) {
                    $query->where('pin', '=', $pin)->where('name', 'like', '%' . $tel .'%');
                } else {
                    $query->where('name', 'like', '%' . $tel .'%');
                }
            })->orderBy('created_at', 'DESC')->paginate(50);
        } else{
            $data = Invoice::with('invoiceStatus')->whereHas('invoiceStatus', function ($query) use ($label) {
                $query->where('label', '=', $label);
            })->where('active', '=', '1')->with('products.productTypes')->with('relUserProducts.users.userContacts')->orderBy('created_at', 'DESC')->paginate(50);
        }
        return $data;
    }

    private function waiting($pin, $tel, $user_code, $invoice) {
        $label = 'waiting';
        if($tel || $pin || $user_code) {
            $data = Invoice::with('invoiceStatus')->whereHas('invoiceStatus', function ($query) use ($label) {
                $query->where('label', '=', $label);
            })->where('active', '=', '1')->with('products.productTypes')->with('relUserProducts.users')->whereHas('relUserProducts.users', function($query) use($user_code) {
                if($user_code) {
                    $query->where('uniqid', 'like','%' . $user_code .'%');
                }
            })->with('relUserProducts.users.userContacts')->whereHas('relUserProducts.users.userContacts', function ($query) use ($tel, $pin) {
                if ($pin) {
                    $query->where('pin', '=', $pin)->where('name', 'like', '%' . $tel .'%');
                } else {
                    $query->where('name', 'like', '%' . $tel .'%');
                }
            })->where('file', $invoice, NULL)->orderBy('created_at', 'DESC')->paginate(50);
        } else{
            $data = Invoice::with('invoiceStatus')->whereHas('invoiceStatus', function ($query) use ($label) {
                $query->where('label', '=', $label);
            })->where('active', '=', '1')->where('file', $invoice, NULL)->with('products.productTypes')->with('relUserProducts.users.userContacts')->orderBy('created_at', 'DESC')->paginate(50);
        }
        return $data;
    }

    private function inRoad($pin, $tel, $user_code) {
        $label = 'on_the_way';
        if($tel || $pin || $user_code) {
            $data = Invoice::with('invoiceStatus')->whereHas('invoiceStatus', function ($query) use ($label) {
                $query->where('label', '=', $label);
            })->where('active', '=', '1')->with('products.productTypes')->with('relUserProducts.users')->whereHas('relUserProducts.users', function($query) use($user_code) {
                if($user_code) {
                    $query->where('uniqid', 'like','%' . $user_code .'%');
                }
            })
                ->with('relUserProducts.users.userContacts')->whereHas('relUserProducts.users.userContacts', function ($query) use ($tel, $pin) {
                    if ($pin) {
                        $query->where('pin', '=', $pin)->where('name', 'like', '%' . $tel .'%');
                    } else {
                        $query->where('name', 'like', '%' . $tel .'%');
                    }
                })->orderBy('created_at', 'DESC')->paginate(50);
        } else{
            $data = Invoice::with('invoiceStatus')->whereHas('invoiceStatus', function ($query) use ($label) {
                $query->where('label', '=', $label);
            })->where('active', '=', '1')->with('products.productTypes')->with('relUserProducts.users.userContacts')->orderBy('created_at', 'DESC')->paginate(50);
        }
        return $data;
    }

    public function setInvoiceDataByBuyer (Request $request) {
        $productIds = $request['productIds'];
        $package= new Packages();
        $package->status = 1;
        $package->save();
        $i = 0;

        foreach($productIds as $productId){
            $productModel = Product::find($productId);

            $lastPurchase_no = Invoice::select('id')->orderBy('id', 'desc')->first();
            if($lastPurchase_no) {
                $lastPurchase_no =$lastPurchase_no->id;
            }else {
                $lastPurchase_no = 0;
            }

            $productModel->shop_name          = $request['shop'];
            $productModel->quantity           = $request['packageCount'];
            $productModel->product_type_name  = $request['productType'];
            $productModel->price              = $request['productPrice'];
            $productModel->status_id          = 2;
            $productModel->is_ordered         = 1;
            $productModel->save();


            $invoiceModel = new Invoice();
            $invoiceModel->user_id               = $productModel->user_id;
            $invoiceModel->product_id            = $productModel->id;
            $invoiceModel->rel_user_product_id   = $productModel->rel_user_product_id;
            $invoiceModel->status_id             = 1;
            $invoiceModel->purchase_no           = str_pad($lastPurchase_no + 1, 9, 0, STR_PAD_LEFT);
            $invoiceModel->order_tracking_number = $request['orderTrackingNumber'];
            $invoiceModel->order_date            = $request['orderDate'];
            $invoiceModel->package_id            = $package->id;
            if($i != 0) $invoiceModel->active = 0;
            $invoiceModel->save();

            $i++;
        }

        if (true) {
            return response()->json([
                'status'  => 200,
                'message' => 'Bəyənnamə yükləndi!'
            ]);
        } else {
            return response()->json([
                'status'  => 500,
                'message' => 'Server xətası!'
            ]);
        }
    }

    public function updateInvoiceDataByBuyer (Request $request) {
        $productId = $request['productId'];

        $productModel = Product::find($productId);

        $productModel->shop_name          = $request['shop'];
        $productModel->quantity           = $request['packageCount'];
        $productModel->product_type_name  = $request['productType'];
        $productModel->price              = $request['productPrice'];
        $productModel->save();

        $invoiceModel = $productModel->invoices[0];

        $invoiceModel->order_tracking_number = $request['orderTrackingNumber'];
        $invoiceModel->order_date            = $request['orderDate'];

        if ($invoiceModel->save()) {
            return response()->json([
                'status'  => 200,
                'message' => 'Bəyənnamə müvəffəqiyyətlə dəyişdirildi!'
            ]);
        } else {
            return response()->json([
                'status'  => 500,
                'message' => 'Server xətası!'
            ]);
        }
    }

    public function addToOnTheWay (Request $request) {
        $status = Status::where('label', '=', 'on_the_way')->where('type', '=', 'invoice')->get();
        $invoice = Invoice::find($request->id);
        $invoice->status_id = $status[0]->id;
        $invoice->save();
        return response()->json(['status' => 'ok', 'code' => 200]);
    }

    public function addToForeignStorage (Request $request) {
        $status = Status::where('label', '=', 'foreign')->where('type', '=', 'invoice')->get();
        $invoice = Invoice::find($request->id);
        $invoice->status_id = $status[0]->id;
        $invoice->save();
        return response()->json(['status' => 'ok', 'code' => 200]);
    }

    public function addToStorage (Request $request) {
        $status = Status::where('label', '=', 'home')->where('type', '=', 'invoice')->get();
        $invoice = Invoice::find($request->id);
        $invoice->status_id = $status[0]->id;
        $invoice->save();
        return response()->json(['status' => 'ok', 'code' => 200]);
    }

    public function addToCompleted (Request $request) {
        $status = Status::where('label', '=', 'completed')->where('type', '=', 'invoice')->get();
        $invoice = Invoice::find($request->id);
        $invoice->status_id = $status[0]->id;
        $invoice->save();
        return response()->json(['status' => 'ok', 'code' => 200]);
    }

    public function sendInvoicelessMessage(Request $request) {

        $user = User::where('uniqid', $request->user_uid)->first();
        if($user)
            notify((object)['template' => 'invoice-id-absent','user_id' => $user->id], (object)['phone' => $user->userContacts[0]->name, 'email' => $user->email]);
        else
            return response()->json(['data'=>'error', 'code'=>404]);

        if($request->ajax()) {
            return response()->json('OK', 200);
        } else {
            return back();
        }


        $data = new Invoiceless();
        $data->note = $note;
        $data->user_uid = $user_uid;
        $data->save();
        return response()->json(["data" => "ok", "code" => 200]);
    }

    public function waybill($id){

        $invoice = DB::table('invoices as i')
            ->select('i.width', 'i.height', 'i.length', 'i.weight' , 'i.shipping_price','i.purchase_no',
                'u.uniqid', 'u.name', 'u.surname', 'u.address',
                'c.name as phone', 'i.price', 'p.quantity', 'p.shop_name', 'product_type_name'
            )
            ->leftJoin('rel_user_products as r', 'r.id', '=', 'i.rel_user_product_id')
            ->leftJoin('users as u', 'r.user_id', '=', 'u.id')
            ->leftJoin('user_contacts as c', 'c.user_id', '=', 'u.id')
            ->leftJoin('products as p', 'i.product_id', '=', 'p.id')
            ->where('i.id', '=', $id)
            ->first();

        $currency = Currency::find(1);

        $tryToUsd = 1/$currency->tl * $currency->usd;

        return view('admin.invoice.waybill', ['data'=>$invoice, 'tryToUsd' => $tryToUsd]);
    }

    public function waybillNew($id){

        $invoice = DB::table('invoices as i')
            ->select('i.width', 'i.height', 'i.length', 'i.weight' , 'i.shipping_price','i.purchase_no',
                'u.uniqid', 'u.name', 'u.surname', 'u.address',
                'c.name as phone', 'i.price', 'p.quantity', 'p.shop_name', 'product_type_name',
                'cl.name as client_name','cl.surname as client_surname','cl.id as client_id','cl.phone as client_phone','cl.address as client_address'
            )
            ->leftJoin('rel_user_products as r', 'r.id', '=', 'i.rel_user_product_id')
            ->leftJoin('users as u', 'r.user_id', '=', 'u.id')
            ->leftJoin('user_contacts as c', 'c.user_id', '=', 'u.id')
            ->leftJoin('clients as cl', 'i.client_id', '=', 'cl.id')
            ->leftJoin('products as p', 'i.product_id', '=', 'p.id')
            ->where('i.id', '=', $id)
            ->first();

        $currency = Currency::find(1);

        $tryToUsd = 1/$currency->tl * $currency->usd;
        $barcode_code = "trlmk".$id;
        $barcode = DNS1D::getBarcodeSVG($barcode_code, "C128",1.5,64);
        return view('tr.invoice.waybill', ['data'=>$invoice,'barcode' => $barcode, 'barcode_code' => $barcode_code, 'tryToUsd' => $tryToUsd]);
    }

    public function waybillFatura($id){
        $fatura_id = 0;
        $fatura = Fatura::where("id",$id)->where("status_id","!=",2)->first();
        if($fatura!=null){
            $fatura_id = $id;
        }
        $invoices = DB::table('invoices as i')
            ->select('i.id','i.width', 'i.height', 'i.length', 'i.weight' , 'i.shipping_price','i.purchase_no',
                'u.uniqid', 'u.name', 'u.surname', 'u.address',
                'c.name as phone', 'i.price', 'p.quantity', 'p.shop_name', 'product_type_name'
            )
            ->leftJoin('rel_user_products as r', 'r.id', '=', 'i.rel_user_product_id')
            ->leftJoin('users as u', 'r.user_id', '=', 'u.id')
            ->leftJoin('user_contacts as c', 'c.user_id', '=', 'u.id')
            ->leftJoin('products as p', 'i.product_id', '=', 'p.id')
            ->where('i.fatura_id', '=', $fatura_id)
            ->where('i.status_id', '=', 2)
            ->get();

        $currency = Currency::find(1);

        $tryToUsd = 1/$currency->tl * $currency->usd;

        return view('tr.invoice.waybillFatura', ['invoices'=>$invoices, 'tryToUsd' => $tryToUsd]);
    }

    public function waybillFaturaOld($id){
        $fatura_id = 0;
        $fatura = Fatura::where("id",$id)->where("status_id","!=",2)->first();
        if($fatura!=null){
            $fatura_id = $id;
        }
        $invoices = DB::table('invoices as i')
            ->select('i.width', 'i.height', 'i.length', 'i.weight' , 'i.shipping_price','i.purchase_no',
                'u.uniqid', 'u.name', 'u.surname', 'u.address',
                'c.name as phone', 'i.price', 'p.quantity', 'p.shop_name', 'product_type_name'
            )
            ->leftJoin('rel_user_products as r', 'r.id', '=', 'i.rel_user_product_id')
            ->leftJoin('users as u', 'r.user_id', '=', 'u.id')
            ->leftJoin('user_contacts as c', 'c.user_id', '=', 'u.id')
            ->leftJoin('products as p', 'i.product_id', '=', 'p.id')
            ->where('i.fatura_id', '=', $fatura_id)
            ->get();

        $currency = Currency::find(1);

        $tryToUsd = 1/$currency->tl * $currency->usd;

        return view('admin.invoice.waybillFatura', ['invoices'=>$invoices, 'tryToUsd' => $tryToUsd]);
    }

    public function waybill2($id){

        $invoice = DB::table('invoices as i')
            ->select('i.width', 'i.height', 'i.length', 'i.weight' , 'i.shipping_price','i.purchase_no',
                'u.uniqid', 'u.name', 'u.surname', 'u.address',
                'c.name as phone', 'i.price', 'p.quantity', 'p.shop_name', 'product_type_name'
            )
            ->leftJoin('rel_user_products as r', 'r.id', '=', 'i.rel_user_product_id')
            ->leftJoin('users as u', 'r.user_id', '=', 'u.id')
            ->leftJoin('user_contacts as c', 'c.user_id', '=', 'u.id')
            ->leftJoin('products as p', 'i.product_id', '=', 'p.id')
            ->where('i.id', '=', $id)
            ->first();

        $currency = Currency::find(1);

        $tryToUsd = 1/$currency->tl * $currency->usd;
        return view('admin.invoice.waybill', ['data'=>$invoice, 'tryToUsd' => $tryToUsd]);
    }

    public function xml(){

        \Artisan::call('cache:clear');
        \Artisan::call('view:clear');
        $invoice = DB::table('invoices as i')
            ->select('i.width', 'i.height', 'i.length', 'i.weight' , 'i.shipping_price','i.purchase_no','i.waybill',
                'u.uniqid', 'u.name', 'u.surname', 'u.address',
                'c.name as phone', 'p.price', 'p.quantity', 'p.shop_name', 'product_type_name'
            )
            ->leftJoin('rel_user_products as r', 'r.id', '=', 'i.rel_user_product_id')
            ->leftJoin('users as u', 'r.user_id', '=', 'u.id')
            ->leftJoin('user_contacts as c', 'c.user_id', '=', 'u.id')
            ->leftJoin('products as p', 'i.product_id', '=', 'p.id')
            ->where('i.status_id', '=', 3)
            ->where('i.by_bus', '=', 0)
            ->where('i.country_id', '=', $this->country)
            ->get();


        $currency = Currency::find(1);

        $tryToUsd = 1/$currency->tl * $currency->usd;


        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="yes"?><GoodsInfo></GoodsInfo>');
        //$xml->addAttribute('version', '1.0');
        foreach ($invoice as $item){
            $Goods = $xml->addChild('GOODS');
            $Goods->addChild('TR_NUMBER', $item->purchase_no);
            $Goods->addChild('DIRECTION', 1);
            $Goods->addChild('QUANTITY_OF_GOODS', $item->quantity);
            $Goods->addChild('WEIGHT_GOODS', $item->weight);
            $Goods->addChild('INVOYS_PRICE', round($item->price / $tryToUsd, 2));
            $Goods->addChild('CURRENCY_TYPE', 840);
            $Goods->addChild('NAME_OF_GOODS', htmlspecialchars($item->product_type_name));
            $Goods->addChild('IDXAL_NAME', $item->name." ".$item->surname);
            $Goods->addChild('IDXAL_ADRESS', $item->address);
            $Goods->addChild('IXRAC_NAME', htmlspecialchars($item->shop_name));
            $Goods->addChild('IXRAC_ADRESS', 'Istanbul');
            $Goods->addChild('GOODS_TRAFFIC_FR', 792);
            $Goods->addChild('GOODS_TRAFFIC_TO', '031');
            $Goods->addChild('QAIME', $item->waybill);
        }


        $response = Response::make($xml->asXML(), 200);
        $response->header('Content-Description', 'File Transfer');
        $response->header('Content-Disposition', 'attachment; filename=Inv-'.date('d-m-Y').'.xml');
        $response->header('Content-Type', 'text/xml');

        return $response;
    }

    public function manifest(){


        \Artisan::call('cache:clear');
        \Artisan::call('view:clear');
        $invoice = DB::table('invoices as i')
            ->select('i.width', 'i.height', 'i.length', 'i.weight' , 'i.shipping_price','i.purchase_no','i.waybill','i.order_tracking_number',
                'u.uniqid', 'u.name', 'u.pin','u.surname', 'u.address', 'u.serial_number','u.birthdate','u.id as user_id',
                'c.name as phone', 'p.price', 'p.quantity', 'p.shop_name', 'product_type_name','p.description'
            )
            ->leftJoin('rel_user_products as r', 'r.id', '=', 'i.rel_user_product_id')
            ->leftJoin('users as u', 'r.user_id', '=', 'u.id')
            ->leftJoin('user_contacts as c', 'c.user_id', '=', 'u.id')
            ->leftJoin('products as p', 'i.product_id', '=', 'p.id')
            ->leftJoin('product_types as pt', 'p.product_type_id', '=', 'pt.id')
            ->where('i.status_id', '=', 3)
            ->where('i.country_id', '=', $this->country)
            ->get();

        $data_users=[];
        foreach ($invoice as $item){
            $data_users[] = $item->user_id;
        }


        $currency = Currency::find(1);

        $tryToUsd = 1/$currency->tl * $currency->usd;

        $minus30=date('Y-m-d', strtotime('-30 day', strtotime(date('Y-m-d'))));
        $last30days=DB::table('invoices as i')
            ->select(DB::raw('SUM(i.shipping_price) as shipping_price'), DB::raw('SUM(p.price) as price'), 'r.user_id')
            ->leftJoin('rel_user_products as r', 'r.id', '=', 'i.rel_user_product_id')
            ->leftJoin('products as p', 'i.product_id', '=', 'p.id')
            ->where('i.order_date', '>', $minus30)
            ->where('i.country_id', '=', $this->country)
            ->whereIN('r.user_id', array_unique($data_users))
            ->groupBy('r.user_id')
            ->get();


        foreach ($last30days as $item){
            $data_users[$item->user_id] = number_format($item->price / $tryToUsd + $item->shipping_price, 2);
        }

//        dd($last30days);

        return view('tr.manifest', [
            'invoices'=>$invoice,
            'tryToUsd' => $tryToUsd,
            'last30days' => $data_users
        ]);
    }


    public function manifest2(){


        \Artisan::call('cache:clear');
        \Artisan::call('view:clear');
        $invoice = DB::table('invoices as i')
            ->select('i.width', 'i.height', 'i.length', 'i.weight' , 'i.shipping_price','i.purchase_no','i.waybill','i.order_tracking_number',
                'u.uniqid', 'u.name', 'u.surname', 'u.pin', 'u.address', 'u.serial_number','u.birthdate','u.id as user_id',
                'c.name as phone', 'p.price', 'p.quantity', 'p.shop_name', 'p.product_type_name','p.description',
                'pt.name as pt_name'
            )
            ->leftJoin('rel_user_products as r', 'r.id', '=', 'i.rel_user_product_id')
            ->leftJoin('users as u', 'r.user_id', '=', 'u.id')
            ->leftJoin('user_contacts as c', 'c.user_id', '=', 'u.id')
            ->leftJoin('products as p', 'i.product_id', '=', 'p.id')
            ->leftJoin('product_types as pt', 'p.product_type_id', '=', 'pt.id')
            ->where('i.status_id', '=', 3)
            ->where('i.country_id', '=', $this->country)
            ->orderBy('i.user_id', 'DESC')
            ->get();

        $data_users=[];
        foreach ($invoice as $item){
            $data_users[] = $item->user_id;
        }


        $currency = Currency::find(1);

        $tryToUsd = 1/$currency->tl * $currency->usd;

        $minus30=date('Y-m-d', strtotime('-30 day', strtotime(date('Y-m-d'))));
        $last30days=DB::table('invoices as i')
            ->select(DB::raw('SUM(i.shipping_price) as shipping_price'), DB::raw('SUM(p.price) as price'), 'r.user_id')
            ->leftJoin('rel_user_products as r', 'r.id', '=', 'i.rel_user_product_id')
            ->leftJoin('products as p', 'i.product_id', '=', 'p.id')
            ->where('i.order_date', '>', $minus30)
            ->where('i.country_id', '=', $this->country)
            ->whereIN('r.user_id', array_unique($data_users))
            ->groupBy('r.user_id')
            ->get();


        foreach ($last30days as $item){
            $data_users[$item->user_id] = number_format($item->price / $tryToUsd + $item->shipping_price, 2);
        }

        return view('tr.manifest', [
            'invoices'=>$invoice,
            'tryToUsd' => $tryToUsd,
            'last30days' => $data_users
        ]);
    }

    public function mayeler(Request $request){


        \Artisan::call('cache:clear');
        \Artisan::call('view:clear');

        $region_id = $request->get("region_id",1);
        $country_id = $request->get("country_id",1);
        $region = DB::table("regions")->where("id",$region_id)->first();
        $country = DB::table("countries")->where("id",$country_id)->first();
        $invoice = DB::table('invoices as i')
            ->select('i.id','i.width', 'i.height', 'i.length', 'i.weight' , 'i.shipping_price','i.purchase_no','i.waybill','i.order_tracking_number','i.price',
                'u.uniqid','u.pin' ,'u.name', 'u.surname', 'u.address', 'u.serial_number','u.birthdate','u.id as user_id','u.email',
                'c.name as phone', 'p.quantity', 'p.shop_name', 'product_type_name','p.description',
                'cl.id as client_id','cl.name as client_name','cl.surname as client_surname','cl.serial_number as client_serial_number','cl.pin as cl_pin','cl.phone as client_phone','cl.email as client_email','cl.address as client_address',
                'f.updated_at as way_date'
            )
            ->leftJoin('users as u', 'i.user_id', '=', 'u.id')
            ->leftJoin('user_contacts as c', 'c.user_id', '=', 'u.id')
            ->leftJoin('products as p', 'i.product_id', '=', 'p.id')
            ->leftJoin('clients as cl', 'cl.id', '=', 'i.client_id')
            ->leftJoin('fatura as f', 'f.id', '=', 'i.fatura_id')
            ->where('i.status_id', '=', 3)
            //->where('i.s_id', '=', 1)
            ->where('i.country_id', '=', 1)
            //->where('i.region_id', '=', $region_id)
            ->where('i.liquid_type', '=', 1)
            ->where('i.active', '=', 1)
            ->orderBy('i.fatura_id', 'ASC')
            ->orderBy('u.name', 'ASC')
            ->orderBy('u.surname', 'ASC')
            ->get();


        $data_users=[];
        foreach ($invoice as $item){
            $data_users[] = $item->user_id;
        }

        $currency = Currency::find(1);

        $tryToUsd = 1/$currency->tl * $currency->usd;

        $json = file_get_contents('https://api.exchangeratesapi.io/latest?symbols=TRY');

        $json_array = json_decode($json, true);

        $tryToEur = round(1/$json_array["rates"]["TRY"],2);
        return view('cp.custom.mayeler', [
            'invoices'=>$invoice,
            'region' => $region,
            'country' => $country,
            'tryToUsd' => $tryToUsd,
            'tryToEur' => $tryToEur,
        ]);
    }

    public function logout(){
        if(isset($_SERVER['PHP_AUTH_USER']))
            unset($_SERVER['PHP_AUTH_USER']);

        if (isset($_SERVER['PHP_AUTH_PW']))
            unset($_SERVER['PHP_AUTH_PW']);
        return redirect()->to('https://www.limak.az/');
    }
}
