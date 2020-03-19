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


class SendController extends Controller
{

    public function notificationSend()
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



    public function AmerikaAnbardakilaraGecikmeileBagli()
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
}
