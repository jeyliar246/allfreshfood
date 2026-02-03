<x-admin-layout>
    <div class="main-content">
        @include('layouts.admin-header')
        <div class="container py-4">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="card mb-4">
                <div class="card-header">Set Markup</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('markup.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Amount (%)</label>
                            <input type="number" step="0.01" min="0" class="form-control" name="markup_percentage" required>
                            @error('markup_percentage')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        </div>
                        <button class="btn btn-primary" type="submit">Create/Update</button>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">Current Markup</div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Markup (%)</th>
                                    <th>Created At</th>
                                    {{-- <th>Actions</th> --}}
                                </tr>
                            </thead>
                           <tbody>
                                @foreach($markups as $markup)
                                <tr>
                                    <td>{{ $markup->id }}</td>
                                    <td>{{ number_format($markup->markup_percentage, 2) }}</td>
                                    <td>{{ $markup->created_at->format('Y-m-d H:i:s') }}</td>
                                    {{-- <td>
                                        <a class="btn btn-sm btn-outline-primary" href="{{ route('markup.edit', $markup->id) }}">Edit</a>
                                    </td> --}}
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
