<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Registration;
use App\Models\personal\Lead;
use App\Models\Deal;
use App\Models\Activity;
use App\Models\JobRole;
use App\Models\Employee;
use App\Models\AdminPage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function login(){

        return view('login');

    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'emp_username' => 'required|string|max:50',
            'emp_password' => 'required|string|min:3',
        ]);

        // Find the employee by username
        $employee = Employee::where('emp_username', $validatedData['emp_username'])->first();


        // $userId = $employee->id ;
        // dd($userId);

        if (!$employee || $employee->emp_password !== $validatedData['emp_password']) {
            // Pass error message to the session
           return back()->with('error', 'Invalid credentials. Please enter the correct username and password.');
        }
        session(['user_id' => $employee->id,]);

        // dd(session('user_id')  );
        // Redirect to the dashboard
        return redirect()->route('home');

    }

    public function myAccount()
    {
        // Retrieve the user ID from the session
    $userId = session('user_id');

    if (!$userId) {
        return redirect()->route('login')->with('error', 'Please log in.');
    }

    // Find the employee by the user ID
    $employee = Employee::find($userId);

    if (!$employee) {
        return redirect()->route('login')->with('error', 'User not found.');
    }

    // Pass the employee data to the view
    return view('admin.my-account', compact('employee'));

    }


    // public function changePassword(Request $request)
    // {
    //     $validatedData = $request->validate([
    //         'current_password' => 'required|string',
    //         'new_password' => [
    //             'required',
    //             'string',
    //             'min:8',
    //             'max:15',
    //             'regex:/[A-Z]/', // At least one uppercase letter
    //             'regex:/[a-z]/', // At least one lowercase letter
    //             'regex:/[0-9]/', // At least one number
    //             'regex:/[@$!%*?&]/', // At least one special character
    //         ],
    //         'confirm_password' => 'required|string|same:new_password',
    //     ]);
    
    //      // Retrieve the user ID from the session
    // $userId = session('user_id');

    // // dd($userId);

    // if (!$userId) {
    //     return redirect()->route('login')->with('error', 'Please log in.');
    // }

    // // Find the employee by the user ID
    // $employee = Employee::find($userId);

    // // dd($employee);

    // if (!$employee) {
    //     return back()->with('error', 'User not found.');
    // }

    // // Verify the current password (plain text comparison)
    // if ($validatedData['current_password'] !== $employee->emp_password) {
    //     return back()->with('error', 'Current password is incorrect.');
    // }

    // // Update the password (plain text)
    // $employee->emp_password = $validatedData['new_password'];
    // $employee->save();

    // return back()->with('success', 'Password changed successfully.');

    // }

    public function logout(Request $request)
    {
            // Clear all session data
            Session::flush();

            // Clear all cache (optional)
            Cache::flush();
            return redirect('/')->with('success', 'Logged out successfully.');
    }

      public function addlead()
         {
            return view('leads.add_lead');
         }

         public function add_lead(Request $request)
         {
            //  $request->validate([
            //      'first_name' => 'required|string|max:255',
            //      'title' => 'nullable|string|max:255',
            //      'last_name' => 'required|string|max:255',
            //      'email' => 'required|email|unique:leads,email',
            //      'phone' => 'required|string|max:20',
            //      'company' => 'required|string|max:255',
            //      'lead_source' => 'nullable|string|max:255',
            //      'lead_status' => 'nullable|string|in:new,contacted,qualified,lost,closed',
            //      'street' => 'nullable|string|max:255',
            //      'state' => 'nullable|string|max:255',
            //      'country' => 'nullable|string|max:255',
            //      'city' => 'nullable|string|max:255',
            //      'zip_code' => 'nullable|string|max:20',
            //      'description' => 'nullable|string',
            //  ]);

            // dd($request->all());
     
             $lead = new Lead();
             $lead->first_name = $request->first_name;
             $lead->title = $request->title;
             $lead->last_name = $request->last_name;
             $lead->email = $request->email;
             $lead->phone = $request->phone;
             $lead->company = $request->company;
             $lead->lead_source = $request->lead_source;
             $lead->lead_status = $request->lead_status;
             $lead->university = $request->university;
             $lead->courses = $request->courses;
             $lead->street = $request->street;
             $lead->state = $request->state;
             $lead->country = $request->country;
             $lead->city = $request->city;
             $lead->zip_code = $request->zip_code;
             $lead->description = $request->description; 

            //  dd($lead);

             // Calculate lead score
             $lead->lead_score = $this->calculateLeadScore($lead);

             $lead->save();

              // Prepare email subject and body
             $subject = " {$lead->courses} Mapping Your Path to Career Versatility";
             $emailBody = "
                Dear {$lead->first_name} {$lead->last_name},<br><br>
                With the increasing demand for skilled professionals across industries, 
                {$lead->university}'s {$lead->courses} degree opens doors to exciting career prospects, 
                master’s degrees, and positions graduates at the forefront of innovation in technology.<br><br>
                Regards,<br>
                Admissions Team<br>
                {$lead->university}
    "          ;
               
                // Send email using the Mail facade
                  Mail::send([], [], function ($message) use ($lead, $subject, $emailBody) {
                    $message->to($lead->email)
                            ->subject($subject)
                            ->html($emailBody);
                  });

                 session()->flash('success', 'Lead added successfully');
                 return redirect()->back();
           }


      public function viewLead($id)
      {
        $lead = Lead::findOrFail($id);
        return view('leads.view_lead', compact('lead'));
      }

     public function show_leads() {

            $leads = Lead::all(); // Fetch all leads from the database
            return view('leads.show_lead', compact('leads'));
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
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:15',
            'company' => 'required|string|max:255',
        ]);

        $lead = Lead::findOrFail($id);
        $lead->update($request->all());

         // Recalculate lead score after update
         $lead->lead_score = $this->calculateLeadScore($lead);

        return redirect('/admin/show-leads')->with('success', 'Lead updated successfully!');
    }

