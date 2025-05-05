<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Orders') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4">
                <a href="{{ route('cart.index') }}" class="text-gray-500 hover:underline">
                    &larr; Back
                </a>
            </div>
            <div class="card shadow-sm mb-6">
                <h3 class="p-3">Current Orders</h3>
                <div class="card-body">
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

                    <div class="mb-3">
                        <span><strong>Total Orders: </strong>{{ $orderCount }}</span>
                    </div>

                    <div class="row row-cols-1 row-cols-md-4 g-4">
                        @php
                        $currentOrders = $orders->filter(fn($order) => in_array(strtolower($order->status), ['pending', 'processing', 'out for delivery']));
                        @endphp
                        @forelse($orders as $order)
                        @if (in_array(strtolower($order->status), ['pending', 'processing', 'out for delivery']))
                        <div class="col">
                            <div class="card h-100 position-relative">
                                <!-- Status Badge -->
                                <span class="badge text-capitalize position-absolute top-0 end-0 m-2" style="background-color: 
                                    {{ strtolower($order->status) === 'pending' ? '#ffc107' : 
                                       (strtolower($order->status) === 'processing' ? '#17a2b8' : 
                                       (strtolower($order->status) === 'out for delivery' ? '#007bff' : '#6c757d')) }};
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
                                        <span> ₱{{ number_format($order->total_price - $order->price * $order->quantity, 2) }}</span><br>
                                        <strong>Total Amount:</strong>
                                        <span class="text-success">₱{{ number_format($order->total_price, 2) }}</span>
                                        <strong>Order Date:</strong>
                                        {{ $order->created_at->format('F d, Y \a\t h:i A') }}
                                    </p>
                                    @if (strtolower($order->status) === 'processing')
                                    <p class="text-warning mt-2">
                                        <strong>Note:</strong> Your order is currently being processed. Please allow some time for updates.
                                    </p>
                                    <div class="d-flex justify-content-between">
                                        <form action="{{ route('orders.updateStatus', ['order' => $order->id]) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="Completed">
                                            <button type="submit" class="btn btn-sm btn-success" hidden>Order Received</button>
                                        </form>
                                        <form action="{{ route('orders.updateStatus', ['order' => $order->id]) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="Cancelled">
                                            <button type="submit" class="btn btn-sm btn-danger" hidden>Cancel Order</button>
                                        </form>
                                    </div>
                                    @elseif (strtolower($order->status) === 'pending')
                                    <p class="text-info mt-2">
                                        <strong>Note:</strong> Your order is pending. Please check back later for updates.
                                    </p>
                                    <div class="d-flex justify-content-between">
                                        <form action="{{ route('orders.updateStatus', ['order' => $order->id]) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="Completed">
                                            <button type="submit" class="btn btn-sm btn-success" hidden>Order Received</button>
                                        </form>
                                        <form action="{{ route('orders.updateStatus', ['order' => $order->id]) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="Cancelled">
                                            <button type="submit" class="btn btn-sm btn-danger">Cancel Order</button>
                                        </form>
                                    </div>
                                    @elseif (strtolower($order->status) === 'out for delivery')
                                    <p class="text-primary mt-2">
                                        <strong>Note:</strong> Your order is out for delivery. It should arrive soon!
                                    </p>
                                    <div class="d-flex justify-content-between">
                                        <form action="{{ route('orders.updateStatus', ['order' => $order->id]) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="Completed">
                                            <button type="submit" class="btn btn-sm btn-success">Order Received</button>
                                        </form>
                                        <form action="{{ route('orders.updateStatus', ['order' => $order->id]) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="Cancelled">
                                            <button type="submit" class="btn btn-sm btn-danger" hidden>Cancel Order</button>
                                        </form>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif
                        @empty
                        <div class="col-span-1 text-center">
                        </div>
                        @endforelse
                        @if ($currentOrders->isEmpty())
                        <div class="text-center">
                            <p class="col-span-2 text-black">No orders found.</p>
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