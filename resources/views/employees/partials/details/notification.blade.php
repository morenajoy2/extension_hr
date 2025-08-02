<div>
    <h3 class="text-lg font-semibold mb-4">{{ $notification?->percent_completed ?? '' }}% Notification</h3>
    <div class="grid grid-cols-2 gap-4 text-sm">
        <p>Employee ID Number: {{ $user?->employee_id ?? 'N/A' }}</p>
        <p>Full Name: {{ $user?->first_name ?? '' }} {{ $user?->last_name ?? '' }}</p>
        <p>Email Address: {{ $user?->email ?? 'N/A' }}</p>
        <p>Department: {{ $notification?->department?->department_name ?? 'N/A' }}</p>
        <p>Group Number Assigned: {{ $notification?->group?->group_no ?? 'N/A' }}</p>
        <p>Position: {{ $notification?->position?->position_name ?? 'N/A' }}</p>
        <p>Role: {{ $notification?->role?->role ?? 'N/A' }}</p>
        <p>Date of {{ $notification?->percent_completed ?? '' }}% Worked Hours: 
            {{ $notification?->percent_date ? \Carbon\Carbon::parse($notification->percent_date)->format('F d, Y') : 'N/A' }}
        </p>
        <p>Total Worked Hours Completed: {{ $notification?->total_worked_hours_completed ?? 'N/A' }}</p>
        <p>Total Worked Hours Required: {{ $notification?->total_worked_hours_required ?? 'N/A' }}</p>
    </div>
</div>
