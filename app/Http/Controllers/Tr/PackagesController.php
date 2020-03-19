<?php

namespace App\Http\Controllers\Tr;

use App\Currency;
use App\ForeignDepo;
use App\ModelCountry\Country;
use App\Invoice;
use App\InvoiceDates;
use App\Invoiceless;
use App\Libraries\Upload\Uploader;
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
use \Milon\Barcode\DNS1D;

class PackagesController extends BaseController
{
    public function __construct()
    {
        $data = Country::where('prefix','usa')->first();
        $this->country=$data->id;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function packages(Request $request)
    {
        $admin_id = Auth::guard('usa')->user()->id;

        if($admin_id==54){
            return 'Your are not access'; exit;
        }

        \Artisan::call('cache:clear');
        \Artisan::call('view:clear');

        $data = DB::table('foreign_depo')
            ->where("status_id",0)
            ->orderBy("parcel_id","DESC")
            ->get();
        $status = 'packages';

        return view('usa.invoice.packages',  compact('data','status' ));
    }


    public function getPackage($id)
    {
        $data = DB::table("foreign_depo as f")->where("f.parcel_id",$id)->first();
        $user = (object)["address" => "","phone" => "","name" => "","surname" => "","email" => ""];
        if($data!=null){
            $user_id = $data->user_id;
            if(intval($user_id)==0){
                $user_id = str_replace("LMA-","",$data->suite_number);
            }

            if($user_id>0){
                $user = DB::table("users as u")
                    ->select("u.name",'u.surname','u.address',"u.email","uc.name as phone")
                    ->leftJoin("user_contacts as uc","u.id","uc.user_id")
                    ->where("u.id",$user_id)->first();

                iF($user!=null){
                    $data->user_id = $user_id;
                }else{
                    $user = (object)["address" => "","phone" => "","name" => "","surname" => "","email" => ""];

                }

            }
            return view('usa.packages.get_package', compact('data','user'));
        }else{
            echo "Package not found";
        }
    }


    public function packagePostParcelContents(Request $request)
    {
        $curl = curl_init();

        $parcel_id = $request->get("parcel_id");
        $description = $request->get("desc");//'1 PCSS SHOES';
        $desc = urlencode($description);
        $value = $request->get("price");
        $url = "https://api.ypn.io/v1/fulfillments/parcels/$parcel_id/contents?description=$desc&value=$value";

        $package_invoice_id = $request->get("package_invoice_id",0);
        $package_user_id = $request->get("package_user_id",0);
        $package_address = $request->get("package_address");
        $package_email = $request->get("package_email");
        $package_phone = $request->get("package_phone");
        $package_company = $request->get("package_company");

        $data = ForeignDepo::where("parcel_id",$parcel_id)->where("status_id","<",2)->first();

        if($data!=null){
            if($package_invoice_id>0){
                $data->invoice_id = $package_invoice_id;
            }

            if($package_user_id>0){
                $data->user_id = $package_user_id;
                $data->suite_number = "LMA-".$package_user_id;
                $data->client_phone = $package_phone;
                $data->client_company = $package_company;
                $data->client_email = $package_email;
                $data->client_address = $package_address;
                $data->price = $value;
                $data->description = $description;
            }

            $data->save();


            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_HTTPHEADER => array(
                    "Accept: */*",
                    "Accept-Encoding: gzip, deflate",
                    "Authorization: Basic TWpBPS5YY2lma0pNc1lkQzBwc240OTNwczAxU00wUkY3RG8zY3p4cE8xcCtpckZJPTo=",
                    "Cache-Control: no-cache",
                    "Connection: keep-alive",
                    "Content-Length: 0",
                    "Content-Type: application/json",
                    "cache-control: no-cache"
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                echo "cURL Error #:" . $err;
            } else {
                echo $response;

                $invoice = Invoice::find($package_invoice_id);
                if($invoice!=null){
                    $old_status_id = $invoice->status_id;

                    $invoice->weight    = lbToKg( $data->weight);
                    $invoice->length    = inchToSm($data->length);
                    $invoice->width     = inchToSm($data->width);
                    $invoice->height    = inchToSm($data->height);
                    $invoice->status_id = 2;
                    $invoice->purchase_no = $data->parcel_id;
                    $region_id = $invoice->region_id;
                    $tariffs = Country::where('prefix', '=', 'usa')->with('tariffs')->first();

                    $tariffArr = [];

                    foreach ($tariffs->tariffs as $val) {
                        if($val->region_id==$region_id){
                            $tariffArr[$val->label] = (float)$val->price;
                        }
                    }
                    // IF CORPORATE BEGIN TARIFF
                    if($invoice->corporate==1){
                        $user_id = $invoice->user_id;
                        $tariff_array = [];
                        $user = DB::table("users")->select("tariffs")->where("id",$user_id)->first();
                        if($user!=null){
                            $tariffAll = json_decode($user->tariffs,true);
                            if($invoice->region_id==1 and isset($tariffAll["tariffs"][$model->country_id][1])) {
                                $tariff_array = $tariffAll["tariffs"][$invoice->country_id][1]; // Baki
                            }
                            elseif($invoice->region_id==3 and isset($tariffAll["tariffs"][$model->country_id][2])){
                                $tariff_array = $tariffAll["tariffs"][$invoice->country_id][2]; // Sumqayit
                            }else{
                                if(isset($tariffAll["tariffs"][$model->country_id][3])){
                                    $tariff_array = $tariffAll["tariffs"][$invoice->country_id][3]; //Regionlar
                                }
                            }

                            if(count($tariff_array)>=1){
                                foreach ($tariff_array as $t){
                                    $tariffArr[$t["label"]] = floatval($t["price"]);
                                }
                            }

                        }
                    }
                    // IF CORPORATE END TARIFF

                    $cm = 99;

                    $weightResult = (float) ($invoice->length > $cm || $invoice->width > $cm || $invoice->height   > $cm) ? dimensionalWeight($invoice->width, $invoice->length, $invoice->height, $invoice->weight ) : $invoice->weight ;


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

                    $invoice->delivery_price = $result;
                    $invoice->shipping_price = $result;

                    $invoice->save();

                    if($old_status_id!=2){
                        $invoice_date=new InvoiceDates();
                        $invoice_date->status_id=2;
                        $invoice_date->invoice_id=$invoice->id;
                        $invoice_date->action_date=date('Y-m-d H:i:s');
                        $invoice_date->save();

                    }

                }

                // Invoice edit

                echo "<hr />";
                $address1 = urlencode($request->get("package_address",""));
                $address2 = urlencode('');
                $address3 = urlencode('');
                $email = urlencode($request->get("package_email",""));
                $phone = urlencode($request->get("package_phone",""));
                $company = urlencode($request->get("package_company",""));
                $suiteNumber = $data->suite_number;
                $country = 'AZ';

                $url = "https://api.ypn.io/v1/fulfillments/parcels/$parcel_id/consignee?company=$company&suiteNumber=$suiteNumber&name=$company&phone=$phone&address1=$address1&address2=$address2&address3=$address3&country=$country&email=$email";
                $curl = curl_init();


                curl_setopt_array($curl, array(
                    CURLOPT_URL => $url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_HTTPHEADER => array(
                        "Accept: */*",
                        "Accept-Encoding: gzip, deflate",
                        "Authorization: Basic TWpBPS5YY2lma0pNc1lkQzBwc240OTNwczAxU00wUkY3RG8zY3p4cE8xcCtpckZJPTo=",
                        "Cache-Control: no-cache",
                        "Connection: keep-alive",
                        "Content-Length: 0",
                        "Content-Type: application/json",
                        "cache-control: no-cache"
                    ),
                ));

                $response = curl_exec($curl);
                $err = curl_error($curl);

                curl_close($curl);

                if ($err) {
                    echo "cURL Error #:" . $err;
                } else {
                    echo $response;
                    echo "Data has been saved! ";
                }

            }
        }






    }




}
