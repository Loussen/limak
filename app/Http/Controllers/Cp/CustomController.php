<?php

namespace App\Http\Controllers\cp;

use App\Currency;
use App\Invoice;
use App\Invoiceless;
use App\Libraries\Upload\Uploader;
use App\ModelCountry\Country;
use App\ModelCountry\Regions;
use App\ModelProduct\Product;
use App\ModelProduct\ProductType;
use App\ModelUser\User;
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

class CustomController extends Controller
{
    public function xml(){

        \Artisan::call('cache:clear');
        \Artisan::call('view:clear');
        $invoice = DB::table('invoices as i')
            ->select('i.id','i.width', 'i.height', 'i.length', 'i.weight' , 'i.shipping_price','i.purchase_no','i.waybill','i.order_tracking_number','i.price','i.corporate',
                'u.uniqid', 'u.name', 'u.surname', 'u.address', 'u.pin', 'c.name as phone',
                'c.name as phone', 'p.quantity', 'p.shop_name', 'product_type_name',
                'cl.id as client_id','cl.name as client_name','cl.surname as client_surname','cl.serial_number as client_serial_number','cl.pin as client_pin','cl.phone as client_phone','cl.email as cl_email','cl.address as client_address',
                'pe.auto_id as person_id','pe.name as person_name','pe.surname as person_surname','pe.serial_number as person_serial_number','pe.pin as person_pin','pe.phone as person_phone','pe.email as person_email','pe.address as person_address'
            )
            ->leftJoin('users as u', 'i.user_id', '=', 'u.id')
            ->leftJoin('user_contacts as c', 'c.user_id', '=', 'u.id')
            ->leftJoin('products as p', 'i.product_id', '=', 'p.id')
            ->leftJoin('clients as cl', 'cl.id', '=', 'i.client_id')
            ->leftJoin('persons as pe', 'pe.id', '=', 'i.person_id')
            ->where('i.country_id', '=', 1)
            ->where('i.status_id', '=', 3)
            //->where('i.s_id', '=', 1)
            //->where('i.liquid_type', '=', 0)
            ->where('i.active', '=', '1')
           //->where('i.id', '=', '1127')
            ->get();
//        dd($invoice);
        $currency = Currency::find(1);

        $tryToUsd = 1/$currency->tl * $currency->usd;

        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="yes"?><GoodsInfo></GoodsInfo>');
        //$xml->addAttribute('version', '1.0');

        foreach ($invoice as $item){
            $barcode_code = "trlmk".$item->id;
            $purchase_no = str_pad($item->id, 9, 0, STR_PAD_LEFT);
            $Goods = $xml->addChild('GOODS');
            $Goods->addChild('TR_NUMBER', $purchase_no);
            $Goods->addChild('DIRECTION', 1);
            $Goods->addChild('QUANTITY_OF_GOODS', 1);
            //$Goods->addChild('QUANTITY_OF_GOODS', $item->quantity);
            $Goods->addChild('WEIGHT_GOODS', $item->weight);
            $Goods->addChild('INVOYS_PRICE', round($item->price / $tryToUsd, 2) + $item->shipping_price);
            $Goods->addChild('CURRENCY_TYPE', 840);
            $Goods->addChild('NAME_OF_GOODS', htmlspecialchars($item->product_type_name));
            if($item->corporate==1 and $item->client_id>0){
                $Goods->addChild('IDXAL_NAME', $item->client_name." ".$item->client_surname);
                $Goods->addChild('IDXAL_ADRESS', $item->client_address);
            }elseif($item->person_id>0){
                $Goods->addChild('IDXAL_NAME', $item->person_name." ".$item->person_surname);
                $Goods->addChild('IDXAL_ADRESS', $item->person_address);
            }
            else{
                $Goods->addChild('IDXAL_NAME', $item->name." ".$item->surname);
                $Goods->addChild('IDXAL_ADRESS', $item->address);
            }


            $Goods->addChild('IXRAC_NAME', htmlspecialchars($item->shop_name));
            $Goods->addChild('IXRAC_ADRESS', 'Istanbul');
            $Goods->addChild('GOODS_TRAFFIC_FR', 792);
            $Goods->addChild('GOODS_TRAFFIC_TO', '031');
            $Goods->addChild('QAIME', $barcode_code);
            $Goods->addChild('TRACKING_NO', htmlspecialchars($item->order_tracking_number));
            if($item->corporate==1 and $item->client_id>0) {
                $Goods->addChild('FIN', $item->client_pin);
                $Goods->addChild('PHONE', $item->client_phone);
            }elseif($item->person_id>0){
                $Goods->addChild('FIN', $item->person_pin);
                $Goods->addChild('PHONE', $item->person_phone);
            }
            else{
                $Goods->addChild('FIN', $item->pin);
                $Goods->addChild('PHONE', $item->phone);
            }

        }

        $response = Response::make($xml->asXML(), 200);
        $response->header('Content-Description', 'File Transfer');
        $response->header('Content-Disposition', 'attachment; filename=Inv-'.date('d-m-Y').'.xml');
        $response->header('Content-Type', 'text/xml');

        return $response;
    }

    public function xmlLiquid(){

        \Artisan::call('cache:clear');
        \Artisan::call('view:clear');
        $invoice = DB::table('invoices as i')
            ->select('i.id','i.width', 'i.height', 'i.length', 'i.weight' , 'i.shipping_price','i.purchase_no','i.waybill','i.order_tracking_number','i.price','i.corporate',
                'u.uniqid', 'u.name', 'u.surname', 'u.address', 'u.pin', 'c.name as phone',
                'c.name as phone', 'p.quantity', 'p.shop_name', 'product_type_name',
                'cl.id as client_id','cl.name as client_name','cl.surname as client_surname','cl.serial_number as client_serial_number','cl.pin as client_pin','cl.phone as client_phone','cl.email as cl_email','cl.address as client_address',
                'pe.auto_id as person_id','pe.name as person_name','pe.surname as person_surname','pe.serial_number as person_serial_number','pe.pin as person_pin','pe.phone as person_phone','pe.email as person_email','pe.address as person_address'
            )
            ->leftJoin('users as u', 'i.user_id', '=', 'u.id')
            ->leftJoin('user_contacts as c', 'c.user_id', '=', 'u.id')
            ->leftJoin('products as p', 'i.product_id', '=', 'p.id')
            ->leftJoin('clients as cl', 'cl.id', '=', 'i.client_id')
            ->leftJoin('persons as pe', 'pe.id', '=', 'i.person_id')
            ->where('i.country_id', '=', 1)
            ->where('i.status_id', '=', 3)
            //->where('i.s_id', '=', 1)
            ->where('i.liquid_type', '=', 1)
            ->where('i.active', '=', '1')
            //->where('i.id', '=', '1127')
            ->get();
//        dd($invoice);
        $currency = Currency::find(1);

        $tryToUsd = 1/$currency->tl * $currency->usd;

        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="yes"?><GoodsInfo></GoodsInfo>');
        //$xml->addAttribute('version', '1.0');

        foreach ($invoice as $item){
            $barcode_code = "trlmk".$item->id;
            $purchase_no = str_pad($item->id, 9, 0, STR_PAD_LEFT);
            $Goods = $xml->addChild('GOODS');
            $Goods->addChild('TR_NUMBER', $purchase_no);
            $Goods->addChild('DIRECTION', 1);
            $Goods->addChild('QUANTITY_OF_GOODS', 1);
            //$Goods->addChild('QUANTITY_OF_GOODS', $item->quantity);
            $Goods->addChild('WEIGHT_GOODS', $item->weight);
            $Goods->addChild('INVOYS_PRICE', round($item->price / $tryToUsd, 2) + $item->shipping_price);
            $Goods->addChild('CURRENCY_TYPE', 840);
            $Goods->addChild('NAME_OF_GOODS', htmlspecialchars($item->product_type_name));
            if($item->corporate==1 and $item->client_id>0){
                $Goods->addChild('IDXAL_NAME', $item->client_name." ".$item->client_surname);
                $Goods->addChild('IDXAL_ADRESS', $item->client_address);
            }elseif($item->person_id>0){
                $Goods->addChild('IDXAL_NAME', $item->person_name." ".$item->person_surname);
                $Goods->addChild('IDXAL_ADRESS', $item->person_address);
            }
            else{
                $Goods->addChild('IDXAL_NAME', $item->name." ".$item->surname);
                $Goods->addChild('IDXAL_ADRESS', $item->address);
            }


            $Goods->addChild('IXRAC_NAME', htmlspecialchars($item->shop_name));
            $Goods->addChild('IXRAC_ADRESS', 'Istanbul');
            $Goods->addChild('GOODS_TRAFFIC_FR', 792);
            $Goods->addChild('GOODS_TRAFFIC_TO', '031');
            $Goods->addChild('QAIME', $barcode_code);
            $Goods->addChild('TRACKING_NO', htmlspecialchars($item->order_tracking_number));
            if($item->corporate==1 and $item->client_id>0) {
                $Goods->addChild('FIN', $item->client_pin);
                $Goods->addChild('PHONE', $item->client_phone);
            }elseif($item->person_id>0){
                $Goods->addChild('FIN', $item->person_pin);
                $Goods->addChild('PHONE', $item->person_phone);
            }
            else{
                $Goods->addChild('FIN', $item->pin);
                $Goods->addChild('PHONE', $item->phone);
            }

        }

        $response = Response::make($xml->asXML(), 200);
        $response->header('Content-Description', 'File Transfer');
        $response->header('Content-Disposition', 'attachment; filename=Inv-'.date('d-m-Y').'.xml');
        $response->header('Content-Type', 'text/xml');

        return $response;
    }

