@extends('admin.layout.master')

@section('title', 'Testimonials')

@section('css')
<style>
    
    .testimonial-img { width: 50px; height: 50px; object-fit: cover; border-radius: 10px; border: 2px solid #eee; }
    .table-card { border-radius: 15px; border: none; box-shadow: 0 5px 15px rgba(0,0,0,0.05); overflow: hidden; }
    
    /* ট্যাব স্টাইল */
    .nav-tabs-custom { border-bottom: none; gap: 12px; background: #f1f5f9; padding: 8px; border-radius: 16px; display: inline-flex; }
    .nav-tabs-custom .nav-link { border: none; border-radius: 12px; padding: 10px 20px; color: #475569 !important; font-weight: 700; transition: all 0.3s; }
    .nav-tabs-custom .nav-link.active { background: #00a651 !important; color: #fff !important; box-shadow: 0 4px 12px rgba(0, 166, 81, 0.2); }
    
    .btn-add { background: #00a651; color: #fff; border-radius: 10px; font-weight: 600; padding: 8px 20px; transition: 0.3s; }
    .btn-add:hover { background: #008541; color: #fff; transform: translateY(-2px); }
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

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold mb-0">Testimonials List</h4>
            <a href="{{ route('testimonial.create') }}" class="btn btn-add shadow-sm">
                <i class="bi bi-plus-lg me-1"></i> Add New
            </a>
        </div>

        <div class="card table-card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4" style="width: 80px;">Image</th>
                                <th>Client Name</th>
                                <th>Designation</th>
                                <th>Feedback Preview</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($testimonials as $item)
                            <tr>
                                <td class="ps-4">
                                    <img src="{{ asset('public/'.$item->image) }}" alt="client" class="testimonial-img">
                                </td>
                                <td><span class="text-dark fw-bold">{{ $item->name }}</span></td>
                                <td><span class="badge bg-soft-secondary text-secondary p-2">{{ $item->designation }}</span></td>
                                <td>
                                    <small class="text-muted">{{ Str::limit($item->short_description, 60) }}</small>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('testimonial.edit', $item->id) }}" class="btn btn-sm btn-outline-primary rounded-circle">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form action="{{ route('testimonial.destroy', $item->id) }}" method="POST" class="delete-form">
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
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-folder-x fs-1 d-block mb-2"></i>
                                    No testimonials found.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white border-0 py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">Showing {{ $testimonials->firstItem() ?? 0 }} to {{ $testimonials->lastItem() ?? 0 }} of {{ $testimonials->total() }}</small>
                    {{ $testimonials->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        // ডিলিট কনফার্মেশন
        $('.delete-form').submit(function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: "This testimonial will be permanently deleted!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    });
</script>
@endsection