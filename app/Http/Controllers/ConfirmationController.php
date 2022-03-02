<?php

namespace App\Http\Controllers;

use App\Confirmation;
use App\Traits\ConfirmationTrait;
use App\User;
use Illuminate\Http\Request;

class ConfirmationController extends Controller
{

    use ConfirmationTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $confirmations=Confirmation::all();
        $pending_confirmations=Confirmation::where(['status'=>0])->get();
        $successful_confirmations=Confirmation::where(['status'=>1])->get();
        $total_unconfirmed_staff=User::where(['status'=>0])->get();

        return view('confirmation.list',compact('confirmations','pending_confirmations','successful_confirmations','total_unconfirmed_staff'));

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
