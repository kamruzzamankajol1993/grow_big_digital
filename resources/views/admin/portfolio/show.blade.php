@extends('admin.layout.master')

@section('title', 'Project Details')

@section('body')
<div class="container-fluid py-4">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('portfolio.index') }}" class="btn btn-outline-secondary btn-sm rounded-circle me-3">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h4 class="fw-bold mb-0">Project Details</h4>
        <div class="ms-auto">
            <a href="{{ route('portfolio.edit', $portfolio->id) }}" class="btn btn-primary btn-sm rounded-pill px-3">
                <i class="bi bi-pencil-square me-1"></i> Edit Project
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 20px;">
                @if($portfolio->video_link)
                    <div class="ratio ratio-16x9">
                        {!! $portfolio->video_link !!}
                    </div>
                @else
                    <img src="{{ asset('public/'.$portfolio->image) }}" class="img-fluid w-100" alt="{{ $portfolio->title }}">
                @endif
                <div class="card-body bg-light border-top">
                    <h5 class="fw-bold mb-1">{{ $portfolio->title }}</h5>
                    <p class="text-muted mb-0">{{ $portfolio->subtitle }}</p>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 20px;">
                <div class="card-body p-4">
                    <h6 class="text-uppercase text-primary fw-bold small mb-3">Information</h6>
                    
                    <div class="mb-4">
                        <label class="text-muted d-block small">Main Service</label>
                        <span class="fw-bold text-dark">{{ $portfolio->service->name ?? 'N/A' }}</span>
                    </div>

                    <div class="mb-4">
                        <label class="text-muted d-block small">Subcategory</label>
                        <span class="badge bg-soft-success text-success fw-bold">
                            {{ $portfolio->subcategory->name ?? 'None' }}
                        </span>
                    </div>

                    <div class="mb-4">
                        <label class="text-muted d-block small">Description</label>
                        <div class="text-dark" style="line-height: 1.6;">
                            {!! nl2br(e($portfolio->description)) !!}
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <label class="text-muted d-block small">Created At</label>
                            <span class="text-dark small">{{ $portfolio->created_at->format('d M, Y') }}</span>
                        </div>
                        <form action="{{ route('portfolio.destroy', $portfolio->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm border-0">
                                <i class="bi bi-trash"></i> Delete Project
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-soft-success { background-color: rgba(25, 135, 84, 0.1); }
</style>
@endsection