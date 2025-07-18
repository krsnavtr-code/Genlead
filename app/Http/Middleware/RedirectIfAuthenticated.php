<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    public function handle($request, Closure $next, ...$guards)
    {
        if (Auth::check()) {
            $user = Auth::user();
    
            if ($user->is_superadmin) {
                return redirect()->route('superadmin.dashboard');
            }
    
            if ($user->organization) {
                return redirect()->route('organization.dashboard', [
                    'organization' => $user->organization->slug
                ]);
            }
        }
    
        return $next($request);
    }
    
    
    
}
