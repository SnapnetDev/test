<?php
namespace App\Traits;
use App\E360Det;
use App\E360DetQuestion;
use App\E360DetQuestionOption;
use App\E360Evaluation;
use App\E360EvaluationDetail;
use App\E360MeasurementPeriod;
use App\Department;
use App\E360PeerEvaluationNominee;
use App\E360QuestionCategory;
use App\Notifications\Approve360ReviewNomination;
use Illuminate\Http\Request;
use Excel;
use App\User;
use Auth;
trait E360Trait
{
	public function processGet($route,Request $request){
		switch ($route) {


			case 'get_evaluation':
				# code...
				return $this->getEvaluation($request);
				break;
            case 'home':
                # code...
                return $this->employeeHome($request);
                break;
			case 'get_evaluation_report':
				# code...
				return $this->getEvaluationReport($request);
				break;
			case 'department_employees':
				# code...
				return $this->getDepartmentEmployees($request);
				break;
			case 'department_report_employees':
				# code...
				return $this->getDepartmentReportEmployees($request);
				break;
			case 'select_department':
				# code...
				return $this->selectDepartment($request);
				break;
			case 'select_report_department':
				# code...
				return $this->selectReportDepartment($request);
				break;
            case 'nominate_peer':
                # code...
                return $this->nominate_peer_view($request);
                break;
            case 'approve_nominations':
                # code...
                return $this->approve_nominations($request);
                break;
            case 'approve_nomination':
                # code...
                return $this->approve_nomination($request);
                break;
            case 'reject_nomination':
                # code...
                return $this->reject_nomination($request);
                break;
            case 'peer_review_list':
                # code...
                return $this->getPeerReviewList($request);
                break;
            case 'dr_review_list':
                # code...
                return $this->getDirectReportReviewList($request);
                break;
            case 'employee_report1':
                # code...
                return $this->employee_report1($request);
                break;
            case 'employee_report2':
                # code...
                return $this->employee_report2($request);
                break;
            case 'report_users':
                # code...
                return $this->report_users($request);
                break;
            case 'report_index':
                # code...
                return $this->report_index($request);
                break;



			default:
				# code...
				break;
		}

	}


	public function processPost(Request $request){
		// try{
		switch ($request->type) {
			case 'save_evaluation':
				# code...
				return $this->saveEvaluation($request);
				break;
            case 'add_nominees':
                # code...
                return $this->save_peer_nominees($request);
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
	public function selectDepartment(Request $request)
	{
		$company_id=companyId();
        $measurement_periods=E360MeasurementPeriod::all();

        $departments=Department::where('company_id',$company_id)->get();


        return view('e360.select_departments',compact('measurement_periods','departments'));
	}
	public function selectReportDepartment(Request $request)
	{
		$company_id=companyId();
        $measurement_periods=E360MeasurementPeriod::all();

        $departments=Department::where('company_id',$company_id)->get();


        return view('e360.select_report_departments',compact('measurement_periods','departments'));
	}
	public function getDepartmentReportEmployees(Request $request)
	{

		$department=Department::find($request->department);
		$mp=E360MeasurementPeriod::find($request->mp);
		$operation='evaluate';

						$det=E360Det::where(['department_id'=>$department->id,'measurement_period_id'=>$request->mp])->first();
					if($det){


						return view('e360.department_users_report',compact('det'));

					}else{

						return redirect()->back()->with('error', 'Department Review Questions have not been uploaded!');





					}

	}
	public function getDepartmentEmployees(Request $request)
	{

		$department=Department::find($request->department);
		$mp=E360MeasurementPeriod::find($request->mp);
		$operation='evaluate';

						$det=E360Det::where(['department_id'=>$department->id,'measurement_period_id'=>$request->mp])->first();
					if($det){


						return view('e360.department_users',compact('det'));

					}else{

						return redirect()->back()->with('error', 'Department Review Questions have not been uploaded!');





					}

	}

	public function getEvaluation(Request $request)
	{

		$mp=E360MeasurementPeriod::find($request->mp);
		$employee=User::find($request->employee);
		$reviewer=User::find(Auth::user()->id);
		$operation='evaluate';

						$evaluation=E360Evaluation::where(['mp_id'=>$mp->id,'user_id'=>$employee->id,'evaluator_id'=>$reviewer->id])->first();

					if($evaluation){


						return view('e360.review',compact('evaluation','mp'));

					}else{


							$evaluation=E360Evaluation::create(['mp_id'=>$mp->id,'user_id'=>$employee->id,'evaluator_id'=>$reviewer->id,'company_id'=>companyId()]);

						return view('e360.review',compact('evaluation','mp'));

					}

	}
	public function getEvaluationReport(Request $request)
	{

		$det=E360Det::find($request->det);
		$employee=User::find($request->employee);
		$operation='evaluate';

						$evaluation=E360Evaluation::where(['e360_det_id'=>$det->id,'user_id'=>$employee->id])->first();

					if($evaluation){

						$evaluations=E360Evaluation::where(['e360_det_id'=>$det->id,'user_id'=>$employee->id])->get();
						return view('e360.review_report',compact('employee','det','evaluations'));

					}else{


						return redirect()->back()->with('error', 'Employee has not been reviewed!');
					}

	}
	public function saveEvaluation(Request $request)
	{
		$mp=E360MeasurementPeriod::find($request->mp_id);
		$evaluation=E360Evaluation::find($request->evaluation_id);
		foreach ($mp->questions as $question) {
			$det_question=E360EvaluationDetail::updateOrCreate(['e360_det_question_id'=>$request->question_id,'e360_evaluation_id'=>$request->evaluation_id,'e360_det_question_option_id'=>$request->result['question_'.$question->id]],['e360_evaluation_id'=>$request->evaluation_id,'e360_det_question_id'=>$question->id,'e360_det_question_option_id'=>$request->result['question_'.$question->id],'company_id'=>companyId()]);
		}




			return 'success';


	}
	public function save_peer_nominees(Request $request){
	    $user=User::find($request->user_id);
        $no_of_nominees=count($request->input('nominee_id'));
        for ($i=0; $i <$no_of_nominees ; $i++) {
            $previous_nomination=E360PeerEvaluationNominee::where(['user_id'=>$request->user_id,'mp_id'=>2,'nominee_id'=>$request->nominee_id[$i]])->first();
            if ( $previous_nomination){
                $nomination=E360PeerEvaluationNominee::updateOrCreate(['user_id'=>$request->user_id,'mp_id'=>2,'nominee_id'=>$request->nominee_id[$i]],['user_id'=>$request->user_id,'mp_id'=>2,'nominee_id'=>$request->nominee_id[$i],'company_id'=>companyId()]);
            }else{
                $nomination=E360PeerEvaluationNominee::updateOrCreate(['user_id'=>$request->user_id,'mp_id'=>2,'nominee_id'=>$request->nominee_id[$i]],['user_id'=>$request->user_id,'mp_id'=>2,'nominee_id'=>$request->nominee_id[$i],'company_id'=>companyId()]);
                $nomination->nominee->notify(new Approve360ReviewNomination($nomination));
            }

        }
        return 'success';

    }
    public function approve_nominations(Request $request){
        $user=Auth::user();
        $mp=E360MeasurementPeriod::find($request->mp);
        $nominations=E360PeerEvaluationNominee::where(['nominee_id'=>$user->id,'mp_id'=>$mp->id,'status'=>0])->get();
        return view('e360.nomination_list',compact('nominations','mp','user'));
//        return 'success';

    }
    public function approve_nomination(Request $request){
        $nomination=E360PeerEvaluationNominee::find($request->nomination_id);
        $nomination->update(['status'=>1]);
        return 'success';
    }
    public function reject_nomination(Request $request){
        $nomination=E360PeerEvaluationNominee::find($request->nomination_id);
        $nomination->delete();
        return 'success';
    }
    public function nominate_peer_view(Request $request){
        $users=User::where('company_id',companyId())->get();
        $mp=E360MeasurementPeriod::find($request->mp);
        $employee=User::find($request->employee);
         $nominations=E360PeerEvaluationNominee::where(['user_id'=>$request->employee,'mp_id'=>$request->mp])->get();


	    return view('e360.nominate_peer',compact('users','nominations','employee','mp'));


    }
    public function employee_report1(Request $request){
	    $user=User::find($request->employee);
        $mp=E360MeasurementPeriod::find($request->mp);
	    $user_evaluations=E360Evaluation::where(['user_id'=>$user->id,'evaluator_id'=>$user->id,'mp_id'=>$mp->id])->get();
	    $others_evaluations=E360Evaluation::where(['user_id'=>$user->id])->where('evaluator_id','!=',$user->id)->get();
	    $user_result_array=[];
        $others_result_array=[];
	    $question_categories=E360QuestionCategory::all();
	    foreach ($question_categories as $category){
	        $user_result_array['category_name'][$category->id]=$category->name;
	        $evaluation_details=E360EvaluationDetail::whereHas('question.question_category',function($query) use($category){
	            $query->where('e360_question_categories.id',$category->id);
            })
                ->whereHas('evaluation',function($query) use($user){
                $query->where('e360_evaluations.user_id',$user->id);
                    $query->where('e360_evaluations.evaluator_id',$user->id);
            })->get();
	        $count=0;
	        $total=0;
	        foreach($evaluation_details as $detail){
	            $total+=$detail->option->score;
	            $count++;
            }
            $user_result_array['total_score'][$category->id]=$total;
            $user_result_array['total_count'][$category->id]=$count;
            if($count>0){
                $user_result_array['average'][$category->id]=$total/$count;
            }else{
                $user_result_array['average'][$category->id]=0;
            }



        }
        //get others
        foreach ($question_categories as $category){
            $others_result_array['category_name'][$category->id]=$category->name;
            $evaluation_details=E360EvaluationDetail::whereHas('question.question_category',function($query) use($category){
                $query->where('e360_question_categories.id',$category->id);
            })
                ->whereHas('evaluation',function($query) use($user){
                    $query->where('e360_evaluations.user_id',$user->id);
                    $query->where('e360_evaluations.evaluator_id','!=',$user->id);
                })->get();
            $others_count=0;
            $others_total=0;
            foreach($evaluation_details as $detail){
                $others_total+=$detail->option->score;
                $others_count++;
            }
            $others_result_array['total_score'][$category->id]=$others_total;
            $others_result_array['total_count'][$category->id]=$others_count;
            if($others_count>0){
                $others_result_array['average'][$category->id]=$others_total/$others_count;
            }else{
                $others_result_array['average'][$category->id]=0;
            }


        }
        return view('e360.graph1',compact('user_result_array','others_result_array','question_categories','user','mp'));
    }
    public function employee_report2(Request $request){
        $user=User::find($request->employee);
        $mp=E360MeasurementPeriod::find($request->mp);
        $user_evaluations=E360Evaluation::where(['user_id'=>$user->id,'evaluator_id'=>$user->id,'mp_id'=>$mp->id])->get();
        $others_evaluations=E360Evaluation::where(['user_id'=>$user->id])->where('evaluator_id','!=',$user->id)->get();
        $peer=E360PeerEvaluationNominee::where(['nominee_id'=>$user->id,'mp_id'=>$mp->id,'status'=>1])->pluck('nominee_id');
        if ($user->pdreports){
            $dr=$user->pdreports->pluck('id');
        }else{
            $dr=[];
        }

        $result_array=[];
        $question_categories=E360QuestionCategory::all();
        //user
        foreach ($question_categories as $category){

            $result_array['user']['category_name'][$category->id]=$category->name;
            $evaluation_details=E360EvaluationDetail::whereHas('question.question_category',function($query) use($category){
                $query->where('e360_question_categories.id',$category->id);
            })
                ->whereHas('evaluation',function($query) use($user){
                    $query->where('e360_evaluations.user_id',$user->id);
                    $query->where('e360_evaluations.evaluator_id',$user->id);
                })->get();
            $count=0;
            $total=0;
            foreach($evaluation_details as $detail){
                $total+=$detail->option->score;
                $count++;
            }
            $result_array['user']['total_score'][$category->id]=$total;
            $result_array['user']['total_count'][$category->id]=$count;
            if($count>0){
                $result_array['user']['average'][$category->id]=$total/$count;
            }else{
                $result_array['user']['average'][$category->id]=0;
            }



        }
        //get manager

        foreach ($question_categories as $category){
            if($user->plmanager){
            $result_array['manager']['category_name'][$category->id]=$category->name;
            $evaluation_details=E360EvaluationDetail::whereHas('question.question_category',function($query) use($category){
                $query->where('e360_question_categories.id',$category->id);
            })
                ->whereHas('evaluation',function($query) use($user){
                    $query->where('e360_evaluations.user_id',$user->id);
                    $query->where('e360_evaluations.evaluator_id',$user->plmanager->id);
                })->get();
            $manager_count=0;
            $manager_total=0;
            foreach($evaluation_details as $detail){
                $manager_total+=$detail->option->score;
                $manager_count++;
            }
            $result_array['manager']['total_score'][$category->id]=$manager_total;
            $result_array['manager']['total_count'][$category->id]=$manager_count;
            if($manager_count>0){
                 $result_array['manager']['average'][$category->id]=$manager_total/$manager_count;
            }else{
                $result_array['manager']['average'][$category->id]=0;
            }
            } else{
                $result_array['manager']['category_name'][$category->id]=$category->name;
                $result_array['manager']['total_score'][$category->id]=0;
                $result_array['manager']['total_count'][$category->id]=0;
                $result_array['manager']['average'][$category->id]=0;

            }
        }


        //get peer
        foreach ($question_categories as $category){
            $result_array['peer']['category_name'][$category->id]=$category->name;
            $evaluation_details=E360EvaluationDetail::whereHas('question.question_category',function($query) use($category){
                $query->where('e360_question_categories.id',$category->id);
            })
                ->whereHas('evaluation',function($query) use($peer,$user){
                    $query->where('e360_evaluations.user_id',$user->id);
                    $query->whereIn('e360_evaluations.evaluator_id',$peer);
                })->get();
            $manager_count=0;
            $manager_total=0;
            foreach($evaluation_details as $detail){
                $manager_total+=$detail->option->score;
                $manager_count++;
            }
            $result_array['peer']['total_score'][$category->id]=$manager_total;
            $result_array['peer']['total_count'][$category->id]=$manager_count;
            if($manager_count>0){
                $result_array['peer']['average'][$category->id]=$manager_total/$manager_count;
            }else{
                $result_array['peer']['average'][$category->id]=0;
            }


        }
        //get subordinate
        foreach ($question_categories as $category){
            $result_array['subordinate']['category_name'][$category->id]=$category->name;
            $evaluation_details=E360EvaluationDetail::whereHas('question.question_category',function($query) use($category){
                $query->where('e360_question_categories.id',$category->id);
            })
                ->whereHas('evaluation',function($query) use($dr,$user){
                    $query->where('e360_evaluations.user_id',$user->id);
                    $query->whereIn('e360_evaluations.evaluator_id',$dr);
                })->get();
            $manager_count=0;
            $manager_total=0;
            foreach($evaluation_details as $detail){
                $manager_total+=$detail->option->score;
                $manager_count++;
            }
            $result_array['subordinate']['total_score'][$category->id]=$manager_total;
            $result_array['subordinate']['total_count'][$category->id]=$manager_count;
            if($manager_count>0){
                $result_array['subordinate']['average'][$category->id]=$manager_total/$manager_count;
            }else{
                $result_array['subordinate']['average'][$category->id]=0;
            }
        }
        //get combined
        foreach ($question_categories as $category){
            $result_array['combined']['category_name'][$category->id]=$category->name;
            $evaluation_details=E360EvaluationDetail::whereHas('question.question_category',function($query) use($category){
                $query->where('e360_question_categories.id',$category->id);
            })
                ->whereHas('evaluation',function($query) use($user){
                    $query->where('e360_evaluations.user_id',$user->id);
                })->get();
            $manager_count=0;
            $manager_total=0;
            foreach($evaluation_details as $detail){
                $manager_total+=$detail->option->score;
                $manager_count++;
            }
            $result_array['combined']['total_score'][$category->id]=$manager_total;
            $result_array['combined']['total_count'][$category->id]=$manager_count;
            if($manager_count>0){
                $result_array['combined']['average'][$category->id]=$manager_total/$manager_count;
            }else{
                $result_array['combined']['average'][$category->id]=0;
            }


        }
        return view('e360.graph2',compact('result_array','question_categories','user','mp'));
    }
    public function report2(Request $request){
        $user=User::find($request->user_id);
        $det=E360Det::find($request->det);
        $peer=$user->e360_nominees()->where('status',1)->get();
        $manager=$user->linemanager;
        $drs=$user->employees;

    }
    public function getPeerReviewList(Request $request){
        $user=Auth::user();
        $mp=E360MeasurementPeriod::find($request->mp);
        $nominations=E360PeerEvaluationNominee::where(['nominee_id'=>$user->id,'mp_id'=>$request->mp,'status'=>1])->get();
        return view('e360.peer_review_list',compact('nominations','mp'));
    }
    public function getDirectReportReviewList(Request $request){
        $user=Auth::user();
        $employees=Auth::user()->pdreports;
        $mp=E360MeasurementPeriod::find($request->mp);
        return view('e360.dr_review_list',compact('employees','mp'));
    }
    public function employeeHome(Request $request){

        $mp=E360MeasurementPeriod::find($request->mp);
        if ($mp){
            return view('e360.user_home',compact('mp'));
        }else{
            return redirect()->back();
        }

    }
    public function report_index(Request $request){

        $mps=E360MeasurementPeriod::all();
        if ($mps){
            return view('e360.reports_index',compact('mps'));
        }else{
            return redirect()->back();
        }

    }
    public function report_users(Request $request){

        $mp=E360MeasurementPeriod::find($request->mp);
        if (Auth::user()->role->manages=='all'){
            $users=User::where('company_id',companyId())->get();
        }elseif(Auth::user()->role->manages=='dr'){
            $users=Auth::user()->pdreports;
        }else{
            return redirect()->back();
        }

        if ($mp){
            return view('e360.report_users',compact('mp','users'));
        }else{
            return redirect()->back();
        }

    }




}
