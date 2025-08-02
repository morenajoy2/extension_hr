<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'HRIS') }}</title>

    <!-- Scripts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- 🛡️ x-cloak styling to prevent dropdown flash -->
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="h-full" x-data="{ profileOpen: false }">
    <div class="flex h-full">
        <!-- Sidebar -->
        @include('layouts.navigation')

        <!-- Main content area -->
        <main class="flex-1 p-8 overflow-auto">
            @isset($header)
                <h1 class="text-2xl font-bold text-gray-900 ">{{ $header }}</h1>
            @endisset
            @isset($subheader)
                <h4 class="text-l font-bold text-gray-900 mb-6">{{ $subheader }}</h4>
            @endisset

            <div class="rounded-lg border border-gray-300 bg-white p-6 shadow-sm">
                {{ $slot }}
            </div>
        </main>
    </div>
</body>
</html>
