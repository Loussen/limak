<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;

use App\RelUserProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

class PayMController extends Controller
{
    /**
     * Insert Page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */

    public function pay2(Request $request)
    {

        $operationId = Input::get('operationId');

        $rel = RelUserProduct::with('products')->where("id",$operationId)->first();
        $secret = 'E3FBEYK17w6n982i5020pgk2c';
        $cardNumber = Input::get('cardNumber');
        $installmentsNumber = 1;
        $expiryMonth = Input::get('expiryMonth');
        $expiryYear = Input::get('expiryYear');
        $cvv = Input::get('cvv');
        $owner = Input::get('owner');

        $billingFirstname = Auth()->user()->name;
        $billingLastname = Auth()->user()->surname;
        $billingEmail = Auth()->user()->email;
        $billingPhone = '';
        $billingCountrycode = 'az';
        $billingAddressline1 = Auth()->user()->address;
        $billingCity = 'Baku';
        $deliveryFirstname = Auth()->user()->name;
        $deliveryLastname = Auth()->user()->surname;
        $deliveryPhone = '';
        $deliveryAddressline1 = Auth()->user()->address;
        $deliveryCity = 'Baku';


        $clientIp = $_SERVER["REMOTE_ADDR"];
        $productName = '';
        $productSku = '';
        $productQuantity = '';
        $productPrice = '';


        $post_vals=array(
            'secret' => $secret,
            'operationId'=>$operationId,
            'number'=>$cardNumber,
            'installmentsNumber' => $installmentsNumber,
            'expiryMonth' => $expiryMonth,
            'expiryYear' => $expiryYear,
            'cvv' => $cvv,
            'owner' => $owner,

            'billingFirstname' => $billingFirstname,
            'billingLastname' => $billingLastname,
            'billingEmail' => $billingEmail,
            'billingPhone' => $billingPhone,
            'billingCountrycode' => $billingCountrycode,
            'billingAddressline1' => $billingAddressline1,
            'billingCity' => $billingCity,

            'deliveryFirstname' => $deliveryFirstname,
            'deliveryLastname' => $deliveryLastname,
            'deliveryPhone' => $deliveryPhone,
            'deliveryAddressline1' => $deliveryAddressline1,
            'deliveryCity' => $deliveryCity,


            'clientIp' => $clientIp,
            'productName' => $productName,
            'productSku' => $productSku,
            'productQuantity' => $productQuantity,
            'productPrice' => $productPrice,
        );



        $result = '';
        $ch=curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://web.paym.es/gapi.php?operation=authorize");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1) ;
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_vals);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        $result = @curl_exec($ch);
        if(curl_errno($ch))
            die("PAYMES connection error. err:".curl_error($ch));

        curl_close($ch);

        $result=json_decode($result,1);
        return response()->json($result, 200);

        if($result['status']=='success')
            $token=$result['token'];
        else
            die("PAYM failed. reason:".$result['reason']);


        return $token;
    }

    public function pay(Request $request)
    {
        $result = '';
        $response = [];
        $operationId = Input::get('operationId');

        $rel = RelUserProduct::with('products')->where("id", $operationId)->where("is_paid", 0)->first();
        if ($rel != "") {
            $secret = 'E3FBEYK17w6n982i5020pgk2c';
            $cardNumber = Input::get('cardNumber');
            $installmentsNumber = 1;
            $expiryMonth = Input::get('expiryMonth');
            $expiryYear = Input::get('expiryYear');
            $cvv = Input::get('cvv');
            $owner =  Input::get('owner');

            $billingFirstname = Auth()->user()->name;
            $billingLastname = Auth()->user()->surname;
            $billingEmail = Auth()->user()->email;
            $billingPhone = '994555555555';
            $billingCountrycode = 'az';
            $billingAddressline1 = Auth()->user()->address;
            $billingCity = 'Baku';
            $deliveryFirstname = Auth()->user()->name;
            $deliveryLastname = Auth()->user()->surname;
            $deliveryPhone = '';
            $deliveryAddressline1 = Auth()->user()->address;
            $deliveryCity = 'Baku';

            $clientIp = $_SERVER["REMOTE_ADDR"];
            if($rel->products[0]->product_type_name!=null){
                $productName = $rel->products[0]->product_type_name;
            }else{
                $productName = $rel->products[0]->id;
            }
            $productSku = $rel->products[0]->id;
            $productQuantity = $rel->products[0]->quantity;
            $productPrice = $rel->price;


            $post_vals = array(
                'secret' => $secret,
                'operationId' => $operationId,
                'number' => $cardNumber,
                'installmentsNumber' => $installmentsNumber,
                'expiryMonth' => $expiryMonth,
                'expiryYear' => $expiryYear,
                'cvv' => $cvv,
                'owner' => $owner,

                'billingFirstname' => $billingFirstname,
                'billingLastname' => $billingLastname,
                'billingEmail' => $billingEmail,
                'billingPhone' => $billingPhone,
                'billingCountrycode' => $billingCountrycode,
                'billingAddressline1' => $billingAddressline1,
                'billingCity' => $billingCity,

                'deliveryFirstname' => $deliveryFirstname,
                'deliveryLastname' => $deliveryLastname,
                'deliveryPhone' => $deliveryPhone,
                'deliveryAddressline1' => str_replace(' ', '_', $deliveryAddressline1),
                'deliveryCity' => $deliveryCity,


                'clientIp' => $clientIp,
                'productName' => $productName,
                'productSku' => $productSku,
                'productQuantity' => $productQuantity,
                'productPrice' => $productPrice,
                'currency' => 'TRY'
            );

            $new_arr = [];

            foreach ($post_vals as $key => $val) {
                $new_arr[] = $key . '=' . $val;
            }

            $post_vals = implode('&', $new_arr);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://web.paym.es/gapi.php?operation=authorize");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_vals);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 20);
            $result = @curl_exec($ch);
            $response["result"] = $result;
            if (curl_errno($ch)){
                $response = ["code" => 500,"message" => "PAYMES connection error. err:" . curl_error($ch)];
            }


            curl_close($ch);

            $result = json_decode($result, 1);
            //var_dump($result);
            if ($result['code'] == 200) {
                $reference = $result["payuPaymentReference"];
                if ($result["paymentResult"]["type"] == 'redirect') {
                    $rel->reference = $reference;
                    $rel->response_payment = json_encode($result);
                    $rel->save();
                    $response = ["code" => "200","message" => "success","reference" => $reference,"url" =>$result["paymentResult"]["url"]];
                } else {
                    echo "location getmedi<br />";
                }
            } else
                $response = ["code" => 500,"message" => $result];

        } else {
            $response = ["code" => 404,"message" => "Not Found"];
        }


        return response()->json($response);

    }

}
