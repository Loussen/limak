<?php

namespace App\Http\Controllers\Cron;


use App\Email;
use App\Http\Controllers\Controller;
use App\Invoice;
use App\InvoiceDates;
use App\ModelLogs\LogBalance;
use App\ModelUser\User;
use App\Notifications;
use App\Sms;
use Illuminate\Support\Facades\DB;
use App\Currencies;
use App\Currency;
use Carbon\Carbon;


class CronController extends Controller
{
    public function smsSend() {

       $smsAll = Sms::where("status","=",0)->limit(200)->get();
       //var_dump($smsAll); die;
       foreach ($smsAll as $sms){
           $data = (object) ['text' => $sms->text];
           sms($data, str_replace(['+', ' ', ')','('], '',$sms->phone));
           $sms->status = 1;
           $sms->save();
           echo $sms->phone." nomresine ".$sms->text." gonderildi";
       }
    }

    public function emailSend() {
        $emails = Email::where("status","=",0)->limit(50)->get();
        foreach ($emails as $email){
            $data = (object) ['text' => $email->text];
            email($data, $email->email);
            $email->status = 1;
            $email->save();
            echo $email->email." emailine ".$email->text." gonderildi";
        }
    }

    public function notificationSend() {

        $notifications = Notifications::where("status","=",0)->limit(200)->get();
        //var_dump($smsAll); die;
        foreach ($notifications as $notification){
            sendNotification($notification->text,$notification->title,$notification->fcm_token,'ANDROID');
            $notification->status = 1;
            $notification->save();
            echo $notification->user_id." istifadecisine ".$notification->text." gonderildi."."<br />";
        }
    }

    public function currency()
    {
        $today = date('d.m.Y');
        //echo 'https://www.cbar.az/currencies/' . $today . '.xml'; exit;
        $currency_data = file_get_contents("https://www.cbar.az/currencies/" . $today . '.xml');
        $xml = simplexml_load_string($currency_data) or die("Error: Cannot create object");
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
                    $currenciesRow->name = $key;
                }