    public function xmlUsa(){

        \Artisan::call('cache:clear');
        \Artisan::call('view:clear');
        $invoice = DB::table('invoices as i')
            ->select('i.id','i.width', 'i.height', 'i.length', 'i.weight' , 'i.shipping_price','i.purchase_no','i.waybill','i.order_tracking_number','i.price','i.corporate',
                'u.uniqid', 'u.name', 'u.surname', 'u.address', 'u.pin', 'c.name as phone',
                'c.name as phone', 'p.quantity', 'p.shop_name', 'product_type_name',
                'cl.id as client_id','cl.name as client_name','cl.surname as client_surname','cl.serial_number as client_serial_number','cl.pin as client_pin','cl.phone as client_phone','cl.email as cl_email','cl.address as client_address',
                'pe.auto_id as person_id','pe.name as person_name','pe.surname as person_surname','pe.serial_number as person_serial_number','pe.pin as person_pin','pe.phone as person_phone','pe.email as person_email','pe.address as person_address'
            )
            ->leftJoin('users as u', 'i.user_id', '=', 'u.id')
            ->leftJoin('user_contacts as c', 'c.user_id', '=', 'u.id')
            ->leftJoin('products as p', 'i.product_id', '=', 'p.id')
            ->leftJoin('clients as cl', 'cl.id', '=', 'i.client_id')
            ->leftJoin('persons as pe', 'pe.id', '=', 'i.person_id')
            ->where('i.country_id', '=', 2)
            ->where('i.status_id', '=', 3)
            ->where('i.active', '=', '1')
            //->where('i.id', '=', '1127')
            ->get();
//        dd($invoice);
        $currency = Currency::find(1);

        $tryToUsd = 1/$currency->tl * $currency->usd;

        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="yes"?><GoodsInfo></GoodsInfo>');
        //$xml->addAttribute('version', '1.0');
        foreach ($invoice as $item){
            $barcode_code = "uslmk".$item->id;
            //$barcode_code = $item->purchase_no;
            $purchase_no = str_pad($item->id, 9, 0, STR_PAD_LEFT);

            $Goods = $xml->addChild('GOODS');
            $Goods->addChild('TR_NUMBER', $purchase_no);
            $Goods->addChild('DIRECTION', 1);
            $Goods->addChild('QUANTITY_OF_GOODS', 1);
            //$Goods->addChild('QUANTITY_OF_GOODS', $item->quantity);
            $Goods->addChild('WEIGHT_GOODS', $item->weight);
            $Goods->addChild('INVOYS_PRICE', round($item->price, 2) + $item->shipping_price);
            $Goods->addChild('CURRENCY_TYPE', 840);
            $Goods->addChild('NAME_OF_GOODS', htmlspecialchars($item->product_type_name));
            if($item->corporate==1 and $item->client_id>0){
                $Goods->addChild('IDXAL_NAME', $item->client_name." ".$item->client_surname);
                $Goods->addChild('IDXAL_ADRESS', $item->client_address);
            }elseif($item->person_id>0){
                $Goods->addChild('IDXAL_NAME', $item->person_name." ".$item->person_surname);
                $Goods->addChild('IDXAL_ADRESS', $item->person_address);
            }else{
                $Goods->addChild('IDXAL_NAME', $item->name." ".$item->surname);
                $Goods->addChild('IDXAL_ADRESS', $item->address);
            }
            $Goods->addChild('IXRAC_NAME', htmlspecialchars($item->shop_name));
            $Goods->addChild('IXRAC_ADRESS', 'USA');
            $Goods->addChild('GOODS_TRAFFIC_FR', 840);
            $Goods->addChild('GOODS_TRAFFIC_TO', '031');
            $Goods->addChild('QAIME', $barcode_code);
            $Goods->addChild('TRACKING_NO', $item->order_tracking_number);
            if($item->corporate==1 and $item->client_id>0) {
                $Goods->addChild('FIN', $item->client_pin);
                $Goods->addChild('PHONE', $item->client_phone);
            }elseif($item->person_id>0){
                $Goods->addChild('FIN', $item->person_pin);
                $Goods->addChild('PHONE', $item->person_phone);
            }else{
                $Goods->addChild('FIN', $item->pin);
                $Goods->addChild('PHONE', $item->phone);
            }
        }


        $response = Response::make($xml->asXML(), 200);
        $response->header('Content-Description', 'File Transfer');
        $response->header('Content-Disposition', 'attachment; filename=Inv-'.date('d-m-Y').'.xml');
        $response->header('Content-Type', 'text/xml');

        return $response;
    }

