<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    public function handle($request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;
    
        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                if ($guard === 'superadmin') {
                    return redirect()->route('superadmin.dashboard');
                }
                
    
                return redirect('/home'); // default user redirect
            }
        }
    
        return $next($request);
    }
    
    
}
