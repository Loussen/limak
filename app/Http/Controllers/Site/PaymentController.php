<?php
namespace App\Http\Controllers\Site;

use App\Currencies;
use App\Http\Controllers\Controller;
use App\ModelCountry\Country;
use App\ModelLogs\LogBalance;
use App\ModelShop\ShopCategory;
use App\ModelUser\User;
use App\Transactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Lang;

class PaymentController extends Controller
{
    public function millionApi(Request $request)
    {
        //85.132.120.9/32
        //109.205.166.108/32
        $ips = [
            '213.172.85.133',
            '85.132.120.9',
            '85.132.120.10',
            '85.132.120.11',
            '85.132.120.12',
            '85.132.120.13',
            '85.132.120.14',
            '85.132.120.15',
            '85.132.120.16',
            '85.132.120.17',
            '85.132.120.18',
            '85.132.120.19',
            '85.132.120.20',
            '85.132.120.21',
            '85.132.120.22',
            '85.132.120.23',
            '85.132.120.24',
            '85.132.120.25',
            '85.132.120.26',
            '85.132.120.27',
            '85.132.120.28',
            '85.132.120.29',
            '85.132.120.30',
            '85.132.120.31',
            '85.132.120.32',


            '109.205.166.108',
            '109.205.166.109',
            '109.205.166.110',
            '109.205.166.111',
            '109.205.166.112',
            '109.205.166.113',
            '109.205.166.114',
            '109.205.166.115',
            '109.205.166.116',
            '109.205.166.117',
            '109.205.166.118',
            '109.205.166.119',
            '109.205.166.120',
            '109.205.166.121',
            '109.205.166.122',
            '109.205.166.123',
            '109.205.166.124',
            '109.205.166.125',
            '109.205.166.126',
            '109.205.166.127',
            '109.205.166.128',
            '109.205.166.129',
            '109.205.166.130',
            '109.205.166.131',
            '109.205.166.132',
        ];
        //$ips = ['213.173.85.133', '213.172.85.133','85.132.89.45', '85.132.89.43','85.132.89.42','185.43.189.187'];
        //$ips = [];

        $content = '';
        $commands = ["check","pay","status"];
        $command = $request->command;
        $txn_id = $request->txn_id;
        $types = ["azn","try"];

        $error_content1 = '<?xml version="1.0" encoding="UTF-8"?>
                                <response>
                                <osmp_txn_id>'.$txn_id.'</osmp_txn_id>
                                <result>1</result>
                                <comment>NOT OK</comment>
                                <addinfo></addinfo>
                                </response> 
                                ';

        if(!in_array($_SERVER["HTTP_CF_CONNECTING_IP"], $ips)){
            $content = $error_content1;
        }else{
            if(in_array($command,$commands)){

                if($command == 'check'){
                    $accountParams = $request->account;
                    $content = $this->check($txn_id,$accountParams,$error_content1,$types);
                }elseif($command == 'pay'){
                    $sum = $request->sum;
                    $txn_date = $request->txn_date;
                    $accountParams = $request->account;

                    $content = $this->pay($accountParams,$txn_id,$sum,$txn_date,$error_content1,$types);
                }elseif($command == 'status'){
                    $content = $this->status($txn_id);
                }
            }else{
                $content = $error_content1;
            }
        }


        return response($content, 200)->header('Content-Type', 'text/xml');

    }


    private function check($txn_id,$accountParams,$error_content1,$types)
    {
        $content = '';
        $type = 'azn';

        $accountParamsArray = explode("||",$accountParams);

        if(isset($accountParamsArray[1]) and in_array($accountParamsArray[1],$types)){
            $type = $accountParamsArray[1];
        }
        $userHash = $accountParamsArray[0];
        $userId = unHashId($userHash);
        $user = User::find($userId);
        if($user!=null){
            $payment_type = "user_balance_".$type;

            $balance_type = 'balance';
            if($type == 'try'){
                $balance_type = "balance_try";
            }
            $transaction = Transactions::where("payment_id","=",$txn_id)->first();
            if($transaction!=null){
                if($transaction->user_id != $userId){
                    $content = $error_content1;
                }elseif ($transaction->status==1){
                    $content = $error_content1;
                }
            }else{
                $transaction  = new Transactions();
                $transaction->user_id = $userId;
                $transaction->payment_type = 'million';
                $transaction->payment_note = 'Million ile '.$type.' balansinin artirilmasi';
                $transaction->type = $payment_type;
                $transaction->payment_id  = $txn_id;
                $transaction->amount  = 0;
                $transaction->create_date  = date("Y-m-d H:i:s");
                $transaction->response_payment = serialize($_GET);
                $transaction->status = 0;
                if(!$transaction->save()){
                    $content = $error_content1;
                }
            }


            if($content == ''){//
                $content = '<?xml version="1.0" encoding="UTF-8"?>
                            <response>
                            <osmp_txn_id>'.$txn_id.'</osmp_txn_id>
                            <result>0</result>
                            <comment>OK</comment>
                            <addinfo>'.$user->name." ".$user->surname."||".$user->$balance_type.'</addinfo>
                            </response> 
                            ';

            }else{
                $content = $error_content1;
            }
        }else{
            $content = $error_content1;

        }

        return $content;
    }




    private function pay($accountParams,$txn_id,$sum,$txn_date,$error_content1,$types)
    {
        $money_log = $sum;
        $content = '';
        $balance_type_desc = 'Azn balans';
        $accountParamsArray = explode("||",$accountParams);

        $userHash = $accountParamsArray[0];
        $userId = unHashId($userHash);
        $user = User::find($userId);
        if($user!=null){
            $balance_type = 'balance';
            $log_balance_type = 'azn';

            $transaction = Transactions::where("payment_id","=",$txn_id)->where("user_id",$userId)->where("payment_type","million")->where("status",0)->first();
            if($transaction!=null){
                if($sum>0){
                    $transaction->amount = $sum;
                    if($transaction->type == 'user_balance_try') {
                        $balance_type = "balance_try";
                        $balance_type_desc = "TRY Balans ";
                        $log_balance_type = 'try';
                        $currency = Currencies::where("name","azn-try")->first();
                        if($currency!=null){
                            $sum = $sum *$currency->val;
                        }
                    }
                    $old_balance = $user->$balance_type;

                    $user->$balance_type = $old_balance + $sum;
                    $user->save();

                    LogBalance::create([
                        'user_id' => $userId,
                        'old_balance' => $old_balance,
                        'new_balance' => $user->$balance_type,
                        'money' => $sum,
                        'money_log' => $money_log,
                        'type' => $log_balance_type,
                        'message' => 'Million '.$balance_type_desc.' artirma'
                    ]);

                    $transaction->amount  = $sum;
                    $transaction->update_date = date("Y-m-d H:i:s");
                    $transaction->response_payment = serialize($_GET);
                    $transaction->status = 1;
                    $transaction->save();

                    $content = '<?xml version="1.0" encoding="UTF-8"?>
                                <response>
                                <osmp_txn_id>'.$txn_id.'</osmp_txn_id>
                                <prv_txn>'.$transaction->id.'</prv_txn>
                                <amount>'.$sum.'</amount>
                                <result>0</result>
                                <comment>'.$balance_type_desc.' artırıldı</comment>
                                </response>
                                ';

                }else{
                    $content = $error_content1;
                }
            }else{
                $content = $error_content1;
            }

        }else{
            $content = $error_content1;

        }

        return $content;
    }


    private function status($txn_id)
    {
        $transaction = Transactions::where("payment_id","=",$txn_id)->first();
        if($transaction==null){
            $content = '<?xml version="1.0" encoding="UTF-8"?>
                <response>
                <result>1</result>
                <osmp_txn_id>'.$txn_id.'</osmp_txn_id>
                <comment>Not found</comment>
                </response>
                ';
        }else{
            if($transaction->status==1){
                $content = '<?xml version="1.0" encoding="UTF-8"?>
                        <response>
                        <result>0</result>
                        <osmp_txn_id>'.$txn_id.'</osmp_txn_id>
                        <comment>OK</comment>
                        </response>
                        ';
            }else{
                $content = '<?xml version="1.0" encoding="UTF-8"?>
                    <response>
                    <result>1</result>
                    <osmp_txn_id>'.$txn_id.'</osmp_txn_id>
                    <comment>Not payed</comment>
                    </response>
                    ';
            }
        }

        return $content;
    }

}