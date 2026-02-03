<x-admin-layout>
    <div class="main-content">
        <!-- Header -->

        @include('layouts.admin-header')
        
        <div class="admin-header">
            <div class="container-fluid">
                <div class="d-flex align-items-center">
                    <button class="btn btn-outline-secondary me-3 d-lg-none sidebar-toggle">
                        <i class="bi bi-list"></i>
                    </button>
                    <div class="flex-grow-1">
                        <h1 class="h3 fw-bold mb-0">Products</h1>
                        <p class="text-muted mb-0">Manage product listings and inventory</p>
                    </div>
                    <a href="{{ route('dashboard.products.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-lg me-2"></i>Add New Product
                    </a>
                </div>
            </div>
        </div>

        <div class="container-fluid p-4">
            <!-- Filters -->
            <div class="card mb-4">
                <div class="card-body">
                    <form action="{{ route('dashboard.products.index') }}" method="GET">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label for="search" class="form-label">Search</label>
                                <input type="text" class="form-control" id="search" name="search" 
                                       value="{{ request('search') }}" placeholder="Search products...">
                            </div>
                            <div class="col-md-2">
                                <label for="category" class="form-label">Category</label>
                                <select class="form-select" id="category" name="category">
                                    <option value="">All Categories</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="">All Status</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    <option value="out_of_stock" {{ request('status') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="vendor" class="form-label">Vendor</label>
                                <select class="form-select" id="vendor" name="vendor">
                                    <option value="">All Vendors</option>
                                    @foreach($vendors as $vendor)
                                        <option value="{{ $vendor->id }}" {{ request('vendor') == $vendor->id ? 'selected' : '' }}>
                                            {{ $vendor->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-funnel me-1"></i> Filter
                                </button>
                            </div>
                            <div class="col-md-1 d-flex align-items-end">
                                <a href="{{ route('dashboard.products.index') }}" class="btn btn-outline-secondary w-100">
                                    <i class="bi bi-arrow-counterclockwise"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Products Table -->
            <div class="card">
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Image</th>
                                    <th>Product</th>
                                    <th class="d-none d-sm-table-cell">Category</th>
                                    <th class="d-none d-md-table-cell">Vendor</th>
                                    <th>Price</th>
                                    <th class="d-none d-sm-table-cell">Stock</th>
                                    <th class="d-none d-md-table-cell">Status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $product)
                                    <tr>
                                        <td>
                                            @if($product->image)
                                                <img src="{{ asset('uploads/' . $product->image) }}" 
                                                     alt="{{ $product->name }}" 
                                                     class="img-thumbnail" 
                                                     style="width: 50px; height: 50px; object-fit: cover;">
                                            @else
                                                <div class="bg-light d-flex align-items-center justify-content-center" 
                                                     style="width: 50px; height: 50px;">
                                                    <i class="bi bi-image text-muted"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td style="max-width: 220px;">
                                            <div class="fw-semibold text-truncate">{{ $product->name }}</div>
                                            <small class="text-muted text-truncate d-block">{{ Str::limit($product->description, 50) }}</small>
                                        </td>
                                        <td class="d-none d-sm-table-cell">{{ $product->category ?? 'N/A' }}</td>
                                        <td class="d-none d-md-table-cell">{{ $product->vendor->name ?? 'N/A' }}</td>
                                        <td>
                                            <span class="fw-bold">£{{ number_format($product->price, 2) }}</span>
                                            @if($product->original_price > $product->price)
                                                <br><small class="text-danger text-decoration-line-through">
                                                    £{{ number_format($product->original_price, 2) }}
                                                </small>
                                            @endif
                                        </td>
                                        <td class="d-none d-sm-table-cell">
                                            <span class="badge bg-{{ $product->stock > 10 ? 'success' : ($product->stock > 0 ? 'warning' : 'danger') }}">
                                                {{ $product->stock }} in stock
                                            </span>
                                        </td>
                                        <td class="d-none d-md-table-cell">
                                            <span class="badge bg-{{ $product->status === 'active' ? 'success' : 'secondary' }}">
                                                {{ ucfirst($product->status) }}
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('dashboard.products.edit', $product) }}" 
                                                   class="btn btn-sm btn-outline-primary" 
                                                   data-bs-toggle="tooltip" 
                                                   title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-secondary"
                                                        onclick="window.location='{{ route('dashboard.products.show', $product) }}'"
                                                        data-bs-toggle="tooltip" 
                                                        title="View">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <form action="{{ route('dashboard.products.destroy', $product) }}" 
                                                      method="POST" 
                                                      class="d-inline"
                                                      onsubmit="return confirm('Are you sure you want to delete this product?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-outline-danger"
                                                            data-bs-toggle="tooltip" 
                                                            title="Delete">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                                No products found.
                                                @if(request()->anyFilled(['search', 'category', 'status', 'vendor']))
                                                    <div class="mt-2">
                                                        <a href="{{ route('dashboard.products.index') }}" class="btn btn-sm btn-outline-primary">
                                                            Clear filters
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div class="text-muted">
                            Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of {{ $products->total() }} entries
                        </div>
                        {{ $products->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Initialize tooltips
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
    @endpush
</x-admin-layout>
