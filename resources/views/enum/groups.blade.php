<x-app-layout>
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

    @if(session('error'))
        <script>
            window.onload = function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: '{{ session('error') }}',
                    showConfirmButton: true
                });
            }
        </script>
    @endif

    @if(auth()->user()->role?->role === 'Admin')
        <!-- Add Group Modal -->
        <div id="addGroupModal" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center z-50 p-4">
            <div class="bg-white p-3 rounded shadow-lg w-full max-w-xs">
                <h2 class="text-lg font-semibold mb-4">Add Group</h2>
                <form action="{{ route('groups.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="group_no" class="block text-sm font-medium">Group Number</label>
                        <input type="number" name="group_no" id="group_no" required
                               class="w-full border-gray-300 rounded mt-1 px-2 py-1 text-sm" min="1" />
                    </div>

                    <div class="flex justify-end space-x-2">
                        <button type="button" onclick="closeModal('addGroupModal')"
                                class="px-3 py-1 bg-gray-300 rounded text-sm">Cancel</button>
                        <button type="submit"
                                class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-500 text-sm">Submit</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Page Content -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 space-y-2 sm:space-y-0">
            <h1 class="text-2xl font-bold text-gray-900">👨‍👨‍👦 Group Management</h1>
            <button onclick="openModal('addGroupModal')"
                    class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-400 text-sm">
                ➕ Add Group
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-max text-xs sm:text-sm border border-gray-200 w-full">
                <thead class="bg-gray-100 text-left text-xs sm:text-sm">
                    <tr>
                        <th class="py-1 px-3">Group No</th>
                        <th class="py-1 px-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($groups as $group)
                        <tr>
                            <td class="border px-4 py-2 text-xs sm:text-sm">Group {{ $group->group_no }}</td>
                            <td class="border px-4 py-2 text-xs sm:text-sm">
                                <form action="{{ route('groups.destroy', $group->id) }}" method="POST" class="delete-group-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                        class="text-red-600 hover:underline cursor-pointer delete-group-btn"
                                        data-group-id="{{ $group->id }}"
                                        data-group-no="{{ $group->group_no }}"
                                        style="background:none; border:none; padding:0; font-size: 0.875rem;">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="py-4 px-3 text-center text-gray-500 text-sm">No groups found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @else
        <div class="text-red-600 text-center mt-8 font-semibold text-sm">You are not authorized to view this page.</div>
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

        // SweetAlert2 delete confirmation
        document.querySelectorAll('.delete-group-btn').forEach(button => {
            button.addEventListener('click', function () {
                const groupNo = this.dataset.groupNo;
                const form = this.closest('form');

                Swal.fire({
                    title: `Are you sure you want to delete Group ${groupNo}?`,
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
