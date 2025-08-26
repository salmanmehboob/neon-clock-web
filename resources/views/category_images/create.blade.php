@extends('layouts.app')

@section('content')
    <div class="container">
        <h4>Add Category Images</h4>

        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('category-images.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="category_id" class="form-label">Select Category</label>
                        <select name="category_id" id="category_id" class="form-control" required>
                            <option value="">-- Choose Category --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Auto name field (hidden) --}}
                    <input type="hidden" name="name" value="">

                    <div class="mb-3">
                        <label class="form-label">Second Hand Image</label>
                        <input type="file" name="second_image" class="form-control" accept="image/*">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Minute Hand Image</label>
                        <input type="file" name="minute_image" class="form-control" accept="image/*">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Hour Hand Image</label>
                        <input type="file" name="hour_image" class="form-control" accept="image/*">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Background Image</label>
                        <input type="file" name="bg_image" class="form-control" accept="image/*">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Preview Image</label>
                        <input type="file" name="preview_image" class="form-control" accept="image/*">
                    </div>

                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-save"></i> Save
                    </button>
                    <a href="{{ route('category-images.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
@endsection
