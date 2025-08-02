<!-- Exit Clearance Inputs -->
<div id="exitClearanceInputs" class="hidden space-y-3 mb-4">
    <div class="grid grid-cols-2 gap-4">
        <!-- Employee ID -->
        <div>
            <label class="block text-sm">Employee ID Number <span class="text-red-500">*</span></label>
            <input type="number" class="w-full rounded border-gray-300 bg-gray-100" value="{{ $user->employee_id }}" required >
        </div>

        <!-- Full Name -->
        <div>
            <label class="block text-sm">Employee Name <span class="text-red-500">*</span></label>
            <input type="text" class="w-full rounded border-gray-300 bg-gray-100"
                   value="{{ $user->first_name }} {{ $user->last_name }}" required>
        </div>

        <!-- Email -->
        <div>
            <label class="block text-sm">Email <span class="text-red-500">*</span></label>
            <input type="email" class="w-full rounded border-gray-300 bg-gray-100" value="{{ $user->email }}" required>
        </div>

        <!-- Department -->
        <div>
            <label class="block text-sm">Department <span class="text-red-500">*</span></label>
            <select name="department_id" id="department_id" class="w-full rounded border-gray-300 px-3 py-2" required>
                <option value="" disabled selected>-- Select Department --</option>
                @foreach ($departments as $dept)
                    <option 
                        value="{{ $dept->id }}" 
                        {{ (auth()->user()->department_id ?? '') == $dept->id ? 'selected' : '' }}
                    >
                        {{ $dept->department_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Team -->
        <div>
            <label class="block text-sm">Department Team <span class="text-red-500">*</span></label>
            <select name="department_team_id" id="department_team_id" class="w-full rounded border-gray-300 px-3 py-2" required>
                <option value="" disabled selected>-- Select Team --</option>
                @foreach ($departmentTeams as $team)
                    <option 
                        value="{{ $team->id }}" 
                        {{ (auth()->user()->department_team_id ?? '') == $team->id ? 'selected' : '' }}
                    >
                        {{ $team->team_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Role -->
        <div>
            <label class="block text-sm">Role <span class="text-red-500">*</span></label>
            <select name="role_id" id="role_id" class="w-full rounded border-gray-300 px-3 py-2" required>
                <option value="" disabled selected>-- Select Role --</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->id }}" {{ (auth()->user()->role_id ?? '') == $role->id ? 'selected' : '' }}>
                        {{ $role->role }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Group -->
        <div>
            <label class="block text-sm">Group <span class="text-red-500">*</span></label>
            <select name="group_no" class="w-full rounded border-gray-300 px-3 py-2" required>
                <option value="" disabled selected>-- Select Group --</option>
                @foreach ($groups as $group)
                    <option value="{{ $group->id }}" {{ (auth()->user()->group_id ?? '') == $group->id ? 'selected' : '' }}>{{ $group->group_no }}</option>
                @endforeach
            </select>
        </div>

        <!-- Exit Type -->
        @php
            $exitTypes = ['completion' => 'Exit Completion', 'resignation' => 'Resignation', 'termination' => 'Termination'];
        @endphp
        <div>
            <label class="block text-sm">Exit Type <span class="text-red-500">*</span></label>
            <div class="flex gap-4">
                <select name="exit_type" class="w-full rounded border-gray-300 px-3 py-2" required>
                    <option value="" selected disabled>-- Select Type of Exit --</option>
                    @foreach ($exitTypes as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Task Turnover Role -->
        <div>
            <label class="block text-sm">Task Turnover (Who verified) <span class="text-red-500">*</span></label>
            <select name="task_turnover_role" class="w-full rounded border-gray-300 px-3 py-2" required>
                <option value="" disabled selected>-- Select Role --</option>
                @foreach ($roles as $role)
                    @if (in_array($role->role, ['Group Leader', 'Team Leader']))
                        <option value="{{ $role->id }}">{{ $role->role }}</option>
                    @endif
                @endforeach
            </select>
        </div>
    </div>

    <!-- Task Turnover Confirmation -->
    <div class="mb-4">
        <label class="block text-sm ">Do you have to turnover your task? <span class="text-red-500">*</span></label>
        <div class="flex items-center space-x-4">
            <label class="inline-flex items-center">
                <input type="radio" name="task_turnover_required" value="yes" class="form-radio text-blue-600" onclick="toggleTaskList(true)" required>
                <span class="ml-2">Yes</span>
            </label>
            <label class="inline-flex items-center">
                <input type="radio" name="task_turnover_required" value="no" class="form-radio text-blue-600" onclick="toggleTaskList(false)">
                <span class="ml-2">No</span>
            </label>
        </div>
    </div>

    <!-- Task List (conditionally shown) -->
    <div id="task_list_section" class="hidden mb-4">
        <label for="task_list" class="block text-sm">Task List </label>
        <textarea name="task_list" id="task_list" rows="4" class="w-full border-gray-300 rounded px-3 py-2" placeholder="List the tasks to be turned over..." ></textarea>
    </div>

    <!-- File Upload: Leader -->
    <div>
        <label for="team_leader_access_confirmation" class="block text-sm ">
            Upload Access/Data Confirmation (Team/Group Leader) <span class="text-red-500">*</span>
        </label>

        <div class="flex items-center space-x-4">
            <label for="team_leader_access_confirmation"
                class="cursor-pointer px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded hover:bg-gray-700 transition"
            >Choose File</label>
            <span id="teamLeaderFileName" class="text-sm text-black-600">No file selected</span>
        </div>

        <input type="file" name="team_leader_access_confirmation" id="team_leader_access_confirmation" accept=".pdf,.jpg,.jpeg,.png" class="hidden" required>
    </div>

    <!-- File Upload: HR -->
    <div>
        <label for="hr_access_confirmation" class="block text-sm ">
            Upload HR Confirmation File <span class="text-red-500">*</span>
        </label>

        <div class="flex items-center space-x-4">
            <label for="hr_access_confirmation"class="cursor-pointer px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded hover:bg-gray-700 transition"
            >Choose File</label>
            <span id="hrFileName" class="text-sm text-black-600">No file selected</span>
        </div>

        <input type="file" name="hr_access_confirmation" id="hr_access_confirmation" accept=".pdf,.jpg,.jpeg,.png" class="hidden" required>
    </div>

    <!-- Acknowledgement -->
    <div>
        <label class="inline-flex items-start gap-2">
            <input type="checkbox" name="exit_acknowledge" value="yes" required>
            <span class="text-sm"><strong>I acknowledge that all exit procedures and documents are accurate and complete.</strong></span>
        </label>
    </div>

    <!-- File Upload: E-Signature -->
    <div>
        <label for="e_signature_file" class="block text-sm ">
            E-Signature File (.pdf, .jpg, .jpeg, .png) <span class="text-red-500">*</span>
        </label>

        <div class="flex items-center space-x-4">
            <label for="e_signature_file" class="cursor-pointer px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded hover:bg-gray-700 transition"
            >Choose File</label>
            <span id="SignatureFileName" class="text-sm text-black-600">No file selected</span>
        </div>

        <input type="file" name="e_signature_file" id="e_signature_file" accept=".pdf,.jpg,.jpeg,.png" class="hidden" required>
    </div>
</div>

<script>
    function toggleTaskList(show) {
        const taskSection = document.getElementById('task_list_section');
        taskSection.classList.toggle('hidden', !show);
    }

    document.getElementById('e_signature_file').addEventListener('change', function () {
        const file = this.files[0];
        document.getElementById('SignatureFileName').textContent = file ? file.name : 'No file selected';
    });

     document.getElementById('team_leader_access_confirmation').addEventListener('change', function () {
        const file = this.files[0];
        document.getElementById('teamLeaderFileName').textContent = file ? file.name : 'No file selected';
    });

    document.getElementById('hr_access_confirmation').addEventListener('change', function () {
        const file = this.files[0];
        document.getElementById('hrFileName').textContent = file ? file.name : 'No file selected';
    });
</script>
