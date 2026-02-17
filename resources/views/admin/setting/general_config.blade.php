@extends('admin.layout.master')

@section('title', 'General Settings')

@section('css')
<style>
    :root {
        --accent-color: #00a651;
        --card-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
    }

    .config-card {
        border: none;
        border-radius: 20px;
        box-shadow: var(--card-shadow);
        background: #fff;
        overflow: hidden;
        transition: transform 0.3s ease;
    }

    .card-header-custom {
        background: #f8fafc;
        padding: 1.5rem;
        border-bottom: 1px solid #edf2f7;
    }

    .card-header-custom h5 {
        margin: 0;
        font-weight: 700;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .image-preview-wrapper {
        position: relative;
        width: 100%;
        background: #f1f5f9;
        border: 2px dashed #cbd5e1;
        border-radius: 15px;
        padding: 20px;
        transition: all 0.3s ease;
    }

    .image-preview-wrapper:hover {
        border-color: var(--accent-color);
        background: #f0fdf4;
    }

    .preview-img-box {
        height: 120px;
        width: 100%;
        object-fit: contain;
        margin-bottom: 15px;
    }

    .form-label {
        font-weight: 600;
        color: #475569;
        font-size: 0.9rem;
        margin-bottom: 8px;
    }

    .form-control {
        border-radius: 10px;
        padding: 12px;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
    }

    .form-control:focus {
        box-shadow: 0 0 0 4px rgba(0, 166, 81, 0.1);
        border-color: var(--accent-color);
        background: #fff;
    }

    .btn-save {
        background: var(--accent-color);
        border: none;
        padding: 15px;
        border-radius: 12px;
        font-weight: 700;
        letter-spacing: 0.5px;
        transition: all 0.3s;
    }

    .btn-save:hover {
        background: #008541;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 166, 81, 0.3);
    }

    .section-icon {
        width: 35px;
        height: 35px;
        background: rgba(0, 166, 81, 0.1);
        color: var(--accent-color);
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
    }
</style>
@endsection

@section('body')
<div class="container-fluid py-4">
    <form action="{{ route('general.config.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="config-card mb-4">
                    <div class="card-header-custom">
                        <h5><div class="section-icon"><i class="bi bi-shop"></i></div> Branding & Identity</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <label class="form-label">Site Name</label>
                            <input type="text" name="site_name" value="{{ $config->site_name ?? '' }}" class="form-control" placeholder="Enter your business name">
                        </div>

                        <div class="row g-4">
                            <div class="col-md-4">
                                <label class="form-label">Main Logo</label>
                                <div class="image-preview-wrapper text-center">
                                    <img id="logo_prev" src="{{ asset($config->logo ?? 'public/No_Image_Available.jpg') }}" class="preview-img-box">
                                    <input type="file" name="logo" class="form-control form-control-sm" onchange="previewImg(this, 'logo_prev')">
                                    <small class="text-muted d-block mt-2">Max 300x150px</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Mobile Logo</label>
                                <div class="image-preview-wrapper text-center">
                                    <img id="mobile_prev" src="{{ asset($config->mobile_version_logo ?? 'public/No_Image_Available.jpg') }}" class="preview-img-box">
                                    <input type="file" name="mobile_version_logo" class="form-control form-control-sm" onchange="previewImg(this, 'mobile_prev')">
                                       <small class="text-muted d-block mt-2">Max 300x150px</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Favicon Icon</label>
                                <div class="image-preview-wrapper text-center">
                                    <img id="icon_prev" src="{{ asset($config->icon ?? 'public/No_Image_Available.jpg') }}" class="preview-img-box" style="width: 60px; height: 60px; margin: 30px auto;">
                                    <input type="file" name="icon" class="form-control form-control-sm" onchange="previewImg(this, 'icon_prev')">
                                    <small class="text-muted d-block mt-2">Size 60x60px</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="config-card">
                    <div class="card-header-custom">
                        <h5><div class="section-icon"><i class="bi bi-lightning-charge"></i></div> Action Buttons & UI</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Quick Button Text</label>
                                <input type="text" name="quick_button_text" value="{{ $config->quick_button_text ?? '' }}" class="form-control" placeholder="e.g. Call Now">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Appointment Button Text</label>
                                <input type="text" name="book_appointment_button_text" value="{{ $config->book_appointment_button_text ?? '' }}" class="form-control" placeholder="e.g. Book Visit">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Appointment Link</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-link-45deg"></i></span>
                                    <input type="url" name="book_appointment_link" value="{{ $config->book_appointment_link ?? '' }}" class="form-control border-start-0" placeholder="https://example.com/booking">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="config-card mb-4">
                    <div class="card-header-custom">
                        <h5><div class="section-icon"><i class="bi bi-headset"></i></div> Contact Channels</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label class="form-label">Support Email</label>
                            <input type="email" name="email" value="{{ $config->email ?? '' }}" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Phone Number</label>
                            <input type="text" name="phone" value="{{ $config->phone ?? '' }}" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">WhatsApp (With Country Code)</label>
                            <input type="text" name="whatsapp_number" value="{{ $config->whatsapp_number ?? '' }}" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Physical Address</label>
                            <textarea name="address" class="form-control" rows="3">{{ $config->address ?? '' }}</textarea>
                        </div>

                        <div class="mb-0">
                <label class="form-label">Footer Short Description</label>
                <textarea name="footer_short_description" class="form-control" rows="4" placeholder="Brief about your company for footer area...">{{ $config->footer_short_description ?? '' }}</textarea>
                <small class="text-muted">This text will appear in the footer section of the website.</small>
            </div>
                    </div>
                </div>

                <div class="config-card mb-4">
    <div class="card-header-custom">
        <h5><div class="section-icon"><i class="bi bi-search"></i></div> Search Engine Optimization (SEO)</h5>
    </div>
    <div class="card-body p-4">
        <div class="mb-3">
            <label class="form-label">Meta Title</label>
            <input type="text" name="meta_title" value="{{ $config->meta_title ?? '' }}" class="form-control" placeholder="SEO Friendly Title">
        </div>

        <div class="mb-3">
            <label class="form-label">Meta Keywords</label>
            <textarea name="meta_keywords" class="form-control" rows="2" placeholder="keyword1, keyword2, keyword3">{{ $config->meta_keywords ?? '' }}</textarea>
            <small class="text-muted">Separate keywords with commas (,)</small>
        </div>

        <div class="mb-3">
            <label class="form-label">Meta Description</label>
            <textarea name="meta_description" class="form-control" rows="3" placeholder="Brief description for search results...">{{ $config->meta_description ?? '' }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Open Graph (OG) Image</label>
            <div class="image-preview-wrapper text-center">
                <img id="og_prev" src="{{ asset($config->og_image ?? 'public/No_Image_Available.jpg') }}" class="preview-img-box">
                <input type="file" name="og_image" class="form-control form-control-sm" onchange="previewImg(this, 'og_prev')">
                <small class="text-muted d-block mt-2">Recommended size: 300x150px</small>
            </div>
        </div>

        <div class="mb-0">
            <label class="form-label">Google Analytics Code</label>
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0"><i class="bi bi-graph-up-arrow"></i></span>
                <input type="text" name="google_analytics_code" value="{{ $config->google_analytics_code ?? '' }}" class="form-control border-start-0" placeholder="G-XXXXXXXXXX">
            </div>
        </div>
    </div>
</div>

                <button type="submit" class="btn btn-save w-100 text-white shadow-lg">
                    <i class="bi bi-cloud-check me-2"></i> PUBLISH CONFIGURATION
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    function previewImg(input, targetId) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById(targetId).src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection