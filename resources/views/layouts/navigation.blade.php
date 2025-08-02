<nav class="bg-white border-r border-gray-200 w-64 flex flex-col min-h-screen">
    <!-- Top Section -->
    <div>
        <!-- Logo -->
        <div class="flex items-center justify-center h-16 border-b border-gray-200">
            <a href="{{ route('dashboard') }}">
                <img src="{{ asset('storage/roc-logo.png') }}" alt="Logo" class="h-9 w-auto" />
            </a>
        </div>

        <!-- Navigation Links -->
        <div class="py-4">
            <a href="{{ route('dashboard') }}" class="block px-6 py-3 text-gray-700 hover:bg-gray-100 font-medium">
                {{ __('🏠 Home') }}
            </a>

            @php
                $user = Auth::user();
            @endphp

            @if ($user->role?->role === 'Admin' || $user->position?->position_name === 'HR')
                <a href="{{ route('employees.index') }}" class="block px-6 py-3 text-gray-700 hover:bg-gray-100 font-medium">
                    {{ __('📁 201 Tracker') }}
                </a>
            @endif

            @if ($user->role?->role === 'Admin')
                <a href="{{ route('employment-types.index') }}" 
                    class="block px-6 py-3 text-gray-700 hover:bg-gray-100 font-medium">
                    💼 Employment Type
                </a>

                <a href="{{ route('departments.index') }}" 
                    class="block px-6 py-3 text-gray-700 hover:bg-gray-100 font-medium">
                    🏢 Department
                </a>

                <a href="{{ route('department-teams.index') }}" 
                    class="block px-6 py-3 text-gray-700 hover:bg-gray-100 font-medium">
                    👨‍👩‍👧‍👦 Department Team
                </a>

                <a href="{{ route('strands.index') }}" 
                    class="block px-6 py-3 text-gray-700 hover:bg-gray-100 font-medium">
                    📚 Strand
                </a>

                <a href="{{ route('positions.index') }}" 
                    class="block px-6 py-3 text-gray-700 hover:bg-gray-100 font-medium">
                    🧾 Position
                </a>

                <a href="{{ route('groups.index') }}" 
                    class="block px-6 py-3 text-gray-700 hover:bg-gray-100 font-medium">
                    👨‍👨‍👦 Group
                </a>


            @endif

            @unless ($user->role?->role === 'Admin')
                <a href="{{ route('employees.show', $user->id) }}" class="block px-6 py-3 text-gray-700 hover:bg-gray-100 font-medium">
                    {{ __('📋 My 201 Tracker') }}
                </a>
            @endunless
        </div>
    </div>

    <!-- Bottom Section - Profile Dropdown -->
    <div class="border-t border-gray-200 p-4 relative" x-data="{ profileOpen: false, roleOpen: false, groupOpen: false }">
        <!-- Profile Toggle -->
        <button @click.stop="profileOpen = !profileOpen" type="button"
            class="w-full flex items-center justify-between rounded-md px-4 py-2 text-left text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <span>
                {{ Auth::user()->gender === 'Male' ? '🧑‍💼' : '👩‍💼' }} 
                {{ Auth::user()->first_name . ' ' . Auth::user()->last_name }}
            </span>
            <svg :class="{ '-rotate-90': profileOpen }"
                class="w-5 h-5 transform transition-transform duration-200" fill="none"
                stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        <!-- Profile Dropdown -->
        <div x-show="profileOpen" x-transition @click.outside="profileOpen = false" x-cloak
            class="absolute left-full top-1/2 -translate-y-1/2 ml-2 w-44 bg-white shadow-lg rounded-md z-50">

            <!-- View Profile -->
            <a href="{{ route('profile.view') }}" class="block px-4 py-2 text-gray-700 hover:bg-indigo-100">
                {{ __('Profile') }}
            </a>

            <!-- Switch Role Dropdown -->
            <div class="relative">
                <button @click.stop="roleOpen = !roleOpen" type="button"
                    class="w-full flex items-center justify-between px-4 py-2 text-gray-700 hover:bg-indigo-100">
                    {{ __('Switch Role') }}
                    <svg :class="{ 'rotate-90': roleOpen }"
                        class="w-4 h-4 transform transition-transform duration-200" fill="none"
                        stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </button>

                <div x-show="roleOpen" x-transition @click.outside="roleOpen = false" x-cloak
                    class="absolute left-full top-0 ml-2 w-40 bg-white shadow rounded z-50">
                    <form method="POST" action="{{ route('switch.role') }}">
                        @csrf
                        @foreach (\App\Models\Role::all() as $role)
                            @if ($user->role_id !== $role->id)
                                <button type="submit" name="role_id" value="{{ $role->id }}"
                                    class="w-full text-left px-4 py-2 text-gray-700 hover:bg-indigo-100">
                                    {{ $role->role }}
                                </button>
                            @endif
                        @endforeach
                    </form>
                </div>
            </div>

            <!-- Switch Group Dropdown -->
            @if (
                in_array($user->role?->role, ['Admin', 'Team Leader', 'Group Leader', 'Member']) ||
                $user->position?->position_name === 'HR'
            )
                <div class="relative">
                    <button @click.stop="groupOpen = !groupOpen" type="button"
                        class="w-full flex items-center justify-between px-4 py-2 text-gray-700 hover:bg-indigo-100">
                        {{ __('Switch Group') }}
                        <svg :class="{ 'rotate-90': groupOpen }"
                            class="w-4 h-4 transform transition-transform duration-200" fill="none"
                            stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>

                    <div x-show="groupOpen" x-transition @click.outside="groupOpen = false" x-cloak
                        class="absolute left-full top-0 ml-2 w-40 bg-white shadow rounded z-50">
                        <form method="POST" action="{{ route('switch.group') }}">
                            @csrf
                            @foreach (\App\Models\Group::all() as $group)
                                @if ($user->group_id !== $group->id)
                                    <button type="submit" name="group_id" value="{{ $group->id }}"
                                        class="w-full text-left px-4 py-2 text-gray-700 hover:bg-indigo-100">
                                        Group {{ $group->group_no }}
                                    </button>
                                @endif
                            @endforeach
                        </form>
                    </div>
                </div>
            @endif

            <!-- Logout -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full text-left px-4 py-2 text-gray-700 hover:bg-indigo-100">
                    {{ __('Log Out') }}
                </button>
            </form>
        </div>
    </div>
</nav>
