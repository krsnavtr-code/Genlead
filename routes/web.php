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
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\OrganizationDashboardController;
use App\Http\Controllers\AgentApiController;
use App\Http\Controllers\LoginController;

// ====================================
// Super Admin Routes
// ====================================

Route::prefix('superadmin')->name('superadmin.')->group(function () {

    // Guest Routes (Only for non-authenticated users)
    Route::middleware('guest')->group(function () {
        Route::get('/super-login', [SuperAdminController::class, 'showsuperLoginForm'])->name('login.form');
        Route::post('/super-login', [SuperAdminController::class, 'superadminlogin'])->name('login');
    });

    // Authenticated Routes (Only for authenticated superadmin users)
    Route::middleware(['auth', 'superadmin'])->group(function () {
        Route::get('/dashboard', [SuperAdminController::class, 'dashboard'])->name('dashboard');
        Route::post('/logout', [SuperAdminController::class, 'logout'])->name('logout');
    });
});

// ====================================
// Organization Registration Routes
// ====================================
Route::middleware('guest')->group(function () {
    Route::get('/organization/register', [OrganizationController::class, 'showRegisterForm'])
        ->name('organization.register.form');
    Route::post('/organization/register', [OrganizationController::class, 'register'])
        ->name('organization.register');
});

// ====================================
// Organization Login Routes
// ====================================
Route::middleware('guest')->group(function () {
    Route::get('/organization-login', [LoginController::class, 'showLoginForm'])->name('login.formm');
    Route::post('/organization-login', [LoginController::class, 'orglogin'])->name('login.submit');
});

// ====================================
// Root Route
// ====================================
Route::get('/', function () {
    return view('organization.login');
})->name('organization.login');


Route::prefix('{organization}')->middleware('web')->group(function () {
    // Login route
    Route::get('/login', function () {
        return view('login');
    })->name('login');
    // Include all admin routes inside organization slug
    require __DIR__.'/admin.php';
    // Organization dashboard
    // Route::get('/dashboard', [OrganizationDashboardController::class, 'index'])->name('organization.dashboard');

    // Include test routes
    // require __DIR__.'/test.php';

    // API routes
    require __DIR__.'/api.php';

    // Test notification route
    Route::get('/send-test-notification', [\App\Http\Controllers\TestNotificationController::class, 'sendTestNotification']);

    // Notification Routes
    Route::middleware(['auth'])->prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [\App\Http\Controllers\NotificationController::class, 'index'])->name('index');
        Route::post('/{id}/mark-as-read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('markAsRead');
        Route::post('/mark-all-read', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('markAllRead');
    });

    Route::get('/send-reminder',  [CronJobController::class, 'SendLeadReminders']);

    // Dashboard route for organization users
    Route::middleware(['auth'])->get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // Test route for debugging
    Route::get('/test-js', function() {
        return view('test-js');
    });

    // Agent Earnings Routes (Admin)
    Route::middleware(['auth:agent'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/referr-agent-earning', [\App\Http\Controllers\AgentEarningController::class, 'index'])->name('referr-agent-earning.index');
        Route::get('/referr-agent-earning/{earning}', [\App\Http\Controllers\AgentEarningController::class, 'show'])->name('referr-agent-earning.show');
        Route::patch('/referr-agent-earning/{earning}/payout', [\App\Http\Controllers\AgentEarningController::class, 'payout'])->name('referr-agent-earning.payout');
        Route::post('/referr-agent-earning/payout-all', [\App\Http\Controllers\AgentEarningController::class, 'payoutAll'])->name('referr-agent-earning.payout-all');
    });

    // Admin Routes
    Route::middleware(['auth'])
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            // Lead conversation route
            Route::get('/team/lead-conversation/{lead}', [\App\Http\Controllers\TeamManagementController::class, 'getLeadConversation'])
                ->name('team.lead-conversation');
            // ID Card Download Route
            Route::get('employee/download-id-card/{id}', [NewJoinController::class, 'downloadIDCard'])
                ->name('employee.download_id_card');
            // Update employee role
            Route::post('/update-employee-role', [\App\Http\Controllers\ManageController::class, 'updateEmployeeRole'])
                ->name('update.employee.role');
                
            // Assign Agents to Team Leader
            Route::get('/assign-agents-to-team-leader', [\App\Http\Controllers\TeamManagementController::class, 'showAssignAgentsForm'])
                ->name('assign.agents.form');
                
            Route::post('/assign-agents-to-team-leader', [\App\Http\Controllers\TeamManagementController::class, 'assignAgentsToTeamLeader'])
                ->name('assign.agents');

            // Team Management Routes for Team Leaders
            Route::get('/team-management', [\App\Http\Controllers\TeamManagementController::class, 'index'])
                ->name('team.management');
                
            // Agent Referral Chain
            Route::get('/agent-referral-chain', [\App\Http\Controllers\TeamManagementController::class, 'agentReferralChain'])
                ->name('agent.referral.chain');
            
            Route::get('/team/performance', [\App\Http\Controllers\TeamManagementController::class, 'performance'])
                ->name('team.performance');
                
            Route::get('/team/member/{id}/edit', [\App\Http\Controllers\TeamManagementController::class, 'edit'])
                ->name('team.member.edit');
                
            Route::put('/team/member/{id}', [\App\Http\Controllers\TeamManagementController::class, 'update'])
                ->name('team.member.update');
                
            // Team Member Followups
            Route::get('/team/member/{id}/followups', [\App\Http\Controllers\TeamManagementController::class, 'memberFollowups'])
                ->name('team.member.followups');
                
            // Overdue Followups
            Route::get('/team/overdue-followups', [\App\Http\Controllers\TeamManagementController::class, 'overdueFollowups'])
                ->name('team.overdue.followups');
                
            // Complete Followup
            Route::put('/followups/{followup}/complete', [\App\Http\Controllers\TeamManagementController::class, 'completeFollowup'])
                ->name('followup.complete'); // Note: The 'admin.' prefix is automatically applied
                
            // Agent Referral Leads Details
            Route::get('/agent-referral-leads-details', [\App\Http\Controllers\TeamManagementController::class, 'agentReferralLeadsDetails'])
                ->name('admin.agent.referral.leads.details');
                
            // Agent Lead Details
            Route::get('/team/member/{id}/leads-details', [\App\Http\Controllers\TeamManagementController::class, 'memberLeadsDetails'])
                ->name('team.member.leads-details');
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

    // Team Management Routes
    Route::middleware(['auth'])->prefix('admin/team')->name('admin.team.')->group(function () {
        Route::get('/', [TeamManagementController::class, 'index'])->name('management');
        Route::get('/member-leads/{id}', [TeamManagementController::class, 'memberLeadsDetails'])->name('member.leads');
        Route::get('/member/lead-details/{id}', [TeamManagementController::class, 'viewLeadDetails'])->name('member.lead-details');
        Route::get('/assign-agents', [TeamManagementController::class, 'assignAgents'])->name('assign.agents');
        Route::post('/assign-agents', [TeamManagementController::class, 'storeAssignAgents'])->name('store.assign.agents');
        Route::get('/assign-leads', [TeamManagementController::class, 'assignLeads'])->name('assign.leads');
        Route::post('/assign-leads', [TeamManagementController::class, 'storeAssignLeads'])->name('store.assign.leads');
    });

    // i-admin routes
    Route::prefix('i-admin')->group(function () {
        // Add Leads Routes
        Route::get('/how-to-use', [LeadController::class, 'howToUse'])->name('how-to-use');
        Route::get('/leads/add-lead', [LeadController::class, 'addlead']);
        Route::post('/leads/add-lead', [LeadController::class, 'add_lead'])->name('store-lead');
        Route::get('/show-leads',[LeadController::class,'show_leads']);
        Route::post('/leads/bulk-action', [LeadController::class, 'bulkAction'])->name('leads.bulk.action');

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
        // Update lead course
        Route::post('/leads/{lead}/update-course', [LeadController::class, 'updateCourse'])->name('leads.update-course');
        //superadmin routes
    });
});

// // Agent Earnings Routes (Admin)
// Route::middleware(['auth:agent'])->prefix('admin')->name('admin.')->group(function () {
//     Route::get('/referr-agent-earning', [\App\Http\Controllers\AgentEarningController::class, 'index'])->name('referr-agent-earning.index');
//     Route::get('/referr-agent-earning/{earning}', [\App\Http\Controllers\AgentEarningController::class, 'show'])->name('referr-agent-earning.show');
//     Route::patch('/referr-agent-earning/{earning}/payout', [\App\Http\Controllers\AgentEarningController::class, 'payout'])->name('referr-agent-earning.payout');
//     Route::post('/referr-agent-earning/payout-all', [\App\Http\Controllers\AgentEarningController::class, 'payoutAll'])->name('referr-agent-earning.payout-all');
// });

// // Admin Routes
// Route::middleware(['auth'])
//     ->prefix('admin')
//     ->name('admin.')
//     ->group(function () {
//         // Lead conversation route
//         Route::get('/team/lead-conversation/{lead}', [\App\Http\Controllers\TeamManagementController::class, 'getLeadConversation'])
//             ->name('team.lead-conversation');
//         // ID Card Download Route
//         Route::get('employee/download-id-card/{id}', [NewJoinController::class, 'downloadIDCard'])
//             ->name('employee.download_id_card');
//         // Update employee role
//         Route::post('/update-employee-role', [\App\Http\Controllers\ManageController::class, 'updateEmployeeRole'])
//             ->name('update.employee.role');
            
//         // Assign Agents to Team Leader
//         Route::get('/assign-agents-to-team-leader', [\App\Http\Controllers\TeamManagementController::class, 'showAssignAgentsForm'])
//             ->name('assign.agents.form');
            
//         Route::post('/assign-agents-to-team-leader', [\App\Http\Controllers\TeamManagementController::class, 'assignAgentsToTeamLeader'])
//             ->name('assign.agents');

//         // Team Management Routes for Team Leaders
//         Route::get('/team-management', [\App\Http\Controllers\TeamManagementController::class, 'index'])
//             ->name('team.management');
            
//         // Agent Referral Chain
//         Route::get('/agent-referral-chain', [\App\Http\Controllers\TeamManagementController::class, 'agentReferralChain'])
//             ->name('agent.referral.chain');
        
//         Route::get('/team/performance', [\App\Http\Controllers\TeamManagementController::class, 'performance'])
//             ->name('team.performance');
            
//         Route::get('/team/member/{id}/edit', [\App\Http\Controllers\TeamManagementController::class, 'edit'])
//             ->name('team.member.edit');
            
//         Route::put('/team/member/{id}', [\App\Http\Controllers\TeamManagementController::class, 'update'])
//             ->name('team.member.update');
            
//         // Team Member Followups
//         Route::get('/team/member/{id}/followups', [\App\Http\Controllers\TeamManagementController::class, 'memberFollowups'])
//             ->name('team.member.followups');
            
//         // Overdue Followups
//         Route::get('/team/overdue-followups', [\App\Http\Controllers\TeamManagementController::class, 'overdueFollowups'])
//             ->name('team.overdue.followups');
            
//         // Complete Followup
//         Route::put('/followups/{followup}/complete', [\App\Http\Controllers\TeamManagementController::class, 'completeFollowup'])
//             ->name('followup.complete'); // Note: The 'admin.' prefix is automatically applied
            
//         // Agent Referral Leads Details
//         Route::get('/agent-referral-leads-details', [\App\Http\Controllers\TeamManagementController::class, 'agentReferralLeadsDetails'])
//             ->name('admin.agent.referral.leads.details');
            
//         // Agent Lead Details
//         Route::get('/team/member/{id}/leads-details', [\App\Http\Controllers\TeamManagementController::class, 'memberLeadsDetails'])
//             ->name('team.member.leads-details');
//     });

// // Agent Routes
// Route::get('/agent/register', [AgentController::class, 'showRegistrationForm'])->name('agent.register.form');
// Route::post('/agent/register', [AgentController::class, 'register'])->name('agent.register');
// Route::post('/agent/check-email', [AgentController::class, 'checkEmail'])->name('agent.check-email');
// Route::get('/agent/verify-otp', [AgentController::class, 'verifyOtpForm'])->name('agent.verify-otp');
// Route::post('/agent/verify-otp', [AgentController::class, 'verifyOtp'])->name('agent.verify-otp');
// Route::post('/agent/resend-otp', [AgentController::class, 'resendOtp'])->name('agent.resend-otp');

// // Agent Login Routes
// Route::get('/agent/login', [NewJoinController::class, 'agentLoginForm'])->name('agent.login');
// Route::post('/agent/login', [NewJoinController::class, 'agentlogin'])->name('agent.login.submit');

