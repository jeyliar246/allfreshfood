<x-admin-layout>
<div class="main-content">
    @include('layouts.admin-header')
    <div class="container-fluid p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h4 mb-0">Order #{{ $order->id }}</h1>
            <a href="{{ route('vendor.orders.index') }}" class="btn btn-outline-secondary btn-sm">Back</a>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card mb-3">
                    <div class="card-header"><h5 class="mb-0">Items</h5></div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Qty</th>
                                        <th>Price (£)</th>
                                        <th>Total (£)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->items as $item)
                                        <tr>
                                            <td>{{ $item->product->name ?? 'Product #'.$item->product_id }}</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>{{ number_format($item->price, 2) }}</td>
                                            <td>{{ number_format($item->price * $item->quantity, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card mb-3">
                    <div class="card-header"><h5 class="mb-0">Summary</h5></div>
                    <div class="card-body">
                        <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
                        <p><strong>Total:</strong> £{{ number_format($order->total, 2) }}</p>
                        <p><strong>Delivery Fee:</strong> £{{ number_format($order->delivery_fee, 2) }}</p>
                        <p><strong>Payment:</strong> {{ ucfirst($order->payment_status) }}</p>
                        <p><strong>Delivery Address:</strong><br/>{{ $order->delivery_address }}</p>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header"><h5 class="mb-0">Customer</h5></div>
                    <div class="card-body">
                        <p>{{ $order->user->name ?? '' }} @if($order->guest_name) {{ $order->guest_name }} @endif</p>
                        <p>{{ $order->user->email ?? '' }} @if($order->guest_email) {{ $order->guest_email }} @endif</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</x-admin-layout>
