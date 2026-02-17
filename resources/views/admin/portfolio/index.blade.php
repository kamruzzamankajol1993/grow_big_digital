@extends('admin.layout.master')

@section('title', 'Portfolio Projects')

@section('css')
<style>
  
    .project-thumb { width: 80px; height: 50px; object-fit: cover; border-radius: 8px; border: 1px solid #eee; }
    .table-card { border-radius: 15px; border: none; box-shadow: 0 5px 15px rgba(0,0,0,0.05); overflow: hidden; }
    
    /* ট্যাব স্টাইল */
    .nav-tabs-custom { border-bottom: none; gap: 12px; background: #f1f5f9; padding: 8px; border-radius: 16px; display: inline-flex; }
    .nav-tabs-custom .nav-link { border: none; border-radius: 12px; padding: 10px 20px; color: #475569 !important; font-weight: 700; transition: all 0.3s; }
    .nav-tabs-custom .nav-link.active { background: #00a651 !important; color: #fff !important; box-shadow: 0 4px 12px rgba(0, 166, 81, 0.2); }
    
    .video-badge { font-size: 11px; padding: 3px 8px; border-radius: 4px; font-weight: 600; }
    .service-tag { color: #00a651; font-weight: 600; font-size: 13px; }
</style>
@endsection

@section('body')
<div class="container-fluid py-4">
    <div class="custom-width">
        <div class="mb-4">
            <ul class="nav nav-tabs nav-tabs-custom shadow-sm">
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('admin/portfolio/projects*') ? 'active' : '' }}" href="{{ route('portfolio.index') }}">
                        <i class="bi bi-collection-play me-2"></i> All Projects
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('admin/portfolio/header-settings*') ? 'active' : '' }}" href="{{ route('portfolio.header.settings') }}">
                        <i class="bi bi-gear-wide-connected me-2"></i> Header Settings
                    </a>
                </li>
            </ul>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold mb-0">Portfolio Showcase</h4>
            <a href="{{ route('portfolio.create') }}" class="btn btn-success rounded-pill px-4 shadow-sm">
                <i class="bi bi-plus-circle me-1"></i> Add New Project
            </a>
        </div>

        <div class="card table-card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4" style="width: 100px;">Thumbnail</th>
                                <th>Project Info</th>
                                <th>Service Category</th>
                                <th>Video/Media</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($portfolios as $item)
                            <tr>
                                <td class="ps-4">
                                    @if($item->video_link)
                                    @else
                                    <img src="{{ asset('public/'.$item->image) }}" class="project-thumb">
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $item->title }}</div>
                                    <small class="text-muted d-block">{{ Str::limit($item->subtitle, 40) }}</small>
                                </td>
                                <td>
                                    <span class="service-tag"><i class="bi bi-tag-fill me-1"></i>{{ $item->service->name ?? 'N/A' }}</span>
                                </td>
                                <td>
                                    @if($item->video_link)
                                        <span class="video-badge bg-danger bg-opacity-10 text-danger"><i class="bi bi-play-btn-fill me-1"></i> Iframe Link</span>
                                    @elseif($item->video)
                                        <span class="video-badge bg-info bg-opacity-10 text-info"><i class="bi bi-file-earmark-play me-1"></i> Local Video</span>
                                    @else
                                        <span class="text-muted small">No Media</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('portfolio.show', $item->id) }}" class="btn btn-sm btn-outline-info rounded-circle">
        <i class="bi bi-eye"></i>
    </a>
                                        <a href="{{ route('portfolio.edit', $item->id) }}" class="btn btn-sm btn-outline-primary rounded-circle">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form action="{{ route('portfolio.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Delete this project?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle">
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">No portfolio projects found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($portfolios->hasPages())
            <div class="card-footer bg-white border-0 py-3">
                {{ $portfolios->links('pagination::bootstrap-5') }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection