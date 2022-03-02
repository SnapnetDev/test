<?php

namespace App\Http\Controllers;

use App\Department;
use App\User;
use Illuminate\Http\Request;

class VisitorApiController extends Controller
{
    public function users(Request $request){
        $paginate=50;
        if ($request->filled('paginate')){
            User::paginate($request->paginate);
        }
        $users= User::all();
        $users= $users->unique('email');
        $users->map(function ($user){
            $rol=2;
            if ($user->role->permissions->contains('constant', 'book_visit')){
                $rol=2;
            }
            if ($user->role->permissions->contains('constant', 'front_desk')){
                $rol=3;
            }
            if ($user->role->permissions->contains('constant', 'admin_dashboard')){
                $rol=1;
            }
            $user['rol']=$rol;
        });
        return $users;
    }

    public function departments(Request $request){
        return Department::all();
    }

}
