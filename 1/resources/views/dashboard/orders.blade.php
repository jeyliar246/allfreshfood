<x-admin-layout>
    <div class="main-content">
       
        @include('layouts.admin-header')
        <!-- Order Management Content -->
        <div class="container-fluid p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 fw-bold">Order Management</h1>
                    <p class="text-muted mb-0">Track and manage customer orders.</p>
                </div>
                <button class="btn btn-primary">
                    <i class="bi bi-eye me-2"></i>
                    View All Orders
                </button>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-1">Order List</h5>
                    <p class="card-text text-muted small mb-0">Recent orders across the platform</p>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Customer</th>
                                    <th>Vendor</th>
                                    <th>Total (£)</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                               @if ($orders->isEmpty())
                               <tr>
                                   <td colspan="6" class="text-center">No orders found.</td>
                               </tr>
                               @else
                                @foreach ($orders as $order)
                                        <tr>
                                            <td>{{ $order->id }}</td>
                                            <td>{{ $order->user->name ?? '' }} @if($order->guest_name) {{ $order->guest_name }} @endif</td>
                                            <td>{{ $order->vendor->name }}</td>
                                            <td>{{ $order->total }}</td>
                                            <td>
                                                <form action="{{ route('orders.updateStatus', $order) }}" method="POST" class="d-flex align-items-center gap-2">
                                                    @csrf
                                                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                                        @php($statuses = ['pending','confirmed','processing','preparing','ready','on_delivery','shipped','delivered','cancelled'])
                                                        @foreach($statuses as $st)
                                                            <option value="{{ $st }}" {{ $order->status === $st ? 'selected' : '' }}>{{ ucfirst($st) }}</option>
                                                        @endforeach
                                                    </select>
                                                    <noscript>
                                                        <button type="submit" class="btn btn-sm btn-primary">Update</button>
                                                    </noscript>
                                                </form>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#orderModal{{ $order->id }}"><i class="bi bi-eye"></i> View</button>
                                                <form action="{{ route('orders.updateStatus', $order) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="status" value="cancelled">
                                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-x-circle"></i> Cancel</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                               @endif
                            </tbody>
                        </table>
                        <div class="mt-3">
                            {{ $orders->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if(!empty($orders))
            @foreach ($orders as $order)
            <!-- Order Detail Modal (moved outside table for valid HTML) -->
            <div class="modal fade" id="orderModal{{ $order->id }}" tabindex="-1" aria-labelledby="orderModalLabel{{ $order->id }}" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="orderModalLabel{{ $order->id }}">Order #{{ $order->id }} Details</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Customer:</strong> {{ $order->user->name ?? '' }} @if($order->guest_name) {{ $order->guest_name }} @endif</p>
                                    <p class="mb-1"><strong>Vendor:</strong> {{ $order->vendor->name }}</p>
                                    <p class="mb-1"><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
                                    <p class="mb-1"><strong>Payment:</strong> {{ ucfirst($order->payment_status) }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Total:</strong> £{{ number_format($order->total,2) }}</p>
                                    <p class="mb-1"><strong>Delivery Fee:</strong> £{{ number_format($order->delivery_fee,2) }}</p>
                                    <p class="mb-1"><strong>Date:</strong> {{ $order->created_at->format('M d, Y H:i') }}</p>
                                    <p class="mb-1"><strong>Delivery Address:</strong><br/>{{ $order->user->address ?? $order->delivery_address }}</p>
                                    <p class="mb-1"><strong>Phone:</strong><br/>{{ $order->user->phone ?? $order->guest_phone }}</p>
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
                            <form action="{{ route('orders.updateStatus', $order) }}" method="POST" class="d-flex align-items-center gap-2">
                                @csrf
                                <label class="small text-muted">Update Status</label>
                                <select name="status" class="form-select form-select-sm">
                                    @php($statuses = ['pending','confirmed','processing','preparing','ready','on_delivery','shipped','delivered','cancelled'])
                                    @foreach($statuses as $st)
                                        <option value="{{ $st }}" {{ $order->status === $st ? 'selected' : '' }}>{{ ucfirst($st) }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-sm btn-primary">Save Changes</button>
                            </form>
                            <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        @endif

    </div>
</x-admin-layout>