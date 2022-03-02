<?php

namespace App\Traits;

use App\BehavioralSubMetric;
use App\BscMetric;
use App\BscSubMetric;
use App\BscMeasurementPeriod;
use App\BscWeight;
use App\Company;
use App\PerformanceDiscussionDetail;
use App\User;
use Auth;
use App\Department;
use App\Grade;
use App\GradeCategory;
use App\BscEvaluation;
use App\BscEvaluationDetail;
use App\Notifications\KPIsCreated;
use App\BehavioralEvaluationDetail;
use Excel;
use App\BscDet;
use App\Traits\Micellenous;
use App\BscDetDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

trait BSCEvaluationTrait
{
    use Micellenous;
    public $performance_grades = ["Poor Performance" => [0, 1.95], "Below Expectation" => [1.96, 2.45], "Meets Expectation" => [2.46, 3.45], "Exceeds Expectation" => [3.5, 4]];

    public function processGet($route, Request $request)
    {
        switch ($route) {
            case 'get_weight':
                # code...
                return $this->getWeight($request);
                break;
            case 'get_measurement_period':
                # code...
                return $this->getMeasurementPeriod($request);
                break;
            case 'get_evaluation_details':
                # code...
                return $this->getEvaluationDetails($request);
                break;
            case 'delete_evaluation_detail':
                # code...
                return $this->deleteEvaluationDetail($request);
                break;
            case 'get_evaluation_details_sum':
                # code...
                return $this->getEvaluationDetailsSum($request);
                break;
            case 'get_evaluation_wcp':
                # code...
                return $this->getEvaluationWcp($request);
                break;
            case 'get_behavioral_evaluation_wcp':
                # code...
                return $this->getBehavioralEvaluationWcp($request);
                break;
            case 'get_evaluation':

                return $this->getEvaluation($request);
                break;
            case 'set_employee_kpi':

                return $this->setupEmployeeKPI($request);
                break;

            case 'get_evaluation_user_list':

                return $this->getEvaluationUserList($request);
                break;
            case 'my_evaluations':

                return $this->getMyEvaluations($request);
                break;
            case 'get_my_evaluation':

                return $this->viewMyEvaluation($request);
                break;
            case 'use_dept_template':

                return $this->useDeptTemplate($request);
                break;
            case 'accept_kpis':

                return $this->acceptRejectKPIs($request);
                break;
            case 'submit_kpis_for_review':

                return $this->submitKPIsForReview($request);
                break;
            case 'manager_approve':

                return $this->managerApprove($request);
                break;
            case 'employee_approve':

                return $this->employeeApprove($request);
                break;
            case 'hr':

                return $this->hrIndex($request);
                break;
            case 'get_hr_department_list':

                return $this->departmentList($request);
                break;
            case  'performanceDiscussion':
                return $this->getPerformanceDiscussion($request);
                break;
            case  'dept_user_list':
                return $this->departmentUserList($request);
                break;
            case 'get_hr_evaluation':

                return $this->getHREvaluation($request);
                break;

            case 'get_behavioral_evaluation_details':

                return $this->getBehavioralEvaluationDetails($request);
                break;
            case 'graph_report':

                return $this->graphChart($request);
                break;
            case 'bsc_mp_report':

                return $this->getBscMPReport($request);
                break;
            case 'ba_mp_report':

                return $this->getBAMPReport($request);
                break;
            case 'avg_report':

                return $this->getAvgMPReport($request);
                break;
            case 'dept_report':

                return $this->getDeptAvgMPReport($request);
                break;
            case 'excel_report':

                return $this->exportForBSCExcelReport($request);
                break;
            case 'get_metric_weight':

                return $this->getMetricWeight($request);
                break;
            case 'get_approvals':

                return $this->get_approvals($request);
                break;


            default:
                # code...
                break;
        }

    }


    public function processPost(Request $request)
    {
        // try{
        switch ($request->type) {
            case 'get_evaluation':

                return $this->getEvaluation($request);
                break;
            case 'save_evaluation_detail':
                # code...
                return $this->saveEvaluationDetail($request);
                break;
            case 'save_employee_kpi_detail':
                # code...
                return $this->saveEmployeeKpi($request);
                break;
            case 'measurementperiod':
                # code...
                return $this->saveMeasurementPeriod($request);
                break;
            case 'save_evaluation_comment':
                # code...
                return $this->saveEvaluationComment($request);
                break;
            case 'import_emeasures':
                # code...
                return $this->importTemplate($request);
                break;
            case 'saveDiscussion':
                return $this->saveDiscussion($request);
            case 'saveDiscussionDetail':
                return $this->saveDiscussionDetail($request);
                break;
            case 'save_behavioral_evaluation_detail':
                # code...
                return $this->saveBehavioralEvaluationDetail($request);
                break;
            case 'save_evaluation_approval':
                # code...
                return $this->saveEvaluationApproval($request);
                break;
            default:
                # code...
                break;
        }
        // }
        // catch(\Exception $ex){
        // 	return response()->json(['status'=>'error','message'=>$ex->getMessage()]);
        // }
    }


