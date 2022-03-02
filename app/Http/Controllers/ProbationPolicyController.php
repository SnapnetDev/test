<?php

namespace App\Http\Controllers;

use App\ProbationPolicy;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\ProbationPolicyRepository;

class ProbationPolicyController extends Controller
{
    public $probation_policy;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(ProbationPolicyRepository $probation_policy)
    {
        $this->probation_policy=$probation_policy;
    }

    public function index()
    {
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
        //
        return $this->probation_policy->processPost();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ProbationPolicy  $probationPolicy
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->probation_policy->processGet($id);
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ProbationPolicy  $probationPolicy
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
     * @param  \App\ProbationPolicy  $probationPolicy
     * @return \Illuminate\Http\Response
     */
    public function  update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ProbationPolicy  $probationPolicy
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
