@extends('front.master.master')

@section('title')
    {{ $brand->name }} - Products
@endsection

@section('css')
<style>
    /* লেআউট স্টাইল */
    .custom-toolbar {
        background: #f8f9fa;
        padding: 15px;
        border: 1px solid #dee2e6;
        border-radius: 5px 5px 0 0;
        border-bottom: none;
    }
    .custom-table-container {
        border: 1px solid #dee2e6;
        border-radius: 0 0 5px 5px;
        background: #fff;
    }
    .loading-overlay {
        position: relative;
        opacity: 0.6;
        pointer-events: none;
    }
    .filter-sidebar {
        background: #fff;
        border: 1px solid #eee;
        border-radius: 5px;
        padding: 20px;
    }
    .filter-title {
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 15px;
        border-bottom: 2px solid #0d6efd;
        padding-bottom: 10px;
        display: inline-block;
    }
    .filter-group-title {
        font-size: 16px;
        font-weight: 600;
        margin-top: 15px;
        margin-bottom: 10px;
        color: #444;
    }
    .company_listing_image {
        width: 50px; 
        height: 50px; 
        object-fit: cover;
        border-radius: 4px;
        border: 1px solid #eee;
    }
    .custom-pagination .page-link {
        color: #333;
        border: 1px solid #dee2e6;
        margin: 0 3px;
        border-radius: 4px;
        padding: 6px 12px;
        font-weight: 500;
    }
    .custom-pagination .page-item.active .page-link {
        background-color: #0d6efd;
        border-color: #0d6efd;
        color: #fff;
    }
</style>
@endsection

@section('body')
<main>
    <section class="section product_page">
        <div class="container">
            <div class="mt-5 mb-5">
                <h2 class="home_category_title">{{ $brand->name }} Products</h2>
                @if($brand->description)
                    <div class="text-muted">{!! $brand->description !!}</div>
                @endif
            </div>

            <div class="row g-4">
                
                <div class="col-lg-3">
                    <div class="offcanvas-lg offcanvas-start" tabindex="-1" id="filterCanvas">
                        <div class="offcanvas-header">
                            <h5 class="offcanvas-title">Filters</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#filterCanvas"></button>
                        </div>
                        
                        <div class="offcanvas-body d-block filter-sidebar shadow-sm">
                            <h3 class="filter-title">Filter Products</h3>

                            @if($sidebarCategories->count() > 0)
                                <div class="filter-group">
                                    <h5 class="filter-group-title">Categories</h5>
                                    @foreach($sidebarCategories as $cat)
                                        <div class="form-check custom-checkbox mb-2">
                                            <input class="form-check-input filter-checkbox" type="checkbox" 
                                                   value="{{ $cat->id }}" 
                                                   id="cat_{{ $cat->id }}" 
                                                   name="categories[]">
                                            <label class="form-check-label" for="cat_{{ $cat->id }}">
                                                {{ $cat->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-muted small">No category filters available.</p>
                            @endif

                        </div>
                    </div>
                </div>

                <div class="col-lg-9 col-12">
                    
                    <button class="btn btn-primary w-100 mb-3 d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#filterCanvas">
                        <i class="bi bi-funnel"></i> Show Filters
                    </button>

                    <div class="row custom-toolbar align-items-center g-3 mx-0">
                        <div class="col-md-6 col-12 d-flex align-items-center">
                            <label class="me-2 fw-bold text-secondary">Show</label>
                            <select id="per_page" class="form-select form-select-sm w-auto border-secondary">
                                <option value="10" selected>10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                            <span class="ms-2 text-secondary">entries</span>
                        </div>

                        <div class="col-md-6 col-12 text-md-end">
                            <div class="d-inline-flex align-items-center w-100 justify-content-md-end">
                                <label for="search" class="me-2 fw-bold text-secondary">Search:</label>
                                <input type="text" id="search" class="form-control form-control-sm w-auto" placeholder="Name or Code...">
                            </div>
                        </div>
                    </div>

                    <div id="product-table-container">
                        @include('front.company.company_wise_product_data')
                    </div>

                    <input type="hidden" id="ajax_url" value="{{ route('front.company.products', $brand->slug) }}">
                </div>

            </div>
        </div>
    </section>
</main>
@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        
        function fetch_data(page) {
            var search = $('#search').val();
            var per_page = $('#per_page').val();
            var url = $('#ajax_url').val();
            
            // ক্যাটাগরি ফিল্টার অ্যারে
            var categories = [];
            $('.filter-checkbox:checked').each(function() {
                categories.push($(this).val());
            });

            $('#product-table-container').addClass('loading-overlay');

            $.ajax({
                url: url,
                type: "GET",
                data: {
                    page: page,
                    search: search,
                    per_page: per_page,
                    categories: categories
                },
                success: function (data) {
                    $('#product-table-container').html(data);
                    $('#product-table-container').removeClass('loading-overlay');
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                    $('#product-table-container').removeClass('loading-overlay');
                }
            });
        }

        $(document).on('keyup', '#search', function () {
            fetch_data(1);
        });

        $(document).on('change', '#per_page', function () {
            fetch_data(1);
        });

        $(document).on('change', '.filter-checkbox', function () {
            fetch_data(1);
        });

        $(document).on('click', '.ajax-page-link', function (event) {
            event.preventDefault();
            var page = $(this).data('page');
            fetch_data(page);
        });

    });
</script>
@endsection