<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-[var(--primary-color)] leading-tight">
            {{ $product->title }}
        </h2>
    </x-slot>

    <div class="py-6 bg-light">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4">
                <a href="{{ route('dashboard') }}" class="text-gray-500 hover:underline">
                    &larr; Back
                </a>
            </div>
            <div class="bg-white shadow-md rounded-lg overflow-hidden p-4 justify-items-center">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <h3 class="text-2xl font-bold mb-4 text-center">Product Details</h3>
                    <div class="flex justify-center items-center">
                        
                        <!-- Image Section -->
                        <div class=" justify-center items-center">
                            <img src="{{ asset('images/products/' . $product->image) }}"
                            alt="{{ $product->title }}"
                            class="img-fluid rounded-lg mb-4"
                            style="max-height: 300px; object-fit: cover; width: auto; max-width: 100%;" />
                            <h3 class="text-2xl font-bold text-center text-[var(--primary-color)]">{{ $product->title }}</h3>
                            <p class="text-gray-700 text-center">{{ $product->description }}</p>
                        </div>
                        
                        <!-- Product Details Section -->
                        <div class="p-6 text-gray-900">
                            
                            <p class="text-lg mb-4">Explore the details of this product.</p>
                            <div class="text-center mb-4">
                                <p class="text-dark"><strong>Category:</strong> {{ $product->category }}</p>
                                <p class="text-dark"><strong>Stock:</strong><br>
                                <span>Small: {{ $product->stock_small > 0 ? $product->stock_small : 'Out of stock' }}</span><br>
                                <span>Medium: {{ $product->stock_medium > 0 ? $product->stock_medium : 'Out of stock' }}</span><br>
                                <span>Large: {{ $product->stock_large > 0 ? $product->stock_large : 'Out of stock' }}</span>
                                </p>
                            </div>

                            <div class="text-center mb-4">
                                <p class="text-dark"><strong>Prices:</strong><br>
                                    <span>Small: ₱{{ number_format($product->price_small, 2) }}</span><br>
                                    <span>Medium: ₱{{ number_format($product->price_medium, 2) }}</span><br>
                                    <span>Large: ₱{{ number_format($product->price_large, 2) }}</span>
                                </p>
                            </div>
                            <div class="text-center mb-4">
                                <p class="text-dark"><strong>Created At:</strong>
                                    <span>{{ $product->created_at }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-center gap-4 mt-6">
                        @if ($product->stock_small > 0 || $product->stock_medium > 0 || $product->stock_large > 0)
                        <button class="btn btn-danger rounded-pill px-5 py-2" style="pointer: none; cursor: not-allowed;">Place Order</button>
                        @else
                        <span class="text-red-600 font-semibold">This product is currently out of stock.</span>
                        @endif

                        <a href="{{ route('dashboard', ['category' => 'All']) }}" class="btn btn-outline-danger rounded-pill px-5 py-2">
                            Cancel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>