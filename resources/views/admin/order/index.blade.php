@extends('admin.master.master')
@section('title', 'Requested Product List')
@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    .main-content { font-size: 0.9rem; }
    .badge { font-size: 0.8em; padding: 0.4em 0.6em; }
    /* Details Modal Styling */
    #detailsModal .invoice-details p { margin-bottom: 0.5rem; }
</style>
@endsection
@section('body')
<main class="main-content">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
            <h2 class="mb-0">Requested Product List</h2>
        </div>
        <div class="card">
            
            <div class="card-body">
                {{-- Filter Section --}}
                <div class="p-3 mb-3 bg-light rounded border">
                    <form id="filterForm" class="row g-3 align-items-end">
                        <div class="col-md-2">
                            <label for="filterOrderId" class="form-label">Order ID</label>
                            <input type="text" class="form-control form-control-sm" id="filterOrderId" placeholder="Search ID...">
                        </div>
                        <div class="col-md-2">
                            <label for="filterCustomerName" class="form-label">Customer</label>
                            <input type="text" class="form-control form-control-sm" id="filterCustomerName" placeholder="Name/email...">
                        </div>
                         <div class="col-md-2">
                            <label for="filterProduct" class="form-label">Product</label>
                            <input type="text" class="form-control form-control-sm" id="filterProduct" placeholder="Name/Code...">
                        </div>
                        <div class="col-md-2">
                            <label for="filterStartDate" class="form-label">Start Date</label>
                            <input type="text" class="form-control form-control-sm" id="filterStartDate" placeholder="Select date...">
                        </div>
                        <div class="col-md-2">
                            <label for="filterEndDate" class="form-label">End Date</label>
                            <input type="text" class="form-control form-control-sm" id="filterEndDate" placeholder="Select date...">
                        </div>
                        <div class="col-md-2">
                            <div class="d-flex gap-2">
                                <button type="button" id="filterBtn" class="btn btn-primary w-100"><i class="fa fa-filter me-1"></i></button>
                                <button type="button" id="resetBtn" class="btn btn-secondary w-100"><i class="fa fa-undo me-1"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
                
                {{-- Bulk Action & Table Tools --}}
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div id="bulkActionContainer" style="display: none;" class="d-flex align-items-center gap-2">
                        <button class="btn btn-danger btn-sm" id="deleteAllBtn">
                            <i class="fa fa-trash"></i> Delete (<span id="selectedCount">0</span>)
                        </button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="selectAllCheckbox"></th>
                                <th>Order ID</th>
                                <th>Billing Name</th>
                                <th>Date</th>
                                <th>Order From</th>
                                <th>Status</th>
                                <th>Details</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody"></tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white d-flex justify-content-between align-items-center">
                <div id="entryInfo" class="text-muted"></div>
                <nav>
                    <ul class="pagination justify-content-center" id="pagination"></ul>
                </nav>
            </div>
        </div>
    </div>
</main>

