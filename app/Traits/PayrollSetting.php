<?php

namespace App\Traits;

use App\Payroll;
use App\Bank;
use App\CompanyAccountDetail;
use App\PayslipDetail;
use App\PayrollPolicy;
use App\TmsaPolicy;
use App\LoanPolicy;
use App\SalaryComponent;
use App\TmsaComponent;
use App\TmsaSchedule;
use App\SpecificSalaryComponent;
use App\Workflow;
use App\LatenessPolicy;
use App\Setting;
use App\PaceSalaryCategory;
use App\PaceSalaryComponent;
use App\PaceSpecificSalaryComponent;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Auth;
use Excel;
use Illuminate\Validation\Rule;
use Validator;
use App\SpecificSalaryComponentType;
use App\Company;


/**
 *
 */
trait PayrollSetting
{
    public function processGet($route, Request $request)
    {
        switch ($route) {
            case 'account':
                # code...
                return $this->accountSettings($request);
                break;
            case 'payslip':
                # code...
                return $this->payslipDetailSettings($request);
                break;
            case 'payroll_policy':
                # code...
                return $this->payrollPolicySettings($request);
                break;
            case 'tmsa_policy':
                # code...
                return $this->tmsaPolicySettings($request);
                break;
            case 'loan_policy':
                # code...
                return $this->loanPolicySettings($request);
                break;
            case 'salary_components':
                # code...
                return $this->salaryComponents($request);
                break;
            case 'salary_component':
                # code...
                return $this->salaryComponent($request);
                break;
            case 'specific_salary_components':
                # code...
                return $this->specificSalaryComponents($request);
                break;
            case 'downloadssctemplate':
                return $this->downloadSSTemplate($request);
                break;
            case 'downloadssctypetemplate':
                return $this->downloadSSTypeTemplate($request);
                break;
            case 'specific_salary_component':
                # code...
                return $this->specificSalaryComponent($request);
                break;
            case 'change_salary_component_status':
                # code...
                return $this->changeSalaryComponentStatus($request);
                break;
            case 'delete_salary_component':
                # code...
                return $this->deleteSalaryComponent($request);
                break;
            case 'change_specific_salary_component_status':
                # code...
                return $this->changeSpecificSalaryComponentStatus($request);
                break;
            case 'change_salary_component_taxable':
                # code...
                return $this->changeSalaryComponentTaxable($request);
                break;
            case 'change_specific_salary_component_taxable':
                # code...
                return $this->changeSpecificSalaryComponentTaxable($request);
                break;
            case 'delete_specific_salary_component':
                # code...
                return $this->deleteSpecificSalaryComponent($request);
                break;
            case 'lateness_policy':
                # code...
                return $this->latenessPolicy($request);
                break;
            case 'change_lateness_policy_status':
                # code...
                return $this->changeLatenessPolicyStatus($request);
                break;
            case 'delete_lateness_policy':
                # code...
                return $this->deleteLatenessPolicy($request);
                break;
            case 'switch_lateness_policy':
                # code...
                return $this->switchLatenessPolicy($request);
                break;
            case 'switch_office_payroll_policy':
                # code...
                return $this->switchUseOfficePayrollPolicy($request);
                break;
            case 'switch_tmsa_payroll_policy':
                # code...
                return $this->switchUseTmsaPayrollPolicy($request);
                break;
            case 'switch_project_payroll_policy':
                # code...
                return $this->switchUseProjectPayrollPolicy($request);
                break;
            case 'tmsa_components':
                # code...
                return $this->tmsaComponents($request);
                break;
            case 'tmsa_component':
                # code...
                return $this->tmsaComponent($request);
                break;
            case 'change_tmsa_component_status':
                # code...
                return $this->changeTmsaComponentStatus($request);
                break;
            case 'delete_tmsa_component':
                # code...
                return $this->deleteTmsaComponent($request);
                break;
            case 'change_tmsa_component_taxable':
                # code...
                return $this->changeTmsaComponentTaxable($request);
                break;
            case 'project_salary_components':
                # code...
                return $this->projectSalaryComponents($request);
                break;
            case 'project_salary_component':
                # code...
                return $this->projectSalaryComponent($request);
                break;
            case 'change_project_salary_component_status':
                # code...
                return $this->changeProjectSalaryComponentStatus($request);
                break;
            case 'change_project_salary_component_taxable':
                # code...
                return $this->changeProjectSalaryComponentTaxable($request);
                break;
            case 'delete_project_salary_component':
                # code...
                return $this->deleteProjectSalaryComponent($request);
                break;
            case 'download_project_salary_component':
                # code...
                return $this->downloadProjectSalaryTemplate($request);
                break;

            case 'project_salary_categories':
                # code...
                return $this->projectSalaryCategories($request);
                break;

            case 'tmsa_schedules':
                # code...
                return $this->TMSASchedules($request);
                break;
            case 'tmsa_schedule':
                # code...
                return $this->TMSASchedule($request);
                break;
            case 'delete_tmsa_schedule':
                # code...
                return $this->deleteTMSASchedule($request);
                break;
            case 'download_tmsa_template':
                # code...
                return $this->downloadTMSAScheduleUploadTemplate($request);
                break;

            case 'specific_salary_component_type':
                # code...
                return $this->specificSalaryComponentType($request);
                break;
            case 'change_specific_salary_component_type_display':
                # code...
                return $this->changeSpecificSalaryComponentTypeDisplay($request);
                break;
            case 'delete_specific_salary_component_type':
                # code...
                return $this->deleteSpecificSalaryComponentType($request);
                break;
            case 'project_salary_category':
                # code...
                return $this->projectSalaryCategory($request);
                break;
            case 'delete_project_salary_category':
                # code...
                return $this->deleteProjectCategory($request);
                break;
            case 'download_timesheet_upload_template':
                # code...
                return $this->downloadTimesheetUploadTemplate($request);
                break;
            case 'download_all_timesheet_upload_template':
                # code...
                return $this->downloadAllTimesheetUploadTemplate($request);
                break;
            case 'project_salary_category_timesheets':
                # code...
                return $this->projectSalaryCategoryTimesheets($request);
                break;
            case 'chart_of_accounts':
                # code...
                return $this->accounts($request);
                break;
            case 'chart_of_account':
                # code...
                return $this->account($request);
                break;
            case 'delete_chart_of_account':
                # code...
                return $this->deleteAccount($request);
                break;
            case 'monthsearch':
                # code...
                return $this->SearchTmsaMonths($request);
                break;
            case 'long_service_awards':
                # code...
                return $this->longServiceAwards($request);
                break;
            case 'long_service_award':
                # code...
                return $this->longServiceAward($request);
                break;
            case 'delete_long_service_award':
                # code...
                return $this->deleteLongServiceAward($request);
                break;
            case 'switch_nav_export_display':
                # code...
                return $this->switchNavExportDisplay($request);
                break;
            case 'switch_payroll_export_display':
                # code...
                return $this->switchPayrollExportDisplay($request);
                break;
            case 'non_payroll_provision':
                # code...
                return $this->NonPayrollProvisionEmployees($request);
                break;
            case 'get_non_payroll_provision':
                # code...
                return $this->getNonPayrollProvisionEmployees($request);
                break;
            case 'remove_non_payroll_provision_employee':
                # code...
                return $this->removeNonPayrollEmployee($request);
                break;
            case 'non_payroll_provision_search':
                # code...
                return $this->searchNonPayrollProvisionEmployee($request);
                break;
            case 'download_13th_month_template':
                # code...
                return $this->downloadeoyTemplate($request);
                break;
            case 'download_leave_template':
                # code...
                return $this->downloadleaveTemplate($request);
                break;
            case 'salary_reviews':
                # code...
                return $this->salaryReviews($request);
                break;
            case 'salary_review':
                # code...
                return $this->salaryReview($request);
                break;
            case 'delete_salary_review':
                # code...
                return $this->deleteSalaryReview($request);
                break;
            case 'salary_review_injection_components':
                # code...
                return $this->salaryReviewInjectionComponents($request);
                break;
            case 'salary_review_injection_component':
                # code...
                return $this->salaryReviewInjectionComponent($request);
                break;
            case 'delete_salary_review_injection_component':
                # code...
                return $this->deleteSalaryReviewInjectionComponent($request);
                break;

            default:
                # code...
                break;
        }
    }

    public function processPost(Request $request)
    {

        switch ($request->type) {
            case 'account':
                # code...

                return $this->updateAccountSettings($request);
                break;
            case 'payslip':
                # code...
                return $this->updatePayslipDetailSettings($request);
                break;
            case 'payroll_policy':
                # code...
                return $this->savePayrollPolicySettings($request);
                break;
            case 'tmsa_policy':
                # code...
                return $this->saveTmsaPolicySettings($request);
                break;
            case 'loan_policy':
                # code...
                return $this->saveLoanPolicySettings($request);
                break;
            case 'salary_component':
                # code...
                return $this->saveSalaryComponent($request);
                break;
            case 'tmsa_component':
                # code...
                return $this->saveTmsaComponent($request);
                break;
            case 'specific_salary_component':
                # code...
                return $this->saveSpecificSalaryComponent($request);
                break;
            case 'update_specific_salary_component':
                # code...
                return $this->updateSpecificSalaryComponent($request);
                break;
            case 'import_specific_salary_components':
                # code...
                return $this->importSpecificSalaryComponent($request);
                break;
            case 'lateness_policy':
                # code...
                return $this->saveLatenessPolicy($request);
                break;
            case 'sal_component_type':
                # code...
                return $this->saveSpecificSalaryComponentType($request);
                break;
            case 'save_project_salary_category':
                # code...
                return $this->saveProjectSalaryCategory($request);
                break;
            case 'save_project_salary_component':
                # code...
                return $this->saveProjectSalaryComponent($request);
                break;
            case 'import_project_salary_components':
                # code...
                return $this->importProjectSalaryComponent($request);
                break;
            case 'import_project_salary_timesheets':
                # code...
                return $this->importProjectUserTimesheets($request);
                break;
            case 'save_tmsa_schedule':
                # code...
                return $this->saveTMSASchedule($request);
                break;
            case 'import_tmsa_schedule':
                # code...
                return $this->importTMSASchedule($request);
                break;
            case 'chart_of_accounts':
                # code...
                return $this->saveAccount($request);
            case 'chart_of_accounts_positions':
                # code...
                return $this->saveAccountPositions($request);
                break;
            case 'long_service_awards':
                # code...
                return $this->SaveLongserviceAward($request);
                break;
            case 'non_payroll_provision':
                # code...
                return $this->saveNonPayrollProvisionEmployees($request);
                break;
            case 'salary_review':
                # code...
                return $this->saveSalaryReview($request);
                break;
            case 'salary_review_injection_components':
                # code...
                return $this->saveSalaryReviewInjectionComponent($request);
                break;


            default:
                # code...
                break;
        }
    }

    public function accountSettings(Request $request)
    {
        $banks = Bank::orderBy('bank_name', 'ASC')->get();
        $cad = CompanyAccountDetail::first();
        return view('payrollsettings.account', compact('banks', 'cad'));
    }

    public function updateAccountSettings(Request $request)
    {
        // return $request->all();
        $cad = CompanyAccountDetail::first();
        if ($cad) {
            $cad->update(['accountNum' => $request->accountNum, 'first_name' => $request->first_name, 'last_name' => $request->last_name, 'bank_id' => $request->bank_id]);
        } else {
            CompanyAccountDetail::create(['accountNum' => $request->accountNum, 'first_name' => $request->first_name, 'last_name' => $request->last_name, 'bank_id' => $request->bank_id]);
        }
        return 'success';
    }

    public function payslipDetailSettings(Request $request)
    {
        $company_id = companyId();
        $payslip_detail = PayslipDetail::where('company_id', $company_id)->first();
        if (!$payslip_detail) {
            $payslip_detail = PayslipDetail::create(['watermark_text' => '', 'company_id' => $company_id]);
        }
        return view('payrollsettings.payslip_detail', compact('payslip_detail'));
    }

    public function updatePayslipDetailSettings(Request $request)
    {
        $company_id = companyId();
        $payslip_detail = PayslipDetail::first();
        if ($payslip_detail) {
            $payslip_detail->update(['watermark_text' => $request->watermark_text]);
            if ($request->file('logo')) {
                $path = $request->file('logo')->store('public');
                if (Str::contains($path, 'public/')) {
                    $filepath = Str::replaceFirst('public/', '', $path);
                } else {
                    $filepath = $path;
                }
                $payslip_detail->logo = $filepath;
                $payslip_detail->save();
            }
        } else {
            PayslipDetail::create(['watermark_text' => $request->watermark_text, 'company_id' => $company_id]);
            if ($request->file('logo')) {
                $path = $request->file('logo')->store('public');
                if (Str::contains($path, 'public/')) {
                    $filepath = Str::replaceFirst('public/', '', $path);
                } else {
                    $filepath = $path;
                }
                $payslip_detail->logo = $filepath;
                $payslip_detail->save();
            }
        }
        return 'success';


    }

    public function specificSalaryComponent(Request $request)
    {
        $ssc = SpecificSalaryComponent::with('user')->find($request->specific_salary_component_id);
        return $ssc;
    }

    public function specificSalaryComponents(Request $request)
    {
        $company_id = companyId();
        $sscs = SpecificSalaryComponent::where('company_id', $company_id)->whereColumn('grants', '!=', 'duration')->orderBy('created_at', 'desc')->get();
        $sscs_categories = SpecificSalaryComponentType::where('company_id', $company_id)->get();
        return view('payrollsettings.specific_salary_component', compact('sscs', 'sscs_categories'));
    }

    public function updateSpecificSalaryComponent(Request $request)
    {
        $company_id = companyId();
        $sc = SpecificSalaryComponent::find($request->id);
        $sc->update(['name' => $request->name, 'amount' => $request->amount, 'gl_code' => $request->gl_code, 'project_code' => $request->project_code, 'type' => $request->ssctype, 'comment' => $request->comment, 'emp_id' => $request->user_id, 'duration' => $request->has('duration') ? $request->duration : 1,  'company_id' => $company_id, 'specific_salary_component_type_id' => $request->category, 'taxable' => $request->taxable,'taxable_type' => $request->taxable_type, 'one_off' => $request->one_off]);

        return 'success';
    }

    public function saveSpecificSalaryComponent(Request $request)
    {
        $company_id = companyId();
        $sc = SpecificSalaryComponent::updateOrCreate(['id' => $request->specific_salary_component_id], ['name' => $request->name, 'amount' => $request->amount, 'gl_code' => $request->gl_code, 'project_code' => $request->project_code, 'type' => $request->ssctype, 'comment' => $request->comment, 'emp_id' => $request->user_id, 'duration' => $request->has('duration') ? $request->duration : 1, 'grants' => 0, 'status' => 1, 'starts' => $request->starts, 'ends' => $request->ends, 'company_id' => $company_id, 'specific_salary_component_type_id' => $request->category, 'taxable' => $request->taxable,'taxable_type' => $request->taxable_type, 'one_off' => $request->one_off]);

        return 'success';
    }

    public function deleteSpecificSalaryComponent(Request $request)
    {
        $sc = SpecificSalaryComponent::find($request->specific_salary_component_id);
        if ($sc) {

            $sc->delete();
            return 'success';
        }
    }

    public function salaryComponent(Request $request)
    {

        $sc = SalaryComponent::where('id', $request->salary_component_id)->with('exemptions')->first();
        return $sc;
    }

    public function salaryComponents(Request $request)
    {
        $scs = SalaryComponent::with('exemptions')->get();
        return view('payrollsettings.salary_component', compact('scs'));
    }

    public function saveSalaryComponent(Request $request)
    {
        $company_id = companyId();

        $validator = Validator::make($request->all(), [
            'constant' => [
                'required',
                Rule::unique('salary_components')->where(function ($query) use ($company_id, $request) {
                    return $query->where('company_id', $company_id)
                        ->where('id', '!=', $request->salary_component_id);
                })
            ],
        ]);
        if ($validator->fails()) {
            return response()->json([
                $validator->errors()
            ], 401);
        }

        $sc = SalaryComponent::updateOrCreate(['id' => $request->salary_component_id], ['name' => $request->name, 'gl_code' => $request->gl_code, 'project_code' => $request->project_code, 'type' => $request->sctype, 'comment' => $request->comment, 'constant' => $request->constant, 'formula' => $request->formula, 'company_id' => $company_id, 'taxable' => $request->taxable]);
        if ($request->filled('exemptions')) {
            $no_of_exemptions = count($request->input('exemptions'));
        } else {
            $no_of_exemptions = 0;
        }
        if ($no_of_exemptions > 0) {
            $sc->exemptions()->detach();
            for ($i = 0; $i < $no_of_exemptions; $i++) {
                if ($request->exemptions[$i] != 0) {
                    $sc->exemptions()->attach($request->exemptions[$i], ['created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]);
                }

            }
        } else {
            $sc->exemptions()->detach();
        }
        return 'success';
    }

    public function deleteSalaryComponent(Request $request)
    {
        $sc = SalaryComponent::find($request->salary_component_id);
        if ($sc) {
            $sc->exemptions()->detach();
            $sc->delete();
            return 'success';
        }
    }

    public function changeSalaryComponentStatus(Request $request)
    {
        $sc = SalaryComponent::find($request->salary_component_id);
        if ($sc->status == 1) {
            $sc->update(['status' => 0]);
            return 2;
        } elseif ($sc->status == 0) {
            $sc->update(['status' => 1]);
            return 1;
        }


    }

    public function changeSpecificSalaryComponentStatus(Request $request)
    {
        $sc = SpecificSalaryComponent::find($request->specific_salary_component_id);
        if ($sc->status == 1) {
            $sc->update(['status' => 0]);
            return 2;
        } elseif ($sc->status == 0) {
            $sc->update(['status' => 1]);
            return 1;
        }
    }

    public function changeSalaryComponentTaxable(Request $request)
    {
        $sc = SalaryComponent::find($request->salary_component_id);
        if ($sc->taxable == 1) {
            $sc->update(['taxable' => 0]);
            return 2;
        } elseif ($sc->taxable == 0) {
            $sc->update(['taxable' => 1]);
            return 1;
        }


    }

    public function changeSpecificSalaryComponentTaxable(Request $request)
    {
        $ssc = SpecificSalaryComponent::find($request->specific_salary_component_id);
        if ($ssc->taxable == 1) {
            $ssc->update(['taxable' => 0]);
            return 2;
        } elseif ($ssc->taxable == 0) {
            $ssc->update(['taxable' => 1]);
            return 1;
        }


    }

    public function payrollPolicySettings(Request $request)
    {
        $company_id = companyId();
        $pp = PayrollPolicy::where('company_id', $company_id)->first();
        $workflows = Workflow::all();
        // $setting=Setting::where(['name'=>'use_lateness','company_id'=>$company_id])->first();
        // if (!$setting) {
        //  $setting=Setting::create(['name'=>'use_lateness','company_id'=>$company_id]);
        // }
        $latenesspolicies = LatenessPolicy::where('company_id', $company_id)->get();
        if (count($latenesspolicies)==0){
            \App\LatenessPolicy::updateOrCreate(['policy_name'=>'Lateness Deduction', 'late_minute'=>'30',
                'deduction_type'=>'1', 'deduction'=>'10', 'specific_salary_component_type_id'=>'1',
                'company_id'=>$company_id, 'status'=>0
            ]);
        }
        $sal_comp_types = SpecificSalaryComponentType::where('company_id', $company_id)->get();
        if (!$pp) {
            $pp = PayrollPolicy::create(['basic_pay_percentage' => 0, 'payroll_runs' => 1, 'user_id' => Auth::user()->id, 'company_id' => $company_id, 'show_all_gross' => 1,'tax_preference'=>'new']);
        }
        return view('payrollsettings.payroll_policy', compact('pp', 'workflows', 'latenesspolicies', 'sal_comp_types'));
    }

    public function savePayrollPolicySettings(Request $request)
    {

        $company_id = companyId();
        $pp = PayrollPolicy::where('company_id', $company_id)->first();

        if ($pp) {
            $pp->update(['basic_pay_percentage' => $request->basic_pay_percentage, 'payroll_runs' => $request->when, 'user_id' => Auth::user()->id, 'workflow_id' => $request->workflow_id, 'show_all_gross' => $request->show_all_gross,'uses_approval' => $request->uses_approval,'tax_preference'=>$request->tax_preference]);
        } else {
            PayrollPolicy::create(['basic_pay_percentage' => $request->basic_pay_percentage, 'payroll_runs' => $request->payroll_runs, 'user_id' => Auth::user()->id, 'workflow_id' => $request->workflow_id, 'company_id' => $company_id, 'show_all_gross' => $request->show_all_gross,'uses_approval' => $request->uses_approval,'tax_preference'=>$request->tax_preference]);
        }
        return 'success';
    }

    public function tmsaPolicySettings(Request $request)
    {
        $company_id = companyId();
        $tp = TmsaPolicy::where('company_id', $company_id)->first();
        $workflows = Workflow::all();


        if (!$tp) {
            $tp = TmsaPolicy::create(['onshore_day_rate' => $request->onshore_day_rate, 'offshore_day_rate' => $request->offshore_day_rate, 'out_of_station' => $request->out_of_station, 'company_id' => $company_id]);
        }
        return view('payrollsettings.tmsa_policy', compact('tp', 'workflows'));
    }

    public function saveTmsaPolicySettings(Request $request)
    {
        // return $request->all();
        $company_id = companyId();
        $tp = TmsaPolicy::where('company_id', $company_id)->first();

        if ($tp) {
            $tp->update(['onshore_day_rate' => $request->onshore_day_rate, 'brt_percentage' => $request->brt_percentage, 'out_of_station' => $request->out_of_station, 'workflow_id' => $request->workflow_id]);
        } else {
            TmsaPolicy::create(['onshore_day_rate' => $request->onshore_day_rate, 'brt_percentage' => $request->brt_percentage, 'out_of_station' => $request->out_of_station, 'workflow_id' => $request->workflow_id]);
        }
        return 'success';
    }

    public function loanPolicySettings(Request $request)
    {
        $company_id = companyId();
        $lp = LoanPolicy::where('company_id', $company_id)->first();
        $workflows = Workflow::all();


        if (!$lp) {
            $lp = LoanPolicy::create(['annual_interest' => 0, 'maximum_allowed' => 0, 'user_id' => Auth::user()->id, 'company_id' => $company_id, 'workflow_id' => 0]);
        }
        return view('payrollsettings.loan_policy', compact('lp', 'workflows'));
    }

    public function saveLoanPolicySettings(Request $request)
    {
        // return $request->all();
        $company_id = companyId();
        $lp = LoanPolicy::where('company_id', $company_id)->first();

        if ($lp) {
            $lp->update(['annual_interest' => $request->annual_interest, 'maximum_allowed' => $request->maximum_allowed, 'user_id' => Auth::user()->id, 'workflow_id' => $request->workflow_id]);
        } else {
            LoanPolicy::create(['annual_interest' => $request->annual_interest, 'maximum_allowed' => $request->maximum_allowed, 'user_id' => Auth::user()->id, 'workflow_id' => $request->workflow_id, 'company_id' => $company_id]);
        }
        return 'success';
    }

    public function latenessPolicy(Request $request)
    {

        $lp = LatenessPolicy::find($request->lateness_policy_id);
        return $lp;
    }

    public function saveLatenessPolicy(Request $request)
    {
        $company_id = companyId();
        $lp = LatenessPolicy::updateOrCreate(['id' => $request->lateness_policy_id], ['policy_name' => $request->policy_name, 'late_minute' => $request->late_minute, 'deduction_type' => $request->deduction_type, 'deduction' => $request->deduction,
                'company_id' => $company_id, 'payroll' => $request->payroll,'specific_salary_component_type_id'=>$request->specific_salary_component_type_id]);


        return 'success';
    }

    public function deleteLatenessPolicy(Request $request)
    {
        $lp = LatenessPolicy::find($request->lateness_policy_id);
        if ($lp) {

            $lp->delete();
            return 'success';
        }
    }

    public function changeLatenessPolicyStatus(Request $request)
    {
        //this function enables user to enable and disable lateness policy
        $lp = LatenessPolicy::find($request->lateness_policy_id);
        if ($lp->status == 1) {
            $lp->update(['status' => 0]);
            return 2;
        } elseif ($lp->status == 0) {
            $lp->update(['status' => 1]);
            return 1;
        }


    }

    public function switchLatenessPolicy(Request $request)
    {
        $company_id = companyId();
        $pp = PayrollPolicy::where('company_id', $company_id)->first();
        if ($pp->use_lateness == 1) {
            $pp->update(['use_lateness' => 0]);
            return 2;
        } elseif ($pp->use_lateness == 0) {
            $pp->update(['use_lateness' => 1]);
            return 1;
        }
    }

    public function switchUseOfficePayrollPolicy(Request $request)
    {
        $company_id = companyId();
        $pp = PayrollPolicy::where('company_id', $company_id)->first();
        if ($pp->use_office == 1) {
            $pp->update(['use_office' => 0]);
            return 2;
        } elseif ($pp->use_office == 0) {
            $pp->update(['use_office' => 1]);
            return 1;
        }
    }

    public function switchUseTmsaPayrollPolicy(Request $request)
    {
        $company_id = companyId();
        $pp = PayrollPolicy::where('company_id', $company_id)->first();
        if ($pp->use_tmsa == 1) {
            $pp->update(['use_tmsa' => 0]);
            return 2;
        } elseif ($pp->use_tmsa == 0) {
            $pp->update(['use_tmsa' => 1]);
            return 1;
        }
    }

    public function switchUseProjectPayrollPolicy(Request $request)
    {
        $company_id = companyId();
        $pp = PayrollPolicy::where('company_id', $company_id)->first();
        if ($pp->use_project == 1) {
            $pp->update(['use_project' => 0]);
            return 2;
        } elseif ($pp->use_project == 0) {
            $pp->update(['use_project' => 1]);
            return 1;
        }
    }

    public function tmsaComponent(Request $request)
    {

        $tc = TmsaComponent::where('id', $request->tmsa_component_id)->with(['exemptions', 'months'])->first();
        return $tc;
    }

    public function tmsaComponents(Request $request)
    {
        $tcs = TmsaComponent::with(['exemptions', 'months'])->get();
        return view('payrollsettings.tmsa_component', compact('tcs'));
    }

    public function saveTmsaComponent(Request $request)
    {
        $company_id = companyId();

        $validator = Validator::make($request->all(), [
            'constant' => [
                'required',
                Rule::unique('tmsa_components')->where(function ($query) use ($company_id, $request) {
                    return $query->where('company_id', $company_id)
                        ->where('id', '!=', $request->tmsa_component_id);
                })
            ],
        ]);
        if ($validator->fails()) {
            return response()->json([
                $validator->errors()
            ], 401);
        }

        $tc = TmsaComponent::updateOrCreate(['id' => $request->tmsa_component_id], ['name' => $request->name, 'type' => $request->tctype, 'constant' => $request->constant, 'company_id' => $company_id, 'taxable' => $request->taxable, 'amount' => $request->amount, 'status' => 1, 'comment' => $request->comment, 'gl_code' => $request->gl_code, 'project_code' => $request->project_code, 'fixed' => $request->tcfixed, 'formula' => $request->formula, 'uses_month' => $request->uses_month, 'year' => $request->year, 'rate' => $request->rate]);


        if ($request->filled('exemptions')) {
            $no_of_exemptions = count($request->input('exemptions'));
        } else {
            $no_of_exemptions = 0;
        }
        if ($no_of_exemptions > 0) {
            $tc->exemptions()->detach();
            for ($i = 0; $i < $no_of_exemptions; $i++) {
                if ($request->exemptions[$i] != 0) {
                    $tc->exemptions()->attach($request->exemptions[$i], ['created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]);
                }

            }
        } else {
            $tc->exemptions()->detach();
        }


        if ($request->filled('months')) {
            $no_of_months = count($request->input('months'));
        } else {
            $no_of_months = 0;
        }
        if ($no_of_months > 0) {
            $tc->months()->detach();
            for ($i = 0; $i < $no_of_months; $i++) {
                if ($request->months[$i] != 0) {
                    $tc->months()->attach($request->months[$i], ['created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]);
                }

            }
        } else {
            $tc->months()->detach();
        }
        return 'success';
    }

    public function deleteTmsaComponent(Request $request)
    {
        $tc = TmsaComponent::find($request->tmsa_component_id);
        if ($tc) {
            $tc->exemptions()->detach();
            $tc->months()->detach();
            $tc->delete();
            return 'success';
        }
    }

    public function changeTmsaComponentStatus(Request $request)
    {
        $tc = TmsaComponent::find($request->tmsa_component_id);
        if ($tc->status == 1) {
            $tc->update(['status' => 0]);
            return 2;
        } elseif ($tc->status == 0) {
            $tc->update(['status' => 1]);
            return 1;
        }


    }

    public function changeTmsaComponentTaxable(Request $request)
    {
        $tc = TmsaComponent::find($request->tmsa_component_id);
        if ($tc->taxable == 1) {
            $tc->update(['taxable' => 0]);
            return 2;
        } elseif ($tc->taxable == 0) {
            $tc->update(['taxable' => 1]);
            return 1;
        }


    }


    private function downloadSSTypeTemplate(Request $request)
    {
        $ssct = SpecificSalaryComponentType::find($request->ssct_id);

        $template = \App\User::select('emp_num as Staff Id')->where(['company_id' => companyId()])->where('status', '!=', 2)->get()->toArray();
        $all_users = \App\User::where(['company_id' => companyId()])->where('status', '!=', 2)->get();
        $users = \App\User::select('name as Employee Name', 'emp_num as Staff Id')->where(['company_id' => companyId()])->where('status', '!=', 2)->get()->toArray();
        $component_category = \App\SpecificSalaryComponentType::select('name as Component Category', 'id as Id')->where('company_id', companyId())->get()->toArray();
        $option = [['option' => 'yes', 'value' => 1], ['option' => 'no', 'value' => '0']];
        $allowance = [['option' => 'allowance', 'value' => 1], ['option' => 'deduction', 'value' => '0'], ['option' => 'rebate', 'value' => '2']];
        $users_count = \App\User::where(['company_id' => companyId()])->where('status', '!=', 2)->count();
        $component_list = ['category', 'type', 'amount', 'comment', 'duration', 'taxable', 'gl_code', 'project_code', 'active', 'component_name', 'one_off'];
        return $this->exportsstypeexcel($ssct->name . 'template', $component_list, ['template' => $template, 'employees' => $users, 'option' => $option, 'allowance' => $allowance, 'category' => $component_category], $users_count, $all_users, $ssct);
    }

    private function exportsstypeexcel($worksheetname, $component_list, $data, $users_count, $all_users, $ssct)
    {
        return \Excel::create($worksheetname, function ($excel) use ($data, $component_list, $users_count, $all_users, $ssct) {
            foreach ($data as $sheetname => $realdata) {
                $excel->sheet($sheetname, function ($sheet) use ($realdata, $sheetname, $component_list, $users_count, $all_users, $ssct) {
                    $sheet->fromArray($realdata);
                    if ($sheetname == 'template') {

                        $sn = 0;

                        $cnt = count($component_list);
                        foreach (range('b', 'z') as $v) {

                            $sheet->cell($v . '1', function ($cell) use ($component_list, $sn) {
                                $cell->setValue($component_list[$sn]);

                            });
                            $sn++;

                            if ($sn == $cnt) {
                                break;
                            }
                        }


                        for ($i = 2; $i <= $users_count + 1; $i++) {
                            $sheet->cell('E' . $i, function ($cell) use ($all_users, $sheet, $i) {

                                $user = $all_users->firstWhere('emp_num', $sheet->getCell('A' . $i));
                                if ($user) {
                                    $cell->setValue($user->name);
                                }
                            });


                        }
                        for ($i = 2; $i <= $users_count + 1; $i++) {
                            $sheet->cell('B' . $i, function ($cell) use ($all_users, $sheet, $i, $ssct) {
                                $cell->setValue($ssct->name);
                            });


                        }
                        for ($i = 2; $i <= $users_count + 1; $i++) {
                            $sheet->cell('K' . $i, function ($cell) use ($all_users, $sheet, $i, $ssct) {
                                $cell->setValue($ssct->name);
                            });


                        }


                    }
                    if ($sheetname == 'option') {

                        $sheet->_parent->addNamedRange(
                            new \PHPExcel_NamedRange(
                                'sdz', $sheet->_parent->getSheet(2), "A2:A" . $sheet->_parent->getSheet(2)->getHighestRow()
                            )
                        );

                        for ($j = 2; $j <= $users_count + 1; $j++) {
                            $objValidation = $sheet->_parent->getSheet(0)->getCell("G$j")->getDataValidation();
                            $objValidation->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                            $objValidation->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                            $objValidation->setAllowBlank(false);
                            $objValidation->setShowInputMessage(true);
                            $objValidation->setShowErrorMessage(true);
                            $objValidation->setShowDropDown(true);
                            $objValidation->setErrorTitle('Input error');
                            $objValidation->setError('Value is not in list.');
                            $objValidation->setPromptTitle('Pick from list');
                            $objValidation->setPrompt('Please pick a value from the drop-down list.');
                            $objValidation->setFormula1('sdz');


                        }

                        for ($j = 2; $j <= $users_count + 1; $j++) {
                            $objValidation = $sheet->_parent->getSheet(0)->getCell("J$j")->getDataValidation();
                            $objValidation->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                            $objValidation->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                            $objValidation->setAllowBlank(false);
                            $objValidation->setShowInputMessage(true);
                            $objValidation->setShowErrorMessage(true);
                            $objValidation->setShowDropDown(true);
                            $objValidation->setErrorTitle('Input error');
                            $objValidation->setError('Value is not in list.');
                            $objValidation->setPromptTitle('Pick from list');
                            $objValidation->setPrompt('Please pick a value from the drop-down list.');
                            $objValidation->setFormula1('sdz');


                        }
                        for ($j = 2; $j <= $users_count + 1; $j++) {
                            $objValidation = $sheet->_parent->getSheet(0)->getCell("L$j")->getDataValidation();
                            $objValidation->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                            $objValidation->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                            $objValidation->setAllowBlank(false);
                            $objValidation->setShowInputMessage(true);
                            $objValidation->setShowErrorMessage(true);
                            $objValidation->setShowDropDown(true);
                            $objValidation->setErrorTitle('Input error');
                            $objValidation->setError('Value is not in list.');
                            $objValidation->setPromptTitle('Pick from list');
                            $objValidation->setPrompt('Please pick a value from the drop-down list.');
                            $objValidation->setFormula1('sdz');


                        }
                    }


                    if ($sheetname == 'allowance') {

                        $sheet->_parent->addNamedRange(
                            new \PHPExcel_NamedRange(
                                'sdy', $sheet->_parent->getSheet(3), "A2:A" . $sheet->_parent->getSheet(3)->getHighestRow()
                            )
                        );

                        for ($j = 2; $j <= $users_count + 1; $j++) {
                            $objValidation = $sheet->_parent->getSheet(0)->getCell("C$j")->getDataValidation();
                            $objValidation->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                            $objValidation->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                            $objValidation->setAllowBlank(false);
                            $objValidation->setShowInputMessage(true);
                            $objValidation->setShowErrorMessage(true);
                            $objValidation->setShowDropDown(true);
                            $objValidation->setErrorTitle('Input error');
                            $objValidation->setError('Value is not in list.');
                            $objValidation->setPromptTitle('Pick from list');
                            $objValidation->setPrompt('Please pick a value from the drop-down list.');
                            $objValidation->setFormula1('sdy');


                        }
                    }
                    if ($sheetname == 'category') {

                        $sheet->_parent->addNamedRange(
                            new \PHPExcel_NamedRange(
                                'sdx', $sheet->_parent->getSheet(4), "A2:A" . $sheet->_parent->getSheet(4)->getHighestRow()
                            )
                        );

                        for ($j = 2; $j <= $users_count + 1; $j++) {
                            $objValidation = $sheet->_parent->getSheet(0)->getCell("B$j")->getDataValidation();
                            $objValidation->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                            $objValidation->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                            $objValidation->setAllowBlank(false);
                            $objValidation->setShowInputMessage(true);
                            $objValidation->setShowErrorMessage(true);
                            $objValidation->setShowDropDown(true);
                            $objValidation->setErrorTitle('Input error');
                            $objValidation->setError('Value is not in list.');
                            $objValidation->setPromptTitle('Pick from list');
                            $objValidation->setPrompt('Please pick a value from the drop-down list.');
                            $objValidation->setFormula1('sdx');


                        }


                    }

                });
            }
        })->download('xlsx');
    }


    private function downloadeoyTemplate(Request $request)
    {
        $ssct = SpecificSalaryComponentType::find($request->ssct_id);

        $template = \App\User::select('emp_num as Staff Id')->where(['company_id' => companyId()])->where('status', '!=', 2)->get()->toArray();
        $all_users = \App\User::where(['company_id' => companyId()])->where('status', '!=', 2)->get();
        $users = \App\User::select('name as Employee Name', 'emp_num as Staff Id')->where(['company_id' => companyId()])->where('status', '!=', 2)->get()->toArray();
        $component_category = \App\SpecificSalaryComponentType::select('name as Component Category', 'id as Id')->where('company_id', companyId())->get()->toArray();
        $option = [['option' => 'yes', 'value' => 1], ['option' => 'no', 'value' => '0']];
        $allowance = [['option' => 'allowance', 'value' => 1], ['option' => 'deduction', 'value' => '0'], ['option' => 'rebate', 'value' => '2']];
        $users_count = \App\User::where(['company_id' => companyId()])->where('status', '!=', 2)->count();
        $component_list = ['category', 'type', 'amount', 'comment', 'duration', 'taxable', 'gl_code', 'project_code', 'active', 'component_name', 'one_off', 'Previous Monthly Basic Salary', 'Previous Monthly 13th Month Contribution', 'Number of Months Affected', 'Current Monthly Basic Salary', 'Current Monthly 13th Month Contribution', 'Number of Months Affected'];
        return $this->exporteoyexcel('End of Year Calculation template', $component_list, ['13th Month' => $template, 'employees' => $users, 'option' => $option, 'allowance' => $allowance, 'category' => $component_category], $users_count, $all_users, $ssct);
    }

    private function exporteoyexcel($worksheetname, $component_list, $data, $users_count, $all_users, $ssct)
    {
        return \Excel::create($worksheetname, function ($excel) use ($data, $component_list, $users_count, $all_users, $ssct) {
            foreach ($data as $sheetname => $realdata) {
                $excel->sheet($sheetname, function ($sheet) use ($realdata, $sheetname, $component_list, $users_count, $all_users, $ssct) {
                    $sheet->fromArray($realdata);
                    if ($sheetname == '13th Month') {

                        $sn = 0;

                        $cnt = count($component_list);
                        foreach (range('b', 'z') as $v) {

                            $sheet->cell($v . '1', function ($cell) use ($component_list, $sn) {
                                $cell->setValue($component_list[$sn]);

                            });
                            $sn++;

                            if ($sn == $cnt) {
                                break;
                            }
                        }


                        for ($i = 2; $i <= $users_count + 1; $i++) {
                            $sheet->cell('E' . $i, function ($cell) use ($all_users, $sheet, $i) {

                                $user = $all_users->firstWhere('emp_num', $sheet->getCell('A' . $i));
                                if ($user) {
                                    $cell->setValue($user->name);
                                }
                            });


                        }
                        for ($i = 2; $i <= $users_count + 1; $i++) {
                            $sheet->cell('B' . $i, function ($cell) use ($all_users, $sheet, $i, $ssct) {
                                $cell->setValue('13th Month Allowance');
                            });


                        }
                        for ($i = 2; $i <= $users_count + 1; $i++) {
                            $sheet->cell('C' . $i, function ($cell) use ($all_users, $sheet, $i, $ssct) {
                                $cell->setValue('allowance');
                            });


                        }
                        for ($i = 2; $i <= $users_count + 1; $i++) {
                            $sheet->cell('F' . $i, function ($cell) use ($all_users, $sheet, $i, $ssct) {
                                $cell->setValue('1');
                            });


                        }
                        for ($i = 2; $i <= $users_count + 1; $i++) {
                            $sheet->cell('G' . $i, function ($cell) use ($all_users, $sheet, $i, $ssct) {
                                $cell->setValue('no');
                            });


                        }
                        for ($i = 2; $i <= $users_count + 1; $i++) {
                            $sheet->cell('J' . $i, function ($cell) use ($all_users, $sheet, $i, $ssct) {
                                $cell->setValue('yes');
                            });


                        }
                        for ($i = 2; $i <= $users_count + 1; $i++) {
                            $sheet->cell('K' . $i, function ($cell) use ($all_users, $sheet, $i, $ssct) {
                                $cell->setValue('13th Month Allowance');
                            });


                        }
                        for ($i = 2; $i <= $users_count + 1; $i++) {
                            $sheet->cell('L' . $i, function ($cell) use ($all_users, $sheet, $i, $ssct) {
                                $cell->setValue('yes');
                            });


                        }
                        for ($i = 2; $i <= $users_count + 1; $i++) {
                            $sheet->cell('N' . $i, function ($cell) use ($all_users, $sheet, $i, $ssct) {
                                $cell->setValue("=0.0825*M{$i}");
                            });


                        }
                        for ($i = 2; $i <= $users_count + 1; $i++) {
                            $sheet->cell('Q' . $i, function ($cell) use ($all_users, $sheet, $i, $ssct) {
                                $cell->setValue("=0.0825*P{$i}");
                            });


                        }

                        for ($i = 2; $i <= $users_count + 1; $i++) {
                            $sheet->cell('D' . $i, function ($cell) use ($all_users, $sheet, $i, $ssct) {
                                $cell->setValue("=(N{$i}*O{$i})+(Q{$i}*R{$i})");
                            });


                        }


                    }
                    if ($sheetname == 'option') {

                        $sheet->_parent->addNamedRange(
                            new \PHPExcel_NamedRange(
                                'sdz', $sheet->_parent->getSheet(2), "A2:A" . $sheet->_parent->getSheet(2)->getHighestRow()
                            )
                        );

                        for ($j = 2; $j <= $users_count + 1; $j++) {
                            $objValidation = $sheet->_parent->getSheet(0)->getCell("G$j")->getDataValidation();
                            $objValidation->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                            $objValidation->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                            $objValidation->setAllowBlank(false);
                            $objValidation->setShowInputMessage(true);
                            $objValidation->setShowErrorMessage(true);
                            $objValidation->setShowDropDown(true);
                            $objValidation->setErrorTitle('Input error');
                            $objValidation->setError('Value is not in list.');
                            $objValidation->setPromptTitle('Pick from list');
                            $objValidation->setPrompt('Please pick a value from the drop-down list.');
                            $objValidation->setFormula1('sdz');


                        }

                        for ($j = 2; $j <= $users_count + 1; $j++) {
                            $objValidation = $sheet->_parent->getSheet(0)->getCell("J$j")->getDataValidation();
                            $objValidation->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                            $objValidation->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                            $objValidation->setAllowBlank(false);
                            $objValidation->setShowInputMessage(true);
                            $objValidation->setShowErrorMessage(true);
                            $objValidation->setShowDropDown(true);
                            $objValidation->setErrorTitle('Input error');
                            $objValidation->setError('Value is not in list.');
                            $objValidation->setPromptTitle('Pick from list');
                            $objValidation->setPrompt('Please pick a value from the drop-down list.');
                            $objValidation->setFormula1('sdz');


                        }
                        for ($j = 2; $j <= $users_count + 1; $j++) {
                            $objValidation = $sheet->_parent->getSheet(0)->getCell("L$j")->getDataValidation();
                            $objValidation->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                            $objValidation->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                            $objValidation->setAllowBlank(false);
                            $objValidation->setShowInputMessage(true);
                            $objValidation->setShowErrorMessage(true);
                            $objValidation->setShowDropDown(true);
                            $objValidation->setErrorTitle('Input error');
                            $objValidation->setError('Value is not in list.');
                            $objValidation->setPromptTitle('Pick from list');
                            $objValidation->setPrompt('Please pick a value from the drop-down list.');
                            $objValidation->setFormula1('sdz');


                        }
                    }


                    if ($sheetname == 'allowance') {

                        $sheet->_parent->addNamedRange(
                            new \PHPExcel_NamedRange(
                                'sdy', $sheet->_parent->getSheet(3), "A2:A" . $sheet->_parent->getSheet(3)->getHighestRow()
                            )
                        );

                        for ($j = 2; $j <= $users_count + 1; $j++) {
                            $objValidation = $sheet->_parent->getSheet(0)->getCell("C$j")->getDataValidation();
                            $objValidation->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                            $objValidation->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                            $objValidation->setAllowBlank(false);
                            $objValidation->setShowInputMessage(true);
                            $objValidation->setShowErrorMessage(true);
                            $objValidation->setShowDropDown(true);
                            $objValidation->setErrorTitle('Input error');
                            $objValidation->setError('Value is not in list.');
                            $objValidation->setPromptTitle('Pick from list');
                            $objValidation->setPrompt('Please pick a value from the drop-down list.');
                            $objValidation->setFormula1('sdy');


                        }
                    }
                    if ($sheetname == 'category') {

                        $sheet->_parent->addNamedRange(
                            new \PHPExcel_NamedRange(
                                'sdx', $sheet->_parent->getSheet(4), "A2:A" . $sheet->_parent->getSheet(4)->getHighestRow()
                            )
                        );

                        for ($j = 2; $j <= $users_count + 1; $j++) {
                            $objValidation = $sheet->_parent->getSheet(0)->getCell("B$j")->getDataValidation();
                            $objValidation->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                            $objValidation->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                            $objValidation->setAllowBlank(false);
                            $objValidation->setShowInputMessage(true);
                            $objValidation->setShowErrorMessage(true);
                            $objValidation->setShowDropDown(true);
                            $objValidation->setErrorTitle('Input error');
                            $objValidation->setError('Value is not in list.');
                            $objValidation->setPromptTitle('Pick from list');
                            $objValidation->setPrompt('Please pick a value from the drop-down list.');
                            $objValidation->setFormula1('sdx');


                        }


                    }

                });
            }
        })->download('xlsx');
    }

    private function downloadleaveTemplate(Request $request)
    {
        $ssct = SpecificSalaryComponentType::find($request->ssct_id);

        $template = \App\User::select('emp_num as Staff Id')->where(['company_id' => companyId()])->where('status', '!=', 2)->get()->toArray();
        $all_users = \App\User::where(['company_id' => companyId()])->where('status', '!=', 2)->get();
        $users = \App\User::select('name as Employee Name', 'emp_num as Staff Id')->where(['company_id' => companyId()])->where('status', '!=', 2)->get()->toArray();
        $component_category = \App\SpecificSalaryComponentType::select('name as Component Category', 'id as Id')->where('company_id', companyId())->get()->toArray();
        $option = [['option' => 'yes', 'value' => 1], ['option' => 'no', 'value' => '0']];
        $allowance = [['option' => 'allowance', 'value' => 1], ['option' => 'deduction', 'value' => '0'], ['option' => 'rebate', 'value' => '2']];
        $users_count = \App\User::where(['company_id' => companyId()])->where('status', '!=', 2)->count();
        $component_list = ['category', 'type', 'amount', 'comment', 'duration', 'taxable', 'gl_code', 'project_code', 'active', 'component_name', 'one_off', 'Previous Monthly Basic Salary', 'Previous Monthly Leave Contribution', 'Number of Months Affected', 'Current Monthly Basic Salary', 'Current Monthly Leave Contribution', 'Number of Months Affected'];
        return $this->exportleaveexcel('Leave Calculation template', $component_list, ['Leave' => $template, 'employees' => $users, 'option' => $option, 'allowance' => $allowance, 'category' => $component_category], $users_count, $all_users, $ssct);
    }

    private function exportleaveexcel($worksheetname, $component_list, $data, $users_count, $all_users, $ssct)
    {
        return \Excel::create($worksheetname, function ($excel) use ($data, $component_list, $users_count, $all_users, $ssct) {
            foreach ($data as $sheetname => $realdata) {
                $excel->sheet($sheetname, function ($sheet) use ($realdata, $sheetname, $component_list, $users_count, $all_users, $ssct) {
                    $sheet->fromArray($realdata);
                    if ($sheetname == 'Leave') {

                        $sn = 0;

                        $cnt = count($component_list);
                        foreach (range('b', 'z') as $v) {

                            $sheet->cell($v . '1', function ($cell) use ($component_list, $sn) {
                                $cell->setValue($component_list[$sn]);

                            });
                            $sn++;

                            if ($sn == $cnt) {
                                break;
                            }
                        }


                        for ($i = 2; $i <= $users_count + 1; $i++) {
                            $sheet->cell('E' . $i, function ($cell) use ($all_users, $sheet, $i) {

                                $user = $all_users->firstWhere('emp_num', $sheet->getCell('A' . $i));
                                if ($user) {
                                    $cell->setValue($user->name);
                                }
                            });


                        }
                        for ($i = 2; $i <= $users_count + 1; $i++) {
                            $sheet->cell('B' . $i, function ($cell) use ($all_users, $sheet, $i, $ssct) {
                                $cell->setValue('Leave Allowance');
                            });


                        }
                        for ($i = 2; $i <= $users_count + 1; $i++) {
                            $sheet->cell('C' . $i, function ($cell) use ($all_users, $sheet, $i, $ssct) {
                                $cell->setValue('allowance');
                            });


                        }
                        for ($i = 2; $i <= $users_count + 1; $i++) {
                            $sheet->cell('F' . $i, function ($cell) use ($all_users, $sheet, $i, $ssct) {
                                $cell->setValue('1');
                            });


                        }
                        for ($i = 2; $i <= $users_count + 1; $i++) {
                            $sheet->cell('G' . $i, function ($cell) use ($all_users, $sheet, $i, $ssct) {
                                $cell->setValue('no');
                            });


                        }
                        for ($i = 2; $i <= $users_count + 1; $i++) {
                            $sheet->cell('J' . $i, function ($cell) use ($all_users, $sheet, $i, $ssct) {
                                $cell->setValue('yes');
                            });


                        }
                        for ($i = 2; $i <= $users_count + 1; $i++) {
                            $sheet->cell('K' . $i, function ($cell) use ($all_users, $sheet, $i, $ssct) {
                                $cell->setValue('Leave Allowance');
                            });


                        }
                        for ($i = 2; $i <= $users_count + 1; $i++) {
                            $sheet->cell('L' . $i, function ($cell) use ($all_users, $sheet, $i, $ssct) {
                                $cell->setValue('yes');
                            });


                        }
                        for ($i = 2; $i <= $users_count + 1; $i++) {
                            $sheet->cell('N' . $i, function ($cell) use ($all_users, $sheet, $i, $ssct) {
                                $cell->setValue("=0.1*M{$i})");
                            });


                        }
                        for ($i = 2; $i <= $users_count + 1; $i++) {
                            $sheet->cell('Q' . $i, function ($cell) use ($all_users, $sheet, $i, $ssct) {
                                $cell->setValue("=0.1*P{$i}");
                            });


                        }

                        for ($i = 2; $i <= $users_count + 1; $i++) {
                            $sheet->cell('D' . $i, function ($cell) use ($all_users, $sheet, $i, $ssct) {
                                $cell->setValue("=(N{$i}*O{$i})+(Q{$i}*R{$i})");
                            });


                        }


                    }
                    if ($sheetname == 'option') {

                        $sheet->_parent->addNamedRange(
                            new \PHPExcel_NamedRange(
                                'sdz', $sheet->_parent->getSheet(2), "A2:A" . $sheet->_parent->getSheet(2)->getHighestRow()
                            )
                        );

                        for ($j = 2; $j <= $users_count + 1; $j++) {
                            $objValidation = $sheet->_parent->getSheet(0)->getCell("G$j")->getDataValidation();
                            $objValidation->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                            $objValidation->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                            $objValidation->setAllowBlank(false);
                            $objValidation->setShowInputMessage(true);
                            $objValidation->setShowErrorMessage(true);
                            $objValidation->setShowDropDown(true);
                            $objValidation->setErrorTitle('Input error');
                            $objValidation->setError('Value is not in list.');
                            $objValidation->setPromptTitle('Pick from list');
                            $objValidation->setPrompt('Please pick a value from the drop-down list.');
                            $objValidation->setFormula1('sdz');


                        }

                        for ($j = 2; $j <= $users_count + 1; $j++) {
                            $objValidation = $sheet->_parent->getSheet(0)->getCell("J$j")->getDataValidation();
                            $objValidation->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                            $objValidation->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                            $objValidation->setAllowBlank(false);
                            $objValidation->setShowInputMessage(true);
                            $objValidation->setShowErrorMessage(true);
                            $objValidation->setShowDropDown(true);
                            $objValidation->setErrorTitle('Input error');
                            $objValidation->setError('Value is not in list.');
                            $objValidation->setPromptTitle('Pick from list');
                            $objValidation->setPrompt('Please pick a value from the drop-down list.');
                            $objValidation->setFormula1('sdz');


                        }
                        for ($j = 2; $j <= $users_count + 1; $j++) {
                            $objValidation = $sheet->_parent->getSheet(0)->getCell("L$j")->getDataValidation();
                            $objValidation->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                            $objValidation->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                            $objValidation->setAllowBlank(false);
                            $objValidation->setShowInputMessage(true);
                            $objValidation->setShowErrorMessage(true);
                            $objValidation->setShowDropDown(true);
                            $objValidation->setErrorTitle('Input error');
                            $objValidation->setError('Value is not in list.');
                            $objValidation->setPromptTitle('Pick from list');
                            $objValidation->setPrompt('Please pick a value from the drop-down list.');
                            $objValidation->setFormula1('sdz');


                        }
                    }


                    if ($sheetname == 'allowance') {

                        $sheet->_parent->addNamedRange(
                            new \PHPExcel_NamedRange(
                                'sdy', $sheet->_parent->getSheet(3), "A2:A" . $sheet->_parent->getSheet(3)->getHighestRow()
                            )
                        );

                        for ($j = 2; $j <= $users_count + 1; $j++) {
                            $objValidation = $sheet->_parent->getSheet(0)->getCell("C$j")->getDataValidation();
                            $objValidation->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                            $objValidation->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                            $objValidation->setAllowBlank(false);
                            $objValidation->setShowInputMessage(true);
                            $objValidation->setShowErrorMessage(true);
                            $objValidation->setShowDropDown(true);
                            $objValidation->setErrorTitle('Input error');
                            $objValidation->setError('Value is not in list.');
                            $objValidation->setPromptTitle('Pick from list');
                            $objValidation->setPrompt('Please pick a value from the drop-down list.');
                            $objValidation->setFormula1('sdy');


                        }
                    }
                    if ($sheetname == 'category') {

                        $sheet->_parent->addNamedRange(
                            new \PHPExcel_NamedRange(
                                'sdx', $sheet->_parent->getSheet(4), "A2:A" . $sheet->_parent->getSheet(4)->getHighestRow()
                            )
                        );

                        for ($j = 2; $j <= $users_count + 1; $j++) {
                            $objValidation = $sheet->_parent->getSheet(0)->getCell("B$j")->getDataValidation();
                            $objValidation->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                            $objValidation->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                            $objValidation->setAllowBlank(false);
                            $objValidation->setShowInputMessage(true);
                            $objValidation->setShowErrorMessage(true);
                            $objValidation->setShowDropDown(true);
                            $objValidation->setErrorTitle('Input error');
                            $objValidation->setError('Value is not in list.');
                            $objValidation->setPromptTitle('Pick from list');
                            $objValidation->setPrompt('Please pick a value from the drop-down list.');
                            $objValidation->setFormula1('sdx');


                        }


                    }

                });
            }
        })->download('xlsx');
    }

    private function downloadSSTemplate(Request $request)
    {

        $template = ['staff id', 'category', 'type', 'amount', 'comment', 'duration', 'taxable', 'gl_code', 'project_code', 'active', 'component_name', 'one_off','taxable_type'];
        $users = \App\User::select('name as Employee Name', 'emp_num as Staff Id')->where('status', '!=', 2)->where('company_id', companyId())->get()->toArray();
        $component_category = \App\SpecificSalaryComponentType::select('name as Component Category', 'id as Id')->where('company_id', companyId())->get()->toArray();
        $option = [['option' => 'yes', 'value' => 1], ['option' => 'no', 'value' => '0']];
        $taxable_type = [['option' => 'monthly', 'value' => 1], ['option' => 'annual', 'value' => '2']];
        $allowance = [['option' => 'allowance', 'value' => 1], ['option' => 'deduction', 'value' => '0'], ['option' => 'rebate', 'value' => '2']];

        return $this->exportexcel('specific_salary_components_upload_template', ['template' => $template, 'employees' => $users, 'option' => $option, 'allowance' => $allowance, 'category' => $component_category,'taxable_type'=>$taxable_type]);

    }

    private function exportexcel($worksheetname, $data)
    {
        return \Excel::create($worksheetname, function ($excel) use ($data) {
            foreach ($data as $sheetname => $realdata) {
                $excel->sheet($sheetname, function ($sheet) use ($realdata, $sheetname) {

                    $sheet->fromArray($realdata);
                    if ($sheetname == 'taxable_type') {

                        $sheet->_parent->addNamedRange(
                            new \PHPExcel_NamedRange(
                                'se', $sheet->_parent->getSheet(1), "A2:A" . $sheet->_parent->getSheet(1)->getHighestRow()
                            )
                        );

                        for ($j = 2; $j <= 100; $j++) {
                            $objValidation = $sheet->_parent->getSheet(0)->getCell("M$j")->getDataValidation();
                            $objValidation->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                            $objValidation->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                            $objValidation->setAllowBlank(false);
                            $objValidation->setShowInputMessage(true);
                            $objValidation->setShowErrorMessage(true);
                            $objValidation->setShowDropDown(true);
                            $objValidation->setErrorTitle('Input error');
                            $objValidation->setError('Value is not in list.');
                            $objValidation->setPromptTitle('Pick from list');
                            $objValidation->setPrompt('Please pick a value from the drop-down list.');
                            $objValidation->setFormula1('se');


                        }
                    }
                    if ($sheetname == 'employees') {

                        $sheet->_parent->addNamedRange(
                            new \PHPExcel_NamedRange(
                                'sd', $sheet->_parent->getSheet(1), "B2:B" . $sheet->_parent->getSheet(1)->getHighestRow()
                            )
                        );

                        for ($j = 2; $j <= 100; $j++) {
                            $objValidation = $sheet->_parent->getSheet(0)->getCell("A$j")->getDataValidation();
                            $objValidation->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                            $objValidation->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                            $objValidation->setAllowBlank(false);
                            $objValidation->setShowInputMessage(true);
                            $objValidation->setShowErrorMessage(true);
                            $objValidation->setShowDropDown(true);
                            $objValidation->setErrorTitle('Input error');
                            $objValidation->setError('Value is not in list.');
                            $objValidation->setPromptTitle('Pick from list');
                            $objValidation->setPrompt('Please pick a value from the drop-down list.');
                            $objValidation->setFormula1('sd');


                        }
                    }
                    if ($sheetname == 'option') {

                        $sheet->_parent->addNamedRange(
                            new \PHPExcel_NamedRange(
                                'sdz', $sheet->_parent->getSheet(2), "A2:A" . $sheet->_parent->getSheet(2)->getHighestRow()
                            )
                        );

                        for ($j = 2; $j <= 100; $j++) {
                            $objValidation = $sheet->_parent->getSheet(0)->getCell("G$j")->getDataValidation();
                            $objValidation->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                            $objValidation->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                            $objValidation->setAllowBlank(false);
                            $objValidation->setShowInputMessage(true);
                            $objValidation->setShowErrorMessage(true);
                            $objValidation->setShowDropDown(true);
                            $objValidation->setErrorTitle('Input error');
                            $objValidation->setError('Value is not in list.');
                            $objValidation->setPromptTitle('Pick from list');
                            $objValidation->setPrompt('Please pick a value from the drop-down list.');
                            $objValidation->setFormula1('sdz');


                        }

                        for ($j = 2; $j <= 100; $j++) {
                            $objValidation = $sheet->_parent->getSheet(0)->getCell("J$j")->getDataValidation();
                            $objValidation->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                            $objValidation->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                            $objValidation->setAllowBlank(false);
                            $objValidation->setShowInputMessage(true);
                            $objValidation->setShowErrorMessage(true);
                            $objValidation->setShowDropDown(true);
                            $objValidation->setErrorTitle('Input error');
                            $objValidation->setError('Value is not in list.');
                            $objValidation->setPromptTitle('Pick from list');
                            $objValidation->setPrompt('Please pick a value from the drop-down list.');
                            $objValidation->setFormula1('sdz');


                        }
                        for ($j = 2; $j <= 100; $j++) {
                            $objValidation = $sheet->_parent->getSheet(0)->getCell("L$j")->getDataValidation();
                            $objValidation->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                            $objValidation->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                            $objValidation->setAllowBlank(false);
                            $objValidation->setShowInputMessage(true);
                            $objValidation->setShowErrorMessage(true);
                            $objValidation->setShowDropDown(true);
                            $objValidation->setErrorTitle('Input error');
                            $objValidation->setError('Value is not in list.');
                            $objValidation->setPromptTitle('Pick from list');
                            $objValidation->setPrompt('Please pick a value from the drop-down list.');
                            $objValidation->setFormula1('sdz');


                        }
                    }


                    if ($sheetname == 'allowance') {

                        $sheet->_parent->addNamedRange(
                            new \PHPExcel_NamedRange(
                                'sdy', $sheet->_parent->getSheet(3), "A2:A" . $sheet->_parent->getSheet(3)->getHighestRow()
                            )
                        );

                        for ($j = 2; $j <= 100; $j++) {
                            $objValidation = $sheet->_parent->getSheet(0)->getCell("C$j")->getDataValidation();
                            $objValidation->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                            $objValidation->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                            $objValidation->setAllowBlank(false);
                            $objValidation->setShowInputMessage(true);
                            $objValidation->setShowErrorMessage(true);
                            $objValidation->setShowDropDown(true);
                            $objValidation->setErrorTitle('Input error');
                            $objValidation->setError('Value is not in list.');
                            $objValidation->setPromptTitle('Pick from list');
                            $objValidation->setPrompt('Please pick a value from the drop-down list.');
                            $objValidation->setFormula1('sdy');


                        }
                    }
                    if ($sheetname == 'category') {

                        $sheet->_parent->addNamedRange(
                            new \PHPExcel_NamedRange(
                                'sdx', $sheet->_parent->getSheet(4), "A2:A" . $sheet->_parent->getSheet(4)->getHighestRow()
                            )
                        );

                        for ($j = 2; $j <= 100; $j++) {
                            $objValidation = $sheet->_parent->getSheet(0)->getCell("B$j")->getDataValidation();
                            $objValidation->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                            $objValidation->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                            $objValidation->setAllowBlank(false);
                            $objValidation->setShowInputMessage(true);
                            $objValidation->setShowErrorMessage(true);
                            $objValidation->setShowDropDown(true);
                            $objValidation->setErrorTitle('Input error');
                            $objValidation->setError('Value is not in list.');
                            $objValidation->setPromptTitle('Pick from list');
                            $objValidation->setPrompt('Please pick a value from the drop-down list.');
                            $objValidation->setFormula1('sdx');


                        }


                    }


                });
            }
        })->download('xlsx');
    }

    public function importSpecificSalaryComponent(Request $request)
    {
        $document = $request->file('sscs');
        $company_id = companyId();
        $company = Company::find($company_id);
        //$document->getRealPath();
        // return $document->getClientOriginalName();
        // $document->getClientOriginalExtension();
        // $document->getSize();
        // $document->getMimeType();


        if ($request->hasFile('sscs')) {

            $datas = \Excel::load($request->file('sscs')->getrealPath(), function ($reader) {
                $reader->noHeading()->skipRows(1);
            })->get();


            foreach ($datas[0] as $data) {
                // dd($data[0]);
                if ($data[0] && $data[3] > 0) {
                    $data[0];
                    $user = \App\User::where('emp_num', $data[0])->first();
                    //  $type=  ($data[2]=='allowance') ? 1 : 0 ;
                    $type = ($data[2] == 'allowance') ? 1 : ($data[2] == 'deduction' ? 0 : ($data[2] == 'rebate' ? 2 : 0));
                    $active = ($data[9] == 'yes') ? 1 : 0;
                    $taxable = ($data[6] == 'yes') ? 1 : 0;
                    $one_off = ($data[11] == 'yes') ? 1 : 0;
                    $name = ($data[10] == '') ? $data[1] : $data[10];
                    $duration = ($data[5] == '') ? 1 : $data[5];
                    $taxable_type = ($data[12] == 'annual') ? 2 : 1;


                    $category = \App\SpecificSalaryComponentType::where(['name' => $data[1], 'company_id' => $company->id])->first();
                    if ($user && $category) {
                        $sscs = \App\SpecificSalaryComponent::create(['name' => $name, 'gl_code' => $data[7], 'project_code' => $data[8], 'type' => $type, 'comment' => $data[4], 'duration' => $duration, 'grants' => 0, 'status' => $active, 'company_id' => $company->id, 'amount' => $data[3], 'taxable' => $taxable, 'specific_salary_component_type_id' => $category->id, 'emp_id' => $user->id, 'one_off' => $one_off,'taxable_type'=>$taxable_type]);
                    }


                }

            }


            //   $request->session()->flash('success', 'Import was successful!');

            // return back();
            return 'success';
        }

    }

    private function downloadProjectSalaryTemplate(Request $request)
    {

        $template = ['component name', 'type', 'fixed', 'constant', 'formula', 'amount', 'comment', 'uses_days', 'taxable', 'gl_code', 'project_code', 'active', 'category', 'uses_anniversary','for_probationers'];

        $component_category = \App\PaceSalaryCategory::select('name as Component Category', 'id as Id')->where('company_id', companyId())->get()->toArray();
        $option = [['option' => 'yes', 'value' => 1], ['option' => 'no', 'value' => '0']];
        $allowance = [['option' => 'allowance', 'value' => 1], ['option' => 'deduction', 'value' => '0']];

        return $this->exportPSCexcel('template', ['template' => $template, 'option' => $option, 'allowance' => $allowance, 'category' => $component_category]);

    }

    private function exportPSCexcel($worksheetname, $data)
    {
        return \Excel::create($worksheetname, function ($excel) use ($data) {
            foreach ($data as $sheetname => $realdata) {
                $excel->sheet($sheetname, function ($sheet) use ($realdata, $sheetname) {

                    $sheet->fromArray($realdata);
                    if ($sheetname == 'category') {

                        $sheet->_parent->addNamedRange(
                            new \PHPExcel_NamedRange(
                                'sd', $sheet->_parent->getSheet(3), "A2:A50" . $sheet->_parent->getSheet(1)->getHighestRow()
                            )
                        );

                        for ($j = 2; $j <= 100; $j++) {
                            $objValidation = $sheet->_parent->getSheet(0)->getCell("M$j")->getDataValidation();
                            $objValidation->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                            $objValidation->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                            $objValidation->setAllowBlank(false);
                            $objValidation->setShowInputMessage(true);
                            $objValidation->setShowErrorMessage(true);
                            $objValidation->setShowDropDown(true);
                            $objValidation->setErrorTitle('Input error');
                            $objValidation->setError('Value is not in list.');
                            $objValidation->setPromptTitle('Pick from list');
                            $objValidation->setPrompt('Please pick a value from the drop-down list.');
                            $objValidation->setFormula1('sd');


                        }
                    }

                    if ($sheetname == 'option') {

                        $sheet->_parent->addNamedRange(
                            new \PHPExcel_NamedRange(
                                'sdz', $sheet->_parent->getSheet(1), "A2:A" . $sheet->_parent->getSheet(1)->getHighestRow()
                            )
                        );

                        for ($j = 2; $j <= 100; $j++) {
                            $objValidation = $sheet->_parent->getSheet(0)->getCell("C$j")->getDataValidation();
                            $objValidation->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                            $objValidation->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                            $objValidation->setAllowBlank(false);
                            $objValidation->setShowInputMessage(true);
                            $objValidation->setShowErrorMessage(true);
                            $objValidation->setShowDropDown(true);
                            $objValidation->setErrorTitle('Input error');
                            $objValidation->setError('Value is not in list.');
                            $objValidation->setPromptTitle('Pick from list');
                            $objValidation->setPrompt('Please pick a value from the drop-down list.');
                            $objValidation->setFormula1('sdz');


                        }

                        for ($j = 2; $j <= 100; $j++) {
                            $objValidation = $sheet->_parent->getSheet(0)->getCell("H$j")->getDataValidation();
                            $objValidation->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                            $objValidation->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                            $objValidation->setAllowBlank(false);
                            $objValidation->setShowInputMessage(true);
                            $objValidation->setShowErrorMessage(true);
                            $objValidation->setShowDropDown(true);
                            $objValidation->setErrorTitle('Input error');
                            $objValidation->setError('Value is not in list.');
                            $objValidation->setPromptTitle('Pick from list');
                            $objValidation->setPrompt('Please pick a value from the drop-down list.');
                            $objValidation->setFormula1('sdz');


                        }

                        for ($j = 2; $j <= 100; $j++) {
                            $objValidation = $sheet->_parent->getSheet(0)->getCell("I$j")->getDataValidation();
                            $objValidation->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                            $objValidation->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                            $objValidation->setAllowBlank(false);
                            $objValidation->setShowInputMessage(true);
                            $objValidation->setShowErrorMessage(true);
                            $objValidation->setShowDropDown(true);
                            $objValidation->setErrorTitle('Input error');
                            $objValidation->setError('Value is not in list.');
                            $objValidation->setPromptTitle('Pick from list');
                            $objValidation->setPrompt('Please pick a value from the drop-down list.');
                            $objValidation->setFormula1('sdz');


                        }

                        for ($j = 2; $j <= 100; $j++) {
                            $objValidation = $sheet->_parent->getSheet(0)->getCell("L$j")->getDataValidation();
                            $objValidation->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                            $objValidation->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                            $objValidation->setAllowBlank(false);
                            $objValidation->setShowInputMessage(true);
                            $objValidation->setShowErrorMessage(true);
                            $objValidation->setShowDropDown(true);
                            $objValidation->setErrorTitle('Input error');
                            $objValidation->setError('Value is not in list.');
                            $objValidation->setPromptTitle('Pick from list');
                            $objValidation->setPrompt('Please pick a value from the drop-down list.');
                            $objValidation->setFormula1('sdz');


                        }
                        for ($j = 2; $j <= 100; $j++) {
                            $objValidation = $sheet->_parent->getSheet(0)->getCell("N$j")->getDataValidation();
                            $objValidation->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                            $objValidation->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                            $objValidation->setAllowBlank(false);
                            $objValidation->setShowInputMessage(true);
                            $objValidation->setShowErrorMessage(true);
                            $objValidation->setShowDropDown(true);
                            $objValidation->setErrorTitle('Input error');
                            $objValidation->setError('Value is not in list.');
                            $objValidation->setPromptTitle('Pick from list');
                            $objValidation->setPrompt('Please pick a value from the drop-down list.');
                            $objValidation->setFormula1('sdz');
                        }
                        for ($j = 2; $j <= 100; $j++) {
                            $objValidation = $sheet->_parent->getSheet(0)->getCell("O$j")->getDataValidation();
                            $objValidation->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                            $objValidation->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                            $objValidation->setAllowBlank(false);
                            $objValidation->setShowInputMessage(true);
                            $objValidation->setShowErrorMessage(true);
                            $objValidation->setShowDropDown(true);
                            $objValidation->setErrorTitle('Input error');
                            $objValidation->setError('Value is not in list.');
                            $objValidation->setPromptTitle('Pick from list');
                            $objValidation->setPrompt('Please pick a value from the drop-down list.');
                            $objValidation->setFormula1('sdz');
                        }
                    }


                    if ($sheetname == 'allowance') {

                        $sheet->_parent->addNamedRange(
                            new \PHPExcel_NamedRange(
                                'sdy', $sheet->_parent->getSheet(2), "A2:A" . $sheet->_parent->getSheet(1)->getHighestRow()
                            )
                        );

                        for ($j = 2; $j <= 100; $j++) {
                            $objValidation = $sheet->_parent->getSheet(0)->getCell("B$j")->getDataValidation();
                            $objValidation->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                            $objValidation->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                            $objValidation->setAllowBlank(false);
                            $objValidation->setShowInputMessage(true);
                            $objValidation->setShowErrorMessage(true);
                            $objValidation->setShowDropDown(true);
                            $objValidation->setErrorTitle('Input error');
                            $objValidation->setError('Value is not in list.');
                            $objValidation->setPromptTitle('Pick from list');
                            $objValidation->setPrompt('Please pick a value from the drop-down list.');
                            $objValidation->setFormula1('sdy');


                        }
                    }


                });
            }
        })->download('xlsx');
    }

    public function downloadAllTimesheetUploadTemplate(Request $request)
    {
        $company_id = companyId();
        $template = \App\User::select('name as Employee Name', 'emp_num as Staff Id')->where('status', '!=', 2)->where('payroll_type', 'project')->whereHas('project_salary_category', function ($query) use ($company_id) {
            $query->where(['uses_timesheet' => 1, 'pace_salary_categories.company_id' => $company_id]);
        })->get()->toArray();
        $component_list = \App\PaceSalaryComponent::select('constant')->distinct()->where(['uses_days' => 1, 'company_id' => $company_id])->pluck('constant');

        //   for ($i=0; $i < count($component_list); $i++) {
        //     $template['new'][$i+1]=$component_list[$i];
        //   }

        $users = \App\User::select('name as Employee Name', 'emp_num as Staff Id')->whereHas('project_salary_category', function ($query) use ($company_id) {
            $query->where(['uses_timesheet' => 1, 'pace_salary_categories.company_id' => $company_id]);
        })->get()->toArray();

        $components = \App\PaceSalaryComponent::select('constant as Component Constant', 'name as Component name')->where(['company_id' => $company_id, 'uses_days' => 1])->distinct()->get()->toArray();

        return $this->exporttimesheetexcel('project_staff_timesheet_template', ['template' => $template, 'users' => $users, 'components' => $components], $component_list);
    }

    public function downloadTimesheetUploadTemplate(Request $request)
    {
        $template = \App\User::select('name as Employee Name', 'emp_num as Staff Id')->where('status', '!=', 2)->where(['payroll_type' => 'project', 'project_salary_category_id' => $request->salary_category_id])->get()->toArray();
        $component_list = \App\PaceSalaryComponent::where(['pace_salary_category_id' => $request->salary_category_id, 'uses_days' => 1])->pluck('constant');
        $salary_category = \App\PaceSalaryCategory::find($request->salary_category_id);
        //   for ($i=0; $i < count($component_list); $i++) {
        //     $template['new'][$i+1]=$component_list[$i];
        //   }

        $users = \App\User::select('name as Employee Name', 'emp_num as Staff Id')->where(['payroll_type' => 'project', 'project_salary_category_id' => $request->salary_category_id])->get()->toArray();

        $components = \App\PaceSalaryComponent::select('constant as Component Constant', 'name as Component name')->where(['pace_salary_category_id' => $request->salary_category_id, 'uses_days' => 1])->get()->toArray();

        return $this->exporttimesheetexcel($salary_category->name . "(" . $salary_category->basic_salary . ")", ['template' => $template, 'users' => $users, 'components' => $components], $component_list);
    }

    private function exporttimesheetexcel($worksheetname, $data, $component_list)
    {
        return \Excel::create($worksheetname, function ($excel) use ($data, $component_list) {
            foreach ($data as $sheetname => $realdata) {
                $excel->sheet($sheetname, function ($sheet) use ($realdata, $sheetname, $component_list) {
                    $sheet->fromArray($realdata);
                    if ($sheetname == 'template') {

                        $sn = 0;

                        $cnt = count($component_list);
                        foreach (range('c', 'z') as $v) {

                            $sheet->cell($v . '1', function ($cell) use ($component_list, $sn) {
                                $cell->setValue($component_list[$sn]);

                            });
                            $sn++;

                            if ($sn == $cnt) {
                                break;
                            }
                        }

                    }

                });
            }
        })->download('xlsx');
    }

    public function importProjectUserTimesheets(Request $request)
    {
        if ($request->hasFile('timesheet_template')) {
            $date = date('Y-m-d', strtotime('01-' . $request->month));

            $datas = \Excel::load($request->file('timesheet_template')->getrealPath(), function ($reader) {
                $reader->noHeading();
                // $reader->formatDates(true, 'Y-m-d');
            })->get();

            $components = [];
            //   $user= new \App\User();

            foreach ($datas[0] as $dkey => $data) {

                if ($dkey == 0) {
                    foreach ($data as $key => $value) {
                        if ($key != 0 || $key != 1) {
                            $components[$key] = $value;
                        }
                    }

                } else {
                    $user = \App\User::where('emp_num', $data[1])->first();
                    if ($user) {
                        foreach ($data as $key => $value) {
                            if ($key == 0) {

                            } elseif ($key == 1) {


                            } else {

                                if ($user->project_salary_category_id > 0 && $user->payroll_type == 'project') {
                                    $component = \App\PaceSalaryComponent::where(['constant' => $components[$key], 'pace_salary_category_id' => $user->project_salary_category_id])->first();
                                    if ($component && $value != "") {
                                        $timesheet = \App\ProjectSalaryTimesheet::updateOrCreate(['user_id' => $user->id, 'for' => $date, 'pace_salary_component_id' => $component->id], ['user_id' => $user->id, 'for' => $date, 'pace_salary_component_id' => $component->id, 'days' => $value]);
                                    }


                                }


                            }
                        }
                    }
                }


            }
        }
        return 'success';
    }

    public function downloadTMSAScheduleUploadTemplate(Request $request)
    {
        $template = \App\User::select('name as Employee Name', 'emp_num as Staff Id')->where('status', '!=', 2)->where(['company_id' => companyId(), 'payroll_type' => 'tmsa'])->get()->toArray();
        $component_list = ['day_rate_onshore', 'day_rate_offshore', 'paid_off_time_rate', 'days_worked_offshore', 'days_worked_onshore', 'paid_off_day', 'living_allowance', 'transport_allowance', 'extra_addition', 'brt_days', 'extra_deduction', 'days_out_of_station'];

        return $this->exportTMSAScheduleExcel('template', ['template' => $template], $component_list);
    }

    private function exportTMSAScheduleExcel($worksheetname, $data, $component_list)
    {
        return \Excel::create($worksheetname, function ($excel) use ($data, $component_list) {
            foreach ($data as $sheetname => $realdata) {
                $excel->sheet($sheetname, function ($sheet) use ($realdata, $sheetname, $component_list) {
                    $sheet->fromArray($realdata);
                    if ($sheetname == 'template') {

                        $sn = 0;

                        $cnt = count($component_list);
                        foreach (range('c', 'z') as $v) {

                            $sheet->cell($v . '1', function ($cell) use ($component_list, $sn) {
                                $cell->setValue($component_list[$sn]);

                            });
                            $sn++;

                            if ($sn == $cnt) {
                                break;
                            }
                        }

                    }

                });
            }
        })->download('xlsx');
    }

    public function importTMSASchedule(Request $request)
    {
        $document = $request->file('template');
        $company_id = companyId();
        $company = Company::find($company_id);

        if ($request->hasFile('template')) {

            $datas = \Excel::load($request->file('template')->getrealPath(), function ($reader) {
                $reader->noHeading()->skipRows(1);
            })->get();


            foreach ($datas as $data) {
                // dd($data[0]);
                if ($data[0]) {

                    $user = \App\User::where(['emp_num' => $data[1]])->first();
                    $date = date('Y-m-d', strtotime('01-' . $request->for));
                    if ($user) {
                        $tmsa_schedule = TmsaSchedule::where(['for' => $date, 'user_id' => $user->id])->first();

                        if ($tmsa_schedule) {
                            $tmsa_schedule->update(['day_rate_onshore' => $data[2], 'day_rate_offshore' => $data[3], 'paid_off_time_rate' => $data[4], 'days_worked_offshore' => $data[5], 'days_worked_onshore' => $data[6], 'paid_off_day' => $data[7], 'living_allowance' => $data[8], 'transport_allowance' => $data[9], 'extra_addition' => $data[10], 'brt_days' => $data[11], 'extra_deduction' => $data[12], 'days_out_of_station' => $data[13]]);


                        } else {
                            $tmsa = TmsaSchedule::create(['for' => $date, 'user_id' => $user->id, 'day_rate_onshore' => $data[2], 'day_rate_offshore' => $data[3], 'paid_off_time_rate' => $data[4], 'days_worked_offshore' => $data[5], 'days_worked_onshore' => $data[6], 'paid_off_day' => $data[7], 'living_allowance' => $data[8], 'transport_allowance' => $data[9], 'extra_addition' => $data[10], 'brt_days' => $data[11], 'extra_deduction' => $data[12], 'days_out_of_station' => $data[13], 'company_id' => $company_id]);
                        }
                    }

                }

            }

            return 'success';
        }

    }

    public function importProjectSalaryComponent(Request $request)
    {

        $document = $request->file('project_template');
        $company_id = companyId();
        $company = Company::find($company_id);
        // $count=[];
        //$document->getRealPath();
        // return $document->getClientOriginalName();
        // $document->getClientOriginalExtension();
        // $document->getSize();
        // $document->getMimeType();


        if ($request->hasFile('project_template')) {

            $datas = \Excel::load($request->file('project_template')->getrealPath(), function ($reader) {
                $reader->noHeading()->skipRows(1);
            })->get();


            foreach ($datas[0] as $data) {
                // dd($data[0]);
                if ($data[0]) {

                    try {
                        $type = ($data[1] == 'allowance') ? 1 : 0;
                        $active = ($data[11] == 'yes') ? 1 : 0;
                        $taxable = ($data[8] == 'yes') ? 1 : 0;
                        $fixed = ($data[2] == 'yes') ? 1 : 0;
                        $uses_days = ($data[7] == 'yes') ? 1 : 0;
                        $uses_anniversary = ($data[13] == 'yes') ? 1 : 0;
                        $uses_probation = ($data[14] == 'yes') ? 1 : 0;
                        $category = \App\PaceSalaryCategory::where(['name' => $data[12], 'company_id' => $company->id])->first();

                        if ($category && $company) {
                            $psc = \App\PaceSalaryComponent::updateOrCreate(['constant' => $data[3], 'company_id' => $company_id, 'pace_salary_category_id' => $category->id], ['name' => $data[0], 'constant' => $data[3], 'gl_code' => $data[9], 'project_code' => $data[10], 'type' => $type, 'formula' => $data[4], 'comment' => $data[6], 'fixed' => $fixed, 'status' => $active, 'company_id' => $company->id, 'amount' => $data[5], 'taxable' => $taxable, 'pace_salary_category_id' => $category->id, 'uses_days' => $uses_days, 'uses_anniversary' => $uses_anniversary,'uses_probation'=>$uses_probation]);
                            //   $count[]=$psc;

                        }
                    } catch (\Exception $e) {
                        return 'failed';
                    }


                }

            }


            //   $request->session()->flash('success', 'Import was successful!');

            // return back();
            // return $count;
            return 'success';
        }

    }

    public function importUserCategory(Request $request)
    {
        $document = $request->file('project_template');
        $company_id = companyId();
        $company = Company::find($company_id);
        //$document->getRealPath();
        // return $document->getClientOriginalName();
        // $document->getClientOriginalExtension();
        // $document->getSize();
        // $document->getMimeType();


        if ($request->hasFile('project_template')) {

            $datas = \Excel::load($request->file('project_template')->getrealPath(), function ($reader) {
                $reader->noHeading()->skipRows(1);
            })->get();


            foreach ($datas[0] as $data) {
                // dd($data[0]);
                if ($data[0]) {


                    $user = \App\User::where(['emp_num' => $data[0]])->first();


                    $user->project_salary_category_id = $data[1];
                    $user->save();


                }

            }


            //   $request->session()->flash('success', 'Import was successful!');

            // return back();
            return 'success';
        }

    }

    // public function importTemplate(Request $request)
    // {
    //   $document = $request->file('template');
    //   $company_id=companyId();
    //   $det=BscDet::find($request->det_id);


    //    if($request->hasFile('template')){

    //     $datas=\Excel::load($request->file('template')->getrealPath(), function($reader) {
    //                                        $reader->noHeading()->skipRows(1);
    //                          })->get();

    //                          foreach ($datas[0] as $data) {
    //                           // dd($data[0]);
    //                           if ($data[0]) {
    //                       $user=User::where('emp_num',$data[0])->first();
    //                        $det_detail=BscDetDetail::create(['bsc_det_id'=>$det->id,'bsc_metric_id'=>$metric->id,'business_goal'=>$data[1],'measure'=>$data[2],'lower'=>$data[3],'mid'=>$data[4],'upper'=>$data[5],'weighting'=>$data[6]*100]);


    //                     }

    //                          }


    //       return 'success';
    //       }

    // }
    private function projectSalaryComponents(Request $request)
    {
        $company_id = companyId();
        $salary_categories = PaceSalaryCategory::where('company_id', $company_id)->get();
        $salary_category = PaceSalaryCategory::find($request->project_salary_category_id);
        if ($salary_category) {
            $pscs = PaceSalaryComponent::where(['company_id' => $company_id, 'pace_salary_category_id' => $salary_category->id])->get();
            return view('payrollsettings.pace_salary_component', compact('pscs', 'salary_categories'));
        } else {
            $pscs = PaceSalaryComponent::where(['company_id' => $company_id])->get();
            return view('payrollsettings.pace_salary_component', compact('pscs', 'salary_categories'));
        }
    }

    public function projectSalaryComponent(Request $request)
    {

        $ssct = PaceSalaryComponent::find($request->project_salary_component_id);
        return $ssct;
    }

    public function changeProjectSalaryComponentStatus(Request $request)
    {
        $psc = PaceSalaryComponent::find($request->project_salary_component_id);
        if ($psc->status == 1) {
            $psc->update(['status' => 0]);
            return 2;
        } elseif ($psc->status == 0) {
            $psc->update(['status' => 1]);
            return 1;
        }


    }

    public function changeProjectSalaryComponentTaxable(Request $request)
    {
        $psc = PaceSalaryComponent::find($request->project_salary_component_id);
        if ($psc->taxable == 1) {
            $psc->update(['taxable' => 0]);
            return 2;
        } elseif ($psc->taxable == 0) {
            $psc->update(['taxable' => 1]);
            return 1;
        }


    }

    public function deleteProjectSalaryComponent(Request $request)
    {
        $project_salary_component = PaceSalaryComponent::find($request->project_salary_component_id);
        if ($project_salary_component) {

            $project_salary_component->delete();
            return 'success';
        }
    }

    public function saveProjectSalaryComponent(Request $request)
    {
        // return $request->all();
        $company_id = companyId();
        $validator = Validator::make($request->all(), [
            'constant' => Rule::unique('pace_salary_components')->where(function ($query) use ($request, $company_id) {
                return $query->where('company_id', $company_id)
                    ->where('id', '!=', $request->id)
                    ->where('pace_salary_category_id', $request->salary_category_id);
            })]);
        if ($validator->fails()) {
            return response()->json([
                $validator->errors()
            ], 401);
        }
        $sc = PaceSalaryComponent::updateOrCreate(['id' => $request->id], ['name' => $request->name, 'pace_salary_category_id' => $request->salary_category_id, 'type' => $request->pctype, 'gl_code' => $request->gl_code, 'project_code' => $request->project_code, 'fixed' => $request->pcfixed, 'uses_days' => $request->uses_days, 'constant' => $request->constant, 'formula' => $request->formula, 'amount' => $request->amount, 'status' => 1, 'taxable' => $request->taxable, 'company_id' => $company_id, 'uses_anniversary' => $request->uses_anniversary,'uses_probation'=>$request->uses_probation]);
        if ($request->filled('exemptions')) {
            $no_of_exemptions = count($request->input('exemptions'));
        } else {
            $no_of_exemptions = 0;
        }
        if ($no_of_exemptions > 0) {
            $sc->exemptions()->detach();
            for ($i = 0; $i < $no_of_exemptions; $i++) {
                if ($request->exemptions[$i] != 0) {
                    $sc->exemptions()->attach($request->exemptions[$i], ['created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]);
                }

            }
        } else {
            $sc->exemptions()->detach();
        }

        return 'success';
    }


    private function TMSASchedules(Request $request)
    {
        $tmsa_schedules = TmsaSchedule::all();
        $users = \App\User::where(['company_id' => companyId(), 'payroll_type' => 'tmsa'])->get();
        return view('payrollsettings.tmsa_schedule', compact('tmsa_schedules', 'users'));
    }

    public function TMSASchedule(Request $request)
    {
        $tmsa_schedule = TmsaSchedule::with('user')->find($request->tmsa_schedule_id);
        if ($tmsa_schedule) {
            return $tmsa_schedule;
        }
    }

    public function deleteTMSASchedule(Request $request)
    {
        $tmsa_schedule = TmsaSchedule::find($request->tmsa_schedule_id);
        if ($tmsa_schedule) {

            $tmsa_schedule->delete();
            return 'success';
        }
    }

    public function saveTMSASchedule(Request $request)
    {
        $company_id = companyId();
        $tmsa_schedule = TmsaSchedule::find($request->tmsa_schedule_id);
        $date = date('Y-m-d', strtotime('01-' . $request->for));
        $tmsa = TmsaSchedule::updateOrCreate(['id' => $request->tmsa_schedule_id], ['for' => $request->for ? $date : $tmsa_schedule->for, 'user_id' => $request->user_id ? $request->user_id : $tmsa_schedule->user_id, 'day_rate_onshore' => $request->day_rate_onshore, 'day_rate_offshore' => $request->day_rate_offshore, 'paid_off_time_rate' => $request->paid_off_time_rate, 'days_worked_offshore' => $request->days_worked_offshore, 'days_worked_onshore' => $request->days_worked_onshore, 'paid_off_day' => $request->paid_off_day, 'brt_days' => $request->brt_days, 'living_allowance' => $request->living_allowance, 'transport_allowance' => $request->transport_allowance, 'extra_addition' => $request->extra_addition, 'extra_deduction' => $request->extra_deduction, 'days_out_of_station' => $request->days_out_of_station, 'company_id' => $company_id]);


        return 'success';
    }

    public function saveSpecificSalaryComponentType(Request $request)
    {
        $company_id = companyId();
        $sal_comp = SpecificSalaryComponentType::where('name', $request->name)->first();
        if (!$sal_comp) {
            $sal_comp = SpecificSalaryComponentType::updateOrCreate(['id' => $request->id], ['name' => $request->name, 'display' => 1, 'company_id' => $company_id, 'display' => 1]);
        }


        return 'success';
    }

    public function changeSpecificSalaryComponentTypeDisplay(Request $request)
    {
        $ssct = SpecificSalaryComponentType::find($request->specific_salary_component_type_id);
        if ($ssct->display == 1) {
            $ssct->update(['display' => 0]);
            return 2;
        } elseif ($ssct->display == 0) {
            $ssct->update(['display' => 1]);
            return 1;
        }


    }

    public function specificSalaryComponentType(Request $request)
    {

        $ssct = SpecificSalaryComponentType::find($request->ssct_id);
        return $ssct;
    }

    public function deleteSpecificSalaryComponentType(Request $request)
    {
        $ssct = SpecificSalaryComponentType::find($request->ssct_id);
        if ($ssct) {

            $ssct->delete();
            return 'success';
        }
    }

    public function projectSalaryCategories(Request $request)
    {
        $company_id = companyId();
        $salary_categories = PaceSalaryCategory::where('company_id', $company_id)->get();
        return view('payrollsettings.pace_salary_category', compact('salary_categories'));
    }

    public function projectSalaryCategory(Request $request)
    {

        $ssct = PaceSalaryCategory::find($request->project_category_id);
        return $ssct;
    }

    public function deleteProjectCategory(Request $request)
    {
        $ssct = PaceSalaryCategory::find($request->project_category_id);
        if ($ssct) {

            $ssct->delete();
            return 'success';
        }
    }

    public function saveProjectSalaryCategory(Request $request)
    {
        $company_id = companyId();
        $sal_comp = PaceSalaryCategory::updateOrCreate(['id' => $request->id], ['name' => $request->name, 'unionized' => $request->unionized, 'basic_salary' => $request->basic_salary, 'relief' => $request->relief, 'company_id' => $company_id, 'uses_timesheet' => $request->timesheet, 'uses_tax' => $request->uses_tax, 'uses_daily_net' => $request->uses_daily_net,'tax_rate'=>$request->tax_rate,'uses_direct_tax'=>$request->uses_direct_tax]);

        return 'success';
    }

    public function projectSalaryCategoryTimesheets(Request $request)
    {
        $date = date('Y-m-d', strtotime('01-' . $request->month));

        $salary_category = PaceSalaryCategory::find($request->project_category_id);
        $components = $salary_category->paceSalaryComponents()->where('uses_days', 1)->get();
        // $timesheets=$salary_category->project_salary_timesheets()->where('for',$date)->get();
        $users = $salary_category->users()->where('payroll_type', 'project')->where('status', '!=', 2)->get();
        return view('payrollsettings.pace_salary_category_timesheets', compact('salary_category', 'components', 'date', 'users'));
    }

    public function account(Request $request)
    {

        $ca = \App\ChartOfAccount::where('id', $request->account_id)->with('account_extra_components')->first();
        return $ca;
    }

    public function accounts(Request $request)
    {
        $company_id = companyId();
        $cas = \App\ChartOfAccount::where('company_id', $company_id)->orderBy('position')->get();
        $salary_components = \App\SalaryComponent::where('company_id', $company_id)->get()->pluck('name', 'constant');
        $project_salary_components = \App\PaceSalaryComponent::where('company_id', $company_id)->get()->pluck('name', 'constant');
        $components = $salary_components->merge($project_salary_components);
        $components = $components->unique();
        $ssc_types = SpecificSalaryComponentType::where('company_id', $company_id)->get();
        $user_groups = \App\UserGroup::where('company_id', companyId())->get();
        return view('payrollsettings.chart_of_account', compact('cas', 'ssc_types', 'components', 'user_groups'));
    }

    public function saveAccount(Request $request)
    {
        $company_id = companyId();

//     $validator=Validator::make($request->all(), [
//     'code' => [
//         'required',
//         Rule::unique('chart_of_accounts')->where(function ($query) use($company_id,$request) {
//     return $query->where('company_id', $company_id)
//     ->where('id','!=',$request->account_id);
// })
//           ],
//       ]);
//     if ($validator->fails()) {
//             return response()->json([
//                     $validator->errors()
//                     ],401);
//         }

        $ac = \App\ChartOfAccount::updateOrCreate(['id' => $request->account_id], ['name' => $request->name, 'code' => $request->code, 'description' => $request->description, 'type' => $request->account_type, 'display' => $request->display, 'salary_component_constant' => $request->salary_component_constant, 'specific_salary_component_type_id' => $request->specific_salary_component_type_id, 'nationality_display' => $request->nationality_display, 'other_constant' => $request->other_constant, 'source' => $request->source, 'formula' => $request->formula, 'status' => $request->status, 'salary_charge' => $request->salary_charge, 'non_payroll_provision' => $request->non_payroll_provision, 'company_id' => $company_id, 'uses_group' => $request->uses_group, 'group_id' => $request->group_id]);

        $no_of_extra_components = 0;
        if ($request->input('comp_source') !== null) {
            $no_of_extra_components = count($request->input('comp_source'));


            if ($request->input('comp_salary_component') !== null) {
                $no_of_salary_components = count($request->input('comp_salary_component'));
            }
            if ($request->input('comp_specific_salary_component_type_id') !== null) {
                $no_of_specific_salary_component_types = count($request->input('comp_specific_salary_component_type_id'));
            }
            if ($request->input('payroll_constant') !== null) {
                $no_of_payroll_constants = count($request->input('payroll_constant'));
            }
            if ($request->input('amount') !== null) {
                $no_of_amounts = count($request->input('amount'));
            }
            $no_of_salary_components_used = 0;
            $no_of_specific_salary_component_types_used = 0;
            $no_of_payroll_constants_used = 0;
            $no_of_amounts_used = 0;
            for ($i = 0; $i < $no_of_extra_components; $i++) {
                $extra_component = \App\ChartOfAccountComponent::find($request->extra_component_id[$i]);
                if ($extra_component) {
                    if ($request->comp_source[$i] == 1) {
                        $extra_component->update(['salary_component_constant' => $request->comp_salary_component[$i], 'source' => $request->comp_source[$i], 'operator' => $request->comp_operator[$i], 'percentage' => $request->comp_percentage[$i]]);
                        $no_of_salary_components_used++;
                    } elseif ($request->comp_source[$i] == 2) {
                        $extra_component->update(['specific_salary_component_type_id' => $request->comp_specific_salary_component_type_id[$i], 'source' => $request->comp_source[$i], 'operator' => $request->comp_operator[$i], 'percentage' => $request->comp_percentage[$i]]);
                        $no_of_specific_salary_component_types_used++;
                    } elseif ($request->comp_source[$i] == 3) {
                        $extra_component->update(['payroll_constant' => $request->payroll_constant[$i], 'source' => $request->comp_source[$i], 'operator' => $request->comp_operator[$i], 'percentage' => $request->comp_percentage[$i]]);
                        $no_of_payroll_constants_used++;

                    } elseif ($request->comp_source[$i] == 4) {
                        $extra_component->update(['amount' => $request->amount[$i], 'chart_of_account_id' => $ac->id, 'source' => $request->comp_source[$i], 'operator' => $request->comp_operator[$i], 'percentage' => $request->comp_percentage[$i]]);
                        $no_of_amounts_used++;

                    }
                } else {
                    if ($request->comp_source[$i] == 1) {
                        $extra_component = $ac->account_extra_components()->create(['salary_component_constant' => $request->comp_salary_component[$i], 'chart_of_account_id' => $ac->id, 'source' => $request->comp_source[$i], 'operator' => $request->comp_operator[$i], 'percentage' => $request->comp_percentage[$i]]);
                        $no_of_salary_components_used++;
                    } elseif ($request->comp_source[$i] == 2) {
                        $extra_component = $ac->account_extra_components()->create(['specific_salary_component_type_id' => $request->comp_specific_salary_component_type_id[$i], 'chart_of_account_id' => $ac->id, 'source' => $request->comp_source[$i], 'operator' => $request->comp_operator[$i], 'percentage' => $request->comp_percentage[$i]]);
                        $no_of_specific_salary_component_types_used++;
                    } elseif ($request->comp_source[$i] == 3) {
                        $extra_component = $ac->account_extra_components()->create(['payroll_constant' => $request->payroll_constant[$i], 'chart_of_account_id' => $ac->id, 'source' => $request->comp_source[$i], 'operator' => $request->comp_operator[$i], 'percentage' => $request->comp_percentage[$i]]);
                        $no_of_payroll_constants_used++;

                    } elseif ($request->comp_source[$i] == 4) {
                        $extra_component = $ac->account_extra_components()->create(['amount' => $request->amount[$i], 'source' => $request->comp_source[$i], 'operator' => $request->comp_operator[$i], 'percentage' => $request->comp_percentage[$i]]);
                        $no_of_amounts_used++;

                    }
                }

            }
        }
        return 'success';
    }

    public function deleteAccount(Request $request)
    {
        $ca = \App\ChartOfAccount::find($request->account_id);
        if ($ca && ($ca->salary_components == null) && ($ca->specific_salary_components == null)) {

            $ca->delete();
            return 'success';
        }
    }

    public function saveAccountPositions(Request $request)
    {

        foreach ($request->input('positions') as $position) {
            $ca = \App\ChartOfAccount::find($position[0]);
            $ca->update(['position' => $position[1]]);
        }
        return 'success';
    }

    public function SearchTmsaMonths(Request $request)
    {
        $exists = \App\TmsaMonth::where('year', date('Y'))->get();
        $months = [1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'];
        if (count($exists) == 0) {

            foreach ($months as $key => $month) {
                \App\TmsaMonth::create(['name' => $month, 'month' => $key, 'year' => date('Y')]);
            }
        }
        if ($request->q == "") {
            return "";
        } else {
            $name = \App\TmsaMonth::where('name', 'LIKE', '%' . $request->q . '%')
                // ->select('id as id','name as text')
                ->select(\DB::raw("CONCAT(name,'-',year) AS text"), 'id')
                ->get();
        }


        return $name;
    }

    public function longServiceAward(Request $request)
    {

        $lsa = \App\LongServiceAward::where('id', $request->award_id)->first();
        return $lsa;
    }

    public function longServiceAwards(Request $request)
    {
        $company_id = companyId();
        $pp = PayrollPolicy::where('company_id', $company_id)->first();
        $lsas = \App\LongServiceAward::where('company_id', $company_id)->orderBy('max_year', 'ASC')->get();
        $users = \App\User::where(['non_payroll_provision_employee' => 1, 'company_id' => companyId()])->where('status', '!=', 2)->get();


        return view('payrollsettings.long_service_award', compact('lsas', 'users', 'pp'));
    }

    public function SaveLongServiceAward(Request $request)
    {
        $company_id = companyId();
        \App\LongServiceAward::updateOrCreate(['id' => $request->lsa_id], ['max_year' => $request->max_year, 'amount' => $request->amount, 'difference' => $request->difference, 'company_id' => $company_id]);
        return 'success';
    }


    public function deleteLongServiceAward(Request $request)
    {
        $lsa = \App\LongServiceAward::find($request->award_id);
        if ($lsa) {

            $lsa->delete();
            return 'success';
        }
    }

    public function switchNavExportDisplay(Request $request)
    {

        $company_id = companyId();
        $pp = PayrollPolicy::where('company_id', $company_id)->first();
        if ($pp->display_lsa_on_nav_export == 1) {
            $pp->update(['display_lsa_on_nav_export' => 0]);
            return 2;
        } elseif ($pp->display_lsa_on_nav_export == 0) {
            $pp->update(['display_lsa_on_nav_export' => 1]);
            return 1;
        }
    }

    public function switchPayrollExportDisplay(Request $request)
    {
        $company_id = companyId();
        $pp = PayrollPolicy::where('company_id', $company_id)->first();
        if ($pp->display_lsa_on_payroll_export == 1) {
            $pp->update(['display_lsa_on_payroll_export' => 0]);
            return 2;
        } elseif ($pp->display_lsa_on_payroll_export == 0) {
            $pp->update(['display_lsa_on_payroll_export' => 1]);
            return 1;
        }

    }

    public function searchNonPayrollProvisionEmployee(Request $request)
    {


        if ($request->q == "") {
            return "";
        } else {
            $name = \App\User::where('name', 'LIKE', '%' . $request->q . '%')
                ->where(['non_payroll_provision_employee' => 0, 'company_id' => companyId()])
                ->where('status', '!=', 2)
                ->select('id as id', 'name as text')
                ->get();
        }


        return $name;
    }

    public function saveNonPayrollProvisionEmployees(Request $request)
    {
        try {
            $users_count = count($request->user_id);
            for ($i = 0; $i < $users_count; $i++) {
                $user = \App\User::find($request->user_id[$i]);

                if ($user) {
                    $user->non_payroll_provision_employee = 1;
                    $user->save();
                }
            }

            return 'success';
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }

    public function getNonPayrollProvisionEmployees(Request $request)
    {
        return $users = \App\User::where(['non_payroll_provision_employee' => 1, 'company_id' => companyId()])->where('status', '!=', 2)->get();
    }

    public function removeNonPayrollEmployee(Request $request)
    {
        $user = \App\User::find($request->user_id);
        if ($user) {
            $user->non_payroll_provision_employee = 0;
            $user->save();
        }


        return 'success';
    }

    public function nonPayrollProvisionEmployees(Request $request)
    {
        $company_id = companyId();
        $pp = PayrollPolicy::where('company_id', $company_id)->first();

        $users = \App\User::where(['non_payroll_provision_employee' => 1, 'company_id' => companyId()])->where('status', '!=', 2)->get();


        return view('payrollsettings.non_payroll_provision', compact('users', 'pp'));
    }

    public function salaryReviews(Request $request)
    {
        $company_id = companyId();
        $reviews = \App\SalaryReview::where('company_id', $company_id)->get();
        return view('payrollsettings.salary_review', compact('reviews'));
    }

    public function salaryReview(Request $request)
    {
        $review = \App\SalaryReview::find($request->review_id);
        return $review;
    }

    public function deleteSalaryReview(Request $request)
    {
        $review = \App\SalaryReview::find($request->review_id);
        if ($review) {

            $review->delete();
            return 'success';
        }
    }

    public function saveSalaryReview(Request $request)
    {
        $company_id = companyId();
        $review_month=date('Y-m-d',strtotime('01-'.$request->review_month));
        $payment_month=date('Y-m-d',strtotime('01-'.$request->payment_month));
       return $review = \App\SalaryReview::updateOrCreate([
            'id' => $request->id
        ], ['employee_id' => $request->employee_id,
            'review_month' => $review_month,
            'payment_month' => $payment_month,
            'previous_gross' => $request->previous_gross,
            'company_id' => $company_id
        ]);

        return 'success';
    }
    public function salaryReviewInjectionComponents(Request $request)
    {
        $company_id = companyId();
        $salary_review_components = \App\SalaryReviewInjectionComponent::where('company_id', $company_id)->get();
        return view('payrollsettings.salary_review_injection_component', compact('salary_review_components'));
    }

    public function salaryReviewInjectionComponent(Request $request)
    {
        $review_component = \App\SalaryReviewInjectionComponent::find($request->review_comopnent_id);
        return $review_component;
    }

    public function deleteSalaryReviewInjectionComponent(Request $request)
    {
        $review_component = \App\SalaryReviewInjectionComponent::find($request->review_component_id);
        if ($review_component) {

            $review_component->delete();
            return 'success';
        }
    }

    public function saveSalaryReviewInjectionComponent(Request $request)
    {
        $company_id = companyId();
        $review = \App\SalaryReviewInjectionComponent::updateOrCreate([
            'id' => $request->id
        ], ['from_component_type' => $request->from_component_type,
        'to_component_type' => $request->to_component_type,
            'injection_type' => $request->injection_type,
            'status' => $request->status,
            'from_salary_component_constant' => $request->from_salary_component_constant,
            'from_payroll_component_constant' => $request->from_payroll_component_constant,
            'to_salary_component_constant' => $request->to_salary_component_constant,
            'to_payroll_component_constant' => $request->to_payroll_component_constant,
            'percentage' => $request->percentage,
            'company_id' => $company_id

        ]);

        return 'success';
    }


}
