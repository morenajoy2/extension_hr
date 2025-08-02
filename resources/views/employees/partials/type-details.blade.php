@if ($type === 'Weekly Report')
    @include('employees.partials.details.weekly-report', ['weekly' => $weekly])
@elseif ($type === 'Application')
    @include('employees.partials.details.application', ['req' => $req])
@elseif ($type === 'Notification')
    @include('employees.partials.details.notification', ['req' => $req])
@elseif ($type === 'Turnover')
    @include('employees.partials.details.turnover', ['turnover' => $turnover])
@elseif ($type === 'Exit Clearance')
    @include('employees.partials.details.exit-clearance', ['exitClearance' => $exitClearance])
@else
    <p class="text-gray-500 italic">No detail view available for this type.</p>
@endif