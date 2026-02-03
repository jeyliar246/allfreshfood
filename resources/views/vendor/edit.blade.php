<x-admin-layout>

<div class="main-content">
    @include('layouts.admin-header')

    <div class="container-fluid p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 fw-bold">Edit Vendor</h1>
                <p class="text-muted mb-0">Update vendor details and settings.</p>
            </div>
            <a href="{{ route('vendors.show', $vendor) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>
                Back to Details
            </a>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('vendors.update', $vendor) }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                            @csrf
                            @method('PUT')
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="mb-4">Basic Information</h5>
                                    
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Vendor Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $vendor->name) }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @else
                                            <div class="form-text">The official business name of the vendor.</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $vendor->email) }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @else
                                            <div class="form-text">This is used for the vendor's login.</div>
                                        @enderror
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="phone" class="form-label">Phone Number <span class="text-danger">*</span></label>
                                                <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $vendor->phone) }}" required>
                                                @error('phone')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="cuisine" class="form-label">Cuisine Type <span class="text-danger">*</span></label>
                                                <select class="form-select @error('cuisine') is-invalid @enderror" id="cuisine" name="cuisine" required>
                                                    <option value="" disabled>Select cuisine type</option>
                                                    <option value="Nigerian" {{ old('cuisine', $vendor->cuisine) == 'Nigerian' ? 'selected' : '' }}>Nigerian</option>
                                                    <option value="Chinese" {{ old('cuisine', $vendor->cuisine) == 'Chinese' ? 'selected' : '' }}>Chinese</option>
                                                    <option value="Italian" {{ old('cuisine', $vendor->cuisine) == 'Italian' ? 'selected' : '' }}>Italian</option>
                                                    <option value="Indian" {{ old('cuisine', $vendor->cuisine) == 'Indian' ? 'selected' : '' }}>Indian</option>
                                                    <option value="American" {{ old('cuisine', $vendor->cuisine) == 'American' ? 'selected' : '' }}>American</option>
                                                    <option value="Mexican" {{ old('cuisine', $vendor->cuisine) == 'Mexican' ? 'selected' : '' }}>Mexican</option>
                                                    <option value="Mediterranean" {{ old('cuisine', $vendor->cuisine) == 'Mediterranean' ? 'selected' : '' }}>Mediterranean</option>
                                                    <option value="Japanese" {{ old('cuisine', $vendor->cuisine) == 'Japanese' ? 'selected' : '' }}>Japanese</option>
                                                    <option value="Other" {{ old('cuisine', $vendor->cuisine) == 'Other' ? 'selected' : '' }}>Other</option>
                                                </select>
                                                @error('cuisine')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="location" class="form-label">Location/Address <span class="text-danger">*</span></label>
                                        <textarea class="form-control @error('location') is-invalid @enderror" id="location" name="location" rows="2" required>{{ old('location', $vendor->location) }}</textarea>
                                        @error('location')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @else
                                            <div class="form-text">Full business address including landmarks.</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $vendor->description) }}</textarea>
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
                                        <input type="text" class="form-control @error('opening_hours') is-invalid @enderror" id="opening_hours" name="opening_hours" value="{{ old('opening_hours', $vendor->opening_hours) }}">
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
                                                <input type="text" class="form-control @error('delivery_time') is-invalid @enderror" id="delivery_time" name="delivery_time" value="{{ old('delivery_time', $vendor->delivery_time) }}">
                                                @error('delivery_time')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="min_order" class="form-label">Minimum Order (£)</label>
                                                <input type="number" class="form-control @error('min_order') is-invalid @enderror" id="min_order" name="min_order" value="{{ old('min_order', $vendor->min_order) }}" min="0" step="0.01">
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
                                                <input type="number" class="form-control @error('delivery_fee') is-invalid @enderror" id="delivery_fee" name="delivery_fee" value="{{ old('delivery_fee', $vendor->delivery_fee) }}" min="0" step="0.01">
                                                @error('delivery_fee')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="free_delivery_over" class="form-label">Free Delivery Over (£)</label>
                                                <input type="number" class="form-control @error('free_delivery_over') is-invalid @enderror" id="free_delivery_over" name="free_delivery_over" value="{{ old('free_delivery_over', $vendor->free_delivery_over) }}" min="0" step="0.01">
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
                                            <input type="text" class="form-control @error('website') is-invalid @enderror" id="website" name="website" value="{{ old('website', str_replace(['http://', 'https://'], '', $vendor->website)) }}" placeholder="example.com">
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
                                        <label for="image" class="form-label">Logo</label>
                                        @if($vendor->image)
                                            <div class="mb-2">
                                                <img src="{{ asset('uploads/' . $vendor->image) }}" alt="Current logo" class="img-thumbnail" style="max-width: 150px; max-height: 150px;">
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" id="remove_logo" name="remove_logo" value="1">
                                                <label class="form-check-label text-danger" for="remove_logo">
                                                    Remove current logo
                                                </label>
                                            </div>
                                        @endif
                                        <input class="form-control @error('image') is-invalid @enderror" type="file" id="logo" name="image" accept="image/*" onchange="previewImage(this, 'logoPreview')">
                                        @error('image')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @else
                                            <div class="form-text">Recommended size: 200x200px, Max size: 2MB</div>
                                        @enderror
                                        <div id="logoPreview" class="mt-2 text-center">
                                            <img src="{{ $vendor->logo ? asset('uploads/' . $vendor->logo) : asset('assets/img/default-logo.png') }}" alt="Logo preview" class="img-thumbnail" style="max-width: 150px; max-height: 150px;">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <h5 class="mb-4">Cover Image</h5>
                                    
                                    <div class="mb-3">
                                        @if($vendor->cover_image)
                                            <div class="mb-2">
                                                <img src="{{ asset('uploads/' . $vendor->cover_image) }}" alt="Current cover" class="img-thumbnail" style="max-width: 100%; max-height: 150px; object-fit: cover;">
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" id="remove_cover_image" name="remove_cover_image" value="1">
                                                <label class="form-check-label text-danger" for="remove_cover_image">
                                                    Remove current cover image
                                                </label>
                                            </div>
                                        @endif
                                        <input class="form-control @error('cover_image') is-invalid @enderror" type="file" id="cover_image" name="cover_image" accept="image/*" onchange="previewImage(this, 'coverPreview')">
                                        @error('cover_image')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @else
                                            <div class="form-text">Recommended size: 1200x400px, Max size: 5MB</div>
                                        @enderror
                                        <div id="coverPreview" class="mt-2 text-center">
                                            <img src="{{ $vendor->cover_image ? asset('uploads/' . $vendor->cover_image) : asset('assets/img/default-cover.jpg') }}" alt="Cover preview" class="img-thumbnail" style="max-width: 100%; max-height: 150px; object-fit: cover;">
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
                                                <input class="form-check-input" type="checkbox" role="switch" id="is_approved" name="is_approved" value="1" {{ old('is_approved', $vendor->is_approved) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_approved">Approve Vendor</label>
                                                <div class="form-text">Enable to approve this vendor</div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-check form-switch mb-3">
                                                <input class="form-check-input" type="checkbox" role="switch" id="verified" name="verified" value="1" {{ old('verified', $vendor->verified) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="verified">Verified Vendor</label>
                                                <div class="form-text">Mark as verified for trusted vendors</div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-check form-switch mb-3">
                                                <input class="form-check-input" type="checkbox" role="switch" id="featured" name="featured" value="1" {{ old('featured', $vendor->featured) ? 'checked' : '' }}>
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
                                    Reset Changes
                                </button>
                                <div>
                                    <a href="{{ route('vendors.show', $vendor) }}" class="btn btn-outline-secondary me-2">
                                        <i class="bi bi-x-circle me-2"></i>
                                        Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-save me-2"></i>
                                        Update Vendor
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
                        <h5 class="card-title mb-0">Vendor Stats</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <div>
                                <h6 class="text-muted small mb-1">Registration Date</h6>
                                <p class="mb-0">{{ $vendor->created_at->format('M d, Y') }}</p>
                            </div>
                            <div class="text-end">
                                <h6 class="text-muted small mb-1">Last Updated</h6>
                                <p class="mb-0">{{ $vendor->updated_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <h6 class="text-muted small mb-2">Status</h6>
                            <div class="d-flex gap-2">
                                @if($vendor->is_approved)
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle me-1"></i> Approved
                                    </span>
                                @else
                                    <span class="badge bg-warning text-dark">
                                        <i class="bi bi-hourglass-split me-1"></i> Pending Approval
                                    </span>
                                @endif
                                
                                @if($vendor->verified)
                                    <span class="badge bg-info">
                                        <i class="bi bi-patch-check me-1"></i> Verified
                                    </span>
                                @endif
                                
                                @if($vendor->featured)
                                    <span class="badge bg-purple">
                                        <i class="bi bi-star me-1"></i> Featured
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="list-group list-group-flush">
                            <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                <span><i class="bi bi-box-seam me-2"></i> Total Products</span>
                                <span class="badge bg-primary rounded-pill">{{ $vendor->products_count ?? 0 }}</span>
                            </div>
                            <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                <span><i class="bi bi-cart-check me-2"></i> Total Orders</span>
                                <span class="badge bg-success rounded-pill">{{ $vendor->orders_count ?? 0 }}</span>
                            </div>
                            <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                <span><i class="bi bi-star-fill me-2 text-warning"></i> Average Rating</span>
                                <span class="badge bg-warning text-dark rounded-pill">
                                    {{ number_format($vendor->average_rating ?? 0, 1) }} <i class="bi bi-star-fill"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Vendor Preview</h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <img id="previewLogo" src="{{ $vendor->logo ? asset('uploads/' . $vendor->logo) : asset('assets/img/default-logo.png') }}" alt="Logo preview" class="img-fluid rounded-circle mb-2" style="width: 80px; height: 80px; object-fit: cover;">
                            <h5 id="previewName" class="mb-1">{{ $vendor->name }}</h5>
                            <p id="previewCuisine" class="text-muted small mb-2">{{ $vendor->cuisine ?? 'Cuisine Type' }}</p>
                            <div id="previewBadges" class="mb-3">
                                @if($vendor->is_approved)
                                    <span class="badge bg-success me-1">Approved</span>
                                @endif
                                @if($vendor->verified)
                                    <span class="badge bg-info me-1">Verified</span>
                                @endif
                                @if($vendor->featured)
                                    <span class="badge bg-purple">Featured</span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between small text-muted mb-2">
                            <span><i class="bi bi-clock me-1"></i> <span id="previewDeliveryTime">{{ $vendor->delivery_time ?? '30-45 mins' }}</span></span>
                            <span><i class="bi bi-truck me-1"></i> <span id="previewDeliveryFee">{{ $vendor->delivery_fee > 0 ? '£' . number_format($vendor->delivery_fee, 2) : 'Free' }}</span> delivery</span>
                            <span><i class="bi bi-cash me-1"></i> Min: £<span id="previewMinOrder">{{ number_format($vendor->min_order ?? 0, 2) }}</span></span>
                        </div>
                        
                        <p id="previewDescription" class="small text-muted mb-0">
                            {{ $vendor->description ?? 'Vendor description will appear here. This is a preview of how it will look on the vendor card.' }}
                        </p>
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
    
    .form-label.required:after {
        content: ' *';
        color: #dc3545;
    }
    
    .bg-purple {
        background-color: #6f42c1;
    }
    
    .list-group-item {
        padding: 0.5rem 0;
        background: transparent;
    }
    
    .list-group-item:first-child {
        padding-top: 0;
    }
    
    .list-group-item:last-child {
        padding-bottom: 0;
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
                
                // Update preview in the right sidebar
                if (input.id === 'logo') {
                    document.getElementById('previewLogo').src = e.target.result;
                } else if (input.id === 'cover_image') {
                    document.getElementById('previewCover').src = e.target.result;
                }
            }
            
            reader.readAsDataURL(input.files[0]);
        } else if (input.id === 'logo' && document.getElementById('remove_logo')?.checked) {
            previewImg.src = '{{ asset('assets/img/default-logo.png') }}';
            document.getElementById('previewLogo').src = '{{ asset('assets/img/default-logo.png') }}';
        } else if (input.id === 'cover_image' && document.getElementById('remove_cover_image')?.checked) {
            previewImg.src = '{{ asset('assets/img/default-cover.jpg') }}';
            document.getElementById('previewCover').src = '{{ asset('assets/img/default-cover.jpg') }}';
        }
    }
    
    // Handle remove logo checkbox
    document.addEventListener('DOMContentLoaded', function() {
        const removeLogoCheckbox = document.getElementById('remove_logo');
        if (removeLogoCheckbox) {
            removeLogoCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    document.getElementById('logoPreview').querySelector('img').src = '{{ asset('assets/img/default-logo.png') }}';
                    document.getElementById('previewLogo').src = '{{ asset('assets/img/default-logo.png') }}';
                } else {
                    document.getElementById('logoPreview').querySelector('img').src = '{{ $vendor->logo ? asset('uploads/' . $vendor->logo) : asset('assets/img/default-logo.png') }}';
                    document.getElementById('previewLogo').src = '{{ $vendor->logo ? asset('uploads/' . $vendor->logo) : asset('assets/img/default-logo.png') }}';
                }
            });
        }
        
        // Handle remove cover image checkbox
        const removeCoverCheckbox = document.getElementById('remove_cover_image');
        if (removeCoverCheckbox) {
            removeCoverCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    document.getElementById('coverPreview').querySelector('img').src = '{{ asset('assets/img/default-cover.jpg') }}';
                    document.getElementById('previewCover').src = '{{ asset('assets/img/default-cover.jpg') }}';
                } else {
                    document.getElementById('coverPreview').querySelector('img').src = '{{ $vendor->cover_image ? asset('uploads/' . $vendor->cover_image) : asset('assets/img/default-cover.jpg') }}';
                    document.getElementById('previewCover').src = '{{ $vendor->cover_image ? asset('uploads/' . $vendor->cover_image) : asset('assets/img/default-cover.jpg') }}';
                }
            });
        }
        
        // Live preview updates for form fields
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
                document.getElementById('previewName').textContent = '{{ $vendor->name }}';
            }
            
            // Update cuisine
            if (cuisineSelect && cuisineSelect.value) {
                document.getElementById('previewCuisine').textContent = cuisineSelect.options[cuisineSelect.selectedIndex].text;
            } else {
                document.getElementById('previewCuisine').textContent = '{{ $vendor->cuisine ?? 'Cuisine Type' }}';
            }
            
            // Update description
            if (descriptionTextarea && descriptionTextarea.value) {
                document.getElementById('previewDescription').textContent = descriptionTextarea.value;
            } else {
                document.getElementById('previewDescription').textContent = '{{ $vendor->description ?: 'Vendor description will appear here. This is a preview of how it will look on the vendor card.' }}';
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
