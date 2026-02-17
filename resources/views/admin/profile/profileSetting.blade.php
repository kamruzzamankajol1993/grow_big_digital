@extends('admin.layout.master')

@section('title')
Profile Setting | {{ $siteConfig->site_name }}
@endsection

@section('body')
<div class="container-fluid py-4">
    <div class="mb-4">
        <h3 class="fw-bold text-main mb-0">Profile Configuration</h3>
        <p class="text-muted">Manage your personal information and security settings.</p>
    </div>

    <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 15px;">
        <div class="card-body p-0">
            @include('flash_message')

            <form method="post" action="{{ route('profileSettingUpdate') }}" enctype="multipart/form-data" id="form" class="p-4 p-md-5">
                @csrf
                <input type="hidden" name="id" value="{{ Auth::user()->id }}">

                <div class="row align-items-center mb-5 pb-4 border-bottom">
                    <div class="col-md-4 col-xl-3 mb-3 mb-md-0">
                        <h6 class="fw-bold mb-1">Profile Photo</h6>
                        <p class="text-muted small mb-0">Recommended size: 200x200px</p>
                    </div>
                    <div class="col-md-8 col-xl-9">
                        <div class="d-flex align-items-center gap-4">
                            <div class="position-relative">
                                <img src="{{ empty(Auth::user()->image) ? asset('public/No_Image_Available.jpg') : asset(Auth::user()->image) }}" 
                                     alt="Current Profile" id="preview-img" class="rounded-circle border" style="width: 100px; height: 100px; object-fit: cover;">
                            </div>
                            <div class="flex-grow-1">
                                <input class="form-control form-control-sm" type="file" name="image" id="image-input" accept="image/*">
                                <p class="small text-muted mt-2 mb-0">JPEG, PNG, or WebP accepted.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-5 pb-4 border-bottom">
                    <div class="col-md-4 col-xl-3 mb-3 mb-md-0">
                        <h6 class="fw-bold mb-1">General Information</h6>
                        <p class="text-muted small mb-0">Your basic identity on the portal.</p>
                    </div>
                    <div class="col-md-8 col-xl-9">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Full Name</label>
                                <input type="text" name="name" value="{{ Auth::user()->name }}" class="form-control rounded-3" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Email Address</label>
                                <input type="email" name="email" value="{{ Auth::user()->email }}" class="form-control rounded-3" required>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold small">Phone Number</label>
                                <input type="text" name="phone" value="{{ Auth::user()->phone }}" class="form-control rounded-3">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-5">
                    <div class="col-md-4 col-xl-3 mb-3 mb-md-0">
                        <h6 class="fw-bold mb-1">Account Security</h6>
                        <p class="text-muted small mb-0">Update password if you want to change it.</p>
                    </div>
                    <div class="col-md-8 col-xl-9">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">New Password</label>
                                <div class="input-group">
                                    <input type="password" name="password" class="form-control rounded-start-3" placeholder="••••••••">
                                    <span class="input-group-text bg-light border-start-0"><i class="bi bi-lock"></i></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Confirm Password</label>
                                <div class="input-group">
                                    <input type="password" name="confirm-password" class="form-control rounded-start-3" placeholder="••••••••">
                                    <span class="input-group-text bg-light border-start-0"><i class="bi bi-shield-check"></i></span>
                                </div>
                            </div>
                            <div class="col-12 mt-2">
                                <div class="alert alert-soft-warning d-flex align-items-center p-2 small mb-0 border-0 rounded-3">
                                    <i class="bi bi-info-circle me-2"></i> Leave password fields empty to keep current password.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-9 offset-md-3 text-end text-md-start">
                        <button class="btn btn-primary px-5 py-2 rounded-pill shadow-sm" type="submit">
                            <i class="bi bi-cloud-upload me-2"></i> Save Profile Changes
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Simple image preview logic
    document.getElementById('image-input').onchange = evt => {
        const [file] = evt.target.files
        if (file) {
            document.getElementById('preview-img').src = URL.createObjectURL(file)
        }
    }
</script>
@endsection