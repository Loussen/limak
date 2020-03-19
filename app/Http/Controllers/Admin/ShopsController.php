<?php

namespace App\Http\Controllers\Admin;

use App\ModelShop\Shop;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class ShopsController extends Controller
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
            $data = Shop::orderBy('id', 'desc')->paginate($this->limit);
        }

        return view('admin.shops.index', compact('data', 'params'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = null;
        return view('admin.shops.form', compact('data'));
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
        $model = new Shop();
        if ($request->file_values) {
            $image = setBase64Image($request->file_values, 'shop_', 'png', 'public/shops');
        }
        $response = crud($model, $request, ['name', 'url'], $image);

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
        $data = Shop::find($id);

        return view('admin.shops.form', compact('data', 'id'));
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
        $model = Shop::find($id);
        if ($request->file_values) {
            deleteImage($model->file);
            $image = setBase64Image($request->file_values, 'shop_', 'png', 'public/shops');
        }
        $response = crud($model, $request, ['name', 'url'], $image);

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
        $model = Shop::find($id);
        deleteImage($model->file);

        if ($model->delete()) {
            return back();
        }
    }

    public function search ($q) {
        $p = [['id','<>', 0]];
        isset($q['id']) ? is_null($q['id'])?'':$p[] = ['id', '=', $q['id']] : '';
        isset($q['name']) ? is_null($q['name'])?'':$p[] = ['name', 'like', '%'.$q['name'].'%'] : '';
        isset($q['link']) ? is_null($q['link'])?'':$p[] = ['url', 'like', '%'.$q['link'].'%'] : '';
        $data = Shop::where($p)->orderBy('id', 'desc')->paginate($this->limit);
        return $data;
    }
}
