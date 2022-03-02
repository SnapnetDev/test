<?php

namespace App\Http\Controllers;

use App\NPIndividualKPI;
use App\NPMeasurementPeriod;
use App\NPUser;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NPEmployeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function employeeKPIs(){
        $kpi_periods=NPMeasurementPeriod::whereHas('np_users',function ($query){
            $query->where('user_id',auth()->id());
        })->orderBy('id','desc')->get();
        $user=auth()->user();
        return view('performance.nova.employees.employee_kpis',compact('kpi_periods','user'));
    }

    public function loadMeasurementPeriodKPI(Request $request){
        $user=User::find($request->user);
        $measurement_period=NPMeasurementPeriod::find($request->measurement_period);
        $np_user=NPUser::where('user_id',$user->id)->where('n_p_measurement_period_id',$measurement_period->id)->first();
        $kpis=NPIndividualKPI::where('n_p_user_id',$np_user->id)->get();
        return view('performance.nova.employees.load_mp_kpis',compact('kpis','user','measurement_period','np_user'));
    }

    public function submitResponseForKPI(Request $request){
        $kpi_id=$request->kpi_id;
        $request->score;
        $request->comment;
        $user=Auth::user();
        $kpi=NPIndividualKPI::where('user_id',$user->id)->where('id',$kpi_id)->first();
        if (!$kpi){
            return 'you cannot submit a response for a KPI that is not yours';
        }
        NPIndividualKPI::where('id',$kpi_id)->update(['user_score'=>$request->score,'user_comment'=>$request->comment]);
        return $kpi;
    }

    public function managerSubmitResponseForKPI(Request $request){
        $request->kpi_id;
        $request->answer;
        $manager=Auth::user();
        NPIndividualKPI::where('id',$request->kpi_id)->update(['manager_score'=>$request->answer,'manager_comment'=>$request->comment]);
    }

     public function downloadMyKPIForMeasurementPeriod(Request $request){
         $np_user=NPUser::where('id',$request->np_user)->with('user')->first();
         $kpis=NPIndividualKPI::where('n_p_user_id',$request->np_user)->get();
         $view='performance.nova.employees.excelKPI';
         $name=$np_user->user->name;
         return \Excel::create('Individual KPI', function ($excel) use ($view, $name,$kpis) {
             $excel->sheet($name, function ($sheet) use ($view, $kpis) {
                 $sheet->loadView("$view", compact('kpis'))
                     ->setOrientation('landscape');
             });
         })->export('xlsx');

     }
}
