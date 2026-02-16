@foreach($categories as $category)
<div class="col cellexa_company_category_col_item">
    <div class="cellexa_company_category_product_card">
        
        <div class="cellexa_company_category_image_container">
            {{-- ক্যাটাগরি ইমেজ --}}
            <img src="{{ asset('public/' . $category->image) }}" alt="{{ $category->name }}"
                class="cellexa_company_category_product_img" 
                onerror="this.src='{{ asset('public/No_Image_Available.jpg') }}'"> 
        </div>

        <div class="cellexa_company_category_card_body">
            <div>
                <h5 class="cellexa_company_category_product_name">{{ $category->name }}</h5>
            </div>

            {{-- লজিক: সাব-ক্যাটাগরি আছে কিনা চেক --}}
            @if($category->children_count > 0)
                <a href="{{ route('front.company.category.subcategories', $category->slug) }}" 
                   class="cellexa_company_category_explore_btn">
                   View Sub-Categories
                </a>
            @else
                <a href="{{ route('front.company.category.products', $category->slug) }}" 
                   class="cellexa_company_category_explore_btn">
                   View Products
                </a>
            @endif

        </div>
    </div>
</div>
@endforeach