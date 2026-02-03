<x-home-layout>
    <main class="py-4">
        <div class="container">
            <!-- Vendor Header -->
            <div class="card mb-4 border-0 shadow-sm overflow-hidden">
                <div class="position-relative" style="height: 220px;">
                    <img src="{{ $vendor->cover_image ? asset('uploads/' . $vendor->cover_image) : 'https://via.placeholder.com/1200x220?text=' . urlencode($vendor->name) }}"
                         alt="{{ $vendor->name }}"
                         class="w-100 h-100"
                         style="object-fit: cover;">
                    <div class="position-absolute bottom-0 start-0 p-3 w-100" style="background: linear-gradient(180deg, rgba(0,0,0,0) 0%, rgba(0,0,0,.6) 100%);">
                        <div class="d-flex align-items-end justify-content-between">
                            <div class="text-white">
                                <h2 class="mb-1">{{ $vendor->name }}</h2>
                                <div class="small opacity-75">
                                    <i class="bi bi-geo-alt-fill me-1"></i>{{ $vendor->location ?? 'Online' }}
                                    @if(!empty($vendor->cuisine))
                                        <span class="ms-3"><i class="bi bi-tag me-1"></i>{{ $vendor->cuisine }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @if(!empty($vendor->description))
                <div class="card-body">
                    <p class="mb-0 text-muted">{{ $vendor->description }}</p>
                </div>
                @endif
            </div>

            <!-- Search within vendor -->
            {{-- <form action="" method="GET" class="mb-4">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search in {{ $vendor->name }}..." value="{{ request('search') }}">
                    <button class="btn btn-primary"><i class="bi bi-search"></i></button>
                </div>
            </form> --}}

            <!-- Products Grid -->
            @if($products->count())
                <div class="row g-4">
                    @foreach($products as $product)
                        <div class="col-sm-6 col-md-4 col-lg-3">
                            <div class="card h-100 border-0 shadow-sm">
                                <a href="{{route('home.product', $product)}}" class="text-decoration-none text-dark">
                                    <img src="{{ $product->image ? asset('uploads/' . $product->image) : 'https://via.placeholder.com/400x250?text=' . urlencode($product->name) }}"
                                         alt="{{ $product->name }}"
                                         class="card-img-top"
                                         style="height: 150px; object-fit: contain; object-position: center; background: #fff;">
                                </a>
                                <div class="card-body d-flex flex-column">
                                    <a href="{{route('home.product', $product)}}" class="text-decoration-none text-dark">
                                        <h6 class="mb-1">{{ $product->name }}</h6>
                                    </a>
                                    <div class="text-muted small mb-2">{{ Str::limit($product->description, 60) }}</div>
                                    <div class="mt-auto">
                                        <div class="fw-bold mb-2">£{{ number_format($product->pprice, 2) }}</div>
                                        <form action="{{ route('cart.add') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <button type="submit" class="btn btn-sm btn-primary w-100" {{ $product->stock <= 0 ? 'disabled' : '' }}>
                                                <i class="bi bi-cart me-1"></i>
                                                Add
                                            </button>
                                        </form>
                                    </div>
                                    <div class="mt-2 d-flex flex-wrap gap-1 align-items-center">
                                        {{-- Dietary / attribute badges --}}
                                        @if(!empty($product->halal) && $product->halal)
                                            <span class="badge bg-success">Halal</span>
                                        @endif

                                        @if(!empty($product->vegan) && $product->vegan)
                                            <span class="badge bg-success">Vegan</span>
                                        @endif

                                        @if(!empty($product->non_GMO) && $product->non_GMO)
                                            <span class="badge bg-info text-white">Non‑GMO</span>
                                        @endif

                                        @if(!empty($product->gluten_free) && $product->gluten_free)
                                            <span class="badge bg-info text-white">Gluten Free</span>
                                        @endif

                                        {{-- Cuisine badge (if present) --}}
                                        @if(!empty($product->cuisine))
                                            <span class="badge bg-light text-dark">{{ $product->cuisine }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4">
                    {{ $products->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-box text-muted" style="font-size: 3rem;"></i>
                    <h4 class="mt-3">No products found</h4>
                    <p class="text-muted">Try clearing your search or check back later.</p>
                </div>
            @endif
        </div>
    </main>
</x-home-layout>
