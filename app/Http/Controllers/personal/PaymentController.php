<?php

namespace App\Http\Controllers\personal;

use App\Http\Controllers\Controller;
use App\Models\personal\Payment;
use App\Models\personal\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class PaymentController extends Controller
{
    /**
     * Display a listing of the payments.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $payments = Payment::with(['lead', 'agent'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('personal.payments.index', compact('payments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created payment in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'lead_id' => 'required|exists:leads,id',
            'total_amount' => 'required|numeric|min:0',
            'payment_amount' => 'required|numeric|min:0',
            'payment_mode' => 'required|string|max:50',
            'utr_no' => 'nullable|string|max:100',
            'reference_number' => 'nullable|string|max:100',
            'payment_date' => 'required|date',
            'payment_screenshot' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Calculate pending amount
        $validated['pending_amount'] = $validated['total_amount'] - $validated['payment_amount'];

        // Handle file upload if present
        if ($request->hasFile('payment_screenshot')) {
            $path = $request->file('payment_screenshot')->store('payment_screenshots', 'public');
            $validated['payment_screenshot'] = $path;
        }

        // Set additional fields
        $validated['agent_id'] = Auth::id();
        $validated['status'] = Payment::STATUS_PENDING;
        $validated['payment_date'] = Carbon::parse($validated['payment_date']);

        // Create the payment
        $payment = Payment::create($validated);

        // Update lead with total fees and status
        $lead = Lead::find($validated['lead_id']);
        if ($lead) {
            // Update total fees if not set or different
            if (empty($lead->total_fees) || $lead->total_fees != $validated['total_amount']) {
                $lead->total_fees = $validated['total_amount'];
            }
            
            // Calculate total paid amount
            $totalPaid = $lead->payments()->sum('payment_amount');
            $pendingAmount = $lead->total_fees - $totalPaid;
            
            // Update lead status based on payment
            if ($pendingAmount <= 0) {
                $lead->status = 'payment_completed';
            } else {
                $lead->status = 'payment_partial';
            }
            
            // Save pending amount
            $lead->pending_amount = $pendingAmount;
            $lead->save();
        }

        return redirect()->route('payments.show', $payment->id)->with('success', 'Payment recorded successfully.');
    }

    /**
     * Display the specified payment.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $payment = Payment::with(['lead', 'agent', 'verifiedBy'])->findOrFail($id);
        
        // Check if the current user has permission to view this payment
        if (Auth::user()->role !== 'admin' && $payment->agent_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('personal.payments.show', compact('payment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Verify a payment.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verify(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);
        
        // Check if the user has permission to verify payments
        if (!Auth::user()->can('verify_payments')) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'notes' => 'nullable|string|max:1000',
        ]);

        $payment->update([
            'status' => Payment::STATUS_VERIFIED,
            'verified_by' => Auth::id(),
            'verified_at' => now(),
            'notes' => $validated['notes'] ?? $payment->notes,
        ]);

        return redirect()->back()
            ->with('success', 'Payment verified successfully.');
    }

    /**
     * Reject a payment.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reject(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);
        
        // Check if the user has permission to reject payments
        if (!Auth::user()->can('verify_payments')) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $payment->update([
            'status' => Payment::STATUS_REJECTED,
            'verified_by' => Auth::id(),
            'verified_at' => now(),
            'notes' => $validated['rejection_reason'],
        ]);

        return redirect()->back()
            ->with('success', 'Payment rejected successfully.');
    }

    /**
     * Get payment details via API.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPaymentDetails($id)
    {
        try {
            $payment = Payment::with(['lead', 'agent'])->findOrFail($id);
            
            // Ensure the logged-in user has access to this payment
            if (Auth::user()->role !== 'admin' && $payment->agent_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to this payment.'
                ], 403);
            }
            
            return response()->json([
                'success' => true,
                'data' => $payment
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error fetching payment details: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch payment details.'
            ], 500);
        }
    }

    /**
     * Show the form for creating a payment for a specific lead.
     *
     * @param  int  $leadId
     * @return \Illuminate\View\View
     */
    public function createForLead($leadId)
    {
        $lead = Lead::findOrFail($leadId);
        return view('personal.payments.create', compact('lead'));
    }

    /**
     * Get payment history for a specific lead.
     *
     * @param  int  $leadId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLeadPayments($leadId)
    {
        $payments = Payment::where('lead_id', $leadId)
            ->orderBy('created_at', 'desc')
            ->get();
            
        return response()->json([
            'success' => true,
            'data' => $payments
        ]);
    }
}
