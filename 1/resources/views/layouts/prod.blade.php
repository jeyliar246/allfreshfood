<div class="col-6 col-md-4 col-lg-3">
    <div class="card h-100 product-card border-0 shadow-sm">
        <div class="position-relative">
            <a href="{{ route('home.product', $product) }}" class="text-decoration-none">
                <img src="{{ $product->image ? asset('uploads/' . $product->image) : 'https://via.placeholder.com/300x200?text=' . urlencode($product->name) }}" 
                     class="card-img-top" 
                     alt="{{ $product->name }}" 
                     style="height: 150px; object-fit: contain; background: #fff;">
            </a>
            @if($product->original_price > $product->price)
                @php
                    $discount = (($product->original_price - $product->price) / $product->original_price) * 100;
                @endphp
                <span class="badge bg-danger position-absolute top-0 start-0 m-2">-{{ round($discount) }}%</span>
            @endif
        </div>
        <div class="card-body p-3">
            <h6 class="card-title mb-1">
                <a href="{{ route('home.product', $product) }}" class="text-decoration-none text-dark">
                    {{ \Illuminate\Support\Str::limit($product->name, 50) }}
                </a>
            </h6>
            <p class="text-muted small mb-2">{{ $product->vendor->name ?? 'N/A' }}</p>
            
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="fw-bold text-primary">£{{ number_format($product->price, 2) }}</span>
                    @if($product->original_price > $product->price)
                        <small class="text-muted text-decoration-line-through ms-1">
                            £{{ number_format($product->original_price, 2) }}
                        </small>
                    @endif
                </div>
                @if($product->stock > 0)
                    <span class="badge bg-success bg-opacity-10 text-success small">In Stock</span>
                @else
                    <span class="badge bg-danger bg-opacity-10 text-danger small">Out of Stock</span>
                @endif
            </div>

            {{-- Add to Cart button below price --}}
            <div class="mt-3">
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