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
use Illuminate\Support\Facades\Log;
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

    /**
     * Display all employees' login access
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showAllEmployees(Request $request)
    {
        // Ensure only superadmin or childadmin can access this page
        if (!in_array(session('emp_job_role'), [1, 8])) {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }

        // Log the request parameters for debugging
        Log::info('showAllEmployees request:', [
            'role' => $request->role,
            'status' => $request->status,
            'all_params' => $request->all()
        ]);

        // Start building the query
        $query = Employee::query();

        // Apply role filter if provided
        if ($request->filled('role')) {
            $query->where('emp_job_role', $request->role);
        }

        // Apply status filter if provided
        if ($request->filled('status') && $request->status !== '') {
            $status = (int)$request->status;
            $query->where('is_active', $status);
        }

        // Log the generated SQL query
        Log::info('Generated SQL Query:', [
            'sql' => $query->toSql(),
            'bindings' => $query->getBindings()
        ]);

        // Fetch employees with role and status filtering and ensure is_active is set
        $employees = $query->get()->map(function($employee) {
            // Ensure is_active is set (for existing records before migration)
            if ($employee->is_active === null) {
                $employee->is_active = 1;
                $employee->save();
            }
            return $employee;
        });

        // For debugging
        if ($request->has('debug')) {
            return response()->json([
                'employees' => $employees,
                'sql' => $query->toSql(),
                'bindings' => $query->getBindings()
            ]);
        }

        // Return the view with the employees data
        return view('admin.all_login_access', [
            'employees' => $employees,
            'request' => $request,
        ]);
    }

/**
 * Update username for an employee
 * 
 * @param Request $request
 * @param int $id
 * @return \Illuminate\Http\JsonResponse
 */
public function updateUsername(Request $request, $id)
{
    $request->validate([
        'username' => 'required|string|max:255|unique:employees,emp_username,' . $id
    ]);

    $employee = Employee::findOrFail($id);
    
    try {
        $employee->update([
            'emp_username' => $request->username,
            'updated_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Username updated successfully',
            'username' => $employee->emp_username
        ]);

    } catch (\Exception $e) {
        Log::error('Error updating username: ' . $e->getMessage(), [
            'employee_id' => $id,
            'username' => $request->username,
            'exception' => $e
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Failed to update username. Please try again.'
        ], 500);
    }
}

/**
 * Toggle login access for an employee
 * 
 * @param Request $request
 * @param int $id
 * @return \Illuminate\Http\JsonResponse
 */
public function toggleLoginAccess(Request $request, $id)
{
    Log::info('Toggle login access request received', [
        'employee_id' => $id,
        'ip' => $request->ip(),
        'user_agent' => $request->userAgent()
    ]);

    try {
        // For now, we'll allow any request to toggle the status
        // In production, you might want to add some other form of verification
        // like an API token or IP whitelisting

        $employee = Employee::find($id);
        
        if (!$employee) {
            $message = 'Employee not found with ID: ' . $id;
            Log::error($message);
            return response()->json([
                'success' => false,
                'message' => $message
            ], 404);
        }

        // Store old status for logging
        $oldStatus = (bool)$employee->is_active;
        
        // Toggle the is_active status
        $employee->is_active = !$oldStatus;
        
        if (!$employee->save()) {
            $message = 'Failed to save employee record';
            Log::error($message, ['employee_id' => $employee->id]);
            return response()->json([
                'success' => false,
                'message' => $message
            ], 500);
        }

        // Log successful update
        Log::info('Login access toggled successfully', [
            'employee_id' => $employee->id,
            'old_status' => $oldStatus ? 'active' : 'inactive',
            'new_status' => $employee->is_active ? 'active' : 'inactive',
            'changed_by' => session('user_id')
        ]);
        
        // If user was deactivated, log them out immediately
        if ($oldStatus && !$employee->is_active) {
            // Find all active sessions for this user and delete them
            $sessions = \Illuminate\Support\Facades\DB::table('sessions')
                ->where('user_id', $employee->id)
                ->get();
                
            foreach ($sessions as $session) {
                try {
                    $sessionData = unserialize(base64_decode($session->payload));
                    if (isset($sessionData['_token'])) {
                        // Invalidate the session
                        \Illuminate\Support\Facades\Session::getHandler()->destroy($session->id);
                    }
                } catch (\Exception $e) {
                    Log::error('Error destroying session: ' . $e->getMessage(), [
                        'session_id' => $session->id,
                        'employee_id' => $employee->id
                    ]);
                }
            }
            
            Log::info('User sessions terminated after deactivation', [
                'employee_id' => $employee->id,
                'sessions_terminated' => count($sessions)
            ]);
        }

        return response()->json([
            'success' => true,
            'is_active' => $employee->is_active,
            'message' => 'Login access ' . ($employee->is_active ? 'enabled' : 'disabled') . ' successfully.'
        ]);

    } catch (\Exception $e) {
        $errorMessage = 'Error toggling login access: ' . $e->getMessage();
        Log::error($errorMessage, [
            'exception' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'employee_id' => $id
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'An error occurred while updating login access. Please try again.'
        ], 500);
    }
}

/**
            'success' => true,
            'is_active' => $employee->is_active,
            'message' => 'Login access ' . ($employee->is_active ? 'enabled' : 'disabled') . ' successfully.'
        ]);

    } catch (\Exception $e) {
        $errorMessage = 'Error toggling login access: ' . $e->getMessage();
        Log::error($errorMessage, [
            'exception' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'employee_id' => $id,
            'user_id' => session('user_id')
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'An error occurred while updating login access. Please try again.'
        ], 500);
    }
}

/**
 * Handle password change for an employee
 */
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

 
 /**
  * Update an employee's role
  */
 public function updateEmployeeRole(Request $request)
 {
     // Ensure only superadmin or childadmin can perform this action
    if (!in_array(session('emp_job_role'), [1, 8])) {
        return response()->json(['success' => false, 'message' => 'Unauthorized access.'], 403);
    }

     $request->validate([
        'employee_id' => 'required|exists:employees,id',
        'new_role' => 'required|in:1,2,4,5,6,7,8', // Validate against existing role IDs (8 is ChildAdmin)
    ]);

     try {
         $employee = Employee::findOrFail($request->employee_id);
         
         // Prevent changing the role of the current superadmin (optional)
         if ($employee->id === session('emp_id')) {
             return response()->json([
                 'success' => false, 
                 'message' => 'You cannot change your own role.'
             ], 400);
         }
         
         $oldRole = $employee->emp_job_role;
         $employee->emp_job_role = $request->new_role;
         $employee->save();

         // If changing to/from team leader role, handle team assignments
         if ($oldRole == 6 || $request->new_role == 6) {
             // If user was a team leader and is no longer one, remove agents assigned to them
             if ($oldRole == 6) {
                 Employee::where('reports_to', $employee->id)
                     ->update(['reports_to' => null]);
             }
         }

         return response()->json([
             'success' => true, 
             'message' => 'Role updated successfully.',
             'role_name' => $this->getRoleName($request->new_role)
         ]);
     } catch (\Exception $e) {
         Log::error('Error updating employee role: ' . $e->getMessage());
         return response()->json([
             'success' => false, 
             'message' => 'An error occurred while updating the role.'
         ], 500);
     }
 }
 
 /**
  * Get the display name for a role ID
  */
 private function getRoleName($roleId)
 {
     $roles = [
        1 => 'SuperAdmin',
        2 => 'Agent',
        4 => 'HR',
        5 => 'Accountant',
        6 => 'Team Leader',
        7 => 'Referral Agent'
    ];
     
     return $roles[$roleId] ?? 'Unknown Role';
 }

}
