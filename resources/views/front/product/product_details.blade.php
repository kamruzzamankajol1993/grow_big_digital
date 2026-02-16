@extends('front.master.master')

@section('title')
    {{ $product->name }} - Details
@endsection

@section('css')
<style>
    /* Product Details Styles */
    .product-title {
        font-size: 26px;
        font-weight: 700;
        color: #333;
        margin-bottom: 10px;
    }
    .product-code {
        font-size: 14px;
        color: #666;
        margin-bottom: 15px;
    }
    .product-price {
        font-size: 24px;
        font-weight: 600;
        color: #0d6efd;
        margin-bottom: 20px;
    }
    .old-price {
        font-size: 18px;
        color: #999;
        text-decoration: line-through;
        margin-right: 10px;
    }
    .qty-input-group {
        width: 140px;
    }
    .qty-btn {
        background: #f8f9fa;
        border: 1px solid #ced4da;
        color: #333;
    }
    .qty-input {
        text-align: center;
        border-left: 0;
        border-right: 0;
        border-color: #ced4da;
    }
    .main-image-box {
        border: 1px solid #eee;
        border-radius: 5px;
        padding: 10px;
        margin-bottom: 15px;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 300px;
    }
    .main-image {
        max-width: 100%;
        height: auto;
        max-height: 400px;
    }
    .thumb-image {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border: 1px solid #eee;
        cursor: pointer;
        margin-right: 10px;
        border-radius: 4px;
    }
    .thumb-image:hover, .thumb-image.active {
        border-color: #0d6efd;
    }
    .nav-tabs .nav-link {
        color: #555;
        font-weight: 600;
    }
    .nav-tabs .nav-link.active {
        color: #0d6efd;
    }
    .tab-content {
        padding: 20px;
        border: 1px solid #dee2e6;
        border-top: none;
        background: #fff;
    }
    /* Specification Table */
    .spec-table th {
        width: 30%;
        background-color: #f9f9f9;
    }
</style>
@endsection

@section('body')
<main>
    <section class="section product_details_page py-5">
        <div class="container">
            
            <div class="row g-5">
                <div class="col-lg-5">
                    @php
                        // ১. প্রোডাক্ট ইমেজের অ্যারে নেওয়া
                        $images = $product->thumbnail_image; 
                        
                        // ২. মেইন ইমেজ সেট করার লজিক
                        if (is_array($images) && count($images) > 0) {
                            // যদি প্রোডাক্ট ইমেজ থাকে, প্রথমটি দেখাবে
                            $mainImage = $images[0];
                        } elseif ($product->brand && $product->brand->logo) {
                            // যদি প্রোডাক্ট ইমেজ না থাকে, ব্র্যান্ড লোগো দেখাবে
                            $mainImage = $product->brand->logo;
                        } else {
                            // কিছুই না থাকলে ডিফল্ট ইমেজ
                            $mainImage = 'no-image.png'; 
                        }
                    @endphp




                    <div class="main-image-box">

                        @if (is_array($images) && count($images) > 0)
                        <img src="{{ asset('public/uploads/'.$mainImage) }}" alt="{{ $product->name }}" id="mainProductImage" class="main-image"
                             onerror="this.src='{{ asset('public/no-image.png') }}'">
                             @else
                              <img src="{{ asset('public/'.$mainImage) }}" alt="{{ $product->name }}" id="mainProductImage" class="main-image"
                             onerror="this.src='{{ asset('public/no-image.png') }}'">
                                @endif
                    </div>

                    @if(is_array($images) && count($images) > 1)
                        <div class="d-flex overflow-auto">
                            @foreach($images as $img)
                                <img src="{{ asset('public/uploads/'.$img) }}" class="thumb-image" onclick="changeImage(this)">
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="col-lg-7">
                    <h1 class="product-title">{{ $product->name }}</h1>
                    
                    <div class="product-code">
                        <strong>Product Code:</strong> {{ $product->product_code ?? 'N/A' }}
                        @if($product->brand)
                            <span class="mx-2">|</span> <strong>Brand:</strong> {{ $product->brand->name }}
                        @endif
                    </div>

                    <div class="product-price">
                        {{-- @if($product->discount_price>0)
                            <span class="old-price">${{ $product->base_price }}</span>
                            <span>${{ $product->discount_price }}</span>
                        @elseif($product->base_price)
                            <span>${{ $product->base_price }}</span>
                        @else --}}
                            <span class="badge bg-primary fs-6">Asked For Price</span>
                        {{-- @endif --}}
                    </div>

                    @if($product->description)
                        <div class="mb-4 text-muted">
                            {!! Str::limit(strip_tags($product->description), 200) !!}
                        </div>
                    @endif

                    <div class="d-flex align-items-center mb-4 flex-wrap gap-3">
                        
                        <div class="input-group qty-input-group">
                            <button class="btn qty-btn" type="button" onclick="decrementQty()">
                                <i class="bi bi-dash"></i>
                            </button>
                            <input type="text" class="form-control qty-input" id="qtyInput" value="1" readonly>
                            <button class="btn qty-btn" type="button" onclick="incrementQty()">
                                <i class="bi bi-plus"></i>
                            </button>
                        </div>

                        <button class="btn cellexa_custom_btn px-4 py-2" onclick="addToCart({{ $product->id }}, document.getElementById('qtyInput').value)">
    <i class="bi bi-cart-check-fill me-2"></i> Add to Cart
