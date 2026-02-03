<x-home-layout>
    <main class="container py-5">
        <div class="text-center mb-4">
            <h1 class="fw-bold">Become a Vendor</h1>
            <p class="text-muted">Grow your business by selling authentic products to customers across the UK.</p>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-semibold mb-3">Why Sell on All Foods?</h5>
                        <ul>
                            <li>Reach engaged customers who love global cuisines</li>
                            <li>Simple onboarding and vendor tools</li>
                            <li>Secure payments and transparent payouts</li>
                        </ul>
                        @auth
                            @if(Auth::user()->role === 'vendor')
                                <a href="{{ route('dashboard') }}" class="btn btn-primary">Go to Vendor Dashboard</a>
                            @else
                                <a href="{{ route('vendors.create') }}" class="btn btn-primary">Apply as Vendor</a>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary me-2">Sign In</a>
                            <a href="{{ route('home.vending') }}" class="btn btn-outline-primary">Create Account</a>
                        @endauth
                    </div>
                </div>
                <div class="text-muted small text-center">Vendor applications may require verification and approval.</div>
            </div>
        </div>
    </main>
</x-home-layout>
