<?php
namespace App\Http\Controllers\Cp;
use App\Courier;
use App\Invoice;
use App\ModelUser\User;
use App\CashBack;
use http\Env\Response;
use Illuminate\Http\Request;
use App\ModelAccount\Account;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CashController extends BaseController
{
    public function getLogBalanceByUser(Request $request)
    {
        $userId = $request->user;
        $type = $request->get("type",'0');
        $result = Db::table("log_balances")
            ->where("user_id",$userId);
        if($type==1){
            $result = $result->where("money","<",0);
        }elseif($type==2){
            $result = $result->where("money",">",0);
        }

        $result = $result->orderBy("created_at","DESC")->get()->toArray();
        array_map(function ($r){
            $message = explode("||",$r->message);
            $r->message = $message[0];

        },$result);

        return response()->json($result);
    }

    public function add(Request $request)
    {
        $user_id = $request->user_id;
        $balance_payment = number_format($request->balance_payment,2);
        $terminal_payment = number_format($request->terminal_payment,2);
        $cash_amount = number_format($request->cash,2);
        $total_price = number_format($request->total_price,2);
        $invoices = $request->invoices;
        $courier_price = number_format($request->courier_payment,2);
        $user = User::find($user_id);

        $invoices_string = implode(',',$invoices);
        if(number_format(($balance_payment + $terminal_payment + $cash_amount),2) >= $total_price){

            DB::table('invoices')->whereIn('id', $invoices)->update(array('is_paid' => 1));
            DB::table('invoices')->whereIn('id', $invoices)->where("status_id",getStatusByLabel('custom', 'invoice'))->update(array('status_id' => getStatusByLabel('completed', 'invoice')));
            if($cash_amount > 0){
                $cash_account = Account::find(1);
                $before_payment = $cash_account->balance;
                $cash_account->balance =$courier_price>0 ?  ($cash_account->balance + $cash_amount - $courier_price) : ($cash_account->balance + $cash_amount);
                if($cash_account->save()){
                    $cash_amount_new = $courier_price>0 ?  ($cash_amount - $courier_price) : $cash_amount;
                    if($cash_amount_new>0){
                        $this->log($cash_account->id,$cash_amount_new,'plus',$before_payment,$cash_account->balance,$user_id,$invoices_string,'Ödəniş');
                    }
                }
                

                //courier
                if($courier_price > 0){
                    $cash_account = Account::find(1);
                    $before_payment = $cash_account->balance;
                    $cash_account->balance = $before_payment + $courier_price;
                    if($cash_account->save()){
                        $this->log($cash_account->id,$courier_price,'plus',$before_payment,$cash_account->balance,$user_id,$invoices_string,'Kuryer haqqı');
                    }
                }

            }
            if($balance_payment > 0){
                //var_dump($user); die;
                //$user->balance = $user->balance - $balance_payment;
                //$user->save();
                $this->log(0,$balance_payment,'plus',0,0,$user_id,$invoices_string, 'Balans Ödəniş');

                $this->handleUserBalance($user->id,$balance_payment,'minus','Balansdan ödəniş');

            }
            if($terminal_payment > 0){
                $this->log(0,$terminal_payment,'plus',0, 0,$user_id,$invoices_string, 'Terminal Ödəniş');
            }
            if($courier_price > 0){
                $invoicesAll = Invoice::whereIn('id', $invoices)->where('status_id',7)->get();
                if($invoicesAll!=null){
                    foreach ($invoicesAll as $invoice_row){
                        if($invoice_row->courier_id!=null){
                            DB::table('couriers')->where('id', $invoice_row->courier_id)->update(array('is_paid' => 1));
                        }
                    }
                }
                /*$cash_account = Account::find(1);
                $before_payment = $cash_account->balance;
                $cash_account->balance = $before_payment + $courier_price;
                if($cash_account->save()){
                    $this->log($cash_account->id,$courier_price,'plus',$before_payment,$cash_account->balance,$user_id,$invoices_string,'Kuryer haqqı');
                }*/
                //$this->log(0,$courier_price,'plus',0, 0,$user_id,$invoices_string, 'Kuryer haqqı');
            }

            if($user->balance < 0){
                $user->balance = 0;
                $user->save();
            }

        }else{
            return response()->json(['status' => 100,'balance' => $balance_payment,'terminal' => $terminal_payment ,'text' => $balance_payment + $terminal_payment + $cash_amount,'text2' => $total_price]);
        }
    }

    public function show(Request $request){
        $account_id = $request->get("account_id",1);
        $cash = Account::find($account_id)->balance;
        $cash_try = Account::find(11)->balance;
        return response()->json(['status' => 200, 'balance' => $cash, 'balance_try' => $cash_try]);
    }

    public function store(Request $request){

        $amount = $request->money;
        $type = $request->type;
        $cash = Account::find(1);
        $before_payment = $cash->balance;
        $cash_amount = ($type == 'minus') ? $amount - 2*$amount : $amount;
        $message = ($type == 'minus') ? 'Kassadan məxaric' : 'Kassaya mədaxil' ;
        $cash->balance += $cash_amount;
        if($cash->save()) $this->log($cash->id,$amount,$type,$before_payment,$cash->balance,0,0, $message);

        $mainCash = Account::find(7);
        $before_payment = $mainCash->balance;
        $mainCash->balance -= $cash_amount;
        if($mainCash->save()) $this->log($mainCash->id,$amount,$type,$before_payment,$mainCash->balance,0,0, $message);

    }

    public function storeTry(Request $request){

        $amount = $request->money;
        $type = $request->type;
        $cash = Account::find(11);
        $before_payment = $cash->balance;
        $cash_amount = ($type == 'minus') ? $amount - 2*$amount : $amount;
        $message = ($type == 'minus') ? 'TRY Kassadan məxaric' : 'TRY Kassaya mədaxil' ;
        $cash->balance += $cash_amount;
        if($cash->save()) $this->log($cash->id,$amount,$type,$before_payment,$cash->balance,0,0, $message);

        $mainCash = Account::find(12);
        $before_payment = $mainCash->balance;
        $mainCash->balance -= $cash_amount;
        if($mainCash->save()) $this->log($mainCash->id,$amount,$type,$before_payment,$mainCash->balance,0,0, $message);

    }

    public function updateUserBalance(Request $request)
    {
        $user_id = $request->user_id;
        $amount = $request->money;
        $type = $request->type;

        $message = "Kassir tərəfindən müştərinin hesabına mədaxil || Adminid: ~~". Auth::guard('admin')->user()->id;

        $this->handleUserBalance($user_id, $amount, $type, $message);

        $cash_amount = ($type == 'minus') ? $amount - 2*$amount : $amount;
        $cash_account = Account::find(1);
        $before_payment = $cash_account->balance;
        $cash_account->balance = $cash_account->balance + $cash_amount;
        if($cash_account->save()){
            $this->log($cash_account->id,$cash_amount,$type,$before_payment,$cash_account->balance,$user_id,'', $message);
        }

        return response()->json(['success' => true]);
    }

    public function updateUserBalanceTry(Request $request)
    {
        $user_id = $request->user_id;
        $amount = $request->money_try;
        $type = $request->type;

        $message = "Kassir tərəfindən müştərinin TRY hesabına mədaxil || Adminid: ~~". Auth::guard('admin')->user()->id;

        $this->handleUserBalanceTry($user_id, $amount, $type, $message);

        $cash_amount = ($type == 'minus') ? $amount - 2*$amount : $amount;
        $cash_account = Account::find(11);
        $before_payment = $cash_account->balance;
        $cash_account->balance = $cash_account->balance + $cash_amount;
        if($cash_account->save()){
            $this->log($cash_account->id,$cash_amount,$type,$before_payment,$cash_account->balance,$user_id,'', $message);
        }

        return response()->json(['success' => true]);
    }


    public function cashBack(Request $request){
        $amount=$request->amount;
        $reason=$request->reason;
        $user_id=$request->uniqid;

        $type = 'minus';

        $message = "Kassir tərəfindən müştərinin hesabına mədaxil || Adminid: ~~". Auth::guard('admin')->user()->id;

        $cash_amount = ($type == 'minus') ? $amount - 2*$amount : $amount;
        $cash_account = Account::find(1);
        $before_payment = $cash_account->balance;
        $cash_account->balance = $cash_account->balance + $cash_amount;
        if($cash_account->save()){
            $this->log($cash_account->id,$cash_amount,$type,$before_payment,$cash_account->balance,$user_id,'', $message);
        }

        $cash_back = new CashBack();
        $cash_back->amount=$amount;
        $cash_back->reason=$reason;
        $cash_back->user_id=$user_id;
        $cash_back->admin_id=Auth::guard('admin')->user()->id;
        $cash_back->save();
        return response()->json([
            'success' => true,
//            'data' =>
        ]);
    }

    public function getRegionAccounts()
    {
        $user = Auth::guard('admin')->user();
        $accounts = Account::where("account_type",'cash');

        if(intval($user->region_id)>0){
            $accounts = $accounts->where("region_id",intval($user->region_id));
        }

        $accounts = $accounts->orderBy("region_id",'ASC')->get();
        return response()->json(['region_id' => intval($user->region_id),'accounts' => $accounts]);

    }

    public function getAccount(Request $request)
    {
        $account_id = $request->id;
        $account = Account::where("account_type",'cash')->where("account_id",$account_id)->first();

        if($account!=null){
            return response()->json(['account' => $account]);
        }else{
            return response()->json(['account' => null]);
        }


    }
}
