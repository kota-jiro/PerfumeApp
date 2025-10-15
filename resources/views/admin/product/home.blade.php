<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Perfume Management') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7x1 mx-auto sm:px lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 d-flex flex-column" style="min-height: 570px; display: flex; justify-content: space-between;">
                    <div class="d-flex align-items-center justify-content-between">
                        <h1 class="mb-0">List Products</h1>
                        <a href="{{ route('admin.products.create') }}" class="btn btn-outline-danger">+ Add Product</a>
                    </div>
                    <hr />
                    <div class="mb-3">
                        <!-- Category Filter Dropdown -->
                        <form method="GET" action="{{ route('admin.products') }}" class="d-flex">
                            <select name="category" class="form-select" onchange="this.form.submit()">
                                <option value="">All Categories</option>
                                <option value="Male Perfume" {{ $categoryFilter == 'Male Perfume' ? 'selected' : '' }}>Male Perfume</option>
                                <option value="Female Perfume" {{ $categoryFilter == 'Female Perfume' ? 'selected' : '' }}>Female Perfume</option>
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
                    <div id="success-alert" class="alert alert-success" role="alert">
                        {{ Session::get('success') }}
                    </div>

                    <script>
                        // Wait for the page to load
                        document.addEventListener('DOMContentLoaded', function() {
                            // After 3 seconds (3000ms), fade out and remove the alert
                            setTimeout(function() {
                                const alertBox = document.getElementById('success-alert');
                                if (alertBox) {
                                    alertBox.style.transition = 'opacity 0.5s ease';
                                    alertBox.style.opacity = '0';
                                    setTimeout(() => alertBox.remove(), 500); // remove after fade out
                                }
                            }, 3000);
                        });
                    </script>
                    @endif

                    @if (Session::has('error'))
                    <div id="error-alert" class="alert alert-danger" role="alert">
                        {{ Session::get('error') }}
                    </div>
                    <script>
                        // Wait for the page to load
                        document.addEventListener('DOMContentLoaded', function() {
                            // After 3 seconds (3000ms), fade out and remove the alert
                            setTimeout(function() {
                                const alertBox = document.getElementById('error-alert');
                                if (alertBox) {
                                    alertBox.style.transition = 'opacity 0.5s ease';
                                    alertBox.style.opacity = '0';
                                    setTimeout(() => alertBox.remove(), 500); // remove after fade out
                                }
                            }, 3000);
                        });
                    </script>
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
                                <!-- <th>Created At</th>
                                <th>Updated At</th> -->
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($products as $product)
                            <tr>
                                <td class="align-middle clickable-row" data-product="{{ json_encode($product) }}" data-bs-toggle="modal" data-bs-target="#detailsModal">{{ $products->firstItem() + $loop->index }}</td>
                                <td class="align-middle clickable-row" data-product="{{ json_encode($product) }}" data-bs-toggle="modal" data-bs-target="#detailsModal">{{ $product->title }}</td>
                                <td class="align-middle clickable-row" data-product="{{ json_encode($product) }}" data-bs-toggle="modal" data-bs-target="#detailsModal">{{ $product->category }}</td>
                                <td class="align-middle clickable-row" data-product="{{ json_encode($product) }}" data-bs-toggle="modal" data-bs-target="#detailsModal">
                                    <img src="{{ asset('images/products/' . $product->image) }}" alt="Product Image" width="80">
                                </td>
                                <td class="align-middle clickable-row" data-product="{{ json_encode($product) }}" data-bs-toggle="modal" data-bs-target="#detailsModal">
                                    {{ $product->stock_small ? $product->stock_small . ' pcs - ₱' . $product->price_small : 'No stock' }}
                                </td>
                                <td class="align-middle clickable-row" data-product="{{ json_encode($product) }}" data-bs-toggle="modal" data-bs-target="#detailsModal">
                                    {{ $product->stock_medium ? $product->stock_medium . ' pcs - ₱' . $product->price_medium : 'No stock' }}
                                </td>
                                <td class="align-middle clickable-row" data-product="{{ json_encode($product) }}" data-bs-toggle="modal" data-bs-target="#detailsModal">
                                    {{ $product->stock_large ? $product->stock_large . ' pcs - ₱' . $product->price_large : 'No stock' }}
                                </td>

                                <!-- <td class="align-middle">{{ $product->created_at }}</td>
                                <td class="align-middle">{{ $product->updated_at }}</td> -->
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

                    <!-- Pagination Section -->
                    <div class="d-flex justify-content-center mt-1">
                        <div aria-label="Page navigation">
                            <ul class="pagination pagination-sm">
                                {{-- Check if there are pages --}}
                                @if ($products->lastPage() > 1)
                                {{-- Previous Page Link --}}
                                <li class="page-item {{ $products->onFirstPage() ? 'disabled' : '' }}">
                                    <a class="page-link" href="{{ $products->previousPageUrl() }}{{ request()->has('category') ? '&category=' . request('category') : '' }}" tabindex="-1" aria-disabled="{{ $products->onFirstPage() ? 'true' : 'false' }}">
                                        &laquo;
                                    </a>
                                </li>

                                {{-- Pagination Elements --}}
                                @for ($i = 1; $i <= $products->lastPage(); $i++)
                                    <li class="page-item {{ $i == $products->currentPage() ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $products->url($i) }}{{ request()->has('category') ? '&category=' . request('category') : '' }}">{{ $i }}</a>
                                    </li>
                                    @endfor

                                    {{-- Next Page Link --}}
                                    <li class="page-item {{ $products->hasMorePages() ? '' : 'disabled' }}">
                                        <a class="page-link" href="{{ $products->nextPageUrl() }}{{ request()->has('category') ? '&category=' . request('category') : '' }}" aria-disabled="{{ $products->hasMorePages() ? 'false' : 'true' }}">
                                            &raquo;
                                        </a>
                                    </li>
                                    @endif
                            </ul>
                        </div>
                    </div>

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

    <!-- Details Modal -->
    <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailsModalLabel">Product Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <img id="modalProductImage" src="" alt="Product Image" class="img-fluid rounded">
                        </div>
                        <div class="col-md-8">
                            <p class="text-gray-900"><strong>Title:</strong> <span id="modalTitle"></span></p>
                            <p class="text-gray-900"><strong>Category:</strong> <span id="modalCategory"></span></p>
                            <p class="text-gray-900"><strong>Small:</strong> <span id="modalSmall"></span></p>
                            <p class="text-gray-900"><strong>Medium:</strong> <span id="modalMedium"></span></p>
                            <p class="text-gray-900"><strong>Large:</strong> <span id="modalLarge"></span></p>
                            <p class="text-gray-900"><strong>Created At:</strong> <span id="modalCreatedAt"></span></p>
                            <p class="text-gray-900"><strong>Updated At:</strong> <span id="modalUpdatedAt"></span></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".clickable-row").forEach(function(row) {
                row.addEventListener("click", function() {
                    const product = JSON.parse(this.getAttribute("data-product"));

                    document.getElementById("modalProductImage").src = `{{ asset('images/products/') }}/${product.image}`;
                    document.getElementById("modalTitle").innerText = product.title;
                    document.getElementById("modalCategory").innerText = product.category;
                    document.getElementById("modalSmall").innerText = product.stock_small ?
                        `${product.stock_small} pcs - ₱${product.price_small}` :
                        "No stock";
                    document.getElementById("modalMedium").innerText = product.stock_medium ?
                        `${product.stock_medium} pcs - ₱${product.price_medium}` :
                        "No stock";
                    document.getElementById("modalLarge").innerText = product.stock_large ?
                        `${product.stock_large} pcs - ₱${product.price_large}` :
                        "No stock";
                    document.getElementById("modalCreatedAt").innerText = product.created_at ? new Date(product.created_at).toLocaleString() : 'N/A';
                    document.getElementById("modalUpdatedAt").innerText = product.updated_at ? new Date(product.updated_at).toLocaleString() : 'N/A';
                });
            });
        });

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
    <style>
        .pagination .page-item .page-link {
            color: #800000;
            /* Leo’s Perfume brand red */
            border-radius: 0.375rem;
            margin: 0 2px;
            transition: background-color 0.2s ease-in-out;
            padding: 0.25rem 0.6rem;
            /* smaller height & width */
            font-size: 0.875rem;
            /* smaller font size */
            min-width: 35px;
            /* consistent width */
            height: 30px;
            /* consistent height */
            line-height: 1.2;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .pagination .page-item.active .page-link {
            background-color: #800000;
            color: white;
            border-color: #800000;
        }

        .pagination .page-item .page-link:hover {
            background-color: #f8d7da;
            color: #800000;
        }

        .clickable-row {
            cursor: pointer;
        }

        .clickable-row:hover {
            background-color: #f8f9fa;
            /* Light gray background on hover */
        }
    </style>

</x-app-layout>