<x-home-layout>
    <main class="container py-4">
        <!-- Page Header -->
        <div class="d-flex align-items-center mb-4">
            <a href="{{ route('home.cart') }}" class="btn btn-outline-secondary me-3">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h1 class="fw-bold mb-1">Checkout</h1>
                <p class="text-muted mb-0">Review your order and complete your purchase</p>
            </div>
        </div>

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            <!-- Checkout Form -->
            <div class="col-lg-8">
                <form id="checkout-form" method="post" action="{{ route('processCheckout') }}">
                    @csrf
                    
                    <!-- Delivery Information -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-geo-alt me-2"></i>Delivery Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label d-block">Fulfillment *</label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="fulfillment_method" id="fulfillment_delivery" value="delivery" {{ old('fulfillment_method', 'delivery') === 'delivery' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="fulfillment_delivery">Delivery</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="fulfillment_method" id="fulfillment_pickup" value="pickup" {{ old('fulfillment_method') === 'pickup' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="fulfillment_pickup">Self Pickup</label>
                                </div>
                                @error('fulfillment_method')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- These fields only show for delivery -->
                            <div id="delivery_fields" style="display: {{ old('fulfillment_method') === 'pickup' ? 'none' : 'block' }};">
                                <div class="mb-3">
                                    <label for="delivery_address" class="form-label">Delivery Address *</label>
                                    <textarea name="delivery_address" id="delivery_address" class="form-control @error('delivery_address') is-invalid @enderror" rows="3" placeholder="Enter your full delivery address">{{ old('delivery_address') }}</textarea>
                                    @error('delivery_address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="distance_km" class="form-label">Distance to delivery (Km) *</label>
                                    <input type="number" step="0.1" min="0" name="distance_km" id="distance_km" class="form-control @error('distance_km') is-invalid @enderror" placeholder="e.g. 3.5" value="{{ old('distance_km', 0) }}">
                                    @error('distance_km')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted d-block mt-1">Delivery fee is calculated as £1.20 base + £0.30 per Km + £0.60 per store/vendor.</small>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone Number *</label>
                                <input type="tel" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" placeholder="Enter your phone number" value="{{ old('phone') }}" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-credit-card me-2"></i>Payment Method</h5>
                            <small class="text-muted d-block">For Credit/Debit Card, you'll be securely redirected to Stripe after submission.</small>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="radio" name="payment_method" id="card" value="card" checked>
                                        <label class="form-check-label" for="card">
                                            <i class="bi bi-credit-card me-2"></i>Credit/Debit Card
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="radio" name="payment_method" id="cash" value="cash">
                                        <label class="form-check-label" for="cash">
                                            <i class="bi bi-cash me-2"></i>Cash on Delivery
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="radio" name="payment_method" id="bank_transfer" value="bank_transfer">
                                        <label class="form-check-label" for="bank_transfer">
                                            <i class="bi bi-bank me-2"></i>Bank Transfer
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="radio" name="payment_method" id="wallet" value="wallet">
                                        <label class="form-check-label" for="wallet">
                                            <i class="bi bi-wallet2 me-2"></i>Digital Wallet
                                        </label>
                                    </div>
                                </div>
                            </div>
                            @error('payment_method')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Order Review -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-bag-check me-2"></i>Order Review</h5>
                        </div>
                        <div class="card-body">
                            @foreach($vendors as $vendorId => $items)
                                @php
                                    $vendor = \App\Models\Vendor::find($vendorId);
                                    $vendorSubtotal = $items->sum(function ($item) {
                                        return $item->price * $item->qty;
                                    });
                                @endphp
                                
                                <div class="vendor-order mb-4 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                                    <h6 class="text-primary mb-3">
                                        <i class="bi bi-shop me-2"></i>{{ $vendor ? $vendor->name : 'Unknown Vendor' }}
                                    </h6>
                                    
                                    @foreach($items as $item)
                                        <div class="d-flex align-items-center mb-2">
                                            <img src="{{ $item->options->image ?? 'https://via.placeholder.com/50' }}" 
                                                 alt="{{ $item->name }}" 
                                                 class="rounded me-3" 
                                                 width="50" height="50" 
                                                 style="object-fit: cover;">
                                            <div class="flex-grow-1">
                                                <div class="fw-semibold">{{ $item->name }}</div>
                                                <div class="text-muted small">Quantity: {{ $item->qty }}</div>
                                            </div>
                                            <div class="text-end">
                                                <div class="fw-bold">£{{ number_format($item->price * $item->qty, 2) }}</div>
                                                <div class="text-muted small">£{{ number_format($item->price, 2) }} each</div>
                                            </div>
                                        </div>
                                    @endforeach
                                    
                                    <div class="mt-3 pt-2 border-top">
                                        <div class="d-flex justify-content-between">
                                            <span>Subtotal:</span>
                                            <span>£{{ number_format($vendorSubtotal, 2) }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between fw-bold">
                                            <span>Vendor Total:</span>
                                            <span>£{{ number_format($vendorSubtotal, 2) }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </form>
            </div>

            <!-- Order Summary -->
            <div class="col-lg-4">
                <div class="card sticky-top" style="top: 1rem;">
                    <div class="card-header">
                        <h6 class="mb-0">Order Summary</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Items ({{ $count }})</span>
                            <span>£<span id="subtotal-amount">{{ $subtotal }}</span></span>
                        </div>
                        
                        @php
                            $vendorCount = count($vendors);
                            $distanceKm = floatval(old('distance_km', 0));
                            $isPickup = old('fulfillment_method') === 'pickup';
                            $totalDeliveryFee = $isPickup ? 0.00 : round(1.20 + 0.30 * max(0, $distanceKm) + 0.60 * max(0, $vendorCount), 2);
                        @endphp
                        
                        <!-- Delivery fee row - only show for delivery -->
                        <div class="d-flex justify-content-between mb-2" id="delivery_fee_row" style="display: {{ old('fulfillment_method') === 'pickup' ? 'none' : 'flex' }};">
                            <span>Delivery</span>
                            <span>£<span id="delivery-amount">{{ number_format($totalDeliveryFee, 2) }}</span></span>
                        </div>
                        
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tax</span>
                            <span>£<span id="tax-amount">{{ $tax }}</span></span>
                        </div>
                        
                        <hr>
                        
                        <div class="d-flex justify-content-between fw-bold fs-5 mb-4">
                            <span>Total</span>
                            <span>£<span id="total-amount">{{ number_format(floatval(str_replace(',', '', $subtotal)) + $totalDeliveryFee + floatval(str_replace(',', '', $tax)), 2) }}</span></span>
                        </div>

                        <button type="submit" form="checkout-form" class="btn btn-primary w-100 btn-lg mb-3" id="place-order-btn">
                            <i class="bi bi-check-circle me-2"></i>Place Order
                        </button>
                        
                        <a href="{{ route('home.cart') }}" class="btn btn-outline-secondary w-100">
                            <i class="bi bi-arrow-left me-2"></i>Back to Cart
                        </a>

                        <div class="mt-3 text-center">
                            <small class="text-muted">
                                <i class="bi bi-shield-check me-1"></i>
                                Your order is secure and protected
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    @push('scripts')
    <script>
        // Optional: Simple button disable on submit (no interception needed)
        document.getElementById('checkout-form').addEventListener('submit', function() {
            document.getElementById('place-order-btn').disabled = true;
            document.getElementById('place-order-btn').innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processing...';
        });

        // Live compute delivery fee and totals when distance changes
        (function() {
            const vendorCount = {{ count($vendors) }};
            const subtotal = parseFloat(("{{ $subtotal }}").replace(/,/g, '')) || 0;
            const tax = parseFloat(("{{ $tax }}").replace(/,/g, '')) || 0;

            const deliveryFields = document.getElementById('delivery_fields');
            const deliveryFeeRow = document.getElementById('delivery_fee_row');
            const distanceInput = document.getElementById('distance_km');
            const addressField = document.getElementById('delivery_address');
            const deliverySpan = document.getElementById('delivery-amount');
            const totalSpan = document.getElementById('total-amount');
            const radioDelivery = document.getElementById('fulfillment_delivery');
            const radioPickup = document.getElementById('fulfillment_pickup');

            function computeFee(distanceKm) {
                const base = 1.20;
                const perKm = 0.30 * Math.max(0, distanceKm || 0);
                const perVendor = 0.60 * Math.max(0, vendorCount);
                return +(base + perKm + perVendor).toFixed(2);
            }

            function updateTotals() {
                const isPickup = radioPickup.checked;
                let delivery = 0;
                
                if (!isPickup) {
                    const distance = parseFloat(distanceInput.value) || 0;
                    delivery = computeFee(distance);
                }
                
                const total = +(subtotal + delivery + tax).toFixed(2);

                deliverySpan.textContent = delivery.toFixed(2);
                totalSpan.textContent = total.toFixed(2);
            }

            function updateFulfillmentUI() {
                const isPickup = radioPickup.checked;
                
                if (isPickup) {
                    // Hide delivery fields and delivery fee row
                    deliveryFields.style.display = 'none';
                    deliveryFeeRow.style.display = 'none';
                    distanceInput.removeAttribute('required');
                    addressField.removeAttribute('required');
                } else {
                    // Show delivery fields and delivery fee row
                    deliveryFields.style.display = 'block';
                    deliveryFeeRow.style.display = 'flex';
                    distanceInput.setAttribute('required', 'required');
                    addressField.setAttribute('required', 'required');
                }
                
                updateTotals();
            }

            distanceInput.addEventListener('input', updateTotals);
            radioDelivery.addEventListener('change', updateFulfillmentUI);
            radioPickup.addEventListener('change', updateFulfillmentUI);

            // Set initial state based on old input/defaults
            updateFulfillmentUI();
        })();
    </script>
    @endpush
</x-home-layout>