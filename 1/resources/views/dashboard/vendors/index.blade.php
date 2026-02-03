<x-admin-layout>


    <div class="main-content">
        @include('layouts.admin-header')

        <div class="container-fluid p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 fw-bold">Vendor Management</h1>
                    <p class="text-muted mb-0">Manage vendor accounts, approvals, and performance.</p>
                </div>
                <a href="{{ route('vendors.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>
                    Add New Vendor
                </a>
            </div>

            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title mb-1">Vendor List</h5>
                            <p class="card-text text-muted small mb-0">All registered vendors</p>
                        </div>
                        <div class="d-flex gap-2">
                            <div class="input-group input-group-sm" style="width: 250px;">
                                <input type="text" class="form-control" placeholder="Search vendors..." id="searchInput">
                                <button class="btn btn-outline-secondary" type="button">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-funnel me-1"></i> Filter
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="filterDropdown" style="min-width: 200px;">
                                    <li><h6 class="dropdown-header">Status</h6></li>
                                    <li><a class="dropdown-item" href="#">All Vendors</a></li>
                                    <li><a class="dropdown-item" href="#">Approved</a></li>
                                    <li><a class="dropdown-item" href="#">Pending Approval</a></li>
                                    <li><a class="dropdown-item" href="#">Suspended</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><h6 class="dropdown-header">Verification</h6></li>
                                    <li><a class="dropdown-item" href="#">Verified</a></li>
                                    <li><a class="dropdown-item" href="#">Unverified</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Mobile Card List (xs-sm) -->
                    <div class="d-md-none">
                        @forelse($vendors as $vendor)
                            <div class="card mb-3 shadow-sm border-0">
                                <div class="card-body">
                                    <div class="d-flex align-items-start">
                                        <div class="flex-shrink-0 me-3">
                                            @if($vendor->image)
                                                <img src="{{ asset('uploads/' . $vendor->image) }}" alt="{{ $vendor->name }}" class="rounded" style="width:56px; height:56px; object-fit:cover;">
                                            @else
                                                <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width:56px; height:56px;">
                                                    <i class="bi bi-shop text-muted"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <h6 class="mb-1">{{ $vendor->name }}</h6>
                                                    <div class="small text-muted">{{ $vendor->cuisine ?? 'N/A' }}</div>
                                                </div>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-outline-secondary" type="button" id="vendorActionsXs{{ $vendor->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="bi bi-three-dots-vertical"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="vendorActionsXs{{ $vendor->id }}">
                                                        <li><a class="dropdown-item" href="{{ route('vendors.show', $vendor) }}"><i class="bi bi-eye me-2"></i>View Details</a></li>
                                                        <li><a class="dropdown-item" href="{{ route('vendors.edit', $vendor) }}"><i class="bi bi-pencil me-2"></i>Edit</a></li>
                                                        @if(!$vendor->is_approved)
                                                            <li>
                                                                <form action="{{ route('vendors.approve', $vendor) }}" method="POST" class="d-inline">
                                                                    @csrf
                                                                    @method('PATCH')
                                                                    <button type="submit" class="dropdown-item"><i class="bi bi-check-circle me-2"></i>Approve</button>
                                                                </form>
                                                            </li>
                                                        @endif
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li>
                                                            <form action="{{ route('vendors.destroy', $vendor) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this vendor? This action cannot be undone.');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item text-danger"><i class="bi bi-trash me-2"></i>Delete</button>
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="mt-2 small text-muted">
                                                <div class="mb-1"><i class="bi bi-geo-alt me-1"></i>{{ $vendor->location }}</div>
                                                <div class="d-flex flex-wrap gap-2">
                                                    <span><i class="bi bi-telephone me-1"></i>{{ $vendor->phone ?? 'N/A' }}</span>
                                                    <span class="text-truncate" style="max-width: 180px;"><i class="bi bi-envelope me-1"></i>{{ $vendor->email }}</span>
                                                </div>
                                            </div>
                                            <div class="mt-2 d-flex flex-wrap gap-2">
                                                @if($vendor->is_approved)
                                                    <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Approved</span>
                                                @else
                                                    <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split me-1"></i>Pending</span>
                                                @endif
                                                @if($vendor->verified)
                                                    <span class="badge bg-info"><i class="bi bi-patch-check me-1"></i>Verified</span>
                                                @endif
                                                @if($vendor->featured)
                                                    <span class="badge bg-purple"><i class="bi bi-star me-1"></i>Featured</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <div class="mb-2"><i class="bi bi-shop display-6 text-muted"></i></div>
                                <h6 class="mb-1">No Vendors Found</h6>
                                <p class="text-muted small mb-2">There are no vendors registered yet.</p>
                                <a href="{{ route('vendors.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-circle me-1"></i>Add Vendor</a>
                            </div>
                        @endforelse
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle d-none d-md-table">
                            <thead>
                                <tr>
                                    <th>Vendor</th>
                                    <th>Cuisine</th>
                                    <th>Location</th>
                                    <th>Contact</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($vendors as $vendor)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 me-2">
                                                    @if($vendor->image)
                                                        <img src="{{ asset('uploads/' . $vendor->image) }}" alt="{{ $vendor->name }}" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                                                    @else
                                                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                            <i class="bi bi-shop text-muted"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-0">{{ $vendor->name }}</h6>
                                                    <small class="text-muted">{{ $vendor->user->email ?? 'No account' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark">{{ $vendor->cuisine ?? 'N/A' }}</span>
                                        </td>
                                        <td>
                                            <small class="d-block text-muted">{{ $vendor->location }}</small>
                                            <small class="text-muted">{{ $vendor->delivery_time ?? 'N/A' }} • {{ $vendor->delivery_fee ? '£' . number_format($vendor->delivery_fee, 2) : 'Free delivery' }}</small>
                                        </td>
                                        <td>
                                            <small class="d-block">{{ $vendor->phone ?? 'N/A' }}</small>
                                            <small class="text-muted">{{ $vendor->email }}</small>
                                        </td>
                                        <td>
                                            @if($vendor->is_approved)
                                                <span class="badge bg-success">
                                                    <i class="bi bi-check-circle me-1"></i> Approved
                                                </span>
                                            @else
                                                <span class="badge bg-warning text-dark">
                                                    <i class="bi bi-hourglass-split me-1"></i> Pending
                                                </span>
                                            @endif
                                            
                                            @if($vendor->verified)
                                                <span class="badge bg-info mt-1 d-inline-block">
                                                    <i class="bi bi-patch-check me-1"></i> Verified
                                                </span>
                                            @endif
                                            
                                            @if($vendor->featured)
                                                <span class="badge bg-purple mt-1 d-inline-block">
                                                    <i class="bi bi-star me-1"></i> Featured
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="vendorActions{{ $vendor->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="bi bi-three-dots-vertical"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="vendorActions{{ $vendor->id }}">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('vendors.show', $vendor) }}">
                                                            <i class="bi bi-eye me-2"></i> View Details
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('vendors.edit', $vendor) }}">
                                                            <i class="bi bi-pencil me-2"></i> Edit
                                                        </a>
                                                    </li>
                                                    @if(!$vendor->is_approved)
                                                        <li>
                                                            <form action="{{ route('vendors.approve', $vendor) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button type="submit" class="dropdown-item">
                                                                    <i class="bi bi-check-circle me-2"></i> Approve
                                                                </button>
                                                            </form>
                                                        </li>
                                                    @endif
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <form action="{{ route('vendors.destroy', $vendor) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this vendor? This action cannot be undone.');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger">
                                                                <i class="bi bi-trash me-2"></i> Delete
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <div class="mb-3">
                                                <i class="bi bi-shop display-4 text-muted"></i>
                                            </div>
                                            <h5>No Vendors Found</h5>
                                            <p class="text-muted">There are no vendors registered yet.</p>
                                            <a href="{{ route('vendors.create') }}" class="btn btn-primary">
                                                <i class="bi bi-plus-circle me-2"></i> Add New Vendor
                                            </a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($vendors->hasPages())
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <div class="text-muted small">
                                Showing {{ $vendors->firstItem() }} to {{ $vendors->lastItem() }} of {{ $vendors->total() }} entries
                            </div>
                            <div>
                                {{ $vendors->links() }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .bg-purple {
            background-color: #6f42c1;
        }
        
        .table th {
            font-weight: 600;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6c757d;
            border-top: none;
            padding-top: 1rem;
            padding-bottom: 1rem;
        }
        
        .table td {
            vertical-align: middle;
        }
        
        .dropdown-item {
            font-size: 0.85rem;
        }
        
        .badge {
            font-weight: 500;
            letter-spacing: 0.3px;
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
            
            // Search functionality
            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                searchInput.addEventListener('keyup', function(e) {
                    const searchValue = e.target.value.toLowerCase();
                    const rows = document.querySelectorAll('tbody tr');
                    
                    rows.forEach(row => {
                        const text = row.textContent.toLowerCase();
                        row.style.display = text.includes(searchValue) ? '' : 'none';
                    });
                });
            }
        });
    </script>
    @endpush
</x-admin-layout>
