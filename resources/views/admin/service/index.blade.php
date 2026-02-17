@extends('admin.layout.master')

@section('title', 'Service List')

@section('css')
<style>

    .service-icon { width: 40px; height: 40px; object-fit: contain; border-radius: 8px; background: #f8fafc; padding: 5px; }
    .table-card { border-radius: 15px; border: none; box-shadow: 0 5px 15px rgba(0,0,0,0.05); overflow: hidden; }
    
    /* ট্যাব স্টাইল */
    .nav-tabs-custom { border-bottom: none; gap: 12px; background: #f1f5f9; padding: 8px; border-radius: 16px; display: inline-flex; }
    .nav-tabs-custom .nav-link { border: none; border-radius: 12px; padding: 10px 20px; color: #475569 !important; font-weight: 700; transition: all 0.3s; }
    .nav-tabs-custom .nav-link.active { background: #00a651 !important; color: #fff !important; box-shadow: 0 4px 12px rgba(0, 166, 81, 0.2); }
    
    .status-badge { font-size: 11px; padding: 4px 10px; border-radius: 50px; font-weight: 600; }
    .parent-badge { background: #eef2ff; color: #4f46e5; font-size: 11px; padding: 2px 8px; border-radius: 4px; font-weight: 600; }
    .child-badge { background: #fff7ed; color: #ea580c; font-size: 11px; padding: 2px 8px; border-radius: 4px; font-weight: 600; }
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

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold mb-0">Services & Sub-services</h4>
            <a href="{{ route('service.create') }}" class="btn btn-success rounded-pill px-4 shadow-sm">
                <i class="bi bi-plus-lg me-1"></i> Add New Service
            </a>
        </div>

        <div class="card table-card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4" style="width: 80px;">Icon</th>
                                <th>Service Name</th>
                                <th>Category</th>
                                <th>Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($services as $service)
                            <tr>
                                <td class="ps-4">

                                    @if(empty($service->icon))
                                        <div class="service-icon d-flex align-items-center justify-content-center bg-light">
                                            <i class="bi bi-image fs-3 text-muted"></i>
                                        </div>
                                        @else
                                    <img src="{{ asset('public/'.$service->icon ?? 'public/No_Image_Available.jpg') }}" class="service-icon">
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $service->name }}</div>
                                    <small class="text-muted d-block text-truncate" style="max-width: 250px;">{{ $service->short_description }}</small>
                                </td>
                                <td>
                                    @if($service->parent_id)
                                        <span class="child-badge"><i class="bi bi-arrow-return-right me-1"></i> Sub of: {{ $service->parent->name }}</span>
                                    @else
                                        <span class="parent-badge">Main Service</span>
                                    @endif
                                </td>
                                <td>
                                    @if($service->status == 1)
                                        <span class="status-badge bg-success bg-opacity-10 text-success">Active</span>
                                    @else
                                        <span class="status-badge bg-danger bg-opacity-10 text-danger">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('service.edit', $service->id) }}" class="btn btn-sm btn-outline-primary rounded-circle">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form action="{{ route('service.destroy', $service->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this service?')">
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
                                <td colspan="5" class="text-center py-5 text-muted">No services found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($services->hasPages())
            <div class="card-footer bg-white border-0 py-3">
                {{ $services->links('pagination::bootstrap-5') }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection