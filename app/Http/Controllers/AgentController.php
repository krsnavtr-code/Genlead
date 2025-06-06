<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\personal\Agent;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Mail\AgentDocumentUploadMail;

class AgentController extends Controller
{
    public function showRegistrationForm()
    {
        return view('agent.register');
    }
    
    /**
     * Check if a username is available
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    /**
     * Generate a unique username from a name
     * 
     * @param string $name
     * @return string
     */
    /**
     * Check if an email is already registered
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);
        
        $exists = \App\Models\personal\Agent::where('emp_email', $request->email)->exists();
        
        return response()->json([
            'available' => !$exists,
            'email' => $request->email
        ]);
    }

    /**
     * Generate a unique username from a name
     * 
     * @param string $name
     * @return string
     */
    private function generateUniqueUsername($name)
    {
        // Convert name to lowercase and replace spaces with underscores
        $baseUsername = strtolower(preg_replace('/[^a-zA-Z0-9_]/', '', str_replace(' ', '_', $name)));
        
        // If the base username is empty, use a default prefix
        if (empty($baseUsername)) {
            $baseUsername = 'user' . time();
        }
        
        $username = $baseUsername;
        $counter = 1;
        
        // Check if username exists, if it does, append a number and try again
        while (\App\Models\personal\Agent::where('emp_username', $username)->exists()) {
            $username = $baseUsername . $counter;
            $counter++;
            
            // Safety check to prevent infinite loops
            if ($counter > 100) {
                $username = $baseUsername . '_' . time();
                break;
            }
        }
        
        return $username;
    }

    public function register(Request $request)
    {
        try {
            // Validate the request data
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:employees,emp_email',
                'phone' => [
                    'required',
                    'numeric',
                    'digits:10',
                    'regex:/^[0-9]{10}$/'
                ],
                'address' => 'required|string',
                'password' => 'required|min:8',
                'referral_code' => 'nullable|string|exists:employees,referral_code',
            ], [
                'email.unique' => 'This email is already registered.',
                'phone.numeric' => 'Phone number must contain only numbers.',
                'phone.digits' => 'Phone number must be exactly 10 digits long.',
                'phone.regex' => 'Please enter a valid 10-digit phone number.',
                'referral_code.exists' => 'The referral code is invalid.'
            ]);
            
            // Generate a unique username from the agent's name
            $validatedData['emp_username'] = $this->generateUniqueUsername($validatedData['name']);

            // Find referrer if referral code is provided
            $referrer = null;
            if (!empty($validatedData['referral_code'])) {
                $referrer = Agent::where('referral_code', $validatedData['referral_code'])->first();
                
                if (!$referrer) {
                    return back()->with('error', 'Invalid referral code. Please check and try again.');
                }
            }

            Log::info('Registration data validated successfully');
            Log::info('Validated data: ' . json_encode($validatedData));

            // Generate numeric OTP and store it in session
            $otp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
            Log::info('Generated OTP: ' . $otp);

            // Store OTP in session
            session(['agent_otp' => $otp]);

            // Prepare agent data for session
            $agentData = [
                'emp_name' => $validatedData['name'],
                'emp_email' => $validatedData['email'],
                'emp_phone' => $validatedData['phone'],
                'emp_location' => $validatedData['address'],
                'emp_password' => $validatedData['password'], // Storing password in plain text for authentication
                'emp_password_hash' => Hash::make($validatedData['password']), // Also store hashed version for security
                'emp_job_role' => 2,
                'emp_username' => $validatedData['emp_username'],
                'emp_join_date' => now(),
            ];

            // Add referrer_id if referral code is valid
            if ($referrer) {
                $agentData['referrer_id'] = $referrer->id;
            }

            // Store agent data in session
            session(['agent_data' => $agentData]);

            Log::info('Session data stored');
            Log::info('Current session: ' . json_encode(session()->all()));

            // Send OTP email
            Mail::raw('Your OTP for agent registration is: ' . $otp, function ($message) use ($validatedData) {
                $message->to($validatedData['email'])
                        ->subject('Agent Registration OTP Verification');
            });

            Log::info('OTP email sent to: ' . $validatedData['email']);

            // Check if session data is accessible
            $sessionCheck = session()->all();
            Log::info('Session check before redirect: ' . json_encode($sessionCheck));

            return redirect()->route('agent.verify-otp')
                ->with('success', 'An OTP has been sent to your email. Please check your inbox.');

        } catch (\Exception $e) {
            Log::error('Registration error: ' . $e->getMessage());
            return back()->with('error', 'Registration failed. Please try again.');
        }
    }

    public function verifyOtpForm()
    {
        // Check if we have agent data in session
        if (!session('agent_data')) {
            return redirect()->route('agent.register.form')
                ->with('error', 'Session expired. Please register again.');
        }
        
        return view('agent.verify-otp');
    }
    
    /**
     * Resend OTP to the agent's email
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function resendOtp(Request $request)
    {
        try {
            // Check if we have agent data in session
            $agentData = session('agent_data');
            if (!$agentData) {
                return response()->json([
                    'success' => false,
                    'message' => 'Session expired. Please register again.'
                ], 400);
            }
            
            // Generate new OTP
            $otp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
            
            // Store new OTP in session
            session(['agent_otp' => $otp]);
            
            // Send new OTP email
            Mail::raw('Your new OTP for agent registration is: ' . $otp, function ($message) use ($agentData) {
                $message->to($agentData['emp_email'])
                      ->subject('New OTP for Agent Registration');
            });
            
            return response()->json([
                'success' => true,
                'message' => 'New OTP has been sent to your email.'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error resending OTP: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to resend OTP. Please try again.'
            ], 500);
        }
    }

    public function verifyOtp(Request $request)
    {
        try {
            Log::info('Starting OTP verification');
            Log::info('Submitted data: ' . json_encode($request->all()));

            // Validate OTP format
            $validated = $request->validate([
                'otp' => [
                    'required',
                    'string',
                    'digits:6',
                    'regex:/^[0-9]{6}$/'
                ]
            ], [
                'otp.required' => 'Please enter the OTP.',
                'otp.digits' => 'OTP must be exactly 6 digits.',
                'otp.regex' => 'Please enter a valid 6-digit OTP.'
            ]);

            // Get stored OTP and agent data from session
            $storedOtp = session('agent_otp');
            $agentData = session('agent_data');
            
            if (!$storedOtp || !$agentData) {
                Log::warning('Session data missing');
                return back()
                    ->withInput()
                    ->withErrors(['otp' => 'Your session has expired. Please register again.']);
            }

            $submittedOtp = $request->input('otp');
            
            Log::info('Session data verified');
            Log::info('Stored OTP: ' . $storedOtp);
            Log::info('Submitted OTP: ' . $submittedOtp);
            Log::info('Agent data: ' . json_encode($agentData));

            // Check if OTP matches
            if ($submittedOtp !== $storedOtp) {
                // Check if we should show remaining attempts
                $attempts = session('otp_attempts', 0) + 1;
                session(['otp_attempts' => $attempts]);
                
                $remainingAttempts = max(0, 5 - $attempts);
                
                if ($remainingAttempts <= 0) {
                    // Clear session after too many attempts
                    session()->forget(['agent_otp', 'agent_data', 'otp_attempts']);
                    return redirect()->route('agent.register.form')
                        ->with('error', 'Too many failed attempts. Please register again.');
                }
                
                $message = 'The OTP you entered is incorrect.';
                if ($remainingAttempts < 3) {
                    $message .= ' ' . $remainingAttempts . ' attempts remaining.';
                }
                
                return back()
                    ->withInput()
                    ->withErrors(['otp' => $message]);
            }
            
            // Reset attempts on successful OTP verification
            session()->forget('otp_attempts');

            // Check if username already exists
            $existingAgent = Agent::where('emp_username', $agentData['emp_username'])->first();
            if ($existingAgent) {
                Log::warning('Username already exists: ' . $agentData['emp_username']);
                return back()->withErrors(['error' => 'This username is already taken. Please try a different one.']);
            }

            // Check if email already exists
            $existingEmail = Agent::where('emp_email', $agentData['emp_email'])->first();
            if ($existingEmail) {
                Log::warning('Email already exists: ' . $agentData['emp_email']);
                return back()->withErrors(['error' => 'This email is already registered. Please use a different email.']);
            }

            // Remove sensitive data before saving
            $agentData = array_diff_key($agentData, ['emp_password_hash' => '']);
            Log::info('Agent data before save: ' . json_encode($agentData));

            // Create the agent
            $agent = Agent::create($agentData);

            if (!$agent) {
                throw new \Exception('Failed to create agent');
            }

            Log::info('Agent created successfully with data: ' . json_encode([
                'id' => $agent->id,
                'name' => $agent->emp_name,
                'email' => $agent->emp_email
            ]));

            // Generate a temporary password
            $tempPassword = Str::random(8);

            // Store the plain text password in the database
            $agent->emp_password = $tempPassword; // Storing in plain text for authentication
            $agent->save();

            // Send welcome email with credentials
            try {
                Mail::send('emails.agent_credentials', [
                    'candidate' => (object)[
                        'name' => $agent->emp_name
                    ],
                    'username' => $agent->emp_username,
                    'password' => $tempPassword
                ], function($message) use ($agent) {
                    $message->to($agent->emp_email, $agent->emp_name)
                          ->subject('Welcome to Our Company - Your Account Details');
                });

                Log::info('Welcome email sent to: ' . $agent->emp_email);
            } catch (\Exception $e) {
                Log::error('Failed to send welcome email: ' . $e->getMessage());
                // Continue with registration even if email fails
            }

            // Clear session data
            session()->forget(['agent_otp', 'agent_data']);

            return redirect('/')->with('success', 'Agent created successfully!');

        } catch (\Exception $e) {
            Log::error('OTP Verification Error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Registration failed. ' . $e->getMessage()]);
        }
    }
}
