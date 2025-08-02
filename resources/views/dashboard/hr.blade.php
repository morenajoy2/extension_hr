<x-app-layout>
    <x-slot name="header">
        {{ Auth::user()->position?->position_name }} Dashboard
    </x-slot>
    <x-slot name="subheader">
        {{ Auth::user()->department?->department_name }} Department – 
        {{ Auth::user()->departmentTeam?->team_name }} Team
    </x-slot>

    @include('dashboard.partials.summary-cards')
    @include('dashboard.partials.requirements-latest')
</x-app-layout>
