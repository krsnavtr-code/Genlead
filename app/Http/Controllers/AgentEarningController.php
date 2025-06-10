<?php

namespace App\Http\Controllers;

use App\Models\AgentEarning;
use App\Models\personal\Agent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Policies\AgentEarningPolicy;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AgentEarningController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(AgentEarning::class, 'earning');
    }

    /**
     * Display a listing of the agent's earnings.
     */
    public function index(): View
    {
        /** @var \App\Models\personal\Agent $agent */
        $agent = auth()->guard('agent')->user();
        
        // If agent is admin, show all earnings, otherwise show only their own
        $baseQuery = $agent->isAdmin() 
            ? AgentEarning::with('agent')
            : AgentEarning::where('agent_id', $agent->id);
            
        $earnings = $baseQuery->latest('earned_date')->paginate(15);
        
        // Calculate summary statistics
        $totalEarnings = $baseQuery->clone()->sum('amount');
        $totalPaid = $baseQuery->clone()->where('is_paid', true)->sum('amount');
        $totalPending = $baseQuery->clone()->where('is_paid', false)->sum('amount');
        
        // Get list of agents for admin filter
        $agents = $agent->isAdmin() ? Agent::all() : collect();
        
        return view('agent.earnings.index', compact('earnings', 'totalEarnings', 'totalPaid', 'totalPending', 'agents'));
    }
    
    /**
     * Display the specified earning.
     */
    public function show(AgentEarning $earning): View
    {
        $this->authorize('view', $earning);
        
        return view('agent.earnings.show', [
            'earning' => $earning->load('agent'),
        ]);
    }
    
    /**
     * Mark an earning as paid.
     */
    public function payout(AgentEarning $earning): RedirectResponse
    {
        $this->authorize('update', $earning);
        
        if (!$earning->is_paid) {
            $earning->update([
                'is_paid' => true,
                'paid_date' => now(),
            ]);
            
            return redirect()->route('admin.referr-agent-earning.index')
                ->with('success', 'Earning marked as paid successfully.');
        }
        
        return redirect()->route('admin.referr-agent-earning.index')
            ->with('error', 'Earning is already marked as paid.');
    }
    
    /**
     * Mark all pending earnings for an agent as paid.
     */
    public function payoutAll(Request $request): RedirectResponse
    {
        $request->validate([
            'agent_id' => 'required|exists:employees,id',
        ]);
        
        $agent = Agent::findOrFail($request->agent_id);
        $this->authorize('payoutAll', [AgentEarning::class, $agent]);
        
        $count = AgentEarning::where('agent_id', $agent->id)
            ->where('is_paid', false)
            ->update([
                'is_paid' => true,
                'paid_date' => now(),
            ]);
            
        return redirect()->route('admin.referr-agent-earning.index')
            ->with('success', "Marked {$count} earnings as paid successfully.");
    }
}
