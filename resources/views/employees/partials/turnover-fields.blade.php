<!-- Turnover Inputs -->
<div id="turnoverInputs" class="hidden space-y-3 mb-4">
    <h3 class="text-lg font-semibold mb-4">Employee Account Turnover Form</h3>
    <div class="grid grid-cols-2 gap-4">
        <!-- Employee ID Number -->
        <div>
            <label class="block text-sm">Employee ID Number <span class="text-red-500">*</span></label>
            <input type="number" name="employee_id" class="w-full rounded border-gray-300" value="{{ $user->employee_id }}"required>
        </div>

        <!-- Employee Name -->
        <div>
            <label class="block text-sm">Employee Name <span class="text-red-500">*</span></label>
            <input type="text" name="employee_name" class="w-full rounded border-gray-300" value="{{ $user->first_name }} {{ $user->last_name }}"required>
        </div>

        <!-- Email -->
        <div>
            <label class="block text-sm">Email <span class="text-red-500">*</span></label>
            <input type="email" name="email" class="w-full rounded border-gray-300" value="{{ $user->email }}"required>
        </div>

        <!-- Employment Type -->
        <div>
            <label for="employment_type_id" class="block text-sm">
                Employment Type <span class="text-red-500">*</span>
            </label>
            <select name="employment_type_id" id="employment_type_id" class="w-full rounded border-gray-300 px-3 py-2" required>
                <option value="">-- Select Employment Type --</option>
                @foreach($employmentTypes as $employmentType)
                    <option 
                        value="{{ $employmentType->id }}" 
                        {{ (auth()->user()->employment_type_id ?? '') == $employmentType->id ? 'selected' : '' }}
                    >
                        {{ $employmentType->type_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- 🏢 Department -->
        <div>
            <label for="department_id" class="block text-sm ">
                Department <span class="text-red-500">*</span>
            </label>
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

        <!-- Department Team -->
        <div>
            <label for="department_team_id" class="block text-sm ">
                Department Team <span class="text-red-500">*</span>
            </label>
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
            <label for="role_id" class="block text-sm ">
                Role <span class="text-red-500">*</span>
            </label>
            <select name="role_id" id="role_id" class="w-full rounded border-gray-300 px-3 py-2" required>
                <option value="" disabled selected>-- Select Role --</option>
                @foreach ($roles as $role)
                    <option 
                        value="{{ $role->id }}" 
                        {{ (auth()->user()->role_id ?? '') == $role->id ? 'selected' : '' }}
                    >
                        {{ $role->role }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Job Title -->
        <div>
            <label class="block text-sm">Job Title <span class="text-red-500">*</span></label>
            <input type="text" name="job_title" class="w-full rounded border-gray-300" required>
        </div>

        <!-- Orientation Day -->
        <div>
            <label class="block text-sm">Orientation Day <span class="text-red-500">*</span></label>
            <input type="date" name="orientation_date" class="w-full rounded border-gray-300" required>
        </div>

        <!-- First Day -->
        <div>
            <label class="block text-sm">First Day <span class="text-red-500">*</span></label>
            <input type="date" name="first_day_date" class="w-full rounded border-gray-300" required>
        </div>

        <!-- Last Day -->
        <div>
            <label class="block text-sm">Last Day <span class="text-red-500">*</span></label>
            <input type="date" name="last_day_date" class="w-full rounded border-gray-300" required>
        </div>

        <!-- Exit Day -->
        <div>
            <label class="block text-sm">Exit Day <span class="text-red-500">*</span></label>
            <input type="date" name="exit_date" class="w-full rounded border-gray-300" required>
        </div>

        <!-- School Name -->
        <div>
            <label class="block text-sm">School Name <span class="text-red-500">*</span></label>
            <input type="text" name="school" class="w-full rounded border-gray-300" value="{{ $user->school }}" required>
        </div>

        <!-- Required Hours -->
        <div>
            <label class="block text-sm">Total Required Worked Hours <span class="text-red-500">*</span></label>
            <input type="number" name="total_worked_hours_required" class="w-full rounded border-gray-300" required>
        </div>
    </div>

    <hr class="my-4">
    <h3 class="text-lg font-semibold mb-4">Recommended Employee <span class="text-red-500">*</span></h3>

    <!-- Recommended Employee Dual Sync -->
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm">Recommended Employee ID <span class="text-red-500">*</span></label>
            <select name="recommended_employee_id" id="recommended_employee_id" class="w-full rounded border-gray-300 px-3 py-2" required>
                <option value="" disabled selected>-- Select Employee ID --</option>
                @foreach ($users as $user)
                    @if ($user->id !== auth()->id())
                        <option value="{{ $user->id }}" data-name="{{ $user->first_name }} {{ $user->last_name }}">{{ $user->employee_id }}</option>
                    @endif
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm">Recommended Employee Name <span class="text-red-500">*</span></label>
            <select name="recommended_employee_name" id="recommended_employee_name" class="w-full rounded border-gray-300 px-3 py-2" required>
                <option value="" disabled selected>-- Select Name --</option>
                @foreach ($users as $user)
                    @if ($user->id !== auth()->id())
                        <option value="{{ $user->first_name }} {{ $user->last_name }}" data-id="{{ $user->id }}">{{ $user->first_name }} {{ $user->last_name }}</option>
                    @endif
                @endforeach
            </select>
        </div>
    </div>

     <!-- Task Turnover Question -->
    <div class="mb-4">
        <label class="block text-sm">Do you have to turnover your task? <span class="text-red-500">*</span></label>
        <div class="mt-1 flex gap-6">
            <label class="inline-flex items-center">
                <input type="radio" name="has_task_turnover" value="yes" class="text-blue-500 toggle-task-list" required>
                <span class="ml-2">Yes</span>
            </label>
            <label class="inline-flex items-center">
                <input type="radio" name="has_task_turnover" value="no" class="text-blue-500 toggle-task-list">
                <span class="ml-2">No</span>
            </label>
        </div>
    </div>

    <!-- Task Lists ( Hidden) -->
    <div id="task-list-container" class="mb-4 hidden">
        <label class="block text-sm">Task List </label>
        <textarea name="task_list" class="w-full rounded border-gray-300 px-3 py-2" rows="3" placeholder="List of tasks handed over..." ></textarea>
    </div>

    <!-- New Owner Transfer List -->
    <div>
        <label class="block text-sm">New Owner Transfer List <span class="text-red-500">*</span></label>
        <textarea name="new_owner_transfer_list" class="w-full rounded border-gray-300 px-3 py-2" rows="3" required></textarea>
    </div>

    <!-- Access Confirmation -->
    <div>
        <label class="block text-sm">Confirmation that all access credentials have been handed over: <span class="text-red-500">*</span></label>
        <textarea name="confirmation_access_credentials" class="w-full rounded border-gray-300 px-3 py-2" rows="3" required></textarea>
    </div>

    <!-- Department/HR Team Leader -->
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm">Department Team Leader (ID) <span class="text-red-500">*</span></label>
            <select name="dpt_team_leader_employee_id" id="dpt_team_leader_id" class="w-full rounded border-gray-300 px-3 py-2" required>
                <option value="" disabled selected>-- Select ID --</option>
                @foreach ($users as $user)
                    @if ($user->id !== auth()->id())
                        <option value="{{ $user->id }}" data-name="{{ $user->first_name }} {{ $user->last_name }}">{{ $user->employee_id }}</option>
                    @endif
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm">Department Team Leader (Name) <span class="text-red-500">*</span></label>
            <select name="dpt_team_leader_employee_name" id="dpt_team_leader_name" class="w-full rounded border-gray-300 px-3 py-2" required>
                <option value="" disabled selected>-- Select Name --</option>
                @foreach ($users as $user)
                    @if ($user->id !== auth()->id())
                        <option value="{{ $user->first_name }} {{ $user->last_name }}" data-id="{{ $user->id }}">{{ $user->first_name }} {{ $user->last_name }}</option>
                    @endif
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm">HR Team Leader (ID) <span class="text-red-500">*</span></label>
            <select name="hr_team_leader_employee_id" id="hr_team_leader_id" class="w-full rounded border-gray-300 px-3 py-2" required>
                <option value="" disabled selected>-- Select ID --</option>
                @foreach ($users as $user)
                    @if ($user->id !== auth()->id())
                        <option value="{{ $user->id }}" data-name="{{ $user->first_name }} {{ $user->last_name }}">{{ $user->employee_id }}</option>
                    @endif
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm">HR Team Leader (Name) <span class="text-red-500">*</span></label>
            <select name="hr_team_leader_employee_name" id="hr_team_leader_name" class="w-full rounded border-gray-300 px-3 py-2" required>
                <option value="" disabled selected>-- Select Name --</option>
                @foreach ($users as $user)
                    @if ($user->id !== auth()->id())
                        <option value="{{ $user->first_name }} {{ $user->last_name }}" data-id="{{ $user->id }}">{{ $user->first_name }} {{ $user->last_name }}</option>
                    @endif
                @endforeach
            </select>
        </div>
    </div>

    <!-- E-Signature -->
    <div>
        <label for="e_signature" class="block text-sm">Upload E-Signature (.pdf, .jpg, .jpeg, .png) <span class="text-red-500">*</span></label>

    <div class="flex items-center space-x-4">
        <label 
            for="e_signature" 
            class="cursor-pointer px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded hover:bg-gray-700 transition"
        >
            Choose File
        </label>
        <span id="eSignatureFileName" class="text-sm text-black-600">No file selected</span>
    </div>

    <input 
        type="file" 
        name="e_signature" 
        id="e_signature" 
        accept=".pdf,.jpg,.jpeg,.png" 
        class="hidden" 
        required
    >
    </div>
</div>

<!-- Script for Dual Sync -->
<script>
    function syncDualDropdown(idSelector, nameSelector) {
        const idSelect = document.getElementById(idSelector);
        const nameSelect = document.getElementById(nameSelector);

        idSelect.addEventListener('change', function () {
            const selected = idSelect.options[idSelect.selectedIndex];
            const name = selected.getAttribute('data-name');
            [...nameSelect.options].forEach(opt => {
                opt.selected = opt.value === name;
            });
        });

        nameSelect.addEventListener('change', function () {
            const selected = nameSelect.options[nameSelect.selectedIndex];
            const id = selected.getAttribute('data-id');
            [...idSelect.options].forEach(opt => {
                opt.selected = opt.value === id;
            });
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        syncDualDropdown('recommended_employee_id', 'recommended_employee_name');
        syncDualDropdown('dpt_team_leader_id', 'dpt_team_leader_name');
        syncDualDropdown('hr_team_leader_id', 'hr_team_leader_name');

        // Toggle task list container
        const radios = document.querySelectorAll('input[name="has_task_turnover"]');
        const taskListContainer = document.getElementById('task-list-container');

        radios.forEach(radio => {
            radio.addEventListener('change', function () {
                if (this.value === 'yes') {
                    taskListContainer.classList.remove('hidden');
                } else {
                    taskListContainer.classList.add('hidden');
                }
            });
        });
    });

    document.getElementById('e_signature').addEventListener('change', function () {
        const fileName = this.files.length > 0 ? this.files[0].name : 'No file selected';
        document.getElementById('eSignatureFileName').textContent = fileName;
    });
</script>
