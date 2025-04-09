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
                <div class="mt-3 px-3">
                    <label for="productSize" class="form-label">Select Size:</label>
                    <select id="productSize" class="form-select"></select>
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
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
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

                // Set initial price
                document.getElementById('modalProductPrice').innerText = parseFloat(product.price_small).toFixed(2);

                // Show modal
                new bootstrap.Modal(document.getElementById('productModal')).show();
            });
        });

        // Update price when size changes
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

        function showAlert(message) {
            document.getElementById('alertModalMessage').innerText = message;
            new bootstrap.Modal(document.getElementById('alertModal')).show();
        }

        document.getElementById('addToCartBtn').addEventListener('click', () => {
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
                            <button class="btn btn-sm btn-danger" onclick="removeFromCart(${index})">Remove</button>
                        </div>
                    `;
                });
            }
            new bootstrap.Modal(document.getElementById('cartModal')).show();
        });

        // Function to update the quantity of a product in the cart
        function updateQuantity(index, quantity) {
            const maxStock = cart[index].stock;
            if (quantity > maxStock) {
                showAlert(`Only ${maxStock} items are available in stock.`);
                document.getElementById(`quantity-${index}`).value = maxStock; // Reset to max stock
                return;
            }
            cart[index].quantity = parseInt(quantity, 10); // Update the quantity in the cart

            // Save the updated cart to localStorage for the specific user
            const userId = document.querySelector('meta[name="user-id"]').getAttribute('content');
            localStorage.setItem(`cart_${userId}`, JSON.stringify(cart));
        }

        // Function to remove a product from the cart
        function removeFromCart(index) {
            // Remove the product at the specified index
            cart.splice(index, 1);

            // Save the updated cart to localStorage
            localStorage.setItem('cart', JSON.stringify(cart));

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
        }

        document.getElementById('finalCheckout').addEventListener('click', () => {
            if (cart.length === 0) {
                showAlert('Your cart is empty!');
                return;
            }

            const formattedCart = cart.map(item => ({
                product_name: item.product_name,
                price: item.price,
                size: item.size,
                image: item.image,
                product_id: item.product_id,
                quantity: item.quantity // Include quantity in the checkout data
            }));

            fetch("{{ route('checkout.store') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({
                    cart: formattedCart
                }),
            })
            .then(response => response.json())
            .then(data => {
                if (response.ok) {
                    showAlert(data.message);
                    cart = [];
                    document.getElementById('cartCount').innerText = '0';
                    document.getElementById('cartItems').innerHTML = '<p>No products in cart.</p>';
                    bootstrap.Modal.getInstance(document.getElementById('cartModal')).hide();
                } else {
                    showAlert(data.message); // Show error message
                }
            })
            .catch(error => {
                console.error('Checkout failed:', error);
                showAlert('An error occurred during checkout.');
            });
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
            const savedCart = localStorage.getItem(`cart_${userId}`);
            if (savedCart) {
                cart = JSON.parse(savedCart);
                document.getElementById('cartCount').innerText = cart.length;
            }
        });
    </script>
</x-app-layout>