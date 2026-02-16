@extends('front.master.master')

@section('title')
    {{ $category->name }} - Companies - {{ $front_ins_name }}
@endsection

@section('css')
<style>
    /* লোডার ডিজাইন */
    .ajax-load {
        background: #e1e1e1;
        padding: 10px 0px;
        width: 100%;
        text-align: center;
        margin-top: 20px;
        display: none; /* শুরুতে হাইড থাকবে */
    }
</style>
@endsection

@section('body')

<main>
    <section class="section product_page">
        <div class="container">
            <div class="mt-5 mb-5">
                <h2 class="home_category_title">{{ $category->name }}</h2>
                <p>
                    @if($category->description)
                        {!! $category->description !!}
                    @else
                        Cellexa is an independent provider of {{ $category->name }} from the major manufacturers.
                    @endif
                </p>
            </div>

            <div class="productpage_company_logo">
                <div class="row g-3" id="company-data-container">
                    {{-- প্রথম ১০টি ডাটা লোড হবে --}}
                    @include('front.category.company_data')
                </div>
            </div>

            <div class="ajax-load text-center">
                <p><i class="fa fa-spinner fa-spin"></i> Loading More Companies...</p>
            </div>
            
            @if($brands->count() == 0)
                <div class="text-center mt-4">
                    <p class="text-muted">No companies found.</p>
                </div>
            @endif
        </div>
    </section>
</main>

@endsection

@section('scripts')
<script>
    // অন স্ক্রল প্যাজিনেশন স্ক্রিপ্ট
    var page = 1; // বর্তমান পেজ
    var isLoading = false; // ডাটা লোড হচ্ছে কিনা চেক করার ফ্ল্যাগ
    var hasMoreData = true; // আর ডাটা আছে কিনা চেক

    $(window).scroll(function() {
        // উইন্ডো হাইট + স্ক্রল পজিশন >= ডকুমেন্ট হাইট - ৫০০ পিক্সেল (নিচে আসার আগেই লোড শুরু হবে)
        if ($(window).scrollTop() + $(window).height() >= $(document).height() - 500) {
            if (!isLoading && hasMoreData) {
                page++;
                loadMoreData(page);
            }
        }
    });

    function loadMoreData(page) {
        isLoading = true;
        $('.ajax-load').show(); // লোডার শো

        $.ajax({
            url: '?page=' + page,
            type: "get",
            beforeSend: function() {
                // কিছু করার থাকলে এখানে
            }
        })
        .done(function(data) {
            isLoading = false;
            $('.ajax-load').hide(); // লোডার হাইড

            if (data.trim() == "") {
                hasMoreData = false; // আর ডাটা নেই
                $('.ajax-load').html("No more records found");
                return;
            }

            // নতুন ডাটা অ্যাপেন্ড করা
            $("#company-data-container").append(data);
        })
        .fail(function(jqXHR, ajaxOptions, thrownError) {
            isLoading = false;
            $('.ajax-load').hide();
            console.log('Server error');
        });
    }
</script>
@endsection