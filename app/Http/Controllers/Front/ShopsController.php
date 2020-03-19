<?php
/**
 * Created by PhpStorm.
 * User: Rashad
 * Date: 04.12.2018
 * Time: 22:41
 */

namespace App\Http\Controllers\Front;


use App\Http\Controllers\Controller;
use App\ModelShop\Shop;

class ShopsController extends Controller
{
    public function index() {
        $shops = Shop::all();
        return view('front/shop/index', compact('shops'));
    }
}