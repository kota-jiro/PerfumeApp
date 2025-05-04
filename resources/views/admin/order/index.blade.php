<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Order Management') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="d-flex align-items-center justify-content-between">
                        <h1 class="mb-0">List Orders</h1>
                        <div>
                            
                            <form action="{{ route('admin.orders') }}" method="GET" class="d-flex">
                                <select name="user_id" class="form-select me-2 w-auto">
                                    <option value="">All Users</option>
                                    @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ $userFilter == $user->id ? 'selected' : '' }}>
                                        {{ $user->firstname }} {{ $user->lastname }}
                                    </option>
                                    @endforeach
                                </select>
                                <select name="status" class="form-select me-2 w-auto">
                                    <option value="">All Statuses</option>
                                    <option value="Pending" {{ $statusFilter == 'Pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="Processing" {{ $statusFilter == 'Processing' ? 'selected' : '' }}>Processing</option>
                                    <option value="Completed" {{ $statusFilter == 'Completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="Cancelled" {{ $statusFilter == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                                <button type="submit" class="btn btn-sm btn-outline-danger">Filter</button>
                            </form>
                        </div>
                    </div>
                    <hr class="my-4">
                    <div class="mb-4">
                        @if ($statusFilter == 'Pending')
                        <strong>Pending:</strong> {{ $totalPending }}
                        @elseif ($statusFilter == 'Processing')
                        <strong>Processing:</strong> {{ $totalProcessing }}
                        @elseif ($statusFilter == 'Completed')
                        <strong>Completed:</strong> {{ $totalCompleted }}
                        @elseif ($statusFilter == 'Cancelled')
                        <strong>Cancelled:</strong> {{ $totalCancelled }}
                        @else
                        <strong>Total Orders:</strong> {{ $totalOrders }}
                        @endif
                    </div>

                    @if (session('success'))
                    <div class="alert alert-success" id="success-alert">
                        {{ session('success') }}
                    </div>
                    <script>
                        setTimeout(() => {
                            const alert = document.getElementById('success-alert');
                            if (alert) alert.remove();
                        }, 1500);
                    </script>
                    @endif

                    <table class="table table-hover">
                        <thead class="table-primary">
                            <tr>
                                <th>#</th>
                                <th>User</th>
                                <th>Status</th>
                                <th>Products</th>
                                <th>Total Price</th>
                                <th>Created At</th>
                                <th>Update Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $order->user->firstname }} {{ $order->user->lastname }}</td>
                                <td>
                                    <span class="badge bg-secondary text-capitalize">
                                        {{ $order->status }}
                                    </span>
                                </td>
                                <td>
                                    <ul class="list-unstyled">
                                        <li>
                                            {{ $order->product->name }} ({{ $order->size }}) - ₱{{ number_format($order->price, 2) }}
                                        </li>
                                    </ul>
                                </td>
                                <td>
                                    ₱{{ number_format($order->price, 2) }}
                                </td>
                                <td>{{ $order->created_at->format('M d, Y H:i') }}</td>
                                <td>
                                    <form action="{{ route('admin.orders.update', $order) }}" method="POST" class="d-flex">
                                        @csrf
                                        @method('PUT')
                                        <select name="status" class="form-select me-2 w-auto">
                                            <option value="Pending" {{ $order->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="Processing" {{ $order->status == 'Processing' ? 'selected' : '' }}>Processing</option>
                                            <option value="Completed" {{ $order->status == 'Completed' ? 'selected' : '' }}>Completed</option>
                                            <option value="Cancelled" {{ $order->status == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        </select>
                                        <button type="submit" class="btn btn-sm btn-danger">Update</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">No orders found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <!-- Pagination Section -->
                    <div class="d-flex justify-content-center mt-1">
                        <nav aria-label="Page navigation">
                            <ul class="pagination pagination-sm">
                                @if ($orders->lastPage() > 1)
                                {{-- Previous Page Link --}}
                                <li class="page-item {{ $orders->onFirstPage() ? 'disabled' : '' }}">
                                    <a class="page-link" href="{{ $orders->previousPageUrl() }}{{ request()->has('usertype') ? '&usertype=' . request('usertype') : '' }}" tabindex="-1" aria-disabled="{{ $orders->onFirstPage() ? 'true' : 'false' }}">
                                        &laquo;
                                    </a>
                                </li>

                                {{-- Pagination Elements --}}
                                @for ($i = 1; $i <= $orders->lastPage(); $i++)
                                    <li class="page-item {{ $i == $orders->currentPage() ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $orders->url($i) }}{{ request()->has('usertype') ? '&usertype=' . request('usertype') : '' }}">{{ $i }}</a>
                                    </li>
                                    @endfor

                                    {{-- Next Page Link --}}
                                    <li class="page-item {{ $orders->hasMorePages() ? '' : 'disabled' }}">
                                        <a class="page-link" href="{{ $orders->nextPageUrl() }}{{ request()->has('usertype') ? '&usertype=' . request('usertype') : '' }}" aria-disabled="{{ $orders->hasMorePages() ? 'false' : 'true' }}">
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