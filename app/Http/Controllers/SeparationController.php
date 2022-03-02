<?php

namespace App\Http\Controllers;

use App\SeparationPolicy;
use Illuminate\Http\Request;
use App\Separation;
use App\User;
use App\SeparationType;
use App\Traits\SeparationTrait;
class SeparationController extends Controller
{
    use SeparationTrait;
    public function index()
    {
        $company_id=companyId();
        $sp=SeparationPolicy::where('company_id',$company_id)->first();
    	$separations=Separation::paginate(10);
        $users=User::where('status','!=',2)->get();
        return view('empmgt.separations',compact('separations','users','sp'));
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
        //
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
