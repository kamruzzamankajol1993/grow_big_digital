@foreach($subCategories as $subCat)
<div class="col cellexa_company_category_col_item">
    <div class="cellexa_company_category_product_card">
        
        <div class="cellexa_company_category_image_container">
            {{-- ইমেজ --}}
            <img src="{{ asset('public/'.$subCat->image) }}" alt="{{ $subCat->name }}"
                class="cellexa_company_category_product_img" 
                onerror="this.src='{{ asset('public/no-image.jpg') }}'"> 
        </div>

        <div class="cellexa_company_category_card_body">
            <div>
                <h5 class="cellexa_company_category_product_name">{{ $subCat->name }}</h5>
            </div>

            {{-- লিংক লজিক --}}
            {{-- যদি এই সাব-ক্যাটাগরির আরও চাইল্ড থাকে, তবে আবার সাব-ক্যাটাগরি পেজে যাবে --}}
            @if($subCat->children_count > 0)
                <a href="{{ route('front.company.category.subcategories', $subCat->slug) }}" 
                   class="cellexa_company_category_explore_btn">
                   View Sub-Categories
                </a>
            @else
            {{-- যদি চাইল্ড না থাকে, সরাসরি প্রোডাক্ট পেজে যাবে --}}
                <a href="{{ route('front.company.category.products', $subCat->slug) }}" 
                   class="cellexa_company_category_explore_btn">
                   View Products
                </a>
            @endif

        </div>
    </div>
</div>
@endforeach