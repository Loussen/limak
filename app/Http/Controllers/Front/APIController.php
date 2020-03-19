<?php
/**
 * Created by PhpStorm.
 * User: Rashad
 * Date: 10.01.2019
 * Time: 22:41
 */

namespace App\Http\Controllers\Front;


use App\Http\Controllers\Controller;
use App\Invoice;
use App\ModelCountry\Country;
use Illuminate\Http\Request;

class APIController extends Controller
{
    public function getDataByPurchaseNo ($purchaseNo) {
        $data = Invoice::where('purchase_no', '=', $purchaseNo)->with('relUserProducts.users', 'products.extras')->first();

        $result = [
            'userName' => $data->relUserProducts->users->name,
            'userSurname' => $data->relUserProducts->users->surname,
            'address' => 'Səbail rayonu, Lermontov küçəsi 113/117',
            'productsName' => $data->products->shop_name.' - '.$data->products->product_type_name,
            'price' => $data->products->price,
            'currency' => 'TL'
        ];

        return response()->json($result);
    }

    public function setInvoiceDataById (Request $request) {
        $id = $request['id'];

        $cm = 100;

        $trShippingPrice = 1.9;

        $model = Invoice::find($id);

        $tariffs = Country::where('prefix', '=', 'tr')->with('tariffs')->first();

        $tariffArr = [];

        foreach ($tariffs->tariffs as $val) {
            $tariffArr[$val->label] = $val->price;
        }

        $model->width = $request['width'];
        $model->height = $request['height'];
        $model->length = $request['length'];
        $model->weight = $request['weight'];

        $weightResult = ($request['length'] > $cm || $request['width'] > $cm || $request['height'] > $cm) ? $this->dimensionalWeight($request['width'], $request['length'], $request['height'], $request['weight']) : $request['weight'];
        $shippingResult = ($request['length'] > $cm || $request['width'] > $cm || $request['height'] > $cm) ? $this->dimensionalWeight($request['width'], $request['length'], $request['height'], $request['weight']) : $request['weight'];

        $result = 0;

        if ($weightResult >= 0 && $weightResult <= 0.25) {
            $result = round($tariffArr['minWeightPrice'] * $weightResult, 2);
        } else if ($weightResult > 0.25 && $weightResult <= 0.5) {
            $result = round($tariffArr['halfWeightPrice'] * $weightResult, 2);
        } else if ($weightResult > 0.5 && $weightResult <= 0.7) {
            $result = round($tariffArr['bigHalfWeightPrice'] * $weightResult, 2);
        } else {
            $result = round($tariffArr['weightPrice'] * $weightResult, 2);
        }

        $shippingPrice = $shippingResult * $trShippingPrice;

        $model->delivery_price = $result;
        $model->shipping_price = $shippingPrice;


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