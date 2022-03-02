<?php


namespace App\Traits;



use App\Notifications\ApproveEmployeeReimbursement;
use App\Notifications\EmployeeReimbursementApproved;
use App\Notifications\EmployeeReimbursementPassedStage;
use App\Notifications\EmployeeReimbursementRejected;
use App\Stage;
use App\User;
use App\Workflow;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\EmployeeReimbursement;
use App\EmployeeReimbursementApproval;
use App\EmployeeReimbursementType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

trait EmployeeReimbursementTrait

{
    public function processGet($route,Request $request)
    {
        switch ($route) {
            case 'download':
                # code...
                return $this->download($request);
                break;
            case 'index':
                # code...
                return $this->employee_reimbursements($request);
                break;
            case 'my_expense_reimbursements':
                # code...
                return $this->my_employee_reimbursements($request);
                break;
            case 'employee_reimbursement':
                # code...
                return $this->employee_reimbursement($request);
                break;
            case 'approvals':
                # code...
                return $this->approvals($request);
                break;
            case 'department_approvals':
                # code...
                return $this->departmentApprovals($request);
                break;
            case 'settings':
                # code...
                return $this->settings($request);
                break;
            case 'employee_reimbursement_type':
                # code...
                return $this->employee_reimbursement_type($request);
                break;
            case 'delete_employee_reimbursement_type':
                # code...
                return $this->delete_employee_reimbursement_type($request);
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
            case 'save_employee_reimbursement':
                # code...
                return $this->save_employee_reimbursement($request);
                break;
            case 'save_employee_reimbursement_type':
                # code...
                return $this->save_employee_reimbursement_type($request);
                break;
            case 'save_employee_reimbursement_approval':
                # code...
                return $this->save_employee_reimbursement_approval($request);
                break;
            case 'disburse_employee_reimbursement':
                # code...
                return $this->disburse_employee_reimbursement($request);
                break;


            default:
                # code...
                break;
        }

    }

    public function employee_reimbursements(Request $request){
        $employee_reimbursement_types = EmployeeReimbursementType::all();
        $users=\App\User::where('company_id',companyId())->get();
        if (count($request->all())==0) {
            $employee_reimbursements=EmployeeReimbursement::where('company_id',companyId())->paginate(15);

        }else{
        $employee_reimbursements_query = (new EmployeeReimbursement)->newQuery();
        if ($request->filled('emp')) {
            $jt=$request->input('emp');
            $employee_reimbursements_query->where('employee_reimbursements.user_id', $jt );

            // $listing->whereHas('title','like' ,'%' . $filters->input('name_contains') . '%');
        }
        if ($request->filled('employee_reimbursement_type_id')) {
            $jt=$request->input('employee_reimbursement_type_id');
            $employee_reimbursements_query->where('employee_reimbursements.expense_reimbursement_type_id', $jt );

            // $listing->whereHas('title','like' ,'%' . $filters->input('name_contains') . '%');
        }
            if ($request->filled('status')) {
                $jt=$request->input('status');
                $employee_reimbursements_query->where('employee_reimbursements.status', $jt );

                // $listing->whereHas('title','like' ,'%' . $filters->input('name_contains') . '%');
            }
            if ($request->filled('disbursed')) {
                $jt=$request->input('disbursed');
                $employee_reimbursements_query->where('employee_reimbursements.disbursed', $jt );

                // $listing->whereHas('title','like' ,'%' . $filters->input('name_contains') . '%');
            }
        if ($request->filled('start_date')) {
            $dt_from=Carbon::parse($request->input('start_date'));
            $dt_to=Carbon::parse($request->input('end_date'));
            $employee_reimbursements_query->whereBetween('employee_reimbursements.created_at', [$dt_from,$dt_to]);
        }
        if ($request->filled('amount')) {
            $jt=$request->input('amount');
            $employee_reimbursements_query->where('employee_reimbursements.amount',$jt);
        }
            // $listing->whereHas('title','like' ,'%' . $filters->input('name_contains') . '%');
            $company_id=companyId();
            $employee_reimbursements_query->where('company_id',$company_id);
            $employee_reimbursements=$employee_reimbursements_query->paginate(15);
            if ($request->excel==true) {
                $view='employee_reimbursements.list-excel';
                // return view('compensation.d365payroll',compact('payroll','allowances','deductions','income_tax','salary','date','has_been_run'));
                return     \Excel::create("export", function($excel) use ($employee_reimbursements,$view) {

                    $excel->sheet("export", function($sheet) use ($employee_reimbursements,$view) {
                        $sheet->loadView("$view",compact('employee_reimbursements'))
                            ->setOrientation('landscape');
                    });

                })->export('xlsx');
                # code...
            }
            if ($request->excelall==true) {
                $view = 'employee_reimbursements.list-excel';
                $employee_reimbursements=EmployeeReimbursement::where('company_id',companyId())->get();
                // return view('compensation.d365payroll',compact('payroll','allowances','deductions','income_tax','salary','date','has_been_run'));
                return \Excel::create("export", function ($excel) use ($employee_reimbursements, $view) {

                    $excel->sheet("export", function ($sheet) use ($employee_reimbursements, $view) {
                        $sheet->loadView("$view", compact('employee_reimbursements'))
                            ->setOrientation('landscape');
                    });

                })->export('xlsx');
            }

        }
        return view('employee_reimbursements.hrrequests',compact('employee_reimbursements','employee_reimbursement_types','users'));

    }
    public function my_employee_reimbursements(Request $request){
        $employee_reimbursements=EmployeeReimbursement::where('user_id',Auth::user()->id)->get();
        $employee_reimbursement_types=EmployeeReimbursementType::all();

        return view('employee_reimbursements.myrequests',compact('employee_reimbursements','employee_reimbursement_types'));

    }
    public function employee_reimbursement(Request $request){
        return $employee_reimbursement=EmployeeReimbursement::find($request->employee_reimbursement_id);

    }
    public function save_employee_reimbursement(Request $request){
        $workflow=EmployeeReimbursementType::find($request->employee_reimbursement_type_id)->workflow;
        $employee_reimbursement=EmployeeReimbursement::Create(['title'=>$request->title,'expense_reimbursement_type_id'=>$request->employee_reimbursement_type_id,'user_id'=>Auth::user()->id,'workflow_id'=>$workflow->id,'company_id'=>companyId(),'description'=>$request->description,'expense_date'=>date('Y-m-d',strtotime($request->expense_date)),'amount'=>$request->amount]);

        if ($request->file('attachment')) {

            $path = $request->file('attachment')->store('employee_reimbursements');
            if (Str::contains($path, 'employee_reimbursements')) {
                $filepath = Str::replaceFirst('employee_reimbursements', '', $path);
            } else {
                $filepath = $path;
            }
//            $employee_reimbursement=EmployeeReimbursement::find($request->employee_reimbursement_id);
            $employee_reimbursement->attachment = $filepath;
            $employee_reimbursement->save();


        }
        $stage = Workflow::find($workflow->id)->stages->first();
        if ($stage->type == 1) {
            $employee_reimbursement->employee_reimbursement_approvals()->create([ 'stage_id' => $stage->id, 'comments' => '', 'status' => 0, 'approver_id' => $stage->user_id
            ]);
            if ($stage->user) {
                $stage->user->notify(new ApproveEmployeeReimbursement($employee_reimbursement));
            }

        } elseif ($stage->type == 2) {
            $employee_reimbursement->employee_reimbursement_approvals()->create([ 'stage_id' => $stage->id, 'comments' => '', 'status' => 0, 'approver_id' => 0
            ]);
            if ($stage->role->manages == 'dr') {
                if ($employee_reimbursement->user->managers) {
                    foreach ($employee_reimbursement->user->managers as $manager) {
                        $manager->notify(new ApproveEmployeeReimbursement($employee_reimbursement));
                    }
                }
            }elseif($stage->role->manages == 'ss') {

                foreach ($employee_reimbursement->user->plmanager->managers as $manager) {
                    $manager->notify(new ApproveEmployeeReimbursement($employee_reimbursement));
                }
            } elseif ($stage->role->manage == 'all') {
                foreach ($stage->role->users as $user) {
                    $user->notify(new ApproveEmployeeReimbursement($employee_reimbursement));
                }
            } elseif ($stage->role->manage == 'none') {
                foreach ($stage->role->users as $user) {
                    $user->notify(new ApproveEmployeeReimbursement($employee_reimbursement));
                }
            }
        } elseif ($stage->type == 3) {
            $employee_reimbursement->employee_reimbursement_approvals()->create([ 'stage_id' => $stage->id, 'comments' => '', 'status' => 0, 'approver_id' => 0,'company_id'=>companyId()
            ]);
            if ($stage->group) {
                foreach ($stage->group->users as $user) {
                    $user->notify(new ApproveEmployeeReimbursement($employee_reimbursement));
                }
            }

        }
        return 'success';
    }


    public function disburse_employee_reimbursement(Request $request){

        $employee_reimbursement = EmployeeReimbursement::find($request->employee_reimbursement_id);
        $employee_reimbursement->disbursement_date = $request->disbursement_date;
        if ($employee_reimbursement->status == 1) {
            $employee_reimbursement->disbursed = 1;
            $employee_reimbursement->save();
        }

        return 'success';

    }
    public function approvals(Request $request)
    {
        $user = Auth::user();

        $user_approvals = $this->userApprovals($user);
        $dr_approvals = $this->getDREmployeeReimbursementApprovals($user);
        $ss_approvals = $this->getSSEmployeeReimbursementApprovals($user);
        $role_approvals = $this->roleApprovals($user);
        $group_approvals = $this->groupApprovals($user);

        return view('employee_reimbursements.approvals', compact('user_approvals', 'role_approvals', 'group_approvals', 'dr_approvals','ss_approvals'));
    }

    public function departmentApprovals(Request $request)
    {
        $user = Auth::user();
        $dapprovals = EmployeeReimbursementApproval::whereHas('employee_reimbursement.user.job.department', function ($query) use ($user) {
            $query->where('employee_reimbursements.user_id', '!=', $user->id)
                ->where('departments.manager_id', $user->id);

        })
            ->where('status', 0)->orderBy('id', 'asc')->get();
        return view('employee_reimbursements.department_approvals', compact('dapprovals'));
    }

    public function userApprovals(User $user)
    {
        return $las = EmployeeReimbursementApproval::whereHas('stage.user', function ($query) use ($user) {
            $query->where('users.id', $user->id);

        })
            ->where('status', 0)->orderBy('id', 'asc')->get();

    }
    public function getSSEmployeeReimbursementApprovals(User $user)
    {
        return Auth::user()->getSSEmployeeReimbursementApprovals();


    }

    public function getDREmployeeReimbursementApprovals(User $user)
    {
        return Auth::user()->getDREmployeeReimbursementApprovals();
        // 	return $las = LeaveApproval::whereHas('stage.role.users',function($query) use($user){
        // 	$query->where('users.id',$user->id);
        // })

        //  ->where('status',0)->orderBy('id','desc')->get();

    }

    public function roleApprovals(User $user)
    {
        return $las = EmployeeReimbursementApproval::whereHas('stage.role', function ($query) use ($user) {
            $query->where('manages', '!=', 'dr')
                ->where('manages', '!=', 'ss')
                ->where('roles.id', $user->role_id);
        })->where('status', 0)->orderBy('id', 'asc')->get();
    }

    public function groupApprovals(User $user)
    {
        return $las = EmployeeReimbursementApproval::whereHas('stage.group.users', function ($query) use ($user) {
            $query->where('users.id', $user->id);
        })
            ->where('status', 0)->orderBy('id', 'asc')->get();

    }

    public function save_employee_reimbursement_approval(Request $request)
    {
        $employee_reimbursement_approval = EmployeeReimbursementApproval::find($request->employee_reimbursement_approval_id);
        $employee_reimbursement_approval->comments = $request->comment;
        if ($request->approval == 1) {
            $employee_reimbursement_approval->status = 1;
            $employee_reimbursement_approval->approver_id = Auth::user()->id;
            $employee_reimbursement_approval->save();
            // $logmsg=$leave_approval->document->filename.' was approved in the '.$leave_approval->stage->name.' in the '.$leave_approval->stage->workflow->name;
            // $this->saveLog('info','App\Review',$leave_approval->id,'employee_reimbursement_approvals',$logmsg,Auth::user()->id);
            $newposition = $employee_reimbursement_approval->stage->position + 1;
            $nextstage = Stage::where(['workflow_id' => $employee_reimbursement_approval->stage->workflow->id, 'position' => $newposition])->first();
            // return $review->stage->position+1;
            // return $nextstage;

            if ($nextstage) {

                $newemployee_reimbursement_approval = new EmployeeReimbursementApproval();
                $newemployee_reimbursement_approval->stage_id = $nextstage->id;
                $newemployee_reimbursement_approval->employee_reimbursement_id = $employee_reimbursement_approval->employee_reimbursement->id;
                $newemployee_reimbursement_approval->status = 0;
                $newemployee_reimbursement_approval->save();
                // $logmsg='New review process started for '.$newleave_approval->document->filename.' in the '.$newleave_approval->stage->workflow->name;
                // $this->saveLog('info','App\Review',$leave_approval->id,'reviews',$logmsg,Auth::user()->id);
                if ($nextstage->type == 1) {

                    // return $nextstage->type . '-2--' . 'all';

                    $nextstage->user->notify(new ApproveEmployeeReimbursement($newemployee_reimbursement_approval->employee_reimbursement));
                } elseif ($nextstage->type == 2) {
                    // return $nextstage->role->manages . '1---' . 'all' . json_encode($leave_approval->employee_reimbursement->user->managers);
                    if ($nextstage->role->manages == 'dr') {

                        // return $nextstage->role->manage . '---' . json_encode($nextstage->role->users);

                        foreach ($employee_reimbursement_approval->employee_reimbursement->user->managers as $manager) {
                            $manager->notify(new ApproveEmployeeReimbursement($newemployee_reimbursement_approval->employee_reimbursement));
                        }
                    }elseif($nextstage->role->manages == 'ss') {

                        foreach ($employee_reimbursement_approval->employee_reimbursement->user->plmanager->managers as $manager) {
                            $manager->notify(new ApproveEmployeeReimbursement($newemployee_reimbursement_approval->employee_reimbursement));
                        }
                    } elseif ($nextstage->role->manages == 'all') {
                        // return 'all.';

                        // return $nextstage->role->manage . '---' . json_encode($nextstage->role->users);

                        foreach ($nextstage->role->users as $user) {
                            $user->notify(new ApproveEmployeeReimbursement($newemployee_reimbursement_approval->employee_reimbursement));
                        }
                    } elseif ($nextstage->role->manages == 'none') {
                        foreach ($nextstage->role->users as $user) {
                            $user->notify(new ApproveEmployeeReimbursement($newemployee_reimbursement_approval->employee_reimbursement));
                        }
                    }
                } elseif ($nextstage->type == 3) {
                    //1-user
                    //2-role
                    //3-groups
                    // return 'not_blank';

                    foreach ($nextstage->group->users as $user) {
                        $user->notify(new ApproveEmployeeReimbursement($newemployee_reimbursement_approval->employee_reimbursement));
                    }
                } else {
                    // return 'blank';
                }

                $employee_reimbursement_approval->employee_reimbursement->user->notify(new EmployeeReimbursementPassedStage($employee_reimbursement_approval, $employee_reimbursement_approval->stage, $newemployee_reimbursement_approval->stage));
            } else {
                // return 'blank2';
                $employee_reimbursement_approval->employee_reimbursement->status = 1;
                $employee_reimbursement_approval->employee_reimbursement->save();

                $employee_reimbursement_approval->employee_reimbursement->user->notify(new EmployeeReimbursementApproved($employee_reimbursement_approval->stage, $employee_reimbursement_approval));
            }


        } elseif ($request->approval == 2) {
            // return 'blank3';
            $employee_reimbursement_approval->status = 2;
            $employee_reimbursement_approval->comments = $request->comment;
            $employee_reimbursement_approval->approver_id = Auth::user()->id;
            $employee_reimbursement_approval->save();
            // $logmsg=$leave_approval->document->filename.' was rejected in the '.$leave_approval->stage->name.' in the '.$leave_approval->stage->workflow->name;
            // $this->saveLog('info','App\Review',$leave_approval->id,'employee_reimbursement_approvals',$logmsg,Auth::user()->id);
            $employee_reimbursement_approval->employee_reimbursement->status = 2;
            $employee_reimbursement_approval->employee_reimbursement->save();
            $employee_reimbursement_approval->employee_reimbursement->user->notify(new EmployeeReimbursementRejected($employee_reimbursement_approval->stage, $employee_reimbursement_approval));
            // return redirect()->route('documents.mypendingemployee_reimbursement_approvals')->with(['success'=>'Document Reviewed Successfully']);
        }

        return 'success';


        // return redirect()->route('documents.mypendingreviews')->with(['success'=>'Leave Request Approved Successfully']);
    }

    public function settings(Request $request){
        $employee_reimbursement_types=EmployeeReimbursementType::all();
        $workflows=Workflow::where('status',1)->get();

        return view('settings.employeereimbursementsettings.index',compact('employee_reimbursement_types','workflows'));
    }
    public function employee_reimbursement_type(Request $request){
        return $document_type=EmployeeReimbursementType::find($request->employee_reimbursement_type_id);
    }
public function delete_employee_reimbursement_type(Request $request){
    $document_type=EmployeeReimbursementType::find($request->employee_reimbursement_type_id);
    if ($document_type->employee_reimbursements){
        return 'error';
    }else{
        $document_type->delete();
        return 'success';
    }
}
    public function save_employee_reimbursement_type(Request $request){
        EmployeeReimbursementType::updateOrCreate(['id'=>$request->employee_reimbursement_type_id],['name'=>$request->name,'workflow_id'=>$request->workflow_id,'created_by'=>Auth::user()->id]);
        return 'success';
    }
    public function getDetails(Request $request)
    {
        $employee_reimbursement = EmployeeReimbursement::where('id', $request->employee_reimbursement_id)->first();
        return view('employee_reimbursements.partials.employee_reimbursement_details', compact('employee_reimbursement'));
    }
    private function download(Request $request)
    {


        $employee_reimbursement = EmployeeReimbursement::find($request->employee_reimbursement_id);
        if ($employee_reimbursement->attachment != '') {
            $path = $employee_reimbursement->attachment;

            return response()->download(public_path('uploads/employee_reimbursements' . $path));
        } else {
            redirect()->back();
        }
    }




}
