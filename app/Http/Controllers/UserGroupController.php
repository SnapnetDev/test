<?php

namespace App\Http\Controllers;

use App\Role;
use Illuminate\Http\Request;
use App\UserGroup;
use App\Filters\UserGroupFilter;
use App\User;
use App\Traits\LogAction;
use Illuminate\Support\Facades\Auth;

class UserGroupController extends Controller
{
  use LogAction;
  public function __construct()
  {

  }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // try {
            if (count($request->all())==0) {
              $company_id=companyId();
          $users=User::where('company_id',$company_id)->get();

          $groups = UserGroup::where('company_id',$company_id)->withCount('users')->paginate(15);
          return view('groups.list',['groups'=>$groups,'users'=>$users]);
        }else{
          $company_id=companyId();
          $users=User::where('company_id',$company_id)->get();
            $groups=UserGroupFilter::apply($request);
            // return $users;
            return view('groups.list',['groups'=>$groups,'users'=>$users]);

          }
        // } catch (\Exception $e) {
        //
        // }


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
          try {
            $company_id=companyId();
          $users=User::where('company_id',$company_id)->get();
            return view('groups.create',['users'=>$users]);
          } catch (\Exception $e) {

          }


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // try {
        if($request->filled('user_id')){
            $no_of_users=count($request->input('user_id'));
        }else{
            $no_of_users=0;
        }


          $this->validate($request, ['name'=>'required|min:2']);
          $company_id=companyId();
          $group=new UserGroup();
          $group->name=$request->name;
          $group->company_id=$company_id;
          $group->save();
          // $logmsg='UserGroup created';
          // $this->saveLog('info','App\UserGroup',$group->id,'groups',$logmsg,Auth::user()->id);
          if($no_of_users>0){
          for ($i=0; $i <$no_of_users ; $i++) {
            $group->users()->attach($request->user_id[$i],['created_at' => date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')]);
            $user=User::find($request->user_id[$i]);
            // $logmsg='User was added to '.$group->name;
            // $this->saveLog('info','App\User',$user->id,'users',$logmsg,Auth::user()->id);
          }
          }
           return redirect()->route('groups')->with(['success'=>'UserGroup Created Successfully']);
        // } catch (\Exception $e) {

        // }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
          $group= UserGroup::find($id);
          $roles = Role::all();
          return view('groups.view',['group'=>$group,'roles'=>$roles]);
        } catch (\Exception $e) {

        }


    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      try {
        $group= UserGroup::find($id);
        $company_id=companyId();
          $users=User::where('company_id',$company_id)->get();
        return view('groups.edit',['group'=>$group,'users'=>$users]);
      } catch (\Exception $e) {

      }
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
      try {
        $group=UserGroup::find($id);
        $no_of_users=count($request->input('user_id'));
        $this->validate($request, ['name'=>'required|min:2']);
        $group->name=$request->name;
        $group->save();
        $group->users()->detach();
        // $logmsg='UserGroup Edited';
        // $this->saveLog('info','App\UserGroup',$group->id,'groups',$logmsg,Auth::user()->id);
        if($no_of_users>0){
        for ($i=0; $i <$no_of_users ; $i++) {
          if ($group->users->contains('id',$request->user_id[$i])) {
            # code...
          }else{
          $group->users()->attach($request->user_id[$i],['created_at' => date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')]);
          // $logmsg='User was added to '.$group->name;
          // $this->saveLog('info','App\User',$request->user_id[$i],'users',$logmsg,Auth::user()->id);
          }

        }
        }
        // else{
        //   $group->users()->detach();
        // }


         return redirect()->route('groups')->with(['success'=>'UserGroup Updated Successfully']);
      } catch (\Exception $e) {

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
        //
    }
    public function assignRole(Request $request)
    {

        $group=UserGroup::find($request->group_id);
        $role_id=$request->role_id;
        foreach ($group->users as $user){

            $user->role_id=$role_id;
            $user->save();
        }
        return 'success';

    }
}
