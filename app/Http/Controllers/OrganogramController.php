<?php

namespace App\Http\Controllers;

use App\User;
use App\Company;
use Illuminate\Http\Request;

class OrganogramController extends Controller
{
    public function getEmployees($staff){
        return $staff->employees->map(function ($employee){
            $job=$employee->job?$employee->job->title:'';
           return ['id'=>$employee->id,'desc'=>$employee->name,'job'=>$job, 'hasChild'=>$employee->employees->count()>0?true:false];
        });
    }
    public function topLevel(){
        $company=Company::find(companyId());
        $staff='No Manager';
        $employees=[];
        if($company->manager){
            $employees=$this->getEmployees($company->manager);
            $staff=$company->manager->name;
            $job=$company->manager->job?$company->manager->job->title:'';
        }
        return [
            'id'=>'1',
            'desc'=>$staff,
            'job'=>$job,
            'children'=>$employees
        ];
    }
    public function employees($staff_id){
        $staff=User::where('id',$staff_id)->first();
        $employees=$this->getEmployees($staff);
        return [
            'result'=>$employees,
        ];
    }
}
