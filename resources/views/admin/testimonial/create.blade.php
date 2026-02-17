@extends('admin.layout.master')

@section('title', 'Add New Testimonial')

@section('css')
<style>
  
    .preview-box { 
        width: 100px; height: 100px; border: 2px dashed #ddd; 
        border-radius: 10px; display: flex; align-items: center; 
        justify-content: center; overflow: hidden; background: #f8fafc;
    }
    .preview-box img { width: 100%; height: 100%; object-fit: cover; display: none; }
</style>
@endsection

@section('body')
<div class="container-fluid py-4">
    <div class="custom-width">
        <div class="d-flex align-items-center mb-4">
            <a href="{{ route('testimonial.index') }}" class="btn btn-outline-secondary btn-sm rounded-circle me-3">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h4 class="fw-bold mb-0">Add New Testimonial</h4>
        </div>

        <div class="card border-0 shadow-sm" style="border-radius: 20px;">
            <div class="card-body p-4 p-md-5">
                <form action="{{ route('testimonial.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-4">
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Client Image (Recommended: 100x100 px)</label>
                            <div class="d-flex align-items-center gap-3">
                                <div class="preview-box" id="imagePreviewContainer">
                                    <i class="bi bi-image text-muted" id="placeholderIcon"></i>
                                    <img src="" id="imagePreview">
                                </div>
                                <input type="file" name="image" class="form-control" id="imageInput" accept="image/*" required>
                            </div>
                            @error('image') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Client Name</label>
                            <input type="text" name="name" class="form-control" placeholder="Enter name" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Designation</label>
                            <input type="text" name="designation" class="form-control" placeholder="e.g. CEO, Tech Solution" required>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-bold">Social/Profile Link (Optional)</label>
                            <input type="url" name="link" class="form-control" placeholder="https://linkedin.com/in/username">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-bold">Client Feedback</label>
                            <textarea name="short_description" class="form-control" rows="5" placeholder="Write the testimonial content here..." required></textarea>
                        </div>

                        <div class="col-12 text-end">
                            <hr>
                            <button type="submit" class="btn btn-success px-5 rounded-pill fw-bold">
                                <i class="bi bi-cloud-arrow-up me-2"></i> Save Testimonial
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
    // ইমেজ প্রিভিউ স্ক্রিপ্ট
    document.getElementById('imageInput').onchange = evt => {
        const [file] = document.getElementById('imageInput').files;
        if (file) {
            document.getElementById('imagePreview').src = URL.createObjectURL(file);
            document.getElementById('imagePreview').style.display = 'block';
            document.getElementById('placeholderIcon').style.display = 'none';
        }
    }
</script>
@endsection