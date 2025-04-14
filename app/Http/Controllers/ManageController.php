<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lead;
use App\Models\Task;
use App\Models\Employee;
use App\Models\PasswordReset;
use App\Models\ManageList;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\MessageBag;



class ManageController extends Controller
{
    public function create()
    {
        return view('leads.createtask');
    }


    public function store(Request $request)
    {
        $validatedData =  $request->validate([
            'subject' => 'required|string|max:255',
            'task_type' => 'required|string|max:255',
            'task_status' => 'required|string|max:255',
            'schedule_from' => 'required|date',
            'schedule_to' => 'required|date',
            'description' => 'nullable|string',
        ]);

        $task = new Task();

        $task->subject = $validatedData['subject'];
        $task->task_type = $validatedData['task_type'];
        $task->task_status = $validatedData['task_status'];
        $task->description = $validatedData['description'];
        $task->schedule_from = $validatedData['schedule_from'];
        $task->schedule_to = $validatedData['schedule_to'];

        $task->agent_id = session()->get('user_id');

        $task->save();

        return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
    }

   // Display the list of tasks
   public function index(Request $request)
   {
       // Initialize the query builder
       $query = Task::query();

        // Role-based filtering: agents see only their tasks
    if (session()->get('emp_job_role') == 2) {
        $query->where('agent_id', session()->get('user_id'));
    }

       // Apply filters based on request inputs
       if ($request->has('search')) {
           $query->where('subject', 'like', '%' . $request->search . '%');
       }

       if ($request->has('task_type') && $request->task_type !== 'all') {
           $query->where('task_type', $request->task_type);
       }

       if ($request->has('task_status') && $request->task_status !== 'all') {
           $query->where('task_status', $request->task_status);
       }

       if ($request->has('task_owner')) {
           $query->where('task_owner', 'like', '%' . $request->task_owner . '%');
       }

    //    if ($request->has('due_date') && $request->due_date !== 'all') {
    //        $dueDate = $this->getDueDateFilter($request->due_date);
    //        $query->whereBetween('schedule_from', [$dueDate['from'], $dueDate['to']]);
    //    }

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


       // Get paginated results
       $tasks = $query->paginate(10);

       // Return the view with tasks data
       return view('leads.manage_task', compact('tasks'));
   }

    // Show the form to edit an existing task
    public function edit($id)
    {
        $query = Task::where('id', $id);

    // Agents can only edit their own tasks
    if (session()->get('emp_job_role') == 2) {
        $query->where('agent_id', session()->get('user_id'));
    }

    $task = $query->firstOrFail();

    return view('leads.edit_task', compact('task'));

    }

    // Update an existing task
    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        
        $validatedData = $request->validate([
            'subject' => 'required|string|max:255',
            'task_type' => 'required|string',
            'task_status' => 'required|string',
            'task_owner' => 'required|string|max:255',
            'schedule_from' => 'required|date',
            'schedule_to' => 'required|date',
            'description' => 'nullable|string',
        ]);

        $query = Task::where('id', $id);

        // Agents can only update their own tasks
        if (session()->get('emp_job_role') == 2) {
            $query->where('agent_id', session()->get('user_id'));
        }
    
        $task = $query->firstOrFail();
    
        // Update task with validated data
        $task->update(array_merge($request->all(), [
            'agent_id' => session()->get('user_id'), // Ensure the updated task still belongs to the agent
        ]));

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
    }

    // Delete a task
    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
    }

   
    // Manage Lists Functionality


    public function createlist() {
        return view('leads.manage_lists_create');
    }

    public function storelist(Request $request) {
        $request->validate([
            'list_name' => 'required|string|max:255',
            'list_type' => 'required|in:Static,Refreshable,All,Dynamic',
            'description' => 'nullable|string|max:1000', // New validation rule for description
        ]);
    
        ManageList::create([
            'list_name' => $request->list_name,
            'list_type' => $request->list_type,
            'description' => $request->description, // Save the description
            // 'created_by' => auth()->id(),
        ]);
    
        return redirect()->route('lists.index')->with('success', 'List created successfully!');
    }
    
    public function indexlist() {

        $lists = ManageList::all();
        
        return view('leads.manage_list', compact('lists'));
    }

    // Forgot password functionality

    public function showResetRequestForm()
    {
        return view('auth.passwords.email');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);
    
        // Check if the email exists in the employees table
        $employee = Employee::where('emp_email', $request->email)->first();
    
        if (!$employee) {
            return back()->with('error', 'No account found with this email.');
        }
    
        // Generate a token and save it with a timestamp
        $token = Str::random(60);
        PasswordReset::updateOrCreate(
            ['email' => $employee->emp_email],
            ['token' => $token, 'created_at' => now()]
        );
    
        // Send reset link to the superadmin
        Mail::send('emails.password_reset', [
            'token' => $token,
            'emp_name' => $employee->emp_name,
            'emp_email' => $employee->emp_email,

        ], function ($message) use($employee) {

            $message->to('anand24h@gmail.com');
            $message->subject($employee->emp_name . ' Password Reset Request');

        });
    
        return back()->with('success', 'Password reset link has been sent');
    }
    

    
    public function showResetForm($token)
{
    $passwordReset = PasswordReset::where('token', $token)->first();
    $errors = new MessageBag([    ]);

    if (!$passwordReset || !$passwordReset->isValid()) {
        return redirect()->route('login')->with('error', 'This reset link is invalid or expired.');
    }

    return view('auth.passwords.reset', ['token' => $token, 'email' => $passwordReset->email, 'errors' => $errors,
]);
}

public function resetPassword(Request $request)
{
    
    // Define custom error messages
    $messages = [
        'password.required' => 'Password is required.',
        'password.min' => 'Please use at least 8 characters for the password.',
        'password.confirmed' => 'Passwords do not match.',
    ];

    // Validate the request
    $validator = Validator::make($request->all(), [
        'token' => 'required',
        'password' => 'required|string|min:8|confirmed',
    ], $messages);

    // If validation fails, return with errors
    if ($validator->fails()) {
        return back()->withErrors($validator)->withInput();
    }

    // Find the password reset token
    $passwordReset = PasswordReset::where('token', $request->token)->first();

    if (!$passwordReset || !$passwordReset->isValid()) {
        return back()->with('error', 'This reset link is invalid or expired.');
    }

    // Update the password for the employee
    $employee = Employee::where('emp_email', $passwordReset->email)->first();

    if (!$employee) {
        return back()->with('error', 'User not found.');
    }

    // Save the new password
    $employee->emp_password = $request->password;
    $employee->save();

    // Delete the token after successful reset
    $passwordReset->delete();

    return redirect()->route('login')->with('success', 'Password successfully updated.');
}

public function showAllEmployees()
{
    // Ensure only superadmin can access this page
    if (session('emp_job_role') !== 1) {
        return redirect()->back()->with('error', 'Unauthorized access.');
    }

    // Fetch all employees
    $employees = Employee::all();

    return view('admin.all_login_access', compact('employees'));
}

 // Handle password change for an employee
 public function changeEmployeePassword(Request $request)
 {
     // Define validation rules and messages
     $rules = [
        'employee_id' => 'required|exists:employees,id',
        'new_password' => [
            'required',
            'string',
            'min:8',
            'max:15',
            'regex:/[A-Z]/',     // Must contain at least one uppercase letter
            'regex:/[a-z]/',     // Must contain at least one lowercase letter
            'regex:/[0-9]/',     // Must contain at least one digit
            'regex:/[@$!%*?&]/', // Must contain at least one special character
            'confirmed',         // Must match the confirmation field
        ],
    ];

    $messages = [
        'employee_id.required' => 'Employee ID is required.',
        'employee_id.exists' => 'The selected employee does not exist.',
        'new_password.required' => 'The new password is required.',
        'new_password.min' => 'The new password must be at least 8 characters.',
        'new_password.max' => 'The new password must not exceed 15 characters.',
        'new_password.regex' => 'The new password must include at least one uppercase letter, one lowercase letter, one number, and one special character.',
        'new_password.confirmed' => 'The new password confirmation does not match.',
    ];

    // Validate the input
    $validator = Validator::make($request->all(), $rules, $messages);

    // If validation fails, redirect back with errors
    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

     // Ensure only superadmin can perform this action
     if (session('emp_job_role') !== 1) {
         return redirect()->back()->with('error', 'Unauthorized access.');
     }

     // Find the employee and update the password
     $employee = Employee::findOrFail($request->employee_id);
     $employee->emp_password = $request->new_password;
     $employee->save();

     return redirect()->back()->with('success', 'Password successfully changed.');
 }


}
