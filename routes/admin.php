<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ManageController;
use App\Http\Controllers\NewJoinController;
use App\Http\Controllers\personal\LeadController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use App\Models\Registration;

// Public routes that don't require authentication
use App\Models\Lead;
use App\Models\ManageList;
use App\Models\NewEmployee;
use App\Models\Deal;
use App\Models\Document;


Route::post('/hrms/update-candidate-email', [NewJoinController::class, 'updateCandidateEmail'])
    ->name('hrms.update_candidate_email');

Route::get('/', function () {
    return view('login');
})->name('login');

Route::prefix('admin')->group(function () {

    Route::post('/login', [AdminController::class, 'store']);

    // New joinee related routes
    Route::get('/new-joinee/upload-documents/{username}', [NewJoinController::class, 'joineeLoginForm'])->name('new_joinee.login_form');
    Route::post('/new-joinee/resend-document-email/{id}', [NewJoinController::class, 'resendDocumentEmail'])->name('new_joinee.resend_document_email');
    Route::post('/new-joinee/login', [NewJoinController::class, 'joineelogin'])->name('new_joinee.login');
    Route::get('/add-documents', [NewJoinController::class, 'uploadEmployeeForm']);
    Route::post('/upload-documents', [NewJoinController::class, 'store'])->name('documents.store');
    Route::get('/agent-data', [AdminController::class, 'showAgentData'])->name('admin.agent.data');
    Route::post('/transfer-leads', [AdminController::class, 'transferLeads'])->name('admin.leads.transfer');

    // Forgot Password

    Route::get('/password/reset', [ManageController::class, 'showResetRequestForm'])->name('password.request');
    Route::post('/password/reset', [ManageController::class, 'sendResetLink']);
    Route::get('/password/reset/{token}', [ManageController::class, 'showResetForm'])->name('password.reset');
    Route::post('/password/reset-submit', [ManageController::class, 'resetPassword'])->name('password.update');

});

