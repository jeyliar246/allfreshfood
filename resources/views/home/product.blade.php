<x-home-layout>
    <main class="container py-4">
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        @if ($product->image)
                            <img src="{{ asset('uploads/' . $product->image) }}" alt="{{ $product->name }}"
                                class="img-fluid rounded" style="width: 100%; max-height: 420px; object-fit: contain;">
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center"
                                style="height: 300px; border-radius: .5rem;">
                                <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h1 class="h3 fw-bold mb-2">{{ $product->name }}</h1>
                        <div class="text-muted mb-3 d-flex align-items-center">
                            <i class="bi bi-shop me-1"></i>
                            <span>{{ $product->vendor->name ?? 'N/A' }}</span>
                            @if ($product->cuisine)
                                <span class="badge bg-light text-dark ms-2">{{ $product->cuisine }}</span>
                            @endif
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <div class="h4 fw-bold text-primary mb-0">£{{ number_format($product->price, 2) }}</div>
                            @if ($product->original_price > $product->price)
                                <div class="ms-2 text-danger text-decoration-line-through">
                                    £{{ number_format($product->original_price, 2) }}</div>
                            @endif
                        </div>
                        <p class="text-muted">{{ $product->description ?? 'No description available.' }}</p>

                        <div class="mb-3">
                            @if (($product->stock ?? 0) > 0)
                                <span class="badge bg-success bg-opacity-10 text-success">In Stock</span>
                            @else
                                <span class="badge bg-danger bg-opacity-10 text-danger">Out of Stock</span>
                            @endif
                        </div>

                         

                        <div class="mt-2 d-flex flex-wrap gap-1 align-items-center">
                            {{-- Dietary / attribute badges --}}
                            @if (!empty($product->halal) && $product->halal)
                                <span class="badge bg-success">Halal</span>
                            @endif

                            @if (!empty($product->vegan) && $product->vegan)
                                <span class="badge bg-success">Vegan</span>
                            @endif
                            @if (!empty($product->non_GMO) && $product->non_GMO)
                                <span class="badge bg-info text-white">Non‑GMO</span>
                            @endif

                            @if (!empty($product->gluten_free) && $product->gluten_free)
                                <span class="badge bg-info text-white">Gluten Free</span>
                            @endif

                            {{-- Cuisine badge (if present) --}}
                            @if (!empty($product->cuisine))
                                <span class="badge bg-light text-dark">{{ $product->cuisine }}</span>
                            @endif
                        </div>
                        <div class="mt-2 d-flex flex-wrap gap-1 align-items-center">
                            {{-- Dietary / attribute badges (driven from product table fields) --}}
                            @if (!empty($product->halal) && $product->halal)
                                <span class="badge bg-success text-white" title="Halal product"
                                    aria-label="Halal">Halal</span>
                            @endif

                            @if (!empty($product->vegan) && $product->vegan)
                                <span class="badge bg-success text-white" title="Vegan product"
                                    aria-label="Vegan">Vegan</span>
                            @endif

                            @if (!empty($product->non_GMO) && $product->non_GMO)
                                <span class="badge bg-info text-dark" title="Non-GMO product"
                                    aria-label="Non-GMO">Non‑GMO</span>
                            @endif

                            @if (!empty($product->gluten_free) && $product->gluten_free)
                                <span class="badge bg-warning text-dark" title="Gluten free"
                                    aria-label="Gluten Free">Gluten Free</span>
                            @endif

                            
                        </div>

                        {{-- Add to Cart button below price --}}
                            <div class="mt-3">
                                <form action="{{ route('cart.add') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <button type="submit" class="btn btn-sm btn-primary w-100" {{ $product->stock <= 0 ? 'disabled' : '' }}>
                                        <i class="bi bi-cart me-1"></i>
                                        Add To Cart
                                    </button>
                                </form>
                            </div>

                    </div>
                </div>
            </div>
        </div>

        {{-- Vendor Details --}}
        @if ($product->vendor)
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-body d-flex align-items-center">
                    <img src="{{ $product->vendor->image ? asset('uploads/' . $product->vendor->image) : 'https://via.placeholder.com/64x64?text=' . urlencode($product->vendor->name ?? 'Vendor') }}"
                        class="rounded me-3" alt="{{ $product->vendor->name }}" width="64" height="64"
                        style="object-fit: cover;">
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center">
                            <h5 class="mb-0 me-2">{{ $product->vendor->name }}</h5>
                            @if ($product->vendor->cuisine)
                                <span class="badge bg-light text-dark">{{ $product->vendor->cuisine }}</span>
                            @endif
                        </div>
                        <div class="text-muted small mt-1">
                            <i class="bi bi-geo-alt me-1"></i> {{ $product->vendor->location ?? 'N/A' }}
                            @if (!empty($product->vendor->delivery_time))
                                <span class="ms-3"><i
                                        class="bi bi-clock me-1"></i>{{ $product->vendor->delivery_time }}</span>
                            @endif
                            @if (isset($product->vendor->delivery_fee))
                                <span class="ms-3"><i
                                        class="bi bi-truck me-1"></i>{{ $product->vendor->delivery_fee > 0 ? '£' . number_format($product->vendor->delivery_fee, 2) : 'Free delivery' }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Related Products --}}
        @if (!empty($relatedProducts) && $relatedProducts->count() > 0)
            <section class="mt-4">
                <h4 class="fw-bold mb-3">Related Products</h4>
                <div class="row g-3">
                    @foreach ($relatedProducts as $rel)
                        <div class="col-6 col-md-3">
                            <div class="card h-100 border-0 shadow-sm">
                                <a href="{{ route('home.product', $rel) }}" class="text-decoration-none">
                                    <img src="{{ $rel->image ? asset('uploads/' . $rel->image) : 'https://via.placeholder.com/300x200?text=' . urlencode($rel->name) }}"
                                        class="card-img-top" alt="{{ $rel->name }}"
                                        style="height: 140px; object-fit: cover;">
                                </a>
                                <div class="card-body p-2">
                                    <a href="{{ route('home.product', $rel) }}" class="text-decoration-none text-dark">
                                        <div class="small fw-semibold text-truncate">{{ $rel->name }}</div>
                                    </a>
                                    <div class="text-primary small fw-bold">£{{ number_format($rel->price, 2) }}</div>
                                    <div class="text-muted small">{{ $rel->vendor->name ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif
    </main>

    @push('scripts')
        <script>
            function addToCart(productId) {
                fetch('{{ route('cart.add') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            product_id: productId,
                            quantity: 1
                        })
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            const cartCount = document.getElementById('cart-count');
                            if (cartCount) cartCount.textContent = data.cart_count;
                        } else {
                            alert(data.message || 'Failed to add to cart');
                        }
                    })
                    .catch(() => alert('Network error while adding to cart'));
            }
        </script>
    @endpush
</x-home-layout>
