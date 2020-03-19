<?php

namespace App\Http\Controllers;

use App\Currency;

class SharedAPIController extends Controller
{
    public function currency()
    {
        $data = Currency::first();
        return response()->json($data, 200);
    }
}