{{-- Details Modal (Print buttons removed) --}}
<div class="modal fade" id="detailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailsModalTitle">Invoice Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detailsModalBody">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fa fa-times me-1"></i> Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
$(document).ready(function() {
    var currentPage = 1;
    var detailsModal = new bootstrap.Modal(document.getElementById('detailsModal'));
    var debounceTimer;

    flatpickr("#filterStartDate", { dateFormat: "Y-m-d" });
    flatpickr("#filterEndDate", { dateFormat: "Y-m-d" });

    var routes = {
        fetch: "{{ route('ajax.order.data') }}",
        destroy: id => `{{ url('order') }}/${id}`,
        destroyMultiple: "{{ route('order.destroy-multiple') }}",
        getDetails: id => `{{ route('order.get-details', ':id') }}`.replace(':id', id),
        csrf: "{{ csrf_token() }}"
    };

    const statusColors = {
        'pending': 'secondary',
        'waiting': 'info',
        'accepted': 'success',
        'cancelled': 'danger'
    };

    function fetchData() {
        $('#tableBody').html('<tr><td colspan="8" class="text-center"><div class="spinner-border spinner-border-sm" role="status"></div></td></tr>');
        
        const filterData = {
            page: currentPage,
            status: 'all',
            order_id: $('#filterOrderId').val(),
            customer_name: $('#filterCustomerName').val(),
            product_info: $('#filterProduct').val(),
            start_date: $('#filterStartDate').val(),
            end_date: $('#filterEndDate').val(),
        };

        $.get(routes.fetch, filterData, function (res) {
            let rows = '';
            if (res.data.length === 0) {
                rows = '<tr><td colspan="8" class="text-center">No orders found.</td></tr>';
            } else {
                res.data.forEach((order, i) => {
                    const showUrl = `{{ url('order') }}/${order.id}`;
                    
                    const billingName = order.customer ? `${order.customer.name} <br> <small class="text-muted">${order.customer.phone}</small>` : '<span class="text-danger">Customer Deleted</span>';
                    const date = new Date(order.created_at).toLocaleString('en-US', { day: '2-digit', month: 'short', year: 'numeric' });
                    
                    const statusKey = order.status ? order.status.toLowerCase() : 'pending';
                    const badgeColor = statusColors[statusKey] || 'secondary';
                    const displayStatus = order.status ? (order.status.charAt(0).toUpperCase() + order.status.slice(1)) : 'Pending';
                    
                    // UPDATED: Just a badge, no button logic
                    const statusBadge = `<span class="badge bg-${badgeColor}">${displayStatus}</span>`;
                    
                    const detailsButton = `<button class="btn btn-sm btn-primary btn-details" data-id="${order.id}"><i class="fa fa-eye me-1"></i> View</button>`;
                    const orderFromBadge = order.order_from ? (order.order_from === 'web' ? `<span class="badge bg-info">Web</span>` : `<span class="badge bg-secondary">Admin</span>`) : '';

                    rows += `<tr>
                        <td><input type="checkbox" class="row-checkbox" value="${order.id}"></td>
                        <td><b>${order.invoice_no}</b></td>
                        <td>${billingName}</td>
                        <td>${date}</td>
                        <td>${orderFromBadge}</td>
                        <td>${statusBadge}</td>
                        <td>${detailsButton}</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light border" type="button" data-bs-toggle="dropdown"><i class="fa fa-ellipsis-v"></i></button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="${showUrl}"><i class="fa fa-external-link-alt me-2"></i>Show Details & Edit</a></li>
                                    <li><button class="dropdown-item btn-delete" data-id="${order.id}"><i class="fa fa-trash me-2"></i>Delete</button></li>
                                </ul>
                            </div>
                        </td>
                    </tr>`;
                });
            }
            $('#tableBody').html(rows);
            
            const startEntry = (res.current_page - 1) * 10 + 1;
            const endEntry = startEntry + res.data.length - 1;
            $('#entryInfo').text(res.data.length > 0 ? `Showing ${startEntry} to ${endEntry} of ${res.total} entries` : 'No entries');

            let paginationHtml = '';
            if (res.last_page > 1) {
                paginationHtml += `<li class="page-item ${res.current_page === 1 ? 'disabled' : ''}"><a class="page-link" href="#" data-page="${res.current_page - 1}">&laquo;</a></li>`;
                for (let i = Math.max(1, res.current_page - 2); i <= Math.min(res.last_page, res.current_page + 2); i++) {
                    paginationHtml += `<li class="page-item ${i === res.current_page ? 'active' : ''}"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
                }
                paginationHtml += `<li class="page-item ${res.current_page === res.last_page ? 'disabled' : ''}"><a class="page-link" href="#" data-page="${res.current_page + 1}">&raquo;</a></li>`;
            }
            $('#pagination').html(paginationHtml);
        });
    }

    // Search Handlers
    const searchInputs = '#filterOrderId, #filterCustomerName, #filterProduct';
    $(document).on('keyup', searchInputs, function() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(function() { currentPage = 1; fetchData(); }, 500); 
    });
    $('#filterBtn').on('click', function() { clearTimeout(debounceTimer); currentPage = 1; fetchData(); });
    $('#resetBtn').on('click', function() { $('#filterForm')[0].reset(); flatpickr("#filterStartDate").clear(); flatpickr("#filterEndDate").clear(); currentPage = 1; fetchData(); });
    $(document).on('click', '.page-link', function (e) { e.preventDefault(); if(!$(this).parent().hasClass('disabled')) { currentPage = $(this).data('page'); fetchData(); } });

    // Details Modal Handler
    $(document).on('click', '.btn-details', function() {
        const orderId = $(this).data('id');
        $.get(routes.getDetails(orderId), function(data) {
            $('#detailsModalTitle').text(`Invoice #${data.invoice_no}`);
            let itemsHtml = '';
            
            if (data.order_details && data.order_details.length > 0) {
                data.order_details.forEach(item => {
                    const productName = item.product ? item.product.name : 'Unknown Product';
                    const unitPrice = parseFloat(item.unit_price) || 0;
                    const subtotal = parseFloat(item.subtotal) || 0;
                    
                    itemsHtml += `
                        <tr>
                            <td>${productName}</td>
                            <td class="text-center">${item.quantity}</td>
                            <td class="text-end">${unitPrice.toFixed(2)}</td>
                            <td class="text-end">${subtotal.toFixed(2)}</td>
                        </tr>`;
                });
            }
            
            const secondaryPhoneHtml = (data.customer && data.customer.secondary_phone) ? `<br> ${data.customer.secondary_phone} (secondary)` : '';
            
            let summaryHtml = '';
            summaryHtml += `<tr><td>Sub Total:</td><td>${parseFloat(data.subtotal).toFixed(2)}</td></tr>`;
            if (data.discount && parseFloat(data.discount) > 0) {
                summaryHtml += `<tr><td>Discount:</td><td>- ${parseFloat(data.discount).toFixed(2)}</td></tr>`;
            }
            summaryHtml += `<tr style="border-top: 1px solid #ddd;"><td><strong>Grand Total:</strong></td><td><strong>${parseFloat(data.total_amount).toFixed(2)}</strong></td></tr>`;

            const detailsHtml = `
                <div class="invoice-details mb-4">
                    <p><strong>Invoice:</strong> ${data.invoice_no}</p>
                    <p><strong>Customer:</strong> ${data.customer ? data.customer.name : 'N/A'} (${data.customer ? data.customer.email : 'N/A'})${secondaryPhoneHtml}</p>
                    <p><strong>Address:</strong> ${data.shipping_address || 'N/A'}</p>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered invoice-items-table">
                        <thead class="table-light">
                            <tr>
                                <th>Product Name</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Unit Price</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>${itemsHtml}</tbody>
                    </table>
                </div>
                <div class="row justify-content-end">
                    <div class="col-md-5">
                        <table class="table table-sm invoice-totals">
                            <tbody>${summaryHtml}</tbody>
                        </table>
                    </div>
                </div>
                ${data.notes ? `<hr><p class="text-muted small"><strong>Notes:</strong> ${data.notes}</p>` : ''}`;
                
            $('#detailsModalBody').html(detailsHtml);
            detailsModal.show();
        });
    });

    // Bulk Delete Logic
    function updateBulkActionUI() {
        const selectedCount = $('.row-checkbox:checked').length;
        $('#selectedCount').text(selectedCount);
        $('#bulkActionContainer').toggle(selectedCount > 0);
    }
    $('#selectAllCheckbox').on('change', function() { $('.row-checkbox').prop('checked', $(this).is(':checked')); updateBulkActionUI(); });
    $(document).on('change', '.row-checkbox', function() { updateBulkActionUI(); });
    $('#deleteAllBtn').on('click', function() {
        const selectedIds = $('.row-checkbox:checked').map((_, el) => el.value).get();
        Swal.fire({
            title: `Delete ${selectedIds.length} orders?`, icon: 'warning', showCancelButton: true, confirmButtonText: 'Yes, delete!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({ url: routes.destroyMultiple, method: 'get', data: { ids: selectedIds, _token: routes.csrf }, success: function(res) { Swal.fire('Deleted!', res.message, 'success'); fetchData(); updateBulkActionUI(); $('#selectAllCheckbox').prop('checked', false); }});
            }
        });
    });
    
    $(document).on('click', '.btn-delete', function () {
        const id = $(this).data('id');
        Swal.fire({ title: 'Are you sure?', icon: 'warning', showCancelButton: true, confirmButtonText: 'Yes, delete!' }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form'); form.method = 'POST'; form.action = routes.destroy(id);
                form.innerHTML = `<input type="hidden" name="_token" value="${routes.csrf}"><input type="hidden" name="_method" value="DELETE">`;
                document.body.appendChild(form); form.submit();
            }
        });
    });

    fetchData();
});
</script>
@endsection