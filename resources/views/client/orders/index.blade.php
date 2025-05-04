<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Orders') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="card shadow-sm">
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

                    <div class="row row-cols-1 row-cols-md-4 g-4">
                        @forelse($orders as $order)
                        <div class="col">
                            <div class="card h-100 position-relative">
                                <!-- Status Badge -->
                                <span class="badge bg-secondary text-capitalize position-absolute top-0 end-0 m-2">
                                    {{ ucfirst($order->status) }}
                                </span>

                                <div class="card-body">
                                    <h5 class="card-title">Order #{{ $loop->iteration }}</h5>
                                    <p class="card-text text-gray-900">
                                        <strong>Product Details:</strong>
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
                                        <strong>Total Amount:</strong> 
                                        <span class="text-success">₱{{ number_format($order->total_price, 2) }}</span>
                                    </p>
                                    <p class="card-text text-gray-900">
                                        <strong>Order Date:</strong> 
                                        {{ $order->created_at->format('F d, Y \a\t h:i A') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12 text-center">
                            <p>No orders found.</p>
                        </div>
                        @endforelse
                    </div>

                    <!-- Pagination Section -->
                    <div class="d-flex justify-content-center mt-3">
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
