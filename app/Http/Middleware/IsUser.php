<?php
namespace App\Http\Middleware;

use Closure;
use Auth;

class IsUser
{
    //vendor login yazmisan? yoo okay onda user logine sorgu gonder sonra bazada hemin userin tipini deiw vbendor et oldu
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function handle($request, Closure $next)
    {
        //userin olub olmadigini ve varsa tipinin vendor olmasini yoxladiq
        //eger duzgundurse her shey novbeti urle gonderir/ routes-den gelen hansidisa
        if (Auth::check() && Auth::user()->type == "user") {
            return $next($request);
        }else{
            abort(404);
        }
    }
}
