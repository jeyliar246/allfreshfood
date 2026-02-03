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
                        <h1 class="h3 fw-bold mb-0">Add New Product</h1>
                    </div>
                    <a href="{{ route('dashboard.products.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Back to Products
                    </a>
                </div>
            </div>
        </div>

        <div class="container-fluid p-4">
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('dashboard.products.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Product Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                                   id="name" name="name" value="{{ old('name') }}" required>
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
                                                @if(Auth::user()->role == 'admin')
                                                    <option value="">Select Vendor</option>
                                                    @foreach($vendors as $vendor)
                                                        <option value="{{ $vendor->id }}" {{ old('vendor_id') == $vendor->id ? 'selected' : '' }}>
                                                            {{ $vendor->name }}
                                                        </option>
                                                    @endforeach
                                                @else
                                                    @php
                                                        $vendor = $vendors->where('user_id', Auth::id())->first();
                                                    @endphp
                                                    @if($vendor)
                                                        <option value="{{ $vendor->id }}" selected>
                                                            {{ $vendor->name }} {{ $vendor->id }}
                                                        </option>
                                                    @else
                                                        <option value="">No vendor found</option>
                                                    @endif
                                                @endif
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
                                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                                                   id="price" name="price" value="{{ old('price') }}" required>
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
                                                   id="original_price" name="original_price" value="{{ old('original_price') }}">
                                            <div class="form-text">Leave empty if not on sale</div>
                                            @error('original_price')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="stock" class="form-label">Stock <span class="text-danger">*</span></label>
                                            <input type="number" min="0" 
                                                   class="form-control @error('stock') is-invalid @enderror" 
                                                   id="stock" name="stock" value="{{ old('stock', 0) }}" required>
                                            @error('stock')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                            <select class="form-select @error('status') is-invalid @enderror" 
                                                    id="status" name="status" required>
                                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="3">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="halal" class="form-label">Halal</label>
                                            <input type="checkbox" id="halal" value="1" name="halal" {{ old('halal') ? 'checked' : '' }}>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="vegan" class="form-label">Vegan</label>
                                            <input type="checkbox" id="vegan" value="1" name="vegan" {{ old('vegan') ? 'checked' : '' }}>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="gluten_free" class="form-label">Gluten Free</label>
                                            <input type="checkbox" id="gluten_free" value="1" name="gluten_free" {{ old('gluten_free') ? 'checked' : '' }}>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="organic" class="form-label">Organic</label>
                                            <input type="checkbox" id="organic" value="1" name="organic" {{ old('organic') ? 'checked' : '' }}>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="fair_trade" class="form-label">Fair Trade</label>
                                            <input type="checkbox" id="fair_trade" value="1" name="fair_trade" {{ old('fair_trade') ? 'checked' : '' }}>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="non_GMO" class="form-label">Non GMO</label>
                                            <input type="checkbox" id="non_GMO" value="1" name="non_GMO" {{ old('non_GMO') ? 'checked' : '' }}>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="image" class="form-label">Product Image</label>
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

                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-save me-2"></i>Save Product
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">Product Information</h6>
                        </div>
                        <div class="card-body">
                            <p class="small text-muted">
                                Add detailed information about your product to help customers make informed decisions. 
                                Include high-quality images and accurate descriptions.
                            </p>
                            <div class="alert alert-info mb-0">
                                <i class="bi bi-info-circle me-2"></i>
                                Products with clear images and detailed descriptions have higher conversion rates.
                            </div>
                        </div>
                    </div>

                    <div class="card mt-4">
                        <div class="card-header">
                            <h6 class="mb-0">Pricing Tips</h6>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled small">
                                <li class="mb-2">
                                    <i class="bi bi-check-circle text-success me-2"></i>
                                    Set competitive prices based on market research
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-check-circle text-success me-2"></i>
                                    Use the original price to show discounts
                                </li>
                                <li>
                                    <i class="bi bi-check-circle text-success me-2"></i>
                                    Consider your costs and desired profit margin
                                </li>
                            </ul>
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
    </script>
    @endpush
</x-admin-layout>
