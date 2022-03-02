<?php

namespace App\Traits;

use App\Traits\ViewTraits\CourseViewTrait;
use Illuminate\Http\Request;
use App\Traits\Micellenous;
use App\User;

trait Performance {
	use Micellenous;

	use CourseViewTrait;


	public function processGet($route,Request $request){
		switch ($route) {
			case 'employee':
				# code...
				return $this->employee($request);
				break;
				case 'list':
				# code...
				return $this->index($request);
				break;
			case 'toggleSeason':
				# code...
				return $this->toggleSeason($request);
				break;
			case 'settings':
				return $this->settings($request);
				# code...
				break;
			case 'setQuarter':
				# code...
				return $this->setQuarter($request);
				break;
			default:
				return $this->index($request);
				break;
		}
		 
	}


	public function processPost(Request $request){

		try{
		switch ($request->type) {
			case 'saveComment':
				# code...
			     return $this->saveComment($request);
				break;
			case 'addStretch':
				# code...
				return $this->addStretch($request);
				break;
			case 'addProgressReport':
				# code...
				return $this->addProgressReport($request);
				break;
			case 'saveReportComment':
				# code...
				return $this->saveReportComment($request);
				break;
			case 'addKpi':
				# code...
				return $this->addKpi($request);
				break;
			case 'addPilot':
				# code...
				return $this->addPilot($request);
				break;
			case 'deleteGoal':
				# code...	
				return $this->deleteGoal($request);
				break;
			case 'publishPilot':
				# code...
				return $this->publishPilot($request);
				break;
			case 'saveSettings':
				# code...
				return $this->saveSettings($request);
				
				break;
			default:
				# code...
				break;
		}
	}
	catch(\Exception $ex){

		return response()->json(['status'=>'error','message'=>$ex->getMessage()]);
	}

	}
	

	private function addKpi(Request $request){
		if(session()->has('company_id')){
            $compid=session('company_id');
        }
        else{
            $compid=\Auth::user()->company_id;
        }
			$checkTarget=\App\kpi::whereHas('kpiassignedto',function($query) use ($request){

							$query->where(['user_id'=>$request->assignedto])
									->whereYear('created_at',session('FY'));
			})->selectRaw("SUM(targetweight) as weight")->value('weight');

			if(($checkTarget+$request->targetweight)>100){
				$diff=100-$checkTarget;
				throw new \Exception("Sum of target weight must not be greater than 100. Please enter value not greater than $diff");		
			}

			 $kpi=\App\kpi::updateOrCreate(['id'=>$request->formid],
			 	[
			 	 'deliverable'=>$request->deliverables,
			 	 'targetweight'=>$request->targetweight,
			 	 'targetamount'=>$request->targetamount,
			 	 'status'=>0,
			 	 'company_id'=>$compid,
			 	 'comment'=>$request->comment,
			 	 'created_by'=>$request->user()->id,
			 	 'created_at'=>date('Y-m-d H:i:s'),
			 	 'assigned_to'=>$request->assignedto,
			 	 'department_id'=>$request->department_id
			 	]);

			 $kpiassignedto=\App\kpiassignedto::updateOrCreate(['user_id'=>$request->assignedto,'kpi_id'=>$kpi->id],['user_id'=>$request->assignedto,'kpi_id'=>$kpi->id]);

			 return response()->json(['status'=>'success','message'=>'Operation Successfull']);

	}

	private function saveReportComment(Request $request){

			$lmrepcomment=\App\progressreport::where('id',$request->reportid)->update(['reportcomment'=>$request->comment]);
		//Notify Employee that he has been commented
			return response()->json(['status'=>'success','message'=>'Operation Successfull']);
	}

    private function toggleSeason(Request $request){
    	if($request->user()->performanceseason()==1){
    		$performance=0;
    		$msg='Stopped';
    	}
    	else{
    		$performance=1;
    		$msg="Started";
    	}
    	$toggle=\App\PerformanceSeason::where('id',1)->update(['season'=>$performance]);
    	//Notify All Stackholders
    	return response()->json(['status'=>'success','message'=>"Performance Season $msg"]);

    }
 private function convdate($date){

 		return date('Y-m-d',strtotime($date));

 }

