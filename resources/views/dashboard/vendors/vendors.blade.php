<x-admin-layout>
    <div class="main-content">
       
        @include('layouts.admin-header')

        <!-- Vendor Management Content -->
        <div class="container-fluid p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 fw-bold">Vendor Management </h1>
                    <p class="text-muted mb-0">Manage vendor accounts, approvals, and performance.</p>
                </div>
                <button class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>
                    Add New Vendor
                </button>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-1">Vendor List</h5>
                    <p class="card-text text-muted small mb-0">All registered vendors</p>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Location</th>
                                    <th class="d-none d-sm-table-cell">Phone</th>
                                    <th class="d-none d-md-table-cell">Email</th>
                                    <th class="d-none d-sm-table-cell">Image</th>
                                    <th>Verified</th>
                                    <th class="d-none d-lg-table-cell">Opening Hours</th>
                                    <th class="d-none d-sm-table-cell">Is Approved</th>
                                    <th class="d-none d-lg-table-cell">Approved At</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                                
                            </thead>
                            <tbody>
                                @if ($vendors->isEmpty())
                                    <tr>
                                        <td colspan="10" class="text-center">No vendors found.</td>
                                    </tr>
                                @else
                                    @foreach ($vendors as $vendor)
                                        <tr>
                                            <td>{{ $vendor->name }}</td>
                                            <td>{{ $vendor->location }}</td>
                                            <td class="d-none d-sm-table-cell">{{ $vendor->phone }}</td>
                                            <td class="d-none d-md-table-cell">{{ $vendor->email }}</td>
                                            <td class="d-none d-sm-table-cell">
                                                @if ($vendor->image)
                                                    <img src="{{ asset('uploads/' . $vendor->image) }}" alt="Vendor Image" class="img-thumbnail" style="width: 60px; height: 60px; object-fit: cover;">
                                                @else
                                                    <span class="text-muted">No image</span>
                                                @endif
                                            </td>
                                            <td>{{ $vendor->verified ? 'Yes' : 'No' }}</td>
                                            <td class="d-none d-lg-table-cell">{{ $vendor->opening_hours }}</td>
                                            {{-- <td>{{ $vendor->delivery_time }}</td>
                                            {{-- <td>{{ $vendor->delivery_fee }}</td> --}}
                                            <td class="d-none d-sm-table-cell">{{ $vendor->is_approved ? 'Yes' : 'No' }}</td>
                                            <td class="d-none d-lg-table-cell">{{ $vendor->approved_at }}</td>
                                            <td class="text-end">
                                                <button class="btn btn-sm btn-outline-primary me-2"><i class="bi bi-eye"></i> View</button>
                                                <form action="{{ route('vendors.destroy', $vendor) }}" method="POST" class="d-inline" onsubmit="return confirm('Remove vendor?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-x-circle"></i> Remove</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-admin-layout>