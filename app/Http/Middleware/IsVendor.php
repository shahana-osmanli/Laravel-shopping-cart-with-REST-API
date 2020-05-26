<?php
namespace App\Http\Middleware;

use Closure;
use Auth;

class IsVendor
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->type == "vendor") {
            return $next($request);
        }else{
            abort(404);
        }
    }
}