private function calculateLeadScore($lead)
{
    $score = 0;
    
    // Adjust this logic according to your requirements
    if ($lead->lead_status == 'new') {
        $score += 10;
    } elseif ($lead->lead_status == 'contacted') {
        $score += 20;
    } elseif ($lead->lead_status == 'qualified') {
        $score += 30;
    } elseif ($lead->lead_status == 'lost') {
        $score -= 10;
    } elseif ($lead->lead_status == 'closed') {
        $score += 50;
    }

    if ($lead->lead_source == 'Website') {
        $score += 15;
    } elseif ($lead->lead_source == 'Referral') {
        $score += 20;
    } elseif ($lead->lead_source == 'Social Media') {
        $score += 10;
    } elseif ($lead->lead_source == 'Advertisement') {
        $score += 5;
    }

    // You can add more conditions based on other fields like company, phone, etc.
    return $score;
}

    // Method to delete a lead
    public function destroy($id)
    {
        $lead = Lead::findOrFail($id);
        $lead->delete();

        return redirect('/admin/show-leads')->with('success', 'Lead deleted successfully!');
    }

 public function showConvertLeadForm($id)
   {

    $lead = Lead::findOrFail($id);
    return view('leads.convert_lead', compact('lead'));

  }

  public function dealstore(Request $request)
  {
      $validatedData = $request->validate([
          'deal_name' => 'required|string|max:255',
          'amount' => 'nullable|numeric',
          'stage' => 'required|string|max:255',
          'contact_phone' => 'nullable|string|max:20',
          'contact_email' => 'nullable|email|max:255',
          'lead_source' => 'nullable|string|max:255',
          'referral_name' => 'nullable|string|max:255',
          'priority' => 'nullable|string|max:255',
          'estimated_close_date' => 'nullable|date',
          'lead_status' => 'required|string|max:255',
          'notes' => 'nullable|string',
          'attachments' => 'nullable|array',
          'attachments.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx', // Specify allowed file types
          'follow_up' => 'nullable|date|after:now',  // Validation rule for follow-up
      ]);

       // Handle file uploads
       $attachments = [];
       if ($request->hasFile('attachments')) {
           foreach ($request->file('attachments') as $file) {
               $path = $file->store('attachments', 'public');
               $attachments[] = $path;
           }
       }

      $deal = Deal::create([
          'deal_name' => $validatedData['deal_name'],
          'amount' => $validatedData['amount'],
          'stage' => $validatedData['stage'],
          'contact_phone' => $validatedData['contact_phone'],
          'contact_email' => $validatedData['contact_email'],
          'lead_source' => $validatedData['lead_source'],
          'referral_name' => $validatedData['referral_name'],
          'priority' => $validatedData['priority'],
          'estimated_close_date' => $validatedData['estimated_close_date'],
          'lead_status' => $validatedData['lead_status'],
          'notes' => $validatedData['notes'],
          'attachments' => $attachments,  // Save attachments as JSON
          'follow_up' => $validatedData['follow_up'],  // Save the follow-up date and time
      ]);

      return back()->with('success', 'Lead converted to deal successfully!');
  }

  public function viewdeals()
    {
        // Fetch deals with related lead information
        $deals = Deal::all();

        return view('deals.showdeal', compact('deals'));

    }

     // Show the edit deal form
     public function editDeal($id)
     {
         $deal = Deal::findOrFail($id);
         return view('deals.editdeal', compact('deal'));
     }
 
     // Update the deal
     public function updateDeal(Request $request, $id)
     {
         $deal = Deal::findOrFail($id);
 
         $validatedData = $request->validate([
             'deal_name' => 'required|string|max:255',
             'amount' => 'required|numeric',
             'stage' => 'required|string',
             'contact_phone' => 'required|string|max:15',
             'priority' => 'required|string',
             'estimated_close_date' => 'required|date',
             'contact_email' => 'required|email|max:255',
         ]);
 
         $deal->update($validatedData);
 
         return redirect('/admin/deals')->with('success', 'Deal updated successfully');
     }
 
     // Delete the deal
     public function destroyDeal($id)
     {
         $deal = Deal::findOrFail($id);
         $deal->delete();
 
         return redirect('/admin/deals')->with('success', 'Deal deleted successfully');
     }

    
// Import Leads Functionality

public function importLeads(Request $request)
{
    // Validate the uploaded file
    $request->validate([
        'leads_file' => 'required|mimes:csv,txt|max:2048',
    ]);

    // Retrieve the agent ID from the session
    $agentId = 1;

    if (!$agentId) {
        return redirect()->route('login')->with('error', 'You must be logged in to import leads.');
    }

    // Load the CSV file
    $file = $request->file('leads_file');
    $filePath = $file->getRealPath();
    $fileHandle = fopen($filePath, 'r');
    $headers = fgetcsv($fileHandle); // Read headers

    // Required headers (match your database fields)
    $requiredHeaders = [
        'first_name', 'last_name', 'email', 'email_domain', 'secondary_email',
        'secondary_email_domain', 'phone', 'secondary_phone', 'lead_source',
        'university', 'courses', 'college', 'branch', 'session_duration'
    ];

    // Ensure headers in the file match database fields
    foreach ($requiredHeaders as $header) {
        if (!in_array($header, $headers)) {
            return redirect()->back()->with('error', 'Invalid CSV format. Required columns: ' . implode(', ', $requiredHeaders));
        }
    }

    // Process the CSV rows
    while ($row = fgetcsv($fileHandle)) {
        $data = array_combine($headers, $row);

        // Validate data for each row
        $validator = Validator::make($data, [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:leads,email',
            'email_domain' => 'nullable|string|max:255',
            'secondary_email' => 'nullable|email',
            'secondary_email_domain' => 'nullable|string|max:255',
            'phone' => 'required|numeric',
            'secondary_phone' => 'nullable|numeric',
            'lead_source' => 'required|string|max:255',
            'university' => 'nullable|string|max:255',
            'courses' => 'nullable|string|max:255',
            'college' => 'nullable|string|max:255',
            'branch' => 'nullable|string|max:255',
            'session_duration' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            continue; // Skip invalid rows
        }

        // Save to the database
        Lead::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'email_domain' => $data['email_domain'] ?? null,
            'secondary_email' => $data['secondary_email'] ?? null,
            'secondary_email_domain' => $data['secondary_email_domain'] ?? null,
            'phone' => $data['phone'],
            'secondary_phone' => $data['secondary_phone'] ?? null,
            'lead_source' => $data['lead_source'],
            'university' => $data['university'] ?? null,
            'courses' => $data['courses'] ?? null,
            'college' => $data['college'] ?? null,
            'branch' => $data['branch'] ?? null,
            'session_duration' => $data['session_duration'] ?? null,
            'agent_id' => $agentId,
        ]);
    }

    fclose($fileHandle);

    return redirect()->route('leads.index')->with('success', 'Leads imported successfully.');
}


