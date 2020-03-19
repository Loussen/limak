<?php
/**
 * Created by PhpStorm.
 * User: Rashad
 * Date: 04.12.2018
 * Time: 20:48
 */

namespace App\Http\Controllers\Front;


use App\Http\Controllers\Controller;
use App\ModelCountry\Country;

class CountryController extends Controller
{
    public function index() {
        $countries = Country::where('status','=',1)->with('translates', 'tariffs', 'tariffs.translates')->get();

        return view('front/country/index', compact('countries'));
    }
}