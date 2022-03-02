<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Position;
use App\StaffCategory;
use App\Location;
use Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class EmployeeDesignationSettingController extends Controller
{
    public function index()
	{
		$positions=Position::all();
		$staffcategories=StaffCategory::all();
		$locations=Location::all();
		return view('settings.employeedesignationsettings.index',compact('positions','staffcategories','locations'));
	}
	public function savePosition(Request $request)
	{
		Position::updateOrCreate(['id'=>$request->position_id],['name'=>$request->name]);
		return  response()->json('success',200);
	}
	public function getPosition($position_id)
	{
		$position=Position::find($position_id);
		return  response()->json($position,200);
	}
	public function deletePosition($position_id)
	{
		$position=Position::find($position_id);
		if ($position) {
			$position->delete();
		}else{
			return  response()->json(['failed'],200);
		}
		return  response()->json(['success'],200);
	}

	public function saveLocation(Request $request)
	{
		Location::updateOrCreate(['id'=>$request->location_id],['name'=>$request->name,'address'=>$request->address]);
		return  response()->json('success',200);
	}
	public function getLocation($location_id)
	{
		$location=Location::find($location_id);
		return  response()->json($location,200);
	}
	public function deleteLocation($location_id)
	{
		$location=Location::find($location_id);
		if ($location) {
			$location->delete();
		}else{
			return  response()->json(['failed'],200);
		}
		return  response()->json(['success'],200);
	}
	public function saveStaffCategory(Request $request)
	{
		StaffCategory::updateOrCreate(['id'=>$request->staffcategory_id],['name'=>$request->name,'payroll_type'=>$request->payroll_type]);
		return  response()->json('success',200);
	}
	public function getStaffCategory($staffcategory_id)
	{
		$staffcategory=StaffCategory::find($staffcategory_id);
		return  response()->json($staffcategory,200);
	}
	public function deleteStaffCategory($staffcategory_id)
	{
		$staffcategory=StaffCategory::find($staffcategory_id);
		if ($staffcategory) {
			$staffcategory->delete();
		}else{
			return  response()->json(['failed'],200);
		}
		return  response()->json(['success'],200);
	}
}
