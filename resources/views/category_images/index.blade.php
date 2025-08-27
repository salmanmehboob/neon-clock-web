@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">
        <div class="row g-3 align-items-center mb-4">
            <div class="col">
                <h1 class="h4 mb-1">Category Images</h1>
                <p class="text-muted mb-0">Manage clock assets per category: hands, background, and preview.</p>
            </div>
            <div class="col-auto">
                <a href="{{ route('category-images.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i> Add Category Images
                </a>
            </div>
        </div>

        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                <span class="fw-semibold">All Entries</span>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table id="categoryImagesTable" class="table table-hover align-middle table-striped mb-0 datatable">
                        <thead class="table-light">
                        <tr>
                            <th style="width: 60px;">#</th>
                            <th>Category</th>
                            <th>Name</th>
                            <th class="text-center" style="width: 90px;">Second</th>
                            <th class="text-center" style="width: 90px;">Minute</th>
                            <th class="text-center" style="width: 90px;">Hour</th>
                            <th class="text-center" style="width: 110px;">Background</th>
                            <th class="text-center" style="width: 110px;">Preview</th>
                            <th class="d-none d-lg-table-cell" style="width: 160px;">Created</th>
                            <th class="text-end" style="width: 140px;">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($categoryImages as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td class="fw-medium">{{ $item->category->name ?? 'N/A' }}</td>
                                <td>{{ $item->name }}</td>

                                <td class="text-center">
                                    @if($item->second_image)
                                        <img src="{{ asset('storage/' . $item->second_image) }}"
                                             alt="Second"
                                             class="rounded object-fit-cover"
                                             style="width:56px;height:56px;">
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    @if($item->minute_image)
                                        <img src="{{ asset('storage/' . $item->minute_image) }}"
                                             alt="Minute"
                                             class="rounded object-fit-cover"
                                             style="width:56px;height:56px;">
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    @if($item->hour_image)
                                        <img src="{{ asset('storage/' . $item->hour_image) }}"
                                             alt="Hour"
                                             class="rounded object-fit-cover"
                                             style="width:56px;height:56px;">
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    @if($item->bg_image)
                                        <img src="{{ asset('storage/' . $item->bg_image) }}"
                                             alt="Background"
                                             class="rounded object-fit-cover"
                                             style="width:72px;height:56px;">
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    @if($item->preview_image)
                                        <img src="{{ asset('storage/' . $item->preview_image) }}"
                                             alt="Preview"
                                             class="rounded object-fit-cover"
                                             style="width:72px;height:56px;">
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>

                                <td class="text-muted d-none d-lg-table-cell">{{ $item->created_at->format('d M, Y') }}</td>
                                <td class="text-end">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('category-images.edit', $item->id) }}"
                                           class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('category-images.destroy', $item->id) }}"
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Delete this entry?');">
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
                                <td colspan="10" class="text-center text-muted py-5">
                                    <i class="bi bi-card-image fs-3 d-block mb-2"></i>
                                    No category images yet. Create your first set.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
