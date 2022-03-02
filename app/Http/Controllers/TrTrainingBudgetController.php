<?php

namespace App\Http\Controllers;

use App\Department;
use App\Grade;
use App\Tr_TrainingBudget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

use Auth;
use Excel;


class TrTrainingBudgetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
	    //TrTrainingBudgetController
	    return [
	    	'list'=>(new Tr_TrainingBudget)->newQuery()->whereHas('department',function(Builder $builder){
	    	    return $builder;
            })->get()
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


	private function getGradeIdFromLevel($level){
		$obj = Grade::where('level',$level)->first();
		if (!is_null($obj)){
			return $obj->id;
		}else{
			return '';
		}
	}

	private function getDepIdFromName($name){
        $obj = Department::where('name',$name)->first();
        if (!is_null($obj)){
            return $obj->id;
        }else {
            return '';
        }
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


	    if (request()->file('excel_file')){

		    $path = request()->file('excel_file')->getRealPath();
		    $data = Excel::load($path)->get();
		    $ref = $this;

//			dd($data->toArray());

		    if ($data->count() > 0){

			    foreach ($data->toArray() as $k=>$v){

			    	$record = new Tr_TrainingBudget;
				    $record->hr_id = Auth::user()->id;
//				    $record->grade_id = $ref->getGradeIdFromLevel($v['grade']);
                    $record->dep_id = $ref->getDepIdFromName($v['department']);
				    //                    getDepIdFromName
				    $record->training_budget_name = $v['name'];
				    $record->allocation_total = $v['allocation_total'];
				    $record->year_of_allocation = date('Y');
				    $record->save();

			    }


			    return [
				    'message'=>'Bulk Upload successful',
				    'data'=>[],
				    'error'=>false
			    ];


		    }

//			dd('bulk detected');

	    }


	    $record = new Tr_TrainingBudget;
	    $record->hr_id = Auth::user()->id;
	    $record->dep_id = request()->get('dep_id');
	    $record->training_budget_name = request()->get('training_budget_name');
	    $record->allocation_total = request()->get('allocation_total');
	    $record->year_of_allocation = date('Y');
	    $record->save();


	    return [
		    'message'=>'New Training Budget Created',
		    'data'=>$record,
		    'error'=>false
	    ];



    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($path)
    {
        //

	    if ($path == 'fetch-grades'){
	    	return [
	    		'list'=>Grade::where('company_id',companyId())->get()
		    ];
	    }
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
        $record = Tr_TrainingBudget::find($id);
	    $record->dep_id = request()->get('dep_id');
	    $record->training_budget_name = request()->get('training_budget_name');
	    $record->allocation_total = request()->get('allocation_total');
	    $record->save();


	    return [
		    'message'=>'Saved successfully',
		    'error'=>false
	    ];

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

	    $record = Tr_TrainingBudget::find($id);
	    $record->delete();

	    return [
		    'message'=>'Removed successfully',
		    'error'=>false
	    ];

    }

}
