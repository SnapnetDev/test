<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Auth;

class CompanyScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
     public function apply(Builder $builder, Model $model)
    {
        if (!Auth::guest()) {
            # code...
        
        if(session()->has('company_id')){
            $compid=session('company_id');
        }
        else{
 
            $compid=$model->company_id;
        }

        if (Auth::user()->role->permissions->contains('constant', 'group_access') && !session()->has('company_id')) {
            $builder->where('company_id', '>', 0);
        }else{
            $builder->where('company_id', '=', $compid);
        }
    }
        
    }
}
