<?php

namespace App\Http\Controllers;

use App\Setting;
use App\Traits\BiometricTrait;
use App\Company;
use App\ManualAttendance;
use App\Notifications\ManualAttendanceNotify;
use App\User;
use App\Workflow;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManualAttendanceController extends Controller
{
    use biometrictrait;
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function manualAttendance(Request $request){
        $date=Carbon::today();
        if ($request->filled('date')){
            $date=Carbon::parse($request->date);
        }
        $users=User::where('company_id',companyId())->whereIn('status',[0,1])->get();
        $manuals=ManualAttendance::whereDate('date',$date->format('Y-m-d'))->where('company_id',companyId())->get();
        if ($request->type=='excel'){
            $view = 'attendance.manual.excel_manual_attendance';
            $name=$date->format('d M, Y').' Manual Attendance';
            return \Excel::create($name, function ($excel) use ($view, $date, $manuals, $users, $name) {
                $excel->sheet($name, function ($sheet) use ($view, $date, $manuals, $users) {
                    $sheet->loadView("$view", compact('date', 'manuals', 'users'))
                        ->setOrientation('landscape');
                });
            })->export('xlsx');
        }
        return view('attendance.manual.manual_daily_attendance',compact('date','manuals','users'));
    }

    public function storeManualAttendance(Request $request){
        //return $request->all();
        $created_by=User::find(Auth::id());
        $user_id=$request->user_id;
        $time_in=$request->time_in;
        $time_out=$request->time_out;
        $company_id=companyId();
        $company=Company::find($company_id);
        $date=$request->date;
        $reason=$request->reason;

        $workflow_details_response=$this->getWorkflowDetails();
        $workflow_details=$workflow_details_response['workflow_details'];
        $first_stage_users=$workflow_details_response['first_stage_users'];
        $workflow=$workflow_details_response['workflow'];

        $ma= ManualAttendance::updateorcreate(['user_id'=>$user_id,'date'=>$date,'status'=>'pending'],
            ['company_id'=>$company->id,'created_by'=>$created_by->id,'time_in'=>$date.' '.$time_in,'time_out'=>$date.' '.$time_out,
                'reason'=>$reason,
                'workflow_id'=>$workflow->id,'workflow_details'=>$workflow_details
            ]);
        $first_stage_users=User::whereIn('id',$first_stage_users)->get();
        foreach ($first_stage_users as $user) {
            $user->notify(new ManualAttendanceNotify($ma->id));
        }
        return 'done';
    }
    private function getWorkflowDetails(){
        $workflow=1;
        $attendance_workflow=Setting::where('name','attendance_workflow')->where('company_id',companyId())->first();
        if ($attendance_workflow){
            $workflow=$attendance_workflow->value;
        }
        $workflow=Workflow::find($workflow);
        $workflow_details=[];
        $first_stage_users=[];
        foreach ($workflow->stages as $stage){
            $type=$this->stageTypeDetails($stage)['type'];
            $users=$this->stageTypeDetails($stage)['users'];
            $status='inactive';
            if ($stage->position=='0'){$status='pending'; $first_stage_users=$users;}
            $stage_details=[
                'id'=>$stage->id,
                'position'=>$stage->position,
                'type'=>$type,
                'users'=>$users,
                'status'=>$status,
                'approved_by'=>''
            ];
            $workflow_details[]=$stage_details;
        }
        $workflow_details=collect($workflow_details);
        return ['workflow'=>$workflow,'workflow_details'=>$workflow_details,'first_stage_users'=>$first_stage_users];

    }
    private function stageTypeDetails($stage){
        if ($stage->type=='1'){
            $users=[$stage->user_id];
            return ['type'=>'user','users'=>$users];
        }
        elseif ($stage->type=='2'){
            $users=User::where('role_id',$stage->role_id)->pluck('id')->toArray();
            $users=collect($users)->map(function ($user){return (int) $user;});
            return ['type'=>'role','users'=>$users];
        }
        elseif ($stage->type=='3'){
            $users=$stage->group->users->pluck('id')->toArray();
            $users=collect($users)->map(function ($user){return (int) $user;});
            return ['type'=>'group','users'=>$users];
        }
        else{
            $users=[];
            return ['type'=>'','users'=>$users];
        }
    }
    public function manualAttendanceExcelTemplate(Request $request){
        $first_row = [
            ['EmpNum'=>'','date'=>$request->date, 'time_in'=>'','time_out'=>'','reason'=>'']
        ];
        $users = User::where('company_id',companyId())->where('status','1')->select('name','emp_num','company_id')->get();
        $users=$users->map(function ($name) {
            return ['name'=>$name->name,'emp_num'=>$name->emp_num];
        });
        return $this->exportToExcelDropDown('Manual Attendance Template',
            ['Attendance' => [$first_row, ''],'users' => [$users, 'A', 'users']]
        );
    }

    public function manualAttendanceExcel(Request $request){
        // return $request->all();
        $sm=User::find(Auth::id());
        $company_id=companyId();
        $company=Company::find($company_id);
        $created_by=User::find(Auth::id());

        $workflow_details_response=$this->getWorkflowDetails();
        $workflow_details=$workflow_details_response['workflow_details'];
        $first_stage_users=$workflow_details_response['first_stage_users'];
        $workflow=$workflow_details_response['workflow'];

        if ($request->hasFile('template')) {
            try {
                $rows = \Excel::load($request->template)->get();
                if ($rows) {
                    $rows=$rows[0];
                    //return $rows;
                    foreach ($rows as $key => $row) {
                        $user = User::where('emp_num', $row['empnum'])->where('company_id',$company_id)->first();
                        if ($user) {
                            if (isset($row['time_in'])&&isset($row['time_out'])&&isset($row['reason'])&&isset($row['date'])){
                                $date=Carbon::parse($row['date'])->format('Y-m-d');
                                $time_in=$date.' '.Carbon::parse($row['time_in'])->format('H:i:s');
                                $time_out=$date.' '.Carbon::parse($row['time_out'])->format('H:i:s');
                                $ma= ManualAttendance::updateorcreate(['user_id'=>$user->id,'date'=>$date,'status'=>'pending'],
                                    ['company_id'=>$company->id,'created_by'=>$created_by->id,'time_in'=>$time_in,'time_out'=>$time_out,
                                        'reason'=>$row['reason'],
                                        'workflow_id'=>$workflow->id,'workflow_details'=>$workflow_details
                                    ]);
                            }
                        }
                    }
                    $first_stage_users=User::whereIn('id',$first_stage_users)->get();
                    foreach ($first_stage_users as $user) {
                        $user->notify(new ManualAttendanceNotify($ma->id));
                    }
                    return ['status'=> 'success','details'=>'Successfully uploaded details'];
                }
            } catch (\Exception $ex) {
                return ['status'=> 'error','details'=>$ex->getMessage()];
            }
        }
    }

    public function approveMAStage($id,Request $request){
        $response='declined';
        if ($request->status==1){
            $response='approved';
        }
        $user=User::find(Auth::id());
        $ma=ManualAttendance::find($id);
        $pending_stage= collect($ma->workflow_details)->where('status','pending')->first();
        if (!$pending_stage){
            return 'no pending';
        }
        if (!in_array($user->id, $pending_stage['users'])) {
           //fail
            return 'aa';
        }
        $pending_stage_position= collect($ma->workflow_details)->where('status','pending')->first()['position'];
        $next_stage_position= $pending_stage_position+1;
        $next_stage=collect($ma->workflow_details)->where('position',$next_stage_position)->first();
        $updated_workflow_details= collect($ma->workflow_details)->map(function ($stage) use($user,$response){
            if ($stage['status']=='pending'){
                $stage['status']=$response;
                $stage['approved_by']=$user->id;
                return $stage;
            }
            return $stage;
        });
        if ($next_stage && $response=='approved'){
            $updated_workflow_details=$updated_workflow_details->map(function($stage)use($next_stage_position,$id){
                if ($stage['position']==$next_stage_position){
                    $stage['status']='pending';
                    $stage_users=User::whereIn('id',$stage['users'])->get();
                    foreach ($stage_users as $user) {
                        $user->notify(new ManualAttendanceNotify($id));
                    }
                    return $stage;
                }
                return $stage;
            });
        }
        ManualAttendance::where('id',$id)->update(['workflow_details'=>$updated_workflow_details]);
        if (!$next_stage || $response=='declined'){
            return  $this->approval($id,$response);
        }
        //approved stage that isn't the last
        return 'approved stage';

    }

    private function approval($id,$response)
    {
        $manual = ManualAttendance::find($id);
        if ($response == 'approved') {
            //approve
            ManualAttendance::where('id',$id)->update(['status'=>'approved']);
            if ($manual->time_in){
                //0 = clockin
                $data = ['emp_num' => $manual->user->emp_num, 'time' => $manual->time_in, 'status_id' => 0, 'verify_id' => 1,'serial'=>00];
                $this->saveAttendance($data);
            }
            if ($manual->time_out){
                //1 = clockout
                $data = ['emp_num' => $manual->user->emp_num, 'time' => $manual->time_out, 'status_id' => 1, 'verify_id' => 1,'serial'=>00];
                $this->saveAttendance($data);
            }
            return 'approved';

        } else {
            ManualAttendance::where('id',$id)->update(['status'=>'declined']);
            return 'declined';
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
