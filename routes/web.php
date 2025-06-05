<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\personal\LeadController;
use App\Http\Controllers\CronJobController;
use App\Models\Registration;
use App\Models\personal\Lead;
use App\Models\personal\FollowUp;
use App\Models\personal\Agent;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\NewJoinController;
use App\Models\personal\Attendance;
use App\Models\personal\Payment;
use App\Models\ManageList;
use App\Models\NewEmployee;
use App\Models\Deal;
use App\Models\Document;
use App\Models\Employee;
use Carbon\Carbon;
use App\Http\Controllers\TeamManagementController;

Route::get('/send-reminder',  [CronJobController::class, 'SendLeadReminders']);

// Admin Routes
Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Assign Agents to Team Leader
        Route::get('/assign-agents-to-team-leader', [\App\Http\Controllers\TeamManagementController::class, 'showAssignAgentsForm'])
            ->name('assign.agents.form');
            
        Route::post('/assign-agents-to-team-leader', [\App\Http\Controllers\TeamManagementController::class, 'assignAgentsToTeamLeader'])
            ->name('assign.agents');

        // Team Management Routes for Team Leaders
        Route::get('/team-management', [\App\Http\Controllers\TeamManagementController::class, 'index'])
            ->name('team.management');
        
        Route::get('/team/performance', [\App\Http\Controllers\TeamManagementController::class, 'performance'])
            ->name('team.performance');
            
        Route::get('/team/member/{id}/edit', [\App\Http\Controllers\TeamManagementController::class, 'edit'])
            ->name('team.member.edit');
            
        Route::put('/team/member/{id}', [\App\Http\Controllers\TeamManagementController::class, 'update'])
            ->name('team.member.update');
            
        // Team Member Followups
        Route::get('/team/member/{id}/followups', [\App\Http\Controllers\TeamManagementController::class, 'memberFollowups'])
            ->name('team.member.followups');
    });

// Agent Routes
Route::get('/agent/register', [AgentController::class, 'showRegistrationForm'])->name('agent.register.form');
Route::post('/agent/register', [AgentController::class, 'register'])->name('agent.register');
Route::post('/agent/check-email', [AgentController::class, 'checkEmail'])->name('agent.check-email');
Route::get('/agent/verify-otp', [AgentController::class, 'verifyOtpForm'])->name('agent.verify-otp');
Route::post('/agent/verify-otp', [AgentController::class, 'verifyOtp'])->name('agent.verify-otp');
Route::post('/agent/resend-otp', [AgentController::class, 'resendOtp'])->name('agent.resend-otp');

// Agent Login Routes
Route::get('/agent/login', [NewJoinController::class, 'agentLoginForm'])->name('agent.login');
Route::post('/agent/login', [NewJoinController::class, 'agentlogin'])->name('agent.login.submit');


Route::prefix('i-admin')->group(function () {
    // Add Leads Routes
    Route::get('/how-to-use', [LeadController::class, 'howToUse'])->name('how-to-use');
    Route::get('/leads/add-lead', [LeadController::class, 'addlead']);
    Route::post('/leads/add-lead', [LeadController::class, 'add_lead'])->name('store-lead');
    Route::get('/show-leads',[LeadController::class,'show_leads']);

    Route::get('/leads/edit/{id}', [LeadController::class, 'edit']);
    Route::post('/leads/update/{id}', [LeadController::class, 'update']);
    Route::delete('/leads/delete/{id}', [LeadController::class, 'destroy']);

    Route::post('/leads/update-status', [LeadController::class, 'updateStatus'])->name('leads.updateStatus');


    // View Leads Routes

    Route::get('/leads/view/{id}', [LeadController::class, 'show']);

    Route::post('/follow-ups',[LeadController::class,'store'])->name('follow-ups.store');

    // View and follow up page Edit Routes

    Route::get('/leads-view/edit/{id}', [LeadController::class, 'editview']);
    Route::post('/leads/{id}', [LeadController::class, 'updateview']);

    // Follow-up routes
    Route::get('/followups/today', [LeadController::class, 'todayFollowUps'])->name('followups.today');
    Route::get('/followups/tomorrow', [LeadController::class, 'tomorrowFollowUps'])->name('followups.tomorrow');
    Route::get('/followups/upcoming', [LeadController::class, 'upcomingFollowUps'])->name('followups.upcoming');
    Route::get('/followups/overdue', [LeadController::class, 'overdueFollowUps'])->name('followups.overdue');


    // Payment process Routes

    Route::get('/lead/{leadId}/payment', [LeadController::class, 'showPaymentPage'])->name('payment.page');
    Route::post('/process-payment', [LeadController::class, 'processPayment'])->name('process.payment');

    // Pending Payment Routes

    Route::get('/pending', [LeadController::class, 'showPendingPayments'])->name('pending.payments');

    // Payment Details API
    Route::get('/api/payment/{id}', [LeadController::class, 'getPaymentDetails'])->name('api.payment.details');
    
    // Payment Update Routes
    Route::get('/payment/{id}/edit', [\App\Http\Controllers\personal\PaymentController::class, 'edit'])->name('payments.edit');
    Route::put('/payment/{id}', [\App\Http\Controllers\personal\PaymentController::class, 'update'])->name('payments.update');
    Route::get('/payment/{id}', [\App\Http\Controllers\personal\PaymentController::class, 'show'])->name('payments.show');
    Route::get('/payment', [\App\Http\Controllers\personal\PaymentController::class, 'index'])->name('payments.index');
    
    // Payment Guide
    Route::get('/payment-guide', function() {
        return view('personal.payment-guide');
    })->name('payment.guide');


    // Filter Routes

    Route::get('/lead', [\App\Http\Controllers\AdminController::class, 'index'])->name('leads.index');


    // Agent Login Routes

    Route::post('/agent-login', [LeadController::class, 'login']);

    // Export Leads Routes

    Route::get('/leads/export', [LeadController::class, 'exportLeads']);

});