<?php
namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\ModelCountry\Country;
use App\ModelShop\ShopCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\View;

class ShopsController extends Controller
{
    public $seo;

    public function __construct()
    {
        // $this->middleware('auth:web');
        $this->seo = \Lang::get('seo');
        $this->getMetaContent('shop');
    }

    public function getMetaContent($menu){
        $meta = [];
        $meta['title'] = isset($this->seo['title'][$menu]) ? $this->seo['title'][$menu]: $this->seo['title']['home'];
        $meta['description'] = isset($this->seo['description'][$menu]) ? $this->seo['description'][$menu]: $this->seo['description']['home'];
        $meta['keywords'] = isset($this->seo['keywords'][$menu]) ? $this->seo['keywords'][$menu]: $this->seo['keywords']['home'];
        View::share('meta', $meta);
    }

    public function index($country) {
        \Artisan::call('cache:clear');
        \Artisan::call('view:clear');
        $cat=Input::get('cat','');
        $country_id = Country::where('prefix',$country)->pluck('id')->first();

        $shops = DB::table('shops as s')->where('s.country_id',(int) $country_id);
        if($cat != null) $shops=$shops->leftJoin('shops_types_rel as str','str.shop_id','=','s.id')->where('str.type_id',$cat);
        $shops=$shops->get();

        $types = DB::table('shop_types as st')
            ->select('st.id','stt.name')
            ->leftJoin('shop_type_translates as stt','stt.shop_type_id','=','st.id')
            ->where('stt.locale','=',Lang::getLocale())
            ->orderBy('st.created_at','DESC')
            ->get();
        //$shop_categories = ShopCategory::where('locale',Lang::getLocale())->get();
        return view('site.shop.index', compact('shops','country','types'));
    }

}