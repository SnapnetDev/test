<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\QueryRepository;

class QueryController extends Controller
{

    public  $query;

    /**
     * Display a listing of the resource.
     *
     * @param QueryRepository $query
     */
    public function __construct(QueryRepository $query)
    {
        $this->query=$query;

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
    public function store()
    {
        //
        return $this->query->processPost();

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        return $this->query->processGet($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if($this->query->permissionDenied()){
            return $this->query->permissionDenied();
        }

         \App\QueryThread::where('id',$id)->update($this->query->closeQueryConditions());
         \App\QueryThread::where('parent_id',$id)->update(['status'=>'closed']);
        $this->query->notifyUser('closed');
        return response()->json(['status'=>'success','message'=>'Query Successfully Closed']);
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
        \App\Query::where('id',$id)->delete();
        return response()->json(['status'=>'success','message'=>'Operation Successful']);

    }
}
