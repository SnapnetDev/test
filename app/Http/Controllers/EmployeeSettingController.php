<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Company;
use App\Department;
use App\Qualification;
use App\Grade;
use App\GradeCategory;
use App\User;
use Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Role;
use App\PermissionCategory;

class EmployeeSettingController extends Controller
{

	public function index()
	{
		$grades=Grade::all();
		$grade_categories=GradeCategory::all();
		$qualifications=Qualification::all();
		$roles=Role::all();
		$probation_policies=\App\ProbationPolicy::where('company_id',companyId())->first();
		return view('settings.employeesettings.index',compact('grades','qualifications','roles','grade_categories','probation_policies'));
	}
	public function saveGrade(Request $request)
	{
		$company_id=companyId();
		Grade::updateOrCreate(['id'=>$request->grade_id],['level'=>$request->level,'basic_pay'=>$request->basic_pay,'leave_length'=>$request->leave_length,'company_id'=>$company_id,'grade_category_id'=>$request->grade_category_id]);
		return  response()->json('success',200);
	}
	public function getGrade($grade_id)
	{
		$grade=Grade::find($grade_id);
		return  response()->json($grade,200);
	}
	public function deleteGrade($grade_id)
	{
		$grade=Grade::find($grade_id);
		if ($grade) {
			$grade->delete();
		}else{
			return  response()->json(['failed'],200);
		}
		return  response()->json(['success'],200);
	}
	public function saveGradeCategory(Request $request)
	{

		GradeCategory::updateOrCreate(['id'=>$request->grade_category_id],['name'=>$request->name]);
		return  response()->json('success',200);
	}
	public function getGradeCategory($grade_category_id)
	{
		$grade_category=GradeCategory::find($grade_category_id);
		return  response()->json($grade_category,200);
	}
	public function deleteGradeCategory($grade_category_id)
	{
		$grade_category=GradeCategory::find($grade_category_id);
		if ($grade_category) {
			$grade_category->delete();
		}else{
			return  response()->json(['failed'],200);
		}
		return  response()->json(['success'],200);
	}


	public function saveQualification(Request $request)
	{
		Qualification::updateOrCreate(['id'=>$request->qualification_id],['name'=>$request->name]);
		return  response()->json('success',200);
	}
	public function getQualification($qualification_id)
	{
		$qualification=Qualification::find($qualification_id);
		return  response()->json($qualification,200);
	}
	public function deleteQualification($qualification_id)
	{
		$qualification=Qualification::find($qualification_id);
		if ($qualification) {
			$qualification->delete();
		}else{
			return  response()->json(['failed'],200);
		}
		return  response()->json(['success'],200);
	}



}