</button>

                        @if(!$product->base_price)
                            <a href="#" class="btn btn-success px-4 py-2">
                                <i class="bi bi-whatsapp me-2"></i> Enquire Now
                            </a>
                        @endif
                    </div>

                    <div class="mt-4 pt-3 border-top">
                        <p class="mb-1"><strong>Availability:</strong> 
                            @if($product->status == 1)
                                <span class="text-success"><i class="bi bi-check-circle-fill"></i> In Stock</span>
                            @else
                                <span class="text-danger"><i class="bi bi-x-circle-fill"></i> Out of Stock</span>
                            @endif
                        </p>
                    </div>

                </div>
            </div>

            <div class="row mt-5">
                <div class="col-12">
                    <ul class="nav nav-tabs" id="productTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="desc-tab" data-bs-toggle="tab" data-bs-target="#desc" type="button" role="tab">Description</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="spec-tab" data-bs-toggle="tab" data-bs-target="#spec" type="button" role="tab">Specification</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="productTabContent">
                        <div class="tab-pane fade show active" id="desc" role="tabpanel">
                            @if($product->description)
                                {!! $product->description !!}
                            @else
                                <p class="text-muted">No description available.</p>
                            @endif
                        </div>
                        
                        <div class="tab-pane fade" id="spec" role="tabpanel">
                            @if($product->specification)
                                {!! $product->specification !!}
                            @else
                                <p class="text-muted">No specific specifications listed.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @if(isset($relatedProducts) && $relatedProducts->count() > 0)
            <div class="row mt-5">
                <div class="col-12 mb-4">
                    <h3>Related Products</h3>
                </div>
                @foreach($relatedProducts as $related)
                <div class="col-lg-3 col-md-4 col-6 mb-4">
                    <a href="{{ route('front.product.details', $related->slug) }}" class="text-decoration-none">
                        <div class="card h-100 border-0 shadow-sm">
                            @php
                                $rImages = $related->thumbnail_image;
                                if (is_array($rImages) && count($rImages) > 0) {
                                    $rThumb = $rImages[0];
                                } elseif ($related->brand && $related->brand->logo) {
                                    $rThumb = $related->brand->logo;
                                } else {
                                    $rThumb = 'no-image.png';
                                }
                            @endphp
                            @if (is_array($rImages) && count($rImages) > 0)
                            <img src="{{ asset('public/uploads/'.$rThumb) }}"   onerror="this.src='{{ asset('public/no-image.png') }}'" class="card-img-top p-3" alt="{{ $related->name }}" style="height: 200px; object-fit: contain;">
                            @else
                            <img src="{{ asset('public/'.$rThumb) }}"  onerror="this.src='{{ asset('public/no-image.png') }}'" class="card-img-top p-3" alt="{{ $related->name }}" style="height: 200px; object-fit: contain;">
                            @endif
                            <div class="card-body text-center">
                                <h6 class="card-title text-dark">{{ Str::limit($related->name, 40) }}</h6>
                                {{-- @if($related->base_price)
                                    <p class="text-primary fw-bold mb-0">${{ $related->base_price }}</p>
                                @else --}}
                                    <span class="badge bg-secondary">Asked For Price</span>
                                {{-- @endif --}}
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
            @endif

        </div>
    </section>
</main>
@endsection

@section('scripts')
<script>
    // 1. Change Main Image on Thumbnail Click
    function changeImage(element) {
        var newSrc = element.src;
        document.getElementById('mainProductImage').src = newSrc;
        
        // Active Class Handling
        var thumbnails = document.querySelectorAll('.thumb-image');
        thumbnails.forEach(img => img.classList.remove('active'));
        element.classList.add('active');
    }

    // 2. Quantity Increment
    function incrementQty() {
        var qtyInput = document.getElementById('qtyInput');
        var currentQty = parseInt(qtyInput.value);
        qtyInput.value = currentQty + 1;
    }

    // 3. Quantity Decrement
    function decrementQty() {
        var qtyInput = document.getElementById('qtyInput');
        var currentQty = parseInt(qtyInput.value);
        if (currentQty > 1) {
            qtyInput.value = currentQty - 1;
        }
    }

    
</script>
@endsection