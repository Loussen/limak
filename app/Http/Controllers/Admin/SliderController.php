<?php

namespace App\Http\Controllers\Admin;

use App\ModelSlider\Slider;
use App\ModelSlider\SliderTranslate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $limit = 10;
    public function index()
    {
        $params = [];
        if(Input::get('search') == "true"){
            $params = Input::get();
            $data = $this->search($params);
        }else{
            $data = Slider::with('translates')->orderBy('id', 'desc')->paginate($this->limit);
        }

        return view('admin.slider.index', compact('data', 'params'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = null;
        return view('admin.slider.form', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $image = null;
        $model = new Slider();
        $translateModel = new SliderTranslate();

        if($request->file)
        {
            $image   = uploadImage($request->file, 'slider_', 'png', 'public/slider');
        }

        if($request->mobile_file){
            $mobileImage = uploadImage($request->mobile_file, 'slider_', 'png', 'public/slider/mobile');
            $model->mobile_file = $mobileImage;
        }

        $response = crud($model, $request, null, $image, ['fields' => ['name'], 'modelName' => $translateModel], 'slider_id');

        if ($response['status']) {
            Session::flash('message_success', 'Məlumat əlavə olundu!');
            Session::flash('alert-class', 'alert-success');
            return back();
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
        $data = Slider::find($id);

        return view('admin.slider.form', compact('data', 'id'));
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
        $model = Slider::find($id);
        $translateModel = $model->translates;

        if($request->file)
        {
            deleteImage($model->file);
            $image = uploadImage($request->file, 'slider_', 'png', 'public/slider');
        }

        if($request->mobile_file) {
            deleteImage($model->mobile_file);
            $mobileImage = uploadImage($request->mobile_file, 'slider_', 'png', 'public/slider/mobile');
            $model->mobile_file = $mobileImage;
        }

        $response = crud($model, $request, null, $image, ['fields' => ['name'], 'modelName' => $translateModel], 'slider_id', 'update');

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
        $model = Slider::find($id);

        deleteImage($model->file);
        deleteImage($model->mobile_file);

        SliderTranslate::where('slider_id','=',$id)->delete();

        if ($model->delete()) {
            return back();
        }
    }

    public function search ($q) {
        $cp = [['locale', '=', 'az']];
        $p = [['id','<>', 0]];
        isset($q['id'])?$p[] = ['id', '=', $q['id']]:'';
        isset($q['name'])?$cp[] = ['name', 'like', '%'.$q['name'].'%']:'';
        $data = Slider::where($p)->with('translates')->whereHas('translates', function($query) use ($cp) {
            $query->where($cp);
        })->orderBy('id', 'desc')->paginate($this->limit);
        return $data;
    }
}