Route::prefix('admin')->middleware(['checkrole'])->group(function () {

    Route::get('/home', function () {
        try {
            // Get data for dashboard
            $totalLeads = \App\Models\Lead::count();
            $convertedLeads = \App\Models\Lead::where('status', 3)->count();
            
            // Get recent leads
            $recentLeads = \App\Models\Lead::orderBy('created_at', 'desc')->take(5)->get();
            
            // Try to get follow-ups
            try {
                $userId = session()->get('user_id');
                $userRole = session()->get('emp_job_role');
                
                // Today's follow-ups
                $todayQuery = \App\Models\personal\FollowUp::whereDate('follow_up_time', \Carbon\Carbon::today());
                if ($userRole == 2) {
                    $todayQuery->where('agent_id', $userId);
                }
                $todayFollowups = $todayQuery->count();
                
                $todayFollowupsList = $todayQuery->with('lead')
                    ->orderBy('follow_up_time', 'asc')
                    ->take(5)
                    ->get();
                    
                // Tomorrow's follow-ups
                $tomorrowQuery = \App\Models\personal\FollowUp::whereDate('follow_up_time', \Carbon\Carbon::tomorrow());
                if ($userRole == 2) {
                    $tomorrowQuery->where('agent_id', $userId);
                }
                $tomorrowFollowups = $tomorrowQuery->count();
                
                // Upcoming follow-ups (all future follow-ups after tomorrow)
                $tomorrow = \Carbon\Carbon::tomorrow();
                $upcomingQuery = \App\Models\personal\FollowUp::where('follow_up_time', '>', $tomorrow->endOfDay());
                if ($userRole == 2) {
                    $upcomingQuery->where('agent_id', $userId);
                }
                $upcomingFollowups = $upcomingQuery->count();
                
                // Overdue follow-ups (only include if not updated today)
                $today = \Carbon\Carbon::today();
                $overdueQuery = \App\Models\personal\FollowUp::where('follow_up_time', '<', \Carbon\Carbon::now())
                    ->whereDate('updated_at', '<', $today);
                    
                if ($userRole == 2) {
                    $overdueQuery->where('agent_id', $userId);
                }
                $overdueFollowups = $overdueQuery->count();
                
            } catch (\Exception $e) {
                // If there's an error, set default values
                $todayFollowups = 0;
                $tomorrowFollowups = 0;
                $thisWeekFollowups = 0;
                $overdueFollowups = 0;
                $todayFollowupsList = collect([]);
            }
            
            

            // Try to get pending payments
            try {
                // Try with 'status' column first
                $pendingPayments = \App\Models\personal\Payment::where('status', 'pending')->count();
            } catch (\Exception $e) {
                try {
                    // If that fails, try with 'payment_status' column
                    $pendingPayments = \App\Models\personal\Payment::where('payment_status', 'pending')->count();
                } catch (\Exception $e) {
                    // If both fail, set to 0
                    $pendingPayments = 0;
                }
            }
        } catch (\Exception $e) {
            // If there's a general error, set default values for everything
            $totalLeads = 0;
            $convertedLeads = 0;
            $todayFollowups = 0;
            $pendingPayments = 0;
            $recentLeads = collect([]);
            $todayFollowupsList = collect([]);
        }
        
        return view('home', compact('totalLeads', 'convertedLeads', 'todayFollowups', 'pendingPayments', 'recentLeads', 'todayFollowupsList'));
    })->name('home');


    // Superadmin Change Password

    Route::get('/all-login-access', [ManageController::class, 'showAllEmployees'])->name('all.login.access');

    // Handle the password change request
    Route::post('/change-employee-password', [ManageController::class, 'changeEmployeePassword'])->name('change.employee.password');

    // Change password

    Route::get('/my-account', [AdminController::class, 'myAccount'])->name('myAccount');
    Route::post('/change-password', [AdminController::class, 'changePassword']);

    // Logout Routes

    Route::get('/logout', [AdminController::class, 'logout'])->name('logout');

    // Add Leads Routes
    Route::get('/leads/add-lead', [AdminController::class, 'addlead']);
    Route::post('/leads/add-lead', [AdminController::class, 'add_lead']);
    Route::get('/show-leads', [AdminController::class, 'show_leads']);

    Route::get('/leads/edit/{id}', [AdminController::class, 'edit'])->name('leads.edit');
    Route::post('/leads/update/{id}', [AdminController::class, 'update'])->name('leads.update');
    Route::delete('/leads/delete/{id}', [AdminController::class, 'destroy'])->name('leads.destroy');


    // Employee login access management - No auth middleware for AJAX
    Route::post('/employees/{id}/toggle-login-access', [ManageController::class, 'toggleLoginAccess'])
        ->withoutMiddleware(['auth', 'checkrole'])
        ->name('admin.employees.toggle_login_access');

    // View Leads Routes

    Route::get('/leads/view/{id}', [AdminController::class, 'viewLead'])->name('leads.view');


    // Export Leads Routes

    Route::get('/leads/export', [AdminController::class, 'exportLeads'])->name('leads.export');

    // Import Leads Routes

    Route::post('/leads/import', [AdminController::class, 'importLeads'])->name('leads.import');


    // Transfer & Share leads 

    Route::get('/leads/transfer', [LeadController::class, 'transferView'])->name('leads.transfer.view');
    Route::get('/leads/transfer-detail', [LeadController::class, 'transferViewDetail'])->name('leads.transfer.detail');
    Route::post('/leads/transfer', [LeadController::class, 'transferLeads'])->name('leads.transfer');


    // Payment Verify Routes

    Route::get('/lead/payment-verify', [LeadController::class, 'index'])->name('payment.verify');
    Route::post('/lead/payment-verify/{id}', [LeadController::class, 'verify'])->name('payment.confirm');


    // Manage Activities Routes

    Route::get('/activities', [AdminController::class, 'indexactivity'])->name('activities.index');
    Route::get('/activities/create', [AdminController::class, 'create'])->name('activities.create');
    Route::post('/activities/store', [AdminController::class, 'storeactivity'])->name('activities.store');
    Route::get('/activities/edit/{id}', [AdminController::class, 'editactivity'])->name('activities.edit');
    Route::post('/activities/update/{id}', [AdminController::class, 'updateactivity'])->name('activities.update');
    Route::delete('/activities/destroy/{id}', [AdminController::class, 'destroyactivity'])->name('activities.destroy');


    // Manage Tasks Routes

    Route::get('/tasks', [ManageController::class, 'index'])->name('tasks.index');
    Route::get('/tasks/create', [ManageController::class, 'create'])->name('tasks.create');
    Route::post('/tasks', [ManageController::class, 'store'])->name('tasks.store');

    Route::get('/tasks/edit/{id}', [ManageController::class, 'edit'])->name('tasks.edit');
    Route::post('/tasks/update/{id}', [ManageController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/destroy/{id}', [ManageController::class, 'destroy'])->name('tasks.destroy');

    // HRMS & Add candidate Related Routes

    Route::get('/new-join/add', [NewJoinController::class, 'showAddEmployeeForm']);
    Route::post('/new-join/send-welcome-link', [NewJoinController::class, 'sendWelcomeLink'])->name('new-join.send-welcome-link');



    // New Join Employee login and dashboard routes
    Route::get('/new-employee/login', [NewJoinController::class, 'shownewLoginForm'])->name('employee.login');
    Route::get('/employee/dashboard', [NewJoinController::class, 'dashboard'])->name('employee.dashboard');
    Route::post('/new-employee/login', [NewJoinController::class, 'newlogin'])->name('employee.login.submit');


    // Download Documents routes
    Route::get('employee/download-offer-letter/{id}', [NewJoinController::class, 'downloadOfferLetter'])->name('employee.download_offer_letter');
    Route::get('employee/download-experience-letter/{id}', [NewJoinController::class, 'downloadExperienceLetter'])->name('employee.download_experience_letter');

    Route::get('/hrms/manage-employees', [NewJoinController::class, 'index'])->name('hrms.manage_employees');
    Route::get('/hrms/verify-document/{id}', [NewJoinController::class, 'verifyDocument']);
    Route::get('/hrms/candidate-interview-result', [NewJoinController::class, 'candidateInterviewResult']);
    Route::post('/submit-interview-result', [NewJoinController::class, 'submitInterviewResult'])->name('hrms.submit_interview_result');

    // ID Card Download Route - Only requires authentication, not specific role
    Route::get('employee/download-id-card/{id}', [NewJoinController::class, 'downloadIDCard'])
        ->middleware(['auth'])
        ->name('employee.download_id_card');

    // Superadmin New joinee panel

    Route::get('/new-join-panel', [NewJoinController::class, 'showNewJoinPanel']);

    Route::post('/superadmin/verify/{id}', [NewJoinController::class, 'verifyAsSuperAdmin'])->name('superadmin.verify');

    Route::get('/candidates/edit/{id}', [NewJoinController::class, 'edit'])->name('candidates.edit');
    Route::post('/candidates/update/{id}', [NewJoinController::class, 'update'])->name('candidates.update');
    Route::delete('/candidates/delete/{id}', [NewJoinController::class, 'destroy'])->name('candidates.destroy');



});
