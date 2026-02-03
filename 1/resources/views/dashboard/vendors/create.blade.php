<x-admin-layout>
<div class="main-content">
    @include('layouts.admin-header')

    <div class="container-fluid p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 fw-bold">Add New Vendor</h1>
                <p class="text-muted mb-0">Register a new vendor account.</p>
            </div>
            <a href="{{ route('vendors.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>
                Back to List
            </a>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('vendors.store') }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                            @csrf
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="mb-4">Basic Information</h5>
                                    
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Vendor Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @else
                                            <div class="form-text">Vendor name</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @else
                                            <div class="form-text">This will be used for the vendor's login.</div>
                                        @enderror
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="phone" class="form-label">Phone Number <span class="text-danger">*</span></label>
                                                <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" required>
                                                @error('phone')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="cuisine" class="form-label">Cuisine Type <span class="text-danger">*</span></label>
                                                <select class="form-select @error('cuisine') is-invalid @enderror" id="cuisine" name="cuisine" required>
                                                    <option value="" disabled selected>Select cuisine type</option>
                                                    @foreach ($cuisines as $cuisine)
                                                        <option value="{{ $cuisine->name }}" {{ old('cuisine') == $cuisine->name ? 'selected' : '' }}>{{ $cuisine->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('cuisine')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="location" class="form-label">Location/Address <span class="text-danger">*</span></label>
                                        <textarea class="form-control @error('location') is-invalid @enderror" id="location" name="location" rows="2" required>{{ old('location') }}</textarea>
                                        @error('location')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @else
                                            <div class="form-text">Full business address including landmarks.</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @else
                                            <div class="form-text">A brief description of the vendor's offerings.</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <h5 class="mb-4">Business Details</h5>

                                    <div class="mb-3">
                                        <label for="opening_hours" class="form-label">Opening Hours</label>
                                        <input type="text" class="form-control @error('opening_hours') is-invalid @enderror" id="opening_hours" name="opening_hours" value="{{ old('opening_hours', 'Mon-Sun: 8:00 AM - 10:00 PM') }}">
                                        @error('opening_hours')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @else
                                            <div class="form-text">e.g., Mon-Fri: 9am-10pm, Sat-Sun: 10am-11pm</div>
                                        @enderror
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="delivery_time" class="form-label">Delivery Time</label>
                                                <input type="text" class="form-control @error('delivery_time') is-invalid @enderror" id="delivery_time" name="delivery_time" value="{{ old('delivery_time', '30-45 mins') }}">
                                                @error('delivery_time')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="min_order" class="form-label">Minimum Order (£)</label>
                                                <input type="number" class="form-control @error('min_order') is-invalid @enderror" id="min_order" name="min_order" value="{{ old('min_order', 0) }}" min="0" step="0.01">
                                                @error('min_order')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="delivery_fee" class="form-label">Delivery Fee (£)</label>
                                                <input type="number" class="form-control @error('delivery_fee') is-invalid @enderror" id="delivery_fee" name="delivery_fee" value="{{ old('delivery_fee', 0) }}" min="0" step="0.01">
                                                @error('delivery_fee')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="free_delivery_over" class="form-label">Free Delivery Over (£)</label>
                                                <input type="number" class="form-control @error('free_delivery_over') is-invalid @enderror" id="free_delivery_over" name="free_delivery_over" value="{{ old('free_delivery_over', 0) }}" min="0" step="0.01">
                                                @error('free_delivery_over')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="website" class="form-label">Website</label>
                                        <div class="input-group">
                                            <span class="input-group-text">https://</span>
                                            <input type="text" class="form-control @error('website') is-invalid @enderror" id="website" name="website" value="{{ old('website') }}" placeholder="example.com">
                                            @error('website')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="mb-4">Media</h5>
                                    
                                    <div class="mb-3">
                                        <label for="logo" class="form-label">Logo</label>
                                        <input class="form-control @error('image') is-invalid @enderror" type="file" id="image" name="image" accept="image/*" onchange="previewImage(this, 'logoPreview')">
                                        @error('image')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @else
                                            <div class="form-text">Recommended size: 200x200px, Max size: 2MB</div>
                                        @enderror
                                        <div id="logoPreview" class="mt-2 text-center">
                                            <img src="{{ asset('assets/img/default-logo.png') }}" alt="Logo preview" class="img-thumbnail d-none" style="max-width: 150px; max-height: 150px;">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <h5 class="mb-4">Cover Image</h5>
                                    
                                    <div class="mb-3">
                                        <label for="cover_image" class="form-label">Cover Image</label>
                                        <input class="form-control @error('cover_image') is-invalid @enderror" type="file" id="cover_image" name="cover_image" accept="image/*" onchange="previewImage(this, 'coverPreview')">
                                        @error('cover_image')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @else
                                            <div class="form-text">Recommended size: 1200x400px, Max size: 5MB</div>
                                        @enderror
                                        <div id="coverPreview" class="mt-2 text-center">
                                            <img src="{{ asset('assets/img/default-cover.jpg') }}" alt="Cover preview" class="img-thumbnail" style="max-width: 100%; max-height: 150px; object-fit: cover;">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-12">
                                    <h5 class="mb-4">Settings</h5>
                                    
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-check form-switch mb-3">
                                                <input class="form-check-input" type="checkbox" role="switch" id="is_approved" name="is_approved" value="1" {{ old('is_approved', true) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_approved">Approve Vendor</label>
                                                <div class="form-text">Enable to approve this vendor immediately</div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-check form-switch mb-3">
                                                <input class="form-check-input" type="checkbox" role="switch" id="verified" name="verified" value="1" {{ old('verified') ? 'checked' : '' }}>
                                                <label class="form-check-label" for="verified">Verified Vendor</label>
                                                <div class="form-text">Mark as verified for trusted vendors</div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-check form-switch mb-3">
                                                <input class="form-check-input" type="checkbox" role="switch" id="featured" name="featured" value="1" {{ old('featured') ? 'checked' : '' }}>
                                                <label class="form-check-label" for="featured">Featured Vendor</label>
                                                <div class="form-text">Show in featured section</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mt-5">
                                <button type="reset" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-counterclockwise me-2"></i>
                                    Reset Form
                                </button>
                                <div>
                                    <a href="{{ route('vendors.index') }}" class="btn btn-outline-secondary me-2">
                                        <i class="bi bi-x-circle me-2"></i>
                                        Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-save me-2"></i>
                                        Save Vendor
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Quick Tips</h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <h6><i class="bi bi-info-circle me-2"></i> Required Fields</h6>
                            <p class="small mb-0">Fields marked with <span class="text-danger">*</span> are required and must be filled out.</p>
                        </div>
                        
                        <h6 class="mt-4">Vendor Verification</h6>
                        <p class="small text-muted">Verify vendors only after confirming their business details and documentation.</p>
                        
                        <h6 class="mt-3">Featured Vendors</h6>
                        <p class="small text-muted">Featured vendors appear in prominent sections of the platform. Use this sparingly for high-quality vendors.</p>
                        
                        <h6 class="mt-3">Media Guidelines</h6>
                        <ul class="small text-muted">
                            <li>Logo: Square aspect ratio, transparent background preferred</li>
                            <li>Cover: Landscape orientation, high resolution</li>
                            <li>File types: JPG, PNG, or WebP</li>
                        </ul>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Preview</h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <img id="previewLogo" src="{{ asset('assets/img/default-logo.png') }}" alt="Logo preview" class="img-fluid rounded-circle mb-2" style="width: 80px; height: 80px; object-fit: cover;">
                            <h5 id="previewName" class="mb-1">Vendor Name</h5>
                            <p id="previewCuisine" class="text-muted small mb-2">Cuisine Type</p>
                            <div id="previewBadges" class="mb-3">
                                <span class="badge bg-success">Approved</span>
                                <span class="badge bg-info">Verified</span>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between small text-muted mb-2">
                            <span><i class="bi bi-clock me-1"></i> <span id="previewDeliveryTime">30-45 mins</span></span>
                            <span><i class="bi bi-truck me-1"></i> <span id="previewDeliveryFee">£0.00</span> delivery</span>
                            <span><i class="bi bi-cash me-1"></i> Min: £<span id="previewMinOrder">0.00</span></span>
                        </div>
                        
                        <p id="previewDescription" class="small text-muted mb-0">Vendor description will appear here. This is a preview of how it will look on the vendor card.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .form-section {
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid #e9ecef;
    }
    
    .form-section:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }
    
    .form-section h5 {
        font-weight: 600;
        color: #495057;
        margin-bottom: 1.25rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #f0f0f0;
    }
    
    .preview-img-container {
        border: 2px dashed #dee2e6;
        border-radius: 0.25rem;
        padding: 1rem;
        text-align: center;
        background-color: #f8f9fa;
        margin-bottom: 1rem;
    }
    
    .preview-img-container img {
        max-width: 100%;
        height: auto;
        max-height: 200px;
    }
    
    .form-label.required:after {
        content: ' *';
        color: #dc3545;
    }
    
    .btn-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        padding: 0;
    }
</style>
@endpush

@push('scripts')
<script>
    // Form validation
    (function() {
        'use strict';
        
        // Fetch the form element
        const form = document.querySelector('.needs-validation');
        
        // Add validation on form submission
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            
            form.classList.add('was-validated');
        }, false);
    })();
    
    // Image preview function
    function previewImage(input, previewId) {
        const previewContainer = document.getElementById(previewId);
        const previewImg = previewContainer.querySelector('img');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                previewImg.classList.remove('d-none');
                
                // Update preview in the right sidebar
                if (input.id === 'logo') {
                    document.getElementById('previewLogo').src = e.target.result;
                }
            }
            
            reader.readAsDataURL(input.files[0]);
        } else {
            previewImg.classList.add('d-none');
        }
    }
    
    // Live preview updates
    document.addEventListener('DOMContentLoaded', function() {
        const nameInput = document.getElementById('name');
        const cuisineSelect = document.getElementById('cuisine');
        const descriptionTextarea = document.getElementById('description');
        const deliveryTimeInput = document.getElementById('delivery_time');
        const deliveryFeeInput = document.getElementById('delivery_fee');
        const minOrderInput = document.getElementById('min_order');
        const isApprovedCheckbox = document.getElementById('is_approved');
        const verifiedCheckbox = document.getElementById('verified');
        const featuredCheckbox = document.getElementById('featured');
        
        // Update preview when inputs change
        [nameInput, cuisineSelect, descriptionTextarea, deliveryTimeInput, deliveryFeeInput, minOrderInput, 
         isApprovedCheckbox, verifiedCheckbox, featuredCheckbox].forEach(element => {
            if (element) {
                element.addEventListener('input', updatePreview);
                element.addEventListener('change', updatePreview);
            }
        });
        
        function updatePreview() {
            // Update name
            if (nameInput && nameInput.value) {
                document.getElementById('previewName').textContent = nameInput.value;
            } else {
                document.getElementById('previewName').textContent = 'Vendor Name';
            }
            
            // Update cuisine
            if (cuisineSelect && cuisineSelect.value) {
                document.getElementById('previewCuisine').textContent = cuisineSelect.value;
            } else {
                document.getElementById('previewCuisine').textContent = 'Cuisine Type';
            }
            
            // Update description
            if (descriptionTextarea && descriptionTextarea.value) {
                document.getElementById('previewDescription').textContent = descriptionTextarea.value;
            } else {
                document.getElementById('previewDescription').textContent = 'Vendor description will appear here. This is a preview of how it will look on the vendor card.';
            }
            
            // Update delivery time
            if (deliveryTimeInput && deliveryTimeInput.value) {
                document.getElementById('previewDeliveryTime').textContent = deliveryTimeInput.value;
            } else {
                document.getElementById('previewDeliveryTime').textContent = '30-45 mins';
            }
            
            // Update delivery fee
            if (deliveryFeeInput && deliveryFeeInput.value) {
                const fee = parseFloat(deliveryFeeInput.value);
                document.getElementById('previewDeliveryFee').textContent = fee > 0 ? '£' + fee.toFixed(2) : 'Free';
            } else {
                document.getElementById('previewDeliveryFee').textContent = 'Free';
            }
            
            // Update minimum order
            if (minOrderInput && minOrderInput.value) {
                document.getElementById('previewMinOrder').textContent = parseFloat(minOrderInput.value).toFixed(2);
            } else {
                document.getElementById('previewMinOrder').textContent = '0.00';
            }
            
            // Update badges
            const badgesContainer = document.getElementById('previewBadges');
            badgesContainer.innerHTML = '';
            
            if (isApprovedCheckbox && isApprovedCheckbox.checked) {
                badgesContainer.innerHTML += '<span class="badge bg-success me-1">Approved</span>';
            }
            
            if (verifiedCheckbox && verifiedCheckbox.checked) {
                badgesContainer.innerHTML += '<span class="badge bg-info me-1">Verified</span>';
            }
            
            if (featuredCheckbox && featuredCheckbox.checked) {
                badgesContainer.innerHTML += '<span class="badge bg-purple">Featured</span>';
            }
        }
        
        // Initial update
        updatePreview();
    });
</script>
@endpush
</x-admin-layout>
