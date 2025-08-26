@extends('layouts.app')

@section('content')
    <div class="container">
        <h4>Edit Category Image</h4>

        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('category-images.update', $categoryImage->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="category_id" class="form-label">Select Category</label>
                        <select name="category_id" id="category_id" class="form-control" required>
                            <option value="">-- Choose Category --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ $categoryImage->category_id == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Auto name field (readonly) --}}
                    <div class="mb-3">
                        <label class="form-label">Auto Name</label>
                        <input type="text" class="form-control" name="name" value="{{ $categoryImage->name }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Second Hand Image</label>
                        @if($categoryImage->second_image)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $categoryImage->second_image) }}" alt="Second Hand" class="img-thumbnail" style="width:80px; height:80px;">
                            </div>
                        @endif
                        <input type="file" name="second_image" class="form-control" accept="image/*">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Minute Hand Image</label>
                        @if($categoryImage->minute_image)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $categoryImage->minute_image) }}" alt="Minute Hand" class="img-thumbnail" style="width:80px; height:80px;">
                            </div>
                        @endif
                        <input type="file" name="minute_image" class="form-control" accept="image/*">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Hour Hand Image</label>
                        @if($categoryImage->hour_image)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $categoryImage->hour_image) }}" alt="Hour Hand" class="img-thumbnail" style="width:80px; height:80px;">
                            </div>
                        @endif
                        <input type="file" name="hour_image" class="form-control" accept="image/*">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Background Image</label>
                        @if($categoryImage->bg_image)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $categoryImage->bg_image) }}" alt="Background" class="img-thumbnail" style="width:80px; height:80px;">
                            </div>
                        @endif
                        <input type="file" name="bg_image" class="form-control" accept="image/*">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Preview Image</label>
                        @if($categoryImage->preview_image)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $categoryImage->preview_image) }}" alt="Preview" class="img-thumbnail" style="width:80px; height:80px;">
                            </div>
                        @endif
                        <input type="file" name="preview_image" class="form-control" accept="image/*">
                    </div>

                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-save"></i> Update
                    </button>
                    <a href="{{ route('category-images.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
@endsection
