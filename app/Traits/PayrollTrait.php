<?php

namespace App\Traits;

use App\Notifications\PayrollApproved;
use App\Notifications\PayrollPassedStage;
use App\Notifications\PayrollRejected;
use App\Payroll;
use App\Bank;
use App\Company;
use App\Department;
use App\CompanyAccountDetail;
use App\PayrollApproval;
use App\PayslipDetail;
use App\PayrollDetail;
use App\PayrollPolicy;
use App\SalaryComponent;
use App\SalaryReview;
use App\SpecificSalaryComponent;
use App\Stage;
use App\Workflow;
use App\LatenessPolicy;
use App\Setting;
use App\User;
use App\Holiday;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Auth;
use Excel;
use PDF;
use App\Notifications\ApprovePayroll;
use App\ProjectPayrollDetail;
use App\PaceSalaryComponent;
use App\TmsaComponent;
use App\TmsaSchedule;
use App\TmsaPayrollDetail;
use App\TmsaPolicy;

trait PayrollTrait
{

    public function processGet($route, Request $request)
    {
        // return $request->all();
        switch ($route) {
            case 'runpayroll':
                //   return $date=date('Y-m-d',strtotime('01-'.$request->month));
                // return $request->all();
                return $this->runPayroll($request);
                break;
            case 'payroll_list':
                //   return $date=date('Y-m-d',strtotime('01-'.$request->month));
                // return $request->all();
                return $this->payrollList($request);
                break;
            case 'select_payroll_type':
                    //   return $date=date('Y-m-d',strtotime('01-'.$request->month));
                    // return $request->all();
                    return $this->select_payroll_type($request);
                    break;
                
            case'user_payroll_detail':

                return $this->userPayrollDetail($request);
                break;
            case'user_payroll_list':

                return $this->userPayrollList($request);
                break;
            case'download_payslip':

                return $this->downloadPayslip($request);
                break;
            case'issuepayslip':

                return $this->issuePayslip($request);
                break;
            case'sendpayslip':

                return $this->sendPayslip($request);
                break;
            case'sendselectedpayslip':

                return $this->sendSelectedPayslip($request);
                break;
            case'rollback':

                return $this->rollbackPayroll($request);
                break;
            case'exportford365':

                return $this->exportForD365($request);
                break;
            case'exportforexcel':

                return $this->exportForExcel($request);
                break;

            case 'payroll_log':
                return $this->payroll_logs($request);
                break;
            case 'start_approval':
                return $this->start_payroll_approval($request);
                break;
            case 'approvals':
                return $this->approvals($request);
                break;
                case 'disburse':
                return $this->disburse($request);
                break;
            default:
                # code...
                break;
        }
    }

    public function processPost(Request $request)
    {
        switch ($request->type) {
            case 'getProgress':
                # code...
                return $this->getProgress($request);
                break;
            case 'save_approval':
                # code...
                return $this->saveApproval($request);
                break;

            default:
                # code...
                break;
        }
    }

    public function start_payroll_approval(Request $request)
    {
        $company_id = companyId();
        $PR = Payroll::find($request->payroll_id);
        $pp = PayrollPolicy::where('company_id', $company_id)->first();

        if ($pp->uses_approval == 1 & $PR->workflow_id > 0) {
            $PR->approved = 3;
            $PR->save();
            $stage = Workflow::find($PR->workflow_id)->stages->first();
            if ($stage->type == 1) {
                $PR->payroll_approvals()->create(['stage_id' => $stage->id, 'comments' => '', 'status' => 0, 'approver_id' => $stage->user_id
                ]);
                if ($stage->user) {
                    $stage->user->notify(new ApprovePayroll($PR));
                }

            } elseif ($stage->type == 2) {
                $PR->payroll_approvals()->create([
                    'payroll_id' => $request->id, 'stage_id' => $stage->id, 'comments' => '', 'status' => 0, 'approver_id' => 0
                ]);
                if ($stage->role->manages == 'dr') {
                    if ($PR->user->managers) {
                        foreach ($PR->user->managers as $manager) {
                            $manager->notify(new ApprovePayroll($PR));
                        }
                    }
                } elseif ($stage->role->manage == 'all') {
                    foreach ($stage->role->users as $user) {
                        $user->notify(new ApprovePayroll($PR));
                    }
                } elseif ($stage->role->manage == 'none') {
                    foreach ($stage->role->users as $user) {
                        $user->notify(new ApprovePayroll($PR));
                    }
                }
            } elseif ($stage->type == 3) {
                if ($stage->group) {
                    foreach ($stage->group->users as $user) {
                        $user->notify(new ApprovePayroll($PR));
                    }
                }

            }
            return 'success';
        }
        return 'error';
    }

    public function saveApproval(Request $request)
    {
        $payroll_approval = PayrollApproval::find($request->payroll_approval_id);
        $payroll_approval->comments = $request->comment;
        if ($request->approval == 1) {
            $payroll_approval->status = 1;
            $payroll_approval->approver_id = Auth::user()->id;
            $payroll_approval->save();
            // $logmsg=$leave_approval->document->filename.' was approved in the '.$leave_approval->stage->name.' in the '.$leave_approval->stage->workflow->name;
            // $this->saveLog('info','App\Review',$leave_approval->id,'leave_approvals',$logmsg,Auth::user()->id);
            $newposition = $payroll_approval->stage->position + 1;
            $nextstage = Stage::where(['workflow_id' => $payroll_approval->stage->workflow->id, 'position' => $newposition])->first();
            // return $review->stage->position+1;
            // return $nextstage;

            if ($nextstage) {

                $newpayroll_approval = new PayrollApproval();
                $newpayroll_approval->stage_id = $nextstage->id;
                $newpayroll_approval->payroll_id = $payroll_approval->payroll->id;
                $newpayroll_approval->status = 0;
                $newpayroll_approval->save();
                // $logmsg='New review process started for '.$newleave_approval->document->filename.' in the '.$newleave_approval->stage->workflow->name;
                // $this->saveLog('info','App\Review',$leave_approval->id,'reviews',$logmsg,Auth::user()->id);
                if ($nextstage->type == 1) {

                    // return $nextstage->type . '-2--' . 'all';

                    $nextstage->user->notify(new ApprovePayroll($newpayroll_approval->payroll));
                } elseif ($nextstage->type == 2) {
                    // return $nextstage->role->manages . '1---' . 'all' . json_encode($leave_approval->payroll->user->managers);
                    if ($nextstage->role->manages == 'dr') {

                        // return $nextstage->role->manage . '---' . json_encode($nextstage->role->users);

                        foreach ($payroll_approval->payroll->user->managers as $manager) {
                            $manager->notify(new ApprovePayroll($newpayroll_approval->payroll));
                        }
                    } elseif ($nextstage->role->manages == 'all') {
                        // return 'all.';

                        // return $nextstage->role->manage . '---' . json_encode($nextstage->role->users);

                        foreach ($nextstage->role->users as $user) {
                            $user->notify(new ApprovePayroll($newpayroll_approval->payroll));
                        }
                    } elseif ($nextstage->role->manage == 'none') {
                        foreach ($nextstage->role->users as $user) {
                            $user->notify(new ApprovePayroll($newpayroll_approval->payroll));
                        }
                    }
                } elseif ($nextstage->type == 3) {
                    //1-user
                    //2-role
                    //3-groups
                    // return 'not_blank';

                    foreach ($nextstage->group->users as $user) {
                        $user->notify(new ApprovePayroll($newpayroll_approval->payroll));
                    }
                } else {
                    // return 'blank';
                }

                $payroll_approval->payroll->user->notify(new PayrollPassedStage($payroll_approval, $payroll_approval->stage, $newpayroll_approval->stage));
            } else {
                // return 'blank2';
                $payroll_approval->payroll->approved = 1;
                $payroll_approval->payroll->save();

                $payroll_approval->payroll->user->notify(new PayrollApproved($payroll_approval->stage, $payroll_approval));
            }


        } elseif ($request->approval == 2) {
            // return 'blank3';
            $payroll_approval->status = 2;
            $payroll_approval->comments = $request->comment;
            $payroll_approval->approver_id = Auth::user()->id;
            $payroll_approval->save();
            // $logmsg=$leave_approval->document->filename.' was rejected in the '.$leave_approval->stage->name.' in the '.$leave_approval->stage->workflow->name;
            // $this->saveLog('info','App\Review',$leave_approval->id,'leave_approvals',$logmsg,Auth::user()->id);
            $payroll_approval->payroll->status = 2;
            $payroll_approval->payroll->save();
            $payroll_approval->payroll->user->notify(new PayrollRejected($payroll_approval->stage, $payroll_approval));
            // return redirect()->route('documents.mypendingleave_approvals')->with(['success'=>'Document Reviewed Successfully']);
        }

        return 'success';


        // return redirect()->route('documents.mypendingreviews')->with(['success'=>'Leave Request Approved Successfully']);
    }

    public function approvals(Request $request)
    {
        $user = Auth::user();

        $user_approvals = $this->userApprovals($user);
        $dr_approvals = $this->getDRPayrollApprovals($user);
        $ss_approvals = $this->getSSPayrollApprovals($user);
        $role_approvals = $this->roleApprovals($user);
        $group_approvals = $this->groupApprovals($user);

        return view('compensation.approvals', compact('user_approvals', 'role_approvals', 'group_approvals', 'dr_approvals','ss_approvals'));
    }

//    public function departmentApprovals(Request $request)
//    {
//        $user = Auth::user();
//        $dapprovals = PayrollApproval::whereHas('payroll.user.job.department', function ($query) use ($user) {
//            $query->where('payroll.user_id', '!=', $user->id)
//                ->where('departments.manager_id', $user->id);
//
//        })
//            ->where('status', 0)->orderBy('id', 'asc')->get();
//        return view('compensation.department_approvals', compact('dapprovals'));
//    }

    public function userApprovals(User $user)
    {
        return $las = PayrollApproval::whereHas('stage.user', function ($query) use ($user) {
            $query->where('users.id', $user->id);

        })
            ->where('status', 0)->orderBy('id', 'asc')->get();

    }

    public function getDRPayrollApprovals(User $user)
    {
        return Auth::user()->getDRPayrollApprovals();
        // 	return $las = PayrollApproval::whereHas('stage.role.users',function($query) use($user){
        // 	$query->where('users.id',$user->id);
        // })

        //  ->where('status',0)->orderBy('id','desc')->get();

    }
    public function getSSPayrollApprovals(User $user)
    {
        return Auth::user()->getSSPayrollApprovals();
        // 	return $las = PayrollApproval::whereHas('stage.role.users',function($query) use($user){
        // 	$query->where('users.id',$user->id);
        // })

        //  ->where('status',0)->orderBy('id','desc')->get();

    }

    public function roleApprovals(User $user)
    {
        return $las = PayrollApproval::whereHas('stage.role', function ($query) use ($user) {
            $query->where('manages', '!=', 'dr')
                ->where('roles.id', $user->role_id);
        })->where('status', 0)->orderBy('id', 'asc')->get();
    }

    public function groupApprovals(User $user)
    {
        return $las = PayrollApproval::whereHas('stage.group.users', function ($query) use ($user) {
            $query->where('users.id', $user->id);
        })
            ->where('status', 0)->orderBy('id', 'asc')->get();

    }

    public function payroll_logs(Request $request)
    {
        $company_id = companyId();
        $payroll = Payroll::find($request->payroll_id);

        $pp = PayrollPolicy::where('company_id', $company_id)->first();
        $salary_components = \App\SalaryComponent::where('company_id', $company_id)->get();
        $salary_components = $salary_components->map(function ($item, $key) {
            return [
                'name' => $item->name,
                'constant' => $item->constant,
                'type' => $item->type
            ];
        });
        $project_salary_components = \App\PaceSalaryComponent::where('company_id', $company_id)->get();
        $project_salary_components = $project_salary_components->map(function ($item, $key) {
            return [
                'name' => $item->name,
                'constant' => $item->constant,
                'type' => $item->type
            ];
        });
        $specific_salary_component_types = \App\SpecificSalaryComponentType::where(['company_id' => $company_id, 'display' => 1])->get();
        $components = $salary_components->merge($project_salary_components);
        $components = $components->unique('constant');

        return view('compensation.partials.payrollexcel3', compact('payroll', 'specific_salary_component_types', 'pp', 'components'));

        //  foreach($components as $component):
        //   return $component['name'] ;
        //   endforeach;
        //  if ($pp->show_all_gross==1) {
        // $payroll=Payroll::find($request->payroll_id)->payroll_logs->where('payroll_type','project')->pluck('details');
    }

    public function userPayrollDetail(Request $request)
    {
        $detail = PayrollDetail::find($request->payroll_detail_id);
        return view('compensation.partials.userPayrollDetails', compact('detail'));
    }

    public function userPayrollList(Request $request)
    {
        return view('compensation.userPayrollList');
    }

    public function exportForD365(Request $request)
    {

        $company_id = companyId();

        $sections = \App\UserSection::where('company_id', $company_id)->with('users.user_groups')->get();
        $allusers = \App\User::where('company_id', $company_id)->with('user_groups')->get();
        // return ($allusers);
        $payroll = Payroll::find($request->payroll_id);
        $chart_of_accounts = \App\ChartOfAccount::where(['company_id' => $company_id, 'status' => 1,])->orderBy('position')->get();
        $payroll_details = $payroll->payroll_details;
        $specific_salary_component_types = \App\SpecificSalaryComponentType::where('company_id', $company_id)->get();
        $users = \App\User::where('company_id', $company_id)->with('user_groups')->get();
        $lsas = \App\LongServiceAward::where('company_id', $company_id)->orderBy('max_year', 'ASC')->get();
        $pp = PayrollPolicy::where('company_id', $company_id)->first();

        if ($payroll) {

            $days = cal_days_in_month(CAL_GREGORIAN, $payroll->month, $payroll->year);
            $date = date('Y-m-d', strtotime($payroll->year . '-' . $payroll->month . '-' . $days));
            $view = 'compensation.partials.navpayroll';
//             return view('compensation.partials.navpayroll_int',compact('sections','payroll','chart_of_accounts','payroll_details','date','specific_salary_component_types', 'allusers','pp','lsas'));
            return \Excel::create("export", function ($excel) use ($view, $sections, $payroll, $chart_of_accounts, $payroll_details, $date, $specific_salary_component_types, $allusers, $pp, $lsas) {

                $excel->sheet("export", function ($sheet) use ($view, $sections, $payroll, $chart_of_accounts, $payroll_details, $date, $specific_salary_component_types, $allusers, $pp, $lsas) {
                    $sheet->loadView("$view", compact('sections', 'payroll', 'chart_of_accounts', 'payroll_details', 'date', 'specific_salary_component_types', 'allusers', 'pp', 'lsas'))
                        ->setOrientation('landscape');
                    $sheet->setColumnFormat(array(

                        'F' => '0.00', 'G' => '0.00', 'H' => '0.00', 'I' => '0.00', 'J' => '0.00', 'K' => '0.00', 'L' => '0.00', 'M' => '0.00', 'N' => '0.00', '0' => '0.00', 'P' => '0.00', 'Q' => '0.00', 'R' => '0.00', 'S' => '0.00', 'T' => '0.00', 'U' => '0.00', 'V' => '0.00', 'W' => '0.00', 'X' => '0.00', 'Y' => '0.00', 'Z' => '0.00', 'AA' => '0.00', 'AB' => '0.00', 'AC' => '0.00', 'AD' => '0.00', 'AE' => '0.00', 'AF' => '0.00', 'AG' => '0.00', 'AH' => '0.00', 'AI' => '0.00', 'AJ' => '0.00', 'AK' => '0.00', 'AL' => '0.00', 'AM' => '0.00', 'AN' => '0.00', 'A0' => '0.00', 'AP' => '0.00', 'AQ' => '0.00', 'AR' => '0.00', 'AS' => '0.00', 'AT' => '0.00', 'AU' => '0.00', 'V' => '0.00', 'AW' => '0.00', 'AX' => '0.00', 'AY' => '0.00', 'AZ' => '0.00', 'AB' => '0.00', 'BB' => '0.00', 'BC' => '0.00', 'BD' => '0.00', 'BE' => '0.00', 'BF' => '0.00', 'BG' => '0.00', 'BH' => '0.00', 'BI' => '0.00', 'BJ' => '0.00', 'BK' => '0.00', 'BL' => '0.00', 'BM' => '0.00', 'BN' => '0.00', 'B0' => '0.00', 'BP' => '0.00', 'BQ' => '0.00', 'BR' => '0.00', 'BS' => '0.00', 'BT' => '0.00', 'BU' => '0.00', 'V' => '0.00', 'BW' => '0.00', 'BX' => '0.00', 'BY' => '0.00', 'BZ' => '0.00'
                    ));
                });
            })->export('xlsx');
        }
    }


