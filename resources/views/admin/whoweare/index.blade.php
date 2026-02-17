@extends('admin.layout.master')

@section('title', 'Who We Are Settings')

@section('css')
<style>
    .custom-width { max-width: 1000px; margin: 0 auto; }
    .feature-item { 
        background: #f8fafc; border: 1px solid #e2e8f0; 
        border-radius: 15px; padding: 20px; position: relative; transition: 0.3s;
    }
    .feature-item:hover { border-color: #00a651; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
    .remove-feature { position: absolute; top: 10px; right: 10px; z-index: 10; }
    .nav-tabs-custom { border-bottom: none; gap: 12px; background: #f1f5f9; padding: 8px; border-radius: 16px; display: inline-flex; }
    .nav-tabs-custom .nav-link { border: none; border-radius: 12px; padding: 10px 24px; color: #475569 !important; font-weight: 700; transition: 0.3s; }
    .nav-tabs-custom .nav-link.active { background: #00a651 !important; color: #fff !important; box-shadow: 0 4px 12px rgba(0, 166, 81, 0.2); }
</style>
@endsection

@section('body')
<div class="container-fluid py-4">
    <div class="custom-width">
        <div class="mb-4">
            <ul class="nav nav-tabs nav-tabs-custom shadow-sm">
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('whoweare.index') }}">
                        <i class="bi bi-person-badge me-2"></i> Who We Are Content
                    </a>
                </li>
            </ul>
        </div>

        <form action="{{ route('whoweare.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px;">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h5 class="fw-bold mb-0">Main Information</h5>
                </div>
                <div class="card-body p-4 px-md-5 pt-0">
                    <div class="row g-4">
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Main Title</label>
                            <input type="text" name="title" value="{{ $data->title ?? '' }}" class="form-control" placeholder="Enter title" required>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Subtitle One</label>
                            <input type="text" name="subtitle_one" value="{{ $data->subtitle_one ?? '' }}" class="form-control">
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Subtitle Two</label>
                            <input type="text" name="subtitle_two" value="{{ $data->subtitle_two ?? '' }}" class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Subtitle Three</label>
                            <input type="text" name="subtitle_three" value="{{ $data->subtitle_three ?? '' }}" class="form-control">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-bold">Short Description</label>
                            <textarea name="short_description" class="form-control" rows="3">{{ $data->short_description ?? '' }}</textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Section Image (800x533px)</label>
                            <input type="file" name="image" class="form-control mb-2">
                            @if(isset($data->image))
                                <img src="{{ asset($data->image) }}" class="rounded shadow-sm" style="height: 80px;">
                            @endif
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Button Name</label>
                            <input type="text" name="button_name" value="{{ $data->button_name ?? '' }}" class="form-control">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">Feature List Items</h5>
                    <button type="button" class="btn btn-sm btn-success rounded-pill px-3" id="add-feature">
                        <i class="bi bi-plus-lg me-1"></i> Add Item
                    </button>
                </div>
                <div class="card-body p-4 px-md-5 pt-2">
                    <div id="feature-wrapper" class="row g-3">
                        @if(isset($data) && $data->listItems->count() > 0)
                            @foreach($data->listItems as $index => $item)
                            <div class="col-md-6 feature-block">
                                <div class="feature-item">
                                    <button type="button" class="btn btn-sm btn-outline-danger remove-feature border-0 rounded-circle">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                    <div class="mb-2">
                                        <label class="small fw-bold text-muted">Icon Class (Bootstrap/FontAwesome)</label>
                                        <input type="text" name="feature_icons[]" value="{{ $item->icon }}" class="form-control form-control-sm" placeholder="e.g. bi bi-check">
                                    </div>
                                    <div class="mb-2">
                                        <label class="small fw-bold text-muted">Feature Title</label>
                                        <input type="text" name="feature_titles[]" value="{{ $item->title }}" class="form-control form-control-sm" placeholder="Title" required>
                                    </div>
                                    <div>
                                        <label class="small fw-bold text-muted">Short Description</label>
                                        <textarea name="feature_descriptions[]" class="form-control form-control-sm" rows="2">{{ $item->short_description }}</textarea>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @else
                            <div class="col-md-6 feature-block">
                                <div class="feature-item">
                                    <button type="button" class="btn btn-sm btn-outline-danger remove-feature border-0 rounded-circle"><i class="bi bi-x-lg"></i></button>
                                    <div class="mb-2">
                                        <label class="small fw-bold text-muted">Icon Class</label>
                                        <input type="text" name="feature_icons[]" class="form-control form-control-sm" placeholder="bi bi-star">
                                    </div>
                                    <div class="mb-2">
                                        <label class="small fw-bold text-muted">Feature Title</label>
                                        <input type="text" name="feature_titles[]" class="form-control form-control-sm" placeholder="Title">
                                    </div>
                                    <div>
                                        <label class="small fw-bold text-muted">Short Description</label>
                                        <textarea name="feature_descriptions[]" class="form-control form-control-sm" rows="2"></textarea>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="col-12 text-center mt-5 pt-3 border-top">
                        <button type="submit" class="btn btn-success px-5 py-2 rounded-pill fw-bold shadow">
                            <i class="bi bi-cloud-arrow-up me-2"></i> Save All Changes
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Add new feature block
        $('#add-feature').click(function() {
            let html = `
            <div class="col-md-6 feature-block">
                <div class="feature-item">
                    <button type="button" class="btn btn-sm btn-outline-danger remove-feature border-0 rounded-circle">
                        <i class="bi bi-x-lg"></i>
                    </button>
                    <div class="mb-2">
                        <label class="small fw-bold text-muted">Icon Class</label>
                        <input type="text" name="feature_icons[]" class="form-control form-control-sm" placeholder="bi bi-star">
                    </div>
                    <div class="mb-2">
                        <label class="small fw-bold text-muted">Feature Title</label>
                        <input type="text" name="feature_titles[]" class="form-control form-control-sm" placeholder="Title" required>
                    </div>
                    <div>
                        <label class="small fw-bold text-muted">Short Description</label>
                        <textarea name="feature_descriptions[]" class="form-control form-control-sm" rows="2"></textarea>
                    </div>
                </div>
            </div>`;
            $('#feature-wrapper').append(html);
        });

        // Remove feature block
        $(document).on('click', '.remove-feature', function() {
            if($('.feature-block').length > 1) {
                $(this).closest('.feature-block').remove();
            } else {
                alert('At least one feature item is required.');
            }
        });
    });
</script>
@endsection