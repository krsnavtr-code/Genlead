<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\personal\LeadController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CronJobController;
use App\Models\Registration;
use App\Models\personal\Lead;
use App\Models\personal\FollowUp;
use App\Models\personal\Agent;
use App\Models\personal\Attendance;
use App\Models\personal\Payment;
use App\Models\ManageList;
use App\Models\NewEmployee;
use App\Models\Deal;
use App\Models\Document;
use Carbon\Carbon;


Route::get('/send-reminder',  [CronJobController::class, 'SendLeadReminders']);


Route::prefix('i-admin')->group(function () {

// Add Leads Routes
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

// Today follow up routes

Route::get('/followups/today', [LeadController::class, 'todayFollowUps'])->name('followups.today');


// Payment process Routes

Route::get('/lead/{leadId}/payment', [LeadController::class, 'showPaymentPage'])->name('payment.page');
Route::post('/process-payment', [LeadController::class, 'processPayment'])->name('process.payment');

// Pending Payment Routes

Route::get('/pending', [LeadController::class, 'showPendingPayments'])->name('pending.payments');


// Filter Routes

Route::get('/lead', [AdminController::class, 'index'])->name('leads.index');


// Agent Login Routes

Route::post('/agent-login', [LeadController::class, 'login']);

// Export Leads Routes

Route::get('/leads/export', [LeadController::class, 'exportLeads']);

});