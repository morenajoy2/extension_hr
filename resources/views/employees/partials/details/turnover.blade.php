<div>
    <h3 class="text-lg font-semibold mb-4">Employee Account Turnover Form</h3>
    <div class="grid grid-cols-2 gap-4 text-sm">
        <div><p>Employee ID Number: {{ $req?->user?->employee_id }}</p></div>
        <div><p>Employee Name: {{ $req?->user?->first_name }} {{ $req?->user?->last_name }}</p></div>
        <div><p>Email: {{ $req?->user?->email }}</p></div>
        <div><p>Employment Type: {{ $req?->turnover?->employmentType?->type_name }}</p></div>
        <div><p>Department: {{ $req?->turnover?->department?->department_name }}</p></div>
        <div><p>Team: {{ $req?->turnover?->departmentTeam?->team_name }}</p></div>
        <div><p>Role: {{ $req?->turnover?->role?->role }}</p></div>
        <div><p>Job Title: {{ $req?->turnover?->job_title }}</p></div>
        <div><p>Orientation Date: {{ $req?->turnover?->orientation_date }}</p></div>
        <div><p>First Day: {{ $req?->turnover?->first_day_date }}</p></div>
        <div><p>Last Day: {{ $req?->turnover?->last_day_date }}</p></div>
        <div><p>Exit Date: {{ $req?->turnover?->exit_date }}</p></div>
        <div><p>Total Worked Hours Required: {{ $req?->turnover?->total_worked_hours_required }}</p></div>
    </div>
    <hr class="my-4">
    <h3 class="text-lg font-semibold mb-4">Recommended Employee</h3>
    <div class="grid grid-cols-2 gap-4 text-sm">
        <div><p>Recommended Employee ID: {{ $req?->turnover?->recommended_employee_id }}</p></div>
        <div><p>Recommended Employee Name: {{ $req?->turnover?->recommended_employee_name }}</p></div>
        <div class="text-sm space-y-2">
            @if ($req?->turnover?->task_list)
                <p>Task List: {{ $req->turnover->task_list }}</p>
            @endif
        </div>
        <div><p>New Owner Transfer List: {{ $req?->turnover?->new_owner_transfer_list }}</p></div>
        <div><p>Confirmation (Access Credentials): {{ $req?->turnover?->confirmation_access_credentials }}</p></div>
        <div><p>Department Team Leader ID: {{ $req?->turnover?->dpt_team_leader_employee_id }}</p></div>
        <div><p>Department Team Leader Name: {{ $req?->turnover?->dpt_team_leader_employee_name }}</p></div>
        <div><p>HR Team Leader ID: {{ $req?->turnover?->hr_team_leader_employee_id }}</p></div>
        <div><p>HR Team Leader Name: {{ $req?->turnover?->hr_team_leader_employee_name }}</p></div>
    </div> 
</div>
