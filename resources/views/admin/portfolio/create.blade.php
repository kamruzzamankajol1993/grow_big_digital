@extends('admin.layout.master')

@section('title', 'Add New Project')

@section('css')
<style>
    .custom-width { max-width: 900px; margin: 0 auto; }
    .img-preview-box { 
        width: 100%; height: 200px; border: 2px dashed #cbd5e1; 
        border-radius: 15px; display: flex; align-items: center; 
        justify-content: center; overflow: hidden; background: #f8fafc;
    }
    .img-preview-box img { width: 100%; height: 100%; object-fit: cover; display: none; }
</style>
@endsection

@section('body')
<div class="container-fluid py-4">
    <div class="custom-width">
        <div class="d-flex align-items-center mb-4">
            <a href="{{ route('portfolio.index') }}" class="btn btn-outline-secondary btn-sm rounded-circle me-3"><i class="bi bi-arrow-left"></i></a>
            <h4 class="fw-bold mb-0">Add New Portfolio Project</h4>
        </div>

        <form action="{{ route('portfolio.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                <div class="card-body p-4 p-md-5">
                    <div class="row g-4">
                        <div class="col-md-7">
                            <label class="form-label fw-bold">Project Title</label>
                            <input type="text" name="title" class="form-control" placeholder="Project Name" required>
                        </div>

                        <div class="col-md-5">
                            <label class="form-label fw-bold">Associated Service</label>
                            <select name="service_id" class="form-select" required>
                                <option value="">Select Service</option>
                                @foreach($services as $service)
                                    <option value="{{ $service->id }}">{{ $service->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-bold">Subtitle</label>
                            <input type="text" name="subtitle" class="form-control" placeholder="Short tagline for the project">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Project Thumbnail (Image)</label>
                            <div class="img-preview-box mb-2" id="previewBox">
                                <i class="bi bi-image fs-1 text-muted" id="placeholderIcon"></i>
                                <img src="" id="imgPreview">
                            </div>
                            <input type="file" name="image" class="form-control" id="imgInput" accept="image/*" required>
                            <small class="text-muted">Recommended size: 1000x600 px</small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Video Embed Code (Iframe)</label>
                            <textarea name="video_link" class="form-control" rows="8" placeholder="Paste YouTube or Facebook <iframe> code here..."></textarea>
                            <small class="text-muted d-block mt-2">Example: &lt;iframe src="..."&gt;&lt;/iframe&gt;</small>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-bold">Project Description</label>
                            <textarea name="description" class="form-control" rows="4" placeholder="Detailed project information..."></textarea>
                        </div>

                        <div class="col-12 text-end mt-4 pt-3 border-top">
                            <button type="submit" class="btn btn-success px-5 rounded-pill fw-bold shadow-sm">Save Project</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('imgInput').onchange = evt => {
        const [file] = document.getElementById('imgInput').files;
        if (file) {
            document.getElementById('imgPreview').src = URL.createObjectURL(file);
            document.getElementById('imgPreview').style.display = 'block';
            document.getElementById('placeholderIcon').style.display = 'none';
        }
    }
</script>
@endsection