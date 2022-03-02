<?php

namespace App\Http\Controllers;

use App\AttendancePayroll;
use App\AttendancePayrollDetail;
use App\AttendanceReport;
use App\Jobs\ProcessAttendancePayrollJob;
use App\Setting;
use App\Traits\Attendance as AttendanceTrait;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class AttendancePayrollController extends Controller
{
    use attendanceTrait;
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function attendancePayrollSetting(Request $request)
    {
        $pay_early=$this->prepareSetting('pay_early',1);
        $pay_late=$this->prepareSetting('pay_late',0);
        $pay_absent=$this->prepareSetting('pay_absent',0);
        $pay_off=$this->prepareSetting('pay_off',0);
        $pay_full_days=$this->prepareSetting('pay_full_days',25);
        $pay_full_days=$this->prepareSetting('divide_by_days',25);

        $attendance_payroll_settings=[
            'pay_early'=>$pay_early->value,
            'pay_late'=>$pay_late->value,
            'pay_absent'=>$pay_absent->value,
            'pay_off'=>$pay_off->value,
            'pay_full_days'=>$pay_full_days->value,
            'divide_by_days'=>$pay_full_days->value
        ];
        return view('payrollsettings.attendance_payroll', compact('attendance_payroll_settings'));
    }
    private function prepareSetting($variable,$value){
        $company_id=companyId();
        $variable_value=Setting::where('name',$variable)->where('company_id',$company_id)->first();
        if (!$variable_value){
            $variable_value=Setting::create(['name'=>$variable,'value'=>$value,'company_id'=>$company_id]);
        }
        return $variable_value;
    }

    public function saveAttendancePayrollSettings(Request $request){
        $pay_early = ($request->pay_early==1) ? 1 : 0 ;
        $pay_late = ($request->pay_late==1) ? 1 : 0 ;
        $pay_absent = ($request->pay_absent==1) ? 1 : 0 ;
        $pay_off = ($request->pay_off==1) ? 1 : 0 ;

        $pay_full_days = $request->pay_full_days;
        $divide_by_days = $request->divide_by_days;

        $updatesettings=[
            'pay_early'=>$pay_early,
            'pay_late'=>$pay_late,
            'pay_absent'=>$pay_absent,
            'pay_off'=>$pay_off,

            'pay_full_days'=>$pay_full_days,
            'divide_by_days'=>$divide_by_days,
        ];
        $this->bulkUpdateSettings($updatesettings);
        return  response()->json('success',200);
    }

    private function bulkupdateSettings($arr){
        foreach ($arr as $key=>$item){
            Setting::where('name',$key)->update(['value'=>$item]);
        }
    }

    public function getSetting($variable){
      return  $variable_value=Setting::where('name',$variable)->where('company_id',companyId())->first()->value;
    }

    public function data(Request $request){
        return $this->runPayroll($request);
    }

    public function payroll(){
        $date = Carbon::today();
        $payrolls=AttendancePayroll::all();
        return view('attendance.payroll.payroll',compact('payrolls','date'));
    }
    public function checkPayroll(Request $request){
        $request->all();
         if (!$request->filled(['date','start','end'])){
             return 'error';
         }
        $date = Carbon::createFromFormat('m-Y', $request->date);
        $finance=AttendancePayroll::where('month',$date->format('m'))->where('year',$date->format('Y'))->first();
        $show_button='yes';
        $details='No payroll has been ran for '.$date->format('M').' '.$date->format('Y');
         if ($finance){
             $details='Payroll already exists for '.$date->format('M').' '.$date->format('Y').' from '.$finance->start.' to '.$finance->end;
             if ($finance->status=='closed'){
                 $details='Payroll closed for '.$date->format('M').' '.$date->format('Y').' from '.$finance->start.' to '.$finance->end;
                 $show_button='no';
             }
         }
        return ['details'=>$details,'show_button'=>$show_button,'date'=>$request->date,'start'=>$request->start,'end'=>$request->end];
    }

    public function runPayroll(Request $request){
        //return $request->all();
            $company_id = companyId();
        if ($request->filled(['confirmdate','confirmstart','confirmend'])){
            $user=Auth::user()->id;
            $start = Carbon::parse($request->confirmstart)->format('Y-m-d');
            $end = Carbon::parse($request->confirmend)->format('Y-m-d');

            $date = Carbon::createFromFormat('m-Y', $request->confirmdate);

            $month=$date->format('m');
            $year=$date->format('Y');
            $statuses=[];
            $pay_early=$this->getSetting('pay_early');
            $pay_late=$this->getSetting('pay_late');
            $pay_absent=$this->getSetting('pay_absent');
            $pay_off=$this->getSetting('pay_off');

            $pay_full_days=$this->getSetting('pay_full_days');
            $divide_by_days=$this->getSetting('divide_by_days');

            if ($pay_early=='1'){
                $statuses[]='early';
            }if ($pay_late=='1'){
                $statuses[]='late';
            }if ($pay_absent=='1'){
                $statuses[]='absent';
            }if ($pay_off=='1'){
                $statuses[]='off';
            }
            $settings=['status'=>$statuses,'pay_full_days'=>$pay_full_days,'divide_by_days'=>$divide_by_days];
            ProcessAttendancePayrollJob::dispatch($year,$month,$start,$end,$user,$company_id,$settings);
        }
        return 'success';
        return Redirect::back();
    }
    public function monthlyPayroll($id,Request $request){
        $company_id=companyId();
        $payroll=AttendancePayroll::findorfail($id);
        $date=Carbon::create($payroll->year,$payroll->month,$payroll->day);
        $users_payrolls=AttendancePayrollDetail::where('attendance_payroll_id',$payroll->id)->where('company_id',$company_id)->get();
        if ($request->type=='excel'){
            $view = 'attendance.payroll.excelmonthly';
            $name=$date->format('M, Y').' Finance report';
            return \Excel::create($name, function ($excel) use ($view, $users_payrolls, $date,$name,$payroll) {
                $excel->sheet($name, function ($sheet) use ($view, $users_payrolls,$date,$payroll) {
                    $sheet->loadView("$view", compact('users_payrolls', 'date','payroll'))
                        ->setOrientation('landscape');
                });
            })->export('xlsx');
        }
        return view('attendance.payroll.monthly',compact('payroll','users_payrolls','date'));
    }

    public function recalculate($id){
        $apd=AttendancePayrollDetail::findorfail($id);
        $payroll=AttendancePayroll::where('id',$apd->attendance_payroll_id)->first();
        $user=User::findorfail($apd->user_id);
        if ($payroll->status=='open'){
            //recalculate
            $pay_full_days=$this->getSetting('pay_full_days');
            $settings=['status'=>$payroll->pay_status,'pay_full_days'=>$pay_full_days];
            ProcessAttendancePayrollJob::dispatch($payroll->year,$payroll->month,$payroll->start,
                $payroll->end,$payroll->created_by,$user->company_id,$settings,[$user->id]);
            return Redirect::back();
        }

    }
    public function closePayroll($id){
        $payroll=AttendancePayroll::find($id);
        if($payroll->status=='open'){
            $payroll->status='closed';
            $payroll->save();
            return 'success';
        }
        return 'error';
    }
    public  function previousPayroll($date){
        $date = Carbon::createFromFormat('m-Y', $date)->subMonth();
        $previous=AttendancePayroll::where('month',$date->format('m'))->where('year',$date->format('Y'))->first();
        if ($previous){
           $previous= Carbon::parse($previous->end)->format('m/d/Y');
        }
        else{
            $previous='none';
        }
        return $previous;
    }
    public function downloadPayslip($apd){
        $attendance_payroll_detail=AttendancePayrollDetail::find($apd);
        $view = 'attendance.payroll.payslip';
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadView($view, compact('attendance_payroll_detail'))->setPaper('a4', 'landscape');
        return $pdf->stream();
    }
    public function sendSinglePayslip($apd){
        $this->staffPayslipEmail($apd);
        return 'success';
    }

    public function sendPayslips($payroll){
        $payroll=AttendancePayroll::find($payroll);
        foreach ($payroll->attendance_payroll_details as $apd){
            $this->staffPayslipEmail($apd);
        }
        return 'success';
    }

}
