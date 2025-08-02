@php
    use Illuminate\Support\Facades\Auth;

    $user = Auth::user();
    $role = $user->role?->role;
    $department = $user->department?->department_name;
    $departmentTeam = $user->departmentTeam?->team_name;
    $position = $user->position?->position_name;

    $isHR = $role !== 'Admin' &&
            $department === 'Management' &&
            $departmentTeam === 'Corporate Services' &&
            $position === 'HR';
@endphp

<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-4">📊 Overview Section</h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @if ($role === 'Admin')
            <x-summary-card title="Total Employees" :value="$totalEmployees" />
            <x-summary-card title="Active Employees" :value="$activeEmployees" />
            <x-summary-card title="Exited Employees" :value="$exitedEmployees" />
            <x-summary-card title="Total Files Uploaded" :value="$totalFilesUploaded" />
            <x-summary-card title="Completed Requirements Per User" :value="$completedSubmissions" />
            <x-summary-card title="Incomplete Requirements Per User" :value="$incompleteRequirements" />

        @elseif ($isHR)
            <x-summary-card title="Total Employees" :value="$totalCorporateMembers" />
            <x-summary-card title="Active Employees" :value="$hrActiveEmployees" />
            <x-summary-card title="Exited Employees" :value="$hrExitedEmployees" />
            <x-summary-card title="Total Files Uploaded" :value="$hrTotalFilesUploaded" />
            <x-summary-card title="Completed Corporate Requirements Per User" :value="$hrCompletedSubmissions" />
            <x-summary-card title="Incomplete Corporate Requirements Per User" :value="$hrIncompleteRequirements" />

        @elseif ($role === 'Team Leader')
            <x-summary-card title="{!! str_replace('&amp;', '&', $departmentTeam) !!} Team Members" :value="$teamMembersCount" />
            <x-summary-card title="Completed Team Requirements Per User" :value="$teamCompletedSubmissions" />
            <x-summary-card title="Incomplete Team Requirements Per User" :value="$teamIncompleteRequirements" />


        @elseif ($role === 'Group Leader')
            <x-summary-card title="Group Members" :value="$groupMembersCount" />
            <x-summary-card title="Completed Group Requirements Per User" :value="$groupCompletedSubmissions" />
            <x-summary-card title="Incomplete Group Requirements Per User" :value="$groupIncompleteRequirements" />

        @elseif ($role === 'Member')
            <x-summary-card title="Completed Requirements" :value="$yourCompletedRequirements" />
            <x-summary-card title="Incomplete Requirements" :value="$yourIncompleteRequirements" />

        @else
            <p>No summary data available.</p>
        @endif
    </div>
</div>
