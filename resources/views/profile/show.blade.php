<x-app-layout>
    <div class="profile container mt-5">
        <div class="row">
            <div class="mb-4">
                <a href="{{ route('products.index') }}" class="text-gray-500 hover:underline">
                    &larr; Back
                </a>
            </div>
            <!-- Left Side: Profile Image -->
            <div class="col-md-4">
                <img
                    id="imagePreview"
                    src="{{ asset('images/users/' . ($user->image ?? 'default.jpg')) }}"
                    alt="Profile Image"
                    width="150"
                    class="img-thumbnail rounded-circle shadow-lg">
                <h1 class="display-5 font-weight-bold mt-3">
                    {{ Auth::user()->firstname }} {{ Auth::user()->lastname }}
                </h1>
            </div>

            <!-- Right Side: User Details -->
            <div class="col-md-8">
                <div class="profile-details bg-light p-4 rounded shadow-sm">
                    <div class="row p-3">
                        <div class="col-md-6">
                            <h5 class="text-secondary">Email: {{ Auth::user()->email }}</h5>
                        </div>
                        <div class="col-md-6">
                            <h5 class="text-secondary">Phone: {{ Auth::user()->phone ?? 'Not provided' }}</h5>
                        </div>
                    </div>
                    <div class="row p-3">
                        <div class="col-md-6">
                            <h5 class="text-secondary">Address: {{ Auth::user()->address ?? 'Not provided' }}</h5>
                        </div>
                        <div class="col-md-6">
                            <h5 class="text-secondary">Joined On: {{ Auth::user()->created_at->format('F d, Y') }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom: Update Profile Button -->
        <div class="text-center mt-4">
            <x-dropdown-link :href="route('profile.edit')" class="btn btn-outline-danger text-center">
                Update Profile
            </x-dropdown-link>
        </div>
    </div>
</x-app-layout>