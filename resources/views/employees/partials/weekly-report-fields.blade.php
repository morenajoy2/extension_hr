<!-- Weekly Report Inputs -->
<div id="weeklyReportInputs" class="hidden space-y-3 mb-4">
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm">Employee ID Number <span class="text-red-500">*</span></label>
            <input type="number" class="w-full rounded border-gray-300 bg-gray-100" value="{{ $user->employee_id }}" required>
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

        <!-- Department Team -->
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
                    <option 
                        value="{{ $role->id }}" 
                        {{ (auth()->user()->role_id ?? '') == $role->id ? 'selected' : '' }}
                    >
                        {{ $role->role }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Weekly From -->
        <div>
            <label class="block text-sm">Weekly From Date <span class="text-red-500">*</span></label>
            <input type="date" name="from_date" class="w-full rounded border-gray-300 px-3 py-2" required>
        </div>

        <!-- Weekly To -->
        <div>
            <label class="block text-sm">Weekly To Date <span class="text-red-500">*</span></label>
            <input type="date" name="to_date" class="w-full rounded border-gray-300 px-3 py-2" required>
        </div>

        <!-- Worked Hours -->
        <div>
            <label class="block text-sm">Weekly Worked Hours <span class="text-red-500">*</span></label>
            <input type="number" name="worked_hours" class="w-full rounded border-gray-300 px-3 py-2" required>
        </div>

        <!-- Total Hours -->
        <div>
            <label class="block text-sm">Total Hours Complete <span class="text-red-500">*</span></label>
            <input type="number" name="total_hours" class="w-full rounded border-gray-300 px-3 py-2" required>
        </div>

        <!-- Remaining Hours -->
        <div>
            <label class="block text-sm">Required Hours Remaining <span class="text-red-500">*</span></label>
            <input type="number" name="remaining_hours" class="w-full rounded border-gray-300 px-3 py-2" required>
        </div>
    </div>

    <!-- 📄 Upload Word Document -->
    <div class="mb-4">
        <label for="doc_upload" class="block text-sm ">Upload Weekly Report (.doc or .docx) <span class="text-red-500">*</span></label>
        
        <div class="flex items-center space-x-4">
            <label for="doc_upload" class="cursor-pointer px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded hover:bg-gray-700 transition">
                Choose Word File
            </label>
            <span id="docFileName" class="text-sm text-black-600">No file selected</span>
        </div>

        <input type="file" name="doc_upload" id="doc_upload" accept=".doc,.docx" class="hidden" required>
    </div>

    <!-- 📄 Upload PDF Document -->
    <div class="mb-4">
        <label for="pdf_upload" class="block text-sm ">Upload Weekly Report (.pdf) <span class="text-red-500">*</span></label>

        <div class="flex items-center space-x-4">
            <label for="pdf_upload" class="cursor-pointer px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded hover:bg-gray-700 transition">
                Choose PDF File
            </label>
            <span id="pdfFileName" class="text-sm text-black-600">No file selected</span>
        </div>

        <input type="file" name="pdf_upload" id="pdf_upload" accept=".pdf" class="hidden" required>
    </div>

    <!-- Acknowledgement -->
    <div>
        <label class="inline-flex items-start gap-2">
            <input type="checkbox" name="final_acknowledge" value="yes" required>
            <span class="text-sm"><strong>I acknowledge this report is accurate and complete.</strong></span>
        </label>
    </div>
</div>

<script>
    document.getElementById('doc_upload').addEventListener('change', function () {
        document.getElementById('docFileName').textContent = this.files.length > 0 ? this.files[0].name : 'No file selected';
    });

    document.getElementById('pdf_upload').addEventListener('change', function () {
        document.getElementById('pdfFileName').textContent = this.files.length > 0 ? this.files[0].name : 'No file selected';
    });
</script>