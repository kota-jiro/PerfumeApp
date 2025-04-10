<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('User Management') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7x1 mx-auto sm:px lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 d-flex flex-column" style="min-height: 570px; display: flex; justify-content: space-between;">
                    <div class="d-flex align-items-center justify-content-between">
                        <h1 class="mb-0">List Users</h1>
                        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">+ Add User</a>
                    </div>
                    <hr />
                    <div class="mb-3">
                        <!-- Usertype Filter Dropdown -->
                        <form method="GET" action="{{ route('admin.users') }}" class="d-flex">
                            <select name="usertype" class="form-select" onchange="this.form.submit()">
                                <option value="">All Users</option>
                                <option value="admin" {{ $usertypeFilter == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="user" {{ $usertypeFilter == 'user' ? 'selected' : '' }}>User</option>
                            </select>
                        </form>
                    </div>

                    <div class="mb-3">
                        <!-- Display the filtered total count of users -->
                        <strong>Total:</strong> {{ $totalFiltered }}
                        @if ($usertypeFilter)
                        <span>({{ ucfirst($usertypeFilter) }})</span>
                        @else
                        <span>(All Users)</span>
                        @endif
                    </div>
                    @if (Session::has('success'))
                    <div id="success-alert" class="alert alert-success" role="alert">
                        {{ Session::get('success') }}
                    </div>

                    <script>
                        // Wait for the page to load
                        document.addEventListener('DOMContentLoaded', function() {
                            // After 3 seconds (3000ms), fade out and remove the alert
                            setTimeout(function() {
                                const alertBox = document.getElementById('success-alert');
                                if (alertBox) {
                                    alertBox.style.transition = 'opacity 0.5s ease';
                                    alertBox.style.opacity = '0';
                                    setTimeout(() => alertBox.remove(), 500); // remove after fade out
                                }
                            }, 3000);
                        });
                    </script>
                    @endif
                    @if (Session::has('error'))
                    <div class="alert alert-danger" role="alert">
                        {{ Session::get('error') }}
                    </div>
                    <script>
                        // Wait for the page to load
                        document.addEventListener('DOMContentLoaded', function() {
                            // After 3 seconds (3000ms), fade out and remove the alert
                            setTimeout(function() {
                                const alertBox = document.querySelector('.alert-danger');
                                if (alertBox) {
                                    alertBox.style.transition = 'opacity 0.5s ease';
                                    alertBox.style.opacity = '0';
                                    setTimeout(() => alertBox.remove(), 500); // remove after fade out
                                }
                            }, 3000);
                        });
                    </script>
                    @endif
                    <table class="table table-hover">
                        <thead class="table-primary">
                            <tr>
                                <th>#</th>
                                <th>Profile</th>
                                <th>Name</th>
                                <th>UserType</th>
                                <th>Email</th>
                                <th>Phone #</th>
                                <th>Address</th>
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
                                    <img src="{{ asset('images/users/' . ($user->image ?? 'default.jpg')) }}" alt="Profile" width="50" height="50" style="min-height: 89px;" class="rounded-circle object-fit-cover">
                                </td>
                                <td class="align-middle user-name">{{ $user->firstname }} {{ $user->lastname }}</td>
                                <td class="align-middle">
                                    <select class="form-select userTypeChange" style="width: 100px;" data-user-id="{{ $user->id }}">
                                        <option value="user" {{ $user->usertype == 'user' ? 'selected' : '' }}>User</option>
                                        <option value="admin" {{ $user->usertype == 'admin' ? 'selected' : '' }}>Admin</option>
                                    </select>
                                </td>
                                <td class="align-middle">{{ $user->email }}</td>
                                <td class="align-middle">{{ $user->phone ?? '-----'  }}</td>
                                <td class="align-middle">{{ $user->address ?? '-----' }}</td>
                                <td class="align-middle">{{ $user->created_at}}</td>
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
                                <td class="text-center" colspan="10">Users not found!</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <!-- Pagination Section -->
                    <div class="d-flex justify-content-center mt-1">
                        <nav aria-label="Page navigation">
                            <ul class="pagination pagination-sm">
                                @if ($users->lastPage() > 1)
                                    {{-- Previous Page Link --}}
                                    <li class="page-item {{ $users->onFirstPage() ? 'disabled' : '' }}">
                                        <a class="page-link" href="{{ $users->previousPageUrl() }}{{ request()->has('usertype') ? '&usertype=' . request('usertype') : '' }}" tabindex="-1" aria-disabled="{{ $users->onFirstPage() ? 'true' : 'false' }}">
                                            &laquo;
                                        </a>
                                    </li>

                                    {{-- Pagination Elements --}}
                                    @for ($i = 1; $i <= $users->lastPage(); $i++)
                                        <li class="page-item {{ $i == $users->currentPage() ? 'active' : '' }}">
                                            <a class="page-link" href="{{ $users->url($i) }}{{ request()->has('usertype') ? '&usertype=' . request('usertype') : '' }}">{{ $i }}</a>
                                        </li>
                                    @endfor

                                    {{-- Next Page Link --}}
                                    <li class="page-item {{ $users->hasMorePages() ? '' : 'disabled' }}">
                                        <a class="page-link" href="{{ $users->nextPageUrl() }}{{ request()->has('usertype') ? '&usertype=' . request('usertype') : '' }}" aria-disabled="{{ $users->hasMorePages() ? 'false' : 'true' }}">
                                            &raquo;
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </nav>
                    </div>

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
    <style>
        .pagination .page-item .page-link {
            color: #800000;
            /* Leo’s Perfume brand red */
            border-radius: 0.375rem;
            margin: 0 2px;
            transition: background-color 0.2s ease-in-out;
            padding: 0.25rem 0.6rem;
            /* smaller height & width */
            font-size: 0.875rem;
            /* smaller font size */
            min-width: 35px;
            /* consistent width */
            height: 30px;
            /* consistent height */
            line-height: 1.2;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .pagination .page-item.active .page-link {
            background-color: #800000;
            color: white;
            border-color: #800000;
        }

        .pagination .page-item .page-link:hover {
            background-color: #f8d7da;
            color: #800000;
        }
    </style>

</x-app-layout>