<?php

namespace App\Http\Controllers\Admin\Chat;

use App\ModelUser\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ChatController extends Controller
{
    private $path = 'admin.chat.';
    public function index()
    {
        return view($this->path.'index');
    }

    public function usersByUniqid($uniqids)
    {
        return response()->json(User::whereIn('uniqid' , $uniqids)->get(), 200);
    }
}
