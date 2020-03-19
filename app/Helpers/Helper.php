<?php

use App\ModelPermissions\Role;
use App\Status;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon ;

function languages() {
//    return \App\Language::all();
    return [["name" => "az"], ["name" => "ru"], ["name" => "en"]];
}

// for balance log
function getBalanceLogType($type) {
    $types = ["profit" => 0, "expenditure" => 1];
    return $types[$type];
}

/**
 * @param $model
 * @param $request
 * @param $staticFields - which fields don`t need translate
 * @param null $file - if you have an file you must send file name
 * @param null $translate -  {fields: ['name', 'description'], modelName: 'App\ModelShop\ShopTranslate'}
 * @param null $parentId - string 'shop_id'
 * @return array
 */

function crud($model, $request, $staticFields, $file = null, $translate = null, $parentId = null, $type = 'add') {

//    if ($file !== null) {
//        $model->file = $file;
//    }

    if(!empty($file))
    {
        $model->file = $file;
    }

    if (is_null($staticFields)) {
        $status = $model->save();
    } else {
        foreach ($staticFields as $field) {
            $model->$field = $request->$field;
        }
        $status = $model->save();
    }

    if ($type === 'add' && $translate !== null && $model->id !== null) {

        foreach (languages() as $lang)
        {
            $translateModel = new $translate['modelName'];
            foreach ($translate['fields'] as $field) {
                $translateModel->$field = $request->$field[$lang['name']];
            }
            $translateModel->$parentId = $model->id;
            $translateModel->locale = $lang['name'];
            $translateModel->save();

//            if(!(in_array('name',$translate['fields']) && empty($request->name[$lang['name']])))
//            {
//                $translateModel = new $translate['modelName'];
//                foreach ($translate['fields'] as $field) {
//                    $translateModel->$field = $request->$field[$lang['name']];
//                }
//                $translateModel->$parentId = $model->id;
//                $translateModel->locale = $lang['name'];
//                $translateModel->save();
//            }
        }
    }

    if ($type === 'update' && $translate !== null && $model->id !== null) {
        //dd($request);
        //print_r($translate);
        foreach ($translate['modelName'] as $translateModel) {
            foreach (languages() as $lang) {
                if ($translateModel->locale == $lang['name']) {
                    foreach ($translate['fields'] as $field) {
                        $translateModel->$field = $request->$field[$lang['name']];
                    }
                    $translateModel->save();
                }
            }
        }
    }

    return ['status' => $status,'id' => $model->id];
}


/**
 * @param $label - Status`s label - (waiting, rejected, etc)
 * @param $statusType Status`s type - (invoice, product, transaction)
 * @return mixed
 */
function getStatusByLabel($label, $statusType) {
    $status = Status::where([
        ['label', '=', $label],
        ['type', '=', $statusType]
    ])->first();

    //return $status->id;
    return $status->sid;
}

function getRoleByLabel($label) {
    $role = Role::where('label', '=', 'accountant')->first();

    return $role->id;
}

function setBase64Image ($file_data, $imgHeading, $ext, $where) {
    $image = '';
    if(!empty($file_data))
    {
        $file_name = $imgHeading.time().'.'.$ext;
        list($type, $file_data) = explode(';', $file_data);
        list(, $file_data) = explode(',', $file_data);

        if($file_data!=""){
            $path = $where.'/'.$file_name;
            $result = Storage::disk('local')->put($path, base64_decode($file_data));
        }

        return ($where.'/'.$file_name);
    }
}

function uploadImage($file_data, $imgHeading, $ext, $where)
{
    if(!empty($file_data))
    {
        $file_name = $imgHeading.time().'.'.$ext;
        $path = $where.'/'.$file_name;
        Storage::disk('local')->put($path, file_get_contents($file_data));
        return ($where.'/'.$file_name);
    }
}

function deleteImage($fileName) {
    if ($fileName) {
        Storage::disk('local')->delete($fileName);
    }
}

function staticPage($label, $lang) {
    $page = \App\ModelStaticPages\StaticPage::where('label', $label)->first();
    $staticpage = null;
    if (!empty($page) && !empty($page->id)) {
        $staticpage = \App\ModelStaticPages\StaticPageTranslate::where('locale', $lang)->where('static_page_id', $page->id)->first();
    }
    return $staticpage;
}
function staticPages($lang, $devided = null) {
    $staticpage = \App\ModelStaticPages\StaticPageTranslate::where('locale', $lang)->get();
    if(!is_null($devided)) {
        $arr = [];
        $a = [];
        foreach($staticpage as $i => $p) {
            if(($i + 1) % $devided !== 0) {
                $a[] = $p;
            } else {
                $a[] = $p;
                $arr[] = $a;
                $a = [];
            }
        }
        if (count($a) > 0) {
            $arr[] = $a;
        }
        $staticpage = $arr;
    }
    return $staticpage;
}

function template($name) {
    $body = '';
    if ($name === 'order-placed') {
        $body = 'Hormetli mushteri, sifarishiniz icra olundu. Baglamanizi https://limak.az vasitesi ile izleye bilersiniz.';
    } else if($name === 'order-in-turkey') {
        $body = 'Baglamaniz Turkiye anbarimiza daxil olub. Baglamanizi https://limak.az vasitesi ile izleye bilersiniz.';
    } else if($name === 'order-in-home') {
        $body = 'Hormetli mushteri, baglamaniz artiq Baki ofisimizdedir. Unvan: Lermontov kuc. 113/117. Icherisheher m/st yaxınligi. https://limak.az ';
    } else if($name === 'express-order-done') {
        $body = 'Sizin sifarisiniz yerine yetirildi. &quot;Tecili sifaris&quot; xidmetinden istifade etdiyiniz üçün tesekkür edirik.';
    } else if($name === 'invoice-id-absent') {
        $body = 'Diqqet! Siz Beyanname elave etmeyi unutmusunuz. Gomruk resmilesdirmesi uçun TeCiLi Beyannameni doldurub, invoysu sisteme elave etmeyiniz xahis olunur.';
    } else if($name === 'invoice-id-absent-us') {
        $body = 'Diqqet! Siz USA-dan aldiginiz mehsula beyanname elave etmeyi unutmusunuz. Gomruk resmilesdirmesi uçun TeCiLi Beyannameni doldurub, invoysu sisteme elave etmeyiniz xahis olunur.';
    } else if ($name === 'insufficient-cargo') {
        $body = 'Diqqet! Siz Türkiyeiçi karqo pulunu odemeyi unutmusunuz. Sifarisin verilmesi üçün sisteme daxil olub, odenisi tamamlamaginiz xahis olunur.';
    } else if ($name === 'order-accepted') {
        $body = 'Sifarisiniz qebul olundu. Sifarisinizi www.limak.az vasitesile izleye bilersiniz.';
    } else if ($name === 'courier-assigned') {
        $body = 'Sizin sifarisiniz üçün kuryer teyin edildi.';
    } else if ($name === 'courier-delivered') {
        $body = 'Sifarisiniz tehvil verildi. Xidmetimizden istifade etdiyiniz ucun tesekkür edirik.';
    } else if( $name === 'from-turkey-depot'){
        $body = 'Hormetli mushteri, baglamaniz Turkiye anbarimizdan yola cıxdı. Baglaminizi https://limak.az vasitesi ile izleye bilersiniz.';
    } else if($name === 'courier-service'){
        $body = 'Xatirladaq ki, kuryer xidmetimizden yararlana bilersiniz. Baki ve Sumqayıtda istenilen unvana catdirilir. Xidmət haqqı:12 saat erzində 3 AZN.';
    } else if($name === 'post-transfer'){
        $body = 'Poçt ilə çatdırılma sifarisiniz qebul olundu. Sifarisinizi www.limak.az vasitesile izleye bilersiniz.';
    }

    return $body;
}

function smsSend($data, $receiver)
{
    $body = !empty($data->template) && $data->template !== null ? template($data->template) : '';
    $text = !empty($data->text) && $data->text !== null ? $data->text : '';
    $body .= ' ' . $text;
    $user_id = !empty($data->user_id) && $data->user_id !== null ? $data->user_id : '';

    $receiver = phoneNumber($receiver);
    if ($receiver){
        $sms = new \App\Sms();
        $sms->user_id = $user_id;
        $sms->phone = $receiver;
        $sms->text = $body;
        $sms->created_at = date("Y-m-d H:i:s");
        $sms->priority = 0;
        $sms->status = 0;
        if($sms->save()){
            return true;
        }else{
            return false;
        }
    }else{
        return false;
    }
}

function emailSend($data, $receiver) {
    $body = !empty($data->template) && $data->template !== null ? template($data->template) : '';
    $text = !empty($data->text) && $data->text !== null ? $data->text : '';
    $body .= ' '. $text;
    $user_id = !empty($data->user_id) && $data->user_id !== null ? $data->user_id : '';

    $email = new \App\Email();
    $email->user_id = $user_id;
    $email->email = $receiver;
    $email->text = $body;
    $email->created_at = date("Y-m-d H:i:s");
    $email->priority = 0;
    $email->status = 0;

    if($email->save()){
        return true;
    }else{
        return false;
    }
}

function sms($data, $receiver) {

    $body = !empty($data->template) && $data->template !== null ? template($data->template) : '';
    $text = !empty($data->text) && $data->text !== null ? $data->text : '';
    $body .= ' '. $text;

    $login    = 'ona.az';
    $password = 'ona2019az';
    $sender   = 'Limak.az';

    $key = md5(md5($password).$login.$body.$receiver.$sender);

    $api = "http://apps.lsim.az/quicksms/v1/send?login={$login}&msisdn={$receiver}&text={$body}&sender={$sender}&key=$key";

//  $api = 'http://gw.maradit.net/api/xml/reply/submit?Credential={Username:appa1,Password:347h3i4}&Header={From:Limak.az}&Message='.$body.'&To=['.$receiver.']&DataCoding=Default';

    $client = new \GuzzleHttp\Client();
    $res = $client->request('GET', $api);
    return true;
}

function email($data, $receiver) {
    $body = !empty($data->template) && $data->template !== null ? template($data->template) : '';
    $text = !empty($data->text) && $data->text !== null ? $data->text : '';
    $subject = 'Limak.az';
    $body .= ' '. $text;
    \Mail::send('emails.notification',['body' => $body], function($message) use ($receiver, $subject){
        $message->from( env('MAIL_USERNAME'), $subject);
        $message->sender(env('MAIL_USERNAME'), $subject);
        $message->to( $receiver , 'Receiver')->subject($subject);
    });
    return true;
}

function sendNotification($mess,$title,$token,$platform,$type=0,$type_id=0)
{

   /* $notifyTitle = explode('/type',$title);
    if($type==1){
        $message_type = 'message';
    }else{
        $message_type = 'notification';
    }
    switch( $platform ){
        default: case "IOS":
        $fields = array (
            'priority' =>'high',
            'to' => $id,
            'data' => array (
                "content_available" => true,
                "body" => $mess,
                "title" => $title,
                "message_type" => $message_type,
                "type_id" => $type_id,
            ),
            'notification' => array(
                "content_available" => true,
                "body" => $mess,
                "title" => $title,
                "sound" => 'default'
            )
        );
        break;

        case "ANDROID":
            $fields = array (
                'priority' =>'high',
                'to' => $id,
                'data' => array (
                    "content_available" => true,
                    "body" => $mess,
                    "title" => $title,
                    //"message_type" => $message_type,
                    "type_id" => $type_id,
                )
            );
            break;
    }*/

    //sendNotification($notification->text,$notification->title,$notification->fcm_token,'ANDROID');

    $type = 'standart';

    $api_key = 'AAAAwXvTRxg:APA91bE2_Q0lng_dBQKoneHeMEDCEFNhwIgjD3fBwLmYYdZFMojENJiSDZO8BHV-ddqIuJQdnRevjlhzuNVgbqIbReY-nAtnvA9gWAWN2HlZO0O9F61qqJM3F8IdDSl1mubZIFFMPrB6';

    $url = 'https://fcm.googleapis.com/fcm/send';

    $fields = $fcm_data = [
        "to" => $token,
        "notification" => [
            "body" => $mess,
            "title" => $title,
            "icon" => "ic_launcher",
            "type" => $type
        ]
    ];

    $fields = json_encode ( $fields );
    $headers = array(
        'Content-Type:application/json',
        'Authorization:key='.$api_key
    );

    $ch = curl_init ();
    curl_setopt ( $ch, CURLOPT_URL, $url );
    curl_setopt ( $ch, CURLOPT_POST, true );
    curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields );

    $result = curl_exec ( $ch );
    //var_dump($result); exit;
    curl_close ( $ch );

    return 1;
}





function notify($data, $receiver) {
    if (!empty($receiver->phone)) {
        smsSend($data, $receiver->phone);
    }
    if (!empty($receiver->email)) {
        emailSend($data, $receiver->email);
    }
    return true;
}



function hasRole ($label, $data) {
    if(in_array('super_admin', $data)) {
        return true;
    }
    return in_array($label, $data);
}

function calculateDeliveryPrice () {

}

