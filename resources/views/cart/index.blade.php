<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Cart') }}
        </h2>
    </x-slot>

    <div class="container mt-4">
        @if(session('success'))
        <div class="alert alert-success mt-2" id="success-alert">
            {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger mt-2" id="error-alert">
            {{ session('error') }}
        </div>
        @endif

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                setTimeout(function() {
                    const successAlert = document.getElementById('success-alert');
                    const errorAlert = document.getElementById('error-alert');
                    if (successAlert) successAlert.style.display = 'none';
                    if (errorAlert) errorAlert.style.display = 'none';
                }, 3000);
            });
        </script>
        
        <div class="mb-4">
            <a href="{{ route('products.index') }}" class="text-gray-500 hover:underline">
                &larr; Back
            </a>
        </div>
        @if($carts->isEmpty())
        <div class="alert alert-info mt-2 text-center">
            Your cart is empty. <a href="{{ route('products.index') }}" class="btn btn-primary btn-sm">Shop Now</a>
        </div>
        @else
        <div class="row row-cols-1 row-cols-md-4 g-4">
            @foreach ($carts as $cart)
            <div class="col">
                <div class="card shadow-sm h-100" style="max-height: 500px; border-radius: 10px; overflow: hidden;">
                    <img src="{{ asset('images/products/'.$cart->product->image) }}" class="card-img-top" alt="{{ $cart->product->title }}" style="height: 200px; object-fit: contain;">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title text-truncate">{{ $cart->product->title }}</h5>
                        <span class="badge bg-secondary mb-2">Category: {{ $cart->product->category }}</span>
                        <span class="card-text text-gray-900 mb-2">Size: {{ $cart->size }}</span>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="card-text text-gray-900">Quantity: {{ $cart->quantity }}</span>
                            <span class="card-text text-gray-900 fw-bold text-end">₱{{ number_format($cart->price, 2) }}</span>
                        </div>
                        <form action="{{ route('cart.update', $cart->id) }}" method="POST" class="mb-2">
                            @csrf
                            @method('PUT')
                            <div class="input-group">
                                <input type="number" name="quantity" value="{{ $cart->quantity }}" min="1" max="{{ $cart->size === 'small' ? $cart->product->stock_small : ($cart->size === 'medium' ? $cart->product->stock_medium : ($cart->size === 'large' ? $cart->product->stock_large : 0)) }}" class="form-control" style="width: 80px;" oninput="this.setCustomValidity('');" oninvalid="this.setCustomValidity('Caution: The available stock is {{ $cart->size === 'small' ? $cart->product->stock_small : ($cart->size === 'medium' ? $cart->product->stock_medium : ($cart->size === 'large' ? $cart->product->stock_large : 0)) }}.')">
                                <button class="btn btn-primary btn-sm">Update</button>
                            </div>
                        </form>
                        <div class="d-flex justify-content-between mb-2">
                            <small class="card-text text-gray-900">
                                Available Stock:
                                @if($cart->size === 'small')
                                {{ $cart->product->stock_small }}
                                @elseif($cart->size === 'medium')
                                {{ $cart->product->stock_medium }}
                                @elseif($cart->size === 'large')
                                {{ $cart->product->stock_large }}
                                @else
                                <p>No stock available.</p>
                                @endif
                            </small>
                            <small>
                                Total: ₱{{ number_format(($cart->price + ($cart->size === 'small' ? 300 : ($cart->size === 'medium' ? 500 : ($cart->size === 'large' ? 800 : 0)))) * $cart->quantity, 2) }}
                            </small>
                        </div>
                        <div class="mt-auto">
                            <div class="d-flex justify-content-between">
                                <button class="btn btn-danger btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#confirmModal"
                                    data-id="{{ $cart->id }}"
                                    data-url="{{ route('cart.delete', $cart->id) }}">
                                    Remove
                                </button>

                                <button class="btn btn-success btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#checkoutModal"
                                    data-id="{{ $cart->id }}"
                                    data-title="{{ $cart->product->title }}"
                                    data-url="{{ route('checkout.product', $cart->id) }}">
                                    Checkout
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="card mt-4 shadow-sm mb-4">
            <div class="card-body text-end">
                @php
                $total = $carts->sum(function($item) {
                    $additionalPrice = 0;
                    if ($item->size === 'small') {
                        $additionalPrice = 300;
                    } elseif ($item->size === 'medium') {
                        $additionalPrice = 500;
                    } elseif ($item->size === 'large') {
                        $additionalPrice = 800;
                    }
                    return ($item->price + $additionalPrice) * $item->quantity;
                });
                @endphp
                <h5 class="fw-bold">Total: <span class="text-success">₱{{ number_format($total, 2) }}</span></h5>
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#checkoutAllModal">
                    Checkout All
                </button>
            </div>
        </div>
        @endif
    </div>

    <!-- Modal -->
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModalLabel">Confirm Removal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to remove <span id="productTitle" class="fw-bold"></span> from your cart?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form id="deleteForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Yes, Remove</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Checkout Confirmation Modal -->
    <div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="checkoutModalLabel">Confirm Checkout</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h6>Payment Method</h6>
                    <p class="text-black">Please select a payment method:</p>
                    <form id="checkoutForm" method="POST">
                        @csrf
                        <select class="form-select mb-3" name="payment_method" id="payment_method">
                            <option value="COD" selected>Cash on Delivery (COD)</option>
                            <option value="Pick-Up" disabled>Pick-Up</option>
                            <option value="Credit Card" disabled>Credit Card</option>
                            <option value="Debit Card" disabled>Debit Card</option>
                            <option value="Gcash" disabled>Gcash</option>
                        </select>
                        <small>Cash on Delivery is available for now.</small>
                        <p class="text-black">
                            <strong>Note:</strong> The total amount will be calculated based on the selected size and quantity. No returns or exchanges are allowed after checkout.
                            <br>
                        </p>
                        Are you sure you want to checkout <span id="checkoutProductTitle" class="fw-bold text-black"></span>?
                        <div class="modal-footer mt-3">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success">Yes, Checkout</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Checkout All Confirmation Modal -->
    <div class="modal fade" id="checkoutAllModal" tabindex="-1" aria-labelledby="checkoutAllModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="checkoutAllModalLabel">Confirm Checkout All</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h6>Payment Method</h6>
                    <p class="text-black">Please select a payment method:</p>
                    <form id="checkoutAllForm" action="{{ route('checkout.all') }}" method="POST">
                        @csrf
                        <select class="form-select mb-3" name="payment_method_all" id="payment_method_all">
                            <option value="COD" selected>Cash on Delivery (COD)</option>
                            <option value="Pick-Up" disabled>Pick-Up</option>
                            <option value="Credit Card" disabled>Credit Card</option>
                            <option value="Debit Card" disabled>Debit Card</option>
                            <option value="Gcash" disabled>Gcash</option>
                        </select>
                        <small>Cash on Delivery is available for now.</small>
                        <p class="text-black">
                            <strong>Note:</strong> The total amount will be calculated based on the selected size and quantity. No returns or exchanges are allowed after checkout.
                            <br>
                        </p>
                        Are you sure you want to checkout all items in your cart?
                        <div class="modal-footer mt-3">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success">Yes, Checkout All</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const confirmModal = document.getElementById('confirmModal');
            const deleteForm = document.getElementById('deleteForm');

            confirmModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const productTitle = button.closest('.card').querySelector('.card-title').textContent;

                // Set the product title in the modal
                document.getElementById('productTitle').textContent = productTitle;

                // Set form action using route URL passed via data-url
                deleteForm.action = button.getAttribute('data-url');
            });
        });


        document.addEventListener('DOMContentLoaded', function() {
            const checkoutModal = document.getElementById('checkoutModal');
            const checkoutForm = document.getElementById('checkoutForm');

            checkoutModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const productTitle = button.getAttribute('data-title');
                const checkoutUrl = button.getAttribute('data-url');

                // Set the product title in the modal
                document.getElementById('checkoutProductTitle').textContent = productTitle;

                // Set the form action
                checkoutForm.action = checkoutUrl;
            });
        });
    </script>
</x-app-layout>