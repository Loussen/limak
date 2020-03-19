<?php

namespace App\Http\Controllers\Front\Panel;

use App\Complaint;
use App\Http\Controllers\Controller;
use App\UserComplaint;
use App\ComplaintType;
use App\ComplaintMessage;
use App\ComplaintReason;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;


class QuestionsController extends Controller
{
    public function getComplaints(){
        $id=Auth()->user()->id;
        $data=DB::table('user_complaints as uc')
            ->select('uc.*',DB::raw("(SELECT cm.seen from complaint_messages as cm where cm.user_complaint_id=uc.id and cm.seen=0 and cm.user_id=0 limit 1 ) as seen"))
            ->where('uc.user_id',$id)
            ->orderBy('uc.created_at','Desc')
            ->get();

        return response()->json([
            'status' => 200,
            'data' => $data,
        ]);
    }
//    public function getComplaints1(){
//        $id=Auth()->user()->id;
//
//        $data1=DB::table('user_complaints as uc')
//            ->select('uc.*',DB::raw("(SELECT cm.seen from complaint_messages as cm where cm.user_complaint_id=uc.id and cm.seen=0 and cm.user_id=0 limit 1 ) as seen"))
//            ->where('uc.user_id',$id)
//            ->orderBy('uc.created_at','DESC')
//            ->get();
//
//        return response()->json([
//            'status' => 200,
//            'data1'=>$data1,
//        ]);
//    }
    public function getComplaintTypes(){

        $data['complaint_types']=(new ComplaintType())->all();
        $data['complaint_reasons']=(new ComplaintReason())->all();
        return response()->json([
            'status' => 200,
            'data' => $data
        ]);
    }
    public function addComplaint(Request $request)
    {
        $reason = Input::get('complaint_reason');
        $type = Input::get('complaint_type');
        $desc = Input::get('description');

        $validation = [
            'description' => 'string|max:500',
            'file' => 'nullable|mimes:jpg,png,jpeg,bmp|max:10000'
        ];

        $data=$request->validate($validation);
        $complaint = new UserComplaint();

        $complaint->user_id=Auth()->user()->id;
        $complaint->complaint_type_id=$type;
        $complaint->complaint_reason_id=$reason;
        $complaint->save();

        $message = new ComplaintMessage();
        if(request()->file) {
            $imageName = time() . '.' . request()->file->getClientOriginalExtension();
            request()->file->move(public_path('complaints'), $imageName);
            $message->file=$imageName;
        }
        $message->type = 0;
        $message->message = $desc;
        $message->user_id = Auth()->user()->id;
        $message->user_complaint_id = $complaint->id;
        $message->save();


        $message = new ComplaintMessage();
        $message->type = 1;
        $message->message = "Hörmətli istifadəçi, sizin sorğunuz Limak Müştəri Xidmətləri tərəfindən qəbul edildi. Tezliklə araşdırma aparılaraq sorğunuzla bağlı məlumatlandırılacaqsınız. Xidmətimizdən istifadə etdiyiniz üçün təşəkkür edirik.";
        $message->admin_id = 1;
        $message->user_complaint_id = $complaint->id;
        $message->save();
        return response()->json([
            'status' => 200,
            'data' => $data,
        ]);
    }

    public function sendMessagetoAdmin(Request $request)
    {

        $data=$request->validate([
            'message' => 'string|max:1000',
            'user_complaint_id' => 'numeric',
            //'file' => 'max:10000|mimes:jpg,png,jpeg,bmp'
        ]);
        $msg = Input::get('message');
        $user_complaint_id = Input::get('user_complaint_id');

        $this->changeSeen($user_complaint_id);

        $complaint = UserComplaint::where('id',$user_complaint_id)->first();
        $complaint->status = 0;
        $complaint->save();

        $message = new ComplaintMessage();
        if(request()->file) {
            $request->validate([
                'file' => 'max:10000|mimes:jpg,png,jpeg,bmp'
            ]);
            $imageName = time() . '.' . request()->file->getClientOriginalExtension();
            request()->file->move(public_path('complaints'), $imageName);
            $message->file=$imageName;
        }
        $message->type = 0;
        $message->message = $msg;
        $message->user_id = Auth()->user()->id;;
        $message->user_complaint_id = $user_complaint_id;
        $message->save();
        return response()->json([
            'data' => $data,
        ]);
    }
    public function getComplaintMessages($id)
    {
//        $u_id=Auth()->user()->id;

        $data=DB::table('complaint_messages as cm')->select('*')
            ->where('user_complaint_id',$id)
            ->orderBy('id','asc')->get();
        return response()->json([
            'data' => $data,
        ]);
    }

    public function changeSeen($id)
    {
        DB::table('complaint_messages')
            ->where('user_complaint_id', $id)
            ->where('seen',0)
            ->where('user_id',0)
            ->update(['seen' => 1]);
        return response()->json([
            'data' => 'ok',
        ]);
    }
}
