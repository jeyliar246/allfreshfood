<x-admin-layout>
    <div class="main-content">
       
        @include('layouts.admin-header')


        <!-- Delivery Tracking Content -->
        <div class="container-fluid p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 fw-bold">Delivery Tracking</h1>
                    <p class="text-muted mb-0">Monitor delivery status and logistics.</p>
                </div>
                <button class="btn btn-primary">
                    <i class="bi bi-truck me-2"></i>
                    View All Deliveries
                </button>
            </div>

            <div class="row g-4">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-1">Delivery List</h5>
                            <p class="card-text text-muted small mb-0">Current and recent deliveries</p>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Order ID</th>
                                            <th>Customer</th>
                                            <th>Delivery Person</th>
                                            <th>Status</th>
                                            <th>Pickup Time</th>
                                            <th>Delivery Time</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($deliveries->isEmpty())
                                        <tr>
                                            <td colspan="6" class="text-center">No deliveries found.</td>
                                        </tr>
                                        @else
                                        @foreach ($deliveries as $key => $delivery)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $delivery->order->user->name }}</td>
                                                    <td>{{ optional($delivery->deliveryPerson)->name ?? 'Unassigned' }}</td>
                                                    <td>{{ ucfirst($delivery->status) }}</td>
                                                    <td>{{ optional($delivery->pickup_time)->format('Y-m-d H:i') }}</td>
                                                    <td>{{ optional($delivery->delivery_time)->format('Y-m-d H:i') }}</td>
                                                    <td class="d-flex gap-2">
                                                        @can('update', $delivery)
                                                            @if($delivery->status === 'pending')
                                                                @if(is_null($delivery->delivery_person_id))
                                                                    <form method="POST" action="{{ route('delivery.assign', $delivery) }}">
                                                                        @csrf
                                                                        <div class="input-group input-group-sm">
                                                                            <select name="delivery_person_id" class="form-select form-select-sm">
                                                                                <option value="">Select Delivery Person</option>
                                                                                @foreach ($deliveryPersons as $deliveryPerson)
                                                                                    <option value="{{ $deliveryPerson->id }}">{{ $deliveryPerson->name }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                            <button class="btn btn-sm btn-outline-primary">Assign</button>
                                                                        </div>
                                                                    </form>
                                                                @endif
                                                                <form method="POST" action="{{ route('delivery.pickup', $delivery) }}">
                                                                    @csrf
                                                                    <button class="btn btn-sm btn-outline-success">Mark Picked Up</button>
                                                                </form>
                                                            @elseif($delivery->status === 'picked_up')
                                                                <form method="POST" action="{{ route('delivery.deliver', $delivery) }}">
                                                                    @csrf
                                                                    <button class="btn btn-sm btn-primary">Mark Delivered</button>
                                                                </form>
                                                            @endif
                                                        @endcan
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-1">Delivery Map</h5>
                            <p class="card-text text-muted small mb-0">Real-time delivery tracking</p>
                        </div>
                        <div class="card-body">
                            <div style="height: 300px; background: #e9ecef; display: flex; align-items: center; justify-content: center;">
                                <p class="text-muted">Map Placeholder</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-admin-layout>