@extends('admin.layout.master')

@section('title', 'Testimonial Header Settings')

@section('css')
<style>
    /* কন্টেইনার গ্যাপ কমানোর জন্য */


    .config-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        background: #fff;
    }

    /* ট্যাব স্টাইল */
    .nav-tabs-custom {
        border-bottom: none;
        gap: 12px;
        background: #f1f5f9;
        padding: 8px;
        border-radius: 16px;
        display: inline-flex;
    }

    .nav-tabs-custom .nav-link {
        border: none;
        border-radius: 12px;
        padding: 10px 24px;
        color: #475569 !important;
        font-weight: 700;
        transition: all 0.3s;
    }

    .nav-tabs-custom .nav-link.active {
        background: #00a651 !important;
        color: #fff !important;
        box-shadow: 0 4px 12px rgba(0, 166, 81, 0.2);
    }

    .form-control {
        border-radius: 12px;
        padding: 12px;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
    }

    .form-control:focus {
        background: #fff;
        border-color: #00a651;
        box-shadow: 0 0 0 4px rgba(0, 166, 81, 0.1);
    }

    .btn-update {
        background: #00a651;
        border: none;
        padding: 12px 40px;
        border-radius: 12px;
        font-weight: 700;
        color: #fff;
        transition: all 0.3s;
    }

    .btn-update:hover {
        background: #008541;
        transform: translateY(-2px);
    }
</style>
@endsection

@section('body')
<div class="container-fluid py-4">
    <div class="custom-width">
        <div class="mb-4">
            <ul class="nav nav-tabs nav-tabs-custom shadow-sm">
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('admin/testimonial/items*') ? 'active' : '' }}" href="{{ route('testimonial.index') }}">
                        <i class="bi bi-people me-2"></i> Client Testimonials
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('admin/testimonial/header-settings*') ? 'active' : '' }}" href="{{ route('testimonial.header.settings') }}">
                        <i class="bi bi-gear-wide-connected me-2"></i> Header Settings
                    </a>
                </li>
            </ul>
        </div>

        <div class="config-card">
            <div class="card-header bg-white border-0 py-4 px-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-success bg-opacity-10 p-2 rounded-3 text-success">
                        <i class="bi bi-layout-text-window fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0">Testimonial Section Branding</h5>
                        <p class="text-muted small mb-0">Customize the titles and descriptions of your client feedback section.</p>
                    </div>
                </div>
            </div>

            <div class="card-body p-4 p-md-5 pt-0">
                <form action="{{ route('testimonial.header.update') }}" method="POST">
                    @csrf
                    <div class="row g-4">
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Section Main Title</label>
                            <input type="text" name="title" value="{{ $header->title ?? '' }}" class="form-control" placeholder="e.g. What Our Clients Say" required>
                            <div class="form-text">This will be the main heading for the testimonial section.</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Subtitle (Primary)</label>
                            <input type="text" name="subtitle_one" value="{{ $header->subtitle_one ?? '' }}" class="form-control" placeholder="e.g. Testimonials">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Subtitle (Secondary)</label>
                            <input type="text" name="subtitle_two" value="{{ $header->subtitle_two ?? '' }}" class="form-control" placeholder="e.g. Real Stories from Real Clients">
                        </div>

                        <div class="col-12 mt-4 text-end">
                            <hr class="mb-4">
                            <button type="submit" class="btn btn-update shadow-sm">
                                <i class="bi bi-check2-circle me-2"></i> Update Header Settings
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection