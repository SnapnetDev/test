<?php

namespace App\Filters;

use App\User;
use App\UserGroup;
use Illuminate\Http\Request;
use Carbon\Carbon;

class UserGroupFilter
{
    public static function apply(Request $filters)
    {
        $group = (new UserGroup)->newQuery();

        // Search for a user based on their email.
        if ($filters->filled('name_contains')) {
            $group->where('name','like' ,'%' . $filters->input('name_contains') . '%');
        }

          // Search for a user based on their role.
          if ($filters->filled('user')&& $filters->input('userftype')=='or') {
            $user=$filters->input('user');
            $group->whereHas('users', function ($query) use ($user) {
                $query->where('users.id', $user[0]);
                if (count($user)>1) {
                for ($i=1; $i <count($user) ; $i++) {
                  $query->orWhere('users.id', $user[$i]);
                }
                }
            });
          }
          if ($filters->filled('user')&& $filters->input('userftype')=='and') {
            $user=$filters->input('user');
            $group->whereHas('users', function ($query) use ($user) {
                $query->where('users.id', $user[0]);
                if (count($user)>1) {
                for ($i=1; $i <count($user) ; $i++) {
                  $query->where('users.id', $user[$i]);
                }
                }
            });
          }
        // Search for a user based on their creation date.
        if ($filters->filled('created_to')) {
          $dt_from=Carbon::parse($filters->input('created_from'));
          $dt_to=Carbon::parse($filters->input('created_to'));
            $group->whereBetween('groups.created_at', [$dt_from,$dt_to]);
        }
        if ($filters->filled('updated_to')) {
          $dt_from=Carbon::parse($filters->input('updated_from'));
          $dt_to=Carbon::parse($filters->input('updated_to'));
            $group->whereBetween('groups.created_at', [$dt_from,$dt_to]);
        }
        $company_id=companyId();
          $group->where('company_id',$company_id);

        // Get the results and return them.
        return $group->paginate(15);

        }


    }
