<?php

namespace App\Http\Controllers;

use App\Services\TrOfflineTrainingService;
use App\TrOfflineTrainingApproval;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Auth;

class TrOfflineTrainingApprovalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
	    $id = request('id');
	    $queryCollection = (new TrOfflineTrainingApproval)->newQuery()->where('tr_offline_training_id',$id);
	    $list = $queryCollection->orderBy('id','desc')->get();
	    foreach ($list as $k=>$v){
            $list[$k]->canApprove_ = false;
	        if ($v->canApprove(Auth::user())){
	            $list[$k]->canApprove_ = true;
            }
        }
	    return [
	    	'list'=>$list
	    ];
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


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\TrOfflineTrainingApproval  $trOfflineTrainingApproval
     * @return \Illuminate\Http\Response
     */
    public function show(TrOfflineTrainingApproval $trOfflineTrainingApproval)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TrOfflineTrainingApproval  $trOfflineTrainingApproval
     * @return \Illuminate\Http\Response
     */
    public function edit(TrOfflineTrainingApproval $trOfflineTrainingApproval)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TrOfflineTrainingApproval  $trOfflineTrainingApproval
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //

	    $user = Auth::user();
	    $obj = new TrOfflineTrainingService;
//	    $id = request('id');
	    $status = $request->get('status');

	    $objApproval = TrOfflineTrainingApproval::find($id);

//	    dd($objApproval);

	    if (is_null($objApproval)){
		    return [
			    'message'=>'Invalid approval',
			    'error'=>true
		    ];
	    }


	    if ($status == 1){

		    $obj->approveStage($objApproval, $user);

		    return [
			    'message'=>'Stage approved',
			    'error'=>false
		    ];

	    }

	    if ($status == 0){

		    $obj->rejectStage($objApproval, $user);

		    return [
			    'message'=>'Stage rejected',
			    'error'=>false
		    ];

	    }

	    return [
		    'message'=>'Invalid call!',
		    'error'=>true
	    ];

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TrOfflineTrainingApproval  $trOfflineTrainingApproval
     * @return \Illuminate\Http\Response
     */
    public function destroy(TrOfflineTrainingApproval $trOfflineTrainingApproval)
    {
        //
    }
}
