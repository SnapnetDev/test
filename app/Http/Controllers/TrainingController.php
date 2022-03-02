<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use MaddHatter\LaravelFullcalendar\Facades\Calendar;
use Auth;
use DB;
use Excel;

class TrainingController extends Controller
{
  //DISPLAY ALL TRAINING
  public function index()
	{
		$trainings = \App\TrainingRecommended::orderBy('id', 'desc')->get();
		$train = \App\Training::orderBy('training_name', 'desc')->get();


		$company=\App\Company::find(companyId());
		$users = $company->users()->orderBy('name', 'asc')->get();
		$status = \App\StatusAndStage::where('type', '1')->orderBy('name', 'asc')->get();
		$approval = \App\StatusAndStage::where('type', '2')->orderBy('name', 'asc')->get();
		$trainees = $company->users()->orderBy('name', 'asc')->get();
		$train_status = \App\StatusAndStage::where('type', '1')->orderBy('name', 'asc')->get();

		return view('training.index', compact('trainings', 'users', 'status', 'approval', 'trainees', 'train_status', 'train'));
	}

  //DISPLAY ALL TRAINING
  public function newtraining()
	{
		$trainings = \App\Training::orderBy('id', 'desc')->get();

		$company=\App\Company::find(companyId());
		$users = $company->users()->orderBy('name', 'asc')->get();
		$groups = \App\UserGroup::orderBy('name', 'asc')->get();
		$budgets = \App\TrainingBudget::orderBy('purpose', 'asc')->get();

		return view('training.training', compact('trainings', 'users', 'groups', 'budgets'));
	}

	public function save_training_user(Request $request)
  {
      try 
      {
        $no_of_users = count($request->input('trainee_id'));
        // $this->validate($request, ['name'=>'required|min:2']);
        $company_id = companyId();
        $training = \App\Training::find($request->training_id);
       
        if($no_of_users > 0)
        {
        	$training->users()->detach();
          for ($i=0; $i <$no_of_users ; $i++) 
          {
            $training->users()->attach($request->trainee_id[$i],['created_at' => date('Y-m-d H:i:s'), 'created_at' => date('Y-m-d H:i:s'), 'updated_at'=>date('Y-m-d H:i:s')]);
          }
        }
        	return redirect()->route('training')->with('success', 'User(s) Added To Training Successfully');
      } 
      catch (\Exception $e) 
      {
      	return  redirect()->route('training')->with('error', 'Sorry, An Error Occurred, Please Check Your Input and Try Again. ' .$e->getMessage());
      }

  }

	public function save_training_group(Request $request)
  {
      try 
      {
        $no_of_groups = count($request->input('group_id'));
        $company_id = companyId();
        $training = \App\Training::find($request->training_id);
        //dd($training);
       
        if($no_of_groups > 0)
        {
        	$training->groups()->detach();
          for ($i=0; $i <$no_of_groups ; $i++) 
          {
            $training->groups()->attach($request->group_id[$i],['created_at' => date('Y-m-d H:i:s'), 'created_at' => date('Y-m-d H:i:s'), 'updated_at'=>date('Y-m-d H:i:s')]);
          }
        }
        	return redirect()->route('training')->with('success', 'Group(s) Added To Training Successfully');
      } 
      catch (\Exception $e) 
      {
      	return  redirect()->route('training')->with('error', 'Sorry, An Error Occurred, Please Check Your Input and Try Again. ' .$e->getMessage());
      }

  }


	//VIEW TO CREATE NEW TRAINING
  public function create_training()
	{
		$company=\App\Company::find(companyId());
		$dept = $company->departments()->orderBy('name', 'asc')->get();
		return view('training.create_training', compact('dept'));
	}


	//VIEW TO EDIT TRAINING
  public function edit_training(Request $request)
	{
		$id = $request->id;
		$train = \App\Training::where('id', $id)->first();

		$company=\App\Company::find(companyId());
		$users = $company->users()->orderBy('name', 'asc')->get();
		$groups = \App\UserGroup::orderBy('name', 'asc')->get();
		$one_dept =  \App\Department::where('id', $train->department_id)->first();							$dept = $company->departments()->orderBy('name', 'asc')->get();

		return view('training.edit_training', compact('id', 'train', 'one_dept', 'dept', 'train_users', 'users', 'groups'));
	}

  //VIEW TO EDIT on going TRAINING
  public function edit_ongoing_training(Request $request)
  {
    $id = $request->id;
    $train = \App\TrainingRecommended::where('id', $id)->first();

    $company=\App\Company::find(companyId());
    $users = $company->users()->orderBy('name', 'asc')->get();
    $groups = \App\UserGroup::orderBy('name', 'asc')->get();
    $one_training =  \App\Training::where('id', $train->training_id)->first();                 $training = \App\Training::orderBy('training_name', 'asc')->get();
    $one_trainee =  $company->users()->where('id', $train->trainee_id)->first();               $trainees = $company->users()->orderBy('name', 'asc')->get();
    $one_status = \App\StatusAndStage::where('id', $train->status_id)->first();              $status = \App\StatusAndStage::where('type', '1')->orderBy('name', 'asc')->get();

    return view('training.edit_ongoing_training', compact('id', 'train', 'users', 'groups', 'one_training', 'training', 'one_trainee', 'trainees', 'one_status', 'status'));
  }


	//VIEW TO VIEW TRAINING
  public function view_training(Request $request)
	{
		$id = $request->id;
		$train = \App\Training::where('id', $id)->first();
		$one_dept = \App\Department::where('id', $train->department_id)->first();

		$company=\App\Company::find(companyId());
		$one_trainee = $company->users()->where('id', $train->trainee_id)->first();		

		return view('training.view_training', compact('id', 'train', 'one_dept'));
	}


	//VIEW TO ADD NEW TRAINING
  public function save_training(Request $request)
	{
        try
        {
            //INSERTING NEW Recommended Training
            $saved = \App\Training::updateOrCreate
            (['id' => $request->id],
            [
                 'training_name' => $request->training_name,
                 'training_mode' => $request->training_mode,
                 'duration' => $request->duration,
                 'department_id' => $request->department_id,
                 'remark' => $request->remark,
                 'created_by' => \Auth::user()->id,
             ]);

            //send mail
            //self::send_all_mail("Training");
            if($saved && $request->id == '')
            {   
                return redirect()->route('training')->with('success', 'New Recommended Training Details Created Successfully');
            }
            else if($saved && $request->id != '')
            {
            	return redirect()->route('training')->with('success', 'Recommended Training Details Updated Successfully');
            }
        } 
        catch (\Exception $e) 
        {
          return  redirect()->route('training.training')->with('error', 'Sorry, An Error Occurred, Please Check Your Input and Try Again. ' .$e->getMessage());
        }
        
        
    }

  public function delete_training_user(Request $request)
	{
		$training =\App\Training::find($request->training_id);
	   if ($training) 
	   {
	      $training->users()->detach($request->user_id);
	      return 'success';
	   }
	}

  public function approve_training_user(Request $request)
	{
		$training =\App\Training::find($request->training_id);
	   if ($training) 
	   {
	      $data = array
          (
              'stage_id' => '1', 'updated_at' => date('Y-m-d h:i:s')
          );
          DB::table('training_user')->where('user_id', $request->user_id)->update($data);
	      return 'success';
	   }
	}

  public function decline_training_user(Request $request)
	{
		$training =\App\Training::find($request->training_id);
	   if ($training) 
	   {
	      $data = array
          (
              'stage_id' => '2', 'updated_at' => date('Y-m-d h:i:s')
          );
          DB::table('training_user')->where('user_id', $request->user_id)->update($data);
	      return 'success';
	   }
	}


  //DISPLAY ALL USER TRAINING
  public function my_training()
	{
		$train_details = DB::table('training_user')->where('user_id', Auth::user()->id)->first();
		$ongoings = \App\TrainingRecommended::where('id', $train_details->user_id)->where('status_id', 2)->get();
    $recommendeds = \App\Training::where('id', $train_details->id)->get();
    $completeds = \App\TrainingRecommended::where('id', $train_details->user_id)->where('status_id', 3)->get();

		$budgets = \App\TrainingBudget::where('trainee_id', \Auth::user()->id)->orderBy('id', 'desc')->get();

		return view('training.mytraining', compact('ongoings', 'budgets', 'train_details', 'completeds', 'recommendeds'));
	}

	public function training_info(Request $request)
	{
		$training=\App\Training::where('id', $request->training_id)->with('users')->first();
   		return $training;
	}



	//AJAX FUNCTION
  public function getTraineeDetails(Request $request)
  {
      $training_details = \App\Training::where('id', $request->id)->get();

      return response()->json($training_details);
  }


	//VIEW TO ADD NEW TRAINING
  public function save_start_training(Request $request)
	{
        try
        {
            //INSERTING NEW Recommended Training
            $saved = \App\TrainingRecommended::updateOrCreate
            (['id' => $request->id],
            [
                 'training_id' => $request->training_id,
                 'training_mode' => $request->training_mode,
                 'duration' => $request->duration,
                 'proposed_start_date' => date('Y-m-d', strtotime($request->proposed_start_date)),
                 'proposed_end_date' => date('Y-m-d', strtotime($request->proposed_end_date)),
                 'status_id' => $request->status_id,
                 'created_by' => \Auth::user()->id,
             ]);
            $no_of_trainees=count($request->input('trainee_id'));
            $saved->trainees()->detach();
           for ($i=0; $i <$no_of_trainees ; $i++) { 
             $saved->trainees()->attach($request->trainee_id[$i],['created_at'=>date('Y-m-d h:i:s'),'updated_at'=>date('Y-m-d h:i:s')]);
           }
              
            

            //send mail
            //self::send_all_mail("Training");
            if($saved && $request->id == '')
            {   
                return redirect()->route('index')->with('success', 'Training for User Have Started');
            }
            else if($saved && $request->id != '')
            {
            	return redirect()->route('index')->with('success', 'Training for User Have Started');
            }
        } 
        catch (\Exception $e) 
        {
          return  redirect()->route('index')->with('error', 'Sorry, An Error Occurred, Please Check Your Input and Try Again. ' .$e->getMessage());
        }
        
        
    }











    //BUDGET
    //DISPLAY ALL BUDGET
  public function budget()
	{
		$budgets = \App\TrainingBudget::orderBy('id', 'desc')->get();

		$users = \App\User::orderBy('name', 'asc')->get();
		$status = \App\StatusAndStage::where('type', '2')->orderBy('name', 'asc')->get();
		return view('training.budget', compact('budgets', 'users', 'status'));
	}


	//VIEW TO CREATE NEW TRAINING
  public function create_budget()
	{
		$trainee = \App\User::orderBy('name', 'asc')->get();
		$status = \App\StatusAndStage::where('type', '2')->orderBy('name', 'asc')->get();
		return view('training.create_budget', compact('trainee', 'status'));
	}


	//VIEW TO EDIT BUDGET
  public function edit_budget(Request $request)
	{
		$id = $request->id;
		$budget = \App\TrainingBudget::where('id', $id)->first();

		$one_trainee = \App\User::where('id', $budget->trainee_id)->first();							$trainee = \App\User::orderBy('name', 'asc')->get();
		$one_status= \App\StatusAndStage::where('id', $budget->status_id)->first();						$status = \App\StatusAndStage::where('type', '2')->orderBy('name', 'asc')->get();

		return view('training.edit_budget', compact('id', 'budget', 'one_trainee', 'trainee', 'one_status', 'status'));
	}


	//VIEW TO VIEW BUDGET
  public function view_budget(Request $request)
	{
		$id = $request->id;
		$budget = \App\TrainingBudget::where('id', $id)->first();

		$one_trainee = \App\User::where('id', $budget->trainee_id)->first();					
		$one_status = \App\StatusAndStage::where('id', $budget->status_id)->first();		

		return view('training.view_budget', compact('id', 'budget', 'one_trainee', 'one_status'));
	}


	//VIEW TO ADD NEW BUDGET
  public function save_budget(Request $request)
	{
        try
        {
            //INSERTING NEW Recommended Budget
            $saved = \App\TrainingBudget::updateOrCreate
            (['id' => $request->id],
            [
                 'trainee_id' => $request->trainee_id,
                 'purpose' => $request->purpose,
                 'amount' => $request->amount,
                 'status_id' => $request->status_id,
                 'created_by' => \Auth::user()->id,
             ]);

            //send mail
            //self::send_all_mail("Budget");


            if($request->ajax())
             {    
                 return response()->json(['status'=>'ok', 'success'=>'New Training Budget Details Created Successfully.']);
             }
             else
             {
	            if($saved && $request->id == '')
	            {   
	                return redirect()->route('training.budget')->with('success', 'New Training Budget Details Created Successfully');
	            }
	            else if($saved && $request->id != '')
	            {
	            	return redirect()->route('training.budget')->with('success', 'Training Budget Details Updated Successfully');
	            }
	        }
        } 
        catch (\Exception $e) 
        {
          return  redirect()->route('training.budget')->with('error', 'Sorry, An Error Occurred, Please Check Your Input and Try Again. ' .$e->getMessage());
        }
        
        
    }




  public function add_budget_to_training(Request $request)
	{
	   try
        {
		   $training =\App\Training::find($request->training_id);
		   if ($training) 
		   {
		      $data = array
	          (
	              'budget_id' => $request->budget_id, 'updated_at' => date('Y-m-d h:i:s')
	          );
	          $success = DB::table('training_user')->where('training_id', $request->training_id)->update($data);
	          if($success)
	          {
	          	 return redirect()->route('training')->with('success', 'Training Budget Added To Training Successfully');
	          }
	          else
	          {
	          	 return  redirect()->route('training')->with('error', 'Sorry, An Error Occurred, Please Check Your Input and Try Again. ');
	          }		      
		   }
        } 
        catch (\Exception $e) 
        {
          return  redirect()->route('training')->with('error', 'Sorry, An Error Occurred, Please Check Your Input and Try Again. ' .$e->getMessage());
        }
	}


}
