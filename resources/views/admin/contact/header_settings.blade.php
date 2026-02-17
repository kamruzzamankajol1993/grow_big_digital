@extends('admin.layout.master')

@section('title', 'Contact Header Settings')

@section('css')
<style>
   

    .config-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        background: #fff;
    }

    /* ট্যাবের দৃশ্যমানতা বাড়ানোর জন্য আপডেট */
    .nav-tabs-custom {
        border-bottom: none;
        gap: 15px;
        background: #f1f5f9;
        padding: 10px;
        border-radius: 15px;
        display: inline-flex; /* ট্যাবগুলোকে বামপাশে রাখার জন্য */
    }

    .nav-tabs-custom .nav-link {
        border: none;
        border-radius: 10px;
        padding: 12px 24px;
        color: #475569 !important; /* ইন-অ্যাক্টিভ ট্যাবের টেক্সট কালার ডার্ক করা হয়েছে */
        font-weight: 700;
        transition: all 0.3s;
        display: flex;
        align-items: center;
    }

    .nav-tabs-custom .nav-link i {
        color: #64748b;
    }

    .nav-tabs-custom .nav-link.active {
        background: #00a651 !important;
        color: #ffffff !important; /* অ্যাক্টিভ ট্যাবের টেক্সট সাদা */
    }

    .nav-tabs-custom .nav-link.active i {
        color: #ffffff;
    }

    .nav-tabs-custom .nav-link:hover:not(.active) {
        background: #e2e8f0;
    }

    /* ফর্ম সেটিংস */
    .card-header-custom {
        background: #f8fafc;
        padding: 1.5rem;
        border-bottom: 1px solid #edf2f7;
        border-radius: 20px 20px 0 0;
    }

    .form-control {
        border-radius: 12px;
        padding: 12px;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
    }

    .btn-update {
        background: #00a651;
        border: none;
        padding: 12px 40px;
        border-radius: 12px;
        font-weight: 700;
        color: #fff;
    }
</style>
@endsection

@section('body')
<div class="container-fluid py-4">
    <div class="custom-width">
        <div class="mb-4">
            <ul class="nav nav-tabs nav-tabs-custom shadow-sm">
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('admin/contact/messages*') ? 'active' : '' }}" href="{{ route('contact.messages.index') }}">
                        <i class="bi bi-chat-left-dots me-2"></i> User Messages
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('admin/contact/header-settings*') ? 'active' : '' }}" href="{{ route('contact.header.settings') }}">
                        <i class="bi bi-layout-text-window-reverse me-2"></i> Header Settings
                    </a>
                </li>
            </ul>
        </div>

        <div class="config-card">
            <div class="card-header-custom">
                <div class="d-flex align-items-center gap-3">
                    <div class="section-icon bg-success-light p-2 rounded text-success">
                        <i class="bi bi-pencil-square fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0">Contact Page Branding</h5>
                        <p class="text-muted small mb-0">Customize the text and appearance of your contact us page header.</p>
                    </div>
                </div>
            </div>

            <div class="card-body p-4 p-md-5">
                <form action="{{ route('contact.header.update') }}" method="POST">
                    @csrf
                    <div class="row g-4">
                        <div class="col-md-12">
                            <label class="form-label">Main Display Title</label>
                            <input type="text" name="title" value="{{ $header->title ?? '' }}" class="form-control" placeholder="e.g. Let's Start a Conversation" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Subtitle (Primary)</label>
                            <input type="text" name="subtitle_one" value="{{ $header->subtitle_one ?? '' }}" class="form-control" placeholder="Subtitle 1">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Subtitle (Secondary)</label>
                            <input type="text" name="subtitle_two" value="{{ $header->subtitle_two ?? '' }}" class="form-control" placeholder="Subtitle 2">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Form Submit Button Text</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-cursor"></i></span>
                                <input type="text" name="button_name" value="{{ $header->button_name ?? '' }}" class="form-control border-start-0">
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Short Introductory Description</label>
                            <textarea name="short_description" class="form-control" rows="4">{{ $header->short_description ?? '' }}</textarea>
                        </div>

                        <div class="col-12 mt-4 text-end">
                            <hr class="mb-4">
                            <button type="submit" class="btn btn-update shadow-sm">
                                <i class="bi bi-check2-circle me-2"></i> Save Header Configuration
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection