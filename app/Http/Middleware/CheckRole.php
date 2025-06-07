<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\AdminPage;
use App\Models\Employee;
use Illuminate\Support\Facades\Session;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $userId = session()->get('user_id');

        // Check if user is logged in
        if (!$userId) {
            return redirect()->route('login')->withErrors(['error' => 'Unauthorized access.']);
        }

        // Fetch user details from the employees table
        $user = Employee::find($userId);
        if (!$user) {
            return redirect()->route('login')->withErrors(['error' => 'User not found. Please log in again.']);
        }


        // Always allow access to my-account page for all authenticated users
        $pageUrl = $request->path();
        if (str_contains($pageUrl, 'my-account')) {
            Session::put('emp_job_role', $user->emp_job_role);
            return $next($request);
        }

        // For all other pages, check permissions
        Session::put('emp_job_role', $user->emp_job_role);
        $jobRole = $user->emp_job_role;
        
        // Remove parameters from the path
        $basePath = preg_replace('/\/\d+$/', '', $pageUrl);

        // Fetch permissions for the role
        $permissions = $user->jobRole->permissions ?? [];

        // Check if the requested page is accessible
        $page = AdminPage::where('admin_page_url', $basePath)
            ->where('can_display', 1)
            ->first();

        if ($page && in_array($page->id, $permissions)) {
            return $next($request);
        }

        return redirect()->route('login')->withErrors(['error' => 'Access denied.']);
    }
}
