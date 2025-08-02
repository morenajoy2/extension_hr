<!-- Notification Form Extra Fields -->
<div id="notificationInputs" class="hidden mb-4">
    <div class="grid grid-cols-2 gap-4">
        <!-- Employee ID -->
        <div>
            <label class="block text-sm">Employee ID <span class="text-red-500">*</span></label>
            <input type="number" class="w-full rounded border-gray-300 bg-gray-100" value="{{ $user->employee_id }}" required>
        </div>

        <!-- Full Name -->
        <div>
            <label class="block text-sm">Full Name <span class="text-red-500">*</span></label>
            <input type="text" class="w-full rounded border-gray-300 bg-gray-100" value="{{ $user->first_name }} {{ $user->last_name }}" required>
        </div>

        <!-- Email -->
        <div>
            <label class="block text-sm">Email <span class="text-red-500">*</span></label>
            <input type="text" class="w-full rounded border-gray-300 bg-gray-100" value="{{ $user->email }}" required>
        </div>

        <!-- Department -->
        <div>
            <label class="block text-sm">Department <span class="text-red-500">*</span></label>
            <select class="w-full rounded border-gray-300 px-3 py-2" required>
                <option value="" disabled selected>-- Select Department --</option>
                @foreach ($departments as $department)
                    <option value="{{ $department->id }}" {{ $user->department_id == $department->id ? 'selected' : '' }}>
                        {{ $department->department_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Group Assigned -->
        <div>
            <label class="block text-sm">Group Assigned <span class="text-red-500">*</span></label>
            <select name="group_id" class="w-full rounded border-gray-300 px-3 py-2" required>
                <option value="" disabled selected>-- Select Group Assigned --</option>
                @foreach ($groups as $group)
                    <option value="{{ $group->id }}" {{ $user->group_id == $group->id ? 'selected' : '' }}>
                        Group {{ $group->group_no }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Position -->
        <div>
            <label class="block text-sm">Position <span class="text-red-500">*</span></label>
            <select class="w-full rounded border-gray-300 px-3 py-2" required>
                <option value="" disabled selected>-- Select Position --</option>
                @foreach ($positions as $position)
                    <option value="{{ $position->id }}" {{ $user->position_id == $position->id ? 'selected' : '' }}>
                        {{ $position->position_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Role -->
        <div>
            <label class="block text-sm">Role <span class="text-red-500">*</span></label>
            <select name="role_id" class="w-full rounded border-gray-300 px-3 py-2" required>
                <option value="" disabled selected>-- Select Role --</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>
                        {{ $role->role }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Date of % Worked Hours -->
        <div>
            <label class="block text-sm">Date of <span id="percentLabel">%</span> Worked Hours <span class="text-red-500">*</span></label>
            <input type="date" name="percent_date" class="w-full rounded border-gray-300" required>
        </div>

        <!-- Total Worked Hours Completed -->
        <div>
            <label class="block text-sm">Total Worked Hours Completed <span class="text-red-500">*</span></label>
            <input type="number" name="total_worked_hours_completed" class="w-full rounded border-gray-300" required>
        </div>

        <!-- Total Worked Hours Required -->
        <div>
            <label class="block text-sm">Total Worked Hours Required <span class="text-red-500">*</span></label>
            <input type="number" name="total_worked_hours_required" class="w-full rounded border-gray-300" required>
        </div>

        <!-- File Upload -->
        <div>
            <label for="hr_access_confirmation" class="block text-sm ">Upload File <span class="text-red-500">*</span></label>

            <div class="flex items-center space-x-4">
                <label for="notifyFile"class="cursor-pointer px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded hover:bg-gray-700 transition">
                    Choose File
                </label>
                <span id="notifyFileName" class="text-sm text-black-600">No file selected</span>
            </div>

            <input type="file" name="notifyFile" id="notifyFile" accept=".pdf,.mp4" class="hidden" required>
        </div>
    </div>

    <!-- Hidden input for percent completed -->
    <input type="hidden" name="percent_completed" id="percent_completed" value="">
</div>

<!-- File name display -->
<script>
    document.getElementById('notifyFile').addEventListener('change', function () {
        const file = this.files[0];
        document.getElementById('notifyFileName').textContent = file ? file.name : 'No file selected';
    });
</script>
