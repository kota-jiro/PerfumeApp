<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Product') }}
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
                    <div class="d-flex align-items-center justify-content-between">
                        <h1 class="mb-0">Add Product</h1>
                    </div>
                    <hr />
                    @if (session()->has('error'))
                    <div>
                        {{ session('error') }}
                    </div>
                    @endif
                    <form action="{{ route('admin.products.save') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-3">
                            <div class="col">
                                <label for="title" class="form-label">Product Name:</label>
                                <input type="text" name="title" class="form-control" value="{{ old('title') }}" placeholder="Product Name" />
                                @error('title')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col">
                                <label for="category" class="form-label">Category:</label>
                                <select name="category" class="form-control" ">
                                    <option value="">Select Category</option>
                                    <option value=" Male Perfume">Male Perfume</option>
                                    <option value="Female Perfume">Female Perfume</option>
                                </select>
                                @error('category')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col">
                                <label for="description" class="form-label">Description:</label>
                                <textarea type="text" name="description" value="{{ old('description') }}" class="form-control" placeholder="Description" ></textarea>
                                @error('description')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- <div class="row mb-3">
                            <div class="col">
                                <label for="price" class="form-label">Price</label>
                                <input type="number" name="price" class="form-control" value="{{ old('price') }}" placeholder="Price" />
                                @error('price')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div> -->

                        <div class="row mb-3">
                            <div class="col">
                                <label for="image" class="form-label">Product Image: (Optional)</label>
                                <input type="file" name="image" class="form-control">
                                @error('image')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <hr>
                        <div class="row mb-3">
                            <label>Add Stock/s:</label>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="stock_small">Small (10ml - 20ml)</label>
                                <input type="number" name="stock_small" class="form-control" value="{{ old('stock_small') }}" placeholder="Stock for Small">
                                @error('stock_small')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <hr>
                                <input type="number" name="price_small" class="form-control mt-2" value="{{ old('price_small') }}" placeholder="Price for Small ₱1199 - ₱2399">
                                @error('price_small')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="stock_medium">Medium (30ml - 50ml)</label>
                                <input type="number" name="stock_medium" class="form-control" value="{{ old('stock_medium') }}" placeholder="Stock for Medium">
                                @error('stock_medium')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <hr>
                                <input type="number" name="price_medium" class="form-control mt-2" value="{{ old('price_medium') }}" placeholder="Price for Medium ₱3599 - ₱5999">
                                @error('price_medium')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="stock_large">Large (100ml - 200ml)</label>
                                <input type="number" name="stock_large" class="form-control" value="{{ old('stock_large') }}" placeholder="Stock for Large">
                                @error('stock_large')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <hr>
                                <input type="number" name="price_large" class="form-control mt-2" value="{{ old('price_large') }}" placeholder="Price for Large ₱11999 - ₱23999">
                                @error('price_large')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="d-grid">
                                <button class="btn btn-danger">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>