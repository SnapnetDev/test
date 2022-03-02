<?php


namespace App\Traits;


use App\Services\AuditExportService;
use App\Setting;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

trait AuditTrait
{
    public function processGet($route, Request $request)
    {
        switch ($route) {

            case 'view_bsc_evaluation_activity':
                return $this->bscEvaluationActivity($request);
                break;

            case 'view_leave_request_activity':
                return $this->leaveRequestActivity($request);
                break;

            case 'view_leave_request_recall_activity':
                return $this->leaveRecallActivity($request);
                break;

            case 'view_leave_request_adjustment_activity':
                return $this->leaveAdjustmentActivity($request);
                break;

            case 'view_bsc_activity':
                return $this->balanceScoreCardActivity($request);
                break;

            case 'view_activity':
                # code...
                return $this->view_activity($request);
                break;
            case 'view_login_activity':
                # code...
                return $this->view_login_activity($request);
                break;
            case 'view_payroll_activity':
                # code...
                return $this->view_payroll_activity($request);
                break;
            case 'index':
                # code...
                return $this->get_trails($request);
                break;
            case 'view_salary_component_activity':
                # code...
                return $this->view_salary_component_activity($request);
                break;

            case 'view_specific_salary_component_activity':
                # code...
                return $this->specificSalaryComponent($request);
                break;

            case 'view_pace_salary_activity':
                # code...
                return $this->paceSalaryActivity($request);
                break;


            default:
                # code...
                break;

        }
    }

    //

    public function bscEvaluationActivity(Request $request)
    {

        $dt_from = Carbon::parse($request->input('start_date'));
        $dt_to = Carbon::parse($request->input('end_date'));

        //      make checks on the incoming request for excel and returns activities
        $activities = AuditExportService::checkExcelRequest($request, $dt_from, $dt_to, 'log_name', 'bscEvaluation');


        $type = "Balance Score Card";
        $company_id = companyId();
        $setting = Setting::where(['name' => 'payroll', 'company_id' => $company_id])->first();
        return view('audit.list', compact('activities', 'type', 'setting'));
    }

    public function leaveRequestActivity(Request $request)
    {

        $dt_from = Carbon::parse($request->input('start_date'));
        $dt_to = Carbon::parse($request->input('end_date'));


        //      make checks on the incoming request for excel and returns activities
        $activities = AuditExportService::checkExcelRequest($request, $dt_from, $dt_to, 'log_name', 'leaveRequest');


        $type = "Leave Request";
        $company_id = companyId();
        $setting = Setting::where(['name' => 'payroll', 'company_id' => $company_id])->first();
        return view('audit.list', compact('activities', 'type', 'setting'));
    }

    public function leaveRecallActivity(Request $request)
    {

        $dt_from = Carbon::parse($request->input('start_date'));
        $dt_to = Carbon::parse($request->input('end_date'));


        //      make checks on the incoming request for excel and returns activities
        $activities = AuditExportService::checkExcelRequest($request, $dt_from, $dt_to, 'subject_type', 'App\LeaveRequestRecall');


        $type = "Leave Request Recall";
        $company_id = companyId();
        $setting = Setting::where(['name' => 'payroll', 'company_id' => $company_id])->first();
        return view('audit.list', compact('activities', 'type', 'setting'));
    }

    //    get Balance Scorecard activity logs

    public function leaveAdjustmentActivity(Request $request)
    {

        $dt_from = Carbon::parse($request->input('start_date'));
        $dt_to = Carbon::parse($request->input('end_date'));

        //      make checks on the incoming request for excel and returns activities
        $activities = AuditExportService::checkExcelRequest($request, $dt_from, $dt_to, 'subject_type', 'App\LeaveRequestAdjustment');

        $type = "Leave Request Adjustment";
        $company_id = companyId();
        $setting = Setting::where(['name' => 'payroll', 'company_id' => $company_id])->first();
        return view('audit.list', compact('activities', 'type', 'setting'));

    }

    //    get request activity logs
    public function balanceScoreCardActivity(Request $request)
    {

        $dt_from = Carbon::parse($request->input('start_date'));
        $dt_to = Carbon::parse($request->input('end_date'));

        //      make checks on the incoming request for excel and returns activities
        $activities = AuditExportService::checkExcelRequest($request, $dt_from, $dt_to, 'log_name', 'bsc');

        $type = "Balance Score Card";
        $company_id = companyId();
        $setting = Setting::where(['name' => 'payroll', 'company_id' => $company_id])->first();
        return view('audit.list', compact('activities', 'type', 'setting'));
    }

    public function view_login_activity(Request $request)
    {
        $users = User::where('company_id', companyId())->get();
        //       return $activity->changes['old']['name'];


        return view('audit.login', compact('users'));
    }

    public function view_payroll_activity(Request $request)
    {
        $dt_from = Carbon::parse($request->input('start_date'));
        $dt_to = Carbon::parse($request->input('end_date'));

        $activities = AuditExportService::checkExcelRequest($request, $dt_from, $dt_to, 'subject_type', 'App\Payroll');
        $type = 'payroll';
        $company_id = companyId();
        $setting = Setting::where(['name' => $type, 'company_id' => $company_id])->first();
        //        return count($activities);
        return view('audit.list', compact('activities', 'type', 'setting'));
    }

    public function get_trails(Request $request)
    {
        $dt_from = Carbon::parse($request->input('start_date'));
        $dt_to = Carbon::parse($request->input('end_date'));

        $activities = AuditExportService::checkExcelRequest($request, $dt_from, $dt_to, 'subject_type', 'App\User');

        $type = 'Employee Profile';
        //
        $company_id = companyId();
        $setting = Setting::where(['name' => 'profile', 'company_id' => $company_id])->first();
        return view('audit.list', compact('activities', 'type', 'setting'));
    }


    public function view_salary_component_activity(Request $request)
    {

        $dt_from = Carbon::parse($request->input('start_date'));
        $dt_to = Carbon::parse($request->input('end_date'));
        
        $activities = AuditExportService::checkExcelRequest($request, $dt_from, $dt_to, 'subject_type', 'App\SalaryComponent');

        $type = 'Salary Component';
        //        return count($activities);

        $company_id = companyId();
        $setting = Setting::where(['name' => 'salary_component', 'company_id' => $company_id])->first();
        return view('audit.list', compact('activities', 'type', 'setting'));
    }

    public function specificSalaryComponent(Request $request)
    {

        $dt_from = Carbon::parse($request->input('start_date'));
        $dt_to = Carbon::parse($request->input('end_date'));


        $activities = AuditExportService::checkExcelRequest($request, $dt_from, $dt_to, 'subject_type', 'App\SalaryComponent');

        $type = "Specific Salary Component";
        $company_id = companyId();
        $setting = Setting::where(['name' => 'salary_component', 'company_id' => $company_id])->first();
        return view('audit.list', compact('activities', 'type', 'setting'));

    }

    public function paceSalaryActivity(Request $request)
    {

        $dt_from = Carbon::parse($request->input('start_date'));
        $dt_to = Carbon::parse($request->input('end_date'));

        $activities = AuditExportService::checkExcelRequest($request, $dt_from, $dt_to, 'log_name', 'paceSalary');

        $type = "Pace Salary Activity";
        $company_id = companyId();
        $setting = Setting::where(['name' => 'salary_component', 'company_id' => $company_id])->first();
        return view('audit.list', compact('activities', 'type', 'setting'));
    }


    //    Function to view individual activity

    public function view_activity(Request $request)
    {
        $activity = Activity::find($request->activity_id);
        //       return $activity->changes['old']['name'];

        if ($request->type == 'user') {
            return view('audit.partials.info', compact('activity'));
        }
        elseif ($request->type == 'payroll') {
            return view('audit.partials.payroll_info', compact('activity'));
        }
        elseif ($request->type == 'salary_component') {
            return view('audit.partials.salary_component_info', compact('activity'));
        }
        elseif ($request->type == 'payroll_policy') {

        }
        elseif ($request->type == 'leave_request') {
            return view('audit.partials.leave_request_info', compact('activity'));
        }

        elseif ($request->type == 'leave_recall') {
            return view('audit.partials.leave_recall_info', compact('activity'));
        }

        elseif ($request->type == 'leave_adjustment') {
            return view('audit.partials.leave_adjustment_info', compact('activity'));
        }

        elseif ($request->type == 'bsc') {
            return view('audit.partials.bsc_info', compact('activity'));
        }
        elseif ($request->type == 'bscEval') {
            return view('audit.partials.bsc_evaluation_info', compact('activity'));
        }
        elseif ($request->type == 'spsc') {
            return view('audit.partials.specific_salary_component_info', compact('activity'));
        }

        elseif ($request->type == 'paceSalary') {
            return view('audit.partials.pace_salary_info', compact('activity'));
        }
    }

    //    end of functions
    public function processPost(Request $request)
    {
        // try{


    }


}