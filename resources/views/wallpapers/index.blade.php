@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">
        <div class="row g-3 align-items-center mb-4">
            <div class="col">
                <h1 class="h4 mb-1">Wallpapers</h1>
                <p class="text-muted mb-0">Manage your wallpaper library, uploads, and updates.</p>
            </div>
            <div class="col-auto">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createWallpaperModal">
                    <i class="bi bi-upload me-1"></i> Add Wallpaper
                </button>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                <span class="fw-semibold">All Wallpapers</span>
                <div class="text-muted small">Total: {{ $wallpapers->count() }}</div>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 datatable">
                        <thead class="table-light">
                        <tr>
                            <th style="width: 60px;">#</th>
                            <th>Title</th>
                            <th style="width: 140px;">Preview</th>
                            <th class="text-end" style="width: 180px;">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($wallpapers as $wallpaper)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="fw-medium">{{ $wallpaper->title }}</td>
                                <td>
                                    <div class="ratio ratio-1x1" style="max-width: 100px;">
                                        <img
                                            src="{{ asset('storage/' . $wallpaper->image_path) }}"
                                            alt="{{ $wallpaper->title }}"
                                            class="rounded object-fit-cover"
                                            loading="lazy"
                                        >
                                    </div>
                                </td>
                                <td class="text-end">
                                    <div class="btn-group" role="group">
                                        <a href="{{ asset('storage/' . $wallpaper->image_path) }}" target="_blank" class="btn btn-sm btn-outline-secondary" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <button
                                            class="btn btn-sm btn-outline-primary edit-btn"
                                            data-id="{{ $wallpaper->id }}"
                                            data-title="{{ $wallpaper->title }}"
                                            data-image="{{ asset('storage/'.$wallpaper->image_path) }}"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editWallpaperModal"
                                            title="Edit"
                                        >
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <form action="{{ route('wallpapers.destroy', $wallpaper->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this wallpaper?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-5">
                                    <i class="bi bi-image fs-3 d-block mb-2"></i>
                                    No wallpapers found. Upload your first one.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Wallpaper Modal -->
    <div class="modal fade" id="createWallpaperModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="{{ route('wallpapers.store') }}" method="POST" enctype="multipart/form-data" class="modal-content border-0 shadow">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Wallpaper</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" placeholder="e.g., Sunset Mountain" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Image</label>
                        <input type="file" name="image" class="form-control" accept="image/*" required>
                        <div class="form-text">Supported: JPG, PNG, WEBP. Max size per your server limits.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Wallpaper Modal -->
    <div class="modal fade" id="editWallpaperModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form id="editWallpaperForm" method="POST" enctype="multipart/form-data" class="modal-content border-0 shadow">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Wallpaper</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" id="editTitle" name="title" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Replace Image (optional)</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>
                    <div class="mt-2">
                        <div class="small text-muted mb-1">Current preview</div>
                        <img id="editImagePreview" src="" class="img-thumbnail" style="max-width: 160px;">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success">Update</button>
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {
    const editButtons = document.querySelectorAll(".edit-btn");
    const editForm = document.getElementById("editWallpaperForm");
    const editTitle = document.getElementById("editTitle");
    const editImagePreview = document.getElementById("editImagePreview");

    editButtons.forEach(btn => {
        btn.addEventListener("click", function() {
            const id = this.dataset.id;
            const title = this.dataset.title;
            const image = this.dataset.image;

            editForm.action = `/wallpapers/${id}`;
            editTitle.value = title;
            editImagePreview.src = image;
        });
    });
});
</script>
@endpush
