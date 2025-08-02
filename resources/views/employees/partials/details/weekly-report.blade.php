<div>
    <h3 class="text-lg font-semibold mb-4">Weekly Report</h3>
    <div class="grid grid-cols-2 gap-4 text-sm">
        <div><p>Employee ID Number : {{ $weekly?->user?->employee_id }}</p></div>
        <div><p>Department: {{ $weekly?->department?->department_name }}</p></div>
        <div><p>Team: {{ $weekly?->departmentTeam?->team_name }}</p></div>
        <div><p>Role: {{ $weekly?->role?->role }}</p></div>
        <div><p>From: {{ \Carbon\Carbon::parse($weekly?->from_date)->format('F j, Y') }}</p></div>
        <div><p>To: {{ \Carbon\Carbon::parse($weekly?->to_date)->format('F j, Y') }}</p></div>
        <div><p>Weekly Worked Hours: {{ $weekly?->worked_hours }}</p></div>
        <div><p>Total Hours Complete: {{ $weekly?->total_hours }}</p></div> 
        <div><p>Required Hours Remaining: {{ $weekly?->remaining_hours }}</p></div> 
    </div>
</div>
