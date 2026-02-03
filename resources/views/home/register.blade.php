<x-auth-layout>
    <section class="min-vh-100 d-flex align-items-center justify-content-center py-5" style="background: var(--gradient-secondary);">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-5">
                    <div class="card shadow-lg border-0 animate__animated animate__fadeInUp">
                        <div class="card-header bg-white border-0 text-center py-4">
                            <div class="d-flex justify-content-center mb-3">
                                <div class="bg-primary rounded p-3">
                                    <i class="bi bi-globe text-white fs-2"></i>
                                </div>
                            </div>
                            <h2 class="fw-bold mb-2">Create Your GlobalGrub Account</h2>
                            <p class="text-muted small">Join us to explore authentic global flavors</p>
                        </div>
                        <div class="card-body p-4">
                            <form id="register-form" class="needs-validation" novalidate>
                                <!-- Full Name -->
                                <div class="mb-3">
                                    <label for="fullName" class="form-label fw-medium">Full Name</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                                        <input type="text" class="form-control" id="fullName" placeholder="Enter your full name" required>
                                        <div class="invalid-feedback">Please provide your full name.</div>
                                    </div>
                                </div>

                                <!-- Email -->
                                <div class="mb-3">
                                    <label for="email" class="form-label fw-medium">Email Address</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                        <input type="email" class="form-control" id="email" placeholder="Enter your email" required>
                                        <div class="invalid-feedback">Please provide a valid email.</div>
                                    </div>
                                </div>

                                <!-- Password -->
                                <div class="mb-3">
                                    <label for="password" class="form-label fw-medium">Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                        <input type="password" class="form-control" id="password" placeholder="Enter your password" required minlength="8">
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">
                                            <i class="bi bi-eye" id="password-toggle"></i>
                                        </button>
                                        <div class="invalid-feedback">Password must be at least 8 characters.</div>
                                    </div>
                                </div>

                                <!-- Confirm Password -->
                                <div class="mb-3">
                                    <label for="confirmPassword" class="form-label fw-medium">Confirm Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                        <input type="password" class="form-control" id="confirmPassword" placeholder="Confirm your password" required minlength="8">
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('confirmPassword')">
                                            <i class="bi bi-eye" id="confirmPassword-toggle"></i>
                                        </button>
                                        <div class="invalid-feedback">Passwords do not match.</div>
                                    </div>
                                </div>

                                <!-- Terms Checkbox -->
                                <div class="mb-4 form-check">
                                    <input class="form-check-input" type="checkbox" id="terms" required>
                                    <label class="form-check-label small" for="terms">
                                        I agree to the <a href="#" class="text-decoration-none text-primary">Terms of Service</a> and <a href="#" class="text-decoration-none text-primary">Privacy Policy</a>
                                    </label>
                                    <div class="invalid-feedback">You must agree to the terms.</div>
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

                            <div class="row g-2 mb-4">
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
                            </div>

                            <!-- Sign In Link -->
                            <div class="text-center">
                                <p class="text-muted small mb-0">
                                    Already have an account? 
                                    <a href="signin.html" class="text-decoration-none fw-medium text-primary">Sign In</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-auth-layout>
