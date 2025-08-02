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

    @if(auth()->user()->role?->role === 'Admin')
        <!-- Add Modal -->
        <div id="addEmploymentModal" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center z-50 p-4">
            <div class="bg-white p-4 rounded shadow-lg w-full max-w-xs">
                <h2 class="text-lg font-semibold mb-4">Add Employment Type</h2>
                <form action="{{ route('employment-types.store') }}" method="POST">
                    @csrf
                    <input type="text" name="type_name" placeholder="Employment Type Name" required class="w-full border-gray-300 rounded mb-3 p-2">
                    <div class="flex justify-end space-x-2">
                        <button type="button" onclick="closeModal('addEmploymentModal')" class="px-3 py-1 bg-gray-300 rounded text-sm">Cancel</button>
                        <button type="submit" class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-500 text-sm">Submit</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit Modal -->
        <div id="editEmploymentModal" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center z-50 p-4">
            <div class="bg-white p-4 rounded shadow-lg w-full max-w-xs">
                <h2 class="text-lg font-semibold mb-4">Edit Employment Type</h2>
                <form id="editEmploymentForm" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="text" id="edit_type_name" name="type_name" placeholder="Employment Type Name" required class="w-full border-gray-300 rounded mb-3 p-2">
                    <div class="flex justify-end space-x-2">
                        <button type="button" onclick="closeModal('editEmploymentModal')" class="px-3 py-1 bg-gray-300 rounded text-sm">Cancel</button>
                        <button type="submit" class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-500 text-sm">Save</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Heading + Add Button -->
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold text-gray-900">💼 Employment Type Management</h1>
            <button onclick="openModal('addEmploymentModal')" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-400 text-sm">
                ➕ Add Employment Type
            </button>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-max text-xs sm:text-sm border border-gray-200 w-full">
                <thead class="bg-gray-100 text-left text-xs sm:text-sm">
                    <tr>
                        <th class="px-4 py-2">Type Name</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employmentTypes as $type)
                        <tr>
                            <td class="border px-4 py-2 text-xs sm:text-sm">{{ $type->type_name }}</td>
                            <td class="border px-4 py-2 text-xs sm:text-sm">
                                <div class="flex items-center gap-2">
                                    <!-- Edit -->
                                    <button type="button"
                                        onclick="openEditModal({{ $type->id }}, '{{ $type->type_name }}')"
                                        class="text-blue-600 hover:underline text-sm">Edit</button>

                                    <!-- Delete -->
                                    <form action="{{ route('employment-types.destroy', $type->id) }}" method="POST" class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="text-red-600 hover:underline delete-btn" data-name="{{ $type->type_name }}">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="text-center text-gray-500 px-4 py-4">No employment types found.</td>
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
            const form = document.getElementById('editEmploymentForm');
            const input = document.getElementById('edit_type_name');

            input.value = name;
            form.action = `/employment-types/${id}`;

            openModal('editEmploymentModal');
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
