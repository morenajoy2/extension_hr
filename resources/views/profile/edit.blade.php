<x-app-layout>
    <div class="py-5" x-data="{ tab: 'personal' }">
        <div class="w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Tab Navigation -->
            <div class="mb-4 border-b border-gray-200 flex justify-center" >
                <nav class="-mb-px flex space-x-4 sm:space-x-8" aria-label="Tabs">
                    <a href="#" @click.prevent="tab = 'personal'" 
                        :class="tab === 'personal' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                        Personal Information
                    </a>
                    <a href="#" @click.prevent="tab = 'employment'" 
                        :class="tab === 'employment' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                        Employment Information
                    </a>
                    <a href="#" @click.prevent="tab = 'password'" 
                        :class="tab === 'password' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                        Update Password
                    </a>
                    <a href="#" @click.prevent="tab = 'delete'" 
                        :class="tab === 'delete' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                        Delete Account
                    </a>
                </nav>
            </div>

            <!-- Tab Contents -->
            <div class="p-4 sm:p-6 bg-white shadow sm:rounded-lg">
                <div class="max-w-md mx-auto space-y-6">

                    <!-- Personal Information -->
                    <div x-show="tab === 'personal'" x-cloak>
                        @include('profile.partials.update-personal-information-form')
                    </div>

                    <!-- Employment Information -->
                    <div x-show="tab === 'employment'" x-cloak>
                        @include('profile.partials.update-employment-information-form')
                    </div>

                    <!-- Update Password -->
                    <div x-show="tab === 'password'" x-cloak>
                        @include('profile.partials.update-password-form')
                    </div>

                    <!-- Delete Account -->
                    <div x-show="tab === 'delete'" x-cloak>
                        @include('profile.partials.delete-user-form')
                    </div>

                </div>
            </div>
        </div>
    </div>
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
        </script>
</x-app-layout>
