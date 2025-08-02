<!-- Application Form Extra Fields -->
<div id="applicationInputs" class="hidden mb-4">
    <div class="space-y-3 max-h-72 overflow-y-auto pr-2">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="contact_number" class="block text-sm ">Contact Number <span class="text-red-500">*</span></label>
                <input type="text" name="contact_number" id="contact_number"class="w-full rounded border-gray-300"value="{{ $user->contact_number }}" required>
            </div>

            <div>
                <label for="address" class="block text-sm ">Address <span class="text-red-500">*</span></label>
                <input type="text" name="address" id="address" class="w-full rounded border-gray-300" value="{{ $user->address }}" required>
            </div>

            <div>
                <label for="school" class="block text-sm ">School Name <span class="text-red-500">*</span></label>
                <input type="text" name="school" id="school"class="w-full rounded border-gray-300" value="{{ $user->school }}" required>
            </div>

            <div>
                <label for="school_address" class="block text-sm ">School Address <span class="text-red-500">*</span></label>
                <input type="text" name="school_address" id="school_address" class="w-full rounded border-gray-300" value="{{ $user->school_address }}" required>
            </div>
        </div>
    </div>
</div>