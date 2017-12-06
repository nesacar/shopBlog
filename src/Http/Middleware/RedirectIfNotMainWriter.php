<?php

namespace App\Http\Middleware;

use Closure;

class RedirectIfNotMainWriter
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
            if(!$request->user()->isMainWriter() || $request->user()->block == 1){
                return redirect('/');
            }
        }else{
            return redirect('/');
        }

        return $next($request);
    }
}
