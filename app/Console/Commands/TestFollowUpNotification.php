<?php

namespace App\Console\Commands;

use App\Models\personal\FollowUp;
use App\Models\personal\Lead;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class TestFollowUpNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'followups:test-notification {--agent=} {--lead=} {--time=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the follow-up notification system';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Use provided agent ID or get the first available agent
        if ($this->option('agent')) {
            $agent = Employee::find($this->option('agent'));
        } else {
            $agent = Employee::first();
        }

        if (!$agent) {
            $this->error('No agents found. Please create an agent first.');
            return 1;
        }

        // Use provided lead ID or get the first available lead
        if ($this->option('lead')) {
            $lead = Lead::find($this->option('lead'));
        } else {
            $lead = Lead::where('agent_id', $agent->id)->first();
            
            if (!$lead) {
                $this->error('No leads found for this agent. Please provide a lead ID with --lead=ID or assign leads to this agent first.');
                return 1;
            }
        }

        if (!$lead) {
            $this->error('Could not find or create a lead.');
            return 1;
        }

        // Use provided time or set to now
        $followUpTime = $this->option('time') 
            ? Carbon::parse($this->option('time'))
            : Carbon::now();

        // Create a test follow-up
        $followUp = FollowUp::create([
            'lead_id' => $lead->id,
            'agent_id' => $agent->id,
            'follow_up_time' => $followUpTime,
            'comments' => 'Test follow-up notification',
            'action' => 'test',
            'notified' => 0,
        ]);

        $this->info("Using lead ID: " . $lead->id . " - " . $lead->first_name . ' ' . $lead->last_name);
        $this->info("Created test follow-up with ID: " . $followUp->id);
        $this->info("Follow-up time: " . $followUpTime->format('Y-m-d H:i:s'));
        $this->info("Agent: " . $agent->emp_name . " <" . $agent->emp_email . ">");

        // Run the notification command
        $this->info("\nSending notification...");
        $this->call('followups:send-notifications');

        return 0;
    }
}
