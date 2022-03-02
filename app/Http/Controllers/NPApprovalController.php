<?php

namespace App\Http\Controllers;

use App\Notifications\ApproveNPNotification;
use App\NPMeasurementPeriod;
use App\NPUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NPApprovalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function approvals(){
        $user=Auth::user();
        $mp=NPMeasurementPeriod::orderBy('id','DESC')->first();
        $sos=NPUser::where('sos_id',$user->id)->where('n_p_measurement_period_id',$mp->id)->with('user')->get();
        $line_executive=NPUser::where('line_executive_id',$user->id)->where('n_p_measurement_period_id',$mp->id)->with('user')->get();
        return view('performance.nova.approvals',compact('sos','line_executive','mp'));
    }

    public function sendApproval(Request $request){
        $np_user=$request->np_user;
        $approve_as=$request->approve_as;
        $comment=$request->comment;
        if ($request->response=='approve'){
           return $this->approve($np_user,$approve_as,$comment);
        }
        else{
            return $this->reject($np_user,$approve_as,$comment);
        }
    }

    private function approve($np_user,$approve_as,$comment){
        $user=Auth::user();
        $np=NPUser::find($np_user);
        if ($approve_as=='sos'){
            NPUser::where('id',$np_user)->update(['status'=>'sos_approve','sos_comment'=>$comment,'sos_response'=>'approve','line_executive_response'=>'']);
            $line_executive= $user->plmanager;
            if ($line_executive){
                $line_executive->notify(new ApproveNPNotification($np));
                NPUser::where('id',$np_user)->update(['line_executive_id'=>$line_executive->id]);
                return 'SoS approved';
            }
            else{
                NPUser::where('id',$np_user)->update(['status'=>'approved']);
                return 'approved';
            }
        }
        elseif($approve_as=='line_executive'){
            NPUser::where('id',$np_user)
                ->update(['status'=>'approved','line_executive_comment'=>$comment,'line_executive_response'=>'approve',]);
            return 'approved';
        }
    }

    private function reject($np_user,$approve_as,$comment){
        if ($approve_as=='sos'){
            NPUser::where('id',$np_user)->update(['status'=>'sos_reject','sos_comment'=>$comment,'sos_response'=>'reject']);
        }
        elseif($approve_as=='line_executive'){
            NPUser::where('id',$np_user)
                ->update(['status'=>'line_executive_reject','line_executive_comment'=>$comment,'line_executive_response'=>'reject']);
        }
        NPUser::where('id',$np_user)->update(['status'=>'pending']);
    }
}
