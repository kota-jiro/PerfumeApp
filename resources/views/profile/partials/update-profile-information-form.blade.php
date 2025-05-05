<section class="p-6 bg-white shadow-md rounded-lg">
    <header class="mb-6">
        <h2 class="text-xl font-semibold text-gray-900">
            {{ __('Profile Information') }}
        </h2>
        <p class="mt-2 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('patch')

        <!-- Profile Image -->
        <div class="text-center">
            <img id="imagePreview" src="{{ asset('images/users/' . ($user->image ?? 'default.jpg')) }}" alt="Profile Image" class="w-32 h-32 rounded-full mx-auto mb-4 shadow-md">
            <label for="imageInput" class="cursor-pointer inline-block px-4 py-2 bg-indigo-600 text-gray-600 font-medium text-sm rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
            {{ __('Update Profile Pic') }}
            </label>
            <input type="file" name="image" id="imageInput" class="hidden" accept="image/*" onchange="previewImage(event)">
            <p id="fileName" class="mt-2 text-sm text-gray-600"></p>
            @error('image')
            <span class="text-sm text-red-600">{{ $message }}</span>
            @enderror
        </div>

        <script>
            function previewImage(event) {
            const imagePreview = document.getElementById('imagePreview');
            const fileName = document.getElementById('fileName');
            const file = event.target.files[0];

            if (file) {
                imagePreview.src = URL.createObjectURL(file);
                fileName.textContent = `Selected file: ${file.name}`;
            } else {
                fileName.textContent = '';
            }
            }
        </script>

        <!-- First Name -->
        <div>
            <x-input-label for="firstname" :value="__('First Name')" />
            <x-text-input id="firstname" name="firstname" type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" :value="old('firstname', $user->firstname)" required autofocus autocomplete="firstname" />
            <x-input-error class="mt-2" :messages="$errors->get('firstname')" />
        </div>

        <!-- Last Name -->
        <div>
            <x-input-label for="lastname" :value="__('Last Name')" />
            <x-text-input id="lastname" name="lastname" type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" :value="old('lastname', $user->lastname)" required autofocus autocomplete="lastname" />
            <x-input-error class="mt-2" :messages="$errors->get('lastname')" />
        </div>

        @if ($user->usertype === 'user')
            <!-- Phone -->
            <div>
                <x-input-label for="phone" :value="__('Phone')" />
                <div class="flex items-center">
                    <span class="px-3 py-2 bg-gray-200 border border-r-0 border-gray-300 rounded-l-md text-gray-600">+63</span>
                    <x-text-input id="phone" name="phone" type="number" class="mt-1 block w-full border-gray-300 rounded-r-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" :value="old('phone', $user->phone)" maxlength="9" oninput="if(this.value.length > 9) this.value = this.value.slice(0, 9);" autocomplete="phone" />
                </div>
                <x-input-error class="mt-2" :messages="$errors->get('phone')" />
            </div>

            <!-- Address -->
            <div>
                <x-input-label for="address" :value="__('Address')" />
                <select id="address" name="address" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="" disabled selected>{{ __('Select your address in Cebu') }}</option>
                    <option value="Liloan" {{ old('address', $user->address) === 'Liloan' ? 'selected' : '' }}>Liloan</option>
                    <option value="Consolacion" {{ old('address', $user->address) === 'Consolacion' ? 'selected' : '' }}>Consolacion</option>
                    <option value="Mandaue City" {{ old('address', $user->address) === 'Mandaue City' ? 'selected' : '' }}>Mandaue City</option>
                    <option value="Lapu-Lapu City" {{ old('address', $user->address) === 'Lapu-Lapu City' ? 'selected' : '' }}>Lapu-Lapu City</option>
                    <option value="Cebu City" {{ old('address', $user->address) === 'Cebu City' ? 'selected' : '' }}>Cebu City</option>
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('address')" />
            </div>
        @endif

        <!-- Email -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div class="mt-4">
                <p class="text-sm text-gray-800">
                    {{ __('Your email address is unverified.') }}
                    <button form="send-verification" class="underline text-sm text-indigo-600 hover:text-indigo-900">
                        {{ __('Click here to re-send the verification email.') }}
                    </button>
                </p>
                @if (session('status') === 'verification-link-sent')
                <p class="mt-2 text-sm text-green-600">
                    {{ __('A new verification link has been sent to your email address.') }}
                </p>
                @endif
            </div>
            @endif
        </div>

        <!-- Save Button -->
        <div class="flex items-center justify-between">
            <x-primary-button>{{ __('Save') }}</x-primary-button>
            @if (session('status') === 'profile-updated')
            <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-green-600">
                {{ __('Saved.') }}
            </p>
            @endif
        </div>
    </form>
</section>
