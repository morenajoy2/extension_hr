<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center pb-4">
            <div>
                📁 201 Tracker: {{ $user->first_name . ' ' . $user->last_name }}
            </div>
                <button onclick="openModal('addRequirementModal')" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-400 text-sm">
                    ➕ Add Requirement
                </button>
        </div>
    </x-slot>

    @include('employees.partials.add')

    <!-- Requirements Table -->
    <div class="overflow-x-auto">
        <table class="min-w-max text-xs sm:text-sm border border-gray-200 mt-4 w-full">
            <thead class="bg-gray-100 text-left text-xs sm:text-sm">
                <tr>
                    <th class="px-4 py-2 border">Requirement Types</th>
                    <th class="px-4 py-2 border">Details</th>
                    <th class="px-4 py-2 border">File Status</th>
                    <th class="px-4 py-2 border">Uploaded File</th>
                    <th class="px-4 py-2 border">Upload Date</th>
                    <th class="px-4 py-2 border">Requires Signature</th>
                    <th class="px-4 py-2 border">Signature Status</th>
                    <th class="px-4 py-2 border">Signed Date</th>
                    <th class="px-4 py-2 border">Signed File</th>
                    <th class="px-4 py-2 border">Action</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $filteredTypes = array_filter($allTypes, fn($type) => $type !== 'Weekly Report');
                @endphp

                @foreach ($filteredTypes as $type)
                    @php
                        $req = $requirements->firstWhere('type', $type);
                    @endphp
                    <tr>
                        <td class="border px-4 py-2 text-xs sm:text-sm">{{ $type }}</td>

                        <!-- View Detail Button -->
                        <td class="border px-4 py-2 text-xs sm:text-sm">
                            @php
                                $isNotification = in_array($type, ['25% Notification', '50% Notification', '75% Notification', '100% Notification']);
                                $isApplication = in_array($type, [
                                    'Resume', 'Photo ID', 'Workstation Photo', 'Internet Speed Photo',
                                    'PC Specification Photo', 'School ID', 'Signed Consent',
                                    'Valid ID Signed Consent', 'Endorsement Letter', 'MOA'
                                ]);
                                $hasDetails = $req &&
                                    (!$isNotification || ($req->notificationSubmission ?? false)) &&
                                    (!$isApplication || ($req->file !== null));
                            @endphp

                            @if ($hasDetails)
                                <button 
                                    class="text-blue-600 hover:underline whitespace-nowrap"
                                    onclick="openRequirementDetails('{{ $req->type }}', {{ $req->id }})">
                                    View 
                                </button>
                            @else
                                <span class="text-gray-400"></span>
                            @endif
                        </td>

                        <!-- Upload Status -->
                        <td class="border px-4 py-2 text-xs sm:text-sm">
                            @if ($req && $req->status === 'Incomplete')
                                ✅ Uploaded
                            @else
                                ❌ Not Uploaded
                            @endif
                        </td>

                        <!-- Uploaded File -->
                        <td class="border px-4 py-2 text-xs sm:text-sm">
                            @if ($req && $req->file)
                                <a href="{{ asset('storage/' . $req->file) }}" target="_blank"
                                    class="block max-w-[200px] truncate text-blue-600 hover:underline"
                                    title="{{ basename($req->file) }}">
                                    {{ basename($req->file) }}
                                </a>
                            @elseif (!empty($req->notificationSubmission?->notifyFile))
                                <a href="{{ asset('storage/' . $req->notificationSubmission->notifyFile) }}" target="_blank"
                                    class="block max-w-[200px] truncate text-blue-600 hover:underline"
                                    title="{{ basename($req->notificationSubmission->notifyFile) }}">
                                    {{ basename($req->notificationSubmission->notifyFile) }}
                                </a>
                            @endif
                        </td>

                        <!-- Upload Date -->
                        <td class="border px-4 py-2 text-xs sm:text-sm">
                            {{ $req && $req->upload_date ? $req->upload_date->format('Y-m-d') : '' }}
                        </td>

                        <!-- Requires Signature: ALWAYS show -->
                        <td class="border px-4 py-2 text-xs sm:text-sm">
                            @if ($req && strtolower($req->requires_signature) === 'yes')
                                Yes
                            @else
                                {{-- Display blank when it's "No" or null --}}
                                <span class="text-gray-400"></span>
                            @endif
                        </td>

                        <!-- Signature Status: show if requires signature -->
                        <td class="border px-4 py-2 text-xs sm:text-sm">
                            @if ($req && strtolower($req->requires_signature) === 'yes')
                                {{ $req->signature_status ?? 'Unsigned' }}
                            @endif
                        </td>

                        <!-- Signed Date: show if Admin/HR or file exists -->
                        <td class="border px-4 py-2 text-xs sm:text-sm">
                            @if ($req && ($req->signed_file || Auth::user()->role->role === 'Admin' || Auth::user()->position->position_name === 'HR'))
                                {{ $req->signed_date ? $req->signed_date->format('Y-m-d') : '' }}
                            @endif
                        </td>

                        <!-- Signed File -->
                        <td class="border px-4 py-2 text-xs sm:text-sm">
                            @if ($req && $req->signed_file)
                                <a href="{{ asset('storage/' . $req->signed_file) }}" target="_blank"
                                    class="block max-w-[200px] truncate text-blue-600 hover:underline"
                                    title="{{ basename($req->signed_file) }}">
                                    {{ basename($req->signed_file) }}
                                </a>
                            @endif
                        </td>

                        <!-- Actions -->
                        <td class="border px-4 py-2 text-xs sm:text-sm">
                            <div class="flex items-center gap-2">
                                @if ($req)
                                    {{-- Add/Replace Signed File: Only if requires_signature = Yes AND user is Admin/HR --}}
                                    @if (
                                        strtolower($req->requires_signature) === 'yes' &&
                                        (optional(Auth::user()->role)->role === 'Admin' || optional(Auth::user()->position)->position_name === 'HR')
                                    )
                                        <button onclick="openSignedModal({{ $req->id }})"
                                            class="text-blue-600 hover:underline whitespace-nowrap">
                                            {{ $req->signed_file ? 'Replace Signed File' : 'Add Signed File' }}
                                        </button>
                                    @endif

                                    {{-- Show Delete if any file (main file or notifyFile) exists --}}
                                    @if ($req->file || $req->notificationSubmission?->notifyFile)
                                        <button onclick="openDeleteModal({{ $req->id }})"
                                            class="text-red-600 hover:underline whitespace-nowrap">
                                            Delete
                                        </button>
                                    @endif
                                @endif
                            </div>
                        </td>

                    </tr>
                @endforeach

                {{-- WEEKLY REPORT TABLE (multi) --}}
                @foreach ($weeklyRequirements as $weeklyRequirement)
                    @php
                        $weeklyData = $weeklyRequirement->weeklyReport;
                        $dateRange = '';

                        if ($weeklyData) {
                            $from = \Carbon\Carbon::parse($weeklyData->from_date)->format('F j, Y');
                            $to = \Carbon\Carbon::parse($weeklyData->to_date)->format('F j, Y');
                            $dateRange = " ($from - $to)";
                        }
                    @endphp

                    <tr>
                        <td class="border px-4 py-2 text-xs sm:text-sm">Weekly Report{{ $dateRange }}</td>
                        <td class="border px-4 py-2 text-xs sm:text-sm">
                            <button 
                                onclick="openRequirementDetails('{{ $weeklyRequirement->type }}', {{ $weeklyRequirement->id }})"
                                class="text-blue-600 hover:underline whitespace-nowrap">
                                View 
                            </button>
                        </td>
                        <td class="border px-4 py-2 text-xs sm:text-sm">
                            @if ($weeklyRequirement->status === 'Pending' || $weeklyRequirement->status === 'Completed')
                                ✅ Uploaded
                            @else
                                ❌ Not Uploaded
                            @endif
                        </td>
                        <td class="border px-4 py-2 text-xs sm:text-sm">
                            <button onclick="openWeeklyReportFiles({{ $weeklyRequirement->id }})"
                                class="text-blue-600 hover:underline">
                                View Files
                            </button>
                        </td>
                        <td class="border px-4 py-2 text-xs sm:text-sm">
                            {{ $weeklyRequirement->upload_date ? $weeklyRequirement->upload_date->format('Y-m-d') : '' }}
                        </td>
                        <td class="border px-4 py-2 text-xs sm:text-sm">
                            {{ $weeklyRequirement->requires_signature === 'Yes' ? 'Yes' : 'No' }}
                        </td>
                        <td class="border px-4 py-2 text-xs sm:text-sm">
                            {{ $weeklyRequirement->signature_status ?? 'Unsigned' }}
                        </td>
                        <td class="border px-4 py-2 text-xs sm:text-sm">
                            @if ($weeklyRequirement->signed_file || Auth::user()->role->role === 'Admin' || Auth::user()->position->position_name === 'HR')
                                {{ $weeklyRequirement->signed_date ? $weeklyRequirement->signed_date->format('Y-m-d') : '' }}
                            @endif
                        </td>
                        <td class="border px-4 py-2 text-xs sm:text-sm">
                            @if ($weeklyRequirement->signed_file || Auth::user()->role->role === 'Admin' || Auth::user()->position->position_name === 'HR')
                                @if ($weeklyRequirement->signed_file)
                                    <a href="{{ asset('storage/' . $weeklyRequirement->signed_file) }}"
                                    target="_blank"
                                    class="block max-w-[200px] truncate text-blue-600 hover:underline"
                                    title="{{ basename($weeklyRequirement->signed_file) }}">
                                        {{ basename($weeklyRequirement->signed_file) }}
                                    </a>
                                @endif
                            @endif
                        </td>
                        <td class="border px-4 py-2 text-xs sm:text-sm">
                            <div class="flex items-center gap-2">
                                @if ($weeklyRequirement->weeklyReport && ($weeklyRequirement->weeklyReport->doc_upload || $weeklyRequirement->weeklyReport->pdf_upload))
                                    {{-- Signed file button (only if requires_signature is "Yes" and user is Admin or HR) --}}
                                    @if (
                                        strtolower($weeklyRequirement->requires_signature) === 'yes' &&
                                        (optional(Auth::user()->role)->role === 'Admin' || optional(Auth::user()->position)->position_name === 'HR')
                                    )
                                        <button onclick="openSignedModal({{ $weeklyRequirement->id }})" class="text-blue-600 hover:underline whitespace-nowrap">
                                            {{ $weeklyRequirement->signed_file ? 'Replace Signed File' : 'Add Signed File' }}
                                        </button>
                                    @endif

                                    {{-- Delete button (applies if either file exists) --}}
                                    <button onclick="openDeleteModal({{ $weeklyRequirement->id }})" class="text-red-600 hover:underline whitespace-nowrap">
                                        Delete
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach

                {{-- TURNOVER --}}
                @foreach ($turnovers as $turnoverRequirement)
                    @php
                        $turnoverData = $turnoverRequirement->turnover;
                    @endphp

                    @if ($turnoverData)
                        <tr>
                            <td class="border px-4 py-2 text-xs sm:text-sm">Turnover</td>
                            <td class="border px-4 py-2 text-xs sm:text-sm">
                                <button 
                                    onclick="openRequirementDetails('Turnover', {{ $turnoverRequirement->id }})"
                                    class="text-blue-600 hover:underline whitespace-nowrap">
                                    View 
                                </button>
                            </td>
                            <td class="border px-4 py-2 text-xs sm:text-sm">
                                ✅ Uploaded
                            </td>
                            <td class="border px-4 py-2 text-xs sm:text-sm text-gray-600">
                                @if (!empty($turnoverData->e_signature))
                                    <a href="{{ asset('storage/' . $turnoverData->e_signature) }}"
                                    target="_blank"
                                    class="text-blue-600 hover:underline truncate max-w-[200px]"
                                    title="{{ basename($turnoverData->e_signature) }}">
                                        {{ basename($turnoverData->e_signature) }}
                                    </a>
                                @else
                                    <span class="text-gray-400 italic">No File</span>
                                @endif
                            </td>
                            <td class="border px-4 py-2 text-xs sm:text-sm">
                                {{ $turnoverRequirement->upload_date ? $turnoverRequirement->upload_date->format('Y-m-d') : '' }}
                            </td>
                            <td class="border px-4 py-2 text-xs sm:text-sm">
                                {{ $turnoverRequirement->requires_signature === 'Yes' ? 'Yes' : 'No' }}
                            </td>
                            <td class="border px-4 py-2 text-xs sm:text-sm">
                                {{ $turnoverRequirement->signature_status ?? 'Unsigned' }}
                            </td>
                            <td class="border px-4 py-2 text-xs sm:text-sm">
                                {{ $turnoverRequirement->signed_date ? $turnoverRequirement->signed_date->format('Y-m-d') : '' }}
                            </td>
                            <td class="border px-4 py-2 text-xs sm:text-sm">
                                @if ($turnoverRequirement->signed_file)
                                    <a href="{{ asset('storage/' . $turnoverRequirement->signed_file) }}"
                                    target="_blank"
                                    class="text-blue-600 hover:underline truncate max-w-[200px]"
                                    title="{{ basename($turnoverRequirement->signed_file) }}">
                                        {{ basename($turnoverRequirement->signed_file) }}
                                    </a>
                                @endif
                            </td>
                            <td class="border px-4 py-2 text-xs sm:text-sm">
                                <div class="flex items-center gap-2">
                                    @if (
                                        strtolower($turnoverRequirement->requires_signature) === 'yes' &&
                                        (Auth::user()->role->role === 'Admin' || Auth::user()->position->position_name === 'HR')
                                    )
                                        <button onclick="openSignedModal({{ $turnoverRequirement->id }})"
                                                class="text-blue-600 hover:underline whitespace-nowrap">
                                            {{ $turnoverRequirement->signed_file ? 'Replace Signed File' : 'Add Signed File' }}
                                        </button>
                                    @endif

                                    <button onclick="openDeleteModal({{ $turnoverRequirement->id }})"
                                            class="text-red-600 hover:underline whitespace-nowrap">
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endif
                @endforeach

                {{-- EXIT CLEARANCE --}}
                @foreach ($exitClearances as $exitRequirement)
                    @php
                        $exitData = $exitRequirement->exitClearance;
                    @endphp

                    @if ($exitData)
                        <tr>
                            <td class="border px-4 py-2 text-xs sm:text-sm">Exit Clearance</td>
                            <td class="border px-4 py-2 text-xs sm:text-sm">
                                <button 
                                    onclick="openRequirementDetails('Exit Clearance', {{ $exitRequirement->id }})"
                                    class="text-blue-600 hover:underline whitespace-nowrap">
                                    View 
                                </button>
                            </td>
                            <td class="border px-4 py-2 text-xs sm:text-sm">
                                ✅ Uploaded
                            </td>
                            <td class="border px-4 py-2 text-xs sm:text-sm">
                                <button onclick="openExitClearanceFiles({{ $exitRequirement->id }})"
                                        class="text-blue-600 hover:underline">
                                    View Files
                                </button>
                            </td>
                            <td class="border px-4 py-2 text-xs sm:text-sm">
                                {{ $exitRequirement->upload_date ? $exitRequirement->upload_date->format('Y-m-d') : '' }}
                            </td>
                            <td class="border px-4 py-2 text-xs sm:text-sm">
                                {{ $exitRequirement->requires_signature === 'Yes' ? 'Yes' : 'No' }}
                            </td>
                            <td class="border px-4 py-2 text-xs sm:text-sm">
                                {{ $exitRequirement->signature_status ?? 'Unsigned' }}
                            </td>
                            <td class="border px-4 py-2 text-xs sm:text-sm">
                                {{ $exitRequirement->signed_date ? $exitRequirement->signed_date->format('Y-m-d') : '' }}
                            </td>
                            <td class="border px-4 py-2 text-xs sm:text-sm">
                                @if ($exitRequirement->signed_file)
                                    <a href="{{ asset('storage/' . $exitRequirement->signed_file) }}"
                                    target="_blank"
                                    class="text-blue-600 hover:underline truncate max-w-[200px]"
                                    title="{{ basename($exitRequirement->signed_file) }}">
                                        {{ basename($exitRequirement->signed_file) }}
                                    </a>
                                @endif
                            </td>
                            <td class="border px-4 py-2 text-xs sm:text-sm">
                                <div class="flex items-center gap-2">
                                    @if (
                                        strtolower($exitRequirement->requires_signature) === 'yes' &&
                                        (Auth::user()->role->role === 'Admin' || Auth::user()->position->position_name === 'HR')
                                    )
                                        <button onclick="openSignedModal({{ $exitRequirement->id }})"
                                                class="text-blue-600 hover:underline whitespace-nowrap">
                                            {{ $exitRequirement->signed_file ? 'Replace Signed File' : 'Add Signed File' }}
                                        </button>
                                    @endif

                                    <button onclick="openDeleteModal({{ $exitRequirement->id }})"
                                            class="text-red-600 hover:underline whitespace-nowrap">
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endif
                @endforeach

                <!-- Optional/Others -->
                @php
                    $otherRequirements = $requirements->filter(function($req) use ($allTypes) {
                        return !in_array($req->type, $allTypes) && !in_array($req->type, ['Weekly Report', 'Exit Clearance', 'Turnover']);
                    });
                @endphp

                @foreach ($otherRequirements as $req)
                    <tr>
                        <td class="border px-4 py-2 text-xs sm:text-sm">{{ $req->type }}</td>
                        <td class="border px-4 py-2 text-xs sm:text-sm">
                            {{-- blank --}}
                        </td>
                        <td class="border px-4 py-2 text-xs sm:text-sm">
                            @if ($req->status === 'Incomplete')
                                ✅ Uploaded
                            @else
                                ❌ Not Uploaded
                            @endif
                        </td>
                        <td class="border px-4 py-2 text-xs sm:text-sm">
                            @if ($req->file)
                                <a href="{{ asset('storage/' . $req->file) }}"
                            target="_blank"
                            class="block max-w-[200px] truncate text-blue-600 hover:underline"
                            title="{{ basename($req->file) }}">
                                {{ basename($req->file) }}
                            </a>
                            @else
                                {{-- blank --}}
                            @endif
                        </td>
                        <td class="border px-4 py-2 text-xs sm:text-sm">{{ $req->upload_date ? $req->upload_date->format('Y-m-d') : '' }}</td>
                        
                        <!-- Requires Signature -->
                        <td class="border px-4 py-2 text-xs sm:text-sm">
                            {{ $req && $req->file ? ($req->requires_signature) : '' }}
                        </td>

                        <td class="border px-4 py-2 text-xs sm:text-sm">
                            {{ $req && $req->file ? ($req->signature_status ?? '') : '' }}
                        </td>

                        <!-- Signed Date (visible only if signed file exists or user is HR/Admin) -->
                        <td class="border px-4 py-2 text-xs sm:text-sm">
                            @if ($req && $req->signed_file || Auth::user()->role->role === 'Admin' || Auth::user()->position->position_name === 'HR')
                                {{ $req && $req->signed_date ? $req->signed_date->format('Y-m-d') : '' }}
                            @else
                                {{-- blank --}}
                            @endif
                        </td>

                        <!-- Signed File Link (visible only if signed file exists or user is HR/Admin) -->
                        <td class="border px-4 py-2 text-xs sm:text-sm">
                            @if ($req && $req->signed_file || Auth::user()->role->role === 'Admin' || Auth::user()->position->position_name === 'HR')
                                @if ($req && $req->signed_file)
                                    <a href="{{ asset('storage/' . $req->signed_file) }}"
                                    target="_blank"
                                    class="block max-w-[200px] truncate text-blue-600 hover:underline"
                                    title="{{ basename($req->signed_file) }}">
                                        {{ basename($req->signed_file) }}
                                    </a>
                                @else
                                    {{-- blank --}}
                                @endif
                            @else
                                    {{-- blank --}}
                            @endif
                        </td>

                        <!-- Actions -->
                        <td class="border px-4 py-2 text-xs sm:text-sm">
                            <div class="flex items-center gap-2">
                                @if ($req && $req->file)
                                    @if ($req && $req->file && strtolower($req->requires_signature) === 'yes' &&
                                        (optional(Auth::user()->role)->role === 'Admin' || optional(Auth::user()->position)->position_name === 'HR')
                                    )
                                        <button onclick="openSignedModal({{ $req->id }})" class="text-blue-600 hover:underline whitespace-nowrap">
                                            {{ $req->signed_file ? 'Replace Signed File' : 'Add Signed File' }}
                                        </button>
                                    @endif

                                    <button onclick="openDeleteModal({{ $req->id }})" class="text-red-600 hover:underline whitespace-nowrap">Delete</button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div id="requirementDetailModal" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center z-50 min-h-screen">
        <div class="bg-white p-6 rounded shadow-lg w-full max-w-4xl max-h-[90vh] overflow-y-auto relative">
            <button onclick="closeRequirementModal()" class="absolute top-2 right-4 text-gray-500 hover:text-red-500 text-xl">&times;</button>
            <div id="requirementDetailContent"></div>
        </div>
    </div>

    <!-- Exit Clearance Files Modal -->
    <div id="exitClearanceFilesModal" class="hidden fixed inset-0 bg-black bg-opacity-50 justify-center items-center z-50">
        <div class="bg-white rounded-lg shadow-lg max-w-md w-full p-6 relative flex flex-col">
            <h2 class="text-xl font-bold mb-4">Exit Clearance Files</h2>
            <div id="exitClearanceFilesContent" class="space-y-2 text-sm text-blue-600 underline">
                <!-- File links will be injected here -->
            </div>
            <button onclick="closeExitClearanceFiles()" class="absolute top-2 right-3 text-gray-400 hover:text-red-500 text-xl font-bold">
                &times;
            </button>
        </div>
    </div>

    <!-- Weekly Report Files Modal -->
    @include('employees.partials.weekly-files')

    @include('employees.partials.delete')
    @include('employees.partials.signed-file')

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: '{{ session('success') }}',
                    timer: 2000,
                    showConfirmButton: false
                });
            @endif

            @if($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    html: `{!! implode('<br>', $errors->all()) !!}`,
                    showConfirmButton: true
                });
            @endif

            @if($errors->has('duplicate'))
                Swal.fire({
                    icon: 'error',
                    title: 'Duplicate Upload',
                    html: {!! json_encode(
                        preg_replace(
                            '/"(.+?)"/',
                            '<strong>$1</strong>',
                            $errors->first('duplicate')
                        )
                    ) !!},
                    showConfirmButton: true
                });
            @endif
        });
        
        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
            document.getElementById(id).classList.add('flex');
        }

        function closeModal(id) {
            document.getElementById(id).classList.remove('flex');
            document.getElementById(id).classList.add('hidden');
        }

        function openDeleteModal(id) {
            const form = document.getElementById('deleteForm');
            form.action = `/201/${id}/delete`;
            openModal('deleteModal');
        }

        function openSignedModal(id) {
            const form = document.getElementById('signedForm');
            form.action = `/201/${id}/signed`; // Route to handle signed file
            openModal('signedModal');
        }

        const applicationTypes = [
            'Resume', 'Photo ID', 'Workstation Photo', 'Internet Speed Photo',
            'PC Specification Photo', 'School ID', 'Signed Consent', 'Valid ID Signed Consent',
            'Endorsement Letter', 'MOA'
        ];

        function toggleApplicationInputs() {
            const selected = document.getElementById('type').value;
            const appFields = document.getElementById('applicationInputs');
            const show = applicationTypes.includes(selected);
            appFields.classList.toggle('hidden', !applicationTypes.includes(selected));

            // Select the fields inside #applicationInputs
            const requiredFields = appFields.querySelectorAll('input');

            requiredFields.forEach(input => {
                if (show) {
                    input.setAttribute('required', 'required');
                } else {
                    input.removeAttribute('required');
                }
            });
        }

        const notificationTypes = [
            '25% Notification', '50% Notification', '75% Notification', '100% Notification'
        ];

        function toggleNotificationInputs() {
            const selected = document.getElementById('type').value;
            const notifDiv = document.getElementById('notificationInputs');
            const percentInput = document.getElementById('percent_completed');
            const percentLabel = document.getElementById('percentLabel');

            const shouldShow = notificationTypes.includes(selected);
            notifDiv.classList.toggle('hidden', !shouldShow);

            // Enable or disable all input/selects inside notification section
            const fields = notifDiv.querySelectorAll('input, select, textarea');

            fields.forEach(field => {
                if (shouldShow) {
                    field.removeAttribute('disabled');
                    if (field.dataset.required === "true") {
                        field.setAttribute('required', 'required');
                    }
                } else {
                    field.setAttribute('disabled', 'disabled');
                    if (field.hasAttribute('required')) {
                        field.dataset.required = "true";
                        field.removeAttribute('required');
                    }
                }
            });

            if (shouldShow) {
                const percent = selected.split('%')[0]; // Get '25' from '25% Notification'
                percentInput.value = percent;
                percentLabel.textContent = percent + '%';
            } else {
                percentInput.value = '';
                percentLabel.textContent = '';
            }
        }

        const weeklyReportTypes = ['Weekly Report'];

        function toggleWeeklyReportInputs() {
            const selected = document.getElementById('type').value;
            const exitDiv = document.getElementById('weeklyReportInputs');
            const shouldShow = selected === 'Weekly Report';

            exitDiv.classList.toggle('hidden', !shouldShow);

            const fields = exitDiv.querySelectorAll('input, select, textarea');

            fields.forEach(field => {
                if (shouldShow) {
                    field.removeAttribute('disabled');
                    if (field.dataset.required === "true") {
                        field.setAttribute('required', 'required');
                    }
                } else {
                    field.setAttribute('disabled', 'disabled');
                    if (field.hasAttribute('required')) {
                        field.dataset.required = "true";
                        field.removeAttribute('required');
                    }
                }
            });
        }

        const exitClearanceTypes = ['Exit Clearance'];

        function toggleExitClearanceInputs() {
            const typeElement = document.getElementById('type');
            const exitSection = document.getElementById('exitClearanceInputs');

            // If either element is missing, exit silently
            if (!typeElement || !exitSection) return;

            const selectedType = typeElement.value;
            const shouldShow = selectedType === 'Exit Clearance';

            // Show or hide the exit clearance section
            exitSection.classList.toggle('hidden', !shouldShow);

            // Get all fields inside the exit clearance section
            const inputs = exitSection.querySelectorAll('input, select, textarea');

            inputs.forEach(input => {
                if (shouldShow) {
                    // Re-apply required if previously saved as originally required
                    if (input.dataset.originalRequired === "true") {
                        input.setAttribute('required', 'required');
                    }
                    input.removeAttribute('disabled');
                } else {
                    // Save the original required state for restoration later
                    if (input.hasAttribute('required')) {
                        input.dataset.originalRequired = "true";
                    }
                    input.removeAttribute('required');
                    input.setAttribute('disabled', 'disabled');
                }
            });
        }

        const turnoverTypes = ['Turnover'];

        function toggleTurnoverInputs() {
            const type = document.getElementById('type').value;
            const turnoverDiv = document.getElementById('turnoverInputs');
            const shouldShow = turnoverTypes.includes(type);

            turnoverDiv.classList.toggle('hidden', !shouldShow);

            const fields = turnoverDiv.querySelectorAll('input, select, textarea');

            fields.forEach(field => {
                if (shouldShow) {
                    field.removeAttribute('disabled');
                    if (field.dataset.required === "true") {
                        field.setAttribute('required', 'required');
                    }
                } else {
                    field.setAttribute('disabled', 'disabled');
                    if (field.hasAttribute('required')) {
                        field.dataset.required = "true";
                        field.removeAttribute('required');
                    }
                }
            });
        }

        function toggleOtherTypeInput() {
            const type = document.getElementById('type').value;
            const otherDiv = document.getElementById('otherTypeDiv');
            const fileUploadDiv = document.getElementById('fileUploadDiv');
            const fileInput = document.getElementById('file');
            const weeklyReportDiv = document.getElementById('weeklyReportInputs');

            otherDiv.classList.toggle('hidden', type !== 'Others');
             weeklyReportDiv.classList.toggle('hidden', type !== 'Weekly Report');

            // Determine if file upload should be shown or hidden
            const showFileUpload = (
                applicationTypes.includes(type) ||
                type === 'Others'  
            );

            fileUploadDiv.classList.toggle('hidden', !showFileUpload);

            if (showFileUpload) {
                fileInput.removeAttribute('disabled');
                fileInput.setAttribute('required', 'required');
            } else {
                fileInput.setAttribute('disabled', 'disabled');
                fileInput.removeAttribute('required');
            }

            toggleApplicationInputs();
            toggleNotificationInputs();
            toggleWeeklyReportInputs();
            toggleExitClearanceInputs();
            toggleTurnoverInputs();
        }

        window.onload = function () {
            toggleOtherTypeInput();
            toggleNotificationInputs();
            toggleApplicationInputs();
            toggleWeeklyReportInputs();
            toggleExitClearanceInputs();
            toggleTurnoverInputs();
        };

        function openRequirementDetails(type, id) {
            const modal = document.getElementById('requirementDetailModal');
            const content = document.getElementById('requirementDetailContent');

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            content.innerHTML = '<p class="text-gray-500">Loading details...</p>';

            fetch(`/requirements/${id}/details`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error("Failed to load details");
                    }
                    return response.text();
                })
                .then(html => {
                    content.innerHTML = html;
                })
                .catch(error => {
                    content.innerHTML = `<p class="text-red-600">Error loading details: ${error.message}</p>`;
                });
        }

        function closeRequirementModal() {
            const modal = document.getElementById('requirementDetailModal');
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }

        document.addEventListener('DOMContentLoaded', function () {
            const typeSelect = document.querySelector('select[name="type"]');
            const exitClearanceFields = document.getElementById('exitClearanceInputs');

            function toggleFields() {
                const selected = typeSelect.value;
                if (selected === 'Exit Clearance') {
                    exitClearanceFields.classList.remove('hidden');
                } else {
                    exitClearanceFields.classList.add('hidden');
                }
            }

            typeSelect.addEventListener('change', toggleFields);
            toggleFields(); // initialize on page load
        });

        function openExitClearanceFiles(requirementId) {
        fetch(`/requirements/${requirementId}/exit-clearance/files`)
            .then(response => response.json())
            .then(data => {
                const modal = document.getElementById('exitClearanceFilesModal');
                const content = document.getElementById('exitClearanceFilesContent');
                content.innerHTML = '';

                if (data.success && data.files.length > 0) {
                    data.files.forEach(file => {
                        const button = document.createElement('button');
                        button.textContent = file.label;
                        button.className = 'bg-blue-500 hover:bg-blue-600 text-white font-semibold py-1 px-3 rounded m-1';
                        button.onclick = () => window.open(file.url, '_blank');
                        content.appendChild(button);
                    });
                } else {
                    content.innerHTML = '<p class="text-red-500">No files found.</p>';
                }

                modal.classList.remove('hidden');
                modal.classList.add('flex');
            })
            .catch(error => {
                console.error('Error fetching files:', error);
                const modal = document.getElementById('exitClearanceFilesModal');
                document.getElementById('exitClearanceFilesContent').innerHTML = '<p class="text-red-500">Failed to load files.</p>';
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            });
        }

        function closeExitClearanceFiles() {
            const modal = document.getElementById('exitClearanceFilesModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function openWeeklyReportFiles(requirementId) {
            fetch(`/requirements/${requirementId}/weekly-report/files`)
                .then(response => response.json())
                .then(data => {
                    const modal = document.getElementById('weeklyReportFilesModal');
                    const content = document.getElementById('weeklyReportFilesContent');
                    content.innerHTML = '';

                    if (data.success && data.files.length > 0) {
                        data.files.forEach(file => {
                            const button = document.createElement('button');
                            button.textContent = file.label;
                            button.className = 'bg-blue-500 hover:bg-blue-600 text-white font-semibold py-1 px-3 rounded m-1';
                            button.onclick = () => window.open(file.url, '_blank');
                            content.appendChild(button);
                        });
                    } else {
                        content.innerHTML = '<p class="text-red-500">No files found.</p>';
                    }

                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                })
                .catch(error => {
                    console.error('Error fetching files:', error);
                    const modal = document.getElementById('weeklyReportFilesModal');
                    document.getElementById('weeklyReportFilesContent').innerHTML = '<p class="text-red-500">Failed to load files.</p>';
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                });
        }

        function closeWeeklyReportFiles() {
            const modal = document.getElementById('weeklyReportFilesModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>
</x-app-layout>