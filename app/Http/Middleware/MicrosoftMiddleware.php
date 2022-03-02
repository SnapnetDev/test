<?php

namespace App\Http\Middleware;

use Closure;
use \App\Http\Controllers\MicrosoftController;

class MicrosoftMiddleware
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
        $microsoft = new MicrosoftController();
        $microsoft->refresh_token();
        if(session()->has('microsoft') && session('microsoft') == 1)
        {
            return $next($request);
        }
        return $next($request);
    }
}
