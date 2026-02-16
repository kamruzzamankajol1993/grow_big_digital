@extends('admin.master.master')
@section('title', 'Order Details')

@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        .main-content { font-size: 0.9rem; }
        .main-content h2 { font-size: 1.6rem; }
        .main-content h4 { font-size: 1.15rem; }
        .card { border: none; border-radius: 0.5rem; box-shadow: 0 4px 6px rgba(0,0,0,0.05); margin-bottom: 1.5rem; }
        .card-header { background-color: #fff; border-bottom: 1px solid #e9ecef; padding: 1rem 1.5rem; font-weight: 600; }
        .invoice-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2rem; }
        .invoice-header .logo { max-height: 50px; }
        .invoice-header .invoice-info { text-align: right; }
        .summary-card .list-group-item { display: flex; justify-content: space-between; border: none; padding: 0.75rem 0; }
        .summary-card .grand-total { font-size: 1.2rem; font-weight: bold; color: #0d6efd; border-top: 1px solid #e9ecef; margin-top: 0.5rem; padding-top: 0.75rem; }
        
        /* Input Styles for Inline Editing */
        .price-input { text-align: right; font-weight: bold; max-width: 120px; float: right; }
        .qty-badge { background: #f8f9fa; border: 1px solid #ddd; padding: 5px 10px; border-radius: 4px; }
        .status-select { font-weight: 600; }
    </style>
@endsection

@section('body')
<main class="main-content">
    <div class="container-fluid">
        <form action="{{ route('order.update.status.prices', $order->id) }}" method="POST" id="orderUpdateForm">
        @csrf
        
        <div class="row">
            <div class="col-lg-8">
                {{-- (.... আগের কোড সেইম থাকবে ....) --}}
                <div class="card">
                    <div class="card-body">
                        <div class="invoice-header">
                            <div>
                                @if($companyInfo && $companyInfo->logo)
                                    <img src="{{ asset('/') }}{{$front_logo_name}}" alt="Company Logo" class="logo">
                                @else
                                    <h4 class="mb-0">{{ $companyInfo->ins_name ?? 'Company Name' }}</h4>
                                @endif
                                <address class="mt-2 mb-0 text-muted address-block">
                                    {{ $companyInfo->address ?? 'Company Address' }}<br>
                                    Phone: {{ $companyInfo->phone ?? 'N/A' }}
                                </address>
                            </div>
                            <div class="invoice-info">
                                <h4 class="text-primary">INVOICE</h4>
                                <p>#{{ $order->invoice_no }}</p>
                                <p>Date: {{ \Carbon\Carbon::parse($order->order_date)->format('d F, Y') }}</p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="section-title">Billed To</h6>
                                <address class="address-block">
                                    @if($order->customer)
                                        <strong>{{ $order->customer->name }}</strong><br>
                                        {{ $order->customer->address ?? 'N/A' }}<br>
                                        <i class="fa fa-phone me-1"></i> {{ $order->customer->phone }}<br>
                                        <i class="fa fa-envelope me-1"></i> {{ $order->customer->email ?? 'N/A' }}
                                    @else
                                        <strong class="text-danger">Customer Deleted</strong>
                                    @endif
                                </address>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <h6 class="section-title">Shipped To</h6>
                                <address class="address-block">
                                    @if($order->customer)
                                        <strong>{{ $order->customer->name }}</strong><br>
                                        {{ $order->shipping_address }}
                                    @else
                                        <strong class="text-danger">Customer Deleted</strong>
                                    @endif
                                </address>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">Order Items (Update Prices Here)</div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Product</th>
                                        <th class="text-center">Quantity</th>
                                        <th class="text-end">Unit Price</th>
                                        <th class="text-end pe-4">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->orderDetails as $detail)
                                    <tr>
                                        <td class="ps-4">
                                            {{ $detail->product->name ?? 'N/A' }}
                                            <div class="small text-muted">{{ $detail->product->product_code ?? '' }}</div>
                                        </td>
                                        <td class="text-center align-middle">
                                            <span class="qty-badge">{{ $detail->quantity }}</span>
                                            <input type="hidden" class="qty-hidden" value="{{ $detail->quantity }}">
                                            <input type="hidden" class="discount-hidden" value="{{ $detail->discount ?? 0 }}">
                                        </td>
                                        <td class="text-end">
                                            <input type="number" step="0.01" 
                                               name="prices[{{ $detail->id }}]" 
                                               class="form-control form-control-sm price-input" 
                                               value="{{ $detail->unit_price > 0 ? $detail->unit_price : ($detail->product->base_price ?? 0) }}" 
                                               min="0">
                                        </td>
                                        <td class="text-end pe-4 align-middle">
                                            <span class="row-total">{{ number_format($detail->subtotal, 2) }}</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card summary-card">
                    <div class="card-header">
                        Order Status & Summary
                    </div>

                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label text-muted small">Update Status</label>
                            <select name="status" class="form-select status-select">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="waiting" {{ $order->status == 'waiting' ? 'selected' : '' }}>Waiting</option>
                                <option value="accepted" {{ $order->status == 'accepted' ? 'selected' : '' }}>Accepted</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>

                        <ul class="list-group list-group-flush border-top pt-2">
                            <li class="list-group-item">
                                Subtotal <span id="displaySubtotal">{{ number_format($order->subtotal, 2) }}</span>
                            </li>
                            
                            {{-- REMOVED DELIVERY CHARGE UI BUT KEPT HIDDEN INPUT FOR CALCULATION --}}
                            {{-- This ensures the JS math stays correct based on DB values --}}
                            <input type="hidden" id="shippingCost" value="{{ $order->shipping_cost }}">

                            @if($order->discount > 0)
                                <li class="list-group-item text-success">
                                    Discount <span>- {{ number_format($order->discount, 2) }}</span>
                                    <input type="hidden" id="orderDiscount" value="{{ $order->discount }}">
                                </li>
                            @else
                                <input type="hidden" id="orderDiscount" value="0">
                            @endif
                            
                            <li class="list-group-item grand-total">
                                Total <span id="displayGrandTotal">{{ number_format($order->total_amount, 2) }}</span>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="card-footer bg-white p-3">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fa fa-save me-1"></i> Update Status & Prices
                            </button>

                            <hr class="my-2">

                            <div class="btn-group">
                                <a href="{{ route('order.print.a4', $order->id) }}" target="_blank" class="btn btn-outline-secondary"><i class="fa fa-print me-1"></i> A4</a>
                                <a href="{{ route('order.print.a5', $order->id) }}" target="_blank" class="btn btn-outline-secondary"><i class="fa fa-print me-1"></i> A5</a>
                          
                            </div>

                            <button type="button" id="delete-btn" class="btn btn-outline-danger"><i class="fa fa-trash me-1"></i> Delete Invoice</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </form>
        
        <form id="delete-form" action="{{ route('order.destroy', $order->id) }}" method="POST" style="display: none;">
            @csrf
            @method('DELETE')
        </form>
    </div>
</main>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    
    // --- SWEET ALERT NOTIFICATION LOGIC ---
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: '{{ session('success') }}',
            showConfirmButton: false,
            timer: 2000
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '{{ session('error') }}',
        });
    @endif
    // --------------------------------------

    // 1. Live Calculation Logic
    function calculateTotals() {
        let subtotal = 0;
        
        $('.price-input').each(function() {
            let price = parseFloat($(this).val()) || 0;
            let qty = parseFloat($(this).closest('tr').find('.qty-hidden').val()) || 0;
            let rowTotal = price * qty;
            
            $(this).closest('tr').find('.row-total').text(rowTotal.toFixed(2));
            subtotal += rowTotal;
        });

        // Hidden input value is used for calculation but not shown in UI
        let shipping = parseFloat($('#shippingCost').val()) || 0;
        let discount = parseFloat($('#orderDiscount').val()) || 0;
        let grandTotal = subtotal + shipping - discount;

        $('#displaySubtotal').text(subtotal.toFixed(2));
        $('#displayGrandTotal').text(grandTotal.toFixed(2));
    }

    // Run on load
    calculateTotals();

    // Run on input change
    $('.price-input').on('input', function() {
        calculateTotals();
    });

    // 2. Delete Confirmation
    $('#delete-btn').on('click', function(e){
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#delete-form').submit();
            }
        })
    });
});
</script>
@endsection