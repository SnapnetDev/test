<?php

namespace App\Traits;

use App\AttendanceDetail;
use App\BiometricDevice;
use App\CommandLog;
use App\Jobs\ProcessSingleAttendanceJob;
use App\User;
use App\UserDailyShift;
use Illuminate\Support\Facades\Response;

trait BiometricTrait
{
    public function commandToSend($device_serial)
    {
        $logs = CommandLog::where('status', 'pending')->where('biometric_serial', $device_serial)->limit('3')->get();
        if (count($logs)>0) {
            $commands='';
            foreach ($logs as $log){
                $command = $log->command.'\n';
                $commands=$commands.$command;
            }
            return $this->commandresponse($commands);
        }
        else{
            return $this->returnOk();
        }
    }
    public function deleteUser($user_id)
    {
        $user = User::findorFail($user_id);
        $contents = "DATA DELETE USERINFO PIN=$user->emp_num\tName=$user->name\tPri=0";
        $this->logCommand($contents,$user->company_id,$user->company->biometric_serial);
    }


    public function createUser($user_id)
    {
        $user = User::findorFail($user_id);
        if ($user->role_id=='1'){//admin
            $contents = "DATA UPDATE USERINFO PIN=$user->emp_num\tName=$user->name\tPri=14\tPasswd=3696\t";
        }
        else{
            $contents = "DATA UPDATE USERINFO PIN=$user->emp_num\tName=$user->name\tPri=0\tPasswd=1117\t";
        }
        $this->logCommand($contents,$user->company_id,$user->company->biometric_serial);
    }

    public function updateLogOnReturn($array){
        foreach ($array as $item){
            CommandLog::where('id',$item['id'])->update(['status'=>$item['status']]);
        }
    }

    public function logCommand($command,$company_id,$biometric_serial){
        $log=new CommandLog();
        $log->command='XX';
        $log->status='queued';
        $log->company_id=$company_id;
        $log->biometric_serial=$biometric_serial;
        $log->save();
        $contents = "C:$log->id:".$command;
        CommandLog::where('id',$log->id)->update(['status'=>'pending','command'=>$contents]);
    }

    public function createMultipleUsers($users)
    {
        foreach ($users as $user) {
            $this->createUser($user->id);
        }
    }

    public function deleteMultipleUsers($users)
    {
        foreach ($users as $user) {
            $this->deleteUser($user->id);
        }
    }

    private function returnOk()
    {
        $contents = 'OK';
        return $this->commandresponse($contents);
    }

    public function commandresponse($command){
        $statusCode = 200;
        $response = Response::make($command, $statusCode);
        $response->header('Content-Type', 'text/plain');
        return $response;
    }

