<x-admin-layout>
    <div class="main-content">
        <!-- Header -->
        @include('layouts.admin-header')
        
        <div class="container-fluid">
                <div class="d-flex align-items-center">
                    <button class="btn btn-outline-secondary me-3 d-lg-none sidebar-toggle">
                        <i class="bi bi-list"></i>
                    </button>
                    <div class="flex-grow-1">
                        <h1 class="h3 fw-bold mb-0">Edit Product: {{ $product->name }}</h1>
                    </div>
                    <a href="{{ route('dashboard.products.index') }}" class="btn btn-outline-secondary me-2">
                        <i class="bi bi-arrow-left me-2"></i>Back to Products
                    </a>
                    <a href="{{ route('dashboard.products.show', $product) }}" class="btn btn-outline-primary">
                        <i class="bi bi-eye me-2"></i>View
                    </a>
                </div>
            </div>

        <div class="container-fluid p-4">
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('dashboard.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Product Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                                   id="name" name="name" value="{{ old('name', $product->name) }}" required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="vendor_id" class="form-label">Vendor <span class="text-danger">*</span></label>
                                            <select class="form-select @error('vendor_id') is-invalid @enderror" 
                                                    id="vendor_id" name="vendor_id" required>
                                                <option value="">Select Vendor</option>
                                                @foreach($vendors as $vendor)
                                                    <option value="{{ $vendor->id }}" {{ (old('vendor_id', $product->vendor_id) == $vendor->id) ? 'selected' : '' }}>
                                                        {{ $vendor->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('vendor_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                                            <select class="form-select @error('category_id') is-invalid @enderror" 
                                                    id="category_id" name="category_id" required>
                                                <option value="">Select Category</option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}" {{ (old('category_id', $product->category_id) == $category->id) ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('category_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="price" class="form-label">Price (£) <span class="text-danger">*</span></label>
                                            <input type="number" step="0.01" min="0" 
                                                   class="form-control @error('price') is-invalid @enderror" 
                                                   id="price" name="price" value="{{ old('price', $product->price) }}" required>
                                            @error('price')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="original_price" class="form-label">Original Price (£)</label>
                                            <input type="number" step="0.01" min="0" 
                                                   class="form-control @error('original_price') is-invalid @enderror" 
                                                   id="original_price" name="original_price" 
                                                   value="{{ old('original_price', $product->original_price) }}">
                                            <div class="form-text">Leave empty if not on sale</div>
                                            @error('original_price')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="stock" class="form-label">Stock <span class="text-danger">*</span></label>
                                            <input type="number" min="0" 
                                                   class="form-control @error('stock') is-invalid @enderror" 
                                                   id="stock" name="stock" value="{{ old('stock', $product->stock) }}" required>
                                            @error('stock')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                            <select class="form-select @error('status') is-invalid @enderror" 
                                                    id="status" name="status" required>
                                                <option value="active" {{ old('status', $product->status) == 'active' ? 'selected' : '' }}>Active</option>
                                                <option value="inactive" {{ old('status', $product->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="cuisine" class="form-label">Cuisine</label>
                                            <input type="text" class="form-control @error('cuisine') is-invalid @enderror" 
                                                   id="cuisine" name="cuisine" value="{{ old('cuisine', $product->cuisine) }}">
                                            @error('cuisine')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="3">{{ old('description', $product->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="halal" class="form-label">Halal</label>
                                            <input type="checkbox" id="halal" value="1" name="halal" {{ $product->halal ? 'checked' : '' }}>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="vegan" class="form-label">Vegan</label>
                                            <input type="checkbox" id="vegan" value="1" name="vegan" {{ $product->vegan ? 'checked' : '' }}>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="gluten_free" class="form-label">Gluten Free</label>
                                            <input type="checkbox" id="gluten_free" value="1" name="gluten_free" {{ $product->gluten_free ? 'checked' : '' }}>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="organic" class="form-label">Organic</label>
                                            <input type="checkbox" id="organic" value="1" name="organic" {{ $product->organic ? 'checked' : '' }}>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="fair_trade" class="form-label">Fair Trade</label>
                                            <input type="checkbox" id="fair_trade" value="1" name="fair_trade" {{ $product->fair_trade ? 'checked' : '' }}>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="non_GMO" class="form-label">Non GMO</label>
                                            <input type="checkbox" id="non_GMO" value="1" name="non_GMO" {{ $product->non_GMO ? 'checked' : '' }}>
                                        </div>
                                    </div>
                                </div>

                                 <hr class="text-primary" />

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="discount" class="form-label">Discount (%) <span
                                                    class="text-danger">*</span></label>
                                            <input type="number" min="0" max="100" step="0.01"
                                                class="form-control @error('discount') is-invalid @enderror"
                                                id="discount" name="discount" value="{{ $product->discount ?? 0 }}"
                                                required>
                                            @error('discount')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="deal" class="form-label">Deal Status <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-select @error('deal') is-invalid @enderror"
                                                id="deal" name="deal">
                                                <option value="">Select Deal </option>
                                                <option value="active"
                                                    {{ $product->deal == 'active' ? 'selected' : '' }}>Active</option>
                                                <option value="inactive"
                                                    {{ $product->deal == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                            </select>
                                            @error('deal')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="image" class="form-label">Product Image</label>
                                    
                                    @if($product->image)
                                        <div class="mb-3">
                                            <img src="{{ asset('uploads/' . $product->image) }}" 
                                                 alt="{{ $product->name }}" 
                                                 class="img-thumbnail d-block mb-2" 
                                                 style="max-width: 200px; max-height: 200px;">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="remove_image" name="remove_image">
                                                <label class="form-check-label" for="remove_image">
                                                    Remove current image
                                                </label>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    <input class="form-control @error('image') is-invalid @enderror" 
                                           type="file" id="image" name="image" accept="image/*">
                                    <div class="form-text">Recommended size: 800x800px. Max file size: 2MB.</div>
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    
                                    <div id="imagePreview" class="mt-2" style="display: none;">
                                        <img id="previewImg" src="#" alt="Preview" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between">
                                    <button type="button" class="btn btn-outline-danger" 
                                            onclick="if(confirm('Are you sure you want to delete this product?')) { document.getElementById('delete-form').submit(); }">
                                        <i class="bi bi-trash me-2"></i>Delete Product
                                    </button>
                                    <div>
                                        <a href="{{ route('dashboard.products.index') }}" class="btn btn-outline-secondary me-2">
                                            Cancel
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-save me-2"></i>Save Changes
                                        </button>
                                    </div>
                                </div>
                            </form>

                            <form id="delete-form" action="{{ route('dashboard.products.destroy', $product) }}" method="POST" class="d-none">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">Product Details</h6>
                        </div>
                        <div class="card-body">
                            <dl class="mb-0">
                                <dt>Vendor</dt>
                                <dd>{{ $product->vendor->name ?? 'N/A' }}</dd>
                                
                                <dt class="mt-3">Created At</dt>
                                <dd>{{ $product->created_at->format('M d, Y') }}</dd>
                                
                                <dt class="mt-2">Last Updated</dt>
                                <dd>{{ $product->updated_at->format('M d, Y') }}</dd>
                                
                                <dt class="mt-2">SKU</dt>
                                <dd>{{ $product->sku ?? 'Not set' }}</dd>
                            </dl>
                        </div>
                    </div>

                    <div class="card mt-4">
                        <div class="card-header">
                            <h6 class="mb-0">Inventory Status</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Stock Level</span>
                                    <span class="fw-bold">{{ $product->stock }} in stock</span>
                                </div>
                                <div class="progress" style="height: 10px;">
                                    @php
                                        $stockPercentage = min(100, ($product->stock / 100) * 100);
                                        $stockClass = $stockPercentage > 50 ? 'bg-success' : ($stockPercentage > 20 ? 'bg-warning' : 'bg-danger');
                                    @endphp
                                    <div class="progress-bar {{ $stockClass }}" role="progressbar" 
                                         style="width: {{ $stockPercentage }}%" 
                                         aria-valuenow="{{ $stockPercentage }}" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="alert alert-{{ $product->status === 'active' ? 'success' : 'secondary' }} mb-0">
                                <i class="bi bi-{{ $product->status === 'active' ? 'check-circle' : 'x-circle' }} me-2"></i>
                                This product is currently <strong>{{ $product->status === 'active' ? 'active' : 'inactive' }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Image preview
        document.getElementById('image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                const preview = document.getElementById('previewImg');
                const previewContainer = document.getElementById('imagePreview');
                
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    previewContainer.style.display = 'block';
                }
                
                reader.readAsDataURL(file);
            }
        });

        // Toggle original price field based on sale status
        document.addEventListener('DOMContentLoaded', function() {
            const originalPriceField = document.getElementById('original_price');
            const priceField = document.getElementById('price');
            
            // If original price is not set, clear it when page loads
            if (!originalPriceField.value || parseFloat(originalPriceField.value) <= 0) {
                originalPriceField.value = '';
            }
            
            // Auto-fill original price if empty when price changes
            priceField.addEventListener('change', function() {
                if (!originalPriceField.value) {
                    originalPriceField.value = parseFloat(priceField.value).toFixed(2);
                }
            });
        });
    </script>
    @endpush
</x-admin-layout>
