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
                        <h1 class="h3 fw-bold mb-0">{{ $product->name }}</h1>
                        <nav aria-label="breadcrumb" class="mt-2">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('dashboard.products.index') }}">Products</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($product->name, 20) }}</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="d-flex">
                        <a href="{{ route('dashboard.products.edit', $product) }}" class="btn btn-outline-primary me-2">
                            <i class="bi bi-pencil me-2"></i>Edit
                        </a>
                        <a href="{{ route('dashboard.products.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Back to Products
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid p-4">
            <div class="row">
                <div class="col-lg-8">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-5">
                                    @if($product->image)
                                        <img src="{{ asset('uploads/' . $product->image) }}" 
                                             alt="{{ $product->name }}" 
                                             class="img-fluid rounded"
                                             style="max-height: 400px; width: 100%; object-fit: contain;">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center" 
                                             style="height: 300px; border-radius: 0.375rem;">
                                            <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-7">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div>
                                            <span class="badge bg-{{ $product->status === 'active' ? 'success' : 'secondary' }} mb-2">
                                                {{ ucfirst($product->status) }}
                                            </span>
                                            <h2 class="h4 fw-bold mb-1">{{ $product->name }}</h2>
                                            <div class="text-muted mb-3">
                                                <i class="bi bi-shop me-1"></i> {{ $product->vendor->name ?? 'N/A' }}
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <div class="h4 fw-bold text-primary">
                                                £{{ number_format($product->price, 2) }}
                                                @if($product->original_price > $product->price)
                                                    <small class="text-danger text-decoration-line-through d-block">
                                                        £{{ number_format($product->original_price, 2) }}
                                                    </small>
                                                @endif
                                            </div>
                                            <div class="small text-muted">
                                                <i class="bi bi-box-seam me-1"></i> 
                                                {{ $product->stock }} in stock
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <h5 class="h6 fw-bold mb-2">Category</h5>
                                        <span class="badge bg-light text-dark">
                                            <i class="bi bi-tag me-1"></i> {{ $product->category->name ?? 'Uncategorized' }}
                                        </span>
                                        
                                        @if($product->cuisine)
                                            <span class="badge bg-light text-dark ms-2">
                                                <i class="bi bi-egg-fried me-1"></i> {{ $product->cuisine }}
                                            </span>
                                        @endif
                                    </div>

                                    <div class="mb-4">
                                        <h5 class="h6 fw-bold mb-2">Description</h5>
                                        <p class="mb-0">
                                            {{ $product->description ?? 'No description available.' }}
                                        </p>
                                    </div>

                                    <div class="d-flex gap-2">
                                        <a href="{{ route('dashboard.products.edit', $product) }}" 
                                           class="btn btn-primary">
                                            <i class="bi bi-pencil me-2"></i>Edit Product
                                        </a>
                                        <form action="{{ route('dashboard.products.destroy', $product) }}" 
                                              method="POST" 
                                              onsubmit="return confirm('Are you sure you want to delete this product?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger">
                                                <i class="bi bi-trash me-2"></i>Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0">Product Details</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <th style="width: 200px;">SKU</th>
                                            <td>{{ $product->sku ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Vendor</th>
                                            <td>{{ $product->vendor->name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Category</th>
                                            <td>{{ $product->category->name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Price</th>
                                            <td>
                                                £{{ number_format($product->price, 2) }}
                                                @if($product->original_price > $product->price)
                                                    <span class="text-danger text-decoration-line-through ms-2">
                                                        £{{ number_format($product->original_price, 2) }}
                                                    </span>
                                                    <span class="badge bg-danger ms-2">
                                                        {{ number_format((($product->original_price - $product->price) / $product->original_price) * 100, 0) }}% OFF
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Stock</th>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="progress flex-grow-1 me-3" style="height: 10px;">
                                                        @php
                                                            $stockPercentage = min(100, ($product->stock / 100) * 100);
                                                            $stockClass = $stockPercentage > 50 ? 'bg-success' : ($stockPercentage > 20 ? 'bg-warning' : 'bg-danger');
                                                        @endphp
                                                        <div class="progress-bar {{ $stockClass }}" 
                                                             role="progressbar" 
                                                             style="width: {{ $stockPercentage }}%" 
                                                             aria-valuenow="{{ $stockPercentage }}" 
                                                             aria-valuemin="0" 
                                                             aria-valuemax="100">
                                                        </div>
                                                    </div>
                                                    <span class="fw-medium">{{ $product->stock }}</span>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Status</th>
                                            <td>
                                                <span class="badge bg-{{ $product->status === 'active' ? 'success' : 'secondary' }}">
                                                    {{ ucfirst($product->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Created At</th>
                                            <td>{{ $product->created_at->format('M d, Y \a\t h:i A') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Last Updated</th>
                                            <td>{{ $product->updated_at->format('M d, Y \a\t h:i A') }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <!-- Inventory Summary -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0">Inventory Summary</h6>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h5 class="mb-0">{{ $product->stock }}</h5>
                                    <small class="text-muted">In Stock</small>
                                </div>
                                <div class="text-end">
                                    <h5 class="mb-0">£{{ number_format($product->price * $product->stock, 2) }}</h5>
                                    <small class="text-muted">Inventory Value</small>
                                </div>
                            </div>
                            
                            <div class="alert alert-{{ $product->stock > 10 ? 'success' : ($product->stock > 0 ? 'warning' : 'danger') }} mb-0">
                                <div class="d-flex align-items-center">
                                    <i class="bi {{ $product->stock > 10 ? 'bi-check-circle' : ($product->stock > 0 ? 'bi-exclamation-triangle' : 'bi-x-circle') }} me-2"></i>
                                    <div>
                                        @if($product->stock > 10)
                                            <strong>Good Stock Level</strong>
                                            <div class="small">You have sufficient inventory.</div>
                                        @elseif($product->stock > 0)
                                            <strong>Low Stock Warning</strong>
                                            <div class="small">Consider restocking soon.</div>
                                        @else
                                            <strong>Out of Stock</strong>
                                            <div class="small">This product is currently unavailable.</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0">Quick Actions</h6>
                        </div>
                        <div class="list-group list-group-flush">
                            <a href="{{ route('dashboard.products.edit', $product) }}" 
                               class="list-group-item list-group-item-action d-flex align-items-center">
                                <i class="bi bi-pencil me-3 text-primary"></i>
                                <div>
                                    <div class="fw-medium">Edit Product</div>
                                    <small class="text-muted">Update product details, pricing, or images</small>
                                </div>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action d-flex align-items-center">
                                <i class="bi bi-arrow-left-right me-3 text-primary"></i>
                                <div>
                                    <div class="fw-medium">Update Stock</div>
                                    <small class="text-muted">Add or remove inventory</small>
                                </div>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action d-flex align-items-center">
                                <i class="bi bi-tags me-3 text-primary"></i>
                                <div>
                                    <div class="fw-medium">Manage Discounts</div>
                                    <small class="text-muted">Create or update special offers</small>
                                </div>
                            </a>
                            <form action="{{ route('dashboard.products.destroy', $product) }}" 
                                  method="POST" 
                                  onsubmit="return confirm('Are you sure you want to delete this product? This action cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="list-group-item list-group-item-action d-flex align-items-center text-danger w-100 border-0">
                                    <i class="bi bi-trash me-3"></i>
                                    <div>
                                        <div class="fw-medium">Delete Product</div>
                                        <small class="text-muted">Permanently remove this product</small>
                                    </div>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Product Status -->
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">Product Status</h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('dashboard.products.update', $product) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select" id="status" name="status" onchange="this.form.submit()">
                                        <option value="active" {{ $product->status === 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ $product->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                            </form>
                            
                            <div class="alert alert-{{ $product->status === 'active' ? 'success' : 'secondary' }} mb-0">
                                <div class="d-flex">
                                    <i class="bi {{ $product->status === 'active' ? 'bi-check-circle' : 'bi-pause-circle' }} me-2 mt-1"></i>
                                    <div>
                                        This product is currently <strong>{{ $product->status === 'active' ? 'visible' : 'hidden' }}</strong> to customers.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
