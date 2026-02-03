<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Foods -Authentication</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{asset('assets/css/style.css')}}" rel="stylesheet">
    <link rel="icon" href="{{asset('assets/favicon.png')}}">
    <style>
        /* Mobile Navigation Improvements */
        @media (max-width: 991px) {
            /* Top bar with logo and action buttons */
            .mobile-top-bar {
                display: flex;
                align-items: center;
                justify-content: space-between;
                width: 100%;
                padding: 0.5rem 0;
            }
            
            .mobile-action-buttons {
                display: flex;
                gap: 0.5rem;
                align-items: center;
            }
            
            .mobile-action-buttons .btn {
                padding: 0.5rem 0.75rem;
                border-radius: 8px;
            }
            
            .mobile-action-buttons .btn-outline-primary {
                border-width: 2px;
            }
            
            /* Search bar directly under logo/buttons */
            .mobile-search-wrapper {
                width: 100%;
                padding: 0.75rem 0;
                border-bottom: 1px solid #dee2e6;
            }
            
            .mobile-search-form {
                width: 100%;
            }
            
            .mobile-search-form .input-group {
                border-radius: 8px;
                overflow: hidden;
            }
            
            .mobile-search-form .form-control {
                border: 2px solid #dee2e6;
                padding: 0.75rem;
            }
            
            .mobile-search-form .input-group-text {
                border: 2px solid #dee2e6;
                border-right: none;
                background-color: white;
            }
            
            /* Collapsible navigation menu */
            .navbar-collapse {
                background: white;
                padding: 1rem 0;
                margin-top: 0;
            }
            
            .navbar-nav {
                width: 100%;
            }
            
            .navbar-nav .nav-link {
                padding: 0.875rem 1rem;
                margin: 0.25rem 0;
                border-radius: 8px;
                transition: all 0.3s ease;
                display: flex;
                align-items: center;
                font-weight: 500;
            }
            
            .navbar-nav .nav-link i {
                font-size: 1.1rem;
                width: 24px;
            }
            
            .navbar-nav .nav-link:hover {
                background-color: #f8f9fa;
                transform: translateX(4px);
            }
            
            .navbar-nav .nav-link.active {
                background-color: #0d6efd;
                color: white !important;
            }
            
            .navbar-nav .nav-link.btn-outline-primary {
                margin: 0.5rem 0;
                width: 100%;
                justify-content: flex-start;
                border-width: 2px;
                background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);
                color: white;
                border: none;
            }
            
            .navbar-nav .nav-link.btn-outline-primary:hover {
                background: linear-gradient(135deg, #0b5ed7 0%, #0a58ca 100%);
                transform: translateX(4px);
            }
            
            /* User dropdown in mobile menu */
            .mobile-user-section {
                margin-top: 1rem;
                padding-top: 1rem;
                border-top: 1px solid #dee2e6;
            }
            
            .mobile-user-section .dropdown-toggle {
                width: 100%;
                justify-content: space-between;
                padding: 0.875rem 1rem;
                border-radius: 8px;
                border-width: 2px;
            }
            
            .mobile-user-section .dropdown-menu {
                width: 100%;
                margin-top: 0.5rem;
                border-radius: 8px;
                border: none;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            }
            
            .mobile-user-section .dropdown-item {
                padding: 0.75rem 1rem;
                border-radius: 6px;
                margin: 0.25rem 0.5rem;
            }
            
            .mobile-user-section .dropdown-item:hover {
                background-color: #f8f9fa;
            }
            
            /* Hide desktop elements */
            .desktop-search {
                display: none !important;
            }
            
            .desktop-actions {
                display: none !important;
            }
        }
        
        /* Desktop - hide mobile elements */
        @media (min-width: 992px) {
            .mobile-top-bar {
                display: none !important;
            }
            
            .mobile-search-wrapper {
                display: none !important;
            }
            
            .mobile-action-buttons {
                display: none !important;
            }
            
            .mobile-user-section {
                display: none !important;
            }
            
            .navbar-toggler {
                display: none !important;
            }
        }
        
        /* Cart badge styling */
        .cart-count {
            font-size: 0.7rem;
            padding: 0.25rem 0.5rem;
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="navbar navbar-expand-lg navbar-light bg-white sticky-top border-bottom">
        <div class="container-fluid">
            <!-- Mobile Top Bar: Logo + Action Buttons -->
            <div class="mobile-top-bar d-lg-none">
                <a class="navbar-brand d-flex align-items-center" href="{{route('home')}}">
                    <div class="rounded p-2">
                        <img src="{{asset('assets/logo.png')}}" alt="Logo" class="img-fluid" style="width: 100px; height: 50px;">
                    </div>
                </a>

                <div class="mobile-action-buttons">
                    <button class="btn btn-outline-primary" onclick="window.location.href='{{route('home.ai')}}'">
                        <i class="bi bi-stars"></i>
                    </button>

                    <button class="btn btn-outline-primary position-relative" onclick="window.location.href='{{route('home.cart')}}'">
                        <i class="bi bi-cart"></i>
                        <span class="cart-count badge bg-danger position-absolute top-0 start-100 translate-middle {{ Cart::count() == 0 ? 'd-none' : '' }}">
                            {{ Cart::count() }}
                        </span>
                    </button>

                    <button class="navbar-toggler border-0 p-2" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                </div>
            </div>

            <!-- Desktop Logo -->
            <a class="navbar-brand d-none d-lg-flex align-items-center" href="{{route('home')}}">
                <div class="rounded p-2 me-2">
                    <img src="{{asset('assets/logo.png')}}" alt="Logo" class="img-fluid" style="width: 120px; height: 60px;">
                </div>
            </a>

            <!-- Mobile Search Bar (below logo and buttons) -->
            <div class="mobile-search-wrapper d-lg-none w-100">
                <form action="{{ route('home.browse') }}" method="GET" class="mobile-search-form">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Search stores...">
                        <span class="input-group-text bg-white">
                            <i class="bi bi-search"></i>
                        </span>
                    </div>
                </form>
            </div>

            <!-- Collapsible Navigation -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- Navigation Links -->
                <nav class="navbar-nav me-auto">
                    <a class="nav-link {{Request::routeIs('home') ? 'active' : ''}}" href="{{route('home')}}">
                        <i class="bi bi-house-door me-2"></i>Home
                    </a>
                    <a class="nav-link {{Request::routeIs('home.browse') ? 'active' : ''}}" href="{{route('home.browse')}}">
                        <i class="bi bi-grid me-2"></i>Browse
                    </a>
                    <a class="nav-link {{Request::routeIs('home.vendors') ? 'active' : ''}}" href="{{route('home.vendors')}}">
                        <i class="bi bi-shop me-2"></i>Stores
                    </a>
                    <a class="nav-link btn btn-outline-primary d-none d-lg-flex {{Request::routeIs('home.ai') ? 'active' : ''}}" href="{{route('home.ai')}}">
                        <i class="bi bi-stars me-2 text-white"></i><span class="text-white">Ask Francis</span>
                    </a>
                </nav>

                <!-- Mobile User Section -->
                <div class="mobile-user-section d-lg-none">
                    <div class="dropdown w-100">
                        <button class="btn btn-outline-primary dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-2"></i>
                            <span>
                                @auth
                                    @if(Auth::user()->email_verified_at != null)
                                        {{ Str::limit(Auth::user()->name, 20) }}
                                    @else   
                                        Verify Your Account
                                    @endif
                                @else   
                                    Sign In / Sign Up
                                @endauth
                            </span>
                        </button>
                        <ul class="dropdown-menu">
                            @auth
                                @if(Auth::user()->email_verified_at != null)
                                    <li>
                                        @if(Auth::user()->role == 'vendor')
                                            <a class="dropdown-item" href="{{ route('dashboard') }}">
                                                <i class="bi bi-speedometer2 me-2"></i>Dashboard
                                            </a>
                                        @elseif (Auth::user()->role == 'admin')
                                            <a class="dropdown-item" href="{{ route('dashboard') }}">
                                                <i class="bi bi-speedometer2 me-2"></i>Dashboard
                                            </a>
                                        @else
                                            <a class="dropdown-item" href="{{ route('home.orders') }}">
                                                <i class="bi bi-bag-check me-2"></i>Order History
                                            </a>
                                        @endif
                                    </li>
                                    <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                    <li>
                                        <a class="dropdown-item text-danger" href="#" onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();">
                                            <i class="bi bi-power me-2"></i>Sign Out
                                        </a>
                                    </li>
                                @else
                                    <li>
                                        <a class="dropdown-item" href="{{route('verify')}}">
                                            <i class="bi bi-exclamation-circle me-2"></i>Verify Account
                                        </a>
                                    </li>
                                @endif
                            @else
                                <li>
                                    <a class="dropdown-item" href="/login">
                                        <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="/register">
                                        <i class="bi bi-person-plus me-2"></i>Sign Up
                                    </a>
                                </li>
                            @endauth
                        </ul>
                    </div>
                </div>

                <!-- Desktop Search Bar -->
                <form action="{{ route('home.browse') }}" method="GET" class="desktop-search d-none d-lg-flex me-3 flex-grow-1" style="max-width: 400px;">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Search stores...">
                    </div>
                </form>

                <!-- Desktop Right Side Actions -->
                <div class="desktop-actions d-none d-lg-flex align-items-center gap-3">
                    <button class="btn btn-outline-primary">
                        <i class="bi bi-heart"></i>
                    </button>

                    <button class="btn btn-outline-primary position-relative" onclick="window.location.href='{{route('home.cart')}}'">
                        <i class="bi bi-cart"></i>
                        <span class="cart-count badge bg-white text-primary {{ Cart::count() == 0 ? 'd-none' : '' }}">
                            {{ Cart::count() }}
                        </span>
                    </button>

                    <div class="dropdown">
                        <button class="btn btn-outline-primary dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-1"></i>
                            <span>
                                @auth
                                    @if(Auth::user()->email_verified_at != null)
                                        {{ Str::limit(Auth::user()->name, 10) }}
                                    @else   
                                        Verify
                                    @endif
                                @else   
                                    Sign In
                                @endauth
                            </span>
                        </button>
                        <ul class="dropdown-menu">
                            @auth
                                @if(Auth::user()->email_verified_at != null)
                                    <li>
                                        @if(Auth::user()->role == 'vendor')
                                            <a class="dropdown-item" href="{{ route('dashboard') }}">
                                                <i class="bi bi-speedometer2 me-2"></i>Dashboard
                                            </a>
                                        @elseif (Auth::user()->role == 'admin')
                                            <a class="dropdown-item" href="{{ route('dashboard') }}">
                                                <i class="bi bi-speedometer2 me-2"></i>Dashboard
                                            </a>
                                        @else
                                            <a class="dropdown-item" href="{{ route('home.orders') }}">
                                                <i class="bi bi-speedometer2 me-2"></i>Order History
                                            </a>
                                        @endif
                                    </li>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                    <li>
                                        <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <i class="bi bi-power me-2"></i>Sign Out
                                        </a>
                                    </li>
                                @else
                                    <li>
                                        <a class="dropdown-item" href="{{route('verify')}}">
                                            <i class="bi bi-exclamation-circle me-2"></i>Verify
                                        </a>
                                    </li>
                                @endif
                            @else
                                <li>
                                    <a class="dropdown-item" href="/login">
                                        <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="/register">
                                        <i class="bi bi-person-plus me-2"></i>Sign Up
                                    </a>
                                </li>
                            @endauth
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Registration Form -->
   {{ $slot }}

    <!-- Footer -->
     <footer class="bg-dark text-black py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="d-flex align-items-center">
                        <div class="rounded "> 
                            {{-- assets/logo_bg.png --}}
                            <img src="{{asset('assets/logo.png')}}" alt="Logo" class="img-fluid" style="width: 280px; height: 190px;">
                        </div>
                    </div>
                    <p class="text-black mb-3">
                        Connecting you with authentic global flavors from local and international vendors across the UK.
                    </p>
                    <div class="d-flex gap-2">
                        <a href="#" class="btn btn-outline-dark btn-sm">
                            <i class="bi bi-envelope"></i>
                        </a>
                        <a href="#" class="btn btn-outline-dark btn-sm">
                            <i class="bi bi-telephone"></i>
                        </a>
                    </div>
                </div>

                <div class="col-md-3">
                    <h6 class="fw-semibold mb-3">Quick Links</h6>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('home.about') }}" class="text-black text-decoration-none">About Us</a></li>
                        <li><a href="{{ route('home.how') }}" class="text-black text-decoration-none">How It Works</a></li>
                        <li><a href="{{ route('home.become-vendor') }}" class="text-black text-decoration-none">Become a Vendor</a></li>
                        <li><a href="{{ route('home.careers') }}" class="text-black text-decoration-none">Careers</a></li>
                    </ul>
                </div>

                <div class="col-md-3">
                    <h6 class="fw-semibold mb-3">Support</h6>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('home.help') }}" class="text-black text-decoration-none">Help Center</a></li>
                        <li><a href="{{ route('home.contact') }}" class="text-black text-decoration-none">Contact Us</a></li>
                        <li><a href="{{ route('home.delivery') }}" class="text-black text-decoration-none">Delivery Info</a></li>
                        <li><a href="{{ route('home.returns') }}" class="text-black text-decoration-none">Returns</a></li>
                    </ul>
                </div>

                <div class="col-md-3">
                    <h6 class="fw-semibold mb-3">Legal</h6>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('home.privacy') }}" class="text-black text-decoration-none">Privacy Policy</a></li>
                        <li><a href="{{ route('home.terms') }}" class="text-black text-decoration-none">Terms of Service</a></li>
                        <li><a href="{{ route('home.cookies') }}" class="text-black text-decoration-none">Cookie Policy</a></li>
                        <li><a href="{{ route('home.accessibility') }}" class="text-black text-decoration-none">Accessibility</a></li>
                    </ul>
                </div>
            </div>

            <hr class="my-4">
            <div class="text-center text-black">
                <p>&copy; {{date('Y')}} All foods. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Form validation (only if a form with id 'register-form' exists on the page)
        (function() {
            const form = document.getElementById('register-form');
            if (!form) return;

            form.addEventListener('submit', function() {
                const passwordEl = document.getElementById('password');
                const confirmEl = document.getElementById('password_confirmation');

                if (passwordEl && confirmEl) {
                    if (passwordEl.value !== confirmEl.value) {
                        confirmEl.setCustomValidity('Passwords do not match');
                    } else {
                        confirmEl.setCustomValidity('');
                    }
                }

                this.classList.add('was-validated');
            });
        })();

        // Password toggle functionality
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const toggle = document.getElementById(inputId + '-toggle');
            
            if (input.type === 'password') {
                input.type = 'text';
                toggle.className = 'bi bi-eye-slash';
            } else {
                input.type = 'password';
                toggle.className = 'bi bi-eye';
            }
        }
    </script>
</body>
</html>