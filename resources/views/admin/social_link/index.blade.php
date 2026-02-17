@extends('admin.layout.master')

@section('title', 'Social Media Links')

@section('body')
@include('flash_message')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-main mb-0">Social Media Management</h3>
            <p class="text-muted small mb-0">Manage all your official social media profile links here.</p>
        </div>
        <a href="{{ route('socialLink.create') }}" class="btn btn-primary px-4 rounded-pill shadow-sm">
            <i class="bi bi-plus-lg me-2"></i> Add New Link
        </a>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius: 15px;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3 text-uppercase small fw-bold text-muted">Platform</th>
                            <th class="py-3 text-uppercase small fw-bold text-muted">URL / Link</th>
                            <th class="py-3 text-center text-uppercase small fw-bold text-muted" style="width: 150px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($socialLinks as $link)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-soft-primary text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 35px; height: 35px; background: #eef2ff;">
                                        <i class="bi bi-share-fill small"></i>
                                    </div>
                                    <span class="fw-bold text-dark">{{ $link->title }}</span>
                                </div>
                            </td>
                            <td>
                                <a href="{{ $link->link }}" target="_blank" class="text-primary text-decoration-none small">
                                    {{ Str::limit($link->link, 50) }} <i class="bi bi-box-arrow-up-right ms-1" style="font-size: 10px;"></i>
                                </a>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('socialLink.edit', $link->id) }}" class="btn btn-sm btn-outline-info rounded-circle" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('socialLink.destroy', $link->id) }}" method="POST" class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle" title="Delete">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')

<script>
    $(document).ready(function() {
        $('.delete-form').on('submit', function(e) {
            e.preventDefault(); // ফর্মটি সরাসরি সাবমিট হওয়া বন্ধ করবে
            
            let form = this;

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true,
                customClass: {
                    confirmButton: 'rounded-pill px-4',
                    cancelButton: 'rounded-pill px-4'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // ইউজার কনফার্ম করলে ফর্ম সাবমিট হবে
                    form.submit();
                }
            });
        });
    });
</script>

@endsection