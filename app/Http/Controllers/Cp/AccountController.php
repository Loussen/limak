<?php
namespace App\Http\Controllers\Cp;
use App\Currency;
use App\Dispatch;
use App\Http\Controllers\Controller;
use App\ModelLogs\LogBalance;
use Illuminate\Http\Request;
use App\ModelAccount\Account;
use App\ModelAccount\Repayment;
use App\ModelAccount\Eft;
use App\ModelLogs\AccountLog;
use App\ModelUser\User;
use Illuminate\Support\Facades\Input;
use DB;

class AccountController extends Controller
{

    public function index(){
        $accounts = DB::table('account_types as at')->leftJoin('accounts as a','a.type','=','at.id')->select('a.*','at.id as num','at.name as account_id')->get();
        $result = [];
        $array = [];

        $data = DB::table("account_logs")->select(DB::raw("sum(payment) as payment"),"type","account_id")->where("created_at",">=",date("Y-m-d 00:00"))->where("created_at","<=",date("Y-m-d 23:59:59"))->groupBy("account_id")->groupBy("type")->get();
        foreach ($data as $r){
            if($r->type=='plus'){
                $array[$r->account_id]["plus"] = $r->payment;
            }else{
                $array[$r->account_id]["minus"] = $r->payment;
            }
        }




        foreach ($accounts as $item){
            $item->minus = null;
            $item->plus  = null;
            if(isset($array[$item->id]["plus"])){
                $item->plus  = $array[$item->id]["plus"];
            }

            if(isset($array[$item->id]["minus"])){
                $item->minus  = $array[$item->id]["minus"];
            }

            $result[$item->num]['accounts'][] = $item;
            $result[$item->num]['account_id'] = $item->account_id;
        }


        return response()->json(['status' => 200, 'accounts' => $result]);
    }

    public function add(Request $request){

        $account_id = $request->id;
        $amount = $request->amount;
        $comment = $request->comment;
        $type = $request->type;

        $auth_id = auth()->id();
        $account = Account::find($account_id);
        $previous_balance = $account->balance;
        if($type == 'minus'){
            $account->balance = $previous_balance - $amount;
            $type = 'minus';
        }elseif($type == 'plus'){
            $account->balance = $previous_balance + $amount;
            $type = 'plus';
        }
        $account->save();
        $log = AccountLog::create([
            'account_id' => $account_id,
            'type' => $type,
            'payment' => $amount,
            'before_payment' => $previous_balance,
            'after_payment' => $account->balance,
            'admin_id' => $auth_id,
            'user_id' => 0,
            'invoice_id' => 0,
            'comment' => $comment

        ]);

        return $log;
    }

    public function listAccount(Request $request){
        $account_id = $request->log;
        $begin_date = $request->begin_date;
        $end_date = $request->end_date;


        if($begin_date!=null){
            $begin_date = date("Y-m-d H:i:s",strtotime($begin_date." 00:00:00"));
        }else{
            $begin_date = date("Y-m-d")." 00:00:00";
        }

        if($end_date!=null){
            $end_date = date("Y-m-d H:i:s",strtotime($end_date." 23:59:59"));
        }else{
            $end_date = date("Y-m-d")." 23:59:59";
        }

        $admin_id = $request->get('admin_id', false);
        $user_id = $request->get('user_id', false);
        $payment = $request->get('payment', false);

        $logs = AccountLog::where('account_id','=',$account_id)
            ->where("created_at",">=",$begin_date)
            ->where("created_at","<=",$end_date);

        $payment_logs = AccountLog::where('account_id','=',$account_id)
            ->where("created_at",">=",$begin_date)
            ->where("created_at","<=",$end_date);

        if($admin_id){
            $logs = $logs->where('admin_id','=',$admin_id);
            $payment_logs = $payment_logs->where('admin_id','=',$admin_id);
        }
        if($user_id) {
            $logs = $logs->where('user_id','=',$user_id);
            $payment_logs = $payment_logs->where('user_id','=',$user_id);
        }

        if($payment){
            $logs = $logs->where('payment','=',$payment);
            $payment_logs = $payment_logs->where('payment','=',$payment);
        }



        $logs = $logs->with('admin','user')
        ->orderBy('id','desc')
        ->paginate(30);



        $payment_logs = $payment_logs->select(DB::raw("sum(payment) as payment"),"type")->groupBy("type")->get();

        $account = Account::find($account_id);
        return response()->json(['status' => 200, 'account' => $account,"payment_logs" => $payment_logs,'logs' => $logs,"begin_date" => date("d/m/Y H:i",strtotime($begin_date)),"end_date" => date("d/m/Y H:i",strtotime($end_date))]);
    }

    public function listAccountPrint(Request $request){
        $account_id = $request->log;
        $begin_date = $request->begin_date;
        $end_date = $request->end_date;


        if($begin_date!=null){
            $begin_date = date("Y-m-d H:i:s",strtotime($begin_date." 00:00:00"));
        }else{
            $begin_date = date("Y-m-d")." 00:00:00";
        }

        if($end_date!=null){
            $end_date = date("Y-m-d H:i:s",strtotime($end_date." 23:59:59"));
        }else{
            $end_date = date("Y-m-d")." 23:59:59";
        }

        $admin_id = $request->get('admin_id', false);
        $user_id = $request->get('user_id', false);
        $payment = $request->get('payment', false);

        $logs = AccountLog::where('account_id','=',$account_id)
            ->where("created_at",">=",$begin_date)
            ->where("created_at","<=",$end_date);

        $payment_logs = AccountLog::where('account_id','=',$account_id)
            ->where("created_at",">=",$begin_date)
            ->where("created_at","<=",$end_date);

        if($admin_id){
            $logs = $logs->where('admin_id','=',$admin_id);
            $payment_logs = $payment_logs->where('admin_id','=',$admin_id);
        }
        if($user_id) {
            $logs = $logs->where('user_id','=',$user_id);
            $payment_logs = $payment_logs->where('user_id','=',$user_id);
        }

        if($payment){
            $logs = $logs->where('payment','=',$payment);
            $payment_logs = $payment_logs->where('payment','=',$payment);
        }



        $logs = $logs->with('admin','user')
            ->orderBy('id','desc')
            ->get();



        $payment_logs = $payment_logs->select(DB::raw("sum(payment) as payment"),"type")->groupBy("type")->get();

        $account = Account::find($account_id);
        return view('cp.account.logPrint',
            [
                'status' => 200,
                'account' => $account,
                "payment_logs" => $payment_logs,
                'logs' => $logs,
                "begin_date" => date("d.m.Y H:i",strtotime($begin_date)),
                "end_date" => date("d.m.Y H:i",strtotime($end_date))
            ]);
    }

    public function list($account_id){

        $begin_date = Input::get('begin_date', false);
        $end_date = Input::get('end_date', false);
        $user_id = Input::get('user_id', false);
        $logs = AccountLog::where('account_id','=',$account_id)->with('admin','user')->orderBy('id','desc');

        if($begin_date) $logs = $logs->where("created_at",'>',date("Y-m-d 00:00:00",strtotime($begin_date." 00:00:00")));
        if($end_date) $logs = $logs->where("created_at",'<',date("Y-m-d 23:59:59",strtotime($end_date." 23:59:59")));
        if($user_id) $logs = $logs->where("user_id",'=',$user_id);


        $logs = $logs->paginate(30);
        return response()->json(['status' => 200, 'logs' => $logs]);
    }

    public function customerBalances(Request $request){
        $per_page = 30;
        $type = $request->balance == 'plus' ? '>' : '<';
        $balances = User::select('id','name','surname','balance','uniqid')->where('balance',$type,0)->paginate($per_page);
        return response()->json(['status' => 200, 'balances' => $balances]);
    }


    public function getLogBalances(Request $request)
    {
        $per_page = 30;
        //$logs = LogBalance::orderBy("id","DESC")->paginate($per_page);

        $logs = DB::table('log_balances as lb')
            ->select("u.uniqid","u.name as u_name","u.surname as u_surname","lb.id",'lb.user_id','lb.old_balance','lb.new_balance','lb.money','lb.type','lb.message','lb.created_at')
            ->leftJoin('users as u','u.id','=','lb.user_id')
            ->orderBy('id','desc')->paginate($per_page);


        return response()->json(['status' => 200, 'logs' => $logs]);
    }

    public function dispatchs(Request $request){
        $dispatchs = Dispatch::where("status",0)->orderBy("id","DESC")->get();
        return response()->json(['status' => 200, 'dispatchs' => $dispatchs]);
    }

    public function getRepayments( Request $request ){
        $per_page = 20;
        $payment_type = $request->type;
        $user_id =  $request->user_id;

        $repayments = DB::table('repayments')
            ->join('users','users.id','=','repayments.user_id')
            ->join('user_contacts','user_contacts.id','=','users.id')
            ->leftJoin('admins','repayments.admin_id','=','admins.id')
            ->where('payment_type','=',$payment_type)
            ->orderBy('repayment_date','desc')    ;

            if($user_id>0) $repayments=$repayments->where('repayments.user_id',$user_id);

            if($request->executing==5) $repayments=$repayments->where('executer',null);
            else $repayments=$repayments->whereRaw('executer is not null');
            $repayments=$repayments->select('user_contacts.name as phone','users.name','users.surname','users.uniqid','email','repayments.*', 'admins.name as admin_name', 'admins.surname as admin_surname')
            ->paginate($per_page);

        return response()->json(['status' => 200, 'repayments' => $repayments ]);
    }

    public function getEFTpayments(){
        $per_page = 6;
        $eft = DB::table('efts')
            ->join('users','users.id','=','efts.user_id')
            ->select('efts.*','users.name','users.surname','users.uniqid')
            ->paginate($per_page);
        return response()->json(['status' => 200, 'eft' => $eft ]);
    }

    public function storeEFT(Request $request) {
        $efts = [];
        foreach ($request->products as $product){
            $efts [] = [
                'user_id' => $request->user,
                'paymentTo' => $request->paymentTo,
                'iban' => $request->iban,
                'account' => $request->account,
                'tax' => $request->tax,
                'payment_amount' => $product['amount'],
                'link' => $product['link'],
                'created_at' => date('Y-m-d H:i:s'),
            ];
        }
        Eft::insert($efts);
        return response()->json(['status' => 200 ]);

    }

    public function storeBackToCard(Request $request){
        $backToCardAmount = $request->amount;
        $reason = $request->reason;
        $user_id = $request->user;
        $payed_amount = 0;
        $orders = '';
        if($request->products==null){
            Repayment::create([
                'user_id' => $user_id,
                'admin_id'=> auth()->id(),
                'payment_type' => 1,
                'repayment_amount' => $backToCardAmount,
                'reason' => $reason,
            ]);
            return response()->json(['status' => 200 ]);

        }
        foreach ($request->products as $product){
            $payed_amount += $product['payment'];
            $orders .= $product['id'].',';
        }
        Repayment::create([
            'user_id' => $user_id,
            'admin_id' => auth()->id(),
            'payment_code' => $request->products[0]['transaction'],
            'payment_type' => 1,
            'payment_amount' => $payed_amount,
            'repayment_amount' => $backToCardAmount,
            'reason' => $reason,
            'orders' => trim($orders,',')
        ]);

        return response()->json(['status' => 200 ]);
    }

    public function storeBackToCard2(Request $request){
        $balanceCheck = $request->balanceCheck;
        $backToCardAmount = $request->amountCard;
        $backToBalanceAmount = $request->amountBalance;
        $reason = $request->reason;
        $user_id = $request->user;
        $user = User::find($user_id);

        $payed_amount = 0;
        $tl = 1;
        $currency = Currency::find(1);
        if($currency!=null){
            $tl = $currency->tl;
        }
        if($user!=null){
            $aznBalance = $user->balance;
            $tryBalance = $user->balance_try;

            if($balanceCheck){
                $backToBalanceAmount = $backToBalanceAmount + $backToCardAmount;
            }else{
                if($backToCardAmount<0){
                    $backToBalanceAmount = $backToBalanceAmount + $backToCardAmount;
                    $backToCardAmount = 0;
                }
            }

            if($aznBalance<0){
                if($backToBalanceAmount>0){
                    $old_balance = $aznBalance;
                    if(($aznBalance+($backToBalanceAmount*$tl))>=0){
                        $logAznBalance = abs($aznBalance);
                        $backToBalanceAmount = $tl*$backToBalanceAmount+$aznBalance;
                        $logAznMessage = 'Balansa qaytarılacaq məbləğdən minus balansının sıfırlanması';

                        LogBalance::create([
                            'user_id' => $user->id,
                            'old_balance' => $old_balance,
                            'new_balance' => $aznBalance,
                            'money' => $logAznBalance,
                            'message' => $logAznMessage,
                            'type' => 'azn'
                        ]);
                        $aznBalance = 0;

                    }else{
                        $logAznBalance = abs($backToBalanceAmount)*$tl;
                        $aznBalance = $backToBalanceAmount*$tl+$aznBalance;
                        $logAznMessage = 'Balansa qaytarılacaq məbləğdən minus balansının ödənməsi';

                        LogBalance::create([
                            'user_id' => $user->id,
                            'old_balance' => $old_balance,
                            'new_balance' => $aznBalance,
                            'money' => $logAznBalance,
                            'message' => $logAznMessage,
                            'type' => 'azn'
                        ]);
                        $backToBalanceAmount = 0;
                    }
                }
                if($aznBalance<0){
                    if($backToCardAmount>0){
                        $old_balance = $aznBalance;
                        if(($tl*$backToCardAmount+$aznBalance)>=0){

                            $logAznBalance = abs($aznBalance);
                            $backToCardAmount = $tl*$backToCardAmount+$aznBalance;
                            $logAznMessage = 'Karta qaytarılacaq məbləğdən minus balansının sıfırlanması';

                            LogBalance::create([
                                'user_id' => $user->id,
                                'old_balance' => $old_balance,
                                'new_balance' => $aznBalance,
                                'money' => $logAznBalance,
                                'message' => $logAznMessage,
                                'type' => 'azn'
                            ]);
                            $aznBalance = 0;

                        }else{
                            $logAznBalance = abs($backToCardAmount)*$tl;
                            $aznBalance = $tl*$backToCardAmount+$aznBalance;
                            $logAznMessage = 'Karta qaytarılacaq məbləğdən minus balansının sıfırlanması';

                            LogBalance::create([
                                'user_id' => $user->id,
                                'old_balance' => $old_balance,
                                'new_balance' => $aznBalance,
                                'money' => $logAznBalance,
                                'message' => $logAznMessage,
                                'type' => 'azn'
                            ]);
                            $backToCardAmount = 0;

                        }
                    }
                }

            }
            //echo $aznBalance; exit;
            $user->balance = $aznBalance;
            $user->save();
            $orders = '';
            $payment_codes = '';
            if($backToCardAmount>0){
                if($request->products==null){
                    Repayment::create([
                        'user_id' => $user_id,
                        'admin_id'=> auth()->id(),
                        'payment_type' => 1,
                        'repayment_amount' => $backToCardAmount,
                        'reason' => $reason,
                    ]);

                }else{
                    foreach ($request->products as $product){
                        $payed_amount += $product['payment'];
                        $orders .= $product['id'].',';
                        $payment_codes .= $product['transaction'].',';
                    }
                    Repayment::create([
                        'user_id' => $user_id,
                        'admin_id' => auth()->id(),
                        'payment_code' => trim($payment_codes,','),
                        'payment_type' => 1,
                        'payment_amount' => $payed_amount,
                        'repayment_amount' => $backToCardAmount,
                        'reason' => $reason,
                        'orders' => trim($orders,',')
                    ]);
                }

            }



            $tryBalance = $tryBalance + $backToBalanceAmount;

        }

        echo $backToBalanceAmount."<br />";
        echo $backToCardAmount."<br />";

        exit;
        $backToCardAmount = $request->amount;
        $reason = $request->reason;
        $user_id = $request->user;
        $payed_amount = 0;
        $orders = '';
        if($request->products==null){
            Repayment::create([
                'user_id' => $user_id,
                'admin_id'=> auth()->id(),
                'payment_type' => 1,
                'repayment_amount' => $backToCardAmount,
                'reason' => $reason,
            ]);
            return response()->json(['status' => 200 ]);

        }
        foreach ($request->products as $product){
            $payed_amount += $product['payment'];
            $orders .= $product['id'].',';
        }
        Repayment::create([
            'user_id' => $user_id,
            'admin_id' => auth()->id(),
            'payment_code' => $request->products[0]['transaction'],
            'payment_type' => 1,
            'payment_amount' => $payed_amount,
            'repayment_amount' => $backToCardAmount,
            'reason' => $reason,
            'orders' => trim($orders,',')
        ]);

        return response()->json(['status' => 200 ]);
    }

    public function getRepaymentDetails(Repayment $repayment){
        $orders_array = explode(',',$repayment->orders);
        $user = DB::table('users as u')->select('uc.name','u.email')->join('user_contacts as uc','u.id','=','uc.user_id')->where('u.id',$repayment->user_id)->first();
        $orders = DB::table('products')->whereIn('id',$orders_array)->get();
        return response()->json(['status' => 200, 'orders' => $orders, 'repayment' => $repayment,'user' => $user ]);
    }

    public function RepaymentExecute(Request $request, Repayment $repayment){
        $repayment->confirmation_code = $request->confirmation_code;
        $repayment->referance = $request->referance;
        $repayment->repayment_date = $request->repayment_date;
        $repayment->executer = auth()->id();
        $repayment->save();
        return response()->json(['status' => 200, 'repayment' => $repayment ]);
    }

    public function forOrderPayments(){
        return response()->json(['success' => true, 'data' => Account::where('for_order_payment', 1)->get() ]);
    }

    public function RepaymentDelete( Repayment $repayment ){
        $repayment->delete();
        return response()->json(['status' => 200 ]);
    }

    public function changeAccountId(Request $request){
        $log = AccountLog::find($request->get("id"));
        if($log!=null){
           $old_account_id = $log->account_id;
           $new_account_id = $request->get('account_id');

           $newAccount = Account::find($new_account_id);
           $oldAccount = Account::find($old_account_id);

           if($log->type=='minus' and $log->payment>0){
                $log->account_id = $new_account_id;
                $log->before_payment = $newAccount->balance;
                $log->after_payment = $newAccount->balance - $log->payment;
                $log->save();

                $newAccount->balance = $newAccount->balance - $log->payment;
                $newAccount->save();

               $oldAccount->balance = $oldAccount->balance + $log->payment;
               $oldAccount->save();

           }elseif($log->type=='plus' and $log->payment>0){
               $log->account_id = $new_account_id;
               $log->before_payment = $newAccount->balance;
               $log->after_payment = $newAccount->balance + $log->payment;
               $log->save();


               $newAccount->balance = $newAccount->balance + $log->payment;
               $newAccount->save();

               $oldAccount->balance = $oldAccount->balance - $log->payment;
               $oldAccount->save();
           }

        }

        return response()->json([
            'status' => 200,
            'data' => $log
        ]);
    }

}
