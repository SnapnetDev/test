<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\BscMetric;
use App\BscSubMetric;
use App\BscMeasurementPeriod;
use App\BscWeight;
use App\Department;
use App\GradeCategory;
use App\Company;
use App\Traits\BSCEvaluationTrait;
use App\User;
use Auth;

class BSCEvaluationController extends Controller
{
    use BSCEvaluationTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         $company_id=companyId();
         $company=Company::find($company_id);
        $metrics=BscMetric::all();
        $measurement_periods=BscMeasurementPeriod::all();
        $weights=BscWeight::all();
        $departments=Department::where('company_id',$company_id)->get();
        $grade_categories=GradeCategory::all();
        $user=new User();
        $operation='select';
       
        return view('bsc.index',compact('metrics','measurement_periods','weights','departments','grade_categories','user','operation'));//
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
     public function store(Request $request)
    {
       
        return $this->processPost($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
  
        return $this->processGet($id,$request);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function usersearch(Request $request)
    {

    	$company_id=companyId();
         $company=Company::find($company_id);

    	 if (Auth::user()->role->manages=="dr") {
    	 	 $manager=Auth::user();
        	if($request->q==""){
            return "";
		        }
		       else{
		        $name=$manager->whereHas('employees', function ($query) use($manager,$request) {
		        					$query->where('name','LIKE','%'.$request->q.'%');
									    
									})
		                        ->select('id as id','name as text')
		                        ->get();
		            }
        
        
        return $name;
    
        }elseif(Auth::user()->role->manages=="all"){
        	if($request->q==""){
            return "";
        }
       else{
        $name=$company->users()->where('name','LIKE','%'.$request->q.'%')
        						->where('status',1)
                        ->select('id as id','name as text')
                        ->get();
            }
        
        
        return $name;
    }
        }
    	 
}
