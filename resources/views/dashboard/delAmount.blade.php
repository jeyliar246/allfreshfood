<x-admin-layout>
    <div class="main-content">
        @include('layouts.admin-header')
        <div class="container py-4">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="card mb-4">
                <div class="card-header">Set Delivery Amount</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('delivery.amounts.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Amount (£)</label>
                            <input type="number" step="0.01" min="0" class="form-control" name="amount" value="{{ old('amount', optional($current)->amount) }}" required>
                            @error('amount')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Outside Bradford (£)</label>
                            <input type="number" step="0.01" min="0" class="form-control" name="outside" value="{{ old('outside', optional($current)->outside) }}">
                            @error('outside')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        <button class="btn btn-primary" type="submit">Save</button>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">History</div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Amount (£)</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($deliveryAmounts as $idx => $da)
                                    <tr>
                                        <td>{{ $idx + 1 }}</td>
                                        <td>{{ number_format($da->amount, 2) }}</td>
                                        <td>{{ $da->created_at }}</td>
                                        <td>
                                            <a class="btn btn-sm btn-outline-primary" href="{{ route('delivery.amounts.edit', $da) }}">Edit</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="text-center p-3">No records</td></tr>
                                @endforelse
                            </tbody>
                            <tbody>
                                @forelse($deliveryAmounts as $idx => $da)
                                    <tr>
                                        <td>{{ $idx + 1 }}</td>
                                        <td>{{ number_format($da->outside, 2) }} (<small>Outside Bradford</small>)</td>
                                        <td>{{ $da->created_at }}</td>
                                        <td>
                                            <a class="btn btn-sm btn-outline-primary" href="{{ route('delivery.amounts.edit', $da) }}">Edit</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="text-center p-3">No records</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
