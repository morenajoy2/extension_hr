<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\DepartmentTeam;
use App\Models\Requirement;
use App\Models\User;
use App\Models\WeeklyReport;
use App\Models\NotificationSubmission;
use App\Models\ExitClearance;
use App\Models\Turnover;
use App\Models\EmploymentType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RequirementController extends Controller
{
    public function saveApplication(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'type' => 'required|string|max:255',
            'other_type' => 'nullable|string|max:255',
            'file' => 'required|file|mimes:pdf,jpeg,png,jpg,jfif|max:102400',
            'requires_signature' => 'required|in:Yes,No',
    
            // Application metadata
            'contact_number' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'school' => 'nullable|string|max:255',
            'school_address' => 'nullable|string|max:255',
        ]);
    
        $type = $request->type === 'Others' ? $request->other_type : $request->type;
    
        // Check if the type is one of the predefined application types
        $applicationTypes = [
            'Resume', 'Photo ID', 'Workstation Photo', 'Internet Speed Photo',
            'PC Specification Photo', 'School ID', 'Signed Consent',
            'Valid ID Signed Consent', 'Endorsement Letter', 'MOA'
        ];
    
        if (!in_array($type, $applicationTypes)) {
            return back()->withErrors([
                'type' => "The requirement type \"$type\" is not a valid application requirement."
            ]);
        }
    
        // Prevent duplicate uploads
        $existing = Requirement::where('user_id', $request->user_id)
            ->where('type', $type)
            ->first();
    
        if ($existing && $existing->file) {
            return back()->withErrors([
                'duplicate' => "Requirement \"$type\" has already been uploaded.",
            ]);
        }
    
        $path = $request->file('file')->store("{$request->user_id}/requirements", 'public');
    
        // Save requirement record
        $data = [
            'user_id' => $request->user_id,
            'type' => $type,
            'file' => $path,
            'upload_date' => now(),
            'status' => 'Incomplete',
            'requires_signature' => $validated['requires_signature'],
        ];
    
        if ($validated['requires_signature'] === 'Yes') {
            $data['signature_status'] = 'Unsigned';
        }
    
        $requirement = Requirement::updateOrCreate(
            ['user_id' => $request->user_id, 'type' => $type],
            $data
        );
    
        // Update user application metadata
        $user = User::findOrFail($request->user_id);
        $user->update([
            'contact_number' => $validated['contact_number'],
            'address' => $validated['address'],
            'school' => $validated['school'],
            'school_address' => $validated['school_address'],
        ]);
    
        return redirect()->back()->with('success', $type . ' requirement uploaded successfully!');
    }

    public function saveNotifications(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:25% Notification,50% Notification,75% Notification,100% Notification',
            'requires_signature' => 'required|in:Yes,No',

            'group_id' => 'nullable|exists:groups,id',
            'department_id' => 'nullable|exists:departments,id',
            'position_id' => 'nullable|exists:positions,id',
            'role_id' => 'nullable|exists:roles,id',
            'percent_completed' => 'required|in:25,50,75,100',
            'percent_date' => 'required|date',
            'total_worked_hours_completed' => 'nullable|integer|min:0',
            'total_worked_hours_required' => 'nullable|integer|min:0',
            'notifyFile' => 'required|file|mimes:pdf,mp4|max:102400',
        ]);

        $type = $request->type === 'Others' ? $request->other_type : $request->type;

        // === DUPLICATE CHECK ===
        $existing = DB::table('notification_submissions')
            ->where('user_id', $request->user_id)
            ->where('percent_completed', $request->percent_completed)
            ->first();

        if ($existing && $existing->notifyFile) {
            return back()->withErrors([
                'duplicate' => "Requirement \"$type\" has already been uploaded.",
            ]);
        }

        // === FILE UPLOAD ===
        $basePath = "{$request->user_id}/requirements/notifications";
        $notificationFile = $request->file('notifyFile')->store($basePath, 'public');

        $data = [
            'user_id' => $request->user_id,
            'type' => $type,
            'upload_date' => now(),
            'status' => 'Incomplete',
            'requires_signature' => $validated['requires_signature'],
        ];

        if ($validated['requires_signature'] === 'Yes') {
            $data['signature_status'] = 'Unsigned';
        }

        $requirement = Requirement::updateOrCreate(
            ['user_id' => $request->user_id, 'type' => $type],
            $data
        );

        $notifData = [
            'user_id' => $request->user_id,
            'percent_completed' => $request->percent_completed,
            'group_id' => $request->group_id,
            'department_id' => $request->department_id,
            'position_id' => $request->position_id,
            'role_id' => $request->role_id,
            'percent_date' => $request->percent_date,
            'total_worked_hours_completed' => $request->total_worked_hours_completed,
            'total_worked_hours_required' => $request->total_worked_hours_required,
            'requirement_id' => $requirement->id,
            'notifyFile' => $notificationFile,
            'updated_at' => now(),
            'created_at' => now(),
        ];

        DB::table('notification_submissions')->updateOrInsert(
            [
                'user_id' => $request->user_id,
                'percent_completed' => $request->percent_completed
            ],
            $notifData
        );

        return redirect()->back()->with('success', 'Notification uploaded successfully!');
    }

    public function saveWeeklyReport(Request $request)
    {
        $type = $request->type === 'Others' ? $request->other_type : $request->type;

        if ($type === 'Weekly Report') {
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
                'type' => 'required|string|max:255',
                'other_type' => 'nullable|string|max:255',
                'requires_signature' => 'required|in:Yes,No',

                'department_id' => 'required|exists:departments,id',
                'department_team_id' => 'required|exists:department_teams,id',
                'role_id' => 'required|exists:roles,id',
                'from_date' => 'required|date',
                'to_date' => 'required|date|after_or_equal:from_date',
                'worked_hours' => 'required|integer|min:0',
                'total_hours' => 'required|integer|min:0',
                'remaining_hours' => 'required|integer|min:0',

                // Updated for 2 files
                'doc_upload' => 'required|file|mimes:doc,docx|max:102400',
                'pdf_upload' => 'required|file|mimes:pdf|max:102400',
            ]);

            // Prevent duplicates for the same date range
            $existingWeekly = Requirement::where('user_id', $request->user_id)
                ->where('type', 'Weekly Report')
                ->whereHas('weeklyReport', function ($query) use ($request) {
                    $query->where('from_date', $request->from_date)
                        ->where('to_date', $request->to_date);
                })
                ->first();

            if ($existingWeekly) {
                return back()->withErrors([
                    'duplicate' => "Weekly Report for this date range already exists.",
                ]);
            }

            // Store files
            $basePath = "{$request->user_id}/requirements/weeklyreport";

            $docPath = $request->file('doc_upload')->store($basePath, 'public');
            $pdfPath = $request->file('pdf_upload')->store($basePath, 'public');

            // Save Requirement (file is optional here since WeeklyReport stores real file paths)
            $data = [
                'user_id' => $request->user_id,
                'type' => $type,
                'upload_date' => now(),
                'status' => 'Pending',
                'requires_signature' => $validated['requires_signature'],
            ];

            if ($validated['requires_signature'] === 'Yes') {
                $data['signature_status'] = 'Unsigned';
            }

            $requirement = Requirement::create($data);

            // Save Weekly Report Data
            WeeklyReport::create([
                'requirement_id' => $requirement->id,
                'user_id' => $request->user_id,
                'department_id' => $validated['department_id'],
                'department_team_id' => $validated['department_team_id'],
                'role_id' => $validated['role_id'],
                'from_date' => $validated['from_date'],
                'to_date' => $validated['to_date'],
                'worked_hours' => $validated['worked_hours'],
                'total_hours' => $validated['total_hours'],
                'remaining_hours' => $validated['remaining_hours'],
                'doc_upload' => $docPath,
                'pdf_upload' => $pdfPath,
            ]);

            return redirect()->back()->with('success', 'Weekly Report uploaded successfully!');
        }

        return redirect()->back()->with('error', 'Invalid report type.');
    }

    public function saveExitClearance(Request $request)
    {
        $type = $request->type === 'Others' ? $request->other_type : $request->type;

        $request->validate([
            'department_id' => 'required|exists:departments,id',
            'department_team_id' => 'required|exists:department_teams,id',
            'role_id' => 'required|exists:roles,id',
            'group_no' => 'required|exists:groups,id',
            'task_turnover_role' => 'required|exists:roles,id',
            'task_list' => 'nullable|string',
            'team_leader_access_confirmation' => 'required|file|mimes:pdf,jpg,jpeg,png|max:102400',
            'hr_access_confirmation' => 'required|file|mimes:pdf,jpg,jpeg,png|max:102400',
            'e_signature_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:102400',
            'exit_type' => 'required|in:completion,resignation,termination',
        ]);

        // 🔍 Check for existing Exit Clearance
        $existing = Requirement::where('user_id', $request->user_id)
            ->where('type', 'Exit Clearance')
            ->first();

        if ($existing && $existing->exitClearance && $existing->exitClearance->team_leader_access_confirmation) {
            return back()->withErrors([
                'duplicate' => 'Exit Clearance has already been submitted.',
            ]);
        }

        // Create or update Requirement
        $requirement = $existing ?? Requirement::create([
            'user_id' => $request->user_id,
            'type' => $type,
            'upload_date' => now(),
            'status' => 'Pending',
            'requires_signature' => $request['requires_signature'],
        ]);

        // File storage
        $basePath = "{$request->user_id}/requirements/exit_clearances";
        $teamLeaderFile = $request->file('team_leader_access_confirmation')->store($basePath, 'public');
        $hrFile = $request->file('hr_access_confirmation')->store($basePath, 'public');
        $signatureFile = $request->file('e_signature_file')->store($basePath, 'public');

        ExitClearance::updateOrCreate(
            ['requirement_id' => $requirement->id],
            [
                'user_id' => auth()->id(),
                'department_id' => $request->department_id,
                'department_team_id' => $request->department_team_id,
                'role_id' => $request->role_id,
                'group_id' => $request->group_no,
                'exit_type' => $request->exit_type,
                'task_turnover_role' => $request->task_turnover_role,
                'task_list' => $request->task_list,
                'team_leader_access_confirmation' => $teamLeaderFile,
                'hr_access_confirmation' => $hrFile,
                'e_signature' => $signatureFile,
            ]
        );

        return redirect()->back()->with('success', 'Exit Clearance submitted successfully.');
    }

    public function saveTurnover(Request $request)
    {
        $type = $request->type === 'Others' ? $request->other_type : $request->type;

        $request->validate([
            'department_id' => 'required|exists:departments,id',
            'department_team_id' => 'required|exists:department_teams,id',
            'role_id' => 'required|exists:roles,id',
            'employment_type_id' => 'required|exists:employment_types,id',
            'job_title' => 'required|string',
            'orientation_date' => 'required|date',
            'first_day_date' => 'required|date',
            'last_day_date' => 'required|date',
            'exit_date' => 'required|date',
            'total_worked_hours_required' => 'required|numeric',
            'recommended_employee_id' => 'required|exists:users,id',
            'recommended_employee_name' => 'required|string|max:255',
            'new_owner_transfer_list' => 'nullable|string',
            'confirmation_access_credentials' => 'nullable|string',
            'task_list' => 'nullable|string',
            'dpt_team_leader_employee_id' => 'required|exists:users,id',
            'dpt_team_leader_employee_name' => 'required|string|max:255',
            'hr_team_leader_employee_id' => 'required|exists:users,id',
            'hr_team_leader_employee_name' => 'required|string|max:255',
            'e_signature' => 'required|file|mimes:pdf,jpg,jpeg,png|max:102400',
        ]);

        // 🔍 Check for existing Turnover
        $existing = Requirement::where('user_id', $request->user_id)
            ->where('type', 'Turnover')
            ->first();

        if ($existing && $existing->turnover && $existing->turnover->e_signature) {
            return back()->withErrors([
                'duplicate' => 'Turnover has already been submitted.',
            ]);
        }

        $requirement = $existing ?? Requirement::create([
            'user_id' => $request->user_id,
            'type' => $type,
            'upload_date' => now(),
            'status' => 'Pending',
            'requires_signature' => $request['requires_signature'],
        ]);

        $basePath = "{$request->user_id}/requirements/turnover";
        $signatureFile = $request->file('e_signature')->store($basePath, 'public');

        $turnoverData = [
            'user_id' => auth()->id(),
            'department_id' => $request->department_id,
            'department_team_id' => $request->department_team_id,
            'role_id' => $request->role_id,
            'employment_type_id' => $request->employment_type_id,
            'job_title' => $request->job_title,
            'orientation_date' => $request->orientation_date,
            'first_day_date' => $request->first_day_date,
            'last_day_date' => $request->last_day_date,
            'exit_date' => $request->exit_date,
            'total_worked_hours_required' => $request->total_worked_hours_required,
            'recommended_employee_id' => $request->recommended_employee_id,
            'recommended_employee_name' => $request->recommended_employee_name,
            'task_list' => $request->task_list,
            'new_owner_transfer_list' => $request->new_owner_transfer_list,
            'confirmation_access_credentials' => $request->confirmation_access_credentials,
            'dpt_team_leader_employee_id' => $request->dpt_team_leader_employee_id,
            'dpt_team_leader_employee_name' => $request->dpt_team_leader_employee_name,
            'hr_team_leader_employee_id' => $request->hr_team_leader_employee_id,
            'hr_team_leader_employee_name' => $request->hr_team_leader_employee_name,
            'e_signature' => $signatureFile,
        ];

        Turnover::updateOrCreate(
            ['requirement_id' => $requirement->id],
            $turnoverData
        );

        return redirect()->back()->with('success', 'Turnover submitted successfully.');
    }

    public function store(Request $request)
    {
        // Define application types early
        $applicationTypes = [
            'Resume', 'Photo ID', 'Workstation Photo', 'Internet Speed Photo',
            'PC Specification Photo', 'School ID', 'Signed Consent',
            'Valid ID Signed Consent', 'Endorsement Letter', 'MOA'
        ];

        $notificationTypes = [
            '25% Notification', '50% Notification', '75% Notification', '100% Notification'
        ];
    
        // Route Weekly Report to its dedicated method
        if ($request->type === 'Weekly Report') {
            return app()->call([$this, 'saveWeeklyReport'], ['request' => $request]);
        }
    
        // Route Application types to their dedicated method
        if (in_array($request->type, $applicationTypes)) {
            return app()->call([$this, 'saveApplication'], ['request' => $request]);
        }

        if (in_array($request->type, $notificationTypes)) {
            return app()->call([$this, 'saveNotifications'], ['request' => $request]);
        }

        if ($request->type === 'Exit Clearance') {
            return app()->call([$this, 'saveExitClearance'], ['request' => $request]);
        }

        if ($request->type === 'Turnover') {
            return app()->call([$this, 'saveTurnover'], ['request' => $request]);
        }

        // Continue with validation for other types (Exit Clearance, Notifications, etc.)
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'type' => 'required|string|max:255',
            'other_type' => 'nullable|string|max:255',
            'file' => 'required|file',
            'requires_signature' => 'required|in:Yes,No',
        ]);
    
        $type = $request->type === 'Others' ? $request->other_type : $request->type;
    
        // Prevent duplicate requirement for types (except Weekly Report handled separately)
        $existing = Requirement::where('user_id', $request->user_id)
            ->where('type', $type)
            ->first();
    
        if ($existing && $existing->file) {
            return back()->withErrors([
                'duplicate' => "Requirement \"$type\" has already been uploaded.",
            ]);
        }
    
        // Store file
        $path = $request->file('file')->store("{$request->user_id}/requirements", 'public');
    
        // Save to requirements table
        $data = [
            'user_id' => $request->user_id,
            'type' => $type,
            'file' => $path,
            'upload_date' => now(),
            'status' => 'Incomplete',
            'requires_signature' => $validated['requires_signature'],
        ];
    
        if ($validated['requires_signature'] === 'Yes') {
            $data['signature_status'] = 'Unsigned';
        }
    
        Requirement::create($data);
    
        return redirect()->back()->with('success', 'Requirement uploaded successfully!');
    }
    
    public function delete(Requirement $requirement)
    {
        // Delete main requirement files
        if ($requirement->file) {
            Storage::disk('public')->delete($requirement->file);
        }

        if ($requirement->signed_file) {
            Storage::disk('public')->delete($requirement->signed_file);
        }

        // Check if this is a Notification-type requirement
        $notificationTypes = ['25% Notification', '50% Notification', '75% Notification', '100% Notification'];
        if (in_array($requirement->type, $notificationTypes)) {
            // Get the associated notification submission
            $notif = DB::table('notification_submissions')
                ->where('requirement_id', $requirement->id)
                ->first();

            // Delete the notification file if it exists
            if ($notif && $notif->notifyFile) {
                Storage::disk('public')->delete($notif->notifyFile);
            }

            // Delete the notification submission record
            DB::table('notification_submissions')
                ->where('requirement_id', $requirement->id)
                ->delete();
        }

        $requiredTypes = config('requirements.required_types') ?? [];

        if (in_array($requirement->type, $requiredTypes)) {
            // Reset (not delete) for required types
            $requirement->update([
                'file' => null,
                'upload_date' => null,
                'status' => 'Completed',
                'requires_signature' => 'No',
                'signed_file' => null,
                'signature_status' => 'Unsigned',
                'signed_date' => null,
            ]);
        } else {
            // Fully delete for optional types
            $requirement->delete();
        }

        return redirect()->route('employees.show', $requirement->user_id)
            ->with('success', 'Requirement deleted successfully.');
    }

    public function myRequirements()
    {
        $user = Auth::user();
        $allTypes = config('requirements.required_types');

        // Automatically ensure 'Weekly Report' exists for this user
         Requirement::firstOrCreate([
            'user_id' => $user->id,
            'type' => 'Weekly Report',
        ], [
            'status' => 'Incomplete',
        ]);

        $requirements = Requirement::where('user_id', $user->id)->get();

        return view('employees.show', compact('user', 'requirements', 'allTypes'));
    }

    public function uploadSignedFile(Request $request, Requirement $requirement)
    {
        $request->validate([
            'signed_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:51200', // up to 50MB
        ]);

        // Delete old signed file if exists
        if ($requirement->signed_file) {
            Storage::disk('public')->delete($requirement->signed_file);
        }

        $userId = $requirement->user_id;
        $folder = $userId . '/requirements/signed';
        $filename = time() . '_' . $request->file('signed_file')->getClientOriginalName();
        $filePath = $request->file('signed_file')->store("{$userId}/requirements/signed", 'public');

        $requirement->update([
            'signed_file' => $filePath,
            'signature_status' => 'Signed',
            'signed_date' => now(),
        ]);

        return redirect()->back()->with('success', 'Signed file uploaded successfully.');
    }

    public function showRequirementDetails($id)
    {
        $requirement = Requirement::with([
            'weeklyReport',
            'notificationSubmission',
            'user',
            'exitClearance',
            'turnover'
        ])->findOrFail($id);

        $type = $requirement->type;

        if (Str::contains($type, 'Weekly Report')) {
            $viewPath = 'employees.partials.details.weekly-report';
        } elseif (Str::contains($type, 'Application') || in_array($type, ['Resume', 
                'Photo ID', 'Workstation Photo', 'Internet Speed Photo', 'PC Specification Photo', 
                'School ID', 'Signed Consent', 'Valid ID Signed Consent', 'Endorsement Letter', 'MOA'])) {
            $viewPath = 'employees.partials.details.application';
        } elseif (Str::contains($type, 'Notification')) {
            $viewPath = 'employees.partials.details.notification';
         } elseif (Str::contains($type, 'Turnover')) {
            $viewPath = 'employees.partials.details.turnover';    
        } elseif (Str::contains($type, 'Exit Clearance')) {
            $viewPath = 'employees.partials.details.exit-clearance';
        } else {
            $viewPath = null;
        }

        if (!$viewPath || !view()->exists($viewPath)) {
            return response()->json(['error' => 'Detail view not found for this requirement type.'], 404);
        }

        return response()->view($viewPath, [
            'req' => $requirement,
            'weekly' => $requirement->weeklyReport,
            'notification' => $requirement->notificationSubmission,
            'user' => $requirement->user,
            'type' => $requirement->type,
            'exitClearance' => $requirement->exitClearance,
            'turnover' => $requirement->turnover,
        ]);
    }

    public function getExitClearanceFiles(Requirement $requirement)
    {
        $exitClearance = $requirement->exitClearance;

        if (!$exitClearance) {
            return response()->json(['success' => false, 'message' => 'No exit clearance found.']);
        }

        $files = [];

        if ($exitClearance->team_leader_access_confirmation) {
            $files[] = [
                'label' => 'Team Leader Access Confirmation',
                'url' => asset('storage/' . $exitClearance->team_leader_access_confirmation),
            ];
        }

        if ($exitClearance->hr_access_confirmation) {
            $files[] = [
                'label' => 'HR Access Confirmation',
                'url' => asset('storage/' . $exitClearance->hr_access_confirmation),
            ];
        }

        if ($exitClearance->e_signature) {
            $files[] = [
                'label' => 'E-Signature',
                'url' => asset('storage/' . $exitClearance->e_signature),
            ];
        }

        return response()->json([
            'success' => true,
            'files' => $files
        ]);
    }

    public function getWeeklyReportFiles(Requirement $requirement)
    {
        $weekly = $requirement->weeklyReport;

        if (!$weekly) {
            return response()->json([
                'success' => false,
                'files' => [],
            ]);
        }

        $files = [];

        if ($weekly->doc_upload) {
            $files[] = [
                'label' => 'Word File',
                'url' => asset('storage/' . $weekly->doc_upload),
            ];
        }

        if ($weekly->pdf_upload) {
            $files[] = [
                'label' => 'PDF File',
                'url' => asset('storage/' . $weekly->pdf_upload),
            ];
        }

        return response()->json([
            'success' => true,
            'files' => $files,
        ]);
    }
}