<x-admin-layout>
<div class="main-content">
    @include('layouts.admin-header')

    <div class="container-fluid p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 fw-bold">Distributor Details</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('distributors.index') }}">Distributors</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $distributor->name }}</li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="{{ route('distributors.edit', $distributor) }}" class="btn btn-outline-primary me-2">
                    <i class="bi bi-pencil me-2"></i>
                    Edit
                </a>
                <a href="{{ route('distributors.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>
                    Back to List
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-body text-center">
                        @if($distributor->logo)
                            <img src="{{ asset('uploads/' . $distributor->logo) }}" alt="{{ $distributor->name }} Logo" class="img-fluid rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                        @else
                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 150px; height: 150px;">
                                <i class="bi bi-building text-muted" style="font-size: 3rem;"></i>
                            </div>
                        @endif
                        
                        <h4 class="card-title mb-1">{{ $distributor->name }}</h4>
                        
                        <div class="mb-3">
                            <span class="badge bg-{{ $distributor->status ? 'success' : 'secondary' }} me-2">
                                {{ $distributor->status ? 'Active' : 'Inactive' }}
                            </span>
                            @if($distributor->website)
                                <a href="{{ $distributor->website }}" target="_blank" class="text-decoration-none">
                                    <span class="badge bg-info">
                                        <i class="bi bi-globe me-1"></i> Website
                                    </span>
                                </a>
                            @endif
                        </div>
                        
                        <p class="card-text text-muted">{{ $distributor->description }}</p>
                        
                        <div class="d-flex justify-content-center gap-2 mb-3">
                            <a href="mailto:{{ $distributor->email }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-envelope me-1"></i> Email
                            </a>
                            <a href="tel:{{ $distributor->phone }}" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-telephone me-1"></i> Call
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Contact Information</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2">
                                <i class="bi bi-person me-2 text-primary"></i>
                                {{ $distributor->contact_person }}
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-envelope me-2 text-primary"></i>
                                <a href="mailto:{{ $distributor->email }}" class="text-decoration-none">
                                    {{ $distributor->email }}
                                </a>
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-telephone me-2 text-primary"></i>
                                <a href="tel:{{ $distributor->phone }}" class="text-decoration-none">
                                    {{ $distributor->phone }}
                                </a>
                            </li>
                            @if($distributor->website)
                            <li class="mb-2">
                                <i class="bi bi-globe me-2 text-primary"></i>
                                <a href="{{ $distributor->website }}" target="_blank" class="text-decoration-none">
                                    {{ parse_url($distributor->website, PHP_URL_HOST) }}
                                </a>
                            </li>
                            @endif
                        </ul>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Address</h5>
                    </div>
                    <div class="card-body">
                        <address class="mb-0">
                            {{ $distributor->address }}<br>
                            {{ $distributor->city }}, {{ $distributor->state }}<br>
                            {{ $distributor->country }}<br>
                            @if($distributor->postal_code)
                                {{ $distributor->postal_code }}
                            @endif
                        </address>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Distributor Information</h5>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="actionsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="actionsDropdown">
                                <li>
                                    <a class="dropdown-item" href="{{ route('distributors.edit', $distributor) }}">
                                        <i class="bi bi-pencil me-2"></i> Edit
                                    </a>
                                </li>
                                <li>
                                    <form action="{{ route('distributors.destroy', $distributor) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this distributor?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bi bi-trash me-2"></i> Delete
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <h6 class="text-muted small mb-1">Status</h6>
                                    <p class="mb-0">
                                        @if($distributor->status)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <h6 class="text-muted small mb-1">Registration Date</h6>
                                    <p class="mb-0">{{ $distributor->created_at->format('M d, Y') }}</p>
                                </div>
                            </div>
                        </div>
                        
                        @if($distributor->description)
                            <div class="mb-3">
                                <h6 class="text-muted small mb-2">Description</h6>
                                <p class="mb-0">{{ $distributor->description }}</p>
                            </div>
                        @endif
                        
                        <div class="mt-4">
                            <h5 class="h6 mb-3">Additional Information</h5>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <tbody>
                                        <tr>
                                            <th class="text-muted" width="30%">Contact Person</th>
                                            <td>{{ $distributor->contact_person }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-muted">Email</th>
                                            <td>
                                                <a href="mailto:{{ $distributor->email }}" class="text-decoration-none">
                                                    {{ $distributor->email }}
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-muted">Phone</th>
                                            <td>
                                                <a href="tel:{{ $distributor->phone }}" class="text-decoration-none">
                                                    {{ $distributor->phone }}
                                                </a>
                                            </td>
                                        </tr>
                                        @if($distributor->website)
                                        <tr>
                                            <th class="text-muted">Website</th>
                                            <td>
                                                <a href="{{ $distributor->website }}" target="_blank" class="text-decoration-none">
                                                    {{ $distributor->website }}
                                                </a>
                                            </td>
                                        </tr>
                                        @endif
                                        <tr>
                                            <th class="text-muted">Address</th>
                                            <td>
                                                {{ $distributor->address }},<br>
                                                {{ $distributor->city }}, {{ $distributor->state }},<br>
                                                {{ $distributor->country }}
                                                @if($distributor->postal_code)
                                                    , {{ $distributor->postal_code }}
                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Products Section -->
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Products</h5>
                            <span class="badge bg-primary">{{ $distributor->products_count ?? 0 }} Products</span>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($distributor->products_count > 0)
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Category</th>
                                            <th>Price</th>
                                            <th>Stock</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($distributor->products as $product)
                                            <tr>
                                                <td>
                                                    <a href="#" class="text-decoration-none">{{ $product->name }}</a>
                                                </td>
                                                <td>{{ $product->category->name ?? 'N/A' }}</td>
                                                <td>{{ number_format($product->price, 2) }}</td>
                                                <td>{{ $product->stock_quantity }}</td>
                                                <td>
                                                    @if($product->is_available)
                                                        <span class="badge bg-success">Available</span>
                                                    @else
                                                        <span class="badge bg-secondary">Out of Stock</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-end mt-3">
                                <a href="#" class="btn btn-sm btn-outline-primary">View All Products</a>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <div class="mb-3">
                                    <i class="bi bi-box-seam display-4 text-muted"></i>
                                </div>
                                <h5>No Products Found</h5>
                                <p class="text-muted">This distributor doesn't have any products yet.</p>
                                <a href="#" class="btn btn-primary btn-sm">
                                    <i class="bi bi-plus-circle me-1"></i> Add Product
                                </a>
                            </div>
                        @endif
                    </div>
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