    public function exportForExcel(Request $request)
    {


        // $company_id=companyId();
        // if ($company_id==0) {
        //    $departments=Department::all();
        // } else {
        //     $departments=Department::where('company_id',$company_id)->get();
        // }
        $company_id = companyId();


        $payroll = Payroll::find($request->payroll_id);

        $pp = PayrollPolicy::where('company_id', $company_id)->first();
        $salary_categories = \App\PaceSalaryCategory::where('company_id', $company_id)->get();
        $lsas = \App\LongServiceAward::where('company_id', $company_id)->orderBy('max_year', 'ASC')->get();


        // return $payroll->payroll_details->count();
        if ($pp->show_all_gross == 1) {
            $view = 'compensation.partials.payrollexcel';
        } else {
            $view = 'compensation.partials.payrollexcel4';
        }

        $salary_components = \App\SalaryComponent::where('company_id', $company_id)->get();
        $salary_components = $salary_components->map(function ($item, $key) {
            return [
                'name' => $item->name,
                'constant' => $item->constant,
                'type' => $item->type
            ];
        });
        $project_salary_components = \App\PaceSalaryComponent::where('company_id', $company_id)->get();
         $project_salary_components = $project_salary_components->map(function ($item, $key) {
            return [
                'name' => $item->name,
                'constant' => $item->constant,
                'type' => $item->type
            ];
        });
        $specific_salary_component_types = \App\SpecificSalaryComponentType::where(['company_id' => $company_id, 'display' => 1])->get();
        if(count($project_salary_components)>0 && count($salary_components)>0){
//            return $salary_components;
             $components = $salary_components->merge($project_salary_components);
        $components = $components->unique('constant');
        }elseif(count($project_salary_components)>0){
             $components = $project_salary_components;
        $components = $components->unique('constant');
        }elseif(count($salary_components)>0){
             $components = $salary_components;
        $components = $components->unique('constant');
        }
       
        //   $view='compensation.partials.payrollexcel';
        //  }else{
        //   $view='compensation.partials.payrollexcel2';
        //  }
        $chart_of_accounts = \App\ChartOfAccount::where(['company_id' => $company_id, 'broadsheet_status' => 1,])->orderBy('position')->get();
        $specific_salary_component_types = \App\SpecificSalaryComponentType::where(['company_id' => $company_id, 'display' => 1])->get();
        // return view('compensation.partials.payrollexcel3',compact('payroll','specific_salary_component_types','pp','salary_categories','components'));
        return \Excel::create("export", function ($excel) use ($view, $payroll, $specific_salary_component_types, $pp, $salary_categories, $components, $chart_of_accounts, $lsas) {

            $excel->sheet("export", function ($sheet) use ($view, $payroll, $specific_salary_component_types, $pp, $salary_categories, $components, $chart_of_accounts, $lsas) {
                $sheet->loadView("$view", compact('payroll', 'specific_salary_component_types', 'pp', 'salary_categories', 'components', 'chart_of_accounts', 'lsas'))
                    ->setOrientation('landscape');
                $sheet->setColumnFormat(array(

                    'C' => '0.00', 'D' => '0.00', 'E' => '0.00', 'F' => '0.00', 'G' => '0.00', 'H' => '0.00', 'I' => '0.00', 'J' => '0.00', 'K' => '0.00', 'L' => '0.00', 'M' => '0.00', 'N' => '0.00', '0' => '0.00', 'P' => '0.00', 'Q' => '0.00', 'R' => '0.00', 'S' => '0.00', 'T' => '0.00', 'U' => '0.00', 'V' => '0.00', 'W' => '0.00', 'X' => '0.00', 'Y' => '0.00', 'Z' => '0.00', 'AA' => '0.00', 'AB' => '0.00', 'AC' => '0.00', 'AD' => '0.00', 'AE' => '0.00', 'AF' => '0.00', 'AG' => '0.00', 'AH' => '0.00', 'AI' => '0.00', 'AJ' => '0.00', 'AK' => '0.00', 'AL' => '0.00', 'AM' => '0.00', 'AN' => '0.00', 'A0' => '0.00', 'AP' => '0.00', 'AQ' => '0.00', 'AR' => '0.00', 'AS' => '0.00', 'AT' => '0.00', 'AU' => '0.00', 'V' => '0.00', 'AW' => '0.00', 'AX' => '0.00', 'AY' => '0.00', 'AZ' => '0.00', 'BA' => '0.00', 'BB' => '0.00', 'BC' => '0.00', 'BD' => '0.00', 'BE' => '0.00', 'BF' => '0.00', 'BG' => '0.00', 'BH' => '0.00', 'BI' => '0.00', 'BJ' => '0.00', 'BK' => '0.00', 'BL' => '0.00', 'BM' => '0.00', 'BN' => '0.00', 'B0' => '0.00', 'BP' => '0.00', 'BQ' => '0.00', 'BR' => '0.00', 'BS' => '0.00', 'BT' => '0.00', 'BU' => '0.00', 'V' => '0.00', 'BW' => '0.00', 'BX' => '0.00', 'BY' => '0.00', 'BZ' => '0.00'
                ));
            });
            foreach ($payroll->tmsa_components as $component) {
                if ($component->uses_month == 1) {
                    $collection = collect(new  \App\TmsaSchedule);
                    foreach ($component->months as $month) {
                        $month_collections = \App\TmsaSchedule::whereMonth('for', $month->month)->whereYear('for', $month->year)->get();
                        foreach ($month_collections as $colt) {
                            $collection->push($colt);
                        }
                    }
                    $excel->sheet($component->name, function ($sheet) use ($component, $payroll, $collection) {

                        $sheet->loadView('compensation.partials.excel_breakdown', compact('component', 'payroll', 'collection'))->setOrientation('landscape');
                    });
                }

            }

        })->export('xlsx');
    }

    public function select_payroll_type(Request $request){
        $sections=\App\UserSection::where('company_id',$company_id)->get();
        return  view('compensation.sections', compact('date', 'employees', 'has_been_run','sections'));
    }

    public function payrollList(Request $request)
    {
        
        if ($request->filled('month')) {
            $date = date('Y-m-d', strtotime('01-' . $request->month));
        } else {
            $date = date('Y-m-d');
        }
        $company_id = companyId();
        // $company=\Auth::user()->company;
        $pmonth = date('m', strtotime($date));
        $pyear = date('Y', strtotime($date));
        
        $payroll = Payroll::where(['month' => $pmonth, 'year' => $pyear, 'company_id' => $company_id])->first();
        
       

        if ($payroll) {
            $date = date('Y-m-d', strtotime($payroll->for));
            $allowances = 0;
            $deductions = 0;
            $income_tax = 0;
            $salary = 0;
            $has_been_run = 1;
            foreach ($payroll->payroll_details as $detail) {
                $salary += $detail->basic_pay;
                $allowances += $detail->allowances;
                $deductions += $detail->deductions;
                $income_tax += $detail->paye;

            }
            // Auth::user()->notify(new ApprovePayroll($payroll));
            return view('compensation.payroll', compact('payroll', 'allowances', 'deductions', 'income_tax', 'salary', 'date', 'has_been_run'));
        } else {
            $has_been_run = 0;
            // $employees=\Auth::user()->company->users()->has('promotionHistories.grade')->get();
            $employees = User::where(['company_id' => $company_id])->where('status', '!=', 2)->get();
            // Company::where('id',$company_id)->first()->users()->has('promotionHistories.grade')->->get();


            return view('compensation.payroll', compact('date', 'employees', 'has_been_run'));

        }

    }

    public function getUserNetPay($user_id)
    {
        $user = User::has('promotionHistories.grade')->where(['id' => $user_id])->first();

        $payroll = [];
        $payroll['user_id'] = $user->id;
        $payroll['date'] = date('Y-m-d');
        $payroll['month'] = $pmonth = date('m');
        $payroll['year'] = $pyear = date('Y');
        $company_id = companyId();

        $payroll['company_id'] = $company_id;
        $payroll['start_day'] = 1;


        if (date('m', strtotime($user->hiredate)) == $pmonth && date('Y', strtotime($user->hiredate)) != $pyear) {
            $payroll['is_anniversary'] = 1;
        } else {
            $payroll['is_anniversary'] = 0;
        }
        $payroll['working_days'] = $this->getExpectedDays($pmonth, $pyear, $company_id);
        $payroll['days_worked'] = $this->getEmployeeDays($pmonth, $pyear, $payroll['start_day'], 31, $company_id);
        $this->calculatePAYE($payroll);
        if ($payroll['has_grade'] == 1) {
            $payroll['serialize']['allowances'] = $payroll['allowances'];
            $payroll['serialize']['deductions'] = $payroll['deductions'];
            $payroll['serialize']['component_names'] = $payroll['component_names'];
            $payroll['serialize'] = serialize($payroll['serialize']);
            return $netpay = ($payroll['basic_pay'] + $payroll['total_allowances'] - $payroll['total_deductions'] - $payroll['paye']) * 12;

        }
    }

    public function runPayroll(Request $request)
    {
        $company_id = companyId();
        $creator_id = Auth::user()->id;
        $thread_id = mt_rand(0, 11111111);
        \Artisan::queue('payroll:run', ['month' => $request->month, 'company_id' => $company_id, 'creator_id' => $creator_id, 'thread_id' => $thread_id]);
        return response()->json(['status' => 'success', 'thread_id' => $thread_id]);
    }


    public function commandrunPayroll($request)
    {
        

        $date = date('Y-m-d', strtotime('01-' . $request->month));
        $pmonth = date('m', strtotime($date));
        $pyear = date('Y', strtotime($date));
        // $company=Company::find($request->company_id);
        $company_id = $request->company_id;
        $creator_id = $request->creator_id;
        // $section=\App\UserSection::find($request->section);
        
        $basic_pay_percentage = intval(PayrollPolicy::where(['company_id' => $company_id])->first()->basic_pay_percentage) / 100;
        $users = User::has('promotionHistories.grade')->where('company_id', $company_id)->where('payroll_type', 'office')->where('status', '!=', 2)->orWhereHas('separations', function ($query) use ($date) {
            $query->where('separations.date_of_separation', '>=', $date)
                ->where('users.payroll_type', 'office');

        })->with('separations')->get();
       
        $tmsa_users = User::where('company_id', $company_id)->where('payroll_type', 'tmsa')->where('status', '!=', 2)->orWhereHas('separations', function ($query) use ($date) {
            $query->where('separations.date_of_separation', '>=', $date)
                ->where('users.payroll_type', 'tmsa');

        })->with('separations')->get();
        $project_users = User::where('company_id', $company_id)->where('payroll_type', 'project')->where('status', '!=', 2)->orWhereHas('separations', function ($query) use ($date) {
            $query->where('separations.date_of_separation', '>=', $date)
                ->where('users.payroll_type', 'project');

        })->with('separations')->get();
        
     
        $payroll = Payroll::where(['month' => $pmonth, 'year' => $pyear, 'company_id' => $company_id])->first();
        $pp = PayrollPolicy::where('company_id', $company_id)->first();
        $tp = TmsaPolicy::where(['company_id' => $company_id])->first();
       
        if (!$pp->workflow) {
             info( 'Please Select a Workflow in the payroll settings');
            return false;
        }
      
        if (isset($pp->use_tmsa) && isset($tp) && $pp->use_tmsa == 1 && $tp->out_of_station == '') {
            return redirect()->back()->with('error', 'Please setup a out of station allowance in payroll settings');
        }
         
        if ($payroll) {
            return redirect()->back();
        } else {
                 
            if ($pp->use_office == 1 || $pp->use_tmsa == 1 || $pp->use_project == 1) {
               
                
                $PR = Payroll::create(['month' => $pmonth, 'year' => $pyear, 'company_id' => $company_id, 'workflow_id' => $pp->workflow->id, 'for' => $date, 'user_id' => $creator_id]);


                $allp = [];
                $progress = 0;
                $i = 1;
                $total = $tmsa_users->count() + $users->count()+$project_users->count();

                if ($pp->use_office == 1 && $users->count() > 0) {
                    $components = SalaryComponent::where(['status' => 1, 'company_id' => $PR->company_id])->get()->pluck('id');

                    $PR->salary_components()->attach($components);
                    $allp = [];
                    foreach ($users as $user) {

                        if (date('Y-m-d', strtotime($user->hiredate)) <= date('Y-m-t', strtotime($date))) {

                            $payroll = [];
                            $payroll['payroll'] = $PR;
                            $payroll['user_id'] = $user->id;
                            $payroll['date'] = $date;
                            $payroll['month'] = $pmonth;
                            $payroll['year'] = $pyear;
                            $payroll['company_id'] = $company_id;
                            $payroll['suspension_deficit'] = 0;
                            $end = 0;

                            if (date('m', strtotime($user->hiredate)) == $pmonth && date('Y', strtotime($user->hiredate)) == $pyear) {
                                $payroll['start_day'] = date('d', strtotime($user->hiredate));
                            } else {
                                $payroll['start_day'] = 1;
                            }
                            if (count($user->separations) > 0) {
                                $separation = \App\Separation::whereMonth('date_of_separation', $pmonth)->whereYear('date_of_separation', $pyear)->where('user_id', $user->id)->first();
                                if ($separation) {
                                    $end = date('d', strtotime($separation->date_of_separation));
                                } else {
                                    $end = 0;
                                }
                            }
                            if (count($user->suspensions) > 0) {

                                $suspension_deductions = \App\SuspensionDeduction::whereHas('suspension', function ($query) use ($user) {
                                    $query->where('user_id', $user->id);
                                })
                                    ->where('deducted', 0)->where('date', '<=', $date)->get();
                                $suspension_deduction_ids = \App\SuspensionDeduction::whereHas('suspension', function ($query) use ($user) {
                                    $query->where('user_id', $user->id);
                                })
                                    ->where('deducted', 0)->where('date', '<=', $date)->get()->pluck('id');
                                $PR->suspension_deductions()->attach($suspension_deduction_ids);
                                foreach ($suspension_deductions as $deduction) {
                                    $payroll['suspension_deficit'] += $deduction->days;
                                    $deduction->deducted = 1;
                                    $deduction->save();
                                }


                            }

                            if (date('m', strtotime($user->hiredate)) == $pmonth && date('Y', strtotime($user->hiredate)) != $pyear) {
                                $payroll['is_anniversary'] = 1;
                            } else {
                                $payroll['is_anniversary'] = 0;
                            }
                            $payroll['working_days'] = $this->getExpectedDays($pmonth, $pyear, $company_id);
                            $payroll['days_worked'] = $this->getEmployeeDays($pmonth, $pyear, $payroll['start_day'], $end, $company_id);

                            $payroll['days_worked'] = $payroll['days_worked'] - $payroll['suspension_deficit'];

                            try {
                                $this->calculatePAYE($payroll);

                            // $this->calculate_loan($payroll);
                            if (@$payroll['has_grade'] == 1) {
                                $payroll['serialize']['allowances'] = $payroll['allowances'];
                                $payroll['serialize']['deductions'] = $payroll['deductions'];
                                $payroll['serialize']['component_names'] = $payroll['component_names'];
                                $payroll['serialize'] = serialize($payroll['serialize']);

                                $payroll['sc_serialize']['sc_allowances'] = $payroll['sc_allowances'];
                                $payroll['sc_serialize']['sc_deductions'] = $payroll['sc_deductions'];
                                $payroll['sc_serialize']['sc_component_names'] = $payroll['sc_component_names'];
                                $payroll['sc_serialize']['sc_project_code'] = $payroll['sc_project_code'];
                                $payroll['sc_serialize']['sc_gl_code'] = $payroll['sc_gl_code'];
                                $payroll['sc_serialize'] = serialize($payroll['sc_serialize']);

                                $payroll['ssc_serialize']['ssc_allowances'] = $payroll['ssc_allowances'];
                                $payroll['ssc_serialize']['ssc_deductions'] = $payroll['ssc_deductions'];
                                $payroll['ssc_serialize']['ssc_component_names'] = $payroll['ssc_component_names'];
                                $payroll['ssc_serialize']['ssc_project_code'] = $payroll['ssc_project_code'];
                                $payroll['ssc_serialize']['ssc_gl_code'] = $payroll['ssc_gl_code'];
                                $payroll['ssc_serialize']['ssc_display'] = $payroll['ssc_display'];
                                $payroll['ssc_serialize']['ssc_annual_tax'] = $payroll['ssc_annual_tax'];
                                $payroll['ssc_serialize']['ssc_tax'] = $payroll['ssc_tax'];
                                $payroll['ssc_serialize']['ssc_taxable'] = $payroll['ssc_taxable'];
                                $payroll['ssc_serialize']['ssc_component_category'] = $payroll['ssc_component_category'];
                                $payroll['ssc_serialize']['ssc_amount'] = $payroll['ssc_amount'];
                                $payroll['ssc_serialize']['ssc_component_type'] = $payroll['ssc_component_type'];
                                $payroll['ssc_serialize'] = serialize($payroll['ssc_serialize']);
                                if ($user->union) {
                                    $netpay = $payroll['basic_pay'] + $payroll['total_allowances'] - $payroll['total_deductions'] - $payroll['paye'];
                                    $payroll['netpay'] = $netpay;
                                    if ($netpay > 0) {
                                        \App\PayrollLog::create(['payroll_id' => $PR->id, 'for' => $date, 'details' => $payroll, 'user_id' => $payroll['user_id'], 'created_by' => $creator_id, 'created_at' => $PR->created_at, 'payroll_type' => 'office', 'status' => 1]);
                                        $payroll_details = PayrollDetail::create(['payroll_id' => $PR->id, 'user_id' => $payroll['user_id'], 'annual_gross_pay' => $payroll['gross_pay'], 'gross_pay' => $payroll['gross_pay'] / 12, 'basic_pay' => $payroll['basic_pay'], 'deductions' => $payroll['total_deductions'], 'allowances' => $payroll['total_allowances'], 'sc_allowances' => $payroll['sc_total_allowances'], 'sc_deductions' => $payroll['sc_total_deductions'], 'ssc_allowances' => $payroll['ssc_total_allowances'], 'ssc_deductions' => $payroll['ssc_total_deductions'], 'working_days' => $payroll['working_days'], 'worked_days' => $payroll['days_worked'], 'details' => $payroll['serialize'], 'sc_details' => $payroll['sc_serialize'], 'ssc_details' => $payroll['ssc_serialize'], 'is_anniversary' => $payroll['is_anniversary'], 'taxable_income' => $payroll['taxable_income'], 'annual_paye' => $payroll['annual_paye'], 'paye' => $payroll['paye'], 'consolidated_allowance' => $payroll['consolidated_allowance'], 'netpay' => $netpay, 'payroll_type' => 'office', 'union_dues' => $payroll['union_dues']]);
                                    } else {
                                        \App\PayrollLog::create(['payroll_id' => $PR->id, 'for' => $date, 'details' => $payroll, 'user_id' => $payroll['user_id'], 'created_by' => $creator_id, 'created_at' => $PR->created_at, 'payroll_type' => 'office', 'status' => 0, 'issue' => 'Negative Net Pay']);
                                    }
                                } else {
                                    $netpay = $payroll['basic_pay'] + $payroll['total_allowances'] - $payroll['total_deductions'] - $payroll['paye'];
                                    $payroll['netpay'] = $netpay;
                                    if ($netpay > 0) {
                                        \App\PayrollLog::create(['payroll_id' => $PR->id, 'for' => $date, 'details' => $payroll, 'user_id' => $payroll['user_id'], 'created_by' => $creator_id, 'created_at' => $PR->created_at, 'payroll_type' => 'office', 'status' => 1]);
                                        $payroll_details = PayrollDetail::create(['payroll_id' => $PR->id, 'user_id' => $payroll['user_id'], 'annual_gross_pay' => $payroll['gross_pay'], 'gross_pay' => $payroll['gross_pay'] / 12, 'basic_pay' => $payroll['basic_pay'], 'deductions' => $payroll['total_deductions'], 'allowances' => $payroll['total_allowances'], 'sc_allowances' => $payroll['sc_total_allowances'], 'sc_deductions' => $payroll['sc_total_deductions'], 'ssc_allowances' => $payroll['ssc_total_allowances'], 'ssc_deductions' => $payroll['ssc_total_deductions'], 'working_days' => $payroll['working_days'], 'worked_days' => $payroll['days_worked'], 'details' => $payroll['serialize'], 'sc_details' => $payroll['sc_serialize'], 'ssc_details' => $payroll['ssc_serialize'], 'is_anniversary' => $payroll['is_anniversary'], 'taxable_income' => $payroll['taxable_income'], 'annual_paye' => $payroll['annual_paye'], 'paye' => $payroll['paye'], 'consolidated_allowance' => $payroll['consolidated_allowance'], 'netpay' => $netpay, 'payroll_type' => 'office']);
                                    } else {
                                        \App\PayrollLog::create(['payroll_id' => $PR->id, 'for' => $date, 'details' => $payroll, 'user_id' => $payroll['user_id'], 'created_by' => $creator_id, 'created_at' => $PR->created_at, 'payroll_type' => 'office', 'status' => 0, 'issue' => 'Negative Net Pay']);
                                    }
                                }


                            }
                            } catch (\Exception $e) {
                                \App\PayrollLog::create(['payroll_id' => $PR->id, 'for' => $date, 'details' => $payroll, 'user_id' => $payroll['user_id'], 'created_by' => $creator_id, 'created_at' => $PR->created_at, 'payroll_type' => 'office', 'status' => 0, 'issue' => $e->getMessage()]);
                            }

                            $allp[] = $payroll;


                        }
                        $i++;
                        $this->reportProgress($i, $total, $request->thread_id);

                    }
                }
                if ($pp->use_tmsa == 1 && $tmsa_users->count() > 0) {
                    $components = TmsaComponent::where(['status' => 1, 'company_id' => $PR->company_id])->get()->pluck('id');

                    $PR->tmsa_components()->attach($components);
                    foreach ($tmsa_users as $tuser) {
                        $payroll = [];
                        $payroll['payroll'] = $PR;
                        $payroll['user_id'] = $tuser->id;
                        $payroll['date'] = $date;
                        $payroll['month'] = $pmonth;
                        $payroll['year'] = $pyear;
                        $payroll['company_id'] = $company_id;
                        if (date('m', strtotime($tuser->hiredate)) == $pmonth && date('Y', strtotime($tuser->hiredate)) == $pyear) {
                            $payroll['start_day'] = date('d', strtotime($tuser->hiredate));
                        } else {
                            $payroll['start_day'] = 1;
                        }

                        if (date('m', strtotime($tuser->hiredate)) == $pmonth && date('Y', strtotime($tuser->hiredate)) != $pyear) {
                            $payroll['is_anniversary'] = 1;
                        } else {
                            $payroll['is_anniversary'] = 0;
                        }
                        $payroll['has_timesheet'] = 0;
                        try {
                            $this->tmsa_calc($payroll);
                        } catch (\Exception $e) {
                            \App\PayrollLog::create(['payroll_id' => $PR->id, 'for' => $date, 'details' => $payroll, 'user_id' => $payroll['user_id'], 'created_by' => $creator_id, 'created_at' => $PR->created_at, 'payroll_type' => 'tmsa', 'status' => 0, 'issue' => $e->getMessage()]);
                        }
                        if ($payroll['has_timesheet'] == 1) {
                            $payroll['serialize']['allowances'] = $payroll['allowances'];
                            $payroll['serialize']['deductions'] = $payroll['deductions'];
                            $payroll['serialize']['component_names'] = $payroll['component_names'];
                            $payroll['serialize'] = serialize($payroll['serialize']);

                            $payroll['sc_serialize']['sc_allowances'] = $payroll['sc_allowances'];
                            $payroll['sc_serialize']['sc_deductions'] = $payroll['sc_deductions'];
                            $payroll['sc_serialize']['sc_component_names'] = $payroll['sc_component_names'];
                            $payroll['sc_serialize']['sc_project_code'] = $payroll['sc_project_code'];
                            $payroll['sc_serialize']['sc_gl_code'] = $payroll['sc_gl_code'];
                            $payroll['sc_serialize'] = serialize($payroll['sc_serialize']);

                            $payroll['ssc_serialize']['ssc_allowances'] = $payroll['ssc_allowances'];
                            $payroll['ssc_serialize']['ssc_deductions'] = $payroll['ssc_deductions'];
                            $payroll['ssc_serialize']['ssc_component_names'] = $payroll['ssc_component_names'];
                            $payroll['ssc_serialize']['ssc_project_code'] = $payroll['ssc_project_code'];
                            $payroll['ssc_serialize']['ssc_gl_code'] = $payroll['ssc_gl_code'];
                            $payroll['ssc_serialize']['ssc_display'] = $payroll['ssc_display'];
                            $payroll['ssc_serialize']['ssc_annual_tax'] = $payroll['ssc_annual_tax'];
                            $payroll['ssc_serialize']['ssc_tax'] = $payroll['ssc_tax'];
                            $payroll['ssc_serialize']['ssc_taxable'] = $payroll['ssc_taxable'];
                            $payroll['ssc_serialize']['ssc_component_category'] = $payroll['ssc_component_category'];
                            $payroll['ssc_serialize']['ssc_amount'] = $payroll['ssc_amount'];
                            $payroll['ssc_serialize']['ssc_component_type'] = $payroll['ssc_component_type'];
                            $payroll['ssc_serialize'] = serialize($payroll['ssc_serialize']);

                            $netpay = $payroll['gross_pay'] + $payroll['total_allowances'] + $payroll['out_of_station_allowance'] + $payroll['brt_allowance'] - $payroll['total_deductions'] - $payroll['paye'] - $payroll['monthly_employee_pension'];
                            if ($netpay > 0) {
                                $payroll['netpay'] = $netpay;
                                \App\PayrollLog::create(['payroll_id' => $PR->id, 'for' => $date, 'details' => $payroll, 'user_id' => $payroll['user_id'], 'created_by' => $creator_id, 'created_at' => $PR->created_at, 'payroll_type' => 'tmsa', 'status' => 1]);

                                $payroll_details = TmsaPayrollDetail::create(['user_id' => $payroll['user_id'], 'payroll_id' => $PR->id, 'onshore_day_rate' => $payroll['day_rate_onshore'], 'days_worked_onshore' => $payroll['days_worked_onshore'], 'offshore_day_rate' => $payroll['day_rate_offshore'], 'days_worked_offshore' => $payroll['days_worked_offshore'], 'total_gross_pay' => $payroll['gross_pay'], 'annual_gross_pay' => $payroll['annual_gross_pay'], 'annual_employee_pension_contribution' => $payroll['annual_employee_pension'], 'monthly_employee_pension_contribution' => $payroll['monthly_employee_pension'], 'allowances' => $payroll['total_allowances'], 'deductions' => $payroll['total_deductions'], 'personal_allowances' => $payroll['ssc_total_allowances'], 'personal_deductions' => $payroll['ssc_total_deductions'], 'details' => $payroll['sc_serialize'], 'personal_details' => $payroll['ssc_serialize'], 'total_relief' => $payroll['total_relief'], 'taxable_income' => $payroll['taxable_income'], 'annual_paye' => $payroll['annual_paye'], 'monthly_paye' => $payroll['paye'], 'cra' => $payroll['consolidated_allowance'], 'netpay' => $netpay, 'out_of_station_allowance' => $payroll['out_of_station_allowance'], 'brt_allowance' => $payroll['brt_allowance'],'leave_allowance'=>$payroll['leave_allowance']]);
                            } else {
                                \App\PayrollLog::create(['payroll_id' => $PR->id, 'for' => $date, 'details' => $payroll, 'user_id' => $payroll['user_id'], 'created_by' => $creator_id, 'created_at' => $PR->created_at, 'payroll_type' => 'tmsa', 'status' => 0, 'issue' => 'Negative Net Pay']);
                            }
                        }
                        $i++;
                        $this->reportProgress($i, $total, $request->thread_id);

                    }
                }
                if ($pp->use_project == 1 && $project_users->count() > 0) {
                    $components = PaceSalaryComponent::where(['status' => 1, 'company_id' => $PR->company_id])->get()->pluck('id');

                    $PR->project_salary_components()->attach($components);
                    foreach ($project_users as $puser) {
                        $payroll = [];
                        $payroll['payroll'] = $PR;
                        $payroll['user_id'] = $puser->id;
                        $this->info($puser->id);
                        $payroll['date'] = $date;
                        $payroll['month'] = $pmonth;
                        $payroll['year'] = $pyear;
                        $payroll['company_id'] = $company_id;
                        $payroll['pp'] = $pp;
                        $payroll['suspension_deficit'] = 0;
                        $end = 0;
                        if (date('m', strtotime($puser->hiredate)) == $pmonth && date('Y', strtotime($puser->hiredate)) == $pyear) {
                            $payroll['start_day'] = date('d', strtotime($puser->hiredate));
                        } else {
                            $payroll['start_day'] = 1;
                        }

                        if (date('m', strtotime($puser->hiredate)) == $pmonth && date('Y', strtotime($puser->hiredate)) != $pyear) {
                            $payroll['is_anniversary'] = 1;
                        } else {
                            $payroll['is_anniversary'] = 0;
                        }
                        if ($puser->separations) {
                            $separation = \App\Separation::whereMonth('date_of_separation', $pmonth)->whereYear('date_of_separation', $pyear)->where('user_id', $puser->id)->first();
                            if ($separation) {
                                $end = date('d', strtotime($separation->date_of_separation));
                            } else {
                                $end = 0;
                            }
                        }

                        if (count($puser->suspensions) > 0) {

                            $suspension_deductions = \App\SuspensionDeduction::whereHas('suspension', function ($query) use ($puser) {
                                $query->where('user_id', $puser->id);
                            })
                                ->where('deducted', 0)->where('date', '<=', $date)->get();
                            $suspension_deduction_ids = \App\SuspensionDeduction::whereHas('suspension', function ($query) use ($puser) {
                                $query->where('user_id', $puser->id);
                            })
                                ->where('deducted', 0)->where('date', '<=', $date)->get()->pluck('id');
                            $PR->suspension_deductions()->attach($suspension_deduction_ids);
                            foreach ($suspension_deductions as $deduction) {
                                $payroll['suspension_deficit'] += $deduction->days;
                                $deduction->deducted = 1;
                                $deduction->save();
                            }


                        }
                        $payroll['working_days'] = $this->getExpectedDays($pmonth, $pyear, $company_id);
                        $payroll['days_worked'] = $this->getEmployeeDays($pmonth, $pyear, $payroll['start_day'], $end, $company_id);
                        $payroll['days_worked'] = $payroll['days_worked'] - $payroll['suspension_deficit'];

                        try {
                            $this->calculateProjectPAYE($payroll);

                        if (@$payroll['has_category'] == 1) {
                            try {
                                $payroll['serialize']['allowances'] = $payroll['allowances'];
                                $payroll['serialize']['deductions'] = $payroll['deductions'];
                                $payroll['serialize']['component_names'] = $payroll['component_names'];
                                $payroll['serialize'] = serialize($payroll['serialize']);

                                $payroll['sc_serialize']['sc_allowances'] = $payroll['sc_allowances'];
                                $payroll['sc_serialize']['sc_deductions'] = $payroll['sc_deductions'];
                                $payroll['sc_serialize']['sc_component_names'] = $payroll['sc_component_names'];
                                $payroll['sc_serialize']['sc_project_code'] = $payroll['sc_project_code'];
                                $payroll['sc_serialize']['sc_gl_code'] = $payroll['sc_gl_code'];
                                $payroll['sc_serialize'] = serialize($payroll['sc_serialize']);

                                $payroll['ssc_serialize']['ssc_allowances'] = $payroll['ssc_allowances'];
                                $payroll['ssc_serialize']['ssc_deductions'] = $payroll['ssc_deductions'];
                                $payroll['ssc_serialize']['ssc_component_names'] = $payroll['ssc_component_names'];
                                $payroll['ssc_serialize']['ssc_project_code'] = $payroll['ssc_project_code'];
                                $payroll['ssc_serialize']['ssc_gl_code'] = $payroll['ssc_gl_code'];
                                $payroll['ssc_serialize']['ssc_display'] = $payroll['ssc_display'];
                                $payroll['ssc_serialize']['ssc_annual_tax'] = $payroll['ssc_annual_tax'];
                                $payroll['ssc_serialize']['ssc_tax'] = $payroll['ssc_tax'];
                                $payroll['ssc_serialize']['ssc_taxable'] = $payroll['ssc_taxable'];
                                $payroll['ssc_serialize']['ssc_component_category'] = $payroll['ssc_component_category'];
                                $payroll['ssc_serialize']['ssc_amount'] = $payroll['ssc_amount'];
                                $payroll['ssc_serialize']['ssc_component_type'] = $payroll['ssc_component_type'];
                                $payroll['ssc_serialize'] = serialize($payroll['ssc_serialize']);
                            } catch (\Exception $e) {
                                \App\PayrollLog::create(['payroll_id' => $PR->id, 'for' => $date, 'details' => $payroll, 'user_id' => $payroll['user_id'], 'created_by' => $creator_id, 'created_at' => $PR->created_at, 'payroll_type' => 'project', 'status' => 0, 'issue' => $e->getMessage()]);
                            }

                            if ($puser->union) {
                                $netpay = $payroll['basic_pay'] + $payroll['total_allowances'] - $payroll['total_deductions'] - $payroll['paye'] - $payroll['union_dues'];

                                $payroll['netpay'] = $netpay;
                                if ($netpay > 0) {
                                    \App\PayrollLog::create(['payroll_id' => $PR->id, 'for' => $date, 'details' => $payroll, 'user_id' => $payroll['user_id'], 'created_by' => $creator_id, 'created_at' => $PR->created_at, 'payroll_type' => 'project', 'status' => 1]);
                                    $payroll_details = PayrollDetail::create(['payroll_id' => $PR->id, 'user_id' => $payroll['user_id'], 'annual_gross_pay' => $payroll['gross_pay'], 'gross_pay' => $payroll['gross_pay'] / 12, 'basic_pay' => $payroll['basic_pay'], 'deductions' => $payroll['total_deductions'], 'allowances' => $payroll['total_allowances'], 'sc_allowances' => $payroll['sc_total_allowances'], 'sc_deductions' => $payroll['sc_total_deductions'], 'ssc_allowances' => $payroll['ssc_total_allowances'], 'ssc_deductions' => $payroll['ssc_total_deductions'], 'working_days' => $payroll['working_days'], 'worked_days' => $payroll['days_worked'], 'details' => $payroll['serialize'], 'sc_details' => $payroll['sc_serialize'], 'ssc_details' => $payroll['ssc_serialize'], 'is_anniversary' => $payroll['is_anniversary'], 'taxable_income' => $payroll['taxable_income'], 'annual_paye' => $payroll['annual_paye'], 'paye' => $payroll['paye'], 'consolidated_allowance' => $payroll['consolidated_allowance'], 'netpay' => $netpay, 'payroll_type' => 'project', 'union_dues' => $payroll['union_dues']]);
                                } else {
                                    \App\PayrollLog::create(['payroll_id' => $PR->id, 'for' => $date, 'details' => serialize($payroll), 'user_id' => $payroll['user_id'], 'created_by' => $creator_id, 'created_at' => $PR->created_at, 'payroll_type' => 'project', 'status' => 0, 'issue' => 'Negative Net Pay']);
                                }
                            } else {

                                $netpay = $payroll['basic_pay'] + $payroll['total_allowances'] - $payroll['total_deductions'] - $payroll['paye'];
                                $payroll['netpay'] = $netpay;
                                if ($netpay > 0) {
                                    \App\PayrollLog::create(['payroll_id' => $PR->id, 'for' => $date, 'details' => serialize($payroll), 'user_id' => $payroll['user_id'], 'created_by' => $creator_id, 'created_at' => $PR->created_at, 'payroll_type' => 'project', 'status' => 1]);
                                    $payroll_details = PayrollDetail::create(['payroll_id' => $PR->id, 'user_id' => $payroll['user_id'], 'annual_gross_pay' => $payroll['gross_pay'], 'gross_pay' => $payroll['gross_pay'] / 12, 'basic_pay' => $payroll['basic_pay'], 'deductions' => $payroll['total_deductions'], 'allowances' => $payroll['total_allowances'], 'sc_allowances' => $payroll['sc_total_allowances'], 'sc_deductions' => $payroll['sc_total_deductions'], 'ssc_allowances' => $payroll['ssc_total_allowances'], 'ssc_deductions' => $payroll['ssc_total_deductions'], 'working_days' => $payroll['working_days'], 'worked_days' => $payroll['days_worked'], 'details' => $payroll['serialize'], 'sc_details' => $payroll['sc_serialize'], 'ssc_details' => $payroll['ssc_serialize'], 'is_anniversary' => $payroll['is_anniversary'], 'taxable_income' => $payroll['taxable_income'], 'annual_paye' => $payroll['annual_paye'], 'paye' => $payroll['paye'], 'consolidated_allowance' => $payroll['consolidated_allowance'], 'netpay' => $netpay, 'payroll_type' => 'project']);
                                } else {
                                    \App\PayrollLog::create(['payroll_id' => $PR->id, 'for' => $date, 'details' => serialize($payroll), 'user_id' => $payroll['user_id'], 'created_by' => $creator_id, 'created_at' => $PR->created_at, 'payroll_type' => 'project', 'status' => 0, 'issue' => 'Negative Net Pay']);
                                }
                            }


                        }
                        } catch (\Exception $e) {
                            \App\PayrollLog::create(['payroll_id' => $PR->id, 'for' => $date, 'details' => $payroll, 'user_id' => $payroll['user_id'], 'created_by' => $creator_id, 'created_at' => $PR->created_at, 'payroll_type' => 'project', 'status' => 0, 'issue' => $e->getMessage()]);
                        }
                        $i++;
                         $this->reportProgress($i, $total, $request->thread_id);

                    }
                    
                }
                // if ($pp->uses_approval == 0) {
                //     $PR->approved = 1;
                //     $PR->save();
                    
                // }
                
                       
            }


            // return redirect(url('compensation/payroll_list?month='.date('m-Y',strtotime($date))));
            return 'success';
        }


    }

    public function officePayroll($date, User $users)
    {
        # code...
    }

    public function reportProgress(&$i, $total, $thread_id)
    {
        $progress = ceil(($i / $total) * 100);
        $logProgress = \App\PayrollProgress::updateOrCreate(['thread_id' => $thread_id], ['thread_id' => $thread_id, 'progress' => $progress]);
        return true;
    }


    public function getProgress(Request $request)
    {
        $progress = \App\PayrollProgress::where('thread_id', $request->thread_id);
        $progressValue = $progress->value('progress');
        if ($progressValue >= 100) {
            $clearProgress = $progress->delete();
        }
        return response()->json(['status' => 'success', 'message' => $progressValue]);
    }


    public function calculatePAYE(&$payroll)
    {
        $user = User::find($payroll['user_id']);
        if ($user->promotionHistories) {

            $payroll['gross_pay'] = $user->promotionHistories()->latest()->first()->grade->basic_pay;
            $payroll['has_grade'] = 1;
            $payroll['ogross_pay'] = $payroll['gross_pay'];
            $payroll['gross_pay'] = ($payroll['gross_pay'] / $payroll['working_days']) * $payroll['days_worked'];

            $rebates = $user->specificSalaryComponents()->where('status', 1)
                ->where('type', 2)
                ->get();
            $sum_of_rebate = 0;
            foreach ($rebates as $rebate) {

                $sum_of_rebate += $rebate->amount;

            }

            $salary_review = SalaryReview::where(['payment_month' => $payroll['date'], 'employee_id' => $user->id])->first();
            if ($salary_review) {

                if (intval(date('Y', strtotime($salary_review->payment_month))) > intval(date('Y', strtotime($salary_review->review_month)))) {
                    $diffyear = intval(date('Y', strtotime($salary_review->payment_month))) - intval(date('Y', strtotime($salary_review->review_month)));
                    $diffmonths = (intval(date('m', strtotime($salary_review->payment_month))) + (12 * $diffyear)) - (intval(date('m', strtotime($salary_review->review_month))));

                } else {
                    $diffmonths = (intval(date('m', strtotime($salary_review->payment_month)))) - (intval(date('m', strtotime($salary_review->review_month))));
                }
                $gross_arrears = ($payroll['ogross_pay'] - $salary_review->previous_gross) * $diffmonths;
                $payroll['gross_pay'] += $gross_arrears;
                $salary_review->used = 1;
                $salary_review->save();
            }


            $payroll['basic_pay_percentage'] = floatval(PayrollPolicy::where(['company_id' => $payroll['company_id']])->first()->basic_pay_percentage) / 100;
            $payroll['basic_pay'] = $payroll['gross_pay'] * $payroll['basic_pay_percentage'];
            $this->allowancesanddeductions($payroll);
            //
            \Log::info('after sc');

            //
            $this->calculate_specific_salary_components($payroll);
            \Log::info('after ssc');
            $payroll['tax_gross_pay'] = ($payroll['gross_pay']);

            $payroll['tax_gross_pay'] = ($payroll['gross_pay']) + ($payroll['ssc_monthly_taxable_all'] * 12) + $payroll['ssc_annual_taxable_all'];
            $payroll['consolidated_allowance'] = $this->consolidated_allowance($payroll['tax_gross_pay'],($payroll['not_taxable'] * 12));
            $payroll['taxable_income'] = $payroll['tax_gross_pay'] - $payroll['consolidated_allowance'] - (($payroll['not_taxable']) * 12) - ($sum_of_rebate * 12) - 14000;// + ($payroll['ssc_total_allowances']*12);
            //
            // $annual_chargeable = ($payroll['basic_pay'] + $payroll['sc_total_allowances']) * 12;
            // $payroll['taxable_income']=  $annual_chargeable - $payroll['consolidated_allowance']-($payroll['not_taxable']*12)-14000 + ($payroll['ssc_total_allowances']*12);
            $payroll['tax_gross_pay_without_annual_allowances'] = ($payroll['gross_pay']) + ($payroll['ssc_monthly_taxable_all'] * 12);
            $payroll['consolidated_allowance_without_annual_allowances'] = $this->consolidated_allowance($payroll['tax_gross_pay_without_annual_allowances'],($payroll['not_taxable'] * 12));

            $payroll['taxable_income_without_annual_allowances'] = $payroll['tax_gross_pay_without_annual_allowances'] - $payroll['consolidated_allowance_without_annual_allowances'] - (($payroll['not_taxable']) * 12) - ($sum_of_rebate * 12) - 14000;
            $this->calculate_tax_without_annual_allowances($payroll);

            $this->calculate_tax($payroll);
        } else {

            $payroll['has_grade'] = 0;
        }


    }

    public function allowancesanddeductions(&$payroll)
    {
        $user_id = $payroll['user_id'];
        $user = \App\User::find($payroll['user_id']);
//		$components=SalaryComponent::where(['company_id'=>$payroll['company_id']])->get();
        $components = SalaryComponent::where(['company_id' => $payroll['company_id']])->whereDoesntHave('exemptions', function ($query) use ($user_id) {
            $query->where('salary_component_exemptions.user_id', $user_id);
        })->get();

        // foreach ($deductions as $deduction) {
        // 	$calc=$deduction->formula;
        // 	$calc=str_replace($basic_pay, '$'.$basic_pay, $calc);
        // 	$calc=str_replace($gross_pay, '$'.$gross_pay, $calc);
        // 	foreach ($components as $component) {
        // 		$calc=str_replace($component->constant, '$'.$component->constant, $calc);
        // 	}
        // 	$payroll[$deduction->constant]['name']=>$deduction_name;
        // 	$payroll[$deduction->constant]['value']=>eval("\$calc = \"$calc\";");

        // }

        // calculate allowances and deductions
        // $payroll['gross_pay']=$payroll['gross_pay']*($payroll['days_worked']/$payroll['working_days']);
        $net = ['basic_pay' => $payroll['basic_pay'], 'basic_salary' => $payroll['basic_pay'], 'gross_salary' => $payroll['gross_pay'], 'gross_pay' => $payroll['gross_pay']];

        $payroll['allowances'] = $payroll['deductions'] = $payroll['component_names'] = $payroll['sc_component_names'] = [];
        $payroll['sc_allowances'] = $payroll['sc_deductions'] = $payroll['sc_project_code'] = $payroll['sc_gl_code'] = [];
        $payroll['total_allowances'] = $payroll['total_deductions'] = $payroll['not_taxable'] = 0;
        $payroll['sc_total_allowances'] = $payroll['sc_total_deductions'] = 0;

        foreach ($components as $component) {

            if ($component->status == 1) {

                $payroll['component_names'][$component->constant] = $component->name;
                $payroll['sc_component_names'][$component->constant] = $component->name;
                $payroll['sc_project_code'][$component->constant] = $component->project_code;
                $payroll['sc_gl_code'][$component->constant] = $component->gl_code;

                $net[$component->constant] = $value = $this->calculate_salary_component($component->constant, $component->formula, $net);
                if ($component->type == 1) {
                    $payroll['allowances'][$component->constant] = number_format($value, 2, '.', '');
                    $payroll['sc_allowances'][$component->constant] = number_format($value, 2, '.', '');

                } else {
                    $payroll['deductions'][$component->constant] = number_format($value, 2, '.', '');
                    $payroll['sc_deductions'][$component->constant] = number_format($value, 2, '.', '');
                }


                // $payroll[$component->type == 1 ? 'allowances' : 'deductions'][$component->constant] =
                // number_format($value, 2, '.', '');
                if ($component->type == 1) {
                    $payroll['total_allowances'] += $value;
                    $payroll['sc_total_allowances'] += $value;
                } else {
                    $payroll['total_deductions'] += $value;
                    $payroll['sc_total_deductions'] += $value;
                }
                if ($component->taxable == 0) {
                    $payroll['not_taxable'] = number_format($value, 2, '.', '');
                }


            }
        }

        if ($user->union) {

            $net['gross_pay'] = $payroll['gross_pay'];
            $net['union_dues'] = $dues = $this->calculate_salary_component('union_dues', $user->union->dues_formula, $net);

            $payroll['union_dues'] = $dues;

        }
        $payroll['gross_pay'] = $payroll['gross_pay'] * 12;
        // $payroll['gross_tax']=($payroll['gross_pay']-$payroll['deductions']['pension'])*12;
        //  $payroll['consolidated_allowance'] = $this->consolidated_allowance($payroll['gross_pay']);

    }

//    private function consolidated_allowance($gross_salary)
//    {
//
//        $annual_gross = $gross_salary;
//
//
//        return $consolidated = ($annual_gross * (1 / 100)) > 200000 ?
//            (($annual_gross * (1 / 100)) + ($annual_gross * (20 / 100))) :
//            (200000 + ($annual_gross * (20 / 100)));
//
//        // return abs(number_format($consolidated, 2, '.', '') / 12);
//
//    }
    private function consolidated_allowance($gross_salary,$reliefs=0) {

        $annual_gross = $gross_salary;
        $gti=$annual_gross-$reliefs;
                return $consolidated = ($annual_gross * (1 / 100)) > 200000 ?
            (($annual_gross * (1 / 100)) + ($gti * (20 / 100))) :
            (200000 + ($gti * (20 / 100)));


//
//        return  $consolidated = ($annual_gross * (1 / 100))-$reliefs > 200000 ?
//            (($annual_gross * (1 / 100)) + ($annual_gross * (20 / 100))-$reliefs):
//            (200000 + ($annual_gross * (20 / 100))-$reliefs);

        // return abs(number_format($consolidated, 2, '.', '') / 12);

    }
    private function new_consolidated_allowance($gross_salary,$reliefs=0) {

        $annual_gross = $gross_salary;


        return  $consolidated = ($annual_gross-$reliefs) * (20 / 100);

        // return abs(number_format($consolidated, 2, '.', '') / 12);

    }


    private function calculate_salary_component($constant, $formula, &$net, $days = '')
    {

        $oldFormula = $formula;

        try {
            foreach ($net as $key => $value) {
                if (substr_count($formula, $key)) {
                    $formula = str_ireplace($key, $net[$key], $formula);
                }
            }

            eval('$result = (' . $formula . ');');

        } catch (\ParseError $e) {
            print_r($days);
            echo $constant;
            print_r($oldFormula);
            print_r($net);
            dd($e);
        }


        return $result;

    }

    private function calculate_specific_salary_components(&$payroll)
    {
        $user = User::find($payroll['user_id']);
        $components = $user->specificSalaryComponents()->where('status', 1)
            ->where('type', '!=', '2')
            // ->whereDate('starts', '<=', $payroll['date'])
            // ->whereDate('ends', '>=',  $payroll['date'])
            ->get();
        $payroll['ssc_allowances'] = $payroll['ssc_deductions'] = $payroll['ssc_project_code'] = $payroll['ssc_gl_code'] = $payroll['ssc_display'] = $payroll['ssc_annual_tax'] = $payroll['ssc_tax'] = [];
        $payroll['specifics']['allowances'] = $payroll['specifics']['deductions'] = $payroll['ssc_monthly_taxable_all'] = $payroll['ssc_annual_taxable_all'] = 0;
        $payroll['ssc_total_allowances'] = $payroll['ssc_total_deductions'] = 0;
        $payroll['ssc_component_names'] = $payroll['ssc_component_category'] = $payroll['ssc_amount'] = $payroll['ssc_component_type'] = $payroll['ssc_taxable'] = [];
        $user = User::find($payroll['user_id']);
        if ($components) {

            foreach ($components as $key => $component) {
                if ($component->grants < $component->duration) {

                    $payroll['ssc_component_category'][$key] = $component->ssc_type->id;
                    $payroll['component_names'][$key] = $component->name . '-' . $user->name;
                    $payroll['ssc_component_names'][$key] = $component->name . '-' . $user->name;
                    $payroll['specifics'][$component->type == 1 ? 'allowances' : 'deductions'] += $component->amount;
                    $payroll[$component->type == 1 ? 'ssc_allowances' : 'ssc_deductions'][$key] = number_format($component->amount, 2, '.', '');
                    if ($component->one_off == 0 && $component->type == 1) {
                        $value = ($component->amount / $payroll['working_days']) * $payroll['days_worked'];

                    } else {
                        $value = $component->amount;
                    }

                    $payroll['ssc_project_code'][$key] = $component->project_code;
                    $payroll['ssc_display'][$key] = $component->ssc_type->display;
                    $payroll['ssc_component_type'][$key] = $component->type;
                    $payroll['ssc_gl_code'][$key] = $component->gl_code;
                    $payroll['ssc_amount'][$key] = number_format($value, 2, '.', '');
                    $payroll[$component->type == 1 ? 'allowances' : 'deductions'][$key] = number_format($value, 2, '.', '');
                    $component->grants++;
                    if (($component->grants) >= $component->duration) {
                        $component->update(['grants' => intval($component->grants), 'completed' => 1]);

                    } else {
                        $component->update(['grants' => intval($component->grants)]);
                    }
                    $payroll['payroll']->specific_salary_components()->attach($component->id);
                    if ($component->type == 1) {

                        $payroll['ssc_total_allowances'] += $value;
                        $payroll['total_allowances'] += $value;
                    } else {

                        $payroll['ssc_total_deductions'] += $value;
                        $payroll['total_deductions'] += $value;
                    }
                    if ($component->taxable == 1) {
                        //   $payroll['ssc_tax']+=number_format($value, 2, '.', '');
                        $annual_amount = number_format($value, 2, '.', '') * 12;
                        $cra = $this->consolidated_allowance($annual_amount);
                        $taxable_income = $annual_amount - $cra - 14000;
                        $tax = $this->calculate_ssc_tax($annual_amount, $taxable_income);
                        $payroll['ssc_annual_tax'][$key] = $tax['annual'];
                        $payroll['ssc_tax'][$key] = $tax['monthly'];
                        $payroll['ssc_taxable'][$key] = 1;
                        if ($component->one_off == 1) {
                            if ($component->taxable_type == 1) {
                                $payroll['ssc_monthly_taxable_all'] += number_format($value, 2, '.', '');
                            } elseif ($component->taxable_type == 2) {
                                $payroll['ssc_annual_taxable_all'] += number_format($value, 2, '.', '');
                            }

                        } else {
//                              $payroll['ssc_taxable_all']+=number_format($value, 2, '.', '');
                            if ($component->taxable_type == 1) {
                                $payroll['ssc_monthly_taxable_all'] += number_format($value, 2, '.', '');
                            } elseif ($component->taxable_type == 2) {
                                $payroll['ssc_annual_taxable_all'] += number_format($value, 2, '.', '');
                            }
                        }
                    } else {
                        $payroll['ssc_taxable'][$key] = 0;
                    }
                }

            }
        }

    }

    public function calculate_loan(&$payroll)
    {
        $user = User::find($payroll['user_id']);
        $loans = $user->loan_requests()->where(['status' => 1, 'completed' => 0])->whereDate('repayment_starts', '<=', $payroll['date'])->get();
        $payroll['loans']['deductions'] = 0;
        if ($loans) {

            foreach ($loans as $key => $component) {
                if ($component->status == 1 and $component->completed != 1) {

                    $payroll['component_names'][] = $user->name . '- Loan';
                    $payroll['ssc_component_names'][] = $user->name . '- Loan';

                    $payroll['loans']['deductions'] += $component->monthly_deduction;
                    $payroll['deductions'][] = number_format($component->monthly_deduction, 2, '.', '');
                    $payroll['ssc_deductions'][] = number_format($component->monthly_deduction, 2, '.', '');
                    $payroll['total_deductions'] += $component->monthly_deduction;
                    $payroll['ssc_total_deductions'] += $component->monthly_deduction;
                    if (($component->months_deducted + 1) == $component->period) {
                        $component->update(['months_deducted' => intval($component->months_deducted) + 1, 'completed' => 1]);

                    } else {
                        $component->update(['months_deducted' => intval($component->months_deducted) + 1]);
                    }
                    $payroll['payroll']->loan_requests()->attach($component->id);


                }

            }
        }
    }

    public function calculate_ssc_tax($amount, $amount_taxable)
    {
        $ti = $amount_taxable;
        $lv1 = 0;
        $lv2 = 0;
        $lv3 = 0;
        $lv4 = 0;
        $lv5 = 0;
        $lv6 = 0;
        //first level

        if ($amount < 300000) {
            $lv1 = $amount * 0.01;
            $ti = 0;
        } elseif ($ti <= 300000) {
            $lv1 = $ti * 0.07;
            $ti = $ti - $ti;
        } elseif ($ti > 300000) {
            $ti = $ti - 300000;
            $lv1 = 300000 * 0.07;
        }
        // second level
        if ($ti < 300000) {
            $lv2 = $ti * 0.11;
            $ti = $ti - $ti;
        } elseif ($ti == 300000) {
            $lv2 = $ti * 0.11;
            $ti = $ti - $ti;
        } elseif ($ti > 300000) {
            $ti = $ti - 300000;
            $lv2 = 300000 * 0.11;
        }
        //third level
        if ($ti < 500000) {
            $lv3 = $ti * 0.15;
            $ti = $ti - $ti;
        } elseif ($ti == 500000) {
            $lv3 = $ti * 0.15;
            $ti = $ti - $ti;
        } elseif ($ti > 500000) {
            $ti = $ti - 500000;
            $lv3 = 500000 * 0.15;
        }
        //fourth level
        if ($ti < 500000) {
            $lv4 = $ti * 0.19;
            $ti = $ti - $ti;
        } elseif ($ti == 500000) {
            $lv4 = $ti * 0.19;
            $ti = $ti - $ti;
        } elseif ($ti > 500000) {
            $ti = $ti - 500000;
            $lv4 = 500000 * 0.19;
        }
        //fifth level
        if ($ti < 1600000) {
            $lv5 = $ti * 0.21;
            $ti = $ti - $ti;
        } elseif ($ti == 1600000) {
            $lv5 = $ti * 0.21;
            $ti = $ti - $ti;
        } elseif ($ti > 1600000) {
            $ti = $ti - 1600000;
            $lv5 = 1600000 * 0.21;
        }
        //sixth level
        if ($ti < 3200000) {
            $lv6 = $ti * 0.24;
            $ti = $ti - $ti;
        } elseif ($ti == 3200000) {
            $lv6 = $ti * 0.24;
            $ti = $ti - $ti;
        } elseif ($ti > 3200000) {
            $lv6 = $ti * 0.24;
        }


        $annual_paye = ($lv1 + $lv2 + $lv3 + $lv4 + $lv5 + $lv6);
        $paye = $annual_paye / 12;
        return ['annual' => $annual_paye, 'monthly' => $paye];
    }

    public function calculate_tax_without_annual_allowances(&$payroll)
    {
        $ti = $payroll['taxable_income_without_annual_allowances'];
        $lv1 = 0;
        $lv2 = 0;
        $lv3 = 0;
        $lv4 = 0;
        $lv5 = 0;
        $lv6 = 0;
        //first level

        if ($payroll['tax_gross_pay_without_annual_allowances'] < 300000) {
            $lv1 = $payroll['tax_gross_pay_without_annual_allowances'] * 0.01;
            $ti = 0;
        } elseif ($ti <= 300000) {
            $lv1 = $ti * 0.07;
            $ti = $ti - $ti;
        } elseif ($ti > 300000) {
            $ti = $ti - 300000;
            $lv1 = 300000 * 0.07;
        }
        // second level
        if ($ti < 300000) {
            $lv2 = $ti * 0.11;
            $ti = $ti - $ti;
        } elseif ($ti == 300000) {
            $lv2 = $ti * 0.11;
            $ti = $ti - $ti;
        } elseif ($ti > 300000) {
            $ti = $ti - 300000;
            $lv2 = 300000 * 0.11;
        }
        //third level
        if ($ti < 500000) {
            $lv3 = $ti * 0.15;
            $ti = $ti - $ti;
        } elseif ($ti == 500000) {
            $lv3 = $ti * 0.15;
            $ti = $ti - $ti;
        } elseif ($ti > 500000) {
            $ti = $ti - 500000;
            $lv3 = 500000 * 0.15;
        }
        //fourth level
        if ($ti < 500000) {
            $lv4 = $ti * 0.19;
            $ti = $ti - $ti;
        } elseif ($ti == 500000) {
            $lv4 = $ti * 0.19;
            $ti = $ti - $ti;
        } elseif ($ti > 500000) {
            $ti = $ti - 500000;
            $lv4 = 500000 * 0.19;
        }
        //fifth level
        if ($ti < 1600000) {
            $lv5 = $ti * 0.21;
            $ti = $ti - $ti;
        } elseif ($ti == 1600000) {
            $lv5 = $ti * 0.21;
            $ti = $ti - $ti;
        } elseif ($ti > 1600000) {
            $ti = $ti - 1600000;
            $lv5 = 1600000 * 0.21;
        }
        //sixth level
        if ($ti < 3200000) {
            $lv6 = $ti * 0.24;
            $ti = $ti - $ti;
        } elseif ($ti == 3200000) {
            $lv6 = $ti * 0.24;
            $ti = $ti - $ti;
        } elseif ($ti > 3200000) {
            $lv6 = $ti * 0.24;
        }

        $payroll['annual_paye_without_annual_allowances'] = ($lv1 + $lv2 + $lv3 + $lv4 + $lv5 + $lv6);
        // 	$payroll['annual_paye']=(($lv1+$lv2+$lv3+$lv4+$lv5+$lv6)/$payroll['working_days'])*$payroll['days_worked'];
        $payroll['paye_without_annual_allowances'] = $payroll['annual_paye_without_annual_allowances'] / 12;

    }

    public function calculate_tax(&$payroll)
    {
        $ti = $payroll['taxable_income'];
        $lv1 = 0;
        $lv2 = 0;
        $lv3 = 0;
        $lv4 = 0;
        $lv5 = 0;
        $lv6 = 0;
        //first level

        if ($payroll['tax_gross_pay'] < 300000) {
            $lv1 = $payroll['tax_gross_pay'] * 0.01;
            $ti = 0;
        } elseif ($ti <= 300000) {
            $lv1 = $ti * 0.07;
            $ti = $ti - $ti;
        } elseif ($ti > 300000) {
            $ti = $ti - 300000;
            $lv1 = 300000 * 0.07;
        }
        // second level
        if ($ti < 300000) {
            $lv2 = $ti * 0.11;
            $ti = $ti - $ti;
        } elseif ($ti == 300000) {
            $lv2 = $ti * 0.11;
            $ti = $ti - $ti;
        } elseif ($ti > 300000) {
            $ti = $ti - 300000;
            $lv2 = 300000 * 0.11;
        }
        //third level
        if ($ti < 500000) {
            $lv3 = $ti * 0.15;
            $ti = $ti - $ti;
        } elseif ($ti == 500000) {
            $lv3 = $ti * 0.15;
            $ti = $ti - $ti;
        } elseif ($ti > 500000) {
            $ti = $ti - 500000;
            $lv3 = 500000 * 0.15;
        }
        //fourth level
        if ($ti < 500000) {
            $lv4 = $ti * 0.19;
            $ti = $ti - $ti;
        } elseif ($ti == 500000) {
            $lv4 = $ti * 0.19;
            $ti = $ti - $ti;
        } elseif ($ti > 500000) {
            $ti = $ti - 500000;
            $lv4 = 500000 * 0.19;
        }
        //fifth level
        if ($ti < 1600000) {
            $lv5 = $ti * 0.21;
            $ti = $ti - $ti;
        } elseif ($ti == 1600000) {
            $lv5 = $ti * 0.21;
            $ti = $ti - $ti;
        } elseif ($ti > 1600000) {
            $ti = $ti - 1600000;
            $lv5 = 1600000 * 0.21;
        }
        //sixth level
        if ($ti < 3200000) {
            $lv6 = $ti * 0.24;
            $ti = $ti - $ti;
        } elseif ($ti == 3200000) {
            $lv6 = $ti * 0.24;
            $ti = $ti - $ti;
        } elseif ($ti > 3200000) {
            $lv6 = $ti * 0.24;
        }

        $payroll['annual_paye'] = ($lv1 + $lv2 + $lv3 + $lv4 + $lv5 + $lv6);
        $payroll['paye_difference'] = $payroll['annual_paye'] - $payroll['annual_paye_without_annual_allowances'];
        // 	$payroll['annual_paye']=(($lv1+$lv2+$lv3+$lv4+$lv5+$lv6)/$payroll['working_days'])*$payroll['days_worked'];

//        	$payroll['paye']=$payroll['annual_paye']/12;
        $payroll['paye'] = ($payroll['annual_paye_without_annual_allowances'] / 12) + $payroll['paye_difference'];

    }
    public function project_direct_tax(&$payroll){
        $user = User::find($payroll['user_id']);
        if ($user->project_salary_category) {
            $payroll['annual_paye']=$payroll['tax_gross_pay']*$user->project_salary_category->tax_rate;
            $payroll['paye']=$payroll['annual_paye']/12;
        }
    }

    //start project payroll calculation
    public function projectGrosspay($payroll)
    {
        $user_id = $payroll['user_id'];
        $user = \App\User::find($user_id);
        $components = PaceSalaryComponent::where(['company_id' => $payroll['company_id'], 'pace_salary_category_id' => $payroll['salary_category_id']])->get();
        $payroll['allowances'] = $payroll['deductions'] = $payroll['component_names'] = $payroll['sc_component_names'] = [];
        $payroll['sc_allowances'] = $payroll['sc_deductions'] = $payroll['sc_project_code'] = $payroll['sc_gl_code'] = [];
        $payroll['total_allowances'] = $payroll['total_deductions'] = $payroll['not_taxable'] = 0;
        $payroll['sc_total_allowances'] = $payroll['sc_total_deductions'] = 0;
        $payroll['statutory_reliefs']=0;


        $salary_category = \App\PaceSalaryCategory::find($payroll['salary_category_id']);
        // $payroll['basic_pay']=$salary_category->basic_salary;


        $net = ['basic_pay' => $payroll['basic_pay'], 'basic_salary' => $payroll['basic_pay'], 'days' => 0];

        foreach ($components as $component) {
            if ($component->fixed == 1) {
                $net[$component->constant] = $component->amount;
            }

        }

        $payroll['allowances'] = $payroll['deductions'] = $payroll['component_names'] = $payroll['sc_component_names'] = [];
        $payroll['sc_allowances'] = $payroll['sc_deductions'] = $payroll['sc_project_code'] = $payroll['sc_gl_code'] = [];
        $payroll['total_allowances'] = $payroll['total_deductions'] = $payroll['not_taxable'] = 0;
        $payroll['sc_total_allowances'] = $payroll['sc_total_deductions'] = 0;
        $payroll['gross_pay'] = $payroll['basic_pay'];

        //  $net['gross_pay']=$payroll['basic_pay'];
        // $net['gross_salary']=$payroll['basic_pay'];
        foreach ($components as $component) {

            if ($component->status == 1 && $component->exemptions->contains('id', $user_id) == false && $component->type == 1 && (($component->uses_anniversary == 1 && $payroll['is_anniversary'] == 1) || ($component->uses_anniversary == 0))
            && (($component->uses_probation ==1 && $user->status==0) || $component->uses_probation==0)) {

                $payroll['component_names'][$component->constant] = $component->name;
                $payroll['sc_component_names'][$component->constant] = $component->name;
                $payroll['sc_project_code'][$component->constant] = $component->project_code;
                $payroll['sc_gl_code'][$component->constant] = $component->gl_code;
                if ($component->fixed == 0 && $component->uses_days == 0) {
                    $net[$component->constant] = $value = $this->calculate_salary_component($component->constant, $component->formula, $net);
                } elseif ($component->uses_days == 0 && $component->fixed == 1) {
                    $value = ($component->amount / $payroll['working_days']) * $payroll['days_worked'];

                } elseif ($component->uses_days == 1 && $component->fixed == 1) {
                    $tsdays = \App\ProjectSalaryTimesheet::where(['user_id' => $user_id, 'for' => $payroll['date'], 'pace_salary_component_id' => $component->id])->first();
                    if ($tsdays == null) {
                        $value = $component->amount * 0;
                    } else {
                        $value = $component->amount * $tsdays->days;
                    }

                } elseif ($component->uses_days == 1 && $component->fixed == 0) {
                    $tsdays = \App\ProjectSalaryTimesheet::where(['user_id' => $user_id, 'for' => $payroll['date'], 'pace_salary_component_id' => $component->id])->first();
                    // $testdays=$tsdays->toArray();
                    if (empty($tsdays)) {
                        $net['days'] = 0;
                        $net['months'] = 0;
                    } else {
                        if (!empty($tsdays->days) || $tsdays->days != null) {
                            $net['days'] = $tsdays->days;
                            $net['months'] = $tsdays->days;
                        } else {
                            $net['days'] = 0;
                            $net['months'] = 0;
                        }

                    }
                    $net[$component->constant] = $value = $this->calculate_salary_component($component->constant, $component->formula, $net);
                    // $value=$component->amount*$payroll['days'];
                }

                if ($component->type == 1) {
                    $payroll['allowances'][$component->constant] = number_format($value, 2, '.', '');
                    $payroll['sc_allowances'][$component->constant] = number_format($value, 2, '.', '');
                    $payroll['gross_pay'] += $value;
                    $payroll['total_allowances'] += $value;
                    $payroll['sc_total_allowances'] += $value;
                }


                // $payroll[$component->type == 1 ? 'allowances' : 'deductions'][$component->constant] =
                // number_format($value, 2, '.', '');

                if ($component->taxable == 0) {
                    $payroll['not_taxable'] += number_format($value, 2, '.', '');
                }


            }
        }
        return $payroll['gross_pay'];
    }

    public function projectAllowancesAndDeductions(&$payroll)
    {
        $user_id = $payroll['user_id'];
        $user = \App\User::find($user_id);
        $components = PaceSalaryComponent::where(['company_id' => $payroll['company_id'], 'pace_salary_category_id' => $payroll['salary_category_id']])->get();
        $payroll['allowances'] = $payroll['deductions'] = $payroll['component_names'] = $payroll['sc_component_names'] = [];
        $payroll['sc_allowances'] = $payroll['sc_deductions'] = $payroll['sc_project_code'] = $payroll['sc_gl_code'] = [];
        $payroll['total_allowances'] = $payroll['total_deductions'] = $payroll['not_taxable'] = 0;
        $payroll['sc_total_allowances'] = $payroll['sc_total_deductions'] = 0;
        $payroll['daily_net_gross'] = 0;
        $payroll['uses_daily_net'] = 0;
        $payroll['statutory_reliefs']=0;

        $salary_category = \App\PaceSalaryCategory::find($payroll['salary_category_id']);
        // $payroll['basic_pay']=$salary_category->basic_salary;
        $payroll['basic_pay'] = ($payroll['basic_pay'] / $payroll['working_days']) * $payroll['days_worked'];

        $net = ['basic_pay' => $payroll['basic_pay'], 'basic_salary' => $payroll['basic_pay'], 'days' => 0];
        if ($salary_category->uses_daily_net == 1) {
            $gross_pay = 0;
            $daily_net = $salary_category->paceSalaryComponents()->where(['constant' => 'daily_net'])->first();
            $daysts = \App\ProjectSalaryTimesheet::where(['user_id' => $user_id, 'for' => $payroll['date'], 'pace_salary_component_id' => $daily_net->id])->first();

            if ($daysts) {
                $days = $daysts->days;
            } else {
                $days = 0;
            }
            $payroll['uses_daily_net'] = 1;

            $daily_gross_object = \DB::table('optimized_daily_salaries')
                ->whereBetween('daily_net', [$daily_net->amount, $daily_net->amount + 30])->where('days', $days)->orderBy('daily_net', 'asc')->first();
            if ($daily_gross_object) {
                $gross_pay = $daily_gross_object->daily_gross * $days;
                $payroll['daily_net_gross'] = $daily_gross_object->daily_gross * $days;
            } else {
                if ($days >= 32) {
                    $gross_pay = (($daily_net->amount * 0.35) + $daily_net->amount) * $days;
                } elseif ($days >= 26) {
                    $gross_pay = (($daily_net->amount * 0.33) + $daily_net->amount) * $days;
                } elseif ($days >= 21) {
                    $gross_pay = (($daily_net->amount * 0.29) + $daily_net->amount) * $days;
                } elseif ($days >= 16) {
                    $gross_pay = (($daily_net->amount * 0.25) + $daily_net->amount) * $days;
                } elseif ($days >= 11) {
                    $gross_pay = (($daily_net->amount * 0.21) + $daily_net->amount) * $days;
                } elseif ($days >= 4) {
                    $gross_pay = (($daily_net->amount * 0.17) + $daily_net->amount) * $days;
                } elseif ($days >= 2) {
                    $gross_pay = (($daily_net->amount * 0.15) + $daily_net->amount) * $days;
                } elseif ($days == 1) {
                    $gross_pay = (($daily_net->amount * 0.112) + $daily_net->amount) * $days;
                }
            }


            $net['gross_pay'] = $gross_pay;
            $net['gross_salary'] = $gross_pay;
            $payroll['component_names'][$daily_net->constant] = $daily_net->name;
            $payroll['sc_component_names'][$daily_net->constant] = $daily_net->name;
            $payroll['sc_project_code'][$daily_net->constant] = $daily_net->project_code;
            $payroll['sc_gl_code'][$daily_net->constant] = $daily_net->gl_code;
            $payroll['allowances'][$daily_net->constant] = number_format($gross_pay, 2, '.', '');
            $payroll['sc_allowances'][$daily_net->constant] = number_format($gross_pay, 2, '.', '');
            $payroll['total_allowances'] += $gross_pay;
            $payroll['sc_total_allowances'] += $gross_pay;
            $payroll['gross_pay'] = $gross_pay;

            foreach ($components as $component) {
                if ($component->fixed == 1 && $component->constant != 'daily_net') {
                    $net[$component->constant] = $component->amount;
                }
            }

            // $gross_pay=$this->getDailyGross($days,$daily_net->amount);


            foreach ($components as $component) {

                if ($component->status == 1 && $component->exemptions->contains('id', $user_id) == false
                 && $component->constant != 'daily_net' 
                 && (($component->uses_anniversary == 1 && $payroll['is_anniversary'] == 1) || ($component->uses_anniversary == 0)) 
                 && (($component->uses_probation ==1 && $user->status==0) || $component->uses_probation==0)) {

                    $payroll['component_names'][$component->constant] = $component->name;
                    $payroll['sc_component_names'][$component->constant] = $component->name;
                    $payroll['sc_project_code'][$component->constant] = $component->project_code;
                    $payroll['sc_gl_code'][$component->constant] = $component->gl_code;
                    if ($component->fixed == 0 && $component->uses_days == 0) {
                        $net[$component->constant] = $value = $this->calculate_salary_component($component->constant, $component->formula, $net);
                    } elseif ($component->uses_days == 0 && $component->fixed == 1) {
                        $value = ($component->amount / $payroll['working_days']) * $payroll['days_worked'];

                    } elseif ($component->uses_days == 1 && $component->fixed == 1) {
                        $tsdays = \App\ProjectSalaryTimesheet::where(['user_id' => $user_id, 'for' => $payroll['date'], 'pace_salary_component_id' => $component->id])->first();
                        if ($tsdays == null) {
                            $value = $component->amount * 0;
                        } else {
                            $value = $component->amount * $tsdays->days;
                        }

                    } elseif ($component->uses_days == 1 && $component->fixed == 0) {
                        $tsdays = \App\ProjectSalaryTimesheet::where(['user_id' => $user_id, 'for' => $payroll['date'], 'pace_salary_component_id' => $component->id])->first();
                        if (empty($tsdays)) {
                            $net['days'] = 0;
                            $net['months'] = 0;
                        } else {
                            if (!empty($tsdays->days) || $tsdays->days != '') {
                                $net['days'] = $tsdays->days;
                                $net['months'] = $tsdays->days;
                            } else {
                                $net['days'] = 0;
                                $net['months'] = 0;
                            }

                        }
                        // if(!isset($net['gross_salary'])){
                        //     return $payroll['user_id'];
                        // }
                        $net[$component->constant] = $value = $this->calculate_salary_component($component->constant, $component->formula, $net);
                        // $value=$component->amount*$payroll['days'];
                    }

                    if ($component->type == 1) {
                        $payroll['allowances'][$component->constant] = number_format($value, 2, '.', '');
                        $payroll['sc_allowances'][$component->constant] = number_format($value, 2, '.', '');


                    } else {
                        $payroll['deductions'][$component->constant] = number_format($value, 2, '.', '');
                        $payroll['sc_deductions'][$component->constant] = number_format($value, 2, '.', '');
                    }


                    // $payroll[$component->type == 1 ? 'allowances' : 'deductions'][$component->constant] =
                    // number_format($value, 2, '.', '');
                    if ($component->type == 1) {

                        $payroll['total_allowances'] += $value;
                        $payroll['sc_total_allowances'] += $value;
                        $payroll['gross_pay'] += $value;
                    } else {
                        $payroll['total_deductions'] += $value;
                        $payroll['sc_total_deductions'] += $value;
                    }
                    if ($component->taxable == 0) {
                        $payroll['not_taxable'] += number_format($value, 2, '.', '');
                    }


                }
            }
            //end of daily netpay

        } else {

            foreach ($components as $component) {
                if ($component->fixed == 1) {
                    $net[$component->constant] = $component->amount;
                }

            }

            $payroll['allowances'] = $payroll['deductions'] = $payroll['component_names'] = $payroll['sc_component_names'] = [];
            $payroll['sc_allowances'] = $payroll['sc_deductions'] = $payroll['sc_project_code'] = $payroll['sc_gl_code'] = [];
            $payroll['total_allowances'] = $payroll['total_deductions'] = $payroll['not_taxable'] = 0;
            $payroll['sc_total_allowances'] = $payroll['sc_total_deductions'] = 0;
            $payroll['gross_pay'] = $this->projectGrosspay($payroll);
            //  $net = ['gross_pay' => $payroll['gross_pay'],'gross_salary'=>$payroll['gross_pay'],'basic_pay' => $payroll['basic_pay'],'basic_salary'=>$payroll['basic_pay'],'days'=>0,'transport'=>0,'transport_allowance'=>0,'housing'=>0,'housing_allowance'=>0];
            $net['gross_pay'] = $payroll['gross_pay'];
            // $net['gross_salary']=$payroll['basic_pay'];
            foreach ($components as $component) {

                if ($component->status == 1 && $component->exemptions->contains('id', $user_id) == false && (($component->uses_anniversary == 1 && $payroll['is_anniversary'] == 1) || ($component->uses_anniversary == 0))
                && (($component->uses_probation ==1 && $user->status==0) || $component->uses_probation==0)) {

                    $payroll['component_names'][$component->constant] = $component->name;
                    $payroll['sc_component_names'][$component->constant] = $component->name;
                    $payroll['sc_project_code'][$component->constant] = $component->project_code;
                    $payroll['sc_gl_code'][$component->constant] = $component->gl_code;
                    if ($component->fixed == 0 && $component->uses_days == 0) {
                        $net[$component->constant] = $value = $this->calculate_salary_component($component->constant, $component->formula, $net);
                    } elseif ($component->uses_days == 0 && $component->fixed == 1) {
                        $value = ($component->amount / $payroll['working_days']) * $payroll['days_worked'];

                    } elseif ($component->uses_days == 1 && $component->fixed == 1) {
                        $tsdays = \App\ProjectSalaryTimesheet::where(['user_id' => $user_id, 'for' => $payroll['date'], 'pace_salary_component_id' => $component->id])->first();
                        if ($tsdays == null) {
                            $value = $component->amount * 0;
                        } else {
                            $value = $component->amount * $tsdays->days;
                        }

                    } elseif ($component->uses_days == 1 && $component->fixed == 0) {
                        $tsdays = \App\ProjectSalaryTimesheet::where(['user_id' => $user_id, 'for' => $payroll['date'], 'pace_salary_component_id' => $component->id])->first();
                        if (empty($tsdays)) {
                            $net['days'] = 0;
                            $net['months'] = 0;
                        } else {
                            if (!empty($tsdays->days)) {
                                $net['days'] = $tsdays->days;
                                $net['months'] = $tsdays->days;
                            } else {
                                $net['days'] = 0;
                                $net['months'] = 0;
                            }

                        }
                        $net[$component->constant] = $value = $this->calculate_salary_component($component->constant, $component->formula, $net);
                        // $value=$component->amount*$payroll['days'];
                    }

                    if ($component->type == 1) {
                        $payroll['allowances'][$component->constant] = number_format($value, 2, '.', '');
                        $payroll['sc_allowances'][$component->constant] = number_format($value, 2, '.', '');
                        // $payroll['gross_pay']+=$value;
                        $payroll['total_allowances'] += $value;
                        $payroll['sc_total_allowances'] += $value;
                    } else {
                        $payroll['deductions'][$component->constant] = number_format($value, 2, '.', '');
                        $payroll['sc_deductions'][$component->constant] = number_format($value, 2, '.', '');
                        $payroll['total_deductions'] += $value;
                        $payroll['sc_total_deductions'] += $value;
                        if($component->is_relief==1){
                            $payroll['statutory_reliefs']+=$value;
                        }
                    }


                    // $payroll[$component->type == 1 ? 'allowances' : 'deductions'][$component->constant] =
                    // number_format($value, 2, '.', '');

                    if ($component->taxable == 0) {
                        $payroll['not_taxable'] += number_format($value, 2, '.', '');
                    }


                }
            }

        }
        if ($user->union) {

            $net['gross_pay'] = $payroll['gross_pay'];
            $net['gross_salary'] = $payroll['gross_pay'];
            $net['union_dues'] = $dues = $this->calculate_salary_component('union_dues', $user->union->dues_formula, $net);

            $payroll['union_dues'] = $dues;

        }


        $payroll['gross_pay'] = $payroll['gross_pay'] * 12;
        // $payroll['gross_tax']=($payroll['gross_pay']-$payroll['deductions']['pension'])*12;
        //  $payroll['consolidated_allowance'] = $this->consolidated_allowance($payroll['gross_pay']);

    }

