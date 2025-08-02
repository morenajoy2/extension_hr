<x-app-layout>
    <x-slot name="header">
        {{ Auth::user()->role?->role }} Dashboard
    </x-slot>

    @include('dashboard.partials.summary-cards')
    @include('dashboard.partials.requirements-latest')
</x-app-layout>
