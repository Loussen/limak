<?php

namespace App\Http\Controllers\Admin;

use App\ModelPermissions\Api;
use App\ModelPermissions\RelRoleApi;
use App\ModelPermissions\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class RolesController extends Controller
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
            $data = Role::orderBy('id', 'desc')->paginate($this->limit);
        }
        return view('admin.roles.index', compact('data', 'params'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = null;
        $apis = Api::all();
        return view('admin.roles.form', compact('data', 'apis'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       $apis =  isset($request->apis)? $request->apis: null;
        $image = null;
        $model = new Role();
        $response = crud($model, $request, ['name'], $image);
        if(isset($apis)) {
            foreach($apis as $k => $v){
                $relRoleApi = new RelRoleApi();
                $relRoleApi->api_id = $v;
                $relRoleApi->role_id = $response['id'];
                $relRoleApi->save();
            }
        }
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
        $data = Role::find($id);
        $apis = Api::with('relRolesApis')->get();
        return view('admin.roles.form', compact('data', 'id', 'apis'));
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
        $apis =  isset($request->apis)? $request->apis: null;
        $apisupdate =  isset($request->apisUpdate)? $request->apisUpdate: null;
        $image = null;
        $model = Role::find($id);
        $response = crud($model, $request, ['name'], $image);
        if(isset($apisupdate)) {
            RelRoleApi::where('role_id', '=', $id)->whereNotIn('api_id', $apisupdate)->delete();
        }
        if(isset($apis)) {
            foreach($apis as $k => $v){
                $relRoleApi = new RelRoleApi();
                $relRoleApi->api_id = $v;
                $relRoleApi->role_id = $response['id'];
                $relRoleApi->save();
            }
        }
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
        $deletedId = Role::find($id)->delete();
        return back();
    }

    public function search ($q) {
        $p = [['id','<>', 0]];
        isset($q['id']) ? is_null($q['id'])?'':$p[] = ['id', '=', $q['id']] : '';
        isset($q['name']) ? is_null($q['name'])?'':$p[] = ['name', 'like', '%'.$q['name'].'%'] : '';
        $data = Role::where($p)->orderBy('id', 'desc')->paginate($this->limit);
        return $data;
    }
}
