<?php

namespace App\Http\Controllers\Us;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;

class AuthController extends Controller
{

    public function index()
    {
        return view('usa/auth/login');
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email'   => 'required',
            'password' => 'required'
        ]);

        if (Auth::guard('usa')->attempt(['username' => $request->email, 'password' => $request->password], $request->get('remember'))) {
            return redirect('/');
        }
        return back()->withInput($request->only('email', 'remember'));
    }

    public function logout(Request $request) {
        Auth::logout();
        return redirect('/login');
    }
}
