<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CheckUserStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip for login/logout routes to prevent redirect loops
        if ($request->is('login') || $request->is('logout') || $request->is('admin/login') || $request->is('admin/logout')) {
            return $next($request);
        }

        $user = Auth::guard('agent')->user();
        
        if ($user) {
            // Log the current status check
            Log::debug('Checking user status', [
                'user_id' => $user->id,
                'username' => $user->emp_username,
                'is_active' => $user->is_active,
                'route' => $request->path(),
                'ip' => $request->ip()
            ]);

            // Get fresh user data from database to check current status
            $freshUser = \App\Models\personal\Agent::find($user->id);
            
            if ($freshUser && $freshUser->is_active === 0) {
                // Log before logging out
                Log::warning('User account is inactive, logging out', [
                    'user_id' => $freshUser->id,
                    'username' => $freshUser->emp_username,
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent()
                ]);

                // Logout the user
                Auth::guard('agent')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                // Log after logging out
                Log::warning('Successfully logged out inactive user', [
                    'user_id' => $freshUser->id,
                    'username' => $freshUser->emp_username,
                    'ip' => $request->ip()
                ]);

                return redirect()->route('login')
                    ->with('error', 'Your account has been deactivated. Please contact Your Team Leader & HR for assistance.');
            }
        }

        return $next($request);
    }
}
