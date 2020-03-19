<?php

namespace App\Http\Controllers\Cp;

use App\ModelPermissions\Api;
use App\ModelPermissions\Role;
use App\ModelPermissions\RelRoleApi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class ApisController extends Controller
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
        $methods = Api::API_METHODS;
        if(Input::get('search') == "true"){
            $params = Input::get();
            $data = $this->search($params);
        }else{
            $data = Api::orderBy('id', 'desc')->paginate($this->limit);
        }

        return view('cp.apis.index', compact('data', 'params', 'methods'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = null;
        $methods = Api::API_METHODS;
        $roles = Role::all();
        return view('cp.apis.form', compact('data', 'methods', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $model = new Api();
        $response = crud($model, $request, ['name', 'method', 'description']);

        if ($response['status']) {
            if (isset($request['roles'])) {
                $this->_addRoleApis($request['roles'], $response['id']);
            }
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
        $data = Api::with('relRolesApis')->find($id);
        $selectedRoles = [];
        foreach ($data->relRolesApis as $key => $value) {
            $selectedRoles[] = $value->role_id;
        }

        $methods = Api::API_METHODS;
        $roles = Role::all();

        return view('admin.apis.form', compact('data', 'id', 'methods', 'roles', 'selectedRoles'));
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
        $model = Api::with('relRolesApis')->find($id);
        $response = crud($model, $request, ['name', 'method', 'description']);

        $selectedRoles = isset($request['roles']) ? $request['roles'] : [];

        $currentRoles = [];
        foreach ($model->relRolesApis as $key => $value) {
            $currentRoles[] = $value->role_id;
        }

        $newRoles = collect($selectedRoles)->diff($currentRoles)->all();
        $willDeleteRoles = collect($currentRoles)->diff($selectedRoles)->all();

        $this->_addRoleApis($newRoles, $model->id);
        $this->_deleteRoleApis($willDeleteRoles, $model->id);

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
        $model = Api::destroy($id);

        if ($model) {
            return back();
        }
    }

    public function search ($q) {
        $p = [['id','<>', 0]];
        isset($q['id']) ? is_null($q['id'])?'':$p[] = ['id', '=', $q['id']] : '';
        isset($q['name']) ? is_null($q['name'])?'':$p[] = ['name', 'like', '%'.$q['name'].'%'] : '';
        isset($q['method']) ? is_null($q['method'])?'':$p[] = ['method', 'like', '%'.$q['method'].'%'] : '';
        isset($q['description']) ? is_null($q['description'])?'':$p[] = ['description', 'like', '%'.$q['description'].'%'] : '';
        $data = Api::where($p)->orderBy('id', 'desc')->paginate($this->limit);
        return $data;
    }

    private function _addRoleApis($roles, $apiId) {
        foreach ($roles as $key => $value) {
            $relRolApis = new RelRoleApi();
            $relRolApis->api_id = $apiId;
            $relRolApis->role_id = $value;
            $relRolApis->save();
        }
    }

    private function _deleteRoleApis($roles, $apiId) {
        RelRoleApi::where('api_id', '=', $apiId)->whereIn('role_id', $roles)->delete();
    }
}
