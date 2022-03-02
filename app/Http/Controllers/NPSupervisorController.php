<?php

namespace App\Http\Controllers;

use App\NPIndividualKPI;
use App\NPMeasurementPeriod;
use App\NPUser;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NPSupervisorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function periodList(){
        $kpi_periods=NPMeasurementPeriod::orderBy('id','DESC')->get();
        return view('performance.nova.supervisor.periods',compact('kpi_periods'));
    }

    public function loadUsers(Request $request){
        $measurement_period=NPMeasurementPeriod::find($request->measurement_period);
        $my_drs=\auth()->user()->pdreports->pluck('id');
        $users=NPUser::where('n_p_measurement_period_id',$measurement_period->id)->with('user')->whereIn('user_id',$my_drs)->get();
        return view('performance.nova.supervisor.load_users',compact('users','measurement_period'));
    }

    public function loadMeasurementPeriodKPI(Request $request){
        $np_user=NPUser::find($request->user);
        $measurement_period=$np_user->measurement_period;
        $kpis=NPIndividualKPI::where('n_p_user_id',$request->user)->get();
        return view('performance.nova.supervisor.load_mp_kpis',compact('kpis','np_user','measurement_period'));
    }

    public function submitResponseForKPI(Request $request){
        $kpi_id=$request->kpi_id;
        $request->comment;
        $user=Auth::user();
        $kpi=NPIndividualKPI::where('id',$kpi_id)->first();
        if (!$kpi){
            return ['status'=>'fail','details'=>'You cannot submit a response for a KPI that is not for a staff under you'];
        }
        if ($kpi->kpi_rating_type!='punitive' && $request->actual>$kpi->target){
            return ['status'=>'fail','details'=>'Actual cannot be greater than target'];
        }
        if ($kpi->kpi_rating_type=='punitive'){
            $score=0;
            if ($request->actual!='0'){
                $score=$kpi->weight;
            }
        }
        else{
            $score=($request->actual/$kpi->target)*$kpi->weight;
        }
        $score=round($score,3);
        NPIndividualKPI::where('id',$kpi_id)->update(['actual'=>$request->actual,'manager_comment'=>$request->comment,'score'=>$score]);
        $total_score=$kpi->np_user->individual_kpis->sum('score');
        $total_score=round($total_score);
        $kpi->np_user->update(['score'=>$total_score]);
        return ['status'=>'success','kpi'=>$kpi];
    }
}
