<?php

namespace App\Http\Controllers\Site;

use App\Events\OrderAccepted;
use App\Http\Controllers\Controller;
use App\Libraries\Parser\Parser;
use App\ModelLogs\LogBalance;
use App\ModelProduct\Extras;
use App\ModelProduct\Product;
use App\ModelUser\User;
use App\RelUserProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\UserPromoCodes;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    private $path = 'site.orders.link.';

    /**
     * Insert Page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function insert()
    {
        $message = 'test message';
        return view($this->path.'index',['message'=>$message]);
    }


    public function parse(Request $request)
    {
        $product = new Parser($request->url, $request->domain);
        $response = $product->parse();

        if ($response) {
            $response->link = $request->url;
            return response()->json($response, 200);
        } else {
            return response()->json('hell', 200);
        }
    }

    public function store(Request $request)
    {
        setcookie("ui", Auth::user()->id, time() + (86400 * 30), "/"); // 86400 = 1 day
        if ($request->session()->exists('uid')!=true) {
            $request->session()->put('uid', Auth::user()->id);
        }
        $uniqueId = time().Auth::user()->id;

        $status = 1;
        $rowStatus = DB::table("settings")->first();
        if($rowStatus!=null){
            $status = $rowStatus->order_status;
        }
        if($status==1) {
                if (!empty($request->data) && count($request->data) > 0) {
                $relUserProduct = new RelUserProduct();
                $relUserProduct->user_id = Auth::user()->id;
                $relUserProduct->status_id = getStatusByLabel('waiting', 'transaction');
                $relUserProduct->transaction_id = $uniqueId;
                $relUserProduct->is_paid = '0';
                $relUserProduct->price = $request->ytl;
                $relUserProduct->delivery_type = $request->deliveryType;
                $relUserProduct->save();
                $promo_code = $request->promo_code;
                foreach($request->data as $index => $order) {


                    $order = (object)$order;
                    $extra = new Extras();
                    $product = new Product();
                    /**
                     * Feed Extra
                     */
                    $extra->country_id = $request->country; // TODO: make it dynamic
                    $extra->link = $order->link;
                    $extra->brand = $order->brand;
                    $extra->color = $order->brand;
                    $extra->size = $order->size;
                    $extra->cargo_price = $order->cargo;
                    $extra->save();

                    $product->product_type_name = $order->type;
                    $product->rel_user_product_id = $relUserProduct->id;
                    if(Auth::user()->corporate==1){
                        $product->corporate = 1;
                        $product->client_id = $request->get("client_id",0);
                    }else{
                        $product->client_id = 0;
                    }
                    $product->extras_id = $extra->id;
                    $product->price = $order->price*$order->quantity;
                    $product->user_id = Auth::user()->id;
                    $product->is_premium = Auth::user()->is_premium;
                    $product->quantity = $order->quantity;
                    $product->region_id = $request->region_id;
                    $product->shop_name = $order->shop;
                    $product->description = !empty($order->desc) ? $order->desc: '';
                    $product->status_id = ($request->has('basket'))? 3 : 1;//getStatusByLabel('waiting', 'transaction');
                    $product->promo_code = $promo_code;
                    $product->save();
                }
                $pay = new PayTrController();
                $token = $pay->payByTr($request->ytl, $request->data, $uniqueId);
                if($promo_code){
                    UserPromoCodes::create([
                        "user_id" => auth()->id(),
                        "campaign_id" => 1,
                        "promo_code" => $promo_code,
                        "invoice_id" => 0,
                        "status" => 0

                    ]);
                }
                if(Auth()->guard('web')->user()->region_id!=$request->region_id){
                    $user = User::find(Auth()->guard('web')->user()->id);
                    if($user!=null){
                        $user->region_id = $request->region_id;
                        $user->save();
                    }
                }
                if($request->has('basket')) return response()->json(['success' => true, 'message' => 'Added to basket']);

                return view('paymentTr', compact('token'));
            } else {
                return response()->json('Məlumatlar tam deyildir', 500);
            }
        }else{
            return response()->json('Sifariş et xidməti aktiv deyil', 500);
        }

    }

    public function storeBalance(Request $request)
    {
        setcookie("ui", Auth::user()->id, time() + (86400 * 30), "/"); // 86400 = 1 day
        $request->session()->put('uid', Auth::user()->id);
        $uniqueId = time().Auth::user()->id;
        $status = 1;
        $rowStatus = DB::table("settings")->first();
        if($rowStatus!=null){
            $status = $rowStatus->order_status;
        }
        $user = User::find(Auth::user()->id);

        if($status==1) {

            if ($user != null) {

                $balance_try = $user->balance_try;
                $try = $request->ytl;
                if ($balance_try >= $try and $try > 1) {
                    if (!empty($request->data) && count($request->data) > 0) {
                        $relUserProduct = new RelUserProduct();
                        $relUserProduct->user_id = Auth::user()->id;
                        $relUserProduct->status_id = getStatusByLabel('waiting', 'transaction');
                        $relUserProduct->transaction_id = $uniqueId;
                        $relUserProduct->is_paid = 1;
                        $relUserProduct->price = $try;
                        $relUserProduct->delivery_type = $request->deliveryType;
                        $relUserProduct->response_payment = 'Payed with balance';
                        $relUserProduct->save();
                        $promo_code = $request->promo_code;
                        foreach ($request->data as $index => $order) {


                            $order = (object)$order;
                            $extra = new Extras();
                            $product = new Product();
                            /**
                             * Feed Extra
                             */
                            $extra->country_id = $request->country; // TODO: make it dynamic
                            $extra->link = $order->link;
                            $extra->brand = $order->brand;
                            $extra->color = $order->brand;
                            $extra->size = $order->size;
                            $extra->cargo_price = $order->cargo;
                            $extra->save();

                            $product->product_type_name = $order->type;
                            $product->rel_user_product_id = $relUserProduct->id;
                            $product->extras_id = $extra->id;
                            $product->price = $order->price * $order->quantity;
                            $product->user_id = Auth::user()->id;
                            $product->is_premium = Auth::user()->is_premium;
                            if(Auth::user()->corporate==1){
                                $product->corporate = 1;
                                $product->client_id = $request->get("client_id",0);
                            }else{
                                $product->client_id = 0;
                            }
                            $product->quantity = $order->quantity;
                            $product->shop_name = $order->shop;
                            $product->region_id = $request->region_id;
                            $product->description = !empty($order->desc) ? $order->desc : '';
                            $product->status_id = 1;//getStatusByLabel('waiting', 'transaction');
                            $product->promo_code = $promo_code;
                            $product->not_basket = 1;
                            $product->is_paid = 1;
                            $product->save();
                        }
                        /*$pay = new PayTrController();
                        $token = $pay->payByTr($request->ytl, $request->data, $uniqueId);*/
                        if ($promo_code) {
                            UserPromoCodes::create([
                                "user_id" => auth()->id(),
                                "campaign_id" => 1,
                                "promo_code" => $promo_code,
                                "invoice_id" => 0,
                                "status" => 0

                            ]);
                        }

                        $user->balance_try = $balance_try - $try;
                        $user->save();
                        $message = 'Balansdan sifariş';
                        $amount = $try - 2 * $try;
                        $user->save();
                        LogBalance::create([
                            'user_id' => $user->id,
                            'old_balance' => $balance_try,
                            'new_balance' => $user->balance_try,
                            'money' => $amount,
                            'message' => $message,
                            'type' => 'try',
                        ]);

                        if (Auth()->guard('web')->user()->region_id != $request->region_id) {
                            $user = User::find(Auth()->guard('web')->user()->id);
                            if ($user != null) {
                                $user->region_id = $request->region_id;
                                $user->save();
                            }
                        }
                        return response()->json(["data" => 'Uğurlu ödəniş', "status" => 200]);
                    } else {
                        return response()->json(["data" => 'Məlumatlar tam deyildir', "status" => 500]);
                    }
                } else {
                    if ($balance_try < $try) {
                        return response()->json(["data" => 'TL balansınızda yetərli məbləğ yoxdur. Balansınızı artırıb ödəniş edə bilərsiniz.', "status" => 500]);
                    } else {
                        return response()->json(["data" => 'Məbləğ düzgün deyil', "status" => 500]);
                    }
                }
            } else {
                return response()->json(["data" => 'İstifadəçi tapılmadı', "status" => 500]);
            }
        }else{
            return response()->json(["data" => 'Sifariş et xidməti aktiv deyil', "status" => 500]);
        }

    }

    public function getData(Request $request)
    {
        $id = $request->id;
        $rel = RelUserProduct::where("id",$id)->where("is_paid",0)->first();
        $prices["code"] = 500;
        $prices["try"] = 0;
        $prices["azn"] = 0;
        if($rel!=null){
            $prices["try"] = floatval($rel->price);
            $currency = DB::table('currencies')->where("name","try-azn")->first();
            if($currency!=null){
                $prices["azn"] = $prices["try"]*$currency->val;
            }
            $prices["code"] = 200;
        }
        return response()->json($prices, 200);
    }

    public function updateBasket(Request $request,Product $product){

        if($product->user_id === Auth::id() && $product->is_ordered === 0){

            $product->price = $request->price;
            $product->quantity = $request->quantity;
            $product->shop_name = $request->shop_name;
            $product->product_type_name = $request->product_type_name;
            $product->price = $request->price;
            $product->description = $request->description;

            $extra = Extras::find($product->extras_id);
            $extra->link = $request->extras['link'];
            $extra->size = $request->extras['size'];
            $extra->color = $request->extras['color'];
            $extra->brand = $request->extras['brand'];
            $extra->cargo_price = $request->extras['cargo_price'];

            $product->save();
            $extra->save();
            return response()->json('Sifariş dəyişildi', 200);

        }

    }




    public function paymentNotify (Request $request) {
        ## 2. ADIM için örnek kodlar ##

        ## ÖNEMLİ UYARILAR ##
        ## 1) Bu sayfaya oturum (SESSION) ile veri taşıyamazsınız. Çünkü bu sayfa müşterilerin yönlendirildiği bir sayfa değildir.
        ## 2) Entegrasyonun 1. ADIM'ında gönderdiğniz merchant_oid değeri bu sayfaya POST ile gelir. Bu değeri kullanarak
        ## veri tabanınızdan ilgili siparişi tespit edip onaylamalı veya iptal etmelisiniz.
        ## 3) Aynı sipariş için birden fazla bildirim ulaşabilir (Ağ bağlantı sorunları vb. nedeniyle). Bu nedenle öncelikle
        ## siparişin durumunu veri tabanınızdan kontrol edin, eğer onaylandıysa tekrar işlem yapmayın. Örneği aşağıda bulunmaktadır.

        $post = $_POST;

        ####################### DÜZENLEMESİ ZORUNLU ALANLAR #######################
        #
        ## API Entegrasyon Bilgileri - Mağaza paneline giriş yaparak BİLGİ sayfasından alabilirsiniz.
        $merchant_key 	= 'rtP8g1BSCZjbwn72';
        $merchant_salt	= 'Dej6k41jELPnhZkQ';
        ###########################################################################

        ####### Bu kısımda herhangi bir değişiklik yapmanıza gerek yoktur. #######
        #
        ## POST değerleri ile hash oluştur.
        $hash = base64_encode( hash_hmac('sha256', $post['merchant_oid'].$merchant_salt.$post['status'].$post['total_amount'], $merchant_key, true) );
        #
        ## Oluşturulan hash'i, paytr'dan gelen post içindeki hash ile karşılaştır (isteğin paytr'dan geldiğine ve değişmediğine emin olmak için)
        ## Bu işlemi yapmazsanız maddi zarara uğramanız olasıdır.
        if( $hash != $post['hash'] )
            die('PAYTR notification failed: bad hash');
        ###########################################################################

        ## BURADA YAPILMASI GEREKENLER
        ## 1) Siparişin durumunu $post['merchant_oid'] değerini kullanarak veri tabanınızdan sorgulayın.
        ## 2) Eğer sipariş zaten daha önceden onaylandıysa veya iptal edildiyse  echo "OK"; exit; yaparak sonlandırın.

        /* Sipariş durum sorgulama örnek
            $durum = SQL
           if($durum == "onay" || $durum == "iptal"){
                echo "OK";
                exit;
            }
         */


        if( $post['status'] == 'success' ) { ## Ödeme Onaylandı

            $data = RelUserProduct::where('transaction_id', '=', $post['merchant_oid'])->first();
            $data->is_paid = '1';
            $data->save();

            $products = Product::where('rel_user_product_id', '=', $data->id)->get();
            foreach ($products as $item){
                $item->is_paid = 1;
                $item->status_id = 1;
                $item->save();
            }

            //event(new OrderAccepted('test', true));

            ## BURADA YAPILMASI GEREKENLER
            ## 1) Siparişi onaylayın.
            ## 2) Eğer müşterinize mesaj / SMS / e-posta gibi bilgilendirme yapacaksanız bu aşamada yapmalısınız.
            ## 3) 1. ADIM'da gönderilen payment_amount sipariş tutarı taksitli alışveriş yapılması durumunda
            ## değişebilir. Güncel tutarı $post['total_amount'] değerinden alarak muhasebe işlemlerinizde kullanabilirsiniz.

        } else { ## Ödemeye Onay Verilmedi
            //$data = RelUserProduct::where('transaction_id', '=', $post['merchant_oid'])->first();
            //$data->delete();
            ## BURADA YAPILMASI GEREKENLER
            ## 1) Siparişi iptal edin.
            ## 2) Eğer ödemenin onaylanmama sebebini kayıt edecekseniz aşağıdaki değerleri kullanabilirsiniz.
            ## $post['failed_reason_code'] - başarısız hata kodu
            ## $post['failed_reason_msg'] - başarısız hata mesajı
            $data = RelUserProduct::where('transaction_id', '=', $post['merchant_oid'])->first();
            $data->is_paid = '2';
            $data->save();

            /*$products = Product::where('rel_user_product_id', '=', $data->id)->get();
            foreach ($products as $item){
                $item->is_paid = 2;
                $item->save();
            }*/

            //event(new OrderAccepted('test', true));
        }

        ## Bildirimin alındığını PayTR sistemine bildir.
        echo "OK";
        exit;
    }

    public function paymentSuccess (Request $request) {
        $message = 'Ödəniş tamamlanmışdır!';
        return view('front.paymentSuccess', compact('message'));
    }


    public function paymentFail (Request $request) {
        $message = session('failed_reason_msg');
        session(['failed_reason_msg' => '']);
        return view('front.paymentFail', compact('message'));
    }

    public function checkPromo(Request $request){
        $promo_code = $request->promo_code;
        if(stripos($promo_code, '_') !== false){
            $campaign_name = explode('_',$promo_code)[0].'?';
            $hashed_code = explode('_',$promo_code)[1];
            $user = ownUnHash($hashed_code);

            $User = User::find((int) $user);
            if(!$User) return response()->json(false, 200);
            $campaign = DB::table('campaigns')->where('promo_code',$campaign_name)
                ->where('begin_date', '<=',date('Y-m-d'))
                ->where('end_date', '>=',date('Y-m-d'))
                ->first();
            if($campaign){
                if($campaign->max_user_id !== null && $campaign->max_user_id >= $user){
                    if(UserPromoCodes::where('promo_code',$promo_code)->where('status',1)->first()){
                        return response()->json(false, 200);
                    }
                    return response()->json(true, 200);
                }
                else{
                    return response()->json(false, 200);
                }

            } else{
                return response()->json(false, 200);
            }
        }
        else{
            return response()->json(false, 200);
        }

    }
}