    public function xmlParts()
    {
        \Artisan::call('cache:clear');
        \Artisan::call('view:clear');
        $invoice = DB::table('invoices as i')
            ->select('count(id)')
            ->where('i.status_id', '=', 3)
            ->where('i.active', '=', '1')
            //->where('i.id', '=', '1127')
            ->count();


        $pages = $invoice/50;

        $count = intval($pages);
        if($count<$pages){
            $count +=1;
        }

        for ($i=1;$i<=$count;$i++){
            echo '<a target="_blank" href="/cp/customs/xmlPage/'.$i.'">XML-'.$i.'</a><br />';
        }


    }


    public function xmlPage($page){
        $count = $page;
        \Artisan::call('cache:clear');
        \Artisan::call('view:clear');
        if(intval($page)>0){
            $page = intval($page)-1;
        }else{
            $page = 0;
        }
        $limit = 50;
        $offset = $limit*$page;
        $invoice = DB::table('invoices as i')
            ->select('i.width', 'i.height', 'i.length', 'i.weight' , 'i.shipping_price','i.purchase_no','i.waybill','i.order_tracking_number','i.price',
                'u.uniqid', 'u.name', 'u.surname', 'u.address', 'u.pin', 'c.name as phone',
                'c.name as phone', 'p.quantity', 'p.shop_name', 'product_type_name'
            )
            ->leftJoin('users as u', 'i.user_id', '=', 'u.id')
            ->leftJoin('user_contacts as c', 'c.user_id', '=', 'u.id')
            ->leftJoin('products as p', 'i.product_id', '=', 'p.id')
            ->where('i.status_id', '=', 3)
            ->where('i.active', '=', '1')
            ->offset($offset)->limit($limit)
            //->where('i.id', '=', '1127')
            ->get();
//        dd($invoice);
        $currency = Currency::find(1);

        $tryToUsd = 1/$currency->tl * $currency->usd;

        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="yes"?><GoodsInfo></GoodsInfo>');
        //$xml->addAttribute('version', '1.0');
        foreach ($invoice as $item){
            $Goods = $xml->addChild('GOODS');
            $Goods->addChild('TR_NUMBER', $item->purchase_no);
            $Goods->addChild('DIRECTION', 1);
            $Goods->addChild('QUANTITY_OF_GOODS', 1);
            //$Goods->addChild('QUANTITY_OF_GOODS', $item->quantity);
            $Goods->addChild('WEIGHT_GOODS', $item->weight);
            $Goods->addChild('INVOYS_PRICE', round($item->price / $tryToUsd, 2) + $item->shipping_price);
            $Goods->addChild('CURRENCY_TYPE', 840);
            $Goods->addChild('NAME_OF_GOODS', htmlspecialchars($item->product_type_name));
            $Goods->addChild('IDXAL_NAME', $item->name." ".$item->surname);
            $Goods->addChild('IDXAL_ADRESS', $item->address);
            $Goods->addChild('IXRAC_NAME', htmlspecialchars($item->shop_name));
            $Goods->addChild('IXRAC_ADRESS', 'Istanbul');
            $Goods->addChild('GOODS_TRAFFIC_FR', 792);
            $Goods->addChild('GOODS_TRAFFIC_TO', '031');
            $Goods->addChild('QAIME', $item->waybill);
            $Goods->addChild('TRACKING_NO', $item->order_tracking_number);
            $Goods->addChild('FIN', $item->pin);
            $Goods->addChild('PHONE', $item->phone);
        }


        $response = Response::make($xml->asXML(), 200);
        $response->header('Content-Description', 'File Transfer');
        $response->header('Content-Disposition', 'attachment; filename=Inv-'.date('d-m-Y').'-'.$count.'.xml');
        $response->header('Content-Type', 'text/xml');

        return $response;
    }

