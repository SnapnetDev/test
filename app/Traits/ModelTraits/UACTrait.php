<?php
/**
 * Created by PhpStorm.
 * User: NnamdiAlexanderAkamu
 * Date: 4/15/2020
 * Time: 10:58 PM
 */

namespace App\Traits\ModelTraits;


use App\User;
use Auth;
use Illuminate\Database\Eloquent\Builder;

trait UACTrait
{

//whereHas('user.managers',function($query){
//
//	$query->where('manager_id',request()->user()->id);
//});


 function managesDr($userId){
 	$query = new User;
 	$query = $query::query();
 	$query = $query->whereHas('user.managers',function(Builder $builder) use ($userId){
 		return $builder->where('manager_id',$userId);
    });

 	return $query->count() > 0;
 }

 function getUsersByPermission_($constant){


 	//$auth->role->permissions->contains('constant', 'group_access')
	 $query = User::whereHas('role',function(Builder $builder) use ($constant){
		 return $builder->whereHas('permissions',function(Builder $builder2) use ($constant){

			 return $builder2->where('constant',$constant);

		 });
	 })->where('company_id',companyId());

//	 dd($query->toSql());

	 $record = $query->get();

	 return $record;

 }


}