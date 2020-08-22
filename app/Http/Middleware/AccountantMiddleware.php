<?php

namespace App\Http\Middleware;

use Closure;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;

class AccountantMiddleware
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
        if(Auth::user()->role->id == 1 || Auth::user()->role->id == 2 || Auth::user()->role->id == 3 ){
            return $next($request);
        }else{
            Toastr::error('You Are Not Authorized To Access This Routes', 'error');
            return redirect()->back();
        }
    }
}