    public function get_approvals(Request $request)
    {

        $evaluations = BscEvaluation::where(function ($query) {
            $query->where(['manager_of_manager_id' => Auth::user()->id,
                'approval_status' => 'manager_of_manager']);
        })->orwhere(function ($query) {
            $query->where(['head_of_strategy_id' => Auth::user()->id,
                'approval_status' => 'head_of_strategy']);
        })->orwhere(function ($query) {
            $query->where(['head_of_hr_id' => Auth::user()->id,
                'approval_status' => 'head_of_hr']);
        })->get();

        return view('bsc.approvals', compact('evaluations'));

    }

    public function saveEvaluationApproval(Request $request)
    {
        $evaluation = BscEvaluation::find($request->evaluation_id);
        $company = Company::find(companyId());
        switch ($request->approval_status) {
            case 'appraisal':
//                change stage
                $evaluation->update(['approval_status' => 'accepting', 'manager_approval_date' => date('Y-m-d'), 'manager_approval_comment' => $request->comment,
                    'manager_approval_approved'=>1
                ]);


            //notify employee

            case 'accepting':
                $manager = $evaluation->manager;
                Log::info($manager);
                $head_of_strategy_id = $evaluation->measurement_period->head_of_strategy_id;
                if (!$manager) {
                    return 'failed';
                }
                if ($request->approval_type == 'approved') {
                    if ($manager->line_manager_id == $company->manager_id ||$manager->line_manager_id==0) {
                        $evaluation->update(['approval_status' => 'head_of_strategy', 'appraisal_accepted_date' => date('Y-m-d'), 'appraisal_accepted' => 1
                            ,'appraisal_accepted_comment'=>$request->comment, 'head_of_strategy_id' => $head_of_strategy_id
                        ]);
                        return 'success';
                    } else {
                        $evaluation->update(['approval_status' => 'manager_of_manager', 'appraisal_accepted_date' => date('Y-m-d'), 'appraisal_accepted' => 1
                            ,'appraisal_accepted_comment'=>$request->comment,'manager_ofmanager_id'=>$manager->line_manager_id
                        ]);
                        return 'success';

                    }

                } else {
                    $evaluation->update(['approval_status' => 'manager_of_manager', 'appraisal_accepted_date' => date('Y-m-d'), 'appraisal_accepted' => 0
                        ,'appraisal_accepted_comment'=>$request->comment
                    ]);
                    return 'success';
                }


            case 'manager_of_manager':
                $manager = $evaluation->manager;
                $head_of_strategy_id = $evaluation->measurement_period->head_of_strategy_id;
                if ($request->approval_type == 'approved') {
                    $evaluation->update(['manager_of_manager_approval_comment' => $request->comment,
                        'manager_of_manager_approval_date' => date('Y-m-d'),
                        'approval_status' => 'head_of_strategy', 'head_of_strategy_id' => $head_of_strategy_id,
                        'manager_of_manager_approved' => 1]);
                    return 'success';
                } else {
                    $evaluation->update(['manager_of_manager_approval_comment' => $request->comment,
                        'manager_of_manager_approval_date' => date('Y-m-d'),
                        'approval_status' => 'appraisal',
                        'manager_of_manager_approved' => 0]);
                    return 'success';
                }


            case 'head_of_strategy':
                if ($request->approval_type == 'approved') {
                    $head_of_hr_id = $evaluation->measurement_period->head_of_hr_id;
                    $evaluation->update(['head_of_strategy_approval_comment' => $request->comment,
                        'head_of_strategy_approved_date' => date('Y-m-d'),
                        'approval_status' => 'head_of_hr', 'head_of_hr_id' => $head_of_hr_id,
                        'head_of_strategy_approved' => 1]);
                    return 'success';
                } else {

                    $evaluation->update(['head_of_strategy_approval_comment' => $request->comment,
                        'head_of_strategy_approved_date' => date('Y-m-d'),
                        'approval_status' => 'appraisal', 'head_of_strategy_approved' => 0]);
                    return 'success';
                }


            case 'head_of_hr':
                if ($request->approval_type == 'approved') {
                    $evaluation->update(['head_of_hr_approval_comment' => $request->comment,
                        'head_of_hr_approved_date' => date('Y-m-d'),
                        'approval_status' => 'approved', 'head_of_hr_approved' => 1]);
                    return 'success';
                } else {
                    $evaluation->update(['head_of_hr_approval_comment' => $request->comment,
                        'head_of_hr_approved_date' => date('Y-m-d'),
                        'approval_status' => 'appraisal', 'head_of_hr_approved' => 0]);
                    return 'success';
                }

            default:
                return 1;
        }


    }

    public function getEvaluationUserList(Request $request)
    {

        $mp = BscMeasurementPeriod::find($request->mp);
        $manager = Auth::user();

        $users = User::whereHas('managers', function ($query) use ($manager) {
            $query->where('manager_id', $manager->id);
        })->where('hiredate', '<=', $mp->to)->where('status', '!=', 2)->get();
        return view('bsc.users_list', compact('users', 'mp'));

    }

    public function getMyEvaluations(Request $request)
    {

        $evaluations = BscEvaluation::where(['user_id' => Auth::user()->id])->get();
        return view('bsc.user_index', compact('evaluations'));
    }

    public function viewMyEvaluation(Request $request)
    {
        $evaluation = BscEvaluation::find($request->evaluation);
        $metrics = BscMetric::all();
        $user = $evaluation->user;
        if ($evaluation->user_id == Auth::user()->id) {
            if ($evaluation) {

                return view('bsc.view_evaluation', compact('evaluation', 'metrics', 'user'));
            } else {
                $request->session()->flash('error', 'User does not have a grade category or a department');
                return redirect()->back();
            }
        } else {
            $request->session()->flash('error', 'You cannot view this evaluation');
            return redirect()->back();
        }

    }

    public function acceptRejectKPIs(Request $request)
    {
        $evaluation = BscEvaluation::find($request->bsc_evaluation_id);
        if ($evaluation && $evaluation->user_id == Auth::user()->id) {
            $evaluation->update(['kpi_accepted' => $request->action, 'date_kpi_accepted' => date('Y-m-d'),
                'approval_status' => 'appraisal', 'manager_id' => Auth::user()->line_manager_id]);
            return 'success';
        }
        return 'error';

    }

    public function submitKPIsForReview(Request $request)
    {
        $evaluation = BscEvaluation::find($request->bsc_evaluation_id);

        if ($evaluation->user_id == Auth::user()->id) {
            $evaluation->update(['kpi_accepted' => 1, 'date_kpi_accepted' => date('Y-m-d'), 'kpi_submitted' => 0, 'kpi_submitted_date' => date('Y-m-d'), 'evaluator_id' => Auth::user()->line_manager_id]);
            $evaluation->user->plmanager->notify((new KPIsCreated($evaluation, "bsc/get_evaluation?employee=$evaluation->user->id&mp=$evaluation->measurement_period->id")));
            return 'success';
        } else {
            $evaluation->update(['kpi_submitted' => 1, 'kpi_submitted_date' => date('Y-m-d'), 'manager_id' => Auth::user()->id]);
            $evaluation->user->notify((new KPIsCreated($evaluation, "bsc/get_my_evaluation?evaluation=$evaluation->id")));
            return 'success';
        }

    }

    public function managerApprove(Request $request)
    {
        $evaluation = BscEvaluation::find($request->bsc_evaluation_id);
        if ($evaluation) {
            $evaluation->update(['manager_approved' => 1, 'date_manager_approved' => date('Y-m-d'), 'evaluator_id' => Auth::user()->id]);
            return 'success';
        }
        return 'error';

    }

    public function employeeApprove(Request $request)
    {
        $evaluation = BscEvaluation::find($request->bsc_evaluation_id);
        if ($evaluation && $evaluation->user_id == Auth::user()->id) {
            $evaluation->update(['employee_approved' => 1, 'date_employee_approved' => date('Y-m-d')]);
            return 'success';
        }
        return 'error';

    }

    public function setupEmployeeKPI(Request $request)
    {
        $user = User::find($request->employee);
        $mp = BscMeasurementPeriod::find($request->mp);
        $bsms = \App\BehavioralSubMetric::where(['status' => 1, 'company_id' => companyId()])->get();
        $templates = BscDet::where('company_id', companyId())->get();
        $operation = 'evaluate';
        $grade = Grade::find($user->grade_id);
        if ($grade && $user->job) {
            if ($user->job) {

                $evaluation = BscEvaluation::where(['user_id' => $user->id, 'bsc_measurement_period_id' => $mp->id])->with(['behavioral_evaluation_details'])->first();

                if ($evaluation) {
                    foreach ($bsms as $bsm) {
                        $evaluation_detail = BehavioralEvaluationDetail::firstOrCreate(['bsc_evaluation_id' => $evaluation->id, 'behavioral_sub_metric_id' => $bsm->id]);
                    }
                    $metrics = BscMetric::all();


                    return view('bsc.setup_employee_kpi', compact('user', 'operation', 'evaluation', 'metrics', 'bsms', 'templates'));

                } else {


                    $evaluation = BscEvaluation::create(['user_id' => $user->id, 'bsc_measurement_period_id' => $mp->id, 'department_id' => $user->job->department_id, 'company_id' => companyId(), 'performance_category_id' => 0]);
                    foreach ($bsms as $bsm) {

                        $evaluation_detail = BehavioralEvaluationDetail::firstOrCreate(['bsc_evaluation_id' => $evaluation->id, 'behavioral_sub_metric_id' => $bsm->id]);
                    }
                    $metrics = BscMetric::all();

                    return view('bsc.setup_employee_kpi', compact('user', 'operation', 'evaluation', 'metrics', 'bsms', 'templates'));


                }

            } else {
                $request->session()->flash('error', 'User does not have a department ');
            }


        } elseif (!$grade) {
            $request->session()->flash('error', 'User does not have a grade  or job ');
            return redirect()->back();
        } else {
            return redirect()->back();

        }


    }

    public function saveEmployeeKpi(Request $request)
    {
        $metric = BscMetric::find($request->metric_id);
        if ($metric->has_penalties == 1) {
            $is_penalty = 1;
            if ($request->achievement > 0) {
                $score = $request->weight;
                $final_score = $request->weight;

            } else {
                $score = 0;
                $final_score = 0;
            }
        } else {
            $is_penalty = 0;

        }

        $evaluation_detail = BscEvaluationDetail::updateOrCreate(['id' => $request->id], ['bsc_evaluation_id' => $request->bsc_evaluation_id, 'metric_id' => $request->metric_id,
            'focus' => $request->focus, 'objective' => $request->objective,
            'key_deliverable' => $request->key_deliverable, 'measure_of_success' => $request->measure_of_success,
            'means_of_verification' => $request->means_of_verification, 'weight' => $request->weight, 'is_penalty' => $is_penalty]);

        $weightSum = BscEvaluationDetail::where(['bsc_evaluation_id' => $evaluation_detail->bsc_evaluation_id, 'is_penalty' => 0])->sum('weight');
        $evaluation = BscEvaluation::updateorCreate(['id' => $evaluation_detail->bsc_evaluation_id], ['weight_sum' => $weightSum,
            'kpi_submitted_date' => date('Y-m-d'), 'kpi_submitted' => 0,
            'manager_approval_approved' => 0, 'manager_approval_date' => date('Y-m-d'),
            'kpi_accepted' => 0, 'kpi_accepted_date' => date('Y-m-d'), 'company_id' => companyId()]);

        $this->notifyUserKpiChange($evaluation->user_id, $evaluation_detail->bsc_evaluation_id);

        return $evaluation_detail;


    }

    public function getEvaluation(Request $request)
    {

        $user = User::find($request->employee);
        $mp = BscMeasurementPeriod::find($request->mp);
        $bsms = \App\BehavioralSubMetric::where(['status' => 1, 'company_id' => companyId()])->get();
        $templates = BscDet::where('company_id', companyId())->get();
        $operation = 'evaluate';
        $grade = Grade::find($user->grade_id);
        if ($grade && $user->job) {
            if ($user->job) {

                $evaluation = BscEvaluation::where(['user_id' => $user->id, 'bsc_measurement_period_id' => $mp->id])->with(['behavioral_evaluation_details'])->first();

                if ($evaluation) {
                    foreach ($bsms as $bsm) {
                        $evaluation_detail = BehavioralEvaluationDetail::firstOrCreate(['bsc_evaluation_id' => $evaluation->id, 'behavioral_sub_metric_id' => $bsm->id]);
                    }
                    $metrics = BscMetric::all();
                    foreach ($bsms as $bsm) {

                        $evaluation_detail = BehavioralEvaluationDetail::firstOrCreate(['bsc_evaluation_id' => $evaluation->id, 'behavioral_sub_metric_id' => $bsm->id]);
                    }

                    return view('bsc.evaluation', compact('user', 'operation', 'evaluation', 'metrics', 'bsms', 'templates'));


                } else {


                    $evaluation = BscEvaluation::create(['user_id' => $user->id, 'bsc_measurement_period_id' => $mp->id, 'department_id' => $user->job->department_id, 'company_id' => companyId(), 'performance_category_id' => 0]);
                    foreach ($bsms as $bsm) {

                        $evaluation_detail = BehavioralEvaluationDetail::firstOrCreate(['bsc_evaluation_id' => $evaluation->id, 'behavioral_sub_metric_id' => $bsm->id]);
                    }
                    $metrics = BscMetric::all();

                    return view('bsc.evaluation', compact('user', 'operation', 'evaluation', 'metrics', 'bsms', 'templates'));


                }

            } else {
                $request->session()->flash('error', 'User does not have a department ');
            }


        } elseif (!$grade) {
            $request->session()->flash('error', 'User does not have a grade  or job ');
            return redirect()->back();
        } else {
            return redirect()->back();

        }


    }

    public function saveEvaluationDetail(Request $request)
    {
        $metric = BscMetric::find($request->metric_id);
        if ($metric->has_penalties == 1) {
            $is_penalty = 1;


        } else {
            $is_penalty = 0;


        }

        $evaluation_detail = BscEvaluationDetail::updateOrCreate(['id' => $request->id], ['bsc_evaluation_id' => $request->bsc_evaluation_id, 'metric_id' => $request->metric_id,
            'self_assessment' => $request->self_assessment, 'manager_assessment' => $request->manager_assessment,
            'justification_of_rating' => $request->justification_of_rating,
            'accept_reject' => $request->accept_reject, 'manager_of_manager_assessment' => $request->manager_of_manager_assessment, 'is_penalty' => $is_penalty]);

        $scorecardSum = BscEvaluationDetail::where(['bsc_evaluation_id' => $request->bsc_evaluation_id, 'is_penalty' => 0])->sum('manager_assessment');
        $penaltyScoreSum = BscEvaluationDetail::where(['bsc_evaluation_id' => $request->bsc_evaluation_id, 'is_penalty' => 1])->sum('manager_assessment');
        $weightSum = BscEvaluationDetail::where(['bsc_evaluation_id' => $request->bsc_evaluation_id, 'is_penalty' => 0])->sum('weight');

        $evaluation = BscEvaluation::find($evaluation_detail->bsc_evaluation_id);
        $scorecard_percentage = $evaluation->measurement_period->scorecard_percentage;
        $evaluation->update(['scorecard_score' => $scorecardSum, 'scorecard_percentage' => (($scorecardSum / $weightSum) * ($scorecard_percentage)),
            'weight_sum' => $weightSum, 'penalty_score' => $penaltyScoreSum]);


        return $evaluation_detail;


    }

    public function importTemplate(Request $request)
    {
        $document = $request->file('template');
        $evaluation = BscEvaluation::find($request->evaluation_id);


        if ($request->hasFile('template')) {

            $datas = \Excel::load($request->file('template')->getrealPath(), function ($reader) {
                $reader->noHeading()->skipRows(1);
            })->get();

            foreach ($datas[0] as $data) {

                if ($data[0]) {
                    $metric = BscMetric::where('name', $data[0])->first();
                    $is_penalty = 0;
                    if ($metric->has_penalties == 1) {
                        $is_penalty = 1;
                    }
                    $det_detail = BscEvaluationDetail::create(['bsc_evaluation_id' => $request->evaluation_id, 'metric_id' => $metric->id,
                        'focus' => $data[1],
                        'objective' => $data[2], 'key_deliverable' => $data[3], 'measure_of_success' => $data[4],
                        'means_of_verification' => $data[5], 'weight' => $data[6] * 100, 'is_penalty' => $is_penalty,]);


                }

            }
            $weightSum = BscEvaluationDetail::where(['bsc_evaluation_id' => $request->evaluation_id, 'is_penalty' => 0])->sum('weight');
            $evaluation = BscEvaluation::find($request->evaluation_id);
            $evaluation->update(['weight_sum' => $weightSum]);


            return 'success';
        }

    }

    public function getEvaluationDetails(Request $request)
    {
        return $evaluation_details = BscEvaluationDetail::where(['bsc_evaluation_id' => $request->bsc_evaluation_id, 'metric_id' => $request->metric_id])->get();

    }

    public function useDeptTemplate(Request $request)
    {
        $evaluation = BscEvaluation::find($request->bsc_evaluation_id);
        $det = BscDet::find($request->det_id);
        if ($evaluation && $det) {

            foreach ($det->details as $detail) {
                $evaluation->evaluation_details()->create(['metric_id' => $detail->metric_id, 'business_goal' => $detail->business_goal, 'is_penalty' => $detail->is_penalty, 'performance_metric_description' => $detail->performance_metric_description, 'target' => $detail->target, 'weight' => $detail->weight]);
            }
            return 'success';
        }

    }
    public function getBehavioralEvaluationWcp(Request $request)
    {
        $evaluation = BscEvaluation::find($request->bsc_evaluation_id);
        $behavioral_percentage = intval($evaluation->measurement_period->behavioral_percentage);
        $behavioralSum = BehavioralEvaluationDetail::where(['bsc_evaluation_id' => $request->bsc_evaluation_id])->sum('manager_assessment');
        $weightSum=BehavioralSubMetric::where(['status'=>1])->sum('weighting');
        $evaluation->update(['behavioral_score' => $behavioralSum, 'behavioral_percentage' => (($behavioralSum / $weightSum) * ($behavioral_percentage)),
            'weight_sum' => $weightSum]);

        return ['evaluation' => $evaluation];

    }

    public function getEvaluationWcp(Request $request)
    {
        $evaluation = BscEvaluation::find($request->bsc_evaluation_id);
        $scorecard_percentage = intval($evaluation->measurement_period->scorecard_percentage);
        $scorecardSum = BscEvaluationDetail::where(['bsc_evaluation_id' => $request->bsc_evaluation_id, 'is_penalty' => 0])->sum('manager_assessment');
        $penaltyScoreSum = BscEvaluationDetail::where(['bsc_evaluation_id' => $request->bsc_evaluation_id, 'is_penalty' => 1])->sum('manager_assessment');
        $weightSum = BscEvaluationDetail::where(['bsc_evaluation_id' => $request->bsc_evaluation_id, 'is_penalty' => 0])->sum('weight');
        $metricWeight = BscEvaluationDetail::where(['bsc_evaluation_id' => $request->bsc_evaluation_id, 'metric_id' => $request->metric_id])->sum('weight');
        $evaluation->update(['scorecard_score' => $scorecardSum, 'scorecard_percentage' => (($scorecardSum / $weightSum) * ($scorecard_percentage)),
            'weight_sum' => $weightSum, 'penalty_score' => $penaltyScoreSum]);

        return ['evaluation' => $evaluation, 'remark' => $this->calc_Performance($evaluation->score), 'metric_weight' => $metricWeight];

    }

    public function getMetricWeight(Request $request)
    {
        $evaluation = BscEvaluation::find($request->evaluation_id);
        return $weightSum = BscEvaluationDetail::where(['bsc_evaluation_id' => $request->evaluation_id, 'metric_id' => $request->metric_id])->sum('weight');


    }

    public function saveEvaluationComment(Request $request)
    {
        $evaluation = BscEvaluation::find($request->bsc_evaluation_id);
        $evaluation->update(['employee_strength' => $request->employee_strength,
            'employee_developmental_area' => $request->employee_developmental_area,
            'special_achievement' => $request->special_achievement,
            'manager_approval_comment' => $request->manager_approval_comment]);
        return 'success';
    }

    public function deleteEvaluationDetail(Request $request)
    {
        $evaluation_detail = BscEvaluationDetail::find($request->id);
        $evaluation_detail->delete();

    }

    public function getEvaluationDetailsSum(Request $request)
    {
        return $sum = BscEvaluationDetail::where(['bsc_evaluation_id' => $request->bsc_evaluation_id, 'metric_id' => $request->metric_id])->sum('weighting');
    }

    public function calc_Performance($summed_performance)
    {
        if ($summed_performance <= 1.95) {
            return "Poor Performance";
        } elseif ($summed_performance <= 2.45) {
            return "Below Expectation";
        } elseif ($summed_performance >= 3.5) {
            return "Exceeds Expectation";
        } elseif ($summed_performance <= 3.45) {
            return "Meets Expectation";
        } else {
            return "";
        }
    }

    public function weighted_contribution(Request $request)
    {
        $weighing = $request->weighing;
        $calc_result_achieved = $this->calc_result_achieved($request);
        return $weighing * $calc_result_achieved;
    }

    public function final_score(Request $request)
    {

        if ($request->achievement > $request->target) {
            return $request->weight;
        }
        return $final_score = ($request->achievement / $request->target) * $request->weight;
    }

    public function calc_result_achieved(Request $request)
    {


        if ($request->achievement > $request->target) {
            return 100;
        }

        return $score = ($request->achievement / $request->target) * 100;


    }

    public function departmentList(Request $request)
    {
        $company_id = companyId();
        $mp = BscMeasurementPeriod::find($request->mp_id);
        if ($company_id == 0) {
            $departments = Department::paginate(10);
        } else {
            $departments = Department::where('company_id', $company_id)->get();
        }

        return view('bsc.department_list', compact('departments', 'mp'));
    }

    public function measurementPeriodDepartmentUsers(Request $request)
    {
        $company_id = companyId();
        if ($company_id == 0) {
            $departments = Department::paginate(10);
        } else {
            $departments = Department::where('company_id', $company_id)->get();
        }

        return view('bsc.department_list', compact('departments'));
    }


    public function getPerformanceDiscussion(Request $request)
    {
        $discussions = \App\PerformanceDiscussion::where('evaluation_id', $request->evaluation_id)->get();

        return view('bsc.ajax.performanceDiscussion', compact('discussions'));
    }

    public function getPerformanceDiscussionDetail(Request $request)
    {
        return $discussion_detail = PerformanceDiscussionDetail::where('id', $request->performance_discussion_detail_id)->get()->with('evaluation_detail');
//        $discussions=\App\PerformanceDiscussion::where('evaluation_id',$request->evaluation_id)->get();
        return view('bsc.ajax.performanceDiscussion', compact('discussions'));
    }

