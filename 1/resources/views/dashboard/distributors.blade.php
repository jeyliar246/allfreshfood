<x-admin-layout>
    <div class="main-content">
       
        @include('layouts.admin-header')

        <!-- Distributor Management Content -->
        <div class="container-fluid p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 fw-bold">Distributor Management</h1>
                    <p class="text-muted mb-0">Manage distributor accounts and logistics.</p>
                </div>
                <button class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>
                    Add New Distributor
                </button>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-1">Distributor List</h5>
                    <p class="card-text text-muted small mb-0">All registered distributors</p>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Distributor Name</th>
                                    <th>Contact</th>
                                    <th>Status</th>
                                    <th>Regions</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                               @if ($distributors->isEmpty())
                               <tr>
                                   <td colspan="5" class="text-center">No distributors found.</td>
                               </tr>
                               @else
                                @foreach ($distributors as $distributor)
                                        <tr>
                                            <td>{{ $distributor->name }}</td>
                                            <td>{{ $distributor->contact }}</td>
                                            <td>{{ $distributor->status }}</td>
                                            <td>{{ $distributor->regions }}</td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary me-2"><i class="bi bi-eye"></i> View</button>
                                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-x-circle"></i> Remove</button>
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