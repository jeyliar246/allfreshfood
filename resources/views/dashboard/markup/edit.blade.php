<x-admin-layout>
    <div class="main-content">
        @include('layouts.admin-header')
        <div class="container py-4">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="card mb-4">
                <div class="card-header">Update Markup</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('markup.update', $markup->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label">Markup (%)</label>
                            <input type="number" step="0.01" min="0" class="form-control" name="markup_percentage" value="{{ $markup->markup_percentage }}" required>
                            @error('markup_percentage')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        </div>
                        <button class="btn btn-primary" type="submit">Update</button>
                    </form>
                </div>
            </div>


        </div>
    </div>
</x-admin-layout>
