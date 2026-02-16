@extends('front.master.master')
@section('title')
    Customer Profile - {{ $front_ins_name }}
@endsection

@section('css')
<style>
    /* Validation Error Styles */
    .is-invalid {
        border-color: #dc3545 !important;
    }
    .invalid-feedback {
        display: block;
        color: #dc3545;
        font-size: 0.875em;
        margin-top: 5px;
    }
    /* Modal Header Custom Style */
    .cellexa_user_modal_header {
        border-bottom: none;
        padding-bottom: 0;
    }
    .cellexa_user_modal_title {
        font-weight: 700;
        font-size: 1.25rem;
    }
</style>
@endsection

@section('body')
<main>
    <section class="section">
        <div class="container cellexa_user_wrapper">
            <div class="row">

                <div class="col-lg-3 col-md-4">
                    <div class="cellexa_user_sidebar">
                        <div class="cellexa_user_profile_header">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=A1573C&color=fff&size=200"
                                alt="Profile" class="cellexa_user_avatar" id="sidebar_avatar">
                            <div class="cellexa_user_header_text">
                                <h5 class="cellexa_user_name_display" id="sidebar_name">{{ $user->name }}</h5>
                                <span class="cellexa_user_role_badge">Member</span>
                            </div>
                        </div>

                        <div class="nav cellexa_user_nav_menu" role="tablist">
                            <button class="cellexa_user_nav_btn active" id="tab-profile" data-bs-toggle="pill"
                                data-bs-target="#content-profile" type="button" role="tab" aria-selected="true">
                                <i class="fas fa-user"></i> User Profile
                            </button>
                            <button class="cellexa_user_nav_btn" id="tab-quotes" data-bs-toggle="pill"
                                data-bs-target="#content-quotes" type="button" role="tab" aria-selected="false">
                                <i class="fas fa-file-invoice"></i> Quotes List
                            </button>
                            <button class="cellexa_user_nav_btn" id="tab-orders" data-bs-toggle="pill"
                                data-bs-target="#content-orders" type="button" role="tab" aria-selected="false">
                                <i class="fas fa-shopping-bag"></i> Order List
                            </button>
                            
                            <form action="{{ route('logout') }}" method="POST" class="w-100">
                                @csrf
                                <button type="submit" class="cellexa_user_nav_btn w-100 text-start text-danger" style="border:none;">
                                    <i class="fas fa-sign-out-alt"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-9 col-md-8">
                    <div class="cellexa_user_content_area">

                        <div class="tab-content" id="v-pills-tabContent">

                            <div class="tab-pane fade show active" id="content-profile" role="tabpanel" tabindex="0">
                                <h2 class="cellexa_user_section_title">Profile Settings</h2>
                                
                                <form id="profile_update_form">
                                    @csrf
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label cellexa_user_form_label">Full Name</label>
                                            <input type="text" name="name" class="form-control cellexa_user_input" value="{{ $user->name }}">
                                            <div class="invalid-feedback error-name"></div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label cellexa_user_form_label">Email Address</label>
                                            <input type="email" class="form-control cellexa_user_input"
                                                value="{{ $user->email }}" readonly disabled style="background-color: #f8f9fa;">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label cellexa_user_form_label">Company Name</label>
                                            <input type="text" name="company_name" class="form-control cellexa_user_input" value="{{ $customer->company_name ?? '' }}">
                                            <div class="invalid-feedback error-company_name"></div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label cellexa_user_form_label">Phone Number</label>
                                            <input type="tel" name="phone" class="form-control cellexa_user_input" value="{{ $customer->phone ?? '' }}">
                                            <div class="invalid-feedback error-phone"></div>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label cellexa_user_form_label">Address</label>
                                            <textarea name="address" class="form-control cellexa_user_input" rows="4">{{ $customer->address ?? '' }}</textarea>
                                            <div class="invalid-feedback error-address"></div>
                                        </div>
                                        <div class="col-12 mt-4">
                                            <button type="submit" class="btn cellexa_user_save_btn">Save Changes</button>
                                        </div>
                                    </div>
                                </form>

                                <hr class="my-5">

                                <h2 class="cellexa_user_section_title text-danger">Change Password</h2>
                                <form id="password_update_form">
                                    @csrf
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label class="form-label cellexa_user_form_label">Current Password</label>
                                            <input type="password" name="current_password" class="form-control cellexa_user_input">
                                            <div class="invalid-feedback error-current_password"></div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label cellexa_user_form_label">New Password</label>
                                            <input type="password" name="password" class="form-control cellexa_user_input">
                                            <div class="invalid-feedback error-password"></div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label cellexa_user_form_label">Confirm Password</label>
                                            <input type="password" name="password_confirmation" class="form-control cellexa_user_input">
                                        </div>
                                        <div class="col-12 mt-4">
                                            <button type="submit" class="btn btn-danger text-white">Update Password</button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="tab-pane fade" id="content-quotes" role="tabpanel" tabindex="0">
                                <h2 class="cellexa_user_section_title">My Quotes</h2>
                                <div class="cellexa_user_table_wrapper">
                                    <table class="table table-hover align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Quote ID</th>
                                                <th>Order ID</th>
                                                <th>Date</th>
                                                <th>Amount</th>
                                                <th class="text-end">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php 
                                                // Filter: Only Accepted Orders
                                                $acceptedQuotes = $orders->where('status', 'accepted'); 
                                            @endphp

                                            @forelse($acceptedQuotes as $order)
                                            <tr>
                                                <td>Q-{{ 2023000 + $order->id }}</td>
                                                <td>#{{ $order->invoice_no }}</td>
                                                <td>{{ date('M d, Y', strtotime($order->created_at)) }}</td>
                                                <td class="fw-bold text-success">${{ number_format($order->total_amount, 2) }}</td>
                                                <td class="text-end">
                                                    <button class="btn btn-sm btn-outline-danger view-quote-btn me-1"
                                                            data-id="{{ $order->id }}"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#cellexa_quote_modal">
                                                        View
                                                    </button>
                                                    
                                                    <a href="{{ route('front.order.print', $order->id) }}" target="_blank" class="btn btn-sm btn-light border" title="Download PDF">
                                                        <i class="fas fa-file-pdf text-danger"></i> PDF
                                                    </a>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-5 text-muted">
                                                    <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                                    No accepted quotes found.
                                                </td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="content-orders" role="tabpanel" tabindex="0">
                                <h2 class="cellexa_user_section_title">Order History</h2>
                                <div class="cellexa_user_table_wrapper">
                                    <table class="table table-hover align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Order ID</th>
                                                <th>Order Date</th>
                                                <th>Total Product</th>
                                                <th>Status</th>
                                                <th class="text-end">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($orders as $order)
                                            <tr>
                                                <td><span class="fw-bold text-dark">#{{ $order->invoice_no }}</span></td>
                                                <td>{{ date('M d, Y', strtotime($order->created_at)) }}</td>
                                                <td>{{ $order->orderDetails->count() }}</td>
                                                <td>
                                                    @if($order->status == 'pending')
                                                        <span class="badge bg-info text-dark">Pending</span>
                                                    @elseif($order->status == 'waiting')
                                                        <span class="badge bg-warning text-dark">Quote Send</span>
                                                    @elseif($order->status == 'accepted')
                                                        <span class="badge bg-success">Accepted</span>
                                                    @elseif($order->status == 'cancelled')
                                                        <span class="badge bg-danger">Cancelled</span>
                                                    @else
                                                        <span class="badge bg-secondary">{{ ucfirst($order->status) }}</span>
                                                    @endif
                                                </td>
                                                <td class="text-end">
                                                    <button class="btn btn-sm btn-outline-danger view-order-btn px-3" 
                                                            data-id="{{ $order->id }}"
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#cellexa_user_productModal">
                                                        View
                                                    </button>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-5 text-muted">No order history found.</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="cellexa_user_productModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header cellexa_user_modal_header">
                        <h5 class="modal-title cellexa_user_modal_title">Order Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body pt-0" id="modal_content_area">
                        <div class="text-center py-4"><div class="spinner-border text-primary"></div></div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary bg-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="cellexa_quote_modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header cellexa_user_modal_header">
                        <h5 class="modal-title cellexa_user_modal_title">Quote Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body pt-0" id="quote_modal_content_area">
                        <div class="text-center py-4"><div class="spinner-border text-success"></div></div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary bg-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

    </section>
</main>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        
        // Tab Handling from URL
        const urlParams = new URLSearchParams(window.location.search);
        if(urlParams.get('tab') === 'quotes'){
            var triggerEl = document.querySelector('#tab-quotes');
            var tab = new bootstrap.Tab(triggerEl);
            tab.show();
        } else if(urlParams.get('tab') === 'orders'){
            var triggerEl = document.querySelector('#tab-orders');
            var tab = new bootstrap.Tab(triggerEl);
            tab.show();
        }

        // Profile Update AJAX
        $('#profile_update_form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').text('');

            $.ajax({
                url: "{{ route('profile.update') }}",
                type: "POST",
                data: formData,
                success: function(response) {
                    if(response.status === 'success') {
                        Swal.fire({ icon: 'success', title: 'Updated!', text: response.message, timer: 2000, showConfirmButton: false });
                        $('#sidebar_name').text(response.new_name);
                        var newAvatar = "https://ui-avatars.com/api/?name=" + encodeURIComponent(response.new_name) + "&background=A1573C&color=fff&size=200";
                        $('#sidebar_avatar').attr('src', newAvatar);
                    } else {
                        if(response.errors) {
                            $.each(response.errors, function(key, value) {
                                var input = $('[name="'+key+'"]');
                                input.addClass('is-invalid');
                                $('.error-'+key).text(value[0]);
                            });
                        }
                    }
                }
            });
        });

        // Password Update AJAX
        $('#password_update_form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            var form = this;
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').text('');

            $.ajax({
                url: "{{ route('password.update') }}",
                type: "POST",
                data: formData,
                success: function(response) {
                    if(response.status === 'success') {
                        Swal.fire({ icon: 'success', title: 'Success', text: response.message });
                        form.reset();
                    } else {
                        if(response.errors) {
                            $.each(response.errors, function(key, value) {
                                var input = $('#password_update_form [name="'+key+'"]');
                                input.addClass('is-invalid');
                                $('.error-'+key).text(value[0]);
                            });
                        }
                    }
                }
            });
        });

        // 1. View Order Details (Order List Tab - No Price)
        $(document).on('click', '.view-order-btn', function(){
            var id = $(this).data('id');
            // Route for General Order Details (No Price)
            var url = "{{ route('front.order.details.html', ':id') }}";
            url = url.replace(':id', id);

            $('#modal_content_area').html('<div class="text-center py-4"><div class="spinner-border text-primary"></div></div>');
            
            $.ajax({
                url: url,
                type: 'GET',
                success: function(response){
                    $('#modal_content_area').html(response);
                },
                error: function(){
                    $('#modal_content_area').html('<p class="text-danger text-center">Failed to load data.</p>');
                }
            });
        });

        // 2. View Quote Details (Quotes List Tab - With Price)
        $(document).on('click', '.view-quote-btn', function(){
            var id = $(this).data('id');
            // Route for Quote Details (With Price) - Ensure this route exists in web.php
            var url = "{{ route('front.quote.details.html', ':id') }}";
            url = url.replace(':id', id);

            $('#quote_modal_content_area').html('<div class="text-center py-4"><div class="spinner-border text-success"></div></div>');
            
            $.ajax({
                url: url,
                type: 'GET',
                success: function(response){
                    $('#quote_modal_content_area').html(response);
                },
                error: function(){
                    $('#quote_modal_content_area').html('<p class="text-danger text-center">Failed to load data.</p>');
                }
            });
        });

    });
</script>
@endsection