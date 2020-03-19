<?php

namespace App\Http\Controllers\Admin;

use App\ModelCountry\Country;
use App\ModelCountry\CountryTranslate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class CountriesController extends Controller
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
            $data = Country::with('translates')->whereHas('translates', function($query) {
                $query->where('locale', '=', 'az');
            })->orderBy('id', 'desc')->paginate($this->limit);
        }

        return view('admin.countries.index', compact('data', 'params'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = null;
        return view('admin.countries.form', compact('data'));
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
        $model = new Country();
        $translateModel = new CountryTranslate();

        if ($request->file_values) {
            $image = setBase64Image($request->file_values, 'country_flag_', 'png', 'public/countries');
        }

        $response = crud($model, $request, null, $image, ['fields' => ['name'], 'modelName' => $translateModel], 'country_id');

        if ($response['status']) {
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
        $data = Country::find($id);

        return view('admin.countries.form', compact('data', 'id'));
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
        $model = Country::find($id);
        $translateModel = $model->translates;

        if ($request->file_values) {
            deleteImage($model->file);
            $image = setBase64Image($request->file_values, 'country_flag_', 'png', 'public/countries');
        }

        $response = crud($model, $request, null, $image, ['fields' => ['name'], 'modelName' => $translateModel], 'country_id', 'update');

        if ($response['status']) {
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
        $model = Country::find($id);
        deleteImage($model->file);

        if ($model->delete()) {
            return back();
        }
    }

    public function search ($q) {
        $cp = [['locale', '=', 'az']];
        $p = [['id','<>', 0]];
        isset($q['id'])?$p[] = ['id', '=', $q['id']]:'';
        isset($q['name'])?$cp[] = ['name', 'like', '%'.$q['name'].'%']:'';
        $data = Country::where($p)->with('translates')->whereHas('translates', function($query) use ($cp) {
            $query->where($cp);
        })->orderBy('id', 'desc')->paginate($this->limit);
        return $data;
    }
}
