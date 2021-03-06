<?php

namespace App\Http\Middleware;

use Closure;

class RedirectIfNotMenager
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
        if(\Auth::check()){
            if(!$request->user()->isMenager() || $request->user()->block == 1){
                return redirect('logout');
            }
        }else{
            return redirect('logout');
        }

        return $next($request);
    }
}
