<?php

namespace App\Console\Commands;

use App\Models\personal\FollowUp;
use App\Models\personal\Lead;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendFollowUpNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'followups:send-notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email notifications for due follow-ups';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $now = Carbon::now();
        $timeWindow = $now->copy()->addMinute(); // Check within next minute
        
        $this->info("Checking for follow-ups between " . $now->format('Y-m-d H:i:s') . " and " . $timeWindow->format('Y-m-d H:i:s'));

        // Find follow-ups that are due now
        $followUps = FollowUp::with(['lead', 'agent'])
            ->whereBetween('follow_up_time', [$now, $timeWindow])
            ->where('notified', 0)
            ->get()
            ->map(function ($followUp) {
                // Ensure follow_up_time is a Carbon instance
                $followUp->follow_up_time = \Carbon\Carbon::parse($followUp->follow_up_time);
                return $followUp;
            });

        $this->info("Found " . $followUps->count() . " follow-ups to notify");

        foreach ($followUps as $followUp) {
            try {
                $this->sendNotification($followUp);
                // Mark as notified
                $followUp->update(['notified' => 1]);
                $this->info("Successfully processed follow-up ID: " . $followUp->id);
            } catch (\Exception $e) {
                Log::error("Error processing follow-up ID: " . $followUp->id . " - " . $e->getMessage());
                $this->error("Error processing follow-up ID: " . $followUp->id . " - " . $e->getMessage());
            }
        }

        return 0;
    }

    protected function sendNotification($followUp)
    {
        $agent = $followUp->agent;
        $lead = $followUp->lead;

        if (!$agent || !$agent->emp_email) {
            $this->error("No agent or email found for follow-up ID: " . $followUp->id);
            return;
        }

        // Email to agent
        $subject = "🔔 Follow-up Due: {$lead->first_name} {$lead->last_name}";
        $message = "You have a follow-up scheduled now with {$lead->first_name} {$lead->last_name}.\n";
        $message .= "Time: " . $followUp->follow_up_time->format('Y-m-d h:i A') . "\n";
        $message .= "Comments: " . ($followUp->comments ?? 'No comments') . "\n";

        try {
            // Send to agent
            Mail::raw($message, function($mail) use ($agent, $subject) {
                $mail->to($agent->emp_email)
                     ->subject($subject);
            });

            // Send to admin
            Mail::raw("Follow-up notification sent to agent {$agent->emp_name} ({$agent->emp_email}) " . 
                     "for lead {$lead->first_name} {$lead->last_name} at " . $followUp->follow_up_time->format('Y-m-d h:i A'), 
                function($mail) use ($subject) {
                    $mail->to('admin@genlead.com')
                         ->subject("📬 Admin: " . $subject);
                });

            $this->info("Notification sent for follow-up ID: " . $followUp->id);
        } catch (\Exception $e) {
            $this->error("Failed to send notification for follow-up ID: " . $followUp->id . " - " . $e->getMessage());
        }
    }
}
