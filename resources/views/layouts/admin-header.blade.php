<!-- Header -->
<div class="admin-header">
    <div class="container-fluid">
        <div class="d-flex align-items-center">
            <button class="btn btn-outline-secondary me-3 d-lg-none sidebar-toggle">
                <i class="bi bi-list"></i>
            </button>
            <div class="flex-grow-1">
                <div class="input-group" style="max-width: 400px;">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control" placeholder="Search vendors, products, orders...">
                </div>
            </div>
            <button class="btn btn-outline-secondary">
                <i class="bi bi-bell"></i>
            </button>

            <!-- User Profile Dropdown -->
            <div class="ms-auto dropdown user-dropdown">
                <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
                    @if(Auth::check())
                        @if(Auth::user()->profile_image)
                            <img src="{{ Auth::user()->profile_image }}" alt="{{ Auth::user()->name }}" width="32" height="32" class="rounded-circle me-2">
                        @else
                            <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                            </div>
                        @endif
                        {{-- <span class="d-none d-md-inline-block">{{ Auth::user()->name }}</span> --}}
                    @else
                        <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px; font-size: 0.8rem;">
                            GU
                        </div>
                        <span class="d-none d-md-inline-block">Guest</span>
                    @endif
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="dropdownUser">
                    @if(Auth::check())
                        <li><a class="dropdown-item" href="#">Profile</a></li>
                        <li><a class="dropdown-item" href="#">Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                        
                        <li>
                            <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Sign Out</a>
                            
                        </li>
                    @else
                        <li><a class="dropdown-item" href="{{ route('login') }}">Sign In</a></li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>
