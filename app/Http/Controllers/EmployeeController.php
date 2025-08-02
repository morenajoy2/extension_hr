<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\DepartmentTeam;
use App\Models\EmploymentType;
use App\Models\Group;
use App\Models\Requirement;
use App\Models\Role;
use App\Models\User;
use App\Models\WeeklyReport;
use Illuminate\Http\Request;
use App\Models\Position;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['role', 'department', 'departmentTeam', 'position']); // eager load relations

        // Search filter
        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%$search%")
                  ->orWhere('last_name', 'like', "%$search%")
                  ->orWhere('employee_id', 'like', "%$search%")
                  ->orWhere('school', 'like', "%$search%");
            });
        }

        // Filter by latest, oldest, active, exited
        switch ($request->filter) {
            case 'latest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'active':
                $query->where('status', 'Active');
                break;
            case 'exited':
                $query->where('status', 'Exited');
                break;
        }

        $employees = $query->paginate(10);

        return view('employees.index', compact('employees'));
    }

    public function show(User $user)
    {
        $requirements = Requirement::where('user_id', $user->id)->get();
        $allTypes = config('requirements.required_types') ?? [];

        $otherRequirements = $requirements->filter(function ($req) use ($allTypes) {
            return !in_array($req->type, $allTypes);
        });

        $weeklyRequirements = Requirement::where('user_id', $user->id)
        ->where('type', 'Weekly Report')
        ->with('weeklyReport') 
        ->orderByDesc('upload_date')
        ->get();

        $departments = Department::all();
        $departmentTeams = DepartmentTeam::all();
        $roles = Role::all();
        $groups = Group::all();
        $employee = User::with(['requirements.exitClearance'])->findOrFail($user->id);

        $exitClearances = Requirement::where('user_id', $user->id)
            ->where('type', 'Exit Clearance')
            ->with('exitClearance')
            ->get();

        $users = User::all();
        $turnovers = Requirement::with('turnover')
            ->where('type', 'Turnover')
            ->where('user_id', $user->id) 
            ->get();

        $employmentTypes = EmploymentType::all();
        $positions = Position::all();

        return view('employees.show', compact('user', 'requirements', 'allTypes', 'otherRequirements', 'departments', 
            'departmentTeams', 'positions', 'groups', 'roles', 'weeklyRequirements', 'employee', 'exitClearances', 'users', 
            'turnovers', 'employmentTypes'));
    }

    public function edit(User $user)
    {
        return view('employees.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'school'     => 'nullable|string|max:255',
            'status'     => 'required|in:Active,Exited',

        ]);

        $user->update($validated);
        return redirect()->route('employees.index')->with('success', 'Employee updated.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('employees.index')->with('success', 'Employee deleted.');
    }

    public function updateStatus(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $newStatus = ucfirst(strtolower($request->input('status'))); 

        if (!in_array($newStatus, ['Active', 'Exited'])) {
            return back()->withErrors(['Invalid status.']);
        }

        $user->status = $newStatus;
        $user->save();

        return back()->with('success', 'Status updated successfully.');
    }

    public function updateRequirementStatus(Request $request, $id)
    {
        $request->validate([
            'req_status' => 'required|in:Completed,Incomplete,Pending',
        ]);

        $updated = Requirement::where('user_id', $id)->update([
            'status' => $request->req_status,
        ]);

        if ($updated === 0) {
            return back()->with('success', 'No requirements found for this user.');
        }

        return back()->with('success', 'Requirement status updated successfully.');
    }

    public function autocomplete(Request $request)
    {
        $search = $request->input('query');

        $users = User::where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('employee_id', 'like', "%{$search}%")
                    ->orWhere('school', 'like', "%{$search}%")
                    ->limit(5)
                    ->get(['id', 'first_name', 'last_name', 'employee_id', 'school']);

        return response()->json($users);
    }
}
