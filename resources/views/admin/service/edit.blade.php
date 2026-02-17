@extends('admin.layout.master')

@section('title', 'Edit Service')

@section('css')
<style>
  
    .icon-preview-box { 
        width: 80px; height: 80px; border: 2px solid #00a651; 
        border-radius: 12px; display: flex; align-items: center; 
        justify-content: center; overflow: hidden; background: #fff;
    }
    .icon-preview-box img { width: 50px; height: 50px; object-fit: contain; }
</style>
@endsection

@section('body')
<div class="container-fluid py-4">
    <div class="custom-width">
        <div class="d-flex align-items-center mb-4">
            <a href="{{ route('service.index') }}" class="btn btn-outline-secondary btn-sm rounded-circle me-3"><i class="bi bi-arrow-left"></i></a>
            <h4 class="fw-bold mb-0">Edit Service Information</h4>
        </div>

        <form action="{{ route('service.update', $service->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                <div class="card-body p-4 p-md-5">
                    <div class="row g-4">
                        <div class="col-md-8">
                            <label class="form-label fw-bold">Service Name</label>
                            <input type="text" name="name" value="{{ $service->name }}" class="form-control" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="1" {{ $service->status == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ $service->status == 0 ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-bold">Parent Service</label>
                            <select name="parent_id" class="form-select">
                                <option value="">-- None (This is a Main Service) --</option>
                                @foreach($parentServices as $parent)
                                    <option value="{{ $parent->id }}" {{ $service->parent_id == $parent->id ? 'selected' : '' }}>
                                        {{ $parent->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-bold">Service Icon</label>
                            <div class="d-flex align-items-center gap-4 p-3 border rounded-3 bg-light">
                                <div class="icon-preview-box">
                                    <img src="{{ $service->icon ? asset('public/'.$service->icon) : asset('public/No_Image_Available.jpg') }}" id="iconPreview">
                                </div>
                                <div class="flex-grow-1">
                                    <input type="file" name="icon" class="form-control" id="iconInput" accept="image/*">
                                    <small class="text-muted mt-1 d-block">Leave blank to keep current icon (50x50 px).</small>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-bold">Short Description</label>
                            <textarea name="short_description" class="form-control" rows="4">{{ $service->short_description }}</textarea>
                        </div>

                        <div class="col-12 text-end mt-4 pt-3 border-top">
                            <button type="submit" class="btn btn-primary px-5 rounded-pill fw-bold">Update Service</button>
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
        }
    }
</script>
@endsection