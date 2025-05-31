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

    public function register(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:employees,emp_email',
                'phone' => 'required|numeric|digits:10',
                'address' => 'required|string',
                'password' => 'required|min:8',
            ]);

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
                'emp_username' => Str::slug($validatedData['name']),
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

            // Get stored OTP from session
            $storedOtp = session('agent_otp');
            
            if (!$storedOtp) {
                throw new \Exception('OTP session expired. Please try again.');
            }

            Log::info('OTP validation passed');
            Log::info('Session data verified');
            Log::info('Stored OTP: ' . $storedOtp);

            // Get agent data from session
            $agentData = session('agent_data');
            Log::info('Agent data: ' . json_encode($agentData));

            if (!$agentData) {
                throw new \Exception('Agent data not found in session.');
            }

            // Create agent record
            $agent = new Agent();
            $agent->emp_name = $agentData['emp_name'];
            $agent->emp_email = $agentData['emp_email'];
            $agent->emp_phone = $agentData['emp_phone'];
            $agent->emp_location = $agentData['emp_location'];
            $agent->emp_password = $agentData['emp_password']; // Already hashed
            $agent->emp_job_role = $agentData['emp_job_role'];
            $agent->emp_username = $agentData['emp_username'];
            $agent->emp_join_date = $agentData['emp_join_date'];
            
            Log::info('Agent data before save: ' . json_encode($agent->toArray()));
            
            $agent->save();

            Log::info('Agent created successfully with data: ' . json_encode([
                'id' => $agent->id,
                'name' => $agent->emp_name,
                'email' => $agent->emp_email
            ]));
            
            Log::info('Agent created successfully');
            
            // Clear session data
            session()->forget(['agent_otp', 'agent_data']);

            // Generate a temporary password
            $tempPassword = Str::random(8);
            
            // Send welcome email with credentials
            try {
                Mail::send('emails.selected', [
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
            
            // Store the plain text password in the database
            $agent->emp_password = $tempPassword; // Storing in plain text for authentication
            $agent->save();
            
            
            return redirect('/')->with('success', 'Agent created successfully!');
            
        } catch (\Exception $e) {
            Log::error('OTP Verification Error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Registration failed. Please try again.']);
        }
    }
}
