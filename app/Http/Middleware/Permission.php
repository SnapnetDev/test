<?php

namespace App\Http\Middleware;

use Closure;

class Permission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next,$perm)
    {
        //  $has_permission=\App\Permission::where('constant',$perm)->whereHas('roles', function($query){
            // $query->where('role_id',\Auth::user()->role_id);
        // })->count();
         $permissions=\Auth::user()->role->permissions;
         $count=$permissions->where('constant', $perm);
        // $role=\Auth::user()->role->whereHas('permissions', function ($query) use ($perm) {
        //         $query->where('constant', $perm);
        //     })->get();
        if(count($count)<1)
        {
            // throw new \Exception("Your Role Id : ". $role);
            return redirect()->route('home');
            // return 1;
            // return abort(403);
        }         
        return $next($request);
    }
}
