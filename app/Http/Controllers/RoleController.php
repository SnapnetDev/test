<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Role;
use App\PermissionCategory;


class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $permissioncategories=PermissionCategory::all();
        return view('settings.rolesettings.index',compact('roles','permissioncategories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissioncategories=PermissionCategory::all();
        $role=[];
        return view('settings.employeesettings.role',compact('permissioncategories','role'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $no_of_permissions=is_countable($request->input('permission_list')) ? count($request->input('permission_list'))  : 0;
        $role=Role::updateOrCreate(['id'=>$request->role_id],['name'=>$request->name,'manages'=>$request->manages]);
        $role->permissions()->detach();
          if($no_of_permissions>0){
          for ($i=0; $i <$no_of_permissions ; $i++) {
                 $role->permissions()->attach($request->permission_list[$i],['created_at' => date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')]);
             }
            }
        return  response()->json('success',200);
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
        $permissioncategories=PermissionCategory::all();
        $role=Role::find($id);
        return view('settings.employeesettings.role',compact('permissioncategories','role'));
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        $role=Role::find($request->id);
        if ($role) {
             $role->permissions()->detach();
            $role->delete();
        }else{
            return  response()->json(['failed'],200);
        }
        return  response()->json(['success'],200);
    }
}