function numberToWord($num,$words) {
    if(!$num) return;
    $num_array = explode('.',$num);
    $main_part = str_replace(',', '', $num_array[0]);
    $second_part = $num_array[1];
    $number_text_main = "";
    $number_length_main =  strlen((string)$main_part);
    if($number_length_main === 5){
        $first = substr($main_part,0,2);
        $second = substr($main_part,2);
        $first_text="";
        for($i = 0; $i < 2; $i++){
            $digit = substr($main_part,$i,1);
            $first_text .=" " .$words[2 - $i - 1][$digit];
        }
        $number_text_main = $first_text . ' bin';

        $second_text="";
        for($i = 0; $i < 3; $i++){
            $digit = substr($second,$i,1);
            $second_text .=" " .$words[3 - $i - 1][$digit];
        }
        $number_text_main .= $second_text;


    } else{
        for($i = 0; $i < $number_length_main; $i++){
            $digit = substr($main_part,$i,1);
            $number_text_main .=" " .$words[$number_length_main - $i - 1][$digit];
        }

    }

    $number_length_second =  strlen((string)$second_part);
    $number_text_second = "";
    for($i = 0; $i < $number_length_second; $i++){
        $digit = substr($second_part,$i,1);
        $number_text_second .=" " .$words[$number_length_second - $i - 1][$digit];
    }
    if(trim($number_text_second)){
        $number_text_second = $number_text_second . ' cent';
    } else{
        $number_text_second = "";
    }
    return $number_text_main . " euro". $number_text_second;
}

function phoneNumber($phone_str){
    $result = false;
    $operators = [50,51,55,70,77];
    $phone = str_replace([" ","9940","+","(",")","-"],["","994","","","",""],$phone_str);
    if(strlen($phone)==12){
        $operator = substr($phone,3,2);
        if(in_array($operator,$operators)){
            if(substr($phone,5,1)!=1){
                $result =  $phone;
            }
        }else{
            $result =  false;
            //echo $phone." operator duzgun deyil<br />";
        }
    }else{
        $result =  false;
        //echo $phone."nomre duzgun deyil<br />";
    }

    return $result;
}

function userLast30($user_id,$client_id=0,$person_id=0){
    $last30days_price = 0;

    $currency = \App\Currencies::where("name","try-usd")->first();
    $tryToUsd = $currency->val;

    $last30days = DB::table('invoices')
        ->select("invoices.shipping_price","invoices.client_id","invoices.price","invoices.country_id")
        //->select(DB::raw("sum(shipping_price) as shipping_price"),DB::raw("sum(price) as price"))
        ->whereIn('id', function($query)
        {
            $status = Status::where('label', '=', 'on_the_way')->where('type', '=', 'invoice')->first();
            $query->select('invoice_id')
                ->from('invoice_dates')
                ->where('action_date', '>', Carbon::now()->subDays(30)->toDateTimeString())
                ->where("status_id","=",$status->sid);
        })
        ->where('invoices.user_id','=',$user_id)
        ->where('invoices.client_id','=',$client_id)
        ->where('invoices.person_id','=',$person_id)
        ->where('invoices.status_id','!=',777)
        ->get();

    foreach ($last30days as $row){
        if($row->country_id==1){
            $last30days_price =  round((float)( (float)$last30days_price + (float)$row->price*$tryToUsd+ (float)$row->shipping_price), 2);
        }elseif($row->country_id==2){
            $last30days_price =  round((float) ((float)$last30days_price + (float)($row->price) + (float)($row->shipping_price)), 2);
        }
    }

    return $last30days_price;
}

function ownHash($num){
    $deleted_from = [0,1,2,3,4,5,6,7,8,9];
    $replaced_from = ['a','b','c','d','e','f','g','i','j','k'];
    return str_replace($deleted_from, $replaced_from, $num * 777 );
}

function ownUnHash($str){
    $replaced_from = [0,1,2,3,4,5,6,7,8,9];
    $deleted_from = ['a','b','c','d','e','f','g','i','j','k'];
    return (int) str_replace($deleted_from, $replaced_from, strtolower($str)) / 777;
}

function generateHashId($id)
{
    $hash = $id*7+1000;
    return $hash;
}

function unHashId($hash){
    if(($hash-1000)%7==0){
        $id = ($hash-1000)/7;
    }else{
        $id = 0;
    }
    return $id;
}

function lbToKg($lb)
{
    $kg = (float)($lb/2.205);
    $kg = round($kg,3);
    return $kg;
}

function inchToSm($i)
{
    $sm = (float)($i*2.54);
    $sm = round($sm,2);
    return $sm;

}

function dimensionalWeight($width, $length, $height, $weight) {
    $dimensionalWeight = round(($width * $length * $height) / 6000, 2);

    if ($dimensionalWeight > $weight) {
        return $dimensionalWeight;
    } else {
        return $weight;
    }
}

function orderStatus()
{
    $settings = DB::table("settings")->first();
    if(date("Y-m-d H:i:s",strtotime($settings->pause_begin_date))<=date("Y-m-d H:i:s") and date("Y-m-d H:i:s",strtotime($settings->pause_end_date))>=date("Y-m-d H:i:s")){
        $data = ["status" => 0,"text" => $settings->status_text];
    }else{
        $data = ["status" => 1,"text" => "Aktivdir"];
    }

    return $data;
}

function calculateInvoiceShipping($invoice){
    if($invoice!=null) {

        $region_id = $invoice->region_id;
        $country_id = $invoice->country_id;

        $prefix = 'tr';
        if($country_id == 1)      $prefix = 'tr';
        elseif($country_id == 2)  $prefix = 'usa';

        $tariffs = \App\ModelCountry\Country::where('prefix', '=', $prefix)->with('tariffs')->first();

        $tariffArr = [];

        foreach ($tariffs->tariffs as $val) {
            if ($val->region_id == $region_id) {
                $tariffArr[$val->label] = (float)$val->price;
            }
        }

        $cm = 99;

        $result = 0;
        $weightResult = (float)($invoice->length > $cm || $invoice->width > $cm || $invoice->height > $cm) ? dimensionalWeight($invoice->width, $invoice->length, $invoice->height, $invoice->weight) : $invoice->weight;

        if ($weightResult > 0) {
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

        return $result;
    }

    return 0;
}



/**
 *  Notify all begin******************************************************************
 * */

function notifyAll($data,$receiver){
    if (!empty($receiver->phone)) {
        smsSendDirect($data, $receiver->phone);
    }
    if (!empty($receiver->email)) {
        emailSendDirect($data, $receiver->email);
    }

    if (!empty($receiver->fcm_token)) {
        notificationSendDirect($data, $receiver->fcm_token);
    }

    return true;
}


function smsSendDirect($data, $receiver)
{
    $body = !empty($data->template) && $data->template !== null ? template($data->template) : '';
    $text = !empty($data->text) && $data->text !== null ? $data->text : '';
    $body .= ' ' . $text;
    $user_id = !empty($data->user_id) && $data->user_id !== null ? $data->user_id : '';

    $receiver = phoneNumber($receiver);
    if ($receiver){
        $sms = new \App\Sms();
        $sms->user_id = $user_id;
        $sms->phone = $receiver;
        $sms->text = $body;
        $sms->created_at = date("Y-m-d H:i:s");
        $sms->priority = 0;
        $sms->status = 0;
        if($sms->save()){
            return true;
        }else{
            return false;
        }
    }else{
        return false;
    }
}

function emailSendDirect($data, $receiver) {
    $body = !empty($data->template) && $data->template !== null ? template($data->template) : '';
    $text = !empty($data->text) && $data->text !== null ? $data->text : '';
    $body .= ' '. $text;
    $user_id = !empty($data->user_id) && $data->user_id !== null ? $data->user_id : '';

    $email = new \App\Email();
    $email->user_id = $user_id;
    $email->email = $receiver;
    $email->text = $body;
    $email->created_at = date("Y-m-d H:i:s");
    $email->priority = 0;
    $email->status = 0;

    if($email->save()){
        return true;
    }else{
        return false;
    }
}

function notificationSendDirect($data, $receiver) {
    $body = !empty($data->template) && $data->template !== null ? template($data->template) : '';
    $text = !empty($data->text) && $data->text !== null ? $data->text : '';
    $title = !empty($data->title) && $data->title !== null ? $data->text : 'Limak.az';
    $body .= ' '. $text;
    $user_id = !empty($data->user_id) && $data->user_id !== null ? $data->user_id : '';

    $notf = new \App\Notifications();
    $notf->user_id = $user_id;
    $notf->fcm_token = $receiver;
    $notf->title = $title;
    $notf->text = $body;
    $notf->created_at = date("Y-m-d H:i:s");
    $notf->status = 0;

    if($notf->save()){
        return true;
    }else{
        return false;
    }
}

/**
 *  Notify all end******************************************************************
 * */