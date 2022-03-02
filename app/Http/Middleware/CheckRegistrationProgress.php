<?php

namespace App\Http\Middleware;

use App\RegistrationProgress;
use Closure;

class CheckRegistrationProgress
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $rp=RegistrationProgress::where('company_id',companyId())->first();
        if (!$rp){
            //$rp=RegistrationProgress::create(['has_users'=>0,'has_grades'=>0,'has_leave_policy'=>0,'has_payroll_policy'=>0,'has_departments'=>0,'has_job_roles'=>0,'company_id'=>companyId(),'completed'=>0]);
        }
//        dd($request->route('parameter_name'));
//        dd($request->route()->uri);

        if ($rp->completed==0){
            return redirect('registration-progress')->with(['error'=>'Please complete the registration process to use the application']);
        }

        return $next($request);
    }
}
