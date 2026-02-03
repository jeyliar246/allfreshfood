<x-home-layout>
    <main class="container py-4">
         @include('layouts.bann')
        <!-- Page Header -->
        <div class="mb-4">
            {{-- <h1 class="fw-bold mb-2">Browse Products</h1> --}}
            <p class="text-muted mt-2">Discover authentic ingredients from around the world</p>
        </div>

        <div class="row">
            <aside class="col-lg-3 col-md-4 mb-4">
                <!-- Sidebar Filters -->
                <form action="{{ route('home.browse') }}" method="GET">
                    <div class="mb-3">
                        <label class="form-label">Search</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="text" name="search" class="form-control" placeholder="Search products..." value="{{ request('search') }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <select name="category" class="form-select" onchange="this.form.submit()">
                            <option value="">All</option>
                            @isset($categories)
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ (string)request('category') === (string)$cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            @endisset
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Cuisine</label>
                        <select name="cuisine" class="form-select" onchange="this.form.submit()">
                            <option value="">All</option>
                            @isset($cuisines)
                                @foreach($cuisines as $c)
                                    <option value="{{ $c->name }}" {{ request('cuisine') == $c->name ? 'selected' : '' }}>{{ $c->name }}</option>
                                @endforeach
                            @endisset
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Vendor</label>
                        <select name="vendor" class="form-select" onchange="this.form.submit()">
                            <option value="">All</option>
                            @isset($vendors)
                                @foreach($vendors as $v)
                                    <option value="{{ $v->id }}" {{ (string)request('vendor') === (string)$v->id ? 'selected' : '' }}>{{ $v->name }}</option>
                                @endforeach
                            @endisset
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Price Range</label>
                        <div class="input-group">
                            <span class="input-group-text">£</span>
                            <input type="number" step="0.01" min="0" name="min_price" class="form-control" placeholder="Min" value="{{ request('min_price') }}">
                            <span class="input-group-text">-</span>
                            <input type="number" step="0.01" min="0" name="max_price" class="form-control" placeholder="Max" value="{{ request('max_price') }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Weight (kg)</label>
                        <div class="input-group">
                            <input type="number" step="0.1" min="0" name="min_weight" class="form-control" placeholder="Min" value="{{ request('min_weight') }}">
                            <span class="input-group-text">-</span>
                            <input type="number" step="0.1" min="0" name="max_weight" class="form-control" placeholder="Max" value="{{ request('max_weight') }}">
                            <span class="input-group-text">kg</span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Dietary</label>
                        <div class="d-flex flex-column gap-1">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="halal" id="filter-halal" value="1" {{ request()->boolean('halal') ? 'checked' : '' }}>
                                <label class="form-check-label" for="filter-halal">Halal</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="vegan" id="filter-vegan" value="1" {{ request()->boolean('vegan') ? 'checked' : '' }}>
                                <label class="form-check-label" for="filter-vegan">Vegan</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="gluten_free" id="filter-gluten" value="1" {{ request()->boolean('gluten_free') ? 'checked' : '' }}>
                                <label class="form-check-label" for="filter-gluten">Gluten Free</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="organic" id="filter-organic" value="1" {{ request()->boolean('organic') ? 'checked' : '' }}>
                                <label class="form-check-label" for="filter-organic">Organic</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="non_GMO" id="filter-non-gmo" value="1" {{ request()->boolean('non_GMO') ? 'checked' : '' }}>
                                <label class="form-check-label" for="filter-non-gmo">Non-GMO</label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-sm">Apply</button>
                        <a href="{{ route('home.browse') }}" class="btn btn-outline-secondary btn-sm">Clear</a>
                    </div>

                    {{-- <div class="row mt-3">
                        <!-- Cuisine Filter -->
                        <div class="col-12 mb-3">
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('home.browse') }}" 
                                   class="btn btn-sm {{ !request('cuisine') ? 'btn-primary' : 'btn-outline-primary' }}">
                                    All
                                </a>
                                @foreach($cuisines as $cuisine)
                                    <button type="submit" 
                                            name="cuisine" 
                                            value="{{ $cuisine->name }}"
                                            class="btn btn-sm {{ request('cuisine') == $cuisine->name ? 'btn-primary' : 'btn-outline-primary' }}">
                                        {{ $cuisine->name }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div> --}}
                </form>
            </aside>

            <div class="col-lg-9 col-md-8">
                <div class="d-flex justify-content-end mb-3">
                    <form action="{{ route('home.browse') }}" method="GET" class="d-flex gap-2 align-items-center">
                        @foreach(request()->except('sort') as $k => $v)
                            <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                        @endforeach
                        <label class="me-2 text-muted">Sort</label>
                        <select name="sort" class="form-select form-select-sm" onchange="this.form.submit()" style="max-width: 220px;">
                            <option value="relevance" {{ request('sort') == 'relevance' ? 'selected' : '' }}>Relevance</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest</option>
                        </select>
                    </form>
                </div>

                @if($products->count() > 0)
                    <div class="row g-4" id="products-grid">
                        @foreach($products as $product)
                            @include('layouts.prod')
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-5">
                        {{ $products->withQueryString()->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-box-seam text-muted" style="font-size: 3rem;"></i>
                        <h4 class="mt-3">No products found</h4>
                        <p class="text-muted">We couldn't find any products matching your criteria.</p>
                        <a href="{{ route('home.browse') }}" class="btn btn-primary mt-3">Clear filters</a>
                    </div>
                @endif
            </div>
        </div>
    </main>

    @push('scripts')
    <script>
        function addToCart(productId) {
            fetch('{{ route("cart.add") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ product_id: productId, quantity: 1 })
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