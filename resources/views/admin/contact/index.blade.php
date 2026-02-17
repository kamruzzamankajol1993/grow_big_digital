@extends('admin.layout.master')

@section('title', 'User Messages')

@section('css')
<style>
    .unread-row { background-color: rgba(0, 166, 81, 0.05) !important; font-weight: 600; }
    .status-badge { width: 80px; text-align: center; border-radius: 50px; font-size: 11px; }
    .table-card { border-radius: 15px; border: none; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
    .modal-content { border-radius: 20px; border: none; }
    .modal-header { background: #f8fafc; border-radius: 20px 20px 0 0; }
</style>
@endsection

@section('body')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0">Contact Messages</h3>
            <p class="text-muted small">Manage and respond to user inquiries from the front-end.</p>
        </div>
        <button id="deleteSelected" class="btn btn-danger px-4 rounded-pill shadow-sm" style="display: none;">
            <i class="bi bi-trash3 me-2"></i> Delete Selected (<span id="selectedCount">0</span>)
        </button>
    </div>

    <div class="card table-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="messageTable">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4" style="width: 50px;">
                                <input type="checkbox" class="form-check-input" id="selectAll">
                            </th>
                            <th>Sender Info</th>
                            <th>Message Preview</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($messages as $msg)
                        <tr class="{{ $msg->is_read == 0 ? 'unread-row' : '' }}" id="row_{{ $msg->id }}">
                            <td class="ps-4">
                                <input type="checkbox" class="form-check-input msg-check" value="{{ $msg->id }}">
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="text-dark fw-bold">{{ $msg->full_name }}</span>
                                    <small class="text-muted">{{ $msg->email }}</small>
                                </div>
                            </td>
                            <td>
                                <span class="text-muted small">{{ Str::limit($msg->description, 50) }}</span>
                            </td>
                            <td>{{ $msg->created_at->format('d M, Y') }}</td>
                            <td>
                                @if($msg->is_read == 0)
                                    <span class="badge bg-danger status-badge">Unread</span>
                                @else
                                    <span class="badge bg-success status-badge">Read</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <button class="btn btn-sm btn-outline-primary rounded-circle view-msg" data-id="{{ $msg->id }}">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <form action="{{ route('contact.messages.destroy', $msg->id) }}" method="POST" class="delete-form d-inline">
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
                            <td colspan="6" class="text-center py-5">No messages found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white border-0 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">Showing {{ $messages->firstItem() ?? 0 }} to {{ $messages->lastItem() ?? 0 }} of {{ $messages->total() }}</small>
                {{ $messages->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="messageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg">
            <div class="modal-header border-0">
                <h5 class="fw-bold mb-0">Message Detail</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="text-muted small text-uppercase fw-bold">From</label>
                    <p class="mb-0 fw-bold text-dark" id="m_name"></p>
                    <p class="text-muted small" id="m_email"></p>
                </div>
                <div class="mb-3">
                    <label class="text-muted small text-uppercase fw-bold">Phone</label>
                    <p class="mb-0 text-dark" id="m_phone"></p>
                </div>
                <div class="mb-3">
                    <label class="text-muted small text-uppercase fw-bold">Interested In</label>
                    <p class="mb-0 text-dark" id="m_interest"></p>
                </div>
                <hr class="my-4">
                <div class="mb-0">
                    <label class="text-muted small text-uppercase fw-bold">Message Content</label>
                    <p class="text-dark bg-light p-3 rounded-3" id="m_desc" style="white-space: pre-wrap;"></p>
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
        // --- View Message Ajax ---
        $('.view-msg').on('click', function() {
            let id = $(this).data('id');
            let row = $('#row_' + id);
            
            $.get("{{ url('admin/contact/messages') }}/" + id, function(res) {
                if(res.status === 'success') {
                    $('#m_name').text(res.data.full_name);
                    $('#m_email').text(res.data.email);
                    $('#m_phone').text(res.data.phone ?? 'N/A');
                    $('#m_interest').text(res.data.interested_in ?? 'N/A');
                    $('#m_desc').text(res.data.description);
                    $('#messageModal').modal('show');

                    // স্ট্যাটাস UI আপডেট
                    row.removeClass('unread-row');
                    row.find('.status-badge').removeClass('bg-danger').addClass('bg-success').text('Read');
                }
            });
        });

        // --- Select All Checkboxes ---
        $('#selectAll').on('click', function() {
            $('.msg-check').prop('checked', this.checked);
            toggleDeleteBtn();
        });

        $('.msg-check').on('change', function() {
            toggleDeleteBtn();
        });

        function toggleDeleteBtn() {
            let selectedCount = $('.msg-check:checked').length;
            $('#selectedCount').text(selectedCount);
            if(selectedCount > 0) {
                $('#deleteSelected').fadeIn();
            } else {
                $('#deleteSelected').fadeOut();
            }
        }

        // --- Multi-Delete Ajax ---
        $('#deleteSelected').on('click', function() {
            let ids = $('.msg-check:checked').map(function() { return $(this).val(); }).get();

            Swal.fire({
                title: 'Are you sure?',
                text: "Selected messages will be permanently deleted!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Yes, delete all!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post("{{ route('contact.messages.multiDelete') }}", {
                        ids: ids,
                        _token: "{{ csrf_token() }}"
                    }, function(res) {
                        Swal.fire('Deleted!', res.message, 'success').then(() => {
                            location.reload();
                        });
                    });
                }
            });
        });

        // --- Single Delete Confirmation ---
        $('.delete-form').on('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Delete this message?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Yes, delete!'
            }).then((result) => {
                if (result.isConfirmed) this.submit();
            });
        });
    });
</script>
@endsection