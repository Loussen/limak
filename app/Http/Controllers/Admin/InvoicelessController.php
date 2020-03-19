<?php

namespace App\Http\Controllers\Admin;

use App\Invoiceless;
use App\ModelUser\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class InvoicelessController extends Controller
{
    private $invoiceless;
    private $path = 'admin.invoiceless.';
    public function __construct(Invoiceless $invoiceless)
    {
        $this->invoiceless = $invoiceless;
    }

    public function index()
    {
        $list = $this->invoiceless
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        return view($this->path.'index', compact('list'));
    }

    public function store(Request $request)
    {
        $invoiceless = new Invoiceless();

        $invoiceless->note = $request->note;
        $invoiceless->user_uid = $request->user_uid;

        $invoiceless->save();

        return back();
    }

    public function send($id, Request $request) {
        $user = User::where('uniqid', $request->uid)->first();
        $invoiceless = Invoiceless::findOrFail($id);
        notify((object)['template' => 'invoice-id-absent', 'text' => $request->note], (object)['phone' => $user->userContacts[0]->name, 'email' => $user->email]);
        $invoiceless->sent = true;
        $invoiceless->sent_date = \Carbon\Carbon::now();
        $invoiceless->update();
        if($request->ajax()) {
            return response()->json('OK', 200);
        } else {
            return back();
        }
    }

    public function done($id)
    {
        $i = Invoiceless::findOrFail($id);
        $i->is_active = false;
        $i->update();
        return back();
    }

}
