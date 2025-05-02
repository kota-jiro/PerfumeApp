<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-[var(--primary-color)] leading-tight text-center">
            {{ $product->title }}
        </h2>
    </x-slot>

    <div class="py-6 bg-light">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg overflow-hidden p-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Image Section -->
                    <div class="flex justify-center items-center">
                        <img src="{{ asset('images/products/' . $product->image) }}"
                            alt="{{ $product->title }}"
                            class="img-fluid rounded-lg"
                            style="max-height: 300px; object-fit: cover; width: auto; max-width: 100%;" />
                    </div>

                    <!-- Product Details Section -->
                    <div class="p-6 text-gray-900">
                        <h3 class="text-2xl font-bold text-center text-[var(--primary-color)] mb-4">{{ $product->title }}</h3>
                        <p class="text-gray-700 text-center">{{ $product->description }}</p>

                        <div class="text-center mb-4">
                            <p class="text-dark"><strong>Category:</strong> {{ $product->category }}</p>
                            <p class="text-dark"><strong>Stock:</strong></p>
                            <ul class="list-none">
                                <li>Small: {{ $product->stock_small > 0 ? $product->stock_small : 'Out of stock' }}</li>
                                <li>Medium: {{ $product->stock_medium > 0 ? $product->stock_medium : 'Out of stock' }}</li>
                                <li>Large: {{ $product->stock_large > 0 ? $product->stock_large : 'Out of stock' }}</li>
                            </ul>
                        </div>

                        <div class="text-center mb-4">
                            <p class="text-dark"><strong>Prices:</strong></p>
                            <ul class="list-none">
                                <li>Small: ₱{{ number_format($product->price_small, 2) }}</li>
                                <li>Medium: ₱{{ number_format($product->price_medium, 2) }}</li>
                                <li>Large: ₱{{ number_format($product->price_large, 2) }}</li>
                            </ul>
                        </div>

                        <div class="flex justify-center gap-4 mt-6">
                            @if ($product->stock_small > 0 || $product->stock_medium > 0 || $product->stock_large > 0)
                                <button class="btn btn-danger rounded-pill px-5 py-2">Place Order</button>
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
    </div>
</x-app-layout>
