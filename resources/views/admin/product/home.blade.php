<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Product') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7x1 mx-auto sm:px lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="d-flex align-items-center justify-content-between">
                        <h1 class="mb-0">List Products</h1>
                        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">+ Add Product</a>
                    </div>
                    <hr />
                    
                    <div class="mb-3">
                        <!-- Category Filter Dropdown -->
                        <form method="GET" action="{{ route('admin.products') }}" class="d-flex">
                            <select name="category" class="form-select" onchange="this.form.submit()">
                                <option value="">All Categories</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category }}" {{ $categoryFilter == $category ? 'selected' : '' }}>
                                        {{ ucfirst($category) }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>

                    <div class="mb-3">
                        <!-- Display the filtered total count of products -->
                        <strong>Total:</strong> {{ $totalFiltered }} 
                        @if ($categoryFilter)
                            <span>({{ ucfirst($categoryFilter) }} Products)</span>
                        @else
                            <span>(All Products)</span>
                        @endif
                    </div>

                    @if (Session::has('success'))
                        <div class="alert alert-success" role="alert">
                            {{ Session::get('success') }}
                        </div>
                    @endif

                    <table class="table table-hover">
                        <thead class="table-primary">
                            <tr>
                                <th>#</th>
                                <th>Product Name</th>
                                <th>Category</th>
                                <th>Image</th>
                                <th>Small (10-20ml)</th>
                                <th>Medium (30-50ml)</th>
                                <th>Large (100-200ml)</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($products as $product)
                                <tr>
                                    <td class="align-middle">{{ $loop->iteration }}</td>
                                    <td class="align-middle">{{ $product->title }}</td>
                                    <td class="align-middle">{{ $product->category }}</td>
                                    <td class="align-middle">
                                        <img src="{{ asset('images/products/' . $product->image) }}" alt="Product Image" width="80">
                                    </td>
                                    <td class="align-middle">{{ $product->stock_small }} pcs - ₱{{ $product->price_small }}</td>
                                    <td class="align-middle">{{ $product->stock_medium }} pcs - ₱{{ $product->price_medium }}</td>
                                    <td class="align-middle">{{ $product->stock_large }} pcs - ₱{{ $product->price_large }}</td>
                                    <td class="align-middle">{{ $product->created_at }}</td>
                                    <td class="align-middle">{{ $product->updated_at }}</td>
                                    <td class="align-middle">
                                        <div class="btn-group" role="group" aria-label="Basic example">
                                            <a href="{{ route('admin.products.edit', ['id' => $product->id]) }}" class="btn btn-secondary">Edit</a>

                                            <!-- Button to trigger modal -->
                                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-product-id="{{ $product->id }}">
                                                Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center" colspan="10">No products found!</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this product?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form id="deleteForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Yes, Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var deleteModal = document.getElementById('deleteModal');
            deleteModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;
                var productId = button.getAttribute('data-product-id');

                // Set correct action URL for deletion
                var deleteForm = document.getElementById('deleteForm');
                deleteForm.action = "{{ url('admin/products/delete') }}/" + productId;
            });
        });
    </script>
</x-app-layout>
