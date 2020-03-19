<?php

namespace App\Http\Controllers\Cron;

use App\Currencies;
use App\Currency;
use App\ForeignDepo;
use App\Http\Controllers\Controller;
use App\Invoice;
use App\InvoiceDates;
use App\ModelCountry\Country;
use App\Packages;
use App\Packages_1704;
use App\UserPromoCodes;
use App\Helpers;
use Illuminate\Support\Facades\DB;


class UsaController extends Controller
{
    public function getPackages()
    {
        $key = 'TWpBPS5YY2lma0pNc1lkQzBwc240OTNwczAxU00wUkY3RG8zY3p4cE8xcCtpckZJPTo=';
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.ypn.io/v1/fulfillments/parcels?operation=manifest",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Basic TWpBPS5YY2lma0pNc1lkQzBwc240OTNwczAxU00wUkY3RG8zY3p4cE8xcCtpckZJPTo=",
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $data = json_decode($response,true);
            foreach ($data as $item){

                $issetDepo = DB::table("foreign_depo")->select("id")->where("parcel_id",$item["id"])->first();
                if($issetDepo==null){
                    $setDepo = new ForeignDepo();
                    $setDepo->parcel_id = $item["id"];
                    $setDepo->user_id = '';
                    $setDepo->country_id = 2; // USA
                    $setDepo->tracking = $item["reference"];
                    $setDepo->arrived_date = date("Y-m-d H:i:s",strtotime($item["arrivedAt"]));
                    $setDepo->status_id = 0;
                    $setDepo->suite_number = $item["suiteNumber"];
                    $setDepo->client_name = $item["consignee"]["name"];
                    $setDepo->client_company = $item["consignee"]["company"];
                    $setDepo->client_email = $item["consignee"]["email"];
                    $setDepo->client_phone = $item["consignee"]["phone"];
                    $setDepo->client_address = $item["consignee"]["address1"];
                    $setDepo->client_country = $item["consignee"]["country"];
                    $setDepo->client_postalcode = $item["consignee"]["postalCode"];
                    $setDepo->description = $item["contents"][0]["description"];
                    $setDepo->currency = $item["contents"][0]["currency"];
                    $setDepo->price = $item["contents"][0]["price"];
                    $setDepo->weight = $item["weight"];
                    $setDepo->length = $item["length"];
                    $setDepo->width = $item["width"];
                    $setDepo->height = $item["height"];
                    $setDepo->quantity = $item["contents"][0]["quantity"];
                    $setDepo->unit = $item["contents"][0]["unit"];
                    $setDepo->store = $item["sender"];
                    $setDepo->save();
                }

            }
        }
    }
}