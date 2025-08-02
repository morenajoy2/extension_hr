<div>
    <h3 class="text-lg font-semibold mb-4">Exit Clearance</h3>
    <div class="grid grid-cols-2 gap-4 text-sm">
        <div><p>Employee ID Number: {{ $req?->user?->employee_id }}</p></div>
        <div><p>Employee Name: {{ $req?->user?->first_name }} {{ $req?->user?->last_name }}</p></div>
        <div><p>Email: {{ $req?->user?->email }}</p></div>
        <div><p>Department: {{ $req?->exitClearance?->department?->department_name }}</p></div>
        <div><p>Team: {{ $req?->exitClearance?->departmentTeam?->team_name }}</p></div>
        <div><p>Group No.: {{ $req?->exitClearance?->group?->group_no }}</p></div>
        <div><p>Role: {{ $req?->exitClearance?->role?->role }}</p></div>
        <div><p>Exit Type: {{ ucfirst($req?->exitClearance?->exit_type) }}</p></div>
        <div><p>Task Turnover To: {{ $req?->exitClearance?->taskTurnoverRole?->role }}</p></div>
    </div>

    <div class="mt-4 text-sm space-y-2">
        @if ($req?->exitClearance?->task_list)
            <p>Task List: {{ $req->exitClearance->task_list }}</p>
        @endif
    </div>
</div>
