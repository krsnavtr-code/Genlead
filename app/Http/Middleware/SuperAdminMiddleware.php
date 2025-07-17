<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class SuperAdmin
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->is_superadmin) {
            return $next($request);
        }

        return redirect()->route('superadmin.login.form');
    }
}

