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
use App\Traits\E360Trait;

class E360Controller extends Controller
{
     use E360Trait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//         $questions=e360DetQuestion::all();
//         $question_categories=E360QuestionCategory::all();
//        return view('e360.review',compact('questions','question_categories'));//

        $mps=E360MeasurementPeriod::all();
        return view('e360.index',compact('mps'));
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