    public function saveAttendance($data)
    {
        $long = null;
        $lat = null;
        $imei = null;
        if (array_key_exists('long', $data)) {
            $long = $data['long'];
        }
        if (array_key_exists('lat', $data)) {
            $lat = $data['lat'];
        }
        if (array_key_exists('imei', $data)) {
            $imei = $data['imei'];
        }
        $overflow='0';
        //$station=Company::where('biometric_serial',$data['serial'])->first();
        $save_date=date('Y-m-d', strtotime($data['time']));
        $user = User::where(['emp_num' => $data['emp_num']])->first();
        //$user = User::where(['emp_num' => $data['emp_num']])->where('company_id',$station->id)->first();
        if ($user) {
            if ($data['status_id'] == 1) {//if clockout check the ends time of a shift
                $has_end=UserDailyShift::where('user_id', $user->id)->where('ends', date('Y-m-d', strtotime($data['time'])))->first();
                if ($has_end){  //if there is a shift that ends today,
                    $save_date= $has_end->sdate;
                    $shift = $has_end->id;
                }
                else{   //no shift ends today, check shift for today
                    $user_shift = UserDailyShift::where('user_id', $user->id)->where('sdate', date('Y-m-d', strtotime($data['time'])))->first();
                    if ($user_shift) {
                        $shift = $user_shift->id;
                    } else {
                        //there is no shift for today, but we need to push this clockout to a clockin
                        //check if there is a clockin in the previous date that doesnt have a clockout
                        //if the clockin in the previous date is less than 24 hours from the $data['time'], save_date=$previous_date
                        $shift = 0;
                        $yesterday=date('Y-m-d', strtotime($data['time']. '-1 day'));
                        $yesterdat_att=\App\Attendance::where('date',$yesterday)->where('emp_num',$data['emp_num'])->first();

                        if($yesterdat_att){
                            $det = $yesterdat_att->attendancedetails()->latest()->first();
                            if ($det) {  //a clockin exists
                                if ($det->clock_out == '') {//there is a null clock_out
                                    $out_time= date('H:i:s', strtotime($data['time']));
                                    if ($det->clock_in>$out_time){//check if the clockin yesterday is greater than the clock out of now
                                        //it means the clockout made today is actually for yesterday attendance
                                        $save_date=$yesterday;
                                    }
                                }
                                //nothing will happen, the previous date has clockout
                            }
                        }

                        else{
                            $user_shift = UserDailyShift::where('user_id', $user->id)->where('sdate', date('Y-m-d', strtotime($data['time'])))->first();
                            if ($user_shift) {
                                $shift = $user_shift->id;
                            } else {
                                $shift = 0;
                            }
                        }
                    }
                }
            }
            else{
                $user_shift = UserDailyShift::where('user_id', $user->id)->where('sdate', date('Y-m-d', strtotime($data['time'])))->first();
                if ($user_shift) {
                    $shift = $user_shift->id;
                } else {
                    $shift = 0;
                }

            }
            $attendance = \App\Attendance::updateOrCreate(['date' => $save_date, 'shift_id' => '1',//shift is not meant to be here
                'emp_num' => $data['emp_num']],
                ['user_daily_shift_id' => $shift]);




            if ($data['status_id'] == 0 || $data['status_id'] > 1) {//clock in
                //if (isset($attendance->attendancedetails()->latest()->first()->clock_out)){//if there is a previous clockout
                if (AttendanceDetail::where('attendance_id',$attendance->id)->count()>0){//if there is a previous clockout

                    if ($attendance->attendancedetails()->latest()->first()->clock_out == '') {//clockin twice, previous clockin will become previous clockout time
                        if (date('H:i:s', strtotime($data['time']))!=$attendance->attendancedetails()->latest()->first()->clock_in){
                            //update clockout as clockin if the previous clock in time is not the same as the clock in coming now
                            $attendance->attendancedetails()->latest()->first()
                                ->update(['clock_out' => $attendance->attendancedetails()->latest()->first()->clock_in]);
                        }
                    }

                }
                //fresh clockin
                AttendanceDetail::updateOrCreate(['attendance_id' => $attendance->id, 'clock_in' => date('H:i:s', strtotime($data['time'])),'clock_in_lat'=>$lat,'clock_in_long'=>$long,'clock_in_imei'=>$imei]);
            } elseif ($data['status_id'] == 1) {//clockout
                $ad = $attendance->attendancedetails()->latest()->first();
                if ($ad) {  //a clockin exists
                    if ($ad->clock_out == '') {    //a clockin exists but no clockout, create a clockout
                        $ad->update(['clock_out' => date('H:i:s', strtotime($data['time'])),'clock_out_lat'=>$lat,'clock_out_long'=>$long,'clock_out_imei'=>$imei]);
                    } else {  //a clockout is made without a clockin. create a clockout and clockin as clockout time
                        AttendanceDetail::updateOrCreate(['attendance_id' => $attendance->id, 'clock_in' => date('H:i:s', strtotime($data['time'])),
                            'clock_out' => date('H:i:s', strtotime($data['time'])),'clock_out_lat'=>$lat,'clock_out_long'=>$long,'clock_out_imei'=>$imei]);
                    }
                } else {   //no clockin but there is clock out, clockin and clock out is thesame time
                    AttendanceDetail::create(['attendance_id' => $attendance->id, 'clock_in' => date('H:i:s', strtotime($data['time'])),
                        'clock_out' => date('H:i:s', strtotime($data['time'])),'clock_out_lat'=>$lat,'clock_out_long'=>$long,'clock_out_imei'=>$imei]);
                }
            }
            //after adding, call a job to update attendanceReport
            ProcessSingleAttendanceJob::dispatch($attendance->id);
            //return "success";
        }
    }
    public function getDistanceFromLatLngInKm($c1, $c2)
    {
        $earth_radius = 6371; // Radius of the earth in km

        $c1_lat = $c1['latitude'];
        $c1_long = $c1['longitude'];

        $c2_lat = $c2['latitude'];
        $c2_long = $c2['longitude'];

        $lat_diff = deg2rad($c2_lat - $c1_lat);

        $long_diff = deg2rad($c2_long - $c1_long);

        $a = sin($lat_diff / 2) * sin($lat_diff / 2)
            + cos(deg2rad($c1_lat)) * cos(deg2rad($c2_lat))
            * sin($long_diff / 2) * sin($long_diff / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $d = $earth_radius * $c; // Distance in km
        return $d;
    }

    //other biometric devices
    public function addUsersToDevice($users,$device_serial){
        $device=BiometricDevice::where('biometric_serial',$device_serial)->first();
        foreach ($users as $user) {
            if ($user->role_id=='1'){//admin
                $contents = "DATA UPDATE USERINFO PIN=$user->emp_num\tName=$user->name\tPri=14\tPasswd=3696\t";
            }
            else{
                $contents = "DATA UPDATE USERINFO PIN=$user->emp_num\tName=$user->name\tPri=0\tPasswd=1117\t";
            }
            $this->logCommand($contents,$user->company_id,$device_serial);
            $this->sendUserFingerprintToDevice($user->id,$device->id);
        }

    }
    public function deleteUsersFromDevice($users,$device_serial){
        foreach ($users as $user) {
            $contents = "DATA DELETE USERINFO PIN=$user->emp_num\tName=$user->name\tPri=0";
            $this->logCommand($contents,$user->company_id,$device_serial);
        }
    }
    public function sendUserFingerprintToDevice($user_id,$device_id){
        $user = User::findorFail($user_id);
        $device=BiometricDevice::findorFail($device_id);
        foreach ($user->user_finger_prints as $print){
            $contents="DATA FP PIN=$user->emp_num\tFID=$print->finger_no\tSize=$print->size\tValid=1\tTMP=$print->finger_print\t";
            $this->logCommand($contents,$user->company_id,$device->biometric_serial);
        };
    }
}