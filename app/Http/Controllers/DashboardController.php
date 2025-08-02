<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Requirement;

class DashboardController extends Controller
{
    public function redirectByRole()
    {
        $user = Auth::user();
        $roleName = optional($user->role)->role; // Get role name from related model
        $data = [];

        // HR-specific logic: Department = Management, Team = Corporate Services, Position = HR
        if (
            $roleName !== 'Admin' &&
            optional($user->department)->department_name === 'Management' &&
            optional($user->departmentTeam)->team_name === 'Corporate Services' &&
            optional($user->position)->position_name === 'HR'
        ) {
            $data = $this->getHRSummary($user);
            return view('dashboard.hr', $data);
        }

        // Role-based dashboard redirection
        switch ($roleName) {
            case 'Admin':
                $data = $this->getAdminSummary();
                return view('dashboard.admin', $data);
            case 'Team Leader':
                $data = $this->getTeamLeaderSummary($user);
                return view('dashboard.team-leader', $data);
            case 'Group Leader':
                $data = $this->getGroupLeaderSummary($user);
                return view('dashboard.group-leader', $data);
            case 'Member':
                $data = $this->getMemberSummary($user);
                return view('dashboard.member', $data);
            default:
                abort(403, 'Unauthorized access');
        }
    }

    private function getAdminSummary()
    {
        $requiredTypes = config('requirements.required_types');
        $otherTypes = config('requirements.other_types');
        $allExpectedTypes = array_merge($requiredTypes, $otherTypes);

        $users = User::all();

        $completedSubmissions = 0;
        $incompleteRequirements = 0;
        $totalFilesUploaded = 0;

        foreach ($users as $user) {
            $uploadedTypes = Requirement::where('user_id', $user->id)
                                        ->where('status', 'Completed')
                                        ->pluck('type')
                                        ->toArray();

            $uploadedExpected = array_intersect($uploadedTypes, $allExpectedTypes);
            $uploadedCount = count($uploadedExpected);
            $expectedCount = count($allExpectedTypes);

            $totalFilesUploaded += $uploadedCount;

            if ($uploadedCount >= $expectedCount && $expectedCount > 0) {
                $completedSubmissions++;
            } else {
                $incompleteRequirements++;
            }
        }

        return [
            'totalEmployees' => $users->count(),
            'completedSubmissions' => $completedSubmissions,
            'incompleteRequirements' => $incompleteRequirements,
            'totalFilesUploaded' => $totalFilesUploaded,
            'activeEmployees' => User::where('status', 'Active')->count(),
            'exitedEmployees' => User::where('status', 'Exited')->count(),
        ];
    }

    private function getHRSummary($user)
    {
        $applicationTypes = config('requirements.required_types');
        $otherTypes = config('requirements.other_types');

        // Merge all expected types (unique list)
        $allExpectedTypes = array_merge($applicationTypes, $otherTypes);

        $allUsers = User::all();

        $completedSubmissions = 0;
        $incompleteRequirements = 0;
        $totalFilesUploaded = 0;

        foreach ($allUsers as $employee) {
            // Get uploaded types with 'Completed' status
            $uploadedTypes = Requirement::where('user_id', $employee->id)
                                        ->where('status', 'Completed')
                                        ->pluck('type')
                                        ->toArray();

            // Count only the uploaded types that match expected types
            $uploadedExpected = array_intersect($uploadedTypes, $allExpectedTypes);
            $uploadedCount = count($uploadedExpected);
            $expectedCount = count($allExpectedTypes);

            $totalFilesUploaded += $uploadedCount;

            if ($uploadedCount >= $expectedCount && $expectedCount > 0) {
                $completedSubmissions++;
            } else {
                $incompleteRequirements++;
            }
        }

        return [
            'totalCorporateMembers' => $allUsers->count(),
            'hrCompletedSubmissions' => $completedSubmissions,
            'hrIncompleteRequirements' => $incompleteRequirements,
            'hrTotalFilesUploaded' => $totalFilesUploaded,
            'hrActiveEmployees' => User::where('status', 'Active')->count(),
            'hrExitedEmployees' => User::where('status', 'Exited')->count(),
        ];
    }

    private function getTeamLeaderSummary($user)
    {
        $requiredTypes = config('requirements.required_types');
        $otherTypes = config('requirements.other_types');
        $allExpectedTypes = array_merge($requiredTypes, $otherTypes);

        $departmentId = $user->department_id;
        $teamMembers = User::where('department_id', $departmentId)->get();

        $completedSubmissions = 0;
        $incompleteRequirements = 0;

        foreach ($teamMembers as $member) {
            $uploadedTypes = Requirement::where('user_id', $member->id)
                                        ->where('status', 'Completed')
                                        ->pluck('type')
                                        ->toArray();

            $uploadedExpected = array_intersect($uploadedTypes, $allExpectedTypes);
            $uploadedCount = count($uploadedExpected);
            $expectedCount = count($allExpectedTypes);

            if ($uploadedCount >= $expectedCount && $expectedCount > 0) {
                $completedSubmissions++;
            } else {
                $incompleteRequirements++;
            }
        }

        return [
            'teamMembersCount' => $teamMembers->count(),
            'teamCompletedSubmissions' => $completedSubmissions,
            'teamIncompleteRequirements' => $incompleteRequirements,
        ];
    }

    private function getGroupLeaderSummary($user)
    {
        $requiredTypes = config('requirements.required_types');
        $otherTypes = config('requirements.other_types');
        $allExpectedTypes = array_merge($requiredTypes, $otherTypes);

        $teamId = $user->department_team_id;
        $groupMembers = User::where('department_team_id', $teamId)->get();

        $completedSubmissions = 0;
        $incompleteRequirements = 0;

        foreach ($groupMembers as $member) {
            $uploadedTypes = Requirement::where('user_id', $member->id)
                                        ->where('status', 'Completed')
                                        ->pluck('type')
                                        ->toArray();

            $uploadedExpected = array_intersect($uploadedTypes, $allExpectedTypes);
            $uploadedCount = count($uploadedExpected);
            $expectedCount = count($allExpectedTypes);

            if ($uploadedCount >= $expectedCount && $expectedCount > 0) {
                $completedSubmissions++;
            } else {
                $incompleteRequirements++;
            }
        }

        return [
            'groupMembersCount' => $groupMembers->count(),
            'groupCompletedSubmissions' => $completedSubmissions,
            'groupIncompleteRequirements' => $incompleteRequirements,
        ];
    }

    private function getMemberSummary($user)
    {
        $requiredTypes = config('requirements.required_types');
        $otherTypes = config('requirements.other_types');
        $allExpectedTypes = array_merge($requiredTypes, $otherTypes);

        $uploadedTypes = Requirement::where('user_id', $user->id)
                                    ->where('status', 'Completed')
                                    ->pluck('type')
                                    ->toArray();

        $uploadedExpected = array_intersect($uploadedTypes, $allExpectedTypes);
        $uploadedCount = count($uploadedExpected);
        $expectedCount = count($allExpectedTypes);

        return [
            'yourCompletedRequirements' => min($uploadedCount, $expectedCount),
            'yourIncompleteRequirements' => max(0, $expectedCount - $uploadedCount),
        ];
    }
}
