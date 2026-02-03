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
                        <h1 class="h3 fw-bold mb-0">Edit Category: {{ $category->name }}</h1>
                    </div>
                    <a href="{{ route('dashboard.categories.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Back to Categories
                    </a>
                </div>
            </div>

        <div class="container-fluid p-4">
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('dashboard.categories.update', $category) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                
                                <div class="mb-3">
                                    <label for="name" class="form-label">Category Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $category->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="3">{{ old('description', $category->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="image" class="form-label">Category Image</label>
                                    
                                    @if($category->image)
                                        <div class="mb-2">
                                            <img src="{{ asset('uploads/' . $category->image) }}" alt="{{ $category->name }}" 
                                                 class="img-thumbnail" style="max-width: 200px; max-height: 200px; display: block;">
                                            <div class="form-check mt-2">
                                                <input class="form-check-input" type="checkbox" id="remove_image" name="remove_image">
                                                <label class="form-check-label" for="remove_image">
                                                    Remove current image
                                                </label>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    <input class="form-control @error('image') is-invalid @enderror" type="file" 
                                           id="image" name="image" accept="image/*">
                                    <div class="form-text">Recommended size: 500x500px. Max file size: 2MB.</div>
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    
                                    <div id="imagePreview" class="mt-2" style="display: none;">
                                        <img id="previewImg" src="#" alt="Preview" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between">
                                    <button type="button" class="btn btn-outline-danger" 
                                            onclick="if(confirm('Are you sure you want to delete this category?')) { document.getElementById('delete-form').submit(); }">
                                        <i class="bi bi-trash me-2"></i>Delete Category
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-save me-2"></i>Save Changes
                                    </button>
                                </div>
                            </form>

                            <form id="delete-form" action="{{ route('dashboard.categories.destroy', $category) }}" method="POST" class="d-none">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">Category Information</h6>
                        </div>
                        <div class="card-body">
                            <dl class="mb-0">
                                <dt>Created At</dt>
                                <dd>{{ $category->created_at->format('M d, Y') }}</dd>
                                
                                <dt class="mt-2">Last Updated</dt>
                                <dd>{{ $category->updated_at->format('M d, Y') }}</dd>
                                
                                <dt class="mt-2">Total Products</dt>
                                <dd>{{ $category->products_count ?? 0 }} products</dd>
                            </dl>
                            
                            @if($category->products_count > 0)
                                <div class="alert alert-warning mt-3 mb-0">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    This category contains {{ $category->products_count }} products. 
                                    You cannot delete it until all products are removed or reassigned.
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

        // Show confirmation before deleting
        document.getElementById('deleteBtn').addEventListener('click', function(e) {
            if (confirm('Are you sure you want to delete this category? This action cannot be undone.')) {
                document.getElementById('delete-form').submit();
            }
        });
    </script>
    @endpush
</x-admin-layout>
