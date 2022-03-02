<?php

namespace App\Http\Controllers;

use App\OffPayrollComputation;
use App\OffPayrollComputationDetail;
use App\OffPayrollItem;
use App\OffPayrollItemComponent;
use App\OffPayrollType;
use App\Services\OffPayrollItemService;
use App\User;
use Illuminate\Http\Request;

class OffPayrollController extends Controller
{
    public function index()
    {
       $types=OffPayrollType::all();
       return view('compensation.offpayroll.type',compact('types'));
    }

    public function saveType(Request $request)
    {
        $type=OffPayrollType::updateOrCreate(['id'=>$request->id],['name'=>$request->name]);
        return 'success';
    }

    public function getType(Request $request,$id)
    {
        return $type=OffPayrollType::find($request->id);
    }
    public function items(Request $request)
    {
        $types=OffPayrollType::all();
        $items=OffPayrollItem::all();
        return view('off_payroll.items',compact('type','items'));
    }
    public function createItem(Request $request)
    {
        $company_id=companyId();
        $types=OffPayrollType::all();
        $project_salary_components = \App\PaceSalaryComponent::where('company_id', $company_id)->get()->pluck('name', 'constant');
        
        $project_salary_components = $project_salary_components->unique();
        return view('off_payroll.add_items',compact('types','project_salary_components'));
    }
    public function editItem(Request $request,$item_id)
    {
        $company_id=companyId();
        $types=OffPayrollType::all();
        $project_salary_components = \App\PaceSalaryComponent::where('company_id', $company_id)->get()->pluck('name', 'constant');
        $item=OffPayrollItem::find($item_id);
        $project_salary_components = $project_salary_components->unique();
        return view('off_payroll.edit_items',compact('types','project_salary_components','item'));
    }

    public function getItem(Request $request,$id)
    {
        return $item=OffPayrollItem::find($id);
    }

    public function saveItem(Request $request)
    {
       return OffPayrollItemService::storeItem($request);
       
    }
    public function computations(Request $request,$item_id)
    {
        $item=OffPayrollItem::find($item_id);
        $computations=OffPayrollComputation::where('off_payroll_item_id',$item_id)->get();
       
        return view('off_payroll.computations',compact('item','computations'));
    }
    public function computation_details(Request $request,$computation_id)
    {
    
        $computation=OffPayrollComputation::find($computation_id);
        $computation_details=OffPayrollComputationDetail::where('off_payroll_computation_id',$computation->id)->get();
        return view('off_payroll.computation_details',compact('computation','computation_details'));
    }

    public function allEmployeesItemComputation(Request $request)
    { 
        return OffPayrollItemService::employeesComputation($request->item_id,$request->year);
          
    }

    public function oneEmployeeItemComputation(Request $request)
    {
        return OffPayrollItemService::employeeComputation($request->item_id,$request->year,$request->user_id);
        
    }
    

}
