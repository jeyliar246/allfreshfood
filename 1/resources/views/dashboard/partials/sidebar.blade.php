<!-- Sidebar -->
<nav id="sidebar" class="bg-dark text-white" style="width: 250px; min-height: 100vh;">
    <div class="p-3">
        <a href="{{ route('dashboard') }}" class="d-flex align-items-center mb-4 text-white text-decoration-none">
            <span class="fs-4">{{ config('app.name', 'GLB API') }}</span>
        </a>
        <hr class="my-4">
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="{{ route('dashboard') }}" class="nav-link text-white {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2 me-2"></i>
                    Dashboard
                </a>
            </li>
            @can('admin')
                <li>
                    <a href="{{ route('admin.users') }}" class="nav-link text-white {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                        <i class="bi bi-people me-2"></i>
                        Users
                    </a>
                </li>
                <li>
                    <a href="{{ route('vendors.index') }}" class="nav-link text-white {{ request()->routeIs('vendors.*') ? 'active' : '' }}">
                        <i class="bi bi-shop me-2"></i>
                        Vendors
                    </a>
                </li>
                <li>
                    <a href="{{ route('distributors.index') }}" class="nav-link text-white {{ request()->routeIs('distributors.*') ? 'active' : '' }}">
                        <i class="bi bi-box-seam me-2"></i>
                        Distributors
                    </a>
                </li>
                <li>
                    <a href="{{ route('dashboard.products.index') }}" class="nav-link text-white {{ request()->routeIs('dashboard.products.*') ? 'active' : '' }}">
                        <i class="bi bi-box me-2"></i>
                        Products
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.orders') }}" class="nav-link text-white {{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
                        <i class="bi bi-cart me-2"></i>
                        Orders
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.delivery') }}" class="nav-link text-white {{ request()->routeIs('admin.delivery*') ? 'active' : '' }}">
                        <i class="bi bi-truck me-2"></i>
                        Delivery
                    </a>
                </li>
            @endcan
        </ul>
    </div>
    
    <div class="position-absolute bottom-0 start-0 p-3 w-100">
        <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="{{ Auth::user()->profile_image ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name) }}" alt="" width="32" height="32" class="rounded-circle me-2">
                <strong>{{ Auth::user()->name }}</strong>
            </a>
            <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
                <li><a class="dropdown-item" href="#">Profile</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item">Sign out</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>
