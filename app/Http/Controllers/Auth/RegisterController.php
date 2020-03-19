<?php

namespace App\Http\Controllers\Auth;

use App\Contact;
use App\ModelPermissions\Admin;
use App\ModelUser\User;
use App\Http\Controllers\Controller;
use App\ModelUser\UserContact;
use App\Rules\Phone;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/success';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
        $this->middleware('guest:admin');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $phone = $data["phone"];
        $data['phone'] = "+994".str_replace(["(050)","(051)","(055)","(070)","(077)","(",")"," ","-"],["50","51","55","70","77","","","",""],$phone);
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'phone' => ['required', 'string','unique:user_contacts,name', new Phone],
            'surname' => 'required',
            'address' => 'required',
            'serial_number' => 'required|string|min:7|max:9|unique:users',
            'citizenship' => 'required',
            'day' => 'required',
            'month' => 'required',
            'year' => 'required',
            'fin' => 'required|string|max:7|min:7|unique:users,pin',
            'gender' => 'required',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    protected function validatorForAdmin(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        if($_SERVER["REMOTE_ADDR"]=='213.172.85.133'){
            var_dump($data);
        }
        $lastPurchase_no = User::select('id')->orderBy('id', 'desc')->first();
        if($lastPurchase_no) {
            $lastPurchase_no =$lastPurchase_no->id;
        }else {
            $lastPurchase_no = 0;
        }
        //$passport_date =date_format(Carbon::createFromDate($data['year'], $data['month'], $data['day']),"Y:m:d") ;
        if($data['month'] < 10){
            $month = 0 . $data['month'];
        }else{
            $month = $data['month'];
        }

        if($data['day'] < 10){
            $day = 0 . $data['day'];
        }else{
            $day = $data['day'];
        }


        $data["name"] = strip_tags($data["name"]);
        $data["surname"] = strip_tags($data["surname"]);
        $data["address"] = strip_tags($data["address"]);
        $data["serial_number"] = strip_tags($data["serial_number"]);
        $data["fin"] = strip_tags($data["fin"]);

        $birthdate =$day . '.'. $month. '.'. $data['year'] ;
        $user = User::create([
            'name' => $data['name'],
            'balance' => 0,
            'email' => $data['email'],
            'uniqid' => str_pad($lastPurchase_no + 1, 7, 0, STR_PAD_LEFT),
            'surname' => $data['surname'],
            'address' => $data['address'],
            'gender' => $data['gender'],
            //'nationality' => $data['nationality'],
            'serial_number' => $data['serial_number'],
            'nationality' => $data['citizenship'],
            'birthdate' => $birthdate,
            'pin' => $data['fin'],
            //'birthdate' => $data['birthday'],
            'password' => Hash::make($data['password']),
        ]);

        $phone = $data['phone'];

        $phone = str_replace(["(050)","(051)","(055)","(070)","(077)","(",")"," ","-"],["50","51","55","70","77","","","",""],$phone);
         UserContact::create([
             'name' => "+994".ltrim($phone,0),
             'user_id' => $user->id
         ]);

        return $user;
    }

    protected function createAdmin(Request $request)
    {
        $this->validatorForAdmin($request->all())->validate();
        $admin = Admin::create([
            'name' => $request['name'],
            'username' => $request['username'],
            'password' => Hash::make($request['password']),
        ]);
        return redirect()->intended('login/admin');
    }

//    public function showAdminRegisterForm()
//    {
//        return view('auth.register', ['url' => 'admin']);
//    }

}
