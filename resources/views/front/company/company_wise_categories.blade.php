@extends('front.master.master')

@section('title')
    {{ $brand->name }} - Categories
@endsection

@section('css')
<style>
    .cellexa_company_category_form_check {
        cursor: pointer;
        margin-bottom: 10px;
    }
    .cellexa_company_category_form_check a {
        text-decoration: none;
        color: #333;
        display: block;
        width: 100%;
    }
    /* লোডার ডিজাইন */
    .ajax-load {
        background: #fff;
        padding: 20px 0px;
        width: 100%;
        text-align: center;
        display: none;
    }
</style>
@endsection

@section('body')
 <main>
        <section class="product_page">
            <div class="container">
                <div class="mt-5 mb-5">
                    <h2 class="home_category_title">{{ $brand->name }}</h2>
                    <p>
                        @if($brand->description)
                            {!! $brand->description !!}
                        @else
                            Explore the extensive range of products from {{ $brand->name }}.
                        @endif
                    </p>
                </div>
            </div>
        </section>

        <section class="section">
            <div class="cellexa_company_category_page_wrapper container">
                <div class="cellexa_company_category_row row g-4">

                    <div class="cellexa_company_category_col_sidebar col-lg-3">
                        <div class="offcanvas-lg offcanvas-start cellexa_company_category_filter_sidebar" tabindex="-1" id="cellexaFilterCanvas">
                            <div class="offcanvas-header d-lg-none">
                                <h5 class="offcanvas-title">Filters</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#cellexaFilterCanvas" aria-label="Close"></button>
                            </div>
                            <div class="offcanvas-body d-block">
                                <div class="cellexa_company_category_filter_header">
                                    <h3 class="cellexa_company_category_filter_title">Filter by Brand</h3>
                                </div>
                                <div class="cellexa_company_category_filter_group">
                                    <h5 class="cellexa_company_category_filter_subtitle">Company Name</h5>
                                    @foreach($all_brands as $b)
                                    <div class="form-check cellexa_company_category_form_check">
                                        <input class="form-check-input cellexa_company_category_checkbox_input"
                                            type="radio" 
                                            name="cellexa_brand_filter" 
                                            id="brand_{{ $b->id }}"
                                            {{ $b->id == $brand->id ? 'checked' : '' }}
                                            onclick="window.location='{{ route('front.company.categories', $b->slug) }}'">
                                        <label class="form-check-label cellexa_company_category_checkbox_label" for="brand_{{ $b->id }}">
                                            <a href="{{ route('front.company.categories', $b->slug) }}">{{ $b->name }}</a>
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="cellexa_company_category_col_main col-lg-9 col-12">
                        <button class="btn btn-outline-dark w-100 mb-4 d-lg-none" type="button"
                            data-bs-toggle="offcanvas" data-bs-target="#cellexaFilterCanvas"
                            aria-controls="cellexaFilterCanvas">
                            <i class="bi bi-funnel"></i> Filter Companies
                        </button>

                        <div class="d-flex justify-content-between align-items-center mb-4 cellexa_company_category_results_header">
                            {{-- টোটাল কাউন্ট দেখানোর জন্য --}}
                            <span class="text-muted cellexa_company_category_result_count">Showing Categories</span>
                        </div>

                        <div class="row row-cols-2 row-cols-sm-2 row-cols-lg-3 g-4 cellexa_company_category_product_grid" id="category-data-container">
                            
                            @include('front.company.company_category_data')

                        </div>

                        <div class="ajax-load text-center">
                            <p><i class="fa fa-spinner fa-spin"></i> Loading More Categories...</p>
                        </div>

                        @if($categories->count() == 0)
                            <div class="col-12">
                                <p class="text-center text-muted">No categories found for this company.</p>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection

@section('scripts')
<script>
    // Infinite Scroll Script
    var page = 1;
    var isLoading = false;
    var hasMoreData = true;

    $(window).scroll(function() {
        if ($(window).scrollTop() + $(window).height() >= $(document).height() - 500) {
            if (!isLoading && hasMoreData) {
                page++;
                loadMoreData(page);
            }
        }
    });

    function loadMoreData(page) {
        isLoading = true;
        $('.ajax-load').show();

        $.ajax({
            url: '?page=' + page,
            type: "get",
            beforeSend: function() {
            }
        })
        .done(function(data) {
            isLoading = false;
            $('.ajax-load').hide();

            if (data.trim() == "") {
                hasMoreData = false;
                $('.ajax-load').html("No more categories found");
                return;
            }

            $("#category-data-container").append(data);
        })
        .fail(function(jqXHR, ajaxOptions, thrownError) {
            isLoading = false;
            $('.ajax-load').hide();
            console.log('Server error');
        });
    }
</script>
@endsection