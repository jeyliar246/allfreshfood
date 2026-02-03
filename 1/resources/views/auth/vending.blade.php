<x-auth-layout>
    <section class="min-vh-100 d-flex align-items-center justify-content-center py-5" style="background: var(--gradient-secondary);">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-5">
                    <div class="card shadow-lg border-0 animate__animated animate__fadeInUp">
                        <div class="card-header bg-white border-0 text-center py-4">
                            <div class="d-flex justify-content-center mb-3">
                                <div class="rounded p-3">
                                    {{-- <i class="bi bi-globe text-white fs-2"></i> --}}
                                    <img src="{{asset('assets/logo.png')}}" alt="Logo" class="img-fluid" style="width: 200px; height: 80px;">
                                </div>
                            </div>
                            <h2 class="fw-bold mb-2">Become a Vendor</h2>
                        </div>
                        <div class="card-body p-4">
                            <form  method="POST" action="{{ route('registerVendor') }}">
                                @csrf
                                <!-- Full Name -->
                                <div class="mb-3">
                                    <label for="fullName" class="form-label fw-medium">Full Name</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                                        <input type="text" name="name" class="form-control" id="fullName" placeholder="Enter your full name" required>
                                        <div class="invalid-feedback">Please provide your full name.</div>
                                    </div>
                                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                </div>

                                <!-- Email -->
                                <div class="mb-3">
                                    <label for="email" class="form-label fw-medium">Email Address</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                        <input type="email" name="email" class="form-control" id="email" placeholder="Enter your email" required>
                                        <div class="invalid-feedback">Please provide a valid email.</div>
                                    </div>
                                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                </div>

                                <!-- Password -->
                                <div class="mb-3">
                                    <label for="password" class="form-label fw-medium">Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                        <input type="password" name="password" class="form-control" id="password" placeholder="Enter your password" required minlength="8">
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">
                                            <i class="bi bi-eye" id="password-toggle"></i>
                                        </button>
                                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                    </div>
                                </div>

                                <!-- Confirm Password -->
                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label fw-medium">Confirm Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                        <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" placeholder="Re-enter your password" required minlength="8">
                                    </div>
                                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                                </div>

                                <!-- address -->
                                <div class="mb-3">
                                    <label for="address" class="form-label fw-medium">Address</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                        <input type="text" name="address" class="form-control" id="address" placeholder="123 Main St" required>
                                    </div>
                                    <x-input-error :messages="$errors->get('address')" class="mt-2" />
                                </div>

                                <!-- phone -->
                                <div class="mb-3">
                                    <label for="phone" class="form-label fw-medium">Phone</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-phone"></i></span>
                                        <input type="text" name="phone" minlength="11" maxlength="11" class="form-control" id="phone" placeholder="07123456789" required>
                                    </div>
                                    <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                                </div>
                                
                                <!-- postcode -->
                                <div class="mb-3">
                                    <label for="postcode" class="form-label fw-medium">Postcode</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-map"></i></span>
                                        <input type="text" name="postcode" minlength="6" maxlength="8" class="form-control" id="postcode" placeholder="G811AA" required>
                                    </div>
                                    <x-input-error :messages="$errors->get('postcode')" class="mt-2" />
                                </div>

                                <!-- location -->
                                <div class="mb-3">
                                    <label for="location" class="form-label fw-medium">City / Location</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-geo"></i></span>
                                        <input type="text" name="location" class="form-control" id="location" placeholder="e.g., London" required>
                                    </div>
                                    <x-input-error :messages="$errors->get('location')" class="mt-2" />
                                </div>

                                <!-- Terms Checkbox -->
                                <div class="mb-4 form-check">
                                    <input class="form-check-input" type="checkbox" id="terms" required>
                                    <label class="form-check-label small" for="terms">
                                        I agree to the <a href="{{ route('home.terms') }}" class="text-decoration-none text-primary">Terms of Service</a> and <a href="{{ route('home.privacy') }}" class="text-decoration-none text-primary">Privacy Policy</a>
                                    </label>
                                    {{-- <x-input-error :messages="$errors->get('terms')" class="mt-2" /> --}}
                                </div>

                                <!-- Submit Button -->
                                <button type="submit" class="btn btn-primary w-100 mb-3">
                                    <span class="register-text">Create Account</span>
                                    <span class="register-loading d-none">
                                        <span class="spinner-border spinner-border-sm me-2"></span>
                                        Creating Account...
                                    </span>
                                </button>
                            </form>

                            <!-- Social Sign-Up -->
                            <div class="text-center mb-3">
                                <div class="position-relative">
                                    <hr>
                                    <span class="position-absolute top-50 start-50 translate-middle bg-white px-3 text-muted small">Or sign up with</span>
                                </div>
                            </div>

                            {{-- <div class="row g-2 mb-4">
                                <div class="col-6">
                                    <button class="btn btn-outline-secondary w-100">
                                        <i class="bi bi-google me-1"></i> Google
                                    </button>
                                </div>
                                <div class="col-6">
                                    <button class="btn btn-outline-secondary w-100">
                                        <i class="bi bi-facebook me-1"></i> Facebook
                                    </button>
                                </div>
                            </div> --}}

                            <!-- Sign In Link -->
                            <div class="text-center">
                                <p class="text-muted small mb-0">
                                    Already have an account? 
                                    <a href="/login" class="text-decoration-none fw-medium text-primary">Sign In</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <script>
        // Toggle password visibility
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = document.getElementById(fieldId + '-toggle');
            
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        }

        // UK Postcode validation
        function validateUKPostcode(postcode) {
            // Remove spaces and convert to uppercase
            const cleaned = postcode.replace(/\s/g, '').toUpperCase();
            
            // UK postcode regex pattern
            const postcodeRegex = /^[A-Z]{1,2}[0-9]{1,2}[A-Z]?[0-9][A-Z]{2}$/;
            
            return postcodeRegex.test(cleaned);
        }

        // UK Phone number validation
        function validateUKPhone(phone) {
            // Remove all spaces, dashes, and parentheses
            const cleaned = phone.replace(/[\s\-\(\)]/g, '');
            
            // Check if it starts with 0 and has 10 or 11 digits
            const phoneRegex = /^0[0-9]{9,10}$/;
            
            return phoneRegex.test(cleaned);
        }

        // Format UK Postcode (adds space in correct position)
        function formatUKPostcode(postcode) {
            const cleaned = postcode.replace(/\s/g, '').toUpperCase();
            
            if (cleaned.length >= 5) {
                return cleaned.slice(0, -3) + ' ' + cleaned.slice(-3);
            }
            
            return cleaned;
        }

        // Format UK Phone (adds spaces for readability)
        function formatUKPhone(phone) {
            const cleaned = phone.replace(/[\s\-\(\)]/g, '');
            
            if (cleaned.length === 11) {
                // Format as: 07123 456789
                return cleaned.slice(0, 5) + ' ' + cleaned.slice(5);
            }
            
            return cleaned;
        }

        // Real-time validation
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const nameInput = document.getElementById('fullName');
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            const confirmPasswordInput = document.getElementById('password_confirmation');
            const addressInput = document.getElementById('address');
            const phoneInput = document.getElementById('phone');
            const postcodeInput = document.getElementById('postcode');
            const locationInput = document.getElementById('location');
            const termsCheckbox = document.getElementById('terms');
            
            // Name validation
            nameInput.addEventListener('blur', function() {
                if (this.value.trim().length < 2) {
                    this.classList.add('is-invalid');
                    this.classList.remove('is-valid');
                    showError(this, 'Name must be at least 2 characters long');
                } else {
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                    hideError(this);
                }
            });
            
            // Email validation
            emailInput.addEventListener('blur', function() {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                
                if (!emailRegex.test(this.value)) {
                    this.classList.add('is-invalid');
                    this.classList.remove('is-valid');
                    showError(this, 'Please enter a valid email address');
                } else {
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                    hideError(this);
                }
            });
            
            // Password validation
            passwordInput.addEventListener('input', function() {
                const value = this.value;
                let errors = [];
                
                if (value.length < 8) {
                    errors.push('at least 8 characters');
                }
                if (!/[A-Z]/.test(value)) {
                    errors.push('one uppercase letter');
                }
                if (!/[a-z]/.test(value)) {
                    errors.push('one lowercase letter');
                }
                if (!/[0-9]/.test(value)) {
                    errors.push('one number');
                }
                
                if (errors.length > 0) {
                    this.classList.add('is-invalid');
                    this.classList.remove('is-valid');
                    showError(this, 'Password must contain ' + errors.join(', '));
                } else {
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                    hideError(this);
                }
                
                // Also validate confirm password if it has a value
                if (confirmPasswordInput.value) {
                    validateConfirmPassword();
                }
            });
            
            // Confirm Password validation
            confirmPasswordInput.addEventListener('input', validateConfirmPassword);
            confirmPasswordInput.addEventListener('blur', validateConfirmPassword);
            
            function validateConfirmPassword() {
                if (confirmPasswordInput.value !== passwordInput.value) {
                    confirmPasswordInput.classList.add('is-invalid');
                    confirmPasswordInput.classList.remove('is-valid');
                    showError(confirmPasswordInput, 'Passwords do not match');
                } else if (confirmPasswordInput.value.length >= 8) {
                    confirmPasswordInput.classList.remove('is-invalid');
                    confirmPasswordInput.classList.add('is-valid');
                    hideError(confirmPasswordInput);
                }
            }
            
            // Address validation
            addressInput.addEventListener('blur', function() {
                if (this.value.trim().length < 5) {
                    this.classList.add('is-invalid');
                    this.classList.remove('is-valid');
                    showError(this, 'Please enter a complete address (at least 5 characters)');
                } else {
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                    hideError(this);
                }
            });
            
            // Phone validation and formatting
            phoneInput.addEventListener('input', function() {
                // Only allow numbers
                this.value = this.value.replace(/[^0-9]/g, '');
            });
            
            phoneInput.addEventListener('blur', function() {
                if (!validateUKPhone(this.value)) {
                    this.classList.add('is-invalid');
                    this.classList.remove('is-valid');
                    showError(this, 'Please enter a valid UK phone number (e.g., 07123456789)');
                } else {
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                    this.value = formatUKPhone(this.value);
                    hideError(this);
                }
            });
            
            // Postcode validation and formatting
            postcodeInput.addEventListener('input', function() {
                // Convert to uppercase and remove invalid characters
                this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
            });
            
            postcodeInput.addEventListener('blur', function() {
                if (!validateUKPostcode(this.value)) {
                    this.classList.add('is-invalid');
                    this.classList.remove('is-valid');
                    showError(this, 'Please enter a valid UK postcode (e.g., SW1A 1AA)');
                } else {
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                    this.value = formatUKPostcode(this.value);
                    hideError(this);
                }
            });
            
            // Location validation
            locationInput.addEventListener('blur', function() {
                if (this.value.trim().length < 2) {
                    this.classList.add('is-invalid');
                    this.classList.remove('is-valid');
                    showError(this, 'Please enter a valid city or location');
                } else {
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                    hideError(this);
                }
            });
            
            // Form submission validation
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                let isValid = true;
                
                // Validate all fields
                if (nameInput.value.trim().length < 2) {
                    nameInput.classList.add('is-invalid');
                    showError(nameInput, 'Name must be at least 2 characters long');
                    isValid = false;
                }
                
                if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailInput.value)) {
                    emailInput.classList.add('is-invalid');
                    showError(emailInput, 'Please enter a valid email address');
                    isValid = false;
                }
                
                if (passwordInput.value.length < 8 || 
                    !/[A-Z]/.test(passwordInput.value) || 
                    !/[a-z]/.test(passwordInput.value) || 
                    !/[0-9]/.test(passwordInput.value)) {
                    passwordInput.classList.add('is-invalid');
                    showError(passwordInput, 'Password must contain at least 8 characters, one uppercase, one lowercase, and one number');
                    isValid = false;
                }
                
                if (confirmPasswordInput.value !== passwordInput.value) {
                    confirmPasswordInput.classList.add('is-invalid');
                    showError(confirmPasswordInput, 'Passwords do not match');
                    isValid = false;
                }
                
                if (addressInput.value.trim().length < 5) {
                    addressInput.classList.add('is-invalid');
                    showError(addressInput, 'Please enter a complete address');
                    isValid = false;
                }
                
                if (!validateUKPhone(phoneInput.value)) {
                    phoneInput.classList.add('is-invalid');
                    showError(phoneInput, 'Please enter a valid UK phone number');
                    isValid = false;
                }
                
                if (!validateUKPostcode(postcodeInput.value)) {
                    postcodeInput.classList.add('is-invalid');
                    showError(postcodeInput, 'Please enter a valid UK postcode');
                    isValid = false;
                }
                
                if (locationInput.value.trim().length < 2) {
                    locationInput.classList.add('is-invalid');
                    showError(locationInput, 'Please enter a valid city or location');
                    isValid = false;
                }
                
                if (!termsCheckbox.checked) {
                    termsCheckbox.classList.add('is-invalid');
                    showError(termsCheckbox.parentElement, 'You must agree to the terms');
                    isValid = false;
                }
                
                if (isValid) {
                    // Show loading state
                    const btn = form.querySelector('button[type="submit"]');
                    btn.querySelector('.register-text').classList.add('d-none');
                    btn.querySelector('.register-loading').classList.remove('d-none');
                    btn.disabled = true;
                    
                    // Submit the form
                    form.submit();
                } else {
                    // Scroll to first error
                    const firstError = form.querySelector('.is-invalid');
                    if (firstError) {
                        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        firstError.focus();
                    }
                }
            });
            
            // Helper functions
            function showError(element, message) {
                let feedback = element.parentElement.querySelector('.invalid-feedback');
                if (!feedback) {
                    feedback = document.createElement('div');
                    feedback.className = 'invalid-feedback';
                    element.parentElement.appendChild(feedback);
                }
                feedback.textContent = message;
                feedback.style.display = 'block';
            }
            
            function hideError(element) {
                const feedback = element.parentElement.querySelector('.invalid-feedback');
                if (feedback) {
                    feedback.style.display = 'none';
                }
            }
        });
    </script>
</x-auth-layout>
