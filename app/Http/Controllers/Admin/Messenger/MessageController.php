<?php

namespace App\Http\Controllers\Admin\Messenger;

use App\Libraries\Upload\Uploader;
use App\ModelMessages\Message;
use App\ModelMessages\MessageAttachment;
use App\ModelMessages\MessageLog;
use App\ModelUser\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    private $path = 'admin.messenger.';
    public function index()
    {
        return view($this->path.'index');
    }

    /**
     * Get list of messages
     * messages are
     * root
     * if to user is NULL
     * if to user is the id of this admin
     */
    public function list()
    {
        $user =  Auth::guard('admin')->user()->uniqid;
        $list = Message::whereNull('to_user_id')
            ->orWhereIn('to_user_id', [$user])
            ->where('status' , '<', 5 )
            ->whereNull('message_id')
            ->paginate(200);

        foreach ($list as $item) {
            $item->fromUser = User::where('uniqid', $item->from_user_id)->select('name', 'surname')->first();
        }
        return response()->json($list, 200);
    }

    public function show($id)
    {
        $root = Message::findOrFail($id);
        $children = Message::where('message_id', $id)->get();
        if($root->status === 1) {
            $this->updateStatus($root);
        }
        $response = $root;
        $response->children = $children;
        return response()->json($response, 200);
    }

    public function post(Request $request) {
        $parent = Message::findOrFail($request->parent);
        $this->updateStatus($parent);
        $fromuser = Auth::guard('admin')->user()->uniqid;
        $message = new Message();
        $message->subject = null;
        $message->message = $request->message;
        $message->category_id = $parent->category_id;
        $message->from_user_id = $fromuser;
        $message->to_user_id = $parent->from_user_id;
        $message->status = 3;
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
    }

    public function updateStatus($root) {
        if($root->status === 1) {
            $user = Auth::guard('admin')->user()->uniqid;;
            $root->status = 2;
            $root->to_user_id = $user;
            $root->update();
            $log = new MessageLog();
            $log->status = 2;
            $log->message_id = $root->id;
            $log->save();
        } else if($root->status === 2 || $root->status === 3 || $root->status === 4) {
            $root->status = 3;
            $root->update();
            $log = new MessageLog();
            $log->status = 3;
            $log->message_id = $root->id;
            $log->save();
        }
    }
}
