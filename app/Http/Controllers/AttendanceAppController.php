<?php

namespace App\Http\Controllers;

use App\Setting;
use App\Traits\BiometricTrait;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceAppController extends Controller
{
    use BiometricTrait;
    public function authenticateUser(Request $request){//email, password
        if (!$request->filled(['email','password'])){
            $response= ['status'=>'fail','details'=>'Unauthenticated'];
            return response()->json($response,401);
        }
        $email=$request->email;
        $password=$request->password;
        $credentials=[
            'email' =>$email,
            'password'=>$password
        ];
        if (Auth::attempt($credentials)){
            $activeUser =  User::whereEmail($credentials['email'])->first();
            return $activeUser;
        }else{
            return false;
        }
    }
    public function softClockIn($data){//user_id,long,lat,imei
        $user=User::find($data['user_id']);
        if (!$user){
            $response= ['status'=>'fail','details'=>'No user with ID'];
            return response()->json($response,200);
        }
        $long=$data['long'];
        $lat=$data['lat'];
        $imei=$data['imei'];
        $date_time=$data['date'];
        $company_id=$user->company_id;
        $enforce_geofence=0;
        $enforce_geofence_exist=Setting::where('name','enforce_geofence')->where('company_id',$company_id)->first();
        if ($enforce_geofence_exist){
            $enforce_geofence=$enforce_geofence_exist->value;
        }
        if ($enforce_geofence==1){
            $company_long=Setting::where('name','company_long')->where('company_id',$company_id)->first()->value;
            $company_lat=Setting::where('name','company_lat')->where('company_id',$company_id)->first()->value;
            $distance=Setting::where('name','distance')->where('company_id',$company_id)->first()->value;

            $cood_distance=$this->getDistanceFromLatLngInKm(['latitude'=>$company_lat,'longitude'=>$company_long],
                ['latitude'=>$lat,'longitude'=>$long]);
            if ($cood_distance>$distance){
                $response= ['status'=>'fail','details'=>'You are not within company premises'];
                return response()->json($response,200);
            }
        }
        $now=Carbon::parse($date_time)->format('Y-m-d H:i:s');
        $data = ['emp_num' => $user->emp_num, 'time' => $now, 'status_id' => 0, 'verify_id' => 1,'serial'=>00,'long'=>$long,'lat'=>$lat,'imei'=>$imei];
        $this->saveAttendance($data);
        $response= ['status'=>'success','details'=>'You have successfully Clocked In!'];
        return response()->json($response,200);
    }
    public function softClockOut($data){
        $user=User::find($data['user_id']);
        if (!$user){
            $response= ['status'=>'fail','details'=>'No user with ID'];
            return response()->json($response,200);
        }
        $long=$data['long'];
        $lat=$data['lat'];
        $imei=$data['imei'];
        $date_time=$data['date'];
        $company_id=$user->company_id;
        $enforce_geofence=0;
        $enforce_geofence_exist=Setting::where('name','enforce_geofence')->where('company_id',$company_id)->first();
        if ($enforce_geofence_exist){
            $enforce_geofence=$enforce_geofence_exist->value;
        }
        if ($enforce_geofence==1){
            $company_long=Setting::where('name','company_long')->where('company_id',$company_id)->first()->value;
            $company_lat=Setting::where('name','company_lat')->where('company_id',$company_id)->first()->value;
            $distance=Setting::where('name','distance')->where('company_id',$company_id)->first()->value;
            $cood_distance=$this->getDistanceFromLatLngInKm(['latitude'=>$company_lat,'longitude'=>$company_long],
                ['latitude'=>$lat,'longitude'=>$long]);
            if ($cood_distance>$distance){
                $response= ['status'=>'fail','details'=>'You are not within company premises'];
                return response()->json($response,200);
            }
        }
        $now=Carbon::parse($date_time)->format('Y-m-d H:i:s');
        $data = ['emp_num' => $user->emp_num, 'time' => $now, 'status_id' => 1, 'verify_id' => 1,'serial'=>00,'long'=>$long,'lat'=>$lat,'imei'=>$imei];
        $this->saveAttendance($data);
        $response= ['status'=>'success','details'=>'You have successfully Clocked Out!'];
        return response()->json($response,200);
    }

    public function softClock(Request $request){// data=[['user_id','long','lat','imei','date','type']]               1//clockout 0=clockin
        /*[['type'=>1,'user_id'=>1,'long'=>6,'lat'=>3,'imei'=>111,'date'=>'2020-06-17 12:08:30']];*/
        if ($request->filled('data')){
            foreach ($request->data as $data){
                if ($data['type']=='0'){
                   // ['user_id','long','lat','imei','date'];
                    $this->softClockIn($data);
                }
                elseif($data['type']=='1'){
                    $this->softClockOut($data);
                }
            }
            return 'success';
        }
        return 'fail';
    }
}
