<x-admin-layout>
<div class="main-content">
    @include('layouts.admin-header')

    <div class="container-fluid p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 fw-bold">Distributor Management</h1>
                <p class="text-muted mb-0">Manage distributor accounts and logistics.</p>
            </div>
            <a href="{{ route('distributors.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>
                Add New Distributor
            </a>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-1">Distributor List</h5>
                <p class="card-text text-muted small mb-0">All registered distributors</p>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Contact Person</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Location</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($distributors as $distributor)
                                <tr>
                                    <td>{{ $distributor->name }}</td>
                                    <td>{{ $distributor->contact_person }}</td>
                                    <td>{{ $distributor->email }}</td>
                                    <td>{{ $distributor->phone }}</td>
                                    <td>
                                        {{ $distributor->city }}, {{ $distributor->state }}, {{ $distributor->country }}
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $distributor->status ? 'success' : 'secondary' }}">
                                            {{ $distributor->status ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('distributors.show', $distributor) }}" class="btn btn-sm btn-outline-primary me-1">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('distributors.edit', $distributor) }}" class="btn btn-sm btn-outline-secondary me-1">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('distributors.destroy', $distributor) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this distributor?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">No distributors found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{ $distributors->links() }}
            </div>
        </div>
    </div>
</div>
</x-admin-layout>
