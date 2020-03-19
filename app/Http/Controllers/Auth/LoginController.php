<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\ModelUser\User;
use App\Admins as Admin;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->redirectTo = '/' . Lang::getLocale().'/site/user-panel#/';
        //$this->middleware('isblocked')->except('login');
        $this->middleware('guest')->except('logout');
        $this->middleware('guest:admin')->except('adminLogout');
    }

    public function showAdminLoginForm()
    {
        return view('auth.admin-login', ['url' => 'admin']);
    }

    public function adminLogin(Request $request)
    {
        $this->validate($request, [
            'username'   => 'required',
            'password' => 'required'
        ]);

        if($request->has('sms_code')){
            $Admin = Admin::where('username', $request->username)->first();
            if($Admin->sms_code == $request->sms_code){
                if (Auth::guard('admin')->attempt(['username' => $request->username, 'password' => $request->password], $request->get('remember'))) {
                    return redirect('/cp');
                }
            }else{
                $data = [];
                $data['url'] ='admin';
                $data['username'] = $request->username;
                $data['password'] = $request->password;
                return view('auth.sms-login', $data);
            }
        }

//        if (!$user->is_blocked) {
            if (Auth::guard('admin')->attempt(['username' => $request->username, 'password' => $request->password], $request->get('remember'))) {
                $ips = [
                   '85.132.96.92','85.132.89.210','85.132.89.211','85.132.89.212','85.132.89.213', //apa
                    '213.173.85.133', '213.172.85.133','85.132.89.45', '85.132.89.43', '85.132.89.44','85.132.89.42','185.43.189.187','185.146.112.169','185.146.112.103','91.242.30.26','158.181.40.221','80.69.52.52','80.69.54.143'];
                //$ips = [];

                if(!in_array($_SERVER["HTTP_CF_CONNECTING_IP"], $ips)){
                    //Auth::guard('admin')->logout();
                    //return redirect()->route('admin.login.form');

                    $user = Auth::guard('admin')->user();
                    if($user && $user->login_from_other == 1){
                        $data = [];
                        $data['url'] ='admin';
                        $data['username'] = $request->username;
                        $data['password'] = $request->password;
                        $sms_code = rand(10000, 99999);
                        $admin = Admin::find($user->id);
                        $admin->sms_code = $sms_code;
                        $admin->save();
                        $admin->phone = str_replace("+","",$admin->phone);
                        sms((object) ['text' => 'Admin kod: '. $sms_code], $admin->phone);
                        Auth::guard('admin')->logout();
                        return view('auth.sms-login', $data);
                    }else{
                        Auth::guard('admin')->logout();
                        return redirect()->route('admin.login.form');
                    }
                }
                // echo $request->ip(); die;
                return redirect('/cp');
            }
//        }
        return back()->withInput($request->only('username', 'remember'));
    }

    public function adminLogout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login.form');
    }

    public function logout(Request $request) {
        $lang =  Lang::getLocale();
        Auth::logout();
        return redirect('/'.$lang.'/login');
    }
}
