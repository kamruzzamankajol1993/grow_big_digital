@extends('admin.layout.master')

@section('title', 'Edit Social Link')

@section('body')
@include('flash_message')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0 text-main"><i class="bi bi-pencil-square me-2"></i> Update Social Link</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('socialLink.update', $socialLink->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Platform</label>
                            <select name="title" class="form-select rounded-3" required>
                                @foreach ($socialMediaNames as $platform)
                                    <option value="{{ $platform }}" {{ $socialLink->title == $platform ? 'selected' : '' }}>{{ $platform }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Profile URL</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-link-45deg"></i></span>
                                <input type="url" name="link" value="{{ $socialLink->link }}" class="form-control rounded-end-3" required>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-info text-white px-4 rounded-pill">Update Changes</button>
                            <a href="{{ route('socialLink.index') }}" class="btn btn-light px-4 rounded-pill">Go Back</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection