<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Libraries\Upload\Uploader;
use App\ModelMessages\Message;
use App\ModelMessages\MessageAttachment;
use App\ModelMessages\MessageCategory;
use App\ModelMessages\MessageLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessengerController extends Controller
{
    private $path = 'front.messenger.';
    public function index()
    {
        $fromuser = Auth::user()->uniqid;
        $list = Message::where('from_user_id' , $fromuser)
            ->orwhere('to_user_id', $fromuser)->where('message_id', null)->paginate(10);
        $categories = MessageCategory::select('id', 'name')->get();
        return view($this->path.'index', compact('list', 'categories'));
    }


    public function list()
    {
        $fromuser = Auth::user()->uniqid;
        $list = Message::with('messages', 'category')
            ->where('from_user_id' , $fromuser)
            ->where('message_id', null)
            ->where('status' , '<', 5 )
            ->get();
        return response()->json($list, 200);
    }
    public function categories()
    {
        $categories = MessageCategory::select('id', 'name')->get();
        return response()->json($categories, 200);
    }

    public function post(Request $request)
    {
        $fromuser = Auth::user()->uniqid;
        $message = new Message();
        $message->subject = $request->subject;
        $message->message = $request->message;
        $message->category_id = $request->category_id;
        $message->from_user_id = $fromuser;
        $message->has_attachment = !empty($request->files) && count($request->files) > 0;
        $message->save();
        if(!empty($request->files) && count($request->files) > 0) {

            $paths = Uploader::upload('files', 'public/uploads/messages/', 'message_attachment', true);
            if(count($paths) > 0) {
                foreach($paths as $path){
                    $attachment = new MessageAttachment();
                    $attachment->file = $path;
                    $attachment->message_id = $message->id;
                    $attachment->save();
                }
            }
        }
        $log = new MessageLog();
        $log->status = 1;
        $log->message_id = $message->id;
        $log->save();

        return response()->json('OK', 200);
    }

    public function show($id)
    {

    }

    public function reply(Request $request)
    {
        $parent = Message::findOrFail($request->id);
        $this->updateStatus($parent);
        $fromuser = Auth::user()->uniqid;
        $message = new Message();
        $message->subject = null;
        $message->message = $request->message;
        $message->category_id = $parent->category_id;
        $message->from_user_id = $fromuser;
        $message->to_user_id = $parent->to_user_id;
        $message->status = 4;
        $message->message_id = $parent->id;
        $message->has_attachment = !empty($request->files) && count($request->files) > 0;
        $message->save();
        if(!empty($request->files) && count($request->files) > 0) {

            $paths = Uploader::upload('files', 'public/uploads/messages/', 'message_attachment', true);
            if(count($paths) > 0) {
                foreach($paths as $path){
                    $attachment = new MessageAttachment();
                    $attachment->file = $path;
                    $attachment->message_id = $message->id;
                    $attachment->save();
                }
            }
        }
        return response()->json('OK', 200);
    }
    public function updateStatus($root) {
            $root->status = 4;
            $root->update();
            $log = new MessageLog();
            $log->status = 4;
            $log->message_id = $root->id;
            $log->save();
    }
    public function close($id) {
        $parent = Message::findOrFail($id);
        $parent->status = 5;
        $parent->update();
        return response()->json('OK', 200);
    }
}
