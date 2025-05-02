<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-2xl font-bold mb-2">
                        {{ __("Welcome, {$user->firstname}!") }}
                    </h3>
                    <p class="text-md text-gray-700">Ready to find your perfect scent? Discover premium fragrances tailored to your style at <span class="font-semibold">Leo's Perfume</span>.</p>

                    <!-- Search Form -->
                    <div class="d-flex justify-content-between align-items-center my-6">
                        <form action="{{ route('dashboard') }}" method="GET" class="d-flex w-100">
                            <input class="form-control rounded-pill py-2 px-4 border-gray-300"
                                type="search" name="search" value="{{ request()->search }}"
                                placeholder="Search products..." aria-label="Search" style="width: 250px;">
                            <button class="btn btn-outline-danger rounded-pill px-4 ms-2" type="submit">Search</button>
                        </form>
                        <a href="{{ route('products.index') }}">
                            <button class="btn btn-outline-danger rounded-pill px-4 ms-2">Products</button>
                        </a>
                    </div>

                    <!-- Product Carousel -->
                    @if($filteredProducts->count())
                    <div id="productCarousel" class="carousel slide mt-6" data-bs-ride="carousel" data-bs-interval="4000">
                        <div class="carousel-inner">
                            @foreach ($filteredProducts as $index => $product)
                            <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                <div class="row align-items-center mb-4 border p-5 rounded-lg shadow-md" style="background-color: #fce9ea;">
                                    <div class="col-md-4 text-center">
                                        <img src="{{ asset('images/products/' . $product->image) }}"
                                            alt="{{ $product->title }}"
                                            class="img-fluid rounded-lg"
                                            style="max-height: 210px; object-fit: cover;">
                                    </div>
                                    <div class="col-md-8">
                                        <h3 class="text-red-600 font-semibold text-3xl mb-2">{{ $product->title }}</h3>
                                        <p class="text-gray-700 text-md mb-4">{{ $product->description ?: 'No description available.' }}</p>
                                        @if ($product->price_small)
                                        <h4 class="font-semibold text-gray-800 text-2xl mb-4">₱ {{ number_format($product->price_small, 2) }}</h4>
                                        @elseif ($product->price_medium)
                                        <h4 class="font-semibold text-gray-800 text-2xl mb-4">₱ {{ number_format($product->price_medium, 2) }}</h4>
                                        @elseif ($product->price_large)
                                        <h4 class="font-semibold text-gray-800 text-2xl mb-4">₱ {{ number_format($product->price_large, 2) }}</h4>
                                        @else
                                        <h4 class="font-semibold text-gray-500 text-md mb-4">Price not available</h4>

                                        @endif

                                        <div class="d-flex">
                                            <button class="btn btn-danger rounded-pill me-3 px-4 py-2 shadow-sm hover:shadow-lg">Place Order</button>
                                            <a href="{{ route('products.show', $product->id) }}"><button class="btn btn-outline-danger rounded-pill px-4 py-2 hover:bg-red-600 hover:text-white transition">View Details</button></a>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <!-- Carousel Indicators -->
                        <div class="d-flex justify-content-center">
                            @foreach ($filteredProducts as $index => $product)
                            <button type="button"
                                data-bs-target="#productCarousel"
                                data-bs-slide-to="{{ $index }}"
                                class="carousel-indicator mx-1 rounded-circle border-0 {{ $index == 0 ? 'bg-danger' : 'bg-secondary' }}"
                                style="width: 12px; height: 12px;">
                            </button>
                            @endforeach
                        </div>


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
                        No products found. Try adjusting your search.
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
    <script>
        const carousel = document.getElementById('productCarousel');

        carousel.addEventListener('slid.bs.carousel', function() {
            const activeIndex = Array.from(carousel.querySelectorAll('.carousel-item')).findIndex(item =>
                item.classList.contains('active')
            );

            document.querySelectorAll('.carousel-indicator').forEach((btn, index) => {
                if (index === activeIndex) {
                    btn.classList.remove('bg-secondary');
                    btn.classList.add('bg-danger');
                } else {
                    btn.classList.remove('bg-danger');
                    btn.classList.add('bg-secondary');
                }
            });
        });
    </script>


</x-app-layout>