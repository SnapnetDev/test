<?php

namespace App\Http\Controllers;

use App\Notifications\ApproveNPNotification;
use App\NPIndividualKPI;
use App\NPMeasurementPeriod;
use App\NPUser;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NPMeasurementPeriodController extends Controller
{
    public function index(){

    }

    public function create(){
        //NPMeasurementPeriod::create(['name'=>'First Quarter 2020','from'=>'2020-01-01','to'=>'2020-03-31','start'=>'2020-04-01','end'=>'2020-04-30']);
        return view('');
    }

    public function store(Request $request){
        if ($request->type=='edit'){
            return $this->edit($request);
        }
        $count=NPMeasurementPeriod::whereIn('status',['pending','active'])->count();
        if ($count>=1){
            return ['status'=>'pending','details'=>'You cannot create a new Period if previous is still active'];
        }
        try{
            $measurement_period=NPMeasurementPeriod::create(['name'=>$request->name,'from'=>$request->from,'to'=>$request->to,
                'start'=>$request->start,'end'=>$request->end]);
            $active_users=User::whereIn('status',[0,1])->select('id','name')->get();
            foreach ($active_users as $user){
                NPUser::updateorcreate(['user_id'=>$user->id, 'n_p_measurement_period_id'=>$measurement_period->id]);
            }
            return ['status'=>'success','details'=>'Successfully Created Measurement Period'];
        }
        catch (\Exception $exception){
            return ['status'=>'fail','message'=>$exception->getMessage()];
        }
    }

    public function edit(Request $request){
        $measurement_period=NPMeasurementPeriod::where('id',$request->id)->where('status','!=','disabled')->update(['name'=>$request->name,'from'=>$request->from,'to'=>$request->to,
            'start'=>$request->start,'end'=>$request->end]);
        return ['status'=>'success','details'=>'Successfully Updated Measurement Period'];
    }

    public function changeMeasurementPeriodStatus(Request $request){
        if ($request->filled('status') && $request->filled('measurement_period')){
            $status=$request->status;
            $message= 'Successfully updated Measurement Period Status to '.$status;
            if ($status=='active'){
                $message=$message.' Staff can now fill their KPI';
            }
            elseif ($status=='disabled'){
                $message=$message.' Staff can not fill KPI anymore';
            }
            NPMeasurementPeriod::where('id',$request->measurement_period)->update(['status'=>$status]);
            return ['status'=>'success','details'=>$message];
        };
    }

    public function changeNPUserStatus(Request $request){
        $user=Auth::user();
        $status=$request->status;
        $np_user=$request->np_user;
        $np=NPUser::find($np_user);
        if ($status=='supervisor_complete'){
            NPUser::where('id',$np_user)->update(['status'=>$status,'manager_id'=>$user->id,'manager_response'=>'approve','manager_comment'=>'']);
            $sos= $user->plmanager;
            if ($sos){
                NPUser::where('id',$np_user)->update(['sos_id'=>$sos->id,'sos_response'=>'']);
                $sos->notify(new ApproveNPNotification($np));
            }
            else{
                NPUser::where('id',$np_user)->update(['status'=>'approved']);
            }
        }

        return ['status'=>'success','details'=>'Successfully updated','np_user'=>NPUser::find($np_user)];
    }


}
