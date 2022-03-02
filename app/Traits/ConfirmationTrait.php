<?php


namespace App\Traits;



use App\Notifications\ApproveConfirmationRequest;
use App\Notifications\ConfirmationRequestApproved;
use App\Notifications\ConfirmationRequestPassedStage;
use App\Notifications\ConfirmationRequestRejected;
use App\Notifications\ConfirmationRequestUploaded;
use App\Notifications\ConfirmationRequirementUploaded;
use App\Setting;
use App\Stage;
use App\User;
use App\Workflow;
use Illuminate\Http\Request;
use App\Confirmation;
use App\ConfirmationApproval;
use App\ConfirmationRequirement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

trait ConfirmationTrait

{
    public function processGet($route,Request $request)
    {
        switch ($route) {
            case 'download':
                # code...
                return $this->download($request);
                break;
            case 'start_confirmation_process':
                # code...
                return $this->start_confirmation_process($request);
                break;
            case 'my_confirmation_request':
                # code...
                return $this->my_confirmation_request($request);
                break;
            case 'view_confirmation':
                # code...
                return $this->view_confirmation($request);
                break;
            case 'approvals':
                # code...
                return $this->approvals($request);
                break;

            case 'settings':
                # code...
                return $this->settings($request);
                break;
            case 'confirmation_requirement':
                # code...
                return $this->confirmation_requirement($request);
                break;
            case 'delete_confirmation_requirement':
                # code...
                return $this->delete_confirmation_requirement($request);
                break;
            case 'get_details':
                # code...
                return $this->getDetails($request);
                break;


            default:
                # code...
                break;

        }
    }

    public function processPost(Request $request){
        // try{
        switch ($request->type) {
            case 'save_confirmation':
                # code...
                return $this->save_confirmation($request);
                break;
            case 'save_confirmation_requirement':
                # code...
                return $this->save_confirmation_requirement($request);
                break;
            case 'save_confirmation_approval':
                # code...
                return $this->save_confirmation_approval($request);
                break;
            case 'confirmation_policy':
                # code...
                return $this->confirmation_policy($request);
                break;
            case 'upload_confirmation_requirement':
                # code...
                return $this->upload_confirmation_requirement($request);
                break;


            default:
                # code...
                break;
        }

    }

    public function view_confirmation(Request $request)
    {
        $confirmation=Confirmation::find($request->confirmation_id);
        $confirmation_requirements=ConfirmationRequirement::all();
            return view('confirmation.view',compact('confirmation','confirmation_requirements'));

    }
    public function start_confirmation_process(Request $request){
        $workflow_id=Setting::where(['name'=>'confirmation_workflow_id'])->first();
        if (!$workflow_id){
            return 'no workflow';
        }
        $confirmation=Confirmation::Create(['user_id'=>$request->user_id,'initiator_id'=>Auth::user()->id,'workflow_id'=>$workflow_id->value,'status'=>0,'confirmation_date'=>'']);

        $stage = Workflow::find($workflow_id->value)->stages->first();
        if ($stage->type == 1) {
            $confirmation->approvals()->create([ 'stage_id' => $stage->id, 'comments' => '', 'status' => 0, 'approver_id' => $stage->user_id
            ]);
            if ($stage->user) {
                $stage->user->notify(new ApproveConfirmationRequest($confirmation));
            }

        } elseif ($stage->type == 2) {
            $confirmation->approvals()->create([ 'stage_id' => $stage->id, 'comments' => '', 'status' => 0, 'approver_id' => 0
            ]);
            if ($stage->role->manages == 'dr') {
                if ($confirmation->user->managers) {
                    foreach ($confirmation->user->managers as $manager) {
                        $manager->notify(new ApproveConfirmationRequest($confirmation));
                    }
                }
            }elseif($stage->role->manages == 'ss') {

                foreach ($confirmation->user->plmanager->managers as $manager) {
                    $manager->notify(new ApproveConfirmationRequest($confirmation));
                }
            } elseif ($stage->role->manages == 'all') {
                foreach ($stage->role->users as $user) {
                    $user->notify(new ApproveConfirmationRequest($confirmation));
                }
            } elseif ($stage->role->manages == 'none') {
                foreach ($stage->role->users as $user) {
                    $user->notify(new ApproveConfirmationRequest($confirmation));
                }
            }
        } elseif ($stage->type == 3) {
            $confirmation->approvals()->create([ 'stage_id' => $stage->id, 'comments' => '', 'status' => 0, 'approver_id' => 0
            ]);
            if ($stage->group) {
                foreach ($stage->group->users as $user) {
                    $user->notify(new ApproveConfirmationRequest($confirmation));
                }
            }

        }
        return 'success';
    }
    public function my_confirmation_request(Request $request){
        $confirmation=Confirmation::where('user_id',Auth::user()->id)->first();
        if(!$confirmation){
            return back();
        }
        $confirmation_requirements=ConfirmationRequirement::all();

        return view('confirmation.view',compact('confirmation','confirmation_requirements'));

    }

    public function save_confirmaton(Request $request){
        $workflow_id=Setting::where(['name'=>'confirmation_workflow_id','company_id'=>companyId()])->first();
        if (!$workflow_id){
            return back()->with(['message'=>'please setup confirmation workflow']);
        }

        $confirmation=Confirmation::Create(['user_id'=>$request->user_id,'initiator_id'=>Auth::user()->id,'workflow_id'=>$workflow_id,'status'=>0,'confirmation_date'=>'']);

        $stage = Workflow::find($workflow_id)->stages->first();
        if ($stage->type == 1) {
            $confirmation->approvals()->create([ 'stage_id' => $stage->id, 'comments' => '', 'status' => 0, 'approver_id' => $stage->user_id
            ]);
            if ($stage->user) {
                $stage->user->notify(new ApproveConfirmationRequest($confirmation));
            }

        } elseif ($stage->type == 2) {
            $confirmation->approvals()->create([ 'stage_id' => $stage->id, 'comments' => '', 'status' => 0, 'approver_id' => 0
            ]);
            if ($stage->role->manages == 'dr') {
                if ($confirmation->user->managers) {
                    foreach ($confirmation->user->managers as $manager) {
                        $manager->notify(new ApproveConfirmationRequest($confirmation));
                    }
                }
            }elseif($stage->role->manages == 'ss') {

                foreach ($confirmation->user->plmanager->managers as $manager) {
                    $manager->notify(new ApproveConfirmationRequest($confirmation));
                }
            } elseif ($stage->role->manages == 'all') {
                foreach ($stage->role->users as $user) {
                    $user->notify(new ApproveConfirmationRequest($confirmation));
                }
            } elseif ($stage->role->manages == 'none') {
                foreach ($stage->role->users as $user) {
                    $user->notify(new ApproveConfirmationRequest($confirmation));
                }
            }
        } elseif ($stage->type == 3) {
            $confirmation->approvals()->create([ 'stage_id' => $stage->id, 'comments' => '', 'status' => 0, 'approver_id' => 0
            ]);
            if ($stage->group) {
                foreach ($stage->group->users as $user) {
                    $user->notify(new ApproveConfirmationRequest($confirmation));
                }
            }

        }
        return 'success';
    }


    public function upload_confirmation_requirement(Request $request){

        if ($request->file('requested_doc')) {

            $path = $request->file('requested_doc')->store('confirmations');
            if (Str::contains($path, 'confirmations')) {
                $filepath = Str::replaceFirst('confirmations', '', $path);
            } else {
                $filepath = $path;
            }
            $confirmation=Confirmation::find($request->confirmation_id);
            $confirmation_requirement=ConfirmationRequirement::find($request->requirement_id);
            $confirmation->requirements()->attach($request->requirement_id, ['file' => $filepath]);


            $confirmation->user->notify(new ConfirmationRequirementUploaded($confirmation,$confirmation_requirement));
        }

        return 'sucess';

    }
    public function approvals(Request $request)
    {
        $user = Auth::user();

        $user_approvals = $this->userApprovals($user);
        $dr_approvals = $this->getDRConfirmationApprovals($user);
        $ss_approvals = $this->getSSConfirmationApprovals($user);
        $role_approvals = $this->roleApprovals($user);
        $group_approvals = $this->groupApprovals($user);

        return view('confirmation.approvals', compact('user_approvals', 'role_approvals', 'group_approvals', 'dr_approvals','ss_approvals'));
    }



    public function userApprovals(User $user)
    {
        return $las = ConfirmationApproval::whereHas('stage.user', function ($query) use ($user) {
            $query->where('users.id', $user->id);

        })
            ->where('status', 0)->orderBy('id', 'asc')->get();

    }

    public function getDRConfirmationApprovals(User $user)
    {
        return Auth::user()->getDRConfirmationApprovals();

    }
    public function getSSConfirmationApprovals(User $user)
    {
        return Auth::user()->getSSConfirmationApprovals();

    }

    public function roleApprovals(User $user)
    {
        return $las = ConfirmationApproval::whereHas('stage.role', function ($query) use ($user) {
            $query->where('manages', '!=', 'dr')
                ->where('manages', '!=', 'ss')
                ->where('roles.id', $user->role_id);
        })->where('status', 0)->orderBy('id', 'asc')->get();
    }

    public function groupApprovals(User $user)
    {
        return $las = ConfirmationApproval::whereHas('stage.group.users', function ($query) use ($user) {
            $query->where('users.id', $user->id);
        })
            ->where('status', 0)->orderBy('id', 'asc')->get();

    }

    public function save_confirmation_approval(Request $request)
    {
        $confirmation_approval = ConfirmationApproval::find($request->confirmation_approval_id);
        $confirmation_approval->comments = $request->comment;
        if ($request->approval == 1) {
            $confirmation_approval->status = 1;
            $confirmation_approval->approver_id = Auth::user()->id;
            $confirmation_approval->save();
            // $logmsg=$leave_approval->document->filename.' was approved in the '.$leave_approval->stage->name.' in the '.$leave_approval->stage->workflow->name;
            // $this->saveLog('info','App\Review',$leave_approval->id,'document_approvals',$logmsg,Auth::user()->id);
            $newposition = $confirmation_approval->stage->position + 1;
            $nextstage = Stage::where(['workflow_id' => $confirmation_approval->stage->workflow->id, 'position' => $newposition])->first();
            // return $review->stage->position+1;
            // return $nextstage;

            if ($nextstage) {

                $newconfirmation_approval = new ConfirmationApproval();
                $newconfirmation_approval->stage_id = $nextstage->id;
                $newconfirmation_approval->confirmation_id = $confirmation_approval->confirmation->id;
                $newconfirmation_approval->status = 0;
                $newconfirmation_approval->save();
                // $logmsg='New review process started for '.$newleave_approval->document->filename.' in the '.$newleave_approval->stage->workflow->name;
                // $this->saveLog('info','App\Review',$leave_approval->id,'reviews',$logmsg,Auth::user()->id);
                if ($nextstage->type == 1) {

                    // return $nextstage->type . '-2--' . 'all';

                    $nextstage->user->notify(new ApproveConfirmationRequest($newconfirmation_approval->confirmation));
                } elseif ($nextstage->type == 2) {
                    // return $nextstage->role->manages . '1---' . 'all' . json_encode($leave_approval->leave_request->user->managers);
                    if ($nextstage->role->manages == 'dr') {

                        // return $nextstage->role->manage . '---' . json_encode($nextstage->role->users);

                        foreach ($confirmation_approval->confirmation->user->managers as $manager) {
                            $manager->notify(new ApproveConfirmationRequest($newconfirmation_approval->confirmation));
                        }
                    } elseif($nextstage->role->manages == 'ss') {

                        foreach ($confirmation_approval->confirmation->user->plmanager->managers as $manager) {
                            $manager->notify(new ApproveConfirmationRequest($newconfirmation_approval->confirmation));
                        }
                    }elseif ($nextstage->role->manages == 'all') {
                        // return 'all.';

                        // return $nextstage->role->manage . '---' . json_encode($nextstage->role->users);

                        foreach ($nextstage->role->users as $user) {
                            $user->notify(new ApproveConfirmationRequest($newconfirmation_approval->confirmation));
                        }
                    } elseif ($nextstage->role->manages == 'none') {
                        foreach ($nextstage->role->users as $user) {
                            $user->notify(new ApproveConfirmationRequest($newconfirmation_approval->confirmation));
                        }
                    }
                } elseif ($nextstage->type == 3) {
                    //1-user
                    //2-role
                    //3-groups
                    // return 'not_blank';

                    foreach ($nextstage->group->users as $user) {
                        $user->notify(new ApproveConfirmationRequest($newconfirmation_approval->document_request));
                    }
                } else {
                    // return 'blank';
                }

                $confirmation_approval->confirmation->user->notify(new ConfirmationRequestPassedStage($confirmation_approval, $confirmation_approval->stage, $newconfirmation_approval->stage));
            } else {
                // return 'blank2';
                $confirmation_approval->confirmation->status = 1;
                $confirmation_approval->confirmation->save();
                $confirmation_approval->confirmation->user->update(['confirmation_date'=>date('Y-m-d'),'status'=>1]);

                $confirmation_approval->confirmation->user->notify(new ConfirmationRequestApproved($confirmation_approval->stage, $confirmation_approval));
            }


        } elseif ($request->approval == 2) {
            // return 'blank3';
            $confirmation_approval->status = 2;
            $confirmation_approval->comments = $request->comment;
            $confirmation_approval->approver_id = Auth::user()->id;
            $confirmation_approval->save();
            // $logmsg=$leave_approval->document->filename.' was rejected in the '.$leave_approval->stage->name.' in the '.$leave_approval->stage->workflow->name;
            // $this->saveLog('info','App\Review',$leave_approval->id,'document_approvals',$logmsg,Auth::user()->id);
            $confirmation_approval->confirmation->status = 2;
            $confirmation_approval->confirmation->save();
            $confirmation_approval->confirmation->user->notify(new ConfirmationRequestRejected($confirmation_approval->stage, $confirmation_approval));
            // return redirect()->route('documents.mypendingdocument_approvals')->with(['success'=>'Document Reviewed Successfully']);
        }

        return 'success';


        // return redirect()->route('documents.mypendingreviews')->with(['success'=>'Leave Request Approved Successfully']);
    }

    public function settings(Request $request){
        $confirmation_requirements=ConfirmationRequirement::all();
        $workflows=Workflow::where('status',1)->get();
        $workflow_id=Setting::where(['name'=>'confirmation_workflow_id','company_id'=>companyId()])->first();
        if (!$workflow_id){
            $workflow_id= new Setting();
            $workflow_id->name='confirmation_workflow_id';
            $workflow_id->value=0;
            $workflow_id->company_id=companyId();
            $workflow_id->save();
        }
        $workflow_id=$workflow_id->value;

        return view('settings.confirmationsettings.index',compact('confirmation_requirements','workflows','workflow_id'));
    }

    public function confirmation_policy(Request $request)
    {

        Setting::updateOrCreate(['name'=>'confirmation_workflow_id','company_id'=>companyId()],['name'=>'confirmation_workflow_id','company_id'=>companyId(),
            'value'=>$request->workflow_id]);
        return 'success';
    }
    public function confirmation_requirement(Request $request){
        return $confirmation_requirement=ConfirmationRequirement::find($request->confirmation_requirement_id);
    }
public function delete_confirmation_requirement(Request $request){
    $confirmation_requirement=ConfirmationRequirement::find($request->confirmation_requirement_id);
    if ($confirmation_requirement->confirmation){
        return 'error';
    }else{
        $confirmation_requirement->delete();
        return 'success';
    }
}
    public function save_confirmation_requirement(Request $request){

        ConfirmationRequirement::updateOrCreate(['id'=>$request->confirmation_requirement_id],['name'=>$request->name,'compulsory'=>$request->compulsory,'is_approval_requirement'=>$request->is_approval_requirement,'created_by'=>Auth::user()->id]);
        return 'success';
    }
    public function getConfirmationDetails(Request $request)
    {
        $confirmation = Confirmation::where('id', $request->confirmation_id)->first();
        return view('confirmation.confirmation_details', compact('confirmation'));
    }
    private function download(Request $request)
    {


        $confirmation = Confirmation::find($request->confirmation_id);
        $requirement=$confirmation->requirements()->where('confirmation_requirement_id',$request->requirement_id)->first();
        if ($requirement->pivot->file != '') {
            $path = $requirement->pivot->file;

            return response()->download(public_path('uploads/confirmations' . $path));
        } else {
            redirect()->back();
        }
    }




}
