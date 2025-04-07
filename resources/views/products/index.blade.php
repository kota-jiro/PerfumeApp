<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-[var(--primary-color)] leading-tight">
            {{ __('Products') }}
        </h2>
    </x-slot>

    <div class="py-6 bg-light">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Categories Section -->
                    <h3 class="font-semibold text-xl mb-4 text-[var(--primary-color)]">Categories</h3>
                    @php $activeCategory = request()->category ?? 'All'; @endphp
                    <div class="d-flex justify-content-start gap-4">
                        <a href="{{ route('products.filter', ['category' => 'All']) }}" class="btn btn-outline-danger rounded-pill px-4 {{ $activeCategory == 'All' ? 'active' : '' }}">All ({{ $total }})</a>
                        <a href="{{ route('products.filter', ['category' => 'Male Perfume']) }}" class="btn btn-outline-danger rounded-pill px-4 {{ $activeCategory == 'Male Perfume' ? 'active' : '' }}">Male Perfume ({{ $maleCount }})</a>
                        <a href="{{ route('products.filter', ['category' => 'Female Perfume']) }}" class="btn btn-outline-danger rounded-pill px-4 {{ $activeCategory == 'Female Perfume' ? 'active' : '' }}">Female Perfume ({{ $femaleCount }})</a>
                    </div>

                    <!-- Product Cards -->
                    <div class="mt-6">
                        @if(request()->category)
                        <h3 class="font-semibold text-2xl text-[var(--primary-color)] mt-6">{{ request()->category }}</h3>
                        @endif

                        @if($filteredProducts->isEmpty())
                        <p class="mt-4 text-danger">No products found for this category.</p>
                        @else
                        <div class="row row-cols-1 row-cols-md-4 g-4">
                            @foreach ($filteredProducts as $product)
                            <div class="col">
                                <div class="card h-100 shadow-md border-0 rounded-lg product-card" data-product='@json($product)'>
                                    <img src="{{ asset('images/products/' . $product->image) }}" alt="{{ $product->title }}" class="card-img-top rounded-lg" style="height: 200px; object-fit: cover;">
                                    <div class="card-body">
                                        <h5 class="card-title text-[var(--primary-color)]">{{ $product->title }}</h5>
                                        <p class="card-text text-gray-700" style="height: 75px; overflow: hidden; text-overflow: ellipsis;" title="{{ $product->description }}">{{ \Str::limit($product->description, 50, '...') }}</p>
                                        <h6 class="font-semibold text-dark text-lg mb-2">₱ {{ number_format($product->price_small, 2) }}</h6>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productModalLabel">Product Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img id="modalProductImage" src="" class="img-fluid mb-3 rounded" alt="Product Image">
                    <h4 id="modalProductTitle"></h4>
                    <p id="modalProductDescription"></p>
                    <p><strong>Price:</strong> ₱<span id="modalProductPrice"></span></p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-outline-danger" id="addToCartBtn">Add to Cart</button>
                    <button class="btn btn-danger" id="checkoutBtn">Checkout</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Cart Section -->
    <div class="fixed-bottom p-4 d-flex justify-content-end">
        <button class="btn btn-dark rounded-pill px-4" id="viewCartBtn">View Cart (<span id="cartCount">0</span>)</button>
    </div>

    <!-- Cart Modal -->
    <div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cartModalLabel">Your Cart</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="cartItems">
                    <p>No products in cart.</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success" id="finalCheckout">Confirm Checkout</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let selectedProduct = null;
        let cart = [];

        document.querySelectorAll('.product-card').forEach(card => {
            card.addEventListener('click', () => {
                const product = JSON.parse(card.getAttribute('data-product'));
                selectedProduct = product;
                document.getElementById('modalProductTitle').innerText = product.title;
                document.getElementById('modalProductDescription').innerText = product.description;
                document.getElementById('modalProductPrice').innerText = parseFloat(product.price_small).toFixed(2);
                document.getElementById('modalProductImage').src = `/images/products/${product.image}`;
                new bootstrap.Modal(document.getElementById('productModal')).show();
            });
        });

        document.getElementById('addToCartBtn').addEventListener('click', () => {
            if (selectedProduct) {
                cart.push(selectedProduct);
                document.getElementById('cartCount').innerText = cart.length;
                bootstrap.Modal.getInstance(document.getElementById('productModal')).hide();
            }
        });

        document.getElementById('viewCartBtn').addEventListener('click', () => {
            const cartItemsDiv = document.getElementById('cartItems');
            cartItemsDiv.innerHTML = '';
            if (cart.length === 0) {
                cartItemsDiv.innerHTML = '<p>No products in cart.</p>';
            } else {
                cart.forEach((item, index) => {
                    cartItemsDiv.innerHTML += `<div class="mb-2"><strong>${item.title}</strong> - ₱${parseFloat(item.price_small).toFixed(2)}</div>`;
                });
            }
            new bootstrap.Modal(document.getElementById('cartModal')).show();
        });

        document.getElementById('finalCheckout').addEventListener('click', () => {
            alert('Checkout successful! Your order is being processed.');
            cart = [];
            document.getElementById('cartCount').innerText = '0';
            bootstrap.Modal.getInstance(document.getElementById('cartModal')).hide();
        });
    </script>
</x-app-layout>