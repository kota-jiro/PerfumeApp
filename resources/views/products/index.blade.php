<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-[var(--primary-color)] leading-tight">
            {{ __('Products') }}
        </h2>
    </x-slot>

    <div class="py-6 bg-light">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
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
                                    <img src="{{ asset("images/products/{$product->image}") }}" alt="{{ $product->title }}" class="card-img-top rounded-lg" style="height: 200px; object-fit: cover;">
                                    <div class="card-body">
                                        <h5 class="card-title text-[var(--primary-color)]">{{ $product->title }}</h5>
                                        <p class="card-text text-gray-700" style="height: 75px; overflow: hidden; text-overflow: ellipsis;" title="{{ $product->description }}">{{ \Str::limit($product->description, 50, '...') }}</p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>

                    <!-- Pagination Section -->
                    <div class="d-flex justify-content-center mt-3">
                        <div aria-label="Page navigation">
                            <ul class="pagination pagination-sm">
                                @if ($filteredProducts->lastPage() > 1)
                                {{-- Previous Page Link --}}
                                <li class="page-item {{ $filteredProducts->onFirstPage() ? 'disabled' : '' }}">
                                    <a class="page-link" href="{{ $filteredProducts->previousPageUrl() }}{{ request()->has('usertype') ? '&usertype=' . request('usertype') : '' }}" tabindex="-1" aria-disabled="{{ $filteredProducts->onFirstPage() ? 'true' : 'false' }}">
                                        &laquo;
                                    </a>
                                </li>

                                {{-- Pagination Elements --}}
                                @for ($i = 1; $i <= $filteredProducts->lastPage(); $i++)
                                    <li class="page-item {{ $i == $filteredProducts->currentPage() ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $filteredProducts->url($i) }}{{ request()->has('usertype') ? '&usertype=' . request('usertype') : '' }}">{{ $i }}</a>
                                    </li>
                                    @endfor

                                    {{-- Next Page Link --}}
                                    <li class="page-item {{ $filteredProducts->hasMorePages() ? '' : 'disabled' }}">
                                        <a class="page-link" href="{{ $filteredProducts->nextPageUrl() }}{{ request()->has('usertype') ? '&usertype=' . request('usertype') : '' }}" aria-disabled="{{ $filteredProducts->hasMorePages() ? 'false' : 'true' }}">
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

    <!-- Modal -->
    <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productModalLabel">Product Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-dark">
                    <img id="modalProductImage" src="" class="img-fluid mb-3 rounded" alt="Product Image" style="max-height: 250px; object-fit: contain; width: 100%;">
                    <h4 id="modalProductTitle" class="text-[var(--primary-color)]"></h4>
                    <p id="modalProductDescription" class="text-secondary"></p>
                    <p class="mb-1"><strong class="text-dark">Price:</strong> ₱<span id="modalProductPrice" class="text-danger fw-bold"></span></p>
                </div>
                <div class="m-3 px-3">
                    <label for="productSize" class="form-label">Select Size:</label>
                    <select id="productSize" class="form-select">
                        <!-- Options will be dynamically populated -->
                    </select>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-outline-danger" id="addToCartBtn">Add to Cart</button>
                    <button class="btn btn-danger" id="checkoutBtn" style="pointer: none; cursor: not-allowed;">Checkout</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Cart Section -->
    <div class="fixed-bottom p-4 d-flex justify-content-end">
        <button class="btn btn-dark rounded-pill px-4" id="viewCartBtn" hidden>View Cart (<span id="cartCount">0</span>)</button>
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
                    <button class="btn btn-success" id="finalCheckout">Confirm Checkout All</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="alertModalLabel">Notice</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="alertModalMessage">
                    <!-- Message will be dynamically inserted here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">OK</button>
                    <a href="{{ route('cart.index') }}" class="btn btn-danger">View Cart</a>
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

                // Update modal content
                document.getElementById('modalProductTitle').innerText = product.title;
                document.getElementById('modalProductDescription').innerText = product.description;
                document.getElementById('modalProductImage').src = `/images/products/${product.image}`;

                // Update price and disable out-of-stock sizes
                const sizeSelect = document.getElementById('productSize');
                sizeSelect.innerHTML = `
                    <option value="small" ${product.stock_small <= 0 ? 'disabled' : ''}>Small - ₱${parseFloat(product.price_small).toFixed(2)}</option>
                    <option value="medium" ${product.stock_medium <= 0 ? 'disabled' : ''}>Medium - ₱${parseFloat(product.price_medium).toFixed(2)}</option>
                    <option value="large" ${product.stock_large <= 0 ? 'disabled' : ''}>Large - ₱${parseFloat(product.price_large).toFixed(2)}</option>
                `;

                // Set initial price based on stock availability
                if (product.stock_large > 0) {
                    sizeSelect.value = 'large';
                    document.getElementById('modalProductPrice').innerText = parseFloat(product.price_large).toFixed(2);
                } else if (product.stock_medium > 0) {
                    sizeSelect.value = 'medium';
                    document.getElementById('modalProductPrice').innerText = parseFloat(product.price_medium).toFixed(2);
                } else if (product.stock_small > 0) {
                    sizeSelect.value = 'small';
                    document.getElementById('modalProductPrice').innerText = parseFloat(product.price_small).toFixed(2);
                }

                // Show modal
                new bootstrap.Modal(document.getElementById('productModal')).show();
            });
        });

        document.getElementById('productSize').addEventListener('change', (e) => {
            const selectedSize = e.target.value;
            let price = 0;

            switch (selectedSize) {
                case 'small':
                    price = selectedProduct.price_small;
                    break;
                case 'medium':
                    price = selectedProduct.price_medium;
                    break;
                case 'large':
                    price = selectedProduct.price_large;
                    break;
            }

            document.getElementById('modalProductPrice').innerText = parseFloat(price).toFixed(2);
        });

        // Set default size and price based on stock availability
        document.addEventListener('DOMContentLoaded', () => {
            const sizeSelect = document.getElementById('productSize');
            if (selectedProduct.stock_large > 0) {
                sizeSelect.value = 'large';
                document.getElementById('modalProductPrice').innerText = parseFloat(selectedProduct.price_large).toFixed(2);
            } else if (selectedProduct.stock_medium > 0) {
                sizeSelect.value = 'medium';
                document.getElementById('modalProductPrice').innerText = parseFloat(selectedProduct.price_medium).toFixed(2);
            } else if (selectedProduct.stock_small > 0) {
                sizeSelect.value = 'small';
                document.getElementById('modalProductPrice').innerText = parseFloat(selectedProduct.price_small).toFixed(2);
            }
        });

        document.getElementById('addToCartBtn').addEventListener('click', async () => {
            if (selectedProduct) {
                const selectedSize = document.getElementById('productSize').value;

                let price = 0;
                let stock = 0;

                // Determine price and stock based on selected size
                switch (selectedSize) {
                    case 'small':
                        price = selectedProduct.price_small;
                        stock = selectedProduct.stock_small;
                        break;
                    case 'medium':
                        price = selectedProduct.price_medium;
                        stock = selectedProduct.stock_medium;
                        break;
                    case 'large':
                        price = selectedProduct.price_large;
                        stock = selectedProduct.stock_large;
                        break;
                }

                // Check if the selected size is in stock
                if (stock <= 0) {
                    showAlert('This size is out of stock!');
                    return; // Prevent adding to cart
                }

                // Check if the product with the same size already exists in the cart
                const existingCartItemIndex = cart.findIndex(
                    (item) => item.product_id === selectedProduct.id && item.size === selectedSize
                );

                if (existingCartItemIndex !== -1) {
                    // Update the quantity of the existing item
                    const existingCartItem = cart[existingCartItemIndex];
                    if (existingCartItem.quantity + 1 > stock) {
                        showAlert(`Only ${stock} items are available in stock.`);
                        return;
                    }
                    existingCartItem.quantity += 1;
                } else {
                    // Add new product to the cart
                    cart.push({
                        product_name: selectedProduct.title,
                        price: price,
                        size: selectedSize,
                        image: selectedProduct.image,
                        product_id: selectedProduct.id,
                        stock: stock, // Include available stock
                        quantity: 1, // Default quantity is 1
                    });
                }

                // Save the cart to localStorage for the specific user
                const userId = document.querySelector('meta[name="user-id"]').getAttribute('content');
                localStorage.setItem(`cart_${userId}`, JSON.stringify(cart));

                // Update cart count and close modal
                document.getElementById('cartCount').innerText = cart.length;
                bootstrap.Modal.getInstance(document.getElementById('productModal')).hide();
            }
            const selectedSize = document.getElementById('productSize').value;
            const token = '{{ csrf_token() }}';

            try {
                const response = await fetch("{{ route('cart.store') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                    body: JSON.stringify({
                        product_id: selectedProduct.id,
                        size: selectedSize
                    })
                });

                const result = await response.json();

                if (result.success) {
                    document.getElementById('alertModalMessage').innerText = result.message;
                    new bootstrap.Modal(document.getElementById('alertModal')).show();
                } else {
                    throw new Error(result.message || 'Error adding to cart.');
                }
            } catch (error) {
                console.error(error);
                document.getElementById('alertModalMessage').innerText = 'Something went wrong.';
                new bootstrap.Modal(document.getElementById('alertModal')).show();
            }
        });

        document.getElementById('viewCartBtn').addEventListener('click', () => {
            const cartItemsDiv = document.getElementById('cartItems');
            cartItemsDiv.innerHTML = '';
            if (cart.length === 0) {
                cartItemsDiv.innerHTML = '<p>No products in cart.</p>';
            } else {
                cart.forEach((item, index) => {
                    cartItemsDiv.innerHTML += `
                <div class="mb-2 d-flex justify-content-between align-items-center">
                    <div>
                    <strong>${item.product_name}</strong> (${item.size}) - ₱${parseFloat(item.price).toFixed(2)}
                    <br>
                    <label for="quantity-${index}" class="form-label">Quantity:</label>
                    <input type="number" id="quantity-${index}" class="form-control form-control-sm" value="${item.quantity}" min="1" max="${item.stock}" onchange="updateQuantity(${index}, this.value)">
                    <small class="text-muted">Available stock: ${item.stock}</small>
                    </div>
                    <div class="d-flex flex-column">
                    <button class="btn btn-sm btn-danger mb-2" onclick="removeFromCart(${index})">Remove</button>
                    <button class="btn btn-sm btn-success" onclick="checkoutProduct(${index})">Checkout</button>
                    </div>
                </div>
                `;
                });
            }
            new bootstrap.Modal(document.getElementById('cartModal')).show();
        });

        function checkoutProduct(index) {
            const product = cart[index];
            const formattedProduct = {
                product_name: product.product_name,
                price: product.price,
                size: product.size,
                image: product.image,
                product_id: product.product_id,
                quantity: product.quantity
            };

            // Perform checkout logic (e.g., send to server)
            console.log('Checked out product:', formattedProduct);

            // Remove item from cart
            cart.splice(index, 1);

            // Update localStorage
            const userId = document.querySelector('meta[name="user-id"]').getAttribute('content');
            localStorage.setItem(`cart_${userId}`, JSON.stringify(cart));

            // Update UI
            document.getElementById('cartCount').innerText = cart.length;
            document.getElementById('viewCartBtn').click(); // Refresh cart modal
        }

        // Function to update the quantity of a product in the cart
        function updateQuantity(index, newQuantity) {
            const item = cart[index];
            const quantity = parseInt(newQuantity);

            if (isNaN(quantity) || quantity < 1 || quantity > item.stock) {
                showAlert(`Quantity must be between 1 and ${item.stock}.`);
                document.getElementById(`quantity-${index}`).value = item.quantity;
                return;
            }

            item.quantity = quantity;

            const userId = document.querySelector('meta[name="user-id"]').getAttribute('content');
            localStorage.setItem(`cart_${userId}`, JSON.stringify(cart));
        }


        // Function to remove a product from the cart
        function removeFromCart(index) {
            cart.splice(index, 1);

            const userId = document.querySelector('meta[name="user-id"]').getAttribute('content');
            localStorage.setItem(`cart_${userId}`, JSON.stringify(cart));

            document.getElementById('cartCount').innerText = cart.length;
            document.getElementById('viewCartBtn').click(); // Refresh cart modal
        }

        document.getElementById('finalCheckout').addEventListener('click', () => {
            if (cart.length === 0) {
                showAlert('Cart is empty.');
                return;
            }

            // Example: send cart to server via AJAX (adjust endpoint and data structure as needed)
            console.log('Checking out full cart:', cart);

            // Clear cart
            const userId = document.querySelector('meta[name="user-id"]').getAttribute('content');
            localStorage.removeItem(`cart_${userId}`);
            cart = [];

            document.getElementById('cartCount').innerText = 0;
            bootstrap.Modal.getInstance(document.getElementById('cartModal')).hide();
            showAlert('Checkout completed!');
        });

        document.getElementById('cartModal').addEventListener('hidden.bs.modal', () => {
            // Update the cart count
            document.getElementById('cartCount').innerText = cart.length;

            // Refresh the cart modal content
            const cartItemsDiv = document.getElementById('cartItems');
            cartItemsDiv.innerHTML = '';
            if (cart.length === 0) {
                cartItemsDiv.innerHTML = '<p>No products in cart.</p>';
            } else {
                cart.forEach((item, index) => {
                    cartItemsDiv.innerHTML += `
                        <div class="mb-2 d-flex justify-content-between align-items-center">
                            <div>
                                <strong>${item.product_name}</strong> (${item.size}) - ₱${parseFloat(item.price).toFixed(2)}
                                <br>
                                <label for="quantity-${index}" class="form-label">Quantity:</label>
                                <input type="number" id="quantity-${index}" class="form-control form-control-sm" value="${item.quantity}" min="1" max="${item.stock}" onchange="updateQuantity(${index}, this.value)">
                                <small class="text-muted">Available stock: ${item.stock}</small>
                            </div>
                            <button class="btn btn-sm btn-danger" onclick="removeFromCart(${index})">Remove</button>
                        </div>
                    `;
                });
            }
        });

        document.addEventListener('DOMContentLoaded', () => {
            const userId = document.querySelector('meta[name="user-id"]').getAttribute('content');
            const storedCart = localStorage.getItem(`cart_${userId}`);
            if (storedCart) {
                cart = JSON.parse(storedCart);
                document.getElementById('cartCount').innerText = cart.length;
            }
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