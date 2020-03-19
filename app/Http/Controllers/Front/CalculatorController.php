<?php
/**
 * Created by PhpStorm.
 * User: Rashad
 * Date: 04.12.2018
 * Time: 21:57
 */

namespace App\Http\Controllers\Front;


use App\Currency;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CalculatorController extends Controller
{
    public function index() {
        return view('front/calculator/index');
    }

    public function calculateCurrency (Request $request) {
        $currency = Currency::find(1);

        $value = $request->value;

        if (!is_null($value) && is_numeric($value)) {
            $manat = round($value * $currency->usd, 1);

            $resultTl = round($manat / $currency->tl, 1);

            return response()->json(["status" => 200, "result" => ["tl" => $resultTl, 'manat' => $manat]]);
        }

        return response()->json(["status" => 500, "result" => "Düzgün məlumat göndərin"]);
    }
}