<x-admin-layout>
    <!-- Main Content -->
    <div class="main-content">
        @include('layouts.admin-header')

        <!-- Dashboard Content -->
        <div class="container-fluid p-4">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 fw-bold mb-0">Dashboard Overview</h1>
                    <p class="text-muted mb-0">Welcome back, {{ auth()->user()->name }}!</p>
                </div>
                <a href="#" class="btn btn-primary">
                    <i class="bi bi-download me-2"></i>Generate Report
                </a>
            </div>

            <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <!-- Total Users -->
        <div class="col-md-6 col-lg-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Total Users</h6>
                            <h3 class="mb-0">{{ $stats['total_users'] ?? 0 }}</h3>
                        </div>
                        <div class="bg-primary bg-opacity-10 rounded p-3">
                            <i class="bi bi-people text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Total Orders -->
        <div class="col-md-6 col-lg-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Total Orders</h6>
                            <h3 class="mb-0">{{ $stats['total_orders'] ?? 0 }}</h3>
                        </div>
                        <div class="bg-success bg-opacity-10 rounded p-3">
                            <i class="bi bi-cart-check text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Total Revenue -->
        <div class="col-md-6 col-lg-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Total Revenue</h6>
                            <h3 class="mb-0">${{ number_format($stats['total_revenue'] ?? 0, 2) }}</h3>
                        </div>
                        <div class="bg-info bg-opacity-10 rounded p-3">
                            <i class="bi bi-currency-dollar text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Total Products -->
        <div class="col-md-6 col-lg-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Total Products</h6>
                            <h3 class="mb-0">{{ $stats['total_products'] ?? 0 }}</h3>
                        </div>
                        <div class="bg-warning bg-opacity-10 rounded p-3">
                            <i class="bi bi-box-seam text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row g-4 mb-4">
        <!-- Sales Chart -->
        <div class="col-lg-8">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">Sales Overview</h5>
                </div>
                <div class="card-body">
                    <canvas id="salesChart" height="300"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Order Status -->
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">Order Status</h5>
                </div>
                <div class="card-body">
                    <canvas id="orderStatusChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Recent Orders</h5>
            <a href="{{ route('admin.orders') }}" class="btn btn-sm btn-outline-primary">View All</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Customer</th>
                            <th>Vendor</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recent_orders as $key => $order)
                        <tr>
                            <td>#{{ $key + 1 }}</td>
                            <td>{{ $order->user->name ?? 'N/A' }}</td>
                            <td>{{ $order->vendor->name ?? 'N/A' }}</td>
                            <td>${{ number_format($order->total, 2) }}</td>
                            <td>
                                <span class="badge bg-{{ 
                                    $order->status === 'completed' ? 'success' : 
                                    ($order->status === 'processing' ? 'primary' : 'warning') 
                                }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td>{{ $order->created_at->format('M d, Y') }}</td>
                            <td>
                                <a href="#" class="btn btn-sm btn-outline-primary">View</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">No orders found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
        </div>
    </div>



@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Sales Chart
const salesCtx = document.getElementById('salesChart').getContext('2d');
new Chart(salesCtx, {
    type: 'line',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        datasets: [{
            label: 'Sales',
            data: [12000, 19000, 15000, 25000, 22000, 30000, 28000, 32000, 29000, 35000, 38000, 40000],
            borderColor: '#4e73df',
            backgroundColor: 'rgba(78, 115, 223, 0.05)',
            tension: 0.3,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return '$' + value.toLocaleString();
                    }
                }
            }
        }
    }
});

// Order Status Chart
const orderStatusCtx = document.getElementById('orderStatusChart').getContext('2d');
new Chart(orderStatusCtx, {
    type: 'doughnut',
    data: {
        labels: ['Completed', 'Processing', 'Pending', 'Cancelled'],
        datasets: [{
            data: [
                {{ $stats['order_status']['completed'] ?? 0 }},
                {{ $stats['order_status']['processing'] ?? 0 }},
                {{ $stats['order_status']['pending'] ?? 0 }},
                {{ $stats['order_status']['cancelled'] ?? 0 }}
            ],
            backgroundColor: ['#1cc88a', '#4e73df', '#f6c23e', '#e74a3b'],
            hoverBackgroundColor: ['#17a673', '#2e59d9', '#dda20a', '#be2617'],
            hoverBorderColor: 'rgba(234, 236, 244, 1)',
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        const label = context.label || '';
                        const value = context.raw || 0;
                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                        const percentage = Math.round((value / total) * 100);
                        return `${label}: ${value} (${percentage}%)`;
                    }
                }
            }
        },
        cutout: '70%',
    },
});
</script>
@endpush
</x-admin-layout>
    