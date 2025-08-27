@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page header -->
    <div class="row g-3 mb-4 align-items-center">
        <div class="col">
            <h1 class="h3 mb-1">Dashboard</h1>
            <p class="text-muted mb-0">Welcome back, {{ Auth::user()->name ?? 'User' }}.</p>
        </div>
        <div class="col-auto">
            <a href="{{ route('categories.index') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i> New Category
            </a>
            <a href="{{ route('wallpapers.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-upload me-1"></i> Upload Wallpaper
            </a>
        </div>
    </div>

    <!-- Stats cards -->
    <div class="row g-3">
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="p-3 bg-primary bg-opacity-10 rounded-3 me-3">
                            <i class="bi bi-folder fs-3 text-primary"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Categories</div>
                            <div class="fs-4 fw-semibold">{{ $stats['categories'] ?? '--' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="p-3 bg-success bg-opacity-10 rounded-3 me-3">
                            <i class="bi bi-image fs-3 text-success"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Wallpapers</div>
                            <div class="fs-4 fw-semibold">{{ $stats['wallpapers'] ?? '--' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="p-3 bg-warning bg-opacity-10 rounded-3 me-3">
                            <i class="bi bi-card-image fs-3 text-warning"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Category Images</div>
                            <div class="fs-4 fw-semibold">{{ $stats['category_images'] ?? '--' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="p-3 bg-info bg-opacity-10 rounded-3 me-3">
                            <i class="bi bi-people fs-3 text-info"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Users</div>
                            <div class="fs-4 fw-semibold">{{ $stats['users'] ?? '--' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <div class="row g-3 mt-1">
        <div class="col-12 col-xl-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                    <span class="fw-semibold">Recent Wallpapers</span>
                    <a href="{{ route('wallpapers.index') }}" class="btn btn-sm btn-outline-secondary">View all</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">Title</th>
                                    <th scope="col" class="d-none d-md-table-cell">Image</th>
                                    <th scope="col" class="d-none d-md-table-cell">Created</th>
                                    <th scope="col" class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(($recentWallpapers ?? []) as $wp)
                                    <tr>
                                        <td class="fw-medium">{{ $wp->title ?? 'Untitled' }}</td>
                                        <td class="text-muted d-none d-md-table-cell">
                                            <img src="{{ asset('storage/' . $wp->image_path) }}"
                                                 alt="Wallpaper"
                                                 class="img-thumbnail"
                                                 width="100"></td>
                                        <td class="text-muted d-none d-md-table-cell">{{ $wp->created_at?->diffForHumans() ?? '—' }}</td>
                                        <td class="text-end">
                                            <a href="{{ route('wallpapers.edit', $wp->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-pencil"></i>
                                            </a>

                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">No recent items.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right rail -->
        <div class="col-12 col-xl-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-transparent fw-semibold">Quick actions</div>
                <div class="list-group list-group-flush">
                    <a class="list-group-item list-group-item-action d-flex align-items-center" href="{{ route('categories.index') }}">
                        <i class="bi bi-folder me-2 text-primary"></i> Manage Categories
                    </a>
                    <a class="list-group-item list-group-item-action d-flex align-items-center" href="{{ route('wallpapers.index') }}">
                        <i class="bi bi-image me-2 text-success"></i> Manage Wallpapers
                    </a>
                    <a class="list-group-item list-group-item-action d-flex align-items-center" href="{{ route('category-images.index') }}">
                        <i class="bi bi-card-image me-2 text-warning"></i> Category Images
                    </a>
                    <a class="list-group-item list-group-item-action d-flex align-items-center" href="{{ route('admin.apis.index') }}">
                        <i class="bi bi-gear-wide-connected me-2 text-info"></i> APIs
                    </a>
                </div>
            </div>

            <div class="card shadow-sm border-0 mt-3">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-3" style="width:48px;height:48px;">
                            <i class="bi bi-person text-secondary fs-4"></i>
                        </div>
                        <div>
                            <div class="fw-semibold">{{ Auth::user()->name ?? 'User' }}</div>
                            <div class="text-muted small">{{ Auth::user()->email ?? '' }}</div>
                        </div>
                    </div>
                    <div class="mt-3 d-flex gap-2">
                        <a href="{{ route('home') }}" class="btn btn-sm btn-outline-secondary">Refresh</a>
                        <a href="{{ route('logout') }}" class="btn btn-sm btn-outline-danger"
                           onclick="event.preventDefault(); document.getElementById('logout-form-home').submit();">
                            Logout
                        </a>
                        <form id="logout-form-home" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 
@endsection
