<?php
namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MillikartCoreController;
use App\ModelLogs\LogBalance;
use App\ModelUser\User;
use App\RelUserProduct;
use App\Transactions;
use App\UserBalanceImpExpLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Lang;

class PaymentForProductController extends Controller
{
    public function getResponseOfPayment (Request $request) {
        $reference = $request->post("payuPaymentReference");
        $message = $request->post("message");
        $rel = RelUserProduct::where("reference",$reference)->where('is_paid',0)->first();
        if($rel!=null){
            $rel->is_paid = 1;
            $rel->save();
            //*********************************************************************** Product is paid etmek lazimdir
            DB::table('products')
                ->where('rel_user_product_id', $rel->id)
                ->update(['is_paid' => 1]);

            $message = 'Ödəniş tamamlanmışdır11!';
            return view('front.paymentSuccess', compact('message'));
        }else{
            $message = session('failed_reason_msg');
            session(['failed_reason_msg' => '']);
            return view('front.paymentFail', compact('message'));
        }
    }
}
