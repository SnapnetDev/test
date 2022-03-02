<?php

namespace App\Filters;

use App\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;

class UserFilter
{
    public static function apply(Request $filters)
    {
        $user = (new User)->newQuery();

        // Search for a user based on their email. Add q to support select 2
        if ($filters->filled('employee') || $filters->filled('q')) {

          $q= $filters->filled('employee') ? $filters->input('employee') : $filters->input('q');
            $user->where(function ($query) use($q) {
                $query->where('email','like' ,'%' . $q . '%')
                      ->orWhere('first_name','like' ,'%' . $q . '%')
                      ->orWhere('middle_name','like' ,'%' . $q . '%')
                      ->orWhere('last_name','like' ,'%' . $q . '%')
                      ->orWhere('emp_num','like' ,'%' . $q . '%');
            });

        }
       if ($filters->filled('company')&&$filters->input('company')!=0) {

            $user->where('company_id','=' ,$filters->input('company'));
        }
        if ($filters->filled('department')&&$filters->input('department')!=0) {
          $department_id=$filters->input('department');
            $user->whereHas('job.department', function ($query) use($department_id){
                  $query->where('departments.id', '=', $department_id);
              });
        }

        if($filters->filled('branch_id')){
            $user->where('branch_id',$filters->branch_id);
        }
       //  if ($filters->filled('branch')&&$filters->input('branch')!=0) {
       //      $user->where('branch_id','=' ,$filters->input('branch'));
       //  }
          // Search for a user based on their role.

          if ($filters->filled('role')&&$filters->input('role')!=0) {
            $user->where('role_id','=' ,$filters->input('role'));
          }
          if ($filters->filled('status')&&($filters->input('status')!=''||($filters->input('status')!=3))) {
            $user->where('status','=' ,$filters->input('status'));
          }

        // Search for a user based on their group date.
          if ($filters->filled('user_group')&&$filters->input('user_group')!=0) {
            $q=$filters->input('user_group');
            $user->whereHas('user_groups', function ($query) use($q){
                  $query->where('group_id', '=', $q);
              });
          }

             $company_id=companyId();
           if ($company_id>0) {
                $user->where('company_id', $company_id)->paginate(10);
            }
        // Get the results and return them.
          if ($filters->filled('pagi')&&$filters->input('pagi')=='all') {
            return $user->get();
          } elseif($filters->filled('pagi')&&($filters->input('pagi')==10||$filters->input('pagi')==15||$filters->input('pagi')==25||$filters->input('pagi')==50)){
           return $user->paginate($filters->input('pagi'));
          }

          if (Auth::User()->role->manages=="dr") {
            $manager=Auth::User();
            $user->whereHas('managers',function ($query) use($manager) {
                $query->where('users.id',$manager->id);
            });
          }
          if(request()->has('select2')){
              if(strlen($filters->q)>=3) {
//                  dd('d');
                  return \App\User::where('email','like' ,'%' . $q . '%')
                      ->orWhere('name','like' ,'%' . $q . '%')
                      ->orWhere('emp_num','like' ,'%' . $q . '%')->select('id', 'name as text','probation_period','hiredate','company_id')->skip(0)->take(30)->get();
              }
              return [];
          }
        return $user->orderByDesc('created_at')->paginate(10);

        }


    }
