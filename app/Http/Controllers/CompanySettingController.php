<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Company;
use App\Department;
use App\Branch;
use App\Job;
use App\User;
use Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class CompanySettingController extends Controller
{

	
	//company functions
	public function companies()
	{
		$companies=Company::all();
		$users=User::all();
		return view('settings.companysettings.company',compact('companies', 'users'));
	}
	public function saveCompany(Request $request)
	{
		// $validator = Validator::make($request->all(), ['name'=>'required|min:3','user_id'=>'required','email' => [
  //           'required',
  //           Rule::unique('companies')->ignore($request->company_id)
  //       ]]);
  //      if ($validator->fails()) {
  //           return response()->json([
  //                   $validator->errors()
  //                   ],401);
  //       }
        // return $request->all();
		$company=Company::updateOrCreate(['id'=>$request->company_id],['name'=>$request->name,'email'=>$request->email,'address'=>$request->address,'user_id'=>$request->user_id,'biometric_serial'=>$request->biometric]);
		if ($request->file('logo')) {
                    $path = $request->file('logo')->store('logo');
                    if (Str::contains($path, 'logo')) {
                       $filepath= Str::replaceFirst('logo', '', $path);
                    } else {
                        $filepath= $path;
                    }
                    $company->logo = $filepath;
                    $company->save();
                }
        
		return  response()->json(['success'],200);
	}
	public function changeParentCompany($company_id='')
	{
		$companies=Company::all();
		foreach ($companies as $company) {
			if ($company->id==$company_id) {
				$company->is_parent=1;
			$company->save();
			}else{
				$company->is_parent=0;
			$company->save();
			}
	
		}
		return 'success';

	}
	public function getCompany($company_id)
	{
		 $company=Company::with('users')->find($company_id);
		return  response()->json($company,200);

	}
	//end company functions
	//department functions
	public function departments($company_id)
	{
		
		$company=Company::find($company_id);
		$departments=$company->departments;
		$users=$company->users;
		return view('settings.companysettings.department',compact('departments','company','users'));
	}
	public function saveDepartment(Request $request)
	{
		 Department::updateOrCreate(['id'=>$request->department_id],['name'=>$request->name,'manager_id'=>$request->manager_id,'company_id'=>$request->company_id]);

		return  response()->json(['success'],200);
	}
	public function getDepartment($department_id)
	{
		$department=Department::find($department_id);
		return  response()->json($department,200);
	}
	public function deleteDepartment($department_id)
	{
		$department=Department::find($department_id);
		// return $department->users;
		if ($department->jobs->count()>0) {
			return 'Department has users and cannot be deleted';
		}
		$department->delete();
		return  response()->json(['success'],200);
	}
	//end department functions
	//branch functions
	public function branches($company_id)
	{
		$branches=Branch::all();
		$company=Company::find($company_id);
		$users=User::all();
		return view('settings.companysettings.branch',compact('branches','company','users'));
	}
	public function saveBranch(Request $request)
	{
		Branch::updateOrCreate(['id'=>$request->branch_id],['name'=>$request->name,'email'=>$request->email,'address'=>$request->address,'company_id'=>$request->company_id,'manager_id'=>$request->manager_id]);
		return  response()->json(['success'],200);
	}
	public function getBranch($branch_id)
	{
		$branch=Branch::find($branch_id);
		return  response()->json($branch,200);
	}
	public function deleteBranch($branch_id)
	{
		$branch=Branch::find($branch_id);
		if ($branch->has('users')) {
			return 'Branch has users and cannot be deleted';
		}
		$branch->delete();
		return  response()->json(['success'],200);
	}
	//end branch functions
	//job functions
	public function jobs($department_id)
	{
		$jobs=Job::all();
		return view('settings.companysettings.job',['jobs'=>$jobs]);
	}
	public function saveJob(Request $request)
	{
		Job::updateOrCreate(['id'=>$request->job_id],[$request->all()]);
		return  response()->json(['success'],200);
	}
	public function getJob($job_id)
	{
		$job=Job::find($job_id);
		return  response()->json([$job],200);
	}
	//end job functions

}