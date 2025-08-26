@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold mb-0">Dashboard</h3>
            <span class="text-muted">Welcome back, {{ Auth::user()->name }} 👋</span>
        </div>

        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-body">
                        <h6 class="text-muted">Categories</h6>
                        <h3 class="fw-bold">{{ \App\Models\Category::count() }}</h3>
                        <a href="{{ route('categories.index') }}" class="text-decoration-none small">Manage Categories →</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-body">
                        <h6 class="text-muted">Wallpapers</h6>
                        <h3 class="fw-bold">{{ \App\Models\Wallpaper::count() }}</h3>
                        <a href="{{ route('wallpapers.index') }}" class="text-decoration-none small">View Wallpapers →</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-body">
                        <h6 class="text-muted">Users</h6>
                        <h3 class="fw-bold">{{ \App\Models\User::count() }}</h3>
                        <span class="text-muted small">Total Registered</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Categories Table -->
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-header bg-white fw-bold">Recent Categories</div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Status</th>
                         <th>Created At</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse(\App\Models\Category::latest()->take(5)->get() as $category)
                        <tr>
                            <td>{{ $category->name }}</td>
                            <td>
                                <span class="badge bg-{{ $category->status ? 'success' : 'secondary' }}">
                                    {{ $category->status ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                             <td>{{ $category->created_at->format('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">No categories found</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
