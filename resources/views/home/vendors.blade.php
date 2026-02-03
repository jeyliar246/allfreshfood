<x-home-layout>
    <main class="py-5">
         @include('layouts.bann')
        <div class="container">
            {{-- <h1 class="fw-bold text-center mb-5">Discover Stores</h1> --}}
            
            <!-- Search and Filter Section -->
            <div class="row mb-4 mt-4">
                {{-- <div class="col-md-10 mx-auto">
                    <form action="{{ route('home.vendors') }}" method="GET" class="mb-4">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control form-control-lg" 
                                   placeholder="Search vendors..." value="{{ request('search') }}">
                            <button class="btn btn-primary" type="submit">
                                <i class="bi bi-search"></i> Search
                            </button>
                        </div>
                    </form>
                </div> --}}
                
                {{-- <div class="col-12">
                    <div class="d-flex flex-wrap gap-2 mb-4">
                        <a href="{{ route('home.vendors') }}" 
                           class="btn btn-outline-primary {{ !request('cuisine') ? 'active' : '' }}">
                            All Cuisines
                        </a>
                        @foreach($cuisines as $cuisine)
                            <a href="{{ route('home.vendors', ['cuisine' => $cuisine->name]) }}" 
                               class="btn btn-outline-primary {{ request('cuisine') == $cuisine->name ? 'active' : '' }}">
                                {{ $cuisine->name }}
                            </a>
                        @endforeach
                    </div>
                </div> --}}
            </div>

            @if($vendors->count() > 0)
                <div class="row g-4">
                    @foreach($vendors as $vendor)
                        <div class="col-md-6 col-lg-4">
                            <div class="card vendor-card h-100 border-0 shadow">
                                <div class="position-relative">
                                    <a href="{{ route('vendor.shop', ['id' => $vendor->id]) }}" class="d-block">
                                        <img src="{{ $vendor->image ? asset('uploads/' . $vendor->image) : 'https://via.placeholder.com/400x200?text=' . urlencode($vendor->name) }}" 
                                             class="card-img-top" 
                                             alt="{{ $vendor->name }}" 
                                             style="height: 200px; object-fit: cover;">
                                    </a>
                                    @if($vendor->featured)
                                        <span class="badge bg-primary position-absolute top-0 start-0 m-2">Featured</span>
                                    @endif
                                    <button class="btn btn-light btn-sm position-absolute top-0 end-0 m-2">
                                        <i class="bi bi-heart"></i>
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="card-title mb-0">
                                            <a href="{{ route('vendor.shop', ['id' => $vendor->id]) }}" class="text-decoration-none text-dark">{{ $vendor->name }}</a>
                                            {{-- {{ $vendor->name }} --}}
                                        </h5>
                                        <div class="d-flex align-items-center">
                                            @php
                                                $rating = $vendor->reviews_avg_rating ?? 0;
                                                $reviewCount = $vendor->reviews_count ?? 0;
                                            @endphp
                                            @if($reviewCount > 0)
                                                <i class="bi bi-star-fill text-warning me-1"></i>
                                                <span class="fw-semibold">{{ number_format($rating, 1) }}</span>
                                                <small class="text-muted ms-1">({{ $reviewCount }})</small>
                                            @else
                                                <small class="text-muted">No reviews</small>
                                            @endif
                                        </div>
                                    </div>
                                    <p class="card-text text-muted small mb-2">
                                        {{ Str::limit($vendor->description, 100) }}
                                    </p>
                                    @if($vendor->cuisine)
                                        <span class="badge bg-light text-dark mb-3">{{ $vendor->cuisine }}</span>
                                    @endif
                                    <div class="d-flex justify-content-between align-items-center small text-muted">
                                        <div>
                                            <i class="bi bi-geo-alt-fill text-primary me-1"></i>
                                            {{ $vendor->location ?? 'N/A' }}
                                        </div>
                                        <div class="d-flex align-items-center gap-2">
                                            @if($vendor->delivery_fee > 0)
                                                <i class="bi bi-truck me-1"></i>
                                                £{{ number_format($vendor->delivery_fee, 2) }}
                                            @else
                                                <span class="text-success">Free delivery</span>
                                            @endif
                                            <a href="{{ route('vendor.shop', ['id' => $vendor->id]) }}" class="btn btn-sm btn-primary ms-2">View Shop</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-5">
                    {{ $vendors->withQueryString()->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-shop text-muted" style="font-size: 3rem;"></i>
                    <h4 class="mt-3">No vendors found</h4>
                    <p class="text-muted">We couldn't find any vendors matching your criteria.</p>
                    <a href="{{ route('home.vendors') }}" class="btn btn-primary mt-3">Clear filters</a>
                </div>
            @endif
        </div>
    </main>

    @push('scripts')
    <script>
        // Add any JavaScript for vendor interactions here
        document.addEventListener('DOMContentLoaded', function() {
            // Add to favorites functionality
            document.querySelectorAll('.btn-favorite').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const vendorId = this.dataset.vendorId;
                    // Implement favorite functionality here
                    console.log('Toggle favorite for vendor:', vendorId);
                    this.classList.toggle('text-danger');
                });
            });
        });
    </script>
    @endpush
</x-home-layout>