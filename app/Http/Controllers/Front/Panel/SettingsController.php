<?php

namespace App\Http\Controllers\Front\Panel;

use App\Contact;
use App\Invoice;
use App\ModelUser\User;
use App\ModelUser\UserContact;
use App\RelUserProduct;
use App\Status;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use function PHPSTORM_META\type;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $data = Auth::user();
       $data->userContacts;
       return response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Contact $contact
     * @return void
     */
    public function store(Request $request)
    {

        $userId = Auth::user()->id;
        $validation = [
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'birthdate' => 'required',
            'pin' => 'required',
            'gender' => 'required',
            'serialNumber' => 'required',
        ];
        $request->validate($validation);
        $user = User::find($userId);
        $user->name = $request->name;
        $user->address = $request->address;
        $user->surname = $request->surname;
        $user->email = $request->email;
        $user->birthdate = $request->birthdate;
        $user->pin = $request->pin;
        $user->serial_number = $request->serialNumber;
        $user->gender = $request->gender;
        $user->nationality = $request->nationality;

        if ( strlen($request->password) >= 8 && $request->password === $request->cpassword) {

            if(Hash::check($request->currentPassword, $user->password)) {
                $user->password = Hash::make($request->password);
            }
        }
        $user->save();
        $userContacts = UserContact::where('user_id', '=', $userId)->first();
        if($userContacts) {
            $userContacts->name = $request->phone;
            $userContacts->save();
        } else {
            $userContacts = new UserContact();
            $userContacts->user_id = $userId;
            $userContacts->name = $request->phone;
            $userContacts->save();
        }

        return response()->json((object)['code' => 200, 'message' => 'ok']);
    }
    public function storeProfile(Request $request)
    {

        $userId = Auth::user()->id;
        $validation = [
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'birthdate' => 'required',
        ];
        $request->validate($validation);
        $user = User::find($userId);
        $user->name = $request->name;
        $user->surname = $request->surname;
        $user->email = $request->email;
        $user->birthdate = $request->birthdate;
        $user->region_id = $request->region_id;

        $user->save();
        $userContacts = UserContact::where('user_id', '=', $userId)->first();
        if($userContacts) {
            $userContacts->name = $request->phone;
            $userContacts->save();
        } else {
            $userContacts = new UserContact();
            $userContacts->user_id = $userId;
            $userContacts->name = $request->phone;
            $userContacts->save();
        }

        return response()->json((object)['code' => 200, 'message' => 'ok']);
    }
    public function storePassport(Request $request)
    {

        $userId = Auth::user()->id;
        $validation = [
            'pin' => 'required',
            'gender' => 'required',
            'serialNumber' => 'required',
            'address' => 'required',
        ];
        $request->validate($validation);
        $user = User::find($userId);
        $user->pin = $request->pin;
        $user->serial_number = $request->serialNumber;
        $user->gender = $request->gender;
        $user->nationality = $request->nationality;
        $user->address = $request->address;
        $user->save();

        return response()->json((object)['code' => 200, 'message' => 'ok']);
    }

    public function storePassword(Request $request)
    {
        $userId = Auth::user()->id;
        $user = User::find($userId);

        if ( strlen($request->password) >= 8 && $request->password === $request->cpassword) {

            if(Hash::check($request->currentPassword, $user->password)) {
                $user->password = Hash::make($request->password);
            }
        }
        $user->save();
        return response()->json((object)['code' => 200, 'message' => 'ok']);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
