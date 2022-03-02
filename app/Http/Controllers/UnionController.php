<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\Union;
use App\User;
use App\Company;

class UnionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    use Union;
    public function index()
    {
        $project_components=\App\PaceSalaryComponent::select('constant')->distinct()->where(['company_id'=>companyId()])->get();
        $office_components=\App\SalaryComponent::select('constant')->distinct()->where(['company_id'=>companyId()])->get();
       $unions=\App\UserUnion::where(['company_id'=>companyId()])->get();
     return view('unions.index',compact('unions','project_components','office_components'));
    }

    /**
     * Show the form for creating a new resource.
     *
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
}
