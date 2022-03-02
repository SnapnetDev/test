<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\BscMetric;
use App\BscSubMetric;
use App\BscMeasurementPeriod;
use App\BscWeight;
use App\Department;
use App\GradeCategory;
use App\Traits\BSCTrait;

class BSCController extends Controller
{
    use BSCTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $company_id = companyId();
        $metrics = BscMetric::all();
        $measurement_periods = BscMeasurementPeriod::all();
        $weights = BscWeight::all();
        $departments = Department::where('company_id', $company_id)->get();
        $performance_categories = \App\BscGradePerformanceCategory::all();
        $bsms=\App\BehavioralSubMetric::where(['status'=>1,'company_id'=>companyId()])->get();
        $users=User::where('status','!=',2)->get();
        return view('settings.bscsettings.index',compact('metrics','measurement_periods','weights','departments','performance_categories','bsms','users'));//
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
     * @param \Illuminate\Http\Request $request
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
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {

        return $this->processGet($id, $request);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}