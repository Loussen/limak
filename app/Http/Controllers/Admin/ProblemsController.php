<?php

namespace App\Http\Controllers\Admin;


use App\LostPackage;
use App\ComplaintType;
use App\ComplaintReason;
use App\Complaint;
use App\UserComplaint;
use App\ComplaintMessage;

use App\FormalComplaint;
use App\ModelProduct\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;


class ProblemsController extends Controller
{

    public function searchLostPackages(){
        $key = Input::get('key','');
        $keyword = Input::get('keyword','');

        $data=[];
        if($key=='id')
            $data = LostPackage::where($key,'=',$keyword)->get();
        elseif($key!='' && $keyword!=''){
                $data = Product::select('products.shop_name as shop_name','products.product_type_name as product_type_name','i.purchase_no as purchase_no')
                    ->leftJoin('invoices as i','i.product_id','=','products.id')
                    ->rightJoin('lost_packages as lp','lp.product_id','=','products.id');
                    if($key=='purchase_no') $data=$data->where('i.'.$key,'like','%'.$keyword.'%')->get();
                    else $data=$data->where('products.'.$key,'like','%'.$keyword.'%')->get();
        }

        return response()->json([
            'status' => 200,
            'data' => $data,
        ]);
    }

    public function getComplaintTypes(){

        $data['complaint_types']=(new ComplaintType())->all();
        $data['complaint_reasons']=(new ComplaintReason())->all();
        return response()->json([
            'status' => 200,
            'data' => $data
        ]);
    }
    public function getUserComplaints(){

        $data = DB::table('user_complaints as uc')
            ->select('uc.*','u.name','u.surname','u.uniqid',DB::raw("(SELECT cm.seen from complaint_messages as cm where cm.user_complaint_id=uc.id and cm.seen=0 and cm.admin_id=0 limit 1 ) as seen"))
            ->leftJoin('users as u','u.id','=','uc.user_id')
            ->orderBy('uc.created_at','desc')
            ->get();
//        $data = UserComplaint::orderBy('created_at','DESC')->get();
        return response()->json([
            'status' => 200,
            'data' => $data,
        ]);
    }

//    public function solveComplaint($id){
//        var_dump($id);
//        $u_id = Input::get('id',0);
//
//        var_dump($u_id);
////        $data = Complaint::orderBy('created_at','DESC')->get();
//        return response()->json([
//            'data' => 'ok',
//        ]);
//    }

    public function getComplaints(){
        $data = Complaint::orderBy('created_at','DESC')->get();
        return response()->json([
            'status' => 200,
            'data' => $data,
        ]);
    }

    public function sendMessagetoUser(Request $request)
    {
        $data=$request->validate([
            'message' => 'string|max:1000',
            'user_complaint_id' => 'numeric',

        ]);
        $msg = Input::get('message');
        $user_complaint_id = Input::get('user_complaint_id');

        $complaint = UserComplaint::where('id',$user_complaint_id)->first();
        $complaint->status = 1;
        $complaint->save();

        $message = new ComplaintMessage();
        $message->type = 1;
        $message->message = $msg;
        $message->admin_id = Auth()->user()->id;;
        $message->user_complaint_id = $user_complaint_id;
        $message->save();

        $this->changeSeen($user_complaint_id);

        return response()->json([
            'status' => 200,
            'data' => $data,
        ]);
    }

    public function getComplaint($id){
//        $id= Input::get('id',0);

//        $data=Complaint::select('complaints.*','u.email','u.pin','u.serial_number as mobile')
//        ->leftJoin('users as u','u.uniqid','=','complaints.uniqid')
//        ->where('complaints.id','=',$id)
//        ->first();
        $data = DB::table('complaint_messages as cm')
            ->select('cm.*','u.name','u.surname','u.uniqid','a.name as a_name','a.surname as a_surname')//,'r.name as role_name'
            ->leftJoin('users as u','u.id','=','cm.user_id')
            ->leftJoin('admins as a','a.id','=','cm.admin_id')
            //->leftJoin('rel_admin_roles as rer','a.id','=','rer.admin_id')
            //->leftJoin('roles as r','r.id','=','rer.role_id')
            ->where('cm.user_complaint_id',$id)
            ->orderBy('cm.id','asc')->get();

        $this->changeSeen($id);

        return response()->json([
            'status'=>200, 
            'data'=>$data
        ]);
    }

    public function formalComplaints($type){
        $data = FormalComplaint::where('type',$type)->get();
        return response()->json([
            'status'=>200,
            'data'=>$data
        ]);
    }

    public function changeSeen($id)
    {
        DB::table('complaint_messages')
            ->where('user_complaint_id', $id)
            ->where('seen',0)
            ->where('admin_id',0)
            ->update(['seen' => 1]);
        return response()->json([
            'data' => 'ok',
        ]);
    }
}

?>