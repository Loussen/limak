<?php
namespace App\Http\Controllers\Site;
use Illuminate\Support\Facades\View;

use App\Http\Controllers\Controller;
use Artisan;

class CountryController extends Controller
{

    public $seo;

    public function __construct()
    {
        // $this->middleware('auth:web');
        $this->seo = \Lang::get('seo');
        $this->getMetaContent('country');
    }

    public function index() {
        \Artisan::call('cache:clear');
        \Artisan::call('view:clear');
        return view('site/country/index');
    }


    public function getMetaContent($menu){
        $meta = [];
        $meta['title'] = isset($this->seo['title'][$menu]) ? $this->seo['title'][$menu]: $this->seo['title']['home'];
        $meta['description'] = isset($this->seo['description'][$menu]) ? $this->seo['description'][$menu]: $this->seo['description']['home'];
        $meta['keywords'] = isset($this->seo['keywords'][$menu]) ? $this->seo['keywords'][$menu]: $this->seo['keywords']['home'];
        View::share('meta', $meta);
    }


}
