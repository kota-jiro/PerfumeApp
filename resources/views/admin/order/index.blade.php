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
                        <h1 class="mb-6">List Orders</h1>
                        <a href="{{ route('products.index') }}" class="btn btn-primary">+ Add Product</a>
                    </div>
                    @if (session('success'))
                    <div class="alert alert-success" id="success-alert">
                        {{ session('success') }}
                    </div>
                    <script>
                        setTimeout(() => {
                            const alert = document.getElementById('success-alert');
                            if (alert) alert.remove();
                        }, 3000);
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
                                            {{ $order->product_name }} ({{ $order->size }}) - ₱{{ number_format($order->price, 2) }}
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
                                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                            <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                        </select>
                                        <button type="submit" class="btn btn-sm btn-primary">Update</button>
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
                                {{-- Check if there are pages --}}
                                @if ($orders->lastPage() > 1)
                                {{-- Previous Page Link --}}
                                <li class="page-item {{ $orders->onFirstPage() ? 'disabled' : '' }}">
                                    <a class="page-link" href="{{ $orders->previousPageUrl() }}{{ request()->has('status') ? '&status=' . request('status') : '' }}" tabindex="-1" aria-disabled="{{ $orders->onFirstPage() ? 'true' : 'false' }}">
                                        &laquo;
                                    </a>
                                </li>

                                {{-- Pagination Elements --}}
                                @for ($i = 1; $i <= $orders->lastPage(); $i++)
                                    <li class="page-item {{ $i == $orders->currentPage() ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $orders->url($i) }}{{ request()->has('status') ? '&status=' . request('status') : '' }}">{{ $i }}</a>
                                    </li>
                                    @endfor

                                    {{-- Next Page Link --}}
                                    <li class="page-item {{ $orders->hasMorePages() ? '' : 'disabled' }}">
                                        <a class="page-link" href="{{ $orders->nextPageUrl() }}{{ request()->has('status') ? '&status=' . request('status') : '' }}" aria-disabled="{{ $orders->hasMorePages() ? 'false' : 'true' }}">
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
    
</x-app-layout>