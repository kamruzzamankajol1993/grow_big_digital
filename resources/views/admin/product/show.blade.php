@extends('admin.master.master')
@section('title', 'Product Details')
@section('css')
<style>
    /* --- Modern Reset --- */
    :root {
        --text-dark: #1f2937;
        --text-muted: #6b7280;
        --bg-light: #f9fafb;
    }
    .main-content {
        font-size: 0.95rem;
        background-color: var(--bg-light);
        min-height: 100vh;
    }
    .card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        background: #fff;
        margin-bottom: 1.5rem;
    }
    .card-body { padding: 2rem; }
    .card-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
    }
    .card-title i { margin-right: 0.5rem; color: #4f46e5; }
    
    /* --- Data Labels --- */
    .data-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--text-muted);
        font-weight: 600;
        margin-bottom: 0.25rem;
    }
    .data-value {
        font-size: 1rem;
        color: var(--text-dark);
        font-weight: 500;
    }
    
    .list-group-flush > .list-group-item {
        border-color: #f3f4f6;
        padding: 1rem 0;
    }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endsection
@section('body')
<main class="main-content">
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
            <div>
                <h2 class="mb-1 fw-bold text-dark">{{ $product->name }}</h2>
                <div class="text-muted small">
                    <i class="fas fa-barcode me-1"></i> {{ $product->product_code ?? 'N/A' }}
                </div>
            </div>
            <div>
                <a href="{{ route('product.index') }}" class="btn btn-light border shadow-sm me-2">
                    <i class="fas fa-arrow-left me-1"></i> Back
                </a>
                <a href="{{ route('product.edit', $product->id) }}" class="btn btn-primary shadow-sm">
                    <i class="fas fa-edit me-1"></i> Edit Product
                </a>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-8">
                {{-- Main Product Details --}}
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-info-circle"></i> Product Information</h5>
                        
                        <div class="mb-4">
                            <div class="data-label">Description</div>
                            <div class="data-value mt-2 lh-base">
                                {!! $product->description ?? '<span class="text-muted fst-italic">No description provided.</span>' !!}
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="data-label">Specification</div>
                            <div class="data-value rich-text mt-2 lh-base">
                                {!! $product->specification ?? '<span class="text-muted fst-italic">No specification provided.</span>' !!}
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded-3">
                                    <div class="data-label">Product Code</div>
                                    <div class="data-value font-monospace">{{ $product->product_code ?? 'N/A' }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded-3">
                                    <div class="data-label">Main Category</div>
                                    <div class="data-value">{{ $product->category->name ?? 'Uncategorized' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                {{-- Pricing & Media --}}
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-images"></i> Media Gallery</h5>
                        
                        <div class="mb-4">
                            <div class="data-label mb-2">Thumbnail Images</div>
                            <div class="d-flex flex-wrap gap-2">
                                @if(!empty($product->thumbnail_image) && is_array($product->thumbnail_image))
                                    @foreach($product->thumbnail_image as $image)
                                        <a href="{{ asset('public/uploads/'.$image) }}" target="_blank">
                                            <img src="{{ asset('public/uploads/'.$image) }}" class="img-thumbnail shadow-sm" style="height: 70px; width: 70px; object-fit: cover;" alt="Thumbnail">
                                        </a>
                                    @endforeach
                                @else
                                    <span class="text-muted small fst-italic">No thumbnails available</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-tag"></i> Pricing & Status</h5>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span class="text-muted">Purchase Price</span>
                                <span class="fw-bold">${{ number_format($product->purchase_price, 2) }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span class="text-muted">Base Price</span>
                                <span class="fw-bold">${{ number_format($product->base_price, 2) }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span class="text-muted">Discount Price</span>
                                <span class="text-success fw-bold">
                                    {{ $product->discount_price ? '$'.number_format($product->discount_price, 2) : 'N/A' }}
                                </span>
                            </li>
                            
                            {{-- Company & Company Categories --}}
                            <li class="list-group-item">
                                <div class="mb-2 text-muted small">Company (Brand)</div>
                                <div class="fw-bold text-dark">{{ $product->brand->name ?? 'N/A' }}</div>
                            </li>

                            <li class="list-group-item">
                                <div class="mb-2 text-muted small">Company Categories</div>
                                <div class="d-flex flex-wrap gap-1">
                                    @forelse($product->assigns as $assign)
                                        <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 fw-normal">
                                            {{ $assign->category_name ?? 'N/A' }}
                                        </span>
                                    @empty
                                        <span class="text-muted small fst-italic">No categories assigned</span>
                                    @endforelse
                                </div>
                            </li>

                            <li class="list-group-item d-flex justify-content-between align-items-center pt-3">
                                <span class="text-muted">Status</span>
                                @if($product->status)
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2 rounded-pill">Active</span>
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-3 py-2 rounded-pill">Inactive</span>
                                @endif
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection