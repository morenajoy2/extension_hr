<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Personal Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="POST" action="{{ route('profile.update', $user->id) }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')
        <input type="hidden" name="form_type" value="personal_info">

        {{-- First Name --}}
        <div>
            <label for="first_name" class="block font-medium text-sm text-gray-700">First Name</label>
            <input id="first_name" name="first_name" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('first_name', $user->first_name) }}" required autofocus>
            @error('first_name')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Middle Name --}}
        <div>
            <label for="middle_name" class="block font-medium text-sm text-gray-700">Middle Name</label>
            <input id="middle_name" name="middle_name" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('middle_name', $user->middle_name) }}">
            @error('middle_name')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Last Name --}}
        <div>
            <label for="last_name" class="block font-medium text-sm text-gray-700">Last Name</label>
            <input id="last_name" name="last_name" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('last_name', $user->last_name) }}" required>
            @error('last_name')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Birthdate --}}
        <div>
            <label for="birth_of_date" class="block font-medium text-sm text-gray-700">Birthdate</label>
            <input id="birth_of_date" name="birth_of_date" type="date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('birth_of_date', $user->birth_of_date) }}" required>
            @error('birth_of_date')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Contact Number --}}
        <div>
            <label for="contact_number" class="block font-medium text-sm text-gray-700">Contact Number</label>
            <input id="contact_number" name="contact_number" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('contact_number', $user->contact_number) }}" required>
            @error('contact_number')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Gender --}}
        <div>
            <label for="gender" class="block font-medium text-sm text-gray-700">Gender</label>
            <select id="gender" name="gender" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm px-3 py-2">
                <option value="" disabled selected>-- Select Gender --</option>
                <option value="Male" {{ old('gender', $user->gender) === 'Male' ? 'selected' : '' }}>Male</option>
                <option value="Female" {{ old('gender', $user->gender) === 'Female' ? 'selected' : '' }}>Female</option>
            </select>
            @error('gender')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Address --}}
        <div>
            <label for="address" class="block font-medium text-sm text-gray-700">Permanent Address</label>
            <input id="address" name="address" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('address', $user->address) }}" required>
            @error('address')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- School --}}
        <div>
            <label for="school" class="block font-medium text-sm text-gray-700">School</label>
            <input id="school" name="school" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('school', $user->school) }}" required>
            @error('school')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- School Address --}}
        <div>
            <label for="school_address" class="block font-medium text-sm text-gray-700">School Address</label>
            <input id="school_address" name="school_address" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('school_address', $user->school_address) }}" required>
            @error('school_address')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Email --}}
        <div>
            <label for="email" class="block font-medium text-sm text-gray-700">Email</label>
            <input id="email" name="email" type="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="{{ $user->email }}" autocomplete="username">
            @error('email')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2 text-sm text-gray-800">
                    <p>Your email address is unverified.</p>
                    <button form="send-verification" class="underline text-sm text-blue-600 hover:text-blue-900">Click here to re-send the verification email.</button>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-1 text-green-600">A new verification link has been sent to your email address.</p>
                    @endif
                </div>
            @endif
        </div>

        {{-- Submit --}}
        <div class="flex items-center gap-4">
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-white hover:bg-indigo-700 transition">
                Save
            </button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-gray-600">
                    Saved.
                </p>
            @endif
        </div>
    </form>
</section>
