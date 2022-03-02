<?php

namespace App\Traits;

use App\BscMetric;
use App\BscSubMetric;
use App\BscMeasurementPeriod;
use App\BscWeight;
use App\BscDet;
use App\BscDetDetail;
use App\BscEvaluation;
use App\BscEvaluationDetail;
use App\Department;
use Illuminate\Http\Request;
use Excel;

trait BscTrait
{

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
            case 'delete_measurement_period':
                # code...
                return $this->deleteMeasurementPeriod($request);
                break;
            case 'download_template':
                # code...
                return $this->downloadTemplate($request);
                break;
            case 'get_det_details':
                # code...
                return $this->getDETDetails($request);
                break;
            case 'delete_det_detail':
                # code...
                return $this->deleteDETDetail($request);
                break;
            case 'get_det':
                # code...
                return $this->getDET($request);
                break;
            case 'template':
                # code...
                return $this->template($request);
                break;
            case 'get_bsc_grade_performance_category':
                # code...
                return $this->get_bsc_grade_performance_category($request);
                break;
            case 'search_grade':
                # code...
                return $this->search_grade($request);
                break;
            case 'delete_bsc_grade_performance_category':
                # code...
                return $this->delete_bsc_grade_performance_category($request);
                break;
            case 'remove_bsc_grade_performance_category_grade':
                # code...
                return $this->remove_bsc_grade_performance_category_grade($request);
                break;
            case 'get_behavioral_sub_metric':
                # code...
                return $this->getBehavioralSubMetric($request);
                break;
            case 'delete_behavioral_sub_metric':
                # code...
                return $this->deleteBehavioralSubMetric($request);
                break;
            case 'get_template_metric_weight':
                # code...
                return $this->getMetricWeight($request);
                break;

			
			
			default:
				# code...
				break;
		}
		 
	}


	public function processPost(Request $request){
		// try{
		switch ($request->type) {
			case 'editweight':
				# code...
				return $this->saveWeight($request);
				break;
			case 'measurementperiod':
				# code...
				return $this->saveMeasurementPeriod($request);
				break;
			case 'save_det_detail':
				# code...
				return $this->saveDETDetail($request);
				break;
			case 'import_template':
				# code...
				return $this->importTemplate($request);
				break;
			case 'save_bsc_grade_performance_category':
				# code...
				return $this->save_bsc_grade_performance_category($request);
				break;
			case 'behavioral_sub_metric':
				# code...
				return $this->saveBehavioralSubMetric($request);
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

	
	public function bscsettings(Request $request)
	{
		$metrics=BscMetric::all();
		$measurementperiods=BscMeasurementPeriod::all();
		$performance_categories = \App\BscGradePerformanceCategory::all();
		$perspectives=BscWeight::all();
		return view('settings.bscsettings.metric',compact('metrics','measurementperiods','perspectives','performance_categories'));
	}
	public function saveMetric(Request $request)
	{
		BscMetric::updateOrCreate(['id',$request->metric_id],['name'=>$request->name,'description'=>$request->description]);
		return 'success';
	}
	public function getMetric(Request $request)
	{
		return $metric= BscMetric::find($request->submetric_id);
	}
	
	public function getSubmetric(Request $request)
	{
		return $submetric= BscSubMetric::find($request->submetric_id);
	}
	
	public function getMeasurementPeriod(Request $request)
	{
		return $measurementperiod= BscMeasurementPeriod::find($request->mp_id);
	}
	public function deleteMeasurementPeriod(Request $request)
	{
		 $measurementperiod= BscMeasurementPeriod::find($request->mp_id);
		 if ($measurementperiod) {
		 	if (count($measurementperiod->evaluations)) {
		 		return 'failed';
		 	}else{
		 		$measurementperiod->delete();
		 	}return 'success';
		 }else{
		 	return 'notfound';
		 }
	}
	public function saveMeasurementPeriod(Request $request)
	{
		$month=date('m',strtotime('01-'.$request->to));
		$year=date('Y',strtotime('01-'.$request->to));
		$days=cal_days_in_month(CAL_GREGORIAN,$month,$year);
		BscMeasurementPeriod::updateOrCreate(['id'=>$request->mp_id],['from'=>date('Y-m-d',strtotime('01-'.$request->from)),'to'=>date('Y-m-d',strtotime($days.'-'.$request->to)),
            'status'=>1,'head_of_strategy_id'=>$request->head_of_strategy_id,'head_of_hr_id'=>$request->head_of_hr_id,
            'scorecard_percentage'=>$request->scorecard_percentage,'behavioral_percentage'=>$request->behavioral_percentage]);
		return 'success';
	}

	public function getWeight(Request $request)
	{
		return $weight= BscWeight::where('id',$request->weight_id)->with(['department.company', 'department','metric','performance_category'])->first();
	}

	public function saveWeight(Request $request)
	{
		BscWeight::where('id',$request->weight_id)->update(['percentage'=>$request->percentage]);
		return 'success';
	}

	
private function downloadTemplate(Request $request){

                           $template=['Category','Focus','Objectives','Key Deliverables','Measure of Success','Means of Verification','Weight'];
                           $perspective=\App\BscMetric::select('id as Internal ID','name')->get()->toArray();

                           return $this->exportexcel('template',['template'=>$template,'perspective'=>$perspective]);

              }

	private function exportexcel($worksheetname,$data)
	{
		return \Excel::create($worksheetname, function($excel) use ($data)
		{
			foreach($data as $sheetname=>$realdata)
			{
				$excel->sheet($sheetname, function($sheet) use ($realdata,$sheetname)
				{
					  
			            $sheet->fromArray($realdata);
			           $sheet->_parent->getSheet(0)->setColumnFormat(array(
							    'G' => '0%'
							));

			      if($sheetname=='perspective'){
			      
		            	$sheet->_parent->addNamedRange(
		            		new \PHPExcel_NamedRange(
		            			'sd', $sheet->_parent->getSheet( 1 ), "B2:B" . $sheet->_parent->getSheet( 1 )->getHighestRow()
		            		)
		            	);
		            
			     for($j=2; $j<=100; $j++){ 
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
  		
				});
			}
		})->download('xlsx');
	}

	public function getDet(Request $request)
	{
		
		$det=BscDet::find($request->template_id);

		

					if($det){
						$metrics=BscMetric::all();
						
						return view('bsc.view_department_template',compact('det','metrics'));

					}else{

							$det=BscDet::create(['title'=>$request->title,'company_id'=>companyId()]);
							$metrics=BscMetric::all();
							return redirect(url('bscsettings/get_det?template_id='.$det->id));

					}
			


		
		
	}

	public function saveDETDetail(Request $request)
	{
        $metric=BscMetric::find($request->metric_id);
        $is_penalty = 0;
        if($metric->has_penalties==1) {
            $is_penalty = 1;
        }
			
			 $det_detail=BscDetDetail::updateOrCreate(['id'=>$request->id],['bsc_det_id'=>$request->bsc_det_id,'metric_id'=>$request->metric_id,'business_goal'=>$request->business_goal,'is_penalty'=>$is_penalty,'performance_metric_description'=>$request->performance_metric_description,'target'=>$request->target,'weight'=>$request->weight,'company_id'=>companyId()]);
			
			 
			return $det_detail;
			
	
	}

	public function getDETDetails(Request $request)
	{
		return $det_details=BscDetDetail::where(['bsc_det_id'=>$request->bsc_det_id,'metric_id'=>$request->bsc_metric_id])->get();
		
	}

	public function deleteDETDetail(Request $request)
	{
		$det_detail=BscDetDetail::find($request->id);
		$det_detail->delete();
		return 'success';
		
	}


    public function template(Request $request)
    {
        $company_id = companyId();
        //        $measurement_periods=BscMeasurementPeriod::all();
        $templates = BscDet::where('company_id', $company_id)->get();

        //        $departments=Department::where('company_id',$company_id)->get();


        return view('bsc.template', compact('templates'));
    }

    public function get_bsc_grade_performance_category(Request $request)
    {
        return $bsc_grade_performance_category =
            \App\BscGradePerformanceCategory::where('id', $request->performance_category_id)->with('grades')->first();
    }

    public function search_grade(Request $request)
    {


        if ($request->q == "") {
            return "";
        }
        else {
            $name = \App\Grade::doesntHave('performance_category')
                ->where('level', 'LIKE', '%' . $request->q . '%')
                ->select('id as id', 'level as text')
                ->get();
        }


        return $name;
    }

    public function delete_bsc_grade_performance_category(Request $request)
    {
        $bsc_grade_performance_category = \App\BscGradePerformanceCategory::find($request->bsc_grade_performance_category_id);
        if ($bsc_grade_performance_category) {
            if (count($bsc_grade_performance_category->grades) < 1) {
                $bsc_grade_performance_category->delete();
            }
        }


        return 'success';
    }

    public function remove_bsc_grade_performance_category_grade(Request $request)
    {
        $grade = \App\Grade::find($request->grade_id);
        if ($grade) {
            $grade->performance_category()->dissociate();
            $grade->save();
            return 'success';
        }
    }

    public function getBehavioralSubMetric(Request $request)
    {
        return $behavioralsubmetric = \App\BehavioralSubMetric::find($request->bsm_id);
    }

    public function deleteBehavioralSubMetric(Request $request)
    {
        $behavioralsubmetric = \App\BehavioralSubMetric::find($request->bsm_id);
        if ($behavioralsubmetric) {
            if (count($behavioralsubmetric->evaluations)) {
                return 'failed';
            }
            else {
                $behavioralsubmetric->delete();
            }
            return 'success';
        }
        else {
            return 'notfound';
        }
    }

    public function getMetricWeight(Request $request)
    {
        $det = BscDet::find($request->det_id);
        return $weightSum = BscDetDetail::where(['bsc_det_id' => $request->det_id, 'metric_id' => $request->metric_id])->sum('weight');


    }






    public function importTemplate(Request $request)
    {
        $document = $request->file('template');
        $det = BscDet::find($request->det_id);


        if ($request->hasFile('template')) {

            $datas = \Excel::load($request->file('template')->getrealPath(), function ($reader) {
                $reader->noHeading()->skipRows(1);
            })->get();

            foreach ($datas[0] as $data) {
                // dd($data[0]);
                if ($data[0]) {
                    $metric = BscMetric::where('name', $data[0])->first();
                    $is_penalty = 0;
                    if ($metric->has_penalties == 1) {
                        $is_penalty = 1;
                    }
                    $det_detail = BscDetDetail::create(
                        ['bsc_det_id'                     => $det->id, 'metric_id' => $metric->id, 'focus' => $data[1],
                         'objective' => $data[2], 'key_deliverable' => $data[3], 'measure_of_success' => $data[4],
                            'means_of_verification' => $data[5], 'weight' => $data[6] * 100,
                         'is_penalty'=> $is_penalty, 'company_id' => companyId()]);


                }

            }


            return 'success';
        }

    }

    public function save_bsc_grade_performance_category(Request $request)
    {
        try {


            $bsc_grade_performance_category =
                \App\BscGradePerformanceCategory::updateOrCreate(['id' => $request->performance_category_id], ['name' => $request->name,]);
            if ($request->filled('grade_id')) {
                $grade_count = count($request->grade_id);
                for ($i = 0; $i < $grade_count; $i++) {
                    $grade = \App\Grade::find($request->grade_id[$i]);

                    if ($grade) {

                        $bsc_grade_performance_category->grades()->save($grade);
                    }
                }
            }


            return 'success';
        }
        catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }

    public function saveBehavioralSubMetric(Request $request)
    {

        \App\BehavioralSubMetric::updateOrCreate(
            ['id' => $request->bsm_id],
            ['objective'  => $request->objective, 'measure' => $request->measure, 'weighting' => $request->weighting,
             'status'     => $request->status, 'company_id' => companyId()]);
        return 'success';
    }





}