<?php

namespace App\Http\Controllers\Site;

use App\CurrencyNew;
use App\Http\Controllers\Controller;
use App\ModelCountry\CountryTariff;
use Illuminate\Http\Request;
use App\ModelCountry\Country;
use Illuminate\Support\Facades\View;
use Artisan;

class CalculatorController extends Controller
{
    public $seo;

    public function __construct()
    {
        //  $this->middleware('auth:web');
        $this->seo = \Lang::get('seo');
        $this->getMetaContent('home');
    }

    public function getMetaContent($menu){
        $meta = [];
        $meta['title'] = isset($this->seo['title'][$menu]) ? $this->seo['title'][$menu]: $this->seo['title']['home'];
        $meta['description'] = isset($this->seo['description'][$menu]) ? $this->seo['description'][$menu]: $this->seo['description']['home'];
        $meta['keywords'] = isset($this->seo['keywords'][$menu]) ? $this->seo['keywords'][$menu]: $this->seo['keywords']['home'];
        View::share('meta', $meta);
    }

    //OK
    public function index() {
        return view('site.calculator.index');
    }

    public function premium() {
        return view('site.calculator.premium');
    }


    public function calculate(Request $request){

        $cm = 99;

        $country = $request->get("country",1);
        $region = $request->get("region",1);

        $tariffs = CountryTariff::where("country_id",$country)->where("region_id",$region)->get();

        $tariffArr = [];

        foreach ($tariffs as $val) {
            $tariffArr[$val->label] = (float)$val->price;
        }
        $weightResult = (float) ($request['length'] > $cm || $request['width'] > $cm || $request['height'] > $cm) ? $this->dimensionalWeight($request['width'], $request['length'], $request['height'], $request['weight']) : $request['weight'];
        $result = 0;

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

        return $result* $request['count'];
    }

    private function dimensionalWeight($width, $length, $height, $weight) {
        $dimensionalWeight = round(($width * $length * $height) / 6000, 2);

        if ($dimensionalWeight > $weight) {
            return $dimensionalWeight;
        } else {
            return $weight;
        }
    }


    public function calculateCurrency (Request $request) {

        $currencies = CurrencyNew::all();
        return response()->json(["status" => 200, "currencies" => $currencies]);
    }


}