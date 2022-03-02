<?php

namespace App\Http\Middleware;

use Closure;


class PreventBackHistory
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    protected $except_urls = [
        'download'
    ];
    public function handle($request, Closure $next) {
        $regex = '#' . implode('|', $this->except_urls) . '#';

        if (!preg_match($regex, $request->path())) {
            $response = $next($request);

            return $response->header('Cache-Control', 'nocache, no-store, max-age=0, must-revalidate')
                ->header('Pragma', 'no-cache')
                ->header('Expires', 'Sun, 02 Jan 1990 00:00:00 GMT');
        }else{
            return $next($request);
        }

    }
}
