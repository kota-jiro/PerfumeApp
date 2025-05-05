<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Transaction History') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4">
                <a href="{{ route('client.orders.index') }}" class="text-gray-500 hover:underline">
                    &larr; Back
                </a>
            </div>
            <div class="card shadow-sm">
                <!-- <h3 class="p-3">Transaction History</h3> -->
                <div class="card-body">
                    <!-- Filter by Date -->
                    <form method="GET" action="{{ route('client.transaction.index') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="date" class="form-label">Date:</label>
                                <input type="date" id="date" name="date" class="form-control" value="{{ request('date') }}">

                            </div>
                            <div class="col-md-6 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary me-2">Filter</button>
                                <a href="{{ route('client.transaction.index') }}" class="btn btn-secondary">Reset</a>
                            </div>
                        </div>
                    </form>

                    <div class="mb-3">
                        @if(request('date'))
                        <span><strong>Total Orders on {{ request('date') }}:</strong> {{ $totalOrders }}</span>
                        @else
                        <span><strong>Total Orders:</strong> {{ $totalOrders }}</span>
                        @endif
                    </div>

                    <div class="row row-cols-1 row-cols-md-4 g-4">
                        @forelse($orders as $order)
                        @if (strtolower($order->status) === 'completed' || strtolower($order->status) === 'cancelled')
                        <div class="col">
                            <div class="card h-100 position-relative">
                                <!-- Status Badge -->
                                <span class="badge text-capitalize position-absolute top-0 end-0 m-2" style="background-color: 
                                    {{ strtolower($order->status) === 'completed' ? '#28a745' : (strtolower($order->status) === 'cancelled' ? '#dc3545' : '#6c757d') }};
                                    color: white;">
                                    {{ ucfirst($order->status) }}
                                </span>

                                <div class="card-body">
                                    <h5 class="card-title">Order #{{ $orders->firstItem() + $loop->index }}</h5>
                                    <p class="card-text text-gray-900">
                                        <strong>Product Details:</strong><br><br>
                                        <strong>User:</strong> {{ $order->user->firstname }} {{ $order->user->lastname }}<br>
                                        <strong>Phone:</strong> {{ $order->user->phone }}<br>
                                        <strong>Address:</strong> {{ $order->user->address }}


                                    <ul class="list-unstyled text-center">
                                        <li>
                                            <span class="fw-bold">{{ $order->product->title }}</span>
                                            ({{ $order->size }}) -
                                            <span class="text-success">₱{{ number_format($order->price, 2) }}</span>
                                            <span>({{ $order->quantity }})</span>
                                        </li>
                                    </ul>
                                    </p>

                                    <span class="text-center text-sm">Payment Method: Cash on Delivery</span>
                                    <p class="card-text text-gray-900">
                                        <strong>Delivery Fee:</strong>
                                        <span> ₱{{ number_format($order->total_price - $order->price  * $order->quantity, 2) }}</span><br>
                                        <strong>Total Amount:</strong>
                                        <span class="text-success">₱{{ number_format($order->total_price, 2) }}</span>
                                        <strong>Order Date:</strong>
                                        {{ $order->created_at->format('F d, Y \a\t h:i A') }}
                                    </p>
                                    @if (strtolower($order->status) === 'completed')
                                    <p class="text-success mt-2">
                                        <strong>Note:</strong> Your order has been completed. Thank you for shopping with us!
                                    </p>
                                    @elseif (strtolower($order->status) === 'cancelled')
                                    <p class="text-danger mt-2">
                                        <strong>Note:</strong> Your order has been cancelled. If you have any questions, please contact support.
                                    </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif
                        @empty
                        <div class="col-12 text-center">
                        </div>
                        @endforelse
                        @if ($orders->isEmpty())
                        <div class="col-span-1 text-center">
                            <p class="text-black">No transaction found.</p>
                        </div>
                        @endif
                    </div>

                    <!-- Pagination Section -->
                    <div class="d-flex justify-content-center mt-3">
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