<x-admin-layout>
    <div class="main-content">
       
        @include('layouts.admin-header')

        <!-- Product Management Content -->
        <div class="container-fluid p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 fw-bold">Product Management</h1>
                    <p class="text-muted mb-0">Manage product listings and inventory.</p>
                </div>
                <button class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>
                    Add New Product
                </button>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-1">Product List</h5>
                    <p class="card-text text-muted small mb-0">All products available on the platform</p>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Product Name</th>
                                    <th>Vendor</th>
                                    <th>Price (£)</th>
                                    <th>Stock</th>
                                    <th>Category</th>
                                    <th>Description</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($products->isEmpty())
                                    <tr>
                                        <td colspan="5" class="text-center">No products found.</td>
                                    </tr>
                                @else
                                    @foreach ($products as $product)
                                        <tr>
                                            <td>{{ $product->name }}</td>
                                            <td>{{ $product->vendor->name }}</td>
                                            <td>{{ $product->price }}</td>
                                            <td>{{ $product->stock }}</td>
                                            <td>{{ $product->category->name }}</td>
                                            <td>{{ $product->description }}</td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary me-2"><i class="bi bi-eye"></i> View</button>
                                                <a href="{{ route('products.destroy', $product->id) }}" onclick="return confirm('Are you sure you want to delete this product?')" class="btn btn-sm btn-outline-danger"><i class="bi bi-x-circle"></i> Remove</a>
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