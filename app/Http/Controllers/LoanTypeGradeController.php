<?php

namespace App\Http\Controllers;

use App\LoanType;
use App\LoanTypeGrade;
use Illuminate\Http\Request;

class LoanTypeGradeController extends Controller
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
	    	'list'=>LoanTypeGrade::all()
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
        //
	    $obj = new LoanTypeGrade;
        $obj->loan_type_id = $request->get('loan_type_id');
        $obj->grade_id = $request->get('grade_id');
	    $obj->save();

	    return [
	    	'message'=>'Added',
		    'error'=>false
	    ];

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\LoanTypeGrade  $loanTypeGrade
     * @return \Illuminate\Http\Response
     */
    public function show(LoanTypeGrade $loanTypeGrade)
    {
        //
	    //LoanTypeGradeController
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\LoanTypeGrade  $loanTypeGrade
     * @return \Illuminate\Http\Response
     */
    public function edit(LoanTypeGrade $loanTypeGrade)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\LoanTypeGrade  $loanTypeGrade
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LoanTypeGrade $loanTypeGrade)
    {
        //

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\LoanTypeGrade  $loanTypeGrade
     * @return \Illuminate\Http\Response
     */
    public function destroy(LoanTypeGrade $loanTypeGrade)
    {
        //
	    $obj = $loanTypeGrade;
	    $obj->delete();
	    return [
	    	'message'=>'Removed',
		    'error'=>false
	    ];

    }

}
