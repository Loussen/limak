<?php

namespace App\Http\Controllers\Admin\StaticPages;

use App\Libraries\Upload\Uploader;
use App\ModelStaticPages\StaticPage;
use App\ModelStaticPages\StaticPageTranslate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class PagesController extends Controller
{
    private $path;
    private $staticPage;
    public function __construct(StaticPage $staticPage)
    {
        $this->path = 'admin.static-pages.';
        $this->staticPage = $staticPage;
    }

    /**
     * Show Page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view($this->path.'static-page-list');
    }

    /**
     * Get List
     * @return \Illuminate\Http\JsonResponse
     */
    public function list() {
        $list = $this->staticPage->join('static_page_translates', 'static_page_translates.static_page_id', '=', 'static_pages.id')->get();
        foreach ($list as $page) {
            $page->file = asset(Storage::url($page->thumnail));
        }
        return response()->json($list, 200);
    }

    /**
     * Insert Static page data
     * @param Request $request
     * @param StaticPage $staticPage
     * @param StaticPageTranslate $staticPageTranslate
     * @return \Illuminate\Http\JsonResponse
     */
    public function insert(Request $request, StaticPage $staticPage) {
        if (!empty($request->az['file']) && !is_null($request->az['file'])) {
            $staticPage->thumnail = Uploader::upload($request->az['file'],'public/uploads/static-pages/','sp');
        }
        if (!empty($request->label) && !is_null($request->label)) {
            $staticPage->label = $request->label;
        }

        $staticPage->save();

        // AZ
        $staticPageTranslate = new StaticPageTranslate();
        $staticPageTranslate->name = $request->az['name'];
        $staticPageTranslate->description = $request->az['description'];
        $staticPageTranslate->slug = $request->az['slug'];
        $staticPageTranslate->static_page_id = $staticPage->id;
        $staticPageTranslate->locale = 'az';
        $staticPageTranslate->save();
        // RU
        if( !empty($request->ru) && !empty($request->ru['name']) ) {
            $staticPageTranslate = new StaticPageTranslate();
            $staticPageTranslate->name = $request->ru['name'];
            $staticPageTranslate->description = $request->ru['description'];
            $staticPageTranslate->slug = $request->ru['slug'];
            $staticPageTranslate->static_page_id = $staticPage->id;
            $staticPageTranslate->locale = 'ru';
            $staticPageTranslate->save();
        }

        return response()->json($staticPage, 200);
    }
    /**
     * Insert Static page data
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request) {
        $staticPage = StaticPage::findOrFail((int)$request->az['id']);
        $staticPage->label = $request->label;
        if (isset($request->az['file']) && !empty($request->az['file']) && !is_string($request->az['file']) && !is_null($staticPage->file)) {
            Storage::delete($staticPage->file);
            $staticPage->thumnail = Uploader::upload($request->az['file'],'public/uploads/static-pages/','sp');
        } else if(isset($request->az['file']) && !empty($request->az['file']) && !is_string($request->az['file'])){
            $staticPage->thumnail = Uploader::upload($request->az['file'],'public/uploads/static-pages/','sp');
        }
        $staticPage->update();

        // AZ
        $staticPageTranslate = StaticPageTranslate::findOrFail((int)$request->az['trId']);
        $staticPageTranslate->name = $request->az['name'];
        $staticPageTranslate->description = $request->az['description'];
        $staticPageTranslate->slug = $request->az['slug'];
        $staticPageTranslate->slug = $request->az['slug'];
        $staticPageTranslate->static_page_id = $staticPage->id;
        $staticPageTranslate->locale = 'az';
        $staticPageTranslate->update();
        // RU
        if( !empty($request->ru) && !empty($request->ru['name']) ) {
            $staticPageTranslate = StaticPageTranslate::findOrFail((int)$request->ru['trId']);
            $staticPageTranslate->name = $request->ru['name'];
            $staticPageTranslate->description = $request->ru['description'];
            $staticPageTranslate->slug = $request->ru['slug'];
            $staticPageTranslate->static_page_id = $staticPage->id;
            $staticPageTranslate->locale = 'ru';
            $staticPageTranslate->update();
        }

        return response()->json($staticPage, 200);
    }

    /**
     * Get Static page data by id
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id) {
        $item = $this->staticPage
            ->join('static_page_translates', 'static_page_translates.static_page_id', '=', 'static_pages.id')
            ->where('static_page_id', $id)
            ->get();
        $item[0]->file = asset(Storage::url($item[0]->thumnail));
        return response()->json($item, 200);
    }
}
