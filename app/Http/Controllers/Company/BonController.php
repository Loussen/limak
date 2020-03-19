<?php

namespace App\Http\Controllers\Company;

use App\DepoPackages;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MillikartCoreController;
use App\Invoice;
use App\InvoicePayment;
use App\ModelLogs\LogBalance;
use App\ModelLogs\LogPaymentDeliveryInvoices;
use App\ModelProduct\Extras;
use App\ModelProduct\Product;
use App\Models\Courier;
use App\Models\Transactions;
use App\Packages;
use App\Persons;
use App\RelUserProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\ModelUser\User;
use App\ModelUser\UserContact;
use App\Libraries\Upload\Uploader;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Rules\Phone;
use App\Utility\UserUtility;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\DB;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class BonController extends Controller
{

    public function index(Request $request)
    {
        $url = $request->get("url",false);
        $success = false;
        $row = [];
        if($url){
            $row = DB::table("extras as e")
                ->select("e.link2","p.expenses","p.is_paid","p.is_ordered","i.order_tracking_number as sifaris_nomresi","i.delivery_number as teslimat_nomresi")
                ->leftJoin("products as p","p.extras_id","=","e.id")
                ->leftJoin("invoices as i","p.id","=","i.product_id")
                ->where("e.link2",$url)
                ->get();
            if(count($row)>=1){
                $success = true;
            }

        }
        return response()->json(['success' => $success, 'data' => $row]);

    }

}