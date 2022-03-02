<?php

namespace App\Http\Controllers;

use App\NPIndividualKPI;
use App\NPMeasurementPeriod;
use App\NPUser;
use App\User;
use Illuminate\Http\Request;

class NPSetupController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function periodList(){
        $kpi_periods=NPMeasurementPeriod::orderBy('id','DESC')->get();
        return view('performance.nova.admin.periods',compact('kpi_periods'));
    }

    public function loadUsers(Request $request){
        $measurement_period=NPMeasurementPeriod::find($request->measurement_period);
        if ($measurement_period->status=='pending'){
            //load others users that are active but are not in NPUsers
            $users_not_in_measurement_period=User::whereIn('status',[0,1])->whereNotIn('id',$measurement_period->np_users->pluck('id'))->get();
            foreach ($users_not_in_measurement_period as $exist){
                NPUser::updateorcreate(['user_id'=>$exist->id, 'n_p_measurement_period_id'=>$measurement_period->id]);
            }
        }
        $users=NPUser::where('n_p_measurement_period_id',$measurement_period->id)->with('user')->get();
        return view('performance.nova.admin.load_users',compact('users','measurement_period'));
    }

    public function loadReport(Request $request){
        $measurement_period=NPMeasurementPeriod::find($request->measurement_period);
        $users=NPUser::where('n_p_measurement_period_id',$measurement_period->id)->with('user')->get();
        return view('performance.nova.admin.load_report',compact('users','measurement_period'));
    }

    public $performance_grades=["Poor"=>[0,39], "Below Average"=>[40,55], "Average"=>[56,69], "Above Average"=>[70,89],"Outstanding"=>[90,100]];

    public function reportData(Request $request){
        $measurement_period=NPMeasurementPeriod::find($request->measurement_period);
        $type=$request->type;
        if ($type=='general'){
            foreach ($this->performance_grades as $key => $performance_grade) {
                $data['labels'][]=$key;
                $data['data'][]=NPUser::where('n_p_measurement_period_id',$measurement_period->id)->whereBetween('score',$performance_grade)->count();
            }
            return $data;
        }
        if ($type=='departmental'){
            $departments=\App\Department::where('company_id',companyId())->get();
            foreach ($departments as $department) {
                $data['labels'][]=$department->name;
            }
            $pk=0;
            foreach ($this->performance_grades as $key => $performance_grade) {
                $data['datasets'][$pk]['label']=$key;
                foreach ($departments as $department) {
                    $user_ids=$department->users->pluck('id');

                    $data['datasets'][$pk]['data'][]=
                        NPUser::where('n_p_measurement_period_id',$measurement_period->id)->whereBetween('score',$performance_grade)->whereIn('user_id', $user_ids)->count();
                }
                $pk++;
            }
            return $data;
        }
    }

    public function loadMeasurementPeriodKPI(Request $request){
        $np_user=NPUser::find($request->user);
        $measurement_period=$np_user->measurement_period;
        $kpis=NPIndividualKPI::where('n_p_user_id',$request->user)->get();
        return view('performance.nova.admin.load_mp_kpis',compact('kpis','np_user','measurement_period'));
    }

    public function usersReportExport(Request $request){
        $measurement_period=NPMeasurementPeriod::find($request->measurement_period);
        $users=NPUser::where('n_p_measurement_period_id',$measurement_period->id)->with('user')->get();
    }
}