    private function addProgressReport(Request $request){

      $savereport=\App\progressreport::create([
      			'report'=>$request->progressreport,
      			'from'=>self::convdate($request->reportfroms),
      			'to'=>self::convdate($request->reporttos),
      			'achievedamount'=>$request->achievedamount,
      			'reportcomment'=>$request->commentrep,
      			'status'=>$request->status,
      			'emp_id'=>$request->emp_id,
      			'kpiid'=>$request->kpiid
      		]);
      //send notification
     return response()->json(['status'=>'success','message'=>'Report successfully added']);
    }

	private function saveComment(Request $request){
		// session(['FY'=>date('Y')]);

		 $data=['goal_id'=>$request->goalid,'emp_id'=>$request->empid,'quarter'=>$request->quarter];
		 $rate= $request->has('rating') ? $request->rating : 0;

		 if($request->user()->id==$request->empid){
		 	$rateColumn='lm_rate';
		 	$comment='emp_comment';
		 }
		 else{
		 	$rateColumn=$this->resolveRate($request->goalid);
		 	$comment=$this->resolveCommentRow($request->goalid);

		 }

 		 $data2=[$rateColumn=>$rate,$comment=>$request->comment];
 		 // dd($data2);
		$updateRatingComment=\App\Rating::where($data)->whereYear('created_at',session('FY'))->update($data2);
		 $data=array_merge($data,$data2);
		if(!$updateRatingComment){

			$createRating=\App\Rating::create($data);
		}
		
		return response()->json(['status'=>'success','message'=>'Comment Successfully Added']);
	}

	private function addStretch(Request $request){
		if(in_array(\Auth::user()->role->manages,['dr','all'])){
		$goalCatid=$request->has('goalType') ? $request->goalType : 1;
		if(session()->has('company_id')){
            $compid=session('company_id');
        }
        else{
            $compid=\Auth::user()->company_id;
        }
			$data=['objective'=>$request->objective,
					 'commitment'=>$request->commitment, 
					 'user_id'=>$request->user()->id,
					 'assigned_to'=>$request->emp_id,
					  'goal_cat_id'=>$goalCatid,
					  'quarter'=>$this->predictQuarter(),
					  'company_id'=>$compid
					];
		
			$saveStrech=\App\Goal::updateOrcreate(['id'=>$request->id],$data);
		return response()->json(['status'=>'success','message'=>'Strech Goal Successfully Applied']);
		}
		else{
			throw new \Exception("You Donot Have Permission to perform this action");
			
		}

	}

	private function predictQuarter(){
		$getQuarter=\Auth::user()->getquarter();
		return $getQuarter;
	}

	private function settings(Request $request){
	// return $request->all();
		if(session()->has('company_id')){
   			$compid=session('company_id');
   		}
   		else{
   			$compid=\Auth::user()->company_id;
   		}
		$published=\App\PublishedGoalFY::where('year',session('FY'))->value('published');
		$pilots=\App\Goal::where('goal_cat_id',2)->whereYear('created_at',session('FY'))->get();
		$companys=\App\Company::get();
		$performanceSettings=\App\PerformanceSeason::where('company_id',$compid)->first();
		$performanceSettings =is_null($performanceSettings) ? [] : $performanceSettings->toArray();
		// dd($performanceSettings);
		// Notify All Employees
		return view('settings.performancesettings.index',compact('pilots','published','performanceSettings','companys'));
	}



	private function resolveRate($id){
		$getCat=\App\Goal::where('id',$id)->value('goal_cat_id');
		if(\Auth::user()->role->permissions->contains('constant', 'add_hr_comment') && $getCat==2){

			return 'admin_rate';
		}
		else if(in_array(\Auth::user()->role->manages, ['dr','all'])){
			return 'lm_rate';
		} 
		else {

			return 'lm_rate';
		}

	}
	private function resolveCommentRow($id){

			$getCat=\App\Goal::where('id',$id)->value('goal_cat_id');
		if(\Auth::user()->role->permissions->contains('constant', 'add_hr_comment') && $getCat==2){

			return 'admin_comment';

		}

		else if(in_array(\Auth::user()->role->manages, ['dr','all'])){
			return 'lm_comment';
		}
		 
		else {

			return 'emp_comment';
		}


	}
	private function  employee(Request $request){ 
		$employee=\App\User::where('id',$request->id)->first();
    	$fiscal= $this->fiscalYear(); 
    	$pilots= $this->pilotGoals();
    	$quarter=$request->has('quarter') ? $request->quarter: $this->predictQuarter();
    	$date=$request->has('date') ? $request->date: date('Y');
     	$yearquarter=(object) ['quarter'=>$quarter,'year'=>$date,'emp_id'=>$request->id];

     	$lmGoals= $this->goals(1,$request->id,$quarter,$date);

     	$idps= $this->goals(3,$request->id,$quarter,$date);
  	
     	$carasps= $this->goals(4,$request->id,$quarter,$date);
 	 	$kpis= $this->getkpis($request,$quarter);
 	 	$departments=\App\Department::get();

 	 	$training = $this->getCourseListingForUser($request->id);

		return view('performance.employee',compact('employee','fiscal','lmGoals','pilots','yearquarter','carasps','idps','kpis','departments','training'));
	}


	   public function getkpis(Request $request,$quarter) {
              $userGetdept=\App\User::where('id',$request->id)->first();

               $kpis=\App\kpi::whereYear('created_at',date('Y')) 
               					->where(function($query) use ($request,$userGetdept){
               					$query->whereHas('kpiassignedto',function($query) use ($request){
               								$query->where('user_id',$request->id);
               					})->orwhere('department_id',$userGetdept->department);
               		 })->paginate(10);
               					  

            return $kpis;
 
       }

   private function addPilot(Request $request){

      	if(session()->has('company_id')){
            $compid=session('company_id');
        }
        else{
            $compid=\Auth::user()->company_id;
        } 
       if($request->user()->role->permissions->contains('constant', 'add_pilot')){

       	$unpublish=\App\PublishedGoalFY::where('year',session('FY'))->delete();
      	$savepilot=\App\Goal::updateOrcreate(['id'=>$request->id],['objective'=>$request->objective,'commitment'=>$request->commitment,'goal_cat_id'=>2,'company_id'=>$compid,'user_id'=>$request->user()->id,'assigned_to'=>0]);
		 	return response()->json(['status'=>'success','message'=>'Pilot Goal Successfully Added']);

		 }
		 else{
		 	throw new \Exception("You do not have Permission  to perform this action");
		 	
		 }

      }

   private function deleteGoal(Request $request){

     if($request->user()->role->permissions->contains('constant', 'delete_pilot')){
     	$goal=\App\Goal::where('id',$request->id)->delete();
     	return response()->json(['status'=>'success','message'=>'Operation Successfull']);

     }
     else{
     		throw new \Exception("You do not have Permission  to perform this action");
     }


   }

   private function publishPilot(Request $request){

   	if($request->user()->role->permissions->contains('constant', 'publish_pilot')){
   		$publishPilot=\App\PublishedGoalFY::updateOrCreate(['year'=>session('FY')],['year'=>session('FY'),'published'=>'YES']);
   	
   		return response()->json(['status'=>'success','message'=>'Operation Successfull']);
   	}
   	else{
   		throw new \Exception("You do not have Permission  to perform this action");
   	}

   }

  private function saveSettings(Request $request){
  	// dd($request->all());
   	if($request->user()->role->permissions->contains('constant', 'performance_settings_all_company') || $request->user()->role->permissions->contains('constant', 'group_access')){
   		//All Companies
   		if($request->company_id==0){
   			$companyList=\App\Company::pluck('id');
   			foreach($companyList as $company){

   				$this->saveCompanySettings($company,$request);
   			}
   		}
   		else{
   			$this->saveCompanySettings($request->company_id,$request);
   		}
   		return response()->json(['status'=>'success','message'=>'Operation Successfull']);
   	}

   	elseif($request->user()->role->permissions->contains('constant', 'performance_settings_own_company')){
   		if(session()->has('company_id')){
   			$compid=session('company_id');
   		}
   		else{
   			$compid=\Auth::user()->company_id;
   		}	
   		$this->saveCompanySettings($compid,$request);

   		return response()->json(['status'=>'success','message'=>'Operation Successfull']);
   	}
   	else{
   		throw new \Exception("You Do Not Have Permission to perform this action");
   	}
   }


   private function saveCompanySettings($compid,Request $request){
   	$data=$request->all();
    $data['company_id']= $request->company_id==0 ? $compid : $request->company_id;
  
   	$savefiscal=\App\PerformanceSeason::updateOrCreate(['company_id'=>$compid],$data);
   	return 1;
   }

   private function setQuarter(Request $request){
    	session(['quarter',$request->quarter]);

   		return response()->json(['status'=>'success','message'=>'Operation Successfull']);
   }

}