@extends('admin.layout.master')

@section('title', 'Edit Project')

@section('css')
<style>
    .custom-width { max-width: 900px; margin: 0 auto; }
    .img-preview-box { 
        width: 100%; height: 200px; border: 2px solid var(--primary); 
        border-radius: 15px; display: flex; align-items: center; 
        justify-content: center; overflow: hidden; background: #fff;
    }
    .img-preview-box img { width: 100%; height: 100%; object-fit: cover; }
</style>
@endsection

@section('body')
<div class="container-fluid py-4">
    <div class="custom-width">
        <div class="d-flex align-items-center mb-4">
            <a href="{{ route('portfolio.index') }}" class="btn btn-outline-secondary btn-sm rounded-circle me-3"><i class="bi bi-arrow-left"></i></a>
            <h4 class="fw-bold mb-0">Edit Project: {{ $portfolio->title }}</h4>
        </div>

        <form action="{{ route('portfolio.update', $portfolio->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                <div class="card-body p-4 p-md-5">
                    <div class="row g-4">
                        <div class="col-md-7">
                            <label class="form-label fw-bold">Project Title</label>
                            <input type="text" name="title" value="{{ $portfolio->title }}" class="form-control" required>
                        </div>

                        <div class="col-md-5">
                            <label class="form-label fw-bold">Associated Service</label>
                            <select name="service_id" class="form-select" required>
                                @foreach($services as $service)
                                    <option value="{{ $service->id }}" {{ $portfolio->service_id == $service->id ? 'selected' : '' }}>
                                        {{ $service->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-bold">Subtitle</label>
                            <input type="text" name="subtitle" value="{{ $portfolio->subtitle }}" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Change Thumbnail</label>
                            <div class="img-preview-box mb-2">
                                <img src="{{ asset($portfolio->image) }}" id="imgPreview">
                            </div>
                            <input type="file" name="image" class="form-control" id="imgInput" accept="image/*">
                            <small class="text-muted">Leave blank to keep current image</small>
                             <small class="text-muted">Recommended size: 1000x600 px</small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Update Video Embed Code</label>
                            <textarea name="video_link" class="form-control" rows="8">{{ $portfolio->video_link }}</textarea>
                            <small class="text-muted d-block mt-2">Paste new Facebook/YouTube iframe if needed.</small>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-bold">Project Description</label>
                            <textarea name="description" class="form-control" rows="4">{{ $portfolio->description }}</textarea>
                        </div>

                        <div class="col-12 text-end mt-4 pt-3 border-top">
                            <button type="submit" class="btn btn-primary px-5 rounded-pill fw-bold">Update Portfolio</button>
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
        }
    }
</script>
@endsection