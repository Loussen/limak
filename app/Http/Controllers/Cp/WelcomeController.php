<?php

namespace App\Http\Controllers\Cp;
use App\Http\Controllers\Controller;

class WelcomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public $limit = 10;
    public function index()
    {
        echo "salam"; exit;
    }
}
