@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">
        <div class="row g-3 align-items-center mb-4">
            <div class="col">
                <h1 class="h4 mb-1">Categories</h1>
                <p class="text-muted mb-0">Manage your categories and their visibility.</p>
            </div>
            <div class="col-auto d-flex gap-2">
                <div class="d-none d-md-block">
                    <form class="d-flex" role="search" onsubmit="return false;">
                        <input class="form-control" type="search" placeholder="Search categories…" id="categorySearch">
                    </form>
                </div>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
                    <i class="bi bi-plus-lg me-1"></i> Add Category
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
                <span class="fw-semibold">All Categories</span>
                <div class="d-md-none">
                    <form class="d-flex" role="search" onsubmit="return false;">
                        <input class="form-control form-control-sm" type="search" placeholder="Search…" id="categorySearchMobile">
                    </form>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 datatable" id="categoriesTable">
                        <thead class="table-light">
                        <tr>
                            <th style="width: 60px;">#</th>
                            <th>Name</th>
                            <th style="width: 140px;">Status</th>
                            <th class="d-none d-lg-table-cell" style="width: 200px;">Created</th>
                            <th class="text-end" style="width: 160px;">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($categories as $category)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="fw-medium">{{ $category->name }}</td>
                                <td>
                                    <span class="badge rounded-pill bg-{{ $category->status ? 'success' : 'secondary' }}">
                                        {{ $category->status ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="text-muted d-none d-lg-table-cell">{{ $category->created_at->format('d M Y, h:i A') }}</td>
                                <td class="text-end">
                                    <div class="btn-group" role="group">
                                        <button
                                            class="btn btn-sm btn-outline-primary edit-category-btn"
                                            data-id="{{ $category->id }}"
                                            data-name="{{ $category->name }}"
                                            data-status="{{ (int) $category->status }}"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editCategoryModal"
                                            title="Edit"
                                        >
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <form
                                            action="{{ route('categories.destroy', $category->id) }}"
                                            method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Delete this category?');"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-5">
                                    <i class="bi bi-folder2-open fs-3 d-block mb-2"></i>
                                    No categories found. Create your first one.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Category Modal -->
    <div class="modal fade" id="createCategoryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <form action="{{ route('categories.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Add Category</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" placeholder="e.g., Abstract" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Status</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="status" id="statusCreate" value="1" checked>
                                <label class="form-check-label" for="statusCreate">Active</label>
                            </div>
                            <div class="form-text">Toggle off to create as inactive.</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary">Save</button>
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Category Modal (single, populated by JS) -->
    <div class="modal fade" id="editCategoryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <form id="editCategoryForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Category</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" id="editCategoryName" name="name" class="form-control" required>
                        </div>
                        <div>
                            <label class="form-label">Status</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="editCategoryStatus" name="status" value="1">
                                <label class="form-check-label" for="editCategoryStatus">Active</label>
                            </div>
                            <div class="form-text">Toggle off to set inactive.</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-success">Update</button>
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
// Optional: quick search field to filter the table when not using DataTables' own search
document.addEventListener('DOMContentLoaded', function () {
    const table = document.getElementById('categoriesTable');
    const rows = table?.querySelectorAll('tbody tr') || [];
    const desktopSearch = document.getElementById('categorySearch');
    const mobileSearch = document.getElementById('categorySearchMobile');

    function applyFilter(term) {
        const q = (term || '').toLowerCase().trim();
        rows.forEach(tr => {
            const text = tr.innerText.toLowerCase();
            tr.style.display = text.includes(q) ? '' : 'none';
        });
    }

    [desktopSearch, mobileSearch].forEach(inp => {
        if (!inp) return;
        inp.addEventListener('input', (e) => applyFilter(e.target.value));
    });

    // Edit modal population
    const editButtons = document.querySelectorAll('.edit-category-btn');
    const editForm = document.getElementById('editCategoryForm');
    const editName = document.getElementById('editCategoryName');
    const editStatus = document.getElementById('editCategoryStatus');

    editButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            const name = btn.dataset.name;
            const status = Number(btn.dataset.status) === 1;

            editForm.action = `/categories/${id}`;
            editName.value = name;
            editStatus.checked = status;
        });
    });
});
</script>
@endpush
