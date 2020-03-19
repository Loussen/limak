<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MillikartCoreController;
use App\Invoice;
use App\InvoicePayment;
use App\ModelLogs\LogBalance;
use App\ModelLogs\LogPaymentDeliveryInvoices;
use App\ModelProduct\Extras;
use App\ModelProduct\Product;
use App\Models\Courier;
use App\Models\Transactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\ModelUser\User;
use App\ModelUser\UserContact;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Rules\Phone;
use App\Utility\UserUtility;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\DB;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class UserController extends Controller
{


    public function main(Request $request){

        $User = $request->get('user');

        $UserUtility = new UserUtility($User);

        $data = [
            'last30Days' => number_format($UserUtility->last30Days(), 2),
            'balance'   => $User->balance,
            'million' => generateHashId($User->id),
            'balance_try'   => $User->balance_try,
            'lastBalanceOperation' => ''
        ];

        return response()->json(['success' => true, 'data' => $data]);
    }

    public function changePassword(Request $request){

        $User = $request->get('user');

        $data = $request->all();
        $validator =  Validator::make($data, [
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json(['success'=> false,'message'=>$validator->errors()]);
        }

        $password = $data["password"];

        $User->password = Hash::make($password);

        $User->save();

        return response()->json(['success' => true, 'data' => 'Şifrəniz uğurla dəyişdirildi']);
    }

    public function addBasket(Request $request){

        $User = $request->get('user');

        $Extras = new Extras();
        $Extras->link = $request->get('link');
        $Extras->save();

        $Product = new Product();
        $Product->user_id = $User->id;
        $Product->region_id = $User->region_id;
        $Product->product_type_id = 0;
        $Product->product_type_name = $request->product_type_name;
        $Product->rel_user_product_id = 0;
        $Product->price = 0;
        $Product->quantity = 1;
        $Product->shop_name = '';
        $Product->description = '';
        $Product->status_id = 1;
        $Product->extras_id = $Extras->id;
        $Product->save();



        return response()->json(['success' => true, 'data' => '']);
    }

    public function getInvoice(Request $request){

        $id = intval($request->id);
        $User = $request->get('user');

        $userId = $User->id;

        $invoice = Invoice::with('dates')->with('packages')->with('packages.products')->with('packages.products.extras')->with('invoiceStatus')->where("id",$id)->where("user_id",$userId)->first();
        if($invoice!=null){
            return response()->json(['success' => true, 'data' => $invoice]);
        }else{
            return response()->json(['success' => false, 'data' => 'Error']);
        }
    }

    public function getUserData(Request $request, $cb){
        $User = $request->get('user');
        $lang = $request->header("LANG");

        $UserUtility = new UserUtility($User);

        if(!method_exists($UserUtility, $cb)) return response()->json(['success' => false, 'message' => 'Method not allowed']);

        if($cb == 'orders'){
            $status_id = $request->get("status_id",0);
            $country_id = $request->get("country_id",0);
            $data = $UserUtility->$cb($country_id,$status_id,$lang);
        }elseif($cb == 'track'){
            $invoice_id = $request->get("invoice_id",0);
            $data = $UserUtility->$cb($invoice_id,$lang);
        }elseif($cb == 'statuses'){
            $country_id = $request->get("country_id",1);
            $data = $UserUtility->$cb($country_id,$lang);
        }elseif($cb == 'clientAddresses'){
            $client_id = $request->get("client_id",0);
            $data = $UserUtility->$cb($client_id);
        }
        else{
            $data = $UserUtility->$cb();
        }

        return response()->json(['success' => true, 'data' => $data]);
    }


    public function addClient(Request $request){

        $User = $request->get('user');

        $status = false;

        if($User->corporate==1){

            $UserUtility = new UserUtility($User);

            $data = $UserUtility->addClient($request);

            if($data["code"] == 200){

                $status = true;
            }
        }else{
            $data["message"] = ['Korporativ istifadəçi deyil'];
        }

        return response()->json(['success' => $status, 'data' => $data["message"]]);

    }

    public function editClient(Request $request){

        $User = $request->get('user');

        $status = false;

        if($User->corporate==1){

            $UserUtility = new UserUtility($User);

            $data = $UserUtility->editClient($request);

            if($data["code"] == 200){

                $status = true;
            }
        }else{
            $data["message"] = ['Korporativ istifadəçi deyil'];
        }

        return response()->json(['success' => $status, 'data' => $data["message"]]);

    }

    public function addInvoice(Request $request){
        $User = $request->get('user');

        $UserUtility = new UserUtility($User);

        $data = $UserUtility->addInvoice($request);

        return response()->json(['success' => true, 'data' => $data]);
    }

    public function order(Request $request){
        $User = $request->get('user');

        $UserUtility = new UserUtility($User);

        $status = 1;
        $row = DB::table("settings")->first();
        if($row!=null){
            $status = $row->order_status;
        }

        if($status==0){
            return response()->json("Sifariş et xidməti aktiv  deyil");
        }

        $data = $UserUtility->order($request);

        if($data["success"]){
            $total = (float) round($request->total, 2);
            $token = $this->payByTr($total, $request->data, $data["transaction_id"], $User);
            return response()->json(['success' => true, 'token' => $token]);
        }else{
            return response()->json(['success' => false, 'token' => false]);
        }

    }

    public function orderBalance(Request $request){
        $User = $request->get('user');

        $UserUtility = new UserUtility($User);


        $status = 1;
        $row = DB::table("settings")->first();
        if($row!=null){
            $status = $row->order_status;
        }

        if($status==0){
            return response()->json("not working");
        }


        $data = $UserUtility->orderBalance($request);

        if($data["status"]==200){
            $result= ["success" => true,"data" => $data["data"]];
        }else{
            $result= ["success" => false,"data" => $data["data"]];
        }

        return response()->json($result);
    }

    public function payByTr($amount, $orders, $uniqueId, $user)
    {


        $merchant_id 	= config('app.merchant_id');
        $merchant_key 	= config('app.merchant_key');
        $merchant_salt	= config('app.merchant_salt');

        $email = $user->email;

        $payment_amount	= floatval($amount) * 100;

        $merchant_oid = $uniqueId;
        #
        ## Müşterinizin sitenizde kayıtlı veya form aracılığıyla aldığınız ad ve soyad bilgisi
        $user_name = $user->name ." ".$user->surname;
        #
        ## Müşterinizin sitenizde kayıtlı veya form aracılığıyla aldığınız adres bilgisi
        $user_address = $user->address;
        #
        ## Müşterinizin sitenizde kayıtlı veya form aracılığıyla aldığınız telefon bilgisi
        if(count($user->userContacts) > 0) {
            $user_phone = $user->userContacts[0]->name;
        } else {
            $user_phone =  $user->email;
        }

        #
        ## Başarılı ödeme sonrası müşterinizin yönlendirileceği sayfa
        ## !!! Bu sayfa siparişi onaylayacağınız sayfa değildir! Yalnızca müşterinizi bilgilendireceğiniz sayfadır!
        ## !!! Siparişi onaylayacağız sayfa "Bildirim URL" sayfasıdır (Bakınız: 2.ADIM Klasörü).
        $merchant_ok_url = config('app.ok_url');
        #
        ## Ödeme sürecinde beklenmedik bir hata oluşması durumunda müşterinizin yönlendirileceği sayfa
        ## !!! Bu sayfa siparişi iptal edeceğiniz sayfa değildir! Yalnızca müşterinizi bilgilendireceğiniz sayfadır!
        ## !!! Siparişi iptal edeceğiniz sayfa "Bildirim URL" sayfasıdır (Bakınız: 2.ADIM Klasörü).
        $merchant_fail_url = config('app.fail_url');
        #

        ## Müşterinin sepet/sipariş içeriği
        $user_basket = "";
        #
        /* ÖRNEK $user_basket oluşturma - Ürün adedine göre array'leri çoğaltabilirsiniz */
        $basket = [];
        foreach($orders as $order) {
            $basket[] = [$order['price']];
        }
        $user_basket = base64_encode(json_encode($basket));
        /**/
        ############################################################################################

        ## Kullanıcının IP adresi
        if( isset( $_SERVER["HTTP_CLIENT_IP"] ) ) {
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        } elseif( isset( $_SERVER["HTTP_X_FORWARDED_FOR"] ) ) {
            $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } else {
            $ip = $_SERVER["REMOTE_ADDR"];
        }

        ## !!! Eğer bu örnek kodu sunucuda değil local makinanızda çalıştırıyorsanız
        ## buraya dış ip adresinizi (https://www.whatismyip.com/) yazmalısınız. Aksi halde geçersiz paytr_token hatası alırsınız.
        $user_ip=$ip;
        ##

        ## İşlem zaman aşımı süresi - dakika cinsinden
        $timeout_limit = "30";

        ## Hata mesajlarının ekrana basılması için entegrasyon ve test sürecinde 1 olarak bırakın. Daha sonra 0 yapabilirsiniz.
        $debug_on = 1;

        ## Mağaza canlı modda iken test işlem yapmak için 1 olarak gönderilebilir.
        $test_mode = 0;

        $no_installment	= 0; // Taksit yapılmasını istemiyorsanız, sadece tek çekim sunacaksanız 1 yapın

        ## Sayfada görüntülenecek taksit adedini sınırlamak istiyorsanız uygun şekilde değiştirin.
        ## Sıfır (0) gönderilmesi durumunda yürürlükteki en fazla izin verilen taksit geçerli olur.
        $max_installment = 0;

        $currency = "TL";

        ####### Bu kısımda herhangi bir değişiklik yapmanıza gerek yoktur. #######
        $hash_str = $merchant_id .$user_ip .$merchant_oid .$email .$payment_amount .$user_basket.$no_installment.$max_installment.$currency.$test_mode;
        $paytr_token=base64_encode(hash_hmac('sha256',$hash_str.$merchant_salt,$merchant_key,true));
        $post_vals=array(
            'merchant_id'=>$merchant_id,
            'user_ip'=>$user_ip,
            'merchant_oid'=>$merchant_oid,
            'email'=>$email,
            'payment_amount'=>$payment_amount,
            'paytr_token'=>$paytr_token,
            'user_basket'=>$user_basket,
            'debug_on'=>$debug_on,
            'no_installment'=>$no_installment,
            'max_installment'=>$max_installment,
            'user_name'=>$user_name,
            'user_address'=>$user_address,
            'user_phone'=>$user_phone,
            'merchant_ok_url'=>$merchant_ok_url,
            'merchant_fail_url'=>$merchant_fail_url,
            'timeout_limit'=>$timeout_limit,
            'currency'=>$currency,
            'test_mode'=>$test_mode
        );

        $ch=curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://www.paytr.com/odeme/api/get-token");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1) ;
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_vals);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        $result = @curl_exec($ch);
        if(curl_errno($ch))
            die("PAYTR IFRAME connection error. err:".curl_error($ch));

        curl_close($ch);

        $result=json_decode($result,1);

        if($result['status']=='success')
            $token=$result['token'];
        else
            die("PAYTR IFRAME failed. reason:".$result['reason']);
        #########################################################################

        return $token;
    }

    public function pay(Request $request){
        $User = $request->get('user');
        $amount = $request->amount;
        if($amount>20){
            //return redirect('/site/user-panel#/balance');
            return response()->json(['success' => true, 'message' => '20 manatdan çox balans artırıla bilməz']);
            ///site/user-panel#/balance
            //redirect()->getUrlGenerator()->previous();
        }
        $transaction = new Transactions();
        $transaction->user_id = $User->id;
        $transaction->payment_type = 'millikart';
        $transaction->payment_note = 'Millikart ile balans artirma';
        $transaction->amount = $amount;
        $transaction->create_date =  date("Y-m-d H:i:s");
        $transaction->save();

        $reference = $transaction->id;

        $userData = $User->uniqid;
        $payment = new MillikartCoreController($amount, $reference, $userData);
        $response = $payment->getURL();
        return response()->json(['success' => true, 'url' => $response[0]]);
    }

    public function courier(Request $request)
    {
        $User = $request->get('user');
        $locations =
            [
                1=> ["name" => "Bakı","price" =>4],
                3=> ["name" => "Xırdalan","price" =>6],
                4=> ["name" => "Bakı kəndləri","price" =>8],
            ];

        if($request->city=='' || $request->phone=='' ||  empty($request->products)){
            return response()->json(["success" => false, "message" => "Ulduzlanmış xanaları doldurun", "code" => 500]);
        }
        $data = new Courier();
        $data->user_id = $User->id;
        $data->is_paid = 0;
        $data->delivery_type = 1;
        $data->has_courier = 0;
        $data->phone = $request->phone;
        if(isset($locations[$request->city])){
            $data->city = $locations[$request->city]["name"];
            $data->price = $locations[$request->city]["price"];
        }else{
            $data->city = 'Bakı '.$request->city;
            $data->price = 5;
        }

        $data->district = $request->district;
        $data->village = $request->village;
        $data->street = $request->street;
        $data->home = $request->home;
        $data->description = $request->description;
        $data->save();
        foreach($request->products as $value){
            $insertData = Invoice::where("id",$value)->where("user_id", $User->id)->first();
            //var_dump($insertData->id);
//            if(is_null($insertData->courier_id)) {
                if($insertData!=null){
                    $insertData->courier_id = $data->id;
                    $insertData->save();
                }
//      }
        }
        return response()->json(["data" => "ok", "code" => 200]);

    }



    public function confirmation(Request $request)
    {
        $lang = $request->header("LANG");

        LaravelLocalization::setLocale($lang);

        $user = $request->get('user');
        $user_id = $user->id;
        $error_message = '';
        $status = 500;
        if(isset($_POST["phone"]) and !empty($_POST["phone"]) and $user->is_blocked==0 and $user->activated==0){

            $activations = DB::table('user_activations')->select(DB::raw("count(id) as sms_count"),DB::raw("sum(count_try) as try_count"))->where("user_id",$user_id)->where("status",0)->first();

            if(intval($activations->sms_count)<=6 and intval($activations->try_count)<=15){
                $contact = UserContact::where("user_id",$user->id)->first();

                $new_phone = str_replace([' ', ')','('], '',$_POST["phone"]);
                $issetPhone = DB::table("user_contacts as uc")->select("u.id")->leftJoin("users as u",'u.id','uc.user_id')->where("uc.name",$new_phone)->where("uc.user_id",'!=',$user_id)->where("u.activated",1)->first();
                if($issetPhone!=null){
                    $error_message =  Lang::get('panel-errors.isset_number_error');
                }else{
                    $contact->name = $new_phone;
                    $contact->save();
                    $phone = $new_phone;
                    $step = 2;
                    $code = rand(1000,9999);
                    $user->activation_code = $code;
                    $user->save();

                    DB::table("user_activations")->insert(
                        ['user_id' => $user->id, 'code' => $code,'count_try' =>0,'status' => 0,'phone' => $phone,'created_at' => date("Y-m-d H:i:s")]
                    );

                    $text = "Sizin tesdiq kodunuz: ".$code;
                    $data = (object) ['text' => $text];
                    sms($data, str_replace(['+', ' ', ')','('], '',$phone));
                    $status = 200;
                    $error_message = Lang::get('panel-errors.sending');
                }


            }else{
                $error_message = Lang::get('panel-errors.code_limit_error');
                $user->is_blocked = 1;
                $user->save();

                DB::table("blocked_users")->insert(
                    ['user_id' => $user->id,'reason' => 'Nömrə təsdiq zamanı limiti keçdiyindən blok olunub','status' => 1 ,'created_at' => date("Y-m-d H:i:s")]
                );

            }
        }else{
            if($user->activated==1){
                $error_message = 'İstifadəçi aktivdir';
            }elseif($user->is_blocked==1){
                $error_message = 'İstifadəçi blok olunub';
            }else{
                $error_message = 'kod yoxdur';
            }
        }
        return response()->json(["code" => $status, "message" => $error_message]);
    }

    public function confirmationCode(Request $request)
    {
        $lang = $request->header("LANG");

        LaravelLocalization::setLocale($lang);

        $user = $request->get('user');
        $user_id = $user->id;
        $error_message = '';
        $status = 500;

        if(isset($_POST["code"]) and !empty($_POST["code"]) and $user->is_blocked==0 and $user->activated==0) {
            $activations = DB::table('user_activations')->select(DB::raw("count(id) as sms_count"),DB::raw("sum(count_try) as try_count"))->where("user_id",$user_id)->where("status",0)->first();
            $contact = UserContact::where("user_id",$user->id)->first();
            $phone = $contact->name;

            if ($activations->sms_count <= 6 and $activations->try_count <= 15) {

                $post_code = htmlspecialchars($_POST["code"]);
                $step = 2;

                $activation = DB::table("user_activations")->where("user_id", $user_id)->where("status", 0)->where("code", $user->activation_code)->where("phone", $phone)->orderBy("id", "DESC")->first();
                if ($user->activation_code == $post_code and $activation != null) {
                    $user->activated = 1;
                    $user->save();

                    $text = "Sizin qeydiyyatiniz tesdiqlendi.Musteri kodunuz:" . $user->uniqid;
                    $data = (object)['text' => $text];
                    sms($data, str_replace(['+', ' ', ')', '('], '', $phone));

                    $new_count = $activation->count_try + 1;
                    $actiovation_update = DB::table("user_activations")->where("id", $activation->id)->update(["count_try" => $new_count, 'status' => 1]);
                    $error_message = "Sizin qeydiyyatiniz tesdiqlendi.Musteri kodunuz:" . $user->uniqid;
                    $status = 200;

                } elseif ($activation != null) {
                    $error_message = Lang::get('panel-errors.code_error');
                    $new_count = $activation->count_try + 1;
                    $actiovation_update = DB::table("user_activations")->where("id", $activation->id)->update(["count_try" => $new_count]);

                } else {

                    $error_message = Lang::get("panel-errors.code_error_restart_page");
                }
            }
            else{
                $error_message = Lang::get('panel-errors.code_limit_error');
                $user->is_blocked = 1;
                $user->save();

                DB::table("blocked_users")->insert(
                    ['user_id' => $user->id,'reason' => 'Nömrə təsdiq zamanı limiti keçdiyindən blok olunub','status' => 1 ,'created_at' => date("Y-m-d H:i:s")]
                );
            }
        }else{
            if($user->activated==1){
                $error_message = 'İstifadəçi aktivdir';
            }elseif($user->is_blocked==1){
                $error_message = 'İstifadəçi blok olunub';
            }else{
                $error_message = 'kod yoxdur';
            }
        }

        return response()->json(["code" => $status, "message" => $error_message]);



    }
    

    public function storeProfile(Request $request)
    {
        $user = $request->get('user');
        $userId = $user->id;

        $validator =  Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'birthdate' => 'required',
        ]);
        if ($validator->fails()) {
            return ['success'=> false,'message'=>$validator->errors()];
        }


        $user->name = $request->name;
        $user->surname = $request->surname;
        $user->email = $request->email;
        $user->birthdate = date("d.m.Y",strtotime($request->birthdate));
        $user->region_id = $request->region_id;

        $user->save();
        $userContacts = UserContact::where('user_id', '=', $userId)->first();
        if($userContacts) {
            $userContacts->name = $request->phone;
            $userContacts->save();
        } else {
            $userContacts = new UserContact();
            $userContacts->user_id = $userId;
            $userContacts->name = $request->phone;
            $userContacts->save();
        }
        return response()->json((object)['code' => 200, 'message' => 'ok']);

    }

    public function storePassport(Request $request)
    {

        $user = $request->get('user');
        $userId = $user->id;



        $validator =  Validator::make($request->all(), [
            'pin' => 'required',
            'gender' => 'required',
            'serialNumber' => 'required',
            'address' => 'required',
        ]);
        if ($validator->fails()) {
            return ['success'=> false,'message'=>$validator->errors()];
        }

        $user->pin = $request->pin;
        $user->serial_number = $request->serialNumber;
        $user->gender = $request->gender;
        $user->nationality = $request->nationality;
        $user->address = $request->address;
        $user->save();

        return response()->json((object)['code' => 200, 'message' => 'ok']);
    }

    public function storePassword(Request $request)
    {
        $validator =  Validator::make($request->all(), [
            'currentPassword' => 'required',
            'password' => 'required',
            'cpassword' => 'required',
        ]);
        if ($validator->fails()) {
            return ['success'=> false,'message'=>$validator->errors()];
        }

        $user = $request->get('user');
        $userId = $user->id;

        if ( strlen($request->password) >= 8 && $request->password === $request->cpassword) {

            if(Hash::check($request->currentPassword, $user->password)) {
                $user->password = Hash::make($request->password);
            }
        }
        $user->save();
        return response()->json((object)['code' => 200, 'message' => 'ok']);

    }



    public function payInvoice(Request $request)
    {
        $user = $request->get('user');
        $userId = $user->id;
        $invoices = $request->invoices;
        $price = Input::get('price', 0);
        $currency = Input::get('currency', 'azn');
        $user=User::where('id',$userId)->first();
        if($user->balance>=$price){
            for ($i = 0; $i < count($invoices); $i++) {
                $payment = new InvoicePayment();
                $payment->invoice_id = $invoices[$i];
                $payment->price = $price;
                $payment->currency = $currency;
                $payment->save();

                if($invoices[$i]>0){
                    $invoice = Invoice::where('id',$invoices[$i])->first();
                    $invoice->is_paid=1;
                    $invoice->save();
                }
            }
            $old_balance = $user->balance;
            $user->balance = $old_balance - $price;
            $user->save();



            $message = 'Balansdan çatdırılma haqqı ödənişi';

            LogBalance::create([
                'user_id' => $user->id,
                'old_balance' => $old_balance,
                'new_balance' => $user->balance,
                'money' => $price,
                'message' => $message,
                'type' => 'azn'
            ]);

            return response()->json([
                'data'=>'ok'
            ]);
        }else{
            return response()->json(['data' => 'Balansınız çatmır', 'code' => 1601]);
        }


    }

    public function cancelOnlyCourier(Request $request) {
        $user = $request->get('user');
        $userId = $user->id;

        if($request->courierId) {
            $courierType = Courier::select('delivery_type', 'is_paid','price' )
                ->where('id', '=', $request->courierId)
                ->where('user_id', '=', $userId)
                ->get()[0];
            if(!$courierType->is_paid and $courierType->has_courier!=1) {
                $invoices = Invoice::where("courier_id",$request->courierId)->where("status_id",4)->get();
                foreach ($invoices as $invoice) {
                    $invoice->courier_id = null;
                    $invoice->save();
                }
                $courierType->delete();

                return response()->json(['data' => 'Kuryer sifarişi ləğv edildi', 'code' => 200]);


            }else{
                return response()->json(['data' => 'Bu kuryer sifarişini imtina ede bilməzsiz', 'code' => 501]);
            }
        }

    }

    public function payOnlyCourier(Request $request) {
        $User = $request->get('user');
        $userId = $User->id;
        $userBalance = $User->balance;

        $price = 0;

        if($request->courierId) {
            $courierType = Courier::select('delivery_type', 'is_paid','price' )
                ->where('id', '=', $request->courierId)
                ->where('user_id', '=', $userId)
                ->get()[0];
            $courierCash =  $courierType->price; //$courierType->delivery_type == 1? Courier::COURIER_PRICE_NORMAL : Courier::COURIER_PRICE_FAST;
            if(!$courierType->is_paid) {
                $price += $courierCash;
            }
        }
        if($userBalance >= $price) {
            $newUserBalance = $userBalance - $price;

            /* $changeBalance = User::find($userId);
             $changeBalance->balance = $newUserBalance;
             $changeBalance->save();*/

            if (!$courierType->is_paid) {

                $changeCourier = Courier::find($request->courierId);
                $changeCourier->is_paid = 1;
                $changeCourier->save();


                $logPayment = new LogPaymentDeliveryInvoices();
                $logPayment->user_id = $userId;
                $logPayment->user_balance = $newUserBalance;
                $logPayment->delivery_cash = $price;
                $logPayment->courier_id = $request->courierId;
                $logPayment->save();


                $amount = $price - 2*$price;
                $user = User::find($userId);
                $balance = $user->balance;
                $user->balance = $balance + $amount;
                $user->save();

                $message = 'Balansdan Kuryer ödənişi';

                LogBalance::create([
                    'user_id' => $user->id,
                    'old_balance' => $balance,
                    'new_balance' => $user->balance,
                    'money' => $amount,
                    'message' => $message,
                    'type' => 'azn'
                ]);

            }
            return response()->json(['data' => 'Ödənildi', 'code' => 200]);
        } else {
            return response()->json(['data' => 'Balansınız çatmır', 'code' => 1601]);
        }
    }
}
