@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between mb-3">
            <h4>Wallpapers</h4>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createWallpaperModal">
                + Add Wallpaper
            </button>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- Table -->
        <table class="table table-bordered table-striped align-middle datatable">
            <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Preview</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($wallpapers as $wallpaper)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $wallpaper->title }}</td>
                    <td>
                        <img src="{{ asset('storage/' . $wallpaper->image_path) }}"
                             alt="Wallpaper"
                             class="img-thumbnail"
                             width="100">
                    </td>
                    <td>
                        <!-- Edit Button -->
                        <button class="btn btn-sm btn-warning edit-btn"
                                data-id="{{ $wallpaper->id }}"
                                data-title="{{ $wallpaper->title }}"
                                data-image="{{ asset('storage/'.$wallpaper->image_path) }}"
                                data-bs-toggle="modal"
                                data-bs-target="#editWallpaperModal">
                            Edit
                        </button>

                        <!-- Delete -->
                        <form action="{{ route('wallpapers.destroy', $wallpaper->id) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button onclick="return confirm('Are you sure?')" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>

            @endforeach
            </tbody>
        </table>
    </div>

    <!-- Create Modal -->
    <div class="modal fade" id="createWallpaperModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('wallpapers.store') }}" method="POST" enctype="multipart/form-data" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Wallpaper</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Title</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Image</label>
                        <input type="file" name="image" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal (Single, outside loop) -->
    <div class="modal fade" id="editWallpaperModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form id="editWallpaperForm" method="POST" enctype="multipart/form-data" class="modal-content">
                @csrf @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Wallpaper</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Title</label>
                        <input type="text" id="editTitle" name="title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Image (optional)</label>
                        <input type="file" name="image" class="form-control">
                        <img id="editImagePreview" src="" class="mt-2 img-thumbnail" width="100">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success">Update</button>
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
                    let id = this.dataset.id;
                    let title = this.dataset.title;
                    let image = this.dataset.image;

                    // Update form action
                    editForm.action = `/wallpapers/${id}`;

                    // Fill form fields
                    editTitle.value = title;
                    editImagePreview.src = image;
                });
            });
        });
    </script>
@endpush
