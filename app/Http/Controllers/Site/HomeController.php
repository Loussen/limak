<?php

namespace App\Http\Controllers\Site;
use App\Http\Controllers\Controller;
use App\ModelCountry\Country;
use App\ModelNews\News;
use App\ModelSlider\Slider;
use App\ModelStaticPages\StaticPage;
use App\ModelStaticPages\StaticPageTranslate;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Lang;
use App\Invoice;
use Illuminate\Support\Facades\DB;
use App\Currencies;



class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('auth:web');

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $seo = \Lang::get('seo');
        var_dump($seo);
        $news = News::with('translates')->orderBy('id', 'DESC')->get();
        $tariffsForTr = Country::where('prefix', '=', 'tr')->with('translates', 'tariffs', 'tariffs.translates')->first();
        $sliders = Slider::with('translates')->limit(3)->orderBy('created_at', 'DESC')->get();

        return view('front/home', compact('news', 'tariffsForTr', 'sliders', 'seo'));
    }

    public function getCurrency($type)
    {
        $currency=Currencies::where('name',$type)->first();
        return response()->json(
            $currency->val
        );
    }

    public function anbar()
    {

        $country = Country::where('prefix','tr')->first();


        $fatura_id = Input::get("id",0);

        $html =  file_get_contents('https://www.turkiye.gov.tr/doviz-kurlari');

        $dom = new \domDocument;

//        libxml_use_internal_errors(true);
//        libxml_use_internal_errors(false);
        @$dom->loadHTML($html);
        $dom->preserveWhiteSpace = false;

        $tables = $dom->getElementsByTagName('tbody');

        $rows = $tables->item(0)->getElementsByTagName('tr');
        $row = $rows[3];
        $cols = $row->getElementsByTagName('td');
        $euro =  $cols->item(1)->nodeValue;


        $data= DB::table('invoices')
            ->select('products.product_type_name as name', DB::raw('SUM(products.price) as price'), DB::raw('SUM(products.quantity) as quantity'))
//            ->select('products.id','products.product_type_name as name','products.quantity','products.price')
            ->leftJoin('products','products.id', '=', 'invoices.product_id')
//            ->leftJoin('product_types','product_types.id', '=', 'products.product_type_id')
            ->where('invoices.status_id','=','2');


            if($fatura_id>0){
                $data = $data->where("invoices.fatura_id",$fatura_id);
            }
            $data = $data->where('invoices.active','=','1')
            ->where('invoices.country_id','=',$country->id)
            ->groupBy('products.product_type_name')
            ->orderBy('invoices.created_at','DESC')
            ->get();

//        $sum_price = DB::table('invoices as i')
//            ->select(DB::raw('SUM(p.price) as price'))
//            ->join('products as p','p.id', '=', 'i.product_id')
//            ->where('i.status_id','=',2)
//            ->where('i.active','=',1)
//            ->first()->price;

//        foreach ($data as $datum) {
//            var_dump($datum->name);
//        }

//        $data = Invoice::where('active', '=', '1')->where('status_id','=','3')
//            ->sum('products.quantity')
//            ->sum('products.price')
//            ->with('products.productTypes')
//            ->with('products')
//            ->groupby('productTypes.name')
//            ->orderBy('created_at', 'DESC')->get();
//        dd($data);
        return view('tr.anbar1',compact('data','euro'));
    }
    public function anbarUsa()
    {

        $country = Country::where('prefix','usa')->first();

        $html =  file_get_contents('https://www.turkiye.gov.tr/doviz-kurlari');

        $dom = new \domDocument;

        @$dom->loadHTML($html);
        $dom->preserveWhiteSpace = false;

        $tables = $dom->getElementsByTagName('tbody');

        $rows = $tables->item(0)->getElementsByTagName('tr');
        $row = $rows[3];
        $cols = $row->getElementsByTagName('td');
        $euro =  $cols->item(1)->nodeValue;


        $data= DB::table('invoices')
            ->select('products.product_type_name as name', DB::raw('SUM(products.price) as price'), DB::raw('SUM(products.quantity) as quantity'))
            ->leftJoin('products','products.id', '=', 'invoices.product_id')
            ->where('invoices.status_id','=','2')
            ->where('invoices.active','=','1')
            ->where('invoices.country_id','=',$country->id)
            ->groupBy('products.product_type_name')
            ->orderBy('invoices.created_at','DESC')
            ->get();

        return view('usa.anbar_usa',compact('data','euro','sum_price'));
    }

    public function staticPages($slug)
    {
        $staticPageTranslate = StaticPageTranslate::where('slug', $slug)->first();

        $staticPage = null;
        $pageContent = null;
        if (!empty($staticPageTranslate)) {
            $staticPage = StaticPage::findOrFail($staticPageTranslate->static_page_id);
            $staticPageTranslate = StaticPageTranslate::where('static_page_id', $staticPage->id)->where('locale', Lang::getLocale())->first();
$pageContent = app('App\Http\Controllers\Admin\StaticPages\ContentStaticPagesController')->bySlug($staticPageTranslate->static_page_id);

            return view('front.static_pages.page', compact('staticPageTranslate', 'staticPage', 'pageContent'));
        }
        abort(404);
    }
}
