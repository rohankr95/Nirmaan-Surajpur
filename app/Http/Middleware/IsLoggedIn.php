<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsLoggedIn
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(session()->has('login_id'))
        {
            // A login provisioned with a temporary password reaches nothing but
            // the change-password screen until that password has been replaced.
            if (session()->get('force_password_reset')
                && ! $request->routeIs('change-password', 'update-password', 'logout')) {
                return redirect()->route('change-password')
                    ->with('error', 'जारी रखने के लिए कृपया अपना अस्थायी पासवर्ड बदलें।');
            }

            return $next($request);
        }
        return redirect()->route('login.index')->with('error',"You need to login first");
    }
}