    public function saveDiscussion(Request $request)
    {
        // could become a potential problem better to use $request->id in the where clause, but this has its own importance
        $saveDiscussion = \App\PerformanceDiscussion::updateOrCreate(['title' => $request->title, 'discussion' => $request->discussion], $request->except('_token'));
        $evaluation = BscEvaluation::find($request->evaluation_id);
        foreach ($evaluation->evaluation_details as $detail) {
            PerformanceDiscussionDetail::firstOrCreate(['performance_discussion_id' => $saveDiscussion->id, 'evaluation_detail_id' => $detail->id,
            ], []);
        }
        $this->nofityHrAdminSaveDiscussion($saveDiscussion);
        return $this->getPerformanceDiscussion($request);
        response()->json(['status' => 'success', 'message' => 'Pefromance Discussion Succsessfully Saved']);

    }

    public function saveDiscussionDetail(Request $request)
    {
        // could become a potential problem better to use $request->id in the where clause, but this has its own importance

        $saveDiscussion = \App\PerformanceDiscussionDetail::updateOrCreate(['id' => $request->discussion_detail_id], $request->except(['_token', 'type', 'discussion_detail_id', 'evaluation_id']));
//        $this->nofityHrAdminSaveDiscussion($saveDiscussion);
        return $this->getPerformanceDiscussion($request);
        // response()->json(['status'=>'success','message'=>'Pefromance Discussion Succsessfully Saved']);

    }

    // incase it was requested
    public function deletePerformanceDiscussion(Request $request)
    {
        \App\PefromanceDiscussion::where('id', $request->id)->delete();
        return getPerformanceDiscussion($request);
    }

    public function hrIndex(Request $request)
    {
        $company_id = companyId();
        $company = \App\Company::find($company_id);
        $metrics = \App\BscMetric::all();
        $measurement_periods = BscMeasurementPeriod::all();
        $weights = \App\BscWeight::all();
        $departments = Department::where('company_id', $company_id)->get();
        $grade_categories = GradeCategory::all();
        $user = new User();
        $operation = 'select';

        return view('bsc.hr_index', compact('metrics', 'measurement_periods', 'weights', 'departments', 'grade_categories', 'user', 'operation'));//

    }

    public function departmentUserList(Request $request)
    {
        $company_id = companyId();
        $mp = BscMeasurementPeriod::find($request->mp);
        $department = Department::find($request->department);
        if (isset($mp) & isset($department)) {

            $users = $department->users()->where('hiredate', '<=', $mp->to)->where('status', '!=', 2)->get();

            return view('bsc.hr_users_list', compact('department', 'users', 'mp'));
        } else {
            return redirect()->back();
        }

    }

    public function getHREvaluation(Request $request)
    {

        $user = User::find($request->employee);
        $mp = BscMeasurementPeriod::find($request->mp);
        $operation = 'evaluate';
        if ($user->grade && $user->job) {
            if ($user->job) {
                if (!$user->grade->performance_category) {
                    $request->session()->flash('error', 'Employee does not have a grade category or a department');
                    return redirect()->back();
                } else {
                    $evaluation = BscEvaluation::where(['user_id' => $user->id, 'bsc_measurement_period_id' => $mp->id])->first();
                    if ($evaluation) {
                        $metrics = BscMetric::all();

                        return view('bsc.hr_view_evaluation', compact('user', 'operation', 'evaluation', 'metrics'));

                    } else {


                        $request->session()->flash('error', 'Employee has not been evaluated.');


                    }
                }
            } else {
                $request->session()->flash('error', 'Employee does not have a department ');
            }


        } elseif (!$user->grade) {
            $request->session()->flash('error', 'Employee does not have a grade  or job ');
            return redirect()->back();
        } else {
            return redirect()->back();

        }


    }


    public function saveBehavioralEvaluationDetail(Request $request)
    {

        $evaluation_detail=BehavioralEvaluationDetail::find($request->id);
        $evaluation_detail->update(['bsc_evaluation_id' => $request->bsc_evaluation_id, 'self_assessment' => $request->self_assessment,
            'manager_assessment' => $request->manager_assessment,
            'manager_of_manager_assessment' => $request->manager_of_manager_assessment, 'head_of_strategy' => $request->head_of_strategy,
            'head_of_hr' => $request->head_of_hr,'accept_reject'=>$request->accept_reject]);
        $evaluation=BscEvaluation::find($evaluation_detail->bsc_evaluation_id);
        $behavioral_percentage = intval($evaluation->measurement_period->behavioral_percentage);
        $behavioralSum = BehavioralEvaluationDetail::where(['bsc_evaluation_id' => $request->bsc_evaluation_id])->sum('manager_assessment');
        $weightSum=BehavioralSubMetric::where(['status'=>1])->sum('weighting');
        $evaluation->update(['behavioral_score' => $behavioralSum, 'behavioral_percentage' => (($behavioralSum / $weightSum) * ($behavioral_percentage)),
            ]);
//        $maSum = BehavioralEvaluationDetail::where('bsc_evaluation_id', $evaluation_detail->bsc_evaluation_id)->sum('manager_assessment');
//        $evaluation = BscEvaluation::updateorCreate(['id' => $evaluation_detail->bsc_evaluation_id], ['behavioral_score' => $maSum]);

//        $this->notifyUserKpiChange($evaluation->user_id, $evaluation_detail->bsc_evaluation_id);

        return $evaluation_detail;


    }

