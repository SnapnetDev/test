<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\E360Det;
use App\E360QuestionCategory;
use App\E360DetQuestion;
use App\E360DetQuestionOption;
use App\E360Evaluation;
use App\E360EvaluationDetail;
use App\E360MeasurementPeriod;
use App\Department;
use App\Traits\E360SettingTrait;

class E360SettingController extends Controller
{
    use E360SettingTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         $company_id=companyId();
        $measurement_periods=E360MeasurementPeriod::all();
        $departments=Department::where('company_id',$company_id)->get();
        $question_categories=E360QuestionCategory::all();
        return view('settings.e360settings.index',compact('measurement_periods','departments','question_categories'));//
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
}
