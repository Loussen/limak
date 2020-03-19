<?php

namespace App\Http\Controllers\Cp;

use App\ModelLogs\AccountLog;
use App\ModelUser\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ModelLogs\LogBalance;
use Illuminate\Support\Facades\Auth;

class BaseController extends Controller
{

    public function handleUserBalance($user_id, $amount, $type, $message,$message_admin=''){

        $amount = ($type == 'minus') ? $amount - 2*$amount : $amount;
        $user = User::find($user_id);
        $balance = $user->balance;
        $user->balance = $balance + $amount;
        $user->save();
        LogBalance::create([
            'user_id' => $user_id,
            'old_balance' => $balance,
            'new_balance' => $user->balance,
            'money' => $amount,
            'message' => $message,
            'message_admin' => $message_admin,
            'type' => 'azn'
        ]);
    }

    public function handleUserBalanceTry($user_id, $amount, $type, $message,$message_admin=''){

        $amount = ($type == 'minus') ? $amount - 2*$amount : $amount;
        $user = User::find($user_id);
        $balance_try = $user->balance_try;
        $user->balance_try = $balance_try + $amount;
        $user->save();
        LogBalance::create([
            'user_id' => $user_id,
            'old_balance' => $balance_try,
            'new_balance' => $user->balance_try,
            'money' => $amount,
            'message' => $message,
            'message_admin' => $message_admin,
            'type' => 'try'
        ]);
    }

    protected function log($account_id,$amount,$type,$before_payment,$after_payment,$user_id,$invoice_ids, $comment = ''){

        AccountLog::create([
            'account_id' => $account_id,
            'type' => $type,
            'payment' => $amount,
            'before_payment' => $before_payment,
            'after_payment' => $after_payment,
            'admin_id' => auth()->id(),
            'user_id' => $user_id,
            'invoice_id' =>$invoice_ids,
            'comment' => $comment
        ]);
    }
}
