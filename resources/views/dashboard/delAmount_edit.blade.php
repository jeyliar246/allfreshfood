<x-admin-layout>
    <div class="main-content">
        @include('layouts.admin-header')
        <div class="container py-4">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="d-flex align-items-center justify-content-between mb-3">
                <h5 class="mb-0">Edit Delivery Amount</h5>
                <a href="{{ route('delivery.amounts') }}" class="btn btn-outline-secondary">Back</a>
            </div>

            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('delivery.amounts.update', $deliveryAmount) }}">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label">Amount (£)</label>
                            <input type="number" step="0.01" min="0" class="form-control" name="amount" value="{{ old('amount', $deliveryAmount->amount) }}" required>
                            @error('amount')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Delivery Outside Bradford (£)</label>
                            <input type="number" step="0.01" min="0" class="form-control" name="outside" value="{{ old('outside', $deliveryAmount->outside) }}">
                            @error('outside')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        <button class="btn btn-primary" type="submit">Update</button>
                        <a href="{{ route('delivery.amounts') }}" class="btn btn-light">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
