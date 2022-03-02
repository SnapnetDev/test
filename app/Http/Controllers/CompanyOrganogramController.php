<?php

namespace App\Http\Controllers;

use App\CompanyOrganogram;
use App\CompanyOrganogramLevel;
use App\CompanyOrganogramPosition;
use App\User;
use Illuminate\Http\Request;
use App\Traits\OrganogramTrait;

class CompanyOrganogramController extends Controller
{
    use OrganogramTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $organograms=CompanyOrganogram::where('company_id',companyId())->get();
        return view('organogram.list',compact('organograms'));
//
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
     * @param  \App\CompanyOrganogram  $companyOrganogram
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        return $this->processGet($id,$request);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CompanyOrganogram  $companyOrganogram
     * @return \Illuminate\Http\Response
     */
    public function edit(CompanyOrganogram $companyOrganogram)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CompanyOrganogram  $companyOrganogram
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CompanyOrganogram $companyOrganogram)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CompanyOrganogram  $companyOrganogram
     * @return \Illuminate\Http\Response
     */
    public function destroy(CompanyOrganogram $companyOrganogram)
    {
        //
    }
}
