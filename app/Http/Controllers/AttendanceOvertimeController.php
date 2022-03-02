<?php

namespace App\Http\Controllers;

use App\Attendance;
use App\AttendanceReport;
use App\Notifications\AttendanceOvertimeNotify;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceOvertimeController extends Controller
{
    public function approveAOStage($id,Request $request){
        $response='declined';
        if ($request->status==1){
            $response='approved';
        }
        $user=User::find(Auth::id());
        $attendance=Attendance::find($id);
        $pending_stage= collect($attendance->workflow_details)->where('status','pending')->first();
        if (!$pending_stage){
            return 'no pending';
        }
        if (!in_array($user->id, $pending_stage['users'])) {
            //fail
            return 'aa';
        }
        $pending_stage_position= collect($attendance->workflow_details)->where('status','pending')->first()['position'];
        $next_stage_position= $pending_stage_position+1;
        $next_stage=collect($attendance->workflow_details)->where('position',$next_stage_position)->first();
        $updated_workflow_details= collect($attendance->workflow_details)->map(function ($stage) use($user,$response){
            if ($stage['status']=='pending'){
                $stage['status']=$response;
                $stage['approved_by']=$user->id;
                return $stage;
            }
            return $stage;
        });
        if ($next_stage && $response=='approved'){
            $updated_workflow_details=$updated_workflow_details->map(function($stage)use($next_stage_position,$id,$attendance){
                if ($stage['position']==$next_stage_position){
                    $stage['status']='pending';
                    $stage_users=User::whereIn('id',$stage['users'])->get();
                    foreach ($stage_users as $user) {
                        $user->notify(new AttendanceOvertimeNotify($attendance));
                    }
                    return $stage;
                }
                return $stage;
            });
        }
        Attendance::where('id',$id)->update(['workflow_details'=>$updated_workflow_details]);
        if (!$next_stage || $response=='declined'){
            return  $this->approval($id,$response);
        }
        //approved stage that isn't the last
        return 'approved stage';

    }

    private function approval($id,$response)
    {
        if ($response == 'approved') {
            Attendance::where('id',$id)->update(['workflow_status'=>'approved']);
            $ar=AttendanceReport::where('attendance_id',$id)->first();
            AttendanceReport::where('attendance_id',$id)->update(['approved_overtime'=>$ar->overtime]);
            return 'approved';
        } else {
            Attendance::where('id',$id)->update(['workflow_status'=>'declined']);
            AttendanceReport::where('attendance_id',$id)->update(['approved_overtime'=>0]);
            return 'declined';
        }
    }
}
