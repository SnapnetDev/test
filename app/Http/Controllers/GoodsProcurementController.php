<?php

namespace App\Http\Controllers;

use App\GoodsProcurement;
use Illuminate\Http\Request;

class GoodsProcurementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('goods.goods');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('goods.goods_create');
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
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\GoodsProcurement  $goodsProcurement
     * @return \Illuminate\Http\Response
     */
    public function show(GoodsProcurement $goodsProcurement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\GoodsProcurement  $goodsProcurement
     * @return \Illuminate\Http\Response
     */
    public function edit(GoodsProcurement $goodsProcurement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\GoodsProcurement  $goodsProcurement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, GoodsProcurement $goodsProcurement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\GoodsProcurement  $goodsProcurement
     * @return \Illuminate\Http\Response
     */
    public function destroy(GoodsProcurement $goodsProcurement)
    {
        //
    }
}
