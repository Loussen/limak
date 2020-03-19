<?php
/**
 * Created by PhpStorm.
 * User: Rashad
 * Date: 10.01.2019
 * Time: 22:41
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Invoice;
use App\ModelCountry\Country;
use Illuminate\Http\Request;

class APIController extends Controller
{
    public function getInvoiceDataById ($id) {
        $data = Invoice::find($id);

        /*$result = [
            'userName' => $data->relUserProducts->users->name,
            'userSurname' => $data->relUserProducts->users->surname,
            'address' => 'Səbail rayonu, Lermontov küçəsi 113/117',
            'productsName' => $data->products->shop_name.' - '.$data->products->product_type_name,
            'price' => $data->products->price,
            'currency' => 'TL'
        ];*/

        return view('admin.invoice.hawb-ajax', compact('data'));
    }

    public function setInvoiceDataById (Request $request) {
        $id = $request['id'];

        $cm = 99;

        $trShippingPrice = 5.5;

        $model = Invoice::find($id);

        $tariffs = Country::where('prefix', '=', 'tr')->with('tariffs')->first();

        $tariffArr = [];

        foreach ($tariffs->tariffs as $val) {
            $tariffArr[$val->label] = (float)$val->price;
        }

        $model->width = $request['width'];
        $model->height = $request['height'];
        $model->length = $request['length'];
        $model->weight = $request['weight'];
        $model->waybill = $request['waybill'];

        $weightResult = (float) ($request['length'] > $cm || $request['width'] > $cm || $request['height'] > $cm) ? $this->dimensionalWeight($request['width'], $request['length'], $request['height'], $request['weight']) : $request['weight'];


        if($weightResult >= 0 && $weightResult <= 0.25) {
            $result = $tariffArr['minWeightPrice'];
        }elseif($weightResult > 0.25 && $weightResult <= 0.5) {
            $result = $tariffArr['halfWeightPrice'];
        }elseif ($weightResult > 0.5 && $weightResult <= 0.7) {
            $result = $tariffArr['bigHalfWeightPrice'];
        }
        elseif ($weightResult > 0.7 && $weightResult <= 1) {
            $result = $tariffArr['weightPrice'];
        }else{
            $result = round($tariffArr['weightPrice'] * $weightResult, 2);
        }

        $model->delivery_price = $result;
        $model->shipping_price = $result;


        if ($model->save()) {
            return response()->json([
                'status' => 200,
                'message' => 'Başarılı'
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