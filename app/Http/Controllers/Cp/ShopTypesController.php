<?php

namespace App\Http\Controllers\Cp;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;

class ShopTypesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = DB::table('shop_types as st')
            ->select('st.id','stt.locale','stt.shop_type_id',DB::raw('GROUP_CONCAT(stt.name) as names'))
            ->leftJoin('shop_type_translates as stt','stt.shop_type_id','=','st.id')
            ->orderBy('st.created_at','DESC')
            ->groupBy('st.id')
            ->get();

        return view('cp.shop_types.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data=null;
        return view('cp.shop_types.form',compact('data'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $id = DB::table('shop_types')->insertGetId(['type'=>'']);

        DB::table('shop_type_translates')->insert([
            ['shop_type_id' => $id, 'locale'=>'az', 'name' => $request->name_az],
            ['shop_type_id' => $id, 'locale'=>'ru', 'name' => $request->name_ru],
        ]);
        return redirect('cp/shop-types');
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
        $data = DB::table('shop_types as st')
            ->select('st.id','stt.locale','stt.name','stt.shop_type_id')
            ->leftJoin('shop_type_translates as stt','stt.shop_type_id','=','st.id')
            ->where('st.id',$id)
            ->orderBy('st.created_at','DESC')
            ->get();
        return view('cp.shop_types.form',compact('data','id'));
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
        DB::table('shop_type_translates')
            ->where('shop_type_id', $id)
            ->where('locale', 'az')
            ->update(['name' => $request->name_az]);
        DB::table('shop_type_translates')
            ->where('shop_type_id', $id)
            ->where('locale', 'ru')
            ->update(['name' => $request->name_ru]);
        return redirect('cp/shop-types');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('shop_types')->where('id',  '=',$id)->delete();
        DB::table('shop_type_translates')->where('shop_type_id',  '=',$id)->delete();
        return redirect('cp/shop-types');

    }
}
