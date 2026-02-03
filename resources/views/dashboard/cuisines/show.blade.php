<x-admin-layout>
    <div class="main-content">
        @include('layouts.admin-header')
        
        <div class="container-fluid">
            <div class="d-flex align-items-center">
                <button class="btn btn-outline-secondary me-3 d-lg-none sidebar-toggle">
                    <i class="bi bi-list"></i>
                </button>
                <div class="flex-grow-1">
                    <h1 class="h3 fw-bold mb-0">Cuisine Details</h1>
                </div>
                <a href="{{ route('dashboard.cuisines.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Back to Cuisines
                </a>
            </div>
        </div>

        <div class="container-fluid p-4">
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex gap-3 align-items-start">
                                <div>
                    <button class="btn btn-outline-secondary me-3 d-lg-none sidebar-toggle">
                        <i class="bi bi-list"></i>
                    </button>
                    <div class="flex-grow-1">
                        <h1 class="h3 fw-bold mb-0">Cuisine Details</h1>
                    </div>
                    <a href="{{ route('dashboard.cuisines.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Back to Cuisines
                    </a>
                </div>
            </div>
        </div>

        <div class="container-fluid p-4">
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex gap-3 align-items-start">
                                <div>
                                    @if($cuisine->image)
                                        <img src="{{ asset('uploads/' . $cuisine->image) }}" alt="{{ $cuisine->name }}" class="rounded" style="width: 120px; height: 120px; object-fit: cover;">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center rounded" style="width: 120px; height: 120px;">
                                            <i class="bi bi-image text-muted"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-grow-1">
                                    <h4 class="mb-1">{{ $cuisine->name }}</h4>
                                    <p class="text-muted mb-3">{{ $cuisine->description ?: 'No description provided.' }}</p>
                                    <div class="d-flex align-items-center gap-3">
                                        <span class="badge bg-primary">Vendors: {{ $cuisine->vendors_count ?? $cuisine->vendors()->count() }}</span>
                                        <a href="{{ route('dashboard.cuisines.edit', $cuisine) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil me-1"></i>Edit
                                        </a>
                                        <form action="{{ route('dashboard.cuisines.destroy', $cuisine) }}" method="POST" onsubmit="return confirm('Delete this cuisine?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash me-1"></i>Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">Meta</h6>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2"><strong>Name:</strong> {{ $cuisine->name }}</li>
                                <li class="mb-2"><strong>Created:</strong> {{ $cuisine->created_at?->format('Y-m-d H:i') }}</li>
                                <li class="mb-2"><strong>Updated:</strong> {{ $cuisine->updated_at?->format('Y-m-d H:i') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