    public function getBehavioralEvaluationDetails(Request $request)
    {
        return $evaluation_details = \App\BehavioralEvaluationDetail::where(['bsc_evaluation_id' => $request->bsc_evaluation_id])->get();

    }


    public function graphChart(Request $request)
    {
        $company_id = companyId();
        $mp = BscMeasurementPeriod::find($request->mp_id);
        return view('bsc.graph_report', compact('mp'));
    }

    public function getBscMPReport(Request $request)
    {

        $data = [];


        // $leaves=Leave::where('company_id',comapnyId())->get();


        foreach ($this->performance_grades as $key => $performance_grade) {
            $data['labels'][] = $key;

            $data['data'][] = BscEvaluation::where(['company_id' => companyId(), 'bsc_measurement_period_id' => $request->mp_id])->whereBetween('score', $performance_grade)->count();

        }

        return $data;


    }

    public function getBAMPReport(Request $request)
    {

        $data = [];


        // $leaves=Leave::where('company_id',comapnyId())->get();


        foreach ($this->performance_grades as $key => $performance_grade) {
            $data['labels'][] = $key;

            $data['data'][] = BscEvaluation::where(['company_id' => companyId(), 'bsc_measurement_period_id' => $request->mp_id])->whereBetween('behavioral_score', $performance_grade)->count();


        }
        return $data;


    }

    public function getAvgMPReport(Request $request)
    {

        $data = [];


        // $leaves=Leave::where('company_id',comapnyId())->get();


        foreach ($this->performance_grades as $key => $performance_grade) {
            $data['labels'][] = $key;
            $data['data'][] = BscEvaluation::selectRaw(' ((behavioral_score+score)/2) as avgs')->where(['company_id' => companyId(), 'bsc_measurement_period_id' => $request->mp_id])->get()->filter(function ($value, $key) use ($performance_grade) {
                if ($value->avgs >= $performance_grade[0] and $value->avgs <= $performance_grade[1]) {
                    return $value->avgs;
                };
            })->count();
        }
        return $data;


    }

    public function getDeptAvgMPReport(Request $request)
    {

        $data = [];


        // $leaves=Leave::where('company_id',comapnyId())->get();
        $departments = \App\Department::where('company_id', companyId())->get();
        foreach ($departments as $department) {
            $data['labels'][] = $department->name;
        }
        $pk = 0;
        foreach ($this->performance_grades as $key => $performance_grade) {
            $data['datasets'][$pk]['label'] = $key;
            foreach ($departments as $department) {
                $user_ids = $department->users->pluck('id');

                $data['datasets'][$pk]['data'][] = BscEvaluation::selectRaw(' ((behavioral_score+score)/2) as avgs')->whereIn('user_id', $user_ids)->where(['company_id' => companyId(), 'bsc_measurement_period_id' => $request->mp_id])->get()->filter(function ($value, $key) use ($performance_grade) {
                    if ($value->avgs >= $performance_grade[0] and $value->avgs <= $performance_grade[1]) {
                        return $value->avgs;
                    };
                })->count();
            }
            $pk++;

        }
        return $data;


    }

    public function exportForBSCExcelReport(Request $request)
    {

        $data = [];
        $mp = BscMeasurementPeriod::find($request->mp_id);

        return \Excel::create("BSC export for " . date('F-Y', strtotime($mp->from)) . " to " . date('F-Y', strtotime($mp->to)), function ($excel) use ($request) {

            $type = $request->type;
            $evaluations = BscEvaluation::where(['company_id' => companyId(), 'bsc_measurement_period_id' => $request->mp_id])->get();
            if ($type == 'score') {
                $excel->sheet('performance report', function ($sheet) use ($evaluations) {

                    $sheet->loadView('bsc.partials.bsc_report', compact('evaluations'))->setOrientation('landscape');
                });
            } elseif ($type == 'behavioral_score') {
                $excel->sheet('performance report', function ($sheet) use ($evaluations) {

                    $sheet->loadView('bsc.partials.ba_report', compact('evaluations'))->setOrientation('landscape');
                });

            } elseif ($type == 'average') {
                $excel->sheet('performance report', function ($sheet) use ($evaluations) {

                    $sheet->loadView('bsc.partials.avg_report', compact('evaluations'))->setOrientation('landscape');
                });

            }
        })->export('xlsx');
    }


}
