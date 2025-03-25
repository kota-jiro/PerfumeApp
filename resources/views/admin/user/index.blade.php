<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('User Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7x1 mx-auto sm:px lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="d-flex align-items-center justify-content-between">
                        <h1 class="mb-0">List Users</h1>
                        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">+ Add User</a>
                    </div>
                    <hr />
                    @if (Session::has('success'))
                    <div class="alert alert-success" role="alert">
                        {{ Session::get('success') }}
                    </div>
                    @endif
                    <table class="table table-hover">
                        <thead class="table-primary">
                            <tr>
                                <th>#</th>
                                <th>Profile</th>
                                <th>Name</th>
                                <th>UserType</th>
                                <th>Email</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($users as $user)
                            <tr>
                                <td class="align-middle">{{ $loop->iteration }}</td>
                                <td class="align-middle">
                                    <img src="{{ asset('images/users/' . ($user->image ?? 'default.jpg')) }}" alt="Profile" width="50" height="50" class="rounded-circle">
                                </td>
                                <td class="align-middle user-name">{{ $user->firstname }} {{ $user->lastname }}</td>
                                <td class="align-middle">
                                    <select class="form-select userTypeChange" data-user-id="{{ $user->id }}">
                                        <option value="user" {{ $user->usertype == 'user' ? 'selected' : '' }}>User</option>
                                        <option value="admin" {{ $user->usertype == 'admin' ? 'selected' : '' }}>Admin</option>
                                    </select>
                                </td>
                                <td class="align-middle">{{ $user->email }}</td>
                                <td class="align-middle">{{ $user->created_at }}</td>
                                <td class="align-middle">{{ $user->updated_at }}</td>
                                <td class="align-middle">
                                    <!-- Delete User Button (Triggers Modal) -->
                                    <div class="btn-group" role="group" aria-label="Basic example">
                                        <a href="{{ route('admin.users.edit', ['id' => $user->id]) }}" class="btn btn-secondary">Edit</a>

                                        <!-- Button to trigger modal -->
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-user-id="{{ $user->id }}">
                                            Delete
                                        </button>
                                    </div>

                                    <!-- Delete Confirmation Modal -->
                                    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Are you sure you want to delete this user?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <form id="deleteForm" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">Yes, Delete</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td class="text-center">Users not found po!</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- User Type Change Confirmation Modal -->
    <div class="modal fade" id="userTypeChangeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Role Change</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p id="userTypeChangeText"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" id="confirmUserTypeChange">Yes, Change</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        let selectedUserId = null;
        let selectedUserType = null;

        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".userTypeChange").forEach(function(select) {
                select.addEventListener("change", function() {
                    selectedUserId = this.getAttribute("data-user-id");
                    selectedUserType = this.value; 
                    let userName = this.closest("tr").querySelector(".user-name").innerText;

                    document.getElementById("userTypeChangeText").innerHTML =
                        `Are you sure you want to change <strong>${userName}</strong> to <strong>${selectedUserType}</strong>?`;
                    
                    let userTypeChangeModal = new bootstrap.Modal(document.getElementById("userTypeChangeModal"));
                    userTypeChangeModal.show();
                });
            });

            document.getElementById("confirmUserTypeChange").addEventListener("click", function() {
                if (selectedUserId && selectedUserType) {
                    fetch("{{ route('admin.users.updateRole') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            user_id: selectedUserId,
                            usertype: selectedUserType
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert("Failed to update user type.");
                        }
                    })
                    .catch(error => console.error("Error:", error));
                }
            });
        });
        
        document.addEventListener("DOMContentLoaded", function() {
            var deleteModal = document.getElementById('deleteModal');
            deleteModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;
                var userId = button.getAttribute('data-user-id');

                // Set correct action URL for deletion
                var deleteForm = document.getElementById('deleteForm');
                deleteForm.action = "{{ url('admin/users/delete') }}/" + userId;
            });
        });
    </script>

</x-app-layout>