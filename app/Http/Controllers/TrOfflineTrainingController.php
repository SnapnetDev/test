<?php

namespace App\Http\Controllers;

use App\Department;
use App\Role;
use App\Services\TrOfflineTrainingService;
use App\Tr_OfflineTraining;
use App\Tr_OfflineTrainingGroup;
use App\User;
use App\UserGroup;
use App\Workflow;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Auth;

class TrOfflineTrainingController extends Controller
{
    //

	function index(){

	    $data = [];

	    $data['departments'] = Department::where('company_id',companyId())->get();
	    $data['roles'] = Role::get();
	    $data['groups'] = UserGroup::where('company_id',companyId())->get();

//	    dd(Auth::user()->role);
		$data['employees'] = Auth::user()->employees;
	    if (Auth::user()->role->name == 'Super Admin'){
		    $data['employees'] = User::all();
	    }


//	    dd($data);


		return view('training_new.crud.index',$data);

	}


	function store(){

	   $workflow_name_check = 'training_workflow';

	   $queryCheck = (new Workflow)->newQuery();

	   $queryCheck = $queryCheck->where('name',$workflow_name_check);

	   if (!$queryCheck->exists()){
	   	return [
	   		'message'=>'Workflow with name (' . $workflow_name_check .') not found, go to settings and add workflow with that name!',
		    'error'=>true
	    ];
	   }

	   $workflowObject = $queryCheck->first();

       $obj = new Tr_OfflineTraining;

	    $obj->cost_per_head = request('cost_per_head');
		$obj->dep_id = request('dep_id');
		$obj->enroll_instructions = request('enroll_instructions');
		$obj->grand_total = str_replace(',', '',  request('grand_total'));
		$obj->name = request('name');
		$obj->number_of_enrollees = request('number_of_enrollees');
		$obj->resource_url = request('resource_url');
		$obj->role_id = request('role_id');
		$obj->train_start = request('train_start');
		$obj->train_stop = request('train_stop');
		$obj->status = 0;
		$obj->line_manager_id = Auth::user()->id;
//		$obj->cost_per_head = request('cost_per_head');

		$obj->type = request()->filled('type')? 'online' : 'offline';

	   $obj->save();

	   if (request()->filled('group_id')){
	   	$group_ids = request('group_id');
	   	foreach ($group_ids as $groupId){
	   		$objTrainingGroup = new Tr_OfflineTrainingGroup;
	   		$objTrainingGroup->training_plan_id = $obj->id;
	   		$objTrainingGroup->group_id = $groupId;
	   		$objTrainingGroup->save();
	    }
	   }

	   $trainingService = new TrOfflineTrainingService;
	   $trainingService->startStage($workflowObject->id, $obj->id, Auth::user());


	   return [
	   	 'message'=>'Training Plan Created Successfully',
		 'error'=>false
	   ];

	}

	function update($id){


		$obj = Tr_OfflineTraining::find($id);

		if (is_null($obj)){
			return [
				'message'=>'Invalid record!',
				'error'=>true
			];
		}

		$obj->cost_per_head = request('cost_per_head');
		$obj->dep_id = request('dep_id');
		$obj->enroll_instructions = request('enroll_instructions');
		$obj->grand_total = str_replace(',', '',  request('grand_total'));
		$obj->name = request('name');
		$obj->number_of_enrollees = request('number_of_enrollees');
		$obj->resource_url = request('resource_url');
		$obj->role_id = request('role_id');
		$obj->train_start = request('train_start');
		$obj->train_stop = request('train_stop');
//		$obj->status = 0;
//		$obj->line_manager_id = Auth::user()->id;
//		$obj->cost_per_head = request('cost_per_head');

		$obj->type = request()->filled('type')? 'online' : 'offline';

		$obj->save();

		if (request()->filled('group_id')){

			$garbageOldQuery = (new Tr_OfflineTrainingGroup)->newQuery();
			$garbageOldQuery = $garbageOldQuery->where('training_plan_id',$obj->id)->get();

			foreach ($garbageOldQuery as $item){
				$item->delete();
			}


			$group_ids = request('group_id');
			foreach ($group_ids as $groupId){
				$objTrainingGroup = new Tr_OfflineTrainingGroup;
				$objTrainingGroup->training_plan_id = $obj->id;
				$objTrainingGroup->group_id = $groupId;
				$objTrainingGroup->save();
			}
		}

//		$trainingService = new TrOfflineTrainingService;
//		$trainingService->startStage($workflowObject->id, $obj->id, Auth::user());


		return [
			'message'=>'Training Plan Saved',
			'error'=>false
		];

	}

	function destroy($id){
		$obj = Tr_OfflineTraining::find($id);

		if ($obj->status == 1){
			return [
				'message'=>'Cannot remove an already approved training!',
				'error'=>true
			];
		}

		$obj->delete();
		return [
			'message'=>'Training Plan removed',
			'error'=>false
		];

	}


	function show($path){

//		$roles = Role::all();
//		$departments = Department::where('company_id',companyId())->get();
//		$groups = UserGroup::where('company_id',companyId())->get();


		if ($path == 'fetch'){

			$query = (new Tr_OfflineTraining)->newQuery();

			if (request()->filled('dep_id')){
				$query = $query->whereHas('department',function(Builder $builder){
					return $builder->where('id',request('dep_id'));
				});
			}

			if (request()->filled('role_id')){
				$query = $query->whereHas('role',function(Builder $builder){
					return $builder->where('id',request('role_id'));
				});
			}

			if (request()->filled('group_id')){
				$query = $query->whereHas('training_groups',function(Builder $builder){
					return $builder->whereHas('group',function(Builder $builder){
						return $builder->where('id',request('group_id'));
					});
				});
			}

			return [
				'list'=>$query->get()
			];
		}

		if ($path == 'fetch-roles'){
			return [
				'list'=>Role::all()
			];
		}


		if ($path == 'fetch-departments'){
			return [
				'list'=>Department::where('company_id',companyId())->get()
			];
		}

		if ($path == 'fetch-groups'){
			return [
				'list'=>UserGroup::where('company_id',companyId())->get()
			];
		}


	}



}
