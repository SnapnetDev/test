<?php

namespace App\Http\Middleware;

use App\Setting;
use Closure;

class UsesTAMSMiddleware
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
        if (isset(Setting::where('name','uses_tams')->where('company_id',companyId())->first()->value) && Setting::where('name','uses_tams')->where('company_id',companyId())->first()->value=='1'){
            return $next($request);
        }
        else{
            return \redirect(route('home'));
        }

    }
}
