<x-app-layout>
        <div class="w-5xl mx-auto bg-white shadow-md rounded-lg overflow-hidden">
            <div class="bg-blue-500 p-6 text-white flex items-center justify-between">
                <!-- Left: Photo + Info -->
                <div class="flex items-center gap-6">
                    <!-- Profile Photo Display -->
                    <div class="flex flex-col items-center gap-2">
                        <div class="w-24 h-24 relative">
                            <img src="{{ $user->photo ? asset('storage/' . $user->photo) : asset('storage/default-profile.png') }}"
                                alt="{{ $user->first_name . ' ' . $user->last_name }}"
                                class="w-24 h-24 object-cover rounded-full border-4 border-white shadow-md">
                        </div>

                        <!-- Buttons trigger modal -->
                        <div class="flex gap-4 mt-2">
                            <button onclick="openReplaceModal()" class="bg-blue-500 text-white text-sm px-4 py-1 rounded hover:bg-blue-600 transition">
                                Replace
                            </button>
                            <button onclick="openDeleteModal()" class="bg-blue-500 text-white text-sm px-4 py-1 rounded hover:bg-red-600 transition">
                                Delete
                            </button>
                        </div>
                    </div>

                    <!-- User Info -->
                    <div>
                        <h2 class="text-3xl font-bold">{{ $user->first_name }} {{ $user->last_name }}</h2>
                        <p class="mt-1 text-sm">
                            @if ($user->role?->role === 'Team Leader')
                                {{ $user->role->role }}
                            @elseif ($user->role?->role === 'Group Leader')
                                {{ $user->role->role }} - Group {{ $user->group?->group_no }}
                            @elseif ($user->role?->role === 'Member')
                                Group {{ $user->group?->group_no }} - {{ $user->role->role }}
                            @else
                                {{ $user->role?->role }}
                            @endif
                        </p>

                    </div>
                </div>

                <!-- Edit Button -->
                <a href="{{ route('profile.edit', $user->id) }}" 
                    class="bg-white text-blue-500 font-semibold py-2 px-4 rounded hover:bg-gray-100 transition duration-200">
                    Edit
                </a>
            </div>


            <div class="p-6">
                <div class="border-b">
                    <ul class="flex space-x-4">
                        <li>
                            <a href="#personal-info" class="py-2 px-4 text-gray-600 hover:text-gray-800 font-semibold" onclick="showTab('personal-info')">Personal Info</a>
                        </li>
                        <li>
                            <a href="#employment-details" class="py-2 px-4 text-gray-600 hover:text-gray-800 font-semibold" onclick="showTab('employment-details')">Employment Details</a>
                        </li>
                    </ul>
                </div>
                <div class="mt-4">
                    <!-- Personal Info -->
                    <div id="personal-info" class="tab-content">
                        <h3 class="text-xl font-semibold text-gray-800">Personal Information</h3>
                        <div class="mt-4">
                            <p class="text-gray-600"><strong>Email:</strong> {{ $user->email }}</p>
                            <p class="text-gray-600"><strong>Contact Number:</strong> {{ $user->contact_number }}</p>
                            <p class="text-gray-600"><strong>Permanent Address:</strong> {{ $user->address }}</p>
                            <p class="text-gray-600"><strong>Date of Birth:</strong> {{ \Carbon\Carbon::parse($user->birth_of_date)->format('F j, Y') }}</p>
                            <p class="text-gray-600"><strong>Gender:</strong> {{ $user->gender }}</p>
                            <p class="text-gray-600"><strong>School:</strong> {{ $user->school }}</p>
                            <p class="text-gray-600"><strong>School Address:</strong> {{ $user->school_address }}</p>
                        </div>
                    </div>

                    <!-- Employment Details -->
                    <div id="employment-details" class="tab-content hidden">
                        <h3 class="text-xl font-semibold text-gray-800">Employment Details</h3>
                        <div class="mt-4">
                            <p class="text-gray-600"><strong>Employee ID:</strong> {{ $user->employee_id }}</p>
                            <p class="text-gray-600" hidden><strong>Role:</strong> {{ $user->role?->role }}</p>                            <p class="text-gray-600" hidden><strong>Department:</strong> {{ $user->department?->department_name }}</p>
                            <p class="text-gray-600" hidden><strong>Team:</strong> {{ $user->department_team?->team_name }}</p>
                            <p class="text-gray-600"><strong>Employment Type:</strong> {{ $user->employmentType?->type_name }}</p>                            
                            <p class="text-gray-600"><strong>Status:</strong> {{ $user->status }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Replace Modal -->
        <div id="replaceModal" class="fixed inset-0 bg-black bg-opacity-70 z-[9999] items-center justify-center hidden">
            <div class="bg-white p-6 rounded-lg shadow-lg w-96">
                <h3 class="text-lg font-semibold mb-4">Replace Profile Photo</h3>

                <form action="{{ route('profile.photo.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="photo" accept="image/*" class="mb-4 block w-full">
                    <div class="flex justify-end gap-2">
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Upload</button>
                        <button type="button" onclick="closeReplaceModal()" class="text-gray-600 hover:text-gray-900">Cancel</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Delete Modal -->
        <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-70 z-[9999]  items-center justify-center hidden">
            <div class="bg-white p-6 rounded-lg shadow-lg w-96">
                <h3 class="text-lg font-semibold mb-4 text-center">Delete Profile Photo?</h3>
                <p class="text-gray-600 text-sm mb-6 text-center">This action cannot be undone.</p>

                <form action="{{ route('profile.photo.delete') }}" method="POST" class="flex justify-center gap-4">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Delete</button>
                    <button type="button" onclick="closeDeleteModal()" class="text-gray-600 hover:text-gray-900">Cancel</button>
                </form>
            </div>
        </div>
    <!-- Tab Script -->
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

        function showTab(tabId) {
            document.querySelectorAll('.tab-content').forEach(tab => tab.classList.add('hidden'));
            document.getElementById(tabId).classList.remove('hidden');
            document.querySelectorAll('ul li a').forEach(link => {
                link.classList.remove('text-gray-800');
                link.classList.add('text-gray-600');
            });
            const activeLink = document.querySelector(`a[href="#${tabId}"]`);
            if (activeLink) {
                activeLink.classList.add('text-gray-800');
            }
        }

        function openReplaceModal() {
            const modal = document.getElementById('replaceModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeReplaceModal() {
            const modal = document.getElementById('replaceModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }


        function openDeleteModal() {
            const modal = document.getElementById('deleteModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeDeleteModal() {
            const modal = document.getElementById('deleteModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }


        // Show default tab on load
        document.addEventListener("DOMContentLoaded", () => showTab('personal-info'));
    </script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</x-app-layout>
