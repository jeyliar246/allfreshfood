<x-home-layout>
    <main class="container py-4">
        <!-- Page Header -->
        <div class="d-flex align-items-center mb-4">
            <button class="btn btn-outline-secondary me-3" onclick="window.history.back()">
                <i class="bi bi-arrow-left"></i>
            </button>
            <div>
                <h1 class="fw-bold mb-1">Shopping Cart</h1>
                <p class="text-muted mb-0"><span>  {{ Cart::count() }} </span> items in your cart</p>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            <!-- Cart Items -->
            <div class="col-lg-8">
                @if($count > 0)
                    <div id="cart-items">
                        @foreach($cartItems as $item)
                            <div class="card mb-3" data-row-id="{{ $item->rowId }}">
                                <div class="card-body d-flex align-items-center">
                                    <img src="{{ $item->options->image ?? 'https://via.placeholder.com/60' }}" 
                                         alt="{{ $item->name }}" 
                                         class="rounded me-3" 
                                         width="60" height="60" 
                                         style="object-fit: cover;">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">{{ $item->name }}</h6>
                                        <div class="text-muted small">{{ optional($item->model->vendor ?? null)->name }}</div>
                                        <div class="d-flex align-items-center mt-2">
                                            <span class="fw-bold">£{{ number_format($item->price, 2) }}</span>
                                            @php
                                                $product = \App\Models\Product::find($item->id);
                                            @endphp
                                            @if($product && $product->stock <= 5 && $product->stock > 0)
                                                <span class="badge bg-warning text-dark ms-2 small">Only {{ $product->stock }} left</span>
                                            @elseif($product && $product->stock <= 0)
                                                <span class="badge bg-danger ms-2 small">Out of stock</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center gap-3">
                                        {{-- Quantity Update Form --}}
                                        <form action="{{ route('cart.update') }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="rowId" value="{{ $item->rowId }}">
                                            <div class="input-group" style="width: 140px;">
                                                <button type="submit" name="action" value="decrease" class="btn btn-outline-secondary btn-sm" onclick="this.form.querySelector('input[name=quantity]').value = {{ max(1, $item->qty - 1) }}; this.form.submit(); return false;">-</button>
                                                <input type="number" name="quantity" value="{{ $item->qty }}" class="form-control text-center" min="1" max="{{ $item->model->stock ?? 999 }}" readonly>
                                                <button type="submit" name="action" value="increase" class="btn btn-outline-secondary btn-sm" onclick="this.form.querySelector('input[name=quantity]').value = {{ $item->qty + 1 }}; this.form.submit(); return false;">+</button>
                                            </div>
                                        </form>
                                        
                                        {{-- Remove Form --}}
                                        <form action="{{ route('cart.remove') }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to remove this item from your cart?');">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="rowId" value="{{ $item->rowId }}">
                                            <button type="submit" class="btn btn-outline-danger btn-sm btn-remove" title="Remove item">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Stock Warnings -->
                    @if($hasOutOfStock)
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Some items in your cart are out of stock. Please remove them before checkout.
                        </div>
                    @endif

                    @if($hasLowStock && !$hasOutOfStock)
                        <div class="alert alert-warning">
                            <i class="bi bi-info-circle me-2"></i>
                            Some items in your cart have limited stock remaining.
                        </div>
                    @endif
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-cart-x display-1 text-muted mb-4"></i>
                        <h3 class="mb-3">Your cart is empty</h3>
                        <p class="text-muted mb-4">Looks like you haven't added any items to your cart yet. Start shopping to fill it up!</p>
                        <a href="{{ route('home.browse') }}" class="btn btn-primary btn-lg">Start Shopping</a>
                    </div>
                @endif
            </div>

            <!-- Order Summary -->
            <div class="col-lg-4">
                <div class="card sticky-top" style="top: 1rem;">
                    <div class="card-header">
                        <h6 class="mb-0">Order Summary</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal</span>
                            <span id="subtotal">£{{ $subtotal }}</span>
                        </div>
                        {{-- <div class="d-flex justify-content-between mb-2">
                            <span>Tax</span>
                            <span id="tax">£{{ $tax }}</span>
                        </div> --}}
                        <hr>
                        <div class="d-flex justify-content-between fw-bold fs-5 mb-4">
                            <span>Total</span>
                            <span id="cart-total">£{{ $total }}</span>
                        </div>

                        @if($count > 0)
                            @auth
                                <a href="{{ route('checkout') }}" 
                                   class="btn btn-primary w-100 btn-lg mb-3" 
                                   id="checkout-btn">
                                    <i class="bi bi-credit-card me-2"></i>Proceed to Checkout
                                </a>
                            @endauth
                            @guest
                                <button type="button" class="btn btn-primary w-100 btn-lg mb-3" data-bs-toggle="modal" data-bs-target="#guestCheckoutModal">
                                    <i class="bi bi-credit-card me-2"></i>Proceed to Checkout
                                </button>
                            @endguest
                        @else
                            <button class="btn btn-secondary w-100 btn-lg mb-3 disabled" disabled>
                                <i class="bi bi-credit-card me-2"></i>Proceed to Checkout
                            </button>
                        @endif

                        <a href="{{ route('home.browse') }}" class="btn btn-outline-primary w-100 mb-3">
                            <i class="bi bi-arrow-left me-2"></i>Continue Shopping
                        </a>
                        
                        {{-- Clear Cart Form --}}
                        <form action="{{ route('cart.clear') }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger w-100" 
                                    {{ $count > 0 ? '' : 'disabled' }}
                                    onclick="return confirm('Are you sure you want to clear your entire cart? This action cannot be undone.');">
                                <i class="bi bi-trash me-2"></i>Clear Cart
                            </button>
                        </form>

                        <!-- Security Badge -->
                        <div class="mt-3 text-center">
                            <small class="text-muted">
                                <i class="bi bi-shield-check me-1"></i>
                                Secure checkout guaranteed
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    @guest
    <!-- Guest/Login Choice Modal -->
    <div class="modal fade" id="guestCheckoutModal" tabindex="-1" aria-labelledby="guestCheckoutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="guestCheckoutModalLabel">Checkout Options</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-3">Choose how you'd like to complete your order:</p>
                    <div class="d-grid gap-2">
                        <a href="{{ route('checkout') }}" class="btn btn-primary">
                            Continue as Guest
                        </a>
                        <a href="{{ route('login') }}?redirect={{ urlencode(route('checkout')) }}" class="btn btn-outline-secondary">
                            Login to Checkout
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endguest
</x-home-layout>