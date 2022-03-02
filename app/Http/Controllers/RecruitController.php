<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AvaJob;
use App\Traits\RecruitTrait as Rt;
use App\Company;
use App\Department;
use App\JobListing;
use App\Filters\JobListingFilter;

class RecruitController extends Controller
{
   use Rt;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (count($request->all())==0) {
        $company_id=companyId();
        $company=Company::find($company_id);
        $departments=$company->departments;
         $jobs=$company->departments()->first()->jobs;
         $joblistings=JobListing::paginate(5);


        return view('recruit.listing',compact('company','departments','jobs','joblistings'));
        }else{
                 
        $company_id=companyId();
        $company=Company::find($company_id);
        $departments=$company->departments;
         $jobs=$company->departments()->first()->jobs;
        
                $joblistings=JobListingFilter::apply($request);
                 return view('recruit.listing',compact('company','departments','jobs','joblistings'));
             
         }
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