    public function manifest(){


        \Artisan::call('cache:clear');
        \Artisan::call('view:clear');
        $invoice = DB::table('invoices as i')
            ->select('i.width', 'i.height', 'i.length', 'i.weight' , 'i.shipping_price','i.purchase_no','i.waybill','i.order_tracking_number','i.price',
                'u.uniqid','u.pin' ,'u.name', 'u.surname', 'u.address', 'u.serial_number','u.birthdate','u.id as user_id',
                'c.name as phone', 'p.quantity', 'p.shop_name', 'product_type_name','p.description',
                'cl.id as client_id','cl.name as client_name','cl.surname as client_surname','cl.serial_number as client_serial_number','cl.pin as client_pin','cl.phone as client_phone','cl.email as cl_email','cl.address as client_address',
                'pe.auto_id as person_id','pe.name as person_name','pe.surname as person_surname','pe.serial_number as person_serial_number','pe.pin as person_pin','pe.phone as person_phone','pe.email as person_email','pe.address as person_address'
            )
            ->leftJoin('users as u', 'i.user_id', '=', 'u.id')
            ->leftJoin('user_contacts as c', 'c.user_id', '=', 'u.id')
            ->leftJoin('products as p', 'i.product_id', '=', 'p.id')
            ->leftJoin('clients as cl', 'cl.id', '=', 'i.client_id')
            ->leftJoin('persons as pe', 'pe.id', '=', 'i.person_id')
            ->where('i.status_id', '=', 3)
            //->where('i.s_id', '=', 1)
            ->where('i.country_id', '=', 1)
            ->where('i.active', '=', 1)
            //->where('i.liquid_type', '=', 0)
            ->orderBy('u.name', 'ASC')
            ->orderBy('u.surname', 'ASC')
            ->get();

        $data_users=[];
        foreach ($invoice as $item){
            $data_users[] = $item->user_id;
        }


        $currency = Currency::find(1);

        $tryToUsd = 1/$currency->tl * $currency->usd;
        $minus30=date('Y-m-d', strtotime('-30 day', strtotime(date('Y-m-d'))));

       /* $status = Status::where('label', '=', 'on_the_way')->where('type', '=', 'invoice')->first();
        $last30days=DB::table('invoices as i')
            ->select(DB::raw('SUM(i.shipping_price) as shipping_price'), DB::raw('SUM(p.price) as price'), 'i.user_id')
            ->leftJoin('products as p', 'i.product_id', '=', 'p.id')
            ->leftJoin('invoice_dates as in_d','in_d.invoice_id','=','i.id')
            //->where('i.order_date', '>', $minus30)
            ->where('i.active', '=', 1)
            ->where("in_d.status_id","=",$status->sid)
            ->where('in_d.action_date', '>', Carbon::now()->subDays(30)->toDateTimeString())
            ->whereIN('i.user_id', array_unique($data_users))
            ->groupBy('i.user_id')
            ->get();*/

        $last30days = DB::table('invoices')
            ->select(DB::raw("sum(shipping_price) as shipping_price"),DB::raw("sum(price) as price"),'user_id')
            ->whereIn('id', function($query)
            {
                $status = Status::where('label', '=', 'on_the_way')->where('type', '=', 'invoice')->first();
                $query->select('invoice_id')
                    ->from('invoice_dates')
                    ->where('action_date', '>', Carbon::now()->subDays(30)->toDateTimeString())
                    ->where("status_id","=",$status->sid)
                ;
            })
            ->whereIN('user_id',array_unique($data_users))
            ->groupBy('user_id')
            ->get();

        foreach ($last30days as $item){
            $data_users[$item->user_id] = number_format($item->price / $tryToUsd + $item->shipping_price, 2);
        }

        /*
        var_dump($invoice);

                var_dump($data_users); die;*/

//        dd($last30days);

        return view('cp.custom.manifest', [
            'invoices'=>$invoice,
            'tryToUsd' => $tryToUsd,
            'last30days' => $data_users
        ]);
    }

    public function manifestLiquid(){


        \Artisan::call('cache:clear');
        \Artisan::call('view:clear');
        $invoice = DB::table('invoices as i')
            ->select('i.width', 'i.height', 'i.length', 'i.weight' , 'i.shipping_price','i.purchase_no','i.waybill','i.order_tracking_number','i.price',
                'u.uniqid','u.pin' ,'u.name', 'u.surname', 'u.address', 'u.serial_number','u.birthdate','u.id as user_id',
                'c.name as phone', 'p.quantity', 'p.shop_name', 'product_type_name','p.description',
                'cl.id as client_id','cl.name as client_name','cl.surname as client_surname','cl.serial_number as client_serial_number','cl.pin as client_pin','cl.phone as client_phone','cl.email as cl_email','cl.address as client_address',
                'pe.auto_id as person_id','pe.name as person_name','pe.surname as person_surname','pe.serial_number as person_serial_number','pe.pin as person_pin','pe.phone as person_phone','pe.email as person_email','pe.address as person_address'
            )
            ->leftJoin('users as u', 'i.user_id', '=', 'u.id')
            ->leftJoin('user_contacts as c', 'c.user_id', '=', 'u.id')
            ->leftJoin('products as p', 'i.product_id', '=', 'p.id')
            ->leftJoin('clients as cl', 'cl.id', '=', 'i.client_id')
            ->leftJoin('persons as pe', 'pe.id', '=', 'i.person_id')
            ->where('i.status_id', '=', 3)
            //->where('i.s_id', '=', 1)
            ->where('i.country_id', '=', 1)
            ->where('i.active', '=', 1)
            ->where('i.liquid_type', '=', 1)
            ->orderBy('u.name', 'ASC')
            ->orderBy('u.surname', 'ASC')
            ->get();

        $data_users=[];
        foreach ($invoice as $item){
            $data_users[] = $item->user_id;
        }


        $currency = Currency::find(1);

        $tryToUsd = 1/$currency->tl * $currency->usd;
        $minus30=date('Y-m-d', strtotime('-30 day', strtotime(date('Y-m-d'))));

        /* $status = Status::where('label', '=', 'on_the_way')->where('type', '=', 'invoice')->first();
         $last30days=DB::table('invoices as i')
             ->select(DB::raw('SUM(i.shipping_price) as shipping_price'), DB::raw('SUM(p.price) as price'), 'i.user_id')
             ->leftJoin('products as p', 'i.product_id', '=', 'p.id')
             ->leftJoin('invoice_dates as in_d','in_d.invoice_id','=','i.id')
             //->where('i.order_date', '>', $minus30)
             ->where('i.active', '=', 1)
             ->where("in_d.status_id","=",$status->sid)
             ->where('in_d.action_date', '>', Carbon::now()->subDays(30)->toDateTimeString())
             ->whereIN('i.user_id', array_unique($data_users))
             ->groupBy('i.user_id')
             ->get();*/

        $last30days = DB::table('invoices')
            ->select(DB::raw("sum(shipping_price) as shipping_price"),DB::raw("sum(price) as price"),'user_id')
            ->whereIn('id', function($query)
            {
                $status = Status::where('label', '=', 'on_the_way')->where('type', '=', 'invoice')->first();
                $query->select('invoice_id')
                    ->from('invoice_dates')
                    ->where('action_date', '>', Carbon::now()->subDays(30)->toDateTimeString())
                    ->where("status_id","=",$status->sid)
                ;
            })
            ->whereIN('user_id',array_unique($data_users))
            ->groupBy('user_id')
            ->get();

        foreach ($last30days as $item){
            $data_users[$item->user_id] = number_format($item->price / $tryToUsd + $item->shipping_price, 2);
        }

        /*
        var_dump($invoice);

                var_dump($data_users); die;*/

//        dd($last30days);

        return view('cp.custom.manifest', [
            'invoices'=>$invoice,
            'tryToUsd' => $tryToUsd,
            'last30days' => $data_users
        ]);
    }

    public function manifestUsa(){


        \Artisan::call('cache:clear');
        \Artisan::call('view:clear');
        $invoice = DB::table('invoices as i')
            ->select('i.width', 'i.height', 'i.length', 'i.weight' , 'i.shipping_price','i.purchase_no','i.waybill','i.order_tracking_number','i.price',
                'u.uniqid','u.pin' ,'u.name', 'u.surname', 'u.address', 'u.serial_number','u.birthdate','u.id as user_id',
                'c.name as phone', 'p.quantity', 'p.shop_name', 'product_type_name','p.description',
                'cl.id as client_id','cl.name as client_name','cl.surname as client_surname','cl.serial_number as client_serial_number','cl.pin as client_pin','cl.phone as client_phone','cl.email as cl_email','cl.address as client_address',
                'pe.auto_id as person_id','pe.name as person_name','pe.surname as person_surname','pe.serial_number as person_serial_number','pe.pin as person_pin','pe.phone as person_phone','pe.email as person_email','pe.address as person_address'
            )
            ->leftJoin('users as u', 'i.user_id', '=', 'u.id')
            ->leftJoin('user_contacts as c', 'c.user_id', '=', 'u.id')
            ->leftJoin('products as p', 'i.product_id', '=', 'p.id')
            ->leftJoin('clients as cl', 'cl.id', '=', 'i.client_id')
            ->leftJoin('persons as pe', 'pe.id', '=', 'i.person_id')
            ->where('i.status_id', '=', 3)
            ->where('i.country_id', '=', 2)
            ->where('i.active', '=', 1)
            ->orderBy('u.name', 'ASC')
            ->orderBy('u.surname', 'ASC')
            ->get();

        $data_users=[];
        foreach ($invoice as $item){
            $data_users[] = $item->user_id;
        }


        $currency = Currency::find(1);

        $tryToUsd = 1/$currency->tl * $currency->usd;
        $minus30=date('Y-m-d', strtotime('-30 day', strtotime(date('Y-m-d'))));

        /* $status = Status::where('label', '=', 'on_the_way')->where('type', '=', 'invoice')->first();
         $last30days=DB::table('invoices as i')
             ->select(DB::raw('SUM(i.shipping_price) as shipping_price'), DB::raw('SUM(p.price) as price'), 'i.user_id')
             ->leftJoin('products as p', 'i.product_id', '=', 'p.id')
             ->leftJoin('invoice_dates as in_d','in_d.invoice_id','=','i.id')
             //->where('i.order_date', '>', $minus30)
             ->where('i.active', '=', 1)
             ->where("in_d.status_id","=",$status->sid)
             ->where('in_d.action_date', '>', Carbon::now()->subDays(30)->toDateTimeString())
             ->whereIN('i.user_id', array_unique($data_users))
             ->groupBy('i.user_id')
             ->get();*/

        $last30days = DB::table('invoices')
            ->select(DB::raw("sum(shipping_price) as shipping_price"),DB::raw("sum(price) as price"),'user_id')
            ->whereIn('id', function($query)
            {
                $status = Status::where('label', '=', 'on_the_way')->where('type', '=', 'invoice')->first();
                $query->select('invoice_id')
                    ->from('invoice_dates')
                    ->where('action_date', '>', Carbon::now()->subDays(30)->toDateTimeString())
                    ->where("status_id","=",$status->sid)
                ;
            })
            ->whereIN('user_id',array_unique($data_users))
            ->groupBy('user_id')
            ->get();

        foreach ($last30days as $item){
            $data_users[$item->user_id] = number_format($item->price / $tryToUsd + $item->shipping_price, 2);
        }
        /*
                var_dump($invoice);

                var_dump($data_users); die;*/

//        dd($last30days);

        return view('cp.custom.manifestUsa', [
            'invoices'=>$invoice,
            'tryToUsd' => $tryToUsd,
            'last30days' => $data_users
        ]);
    }


    public function regions(Request $request){


        \Artisan::call('cache:clear');
        \Artisan::call('view:clear');

        $region_id = $request->get("region_id",2);
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
            ->where('i.status_id', '=', 11)
            //->where('i.s_id', '=', 1)
            ->where('i.country_id', '=', $country_id)
            ->where('i.region_id', '=', $region_id)
            ->where('i.active', '=', 1)
            ->where('i.liquid_type', '!=', 1)
            //->orderBy('f.updated_at', 'DESC')
            ->orderBy('u.name', 'ASC')
            ->orderBy('u.surname', 'ASC')
            ->get();


        $data_users=[];
        foreach ($invoice as $item){
            $data_users[] = $item->user_id;
        }

        $currency = Currency::find(1);

        $tryToUsd = 1/$currency->tl * $currency->usd;

        return view('cp.custom.regions', [
            'invoices'=>$invoice,
            'region' => $region,
            'country' => $country,
            'tryToUsd' => $tryToUsd,
        ]);
    }

    public function liquid(Request $request){


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
            ->where('i.status_id', '=', 11)
            //->where('i.s_id', '=', 1)
            ->where('i.country_id', '=', 1)
            ->where('i.region_id', '=', $region_id)
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

        return view('cp.custom.regions', [
            'invoices'=>$invoice,
            'region' => $region,
            'country' => $country,
            'tryToUsd' => $tryToUsd,
        ]);
    }

    public function manifestUsaBaku(){


        \Artisan::call('cache:clear');
        \Artisan::call('view:clear');
        $invoice = DB::table('invoices as i')
            ->select('i.width', 'i.height', 'i.length', 'i.weight' , 'i.shipping_price','i.purchase_no','i.waybill','i.order_tracking_number','i.price',
                'u.uniqid','u.pin' ,'u.name', 'u.surname', 'u.address', 'u.serial_number','u.birthdate','u.id as user_id',
                'c.name as phone', 'p.quantity', 'p.shop_name', 'product_type_name','p.description'
            )
            ->leftJoin('users as u', 'i.user_id', '=', 'u.id')
            ->leftJoin('user_contacts as c', 'c.user_id', '=', 'u.id')
            ->leftJoin('products as p', 'i.product_id', '=', 'p.id')
            ->where('i.status_id', '=', 2)
            ->where('i.country_id', '=', 2)
            ->where('i.active', '=', 1)
            ->orderBy('i.user_id', 'DESC')
            ->get();

        $data_users=[];
        foreach ($invoice as $item){
            $data_users[] = $item->user_id;
        }


        $currency = Currency::find(1);

        $tryToUsd = 1/$currency->tl * $currency->usd;
        $minus30=date('Y-m-d', strtotime('-30 day', strtotime(date('Y-m-d'))));

        /* $status = Status::where('label', '=', 'on_the_way')->where('type', '=', 'invoice')->first();
         $last30days=DB::table('invoices as i')
             ->select(DB::raw('SUM(i.shipping_price) as shipping_price'), DB::raw('SUM(p.price) as price'), 'i.user_id')
             ->leftJoin('products as p', 'i.product_id', '=', 'p.id')
             ->leftJoin('invoice_dates as in_d','in_d.invoice_id','=','i.id')
             //->where('i.order_date', '>', $minus30)
             ->where('i.active', '=', 1)
             ->where("in_d.status_id","=",$status->sid)
             ->where('in_d.action_date', '>', Carbon::now()->subDays(30)->toDateTimeString())
             ->whereIN('i.user_id', array_unique($data_users))
             ->groupBy('i.user_id')
             ->get();*/

        $last30days = DB::table('invoices')
            ->select(DB::raw("sum(shipping_price) as shipping_price"),DB::raw("sum(price) as price"),'user_id')
            ->whereIn('id', function($query)
            {
                $status = Status::where('label', '=', 'on_the_way')->where('type', '=', 'invoice')->first();
                $query->select('invoice_id')
                    ->from('invoice_dates')
                    ->where('action_date', '>', Carbon::now()->subDays(30)->toDateTimeString())
                    ->where("status_id","=",$status->sid)
                ;
            })
            ->whereIN('user_id',array_unique($data_users))
            ->groupBy('user_id')
            ->get();

        foreach ($last30days as $item){
            $data_users[$item->user_id] = number_format($item->price / $tryToUsd + $item->shipping_price, 2);
        }
        /*
                var_dump($invoice);

                var_dump($data_users); die;*/

//        dd($last30days);

        return view('cp.custom.manifestUsaBaku', [
            'invoices'=>$invoice,
            'tryToUsd' => $tryToUsd,
            'last30days' => $data_users
        ]);
    }



    public function manifest2(){


        \Artisan::call('cache:clear');
        \Artisan::call('view:clear');
        $invoice = DB::table('invoices as i')
            ->select('i.width', 'i.height', 'i.length', 'i.weight' , 'i.shipping_price','i.purchase_no','i.waybill','i.order_tracking_number','i.price',
                'u.id','u.uniqid', 'u.name', 'u.surname', 'u.pin', 'u.address', 'u.serial_number','u.birthdate','u.id as user_id',
                'c.name as phone', 'p.quantity', 'p.shop_name', 'p.product_type_name','p.description'
            )
            ->leftJoin('users as u', 'i.user_id', '=', 'u.id')
            ->leftJoin('user_contacts as c', 'c.user_id', '=', 'u.id')
            ->leftJoin('products as p', 'i.product_id', '=', 'p.id')
            ->where('i.status_id', '=', 3)
            ->where('i.active', '=', 1)
            ->orderBy('user_id', 'DESC')
            ->get();

        $data_users=[];
        foreach ($invoice as $item){
            $data_users[] = $item->user_id;
        }


        $currency = Currency::find(1);

        $tryToUsd = 1/$currency->tl * $currency->usd;

        $minus30=date('Y-m-d', strtotime('-30 day', strtotime(date('Y-m-d'))));
        $last30days=DB::table('invoices as i')
            ->select(DB::raw('SUM(i.shipping_price) as shipping_price'), DB::raw('SUM(p.price) as price'), 'i.user_id')
            ->leftJoin('products as p', 'i.product_id', '=', 'p.id')
            ->where('i.order_date', '>', $minus30)
            ->whereIN('i.user_id', array_unique($data_users))
            ->groupBy('i.user_id')
            ->get();


        foreach ($last30days as $item){
            $data_users[$item->user_id] = number_format($item->price / $tryToUsd + $item->shipping_price, 2);
        }

//        dd($last30days);

        return view('cp.custom.manifest2', [
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
            ->where('i.status_id', '=', 2)
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
}
