<?php

namespace App\Http\Controllers\Cp;
use App\ModelNews\PagesFile;
use App\ModelPages\Pages;
use App\ModelPages\PagesTranslate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;

class PagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public $limit = 10;
    public function index()
    {
        $params = [];
        if(Input::get('search') == "true"){
            $params = Input::get();
            $data = $this->search($params);
        }else{
            $data = Pages::with('translates')->whereHas('translates', function($query) {
                $query->where('locale', '=', 'az');
            })->orderBy('id', 'desc')->paginate($this->limit);
        }

        return view('cp.pages.index', compact('data', 'params'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = null;
        $url = null;
        $imagesUrl = [];
        $imagesConfig = [];
        return view('cp.pages.form', compact('data', 'imagesUrl', 'imagesConfig'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $model = new Pages();
        $translateModel = 'App\ModelPages\PagesTranslate';
        $image = setBase64Image($request->file_values, 'shop_', 'png', 'public/pages');
        if(!empty($image))
        {
            $response = crud($model, $request, null, $image, ['fields' => ['name', 'description', 'content', 'keywords'], 'modelName' => $translateModel], 'page_id');
            /*if($files=$request->file('images')){
                foreach($files as $file){
                    $imagePath = Storage::putFile('public/news_files', $file);
                    $newsImages = new PagesFile();
                    $newsImages->news_id = $response['id'];
                    $newsImages->name = $imagePath;
                    $newsImages->save();
                }
            }*/
            if ($response['status']) {
                Session::flash('message_success', 'Məlumat əlavə olundu!');
                Session::flash('alert-class', 'alert-success');
                return back();
            }
        }else{
            Session::flash('message_error', 'Xəta! Əməliyyatın davam etməsi üçün şəkil yükləyin!');
            Session::flash('alert-class', 'alert-danger');
            return back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Pages::find($id);
        $url = Storage::url($data->file);
        $imagesUrl = [];
        $imagesConfig = [];
        /*foreach ($data->files as $img){
            $imagesUrl[] = Storage::url($img->name);
            $imagesConfig[] = ['size' => '222','showRemove' => true,'width' => '120px', 'key' => $img->id];
        }*/
        return view('cp.news.form', compact('data', 'id', 'imagesUrl', 'imagesConfig', 'url'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $image = null;
        $model = Pages::find($id);
        if ($request->file_values) {
            deleteImage($model->file);
            $image = setBase64Image($request->file_values, 'pages_', 'png', 'public/pages');
        }
        $translateModel = $model->translates;
        $response = crud($model, $request, null, $image, ['fields' => ['name', 'description'], 'modelName' => $translateModel], 'page_id', 'update');

        /*if($files=$request->file('images')){
            foreach($files as $file){
                $imagePath = Storage::putFile('public/news_files', $file);
                $newsImages = new PagesFile();
                $newsImages->news_id = $response['id'];
                $newsImages->name = $imagePath;
                $newsImages->save();
            }
        }*/
        if ($response['status']) {
            Session::flash('message_success', 'Məlumat redaktə olundu!');
            Session::flash('alert-class', 'alert-success');
            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = Pages::find($id);
//      $translateModel = 'App\ModelNews\NewsTranslate';

        deleteImage($model->file);
        /*foreach ($model->files as $k => $v) {
            deleteImage($v->name);
        }*/

        PagesTranslate::where('news_id','=',$id)->delete();
/*        PagesFile::where('news_id','=',$id)->delete();*/

        if ($model->delete()) {
            Session::flash('message_success', 'Məlumatlar silindi!');
            Session::flash('alert-class', 'alert-success');
            return back();
        }
    }

    public function search ($q) {
        $cp = [['locale', '=', 'az']];
        $p = [['id','<>', 0]];
        isset($q['id'])?$p[] = ['id', '=', $q['id']]:'';
        isset($q['name'])?$cp[] = ['name', 'like', '%'.$q['name'].'%']:'';
        isset($q['description'])?$cp[] = ['description', 'like', '%'.$q['description'].'%']:'';
        isset($q['created_at'])?$cp[] = ['created_at', 'like', '%'.$q['created_at'].'%']:'';
        $data = Pages::where($p)->with('translates')->whereHas('translates', function($query) use ($cp) {
            $query->where($cp);
        })->orderBy('id', 'desc')->paginate($this->limit);
        return $data;
    }

    public function deleteFile() {
        $id = Input::get('key');
/*        $data = PagesFile::find($id);*/
/*        Storage::delete($data->name);*/
        PagesFile::destroy($id);
        return response()->json(['code' => 200]);
    }
}
