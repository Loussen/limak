<?php

namespace App\Http\Controllers\Site;

use App\Contact;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public $seo;

    public function __construct()
    {
        // $this->middleware('auth:web');
        $this->seo = \Lang::get('seo');
        $this->getMetaContent('about');
    }

    public function getMetaContent($menu){
        $meta = [];
        $meta['title'] = isset($this->seo['title'][$menu]) ? $this->seo['title'][$menu]: $this->seo['title']['home'];
        $meta['description'] = isset($this->seo['description'][$menu]) ? $this->seo['description'][$menu]: $this->seo['description']['home'];
        $meta['keywords'] = isset($this->seo['keywords'][$menu]) ? $this->seo['keywords'][$menu]: $this->seo['keywords']['home'];
        View::share('meta', $meta);
    }

    public function index()
    {
        \Artisan::call('cache:clear');
        \Artisan::call('view:clear');
        return view('site.contact');
    }

    public function store(Request $request)
    {
        $validation = [
            'name' => 'required',
            'email' => 'required',
            'description' => 'required',
        ];
        $request->validate($validation);
        $contact = Contact::create($request->all());
        Mail::to('info@limak.az')->send( new \App\Mail\Contact($contact));
        return redirect()->back()->with('message', Lang::get('common.success'));
    }

}
