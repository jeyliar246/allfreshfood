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
                       <h1 class="h3 fw-bold mb-0">Cuisines</h1>
                   </div>
                   <a href="{{ route('dashboard.cuisines.create') }}" class="btn btn-primary">
                       <i class="bi bi-plus-lg me-2"></i>Add New Cuisine
                   </a>
               </div>
           </div>
       </div>

       <div class="container-fluid p-4">
           <!-- Cuisines Table -->
           <div class="card">
               <div class="card-body">
                   @if(session('success'))
                       <div class="alert alert-success alert-dismissible fade show" role="alert">
                           {{ session('success') }}
                           <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                       </div>
                   @endif

                   <div class="table-responsive">
                       <table class="table table-hover">
                           <thead>
                               <tr>
                                   <th>SN</th>
                                   <th>Image</th>
                                   <th>Name</th>
                                   <th>Description</th>
                                   <th>Created At</th>
                                   <th>Actions</th>
                               </tr>
                           </thead>
                           <tbody>
                               @forelse($cuisines as $key => $cuisine)
                                   <tr>
                                       <td>{{ $key + 1 }}</td>
                                       <td>
                                           @if($cuisine->image)
                                               <img src="{{ asset('uploads/' . $cuisine->image) }}" alt="{{ $cuisine->name }}" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                                           @else
                                               <div class="bg-light d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                   <i class="bi bi-image text-muted"></i>
                                               </div>
                                           @endif
                                       </td>
                                       <td>{{ $cuisine->name }}</td>
                                       <td>{{ $cuisine->description }}</td>
                                       <td>
                                           <span class="badge bg-success">Active</span>
                                       </td>
                                       <td>
                                           <div class="btn-group" role="group">
                                               <a href="{{ route('dashboard.cuisines.edit', $cuisine) }}" class="btn btn-sm btn-outline-primary">
                                                   <i class="bi bi-pencil"></i>
                                               </a>
                                               <form action="{{ route('dashboard.cuisines.destroy', $cuisine) }}" method="POST" class="d-inline">
                                                   @csrf
                                                   @method('DELETE')
                                                   <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this cuisine?')">
                                                       <i class="bi bi-trash"></i>
                                                   </button>
                                               </form>
                                           </div>
                                       </td>
                                   </tr>
                               @empty
                                   <tr>
                                       <td colspan="6" class="text-center py-4">No cuisines found.</td>
                                   </tr>
                               @endforelse
                           </tbody>
                       </table>
                   </div>

                   <div class="d-flex justify-content-center mt-4">
                       {{ $cuisines->links() }}
                   </div>
               </div>
           </div>
       </div>
   </div>
</x-admin-layout>