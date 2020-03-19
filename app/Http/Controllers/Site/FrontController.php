<?php

namespace App\Http\Controllers\Site;
use App\Currencies;
use App\Http\Controllers\Controller;
use App\ModelCountry\Country;
use App\ModelNews\News;
use App\ModelSlider\Slider;
use App\ModelStaticPages\StaticPage;
use App\ModelStaticPages\StaticPageTranslate;
use App\RelUserProduct;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use App\Invoice;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Artisan;


class FrontController extends Controller
{
    public $seo;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('auth:web');
        $this->seo = \Lang::get('seo');
        $this->getMetaContent('home');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    //OK
    public function index()
    {
        $news = News::with('translates')->limit(3)->orderBy('id', 'DESC')->get();

        $sliders = Slider::limit(3)->orderBy('created_at', 'DESC')->get();

        return view('site.home', compact('news', 'sliders'));
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
    public function userRules()
    {
        echo "1";
        $data = DB::table('faqs as f')
            ->select('*')
            ->leftJoin('faq_translates as fq','f.id','=','fq.faq_id')
            ->where('f.type','1')
            ->where('fq.locale',Lang::getLocale())
            ->orderBy('f.created_at', 'DESC')
            ->get();
        return view('site.static_pages.qaydalar',compact('data'));
    }
    public function userQuestions()
    {
        $data = DB::table('faqs as f')
            ->select('*')
            ->leftJoin('faq_translates as fq','f.id','=','fq.faq_id')
            ->where('f.type','2')
            ->where('fq.locale',Lang::getLocale())
            ->get();
        return view('site.static_pages.suallar',compact('data'));
    }

    public function staticPages($slug)
    {
        $staticPageTranslate = StaticPageTranslate::where('slug', $slug)->first();
        //dd($staticPageTranslate->static_page_id );

        $staticPage = null;
        $pageContent = null;
        if (!empty($staticPageTranslate)) {
            $staticPage = StaticPage::findOrFail($staticPageTranslate->static_page_id);
            $staticPageTranslate = StaticPageTranslate::where('static_page_id', $staticPage->id)->where('locale', Lang::getLocale())->first();
            if (!empty($staticPageTranslate)){
                $pageContent = app('App\Http\Controllers\Admin\StaticPages\ContentStaticPagesController')->bySlug($staticPageTranslate->static_page_id);

                return view('site.static_pages.page', compact('staticPageTranslate', 'staticPage', 'pageContent'));
            }
            abort(404);

        }
        abort(404);
    }
    public function newsIn($id) {
        \Artisan::call('cache:clear');
        \Artisan::call('view:clear');
        $news = News::with('translates')->find($id);
        $allNews=News::with('translates')->orderBy('created_at',"DESC")->limit(10)->get();

        return view('site.newsIn', compact('news','allNews'));
    }

    public function paymentSuccess(Request $request)
    {
        if(isset($_SERVER['HTTP_REFERER'])) {
            $ref = $_SERVER["HTTP_REFERER"];
        }
        else
        {
            $ref = "empty";
        }

        $cookie_name = "ui";
        if(!isset($_COOKIE[$cookie_name])) {
            $user_id = 0;

        } else {
            $user_id = $_COOKIE[$cookie_name];
        }


        if ($user_id==0 and $request->session()->exists('uid')==true) {
            $user_id = $request->session()->get('uid', '0');
        }

        $rel_id = 0;
        if($user_id>0 and $ref =='https://www.paytr.com/odeme/pos.pay'){
            $rel = RelUserProduct::where("user_id",$user_id)->where('is_paid',0)->orderBy("id","DESC")->first();
            if($rel!=null){
                DB::table('products')
                    ->where('user_id', $user_id)
                    ->where('rel_user_product_id', $rel->id)
                    ->update(['not_basket' => 1]);

                $rel->response_payment = 'Payed';
                $rel->save();

                $rel_id = $rel->id;
            }
        }

        DB::table("yusif_test")->insert([
            "user_id" => $user_id,
            "ref" => $ref,
            "rel_id" => $rel_id,
            "date" => date("Y-m-d H:i:s")
        ]);

        $message = 'Ödənişiniz uğurla tamamlanmışdır!';
        return view('front.paymentSuccess', compact('message'));
    }


    public function success(){
        return view('site.success');
    }

    public function passwordSent(){
        return view('site.password-sent');
    }

    public function paymentSuccessBalance()
    {
        $message = 'Ödənişiniz uğurla tamamlanmışdır!';
        return view('front.paymentSuccess', compact('message'));
    }

    public function getCurrency($type)
    {
        $currency=Currencies::where('name',$type)->first();
        return response()->json(
            $currency->val
        );
    }

    public function getMetaContent($menu){
        $meta = [];
        $meta['title'] = isset($this->seo['title'][$menu]) ? $this->seo['title'][$menu]: $this->seo['title']['home'];
        $meta['description'] = isset($this->seo['description'][$menu]) ? $this->seo['description'][$menu]: $this->seo['description']['home'];
        $meta['keywords'] = isset($this->seo['keywords'][$menu]) ? $this->seo['keywords'][$menu]: $this->seo['keywords']['home'];
        View::share('meta', $meta);
    }


}
