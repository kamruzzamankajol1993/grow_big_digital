<div class="table-responsive custom-table-container">
    <table class="table table-bordered table-hover mb-0 align-middle">
        <thead class="table-dark">
            <tr>
                <th width="5%" class="text-center">SL</th>
                <th width="40%">Product Name</th>
                <th width="10%">Image</th>
                <th width="15%">Price</th>
                <th width="15%">Code</th>
                <th width="15%" class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $key => $product)
            <tr>
                <td class="text-center fw-bold">
                    {{ ($products->currentPage() - 1) * $products->perPage() + $loop->iteration }}
                </td>
                <td>
                    <a href="{{ route('front.product.details', $product->slug) }}" class="text-decoration-none text-dark fw-bold product-name-link">
                        {{ $product->name }}
                    </a>
                </td>
                <td>
                    @php
                        // ইমেজ লজিক: প্রোডাক্ট ইমেজ > ব্র্যান্ড লোগো > ডিফল্ট
                        $productImages = $product->thumbnail_image; 
                        
                        if (is_array($productImages) && count($productImages) > 0) {
                            $thumb = $productImages[0];
                        } 
                        elseif ($product->brand && $product->brand->logo) {
                            $thumb = $product->brand->logo;
                        } 
                        else {
                            $thumb = 'no-image.png'; 
                        }
                    @endphp
                    
                     @if (is_array($productImages) && count($productImages) > 0)
                    <img class="company_listing_image" src="{{ asset('public/uploads/'.$thumb) }}" alt="{{ $product->name }}" 
                         onerror="this.src='{{ asset('public/No_Image_Available.jpg') }}'">
                    @else
                    <img class="company_listing_image" src="{{ asset('public/'.$thumb) }}" alt="{{ $product->name }}" 
                         onerror="this.src='{{ asset('public/No_Image_Available.jpg') }}'">
                    @endif
                </td>
                <td>
                    {{-- @if($product->discount_price>0)
                        <del class="text-danger small">{{ $product->base_price }}</del> <br>
                        <span class="badge bg-success">{{ $product->discount_price }}</span>
                    @elseif($product->base_price)
                        <span class="badge bg-secondary">{{ $product->base_price }}</span>
                    @else --}}
                        <span class="badge bg-primary">Asked For Price</span>
                    {{-- @endif --}}
                </td>
                <td>
                    <span class="text-muted small">{{ $product->product_code ?? 'N/A' }}</span>
                </td>
                <td class="text-center">
    {{-- onclick ইভেন্ট যুক্ত করা হয়েছে --}}
    <button class="btn cellexa_custom_btn btn-sm text-nowrap" onclick="addToCart({{ $product->id }}, 1)"> 
        <i class="bi bi-cart-plus"></i> Add To Cart
    </button>
</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center py-5">
                    <div class="empty-state">
                        <i class="bi bi-box-seam display-4 text-muted"></i>
                        <h5 class="text-muted mt-3">No products found.</h5>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="row mt-4 align-items-center">
    <div class="col-md-5 col-12 text-center text-md-start mb-3 mb-md-0">
        <p class="text-muted mb-0">
            Showing <span class="fw-bold">{{ $products->firstItem() ?? 0 }}</span> to <span class="fw-bold">{{ $products->lastItem() ?? 0 }}</span> of <span class="fw-bold">{{ $products->total() }}</span> results
        </p>
    </div>
    <div class="col-md-7 col-12">
        @if ($products->hasPages())
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center justify-content-md-end mb-0 custom-pagination">
                @if ($products->onFirstPage())
                    <li class="page-item disabled"><span class="page-link"><i class="bi bi-chevron-left"></i> Prev</span></li>
                @else
                    <li class="page-item"><a class="page-link ajax-page-link" href="#" data-page="{{ $products->currentPage() - 1 }}"><i class="bi bi-chevron-left"></i> Prev</a></li>
                @endif

                @foreach ($products->links()->elements as $element)
                    @if (is_string($element))
                        <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
                    @endif
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $products->currentPage())
                                <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                            @else
                                <li class="page-item"><a class="page-link ajax-page-link" href="#" data-page="{{ $page }}">{{ $page }}</a></li>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                @if ($products->hasMorePages())
                    <li class="page-item"><a class="page-link ajax-page-link" href="#" data-page="{{ $products->currentPage() + 1 }}">Next <i class="bi bi-chevron-right"></i></a></li>
                @else
                    <li class="page-item disabled"><span class="page-link">Next <i class="bi bi-chevron-right"></i></span></li>
                @endif
            </ul>
        </nav>
        @endif
    </div>
</div>