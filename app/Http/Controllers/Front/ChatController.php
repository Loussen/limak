<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    private $path = 'chat.';

    public function index()
    {
        return view($this->path.'index');
    }

    public function hasPermission($id)
    {
        if(!empty(Auth::user()) && Auth::user()->uniqid === $id) {
            return response()->json(true, 200);
        } else if(!empty(Auth::guard('admin')) && Auth::guard('admin')->user()->uniqid === $id) {
            return response()->json(true, 200);
        } else {
            return response()->json(false, 500);
        }
    }
}
