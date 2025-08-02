<!-- Add Requirement Modal -->
<div id="addRequirementModal" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center z-50">
    <div class="bg-white p-6 rounded shadow-lg w-full max-w-4xl max-h-[90vh] overflow-y-auto">
        <h2 class="text-lg font-semibold mb-4">Add Requirement</h2>
        <form action="{{ route('requirements.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="user_id" value="{{ $user->id }}">

            <div class="max-h-[80vh] overflow-y-auto pr-2">
                <!-- Requirement Type -->
                <div class="mb-4">
                    <label for="type" class="block text-sm ">Requirement Type <span class="text-red-500">*</span></label>
                    <select name="type" id="type" required onchange="toggleOtherTypeInput()" class="w-full border-gray-300 rounded mt-1 px-3 py-2" required>
                        <option value="" disabled selected>-- Select Requirement Type --</option>
                        @foreach ($allTypes as $type)
                            <option value="{{ $type }}">{{ $type }}</option>
                        @endforeach
                        <option value="Weekly Report">Weekly Report</option>
                        <option value="Turnover">Turnover</option>
                        <option value="Exit Clearance">Exit Clearance</option>
                        <option value="Others">Others</option>
                    </select>
                </div>

                <!-- Other Type Input -->
                <div class="mb-4 hidden" id="otherTypeDiv">
                    <label for="other_type" class="block text-sm ">Specify Other Requirement</label>
                    <input type="text" name="other_type" id="other_type" class="w-full border-gray-300 rounded mt-1">
                </div>

                <!-- Dynamic Fields -->
                @include('employees.partials.application-fields')
                @include('employees.partials.notification-fields')
                @include('employees.partials.turnover-fields')
                @include('employees.partials.exit-clearance-fields')
                @include('employees.partials.weekly-report-fields')

                <!-- Requires Signature -->
                <div class="mb-4 hidden" id="signatureDiv">
                    <label for="requires_signature" class="block text-sm">Requires Signature? <span class="text-red-500">*</span></label>
                    <select name="requires_signature" id="requires_signature" class="w-full border-gray-300 rounded mt-1 px-3 py-2" required>
                        <option value="Yes">Yes</option>
                        <option value="No">No</option>
                    </select>
                </div>

                <!-- File Upload -->
                <div class="mb-4" id="fileUploadDiv">
                    <label for="file" class="block text-sm">Upload File <span class="text-red-500">*</span></label>
                    
                    <div class="flex items-center space-x-4">
                        <label 
                            for="file" 
                            class="cursor-pointer px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded hover:bg-gray-700 transition"
                        > Choose File </label>
                        <span id="fileName" class="text-sm text-black-600">No file selected</span>
                    </div>
                    
                    <input type="file" name="file" id="file" accept=".pdf,.jpg,.jpeg,.png" class="hidden" required>
                </div>

                <!-- Buttons -->
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeModal('addRequirementModal')" class="px-4 py-2 bg-gray-300 rounded">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Add</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    const fileInput = document.getElementById('file');
    const fileName = document.getElementById('fileName');

    fileInput.addEventListener('change', function () {
        if (this.files.length > 0) {
            fileName.textContent = this.files[0].name;
        } else {
            fileName.textContent = 'No file selected';
        }
    });

    const typeSelect = document.getElementById('type');
    const signatureDiv = document.getElementById('signatureDiv');
    const signatureSelect = document.getElementById('requires_signature');

    typeSelect.addEventListener('change', function () {
        const selectedType = this.value;

        switch (selectedType) {
            case 'Application':
            case 'Notification':
                signatureSelect.value = 'No';
                signatureDiv.classList.add('hidden');
                break;

            case 'Turnover':
            case 'Exit Clearance':
            case 'Weekly Report':
                signatureSelect.value = 'Yes';
                signatureDiv.classList.add('hidden');
                break;

            case 'Others':
                signatureDiv.classList.remove('hidden');
                signatureSelect.value = 'Yes'; // Default value for Others
                break;

            default:
                signatureSelect.value = 'No';
                signatureDiv.classList.add('hidden');
                break;
        }
    });
</script>

