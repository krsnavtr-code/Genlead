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
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:employees,emp_email',
                'phone' => 'required|numeric|digits:10',
                'address' => 'required|string',
                'password' => 'required|min:8',
            ], [
                'email.unique' => 'This email is already registered.'
            ]);
            
            // Generate a unique username from the agent's name
            $validatedData['emp_username'] = $this->generateUniqueUsername($validatedData['name']);

            Log::info('Registration data validated successfully');
            Log::info('Validated data: ' . json_encode($validatedData));

            // Generate numeric OTP and store it in session
            $otp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
            Log::info('Generated OTP: ' . $otp);

            // Store OTP in session
            session(['agent_otp' => $otp]);

            // Store agent data in session
            session(['agent_data' => [
                'emp_name' => $validatedData['name'],
                'emp_email' => $validatedData['email'],
                'emp_phone' => $validatedData['phone'],
                'emp_location' => $validatedData['address'],
                'emp_password' => $validatedData['password'], // Storing password in plain text for authentication
                'emp_password_hash' => Hash::make($validatedData['password']), // Also store hashed version for security
                'emp_job_role' => 2,
                'emp_username' => $validatedData['emp_username'],
                'emp_join_date' => now()
            ]]);

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
        return view('agent.verify-otp');
    }

    public function verifyOtp(Request $request)
    {
        try {
            Log::info('Starting OTP verification');
            Log::info('Submitted data: ' . json_encode($request->all()));

            // Validate OTP
            $request->validate([
                'otp' => 'required|string|digits:6',
            ]);

            // Get stored OTP and agent data from session
            $storedOtp = session('agent_otp');
            $agentData = session('agent_data');
            
            if (!$storedOtp || !$agentData) {
                Log::warning('Session data missing');
                return back()->withErrors(['error' => 'Session expired. Please register again.']);
            }

            $submittedOtp = $request->input('otp');
            
            Log::info('Session data verified');
            Log::info('Stored OTP: ' . $storedOtp);
            Log::info('Agent data: ' . json_encode($agentData));

            if ($submittedOtp !== $storedOtp) {
                return back()->withErrors(['otp' => 'Invalid OTP. Please try again.']);
            }

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
