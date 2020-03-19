<?php

namespace App\Http\Controllers\cp;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ModelExpense\Expense;
use App\ModelExpense\ExpenseType;
use App\ModelAccount\Account;
use DB;

class ExpensesController extends Controller
{
    public function expenseTypes()
    {
        $result = [];
        //$expense_types =  ExpenseType::where("cat_id",0)->get();
        $expense_types =  ExpenseType::get()->toArray();
        foreach ($expense_types as $type){
            if($type["cat_id"]==0){
                $result[$type["id"]] = $type;
                $result[$type["id"]]["list"] = null;
            }else{
                $result[$type["cat_id"]]["list"][] = $type;
            }
        }
        return response()->json($result,200);
    }

    public function accounts(){
        $accounts =  Account::all();
        return response()->json($accounts,200);
    }

    public function storeExpenseType(Request $request)
    {
        $expense_type = ExpenseType::create([
            'cat_id' => $request->cat_id,
            'name' => $request->name,
            'code' => $request->code
        ]);
        return response()->json($expense_type,200);
    }


    public function storeExpense(Request $request)
    {
        $parent_id = $request->expense['type'];
        $account_id = $request->expense['account'];
        $amount = $request->expense['amount'];
        //$paid = $request->expense['paid'];
        $type = $request->expense['type'];
        $status = $request->expense['status'];
        $date = date("Y-m-d", strtotime($request->expense['date']));
        $before_amount = 0;
        $parent_before_amount = 0;

        $typeRow = ExpenseType::where("id",$request->expense['type'])->first();
        if($typeRow!=null){
            if($typeRow->cat_id>0){
                $parent = ExpenseType::where("id",$typeRow->cat_id)->first();
                if($parent!=null){
                    $parent_id = $parent->id;
                }
            }

            $last_expense = Expense::where("date","<=",$date)->where("type_id",$type)->orderBy("id","DESC")->first();
            if($last_expense!=null){
                $before_amount =  $last_expense->after_amount;
            }

            if($status=='kredit'){
                $after_amount = $before_amount - $amount;

            }else{
                $after_amount = $before_amount + $amount;
            }

            if($parent_id!=$type){
                $last_expense_parent = Expense::where("date","<=",$date)->where("parent_type_id",$parent_id)->orderBy("id","DESC")->first();
                if($last_expense_parent!=null){
                    $parent_before_amount =  $last_expense_parent->parent_after_amount;
                }

                if($status=='kredit'){
                    $parent_after_amount = $parent_before_amount - $amount;

                }else{
                    $parent_after_amount = $parent_before_amount + $amount;
                }
            }else{
                $parent_before_amount = $before_amount;
                $parent_after_amount = $after_amount;
            }



         /*   $all_expenses = Expense::where("date",">=",$date)->where("type_id",$type)->orderBy("id","ASC")->get();
            foreach ($all_expenses as $exp){
                if($status=='kredit'){
                    $exp->before_amount = $exp->before_amount - $amount;
                    $exp->after_amount = $exp->after_amount - $amount;
                    if($parent_id==$type){
                        $exp->parent_before_amount = $exp->parent_before_amount - $amount;
                        $exp->parent_after_amount = $exp->parent_after_amount - $amount;
                    }

                }else {
                    $exp->before_amount = $exp->before_amount + $amount;
                    $exp->after_amount = $exp->after_amount + $amount;

                    if($parent_id==$type){
                        $exp->parent_before_amount = $exp->parent_before_amount + $amount;
                        $exp->parent_after_amount = $exp->parent_after_amount + $amount;
                    }
                }
                $exp->save();
            }*/

            if($parent_id!=$type){
                $parent_all_expenses = Expense::where("date",">=",$date)->where("parent_type_id",$parent_id)->orderBy("id","ASC")->get();
                foreach ($parent_all_expenses as $exp){
                    if($status=='kredit'){
                            $exp->parent_before_amount = $exp->parent_before_amount - $amount;
                            $exp->parent_after_amount = $exp->parent_after_amount - $amount;
                    }else {
                            $exp->parent_before_amount = $exp->parent_before_amount + $amount;
                            $exp->parent_after_amount = $exp->parent_after_amount + $amount;

                    }
                    $exp->save();
                }
            }



            $expense = Expense::create([
                'parent_type_id' => $parent_id,
                'type_id' => $type,
                'account_id' => $account_id,
                'parent_before_amount' => $parent_before_amount,
                'before_amount' => $before_amount,
                'amount' => $amount,
                'after_amount' => $after_amount,
                'parent_after_amount' => $parent_after_amount,
                'comment' => $request->expense['comment'],
                'status' => $request->expense['status'],
                'date' => $date
            ]);

        }



        if($account_id>0){
            /*$account = Account::find($account_id);
            $account->balance = $account->balance - $amount;
            $account->save();*/
        }

        return response()->json($expense,200);
    }

    public function storeExpense_newold(Request $request)
    {
        $parent_id = $request->expense['type'];
        $account_id = $request->expense['account'];
        $amount = $request->expense['amount'];
        $type = $request->expense['type'];
        $status = $request->expense['status'];
        $before_amount = 0;
        $after_amount = 0;
        $typeRow = ExpenseType::where("id",$request->expense['type'])->first();

        if($typeRow!=null){

            if($typeRow->cat_id>0){

                if($status=='kredit'){
                    $before_amount = $typeRow->balance;
                    $after_amount = $typeRow->balance - $amount;

                }else{
                    $before_amount = $typeRow->balance;
                    $after_amount = $typeRow->balance + $amount;
                }

                $parent = ExpenseType::where("id",$typeRow->cat_id)->first();
                if($parent!=null){
                    $parent_id = $parent->id;

                    if($status=='kredit'){
                        $parent_before_amount = $parent->balance;
                        $parent_after_amount = $parent->balance - $amount;

                    }else{
                        $parent_before_amount = $parent->balance;
                        $parent_after_amount = $parent->balance + $amount;
                    }

                    $parent->balance = $parent_after_amount;
                    $parent->save();
                }

            }else{
                if($status=='kredit'){
                    $parent_before_amount = $typeRow->balance;
                    $parent_after_amount = $typeRow->balance - $amount;

                }else{
                    $parent_before_amount = $typeRow->balance;
                    $parent_after_amount = $typeRow->balance + $amount;
                }
            }




            $typeRow->balance = $after_amount;
            $typeRow->save();
        }

        $expense = Expense::create([
            'parent_type_id' => $parent_id,
            'type_id' => $type,
            'account_id' => $account_id,
            'parent_before_amount' => $parent_before_amount,
            'before_amount' => $before_amount,
            'amount' => $amount,
            'after_amount' => $after_amount,
            'parent_after_amount' => $parent_after_amount,
            'comment' => $request->expense['comment'],
            'status' => $request->expense['status'],
        ]);

        if($account_id>0){
            /*$account = Account::find($account_id);
            $account->balance = $account->balance - $amount;
            $account->save();*/
        }

        return response()->json($expense,200);
    }

    public function storeExpense_old(Request $request)
    {
        $parent_id = $request->expense['type'];
        $parent = ExpenseType::where("id",$request->expense['type'])->first();
        if($parent!=null){
            if($parent->cat_id>0){
                $parent_id = $parent->cat_id;
            }
        }

        $account_id = $request->expense['account'];
        $amount = $request->expense['amount'];
        $expense = Expense::create([
            'parent_type_id' => $parent_id,
            'type_id' => $request->expense['type'],
            'account_id' => $account_id,
            'amount' => $amount,
            'comment' => $request->expense['comment'],
            'status' => $request->expense['status'],
        ]);

        /*$account = Account::find($account_id);
        $account->balance = $account->balance - $amount;
        $account->save();*/
        return response()->json($expense,200);
    }

    public function expenses2()
    {
        $expenses = DB::table('expense_types as et')
            ->selectRaw('SUM(e.amount) as sum,e.*,et.*,et2.name as parent')
            ->join('expenses as e','e.type_id','=','et.id')
            ->leftJoin('expense_types as et2','et2.id','=','et.cat_id')
            ->groupBy('e.type_id','e.status')
            ->get();
        //return $expenses;
        $new_expenses= [];
        foreach ($expenses as $expense){
            if($expense->status == 'debet'){
                $new_expenses[$expense->type_id]['debet'] = $expense->sum;
            } else if($expense->status == 'kredit'){
                $new_expenses[$expense->type_id]['kredit'] = $expense->sum;
            }
            $new_expenses[$expense->type_id]['name'] = $expense->name;
            $new_expenses[$expense->type_id]['cat_id'] = $expense->cat_id;
            $new_expenses[$expense->type_id]['code'] = $expense->code;
            $new_expenses[$expense->type_id]['parent'] = $expense->parent;
            $new_expenses[$expense->type_id]['type_id'] = $expense->type_id;
        }
        return response()->json($new_expenses,200);
    }



    public function expenses(Request $request)
    {
        $id = $request->get("id","0");

        $result = [];
        if(intval($id)>0){
            $type_f = 'type_id';
            $before_amount = 'before_amount';
            $after_amount = 'after_amount';
            $expense_types =  ExpenseType::where("cat_id",$id)->get()->toArray();
        }else{
            $type_f = 'parent_type_id';
            $expense_types =  ExpenseType::where("cat_id",0)->get()->toArray();
            $before_amount = 'before_amount';
            $after_amount = 'parent_after_amount';

        }
        foreach ($expense_types as $exp_type){
                $result[$exp_type["id"]] = $exp_type;
                $result[$exp_type["id"]]["list"] = null;
                $result[$exp_type["id"]]["debet_before"] = $result[$exp_type["id"]]["kredit_before"] =
                    $result[$exp_type["id"]]["debet_after"]=$result[$exp_type["id"]]["kredit_after"]
                    =$result[$exp_type["id"]]["debet_amount"]=$result[$exp_type["id"]]["kredit_amount"]=0;

        }
        $all = $result;

        $begin_date = $request->get('begin_date', false);
        $end_date = $request->get('end_date', false);

        $expenses = DB::table('expenses as e');

        if(intval($id)>0){
            $expenses->where("parent_type_id",$id);
        }


        if($begin_date) $expenses = $expenses->where("date",'>',date("Y-m-d 00:00:00",strtotime($begin_date." 00:00:00")));
        if($end_date) $expenses = $expenses->where("date",'<',date("Y-m-d 23:59:59",strtotime($end_date." 23:59:59")));

        $expenses = $expenses->orderBy('id','ASC')->get();

        $i=1;
        foreach ($expenses as $expense){

            if($i==1){
                if(floatval($expense->$before_amount)>=0){
                    $all[$expense->$type_f]["debet_before"] = abs(floatval($expense->$before_amount));
                }else{
                    $all[$expense->$type_f]["kredit_before"] = abs(floatval($expense->$before_amount));
                }
            }

            if(floatval($expense->$after_amount)>=0){
                $all[$expense->$type_f]["debet_after"] = abs(floatval($expense->$after_amount));
                $all[$expense->$type_f]["kredit_after"] = 0;
            }else{
                $all[$expense->$type_f]["kredit_after"] = abs(floatval($expense->$after_amount));
                $all[$expense->$type_f]["debet_after"] = 0;
            }


            if($expense->status=='kredit'){
                $all[$expense->$type_f]["kredit_amount"] = $all[$expense->$type_f]["kredit_amount"] + floatval($expense->amount);
            }elseif($expense->status=='debet'){
                $all[$expense->$type_f]["debet_amount"] = $all[$expense->$type_f]["debet_amount"] + floatval($expense->amount);
            }

            $i++;
        }

        $kredit_after = 0;
        $debet_after = 0;
        $kredit_before = 0;
        $debet_before = 0;
        foreach ($all as $one){
            $kredit_after += $one["kredit_after"];
            $debet_after += $one["debet_after"];

            $kredit_before += $one["kredit_before"];
            $debet_after += $one["debet_before"];
        }

        $data["all"] = $all;
        $data["debet_after"] = $debet_after;
        $data["debet_before"] = $debet_before;
        $data["kredit_after"] = $kredit_after;
        $data["kredit_before"] = $kredit_before;


        return response()->json($data,200);
    }


    public function expenseDetails(Request $request)
    {
        $type_id = $request->id;
        $expense_type  = DB::table('expense_types')->where("id",$type_id)->first();
        $type_id_field = 'type_id';
        if($expense_type->cat_id==0){
            $type_id_field = 'parent_type_id';
        }
        $expenses = DB::table('expenses as e');

        if(intval($type_id)>0){
            $expenses->where($type_id_field,$type_id);
        }

        $begin_date = $request->get('begin_date', false);
        $end_date = $request->get('end_date', false);

        if($begin_date) $expenses = $expenses->where("date",'>',date("Y-m-d 00:00:00",strtotime($begin_date." 00:00:00")));
        if($end_date) $expenses = $expenses->where("date",'<',date("Y-m-d 23:59:59",strtotime($end_date." 23:59:59")));

        $expenses = $expenses->orderBy('date','ASC')->get();

        $data["debet"] = 0;
        $data["kredit"] = 0;
        foreach ($expenses as $exp){
            $data["expenses"][$exp->id] = $exp;
            if($exp->status=='debet'){
                $data["debet"] = $data["debet"] + $exp->amount;
            }else{
                $data["kredit"] = $data["kredit"] + $exp->amount;
            }
        }

        $data["type"] = $expense_type;



        return response()->json($data,200);

    }

    public function accountInfo(Request $request)
    {
        $id = $request->id;
        $begin_date = $request->begin_date;
        $end_date = $request->end_date;

        $type = ExpenseType::find($id);

        $expenses = DB::table("expenses")
            ->where("type_id",$id)
            ->where("date",">=",$begin_date)
            ->where("date","<=",$end_date)
            ->get();

        $data["data"] = $expenses;
        $data["type"] = $type;
        return response()->json($data,200);
    }


}
