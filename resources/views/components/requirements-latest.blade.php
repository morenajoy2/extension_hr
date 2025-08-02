@php
    $role = Auth::user()->role;
@endphp

<div class="mt-6 bg-white ">
    <h2 class="text-2xl font-bold text-gray-800 mb-4">📄 Latest Requirements</h2>

    <div class="overflow-x-auto">
        <div class="max-h-96 overflow-y-auto border rounded">
            <table class="min-w-full table-auto border text-sm">
                <thead class="bg-gray-100 text-left sticky top-0 z-10">
                    <tr>
                        <th class="px-4 py-2 border">ID</th>
                        <th class="px-4 py-2 border">Employees</th>
                        <th class="px-4 py-2 border">Types</th>
                        <th class="px-4 py-2 border">Upload Date</th>
                        <th class="px-4 py-2 border">Files</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($requirements as $req)
                        <tr class="hover:bg-gray-50">
                            <td class="border px-4 py-2">{{ $req->id }}</td>
                            <td class="border px-4 py-2">{{ $req->user->first_name }} {{ $req->user->last_name }}</td>
                            <td class="border px-4 py-2">
                                @if ($req->type === 'Weekly Report' && $req->weeklyReport)
                                    {{ $req->type }}
                                    (
                                    {{ \Carbon\Carbon::parse($req->weeklyReport->from_date)->format('F j, Y') }}
                                    -
                                    {{ \Carbon\Carbon::parse($req->weeklyReport->to_date)->format('F j, Y') }}
                                    )
                                @else
                                    {{ $req->type }}
                                @endif
                            </td>
                            <td class="border px-4 py-2">
                                {{ $req->upload_date ? $req->upload_date->format('Y-m-d') : '' }}
                            </td>
                            <td class="border px-4 py-2">
                                @if ($req->type === 'Weekly Report' && $req->weeklyReport)
                                    <button onclick="openWeeklyReportFiles({{ $req->id }})" class="text-indigo-600 underline">View</button>
                                @elseif ($req->type === 'Exit Clearance' && $req->exitClearance)
                                    <button onclick="openExitClearanceFiles({{ $req->id }})" class="text-indigo-600 underline">View</button>
                                @elseif ($req->type === 'Turnover' && $req->turnover?->e_signature)
                                    <a href="{{ asset('storage/' . $req->turnover->e_signature) }}" target="_blank" class="text-indigo-600 underline">View</a>
                                @elseif ($req->file)
                                    <a href="{{ asset('storage/' . $req->file) }}" target="_blank" class="text-indigo-600 underline">View</a>
                                @elseif ($req->notificationSubmission?->notifyFile)
                                    <a href="{{ asset('storage/' . $req->notificationSubmission->notifyFile) }}" target="_blank" class="text-indigo-600 underline">View</a>
                                @else
                                    <span class="text-gray-400 italic"></span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">No recent requirements found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- WEEKLY FILES --}}
<div id="weeklyReportFilesModal" class="hidden fixed inset-0 bg-black bg-opacity-50 justify-center items-center z-50">
    <div class="bg-white rounded-lg shadow-lg max-w-md w-full p-6 relative flex flex-col">
        <h2 class="text-xl font-bold mb-4">Weekly Report Files</h2>
            <div id="weeklyReportFilesContent" class="space-y-2 text-sm text-blue-600 underline">
            <!-- Dynamic content will be inserted here -->
        </div>
        <div class="mt-4 text-right">
            <button onclick="closeWeeklyReportFiles()" class="absolute top-2 right-3 text-gray-400 hover:text-red-500 text-xl font-bold">
                &times;
            </button>
        </div>
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

<script>
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
</script>
