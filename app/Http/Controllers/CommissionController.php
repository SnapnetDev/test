<?php

namespace App\Http\Controllers;

use App\Commission;
use App\Opportunity;
use App\SpecificSalaryComponent;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class CommissionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware(['permission:manage_user','auth'])->except('myCommission');
    }
    public function index()
    {
        $commissions = Commission::all();
        return view('commissions.commissions', compact('commissions'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        if ($request->has('type')){
            return $this->destroy($request);
        }
        Validator::make($request->all(), [
            'opportunity_id' => 'required',
            'staff_id' => 'required',
            'commission' => 'required',
        ])->validate();

        $new = new Commission();
        $new->opportunity_id = $request->opportunity_id;
        $new->staff_id = $request->staff_id;
        $new->commission = $request->commission;
        $new->expected_commission = $request->expected_commission;
        $new->payment_status = 'pending';
        $new->save();
        return ['status' => 'success', 'details' => 'successfully added Staff Commission'];
    }

    public function payCommission($id)
    {
        Commission::where('id', $id)->update(['payment_status' => 'paid']);

        $staff_commission = Commission::where('id', $id)->with('user')->with('opportunity')->first();

        $new = new SpecificSalaryComponent();
        $new->emp_num = $staff_commission->user->emp_num;
        $new->name = $staff_commission->opportunity->project_name . ' Commission';
        $new->type = '1';
        $new->amount = $staff_commission->commission;
        $new->comment = $staff_commission->opportunity->project_name . ' Commission';;
        $new->duration = '1';
        $new->grants = '0';
        $new->status = '1';
        $new->save();

        return Redirect::back();
    }

    public function myCommission()
    {
        $user = Auth::user();
        $commissions = Commission::where('staff_id', $user->id)->with('opportunity.client')->get();
        $today = Carbon::today();

        return view('commissions.mycommissions', compact('commissions', 'user'));
    }

    public function userCommission($id)
    {
        $user = User::find($id);
        $commissions = Commission::where('staff_id', $user->id)->with('opportunity.client')->get();
        $today = Carbon::today();

        return view('commissions.mycommissions', compact('commissions', 'user'));

    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        Validator::make($request->all(), [
            'opportunity_id' => 'required',
            'staff_id' => 'required',
            'commission' => 'required',
            'payment_status' => 'required',
        ])->validate();

        $new = Commission::find($id);
        $new->opportunity_id = $request->opportunity_id;
        $new->staff_id = $request->staff_id;
        $new->commission = $request->commission;
        $new->payment_status = $request->payment_status;
        $new->save();
        return \redirect(route('commission.index'));
    }

    public function destroy($request)
    {
        Commission::where('id',$request->commission_id)->where('payment_status','!=','paid')->delete();
        return ['status'=>'success','details'=>'successfully deleted Commission'];
    }

    public function downloadTemplate()
    {

        $first_row = [
            ['Project' => 'project name', 'empNum' => '00', 'Expected Amount' => '0000', 'To Pay' => '00', 'Payment Date' => '', 'Status' => 'pending']
        ];
        $opportunities = Opportunity::select('id','project_name')->get();
        $users = User::select('name','emp_num')->get();
        return $this->exportToExcelDropDown('Commissions Template',
            ['Commissions' => [$first_row, ''], 'opportunities' => [$opportunities, 'A'],'users' => [$users, 'B', 'users']]
        );

        /* $opportunities = Opportunity::all();
         $view = 'commissions.exceltemplate';
         $name = 'Opportunities Template';
         //return view("$view",compact('opportunities'));
         return \Excel::create($name, function ($excel) use ($view, $name, $opportunities) {
             $excel->sheet("Sheet 1", function ($sheet) use ($view, $opportunities) {
                 $sheet->loadView("$view", compact('opportunities'))
                     ->setOrientation('landscape');
             });

         })->export('xlsx');*/
    }

    public function excelImport(Request $request)
    {

        if ($request->hasFile('template')) {
            try {
                $rows = Excel::load($request->template)->get();
                if ($rows) {
                    $rows=$rows[0];
                    //return $rows;
                    foreach ($rows as $key => $row) {
                        $user = User::where('emp_num', $row['empnum'])->first();
                        if ($user) {
                            $opportunity = Opportunity::where('project_name', $row['project'])->first();
                            $new = new Commission();
                            $new->opportunity_id = $opportunity->id;
                            $new->staff_id = $user->id;
                            $new->commission = $row['to_pay'];
                            $new->expected_commission = $row['expected_amount'];
                            $new->payment_status = $row['status'];
                            $new->payment_date = $row['status'];
                            $new->save();
                        }
                    }
                    return ['status'=> 'success','details'=>'Successfully uploaded details'];
                }
            } catch (\Exception $ex) {
                return ['status'=> 'error','details'=>$ex->getMessage()];
            }
        }

        return ['status'=> 'error','details'=>'Error uploading'];

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
