<x-home-layout>
    <main class="container py-4">
        <!-- Page Header -->
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h1 class="fw-bold mb-1">My Orders</h1>
                <p class="text-muted mb-0">Track and manage your orders</p>
            </div>
            <div class="d-flex gap-2">
                <select id="order-status-filter" class="form-select" style="width: auto;">
                    <option value="" {{ empty($status) ? 'selected' : '' }}>All Orders</option>
                    <option value="pending" {{ (isset($status) && $status==='pending') ? 'selected' : '' }}>Pending</option>
                    <option value="processing" {{ (isset($status) && $status==='processing') ? 'selected' : '' }}>Processing</option>
                    <option value="delivered" {{ (isset($status) && $status==='delivered') ? 'selected' : '' }}>Delivered</option>
                    <option value="cancelled" {{ (isset($status) && $status==='cancelled') ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
        </div>

        <!-- Orders List -->
        <div class="row">
            <div class="col-12">
                @forelse($orders as $order)
                    @if ($status && $order->status !== $status)
                        @continue
                    @endif
                    <div class="card mb-4">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-md-4">
                                    <h6 class="mb-1">Order #{{ $order->id }}</h6>
                                    <small class="text-muted">Placed on {{ $order->created_at?->format('M d, Y \a\t h:i A') }}</small>
                                </div>
                                <div class="col-md-3">
                                    @php
                                        $statusClass = match($order->status) {
                                            'delivered' => 'bg-success',
                                            'processing' => 'bg-warning',
                                            'cancelled' => 'bg-danger',
                                            'pending' => 'bg-secondary',
                                            default => 'bg-secondary'
                                        };
                                    @endphp
                                    <span class="badge {{ $statusClass }}">{{ ucfirst($order->status) }}</span>
                                    <small class="text-muted d-block">Payment: {{ ucfirst($order->payment_status) }}</small>
                                </div>
                                <div class="col-md-3">
                                    <strong>£{{ number_format($order->total, 2) }}</strong>
                                    <small class="text-muted d-block">Vendor: {{ optional($order->vendor)->name ?? 'N/A' }}</small>
                                </div>
                                <div class="col-md-2 text-end">
                                    <a class="btn btn-outline-primary btn-sm" href="#" disabled>View Details</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <h6 class="mb-2">Items ({{ $order->items->sum('quantity') }})</h6>
                                    @foreach($order->items as $orderItem)
                                        @php
                                            $product = $orderItem->product;
                                            $img = $product && $product->image ? asset('uploads/' . $product->image) : 'https://via.placeholder.com/60';
                                        @endphp
                                        <div class="d-flex align-items-center mb-2">
                                            <img src="{{ $img }}" alt="{{ $product->name ?? 'Item' }}" class="rounded me-3" width="40" height="40" style="object-fit: cover;">
                                            <div>
                                                <div class="fw-medium">{{ $product->name ?? 'Item' }}</div>
                                                <small class="text-muted">Qty: {{ $orderItem->quantity }} • £{{ number_format($orderItem->price, 2) }}</small>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="col-md-4">
                                    <h6 class="mb-2">Delivery Info</h6>
                                    <p class="mb-1"><strong>Address:</strong> {{ $order->delivery_address }}</p>
                                    <p class="mb-1"><strong>Payment:</strong> {{ strtoupper($order->payment_method) }} ({{ ucfirst($order->payment_status) }})</p>
                                    <p class="mb-0"><strong>Delivery fee:</strong> £{{ number_format($order->delivery_fee, 2) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <i class="bi bi-bag-x display-1 text-muted mb-4"></i>
                        <h3 class="mb-3">No orders found</h3>
                        <p class="text-muted mb-4">When you place your first order, it will appear here.</p>
                        <a href="{{ route('home') }}" class="btn btn-primary btn-lg">Start Shopping</a>
                    </div>
                @endforelse

                <div class="d-flex justify-content-center mt-3">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </main>

    @push('scripts')
    <script>
        const filter = document.getElementById('order-status-filter');
        if (filter) {
            filter.addEventListener('change', function() {
                const val = this.value;
                const url = new URL(window.location.href);
                if (val) {
                    url.searchParams.set('status', val);
                } else {
                    url.searchParams.delete('status');
                }
                window.location.href = url.toString();
            });
        }
    </script>
    @endpush
</x-home-layout>