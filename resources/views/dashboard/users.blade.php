<x-admin-layout>
    <div class="main-content">
       
        @include('layouts.admin-header')

         <!-- User Management Content -->
         <div class="container-fluid p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 fw-bold">User Management</h1>
                    <p class="text-muted mb-0">Manage customer and admin accounts.</p>
                </div>
                <button class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>
                    Add New User
                </button>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-1">User List</h5>
                    <p class="card-text text-muted small mb-0">All registered users</p>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                               @if ($users->isEmpty())
                               <tr>
                                   <td colspan="5" class="text-center">No users found.</td>
                               </tr>
                               @else
                                @foreach ($users as $user)
                                        <tr>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->phone }}</td>
                                            <td>{{ $user->role }}</td>
                                            <td>{{ $user->status }}</td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary me-2"><i class="bi bi-eye"></i> View</button>
                                                <a href="{{ route('users.destroy', $user->id) }}" onclick="return confirm('Are you sure you want to delete this user?')" class="btn btn-sm btn-outline-danger"><i class="bi bi-x-circle"></i> Remove</a>
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