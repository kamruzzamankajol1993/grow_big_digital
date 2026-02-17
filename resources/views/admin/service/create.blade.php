@extends('admin.layout.master')

@section('title', 'Add New Service')

@section('css')
<style>
    
    .icon-preview-box { 
        width: 80px; height: 80px; border: 2px dashed #cbd5e1; 
        border-radius: 12px; display: flex; align-items: center; 
        justify-content: center; overflow: hidden; background: #f8fafc;
    }
    .icon-preview-box img { width: 50px; height: 50px; object-fit: contain; display: none; }
</style>
@endsection

@section('body')
<div class="container-fluid py-4">
    <div class="custom-width">
        <div class="d-flex align-items-center mb-4">
            <a href="{{ route('service.index') }}" class="btn btn-outline-secondary btn-sm rounded-circle me-3"><i class="bi bi-arrow-left"></i></a>
            <h4 class="fw-bold mb-0">Create New Service</h4>
        </div>

        <form action="{{ route('service.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                <div class="card-body p-4 p-md-5">
                    <div class="row g-4">
                        <div class="col-md-8">
                            <label class="form-label fw-bold">Service Name</label>
                            <input type="text" name="name" class="form-control" placeholder="e.g. Web Development" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-bold">Parent Service (Optional)</label>
                            <select name="parent_id" class="form-select">
                                <option value="">-- None (This is a Main Service) --</option>
                                @foreach($parentServices as $parent)
                                    <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                                @endforeach
                            </select>
                            <div class="form-text">Select a parent if this is a sub-service.</div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-bold">Service Icon (50x50 px)</label>
                            <div class="d-flex align-items-center gap-4 p-3 border rounded-3 bg-light">
                                <div class="icon-preview-box" id="previewBox">
                                    <i class="bi bi-image fs-2 text-muted" id="placeholderIcon"></i>
                                    <img src="" id="iconPreview">
                                </div>
                                <div class="flex-grow-1">
                                    <input type="file" name="icon" class="form-control" id="iconInput" accept="image/*">
                                    <small class="text-muted mt-1 d-block">Recommended size: 50x50 pixels. SVG/PNG preferred.</small>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-bold">Short Description</label>
                            <textarea name="short_description" class="form-control" rows="4" placeholder="Briefly describe the service..."></textarea>
                        </div>

                        <div class="col-12 text-end mt-4 pt-3 border-top">
                            <button type="submit" class="btn btn-success px-5 rounded-pill fw-bold">Save Service</button>
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
    document.getElementById('iconInput').onchange = evt => {
        const [file] = document.getElementById('iconInput').files;
        if (file) {
            document.getElementById('iconPreview').src = URL.createObjectURL(file);
            document.getElementById('iconPreview').style.display = 'block';
            document.getElementById('placeholderIcon').style.display = 'none';
        }
    }
</script>
@endsection