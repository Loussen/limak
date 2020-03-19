<?php

namespace App\Http\Controllers\Admin;
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
        return view('admin.welcome');
    }
}
