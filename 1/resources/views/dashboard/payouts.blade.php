<x-admin-layout>
<div class="main-content">
    @include('layouts.admin-header')
    <div class="container-fluid p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 fw-bold">Payout Requests </h1>
                <p class="text-muted mb-0">Review, approve and mark vendor withdrawals as paid.</p>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Payouts</h5>
                <form method="GET" class="d-flex align-items-center gap-2">
                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                        @php($statuses = [null=>'All','pending'=>'Pending','approved'=>'Approved','paid'=>'Paid'])
                        @foreach($statuses as $key=>$label)
                            <option value="{{ $key }}" {{ ($status??null) == (string)$key ? 'selected':'' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Vendor</th>
                                <th>Amount (£)</th>
                                <th>Method</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="payoutsAccordion">
                            @forelse($payouts as $payout)
                                <tr>
                                    <td>#{{ $payout->id }}</td>
                                    <td>{{ $payout->vendor->name ?? 'Vendor #'.$payout->vendor_id }}</td>
                                    <td>{{ number_format($payout->amount, 2) }}</td>
                                    <td>{{ ucfirst(str_replace('_',' ', $payout->payment_method)) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $payout->status === 'pending' ? 'warning' : ($payout->status==='approved'?'info':'success') }}">{{ ucfirst($payout->status) }}</span>
                                    </td>
                                    <td>{{ $payout->created_at->format('M d, Y H:i') }}</td>
                                    <td class="d-flex gap-2">
                                        @if($payout->status === 'pending')
                                            <form method="POST" action="{{ route('admin.payouts.approve', $payout->id) }}">
                                                @csrf
                                                <button class="btn btn-sm btn-outline-primary">Approve</button>
                                            </form>
                                        @endif
                                        @if(in_array($payout->status, ['pending','approved']))
                                            <form method="POST" action="{{ route('admin.payouts.pay', $payout->id) }}">
                                                @csrf
                                                <button class="btn btn-sm btn-success">Mark Paid</button>
                                            </form>
                                        @endif
                                        <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="collapse" data-bs-target="#collapse-payout-{{$payout->id}}" aria-expanded="false" aria-controls="collapse-payout-{{$payout->id}}">
                                            View Details
                                        </button>

                                    </td>
                                </tr>
                                <tr class="collapse-row">
                                    <td colspan="7" class="p-0 border-0">
                                        <div id="collapse-payout-{{$payout->id}}" class="collapse" data-bs-parent="#payoutsAccordion">
                                            <div class="p-3 border-top bg-light">
                                                <div class="row g-3"></div>
                                                    <div class="col-md-4">
                                                        <div class="card h-100 shadow-sm">
                                                            <div class="card-body">
                                                                <h6 class="text-uppercase text-muted small mb-2">Summary</h6>
                                                                <ul class="list-unstyled mb-0">
                                                                    <li class="d-flex justify-content-between"><span>Payout ID</span><span class="fw-semibold">#{{ $payout->id }}</span></li>
                                                                    <li class="d-flex justify-content-between"><span>Vendor</span><span class="fw-semibold">{{ $payout->vendor->name ?? ('Vendor #'.$payout->vendor_id) }}</span></li>
                                                                    <li class="d-flex justify-content-between"><span>Amount</span><span class="fw-semibold">£{{ number_format($payout->amount, 2) }}</span></li>
                                                                    <li class="d-flex justify-content-between"><span>Method</span><span class="fw-semibold">{{ ucfirst(str_replace('_',' ', $payout->payment_method)) }}</span></li>
                                                                    <li class="d-flex justify-content-between"><span>Status</span><span class="fw-semibold">{{ ucfirst($payout->status) }}</span></li>
                                                                    <li class="d-flex justify-content-between"><span>Requested</span><span class="fw-semibold">{{ $payout->created_at->format('M d, Y H:i') }}</span></li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="card h-100 shadow-sm">
                                                            <div class="card-body">
                                                                <h6 class="text-uppercase text-muted small mb-2">Bank Details</h6>
                                                                <ul class="list-unstyled mb-0">
                                                                    <li class="d-flex justify-content-between"><span>Account Name</span><span class="fw-semibold">{{ optional($payout->vendor)->account_name ?? 'N/A' }}</span></li>
                                                                    <li class="d-flex justify-content-between"><span>Account Number</span><span class="fw-semibold">{{ optional($payout->vendor)->account_number ?? 'N/A' }}</span></li>
                                                                    <li class="d-flex justify-content-between"><span>Sort Code</span><span class="fw-semibold">{{ optional($payout->vendor)->sort_code ?? 'N/A' }}</span></li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="card h-100 shadow-sm">
                                                            <div class="card-body">
                                                                <h6 class="text-uppercase text-muted small mb-2">Actions</h6>
                                                                <div class="d-flex flex-wrap gap-2">
                                                                    @if($payout->status === 'pending')
                                                                        <form method="POST" action="{{ route('admin.payouts.approve', $payout->id) }}">
                                                                            @csrf
                                                                            <button class="btn btn-sm btn-outline-primary">Approve</button>
                                                                        </form>
                                                                    @endif
                                                                    @if(in_array($payout->status, ['pending','approved']))
                                                                        <form method="POST" action="{{ route('admin.payouts.pay', $payout->id) }}">
                                                                            @csrf
                                                                            <button class="btn btn-sm btn-success">Mark Paid</button>
                                                                        </form>
                                                                    @endif
                                                                    <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-payout-{{$payout->id}}">Hide</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="text-center">No payouts found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div>
                    {{ $payouts->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
</div>




</x-admin-layout>
