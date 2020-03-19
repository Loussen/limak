<?php

namespace App\Http\Controllers\Cp;
use App\ModelNews\News;
use App\ModelNews\NewsFile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use App\ModelNews\NewsTranslate;

class NewsController extends Controller
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
            $data = News::with('translates')->whereHas('translates', function($query) {
                $query->where('locale', '=', 'az');
            })->orderBy('id', 'desc')->paginate($this->limit);
        }

        return view('cp.news.index', compact('data', 'params'));
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
        return view('cp.news.form', compact('data', 'imagesUrl', 'imagesConfig'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name.az' => 'required',
        ]);
        $model = new News();
        $is_homepage = $request->homepage ?   1: 0;
        $model->homepage = $is_homepage;
        $translateModel = 'App\ModelNews\NewsTranslate';
        $image = setBase64Image($request->file_values, 'shop_', 'png', 'public/news');
        if(!empty($image))
        {
            $response = crud($model, $request, null, $image, ['fields' => ['name', 'description', 'content', 'keywords'], 'modelName' => $translateModel], 'news_id');
            if($files=$request->file('images')){
                foreach($files as $file){
                    $imagePath = Storage::putFile('public/news_files', $file);
                    $newsImages = new NewsFile();
                    $newsImages->news_id = $response['id'];
                    $newsImages->name = $imagePath;
                    $newsImages->save();
                }
            }
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
        $data = News::find($id);
        $url = Storage::url($data->file);
        $imagesUrl = [];
        $imagesConfig = [];
        foreach ($data->files as $img){
            $imagesUrl[] = Storage::url($img->name);
            $imagesConfig[] = ['size' => '222','showRemove' => true,'width' => '120px', 'key' => $img->id];
        }
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
        //dd($request);
        $image = null;
        $model = News::find($id);
        $is_homepage = $request->homepage ?   1: 0;
        $model->homepage = $is_homepage;
        if ($request->file_values) {
            deleteImage($model->file);
            $image = setBase64Image($request->file_values, 'news_', 'png', 'public/news');
        }
        $translateModel = $model->translates;
        //dd($translateModel );
        $response = crud($model, $request, null, $image, ['fields' => ['name', 'description','content','keywords'], 'modelName' => $translateModel], 'news_id', 'update');

        if($files=$request->file('images')){
            foreach($files as $file){
                $imagePath = Storage::putFile('public/news_files', $file);
                $newsImages = new NewsFile();
                $newsImages->news_id = $response['id'];
                $newsImages->name = $imagePath;
                $newsImages->save();
            }
        }
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
        $model = News::find($id);
//      $translateModel = 'App\ModelNews\NewsTranslate';

        deleteImage($model->file);
        foreach ($model->files as $k => $v) {
            deleteImage($v->name);
        }

        NewsTranslate::where('news_id','=',$id)->delete();
        NewsFile::where('news_id','=',$id)->delete();

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
        $data = News::where($p)->with('translates')->whereHas('translates', function($query) use ($cp) {
            $query->where($cp);
        })->orderBy('id', 'desc')->paginate($this->limit);
        return $data;
    }

    public function deleteFile() {
        $id = Input::get('key');
        $data = NewsFile::find($id);
        Storage::delete($data->name);
        NewsFile::destroy($id);
        return response()->json(['code' => 200]);
    }
}
