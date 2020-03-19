<?php

namespace App\Http\Controllers\Front;
use App\Http\Controllers\Controller;
use App\ModelCountry\Country;
use App\ModelNews\News;
use App\ModelSlider\Slider;
use App\ModelStaticPages\StaticPage;
use App\ModelStaticPages\StaticPageTranslate;
use Illuminate\Support\Facades\Lang;
use App\Invoice;
use Illuminate\Support\Facades\DB;


class FrontController extends Controller
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

        $news = News::with('translates')->limit(3)->orderBy('id', 'DESC')->get();
        $tariffsForTr = Country::where('prefix', '=', 'tr')->with('translates', 'tariffs', 'tariffs.translates')->first();
        $tariffsForUS = Country::where('prefix', '=', 'us')->with('translates', 'tariffs', 'tariffs.translates')->first();
        $sliders = Slider::with('translates')->limit(3)->orderBy('id', 'DESC')->get();
        return view('front/front', compact('news', 'tariffsForTr', 'sliders','tariffsForUS' ));
    }

    public function anbar()
    {
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
//            ->select('products.product_type_name as name', DB::raw('SUM(products.price) as price'), DB::raw('SUM(products.quantity) as quantity'))
            ->select('products.id','products.product_type_name as name','products.quantity','products.price')
            ->leftJoin('products','products.id', '=', 'invoices.product_id')
//            ->leftJoin('product_types','product_types.id', '=', 'products.product_type_id')
            ->where('invoices.status_id','=','3')
            ->where('invoices.active','=','1')
            ->groupBy('products.product_type_name')
            ->orderBy('invoices.created_at','DESC')
            ->get();

//        dd($data);
        $sum_price = DB::table('invoices as i')
            ->select(DB::raw('SUM(p.price) as price'))
            ->join('products as p','p.id', '=', 'i.product_id')
            ->where('i.status_id','=',3)
            ->where('i.active','=',1)
            ->first()->price;

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
        return view('anbar1',compact('data','euro','sum_price'));
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

    public function success(){
        return view('front.new.success');
    }

    public function passwordSent(){
        return view('front.new.password-sent');
    }
}
