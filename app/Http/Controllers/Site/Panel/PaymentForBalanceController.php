<?php
namespace App\Http\Controllers\Site\Panel;

use App\Currencies;
use App\Currency;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MillikartCoreController;
use App\ModelLogs\LogBalance;
use App\ModelUser\User;
use App\Transactions;
use App\Transfer;
use App\UserBalanceImpExpLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Lang;

class PaymentForBalanceController extends Controller
{

    public function requestForPayment (Request $request) {
        $amount = $request->amount;
        if($amount>50){
            return redirect('/site/user-panel#/balance');
            ///site/user-panel#/balance
            //redirect()->getUrlGenerator()->previous();
        }
        $transaction = new Transactions();
        $transaction->user_id = Auth::user()->id;
        $transaction->payment_type = 'millikart';
        $transaction->payment_note = 'Millikart ile balans artirma';
        $transaction->amount = $amount;
        $transaction->create_date =  date("Y-m-d H:i:s");
        $transaction->save();

        $reference = $transaction->id;

        $userData = Auth::user()->uniqid;
        $payment = new MillikartCoreController($amount, $reference, $userData);
        $response = $payment->getURL();
        return redirect($response);
    }

    // Transfer poct xidmeti payment hissesi
    public function requestForTransferPayment (Request $request) {
        $transfer_id = $request->id;
        $user_id = Auth::user()->id;
        $transfer = Transfer::where("id",$transfer_id)->where("user_id",$user_id)->first();
        if($transfer!=null && $transfer->sum_price>0){
            $amount = $transfer->sum_price;

            $transaction = new Transactions();
            $transaction->user_id = Auth::user()->id;
            $transaction->payment_type = 'millikart';
            $transaction->payment_note = 'Millikart ile transfer pulu';
            $transaction->type = 'transfer';
            $transaction->payment_id = $transfer_id;
            $transaction->amount = $amount;
            $transaction->create_date =  date("Y-m-d H:i:s");
            $transaction->save();

            $reference = $transaction->id;

            $payment = new MillikartCoreController($amount, $reference, $transfer_id);
            $response = $payment->getURL();
            return redirect($response);
        }else{
            return redirect('/site/user-panel#/kuryer');
        }

    }

    public function getResponseOfPayment () {
        $data = intval(Input::get('reference'));
        $transaction  = Transactions::find($data);
        if($transaction!=null and $transaction->status == 0){
            $url = config('app.millicart_url').'/gateway/payment/status?mid=limak&reference='.$transaction->id;
            $xml = file_get_contents($url);
            $data = simplexml_load_string($xml);

            if($data->code == 0){
                $balance = User::find($transaction->user_id);
                $amount = $data->amount/100;
                $message_log = 'Online balans artirma';
                $currency = Currencies::where("name","azn-try")->first();
                if($transaction->type == 'user_balance_try'){
                    $balance_type = 'balance_try';
                    if($currency!=null){
                        $amount = $amount * $currency->val;
                    }
                    $message_log = 'Online Tl balans artırma';
                    $log_balance_type = 'try';

                }else{
                    $balance_type = 'balance';
                    $log_balance_type = 'azn';
                }

                $old_balance = $balance->$balance_type;

                $balance->$balance_type = $balance->$balance_type + $amount;
                $balance->save();

                LogBalance::create([
                    'user_id' => $transaction->user_id,
                    'old_balance' => $old_balance,
                    'new_balance' => $balance->$balance_type,
                    'money' => $amount,
                    'message' => $message_log,
                    'type' => $log_balance_type,
                    'note' => $data
                ]);

                $transaction->update_date = date("Y-m-d H:i:s");
                $transaction->status = 1;
                $transaction->response_payment = $data;
                $transaction->save();

                return redirect('/'.Lang::getLocale().'/site/user-panel#/'.$balance_type);

            }
        }else{
            return redirect('/'.Lang::getLocale().'/site/user-panel#/balance');
        }

    }



}
