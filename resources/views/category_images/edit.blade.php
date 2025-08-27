@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">
        <div class="row g-3 align-items-center mb-3">
            <div class="col">
                <h1 class="h4 mb-1">Edit Category Images</h1>
                <p class="text-muted mb-0">Update clock assets (hands, background, preview) for this category.</p>
            </div>
            <div class="col-auto">
                <a href="{{ route('category-images.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
            </div>
        </div>

        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-header bg-transparent">
                <span class="fw-semibold">Details</span>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('category-images.update', $categoryImage->id) }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label for="category_id" class="form-label">Select Category</label>
                            <select name="category_id" id="category_id" class="form-select" required>
                                <option value="">-- Choose Category --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ $categoryImage->category_id == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">Please choose a category.</div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label">Generated Name</label>
                            <input type="text" id="generated_name" class="form-control" value="{{ $categoryImage->name }}" readonly>
                            <small class="text-muted">Auto-filled from category. This value is saved.</small>
                            <input type="hidden" name="name" id="hidden_name" value="{{ $categoryImage->name }}">
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="row g-4">
                        <div class="col-12 col-md-6">
                            <label class="form-label">Second Hand Image</label>
                            @if($categoryImage->second_image)
                                <div class="mb-2">
                                    <div class="small text-muted">Current</div>
                                    <img src="{{ asset('storage/' . $categoryImage->second_image) }}" alt="Second Hand" class="img-thumbnail" style="max-width:120px;">
                                </div>
                            @endif
                            <input type="file" name="second_image" id="second_image" class="form-control" accept="image/*">
                            <div class="mt-2 d-flex align-items-center gap-2">
                                <img id="preview_second" class="img-thumbnail d-none" style="max-width:120px;" alt="Second preview">
                                <span id="placeholder_second" class="text-muted small">No new file selected</span>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label">Minute Hand Image</label>
                            @if($categoryImage->minute_image)
                                <div class="mb-2">
                                    <div class="small text-muted">Current</div>
                                    <img src="{{ asset('storage/' . $categoryImage->minute_image) }}" alt="Minute Hand" class="img-thumbnail" style="max-width:120px;">
                                </div>
                            @endif
                            <input type="file" name="minute_image" id="minute_image" class="form-control" accept="image/*">
                            <div class="mt-2 d-flex align-items-center gap-2">
                                <img id="preview_minute" class="img-thumbnail d-none" style="max-width:120px;" alt="Minute preview">
                                <span id="placeholder_minute" class="text-muted small">No new file selected</span>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label">Hour Hand Image</label>
                            @if($categoryImage->hour_image)
                                <div class="mb-2">
                                    <div class="small text-muted">Current</div>
                                    <img src="{{ asset('storage/' . $categoryImage->hour_image) }}" alt="Hour Hand" class="img-thumbnail" style="max-width:120px;">
                                </div>
                            @endif
                            <input type="file" name="hour_image" id="hour_image" class="form-control" accept="image/*">
                            <div class="mt-2 d-flex align-items-center gap-2">
                                <img id="preview_hour" class="img-thumbnail d-none" style="max-width:120px;" alt="Hour preview">
                                <span id="placeholder_hour" class="text-muted small">No new file selected</span>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label">Background Image</label>
                            @if($categoryImage->bg_image)
                                <div class="mb-2">
                                    <div class="small text-muted">Current</div>
                                    <img src="{{ asset('storage/' . $categoryImage->bg_image) }}" alt="Background" class="img-thumbnail" style="max-width:160px;">
                                </div>
                            @endif
                            <input type="file" name="bg_image" id="bg_image" class="form-control" accept="image/*">
                            <div class="mt-2 d-flex align-items-center gap-2">
                                <img id="preview_bg" class="img-thumbnail d-none" style="max-width:160px;" alt="Background preview">
                                <span id="placeholder_bg" class="text-muted small">No new file selected</span>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Preview Image</label>
                            @if($categoryImage->preview_image)
                                <div class="mb-2">
                                    <div class="small text-muted">Current</div>
                                    <img src="{{ asset('storage/' . $categoryImage->preview_image) }}" alt="Preview" class="img-thumbnail" style="max-width:200px;">
                                </div>
                            @endif
                            <input type="file" name="preview_image" id="preview_image" class="form-control" accept="image/*">
                            <div class="mt-2 d-flex align-items-center gap-2">
                                <img id="preview_preview" class="img-thumbnail d-none" style="max-width:200px;" alt="Preview image">
                                <span id="placeholder_preview" class="text-muted small">No new file selected</span>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-save"></i> Update
                        </button>
                        <a href="{{ route('category-images.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
// Sync generated name with category selection
document.addEventListener('DOMContentLoaded', () => {
    const select = document.getElementById('category_id');
    const gen = document.getElementById('generated_name');
    const hidden = document.getElementById('hidden_name');

    const updateName = () => {
        const text = select.options[select.selectedIndex]?.text?.trim() || '';
        const value = text ? `${text} Assets` : gen.value; // fallback to existing if none
        gen.value = value;
        hidden.value = value;
    };
    select.addEventListener('change', updateName);

    // File preview helpers
    const bindPreview = (inputId, imgId, placeholderId) => {
        const input = document.getElementById(inputId);
        const img = document.getElementById(imgId);
        const ph = document.getElementById(placeholderId);

        if (!input) return;
        input.addEventListener('change', () => {
            const file = input.files?.[0];
            if (file) {
                const url = URL.createObjectURL(file);
                img.src = url;
                img.classList.remove('d-none');
                ph?.classList.add('d-none');
            } else {
                img.src = '';
                img.classList.add('d-none');
                ph?.classList.remove('d-none');
            }
        });
    };

    bindPreview('second_image', 'preview_second', 'placeholder_second');
    bindPreview('minute_image', 'preview_minute', 'placeholder_minute');
    bindPreview('hour_image', 'preview_hour', 'placeholder_hour');
    bindPreview('bg_image', 'preview_bg', 'placeholder_bg');
    bindPreview('preview_image', 'preview_preview', 'placeholder_preview');
});
</script>
@endpush
