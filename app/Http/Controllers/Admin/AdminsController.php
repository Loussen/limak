<?php

namespace App\Http\Controllers\Admin;

use App\ModelPermissions\Admin;
use App\ModelPermissions\Role;
use App\ModelPermissions\RelAdminRole;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;

class AdminsController extends Controller
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
        $roles = Role::all();
        if(Input::get('search') == "true"){
            $params = Input::get();
            $data = $this->search($params);
        }else{
            $data = Admin::with('relAdminRoles')->orderBy('id', 'desc')->paginate($this->limit);
        }

        return view('admin.admins.index', compact('data', 'params', 'roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = null;
        $roles = Role::all();
        return view('admin.admins.form', compact('data', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $model = new Admin();
        $request->password = Hash::make($request->password);
        $request->uniqid = uniqid();
        $response = crud($model, $request, ['name', 'surname', 'username', 'password', 'uniqid']);

        if ($response['status']) {
            if (isset($request['roles'])) {
                $this->_addRoleAdmins($request['roles'], $response['id']);
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
        $data = Admin::with('relAdminRoles')->find($id);


        $selectedRoles = [];
        foreach ($data->relAdminRoles as $key => $value) {
            $selectedRoles[] = $value->role_id;
        }

        $roles = Role::all();

        return view('admin.admins.form', compact('data', 'id', 'roles', 'selectedRoles'));
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
        $model = Admin::with('relAdminRoles')->find($id);
        $fields = ['name', 'surname', 'username'];

        if ($request->password) {
            $request->password = Hash::make($request->password);
            $fields[] = 'password';
        }

        $response = crud($model, $request, $fields);

        $selectedRoles = isset($request['roles']) ? $request['roles'] : [];

        $currentRoles = [];
        foreach ($model->relAdminRoles as $key => $value) {
            $currentRoles[] = $value->role_id;
        }

        $newRoles = collect($selectedRoles)->diff($currentRoles)->all();
        $willDeleteRoles = collect($currentRoles)->diff($selectedRoles)->all();

        $this->_addRoleAdmins($newRoles, $model->id);
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
        $model = Admin::destroy($id);

        if ($model) {
            return back();
        }
    }

    public function getAdminId() {
        return  response()->json(['id' => Auth::guard('admin')->user()->id]);
    }

    public function search ($q) {
        $p = [['id','<>', 0]];
        isset($q['id']) ? is_null($q['id'])?'':$p[] = ['id', '=', $q['id']] : '';
        isset($q['name']) ? is_null($q['name'])?'':$p[] = ['name', 'like', '%'.$q['name'].'%'] : '';
        isset($q['surname']) ? is_null($q['surname'])?'':$p[] = ['surname', 'like', '%'.$q['surname'].'%'] : '';
        $data = Admin::where($p)->orderBy('id', 'desc')->paginate($this->limit);
        return $data;
    }

    public function getAccountants(Request $request) {
        return response()->json(RelAdminRole::where('role_id', '=', getRoleByLabel('accountant'))->with('admins')->get());
    }

    private function _addRoleAdmins($roles, $adminId) {
        foreach ($roles as $key => $value) {
            $relRolAdmins = new RelAdminRole();
            $relRolAdmins->admin_id = $adminId;
            $relRolAdmins->role_id = $value;
            $relRolAdmins->save();
        }
    }

    private function _deleteRoleApis($roles, $adminId) {
        RelAdminRole::where('admin_id', '=', $adminId)->whereIn('role_id', $roles)->delete();
    }
}
