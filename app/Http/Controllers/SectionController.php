<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\Section;
use App\User;
use App\Company;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    use Section;
    public function index()
    {
        $project_components=\App\PaceSalaryComponent::select('constant')->distinct()->where(['company_id'=>companyId()])->get();
        $office_components=\App\SalaryComponent::select('constant')->distinct()->where(['company_id'=>companyId()])->get();
       $sections=\App\UserSection::where(['company_id'=>companyId()])->get();
       
        $non_section_users=\App\User::where(['company_id'=>companyId()])->where('status','!=',2)->doesntHave('section')->get();
        
     return view('sections.index',compact('sections','project_components','office_components','non_section_users'));
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
