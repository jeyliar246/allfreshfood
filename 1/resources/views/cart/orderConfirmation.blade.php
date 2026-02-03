<x-home-layout>
    <main class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Success Header -->
                <div class="text-center mb-5">
                    <div class="mb-4">
                        <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                    </div>
                    <h1 class="fw-bold text-success mb-2">Order Confirmed!</h1>
                    <p class="text-muted">Thank you for your order. We've received your order and will begin processing it shortly.</p>
                </div>

                <!-- Order Details -->
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="bi bi-receipt me-2"></i>Order Details</h5>
                    </div>
                    <div class="card-body">
                        @php
                            $recentOrders = session('recent_orders', []);
                        @endphp

                        @if(!empty($recentOrders))
                            @foreach($recentOrders as $order)
                                <div class="order-summary mb-4 pb-4 {{ !$loop->last ? 'border-bottom' : '' }}">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div>
                                            <h6 class="text-primary mb-1">
                                                <i class="bi bi-shop me-2"></i>{{ $order['vendor']['name'] ?? 'Unknown Vendor' }}
                                            </h6>
                                            <small class="text-muted">Order #{{ $order['id'] }}</small>
                                        </div>
                                        <span class="badge bg-warning text-dark">{{ ucfirst($order['status']) }}</span>
                                    </div>

                                    <!-- Order Items -->
                                    @if(isset($order['items']))
                                        @foreach($order['items'] as $item)
                                            <div class="d-flex align-items-center mb-2">
                                                @if($item['product']['image'])
                                                    <img src="{{ asset('uploads/' . $item['product']['image']) }}" 
                                                         alt="{{ $item['product']['name'] }}" 
                                                         class="rounded me-3" 
                                                         width="50" height="50" 
                                                         style="object-fit: cover;">
                                                @else
                                                    <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                        <i class="bi bi-image text-muted"></i>
                                                    </div>
                                                @endif
                                                <div class="flex-grow-1">
                                                    <div class="fw-semibold">{{ $item['product']['name'] }}</div>
                                                    <div class="text-muted small">Quantity: {{ $item['quantity'] }}</div>
                                                </div>
                                                <div class="text-end">
                                                    <div class="fw-bold">£{{ number_format($item['price'] * $item['quantity'], 2) }}</div>
                                                    <div class="text-muted small">£{{ number_format($item['price'], 2) }} each</div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif

                                    <!-- Order Totals -->
                                    <div class="mt-3 pt-3 border-top">
                                        <div class="d-flex justify-content-between mb-1">
                                            <span>Subtotal:</span>
                                            <span>£{{ number_format($order['subtotal'], 2) }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-1">
                                            <span>Delivery Fee:</span>
                                            @if($order['fulfillment_method'] === 'pickup')
                                            <span>0</span>
                                            @else
                                                <span>{{ $order['delivery_fee'] > 0 ? '£' . number_format($order['delivery_fee'], 2) : 'Free' }}</span>
                                            @endif
                                        </div>
                                        <div class="d-flex justify-content-between fw-bold">
                                            <span>Total:</span>
                                            <span>£{{ number_format($order['total'], 2) }}</span>
                                        </div>
                                    </div>

                                    <!-- Delivery Info -->
                                    <div class="mt-3 pt-3 border-top">
                                        <h6><i class="bi bi-geo-alt me-2"></i>Delivery Information</h6>
                                        <p class="mb-1"><strong>Address:</strong> {{ $order['delivery_address'] }}</p>
                                        <p class="mb-1"><strong>Phone:</strong> {{ $order['phone'] ?? 'Not provided' }}</p>
                                        <p class="mb-0"><strong>Payment Method:</strong> {{ ucwords(str_replace('_', ' ', $order['payment_method'])) }}</p>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <!-- Fallback if no order data in session -->
                            <div class="text-center py-4">
                                <p class="text-muted">Your order has been placed successfully! You should receive a confirmation email shortly.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- What's Next -->
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>What's Next?</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 text-center mb-3">
                                <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 60px; height: 60px;">
                                    <i class="bi bi-check-circle text-primary" style="font-size: 1.5rem;"></i>
                                </div>
                                <h6>Order Confirmed</h6>
                                <small class="text-muted">We've received your order</small>
                            </div>
                            <div class="col-md-4 text-center mb-3">
                                <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 60px; height: 60px;">
                                    <i class="bi bi-box-seam text-warning" style="font-size: 1.5rem;"></i>
                                </div>
                                <h6>Preparing</h6>
                                <small class="text-muted">Vendor is preparing your order</small>
                            </div>
                            <div class="col-md-4 text-center mb-3">
                                <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 60px; height: 60px;">
                                    <i class="bi bi-truck text-success" style="font-size: 1.5rem;"></i>
                                </div>
                                <h6>Delivery</h6>
                                <small class="text-muted">Your order will be delivered</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Tracking -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h6><i class="bi bi-info-circle me-2"></i>Important Information</h6>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2"><i class="bi bi-envelope me-2 text-muted"></i>You will receive a confirmation email shortly</li>
                            <li class="mb-2"><i class="bi bi-bell me-2 text-muted"></i>We'll send you updates as your order progresses</li>
                            <li class="mb-2"><i class="bi bi-headset me-2 text-muted"></i>Contact us if you have any questions about your order</li>
                            <li class="mb-0"><i class="bi bi-clock me-2 text-muted"></i>Estimated delivery time will be communicated by the vendor</li>
                        </ul>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="text-center">
                    <a href="{{ route('home.browse') }}" class="btn btn-primary btn-lg me-3">
                        <i class="bi bi-shop me-2"></i>Continue Shopping
                    </a>
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-lg">
                        <i class="bi bi-house me-2"></i>Back to Home
                    </a>
                </div>

                <!-- Support Contact -->
                <div class="text-center mt-4">
                    <p class="text-muted small">
                        Need help with your order? 
                        <a href="mailto:support@yourstore.com" class="text-decoration-none">Contact Support</a>
                    </p>
                </div>
            </div>
        </div>
    </main>

    @push('scripts')
    <script>
        // Clear the recent orders from session after displaying
        @if(session()->has('recent_orders'))
            {{ session()->forget('recent_orders') }}
        @endif
    </script>
    @endpush
</x-home-layout>