    public function calculateProjectPAYE(&$payroll)
    {
        $user = User::find($payroll['user_id']);
        if ($user->project_salary_category) {
            $payroll['salary_category_id'] = $user->project_salary_category_id;
            $payroll['has_category'] = 1;
            $payroll['unprorated_basic_pay'] = $user->project_salary_category->basic_salary;
            $payroll['individual_proration'] = intval(PayrollPolicy::where(['company_id' => $payroll['company_id']])->first()->use_individual_proration);
            if ($payroll['individual_proration'] == 1) {
                $payroll['basic_pay'] = $user->project_salary_category->basic_salary * ($user->proration_percentage / 100);
            } else {
                $payroll['basic_pay'] = $user->project_salary_category->basic_salary;
            }

            $salary_review = SalaryReview::where(['payment_month' => $payroll['date'], 'employee_id' => $user->id])->first();
            if ($salary_review) {

                if (intval(date('Y', strtotime($salary_review->payment_month))) > intval(date('Y', strtotime($salary_review->review_month)))) {
                    $diffyear = intval(date('Y', strtotime($salary_review->payment_month))) - intval(date('Y', strtotime($salary_review->review_month)));
                    $diffmonths = (intval(date('m', strtotime($salary_review->payment_month))) + (12 * $diffyear)) - (intval(date('m', strtotime($salary_review->review_month))));

                } else {
                    $diffmonths = (intval(date('m', strtotime($salary_review->payment_month)))) - (intval(date('m', strtotime($salary_review->review_month))));
                }
                $basic_arrears = ($payroll['unprorated_basic_pay'] - $salary_review->previous_gross) * $diffmonths;
                $payroll['basic_pay'] += $basic_arrears;
                $salary_review->used = 1;
                $salary_review->save();
            }
            $payroll['ogross_pay'] = $this->projectGrosspay($payroll) * 12;
            $payroll['has_category'] = 1;

            // dd($payroll['basic_pay']);
            $this->projectAllowancesAndDeductions($payroll);
            $this->calculate_specific_salary_components($payroll);
            $rebates = $user->specificSalaryComponents()->where('status', 1)
                ->where('type', 2)
                ->get();
            $sum_of_rebate = 0;
            foreach ($rebates as $rebate) {

                $sum_of_rebate += $rebate->amount;

            }
            // 	$payroll['tax_gross_pay']=$payroll['ogross_pay']+($payroll['ssc_taxable_all']*12);
            $payroll['tax_gross_pay'] = $payroll['gross_pay'] + ($payroll['ssc_monthly_taxable_all'] * 12) + $payroll['ssc_annual_taxable_all'];
//            if($payroll['pp']->tax_preference=='old'){
                $payroll['consolidated_allowance'] = $this->consolidated_allowance($payroll['tax_gross_pay'],(($payroll['statutory_reliefs']) * 12));
//            }elseif ($payroll['pp']->tax_preference=='new'){
//                $payroll['consolidated_allowance'] = $this->new_consolidated_allowance($payroll['tax_gross_pay'],($payroll['not_taxable'] * 12));
//            }


            // $payroll['taxable_income']= $payroll['gross_pay'] - $payroll['consolidated_allowance']-($payroll['not_taxable']*12)-14000 + ($payroll['ssc_total_allowances']*12);
            //   $work_percentage=$payroll['days_worked']/$payroll['working_days'];
            // $payroll['taxable_income']=  $payroll['tax_gross_pay'] - $payroll['consolidated_allowance']-($user->project_salary_category->relief *12)-(($payroll['not_taxable']*12)/$work_percentage);// + ($payroll['ssc_total_allowances']*12);
            $payroll['taxable_income'] = $payroll['tax_gross_pay'] - $payroll['consolidated_allowance'] - ($user->project_salary_category->relief * 12) - ($sum_of_rebate * 12) - ($payroll['statutory_reliefs'] * 12);// + ($payroll['ssc_total_allowances']*12);
            if ($user->project_salary_category->uses_tax == 1 & $user->project_salary_category->uses_direct_tax==0) {
                $payroll['tax_gross_pay_without_annual_allowances'] = ($payroll['gross_pay']) + ($payroll['ssc_monthly_taxable_all'] * 12);
//                if($payroll['pp']->tax_preference=='old') {
                    $payroll['consolidated_allowance_without_annual_allowances'] = $this->consolidated_allowance($payroll['tax_gross_pay_without_annual_allowances'],(($payroll['statutory_reliefs']) * 12));
//                }elseif($payroll['pp']->tax_preference=='new'){
//                    $payroll['consolidated_allowance_without_annual_allowances'] = $this->consolidated_allowance($payroll['tax_gross_pay_without_annual_allowances'],($payroll['not_taxable']) * 12);
//                }
                $payroll['taxable_income_without_annual_allowances'] = $payroll['tax_gross_pay_without_annual_allowances'] - $payroll['consolidated_allowance_without_annual_allowances'] - (($payroll['statutory_reliefs']) * 12) - ($sum_of_rebate * 12) - ($user->project_salary_category->relief * 12);
                $this->calculate_tax_without_annual_allowances($payroll);
                $this->calculate_tax($payroll);

            }elseif($user->project_salary_category->uses_tax == 1 & $user->project_salary_category->uses_direct_tax==1){
                $this->project_direct_tax($payroll);
            } else {
                $payroll['annual_paye'] = 0;
                $payroll['paye'] = 0;
            }

        } else {

            $payroll['has_category'] = 0;
            $payroll['basic_pay'] = 0;
        }


    }
    //end project payroll calculation
    // TMSA Allowances and deductions

    public function tmsa_calc(&$payroll)
    {
       $months=[1,2,3,4,5,6,7,8,9,10,11];
    $user_id = $payroll['user_id'];
        $schedule = TmsaSchedule::whereDate('for', $payroll['date'])->where(['user_id' => $user_id])->first();
        if($payroll['month']==12){
            //calculate gross
            $total_accumulated_gross=0;
            $total_leave_allowances=0;
            $previous_accumulated_tax=0;
            foreach ($months as $month){
                $payroll_detail=TmsaPayrollDetail::whereHas('payroll',function($query) use($month){
                    $query->where('month',$month);
                })->where('user_id',$user_id)->first();
                if ($payroll_detail){
                    $total_accumulated_gross+=($payroll_detail->total_gross_pay);
                    $total_leave_allowances+=$payroll_detail->leave_allowance;
                    $previous_accumulated_tax+=$payroll_detail->monthly_paye;
                }

            }
            $schedule = TmsaSchedule::whereDate('for', $payroll['date'])->where(['user_id' => $user_id])->first();
            $tp = TmsaPolicy::where(['company_id' => $payroll['company_id']])->first();
            if ($schedule) {
                $payroll['has_timesheet'] = 1;
                $payroll['day_rate_onshore'] = ($schedule->day_rate_onshore > 0) ? $schedule->day_rate_onshore : 0;
                $payroll['days_worked_onshore'] = ($schedule->days_worked_onshore > 0) ? $schedule->days_worked_onshore : 0;
                $payroll['day_rate_offshore'] = ($schedule->day_rate_offshore > 0) ? $schedule->day_rate_offshore : 0;
                $payroll['days_worked_offshore'] = ($schedule->days_worked_offshore > 0) ? $schedule->days_worked_offshore : 0;
                $payroll['gross_pay'] = ($payroll['day_rate_onshore'] * $payroll['days_worked_onshore']) + ($payroll['day_rate_offshore'] * $payroll['days_worked_offshore']);
                $payroll['annual_gross_pay']=$payroll['gross_pay'] + $total_accumulated_gross;
                $payroll['tax_gross_pay'] = $payroll['gross_pay'] + $total_accumulated_gross + $total_leave_allowances;
                $payroll['annual_employee_pension'] = $payroll['annual_gross_pay'] * 0.08;
                $payroll['monthly_employee_pension'] = $payroll['gross_pay'] * 0.08;
                $payroll['annual_employer_pension'] = $payroll['annual_gross_pay'] * 0.1;
                $payroll['monthly_employer_pension'] = $payroll['gross_pay'] * 0.1;
                $payroll['consolidated_allowance'] = $this->consolidated_allowance($payroll['tax_gross_pay'] + $payroll['annual_employer_pension']);
                $payroll['total_relief'] = $payroll['consolidated_allowance'] + $payroll['annual_employee_pension'] + 14000;
                $payroll['taxable_income'] = $payroll['tax_gross_pay'] - $payroll['total_relief'];

                $this->calculate_tmsa_tax($payroll);

                $reconciled_tax=$payroll['paye']=$payroll['annual_paye']-$previous_accumulated_tax;
                $payroll['out_of_station_allowance'] = $tp->out_of_station * $schedule->days_out_of_station;
                $payroll['brt_allowance'] = (($tp->brt_percentage / 100) * $schedule->day_rate_onshore) * $schedule->brt_days;
                $payroll['paid_offtime_rate'] = $schedule->paid_offtime_rate;
                $payroll['paid_off_day'] = $schedule->paid_off_day;
                $payroll['total_paid_offtime'] = $payroll['paid_offtime_rate'] * $payroll['paid_off_day'];

                $this->tmsaallowancesanddeductions($payroll);
                $this->calculate_specific_salary_components($payroll);
            }


            //

        }else{
            $this->tmsaschedulecalculations($payroll);

        }
    }

    public function tmsaschedulecalculations(&$payroll)
    {
        $user_id = $payroll['user_id'];
        $schedule = TmsaSchedule::whereDate('for', $payroll['date'])->where(['user_id' => $user_id])->first();
        $tp = TmsaPolicy::where(['company_id' => $payroll['company_id']])->first();
        if ($schedule) {
            $payroll['has_timesheet'] = 1;
            $payroll['day_rate_onshore'] = ($schedule->day_rate_onshore > 0) ? $schedule->day_rate_onshore : 0;
            $payroll['days_worked_onshore'] = ($schedule->days_worked_onshore > 0) ? $schedule->days_worked_onshore : 0;
            $payroll['day_rate_offshore'] = ($schedule->day_rate_offshore > 0) ? $schedule->day_rate_offshore : 0;
            $payroll['days_worked_offshore'] = ($schedule->days_worked_offshore > 0) ? $schedule->days_worked_offshore : 0;
            $payroll['gross_pay'] = ($payroll['day_rate_onshore'] * $payroll['days_worked_onshore']) + ($payroll['day_rate_offshore'] * $payroll['days_worked_offshore']);
            $payroll['annual_gross_pay'] = $payroll['tax_gross_pay'] = $payroll['gross_pay'] * 12;
            $payroll['annual_employee_pension'] = $payroll['annual_gross_pay'] * 0.08;
            $payroll['monthly_employee_pension'] = $payroll['gross_pay'] * 0.08;
            $payroll['annual_employer_pension'] = $payroll['annual_gross_pay'] * 0.1;
            $payroll['monthly_employer_pension'] = $payroll['gross_pay'] * 0.1;
            $payroll['consolidated_allowance'] = $this->consolidated_allowance($payroll['annual_gross_pay'] + $payroll['annual_employer_pension']);
            $payroll['total_relief'] = $payroll['consolidated_allowance'] + $payroll['annual_employee_pension'] + 14000;
            $payroll['taxable_income'] = $payroll['annual_gross_pay'] - $payroll['total_relief'];

            $this->calculate_tmsa_tax($payroll);
            $payroll['out_of_station_allowance'] = $tp->out_of_station * $schedule->days_out_of_station;
            $payroll['brt_allowance'] = (($tp->brt_percentage / 100) * $schedule->day_rate_onshore) * $schedule->brt_days;
            $payroll['paid_offtime_rate'] = $schedule->paid_offtime_rate;
            $payroll['paid_off_day'] = $schedule->paid_off_day;
            $payroll['total_paid_offtime'] = $payroll['paid_offtime_rate'] * $payroll['paid_off_day'];

            $this->tmsaallowancesanddeductions($payroll);
            $this->calculate_specific_salary_components($payroll);
        } else {
            $payroll['has_timesheet'] = 0;
        }

        # code...
    }
    // public function tmsaleaveallowance(&$payroll)
    // {
    //    $total_offshore_days=0;
    //    $total_onshore_days=0;
    //    $payroll['total_days_jan']=0;
    //    $payroll['total_days_jul']=0;
    //    $payroll['day_rate_jul']=0;
    //    $payroll['day_rate_jan']=0;


    //    if ($tp->run_jul_leave) {
    //       for ($i=1; $i <=6 ; $i++) {
    //         $schedule = (new TmsaSchedule)->newQuery();
    //           $schedule->where('user_id',$user_id)->where('for',$year);
    //           $schedule->where(function ($query) {
    //                     $query->whereMonth('for',$i)
    //                           ->orWhere('title', '=', 'Admin');
    //                 });
    //            $schedule->get();
    //            $total_offshore_days+=$schedule->sum('days_worked_offshore');
    //             $total_onshore_days+=$schedule->sum('days_worked_onshore');
    //             $average_rate+=$schedule->avg('day_rate_onshore');

    //       }
    //      $payroll['day_rate_jan']=$ave;
    //       $payroll['total_days_jul']=$total_offshore_days+$total_onshore_days;
    //    }

    //    if ($tp->run_jan_leave) {
    //       for ($i=7; $i <=12 ; $i++) {
    //          $schedule = (new TmsaSchedule)->newQuery();
    //           $schedule->whereMonth('for',$i)->where('user_id',$user_id)->where('for',$year-1);
    //            $schedule->get();
    //            $total_offshore_days+=$schedule->sum('days_worked_offshore');
    //             $total_onshore_days+=$schedule->sum('days_worked_onshore');
    //              $average_rate+=$schedule->avg('day_rate_onshore');
    //       }

    //       $payroll['total_days_jan']=$total_offshore_days+$total_onshore_days;
    //    }

    //    $total_offshore_days=$schedule->sum('days_worked_offshore');
    //    $total_onshore_days=$schedule->sum('days_worked_onshore');
    // }
    public function tmsaallowancesanddeductions(&$payroll)
    {
        $user_id = $payroll['user_id'];
        $components = TmsaComponent::where(['company_id' => $payroll['company_id'], 'uses_month' => 0, 'status' => 1])->get();
        $month_components = TmsaComponent::where(['company_id' => $payroll['company_id'], 'uses_month' => 1, 'status' => 1])->get();

        $payroll['allowances'] = $payroll['deductions'] = $payroll['component_names'] = $payroll['sc_component_names'] = [];
        $payroll['sc_allowances'] = $payroll['sc_deductions'] = $payroll['sc_project_code'] = $payroll['sc_gl_code'] = [];
        $payroll['total_allowances'] = $payroll['total_deductions'] = $payroll['not_taxable'] = 0;
        $payroll['sc_total_allowances'] = $payroll['sc_total_deductions'] = $payroll['leave_allowance'] = 0;
        $net = [];
        foreach ($month_components as $component) {
            $total_month_amount = 0;

            foreach ($component->months as $month) {
                $tmsa_schedule = \App\TmsaSchedule::whereMonth('for', $month->month)->whereYear('for', $month->year)->where('user_id', $user_id)->first();
                if ($tmsa_schedule) {
                    if($user_id==1120){
                        $total_month_amount += $tmsa_schedule->day_rate_onshore * 23;
                    }else{
                        $total_month_amount += ($tmsa_schedule->day_rate_onshore * $tmsa_schedule->days_worked_onshore) + ($tmsa_schedule->day_rate_offshore * $tmsa_schedule->days_worked_offshore);
                    }


                }

            }

            if ($component->rate > 0) {
                $value = ($total_month_amount * $component->rate) / 100;
                $net[$component->constant] = ($total_month_amount * $component->rate) / 100 - (($total_month_amount * $component->rate) / 100) * 0.01;
            } else {
                $value = $total_month_amount;
                $net[$component->constant] = $total_month_amount - ($total_month_amount * 0.01);
            }

            $payroll['component_names'][$component->constant] = $component->name;
            $payroll['sc_component_names'][$component->constant] = $component->name;
            $payroll['sc_project_code'][$component->constant] = $component->project_code;
            $payroll['sc_gl_code'][$component->constant] = $component->gl_code;
            if ($component->type == 1) {
                $payroll['allowances'][$component->constant] = number_format($value, 2, '.', '');
                $payroll['sc_allowances'][$component->constant] = number_format($value, 2, '.', '');

            } else {
                $payroll['deductions'][$component->constant] = number_format($value, 2, '.', '');
                $payroll['sc_deductions'][$component->constant] = number_format($value, 2, '.', '');
            }
            if ($component->type == 1) {
                // $payroll['gross_pay']+=$value;
                $payroll['total_allowances'] += $value;
                $payroll['sc_total_allowances'] += $value;
            } else {
                $payroll['total_deductions'] += $value;
                $payroll['sc_total_deductions'] += $value;
            }
            if($component->constant=='leave_allowance'){
                $payroll['leave_allowance']=$value;
            }

        }

        $net ['daily_rate'] = $payroll['day_rate_onshore'];
        foreach ($components as $component) {
            if ($component->fixed == 1) {
                $net[$component->constant] = $component->amount;
            }
        }

        foreach ($components as $component) {

            if ($component->status == 1 && $component->exemptions->contains('id', $user_id) == false) {

                $payroll['component_names'][$component->constant] = $component->name;
                $payroll['sc_component_names'][$component->constant] = $component->name;
                $payroll['sc_project_code'][$component->constant] = $component->project_code;
                $payroll['sc_gl_code'][$component->constant] = $component->gl_code;

                if ($component->fixed == 0) {

                    $net[$component->constant] = $value = $this->calculate_salary_component($component->constant, $component->formula, $net);
                } elseif ($component->fixed == 1) {
                    $value = $component->amount;
                }
                if ($component->type == 1) {
                    $payroll['allowances'][$component->constant] = number_format($value, 2, '.', '');
                    $payroll['sc_allowances'][$component->constant] = number_format($value, 2, '.', '');

                } else {
                    $payroll['deductions'][$component->constant] = number_format($value, 2, '.', '');
                    $payroll['sc_deductions'][$component->constant] = number_format($value, 2, '.', '');
                }

                if ($component->type == 1) {
                    // $payroll['gross_pay']+=$value;
                    $payroll['total_allowances'] += $value;
                    $payroll['sc_total_allowances'] += $value;
                } else {
                    $payroll['total_deductions'] += $value;
                    $payroll['sc_total_deductions'] += $value;
                }
                if ($component->taxable == 0) {
                    //   $payroll['not_taxable']=number_format($value, 2, '.', '');
                }


            }
        }
    }
    public function calculate_tmsa_tax(&$payroll)
    {
        $ti = $payroll['taxable_income'];
        $lv1 = 0;
        $lv2 = 0;
        $lv3 = 0;
        $lv4 = 0;
        $lv5 = 0;
        $lv6 = 0;
        //first level

        if ($payroll['tax_gross_pay'] < 300000) {
            $lv1 = $payroll['tax_gross_pay'] * 0.01;
            $ti = 0;
        } elseif ($ti <= 300000) {
            $lv1 = $ti * 0.07;
            $ti = $ti - $ti;
        } elseif ($ti > 300000) {
            $ti = $ti - 300000;
            $lv1 = 300000 * 0.07;
        }
        // second level
        if ($ti < 300000) {
            $lv2 = $ti * 0.11;
            $ti = $ti - $ti;
        } elseif ($ti == 300000) {
            $lv2 = $ti * 0.11;
            $ti = $ti - $ti;
        } elseif ($ti > 300000) {
            $ti = $ti - 300000;
            $lv2 = 300000 * 0.11;
        }
        //third level
        if ($ti < 500000) {
            $lv3 = $ti * 0.15;
            $ti = $ti - $ti;
        } elseif ($ti == 500000) {
            $lv3 = $ti * 0.15;
            $ti = $ti - $ti;
        } elseif ($ti > 500000) {
            $ti = $ti - 500000;
            $lv3 = 500000 * 0.15;
        }
        //fourth level
        if ($ti < 500000) {
            $lv4 = $ti * 0.19;
            $ti = $ti - $ti;
        } elseif ($ti == 500000) {
            $lv4 = $ti * 0.19;
            $ti = $ti - $ti;
        } elseif ($ti > 500000) {
            $ti = $ti - 500000;
            $lv4 = 500000 * 0.19;
        }
        //fifth level
        if ($ti < 1600000) {
            $lv5 = $ti * 0.21;
            $ti = $ti - $ti;
        } elseif ($ti == 1600000) {
            $lv5 = $ti * 0.21;
            $ti = $ti - $ti;
        } elseif ($ti > 1600000) {
            $ti = $ti - 1600000;
            $lv5 = 1600000 * 0.21;
        }
        //sixth level
        if ($ti < 3200000) {
            $lv6 = $ti * 0.24;
            $ti = $ti - $ti;
        } elseif ($ti == 3200000) {
            $lv6 = $ti * 0.24;
            $ti = $ti - $ti;
        } elseif ($ti > 3200000) {
            $lv6 = $ti * 0.24;
        }

        $payroll['annual_paye'] = ($lv1 + $lv2 + $lv3 + $lv4 + $lv5 + $lv6);

        	$payroll['paye']=$payroll['annual_paye']/12;


    }

    // End TMSA Allowances and deductions

    public function getExpectedDays($month, $year, $company_id)
    {
        $total_days = 0;
        $company_id = $company_id;
        $days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $pp = PayrollPolicy::where('company_id', $company_id)->first();
        for ($i = 1; $i <= $days; $i++) {
            if ($pp->payroll_runs == 1) {

                $total_days++;

            } else {
                if (date('N', strtotime("$year-$month-$i")) < 6 && $this->checkHoliday("$year-$month-$i") == false) {
                    $total_days++;
                }
            }

        }
        return $total_days;
    }

    public function getEmployeeDays($month, $year, $start = 1, $end = 0, $company_id)
    {
        $total_days = 0;
        $company_id = $company_id;
        if ($end == 0) {
            $days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        } else {
            $days = $end;
        }

        $pp = PayrollPolicy::where('company_id', $company_id)->first();
        if ($start == 1) {
            for ($i = 1; $i <= $days; $i++) {
                if ($pp->payroll_runs == 1) {

                    $total_days++;

                } else {
                    if (date('N', strtotime("$year-$month-$i")) < 6 && $this->checkHoliday("$year-$month-$i") == false) {
                        $total_days++;
                    }
                }
            }
            return $total_days;
        } elseif ($start > 1) {
            for ($i = $start; $i <= $days; $i++) {
                if ($pp->payroll_runs == 1) {

                    $total_days++;

                } else {
                    if (date('N', strtotime("$year-$month-$i")) < 6 && $this->checkHoliday("$year-$month-$i") == false) {
                        $total_days++;
                    }
                }
            }


            return $total_days;
        }
    }

    public function checkHoliday($date)
    {
        $has_holiday = Holiday::whereDate('date', $date)->first();
        $retVal = ($has_holiday) ? true : false;
        return $retVal;
    }

    public function issuePayslip(Request $request)
    {
        $company_id = companyId();
        $payroll = Payroll::find($request->payroll_id);
        if ($payroll) {
            // foreach ($payroll->payroll_details  as $detail) {
            //   if ($detail->user->email!='') {

            //      \Mail::to($detail->user)->send(new \App\Mail\SendPayslip($detail->id,$company_id));

            //     // $detail->user->notify(new \App\Notifications\SendPayslip($detail->id,$company_id));
            //   }


            // }
            $payroll->payslip_issued = 1;
            $payroll->save();
            return 'success';
        }

    }

    public function downloadPayslip(Request $request)
    {

        // $company_id=companyId();
        // $company=\App\Company::find($company_id);
        // $detail=PayrollDetail::find($request->id);
        // $logo=PayslipDetail::first()->logo;
        // return view('compensation.partials.payslip', compact('detail','logo'));

        $company_id = companyId();
        $company = \App\Company::find($company_id);;

        $image = url('uploads/logo') . $company->logo;
        $detail = PayrollDetail::find($request->id);
        $pp = PayrollPolicy::where('company_id', $company_id)->first();

        // return view('compensation.partials.payslip2', compact('detail','company'));
        //return '<img src="'.url('uploads/logo').$company->logo.'" style="height: 2rem;background-color:#fff; width: auto;">';
        if ($pp->show_all_gross == 1) {
            // dd(1);
            $pdf = PDF::loadView('compensation.partials.payslip', compact('detail', 'company'));
            $pdf->setWatermarkImage($image, $opacity = 0.3, $top = '30%', $width = '100%', $height = '100%');
        } else {
            // dd(2);
            $pdf = PDF::loadView('compensation.partials.payslip2', compact('detail', 'company'));
            $pdf->setWatermarkImage($image, $opacity = 0.3, $top = '30%', $width = '100%', $height = '100%');
        }
        //   $pdf = \App::make('dompdf.wrapper');
        //   $pdf->setWatermarkImage($image, $opacity = 0.05, $top = '30%', $width = '100%', $height = '100%');
        // $pdf->loadView('compensation.partials.payslip', compact('detail','company')); //load view page
        // $pdf = PDF::loadView('compensation.partials.payslip', compact('detail','company'));
        // $pdf->setWatermarkImage(public_path('storage'.$logo));
        // $pdf->setWatermarkText('example', '150px');
        return $pdf->stream(Auth::user()->name . '.pdf');
    }

    public function sendPayslip(Request $request)
    {


        $payroll = Payroll::find($request->payroll_id);

        if ($payroll->payslip_issued == 1) {

            foreach ($payroll->payroll_details as $detail) {
                if ($detail->user->email != '') {
                    $company_id = $detail->user->company_id;
                    $company = \App\Company::find($detail->user->company_id);
                    $pp = PayrollPolicy::where('company_id', $company->id)->first();

                    \Mail::to($detail->user)->send(new \App\Mail\SendPayslip($detail->id, $company->id, $pp));

                    // $detail->user->notify(new \App\Notifications\SendPayslip($detail->id,$company_id));
                }


            }

            // session()->flash('notif', 'Invoice has been Mailed to Customer Successfully!');
            return 'success';
        } else {
            return 'error';
        }
    }

    public function sendSelectedPayslip(Request $request)
    {

        $details = \App\PayrollDetail::find($request->details);
        $payroll = Payroll::find($request->payroll_id);

        if ($payroll->payslip_issued == 1) {

            foreach ($details as $detail) {
                if ($detail->user->email != '') {
                    $company_id = $detail->user->company_id;
                    $company = \App\Company::find($detail->user->company_id);
                    $pp = PayrollPolicy::where('company_id', $company->id)->first();


                    \Mail::to($detail->user)->send(new \App\Mail\SendPayslip($detail->id, $company_id, $pp));

                    // $detail->user->notify(new \App\Notifications\SendPayslip($detail->id,$company_id));
                }


            }

            // session()->flash('notif', 'Invoice has been Mailed to Customer Successfully!');
            return 'success';
        } else {
            return 'error';
        }
    }

    public function rollbackPayroll(Request $request)
    {
        $payroll = Payroll::find($request->payroll_id);
        $sscs = $payroll->specific_salary_components;
        $scs = $payroll->salary_components;
        $tcs = $payroll->tmsa_components;
        $pcs = $payroll->project_salary_components;
        $lrs = $payroll->loan_requests;
        $pds = $payroll->payroll_details;
        $tpds = $payroll->tmsa_payroll_details;
        $psds = $payroll->suspension_deductions;
        $salary_reviews = SalaryReview::where('payment_month', $payroll->for)->get();
        if ($salary_reviews) {
            foreach ($salary_reviews as $review) {
                $review->used = 0;
                $review->save();
            }
        }
        if ($pds) {
            foreach ($pds as $pd) {
                $pd->delete();
            }
        }
        if ($tpds) {
            foreach ($tpds as $pd) {
                $pd->delete();
            }
        }

        if ($sscs) {
            foreach ($sscs as $ssc) {
                $ssc->update(['grants' => intval($ssc->grants) - 1, 'completed' => 0]);
                $payroll->specific_salary_components()->detach($ssc->id);
            }
        }
        if ($lrs) {
            foreach ($lrs as $lr) {
                $lr->update(['months_deducted' => intval($lr->months_deducted) - 1, 'completed' => 0]);
                $payroll->loan_requests()->detach($lr->id);
            }
        }
        if ($scs) {
            $payroll->salary_components()->detach();
        }
        if ($pcs) {
            $payroll->project_salary_components()->detach();
        }
        if ($tcs) {
            $payroll->tmsa_components()->detach();
        }
        if ($psds) {
            $payroll->suspension_deductions()->detach();
            foreach ($psds as $key => $psd) {
                $psd->update(['deducted' => 0]);
            }
        }

        $payroll->delete();
        return 'success';
    }

    public function review_payroll(&$payroll)
    {

        //     $user_id=$payroll['user_id'];
        //     $company_id=$payroll['company_id']
        //     $user=User::find($user_id);
        //     $review=\App\SalaryReview::where('salary_month',$payroll['payroll']->salary_month)->where('employee_id',$user_id)->first();
        //     $start    = new DateTime(date('Y-m-d',strtotime($review->effective_month)));
        //     $start->modify('first day of this month');
        //     $end    = new DateTime(date('Y-m-d',strtotime($review->salary_month)));
        //     $end->modify('first day of next month');
        //     $interval = DateInterval::createFromDateString('1 month');
        //     $period   = new DatePeriod($start, $interval, $end);

        //     $netpay_specific_component_type_id = PayrollPolicy::where(['company_id'=>$company_id])->first()->netpay_specific_component_id;
        //     $netpay_difference=0;
        //     foreach ($period as $dt) {
        //         $review_injection_components=\App\SalaryReviewInjectionComponent::where('status',1)->where('company_id',$user->company_id)->get();
        //         $payroll_detail=\App\PayrollDetail::whereHas('payroll', function (Builder $query) use($dt) {

        //             $query->where('for', $dt->format('Y-m-d'));
        //         })->where('user_id',$user_id)->first();
        //         $netpay_difference+=(($payroll['basic_pay']+$payroll['sc_allowances'])-($payroll['sc_deductions']+$payroll['standard_tax']))-(($payroll_detail->basic_pay+$payroll_detail->sc_allowances)-($payroll_detail->sc_deductions+$payroll_detail->standard_paye));

        //         foreach ($review_injection_components as $component){
        //             if ($component->injection_type==1) {
        //                 //if it is a salary component
        //                 $payroll[$component->]
        //             }elseif($component->injection_type==2){
        //                 //if it is a payroll component
        //             }
        //         }
        //         $dt->format("Y-m") . "<br>\n";
        //     }
        //     $

        //     $sc = SpecificSalaryComponent::create(['name' => $request->name, 'Previous Netpay Difference' => $netpay_difference, 'type' => 1, 'comment' => $request->comment, 'emp_id' => $payroll['user_id'], 'duration' => 1, 'grants' => 1, 'status' => 1,  'company_id' => $company_id, 'specific_salary_component_type_id' => $netpay_specific_component_type_id, 'taxable' => 0, 'one_off' => 1]);

    }
    public function disburse(Request $request)
    {
        $company_id = companyId();
        $payroll = Payroll::find($request->payroll_id);
        if ($payroll) {
            Artisan::call('fund:transfer',['payroll_id'=>$payroll->id]);
//            return ['status'=>'success'];
//            $payroll->payslip_issued = 1;
//            $payroll->save();
            return 'success';
        }

    }

}
