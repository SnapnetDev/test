<?php

namespace App\Http\Controllers;

use App\NPIndividualKPI;
use App\NPUser;
use App\User;
use Illuminate\Http\Request;
use Excel;

class NPIndividualKPIController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function webInsertKPIQuestion(Request $request){
        if ($request->type=='edit'){
            return $this->webEditKPIQuestion($request);
        }
        $kpi=  NPIndividualKPI::create(
            ['n_p_user_id'=>$request->np_user_id,'kpi_question'=>$request->question,'weight'=>$request->weight,'target'=>$request->target,'target_words'=>$request->target_words,
                'kpi_rating'=>$request->kpi_rating,'kpi_rating_type'=>$request->kpi_rating_type,
                'measurement'=>$request->measurement,'data_source'=>$request->data_source,'frequency_of_data'=>$request->frequency_of_data,
                'responsible_collation_unit'=>$request->responsible_collation_unit,
            ]);
        $total_score=$kpi->np_user->individual_kpis->sum('score');
        $total_score=round($total_score);
        $kpi->np_user->update(['score'=>$total_score]);
        return $kpi;
    }

    public function webEditKPIQuestion(Request $request){
        NPIndividualKPI::where('id',$request->kpi_id)->update(
            ['kpi_question'=>$request->question,'weight'=>$request->weight,'target'=>$request->target,'target_words'=>$request->target_words,
                'kpi_rating'=>$request->kpi_rating,'kpi_rating_type'=>$request->kpi_rating_type,
                'measurement'=>$request->measurement,'data_source'=>$request->data_source,'frequency_of_data'=>$request->frequency_of_data,
                'responsible_collation_unit'=>$request->responsible_collation_unit,
            ]);
        $kpi=NPIndividualKPI::find($request->kpi_id);
        $kpi->update(['score'=>0,'actual'=>0]);
        $total_score=$kpi->np_user->individual_kpis->sum('score');
        $total_score=round($total_score);
        $kpi->np_user->update(['score'=>$total_score]);
        return $kpi;
    }

    public function template(Request $request){

        if ($request->type=='single'){
            $first_row = [
                ['KPI Question' => '', 'Weight' => '', 'Target Words' => '','Target' => '', 'KPI Rating' => '', 'KPI Rating Type' => '', 'Measurement' => '', 'Data Source' => '', 'Frequency of Data' => '', 'Responsible collation unit' => '']
            ];
            $rating_types = collect([['id'=>'calculation','name'=>'calculation'],['id'=>'conditional','name'=>'conditional'],['id'=>'punitive','name'=>'punitive']]);
            $users = User::select('name','emp_num')->get();

            return $this->exportToExcelDropDown('KPI Template',
                //['Commissions' => [$first_row, ''], 'rating_types' => [$rating_types, 'E'],'users' => [$users, 'B', 'users']]
                ['KPIS' => [$first_row, ''], 'rating_types' => [$rating_types, 'F','rating_types']]
            );
        }
        return $this->bulkTemplate();
    }

    public function bulkTemplate(){

    }

    public function excelImportKPIQuestions(Request $request){
        if ($request->hasFile('template') && $request->filled('np_user_id_excel')) {
            try {
                $rows = \Excel::load($request->template)->get();
                if ($rows) {
                    $rows=$rows[0];
                    // return $rows;
                    foreach ($rows as $key => $row) {
                        if (isset($row['kpi_question']) && isset($row['weight']) &&isset($row['target']) &&isset($row['kpi_rating_type'])){
                            NPIndividualKPI::create(
                                ['n_p_user_id'=>$request->np_user_id_excel,'kpi_question'=>$row['kpi_question'],'weight'=>$row['weight'],'target'=>$row['target'],'target_words'=>$row['target_words'],
                                    'kpi_rating'=>$row['kpi_rating'],'kpi_rating_type'=>$row['kpi_rating_type'],
                                    'measurement'=>$row['measurement'],'data_source'=>$row['data_source'],'frequency_of_data'=>$row['frequency_of_data'],
                                    'responsible_collation_unit'=>$row['responsible_collation_unit'],
                                ]);
                        }
                    }
                    $np_user=NPUser::find($request->np_user_id_excel);
                    //$total_score=$np_user->individual_kpis->sum('score');
                    $np_user->update(['score'=>0]);
                    return ['status'=> 'success','details'=>'Successfully uploaded details','n_p_user_id'=>$request->np_user_id_excel];
                }
            } catch (\Exception $ex) {
                return ['status'=> 'error','details'=>$ex->getMessage()];
            }
        }
    }

    public function excelImportKPIQuestionsMultipleUser(Request $request){
        if ($request->hasFile('template')) {
            try {
                $rows = \Excel::load($request->template)->get();
                if ($rows) {
                    $rows=$rows[0];
                    //return $rows;
                    foreach ($rows as $key => $row) {
                        $user = User::where('emp_num', $row['empnum'])->first();
                        if ($user) {
                            if (isset($row['question'])){

                            }
                        }
                    }
                    return ['status'=> 'success','details'=>'Successfully uploaded details'];
                }
            } catch (\Exception $ex) {
                return ['status'=> 'error','details'=>$ex->getMessage()];
            }
        }
    }

    private function exportToExcelDropDown($worksheetname, $data)
    {


        return \Excel::create($worksheetname, function ($excel) use ($data) {
            foreach ($data as $sheetname => $realdata) {
                $excel->sheet($sheetname, function ($sheet) use ($realdata, $sheetname, $data) {
                    $last = collect($data)->last();
                    $sheet->fromArray($realdata[0]);


                    if ($sheetname == $last[2]) {


                        $i = 1;
                        foreach ($data as $key => $data) {

                            $Cell = $data[1];
                            if ($data[1] != '') {


                                $sheet->_parent->addNamedRange(
                                    new \PHPExcel_NamedRange(
                                        "sd{$data[1]}", $sheet->_parent->getSheet($i), "B2:B" . $sheet->_parent->getSheet($i)->getHighestRow()
                                    )
                                );
                                $i++;
                                for ($j = 2; $j <= 500; $j++) {


                                    $objValidation = $sheet->_parent->getSheet(0)->getCell("{$data[1]}$j")->getDataValidation();
                                    $objValidation->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                                    $objValidation->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                                    $objValidation->setAllowBlank(false);
                                    $objValidation->setShowInputMessage(true);
                                    $objValidation->setShowErrorMessage(true);
                                    $objValidation->setShowDropDown(true);
                                    $objValidation->setErrorTitle('Input error');
                                    $objValidation->setError('Value is not in list.');
                                    $objValidation->setPromptTitle('Pick from list');
                                    // $objValidation->setPrompt('Please pick a value from the drop-down list.');
                                    $objValidation->setFormula1("sd{$data[1]}");


                                }
                            }
                        }
                    }


                });
            }
        })->download('xlsx');
    }
}
