@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between mb-3">
            <h4>Category Images</h4>
            <a href="{{ route('category-images.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Add Category Images
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <table id="categoryImagesTable" class="table table-bordered table-striped datatable">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Category</th>
                        <th>Name</th>
                        <th>Second Image</th>
                        <th>Minute Image</th>
                        <th>Hour Image</th>
                        <th>Background Image</th>
                        <th>Preview Image</th>
                        <th>Created At</th>
                        <th width="120">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($categoryImages as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->category->name ?? 'N/A' }}</td>
                            <td>{{ $item->name }}</td>

                            <td>
                                @if($item->second_image)
                                    <img src="{{ asset('storage/' . $item->second_image) }}"
                                         alt="Second"
                                         class="img-thumbnail"
                                         style="width: 60px; height: 60px; object-fit: cover;">
                                @endif
                            </td>

                            <td>
                                @if($item->minute_image)
                                    <img src="{{ asset('storage/' . $item->minute_image) }}"
                                         alt="Minute"
                                         class="img-thumbnail"
                                         style="width: 60px; height: 60px; object-fit: cover;">
                                @endif
                            </td>

                            <td>
                                @if($item->hour_image)
                                    <img src="{{ asset('storage/' . $item->hour_image) }}"
                                         alt="Hour"
                                         class="img-thumbnail"
                                         style="width: 60px; height: 60px; object-fit: cover;">
                                @endif
                            </td>

                            <td>
                                @if($item->bg_image)
                                    <img src="{{ asset('storage/' . $item->bg_image) }}"
                                         alt="Background"
                                         class="img-thumbnail"
                                         style="width: 60px; height: 60px; object-fit: cover;">
                                @endif
                            </td>

                            <td>
                                @if($item->preview_image)
                                    <img src="{{ asset('storage/' . $item->preview_image) }}"
                                         alt="Preview"
                                         class="img-thumbnail"
                                         style="width: 60px; height: 60px; object-fit: cover;">
                                @endif
                            </td>

                            <td>{{ $item->created_at->format('d M, Y') }}</td>
                            <td>
                                <a href="{{ route('category-images.edit', $item->id) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('category-images.destroy', $item->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Delete this entry?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
