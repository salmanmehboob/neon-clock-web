@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">
        <div class="row g-3 align-items-center mb-3">
            <div class="col">
                <h1 class="h4 mb-1">Add Category Images</h1>
                <p class="text-muted mb-0">Upload clock assets (hands, background, preview) for a category.</p>
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
                <form action="{{ route('category-images.store') }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                    @csrf

                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label for="category_id" class="form-label">Select Category</label>
                            <select name="category_id" id="category_id" class="form-select" required>
                                <option value="">-- Choose Category --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            <div class="form-text">The name will be auto-filled based on the selected category.</div>
                            <div class="invalid-feedback">Please choose a category.</div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label">Generated Name</label>
                            <input type="text" id="generated_name" class="form-control" placeholder="Auto-filled from category" disabled>
                        </div>
                    </div>

                    <!-- Hidden auto name -->
                    <input type="hidden" name="name" id="hidden_name" value="">

                    <hr class="my-4">

                    <div class="row g-4">
                        <div class="col-12 col-md-6">
                            <label class="form-label">Second Hand Image</label>
                            <input type="file" name="second_image" id="second_image" class="form-control" accept="image/*">
                            <div class="form-text">Recommended square image with transparent background (PNG/WebP).</div>
                            <div class="mt-2 d-flex align-items-center gap-2">
                                <img id="preview_second" class="img-thumbnail d-none" style="max-width:120px;" alt="Second preview">
                                <span id="placeholder_second" class="text-muted small">No file selected</span>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label">Minute Hand Image</label>
                            <input type="file" name="minute_image" id="minute_image" class="form-control" accept="image/*">
                            <div class="form-text">Recommended square image with transparent background (PNG/WebP).</div>
                            <div class="mt-2 d-flex align-items-center gap-2">
                                <img id="preview_minute" class="img-thumbnail d-none" style="max-width:120px;" alt="Minute preview">
                                <span id="placeholder_minute" class="text-muted small">No file selected</span>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label">Hour Hand Image</label>
                            <input type="file" name="hour_image" id="hour_image" class="form-control" accept="image/*">
                            <div class="form-text">Recommended square image with transparent background (PNG/WebP).</div>
                            <div class="mt-2 d-flex align-items-center gap-2">
                                <img id="preview_hour" class="img-thumbnail d-none" style="max-width:120px;" alt="Hour preview">
                                <span id="placeholder_hour" class="text-muted small">No file selected</span>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label">Background Image</label>
                            <input type="file" name="bg_image" id="bg_image" class="form-control" accept="image/*">
                            <div class="form-text">Prefer 16:9 or 4:3 ratio, high resolution.</div>
                            <div class="mt-2 d-flex align-items-center gap-2">
                                <img id="preview_bg" class="img-thumbnail d-none" style="max-width:160px;" alt="Background preview">
                                <span id="placeholder_bg" class="text-muted small">No file selected</span>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Preview Image</label>
                            <input type="file" name="preview_image" id="preview_image" class="form-control" accept="image/*">
                            <div class="form-text">This is shown in listings. Use a lightweight image.</div>
                            <div class="mt-2 d-flex align-items-center gap-2">
                                <img id="preview_preview" class="img-thumbnail d-none" style="max-width:200px;" alt="Preview image">
                                <span id="placeholder_preview" class="text-muted small">No file selected</span>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Save
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
// Auto-fill name from selected category option text
document.addEventListener('DOMContentLoaded', () => {
    const select = document.getElementById('category_id');
    const gen = document.getElementById('generated_name');
    const hidden = document.getElementById('hidden_name');

    const updateName = () => {
        const text = select.options[select.selectedIndex]?.text?.trim() || '';
        gen.value = text ? `${text} Assets` : '';
        hidden.value = text ? `${text} Assets` : '';
    };
    select.addEventListener('change', updateName);
    updateName();

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
