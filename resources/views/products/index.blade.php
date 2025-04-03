<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-3xl text-[var(--primary-color)] leading-tight">
            {{ __('Products') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-light">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Search Form -->
                    <div class="d-flex justify-content-between mb-6">
                        <form action="{{ route('products.search') }}" method="GET" class="d-flex w-100">
                            <input class="form-control rounded-pill py-2 px-4 border-gray-300" type="search" name="search" value="{{ request()->search }}" placeholder="Search products..." aria-label="Search" style="width: 250px;">
                            <button class="btn btn-outline-danger rounded-pill px-4 ms-2" type="submit">Search</button>
                        </form>
                    </div>

                    <!-- Product Carousel -->
                    @if($filteredProducts->isNotEmpty())
                        <div id="productCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
                            <div class="carousel-inner">
                                @foreach ($filteredProducts as $index => $product)
                                    <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                        <div class="row align-items-center mb-6 border p-5 rounded-lg shadow-md" style="background-color: #f8e0e0;">
                                            <div class="col-md-4 text-center">
                                                <img src="{{ asset('images/products/' . $product->image) }}" alt="{{ $product->title }}" class="img-fluid rounded-lg" style="height: 300px; object-fit: cover;">
                                            </div>
                                            <div class="col-md-8">
                                                <h3 class="text-[var(--primary-color)] font-semibold text-2xl mb-2">{{ $product->title }}</h3>
                                                <p class="text-gray-700 text-lg mb-4">{{ $product->description }}</p>
                                                <h4 class="font-semibold text-dark text-xl mb-4">$ {{ number_format($product->price_small, 2) }}</h4>
                                                <div class="d-flex">
                                                    <button class="btn btn-danger rounded-pill me-3 py-2 px-5 hover:bg-red-700 transition duration-300">Place Order</button>
                                                    <button class="btn btn-outline-danger rounded-pill py-2 px-5 hover:bg-red-500 hover:text-white transition duration-300">Checkout</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
                        <p>No products found.</p>
                    @endif

                    <!-- Categories Section at the Bottom -->
                    <div class="mt-6">
                        <h3 class="font-semibold text-xl mb-4 text-[var(--primary-color)]">Categories</h3>
                        <div class="d-flex justify-content-start gap-4">
                            <a href="{{ route('products.filter', ['category' => 'All']) }}" class="btn btn-outline-danger rounded-pill px-4 {{ request()->category == 'All' ? 'active' : '' }}">All ({{ $total }})</a>
                            <a href="{{ route('products.filter', ['category' => 'Male Perfume']) }}" class="btn btn-outline-danger rounded-pill px-4 {{ request()->category == 'Male Perfume' ? 'active' : '' }}">Male Perfume ({{ $maleCount }})</a>
                            <a href="{{ route('products.filter', ['category' => 'Female Perfume']) }}" class="btn btn-outline-danger rounded-pill px-4 {{ request()->category == 'Female Perfume' ? 'active' : '' }}">Female Perfume ({{ $femaleCount }})</a>
                        </div>
                    </div>

                    <!-- Display Filtered Products Below Carousel -->
                    <div class="mt-6">
                        @if(request()->category)
                            <h3 class="font-semibold text-2xl text-[var(--primary-color)] mt-6">{{ request()->category }}</h3>
                            <div class="row row-cols-1 row-cols-md-4 g-4">
                                @foreach ($filteredProducts as $product)
                                    <div class="col">
                                        <div class="card h-100 shadow-md border-0 rounded-lg">
                                            <img src="{{ asset('images/products/' . $product->image) }}" alt="{{ $product->title }}" class="card-img-top rounded-lg" style="height: 200px; object-fit: cover;">
                                            <div class="card-body">
                                                <h5 class="card-title text-[var(--primary-color)]">{{ $product->title }}</h5>
                                                <p class="card-text text-gray-700" style="height: 75px; overflow: hidden; text-overflow: ellipsis;" title="{{ $product->description }}">{{
                                                    \Str::limit($product->description, 50, '...') }}</p>
                                                <h6 class="font-semibold text-dark text-lg mb-2">$ {{ number_format($product->price_small, 2) }}</h6>
                                            </div>
                                            <div class="card-footer text-center">
                                                <button class="btn btn-danger rounded-pill py-2 px-5 hover:bg-red-700 transition duration-300">Place Order</button>
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
</x-app-layout>
