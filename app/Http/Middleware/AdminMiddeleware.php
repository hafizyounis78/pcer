<?php

namespace App\Http\Middleware;

use Closure;

class AdminMiddeleware
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
        if(!auth()->check())
            return redirect()->back();
        if(!(auth()->user()->user_role==5 || auth()->user()->user_role==3))
            return redirect()->back();
        return $next($request);
    }
}
