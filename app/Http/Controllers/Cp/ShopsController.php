<?php

namespace App\Http\Controllers\Cp;

use App\ModelShop\Shop;
use App\ModelCountry\Country;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;

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
        $country = Country::get();
        $params = Input::get();

        if (Input::get('search') == "true") {
            $params = Input::get();

            $data = $this->search($params);
        } else {
            $data = Shop::where('country_id', $params['country_id'])->orderBy('id', 'desc')->paginate($this->limit);
        }
        return view('cp.shops.index', compact('data', 'params', 'country'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $c_id = Input::get('country_id', 1);
        $countries = Country::all();
        $types= $data = DB::table('shop_types as st')
            ->select('st.id','stt.name')
            ->leftJoin('shop_type_translates as stt','stt.shop_type_id','=','st.id')
            ->orderBy('st.created_at','DESC')
            ->groupBy('st.id')
            ->get();
        $data = null;
        return view('cp.shops.form', compact('data', 'countries', 'c_id','types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $types = $request->types;

        $image = null;
        $model = new Shop();

//        $response = crud($model, $request, ['name', 'url'], $image);

        $model->name = $request->name;
        $model->url = $request->url;
        $model->country_id = $request->country_id;
        $model->save();

        if($request->hasFile('file')){
            $request->file('file')->storeAs(
                'public/shops',(int) $model->id.".png"
            );
        }

        for ($i = 0; $i < count($types); $i++) {
            DB::table('shops_types_rel')->insert(
                ['shop_id' => $model->id, 'type_id' => $types[$i]]
            );
        }
//        if ($response['status']) {
        return redirect()->back();
//        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $types= $data = DB::table('shop_types as st')
            ->select('st.id','stt.name')
            ->leftJoin('shop_type_translates as stt','stt.shop_type_id','=','st.id')
            ->orderBy('st.created_at','DESC')
            ->groupBy('st.id')
            ->get();
        $data = DB::table('shops as s')
            ->select('s.*',
                DB::raw('GROUP_CONCAT(str.type_id) as types')
            )
            ->leftJoin('shops_types_rel as str', 'str.shop_id', '=', 's.id')
            ->where('s.id', $id)
            ->groupBy('str.shop_id')
            ->first();

        $countries = Country::all();
        $c_id = Input::get('country_id');

        return view('cp.shops.form', compact('data', 'id', 'countries', 'c_id','types'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $types = $request->types;
        $del_types = explode(',', $request->deleteTypes);

        $image = null;
        $model = Shop::find($id);
        if($request->hasFile('file')){
            $request->file('file')->storeAs(
                'public/shops',(int) $model->id.".png"
            );
        }
//        $response = crud($model, $request, ['name', 'url'], $image);
//        if ($request->file_values) {
//            $image = setBase64Image($request->file_values, 'shop_', 'png', 'public/shops');
//        }
//        $response = crud($model, $request, ['name', 'url'], $image);

        $model->name = $request->name;
        $model->url = $request->url;
        $model->country_id = $request->country_id;
        $model->save();

        if ($del_types != null) {
            for ($i = 0; $i < count($del_types); $i++) {
                DB::table('shops_types_rel')->where('type_id', $del_types[$i])->delete();
            }
        }
        if ($types != null) {
            for ($i = 0; $i < count($types); $i++) {
                DB::table('shops_types_rel')->updateOrInsert(
                    ['shop_id' => $id, 'type_id' => $types[$i]],
                    ['type_id' => $types[$i]]
                );
            }
        }
//        if ($response['status']) {
        return redirect('/cp/shops?country_id=' . $request->country_id);
//        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
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

    public function search($q)
    {
        $p = [['id', '<>', 0]];
        isset($q['id']) ? is_null($q['id']) ? '' : $p[] = ['id', '=', $q['id']] : '';
        isset($q['name']) ? is_null($q['name']) ? '' : $p[] = ['name', 'like', '%' . $q['name'] . '%'] : '';
        isset($q['link']) ? is_null($q['link']) ? '' : $p[] = ['url', 'like', '%' . $q['link'] . '%'] : '';
        $data = Shop::where($p)->orderBy('id', 'desc')->paginate($this->limit);
        return $data;
    }
}
