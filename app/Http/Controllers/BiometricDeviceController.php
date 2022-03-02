<?php

namespace App\Http\Controllers;

use App\BiometricDevice;
use Illuminate\Http\Request;

class BiometricDeviceController extends Controller
{
    public function saveDevice(Request $request)
    {
        $company_id=companyId();
        BiometricDevice::updateOrCreate(['id'=>$request->device_id],
            ['name'=>$request->name,'biometric_serial'=>$request->serial,'company_id'=>$company_id]);
        return  response()->json('success',200);
    }
    public function getDevice($id)
    {
        return BiometricDevice::find($id);
    }
}
