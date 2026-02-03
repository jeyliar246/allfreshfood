<x-home-layout>
    <!-- Hero Section -->
    <section class="hero-section position-relative">
        <div class="hero-backgrounds">
            <div class="hero-bg active"
                style="background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('{{ asset('assets/slider2.png') }}'); background-size: 100% auto; background-position: center top; background-repeat: no-repeat;">
            </div>
            <div class="hero-bg"
                style="background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('{{ asset('assets/slider.png') }}'); background-size: 100% auto; background-position: center top; background-repeat: no-repeat;">
            </div>
            <div class="hero-bg"
                style="background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('{{ asset('assets/slider2.png') }}'); background-size: 100% auto; background-position: center top; background-repeat: no-repeat;">
            </div>
        </div>

        <div class="container h-100 d-flex align-items-center">
            <div class="row w-100 justify-content-center text-center text-white">
                <div class="col-lg-8">
                    <h1 class="display-4 fw-bold mb-4 hero-title">Discover Authentic Global Flavors</h1>
                    <p class="lead mb-5 hero-subtitle">From Indian spices to Japanese street food</p>

                    <form method="GET" action="{{ route('home.browse') }}" class="search-wrapper mx-auto">
                        <div class="postcode-search bg-white rounded-3 p-2 d-flex align-items-center gap-2">
                            <i class="bi bi-geo-alt text-muted fs-5"></i>
                            <input type="text" name="postcode" class="form-control border-0 flex-grow-1"
                                placeholder="Enter your location" id="postcodeInput">
                            <button class="btn btn-primary btn-search" type="submit">
                                <i class="bi bi-search me-1"></i><span class="d-none d-sm-inline">Search</span>
                            </button>
                        </div>
                    </form>

                    <p class="small mt-4 opacity-75">
                        Delivering to Bradford, and 50+ cities across the UK
                    </p>
                </div>
            </div>
        </div>

        <div class="position-absolute bottom-0 start-50 translate-middle-x mb-4">
            <div class="hero-dots">
                <button class="hero-dot active" onclick="goToSlide(0)"></button>
                <button class="hero-dot" onclick="goToSlide(1)"></button>
                <button class="hero-dot" onclick="goToSlide(2)"></button>
            </div>
        </div>
    </section>


    <!-- Products Section -->
    @include('layouts.video')
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold mb-3">Explore Varieties of Products</h2>
                <p class="text-muted">Discover authentic flavors from different cultures</p>
            </div>
            @if ($recentProducts->count() > 0)
                <div class="row g-4">
                    @foreach ($recentProducts as $product)
                        @include('layouts.prod')
                    @endforeach
                </div>
                <div class="d-flex justify-content-between align-items-center mt-5">
                    <div>
                        {{-- <h2 class="fw-bold mt-5">Varieties of Products </h2> --}}
                        <p class="text-muted mb-0">Discover authentic flavors from different cultures</p>
                    </div>
                    <a href="{{ route('home.browse') }}" class="btn btn-outline-primary">View All</a>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-egg-fried text-muted" style="font-size: 3rem;"></i>
                    <p class="mt-3 text-muted">No cuisines available at the moment.</p>
                </div>
            @endif
        </div>
    </section>

    <!-- Cuisines Section -->
    @include('layouts.bann')

    <!-- Featured Vendors -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-5">
                <div>
                    <h2 class="fw-bold mb-2">Stores </h2>
                    <p class="text-muted mb-0">Top-rated stores in your area</p>
                </div>
                <a href="{{ route('home.vendors') }}" class="btn btn-outline-primary">View All</a>
            </div>
            @if (isset($featuredVendors) && $featuredVendors->count() > 0)
                <div class="row g-4">
                    @foreach ($featuredVendors as $vendor)
                        <div class="col-md-6 col-lg-4">
                            <div class="card vendor-card h-100 border-0 shadow-sm">
                                <div class="position-relative">
                                    <img src="{{ $vendor->image ? asset('uploads/' . $vendor->image) : 'https://via.placeholder.com/300x200?text=' . urlencode($vendor->name) }}"
                                        class="card-img-top" alt="{{ $vendor->name }}"
                                        style="height: 200px; object-fit: cover;">
                                    @if ($vendor->featured)
                                        <span
                                            class="badge bg-warning position-absolute top-0 start-0 m-2">Featured</span>
                                    @endif
                                    <button
                                        class="btn btn-sm btn-light rounded-circle position-absolute top-0 end-0 m-2">
                                        <i class="bi bi-heart"></i>
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="card-title mb-0">{{ $vendor->name }}</h5>
                                        @if ($vendor->cuisine)
                                            <span class="badge bg-light text-dark">{{ $vendor->cuisine }}</span>
                                        @endif
                                    </div>
                                    <p class="card-text text-muted small mb-2">
                                        <i class="bi bi-geo-alt-fill text-primary"></i>
                                        {{ $vendor->location ?? 'N/A' }}
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-success">Open now</span>
                                        <span class="text-muted">{{ $vendor->products_count ?? 0 }} items</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-shop text-muted" style="font-size: 3rem;"></i>
                    <p class="mt-3 text-muted">No stores available at the moment.</p>
                </div>
            @endif
        </div>
    </section>

    {{-- @include('layouts.banner') --}}

    <!-- Features Section -->
    <section class="py-5" style="background-color: #00ccbc;">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-3 text-center">
                    <div class="feature-icon rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                        style="width: 80px; height: 80px;">
                        <i class="bi bi-globe text-white fs-2"></i>
                    </div>
                    <h5 class="fw-semibold">Global Cuisine</h5>
                    <p class="text-muted small">Authentic flavors from around the world</p>
                </div>
                <div class="col-md-3 text-center">
                    <div class="feature-icon rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                        style="width: 80px; height: 80px;">
                        <i class="bi bi-truck text-white fs-2"></i>
                    </div>
                    <h5 class="fw-semibold">Fast Delivery</h5>
                    <p class="text-muted small">Quick delivery to your doorstep</p>
                </div>
                <div class="col-md-3 text-center">
                    <div class="feature-icon rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                        style="width: 80px; height: 80px;">
                        <i class="bi bi-shield-check text-white fs-2"></i>
                    </div>
                    <h5 class="fw-semibold">Quality Assured</h5>
                    <p class="text-muted small">Verified vendors and fresh ingredients</p>
                </div>
                <div class="col-md-3 text-center">
                    <div class="feature-icon rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                        style="width: 80px; height: 80px;">
                        <i class="bi bi-people text-white fs-2"></i>
                    </div>
                    <h5 class="fw-semibold">Local Community</h5>
                    <p class="text-muted small">Support local and independent vendors</p>
                </div>
            </div>
        </div>
    </section>



    <!-- Newsletter -->
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="col-lg-6">
                    <h2 class="fw-bold mb-4">Stay Updated</h2>
                    <p class="text-muted mb-4">
                        Get notified about new vendors, special offers, and authentic recipes from around the world.
                    </p>
                    <div class="input-group mb-3">
                        <input type="email" class="form-control" placeholder="Enter your email address">
                        <button class="btn btn-primary" type="button">Subscribe</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        /* Add these styles to your index.blade.php in the <style> section or to your style.css */

        /* Updated Hero Section - No Zoom, Full Coverage */
        .hero-section {
            height: 80vh;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            background: white;
            width: 100%;
        }

        .hero-backgrounds {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            transform: none;
            /* Removed the translate transform */
        }

        .hero-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-size: cover;
            /* Ensures image covers the area */
            background-position: center center;
            /* Centers the image */
            background-repeat: no-repeat;
            opacity: 0;
            transition: opacity 2s ease-in-out;
            /* Only transition opacity */
            transform: none;
            /* Removed the scale transform */
        }

        .hero-bg.active {
            opacity: 1;
            animation: none;
            /* Removed kenBurns animation */
        }

        /* Optional: If you want a subtle fade effect only */
        .hero-bg.active {
            opacity: 1;
            animation: subtleFade 20s ease-in-out infinite;
        }

        @keyframes subtleFade {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.95;
            }
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .hero-section {
                height: 60vh;
            }

            .hero-bg {
                background-size: cover;
                /* Maintain cover on mobile */
                background-position: center center;
            }
        }

        @media (max-width: 576px) {
            .hero-section {
                height: 50vh;
            }
        }

        /* Search wrapper styles */
        .search-wrapper {
            max-width: 600px;
        }

        .postcode-search {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .postcode-search input {
            outline: none;
        }

        .postcode-search input:focus {
            outline: none;
            box-shadow: none;
        }

        .btn-search {
            padding: 0.5rem 1.5rem;
            font-weight: 500;
            border-radius: 8px;
            white-space: nowrap;
        }

        /* Mobile responsiveness */
        @media (max-width: 768px) {
            .search-wrapper {
                max-width: 100%;
                padding: 0 1rem;
            }

            .btn-search {
                padding: 0.5rem 1rem;
            }

            .postcode-search {
                padding: 0.5rem !important;
            }
        }

        @media (max-width: 576px) {
            .btn-search {
                padding: 0.5rem 0.75rem;
            }

            .postcode-search input {
                font-size: 0.9rem;
            }
        }
    </style>
</x-home-layout>
