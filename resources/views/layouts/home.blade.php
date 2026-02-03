<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Foods - Discover Authentic Global Flavors</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
    <link rel="icon" href="{{ asset('assets/favicon.png') }}">
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

        .cart-count {
            font-size: 0.7rem;
            padding: 0.25rem 0.5rem;
            border-radius: 10px;
        }
        .ask-francis-link { position: relative; }
        .beta-caption { position: absolute; top: 100%; left: 50%; transform: translateX(-50%); font-size: 0.65rem; line-height: 1; color: #6c757d; white-space: nowrap; pointer-events: none; }
        .ask-francis-wrapper { position: relative; padding-bottom: 0.75rem; }
        .mobile-ask-wrapper { position: relative; padding-bottom: 0.6rem; }
        .mobile-ask-wrapper .beta-caption { top: calc(100% + 2px); }
        footer.bg-dark a { color: #000 !important; }
        footer.bg-dark a:hover { color: #000 !important; }
        footer.bg-dark p, footer.bg-dark li { color: #000; }
        .footer-logo { max-width: 200px; height: auto; object-fit: contain; }
    </style>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-WM1H4BBWR7"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-WM1H4BBWR7');
    </script>
    <!-- Meta Pixel Code -->
    <script>
        ! function(f, b, e, v, n, t, s) {
            if (f.fbq) return;
            n = f.fbq = function() {
                n.callMethod ?
                    n.callMethod.apply(n, arguments) : n.queue.push(arguments)
            };
            if (!f._fbq) f._fbq = n;
            n.push = n;
            n.loaded = !0;
            n.version = '2.0';
            n.queue = [];
            t = b.createElement(e);
            t.async = !0;
            t.src = v;
            s = b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t, s)
        }(window, document, 'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '848837124185810');
        fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
            src="https://www.facebook.com/tr?id=848837124185810&ev=PageView&noscript=1" /></noscript>
    <!-- End Meta Pixel Code -->
</head>

<body>
    <!-- Header -->
    <header class="navbar navbar-expand-lg navbar-light bg-white sticky-top border-bottom">
        <div class="container-fluid">
            <!-- Mobile Top Bar: Logo + Action Buttons -->
            <div class="mobile-top-bar d-lg-none">
                <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                    <div class="rounded p-2">
                        <img src="{{ asset('assets/logo.png') }}" alt="Logo" class="img-fluid"
                            style="width: 100px; height: 50px;">
                    </div>
                </a>

                <div class="mobile-action-buttons">
                    <div class="mobile-ask-wrapper">
                        <button class="btn btn-outline-primary" onclick="window.location.href='{{ route('home.ai') }}'">
                            <i class="bi bi-stars"></i>
                        </button>
                        <div class="beta-caption">Beta</div>
                    </div>

                    <button class="btn btn-outline-primary position-relative"
                        onclick="window.location.href='{{ route('home.cart') }}'">
                        <i class="bi bi-cart"></i>
                        <span
                            class="cart-count badge bg-danger position-absolute top-0 start-100 translate-middle {{ Cart::count() == 0 ? 'd-none' : '' }}">
                            {{ Cart::count() }}
                        </span>
                    </button>

                    <button class="navbar-toggler border-0 p-2" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarNav">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                </div>
            </div>

            <!-- Desktop Logo -->
            <a class="navbar-brand d-none d-lg-flex align-items-center" href="{{ route('home') }}">
                <div class="rounded p-2 me-2">
                    <img src="{{ asset('assets/logo.png') }}" alt="Logo" class="img-fluid"
                        style="width: 120px; height: 60px;">
                </div>
            </a>

            <!-- Mobile Search Bar (below logo and buttons) -->
            <div class="mobile-search-wrapper d-lg-none w-100">
                <form action="{{ route('home.browse') }}" method="GET" class="mobile-search-form">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" value="{{ request('search') }}"
                            placeholder="Search stores...">
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
                    <a class="nav-link {{ Request::routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                        <i class="bi bi-house-door me-2"></i>Home
                    </a>
                    <a class="nav-link {{ Request::routeIs('home.browse') ? 'active' : '' }}"
                        href="{{ route('home.browse') }}">
                        <i class="bi bi-grid me-2"></i>Browse
                    </a>
                    <a class="nav-link {{ Request::routeIs('home.vendors') ? 'active' : '' }}"
                        href="{{ route('home.vendors') }}">
                        <i class="bi bi-shop me-2"></i>Stores
                    </a>
                    <div class="ask-francis-wrapper d-none d-lg-flex">
                        <a class="nav-link btn btn-outline-primary ask-francis-link {{ Request::routeIs('home.ai') ? 'active' : '' }}" href="{{ route('home.ai') }}">
                            <i class="bi bi-stars me-2 text-gray"></i><span class="text-gray">Ask Francis</span>
                        </a>
                        <div class="beta-caption">Beta</div>
                    </div>
                </nav>

                <!-- Mobile User Section -->
                <div class="mobile-user-section d-lg-none">
                    <div class="dropdown w-100">
                        <button class="btn btn-outline-primary dropdown-toggle d-flex align-items-center"
                            data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle text-gray me-2"></i>
                            <span>
                                @auth
                                    @if (Auth::user()->email_verified_at != null)
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
                                @if (Auth::user()->email_verified_at != null)
                                    <li>
                                        @if (Auth::user()->role == 'vendor')
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
                                    <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                    </form>
                                    <li>
                                        <a class="dropdown-item text-danger" href="#"
                                            onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();">
                                            <i class="bi bi-power me-2"></i>Sign Out
                                        </a>
                                    </li>
                                @else
                                    <li>
                                        <a class="dropdown-item" href="{{ route('verify') }}">
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
                <form action="{{ route('home.browse') }}" method="GET"
                    class="desktop-search d-none d-lg-flex me-3 flex-grow-1" style="max-width: 400px;">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control" value="{{ request('search') }}"
                            placeholder="Search stores...">
                    </div>
                </form>

                <!-- Desktop Right Side Actions -->
                <div class="desktop-actions d-none d-lg-flex align-items-center gap-3">
                    {{-- <button class="btn btn-outline-primary">
                        <i class="bi bi-heart text-gray-600"></i>
                    </button> --}}

                    <button class="btn btn-outline-primary position-relative"
                        onclick="window.location.href='{{ route('home.cart') }}'">
                        <i class="bi bi-cart text-gray-600"></i>
                        <span class="cart-count badge bg-white text-gray-600 {{ Cart::count() == 0 ? 'd-none' : '' }}">
                            {{ Cart::count() }}
                        </span>
                    </button>

                    <div class="dropdown">
                        <button class="btn btn-outline-primary dropdown-toggle d-flex align-items-center"
                            data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-1"></i>
                            <span>
                                @auth
                                    @if (Auth::user()->email_verified_at != null)
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
                                @if (Auth::user()->email_verified_at != null)
                                    <li>
                                        @if (Auth::user()->role == 'vendor')
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
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                    </form>
                                    <li>
                                        <a class="dropdown-item" href="#"
                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <i class="bi bi-power me-2"></i>Sign Out
                                        </a>
                                    </li>
                                @else
                                    <li>
                                        <a class="dropdown-item" href="{{ route('verify') }}">
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

    {{ $slot }}

    <!-- Footer -->
    <footer class="bg-dark py-5 mt-5">
        <div class="container">
            <div class="row g-4 align-items-start">
                <div class="col-12 col-md-4 text-center text-md-start">
                    <a class="navbar-brand d-inline-flex align-items-center justify-content-center justify-content-md-start mb-3" href="{{ route('home') }}">
                        <img src="{{ asset('assets/logo.png') }}" alt="All Foods" class="img-fluid footer-logo">
                    </a>
                    <p class="mb-3 text-black">
                        Connecting you with authentic global flavors from local and international vendors across the UK.
                    </p>
                    <div class="d-flex gap-2 justify-content-center justify-content-md-start">
                        <a href="#" class="btn btn-outline-light btn-sm"><i class="bi bi-envelope text-black"></i></a>
                        <a href="#" class="btn btn-outline-light btn-sm"><i class="bi bi-telephone text-black"></i></a>
                    </div>
                </div>

                <div class="col-6 col-md-2 text-center text-md-start">
                    <h6 class="fw-semibold mb-3 text-black">Quick Links</h6>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('home.about') }}" class="text-decoration-none text-black">About Us</a></li>
                        <li><a href="{{ route('home.how') }}" class="text-decoration-none text-black">How It Works</a></li>
                        <li><a href="{{ route('home.become-vendor') }}" class="text-decoration-none text-black">Become a Vendor</a></li>
                        <li><a href="{{ route('home.careers') }}" class="text-decoration-none text-black">Careers</a></li>
                    </ul>
                </div>

                <div class="col-6 col-md-2 text-center text-md-start">
                    <h6 class="fw-semibold mb-3 text-black">Support</h6>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('home.help') }}" class="text-decoration-none text-black">Help Center</a></li>
                        <li><a href="{{ route('home.contact') }}" class="text-decoration-none text-black">Contact Us</a></li>
                        <li><a href="{{ route('home.delivery') }}" class="text-decoration-none text-black">Delivery Info</a></li>
                        <li><a href="{{ route('home.returns') }}" class="text-decoration-none text-black">Returns</a></li>
                    </ul>
                </div>

                <div class="col-12 col-md-4 text-center text-md-start">
                    <h6 class="fw-semibold mb-3 text-black">Legal</h6>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('home.privacy') }}" class="text-decoration-none text-black">Privacy Policy</a></li>
                        <li><a href="{{ route('home.terms') }}" class="text-decoration-none text-black">Terms of Service</a></li>
                        <li><a href="{{ route('home.cookies') }}" class="text-decoration-none text-black">Cookie Policy</a></li>
                        <li><a href="#" class="text-decoration-none text-black" onclick="window.CookieConsent && window.CookieConsent.open(); return false;">Cookie Preferences</a></li>
                        <li><a href="{{ route('home.accessibility') }}" class="text-decoration-none text-black">Accessibility</a></li>
                    </ul>
                </div>
            </div>

            <hr class="my-4" style="border-color: #000;">
            <div class="text-center">
                <p class="mb-0" style="color: #000;">&copy; {{ date('Y') }} All Foods. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>

    <!-- Cookie Banner (Bootstrap) -->
    <div id="cookie-banner" class="position-fixed bottom-0 start-0 end-0 p-3" style="z-index: 1080; display: none;">
        <div class="container">
            <div class="card shadow-lg border-0">
                <div class="card-body d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
                    <div class="text-muted">
                        We use cookies to enhance your experience, analyze traffic, and for marketing. See our
                        <a href="{{ route('home.cookies') }}">Cookie Policy</a> and
                        <a href="{{ route('home.privacy') }}">Privacy Policy</a>.
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <button id="cb-manage" class="btn btn-outline-secondary">Customize</button>
                        <button id="cb-reject" class="btn btn-outline-secondary">Reject non-essential</button>
                        <button id="cb-accept" class="btn btn-primary">Accept all</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cookie Preferences Modal -->
    <div class="modal fade" id="cookieModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cookie Preferences</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" checked disabled id="cp-necessary">
                        <label class="form-check-label" for="cp-necessary">
                            Necessary – Required for the site to function and cannot be disabled.
                        </label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="cp-functional">
                        <label class="form-check-label" for="cp-functional">Functional – Remember your choices.</label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="cp-analytics">
                        <label class="form-check-label" for="cp-analytics">Analytics – Help us improve the site.</label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="cp-marketing">
                        <label class="form-check-label" for="cp-marketing">Marketing – Personalize content and ads.</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="cp-save">Save preferences</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const COOKIE_NAME = 'cookie_consent';
            const CACHE_KEY = 'cookie_consent_cache';
            const DAYS = 180;
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            function now() { return Date.now(); }
            function ms(days) { return days * 24 * 60 * 60 * 1000; }

            function getConsent() {
                try {
                    const row = document.cookie.split('; ').find(r => r.startsWith(COOKIE_NAME + '='));
                    return row ? JSON.parse(decodeURIComponent(row.split('=')[1])) : null;
                } catch (e) { return null; }
            }

            function getCache() {
                try {
                    const raw = localStorage.getItem(CACHE_KEY);
                    if (!raw) return null;
                    const obj = JSON.parse(raw);
                    if (!obj || !obj.expires || obj.expires <= now()) return null;
                    return obj;
                } catch (e) { return null; }
            }

            function setCache(consent) {
                try {
                    const obj = { consent: consent || {}, expires: now() + ms(DAYS) };
                    localStorage.setItem(CACHE_KEY, JSON.stringify(obj));
                } catch (e) {}
            }

            function showBannerIfNeeded() {
                if (getCache()) return;
                const c = getConsent();
                if (c) {
                    setCache(c);
                    return;
                }
                document.getElementById('cookie-banner').style.display = 'block';
            }

            function updateModalFromConsent() {
                const c = (getConsent() || (getCache() ? getCache().consent : {})) || {};
                document.getElementById('cp-functional').checked = !!c.functional;
                document.getElementById('cp-analytics').checked = !!c.analytics;
                document.getElementById('cp-marketing').checked = !!c.marketing;
            }

            async function post(url, body) {
                const res = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(body || {})
                });
                return res.json();
            }

            function hideBanner() {
                const el = document.getElementById('cookie-banner');
                if (el) el.style.display = 'none';
            }

            document.addEventListener('DOMContentLoaded', function () {
                const accept = document.getElementById('cb-accept');
                const reject = document.getElementById('cb-reject');
                const manage = document.getElementById('cb-manage');
                const save = document.getElementById('cp-save');
                const modal = new bootstrap.Modal(document.getElementById('cookieModal'));

                window.CookieConsent = window.CookieConsent || { open: () => { updateModalFromConsent(); modal.show(); } };

                showBannerIfNeeded();

                accept && accept.addEventListener('click', async () => {
                    const res = await post('/consent/accept-all');
                    setCache(res && res.consent ? res.consent : { functional: true, analytics: true, marketing: true });
                    hideBanner();
                });

                reject && reject.addEventListener('click', async () => {
                    const res = await post('/consent/reject-all');
                    setCache(res && res.consent ? res.consent : { functional: false, analytics: false, marketing: false });
                    hideBanner();
                });

                manage && manage.addEventListener('click', () => {
                    updateModalFromConsent();
                    modal.show();
                });

                save && save.addEventListener('click', async () => {
                    const functional = document.getElementById('cp-functional').checked;
                    const analytics = document.getElementById('cp-analytics').checked;
                    const marketing = document.getElementById('cp-marketing').checked;
                    const res = await post('/consent/save', { functional, analytics, marketing });
                    setCache(res && res.consent ? res.consent : { functional, analytics, marketing });
                    modal.hide();
                    hideBanner();
                });
            });
        })();
    </script>
    @stack('scripts')
</body>

</html>
