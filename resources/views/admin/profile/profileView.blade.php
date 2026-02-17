@extends('admin.layout.master')

@section('title')
Profile | {{ $siteConfig->site_name }}
@endsection

@section('css')
<style>
    .profile-header-bg {
        background: linear-gradient(135deg, #00a651 0%, #006b34 100%);
        height: 150px;
        border-radius: 15px 15px 0 0;
    }
    .profile-img-wrapper {
        margin-top: -75px;
        position: relative;
        display: inline-block;
    }
    .profile-img-wrapper img {
        width: 150px;
        height: 150px;
        object-fit: cover;
        border: 5px solid #fff;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    .info-label {
        width: 140px;
        font-weight: 600;
        color: #6c757d;
        font-size: 0.9rem;
    }
    .info-value {
        color: #2b3674;
        font-weight: 500;
        font-size: 0.95rem;
    }
    .detail-card {
        border-radius: 15px;
        transition: transform 0.3s ease;
    }
    .detail-card:hover {
        transform: translateY(-5px);
    }
</style>
@endsection

@section('body')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-main mb-0">User Profile</h3>
        <a href="{{ route('profileSetting') }}" class="btn btn-primary px-4 rounded-pill">
            <i class="bi bi-pencil-square me-2"></i> Edit Profile
        </a>
    </div>

    @php
        $designationName = DB::table('designations')->where('id', Auth::user()->designation_id)->value('name');
        $branchName = DB::table('branches')->where('id', Auth::user()->branch_id)->value('name');
    @endphp

    <div class="row">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm text-center mb-4 overflow-hidden">
                <div class="profile-header-bg"></div>
                <div class="card-body pt-0">
                    <div class="profile-img-wrapper">
                        <img src="{{ empty(Auth::user()->image) ? asset('public/No_Image_Available.jpg') : asset(Auth::user()->image) }}" 
                             class="rounded-circle" alt="User Avatar">
                    </div>
                    <h4 class="mt-3 fw-bold text-main">{{ Auth::user()->name }}</h4>
                    <p class="text-muted mb-3">{{ $designationName ?? 'User' }}</p>
                    <hr class="my-4 opacity-50">
                    <div class="d-flex justify-content-around">
                        <div>
                            <p class="mb-0 text-muted small">Branch</p>
                            <span class="badge bg-soft-success text-success px-3">{{ $branchName ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card border-0 shadow-sm detail-card">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="fw-bold mb-0"><i class="bi bi-person-lines-fill me-2 text-primary"></i> Personal Details</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="d-flex flex-column">
                                <span class="info-label">Full Name</span>
                                <span class="info-value">{{ Auth::user()->name }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex flex-column">
                                <span class="info-label">Email Address</span>
                                <span class="info-value text-primary">{{ Auth::user()->email }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex flex-column">
                                <span class="info-label">Phone Number</span>
                                <span class="info-value">{{ Auth::user()->phone }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex flex-column">
                                <span class="info-label">Designation</span>
                                <span class="info-value">{{ $designationName }}</span>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex flex-column">
                                <span class="info-label">Office Address</span>
                                <span class="info-value text-muted">{{ Auth::user()->address ?? 'Address not updated yet.' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection