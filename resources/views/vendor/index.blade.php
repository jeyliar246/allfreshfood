<x-admin-layout>


<div class="main-content">
    @include('layouts.admin-header')

    <div class="container-fluid p-4">
        <!-- Header with Back Button -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 fw-bold">{{ $vendor->name }}</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($vendor->name, 20) }}</li>
                    </ol>
                </nav>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('vendors.edit', $vendor) }}" class="btn btn-primary">
                    <i class="bi bi-pencil me-2"></i>
                    Edit Vendor
                </a>
            </div>
        </div>

        <!-- Vendor Overview -->
        <div class="row">
            <div class="col-lg-8">
                <!-- Vendor Details Card -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex align-items-start">
                            <div class="flex-shrink-0 me-4">
                                <img src="{{ $vendor->image ? asset('uploads/' . $vendor->image) : asset('assets/img/default-logo.png') }}" alt="{{ $vendor->name }}" class="rounded" style="width: 120px; height: 120px; object-fit: cover;">
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h4 class="mb-1">{{ $vendor->name }}</h4>
                                        <p class="text-muted mb-2">{{ $vendor->cuisine ?? 'No cuisine specified' }}</p>
                                        
                                        <div class="d-flex gap-2 mb-2">
                                            @if($vendor->is_approved)
                                                <span class="badge bg-success">
                                                    <i class="bi bi-check-circle me-1"></i> Approved
                                                </span>
                                            @else
                                                <span class="badge bg-warning text-dark">
                                                    <i class="bi bi-hourglass-split me-1"></i> Pending Approval
                                                </span>
                                            @endif
                                            
                                            @if($vendor->verified)
                                                <span class="badge bg-info">
                                                    <i class="bi bi-patch-check me-1"></i> Verified
                                                </span>
                                            @endif
                                            
                                            @if($vendor->featured)
                                                <span class="badge bg-purple">
                                                    <i class="bi bi-star me-1"></i> Featured
                                                </span>
                                            @endif
                                        </div>
                                        
                                        <div class="d-flex flex-wrap gap-3 text-muted small mb-2">
                                            <span><i class="bi bi-geo-alt me-1"></i> {{ $vendor->location ?? 'No location specified' }}</span>
                                            <span><i class="bi bi-telephone me-1"></i> {{ $vendor->phone ?? 'No phone' }}</span>
                                            <span><i class="bi bi-envelope me-1"></i> {{ $vendor->email }}</span>
                                            @if($vendor->website)
                                                <span><i class="bi bi-globe me-1"></i> <a href="{{ $vendor->website }}" target="_blank" class="text-decoration-none">Website</a></span>
                                            @endif
                                        </div>
                                        
                                        <div class="d-flex gap-3 text-muted small">
                                            <span><i class="bi bi-clock me-1"></i> {{ $vendor->opening_hours ?? 'No opening hours' }}</span>
                                            <span><i class="bi bi-truck me-1"></i> {{ $vendor->delivery_time ?? 'No delivery time' }}</span>
                                            <span><i class="bi bi-cash me-1"></i>£{{ number_format($vendor->delivery_fee, 2) }} delivery</span>
                                            <span><i class="bi bi-cart-check me-1"></i> Min:£{{ number_format($vendor->min_order, 2) }}</span>
                                        </div>
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="vendorActions" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="vendorActions">
                                            <li>
                                                <a class="dropdown-item" href="{{ route('vendors.edit', $vendor) }}">
                                                    <i class="bi bi-pencil me-2"></i> Edit Vendor
                                                </a>
                                            </li>
                                            @if(!$vendor->is_approved)
                                                <li>
                                                    <form action="{{ route('vendors.approve', $vendor) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="dropdown-item">
                                                            <i class="bi bi-check-circle me-2"></i> Approve Vendor 
                                                        </button>
                                                    </form>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        @if($vendor->description)
                            <div class="mt-4">
                                <h6>About</h6>
                                <p class="mb-0">{{ $vendor->description }}</p>
                            </div>
                        @endif
                        
                        @if($vendor->cover_image)
                            <div class="mt-4">
                                <img src="{{ asset('uploads/' . $vendor->cover_image) }}" alt="{{ $vendor->name }} cover" class="img-fluid rounded" style="max-height: 300px; width: 100%; object-fit: cover;">
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- Products Section -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Products</h5>
                        {{-- <a href="{{ route('products.create', ['vendor_id' => $vendor->id]) }}" class="btn btn-sm btn-primary"> --}}
                        <a href="{{ route('dashboard.products.create') }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-plus-lg me-1"></i> Add Product
                        </a>
                    </div>
                    <div class="card-body">
                        @if($vendor->products->count() > 0)

                            <!-- Mobile Card List (Products) -->
                            <div class="d-md-none">
                                @foreach($vendor->products->take(5) as $product)
                                    <div class="card mb-2 shadow-sm border-0">
                                        <div class="card-body py-2">
                                            <div class="d-flex align-items-start">
                                                <div class="flex-shrink-0 me-2">
                                                    <img src="{{ $product->image ? asset('uploads/' . $product->image) : asset('assets/img/default-product.png') }}" alt="{{ $product->name }}" class="rounded" style="width:48px; height:48px; object-fit:cover;">
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div class="d-flex justify-content-between align-items-start">
                                                        <div>
                                                            <div class="fw-semibold">{{ $product->name }}</div>
                                                            <div class="small text-muted">{{ Str::limit($product->description, 40) }}</div>
                                                        </div>
                                                        <div class="ms-2 text-nowrap fw-semibold">£{{ number_format($product->price, 2) }}</div>
                                                    </div>
                                                    <div class="mt-1 d-flex flex-wrap gap-2 small">
                                                        <span class="badge bg-light text-dark">{{ $product->category ?? 'Uncategorized' }}</span>
                                                        @if($product->stock > 0)
                                                            <span class="badge bg-success">Available</span>
                                                        @else
                                                            <span class="badge bg-secondary">Out of Stock</span>
                                                        @endif
                                                    </div>
                                                    <div class="mt-2">
                                                        <div class="btn-group btn-group-sm" role="group">
                                                            <a href="{{ route('dashboard.products.show', $product) }}" class="btn btn-outline-primary"><i class="bi bi-eye"></i></a>
                                                            <a href="{{ route('dashboard.products.edit', $product) }}" class="btn btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                                                            <form action="{{ route('dashboard.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-outline-danger"><i class="bi bi-trash"></i></button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="table-responsive d-none d-md-block">
                                <table class="table table-hover align-middle">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Category</th>
                                            <th>Price</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($vendor->products->take(5) as $product)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0 me-2">
                                                            <img src="{{ $product->image ? asset('uploads/' . $product->image) : asset('assets/img/default-product.png') }}" alt="{{ $product->name }}" class="rounded" style="width: 40px; height: 40px; object-fit: cover;">
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0">{{ $product->name }}</h6>
                                                            <small class="text-muted">{{ Str::limit($product->description, 30) }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $product->category ?? 'Uncategorized' }}</td>
                                                <td>£{{ number_format($product->price, 2) }}</td>
                                                <td>
                                                    @if($product->stock > 0)
                                                        <span class="badge bg-success">Available</span>
                                                    @else
                                                        <span class="badge bg-secondary">Out of Stock</span>
                                                    @endif
                                                </td>
                                                <td class="text-nowrap">
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="productActions{{ $product->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="bi bi-three-dots-vertical"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="productActions{{ $product->id }}">
                                                            <li><a class="dropdown-item" href="{{ route('dashboard.products.show', $product) }}"><i class="bi bi-eye me-2"></i>View</a></li>
                                                            <li><a class="dropdown-item" href="{{ route('dashboard.products.edit', $product) }}"><i class="bi bi-pencil me-2"></i>Edit</a></li>
                                                            <li><hr class="dropdown-divider"></li>
                                                            <li>
                                                                <form action="{{ route('dashboard.products.destroy', $product) }}" method="POST" class="d-inline">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Are you sure you want to delete this product?')">
                                                                        <i class="bi bi-trash me-2"></i>Delete
                                                                    </button>
                                                                </form>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                            </div>
                            
                            @if($vendor->products->count() > 5)
                                <div class="text-center mt-3 d-none d-md-block">
                                    <a href="{{ route('dashboard.products.index') }}" class="btn btn-sm btn-primary">
                                        View All {{ $vendor->products_count }} Products
                                    </a>
                                </div>
                            @endif
                        @else
                            <div class="text-center py-4">
                                <div class="mb-3">
                                    <i class="bi bi-box-seam display-4 text-muted"></i>
                                </div>
                                <h5>No Products Found</h5>
                                <p class="text-muted">This vendor hasn't added any products yet.</p>
                                {{-- <a href="{{ route('products.create', ['vendor_id' => $vendor->id]) }}" class="btn btn-primary"> --}}
                                    <a href="#" class="btn btn-sm btn-primary">
                                    <i class="bi bi-plus-lg me-2"></i> Add First Product
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- Orders Section -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Recent Orders</h5>
                    </div>
                    <div class="card-body">
                        @if($vendor->orders->count() > 0)

                            <div class="d-md-none">
                                @foreach($vendor->orders->sortByDesc('created_at')->take(5) as $order)
                                    <div class="card mb-2 shadow-sm border-0">
                                        <div class="card-body py-2">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <div class="fw-semibold">#{{ $order->id }}</div>
                                                    <div class="small text-muted">{{ $order->created_at->format('M d, Y') }} • {{ $order->user->name ?? 'Guest' }}</div>
                                                </div>
                                                <div class="text-end">
                                                    <div class="fw-semibold">£{{ number_format($order->total, 2) }}</div>
                                                    @php
                                                        $statusClasses = [
                                                            'pending' => 'warning',
                                                            'processing' => 'info',
                                                            'shipped' => 'primary',
                                                            'delivered' => 'success',
                                                            'cancelled' => 'danger',
                                                            'refunded' => 'secondary'
                                                        ];
                                                        $statusClass = $statusClasses[$order->status] ?? 'secondary';
                                                    @endphp
                                                    <span class="badge bg-{{ $statusClass }}">{{ ucfirst($order->status) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="table-responsive d-none d-md-block">
                                <table class="table table-hover align-middle">
                                    <thead>
                                        <tr>
                                            <th>Order #</th>
                                            <th>Date</th>
                                            <th>Customer</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($vendor->orders->sortByDesc('created_at')->take(5) as $order)
                                            <tr>
                                                <td>#{{ $order->id }}</td>
                                                <td>{{ $order->created_at->format('M d, Y') }}</td>
                                                <td>{{ $order->user->name ?? 'Guest' }}</td>
                                                <td>£{{ number_format($order->total, 2) }}</td>
                                                <td>
                                                    @php
                                                        $statusClasses = [
                                                            'pending' => 'warning',
                                                            'processing' => 'info',
                                                            'shipped' => 'primary',
                                                            'delivered' => 'success',
                                                            'cancelled' => 'danger',
                                                            'refunded' => 'secondary'
                                                        ];
                                                        $statusClass = $statusClasses[$order->status] ?? 'secondary';
                                                    @endphp
                                                    <span class="badge bg-{{ $statusClass }}">
                                                        {{ ucfirst($order->status) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    {{-- <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-outline-primary"> --}}
                                                    <a href="#" class="btn btn-sm btn-outline-primary">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            @if($vendor->orders->count() > 5)
                                <div class="text-center mt-3">
                                    <a href="{{ route('vendor.orders.index', ['vendor_id' => $vendor->id]) }}" class="btn btn-outline-primary btn-sm">
                                        View All {{ $vendor->orders_count }} Orders 
                                    </a>
                                </div>
                            @endif
                        @else
                            <div class="text-center py-4">
                                <div class="mb-3">
                                    <i class="bi bi-cart-x display-4 text-muted"></i>
                                </div>
                                <h5>No Orders Found </h5>
                                <p class="text-muted">This vendor hasn't received any orders yet.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <!-- Stats Card -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Vendor Stats</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted">Registration Date</span>
                                <span class="fw-medium">{{ $vendor->created_at->format('M d, Y') }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted">Last Updated</span>
                                <span class="fw-medium">{{ $vendor->updated_at->diffForHumans() }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">Status</span>
                                @if($vendor->is_approved)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-warning text-dark">Pending Approval</span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="list-group list-group-flush mb-3">
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <div>
                                    <i class="bi bi-box-seam text-primary me-2"></i>
                                    <span>Total Products</span>
                                </div>
                                <span class="badge bg-primary rounded-pill">{{$stats['total_products'] ?? 0 }}</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <div>
                                    <i class="bi bi-cart-check text-success me-2"></i>
                                    <span>Total Orders</span>
                                </div>
                                <span class="badge bg-success rounded-pill">{{ $stats['total_orders'] ?? 0 }}</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <div>
                                    <i class="bi bi-currency-dollar text-warning me-2"></i>
                                    <span>Total Revenue</span>
                                </div>
                                <span class="fw-medium">£{{ number_format($stats['total_revenue'], 2) ?? 0 }}</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <div>
                                    <i class="bi bi-star-fill text-warning me-2"></i>
                                    <span>Average Rating</span>
                                </div>
                                <div>
                                    <span class="fw-medium me-1">{{ number_format($vendor->average_rating ?? 0, 1) }}</span>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <small class="text-muted">({{ $vendor->ratings_count ?? 0 }} reviews)</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-grid">
                            @if($vendor->is_approved)
                                <button type="submit" class="btn btn-outline-success w-100 mb-2">
                                    <i class="bi bi-check-circle me-2"></i> Verified
                                </button>
                            @endif
                            
                            <a href="{{ route('vendors.edit', $vendor) }}" class="btn btn-outline-primary w-100 mb-2">
                                <i class="bi bi-pencil me-2"></i> Edit Details
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Contact Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Contact Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <h6 class="small text-muted mb-1">Email Address</h6>
                            <p class="mb-2">
                                <a href="mailto:{{ $vendor->email }}" class="text-decoration-none">
                                    <i class="bi bi-envelope me-2"></i> {{ $vendor->email }}
                                </a>
                            </p>
                        </div>
                        
                        @if($vendor->phone)
                            <div class="mb-3">
                                <h6 class="small text-muted mb-1">Phone Number</h6>
                                <p class="mb-2">
                                    <a href="tel:{{ $vendor->phone }}" class="text-decoration-none">
                                        <i class="bi bi-telephone me-2"></i> {{ $vendor->phone }}
                                    </a>
                                </p>
                            </div>
                        @endif
                        
                        @if($vendor->website)
                            <div class="mb-3">
                                <h6 class="small text-muted mb-1">Website</h6>
                                <p class="mb-0">
                                    <a href="{{ $vendor->website }}" target="_blank" class="text-decoration-none">
                                        <i class="bi bi-globe me-2"></i> {{ parse_url($vendor->website, PHP_URL_HOST) }}
                                    </a>
                                </p>
                            </div>
                        @endif
                        
                        <div class="mb-0">
                            <h6 class="small text-muted mb-1">Location</h6>
                            <p class="mb-0">
                                <i class="bi bi-geo-alt me-2"></i> {{ $vendor->location ?? 'No location specified' }}
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Business Hours -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Business Hours</h5>
                    </div>
                    <div class="card-body">
                        @if($vendor->opening_hours)
                            <p class="mb-0">{!! nl2br(e($vendor->opening_hours)) !!}</p>
                        @else
                            <p class="text-muted mb-0">No business hours specified</p>
                        @endif
                    </div>
                </div>
                
                <!-- Delivery Information -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Delivery Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <h6 class="small text-muted mb-1">Delivery Time</h6>
                            <p class="mb-0">{{ $vendor->delivery_time ?? 'Not specified' }}</p>
                        </div>
                        
                        <div class="mb-3">
                            <h6 class="small text-muted mb-1">Delivery Fee</h6>
                            <p class="mb-0">£{{ number_format($vendor->delivery_fee, 2) }}</p>
                        </div>
                        
                        <div class="mb-0">
                            <h6 class="small text-muted mb-1">Minimum Order</h6>
                            <p class="mb-0">£{{ number_format($vendor->min_order, 2) }}</p>
                        </div>
                        
                        @if($vendor->free_delivery_over > 0)
                            <div class="mt-3">
                                <span class="badge bg-success">
                                    <i class="bi bi-truck me-1"></i> Free delivery on orders over£{{ number_format($vendor->free_delivery_over, 2) }}
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Vendor Modal -->
<div class="modal fade" id="deleteVendorModal" tabindex="-1" aria-labelledby="deleteVendorModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteVendorModalLabel">Delete Vendor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete <strong>{{ $vendor->name }}</strong>? This action cannot be undone.</p>
                <p class="text-danger mb-0">Warning: This will also delete all products and orders associated with this vendor.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('vendors.destroy', $vendor) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash me-1"></i> Delete Vendor
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .bg-purple {
        background-color: #6f42c1;
    }
    
    .card {
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        margin-bottom: 1.5rem;
    }
    
    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        padding: 1rem 1.25rem;
    }
    
    .card-header h5 {
        font-size: 1rem;
        font-weight: 600;
        margin: 0;
    }
    
    .badge {
        font-weight: 500;
        padding: 0.35em 0.65em;
    }
    
    .list-group-item {
        border-left: none;
        border-right: none;
        padding: 0.75rem 0;
    }
    
    .list-group-item:first-child {
        border-top: none;
        padding-top: 0;
    }
    
    .list-group-item:last-child {
        padding-bottom: 0;
    }
    
    .text-muted {
        color: #6c757d !important;
    }
</style>
@endpush

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
