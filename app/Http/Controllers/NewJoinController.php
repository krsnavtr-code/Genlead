<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\personal\Agent;
use App\Models\Document;
use App\Models\Employee;
use App\Models\hrlogin;
use App\Models\NewEmployee;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Str;
use setasign\Fpdi\Fpdi;

class NewJoinController extends Controller
{
    // New Joinee related work

    public function showAddEmployeeForm()
    {
        return view('newJoin.add_candidate');
    }

    public function sendWelcomeLink(Request $request)
    {
        //  $token = Str::random(32);
        //  $expiration = Carbon::now()->addHours(48);

        //  $newEmployee = NewEmployee::create([
        //      'name' => $request->name,
        //      'email' => $request->email,
        //      'branch' => $request->branch,
        //      'token' => $token,
        //      'token_expiration' => $expiration,
        //  ]);

        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50',
            'email' => 'required|email|max:40',
            'phone' => 'required|string|max:10',
            'branch' => 'required|string|max:30',
            'location' => 'required|string|max:30',
            'salary_discussed' => 'required|in:Yes,No',
            'resume' => 'required|file|mimes:pdf,doc,docx',
            'interview_process' => 'required|in:online,offline',
            'interview_date_time' => 'required|date',
        ]);

        // If validation fails, redirect back with errors in session
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Handle resume upload
        $resumePath = $request->file('resume')->store('resumes', 'public');

        // Save candidate to the database
        $candidate = NewEmployee::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'branch' => $request->branch,
            'location' => $request->location,
            'salary_discussed' => $request->salary_discussed == 'Yes',
            'salary_amount' => $request->salary_discussed == 'Yes' ? $request->salary_amount : null,
            'resume' => $resumePath,
            'interview_process' => $request->interview_process,
            'interview_date_time' => $request->interview_date_time,
        ]);

        // Calculate confirmation deadline based on the created_at field
        $confirmationDeadline = Carbon::parse($candidate->created_at)->addDays(2)->format('Y-m-d');

        // Prepare email subject and body
        $subject = 'Interview Invitation for FirstVite e-Learning Pvt Ltd';
        $emailBody = "

       Dear {$candidate->name},<br><br>

        We are pleased to invite you for an interview at FirstVite e-Learning Pvt. Ltd. We have reviewed your application and are excited to discuss your qualifications in more detail.<br><br>

        <strong>Interview Details:</strong><br>
        Date: {$candidate->interview_date_time}<br>
        Time: 10 AM to 5 PM<br>
        Location: Noida sector 63<br>
        Interviewer(s): Dishant Gautam<br><br>

        Please confirm your availability for the interview by <strong>{$confirmationDeadline}</strong>. If you have any questions or need to reschedule, feel free to reach out to me at 7669447242.<br><br>

        We look forward to speaking with you soon.<br><br>

        Best regards,<br>
        Dishant Gautam<br>
        HR<br>
        FirstVite E-Learning Pvt. Ltd.<br>
        7669447242<br>
    ";

        // Send email using the Mail facade
        Mail::send([], [], function ($message) use ($candidate, $subject, $emailBody) {
            $message
                ->to($candidate->email)
                ->cc('info@firstvite.com')  // HR email as CC
                ->subject($subject)
                ->html($emailBody);
        });

        // return back()->with('success', 'Candidate details saved and email sent.');

        session()->flash('success', 'New Candidate added successfully ');
        return redirect()->back();
    }

    // Method to display the "Candidate Interview Result" page
    public function candidateInterviewResult()
    {
        $new_employees = NewEmployee::all();

        //    dd($new_employees);

        return view('newJoin.candidate_interview_result', compact('new_employees'));
    }

    // Method to handle the interview result submission

    public function submitInterviewResult(Request $request)
    {
        $candidateId = $request->input('candidate_id');
        $result = $request->input('result');

        $candidate = NewEmployee::find($candidateId);

        if ($candidate) {
            $candidate->interview_result = $result;
            $candidate->save();

            if ($result === 'Selected') {
                // Generate username and password
                $username = strtolower(str_replace(' ', '_', $candidate->name)) . rand(1000, 9999);
                $password = Str::random(8);

                // Save the username and password in the database
                $candidate->username = $username;
                $candidate->password = $password;
                $candidate->link_expiry = Carbon::now()->addHours(48);  // Set link expiry to 48 hours
                $candidate->save();

                // Send email to the candidate
                Mail::send('emails.selected', ['candidate' => $candidate, 'username' => $username, 'password' => $password], function ($message) use ($candidate) {
                    $message
                        ->to($candidate->email)
                        ->cc('info@firstvite.com')  // Add CC email here
                        ->subject('Congratulations! You are selected');
                });
            } elseif ($result === 'Rejected') {
                // Send email to the candidate
                Mail::send('emails.rejected', ['candidate' => $candidate], function ($message) use ($candidate) {
                    $message
                        ->to($candidate->email)
                        ->cc('info@firstvite.com')  // Add CC email here
                        ->subject('Interview Result: Not Selected');
                });
            }

            return response()->json(['success' => 'Interview result updated successfully']);
        }

        return response()->json(['error' => 'Candidate not found'], 404);
    }

    // Show the login form
    public function joineeLoginForm($username)
    {
        return view('newJoin.login_form', compact('username'));
    }

    public function joineelogin(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $username = $request->input('username');
        $password = $request->input('password');

        // Find the candidate by username
        $candidate = NewEmployee::where('username', $username)->first();

        if ($candidate) {
            // Check if the plain text password matches
            if ($password === $candidate->password) {
                // Directly return the newJoin.dashboard view and pass the candidate data
                return view('newJoin.dashboard', ['candidate' => $candidate]);
            } else {
                // Password does not match
                return back()->withErrors(['error' => 'Password does not match.']);
            }
        }

        // Candidate not found
        return back()->withErrors(['error' => 'Username not found or incorrect credentials.']);
    }

    // Method for the new joinee to upload documents
    public function uploadDocumentsPage($username)
    {
        $candidate = NewEmployee::where('username', $username)->first();

        if (!$candidate || Carbon::now()->greaterThan($candidate->link_expiry)) {
            return view('errors.link_expired');  // Show link expired page
        }

        return view('newJoin.upload_documents', compact('candidate'));
    }

    /**
     * Resend document upload email to candidate
     *
     * @param int $id Candidate ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function resendDocumentEmail($id)
    {
        $candidate = NewEmployee::findOrFail($id);
        
        // Generate new credentials if not exists
        if (empty($candidate->username) || empty($candidate->password)) {
            $candidate->username = strtolower(str_replace(' ', '_', $candidate->name)) . rand(1000, 9999);
            $candidate->password = Str::random(8);
        }
        
        // Update link expiry (48 hours from now)
        $candidate->link_expiry = Carbon::now()->addHours(48);
        $candidate->save();

        // Send email with document upload link
        Mail::send('emails.selected', [
            'candidate' => $candidate,
            'username' => $candidate->username,
            'password' => $candidate->password
        ], function ($message) use ($candidate) {
            $message->to($candidate->email)
                  ->cc('info@firstvite.com')  // Add CC email here
                  ->subject('Document Upload Link - ' . config('app.name'));
        });

        return response()->json([
            'success' => true,
            'message' => 'Document upload email has been resent successfully.'
        ]);
    }

    public function showNewJoinPanel()
    {
        // Fetch candidates whose documents have been fully verified by HR
        $verifiedCandidates = Document::where('is_verified', 1)->get();

        return view('admin.new_join_panel', compact('verifiedCandidates'));
    }

    public function verifyAsSuperAdmin($id)
    {
        $candidate = Document::findOrFail($id);

        // Update verification status
        $candidate->is_superadmin_verified = true;  // Add this column to your `documents` table
        $candidate->save();

        // Generate agent login credentials
        $username = strtolower(str_replace(' ', '_', $candidate->name)) . rand(1000, 9999);
        $password = Str::random(8);  // Plain text password

        // Check if the employee already exists
        $existingEmployee = Employee::where('emp_email', $candidate->email)->first();
        if ($existingEmployee) {
            return redirect()->back()->with('error', 'This candidate is already verified as an employee.');
        }

        // Save the employee credentials to the employees table
        Employee::create([
            'emp_name' => $candidate->name,
            'emp_email' => $candidate->email,
            'emp_phone' => $candidate->phone,
            'emp_location' => $candidate->address,
            'emp_salary' => $candidate->salary_amount,
            'emp_username' => $candidate->username,
            'emp_password' => $candidate->password,
            'emp_job_role' => $candidate->designation === 'Agent' ? 2 : 0,
            'emp_join_date' => now()->format('Y-m-d'),
        ]);

        // Send an email to the new joinee with agent login credentials
        Mail::send('emails.agent_credentials', [
            'candidate' => $candidate,
            'username' => $username,
            'password' => $password
        ], function ($message) use ($candidate) {
            $message
                ->to($candidate->email)
                ->cc('info@firstvite.com')  // Add CC email here
                ->subject('Your Agent Login Credentials');
        });

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Candidate verified and email sent successfully.');
    }

    // Show the agent login form
    public function agentLoginForm()
    {
        return view('agent.login');
    }

    // Handle agent login
    public function agentlogin(Request $request)
    {
        try {
            Log::info('Agent login attempt started', ['ip' => $request->ip()]);

            $validated = $request->validate([
                'username' => 'required|string',
                'password' => 'required|string',
            ]);

            $username = $validated['username'];
            $password = $validated['password'];

            Log::info('Looking for agent', ['username' => $username]);

            // Find the agent by emp_username
            $agent = Agent::where('emp_username', $username)->first();

            if (!$agent) {
                Log::warning('Agent not found', ['username' => $username]);
                return back()->withErrors(['error' => 'Invalid username or password.'])->withInput();
            }

            Log::debug('Agent found', [
                'agent_id' => $agent->id,
                'stored_password' => $agent->emp_password,
                'input_password' => $password,
                'passwords_match' => $password === $agent->emp_password ? 'yes' : 'no'
            ]);

            // Check if the password matches
            if ($password === $agent->emp_password) {
                // Authenticate the agent using the 'agent' guard
                if (Auth::guard('agent')->loginUsingId($agent->id)) {
                    // Store user data in session
                    $request->session()->regenerate();
                    session([
                        'user_id' => $agent->id,
                        'emp_job_role' => $agent->emp_job_role,
                        'emp_name' => $agent->emp_name,
                        'logged_in' => true
                    ]);

                    Log::info('Login successful', [
                        'agent_id' => $agent->id,
                        'name' => $agent->emp_name,
                        'role' => $agent->emp_job_role
                    ]);

                    return redirect()->intended(route('home'));
                } else {
                    Log::error('Auth::loginUsingId failed', ['agent_id' => $agent->id]);
                    return back()->withErrors(['error' => 'Authentication failed. Please try again.']);
                }
            }

            Log::warning('Invalid password', ['agent_id' => $agent->id]);
            return back()->withErrors(['error' => 'Invalid username or password.'])->withInput();
        } catch (\Exception $e) {
            Log::error('Login error: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withErrors(['error' => 'An error occurred. Please try again.']);
        }
    }

    public function uploadEmployeeForm()
    {
        return view('newJoin.documents_upload');
    }

    public function store(Request $request)
    {
        // dd($request->all());

        //  $request->validate([
        //      'name' => 'required|string|max:255',
        //      'email' => 'required|email|max:255',
        //      'phone' => 'required|string|max:255',
        //      'location' => 'required|string|max:255',
        //      'salary_discussed' => 'required|string',
        //      'company_pan_number' => 'required|string',
        //      'company_pan_file' => 'required|file|mimes:jpeg,png,jpg,pdf',
        //      'personal_aadhar_number' => 'required|string',
        //      'personal_aadhar_file' => 'required|file|mimes:jpeg,png,jpg,pdf',
        //      'personal_pan_number' => 'required|string',
        //      'personal_pan_file' => 'required|file|mimes:jpeg,png,jpg,pdf',
        //  ]);

        // dd ($request->all());
        // Define the target directory
        $targetDirectory = public_path('uploads');

        // Ensure the directory exists
        if (!file_exists($targetDirectory)) {
            mkdir($targetDirectory, 0777, true);
        }

        // Handle file uploads
        $company_pan_file = $request->file('company_pan_file')->move($targetDirectory, $request->file('company_pan_file')->getClientOriginalName());
        $personal_aadhar_file = $request->file('personal_aadhar_file')->move($targetDirectory, $request->file('personal_aadhar_file')->getClientOriginalName());
        $personal_pan_file = $request->file('personal_pan_file')->move($targetDirectory, $request->file('personal_pan_file')->getClientOriginalName());

        // Initialize the additional_documents array
        $additional_documents = [];

        // Check if the request has additional documents and files
        if ($request->has('additional_document_name') && $request->hasFile('additional_document_file')) {
            foreach ($request->file('additional_document_file') as $key => $file) {
                // Store each file
                $storedFile = $file->move($targetDirectory, $file->getClientOriginalName());
                $additional_documents[] = [
                    'name' => $request->additional_document_name[$key],  // Name corresponding to the file
                    'file' => 'uploads/' . $file->getClientOriginalName()
                ];
            }
        }

        // Store the document data in the database
        Document::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'salary_discussed' => $request->salary_discussed == 'Yes',
            'salary_amount' => $request->salary_discussed == 'Yes' ? $request->salary_amount : null,
            'company_pan_number' => $request->company_pan_number,
            'company_pan_file' => 'uploads/' . $request->file('company_pan_file')->getClientOriginalName(),
            'personal_aadhar_number' => $request->personal_aadhar_number,
            'personal_aadhar_file' => 'uploads/' . $request->file('personal_aadhar_file')->getClientOriginalName(),
            'personal_pan_number' => $request->personal_pan_number,
            'personal_pan_file' => 'uploads/' . $request->file('personal_pan_file')->getClientOriginalName(),
            'additional_documents' => json_encode($additional_documents),  // Encode array as JSON
        ]);

        return redirect()->back()->with('success', 'Documents uploaded successfully.');
    }

    // Display the uploaded documents

    public function index()
    {
        $employees = Document::all();
        return view('HRMS.manage_employees', compact('employees'));
    }

    //   public function verifyDocument($id)
    //   {
    //       $employee = Document::find($id);

    //       if ($employee) {
    //           $employee->is_verified = true;
    //           $employee->save();

    //           return redirect()->route('hrms.manage_employees')->with('success', 'Documents verified successfully.');
    //        }

    //         return redirect()->route('hrms.manage_employees')->with('error', 'Employee not found.');
    //  }
    public function verifyDocument($id)
    {
        $employee = Document::findOrFail($id);
        // dd($employee);

        if (!$employee->is_verified) {
            $username = strtolower(str_replace(' ', '_', $employee->name)) . rand(1000, 9999);
            $password = Str::random(8);
            $employeeId = 'EMP' . rand(1000, 9999);  // Generate a unique employee ID
            $doj = Carbon::now();  // Capture current date as Date of Joining (DOJ)
            $designation = 'Agent';  // Default

            $employee->username = $username;
            $employee->password = $password;
            $employee->employee_id = $employeeId;
            $employee->doj = $doj;
            $employee->designation = $designation;
            $employee->is_verified = true;
            $employee->save();

            // $message = "Dear {$employee->name},\n\nYour documents have been successfully verified.\n\nHere are your login details:\nUsername: {$username}\nPassword: {$password}\n\nYou can log in here: https://crm.com/new-employee/login\n\nThank you!";

            // mail($employee->email, 'Your Account Credentials', $message);

            // Instead of sending an email, redirect to the employee dashboard
            session(['employeeLogin' => $employee]);  // Store employee info in session
            return back();

            // Instead of redirecting to a route, return the Blade view
            // return view('newJoin.dashboard', compact('employee'))->with('success', 'Documents verified successfully.');
        }
    }

    public function shownewLoginForm()
    {
        return view('newJoin.login');
    }

    public function newlogin(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // Fetch the employee from the Document model (as your model is named Document)
        $employee = Document::where('username', $request->username)->first();

        if ($employee && Hash::check($request->password, $employee->password)) {
            // Store session and redirect to the employee dashboard
            session(['employeeId' => $employee->id, 'employeeLogin' => $employee]);
            return redirect('employee/dashboard')->with('success', 'Login successful.');
        } else {
            return redirect('employee/login')->withErrors(['error' => 'Invalid login credentials.']);
        }
    }

    public function dashboard()
    {
        // Fetch the employee details from the session
        $employee = session('employeeLogin');

        if (!$employee) {
            return redirect()->route('employee.login')->withErrors(['error' => 'Please login first.']);
        }

        return view('newJoin.dashboard', compact('employee'));
    }

    public function downloadOfferLetter($id)
    {
        //  Fetch employee data
        $employee = Employee::findOrFail($id);

        // Path to the PDF template
        $templatePath = public_path('templates/offerletter.pdf');

        // Initialize FPDI
        $pdf = new FPDI();

        // Load the first page of the template
        $pageCount = $pdf->setSourceFile($templatePath);
        for ($i = 1; $i <= $pageCount; $i++) {
            $tplId = $pdf->importPage($i);
            $pdf->AddPage();
            $pdf->useTemplate($tplId, 10, 10, 200);

            // Add dynamic content to specific pages
            if ($i === 1) {
                $pdf->SetFont('Helvetica', '', 11);

                // Add date to the top
                $pdf->SetXY(43, 63.5);
                $pdf->Write(10, date('F j, Y'));

                // Replace [Candidate's Name]
                $pdf->SetXY(33, 75);
                $pdf->Write(10, $employee->emp_name);

                // Replace [Candidate's Address]
                $pdf->SetXY(32, 90);
                $pdf->Write(10, $employee->emp_location);

                // Replace Dear [Candidate's Name]
                $pdf->SetXY(44, 106.5);
                $pdf->Write(10, $employee->emp_name . ',');

                $pdf->SetXY(98, 142.5);
                $pdf->Write(10, $employee->emp_join_date);
            }
        }

        // Output the PDF to the browser
        $pdf->Output('I', 'Offer_Letter.pdf');
    }

    public function downloadExperienceLetter($id)
    {
        // Fetch employee data from the Document model using the provided ID
        $employee = Document::findOrFail($id);

        // Initialize FPDI
        $pdf = new FPDI();

        // Add the page of the experience letter
        $pdf->AddPage();
        $pdf->Image(public_path('templates/experience_letter.jpg'), 0, 0, 210, 297);  // Insert the image
        $this->addExperienceLetterText($pdf, $employee);  // Add dynamic text

        // Output the PDF
        return response($pdf->Output('S', 'Experience_Letter_' . $employee->name . '.pdf'), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="Experience_Letter_' . $employee->name . '.pdf"');
        // Render the Blade view with the background images for debugging
        //  return view('pdf.experience_letter_debug', compact('employee'));
    }

    private function addExperienceLetterText($pdf, $employee)
    {
        // Set font and size for the dynamic text
        $pdf->SetFont('Helvetica');
        $pdf->SetFontSize(12);

        // Date (Dynamic)
        $pdf->SetXY(22, 65);  // Left: 22mm, Top: 65mm
        $pdf->Write(0, date('d-m-Y'));

        // Employee Name (Dynamic)
        $pdf->SetXY(18, 73);  // Left: 18mm, Top: 73mm
        $pdf->Write(0, $employee->name);

        // Employee ID (Dynamic)
        $pdf->SetXY(34, 81);  // Left: 34mm, Top: 81mm
        $pdf->Write(0, $employee->employee_id);

        // Designation (Dynamic)
        $pdf->SetXY(37, 115);  // Left: 37mm, Top: 115mm
        $pdf->Write(0, $employee->designation);

        // Date of Joining (Dynamic)
        $pdf->SetXY(43, 124);  // Left: 43mm, Top: 124mm
        $pdf->Write(0, $employee->doj);

        // Dynamic Date of Leaving
        $pdf->SetXY(45, 132);  // Left: 45mm, Top: 132mm
        $pdf->Write(0, date('d-m-Y'));  // Adjust to actual date logic if needed

        // Annual CTC (Dynamic)
        $pdf->SetXY(38, 140);  // Left: 38mm, Top: 140mm
        $pdf->Write(0, '240000 per annum');

        // Static Date of Leaving
        $pdf->SetXY(76, 94);  // Left: 76mm, Top: 94mm
        $pdf->Write(0, '31-12-2023');  // Static date, change if necess
        // Continue adding any other dynamic text as necessary...
    }

    public function edit($id)
    {
        $candidate = Document::findOrFail($id);  // Fetch the candidate
        return view('newJoin.edit', compact('candidate'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:15',
        ]);

        $candidate = Document::findOrFail($id);
        $candidate->update($validatedData);

        return redirect('/admin/new-join-panel')->with('success', 'Candidate details updated successfully.');
    }

    public function destroy($id)
    {
        $candidate = Document::findOrFail($id);
        $candidate->delete();

        return redirect('/admin/new-join-panel')->with('success', 'Candidate deleted successfully.');
    }

    /**
     * Download ID Card for an employee
     *
     * @param int $id Employee ID
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function downloadIDCard($id)
    {
        try {
            // Check if user is authenticated
            if (!auth()->check()) {
                return redirect()->route('login')->with('error', 'Please login to access this page.');
            }

            // Find the employee
            $employee = Employee::findOrFail($id);

            // Prepare the data for the view
            $data = [
                'employee' => (object) [
                    'name' => $employee->emp_name,
                    'id' => $employee->id,
                    'designation' => $employee->emp_job_role_name ?? 'Employee',
                    'doj' => $employee->emp_join_date ?? 'N/A',
                ]
            ];

            // Load the view and generate PDF
            $pdf = PDF::loadView('documents.id_card', $data);

            // Set paper size and orientation
            $pdf->setPaper([0, 0, 85.6, 54], 'portrait');  // Standard ID card size in mm (3.37" x 2.125")

            // Download the PDF with a custom filename
            return $pdf->download('ID_Card_' . $employee->emp_name . '.pdf');
        } catch (\Exception $e) {
            Log::error('Error generating ID card: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error generating ID card. Please try again.');
        }
    }

    /**
     * Update candidate's email
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateCandidateEmail(Request $request)
    {
        try {
            // Log the incoming request data
            Log::info('Update candidate email request:', $request->all());
            
            // Validate the request
            $validated = $request->validate([
                'candidate_id' => 'required|exists:new_joinee,id',
                'email' => 'required|email|unique:new_joinee,email,' . $request->candidate_id,
            ]);
            
            Log::info('Validation passed', $validated);

            // Find the candidate
            $candidate = NewEmployee::find($validated['candidate_id']);
            
            if (!$candidate) {
                throw new \Exception('Candidate not found with ID: ' . $validated['candidate_id']);
            }
            
            $oldEmail = $candidate->email;
            $candidate->email = $validated['email'];
            
            // Save the changes
            if ($candidate->save()) {
                // Log the successful update
                Log::info('Candidate email updated', [
                    'candidate_id' => $candidate->id,
                    'old_email' => $oldEmail,
                    'new_email' => $candidate->email,
                    'updated_by' => 'public_access',
                    'ip_address' => $request->ip()
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Email updated successfully',
                    'new_email' => $candidate->email
                ]);
            } else {
                throw new \Exception('Failed to save candidate record');
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->errors();
            Log::error('Validation error updating candidate email:', $errors);
            
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $errors
            ], 422);
            
        } catch (\Exception $e) {
            $errorMessage = 'Error updating candidate email: ' . $e->getMessage();
            Log::error($errorMessage);
            Log::error($e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => $errorMessage,
                'exception' => get_class($e)
            ], 500);
        }
    }
}

//     // Prepare the email data
//     $data = [
//         'name' => $newEmployee->name,
//         'url' => $url,
//     ];

//     // Send the email using Laravel Mail
//     Mail::send('mail.welcome_new_joiner', $data, function ($message) use ($newEmployee) {
//         $message->to($newEmployee->email)
//                 ->subject('Welcome to Our Company');
//     });

//     return redirect()->back()->with('success', 'Welcome email with URL link has been sent to the candidate.');

// }
