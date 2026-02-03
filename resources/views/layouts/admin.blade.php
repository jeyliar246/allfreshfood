<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - All Foods</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{asset('assets/css/style.css')}}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="icon" href="{{asset('assets/favicon.png')}}">

</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        @if(Auth::user()->role === 'admin')

        <div class="admin-sidebar">
            <div class="p-4 border-bottom">
                <div class="d-flex align-items-center">
                    <div class="rounded p-2 me-2">
                        <img src="{{asset('assets/logo.png')}}" alt="allfreshfoods" class="img-fluid" style="width: 140px; height: 70px;">
                    </div>
                    {{-- <div>
                        <div class="fw-bold">All Foods</div>
                        <small class="text-muted">Admin Panel</small>
                    </div> --}}
                </div>
            </div>

            <div class="p-3">
                <h6 class="text-muted text-uppercase small mb-3">Main Navigation</h6>
                <nav class="nav flex-column">
                    <a class="nav-link {{request()->routeIs('dashboard') ? 'active' : ''}}" href="{{route('dashboard')}}">
                        <i class="bi bi-house"></i>
                        Overview
                    </a>
                    <a class="nav-link {{ request()->routeIs('vendors.index') ? 'active' : '' }}" href="{{ route('vendors.index') }}">
                        <i class="bi bi-shop"></i>
                        Vendors
                        <span class="badge bg-secondary ms-auto">{{ \App\Models\Vendor::count() }}</span>
                    </a>
                    <a class="nav-link {{ request()->routeIs('distributors.index') ? 'active' : '' }}" href="{{ route('distributors.index') }}">
                        <i class="bi bi-building"></i>
                        Distributors
                        <span class="badge bg-secondary ms-auto">{{ \App\Models\Distributor::count() }}</span>
                    </a>
                    <a class="nav-link {{request()->routeIs('dashboard.categories.index') ? 'active' : ''}}" href="{{route('dashboard.categories.index')}}">
                        <i class="bi bi-list"></i>
                        Categories
                    </a>
                    <a class="nav-link {{request()->routeIs('dashboard.products.index') ? 'active' : ''}}" href="{{route('dashboard.products.index')}}">
                        <i class="bi bi-box"></i>
                        Products
                    </a>
                    <a class="nav-link {{request()->routeIs('dashboard.cuisines.index') ? 'active' : ''}}" href="{{route('dashboard.cuisines.index')}}">
                        <i class="bi bi-basket"></i>
                        Cuisine
                    </a>
                    <a class="nav-link {{request()->routeIs('admin.orders') ? 'active' : ''}}" href="{{route('admin.orders')}}">
                        <i class="bi bi-cart"></i>
                        Orders
                        <span class="badge bg-secondary ms-auto">45</span>
                    </a>
                    <a class="nav-link {{request()->routeIs('admin.payouts.*') ? 'active' : ''}}" href="{{ route('admin.payouts.index') }}">
                        <i class="bi bi-cash-coin"></i>
                        Payouts
                    </a>
                    <a class="nav-link {{request()->routeIs('admin.users') ? 'active' : ''}}" href="{{route('admin.users')}}">
                        <i class="bi bi-people"></i>
                        User Management
                    </a>
                    <a class="nav-link {{request()->routeIs('admin.delivery') ? 'active' : ''}}" href="{{route('admin.delivery')}}">
                        <i class="bi bi-truck"></i>
                        Delivery Tracking
                        <span class="badge bg-secondary ms-auto">8</span>
                    </a>
                    <a class="nav-link {{request()->routeIs('delivery.amounts') ? 'active' : ''}}" href="{{route('delivery.amounts')}}">
                        <i class="bi bi-cash-coin"></i>
                        Delivery Amounts    
                    </a>
                    <a class="nav-link {{request()->routeIs('markup') ? 'active' : ''}}" href="{{route('markup')}}">
                        <i class="bi bi-cash-coin"></i>
                        Markup    
                    </a>
                </nav>
            </div>

            <div class="mt-auto p-3 border-top">
                <div class="dropdown">
                    <button class="btn btn-outline-secondary w-100 d-flex align-items-center" data-bs-toggle="dropdown">
                        <div class="rounded-circle bg-primary me-2" style="width: 32px; height: 32px;">
                            <i class="bi bi-person text-white d-flex align-items-center justify-content-center h-100"></i>
                        </div>
                        <div class="text-start flex-grow-1">
                            <div class="small fw-medium">{{Auth::user()->name}}</div>
                            <div class="small text-muted">{{Auth::user()->email}}</div>
                        </div>
                    </button>
                    <ul class="dropdown-menu w-100">
                        <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Profile Settings</a></li>
                        <li><a class="dropdown-item" href="#"><i class="bi bi-shield me-2"></i>Security</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                        <li>
                            <a class="dropdown-item"  onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="bi bi-box-arrow-right me-2"></i>Sign Out</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        @else

            <div class="admin-sidebar">
                <div class="p-4 border-bottom">
                    <div class="d-flex align-items-center">
                        <div class="rounded p-2 me-2">
                            {{-- <i class="bi bi-globe text-white"></i> --}}
                            <img src="{{asset('assets/logo.png')}}" alt="allfreshfoods" class="img-fluid">
                        </div>
                        {{-- <div>
                            <div class="fw-bold">All Foods</div>
                            <small class="text-muted">Admin Panel</small>
                        </div> --}}
                    </div>
                </div>

                <div class="p-3">
                    <h6 class="text-muted text-uppercase small mb-3">Main Navigation</h6>
                    <nav class="nav flex-column">
                        <a class="nav-link {{request()->routeIs('dashboard') ? 'active' : ''}}" href="{{route('dashboard')}}">
                            <i class="bi bi-house"></i>
                            Overview
                        </a>
                        <a class="nav-link {{request()->routeIs('dashboard.categories.index') ? 'active' : ''}}" href="{{route('dashboard.categories.index')}}">
                            <i class="bi bi-list"></i>
                            Categories
                        </a>
                        <a class="nav-link {{request()->routeIs('dashboard.products.index') ? 'active' : ''}}" href="{{route('dashboard.products.index')}}">
                            <i class="bi bi-box"></i>
                            Products
                        </a>
                        <a class="nav-link {{request()->routeIs('dashboard.cuisines.index') ? 'active' : ''}}" href="{{route('dashboard.cuisines.index')}}">
                            <i class="bi bi-basket"></i>
                            Cuisine
                        </a>
                        <a class="nav-link {{request()->routeIs('vendor.orders.*') ? 'active' : ''}}" href="{{ route('vendor.orders.index') }}">
                            <i class="bi bi-cart"></i>
                            My Orders
                        </a>
                        <a class="nav-link {{request()->routeIs('vendor.finance.*') ? 'active' : ''}}" href="{{ route('vendor.finance.index') }}">
                            <i class="bi bi-cash-coin"></i>
                            Finance & Withdrawals
                        </a>
                        <a class="nav-link {{request()->routeIs('admin.delivery') ? 'active' : ''}}" href="{{route('admin.delivery')}}">
                            <i class="bi bi-truck"></i>
                            Delivery Tracking
                            <span class="badge bg-secondary ms-auto">8</span>
                        </a>
                        {{-- <a class="nav-link" href="{{route('home')}}">
                            <i class="bi bi-globe"></i>
                            Front End
                            <span class="badge bg-secondary ms-auto">8</span>
                        </a> --}}
                    </nav>
                </div>

                <div class="mt-auto p-3 border-top">
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary w-100 d-flex align-items-center" data-bs-toggle="dropdown">
                            <div class="rounded-circle bg-primary me-2" style="width: 32px; height: 32px;">
                                <i class="bi bi-person text-white d-flex align-items-center justify-content-center h-100"></i>
                            </div>
                            <div class="text-start flex-grow-1">
                                <div class="small fw-medium">{{Auth::user()->name}}</div>
                                <div class="small text-muted">{{Auth::user()->email}}</div>
                            </div>
                        </button>
                        <ul class="dropdown-menu w-100">
                            <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Profile Settings</a></li>
                            <li><a class="dropdown-item" href="#"><i class="bi bi-shield me-2"></i>Security</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                            <li>
                                <a class="dropdown-item"  onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="bi bi-box-arrow-right me-2"></i>Sign Out</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        

        {{$slot}}
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{asset('assets/js/main.js')}}"></script>
    @stack('scripts')
</body>
</html>