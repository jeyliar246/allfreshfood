<section class="py-5">
            <div class="container">
                <div class="text-center mb-5">
                    <h2 class="fw-bold mb-3">Explore World Cuisines</h2>
                    <p class="text-muted">Discover authentic flavors from different cultures</p>
                </div>
                @if($popularCuisines->count() > 0)
                    <div class="row g-4">
                        @foreach($popularCuisines as $cuisine)
                            <div class="col-6 col-md-4 col-lg-3">
                                <a href="{{ route('home.browse') }}?cuisine={{ urlencode($cuisine->name) }}" class="text-decoration-none">
                                    <div class="card h-100 border-0 shadow-sm hover-lift">
                                        <div class="card-body text-center p-4">
                                            <div class="bg-light rounded-circle p-3 d-inline-block mb-3">
                                                <i class="bi bi-egg-fried text-warning" style="font-size: 2rem;"></i>
                                            </div>
                                            <h5 class="mb-1">{{ $cuisine->name }}</h5>
                                            <p class="text-muted small mb-0">{{ $cuisine->products_count }} Items</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-egg-fried text-muted" style="font-size: 3rem;"></i>
                        <p class="mt-3 text-muted">No cuisines available at the moment.</p>
                    </div>
                @endif
            </div>
        </section>