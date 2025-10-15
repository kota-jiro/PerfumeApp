<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <div class="p-6 text-gray-900">
                    <!-- Welcome Section -->
                    <h3 class="text-2xl font-bold mb-2">
                        {{ __("Welcome, {$user->firstname}!") }}
                    </h3>
                    <p class="text-md text-gray-700 mb-4">
                        Explore signature fragrances that define your style at <span class="font-semibold">Leo's Perfume</span>.
                    </p>

                    <!-- Search & Product Link -->
                    <div class="d-flex flex-wrap justify-content-between align-items-center my-4">
                        <form action="{{ route('dashboard') }}" method="GET" class="d-flex flex-wrap">
                            <input 
                                type="search" 
                                name="search" 
                                value="{{ request()->search }}"
                                class="form-control rounded-pill py-2 px-4 border border-gray-300"
                                placeholder="Search products..." 
                                aria-label="Search"
                                style="width: 250px;"
                            >
                            <button class="btn btn-outline-danger rounded-pill px-4 ms-2 mt-2 mt-sm-0" type="submit">Search</button>
                        </form>
                        <a href="{{ route('products.index') }}">
                            <button class="btn btn-outline-danger rounded-pill px-4 ms-2 mt-2 mt-sm-0">Browse All Products</button>
                        </a>
                    </div>

                    <!-- Product Carousel -->
                    @if($filteredProducts->count())
                    <div id="productCarousel" class="carousel slide mt-5" data-bs-ride="carousel" data-bs-interval="4000">
                        <div class="carousel-inner">
                            @foreach ($filteredProducts as $index => $product)
                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                <div class="row align-items-center p-5 mb-4 border rounded-lg shadow" style="background-color: #fce9ea;">
                                    <div class="col-md-4 text-center">
                                        <img src="{{ asset('images/products/' . $product->image) }}"
                                             alt="{{ $product->title }}"
                                             onerror="this.src='{{ asset('images/default.jpg') }}';"
                                             class="img-fluid rounded-lg"
                                             style="max-height: 210px; object-fit: cover;">
                                    </div>
                                    <div class="col-md-8">
                                        <h3 class="text-red-600 font-semibold text-3xl mb-2">{{ $product->title }}</h3>
                                        <p class="text-gray-700 mb-3">{{ $product->description ?? 'No description available.' }}</p>
                                        
                                        <h4 class="font-semibold text-gray-800 text-2xl mb-3">
                                            ₱ 
                                            {{ number_format($product->price_small ?? $product->price_medium ?? $product->price_large ?? 0, 2) }}
                                        </h4>

                                        <div class="d-flex">
                                            <button class="btn btn-danger rounded-pill me-3 px-4 py-2 shadow-sm" disabled>Place Order</button>
                                            <a href="{{ route('products.show', $product->id) }}">
                                                <button class="btn btn-outline-danger rounded-pill px-4 py-2 hover:bg-red-600 hover:text-white transition">View Details</button>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <!-- Carousel Indicators -->
                        <div class="d-flex justify-content-center mt-2">
                            @foreach ($filteredProducts as $index => $product)
                            <button type="button"
                                    data-bs-target="#productCarousel"
                                    data-bs-slide-to="{{ $index }}"
                                    class="carousel-indicator mx-1 rounded-circle border-0 {{ $index === 0 ? 'bg-danger' : 'bg-secondary' }}"
                                    style="width: 12px; height: 12px;">
                            </button>
                            @endforeach
                        </div>

                        <!-- Carousel Controls -->
                        <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                    @else
                    <div class="alert alert-info mt-4">
                        No products found. Try adjusting your search or check back later.
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Carousel Active Indicator Script -->
    <script>
        const carousel = document.getElementById('productCarousel');
        carousel.addEventListener('slid.bs.carousel', function () {
            const items = Array.from(carousel.querySelectorAll('.carousel-item'));
            const activeIndex = items.findIndex(item => item.classList.contains('active'));

            document.querySelectorAll('.carousel-indicator').forEach((btn, index) => {
                btn.classList.toggle('bg-danger', index === activeIndex);
                btn.classList.toggle('bg-secondary', index !== activeIndex);
            });
        });
    </script>
</x-app-layout>
