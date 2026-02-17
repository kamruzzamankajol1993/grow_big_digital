@extends('admin.layout.master')

@section('title', 'Edit Testimonial')

@section('css')
<style>
    .preview-box { 
        width: 100px; height: 100px; border: 2px dashed #00a651; 
        border-radius: 10px; display: flex; align-items: center; 
        justify-content: center; overflow: hidden; background: #f8fafc;
    }
    .preview-box img { width: 100%; height: 100%; object-fit: cover; }
</style>
@endsection

@section('body')
<div class="container-fluid py-4">
    <div class="custom-width">
        <div class="d-flex align-items-center mb-4">
            <a href="{{ route('testimonial.index') }}" class="btn btn-outline-secondary btn-sm rounded-circle me-3">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h4 class="fw-bold mb-0">Edit Testimonial</h4>
        </div>

        <div class="card border-0 shadow-sm" style="border-radius: 20px;">
            <div class="card-body p-4 p-md-5">
                <form action="{{ route('testimonial.update', $testimonial->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row g-4">
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Update Image (Recommended: 100x100 px)</label>
                            <div class="d-flex align-items-center gap-3">
                                <div class="preview-box">
                                    <img src="{{ asset('public/'.$testimonial->image) }}" id="imagePreview">
                                </div>
                                <input type="file" name="image" class="form-control" id="imageInput" accept="image/*">
                            </div>
                            <small class="text-muted">Leave empty if you don't want to change the image.</small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Client Name</label>
                            <input type="text" name="name" value="{{ $testimonial->name }}" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Designation</label>
                            <input type="text" name="designation" value="{{ $testimonial->designation }}" class="form-control" required>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-bold">Social/Profile Link</label>
                            <input type="url" name="link" value="{{ $testimonial->link }}" class="form-control">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-bold">Client Feedback</label>
                            <textarea name="short_description" class="form-control" rows="5" required>{{ $testimonial->short_description }}</textarea>
                        </div>

                        <div class="col-12 text-end">
                            <hr>
                            <button type="submit" class="btn btn-primary px-5 rounded-pill fw-bold">
                                <i class="bi bi-check2-circle me-2"></i> Update Testimonial
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('imageInput').onchange = evt => {
        const [file] = document.getElementById('imageInput').files;
        if (file) {
            document.getElementById('imagePreview').src = URL.createObjectURL(file);
        }
    }
</script>
@endsection