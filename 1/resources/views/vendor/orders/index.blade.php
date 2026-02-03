<x-admin-layout>
<div class="main-content">
    @include('layouts.admin-header')
    <div class="container-fluid p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h4 mb-0">My Orders</h1>
            <a href="{{ route('vendor.finance.index') }}" class="btn btn-outline-primary btn-sm">Finance</a>
        </div>

        <div class="card">
            <div class="card-header"><h5 class="mb-0">Orders</h5></div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Date</th>
                                <th>Customer</th>
                                <th>Total (£)</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                                <tr>
                                    <td>#{{ $order->id }}</td>
                                    <td>{{ $order->created_at->format('M d, Y H:i') }}</td>
                                    <td>{{ $order->user->name ?? '' }} @if($order->guest_name) {{ $order->guest_name }} @endif</td>
                                    <td>{{ number_format($order->total, 2) }}</td>
                                    <td>{{ ucfirst($order->status) }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#vendorOrderModal{{ $order->id }}">View</button>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center">No orders found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div>
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
        @foreach($orders as $order)
        <!-- Vendor Order Detail Modal (outside table) -->
        <div class="modal fade" id="vendorOrderModal{{ $order->id }}" tabindex="-1" aria-labelledby="vendorOrderModalLabel{{ $order->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="vendorOrderModalLabel{{ $order->id }}">Order #{{ $order->id }} Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Customer:</strong> {{ $order->user->name ?? '' }} @if($order->guest_name) {{ $order->guest_name }} @endif</p>
                                <p class="mb-1"><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
                                <p class="mb-1"><strong>Payment:</strong> {{ ucfirst($order->payment_status) }}</p>
                                <p class="mb-1"><strong>Phone:</strong><br/>{{ $order->user->phone ?? $order->guest_phone }}</p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Total:</strong> £{{ number_format($order->total,2) }}</p>
                                <p class="mb-1"><strong>Delivery Fee:</strong> £{{ number_format($order->delivery_fee,2) }}</p>
                                <p class="mb-1"><strong>Date:</strong> {{ $order->created_at->format('M d, Y H:i') }}</p>
                                <p class="mb-1"><strong>Address:</strong> {{ $order->user->address ?? $order->delivery_address }}</p>
                            </div>
                        </div>
                        <hr/>
                        <h6>Items</h6>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Qty</th>
                                        <th>Price</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->items as $item)
                                        <tr>
                                            <td>{{ $item->product->name ?? ('#'.$item->product_id) }}</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>£{{ number_format($item->price,2) }}</td>
                                            <td>£{{ number_format($item->price * $item->quantity,2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-between align-items-center">
                        <form action="{{ route('vendor.orders.updateStatus', $order) }}" method="POST" class="d-flex align-items-center gap-2">
                            @csrf
                            <label class="small text-muted">Update Status</label>
                            <select name="status" class="form-select form-select-sm">
                                @php($statuses = ['pending','confirmed','processing','preparing','ready','on_delivery','shipped','delivered','cancelled'])
                                @foreach($statuses as $st)
                                    <option value="{{ $st }}" {{ $order->status === $st ? 'selected' : '' }}>{{ ucfirst($st) }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-lg btn-primary">Save </button>
                        </form>
                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
</x-admin-layout>
