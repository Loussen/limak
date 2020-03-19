<?php

namespace App\Http\Controllers\Cp;
use App\Http\Controllers\Controller;
use App\Invoice;
use App\ModelProduct\Product;
use App\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\ModelUser\User;
use App\RelUserProduct;


class SearchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function invoices (Request $request)
    {

        $users = DB::table('users');

        if(isset($request->uniqid)){
            $uniqid = $request->uniqid;
            $users = $users->where("uniqid",$uniqid);
        }

        if(isset($request->name)){
            $name = $request->name;
            $users = $users->where("name",'like','%'.$name.'%');
        }

        if(isset($request->surname)){
            $surname = $request->surname;
            $users = $users->where("surname",'like','%'.$surname.'%');
        }

        if(isset($request->surname)){
            $surname = $request->surname;
            $users = $users->where("surname",'like','%'.$surname.'%');
        }


        $users = $users->get();

        $array = $users;
        return response()->json(['status' => 200, 'data' => $array ]);

    }


    private function checkRole ($label) {
        $status = false;
        foreach (Auth::guard('admin')->user()->relAdminRoles as $value) {
            if($value->relRole->label === $label) {
                $status = true;
            }
        }
        return $status;
    }

}
