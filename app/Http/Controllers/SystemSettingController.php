<?php

namespace App\Http\Controllers;

use App\IntegrationPolicy;
use Illuminate\Http\Request;
use App\Company;
use App\Department;
use App\Branch;
use App\Job;
use App\User;
use App\Setting;
use Illuminate\Validation\Rules\In;
use Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class SystemSettingController extends Controller
{
public $log_types=['profile','payroll','salary_component'];
	public function index($value='')
	{
        $integration_policy=IntegrationPolicy::where('company_id',companyId())->first();
	    if (!$integration_policy){
            $integration_policy=IntegrationPolicy::create(['hcrecruit_url'=>'','hcrecruit_app_key'=>'','app_key'=>bcrypt(mt_rand(1234,5678)),'company_id'=>companyId()]);
        }
		$has_sub=Setting::where('name','has_sub')->first();
		$use_parent_setting=Setting::where('name','use_parent_setting')->first();
		$name=Setting::where('name','sys_name')->first();
		$logo=Setting::where('name','sys_logo')->first();
		$lp=Setting::whereIn('name',$this->log_types)->get();
		if (count($lp)!=count($this->log_types)){
		    foreach ($this->log_types as $log_type){
                $check=Setting::where('name',$log_type)->where('company_id',companyId())->first();
                if (!$check){
                    Setting::create(['name'=>$log_type,'value'=>0,'company_id'=>companyId()]);
                }
            }
            $lp=Setting::whereIn('name',$this->log_types)->get();
        }
		if (!$name) {
			$name=Setting::create(['name'=>'sys_name','value'=>'HCMatrix']);
		}
		if (!$logo) {
			$logo=Setting::create(['name'=>'sys_logo','value'=>'']);
		}

		return view('settings.systemsettings.index',compact('has_sub','use_parent_setting','name','logo','lp','integration_policy'));
	}
	public function switchHasSubsidiary(Request $request)
	  {
	    $setting=Setting::where('name','has_sub')->first();
	    if ($setting->value==1) {
	     $setting->update(['value'=>0]);
	      return 2;
	    }elseif($setting->value==0){
	      $setting->update(['value'=>1]);
	       return 1;
	    }
	  }
	  public function switchUseParentSetting(Request $request)
	  {
	    $setting=Setting::where('name','use_parent_setting')->first();
	    if ($setting->value==1) {
	     $setting->update(['value'=>0]);
	      return 2;
	    }elseif($setting->value==0){
	      $setting->update(['value'=>1]);
	       return 1;
	    }
	  }
	  public function whiteLabel(Request $request)
	  {
	  	$name=Setting::where('name','sys_name')->first();
	  	$logo=Setting::where('name','sys_logo')->first();
	  	if ($name) {
	  		$name->update(['value'=>$request->name]);
	  	}else{
	  		Setting::create(['name'=>'sys_name','value'=>$request->name]);
	  	}
	  	if ($logo) {
	  		 if ($request->file('logo')) {
                    $path = $request->file('logo')->store('logo');
                    if (Str::contains($path, 'logo')) {
                       $filepath= Str::replaceFirst('logo', '', $path);
                    } else {
                        $filepath= $path;
                    }
                    $logo->update(['value'=>$filepath]);

                }

	  	}else{
	  		 if ($request->file('logo')) {
                    $path = $request->file('logo')->store('logo');
                    if (Str::contains($path, 'logo')) {
                       $filepath= Str::replaceFirst('logo', '', $path);
                    } else {
                        $filepath= $path;
                    }
                    Setting::create(['name'=>'sys_logo','value'=>$request->filepath]);

                }

	  	}
	  	return 'success';

	  }
    public function switchLogPolicy(Request $request)
    {
        $company_id = companyId();
        $setting=Setting::where(['name'=>$request->log_type,'company_id'=>$company_id])->first();
        if ($setting->value==1) {
            $setting->update(['value'=>0]);
            return 2;
        }elseif($setting->value==0){
            $setting->update(['value'=>1]);
            return 1;
        }
    }

    public function saveIntegrationPolicy(Request $request)
    {
        $company_id=companyId();

        $ip=IntegrationPolicy::updateOrCreate(['company_id'=>companyId()],['hcrecruit_url'=>$request->hcrecruit_url, 'app_key'=>$request->app_key,'hcrecruit_app_key'=>$request->hcrecruit_app_key,
            'company_id'=>companyId()]);
        return 'success';
    }

    public function generateAppKey(Request $request)
    {
        return bcrypt(mt_rand(1234,5678));
    }


}
