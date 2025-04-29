<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Lead;
use Carbon\Carbon;
use App\Models\User; // assuming your agent is a User model
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class CronJobController extends Controller
{
    public function SendLeadReminders ()
    {
        $now = Carbon::now();
        $upcoming = $now->copy()->addMinutes(300); // adjust timeframe if needed

        $leads = Lead::whereNotNull('next_lead_datetime')
            ->whereDate('next_lead_datetime', '>=', $now)->whereDate('next_lead_datetime', '<=', $upcoming)
            ->get();

        foreach ($leads as $lead) {
           $agent = Employee::find($lead->agent_id);

            if ($agent && $agent->emp_email) {
                Mail::raw("Reminder: You have a follow-up scheduled with {$lead->first_name} {$lead->last_name} at {$lead->next_lead_datetime}.", function ($message) use ($agent, $lead) {
                    $message->to($agent->emp_email)
                            ->subject("⏰ Lead Follow-up Reminder: {$lead->first_name} {$lead->last_name}");
                });
                
                // Admin email
                Mail::raw("Reminder sent to agent {$agent->emp_name} ({$agent->emp_email}) for lead {$lead->first_name} at {$lead->next_lead_datetime}.", function ($message) {
                    $message->to('admin@example.com')
                            ->subject("📬 Admin Notification: Lead Reminder Sent");
                });
            }
        }

        return 0;
    }
}
