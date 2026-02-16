@foreach($products as $product)
<div class="col-lg-6 col-12">
    {{-- প্রোডাক্ট ডিটেইলস লিংক --}}
    <a href="{{ route('front.product.details', $product->slug) }}">
        <div class="cellexa_item_box">
            <div class="cellexa_item_box_img1">
                {{-- ইমেজ ডিকোড এবং ডিসপ্লে --}}
                               @php
    // No need for json_decode because the Model casts it automatically
    $images = $product->thumbnail_image;
    
    // Check if it is an array and has items
    $thumb = (is_array($images) && count($images) > 0) ? $images[0] : 'default.png';
@endphp

@if (is_array($images) && count($images) > 0)
                                
                                <img src="{{ asset('public/uploads/'.$thumb) }}" onerror="this.src='{{ asset('public/No_Image_Available.jpg') }}'" alt="{{ $product->name }}">
                                @else
                                <img src="{{ asset('public/'.$thumb) }}"  onerror="this.src='{{ asset('public/No_Image_Available.jpg') }}'"alt="{{ $product->name }}">
                                @endif
            </div>
            
            <div class="cellexa_item_box_text1">
                {{-- নাম এবং লিমিট --}}
                <h1>{{ Str::limit($product->name, 50) }}</h1>
                
                <div class="cellexa_item_box_list">
                    {{-- ডেসক্রিপশন স্ট্রিপ ট্যাগ এবং লিমিট --}}
                    <div class="product-short-desc text-muted">
                        {!! Str::limit(strip_tags($product->description), 150) !!}
                    </div>
                </div>
            </div>
        </div>
    </a>
</div>
@endforeach