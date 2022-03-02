<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 28/09/2020
 * Time: 22:04
 */

namespace App\Services;


use App\LoanApproval;
use App\LoanRequest;
use App\Notifications\ReuseNotification;
use App\Stage;
use App\Traits\WithWorkflowStage;
use App\User;
use Dompdf\Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Image;


//use Auth;

class LoanApprovalService
{

    use WithWorkflowStage;

    //LoanApproval::create(['stage_id'=>$stage->id,'status'=>0,'loan_request_id'=>$loan_request->id]);

	private function canApprove(LoanApproval $loanApproval,User $user){

//		(new Image)->make($source='')->save();

       $checkQuery = (new LoanApproval)->newQuery();

       return $checkQuery->where('loan_approvals.id',$loanApproval->id)->whereHas('stage',function(Builder $builder) use ($user){
	         return $builder->whereHas('user', function (Builder $builder) use ($user) {
	            return $builder->where('users.id',$user->id);
	         });
       })->orWhereHas('loan_request', function (Builder $builder) use ($user) { //manages - dr
	        return $builder->whereHas('user', function (Builder $builder) use ($user) {
	            return $builder->whereHas('managers',function(Builder $builder) use ($user){
	            	return $builder->where('users.id',$user->id);
	            });
	        })->whereHas('approval',function(Builder $builder){
	        	return $builder->whereHas('stage', function (Builder $builder) {
		          return $builder->where('type',2)->whereHas('role', function (Builder $builder) {
		             return $builder->where('manages','dr');
		          });
	        	});
	        });
       })->where('loan_approvals.id',$loanApproval->id)->orWhereHas('loan_request', function (Builder $builder) use ($user) { //manages - ss
	       return $builder->whereHas('user', function (Builder $builder) use ($user) {
		       return $builder->whereHas('plmanager',function(Builder $builder) use ($user){
			       return $builder->whereHas('managers',function(Builder $builder) use ($user){
			       	  return $builder->where('users.id',$user->id);
			       });
		       });
	       })->whereHas('approval',function(Builder $builder){
		       return $builder->whereHas('stage', function (Builder $builder) {
			       return $builder->where('type',2)->whereHas('role', function (Builder $builder) {
				       return $builder->where('manages','ss');
			       });
		       });
	       });
       })->where('loan_approvals.id',$loanApproval->id)
	       ->orWhereHas('stage', function (Builder $builder) use ($user) {
			return $builder->whereHas('role',function(Builder $builder) use ($user){
				return $builder->whereHas('users',function(Builder $builder) use ($user){
					return $builder->where('users.id',$user->id);
				});
			})->where('type',2)->whereHas('role', function (Builder $builder) {
				return $builder->where('manages','all')->orWhere('manages','none');
			});
		})->where('loan_approvals.id',$loanApproval->id)->orWhereHas('stage', function (Builder $builder) use ($user) {
		       return $builder->whereHas('group',function(Builder $builder) use ($user){
			       return $builder->whereHas('users',function(Builder $builder) use ($user){
				       return $builder->where('users.id',$user->id);
			       });
		       })->where('type',3);
	       })->where('loan_approvals.id',$loanApproval->id)->exists();

	}

    function index(){

        $query = (new LoanApproval)->newQuery();
        $id = request('loan_request_id');
        $query = $query->whereHas('loan_request',function(Builder $builder) use ($id){
            return $builder->where('id',$id);
        });

        //check if current-user can approve
	    $user = Auth::user();


	    $list = $query->get();

	    foreach ($list as $k=>$item){

//		    dd($k,$item);

		    $list[$k]->can_approve = false;

		    if ($this->canApprove($item, $user)){
	    		$list[$k]->can_approve = true;
		    }

	    }


//        dd($query->toSql());

        return [
           'list'=>$list
        ];

    }

    function onEnableApprovalStage(Stage $stage, $objApproval)
    {
        // TODO: Implement onEnableApprovalStage() method.
        $objApproval->status = 1;
        $objApproval->comment = request('comment');
        $objApproval->approver_id = Auth::user()->id;
        $objApproval->save();

    }

    function onEnableFinalApprovalStage(Stage $stage, $objApproval)
    {
        // TODO: Implement onEnableFinalApprovalStage() method.
        $objApproval->loan_request->status = 1;
        $objApproval->loan_request->approved_by = Auth::user()->id;
        $objApproval->loan_request->completed = 1;

        $objApproval->comment = request('comment');
        $objApproval->loan_request->save(); // = 1;
	    $objApproval->save();
//        $objApproval->approver_id = Auth::user()->id;
//        $objApproval->comment = request('comment');
//        $objApproval->save();

        ///use the excel writer here
        ///

        $loan_request= LoanRequest::find($objApproval->loan_request_id);
        $user = $loan_request->user;

        $obj = new SpecificSalaryWriter;

//        $objApproval->loan_request->loan_id = $objApproval->loan_request->id;

        $obj->writeConfig([
            'loan_id'=>$loan_request->id,
            'duration'=>$loan_request->period,
            'amount'=>$loan_request->amount,
            'specific_salary_component_type_id'=>$loan_request->loan_type->specific_salary_component_type->id
        ],$user);

    }


    function onRejectApprovalStage(Stage $stage, $objApproval)
    {
        // TODO: Implement onRejectApprovalStage() method.
        $objApproval->status = 0;
        $objApproval->approver_id = Auth::user()->id;
        $objApproval->comment = request('comment');
        $objApproval->save();
    }

    function onCreateApprovalStage(Stage $stage, $objApproval)
    {
        // TODO: Implement onCreateApprovalStage() method.

//        LoanApproval::create(['stage_id'=>$stage->id,'status'=>0,'loan_request_id'=>$loan_request->id]);
        $obj = new LoanApproval;
        $obj->stage_id = $stage->id;
        $obj->status = 0;
        $obj->loan_request_id = is_object($objApproval)? $objApproval->loan_request_id : $objApproval;
        $obj->comment = 'Your comments.';// request('comment');
        $obj->save();

    }

    function initStage($workflowId,$loanApproval,$user){
       $this->startStage($workflowId,$loanApproval,$user);
    }

    function onNotifyUserApprove(User $user, Stage $stage, $objApproval)
    {
        // TODO: Implement onNotifyUserApprove() method.
        $notification = new ReuseNotification;

        $notification->setSubject('Loan Request Approval');
        $notification->appendLine('Dear ,' . $user->name)
        ->appendLine('A loan request made by ' . $objApproval->loan_request->user->name . ', seeks your approval.')
            ->appendLine('Thank You');

        try{
            $user->notify($notification);
        }catch (\Exception $ex){
            //
        }


    }

    function onNotifyUserReject(User $user, Stage $stage, $objApproval)
    {
        // TODO: Implement onNotifyUserReject() method.

        $notification = new ReuseNotification;

        $notification->setSubject('Loan Request Rejected');
        $notification->appendLine('Dear ,' . $user->name)
            ->appendLine('A loan request made by ' . $objApproval->loan_request->user->name . ',')
            ->appendLine('Has been rejected by ' . $objApproval->approver->name)
            ->appendLine('Please review and approve')
            ->appendLine('Thank You');

        try{
            $user->notify($notification);
        }catch (\Exception $ex){
            //
        }



    }
}