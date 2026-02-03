<x-auth-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4 text-danger" :status="session('status')" />

    <section class="min-vh-100 d-flex align-items-center justify-content-center py-5" style="background: var(--gradient-secondary);">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-5">
                    <div class="card shadow-lg border-0 animate__animated animate__fadeInUp">
                        <div class="card-header bg-white border-0 text-center py-4">
                            <div class="d-flex justify-content-center mb-3">
                                <div class="rounded p-3">
                                    <img src="{{asset('assets/logo.png')}}" alt="Logo" class="img-fluid" style="width: 200px; height: 80px;">

                                    {{-- <i class="bi bi-globe text-white fs-2"></i> --}}
                                </div>
                            </div>
                            <h2 class="fw-bold mb-2">Verify Your Email Address</h2>
                            <p class="text-muted small">Please check your email for a verification link.</p>
                        </div>
                        <div class="card-body p-4">
                            <form method="POST" action="{{ route('verifyCode') }}"   class="needs-validation">
                                @csrf
                               

                                <!-- Email -->
                                <div class="mb-3">
                                    <label for="email" class="form-label fw-medium">Verification Code</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                        <input type="text" name="verify_code" class="form-control" id="verify_code" placeholder="Verification Code" required>
                                        <div class="invalid-feedback">Please provide a valid email.</div>
                                    </div>
                                    <x-input-error :messages="$errors->get('verify_code')" class="mt-2 text-danger" />
                                </div>


                                <!-- Submit Button -->
                                <button type="submit" class="btn btn-primary w-100 mb-3">
                                    <span class="register-text">Verify</span>
                                    <span class="register-loading d-none">
                                        <span class="spinner-border spinner-border-sm me-2"></span>
                                        Logging In...
                                    </span>
                                </button>

                                <div class="text-center">
                                    <p class="text-muted small mb-0">
                                        <a href="{{ route('resendVerifyCode') }}" class="text-decoration-none fw-medium text-primary">Resend Verification Code</a>
                                    </p>
                                </div>
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
                                    Don't have an account? 
                                    <a href="/register" class="text-decoration-none fw-medium text-primary">Sign Up</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


</x-auth-layout>
