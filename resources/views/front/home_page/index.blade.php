@extends('front.master.master')

@section('title')
{{ $front_ins_name }} -  {{ $front_ins_title }}
@endsection

@section('css')
@endsection

@section('body')
 <!-- Hero Section -->
    @include('front.include.hero')
    <!-- /Hero Section -->

    <section class="section about_section">
        <div class="container" data-aos="fade-up" data-aos-delay="50">
            <div class="row">
                <div class="col-lg-7">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="about_us_content">
                                <h4>ADVANCING HEALTHCARE, EMPOWERING INNOVATION</h4>

                                <div class="about_us_content_below">
                                    <p>Axium Healthcare Solutions is an Australian supplier of hospital-grade
                                        consumables
                                        and
                                        advanced laboratory equipment, serving hospitals, research institutions, and
                                        healthcare
                                        organisations nationwide.</p>
                                    <p>Through partnerships with globally recognised manufacturers, we deliver
                                        innovative,
                                        reliable,
                                        and compliant solutions that support excellence in healthcare, diagnostics, and
                                        life
                                        sciences. </p>
                                    <p>At Axium, we go beyond supply — we are your trusted partner in advancing
                                        healthcare
                                        and
                                        scientific innovation across Australia and beyond.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 mt-4">
                            <div class="about_us_content">
                                <h4>Our Mission</h4>

                                <div class="about_us_content_below">
                                    <p>To deliver premium pharmaceutical laboratory equipment that support innovation,
                                        accuracy, and compliance for clients across Australia.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 mt-4">
                            <div class="about_us_content">
                                <h4>Our Vision</h4>

                                <div class="about_us_content_below">
                                    <p>To be recognised as Australia’s most reliable and trusted partner for
                                        pharmaceutical laboratory solutions, contributing to the growth and advancement
                                        of the nation’s life sciences sector.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="about_image">
                        <img src="{{ asset('/') }}public/front/assets/img/home/about.jpg" alt="">
                    </div>
                </div>
            </div>
        </div>
    </section>
 @if($home_first_category)
    <section class="section home_category">
        <div class="container">
            <div class="row">
                <div class="col-lg-7 col-12">
                    <h2 class="home_category_title">{{ $home_first_category->name }}</h2>
                </div>
                <div class="col-lg-5 col-12">
                    <div class="home_category_sub">
                        <a href="{{ route('front.category.companies', $home_first_category->slug) }}" class="btn Axium_custom_btn">Explore All Company</a>
                    </div>
                </div>
            </div>
            <div class="row g-3 mt-4">

                 @foreach($home_first_category->brands as $company)
                <div class="col-lg-3 col-6">
                   {{-- লজিক শুরু: কোম্পানির নিজস্ব ক্যাটাগরি আছে কিনা চেক --}}
        @if($company->company_categories_count > 0)
            {{-- যদি ক্যাটাগরি থাকে -> ক্যাটাগরি পেজে যাবে --}}
            <a href="{{ route('front.company.categories', $company->slug) }}">
        @else
            {{-- যদি ক্যাটাগরি না থাকে -> প্রোডাক্ট পেজে যাবে --}}
            <a href="{{ route('front.company.products', $company->slug) }}">
        @endif
        {{-- লজিক শেষ --}}
                        <div class="Axium_item_box">
                            <div class="Axium_item_box_img">
                                <img src="{{ asset('public/'.$company->logo) }}" alt="{{ $company->name }}">
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
               
            </div>
        </div>
    </section>

    @endif


 @if($home_second_category)
    <section class="section home_category">
        <div class="container">
            <div class="row">
                <div class="col-lg-7 col-12">
                    <h2 class="home_category_title">{{ $home_second_category->name }}</h2>
                </div>
            </div>
            <div class="row g-3 mt-4">

                @foreach($home_second_category->products as $product)
                <div class="col-lg-3 col-12">
                    <div class="Axium_item_box">
                        <div class="Axium_item_box_img1">
                             @php
    // No need for json_decode because the Model casts it automatically
    $images = $product->thumbnail_image;
    
    // Check if it is an array and has items
    $thumb = (is_array($images) && count($images) > 0) ? $images[0] : 'default.png';
@endphp
                                  <a href="{{ route('front.product.details', $product->slug) }}" class="text-decoration-none text-dark">
                                <img src="{{ asset('public/uploads/'.$thumb) }}" alt="{{ $product->name }}">
                                  </a>
                        </div>
                        <div class="Axium_item_box_text1">
                             <a href="{{ route('front.product.details', $product->slug) }}" class="text-decoration-none text-dark">
                                 <h1>{{ Str::limit($product->name, 30) }}</h1>
                             </a>
                            <div class="Axium_item_box_list">
                                {!! Str::limit(strip_tags($product->description), 100) !!}
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
                

            </div>
        </div>
    </section>
    @endif

  
      <section class="section home_details">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-12 mb-3">
                    <div class="home_details_left">
                        <img src="{{ asset('/') }}public/front/assets/img/home/2.png" alt="">
                    </div>
                </div>
                <div class="col-lg-8 col-md-8 col-sm-12 mb-3">
                    <div class="home_details_right">
                        <h2>Our Story</h2>
                        <p>Axium Healthcare Solutions, a brand of Aurexa Group Pty Ltd, Australia, was founded to
                            bridge the gap between global innovation and Australia’s evolving healthcare and scientific
                            needs. With a commitment to quality, compliance, and reliability, Axium has become a
                            trusted
                            supplier of hospital-grade consumables and advanced laboratory equipment for hospitals,
                            research facilities, and healthcare organisations nationwide and around the globe.</p>
                        <h2>Achievements</h2>
                        <ul>
                            <li>Supplied and supported laboratory equipment and hospital consumables for leading
                                healthcare and research institutions across Asia-Pacific, South Africa. the Middle East
                                and parts of Europe.</li>
                            <li>Partnered with globally recognised manufacturers delivering innovative and compliant
                                healthcare and laboratory solutions.
                            </li>
                            <li>Recognised as a preferred supplier to hospitals, pharmaceutical, and research
                                organisations nationwide.</li>
                            <li>Proven track record of on-time delivery, responsive support, and competitive pricing.
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section faq-section">
        <div class="container">
            <h2>Frequently Asked Questions</h2>

            <!-- Added ID "faqAccordion" here to act as the common parent -->
            <div class="row" id="faqAccordion">

                <!-- Left Column (3 Questions) -->
                <div class="col-lg-6">
                    <div class="accordion custom-accordion">

                        <!-- Q1 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    What products does Axium provide?
                                </button>
                            </h2>
                            <!-- Changed parent to #faqAccordion -->
                            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                                data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Axium supplies hospital consumables and laboratory equipment, supporting both
                                    clinical and research environments.
                                </div>
                            </div>
                        </div>

                        <!-- Q2 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingTwo">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    Do you offer after-sales support?
                                </button>
                            </h2>
                            <!-- Changed parent to #faqAccordion -->
                            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                                data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Yes. We ensure product quality and provide prompt replacements or assistance when
                                    needed.
                                </div>
                            </div>
                        </div>

                        <!-- Q3 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingThree">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                    Can you source specialised products?
                                </button>
                            </h2>
                            <!-- Changed parent to #faqAccordion -->
                            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree"
                                data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Yes. We work with global manufacturers to source or customise products to your
                                    specific requirements.
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Right Column (2 Questions) -->
                <div class="col-lg-6">
                    <div class="accordion custom-accordion">

                        <!-- Q4 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingFour">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                    Where do you deliver?
                                </button>
                            </h2>
                            <!-- Changed parent to #faqAccordion -->
                            <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour"
                                data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    We deliver nationwide across Australia and internationally through trusted logistics
                                    partners.
                                </div>
                            </div>
                        </div>

                        <!-- Q5 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingFive">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                    Why choose Axium?
                                </button>
                            </h2>
                            <!-- Changed parent to #faqAccordion -->
                            <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive"
                                data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    We combine global expertise with local understanding, offering reliable, compliant,
                                    and cost-effective healthcare solutions.
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection

@section('scripts')
@endsection