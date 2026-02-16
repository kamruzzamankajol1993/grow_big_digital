@if(count($cart) > 0)
    <div class="cellexa_company_category_cart_list_container">
        @foreach($cart as $id => $details)
        <div class="cellexa_company_category_cart_item">
            <img src="{{ asset('public/uploads/'.$details['image']) }}" 
                 class="cellexa_company_category_cart_img" 
                 alt="{{ $details['name'] }}"
                 onerror="this.src='{{ asset('public/no-image.png') }}'">
            
            <div class="cellexa_company_category_cart_details">
                <h6 class="mb-1">{{ Str::limit($details['name'], 30) }}</h6>
                <small class="text-muted d-block mb-1">{{ $details['code'] ?? '' }}</small>
                
                <div class="cellexa_company_category_qty_controls">
                    <div class="input-group input-group-sm" style="width: 110px;">
                        <button class="btn btn-outline-secondary cart-qty-minus" type="button" data-id="{{ $id }}">
                            <i class="bi bi-dash"></i>
                        </button>
                        <input type="text" class="form-control text-center cart-qty-input" 
                               value="{{ $details['quantity'] }}" readonly>
                        <button class="btn btn-outline-secondary cart-qty-plus" type="button" data-id="{{ $id }}">
                            <i class="bi bi-plus"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <button class="cellexa_company_category_delete_btn remove-from-cart" data-id="{{ $id }}" aria-label="Delete">
                <i class="bi bi-trash"></i>
            </button>
        </div>
        @endforeach
    </div>

    <div class="cellexa_company_category_cart_footer">
        {{-- ONCLICK ADDED HERE --}}
        <button type="button" onclick="initiateQuoteRequest()" class="btn btn-dark w-100">
            Request For Quote
        </button>
    </div>
@else
    <div class="text-center py-5">
        <i class="bi bi-cart-x display-4 text-muted"></i>
        <p class="mt-3 text-muted">Your Quote List is Empty</p>
    </div>
@endif