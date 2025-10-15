<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7x1 mx-auto sm:px lg:px-8">
            <div class="mb-4">
                <a href="{{ route('admin.users') }}" class="text-gray-500 hover:underline">
                    &larr; Back
                </a>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="mb-0">Edit User</h1>
                    <hr />
                    <form action="{{ route('admin.users.update', $users->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col  mb-3">
                                <label for="form-label">First Name</label>
                                <input type="text" name="firstname" class="form-control" placeholder="First Name" value="{{ old('firstname', $users->firstname) }}" />
                                @error('firstname')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col  mb-3">
                                <label for="form-label">Last Name</label>
                                <input type="text" name="lastname" class="form-control" placeholder="Last Name" value="{{ old('lastname', $users->lastname) }}" />
                                @error('lastname')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- <div class="row mb-3">
                            <div class="col">
                                <label for="phone" class="form-label">Phone #</label>
                                <input type="number" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="Phone Number (Optional)" required autofocus autocomplete="phone" />
                                @error('phone')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col">
                                <label for="address" class="form-label">Address</label>
                                <input type="text" name="address" class="form-control" value="{{ old('address', $users->address) }}" placeholder="Address(Optional)" required autofocus autocomplete="address" />
                                @error('address')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div> -->

                        <div class="row">
                            <div class="col  mb-3">
                                <label for="form-label">Email</label>
                                <input type="text" name="email" class="form-control" placeholder="Email" value="{{ old('email', $users->email) }}" />
                                @error('email')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col">
                                <label for="image" class="form-label">Profile Image: (Optional)</label>
                                <input type="file" name="image" id="imageInput" class="form-control" accept="image/*" onchange="previewImage(event)">
                                @error('image')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col text-center">
                                <img id="imagePreview" src="{{ asset('images/users/' . ($users->image ?? 'default.jpg')) }}" alt="User Image" width="100" class="img-thumbnail">
                            </div>
                        </div>

                        <div class="row">
                            <div class="d-grid">
                                <button class="btn btn-warning">Update</button>
                            </div>
                        </div>
                    </form>
                    <script>
                        function previewImage(event) {
                            const reader = new FileReader();
                            reader.onload = function() {
                                const output = document.getElementById('imagePreview');
                                output.src = reader.result;
                            };
                            reader.readAsDataURL(event.target.files[0]);
                        }
                    </script>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>