// Export Leads Functionality

public function exportLeads()
    {

    // Retrieve the logged-in agent's ID from the session
    $agentId = session('user_id');

    if (!$agentId) {
        return redirect()->route('login')->with('error', 'You must be logged in to export leads.');
    }

    // Fetch leads related to the logged-in agent
    $leads = Lead::where('agent_id', $agentId)->get();

        // Define the CSV filename
        $fileName = 'leads_export.csv';

        // Define the CSV headers
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        // Define the callback function to generate the CSV data
        $callback = function() use ($leads) {
            $file = fopen('php://output', 'w');
            
        // Add the CSV column headers (include all necessary fields)
        fputcsv($file, [

            'agent_id','first_name', 'last_name', 'email', 'email_domain', 'secondary_email',
            'secondary_email_domain', 'phone', 'secondary_phone', 'lead_source',
            'university', 'courses', 'college', 'branch', 'session_duration'
        ]);

            // Add each lead to the CSV file
            foreach ($leads as $lead) {
                fputcsv($file, [
                    $lead->agent_id,
                    $lead->first_name,
                    $lead->last_name,
                    $lead->email,
                    $lead->email_domain,
                    $lead->secondary_email,
                    $lead->secondary_email_domain,
                    $lead->phone,
                    $lead->secondary_phone,
                    $lead->lead_source,
                    $lead->university,
                    $lead->courses,
                    $lead->college,
                    $lead->branch,
                    $lead->session_duration,
                ]);
            }

            fclose($file);
        };

        // Return the CSV file as a downloadable response
        return Response::stream($callback, 200, $headers);
    }

    // Filter Section Functionality

    public function index(Request $request)
    {

          // Retrieve user_id and emp_job_role from the session
              $userId = session()->get('user_id');
              $userRole = session()->get('emp_job_role');

              $query = Lead::query();

        // If the user is an agent (role = 2), only show leads that the agent has added
       if ($userRole == 2) {
           $query->where('agent_id', $userId); // Filter leads by the current agent
        }


        // Handle Search Leads
        if ($request->has('search') && !empty($request->search)) {
            $query->where(function ($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->search . '%')
                    ->orWhere('last_name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%')
                    ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }

        // Handle Lead Source Filter
        if ($request->has('lead_source') && $request->lead_source != 'All') {
            $query->where('lead_source', $request->lead_source);
        }

        // Handle Date Range Filter
        if ($request->has('date_range') && $request->date_range != 'All') {
            if ($request->date_range == 'Last Activity') {
                $query->orderBy('updated_at', 'desc');
            } elseif ($request->date_range == 'Created On') {
                $query->orderBy('created_at', 'desc');
            } elseif ($request->date_range == 'Modified On') {
                $query->where('updated_at', '>=', now()->subDays(30)); // Example: Last 30 days
            }
        }

        // Handle Time Frame Filter
        if ($request->has('time_frame') && $request->time_frame != 'All Time') {
            if ($request->time_frame == 'Yesterday') {
                $query->whereDate('created_at', '=', now()->subDay()->toDateString());
            } elseif ($request->time_frame == 'Today') {
                $query->whereDate('created_at', '=', now()->toDateString());
            } elseif ($request->time_frame == 'Last Week') {
                $query->whereDate('created_at', '>=', now()->subWeek());
            } elseif ($request->time_frame == 'This Week') {
                $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
            } elseif ($request->time_frame == 'Last Month') {
                $query->whereMonth('created_at', '=', now()->subMonth()->month);
            } elseif ($request->time_frame == 'This Month') {
                $query->whereMonth('created_at', '=', now()->month);
            } elseif ($request->time_frame == 'Last Year') {
                $query->whereYear('created_at', '=', now()->subYear()->year);
            }
        }

        // Fetch the leads based on the filters applied
        $leads = $query->paginate(50);

        // Pass leads and the current request filters to the view
        return view('personal.show_lead', compact('leads'));
    }


    // Manage Activity Functionality

    public function create(){

        return view ('leads.add_activity');

    }

    public function indexactivity(Request $request)
    {

         // Get user role and ID from session
    $userId = session()->get('user_id');
    $userRole = session()->get('emp_job_role'); //

     $query = Activity::query();


    // Apply filters based on user role
    if ($userRole == 2) { // If user is an agent
        $query->where('agent_id', $userId); // Only show activities created by the logged-in agent
    }
    
        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
    
        if ($request->has('type') && $request->type != 'all') {
            $query->where('type', $request->type);
        }
    
        if ($request->has('date_filter')) {
            switch ($request->date_filter) {
                case 'yesterday':
                    $query->whereDate('schedule_from', Carbon::yesterday());
                    break;
                case 'today':
                    $query->whereDate('schedule_from', Carbon::today());
                    break;
                case 'tomorrow':
                    $query->whereDate('schedule_from', Carbon::tomorrow());
                    break;
                case 'this_week':
                    $query->whereBetween('schedule_from', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                    break;
                case 'this_month':
                    $query->whereMonth('schedule_from', Carbon::now()->month);
                    break;
                case 'custom':
                    // You can add logic for custom date ranges
                    break;
            }
        }
    
        $activities = $query->get();
    
        return view('leads.manage_activities', compact('activities'));
    }
    
    public function storeactivity(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string',
            'description' => 'nullable|string',
            'schedule_from' => 'required|date',
            'schedule_to' => 'required|date',
            // 'lead_id' => 'required|exists:leads,id',
        ]);

        $activity = new Activity();
        $activity->title = $validatedData['title'];
        $activity->type = $validatedData['type'];
        $activity->description = $validatedData['description'];
        $activity->schedule_from = $validatedData['schedule_from'];
        $activity->schedule_to = $validatedData['schedule_to'];

        $activity->agent_id = session()->get('user_id');
        $activity->is_done = false;
        $activity->save();

        return redirect()->route('activities.index')->with('success', 'Activity added successfully.');
    }

    public function editactivity($id)
    {
        $activity = Activity::findOrFail($id);
        // $leads = Lead::all();
        return view('leads.edit_activity', compact('activity'));
    }

    public function updateactivity(Request $request, $id)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string',
            'description' => 'nullable|string',
            'schedule_from' => 'required|date',
            'schedule_to' => 'required|date',
            // 'lead_id' => 'required|exists:leads,id',
        ]);

        $activity = Activity::findOrFail($id);

        $validatedData['agent_id'] = session()->get('user_id');
        
        $activity->update($validatedData);
    
        return redirect()->route('activities.index')->with('success', 'Activity updated successfully.');
    }

    public function destroyactivity($id)
    {
        $activity = Activity::findOrFail($id);

        $activity->delete();

        return redirect()->route('activities.index')->with('success', 'Activity deleted successfully.');
    }

}