                $currenciesRow->val = $value;
                $currenciesRow->updated_at = date("Y-m-d H:i:s");
                $currenciesRow->save();
            }


        }

        return 'ok';
    }



    public function currency_pashaBank()
    {
        echo '<meta charset="UTF-8">';
        include($_SERVER["DOCUMENT_ROOT"].'/simple/simple_html_dom.php');
        $url='https://www.pashabank.az/exchange_valyuta_azn_currency_rate/';

        $html = file_get_html($url,false,null,0);

        $table = $html->find('.currency_prices')[0];
        $theData = array();
        foreach($table->find('tr') as $row) {

            $rowData = array();
            foreach($row->find('td') as $cell) {
                $rowData[] = trim($cell->innertext);
            }
            $theData[] = $rowData;
        }

        $usd_azn = number_format($theData[1][2],4);
        $try_azn = number_format($theData[6][2],4);
        $azn_try = number_format(floatval(1/$try_azn),4);
        $azn_usd = number_format(floatval(1/$usd_azn),4);

        $usd_try = number_format($usd_azn*$azn_try,4);
        $try_usd = number_format(1/$usd_try,4);


        DB::table("currencies")->where("name","usd-try")->limit(1)->update(["val" => $usd_try]);
        DB::table("currencies")->where("name","try-usd")->limit(1)->update(["val" => $try_usd]);
        DB::table("currencies")->where("name","azn-usd")->limit(1)->update(["val" => $azn_usd]);
        DB::table("currencies")->where("name","usd-azn")->limit(1)->update(["val" => $usd_azn]);
        DB::table("currencies")->where("name","azn-try")->limit(1)->update(["val" => $azn_try]);
        DB::table("currencies")->where("name","try-azn")->limit(1)->update(["val" => $try_azn]);

        $currency = Currency::first();
        if($currency!=null){

        }else{
            $currency = new Currency();
            $currency->create_at = date("Y-m-d H:i:s");
        }
        $currency->updated_at = date("Y-m-d H:i:s");
        $currency->usd = strval($usd_azn);
        $currency->tl = strval($try_azn);
        $currency->save();

        echo '1 usd = '.$usd_azn." azn<br />";
        echo '1 azn = '.$azn_usd." usd<br />";
        echo '1 usd = '.$usd_try." try<br />";
        echo '1 try = '.$try_usd." usd<br />";
        echo '1 azn = '.$azn_try." try<br />";
        echo '1 try = '.$try_azn." azn<br />";

    }

    public function corporate()
    {
        $invoices = DB::table("invoices as i")->select("user_id",DB::raw("sum(i.weight) as weight"))->where("corporate",1)->where("i.status_id",3)->where("i.s_id",1)->groupBy("i.user_id")->get();
        $i = 1;
        foreach ($invoices as $row){
            if($row->weight>10){
                $user_id = $row->user_id;
                $user_invoices = Invoice::where("user_id",$user_id)->where("corporate",1)->where("liquid_type",0)->where("status_id",3)->where("s_id",1)->get();
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

    // Anbardaki mallar uzerinde emeliyyat
    public function depo()
    {
        //13 gunluk
        $days_ago = date('Y-m-d', strtotime('-13 days'))." 00-00";
        $days_ago2 = date('Y-m-d', strtotime('-13 days'))." 23-59";

        $result = DB::table("invoices as i")
            ->select("i.id","i.user_id","ind.action_date","uc.name as phone",'r.name as region_name','i.purchase_no')
            ->leftJoin("regions as r","r.id","i.region_id")
            ->leftJoin("user_contacts as uc","uc.user_id","i.user_id")
            ->leftJoin("invoice_dates as ind","i.id","ind.invoice_id")
            ->where("ind.action_date",">=",$days_ago)
            ->where("ind.action_date","<=",$days_ago2)
            ->where("ind.status_id",4)
            ->where("i.status_id",4)
            ->where("i.active",1)
            ->get();
        $i=1;

        foreach ($result as $invoice){
            $text = 'Diqqet! '.$invoice->purchase_no.' nomreli baglamaniz 13gundur ki, '.$invoice->region_name.' anbarindadir. 15gun tamamlandiqda anbarda qalan her gun ucun 1 azn cerime haqqi hesablanacaq.';

            $user_id = $invoice->user_id;
            $phone = $invoice->phone;

            $data = (object) ['text' => $text,"user_id" =>$user_id];
            smsSend($data, $phone);

            echo $i.")".$invoice->id." - ".$invoice->action_date."<br />";
            echo $invoice->phone; echo "<hr />";
            $i++;
        }

        // 43 gunluk
        $days_ago = date('Y-m-d', strtotime('-43 days'))." 00-00";
        $days_ago2 = date('Y-m-d', strtotime('-43 days'))." 23-59";

        $result = DB::table("invoices as i")
            ->select("i.id","i.user_id","ind.action_date","uc.name as phone",'r.name as region_name','i.purchase_no')
            ->leftJoin("regions as r","r.id","i.region_id")
            ->leftJoin("user_contacts as uc","uc.user_id","i.user_id")
            ->leftJoin("invoice_dates as ind","i.id","ind.invoice_id")
            ->where("ind.action_date",">=",$days_ago)
            ->where("ind.action_date","<=",$days_ago2)
            ->where("ind.status_id",4)
            ->where("i.status_id",4)
            ->where("i.active",1)
            ->get();
        $i=1;
        foreach ($result as $invoice){
            $text = 'Diqqet! '.$invoice->purchase_no.' nomreli baglamaniz 43 gundur ki, '.$invoice->region_name.'  anbarindadir. 45gun tamamlandiqdan sonra, baglama legv edilecek.';

            $user_id = $invoice->user_id;
            $phone = $invoice->phone;

            $data = (object) ['text' => $text,"user_id" =>$user_id];
            smsSend($data, $phone);

            echo $i.")".$invoice->id." - ".$invoice->action_date."<br />";
            echo $invoice->phone; echo "<hr />";
            $i++;
        }
    }


    public function depoMinusFine()
    {
        $orderStatus = orderStatus();

        if($orderStatus["status"]==1){
            $days_ago = date('Y-m-d', strtotime('-17 days'));
            $days_ago2 = date('Y-m-d', strtotime('-45 days'));
            $result = DB::table("invoices as i")
                ->select("i.id","ind.action_date","i.user_id","i.purchase_no","di.depot_id")
                ->leftJoin("invoice_dates as ind","i.id","ind.invoice_id")
                ->leftJoin("depot_invoices as di","di.invoice_id","i.id")
                ->where("ind.action_date","<=",$days_ago)
                ->where("ind.action_date",">=",$days_ago2)
                ->where("ind.status_id",4)
                ->where("i.status_id",4)
                ->where("i.active",1)
                ->get();
            $i=1;
            foreach ($result as $invoice){
                if($invoice->depot_id!=null){
                    $user = User::find($invoice->user_id);
                    if($user!=null){
                        $note = $invoice->purchase_no." cerime: ".date("Y-m-d");
                        $log_isset = DB::table("log_balances")->where("note",$note)->first();
                        if($log_isset==null){
                            $balance = $user->balance;
                            $new_balance = $balance-1;
                            $user->balance = $new_balance;
                            $user->save();
                            $log = new LogBalance();
                            $log->user_id = $user->id;
                            $log->old_balance = $balance;
                            $log->new_balance = $new_balance;
                            $log->money = 1;
                            $log->type = 'azn';
                            $log->message = 'Anbarda qalan '.$invoice->purchase_no." nömrəli məhsula görə cərimə: Tarix: ".date("d-m-Y");
                            $log->created_at = date("Y-m-d H:i:s");
                            $log->updated_at = date("Y-m-d H:i:s");
                            $log->note = $note;
                            $log->save();
                            echo $log->message."<br />";
                        }else{

                            echo $note." artiq balansdan cixilib<br />";
                        }

                    }
                    $i++;
                }

            }
        }

    }

    public function vipexStatus()
    {
        $i = 0;
        $datas["company"] = 'limak';
        $datas["password"] = 'Limak123456@';
        $invoices = Invoice::whereRaw("company_status_id!=status_id")->where("person_id",">",0)->whereIn("status_id",[1,2,3,4,5,11])->limit(20)->get();
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

}
