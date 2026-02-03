<!-- Top Navigation -->
<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
    <div class="container-fluid">
        <button class="btn btn-link text-dark d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
            <i class="bi bi-list"></i>
        </button>
        
        <div class="d-flex align-items-center ms-auto">
            <div class="dropdown">
                <a class="text-dark text-decoration-none dropdown-toggle" href="#" role="button" id="notificationsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-bell fs-5"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        3
                        <span class="visually-hidden">unread notifications</span>
                    </span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationsDropdown">
                    <li><h6 class="dropdown-header">Notifications</h6></li>
                    <li><a class="dropdown-item" href="#">New order received</a></li>
                    <li><a class="dropdown-item" href="#">New user registered</a></li>
                    <li><a class="dropdown-item" href="#">System update available</a></li>
                </ul>
            </div>
            
            <div class="dropdown ms-3">
                <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="{{ Auth::user()->profile_image ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name) }}" alt="" width="32" height="32" class="rounded-circle me-2">
                    <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                    <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i> Profile</a></li>
                    <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i> Settings</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item"><i class="bi bi-box-arrow-right me-2"></i> Sign out</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<!-- Mobile Sidebar -->
<div class="collapse d-md-none" id="sidebarMenu">
    <div class="bg-dark text-white p-3">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a href="{{ route('dashboard') }}" class="nav-link text-white {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2 me-2"></i>
                    Dashboard
                </a>
            </li>
            @can('admin')
                <li class="nav-item">
                    <a href="{{ route('admin.users') }}" class="nav-link text-white {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                        <i class="bi bi-people me-2"></i>
                        Users
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('vendors.index') }}" class="nav-link text-white {{ request()->routeIs('vendors.*') ? 'active' : '' }}">
                        <i class="bi bi-shop me-2"></i>
                        Vendors
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('distributors.index') }}" class="nav-link text-white {{ request()->routeIs('distributors.*') ? 'active' : '' }}">
                        <i class="bi bi-box-seam me-2"></i>
                        Distributors
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('dashboard.products.index') }}" class="nav-link text-white {{ request()->routeIs('dashboard.products.*') ? 'active' : '' }}">
                        <i class="bi bi-box me-2"></i>
                        Products
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.orders') }}" class="nav-link text-white {{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
                        <i class="bi bi-cart me-2"></i>
                        Orders
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.delivery') }}" class="nav-link text-white {{ request()->routeIs('admin.delivery*') ? 'active' : '' }}">
                        <i class="bi bi-truck me-2"></i>
                        Delivery
                    </a>
                </li>
            @endcan
        </ul>
    </div>
</div>
