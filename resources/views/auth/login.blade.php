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
                            <h2 class="fw-bold mb-2">Create Your All Foods Account</h2>
                            <p class="text-muted small">Join us to explore authentic global flavors</p>
                        </div>
                        <div class="card-body p-4">
                            <form method="POST" action="{{ route('login') }}"   class="needs-validation">
                                @csrf
                               

                                <!-- Email -->
                                <div class="mb-3">
                                    <label for="email" class="form-label fw-medium">Email Address</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                        <input type="email" name="email" class="form-control" id="email" placeholder="Enter your email" required>
                                        <div class="invalid-feedback">Please provide a valid email.</div>
                                    </div>
                                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger" />
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
                                        <div class="invalid-feedback">Password must be at least 8 characters.</div>
                                    </div>
                                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-danger" />
                                </div>

                                <!-- Submit Button -->
                                <button type="submit" class="btn btn-primary w-100 mb-3">
                                    <span class="register-text">Login</span>
                                    <span class="register-loading d-none">
                                        <span class="spinner-border spinner-border-sm me-2"></span>
                                        Logging In...
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

    {{-- <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form> --}}
</x-auth-layout>
