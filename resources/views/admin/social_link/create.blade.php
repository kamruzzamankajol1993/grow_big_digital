@extends('admin.layout.master')

@section('title', 'Add Social Link')

@section('body')
@include('flash_message')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <h5 class="fw-bold mb-0 text-main"><i class="bi bi-plus-circle me-2"></i> Add Social Media Link</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('socialLink.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Select Platform</label>
                            <select name="title" class="form-select rounded-3" required>
                                <option value="" selected disabled>-- Choose Platform --</option>
                                @foreach ($socialMediaNames as $platform)
                                    <option value="{{ $platform }}">{{ $platform }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Profile URL</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-link-45deg"></i></span>
                                <input type="url" name="link" class="form-control rounded-end-3" placeholder="https://facebook.com/username" required>
                            </div>
                            <div class="form-text">Example: https://www.linkedin.com/in/username</div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary px-4 rounded-pill">Save Link</button>
                            <a href="{{ route('socialLink.index') }}" class="btn btn-light px-4 rounded-pill">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection