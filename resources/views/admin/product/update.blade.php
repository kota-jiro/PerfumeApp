<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Product') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7x1 mx-auto sm:px lg:px-8">
            <div class="mb-4">
                <a href="{{ route('admin.products') }}" class="text-gray-500 hover:underline">
                    &larr; Back
                </a>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="mb-0">Edit Product</h1>
                    <hr />
                    <form action="{{ route('admin.products.update', $products->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        {{-- Product Name --}}
                        <div class="row mb-3">
                            <div class="col">
                                <label for="title" class="form-label">Product Name:</label>
                                <input type="text" name="title" class="form-control" placeholder="Title" value="{{ old('title', $products->title) }}" />
                                @error('title')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- Category --}}
                        <div class="row mb-3">
                            <div class="col">
                                <label for="category" class="form-label">Category:</label>
                                <select name="category" class="form-control">
                                    <option value="">Select Category</option>
                                    <option value="Male Perfume" {{ old('category', $products->category) == 'Male Perfume' ? 'selected' : '' }}>Male Perfume</option>
                                    <option value="Female Perfume" {{ old('category', $products->category) == 'Female Perfume' ? 'selected' : '' }}>Female Perfume</option>
                                </select>
                                @error('category')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- Description --}}
                        <div class="row mb-3">
                            <div class="col">
                                <label for="description" class="form-label">Description:</label>
                                <input type="text" name="description" class="form-control" placeholder="Description" value="{{ old('description', $products->description) }}" />
                                @error('description')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- Product Image --}}
                        <div class="row mb-3">
                            <div class="col">
                                <label for="image" class="form-label">Product Image: (Optional)</label>
                                <input type="file" name="image" class="form-control">

                                @if($products->image)
                                <img src="{{ asset('images/products/' . $products->image) }}" alt="Product Image" class="mt-2" width="150">
                                @endif

                                @error('image')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <hr>

                        <div class="row mb-3">
                            <label>Add Stock/s:</label>
                        </div>

                        {{-- Stock and Price for Different Sizes --}}
                        <div class="row mb-3">
                            {{-- Small Size --}}
                            <div class="col">
                                <label for="stock_small">Small (10ml - 20ml)</label>
                                <input type="number" name="stock_small" class="form-control" value="{{ old('stock_small', $products->stock_small) }}" placeholder="Stock for Small">
                                @error('stock_small')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror

                                <input type="number" name="price_small" class="form-control mt-2" value="{{ old('price_small', $products->price_small) }}" placeholder="Price for Small ₱1199 - ₱2399">
                                @error('price_small')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Medium Size --}}
                            <div class="col">
                                <label for="stock_medium">Medium (30ml - 50ml)</label>
                                <input type="number" name="stock_medium" class="form-control" value="{{ old('stock_medium', $products->stock_medium) }}" placeholder="Stock for Medium">
                                @error('stock_medium')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror

                                <input type="number" name="price_medium" class="form-control mt-2" value="{{ old('price_medium', $products->price_medium) }}" placeholder="Price for Medium ₱3599 - ₱5999">
                                @error('price_medium')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Large Size --}}
                            <div class="col">
                                <label for="stock_large">Large (100ml - 200ml)</label>
                                <input type="number" name="stock_large" class="form-control" value="{{ old('stock_large', $products->stock_large) }}" placeholder="Stock for Large">
                                @error('stock_large')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror

                                <input type="number" name="price_large" class="form-control mt-2" value="{{ old('price_large', $products->price_large) }}" placeholder="Price for Large ₱11999 - ₱23999">
                                @error('price_large')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- Update Button --}}
                        <div class="row">
                            <div class="d-grid">
                                <button class="btn btn-warning">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>