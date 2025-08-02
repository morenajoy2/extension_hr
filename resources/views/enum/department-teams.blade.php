<x-app-layout>
    {{-- Success Flash Message --}}
    @if(session('success'))
        <script>
            window.onload = function () {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: '{{ session('success') }}',
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        </script>
    @endif

    {{-- Admin Only --}}
    @if(auth()->user()->role?->role === 'Admin')
        <!-- Add Team Modal -->
        <div id="addTeamModal" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center z-50 p-4">
            <div class="bg-white p-4 rounded shadow-lg w-full max-w-xs">
                <h2 class="text-lg font-semibold mb-4">Add Department Team</h2>
                <form action="{{ route('department-teams.store') }}" method="POST">
                    @csrf
                    <input type="text" name="team_name" placeholder="Team Name" required class="w-full border-gray-300 rounded mb-3 p-2">
                    <div class="flex justify-end space-x-2">
                        <button type="button" onclick="closeModal('addTeamModal')" class="px-3 py-1 bg-gray-300 rounded text-sm">Cancel</button>
                        <button type="submit" class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-500 text-sm">Submit</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit Team Modal -->
        <div id="editTeamModal" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center z-50 p-4">
            <div class="bg-white p-4 rounded shadow-lg w-full max-w-xs">
                <h2 class="text-lg font-semibold mb-4">Edit Department Team</h2>
                <form id="editTeamForm" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="text" id="edit_team_name" name="team_name" required class="w-full border-gray-300 rounded mb-3 p-2">
                    <div class="flex justify-end space-x-2">
                        <button type="button" onclick="closeModal('editTeamModal')" class="px-3 py-1 bg-gray-300 rounded text-sm">Cancel</button>
                        <button type="submit" class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-500 text-sm">Save</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Page Heading and Add Button -->
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold text-gray-900">👨‍👩‍👧‍👦 Department Team Management</h2>
            <button onclick="openModal('addTeamModal')" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-400 text-sm">
                ➕ Add Team
            </button>
        </div>

        <!-- Team Table -->
        <div class="overflow-x-auto">
            <table class="min-w-max text-xs sm:text-sm border border-gray-200 w-full">
                <thead class="bg-gray-100 text-left text-xs sm:text-sm">
                    <tr class="bg-gray-100 text-left">
                        <th class="px-4 py-2">Team Name</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($department_teams as $team)
                        <tr class="border-t">
                            <td class="border px-4 py-2 text-xs sm:text-sm">{{ $team->team_name }}</td>
                            <td class="border px-4 py-2 text-xs sm:text-sm">
                                <div class="flex items-center gap-2">
                                    <!-- Edit Button -->
                                    <button type="button"
                                            onclick="openEditModal({{ $team->id }}, '{{ $team->team_name }}')"
                                            class="text-blue-600 hover:underline text-sm">
                                        Edit
                                    </button>

                                    <form action="{{ route('department-teams.destroy', $team->id) }}" method="POST" class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="text-red-600 hover:underline delete-btn"
                                                data-name="{{ $team->team_name }}">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="text-center text-gray-500 px-4 py-4">No teams found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @else
        <div class="text-center text-red-600 mt-8 text-sm font-semibold">
            You are not authorized to view this page.
        </div>
    @endif

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
            document.getElementById(id).classList.add('flex');
        }

        function closeModal(id) {
            document.getElementById(id).classList.remove('flex');
            document.getElementById(id).classList.add('hidden');
        }

        function openEditModal(id, name) {
            document.getElementById('edit_team_name').value = name;
            document.getElementById('editTeamForm').action = `/department-teams/${id}`;
            openModal('editTeamModal');
        }

        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function () {
                const name = this.dataset.name;
                const form = this.closest('form');

                Swal.fire({
                    title: `Are you sure you want to delete "${name}"?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e3342f',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
</x-app-layout>
