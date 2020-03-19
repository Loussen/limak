<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\ModelUser\User;
use App\ModelUser\UserContact;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Rules\Phone;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Illuminate\Support\Facades\DB;


class AuthController extends Controller
{

    public function resetPassword(Request $request)
    {
        $data = $request->all();
        $validator =  Validator::make($data, [
            'phone' => ['required', 'string'],
        ]);
        if ($validator->fails()) {
            return response()->json(['success'=> false,'message'=>$validator->errors()]);
        }

        $phone = str_replace(["(050)","(051)","(055)","(070)","(077)","(",")"," ","-"],["50","51","55","70","77","","","",""],$data['phone']);
        $row = DB::table("user_contacts as uc")->select("u.id")->leftJoin("users as u","u.id","uc.user_id")->where("uc.name",$phone)->first();

        if($row!=null and intval($row->id)>0){
            $User = User::find($row->id);

            $token = md5($User->id + time());
            $User->api_key = $token;

            $code = rand(1000,9999);
            $User->activation_code = $code;

            $User->save();

            DB::table("user_activations")->insert(
                ['user_id' => $User->id, 'code' => $code,'count_try' =>0,'status' => 0,'phone' => $phone,'created_at' => date("Y-m-d H:i:s")]
            );

            $text = "Sizin tesdiq kodunuz: ".$code;
            $data = (object) ['text' => $text];
            sms($data, str_replace(['+', ' ', ')','('], '',$phone));

            $result = [
                "success" => true,
                "code" => $code,
                "token" => $token,
                "email" => $User->email
            ];

        }else{
            $result = [
                "success" => false,
                "message" => "Not Found"
            ];
        }

        return response()->json($result);

    }

    public function login(Request $request){

        $lang = $request->header("LANG",'az');

        LaravelLocalization::setLocale($lang);

        $email = $request->get('email', '1222');
        if (filter_var($request->get('email', '1222'), FILTER_VALIDATE_EMAIL)) {
            $User = User::where('email', $request->get('email', '1222'))->first();

            if($User !== null){

                if(Auth::guard('web')->attempt(['email' => $request->email, 'password' => $request->password])){
                    $token = md5($User->id + time());
                    $User->api_key = $token;
                    if($request->get("fcm_token",null)!=null){
                        $User->fcm_token = $request->get("fcm_token");
                    }

                    if($request->get("app_version",null)!=null){
                        $User->app_version = $request->get("app_version");
                    }
                    if($request->get("app_os",null)!=null){
                        $User->app_os = $request->get("app_os");
                    }
                    $User->save();
                    $userContact = UserContact::where("user_id",$User->id)->first();

                    $result = [
                        "region_id" => $User->region_id,
                        "serial_number" => $User->serial_number,
                        "balance" => $User->balance,
                        "balance_try" => $User->balance_try,
                        "name" => $User->name,
                        "surname" => $User->surname,
                        "email" => $User->email,
                        "address" => $User->address,
                        "birthdate" => $User->birthdate,
                        "gender" => $User->gender,
                        "uniqid" => $User->uniqid,
                        "is_blocked" => $User->is_blocked,
                        "is_corporate" => $User->corporate,
                        "is_premium" => $User->is_premium,
                        "activated" => $User->activated,
                    ];

                    if($userContact!=null){
                        $result["phone"] = $userContact->name;
                    }

                    $data = [
                        'success' => true,
                        'token'   => $token,
                        'user'    => $result
                    ];
                }else{
                    $data = [
                        'success' => false,
                        'message' => __("api.email_pass_in_correct")
                    ];
                }
            }else{
                $data = [
                    'success' => false,
                    'message' =>  __("api.email_pass_in_correct")
                ];
            }
        }else{
            $data = [
                'success' => false,
                'message' => __("api.email_invalid")
            ];
        }



        return response()->json($data);

    }

    public function register(Request $request)
    {
        $data = $request->all();
        $validator =  Validator::make($data, [
            'name' => 'required|string|max:255',
            'phone' => ['required', 'string','unique:user_contacts,name', new Phone],
            'surname' => 'required',
            'address' => 'required',
            'serial_number' => 'required|min:7|max:9|unique:users',
            'citizenship' => 'required',
            'pin' => 'required|string|max:7|min:7|unique:users,pin',
            'gender' => 'required',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
        if ($validator->fails()) {
            return response()->json(['success'=> false,'message'=>$validator->errors()]);
        }

        $data['phone'] = str_replace(["(050)","(051)","(055)","(070)","(077)","(",")"," ","-"],["50","51","55","70","77","","","",""],$data['phone']);

        $lastPurchase_no = User::select('id')->orderBy('id', 'desc')->first();

        $user = User::create([
            'name' => $data['name'],
            'balance' => 0,
            'email' => $data['email'],
            'uniqid' => str_pad($lastPurchase_no->id + 1, 7, 0, STR_PAD_LEFT),
            'surname' => $data['surname'],
            'address' => $data['address'],
            'gender' => $data['gender'],
            'serial_number' => $data['serial_number'],
            'nationality' => $data['citizenship'],
            'birthdate' => $data['birthday'],
            'pin' => $data['pin'],
            'password' => Hash::make($data['password']),
        ]);

        UserContact::create([
            'name' => $data['phone'],
            'user_id' => $user->id
        ]);

        $token = md5($user->id + time());

        $user->api_key = $token;
        if($request->get("fcm_token",null)!=null){
            $user->fcm_token = $request->get("fcm_token");
        }
       /* $code = rand(1000,9999);
        $user->activation_code = $code;*/
        $user->save();

        /*DB::table("user_activations")->insert(
            ['user_id' => $user->id, 'code' => $code,'count_try' =>0,'status' => 0,'phone' => $data['phone'],'created_at' => date("Y-m-d H:i:s")]
        );

        $text = "Sizin tesdiq kodunuz: ".$code;
        $data = (object) ['text' => $text];
        sms($data, str_replace(['+', ' ', ')','('], '',$data['phone']));*/

        $result = [
            "region_id" => $user->region_id,
            "serial_number" => $user->serial_number,
            "balance" => $user->balance,
            "balance_try" => $user->balance_try,
            "name" => $user->name,
            "surname" => $user->surname,
            "email" => $user->email,
            "phone" => $data['phone'],
            "address" => $user->address,
            "birthdate" => $user->birthdate,
            "gender" => $user->gender,
            "uniqid" => $user->uniqid,
            "is_blocked" => $user->is_blocked,
            "activated" => $user->activated,
        ];
        $data = [
            'success' => true,
            'token' => $token,
            'user' => $result
        ];

        return response()->json($data);
    }


}
