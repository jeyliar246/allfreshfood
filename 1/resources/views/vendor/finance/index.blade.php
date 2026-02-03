<x-admin-layout>
<div class="main-content">
    @include('layouts.admin-header')
    <div class="container-fluid p-4">
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header"><h5 class="mb-0">Balance </h5></div>
                    <div class="card-body">
                        <p class="text-muted mb-1">Available Balance</p>
                        <h2>£{{ number_format($balance, 2) }}</h2>
                        <p class="mb-0 small">Delivered total: £{{ number_format($deliveredTotal, 2) }} | Paid out: £{{ number_format($payoutTotal, 2) }}</p>
                    </div>
                </div>
                <div class="card">
                   <div class="card-header"><h5 class="mb-0">Bank Details </h5></div>
                   <div class="card-body">
                       <p>Account Name: {{ $vendor->account_name }}</p>
                       <p>Account Number: {{ $vendor->account_number }}</p>
                       <p>Sort Code: {{ $vendor->sort_code }}</p>
                   </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Request Withdrawal</h5>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        <form action="{{ route('vendor.withdrawals.store') }}" method="POST" class="row g-3">
                            @csrf
                            <div class="col-md-6">
                                <label class="form-label">Amount (£)</label>
                                <input type="number" step="0.01" min="1" name="amount" class="form-control" value="{{ old('amount') }}" required />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Payment Method</label>
                                <select name="payment_method" class="form-select" required>
                                    <option value="bank_transfer">Bank Transfer</option>
                                    <option value="paypal">PayPal</option>
                                    <option value="manual">Manual</option>
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Notes</label>
                                <textarea name="notes" class="form-control" rows="2" placeholder="Optional">{{ old('notes') }}</textarea>
                            </div>
                            <div class="col-12">
                                <button class="btn btn-primary" {{ $balance <= 0 ? 'disabled' : '' }}>Submit Request</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Bank Details</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('vendor.bank-details.store') }}">
                            @csrf
                            <div class="col-md-12">
                                <label class="form-label">Account Name</label>
                                <input type="text" name="account_name" class="form-control" value="{{ $vendor->account_name }}" required />
                            </div> 
                            <div class="row">
                                 <div class="col-md-12">
                                <label class="form-label">Account Number</label>
                                <input type="text" name="account_number" class="form-control" value="{{ $vendor->account_number }}" required />
                            </div> 
                             <div class="col-md-12">
                                <label class="form-label">Sort Code</label>
                                <input type="text" name="sort_code" class="form-control" value="{{ $vendor->sort_code }}" required />
                            </div> 
                            </div>
                            <div class="col-12 mt-2">
                                <button class="btn btn-primary">Change/Create</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header"><h5 class="mb-0">Withdrawal Requests</h5></div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Date</th>
                                <th>Amount (£)</th>
                                <th>Method</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pendingPayouts as $payout)
                                <tr>
                                    <td>#{{ $payout->id }}</td>
                                    <td>{{ $payout->created_at->format('M d, Y H:i') }}</td>
                                    <td>{{ number_format($payout->amount, 2) }}</td>
                                    <td>{{ ucfirst(str_replace('_',' ',$payout->payment_method)) }}</td>
                                    <td><span class="badge bg-{{ $payout->status === 'pending' ? 'warning' : ($payout->status === 'approved' ? 'info' : 'success') }}">{{ ucfirst($payout->status) }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center">No withdrawal requests.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</x-admin-layout>
