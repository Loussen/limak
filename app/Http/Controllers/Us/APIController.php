<?php
/**
 * Created by PhpStorm.
 * User: Rashad
 * Date: 10.01.2019
 * Time: 22:41
 */

namespace App\Http\Controllers\Us;


use App\Http\Controllers\Controller;
use App\Invoice;
use App\ModelCountry\Country;
use App\ModelCountry\Regions;
use App\UserPromoCodes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class APIController extends Controller
{
    public function getInvoiceDataById ($id) {
        $data = Invoice::find($id);

        return view('usa.invoice.hawb-ajax', compact('data'));
    }

    public function setInvoiceDataById (Request $request) {
        $id = $request['id'];

        $cm = 99;

//        $trShippingPrice = 5.5;

        $model = Invoice::find($id);

        $region_id = $model->region_id;

        $region_row = Regions::find($region_id);

        if($region_row==null){
            $region_id = 1;
        }

        $tariffs = Country::where('prefix', '=', 'usa')->with('tariffs')->first();

        $tariffArr = [];

        foreach ($tariffs->tariffs as $val) {
            if($val->region_id==$region_id){
                $tariffArr[$val->label] = (float)$val->price;
            }
        }

        // IF CORPORATE BEGIN TARIFF
        if($model->corporate==1){
            $user_id = $model->user_id;
            $tariff_array = [];
            $user = DB::table("users")->select("tariffs")->where("id",$user_id)->first();
            if($user!=null){
                $tariffAll = json_decode($user->tariffs,true);
                if($model->region_id==1 and isset($tariffAll["tariffs"][$model->country_id][1])) {
                    $tariff_array = $tariffAll["tariffs"][$model->country_id][1]; // Baki
                }
                elseif($model->region_id==3 and isset($tariffAll["tariffs"][$model->country_id][2])){
                    $tariff_array = $tariffAll["tariffs"][$model->country_id][2]; // Sumqayit
                }else{
                    if(isset($tariffAll["tariffs"][$model->country_id][3])){
                        $tariff_array = $tariffAll["tariffs"][$model->country_id][3]; //Regionlar
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

        $model->width = $request['width'];
        $model->height = $request['height'];
        $model->length = $request['length'];
        $model->weight = $request['weight'];
        $model->waybill = $request['waybill'];

        $weightResult = (float) ($request['length'] > $cm || $request['width'] > $cm || $request['height'] > $cm) ? $this->dimensionalWeight($request['width'], $request['length'], $request['height'], $request['weight']) : $request['weight'];

        $resultCampaign = 0;
        $result = 0;
        $promo  = null;
        if($model->promo_code!=null){
            $promo = UserPromoCodes::with("campaign")->where("promo_code","=",$model->promo_code)->where("user_id","=",$model->user_id)->first();
            if($promo!=null){
                if($promo->invoice_id==0){
                    $promo->invoice_id = $model->id;
                }

                if($promo->invoice_id==$model->id){
                    $campaign = $promo->campaign;
                    if($weightResult<=$campaign->max_weight and $campaign->max_weight>0){
                        $weightResultCampaign = $weightResult;
                        $weightResult = -1;
                    }elseif($campaign->max_weight>0){
                        $weightResultCampaign = $campaign->max_weight;
                        $weightResult = $weightResult-$campaign->max_weight;
                    }

                    if($weightResultCampaign >= 0 && $weightResultCampaign <= 0.25) {
                        $resultCampaign = $tariffArr['minWeightPrice'];
                    }elseif($weightResultCampaign > 0.25 && $weightResultCampaign <= 0.5) {
                        $resultCampaign = $tariffArr['halfWeightPrice'];
                    }elseif ($weightResultCampaign > 0.5 && $weightResultCampaign <= 0.7) {
                        $resultCampaign = $tariffArr['bigHalfWeightPrice'];
                    }
                    elseif ($weightResultCampaign > 0.7 && $weightResultCampaign <= 1) {
                        $resultCampaign = $tariffArr['weightPrice'];
                    }else{
                        $resultCampaign = round($tariffArr['weightPrice'] * $weightResultCampaign, 2);
                    }

                    if($campaign->type==0){
                        $resultCampaign = $resultCampaign*((100-$campaign->percent)/100);
                    }elseif($campaign->type==1){
                        $resultCampaign = $resultCampaign - $campaign->amount;
                        if($resultCampaign<0){
                            $resultCampaign = 0;
                        }
                    }
                }else{
                    $promo = null;
                }

            }
        }

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

        $result = $result + $resultCampaign;

        $model->delivery_price = $result;
        $model->shipping_price = $result;

        if ($model->save()) {
            if($promo!=null){
                $promo->status = 1;
                $promo->save();
            }
            return response()->json([
                'status' => 200,
                'message' => 'Successful'
            ]);
        }
    }

    private function dimensionalWeight($width, $length, $height, $weight) {
        $dimensionalWeight = round(($width * $length * $height) / 6000, 2);

        if ($dimensionalWeight > $weight) {
            return $dimensionalWeight;
        } else {
            return $weight;
        }
    }
}