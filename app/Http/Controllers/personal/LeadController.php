<?php

namespace App\Http\Controllers\personal;

use App\Http\Controllers\Controller;
use App\Models\personal\Agent;
use App\Models\personal\Attendance;
use App\Models\personal\FollowUp;
use App\Models\personal\Lead;
use App\Models\personal\Payment;
use App\Models\Activity;
use App\Models\Deal;
use App\Models\Employee;
use App\Models\Registration;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class LeadController extends Controller
{
    public function howToUse()
    {
        return view('how-to-use');
    }

    public function addlead()
    {
        return view('personal.add_lead');
    }

    public function add_lead(Request $request)
    {
        $rules = [
            'email' => [
                'required',
                'email',
                'unique:leads,email',  // Ensure email is unique
                function ($attribute, $value, $fail) {
                    // Define allowed domains including .com and .in for Gmail and Yahoo
                    $allowedDomains = ['gmail.com', 'gmail.in', 'yahoo.com', 'yahoo.in'];
                    $domain = substr(strrchr($value, '@'), 1);  // Extract the domain part of the email

                    // Check if the domain is not in the allowed list
                    if (!in_array($domain, $allowedDomains)) {
                        $fail('The email domain must be gmail.com, gmail.in, yahoo.com, or yahoo.in.');
                    }
                }
            ],
            'secondary_email' => 'nullable|email',
            'secondary_email_domain' => 'nullable|string|max:255',
            'secondary_phone' => 'nullable|numeric',
            'college' => 'nullable|string|max:255',  // Optional field
            'branch' => 'nullable|string|max:255',  // Optional field
        ];

        $messages = [
            'email.required' => 'The email field is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email address has already been used.',
            'secondary_email.email' => 'Please enter a valid secondary email address.',
            'secondary_phone.numeric' => 'Secondary phone number must be numeric.',
            'college.string' => 'College must be a valid text.',
            'branch.string' => 'Branch must be a valid text.',
        ];

        // Step 2: Validate the request (only email field)
        // $validator = Validator::make($request->all(), $rules, $messages);

        // // Step 3: Return errors if validation fails (email-related only)
        // if ($validator->fails()) {
        //     return back()->withErrors($validator)->withInput();
        // }

        // Step 4: Create a new Lead instance
        $lead = new Lead();
        $lead->agent_id = session()->get('user_id');
        $lead->first_name = $request->first_name;
        $lead->last_name = $request->last_name;
        $lead->email = $request->email;
        $lead->email_domain = $request->email_domain;
        $lead->secondary_email = $request->secondary_email;
        $lead->secondary_email_domain = $request->secondary_email_domain;
        $lead->phone = $request->phone;
        $lead->secondary_phone = $request->secondary_phone;
        $lead->lead_source = $request->lead_source;
        $lead->university = $request->university;
        $lead->courses = $request->courses;
        $lead->college = $request->college;
        $lead->branch = $request->branch;
        $lead->session_duration = $request->session_duration;
        $lead->status = $request->status;

        // Step 5: Dump the Lead object before saving
        // dd($lead);

        // Step 6: Save the Lead
        $lead->save();

        // Step 7: Confirm that the lead was saved successfully
        // dd('Lead saved successfully');

        // Step 8: Prepare email subject and body
        $subject = "{$lead->courses} Mapping Your Path to Career Versatility";
        $emailBody = "
            Dear {$lead->first_name} {$lead->last_name},<br><br>
            With the increasing demand for skilled professionals across industries, 
            {$lead->university}'s {$lead->courses} degree opens doors to exciting career prospects, 
            master’s degrees, and positions graduates at the forefront of innovation in technology.<br><br>
            Regards,<br>
            Admissions Team<br>
            {$lead->university}
        ";

        // Step 9: Dump email details before sending
        // dd('Email details:', $subject, $emailBody);

        // Step 10: Send email
        Mail::send([], [], function ($message) use ($lead, $subject, $emailBody) {
            $message
                ->to($lead->email)
                ->subject($subject)
                ->html($emailBody);
        });

        // Step 11: Confirm that the email was sent successfully
        // dd('Email sent successfully');

        // Step 12: Final confirmation and redirect
        return back()->with('success', 'Lead added successfully.');
    }

    public function show($id)
    {
        // Retrieve the logged-in user's user_id and job role
        $userId = session()->get('user_id');
        $userRole = session()->get('emp_job_role');

        $lead = Lead::with('followUps')->findOrFail($id);

        // Check access permissions
        if ($userRole == 1) {  // Superadmin role

            return view('personal.view_lead', compact('lead'));
        } elseif ($userRole == 2) {  // Agent role

            if ($lead->agent_id != $userId) {
                return redirect()->back()->with('error', 'You do not have permission to view this lead.');
            }

            return view('personal.view_lead', compact('lead'));
        } else {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }
    }

    // public function show_leads() {

    //     // Retrieve user_id and job_role from the session
    //     $userId = session()->get('user_id');
    //     $userRole = session()->get('emp_job_role');

    //     if ($userRole == 2) { // Agent role
    //         // Fetch leads where agent_id matches the logged-in user's ID
    //         $leads = Lead::where('agent_id', $userId)->get();

    //         // Return the view with only the leads added by this agent
    //         return view('personal.show_lead', compact('leads'));
    //     } elseif ($userRole == 1) { // Admin role
    //         // Admins can see all leads
    //         $leads = Lead::all();

    //          // Add the "Fresh" property to leads where agent_id = 1
    //           foreach ($leads as $lead) {
    //             $lead->is_fresh = $lead->agent_id == 1; // Mark as fresh if agent_id is 1
    //         }

    //         // Return the view with all leads
    //         return view('personal.show_lead', compact('leads'));
    //     } else {
    //         // If the user is neither an agent nor an admin, deny access
    //         return redirect()->back()->with('error', 'Unauthorized access.');
    //     }

    // }

    public function show_leads()
    {
        $userId = session()->get('user_id');
        $userRole = session()->get('emp_job_role');

        if (in_array($userRole, [2, 7])) {  // Agent or Chain Team Agent role
            $leads = Lead::where('agent_id', $userId)->paginate(50);
        } elseif ($userRole == 1) {  // Admin role
            $leads = Lead::paginate(15);
            foreach ($leads as $lead) {
                $lead->is_fresh = $lead->agent_id == 1;
            }
        } else {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }

        return view('personal.show_lead', compact('leads'));
    }

    // Method to show the edit form

    public function edit($id)
    {
        $lead = Lead::findOrFail($id);
        return view('leads.editlead', compact('lead'));
    }

    // Method to update the lead data
    public function update(Request $request, $id)
    {
        $lead = Lead::findOrFail($id);

        $lead->first_name = $request->first_name;
        $lead->last_name = $request->last_name;
        $lead->email = $request->email;
        $lead->phone = $request->phone;
        $lead->lead_source = $request->lead_source;
        $lead->university = $request->university;
        $lead->courses = $request->courses;

        $lead->save();

        return redirect('/i-admin/show-leads')->with('success', 'Lead updated successfully!');
    }

    // Method to delete a lead
    public function destroy($id)
    {
        $lead = Lead::findOrFail($id);
        $lead->delete();

        return redirect('/i-admin/show-leads')->with('success', 'Lead deleted successfully!');
    }

    // View and follow up Edit Functionality

    public function editview($id)
    {
        // Retrieve the lead data by ID
        $lead = Lead::findOrFail($id);

        // Return the edit view with the lead data
        return view('personal.edit_lead', compact('lead'));
    }

    public function updateview(Request $request, $id)
    {
        // Validate the request
        $request->validate([
            'company' => 'required|string|max:255',
            'lead_source' => 'required|string|max:255',
            'lead_status' => 'required|string|max:255',
            'university' => 'required|string|max:255',
            'course' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:15',
        ]);

        // Retrieve the lead data by ID
        $lead = Lead::findOrFail($id);

        // Update the lead details with the new data
        $lead->update([
            'owner' => $request->owner,
            'company' => $request->company,
            'lead_source' => $request->lead_source,
            'lead_status' => $request->lead_status,
            'university' => $request->university,
            'courses' => $request->course,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        // Redirect back to the lead details page with a success message
        return redirect()->back()->with('success', 'Lead updated successfully.');
    }

    public function store(Request $request)
    {
        // Validate the input data
        $request->validate([
            'comments' => 'required|string|max:255',
            'follow_up_time' => 'required|date',
        ]);

        // Retrieve the logged-in agent's ID from the session
        $agentId = session()->get('user_id');

        // Ensure the agent is allowed to follow up on the lead
        $lead = Lead::findOrFail($request->input('lead_id'));

        if ($lead->agent_id != $agentId) {
            // If the logged-in agent doesn't own the lead, deny access
            return redirect()->back()->with('error', 'You do not have permission to follow up on this lead.');
        }

        // Create a new follow-up record
        FollowUp::create([
            'lead_id' => $request->input('lead_id'),
            'agent_id' => $agentId,
            'follow_up_time' => $request->input('follow_up_time'),
            'comments' => $request->input('comments'),
        ]);

        return redirect()->back()->with('success', 'Follow-up recorded successfully!');
    }

    public function todayFollowUps()
    {
        $userId = session()->get('user_id');
        $userRole = session()->get('emp_job_role');

        $todayStart = Carbon::today()->startOfDay();
        $todayEnd = Carbon::today()->endOfDay();

        $query = \App\Models\personal\FollowUp::with(['lead', 'agent'])
            ->whereBetween('follow_up_time', [$todayStart, $todayEnd]);

        if ($userRole == 2) {
            $query->where('agent_id', $userId);
        }

        $followUps = $query->orderBy('follow_up_time', 'asc')->get();
        return view('followups.today', compact('followUps'));
    }
    
    public function tomorrowFollowUps()
    {
        $userId = session()->get('user_id');
        $userRole = session()->get('emp_job_role');

        $tomorrowStart = Carbon::tomorrow()->startOfDay();
        $tomorrowEnd = Carbon::tomorrow()->endOfDay();

        $query = \App\Models\personal\FollowUp::with(['lead', 'agent'])
            ->whereBetween('follow_up_time', [$tomorrowStart, $tomorrowEnd]);

        if ($userRole == 2) {
            $query->where('agent_id', $userId);
        }

        $followUps = $query->orderBy('follow_up_time', 'asc')->get();
        return view('followups.tomorrow', compact('followUps'));
    }
    
    public function upcomingFollowUps()
    {
        $userId = session()->get('user_id');
        $userRole = session()->get('emp_job_role');

        $tomorrow = Carbon::tomorrow()->endOfDay();

        $query = \App\Models\personal\FollowUp::with(['lead', 'agent'])
            ->where('follow_up_time', '>', $tomorrow)
            ->orderBy('follow_up_time', 'asc');

        if ($userRole == 2) {
            $query->where('agent_id', $userId);
        }

        $followUps = $query->get();
        return view('followups.upcoming', compact('followUps'));
    }
    
    public function overdueFollowUps()
    {
        $userId = session()->get('user_id');
        $userRole = session()->get('emp_job_role');
        $today = Carbon::today();

        // Get all leads with follow-ups
        $query = \App\Models\personal\Lead::with(['followUps' => function($q) {
            $q->orderBy('follow_up_time', 'desc');
        }, 'followUps.agent']);

        if ($userRole == 2) {
            $query->where('agent_id', $userId);
        }

        $leads = $query->get();
        
        $overdueFollowUps = collect();
        
        foreach ($leads as $lead) {
            if ($lead->followUps->isNotEmpty()) {
                $latestFollowUp = $lead->followUps->first();
                
                // Check if the latest follow-up is overdue and not updated today
                if ($latestFollowUp->follow_up_time < now() && 
                    $latestFollowUp->updated_at->startOfDay() < $today) {
                    $overdueFollowUps->push($latestFollowUp);
                }
            }
        }
        
        // Sort by follow-up time
        $followUps = $overdueFollowUps->sortBy('follow_up_time');
        
        return view('followups.overdue', compact('followUps'));
    }

    public function login(Request $request)
    {
        // Authenticate agent (this is an example, adjust according to your logic)
        if (Auth::attempt($request->only('email', 'password'))) {
            $agentId = Auth::id();  // Get the authenticated agent's ID

            // Mark attendance after login
            $this->markAttendance($agentId);

            // Redirect or return response
            return redirect()->route('dashboard');
        }

        return back()->withErrors(['Invalid login credentials.']);
    }

    public function markAttendance($agentId)
    {
        $today = Carbon::today();
        $currentTime = Carbon::now();
        $currentIp = request()->ip();

        // Check if today's attendance already exists
        $attendance = Attendance::firstOrCreate(
            ['agent_id' => $agentId, 'date' => $today],
            ['ip_address' => $currentIp]  // Store IP address when record is created
        );

        // Update login times based on the exact hour and minute
        if ($currentTime->hour === 10 && $currentTime->minute === 0 && !$attendance->morning_login) {
            $attendance->morning_login = $currentTime->toTimeString();
        } elseif ($currentTime->hour === 13 && $currentTime->minute === 0 && !$attendance->afternoon_login) {
            $attendance->afternoon_login = $currentTime->toTimeString();
        } elseif ($currentTime->hour === 18 && $currentTime->minute === 0 && !$attendance->evening_login) {
            $attendance->evening_login = $currentTime->toTimeString();
        }

        // Determine attendance status
        $loginsCount = 0;
        if ($attendance->morning_login)
            $loginsCount++;
        if ($attendance->afternoon_login)
            $loginsCount++;
        if ($attendance->evening_login)
            $loginsCount++;

        if ($loginsCount === 3) {
            $attendance->status = 'full';
        } elseif ($loginsCount > 0) {
            $attendance->status = 'half';
        } else {
            $attendance->status = 'absent';
        }

        // Save the updated attendance
        $attendance->save();
    }

    // Payment process functionality

    public function showPaymentPage($leadId)
    {
        // Retrieve the logged-in agent's ID from the session
        $agentId = session()->get('user_id');

        // Retrieve the lead and verify that the agent has access
        $lead = Lead::where('id', $leadId)
            ->where('agent_id', $agentId)
            ->first();

        if (!$lead) {
            abort(403, 'Access denied. You are not authorized to manage this lead.');
        }

        // Get payment history and calculate pending amount
        $payments = Payment::where('lead_id', $leadId)->get();
        $paymentHistory = [];
        $totalPaid = 0;
        $totalAmount = 0;
        $startYear = null;
        $duration = null;
        $sessionDuration = null;
        $sessionType = null;
        $feeType = null;

        // Get the latest payment information
        $latestPayment = $payments->sortByDesc('created_at')->first();
        if ($latestPayment) {
            $totalAmount = $latestPayment->total_amount ?? 0;
            $startYear = $latestPayment->start_year ?? date('Y');
            $duration = $latestPayment->duration ?? 3;
            $sessionDuration = $latestPayment->session_duration;
            $sessionType = $latestPayment->session;
            $feeType = $latestPayment->fee_type;
        }

        foreach ($payments as $payment) {
            $paymentHistory[] = [
                'date' => $payment->payment_date ? $payment->payment_date->format('d M Y') : $payment->created_at->format('d M Y'),
                'amount' => $payment->payment_amount,
                'status' => $payment->status,
                'id' => $payment->id
            ];

            if ($payment->status !== 'rejected') {
                $totalPaid += $payment->payment_amount;
            }
        }

        // Calculate pending amount
        $pendingAmount = $totalAmount - $totalPaid;
        if ($pendingAmount < 0)
            $pendingAmount = 0;

        // Update lead status based on payment
        if ($totalAmount > 0) {
            if ($pendingAmount <= 0) {
                $lead->status = 'payment_completed';
            } else {
                $lead->status = 'payment_partial';
            }
            $lead->save();
        }

        return view('personal.payment', compact(
            'lead',
            'paymentHistory',
            'totalPaid',
            'pendingAmount',
            'totalAmount',
            'startYear',
            'duration',
            'sessionDuration',
            'sessionType',
            'feeType'
        ));
    }

    public function processPayment(Request $request)
    {
        \Illuminate\Support\Facades\Log::info('Starting payment processing', ['request_data' => $request->all()]);

        try {
            // Retrieve the logged-in agent's ID from the session
            $agentId = session()->get('user_id');
            if (!$agentId) {
                \Illuminate\Support\Facades\Log::error('No agent ID found in session');
                return back()->withInput()->with('error', 'Session expired. Please log in again.');
            }
            \Illuminate\Support\Facades\Log::info('Session user_id', ['user_id' => $agentId]);

            // Validate the lead and agent access
            $lead = Lead::where('id', $request->lead_id)
                ->where('agent_id', $agentId)
                ->first();

            if (!$lead) {
                \Illuminate\Support\Facades\Log::error('Lead not found or unauthorized access', [
                    'lead_id' => $request->lead_id,
                    'agent_id' => $agentId
                ]);
                return back()->with('error', 'Access denied. You are not authorized to process payment for this lead.');
            }

            \Illuminate\Support\Facades\Log::info('Lead found', ['lead' => $lead->toArray()]);

            try {
                $validated = $request->validate([
                    'lead_id' => 'required|exists:leads,id',
                    'total_amount' => 'required|numeric|min:0',
                    'start_year' => 'required|integer|min:2000|max:2050',
                    'duration' => 'required|integer|min:1|max:10',
                    'session_duration' => 'required|string',
                    'session' => 'required|string',
                    'fee_type' => 'required|string',
                    'payment_screenshot' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                    'utr_no' => 'required|digits:12|unique:payments,utr_no',
                    'payment_mode' => 'required|string',
                    'payment_details_input' => 'required|string',
                    'payment_amount' => 'required|numeric',
                    'pending_amount' => 'required|numeric',
                    'loan_amount' => 'nullable|numeric',
                    'loan_details' => 'nullable|string',
                ]);
                \Illuminate\Support\Facades\Log::info('Validation passed', $validated);
            } catch (\Illuminate\Validation\ValidationException $e) {
                \Illuminate\Support\Facades\Log::error('Validation failed', [
                    'errors' => $e->errors(),
                ]);
                return back()->withErrors($e->errors())->withInput()->with('error', 'Please check the form for errors.');
            }

            // Create uploads directory if it doesn't exist
            $uploadPath = 'images/payment-screenshots';
            try {
                if (!file_exists(public_path($uploadPath))) {
                    if (!mkdir(public_path($uploadPath), 0777, true)) {
                        throw new \Exception('Failed to create upload directory');
                    }
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Error creating upload directory', [
                    'path' => $uploadPath,
                    'error' => $e->getMessage()
                ]);
                return back()->withInput()->with('error', 'Server error: Unable to create upload directory. Please contact support.');
            }

            // Handle file upload
            $filePath = null;
            try {
                if ($request->hasFile('payment_screenshot')) {
                    $file = $request->file('payment_screenshot');
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $file->move(public_path($uploadPath), $fileName);
                    $filePath = $uploadPath . '/' . $fileName;
                    \Illuminate\Support\Facades\Log::info('File uploaded successfully', ['file_path' => $filePath]);
                } else {
                    \Illuminate\Support\Facades\Log::warning('No payment screenshot file found in request');
                    return back()->withInput()->with('error', 'Payment screenshot is required.');
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('File upload failed', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                return back()->withInput()->with('error', 'Failed to upload payment screenshot. Please try again.');
            }

            // Calculate total paid so far for this lead (excluding the current payment)
            $existingPayments = Payment::where('lead_id', $request->lead_id)->get();
            $totalPaid = 0;
            foreach ($existingPayments as $payment) {
                if ($payment->status !== 'rejected') {
                    $totalPaid += $payment->payment_amount;
                }
            }

            // Calculate the actual pending amount after this payment
            $totalAmount = $request->total_amount;
            $paymentAmount = $request->payment_amount;
            $pendingAmount = $totalAmount - ($totalPaid + $paymentAmount);
            if ($pendingAmount < 0)
                $pendingAmount = 0;

            // Prepare payment data
            $paymentData = [
                'lead_id' => $request->lead_id,
                'agent_id' => $agentId,
                'total_amount' => $totalAmount,
                'start_year' => $request->start_year,
                'duration' => $request->duration,
                'session_duration' => $request->session_duration,
                'session' => $request->session,
                'fee_type' => $request->fee_type,
                'payment_screenshot' => $filePath,
                'utr_no' => $request->utr_no,
                'payment_mode' => $request->payment_mode,
                'payment_details_input' => $request->payment_details_input,
                'payment_amount' => $paymentAmount,
                'pending_amount' => $pendingAmount,
                'bank' => $request->payment_mode === 'netbanking' ? $request->bank : null,
                'loan_amount' => $request->loan_amount,
                'loan_details' => $request->loan_details,
            ];

            \Illuminate\Support\Facades\Log::info('Payment calculation', [
                'total_amount' => $totalAmount,
                'already_paid' => $totalPaid,
                'current_payment' => $paymentAmount,
                'pending_amount' => $pendingAmount
            ]);

            \Illuminate\Support\Facades\Log::info('Attempting to create payment record', $paymentData);

            // Create the payment record in the database
            try {
                $payment = Payment::create($paymentData);
                \Illuminate\Support\Facades\Log::info('Payment record created successfully', [
                    'payment_id' => $payment->id,
                    'data' => $payment->toArray()
                ]);

                // Get lead name for the success message
                $leadName = $lead->name ?? 'Lead #' . $lead->id;
                $formattedAmount = number_format($request->payment_amount, 2);

                // Create a detailed success message
                $successMessage = "Payment of ₹{$formattedAmount} for {$leadName} has been recorded successfully. ";
                $successMessage .= "Payment mode: {$request->payment_mode}. ";
                $successMessage .= "UTR Number: {$request->utr_no}. ";
                $successMessage .= 'Thank you for your payment!';

                return redirect('/i-admin/show-leads')
                    ->with('success', $successMessage);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Failed to create payment record', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'payment_data' => $paymentData
                ]);
                throw $e;  // Re-throw to be caught by the outer try-catch
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error processing payment', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Determine a more specific error message based on the exception
            $errorMessage = 'An error occurred while processing your payment. Please try again.';

            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                $errorMessage = 'This UTR number has already been used. Please check and enter the correct UTR number.';
            } elseif (strpos($e->getMessage(), 'Column') !== false && strpos($e->getMessage(), 'cannot be null') !== false) {
                $errorMessage = 'Some required payment information is missing. Please fill in all required fields.';
            } elseif (strpos($e->getMessage(), 'Connection refused') !== false) {
                $errorMessage = 'Database connection error. Please try again later or contact support.';
            }

            return back()
                ->withInput()
                ->with('error', $errorMessage);
        }
    }

    public function showPendingPayments()
    {
        // Retrieve the logged-in agent's ID from the session
        $agentId = session()->get('user_id');

        // Fetch leads assigned to the logged-in agent with their payment history
        $leads = Lead::with('payments')
            ->where('agent_id', $agentId)  // Filter leads by agent
            ->get();

        // Define fixed values for total fees, total semesters, and total years
        $totalFees = 100000;  // Example total fees for the course
        $totalSemesters = 6;  // Example total number of semesters
        $totalYears = 3;  // Example total number of years

        // Iterate over each lead to calculate the pending amount
        foreach ($leads as $lead) {
            // Calculate the total amount paid by the lead
            $paidAmount = $lead->payments->sum('payment_amount');

            // Calculate the pending amount based on the payment method
            if ($lead->session === 'semester') {
                // Calculate fees per semester
                $feesPerSemester = $totalFees / $totalSemesters;
                $pendingAmount = $totalFees - $paidAmount;
            } elseif ($lead->session === 'year') {
                // Calculate fees per year
                $feesPerYear = $totalFees / $totalYears;
                $pendingAmount = $totalFees - $paidAmount;
            } else {
                // Default case: full course fees
                $pendingAmount = $totalFees - $paidAmount;
            }

            // Ensure the pending amount does not go negative
            $lead->pending_amount = max($pendingAmount, 0);
        }

        // Pass leads with pending amounts to the view
        return view('personal.pending-payments', compact('leads'));
    }

    public function showConvertLeadForm($id)
    {
        $lead = Lead::findOrFail($id);
        return view('leads.convert_lead', compact('lead'));
    }

    public function transferView()
    {
        // Fetch agents with emp_job_role = 2 (agents)
        $agents = Employee::where('emp_job_role', 2)->select('id', 'emp_name')->get();

        // Fetch fresh leads (leads with agent_id = 1)
        $freshLeads = Lead::where('agent_id', 1)->when(request()->status, function ($Lead) {
            $Lead->where('status', request()->status);
        })->get();

        return view('personal.leadtransfer', compact('agents', 'freshLeads'));
    }

    public function transferViewDetail()
    {
        // Fetch agents with emp_job_role = 2 (agents)
        $agents = Employee::where('emp_job_role', 2)->select('id', 'emp_name')->get();

        // Fetch fresh leads (leads with agent_id = 1)
        $freshLeads = Lead::when(request()->agent_id, function ($lead) {
            $lead->where('agent_id', request()->agent_id);
        })->when(request()->status, function ($Lead) {
            $Lead->where('status', request()->status);
        })->paginate(50);

        return view('personal.leadtransferdetail', compact('agents', 'freshLeads'));
    }

    public function transferLeads(Request $request)
    {
        // $request->validate([
        //     'agent_id' => 'required|exists:employees,id',
        //     'lead_ids' => 'required|array',
        //     'lead_ids.*' => 'exists:leads,id',
        // ]);

        // Update leads with the selected agent_id
        Lead::whereIn('id', $request->lead_ids)->update(['agent_id' => $request->agent_id]);

        return redirect()->route('leads.transfer.view')->with('success', 'Leads successfully transferred!');
    }

    // Payment Verify Functionality

    public function index()
    {
        // Fetch all payments with their related leads

        $payments = Payment::with('lead.agent')->get();

        return view('personal.payment_verify', compact('payments'));
    }

    public function verify(Request $request, $id)
    {
        try {
            // Find the payment with its lead relationship
            $payment = Payment::with('lead')->find($id);
            
            if (!$payment) {
                return redirect()->back()->with('error', 'Payment not found.');
            }

            // Update payment status to verified
            $payment->status = Payment::STATUS_VERIFIED;
            $payment->verified_by = auth()->id ?? session()->get('user_id');
            $payment->verified_at = now();
            $payment->save();

            // Check if lead exists
            if (!isset($payment->lead) || !$payment->lead) {
                return redirect()->back()->with('error', 'No lead associated with this payment.');
            }

            $lead = $payment->lead;

            // Ensure lead has an ID
            if (!isset($lead->id)) {
                return redirect()->back()->with('error', 'Invalid lead data.');
            }

            // Calculate total paid amount and total amount from payments
            $payments = Payment::where('lead_id', $lead->id)
                ->where('status', '!=', Payment::STATUS_REJECTED)
                ->get();

            $totalPaid = $payments->sum('payment_amount');

            // Get the latest total amount from payments
            $latestPayment = $payments->sortByDesc('created_at')->first();
            $totalAmount = $latestPayment ? $latestPayment->total_amount : 0;

            // Calculate pending amount
            $pendingAmount = $totalAmount - $totalPaid;

            // Update lead status based on payment
            if ($pendingAmount <= 0) {
                $lead->status = 'payment_completed';
            } else {
                $lead->status = 'payment_partial';
            }
            $lead->save();

            return redirect()->route('payment.verify')->with('success', 'Payment has been verified successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error verifying payment: ' . $e->getMessage());
        }
    }

    /**
     * Get payment details via API
     *
     * @param int $id Payment ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPaymentDetails($id)
    {
        try {
            $payment = Payment::findOrFail($id);

            // Ensure the logged-in user has access to this payment
            $agentId = session()->get('user_id');
            if ($payment->agent_id != $agentId) {
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
            Log::error('Error fetching payment details: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch payment details.'
            ], 500);
        }
    }

    public function updateStatus(Request $request)
    {
        try {
            $request->validate([
                'lead_id' => 'required|exists:leads,id',
                'new_status' => 'required|string',
                'comments' => 'required|string|max:1000',
            ]);

            // Get the authenticated agent's ID
            $agentId = session()->get('user_id');
            if (!$agentId) {
                throw new \Exception('Agent not authenticated');
            }

            // Update Lead Status
            $lead = Lead::findOrFail($request->lead_id);
            
            // Verify the agent has access to this lead
            if ($lead->agent_id != $agentId) {
                throw new \Exception('You do not have permission to update this lead.');
            }

            $oldStatus = $lead->status;
            $newStatus = $request->new_status;
            $statusChanged = $oldStatus !== $newStatus;

            // Only update status if it's actually changing
            if ($statusChanged) {
                $lead->status = $newStatus;
            }
            
            // Update next follow-up time if provided
            if ($request->filled('next_follow_up')) {
                $lead->next_lead_datetime = $request->next_follow_up;
            }
            
            $lead->save();

            // Prepare comments for the follow-up
            $comments = $request->comments;
            if ($statusChanged) {
                $oldStatusFormated = ucfirst(str_replace('_', ' ', $oldStatus));
                $newStatusFormated = ucfirst(str_replace('_', ' ', $newStatus));
                $statusComment = "Status changed from " . $oldStatusFormated . " to " . $newStatusFormated;
                $comments = $statusComment . (empty($comments) ? '' : "\n" . $comments);
            }

            // Create a new follow-up entry
            $followUp = new FollowUp();
            $followUp->lead_id = $lead->id;
            $followUp->agent_id = $agentId;
            $followUp->comments = $comments;
            $followUp->follow_up_time = $request->next_follow_up ?: now()->addDay();
            $followUp->action = 'status-update';
            $followUp->save();

            // Return JSON response for AJAX
            return response()->json([
                'success' => true,
                'message' => 'Lead status updated successfully.',
                'status' => $newStatus,
                'next_follow_up' => $followUp->follow_up_time
            ]);

        } catch (\Exception $e) {
            // Log the error
            Log::error('Error updating lead status: ' . $e->getMessage());
            
            // Return JSON error response
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