// // Team Management Routes
// Route::middleware(['auth'])->prefix('admin/team')->name('admin.team.')->group(function () {
//     Route::get('/', [TeamManagementController::class, 'index'])->name('management');
//     Route::get('/member-leads/{id}', [TeamManagementController::class, 'memberLeadsDetails'])->name('member.leads');
//     Route::get('/member/lead-details/{id}', [TeamManagementController::class, 'viewLeadDetails'])->name('member.lead-details');
//     Route::get('/assign-agents', [TeamManagementController::class, 'assignAgents'])->name('assign.agents');
//     Route::post('/assign-agents', [TeamManagementController::class, 'storeAssignAgents'])->name('store.assign.agents');
//     Route::get('/assign-leads', [TeamManagementController::class, 'assignLeads'])->name('assign.leads');
//     Route::post('/assign-leads', [TeamManagementController::class, 'storeAssignLeads'])->name('store.assign.leads');
// });

// Route::prefix('i-admin')->group(function () {
//     // Add Leads Routes
//     Route::get('/how-to-use', [LeadController::class, 'howToUse'])->name('how-to-use');
//     Route::get('/leads/add-lead', [LeadController::class, 'addlead']);
//     Route::post('/leads/add-lead', [LeadController::class, 'add_lead'])->name('store-lead');
//     Route::get('/show-leads',[LeadController::class,'show_leads']);
//     Route::post('/leads/bulk-action', [LeadController::class, 'bulkAction'])->name('leads.bulk.action');

//     Route::get('/leads/edit/{id}', [LeadController::class, 'edit']);
//     Route::post('/leads/update/{id}', [LeadController::class, 'update']);
//     Route::delete('/leads/delete/{id}', [LeadController::class, 'destroy']);

//     Route::post('/leads/update-status', [LeadController::class, 'updateStatus'])->name('leads.updateStatus');


//     // View Leads Routes

//     Route::get('/leads/view/{id}', [LeadController::class, 'show']);

//     Route::post('/follow-ups',[LeadController::class,'store'])->name('follow-ups.store');

//     // View and follow up page Edit Routes

//     Route::get('/leads-view/edit/{id}', [LeadController::class, 'editview']);
//     Route::post('/leads/{id}', [LeadController::class, 'updateview']);

//     // Follow-up routes
//     Route::get('/followups/today', [LeadController::class, 'todayFollowUps'])->name('followups.today');
//     Route::get('/followups/tomorrow', [LeadController::class, 'tomorrowFollowUps'])->name('followups.tomorrow');
//     Route::get('/followups/upcoming', [LeadController::class, 'upcomingFollowUps'])->name('followups.upcoming');
//     Route::get('/followups/overdue', [LeadController::class, 'overdueFollowUps'])->name('followups.overdue');


//     // Payment process Routes

//     Route::get('/lead/{leadId}/payment', [LeadController::class, 'showPaymentPage'])->name('payment.page');
//     Route::post('/process-payment', [LeadController::class, 'processPayment'])->name('process.payment');

//     // Pending Payment Routes

//     Route::get('/pending', [LeadController::class, 'showPendingPayments'])->name('pending.payments');

//     // Payment Details API
//     Route::get('/api/payment/{id}', [LeadController::class, 'getPaymentDetails'])->name('api.payment.details');
    
//     // Payment Update Routes
//     Route::get('/payment/{id}/edit', [\App\Http\Controllers\personal\PaymentController::class, 'edit'])->name('payments.edit');
//     Route::put('/payment/{id}', [\App\Http\Controllers\personal\PaymentController::class, 'update'])->name('payments.update');
//     Route::get('/payment/{id}', [\App\Http\Controllers\personal\PaymentController::class, 'show'])->name('payments.show');
//     Route::get('/payment', [\App\Http\Controllers\personal\PaymentController::class, 'index'])->name('payments.index');
    
//     // Payment Guide
//     Route::get('/payment-guide', function() {
//         return view('personal.payment-guide');
//     })->name('payment.guide');


//     // Filter Routes

//     Route::get('/lead', [\App\Http\Controllers\AdminController::class, 'index'])->name('leads.index');


//     // Agent Login Routes

//     Route::post('/agent-login', [LeadController::class, 'login']);

//     // Update lead course
//     Route::post('/leads/{lead}/update-course', [LeadController::class, 'updateCourse'])->name('leads.update-course');

//     //superadmin routes
// });
