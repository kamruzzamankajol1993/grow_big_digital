@extends('admin.layout.master')

@section('title', 'Quick Audits')

@section('css')
<style>
    .unread-row { background-color: rgba(25, 135, 84, 0.05) !important; font-weight: 600; }
    .status-badge { width: 85px; text-align: center; border-radius: 50px; font-size: 11px; padding: 5px 10px; }
    .table-card { border-radius: 15px; border: none; box-shadow: 0 5px 15px rgba(0,0,0,0.05); overflow: hidden; }
    
    /* ট্যাব স্টাইল (আগের পেজের সাথে মিল রেখে) */
    .nav-tabs-custom { border-bottom: none; gap: 12px; background: #f1f5f9; padding: 8px; border-radius: 16px; display: inline-flex; }
    .nav-tabs-custom .nav-link { border: none; border-radius: 12px; padding: 10px 20px; color: #475569 !important; font-weight: 700; transition: all 0.3s; }
    .nav-tabs-custom .nav-link.active { background: #00a651 !important; color: #fff !important; box-shadow: 0 4px 12px rgba(0, 166, 81, 0.2); }
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
                    <a class="nav-link {{ Request::is('admin/contact/quick-audits*') ? 'active' : '' }}" href="{{ route('contact.quick_audits.index') }}">
                        <i class="bi bi-lightning-charge me-2"></i> Quick Audits
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('admin/contact/header-settings*') ? 'active' : '' }}" href="{{ route('contact.header.settings') }}">
                        <i class="bi bi-gear-wide-connected me-2"></i> Header Settings
                    </a>
                </li>
            </ul>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold mb-0">Audit Requests</h4>
            <button id="deleteSelected" class="btn btn-danger btn-sm rounded-pill px-3" style="display: none;">
                <i class="bi bi-trash3 me-1"></i> Delete Selected (<span id="selectedCount">0</span>)
            </button>
        </div>

        <div class="card table-card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4" style="width: 40px;">
                                    <input type="checkbox" class="form-check-input" id="selectAll">
                                </th>
                                <th>Client Info</th>
                                <th>Requested Service</th>
                                <th>Profile/URL</th>
                                <th>Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($audits as $audit)
                            <tr class="{{ $audit->is_read == 0 ? 'unread-row' : '' }}" id="row_{{ $audit->id }}">
                                <td class="ps-4">
                                    <input type="checkbox" class="form-check-input audit-check" value="{{ $audit->id }}">
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="text-dark fw-bold">{{ $audit->full_name }}</span>
                                        <small class="text-muted">{{ $audit->email }}</small>
                                    </div>
                                </td>
                                <td><span class="badge bg-soft-info text-info p-2">{{ $audit->service }}</span></td>
                                <td>
                                    <a href="{{ $audit->profile_or_social_url }}" target="_blank" class="text-truncate d-inline-block" style="max-width: 150px;">
                                        {{ $audit->profile_or_social_url }}
                                    </a>
                                </td>
                                <td>
                                    <span class="badge status-badge {{ $audit->is_read == 0 ? 'bg-danger' : 'bg-success' }}">
                                        {{ $audit->is_read == 0 ? 'New' : 'Reviewed' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <button class="btn btn-sm btn-outline-primary rounded-circle view-audit" data-id="{{ $audit->id }}">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <form action="{{ route('contact.quick_audits.destroy', $audit->id) }}" method="POST" class="delete-form">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle"><i class="bi bi-trash3"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center py-5 text-muted">No audit requests found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white border-0 py-3">
                {{ $audits->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="auditModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg">
            <div class="modal-header border-0 bg-light">
                <h5 class="fw-bold mb-0">Audit Request Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-3">
                    <div class="col-6">
                        <label class="text-muted small fw-bold text-uppercase">Client Name</label>
                        <p class="fw-bold text-dark mb-0" id="a_name"></p>
                    </div>
                    <div class="col-6">
                        <label class="text-muted small fw-bold text-uppercase">Service Type</label>
                        <p class="text-dark mb-0" id="a_service"></p>
                    </div>
                    <div class="col-12">
                        <label class="text-muted small fw-bold text-uppercase">Email Address</label>
                        <p class="text-dark mb-0" id="a_email"></p>
                    </div>
                    <div class="col-12">
                        <label class="text-muted small fw-bold text-uppercase">Profile/Social URL</label>
                        <p class="mb-0"><a href="" id="a_url" target="_blank" class="text-break"></a></p>
                    </div>
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
        // --- Ajax View ---
        $('.view-audit').click(function() {
            let id = $(this).data('id');
            let row = $('#row_' + id);
            $.get("{{ url('admin/contact/quick-audits') }}/" + id, function(res) {
                if(res.status === 'success') {
                    $('#a_name').text(res.data.full_name);
                    $('#a_service').text(res.data.service);
                    $('#a_email').text(res.data.email);
                    $('#a_url').text(res.data.profile_or_social_url).attr('href', res.data.profile_or_social_url);
                    $('#auditModal').modal('show');
                    
                    // Update UI Status
                    row.removeClass('unread-row');
                    row.find('.status-badge').removeClass('bg-danger').addClass('bg-success').text('Reviewed');
                }
            });
        });

        // --- Select All & Multi Delete ---
        $('#selectAll').click(function() {
            $('.audit-check').prop('checked', this.checked);
            updateDeleteUI();
        });

        $('.audit-check').change(updateDeleteUI);

        function updateDeleteUI() {
            let count = $('.audit-check:checked').length;
            $('#selectedCount').text(count);
            count > 0 ? $('#deleteSelected').fadeIn() : $('#deleteSelected').fadeOut();
        }

        $('#deleteSelected').click(function() {
            let ids = $('.audit-check:checked').map(function() { return $(this).val(); }).get();
            Swal.fire({
                title: 'Are you sure?',
                text: "Selected audits will be deleted!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Yes, delete!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post("{{ route('contact.quick_audits.multiDelete') }}", {ids: ids, _token: "{{ csrf_token() }}"}, function() {
                        location.reload();
                    });
                }
            });
        });

        $('.delete-form').submit(function(e) {
            e.preventDefault();
            Swal.fire({ title: 'Delete this record?', icon: 'warning', showCancelButton: true }).then(res => {
                if(res.isConfirmed) this.submit();
            });
        });
    });
</script>
@endsection