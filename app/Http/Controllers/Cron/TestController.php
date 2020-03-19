<?php

namespace App\Http\Controllers\Cron;

use App\Currencies;
use App\Currency;
use App\Depot;
use App\Http\Controllers\Controller;
use App\Invoice;
use App\InvoiceDates;
use App\ModelCountry\Country;
use App\ModelCountry\Regions;
use App\ModelLogs\LogBalance;
use App\ModelSlider\Slider;
use App\ModelUser\User;
use App\Packages;
use App\Packages_1704;
use App\Status;
use App\UserPromoCodes;
use App\Helpers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Carbon\Carbon;


class TestController extends Controller
{

    public function indexsssss()
    {
        $invoices = DB::table("invoices as i")
            ->select("i.id","i.user_id","uc.name")
            ->leftJoin("user_contacts as uc","uc.user_id","i.user_id")
            ->where("i.country_id",2)
            ->where("i.status_id",2)
            ->get();
        $s = [];
        $i = 1;
        $j = 1;


        $text = 'Amerikadan bu hefteye olan gonderis ucuslarin texire salinmasi sebebinden gecikir. Baglamalariniz novbeti heftesonu yola cixacaq. Gecikmeye gore uzr isteyirik.';
        foreach ($invoices as $array){

            $user_id = $array->user_id;
            $phone = phoneNumber($array->name);
            if(!in_array($phone,$s)){
                //$data = (object) ['text' => $text,"user_id" =>$user_id];
                //smsSend($data, $phone);
                $s[$i] = $phone;
                $sms = new \App\Sms();
                $sms->user_id = $user_id;
                $sms->phone = $phone;
                $sms->text = $text;
                $sms->created_at = date("Y-m-d H:i:s");
                $sms->priority = 0;
                $sms->status = 0;
                $sms->save();
                $i++;
                echo $i.")".$user_id." ".$phone."<hr />";

            }
            $j++;

        }


    }



    public function indexExtraLink(Request $request)
    {
        $url = $request->get("url",false);
        $success = false;
        $row = [];
        if($url){
            $row = DB::table("extras as e")
                ->select("e.link2","p.expenses","p.is_paid","p.is_ordered")
                ->leftJoin("products as p","p.extras_id","=","e.id")
                ->where("e.link2",$url)
                ->get();
            if($row!=null){
                $success = true;
            }
        }
        return response()->json(['success' => $success, 'data' => $row]);

    }

    public function indexStatus()
    {
        $i = 0;
        $datas["company"] = 'limak';
        $datas["password"] = 'Limak123456@';
        $invoices = Invoice::whereRaw("company_status_id!=status_id")->where("person_id",">",0)->whereIn("status_id",[1,2,3,4,5,11])->limit(10)->get();
        foreach ($invoices as $invoice){
            $datas["package_id"][$i] = $invoice->id;
            $datas["order_date"][$i] = date("Y-m-d H:i:s");
            $datas["status"][$i] = $invoice->status_id;
            $datas["weight"][$i] = $invoice->weight;

            $i++;
        }

        if(isset($datas["package_id"]) and count($datas["package_id"])>0){
            $curl = curl_init();


            $fields_string = http_build_query($datas);
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://vipex.az/api/batchesupdate",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $fields_string,
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            if ($response == 1) {
                foreach ($invoices as $invoice){
                    $invoice->company_status_id = $invoice->status_id;
                    $invoice->save();

                    echo $invoice->id. " ".$invoice->status_id." deyisdi <br />";
                }
            }
        }else{
            echo "change not found";
        }

    }

    public function Son30gun()
    {
        $user_id = 8523;
        $user = User::find($user_id);
        $begin_date = date('Y-m-d',strtotime('22.11.2019'));
        $end_date = date('Y-m-d',strtotime('22.12.2019'));
        $client_id = 0;

        $currency = \App\Currencies::where("name","try-usd")->first();
        $tryToUsd = $currency->val;

        $last30days = DB::table('invoices')
            ->select("invoices.id","invoices.purchase_no","invoices.shipping_price","invoices.client_id","invoices.price","invoices.country_id",'p.shop_name','p.product_type_name')
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
            $invoices[$row->id]["shop_name"] = $row->shop_name;
            $invoices[$row->id]["product_type_name"] = $row->product_type_name;
            $invoices[$row->id]["purchase_no"] = $row->purchase_no;
        }




        return view('cp.admins.report', compact('invoices','user','begin_date','end_date'));

    }

    public function smsHamiyaGonderEsasYeni()
    {
        $uc = DB::table('users')
            ->select(DB::raw("DISTINCT(uc.name),users.id as user_id"))
            ->leftJoin("user_contacts as uc","uc.user_id","users.id")
            ->where("uc.name","!=",null)->where("users.mid",0)->limit(2000)->get();

        $s = [];
        $i = 1;
        $j = 1;
        $users_array = [];
        $text = 'Diqqet!Gomrukde yaranmis problemle bagli Turkiyeden maye mehsullarin dasinmasi bir muddet texire salinib.Sifarislerinizi vererken nezere almaginizi xahis edirik';
        foreach ($uc as $array){

            $user_id = $array->user_id;
            $users_array[] = $user_id;


            $phone = phoneNumber($array->name);

            if(!in_array($phone,$s) and $phone){
                //$data = (object) ['text' => $text,"user_id" =>$user_id];
                //smsSend($data, $phone);
                $s[$i] = $phone;
                $sms = new \App\Sms();
                $sms->user_id = $user_id;
                $sms->phone = $phone;
                $sms->text = $text;
                $sms->created_at = date("Y-m-d H:i:s");
                $sms->priority = 0;
                $sms->status = 0;
                $sms->save();
                $i++;
            }


            $j++;

        }
        DB::table('users')->whereIn('id', $users_array)->update(array('mid' => 1));


        echo "<hr >".$i." - ".$j;
    }


    public function indexAnbardakilaraMesaj()
    {
        $invoices = DB::table("invoices as i")
            ->select("i.id","i.user_id","uc.name")
            ->leftJoin("user_contacts as uc","uc.user_id","i.user_id")
            ->where("i.region_id",1)
            ->where("i.status_id",4)
            ->get();
        $s = [];
        $i = 1;
        $j = 1;
        $text = 'Hormetli musterimiz, Baki anbarimiz sabah saat 11:00-dan 18:00-dek xidmetinizdedir. Baglamalarinizi tehvil ala bilersiniz.';
        foreach ($invoices as $array){

            $user_id = $array->user_id;
            $phone = phoneNumber($array->name);
            if(!in_array($phone,$s)){
                //$data = (object) ['text' => $text,"user_id" =>$user_id];
                //smsSend($data, $phone);
                $s[$i] = $phone;
                $sms = new \App\Sms();
                $sms->user_id = $user_id;
                $sms->phone = $phone;
                $sms->text = $text;
                $sms->created_at = date("Y-m-d H:i:s");
                $sms->priority = 0;
                $sms->status = 0;
                $sms->save();
                $i++;
                echo $i.")".$user_id." ".$phone."<hr />";

            }
            $j++;

        }


    }

    public function indexdddd()
    {
        $data = [];
        $invoices = DB::table("invoices as i")->select("i.id","a.account_id")->leftJoin("account_logs as a","a.invoice_id","=","i.id")->where("a.comment"," Müştəri sifarişi")->where("a.account_id",">",18)->where("i.account_id",0)->limit(1000)->get();
        foreach ($invoices as $invoice){
            //echo $invoice->id." - ".$invoice->account_id."<br />";
            $data[$invoice->account_id][] = $invoice->id;
        }

        foreach ($data as $key=>$array){
            DB::table('invoices')->whereIn('id', $array)->update(['account_id' => $key]);
            echo "<h1>".$key."</h1>";
            var_dump($array);
            echo "<hr />";

        }
    }

    public function indexMonth()
    {
        $day = '2019-07-31';
        $end_day = '2020-07-31';

        $current = false;

        for($i=1;$i<=12;$i++){
            echo $day = date("Y-m-d", strtotime("+31 days", strtotime($day)));
            if($day<=date("Y-m-d") and $current==false){
                echo "<br />indiki ay";
                $current = true;
            }
            echo "<hr />";
        }

        exit;
        $userId = 719;
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

    public function faturalaGorebaxmaqSIFARISLERE()
    {
        $array = [
            "03.10" => [274,271,270,267,265],
            "30.09" => [261,258,251],
            "26.09" => [250,249,246,243],
            "23.09" => [240,239,237],
            "19.09" => [235,233,232,230,228],
            "16.09" => [226,225,223,216],
            "12.09" => [212,210,206,199],
            "09.09" => [197,196,195],
            "05.09" => [194,193,188,185],
            "03.09" => [183,182,181]
        ];

        $users = [];


        foreach ($array as $key => $fatura){
            //echo "<h1>".$key."</h1><br />";
            $result = DB::table("invoices")->select("user_id",DB::raw("count(id) as count"),DB::raw("sum(weight) as weight"),DB::raw("sum(shipping_price) as shipping_price"))
                ->whereIn("fatura_id",$fatura)
                ->groupBy("user_id")
                ->havingRaw('SUM(weight) > ?', [5])
                ->get();
            $i=1;
            $allweight = 0;
            foreach ($result as $inv){
                $users[$inv->user_id][$key] = ["count" => $inv->count,"weight" => $inv->weight,"shipping_price" => $inv->shipping_price];
                //echo $i.")<b>".$inv->user_id."</b> istifadecisi ".$inv->count." sayinda <b>".$inv->weight."</b> kq cekisinde ".$inv->shipping_price."$ <hr />";
                $allweight += $inv->weight;
                $i++;
            }
            /*echo $allweight;
            echo "<hr />";
            echo "<hr />";
            echo "<hr />";
            echo "<hr />";*/
        }

        echo '<table border="1">';
            echo '<tr>';
                echo '<td> </td>';
                foreach ($array as $key=>$arr){
                    echo '<td><b>'.$key.'</b></td>';
                }
            echo '</tr>';
                foreach ($users as $key=>$user){
                    echo '<tr>';
                    echo '<td><b>'.$key.'</b></td>';
                    foreach ($array as $ak=>$arr){
                        if(isset($user[$ak])){
                            echo "<td>".$user[$ak]["weight"]."</td>";
                        }else{
                            echo "<td> </td>";
                        }
                    }
                    echo '</tr>';

                }
        echo '</table>';

        /*return response()->json(['success' => true, 'data' => $users]); */


     }
    public function smsHamiyaGOnder()
    {
        return 1; exit;
        $uc = DB::table('users')
            ->select(DB::raw("DISTINCT(uc.name),users.id as user_id"))
            ->leftJoin("user_contacts as uc","uc.user_id","users.id")
            ->where("uc.name","!=",null)->get();

        $s = [];
        $i = 1;
        $j = 1;
        $text = 'Diqqet! Turkiyeden maye mehsullarin dashinma tarifleri deyishildi! 1 oktyabrdan etibaren mayelerin dasinma haqqi yeni tarifle hesablanacaq. Etrafli *9595';
        foreach ($uc as $array){

            $user_id = $array->user_id;
            $hone = phoneNumber($array->name);

            if(!in_array($phone,$s)){
                //$data = (object) ['text' => $text,"user_id" =>$user_id];
                //smsSend($data, $phone);
                $s[$i] = $phone;
                $sms = new \App\Sms();
                $sms->user_id = $user_id;
                $sms->phone = $phone;
                $sms->text = $text;
                $sms->created_at = date("Y-m-d H:i:s");
                $sms->priority = 0;
                $sms->status = 0;
                $sms->save();
                $i++;
            }
            $j++;

        }

        echo "<hr >".$i." - ".$j;
    }

    public function yeni_qiymet_hesablama()
    {
        $sum = 0;
        $invoices = DB::table("invoices")->where("fatura_id",248)->get();
        foreach ($invoices as $invoice){
            if($invoice->weight<=0.250){
                $sh = 2.50;
            }elseif($invoice->weight<=0.500){
                if($invoice->region_id == 1){
                    $sh = 4.00;
                }else{
                    $sh = 4.00;
                }
            }elseif($invoice->weight<=0.700){
                if($invoice->region_id == 1){
                    $sh = 6.00;
                }else{
                    $sh = 6.00;
                }
            }elseif ($invoice->weight<=1.000){
                if($invoice->region_id == 1){
                    $sh = 7.50;
                }else{
                    $sh = 7.50;
                }
            }else{
                if($invoice->region_id == 1){
                    $sh = 7.50;
                }else{
                    $sh = 7.50;
                }

                $sh = $sh*$invoice->weight;
            }



            $sum = $sum + $sh;
            echo $sum."<br />";
        }
        echo "<hr />".$sum;
    }

    public function smsGecikme()
    {
        return "1";
        exit;
        $invoices = DB::table("invoices as i")
            ->select("i.id","i.user_id","uc.name")
            ->leftJoin("user_contacts as uc","uc.user_id","i.user_id")
            ->where("i.status_id",3)
            ->get();

        $s = [];
        $i = 1;
        $j = 1;
        $text = 'Diqqet! Gomruk yoxlanisinin uzanmasi sebebile baglamalarin tehvil verilmesi gecikir. Bu sebebden gomruk yoxlanisinda olan baglamalarin dasinma haqqina 15% endirim edilecek.';
        foreach ($invoices as $array){

            $user_id = $array->user_id;
            $phone = phoneNumber($array->name);
            if(!in_array($phone,$s)){
                //$data = (object) ['text' => $text,"user_id" =>$user_id];
                //smsSend($data, $phone);
                $s[$i] = $phone;
                $sms = new \App\Sms();
                $sms->user_id = $user_id;
                $sms->phone = $phone;
                $sms->text = $text;
                $sms->created_at = date("Y-m-d H:i:s");
                $sms->priority = 0;
                $sms->status = 0;
                $sms->save();
                $i++;
                echo $i.")".$user_id." ".$phone."<hr />";

            }
            $j++;

        }
    }




    public function tarifeGoreHesablamaq()
    {
        $invoices = DB::table("invoices as i")->select("user_id",DB::raw("sum(i.weight) as weight"))->where("corporate",1)->where("i.status_id",3)->where("i.s_id",1)->groupBy("i.user_id")->get();
        $i = 1;
        foreach ($invoices as $row){
            if($row->weight>10){
                $user_id = $row->user_id;
                $user_invoices = Invoice::where("user_id",$user_id)->where("corporate",1)->where("status_id",3)->where("s_id",1)->get();
                // IF CORPORATE BEGIN TARIFF
                $user = DB::table("users")->select("tariffs")->where("id",$user_id)->first();
                if($user!=null){
                    $tariffAll = json_decode($user->tariffs,true);
                    foreach ($user_invoices as $invoice){
                        if($invoice->region_id==1){
                            $region_id = 1;
                        }elseif($invoice->region_id == 3){
                            $region_id = 2;
                        }else{
                            $region_id = 3;
                        }

                        if(isset($tariffAll["tariffs"][$invoice->country_id][$region_id])) {
                            $tariff_array = $tariffAll["tariffs"][$invoice->country_id][$region_id];  // Baki
                        }else{
                            $tariff_array = $tariffAll["tariffs"][1][1];
                        }

                        if(count($tariff_array)>=1){
                            foreach ($tariff_array as $t){
                                $tariffArr[$t["label"]] = floatval($t["price"]);
                            }
                        }

                        $result = $invoice->shipping_price;
                        $weightResult = $invoice->weight;
                        if($weightResult>0) {
                            if ($weightResult >= 0 && $weightResult <= 0.25) {
                                $result = $tariffArr['minWeightPrice'];
                            } elseif ($weightResult > 0.25 && $weightResult <= 0.5) {
                                $result = $tariffArr['halfWeightPrice'];
                            } elseif ($weightResult > 0.5 && $weightResult <= 0.7) {
                                $result = $tariffArr['bigHalfWeightPrice'];
                            } elseif ($weightResult > 0.7 && $weightResult <= 1) {
                                $result = $tariffArr['weightPrice'];
                            } else {
                                $result = round($tariffArr['weightPrice'] * $weightResult, 2);
                            }
                        }

                        $invoice->shipping_price = $result;
                        $invoice->delivery_price = $result;
                        $invoice->s_id = 2;
                        if($invoice->save()){
                            echo $invoice->id."<br />";
                        }

                    }



                }
                // IF CORPORATE END TARIFF

            }
        }
    }

    public function last30day()
    {
        $id = 5481;
        $last30day_payment_row = DB::table('invoices')
            ->select("invoices.country_id",DB::raw("sum(shipping_price) as sh_price"),DB::raw("sum(price) as s_price"))
            ->whereIn('id', function($query)
            {
                $status = Status::where('label', '=', 'on_the_way')->where('type', '=', 'invoice')->first();
                $query->select('invoice_id')
                    ->from('invoice_dates')
                    ->where('action_date', '>', Carbon::now()->subDays(30)->toDateTimeString())
                    ->where("status_id","=",$status->sid)
                ;
            })
            ->where('invoices.user_id','=',$id)
            ->groupBy("invoices.country_id")
            ->get();
        $currency = Currencies::where("name","=","try-usd")->first();
        $last30day_payment = 0;
        var_dump($last30day_payment_row);
        if($last30day_payment_row!=null){

            foreach ($last30day_payment_row as $row){
                $last30day_payment += $row->sh_price;
                if($currency!=null and $row->country_id==1){
                    $last30day_payment += $currency->val*$row->s_price;
                }else{
                    $last30day_payment += $row->s_price;
                }
            }
        }
        echo "<br />";
        echo $last30day_payment;
    }

    public function indexdd()
    {
        echo "16.09.2019<br />";
        $result = DB::table("invoices")->select("user_id",DB::raw("count(id) as count"),DB::raw("sum(weight) as weight"),DB::raw("sum(shipping_price) as shipping_price"))
            ->whereIn("fatura_id",[214,216,223,225,226,228,230])
            ->whereIn("user_id",[11371,5348,7410,6818,5916,8062,12336,14661,5535,14503,14664])
            ->groupBy("user_id")->get();
        $i=1;
        $allweight = 0;
        foreach ($result as $inv){
            echo $i.")".$inv->user_id." istifadecisi ".$inv->count." sayinda <b>".$inv->weight."</b> kq cekisinde ".$inv->shipping_price."$ <hr />";
            $allweight += $inv->weight;
            $i++;
        }
        echo $allweight;
        echo "<hr />";
        echo "<hr />";
        echo "<hr />";
        echo "<hr />";

        echo "13.09.2019<br />";
        $result = DB::table("invoices")->select("user_id",DB::raw("count(id) as count"),DB::raw("sum(weight) as weight"),DB::raw("sum(shipping_price) as shipping_price"))
            ->whereIn("fatura_id",[199,206,210,212])
            ->whereIn("user_id",[11371,5348,7410,6818,5916,8062,12336,14661,5535,14503,14664])
            ->groupBy("user_id")->get();
        $i=1;
        $allweight = 0;
        foreach ($result as $inv){
            echo $i.")".$inv->user_id." istifadecisi ".$inv->count." sayinda <b>".$inv->weight."</b> kq cekisinde ".$inv->shipping_price."$ <hr />";
            $allweight += $inv->weight;
            $i++;
        }
        echo $allweight;
        echo "<hr />";
        echo "<hr />";
        echo "<hr />";
        echo "<hr />";

        echo "09.09.2019<br />";
        $result = DB::table("invoices")->select("user_id",DB::raw("count(id) as count"),DB::raw("sum(weight) as weight"),DB::raw("sum(shipping_price) as shipping_price"))
            ->whereIn("fatura_id",[197,196,195,191])
            ->whereIn("user_id",[11371,5348,7410,6818,5916,8062,12336,14661,5535,14503,14664])
            ->groupBy("user_id")->get();
        $i=1;
        $allweight = 0;
        foreach ($result as $inv){
            echo $i.")".$inv->user_id." istifadecisi ".$inv->count." sayinda <b>".$inv->weight."</b> kq cekisinde ".$inv->shipping_price."$ <hr />";
            $allweight += $inv->weight;
            $i++;
        }
        echo $allweight;

        echo "<hr />";
        echo "<hr />";
        echo "<hr />";
        echo "<hr />";
        echo "05.09.2019<br />";
        $result = DB::table("invoices")->select("user_id",DB::raw("count(id) as count"),DB::raw("sum(weight) as weight"),DB::raw("sum(shipping_price) as shipping_price"))
            ->whereIn("user_id",[11371,5348,7410,6818,5916,8062,12336,14661,5535,14503,14664])
            ->whereIn("fatura_id",[194,188,193,185])->groupBy("user_id")->get();
        $i=1;
        $allweight = 0;
        foreach ($result as $inv){
            echo $i.")".$inv->user_id." istifadecisi ".$inv->count." sayinda <b>".$inv->weight."</b> kq cekisinde ".$inv->shipping_price."$ <hr />";
            $allweight += $inv->weight;
            $i++;
        }
        echo $allweight;

    }

    public function indexOld2()
    {
        $api_key = 'TWpBPS5YY2lma0pNc1lkQzBwc240OTNwczAxU00wUkY3RG8zY3p4cE8xcCtpckZJPTo=';
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://api.ypn.io/v1/fulfillments/parcels");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);

        curl_setopt($ch, CURLOPT_POSTFIELDS, "{
  \"operation\": \"manifest\"
}");

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
            "Authorization: Basic ".$api_key
        ));

        $response = curl_exec($ch);
        curl_close($ch);

        var_dump($response);
    }

    public function endirimelemekGecGetirmeye()
    {
        echo "1"; exit;
        $invoices = Invoice::where("country_id",1)->where("status_id",11)->get();
        $i=1;
        foreach ($invoices as $invoice){
            $id = $invoice->id;
            $user_id = $invoice->user_id;

            if($invoice->is_paid==0){
                $price = $invoice->shipping_price;
                $price10 = $price*0.15;
                $new_price = $price - $price10;

                $invoice->shipping_price = $new_price;
                $invoice->delivery_price = $new_price;
                $invoice->save();
                echo $i.")".$id."<br />";
                echo $price; echo "$<br />";
                echo $new_price; echo "$<br />";

                echo "<hr />";
            }else{
                $price = $invoice->shipping_price;
                $price10 = $price*0.15*1.7;


                $user = User::find($user_id);
                $balance = $user->balance;
                $new_balance = $balance + $price10;
                $user->balance = $new_balance;
                $user->save();
                $message = 'Daşınma haqqına 15% endirim';

                LogBalance::create([
                    'user_id' => $user_id,
                    'old_balance' => $balance,
                    'new_balance' => $user->balance,
                    'money' => $price10,
                    'message' => $message,
                    'type' => 'azn'
                ]);

                echo $i.")".$id."<br />";
                echo $price."$<br />";
                echo $price10."AZN<br />";
                echo $balance; echo "AZN<br />";
                echo $new_balance; echo "AZN<br />";

            }

            $i++;
            //$invoice->save();
        }

    }


    public function index2()
    {
        exit;
        $invoices = DB::table("invoices")
            ->leftJoin("user_contacts as uc","invoices.user_id","uc.user_id")
            ->where("status_id",3)->where("country_id",2)->get();
        $i  = 1;
        $array = [];
        $text = 'Hormetli musterimiz, Amerikadan yola cixmis baglamalarin gecikmesine gore Sizden uzr isteyirik! Bu sebebden dasinma haqqina 50% endirim tetbiq edilecek';

        foreach ($invoices as $invoice){

            if(!in_array($invoice->user_id,$array)){
                $user_id = $invoice->user_id;
                $phone = $invoice->name;

                $data = (object) ['text' => $text,"user_id" =>$user_id];
                smsSend($data, $phone);

                echo $i.")".$invoice->user_id." ".$invoice->name."<br />";
                $array[] = $invoice->user_id;
                $i++;

            }
        }
    }

    public function smsGonderHamiya()
    {
        $uc = DB::table('users')
            ->select(DB::raw("DISTINCT(uc.name),users.id as user_id"))
            ->leftJoin("user_contacts as uc","uc.user_id","users.id")
            ->where("uc.name","!=",null)->get();

        $text = 'Deyerli limak.az musterileri! Bu gunden etibaren 4-cu filialimiz olan Zaqatala filiali xidmetinizdedir! Size xidmet etdiyimiz ucun qurur duyuruq! Tesekkurler';
        foreach ($uc as $array){
            $user_id = $array->user_id;
            $phone = $array->name;

            $data = (object) ['text' => $text,"user_id" =>$user_id];
            //smsSend($data, $phone);

            echo $user_id." ",$phone."<hr />";
        }
    }

    public function indexOlkeler()
    {
        $tariffs = Country::where('prefix', '=', 'usa')->with('tariffs')->first();

        $tariffArr = [];

        foreach ($tariffs->tariffs as $val) {
            $tariffArr[$val->label] = (float)$val->price;
        }

        var_dump($tariffArr); exit;

    }

    private function checkInvoice($purchaseNo)
    {
        $label = 'on_the_way';
        $data = Invoice::where('purchase_no', $purchaseNo)->with('invoiceStatus')->whereHas('invoiceStatus', function ($query) use ($label) {
            $query->where('label', '=', $label);
        })->first();
        return $data;
    }

    private function checkInvoice2($purchaseNo){

        $data = DB::table("invoices")->select("id","region_id")->where("purchase_no",$purchaseNo)->where("status_id",3)->first();
        /*$label = 'on_the_way';
        $data = Invoice::where('purchase_no', $purchaseNo)->with('invoiceStatus')->whereHas('invoiceStatus', function ($query) use ($label) {
            $query->where('label', '=', $label);
        })->first();*/
        return $data;
    }

    public function index_tarifss()
    {
        $invoices = InvoiceDates::where("action_date",'0000-00-00 00:00:00')->get();
        $i=0;
        foreach ($invoices as $inv_date){


            $inv_date->action_date = date("Y-m-d H:i:s",'1559049992');
            $inv_date->save();
            $i++;
            echo $i.")".$inv_date->id."<br />";

        }
    }


    public function Paketlesdirmek()
    {
        $array = [];
        $i=1;
        $invoices = DB::table("invoices")->select("id","user_id","package_id")->orderBy("package_id","DESC")->get();
        foreach ($invoices as $invoice){
            if(!in_array($invoice->id,$array)){
                $new = DB::table("invoices")->select("id","user_id","package_id")->where("package_id",$invoice->package_id)->where("user_id","!=",$invoice->user_id)->first();
                if($new!=null and !in_array($new->id,$array)){
                    $array[] = $new->id;
                    $array[] = $invoice->id;

                    $newPackage = new Packages();
                    $newPackage->status=1;
                    $newPackage->save();

                    DB::table('invoices')
                        ->where('user_id', $new->user_id)
                        ->where('package_id', $new->package_id)
                        ->update(['package_id' => $newPackage->id]);


                    echo $i.")".$invoice->id." -> ".$invoice->user_id." -> ".$invoice->package_id; echo "<br />";
                    echo $new->id." -> ".$new->user_id." -> ".$new->package_id;
                    echo "<hr />";
                    $i++;

                }else{
                    //echo $invoice->id." yoxdur";
                }
            }
        }

        exit;
       /* $invoices = DB::table("invoices")->get();
        $i=1;
        foreach ($invoices as $invoice){
            $getInvoice = DB::table("invoices")->where("package_id",$invoice->package_id)->where("user_id","!=",$invoice->user_id)->first();
            if($getInvoice!=null){
                echo $i.")".$invoice->id." -> ".$invoice->package_id." -> ".$invoice->user_id." ->".$getInvoice->user_id; echo "<hr />";
                $i++;
            }
        }*/
    }

    public function InvoysProblemHelli()
    {
        $today = date('d.m.Y');
        //echo 'https://www.cbar.az/currencies/' . $today . '.xml'; exit;
        $currency_data = file_get_contents("https://www.cbar.az/currencies/" . $today . '.xml');
        $xml = simplexml_load_string($currency_data) or die("Error: Cannot create object");
        //file_put_contents('curlog/valyuta.xml', file_get_contents("http://www.cbar.az/currencies/" . $today . ".xml")); // curlog

        if ($xml === false) {
            echo "Failed loading XML: ";
            foreach (libxml_get_errors() as $error) {

                Yii::info($error->message);

            }
        } else {
            $TRY = $xml->xpath('//ValCurs//ValType/Valute[@Code="TRY"]//Value/text()')[0];
            $USD = $xml->xpath('//ValCurs//ValType/Valute[@Code="USD"]//Value/text()')[0];

            //$EUR = $xml->xpath('//ValCurs//ValType/Valute[@Code="EUR"]//Value/text()')[0];
            //$RUB = $xml->xpath('//ValCurs//ValType/Valute[@Code="RUB"]//Value/text()')[0];

            $currency = Currency::first();
            if($currency!=null){

            }else{
                $currency = new Currency();
                $currency->create_at = date("Y-m-d H:i:s");
            }
            $currency->updated_at = date("Y-m-d H:i:s");
            $currency->usd = strval($USD);
            $currency->tl = strval($TRY);
            $currency->save();


            $try_azn = strval($TRY);
            $azn_try = round(1/$try_azn, 4);

            $usd_azn = strval($USD);
            $azn_usd = round(1/$usd_azn,4);

            $usd_try = round((strval($USD) * $azn_try),4);
            $try_usd = round(1/$usd_try,4);


            $array["usd-try"] = $usd_try;
            $array["try-usd"] = $try_usd;
            $array["azn-usd"] = $azn_usd;
            $array["usd-azn"] = $usd_azn;
            $array["azn-try"] = $azn_try;
            $array["try-azn"] = $try_azn;


            foreach ($array as $key=>$value){
                $currenciesRow = Currencies::where("name",$key)->first();
                if($currenciesRow==null){
                    $currenciesRow = new Currencies();
                }

                $currenciesRow->val = $value;
                $currenciesRow->updated_at = date("Y-m-d H:i:s");
                $currenciesRow->save();
            }


        }

        return 'ok';
    }


    public function updatePackageId()
    {
        $lastp=DB::table('invoices as i')->select('package_id')->orderBy('package_id','DESC')->first();
        $pid=$lastp->package_id;
        $data=DB::table('invoices as i')->where('package_id',null)->get();

        dd($data);
        foreach($data as $item){
            DB::table('invoices')
                ->where('id', $item->id)
                ->update(['package_id' => $pid]);
//            var_dump($pid);
            $pid++;
        }
    }
}
