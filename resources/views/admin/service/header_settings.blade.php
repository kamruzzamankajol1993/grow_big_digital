@extends('admin.layout.master')

@section('title', 'Service Header Settings')

@section('css')
<style>
    .custom-width { max-width: 1000px; margin: 0 auto; }
    .config-card { border: none; border-radius: 20px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05); background: #fff; }
    
    /* ট্যাব স্টাইল */
    .nav-tabs-custom { border-bottom: none; gap: 12px; background: #f1f5f9; padding: 8px; border-radius: 16px; display: inline-flex; }
    .nav-tabs-custom .nav-link { border: none; border-radius: 12px; padding: 10px 24px; color: #475569 !important; font-weight: 700; transition: all 0.3s; }
    .nav-tabs-custom .nav-link.active { background: #00a651 !important; color: #fff !important; box-shadow: 0 4px 12px rgba(0, 166, 81, 0.2); }
    
    .form-control { border-radius: 12px; padding: 12px; border: 1px solid #e2e8f0; background: #f8fafc; }
    .form-control:focus { background: #fff; border-color: #00a651; box-shadow: 0 0 0 4px rgba(0, 166, 81, 0.1); }
    .btn-update { background: #00a651; border: none; padding: 12px 40px; border-radius: 12px; font-weight: 700; color: #fff; transition: all 0.3s; }
    .btn-update:hover { background: #008541; transform: translateY(-2px); }
</style>
@endsection

@section('body')
<div class="container-fluid py-4">
    <div class="custom-width">
        <div class="mb-4">
            <ul class="nav nav-tabs nav-tabs-custom shadow-sm">
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('admin/service/items*') ? 'active' : '' }}" href="{{ route('service.index') }}">
                        <i class="bi bi-layers-half me-2"></i> All Services
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('admin/service/header-settings*') ? 'active' : '' }}" href="{{ route('service.header.settings') }}">
                        <i class="bi bi-gear-wide-connected me-2"></i> Header Settings
                    </a>
                </li>
            </ul>
        </div>

        <div class="config-card">
            <div class="card-header bg-white border-0 py-4 px-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-success bg-opacity-10 p-2 rounded-3 text-success">
                        <i class="bi bi-display fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0">Service Section Header</h5>
                        <p class="text-muted small mb-0">Configure the branding and titles of your service showcase area.</p>
                    </div>
                </div>
            </div>

            <div class="card-body p-4 p-md-5 pt-0">
                <form action="{{ route('service.header.update') }}" method="POST">
                    @csrf
                    <div class="row g-4">
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Section Title</label>
                            <input type="text" name="title" value="{{ $header->title ?? '' }}" class="form-control" placeholder="e.g. Services We Offer" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Subtitle One</label>
                            <input type="text" name="subtitle_one" value="{{ $header->subtitle_one ?? '' }}" class="form-control" placeholder="e.g. Expertise">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Subtitle Two</label>
                            <input type="text" name="subtitle_two" value="{{ $header->subtitle_two ?? '' }}" class="form-control" placeholder="e.g. We provide best solutions for your business.">
                        </div>

                        <div class="col-12 mt-4 text-end">
                            <hr class="mb-4">
                            <button type="submit" class="btn btn-update shadow-sm">
                                <i class="bi bi-check2-circle me-2"></i> Save Header Changes
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection