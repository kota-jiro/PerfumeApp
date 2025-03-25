<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Products') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-light">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="d-flex justify-content-between mb-4">
                        <form class="d-flex w-100">
                            <input class="form-control me-2" type="search" placeholder="Search..." aria-label="Search">
                            <button class="btn btn-outline-secondary" type="submit">Search</button>
                        </form>
                    </div>

                    <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @foreach ($products as $index => $product)
                            <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                <div class="row align-items-center mb-5 border p-4 rounded" style="background-color: #f8e0e0;">
                                    <div class="col-md-4 text-center">
                                        <img src="{{ asset('images/products/' . $product->image) }}" alt="{{ $product->title }}" class="img-fluid" style="height: 300px; object-fit: cover; border-radius: 10px;">
                                    </div>
                                    <div class="col-md-8">
                                        <h2 class="text-danger">{{ $product->title }}</h2>
                                        <p>{{ $product->description }}</p>
                                        <h3 class="text-bold text-dark">$ {{ number_format($product->price_small, 2) }}</h3>
                                        <button class="btn btn-danger me-2">Place Order</button>
                                        <button class="btn btn-outline-danger">Checkout</button>
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
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
