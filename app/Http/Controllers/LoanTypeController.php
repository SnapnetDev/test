<?php

namespace App\Http\Controllers;

use App\LoanType;
use App\LoanTypeGrade;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class LoanTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
	    return [
	    	'list'=>LoanType::query()->orderBy('id','desc')->get()
	    ];
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
//        $data = $request->all();
//        dd($data);
        //
	    $obj = new LoanType;

	    $obj->duration_comparator = $request->get('duration_comparator');
	    $obj->interest_rate = $request->get('interest_rate');
	    $obj->multiplier_index = $request->get('multiplier_index');
	    $obj->name = $request->get('name');
	    $obj->open_to_grade_id = $request->get('open_to_grade_id');
	    $obj->pace_salary_component_id = $request->get('pace_salary_component_id');
	    $obj->repayment_period = $request->get('repayment_period');
	    $obj->required_duration_in_months = $request->get('required_duration_in_months');
	    $obj->requires_confirmation = $request->get('requires_confirmation');
	    //specific_salary_component_type_id
	    $obj->specific_salary_component_type_id = $request->get('specific_salary_component_type_id');

	    $obj->save();

	    if ($request->filled('grade_id')){
	        $gradeIds = $request->get('grade_id');
	        foreach ($gradeIds as $gradeId){
	            $objLoanTypeGrade = new LoanTypeGrade;
	            $objLoanTypeGrade->grade_id = $gradeId;
	            $objLoanTypeGrade->loan_type_id = $obj->id;
	            $objLoanTypeGrade->save();
            }
        }

	    return [
	    	'message'=>'Loan Type Added Successfully',
		    'error'=>false
	    ];

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\LoanType  $loanType
     * @return \Illuminate\Http\Response
     */
    public function show(LoanType $loanType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\LoanType  $loanType
     * @return \Illuminate\Http\Response
     */
    public function edit(LoanType $loanType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\LoanType  $loanType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LoanType $loanType)
    {
        //

        $obj = $loanType;
        $obj->duration_comparator = $request->get('duration_comparator');
        $obj->interest_rate = $request->get('interest_rate');
        $obj->multiplier_index = $request->get('multiplier_index');
        $obj->name = $request->get('name');
        $obj->open_to_grade_id = $request->get('open_to_grade_id');
        $obj->pace_salary_component_id = $request->get('pace_salary_component_id');
        $obj->repayment_period = $request->get('repayment_period');
        $obj->required_duration_in_months = $request->get('required_duration_in_months');
        $obj->requires_confirmation = $request->get('requires_confirmation');
	    $obj->specific_salary_component_type_id = $request->get('specific_salary_component_type_id');


	    $obj->save();


	    if ($request->filled('grade_id')){

	    	$garbageQuery = (new LoanTypeGrade)->newQuery();
	    	$garbageQuery = $garbageQuery->where('loan_type_id',$obj->id);
	    	$garbageQuery->delete();

		    $gradeIds = $request->get('grade_id');
		    foreach ($gradeIds as $gradeId){
			    $objLoanTypeGrade = new LoanTypeGrade;
			    $objLoanTypeGrade->grade_id = $gradeId;
			    $objLoanTypeGrade->loan_type_id = $obj->id;
			    $objLoanTypeGrade->save();
		    }
	    }


        return [
            'message'=>'Loan Type Saved Successfully',
            'error'=>false
        ];


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\LoanType  $loanType
     * @return \Illuminate\Http\Response
     */
    public function destroy(LoanType $loanType)
    {
        //
	    $loanType->delete();
	    return [
		    'message'=>'Loan Type Saved Successfully Removed.',
		    'error'=>false
	    ];
    }



    function getResolvedTypes(){

    	$objLoanType = new LoanType;
    	$resolvedFilters = $objLoanType->getRosolvedTypesList(function(LoanType $loanType,$user){

    		//Todo code here


		    if ($loanType->requires_confirmation == 1){
		    	if ($user->status != 1){
		    	  return false;
			    }
		    }

		    $dateToday = Carbon::today();
		    $dateHired = Carbon::parse($user->hiredate);
		    $monthsRequired = $loanType->required_duration_in_months;
		    $measuredMonths = $dateToday->diffInMonths($dateHired);

		    if ($loanType->duration_comparator == '>='){
                if ($measuredMonths < $monthsRequired){
                	return false;
                }
		    }

		    if ($loanType->duration_comparator == '<='){
			    if ($measuredMonths > $monthsRequired){
				    return false;
			    }
		    }

		    $queryCheck = (new LoanType)->newQuery();

		    //loan_type_grade
		    $queryCheck = $queryCheck->where('id',$loanType->id)
			    ->whereHas('loan_type_grade',function(Builder $builder) use ($user){
			    	return $builder->where('grade_id',$user->grade_id);
			    });

		    //hiredate
		    if (!$queryCheck->exists()){
		    	return false;
		    }


		     return true;

		    //Todo Code here

	    });

    	$data = [];
    	$data['list'] = $resolvedFilters;
    	return $data;

    }


}
