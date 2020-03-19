<?php

namespace App\Http\Controllers\Api;

use App\Currencies;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MillikartCoreController;
use App\ModelNews\News;
use App\ModelNews\NewsTranslate;
use App\ModelProduct\Extras;
use App\ModelProduct\Product;
use App\Models\Courier;
use App\Models\Transactions;
use App\ModelStaticPages\StaticPage;
use App\ModelStaticPages\StaticPageTranslate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\ModelUser\User;
use App\ModelUser\UserContact;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Rules\Phone;
use App\Utility\UserUtility;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class InfoController extends Controller
{


    public function getCurrenciesData(Request $request)
    {
        $lang = $request->header("LANG");
        $curs = Currencies::select("id","name","val")->get();
        $list = ["azn","usd","try"];
        $currencies = [];
        foreach ($curs as $currency){
            $currencies[$currency->name] = $currency;
        }

        $data["list"] = $list;
        $data["currencies"] = $currencies;
        return response()->json(['success' => true, 'data' => $data]);
    }




    public function getCountriesData(Request $request)
    {
        $lang = $request->header("LANG");
        LaravelLocalization::setLocale($lang);
        if($lang == 'az'){
            $suf = '';
        }else{
            $suf = "_".$lang;
        }
        $countries_tariffs = DB::table("country_tariff_translates")->select("id","name")->where("locale",$lang)->limit(4)->get();
        $i = 0;
        $tariff_names = [];
        $labels = ["minWeightPrice","halfWeightPrice","bigHalfWeightPrice","weightPrice"];
        foreach ($countries_tariffs as $t){
            $tariff_names[$labels[$i]] = $t->name;
            $i++;
        }

        $countries = DB::table("countries")->select("id","prefix","eng_name","name")->where("status",1)->get();

        $countries_data = [];
        foreach ($countries as $country){
            $countries_data[$country->id] = [
                "id" => $country->id,
                "prefix" => $country->prefix,
                "name" => __('common.'.$country->eng_name)
            ];

            $data["countries"][] = [
                'id'   => $country->id,
                'name' => __('common.'.$country->eng_name)
            ];
        }
        $regions_data = [];
        $regions = DB::table("regions")->select("id","name".$suf." as name")->where("active",1)->get();

        foreach ($regions as $region){
            $regions_data[$region->id] = [
                "id" => $region->id,
                "name" => $region->name
            ];

            $data["regions"][] =  [
                'id'   => $region->id,
                'name' => $region->name
            ];

        }


        $country_tariffs_data = DB::table("country_tariffs")->select("id","country_id","region_id","price","label")->get();
        $country_tariffs_array = [];

        

      
        foreach ($country_tariffs_data as $ct){
            $country_tariffs_array[$ct->country_id][$ct->region_id][] = ["label" => $tariff_names[$ct->label],"price" => $ct->price];  
        }


        $data["tariffs"] = $country_tariffs_array;

        /////////////////////////////////////////////

        $liquid_tariffs_data = DB::table("country_tariffs")->select("id","country_id","region_id","price","label")->where("country_id",0)->where("region_id",0)->get();
        $liquid_tariffs_array = [];




        foreach ($liquid_tariffs_data as $ct){
            $liquid_tariffs_array[] = ["label" => $tariff_names[$ct->label],"price" => $ct->price];
        }

        $data["regions"][] = ["id" => 777,"name" => "Mayelər"];
        $data["tariffs"][1]["777"] = $liquid_tariffs_array;

        return response()->json(['success' => true, 'data' => $data]);
    }


    public function getContactsData(Request $request)
    {
        $lang = $request->header("LANG");
        LaravelLocalization::setLocale($lang);
        if($lang == 'az'){
            $suf = '';
        }else{
            $suf = "_".$lang;
        }


        $email = 'info@limak.az';
        $whatsapp = '99450 824 95 95';
        $phone = '*9595';

        $numbers = ["baku" => "","sumgait" => '994704919595','ganja' => '994707279595','zagatala' => '994708249595','lankaran' => '99470 624 95 95'];


        $regions = DB::table("regions")->select("id","name".$suf." as name","eng_name")->where("active",1)->get();

        foreach ($regions as $region){
            if($region->eng_name == 'baku'){
                $suf = '';
            }else{
                $suf = "-".$region->eng_name;
            }

            $data["regions"][] = [
                "id" => $region->id,
                "name" => $region->name,
                "email" => $email,
                "whatsapp" => $whatsapp,
                "phone" => $phone,
                "region_phone" => $numbers[$region->eng_name],
                "address" => strip_tags(__('common.address'.$suf))
            ];
        }


        return response()->json(['success' => true, 'data' => $data]);
    }

    public function getUserRulesData(Request $request)
    {
        $lang = $request->header("LANG");

        $data = DB::table('faqs as f')
            ->select('fq.question','fq.answer','fq.faq_id')
            ->leftJoin('faq_translates as fq','f.id','=','fq.faq_id')
            ->where('f.type','1')
            ->where('fq.locale',$lang)
            ->orderBy('f.created_at', 'DESC')
            ->get();
        $result = [];
        foreach ($data as $faq){
            $result[] = [
              "question" => str_replace(["<br />","<br>","\r","\n"],[""],$faq->question),
              "answer" => str_replace(["<br />","<br>","\r","\n"],[""],$faq->answer),
               "faq_id" => $faq->faq_id

            ];
        }
        return response()->json(['success' => true, 'data' => $result]);
    }

    public function getQuestionsData(Request $request)
    {
        $lang = $request->header("LANG");

        $data = DB::table('faqs as f')
            ->select('fq.question','fq.answer','fq.faq_id')
            ->leftJoin('faq_translates as fq','f.id','=','fq.faq_id')
            ->where('f.type','2')
            ->where('fq.locale',$lang)
            ->orderBy('f.created_at', 'DESC')
            ->get();


        return response()->json(['success' => true, 'data' => $data]);
    }

    public function getNews(Request $request)
    {
        $lang = $request->header("LANG");
        $count = $request->get("count",10);
        $allNews=DB::table("news_translates as nt")
            ->leftJoin("news as n","n.id","nt.news_id")
            ->orderBy('nt.created_at',"DESC")->where("nt.locale",$lang)->paginate($count);
        return response()->json(['success' => true, 'data' => $allNews]);

    }

    public function getPage(Request $request)
    {
        $slug = $request->get("action","about");
        $lang = $request->header("LANG");

        if($slug == 'about'){
            $slug = 'about-us';
        }elseif($slug == 'privacy'){
            $slug = 'gizlilik-sertleri';
        }else{
            $slug = 'about-us';
        }

        $staticPageTranslate = StaticPageTranslate::where('slug', $slug)->first();
        $staticPage = null;
        $pageContent = null;
        if (!empty($staticPageTranslate)) {
            $staticPage = StaticPage::findOrFail($staticPageTranslate->static_page_id);
            $staticPageTranslate = StaticPageTranslate::where('static_page_id', $staticPage->id)->where('locale', $lang)->first();
            if (!empty($staticPageTranslate)){
                $pageContent = app('App\Http\Controllers\Admin\StaticPages\ContentStaticPagesController')->bySlug($staticPageTranslate->static_page_id);
                $json = json_encode($pageContent);
                $content_array = json_decode($json,true);
                foreach ($content_array[0]["data"] as $content){
                    if($content["locale"] == $lang){
                        $result = $content["description"];
                    }
                }
                $data["pageContent"] = str_replace(["<br />","<br>","\r","\n"],[""],$result);
                return response()->json(['success' => true, 'data' => $data]);
            }
            return response()->json(['success' => false, 'data' => "Page not found"]);


        }
        return response()->json(['success' => false, 'data' => "Page not found"]);

    }

    public function orderStatus()
    {
        $orderStatus = orderStatus();
        return response()->json(['status' => $orderStatus["status"], 'message' => $orderStatus["text"]]);
    }

    public function invoiceStatus()
    {
        $status = 1;
        $text = "OK";
        $row = DB::table("settings")->first();
        if($row!=null){
            $status = $row->invoice_status;
            $text = $row->invoice_status_text;
        }
        return response()->json(['status' => $status, 'message' => $text]);
    }

    public function courierStatus()
    {
        $status = 1;
        $text = "OK";
        $row = DB::table("settings")->first();
        if($row!=null){
            $status = $row->courier_status;
            $text = $row->courier_status_text;
        }
        return response()->json(['status' => $status, 'message' => $text]);
    }

}
