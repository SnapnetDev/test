<?php

namespace App\Http\Controllers;

use App\Tr_OfflineTraining;
use App\Tr_TrainingBudget;
use App\Tr_UserOfflineTraining;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Auth;

class TrUserOfflineTrainingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    	$user_id = request('user_id');
    	$prefs = [];

    	// | cool ...
	    // | user_training
        // | $query = (new Tr_OfflineTraining)->newQuery();

	    if (request()->filled('dep_id')){ //dep_id is user_id
	    	$prefs['dep_id'] = $user_id;
	    }


	    if (request()->filled('role_id')){ //role_id is user_id
		    $prefs['role_id'] = $user_id;
	    }


	    if (request()->filled('group_id')){ //group_id is the user_id in this case.
		    $prefs['group_id'] = $user_id;
	    }


	    if (request()->filled('enrolled')){ //enrolled is the user_id
		    $prefs['enrolled'] = $user_id;
	    }


	    if (request()->filled('completed')){ //completed is the user_id
		    $prefs['completed'] = $user_id;
	    }

	    if (request()->filled('in_progress')){ //in_progress is the user_id
		    $prefs['in_progress'] = $user_id;
	    }


	    if (request()->filled('online')){ //in_progress is the user_id
		    $prefs['online'] = true;
	    }

	    $list = $this->filterEngine($prefs)->get();

	    //attach user profile.
	    foreach ($list as $k=>$v){

	    	//training_plan_id
            //user_id
		    $training_plan_id = $v->id;
		    $list[$k]->user = null;
		    if ($this->isEnrolled($user_id, $training_plan_id)){
		       	$list[$k]->user = $this->getEnrolledUser($user_id, $training_plan_id);
		    }
		    //training_budget

	    }


        //user_training

	    return [
	    	'list'=>$list,
		    'available_training'=>$this->filterEngine([])->count(),
		    'enrolled_training'=>$this->filterEngine(['enrolled'=>$user_id])->count(),
		    'completed_training'=>$this->filterEngine(['completed'=>$user_id])->count(),
		    'in_progress_training'=>$this->filterEngine(['in_progress'=>$user_id])->count()
	    ];


    }

    private function isEnrolled($user_id,$training_plan_id){

       $queryCheck = (new Tr_UserOfflineTraining)->newQuery();

       $queryCheck = $queryCheck->where('user_id',$user_id)->where('training_plan_id',$training_plan_id);

       return $queryCheck->exists();

    }


    private function getEnrolledUser($user_id,$training_plan_id){

	    $queryCheck = (new Tr_UserOfflineTraining)->newQuery();

	    $queryCheck = $queryCheck->where('user_id',$user_id)->where('training_plan_id',$training_plan_id);

	    return $queryCheck->first();

    }
    
    
    private function getExistingUsage($user_id){

    	$query = (new Tr_OfflineTraining)->newQuery();
    	$query = $query->whereHas('training_users',function(Builder $builder) use ($user_id){
    		return $builder->whereHas('user',function(Builder $builder) use ($user_id){
    			return $builder->where('id',$user_id);
		    });
	    })->sum('cost_per_head');
    	return $query;

    }

    private function userCanAffordTraining($user_id,$training_plan_id){
    	//training_budget
	    //$userQuery = (new User)->newQuery();

	    $trainingBudgetQuery = (new Tr_TrainingBudget)->newQuery();

	    $trainingBudgetQuery =  $trainingBudgetQuery->whereHas('department',function(Builder $builder) use ($user_id){
	    	return $builder->whereHas('users',function(Builder $builder) use ($user_id){
	    		return $builder->where('users.id',$user_id);
		    });
	    });

	    if (!$trainingBudgetQuery->exists()){
	    	return false;
	    }

	    $budget = $trainingBudgetQuery->first()->allocation_total;


	    $trainingPlan = Tr_OfflineTraining::find($training_plan_id);
	    $amount = $trainingPlan->cost_per_head;

	    return ($budget >= ($amount + $this->getExistingUsage($user_id)));


//	    $userQuery = $userQuery->where('id',$user_id)->whereHas('grade',function(Builder $builder){
//	    	return $builder->whereHas('training_budget',function(Builder $builder){
//	    		return $builder;
//		    });
//	    });





    }


    private function filterEngine($prefs=[]){

	    $query = (new Tr_OfflineTraining)->newQuery();

	    if (isset($prefs['dep_id'])){ //dep_id is user_id
		    $query = $query->whereHas('department',function(Builder $builder) use ($prefs){
			    return $builder->whereHas('users',function(Builder $builder) use ($prefs){
				    return $builder->where('users.id',$prefs['dep_id']);
			    });
//	    		return $builder->where('id',request('dep_id'));
		    });
	    }


	    if (isset($prefs['role_id'])){ //role_id is user_id
		    $query = $query->whereHas('role',function(Builder $builder) use ($prefs){
			    return $builder->whereHas('users',function(Builder $builder) use ($prefs){
				    return $builder->where('users.id',$prefs['role_id']);
			    });
			    //return $builder->where('id',request('role_id'));
		    });
	    }


	    if (isset($prefs['group_id'])){ //group_id is the user_id in this case.
		    $query = $query->whereHas('training_groups',function(Builder $builder) use ($prefs){
			    return $builder->whereHas('group',function(Builder $builder) use ($prefs){
				    return $builder->whereHas('users',function(Builder $builder) use ($prefs){
					    return $builder->where('users.id',$prefs['group_id']);
				    });
			    });
		    });
	    }


	    if (isset($prefs['enrolled'])){ //enrolled is the user_id

		    $query = $query->whereHas('training_users',function(Builder $builder) use ($prefs){
			    return $builder->where('user_id',$prefs['enrolled'])->where('status',1);
		    });

	    }


	    if (isset($prefs['completed'])){ //completed is the user_id

		    $query = $query->whereHas('training_users',function(Builder $builder) use ($prefs){
			    return $builder->where('user_id',$prefs['completed'])->where('status',1)->where('progress','100');
		    });

	    }


	    if (isset($prefs['in_progress'])){ //in_progress is the user_id

		    $query = $query->whereHas('training_users',function(Builder $builder) use ($prefs){
			    return $builder->where('user_id',$prefs['in_progress'])->where('status',1)->where('progress','<',100);
		    });

	    }


	    if (isset($prefs['online'])){ //in_progress is the user_id

		    $query = $query->where('type','online');

	    }

	    $query = $query->where('status',1);

	    return $query;

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
        if (!$request->filled('type')){
        	return [
        		'message'=>'Invalid action type!',
		        'error'=>true
	        ];
        }
        $type = $request->get('type');

        //check for previous enrollment.
	    $training_plan_id = $request->get('training_plan_id');
	    $user_id = $request->get('user_id');
	    $queryCheck = (new Tr_UserOfflineTraining)->newQuery();
	    $queryCheck = $queryCheck->where('training_plan_id',$training_plan_id)->where('user_id',$user_id);

	    $obj = new Tr_UserOfflineTraining;
	    if ($queryCheck->exists()){
	      $obj = $queryCheck->first();
	    }

	    $obj->training_plan_id = $training_plan_id;
	    $obj->user_id = $user_id;


	    //userCanAffordTraining
	    if (!$this->userCanAffordTraining($user_id, $training_plan_id)){
           return [
           	'message'=>'Exceeded personal budget!',
	        'error'=>true
           ];
	    }


        if ($type == 'enroll'){
	    	$obj->status = 1;
	    	$obj->save();
	    	return [
	    		'message'=>'Employee enrolled.',
			    'error'=>false
		    ];
        }


        if ($type == 'unenroll'){
	        $obj->status = 0;
	        $obj->save();
	        return [
		        'message'=>'Employee un-enrolled.',
		        'error'=>false
	        ];
        }

	    return [
		    'message'=>'Invalid action definition!',
		    'error'=>true
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

        if ($path == 'myfeedback'){
            return view('training_new.crud.my.index');
        }

        if ($path == 'fetch-my-training'){

            $userId = Auth::user()->id;

            $queryTraining = (new Tr_UserOfflineTraining)->newQuery();
            //->where('status',1)
            $queryTraining = $queryTraining->where('user_id',$userId);
            $list = $queryTraining->get();
            foreach ($list as $k=>$item){
              $dt = Carbon::parse($item->created_at);
              $list[$k]->year_of_training = $dt->year;
            }

            return [
                'list'=>$list
            ];

        }


        if ($path == 'enrolled-users' && request()->filled('training_id')){

          $training_id = request('training_id');

          $query = (new Tr_UserOfflineTraining)->newQuery();

          $query = $query->whereHas('training',function(Builder $builder) use ($training_id){

              return $builder->where('id',$training_id);

          });

          return [
              'list'=>$query->get()
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
        //handle user feedbacks here
        //from type=feedback
        $obj = Tr_UserOfflineTraining::find($id);

        $obj->progress_notes = $request->get('progress_notes');
        $obj->feedback = $request->get('feedback');
        $obj->completed = $request->filled('completed')? 1 : 0;
        $obj->progress = $request->get('progress');
        $obj->rating = $request->get('rating');


        if (request()->file('file')){
            $image = request()->file('file')->store('user_training_feedback',['disk'=>'uploads']);
            $obj->upload1 = $image;
//            $this->$fileKey = $image;
//            $callback($image);
        }

        $obj->save();


        return [
            'message'=>'Feedback sent',
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
    }
}
