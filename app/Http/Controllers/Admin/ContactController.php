<?php

namespace App\Http\Controllers\Admin;

use App\Contact;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use PhpParser\Node\Expr\BinaryOp\Concat;

class ContactController extends Controller
{
    private $limit = 10;
    public function index()
    {
        $params = [];
        if(Input::get('search') == "true"){
            $params = Input::get();
            $data = $this->search($params);
        }else{
            $data = Contact::orderBy('id', 'desc')->paginate($this->limit);
        }

        return view('admin.contact.index', compact('data', 'params'));
    }
    public function search ($q) {
        $p = [['id','<>', 0]];
        isset($q['id'])?$p[] = ['id', '=', $q['id']]:'';
        isset($q['name'])?$p[] = ['name', 'like', '%'.$q['name'].'%']:'';
        isset($q['email'])?$p[] = ['email', 'like', '%'.$q['email'].'%']:'';
        isset($q['phone'])?$p[] = ['phone', 'like', '%'.$q['phone'].'%']:'';
        isset($q['description'])?$p[] = ['description', 'like', '%'.$q['description'].'%']:'';
        $data = Contact::where($p)->orderBy('id', 'desc')->paginate($this->limit);
        return $data;
    }
}
