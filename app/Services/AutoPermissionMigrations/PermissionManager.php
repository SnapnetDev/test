<?php


namespace App\Services\AutoPermissionMigrations;


use App\Permission;
use App\PermissionCategory;
use Illuminate\Support\Facades\Auth;

class PermissionManager
{


    static function addCategory($name){

        if (self::fetchPermissionCategories()->where('name',$name)->exists()){
            return;
        }

        $obj = new PermissionCategory;
        $obj->id = self::getLastCategoryInsertId() + 1;
        $obj->name = $name;
        $obj->save();

        return $obj;


    }

    static function addPermission($categoryName,$permissionName,$permissionConstant){


        if (!self::fetchPermissionsByCategory($categoryName)->exists()){
            self::addCategory($categoryName);
        }

        $category = self::fetchPermissionCategoryByName($categoryName)->first();

        if (self::fetchPermissionsByCategory($category->id)->where('name',$permissionName)->exists()){

            return;
        }


        $obj = new Permission;

        $obj->id = self::getLastPermissionInsertId() + 1;
        $obj->permission_category_id = $category->id;
        $obj->name = $permissionName;
        $obj->constant = $permissionConstant;
        $obj->description = $permissionName;
        $obj->save();


        return $obj;
    }

    static function hasPermission($constant){
        return  Auth::check() &&  Auth::user()->role->permissions->contains('constant', $constant);
    }

    static function fetchPermissions(){
        return (new Permission)->newQuery();
    }

    static function fetchPermissionsByCategory($permissionCategoryId){
        return self::fetchPermissions()->where('permission_category_id',$permissionCategoryId);
    }

//    static function fetchPermissionsByName($name){
//        return self::fetchPermissions()->where('name',$name);
//    }

    static function fetchPermissionCategories(){
        return (new PermissionCategory)->newQuery();
    }

    static function fetchPermissionCategoryByName($name){
        return self::fetchPermissionCategories()->where('name',$name);
    }

    static function getLastPermissionInsertId(){
        return self::fetchPermissions()->orderBy('id','desc')->first()->id * 1;
    }

    static function getLastCategoryInsertId(){
        return self::fetchPermissionCategories()->orderBy('id','desc')->first()->id * 1;
    }








}