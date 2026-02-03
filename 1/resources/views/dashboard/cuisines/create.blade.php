<x-admin-layout>
    <div class="main-content">
        @include('layouts.admin-header')
        <div class="admin-header">
            <div class="container-fluid">
                <div class="d-flex align-items-center">
                    <button class="btn btn-outline-secondary me-3 d-lg-none sidebar-toggle">
                        <i class="bi bi-list"></i>
                    </button>
                    <div class="flex-grow-1">
                        <h1 class="h3 fw-bold mb-0">Create New Cuisine</h1>
                    </div>
                    <a href="{{ route('dashboard.cuisines.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Back to Cuisines
                    </a>
                </div>
            </div>
        </div>

        <div class="container-fluid p-4">
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('dashboard.cuisines.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="mb-3">
                                    <label for="name" class="form-label">Cuisine Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="image" class="form-label">Cuisine Image</label>
                                    <input class="form-control @error('image') is-invalid @enderror" type="file" id="image" name="image" accept="image/*">
                                    <div class="form-text">Recommended size: 500x500px. Max file size: 2MB.</div>
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div id="imagePreview" class="mt-2" style="display:none;">
                                        <img id="previewImg" src="#" alt="Preview" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-save me-2"></i>Save Cuisine
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">Cuisine Information</h6>
                        </div>
                        <div class="card-body">
                            <p class="small text-muted">
                                Cuisines help customers discover vendors and products by food type. 
                                Use clear, descriptive names and add relevant images to improve user experience.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.getElementById('image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(ev) {
                    document.getElementById('previewImg').src = ev.target.result;
                    document.getElementById('imagePreview').style.display = 'block';
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
    @endpush
</x-admin-layout>
