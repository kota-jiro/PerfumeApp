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
                                    @foreach(['Pending', 'Processing', 'Out for Delivery', 'Completed', 'Cancelled'] as $status)
                                    <option value="{{ $status }}" {{ $statusFilter == $status ? 'selected' : '' }}>
                                        {{ $status }}
                                    </option>
                                    @endforeach
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
                        @elseif ($statusFilter == 'Out for Delivery')
                        <strong>Out for Delivery:</strong> {{ $totalOutForDelivery }}
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
                                <th>Payment Method</th>
                                <th>Address</th>
                                <th>Contact #</th>
                                <!-- <th>Created At</th> -->
                                <th>Update Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                            <tr>
                                <td class="clickable-row" data-order="{{ json_encode($order) }}" data-bs-toggle="modal" data-bs-target="#detailsModal">{{ $orders->firstItem() + $loop->index }}</td>
                                <td class="clickable-row" data-order="{{ json_encode($order) }}" data-bs-toggle="modal" data-bs-target="#detailsModal">{{ $order->user->firstname }} {{ $order->user->lastname }}</td>
                                <td class="clickable-row" data-order="{{ json_encode($order) }}" data-bs-toggle="modal" data-bs-target="#detailsModal">
                                    <span class="badge text-capitalize" style="background-color: 
                                        @if($order->status == 'Pending') #ffc107 
                                        @elseif($order->status == 'Processing') #17a2b8 
                                        @elseif($order->status == 'Out for Delivery') #007bff 
                                        @elseif($order->status == 'Completed') #28a745 
                                        @elseif($order->status == 'Cancelled') #dc3545 
                                        @else #6c757d 
                                        @endif; color: white;">
                                        {{ $order->status }}
                                    </span>
                                </td>
                                <td class="clickable-row" data-order="{{ json_encode($order) }}" data-bs-toggle="modal" data-bs-target="#detailsModal">
                                    <ul class="list-unstyled">
                                        <li>
                                            {{ $order->product->title }} ({{ $order->size }}) - ₱{{ number_format($order->price, 2) }}
                                        </li>
                                    </ul>
                                </td>
                                <td class="clickable-row" data-order="{{ json_encode($order) }}" data-bs-toggle="modal" data-bs-target="#detailsModal">
                                    ₱{{ number_format($order->total_price, 2) }}
                                </td>
                                <td class="clickable-row" data-order="{{ json_encode($order) }}" data-bs-toggle="modal" data-bs-target="#detailsModal">{{ $order->payment_method }}</td>
                                <td class="clickable-row" data-order="{{ json_encode($order) }}" data-bs-toggle="modal" data-bs-target="#detailsModal">{{ $order->address }}</td>
                                <td class="clickable-row" data-order="{{ json_encode($order) }}" data-bs-toggle="modal" data-bs-target="#detailsModal">{{ $order->phone }}</td>
                                <!-- <td>{{ $order->created_at->format('M d, Y H:i') }}</td> -->
                                <td>
                                    <form action="{{ route('admin.orders.update', $order) }}" method="POST" class="d-flex">
                                        @csrf
                                        @method('PUT')
                                        <select name="status" class="form-select me-2 w-auto" {{ in_array($order->status, ['Cancelled', 'Completed']) ? 'disabled' : '' }}>
                                            <option value="Pending" {{ $order->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="Processing" {{ $order->status == 'Processing' ? 'selected' : '' }}>Processing</option>
                                            <option value="Out for Delivery" {{ $order->status == 'Out for Delivery' ? 'selected' : '' }}>Out for Delivery</option>
                                            <option value="Completed" {{ $order->status == 'Completed' ? 'selected' : '' }} disabled>Completed</option>
                                            <option value="Cancelled" {{ $order->status == 'Cancelled' ? 'selected' : '' }} disabled>Cancelled</option>
                                        </select>
                                        <button type="submit" class="btn btn-sm btn-danger" {{ in_array($order->status, ['Cancelled', 'Completed']) ? 'disabled' : '' }}>Update</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="text-center">No orders found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <!-- Pagination Section -->
                    <div class="d-flex justify-content-center mt-1">
                        <div aria-label="Page navigation">
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Details Modal -->
    <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailsModalLabel">Order Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <p class="text-gray-900"><strong>User:</strong> <span id="modalUser"></span></p>
                            <p class="text-gray-900"><strong>Status:</strong> <span id="modalStatus"></span></p>
                            <p class="text-gray-900"><strong>Products:</strong> <span id="modalProducts"></span></p>
                            <p class="text-gray-900"><strong>Total Price:</strong> <span id="modalTotalPrice"></span></p>
                            <p class="text-gray-900"><strong>Payment Method:</strong> <span id="modalPaymentMethod"></span></p>
                            <p class="text-gray-900"><strong>Address:</strong> <span id="modalAddress"></span></p>
                            <p class="text-gray-900"><strong>Contact #:</strong> <span id="modalPhone"></span></p>
                            <p class="text-gray-900"><strong>Created At:</strong> <span id="modalCreatedAt"></span></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".clickable-row").forEach(function(row) {
                row.addEventListener("click", function() {
                    const order = JSON.parse(this.getAttribute("data-order"));

                    document.getElementById("modalUser").innerText = `${order.user.firstname} ${order.user.lastname}`;
                    document.getElementById("modalStatus").innerText = order.status;
                    document.getElementById("modalProducts").innerHTML = `<ul><li>${order.product.title} (${order.size}) - ₱${parseFloat(order.price).toFixed(2)}</li></ul>`;
                    document.getElementById("modalTotalPrice").innerText = `₱${parseFloat(order.total_price).toFixed(2)}`;
                    document.getElementById("modalPaymentMethod").innerText = order.payment_method;
                    document.getElementById("modalAddress").innerText = order.address;
                    document.getElementById("modalPhone").innerText = order.phone;
                    document.getElementById("modalCreatedAt").innerText = order.created_at ? new Date(order.created_at).toLocaleString() : 'N/A';
                });
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

        .clickable-row {
            cursor: pointer;
        }

        .clickable-row:hover {
            background-color: #f8f9fa;
            /* Light gray background on hover */
        }
    </style>
</x-app-layout>