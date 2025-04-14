<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Employee ;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class AgentApiController extends Controller
{
    public function register(Request $request)
    {

    $request->validate([
        'emp_name' => 'required|string',
        'emp_job_role' => 'required|string',
        'emp_email' => 'required|email',
        'emp_location' => 'required|string',
        'emp_phone' => 'required|numeric',
        'referral_code' => 'nullable|string',
        'emp_pic' => 'required|image|mimes:jpeg,png,jpg',
    ]);

    $referrer_id = null;
    if ($request->referral_code) {
        $referrer = Employee::where('referral_code', $request->referral_code)->first();
        $referrer_id = $referrer->id;
    }

    // Generate agent login credentials
    $candidate = $request->emp_name;
    $email = $request->emp_email;
    $username = strtolower(str_replace(' ', '_', $candidate)) . rand(1000, 9999);
    $password = Str::random(8); // Plain text password

    if (!$request->hasFile('emp_pic')) {
        return response()->json(['message' => 'Employee picture is required'], 422);
    }

    $file = $request->file('emp_pic');
    $filename = time() . '_' . $file->getClientOriginalName();
    $filePath = public_path('images/employee_pics');
    $file->move($filePath, $filename);

    // Save file path in the database
    $empPicPath = 'images/employee_pics/' . $filename;

    $agent = Employee::create([
        'emp_name' => $candidate,
        'emp_email' => $request->emp_email,
        'emp_phone' => $request->emp_phone,
        'emp_location' => $request->emp_location,        
        'emp_username' => $username,
        'emp_password' => $password,
        'emp_job_role' => $request->emp_job_role === 'Agent' ? 2 : 'null', // Save 2 if designation is 'Agent', otherwise save 1
        'referrer_id' => $referrer_id,
        'referral_code' => $username,
        'emp_join_date' => now()->format('Y-m-d'),
        'emp_pic' => $empPicPath,
    ]);    

    // dd($agent) ;
    // Send an email to the new joinee with agent login credentials
    $candidateData = new \stdClass();
    $candidateData->name = $candidate;
    Mail::send('emails.agent_credentials', [
        'candidate' => $candidateData,
        'username' => $username,
        'password' => $password
    ], function ($message) use ($email) {
        $message->to($email)->subject('Your Agent Login Credentials');
    });

    return response()->json(['message' => 'Agent registered successfully'], 200);
    }
}
