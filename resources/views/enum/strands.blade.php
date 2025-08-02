<x-app-layout>
    @if(session('success'))
        <script>
            window.onload = () => {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: '{{ session('success') }}',
                    timer: 2000,
                    showConfirmButton: false
                });
            };
        </script>
    @endif

    <!-- Add Modal -->
    <div id="addStrandModal" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center z-50 p-4">
        <div class="bg-white p-4 rounded shadow-lg w-full max-w-xs">
            <h2 class="text-lg font-semibold mb-4">Add Strand</h2>
            <form action="{{ route('strands.store') }}" method="POST">
                @csrf
                <input type="text" name="strand_name" placeholder="Strand Name" required class="w-full border-gray-300 rounded mb-3 p-2">
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeModal('addStrandModal')" class="px-3 py-1 bg-gray-300 rounded text-sm">Cancel</button>
                    <button type="submit" class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-500 text-sm">Add</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editStrandModal" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center z-50 p-4">
        <div class="bg-white p-4 rounded shadow-lg w-full max-w-xs">
            <h2 class="text-lg font-semibold mb-4">Edit Strand</h2>
            <form id="editStrandForm" method="POST">
                @csrf
                @method('PUT')
                <input type="text" id="edit_strand_name" name="strand_name" placeholder="Strand Name" required class="w-full border-gray-300 rounded mb-3 p-2">
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeModal('editStrandModal')" class="px-3 py-1 bg-gray-300 rounded text-sm">Cancel</button>
                    <button type="submit" class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-500 text-sm">Save</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Heading and Add Button -->
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold text-gray-900">🎓 Strand Management</h1>
        <button onclick="openModal('addStrandModal')" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-400 text-sm">
            ➕ Add Strand
        </button>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="min-w-max text-xs sm:text-sm border border-gray-200 w-full">
            <thead class="bg-gray-100 text-left text-xs sm:text-sm">
                <tr>
                    <th class="px-4 py-2">Strand Name</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($strands as $strand)
                    <tr>
                        <td class="border px-4 py-2 text-xs sm:text-sm">{{ $strand->strand_name }}</td>
                        <td class="border px-4 py-2 text-xs sm:text-sm">
                            <div class="flex items-center gap-2">
                                <button onclick="openEditModal({{ $strand->id }}, '{{ $strand->strand_name }}')" class="text-blue-600 hover:underline text-sm">Edit</button>
                                <form action="{{ route('strands.destroy', $strand->id) }}" method="POST" class="delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="text-red-600 hover:underline delete-btn" data-name="{{ $strand->strand_name }}">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="text-center text-gray-500 px-4 py-4">No strands found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

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
            document.getElementById('edit_strand_name').value = name;
            document.getElementById('editStrandForm').action = `/strands/${id}`;
            openModal('editStrandModal');
        }

        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const name = this.dataset.name;
                const form = this.closest('form');

                Swal.fire({
                    title: `Delete "${name}"?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e3342f',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then(result => {
                    if (result.isConfirmed) form.submit();
                });
            });
        });
    </script>
</x-app-layout>
