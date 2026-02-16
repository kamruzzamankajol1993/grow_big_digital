@extends('front.master.master')

@section('title')
    {{ $category->name }} - Products - {{ $front_ins_name }}
@endsection

@section('css')
<style>
    /* লোডার ডিজাইন */
    .ajax-load {
        background: #fff;
        padding: 20px 0px;
        width: 100%;
        text-align: center;
        display: none; /* শুরুতে হাইড */
    }
    /* লিংক থেকে আন্ডারলাইন রিমুভ */
    .cellexa_item_box a {
        text-decoration: none;
        color: inherit;
    }
</style>
@endsection

@section('body')
<main>
    <section class="section product_page">
        <div class="container">
            <div class="mt-5 mb-5">
                {{-- ডাইনামিক টাইটেল --}}
                <h2 class="home_category_title">{{ $category->name }}</h2>
                
                {{-- ডেসক্রিপশন --}}
                <p>
                    @if($category->description)
                        {!! $category->description !!}
                    @else
                        Cellexa is an independent provider of {{ $category->name }} from the major manufacturers.
                    @endif
                </p>
            </div>

            <div class="productpage_company_logo">
                <div class="row g-3" id="product-data-container">
                    {{-- প্রথমে include করা হবে --}}
                    @include('front.category.product_data')
                </div>
            </div>

            <div class="ajax-load text-center">
                <p><i class="fa fa-spinner fa-spin"></i> Loading More Products...</p>
            </div>

            @if($products->count() == 0)
                <div class="text-center mt-4">
                    <p class="text-muted">No products found in this category.</p>
                </div>
            @endif

        </div>
    </section>
</main>
@endsection

@section('scripts')
<script>
    // স্ক্রল প্যাজিনেশন স্ক্রিপ্ট
    var page = 1; 
    var isLoading = false; 
    var hasMoreData = true; 

    $(window).scroll(function() {
        // পেজের নিচে আসার ৫০০px আগে লোড শুরু হবে
        if ($(window).scrollTop() + $(window).height() >= $(document).height() - 500) {
            if (!isLoading && hasMoreData) {
                page++;
                loadMoreData(page);
            }
        }
    });

    function loadMoreData(page) {
        isLoading = true;
        $('.ajax-load').show(); // লোডার দেখান

        $.ajax({
            url: '?page=' + page,
            type: "get",
            beforeSend: function() {
                // রিকোয়েস্টের আগে কিছু করার থাকলে
            }
        })
        .done(function(data) {
            isLoading = false;
            $('.ajax-load').hide(); // লোডার লুকান

            if (data.trim() == "") {
                hasMoreData = false;
                $('.ajax-load').html("No more products found");
                return;
            }

            // নতুন ডাটা যোগ করুন
            $("#product-data-container").append(data);
        })
        .fail(function(jqXHR, ajaxOptions, thrownError) {
            isLoading = false;
            $('.ajax-load').hide();
            console.log('Server error');
        });
    }
</script>
@endsection