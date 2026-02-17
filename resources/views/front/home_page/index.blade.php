@extends('front.master.master')

@section('title')
{{ $siteConfig->site_name ?? 'Grow Big Digital' }}
@endsection

@section('css')
<style>
    /* আইকন ইমেজের জন্য স্টাইল ও অ্যানিমেশন */
    .service-icon-img {
        width: 60px;
        height: 60px;
        object-fit: contain;
        position: relative;
        z-index: 2;
        transition: transform 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    /* হোভার করলে আইকনটি লাফাবে বা বড় হবে */
    .service-card:hover .service-icon-img {
        transform: scale(1.2) rotate(5deg);
    }

    /* আইকনের পেছনের পালস ইফেক্ট ঠিক করা */
    .service-icon-wrapper {
        position: relative;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 80px;
        height: 80px;
        background: #fff;
        border-radius: 50%;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        margin: 0 auto;
    }

    .icon-pulse {
        position: absolute;
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background: var(--bs-primary);
        opacity: 0.1;
        z-index: 1;
        animation: pulse-animation 2s infinite;
    }

    @keyframes pulse-animation {
        0% { transform: scale(1); opacity: 0.2; }
        100% { transform: scale(1.5); opacity: 0; }
    }

    .bg-soft-primary {
        background-color: rgba(var(--bs-primary-rgb), 0.1);
        color: var(--bs-primary);
        font-weight: 500;
        padding: 6px 12px;
        border-radius: 50px;
    }

    .service-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        justify-content: center;
    }
</style>
<style>
    /* গ্রিডের ভেতরে আইফ্রেমকে ফোর্সফুলি ফিট করা */
    .iframe-container-wrapper iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100% !important;
        height: 100% !important;
        object-fit: cover;
        border: none;
    }

    /* যদি ভিডিওটি খুব বেশি লম্বা হয়ে যায় তবে এটি একটি স্ট্যান্ডার্ড রেশিও মেইনটেইন করবে */
    .portfolio-media {
        aspect-ratio: 16 / 10; /* আপনি চাইলে ৪:৩ বা ১৬:৯ ও দিতে পারেন */
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #000;
    }

    /* আইফ্রেমের ভেতরে স্ক্রলবার বন্ধ রাখা */
    .iframe-container-wrapper {
        pointer-events: none; /* যাতে গ্রিডে ভিডিও প্লে না হয়ে সরাসরি পপআপ ওপেন হয় */
    }
    
    .play-btn-circle {
        pointer-events: auto; /* শুধু প্লে বাটনে ক্লিক কাজ করবে */
    }

    
</style>
<style>
    /* মেইন মেম্বার আইকন */
    .hero-icon-img {
        width: 30px;
        height: 30px;
        object-fit: contain;
    }

    /* সাইড মেম্বার ছোট আইকন */
    .hero-icon-img-sm {
        width: 18px;
        height: 18px;
        object-fit: contain;
    }

    /* নিচের স্ট্যাটাস পিল আইকন */
    .stat-icon-img {
        width: 24px;
        height: 24px;
        object-fit: contain;
        transition: transform 0.3s ease;
    }

    /* হোভার করলে আইকন একটু জুম হবে */
    .stat-pill:hover .stat-icon-img {
        transform: scale(1.2) rotate(5deg);
    }

    /* পিল এর আইকন বক্সের সাইজ অ্যাডজাস্ট */
    .stat-pill-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 40px;
    }
</style>
@endsection

@section('body')
 
  <section class="behance-hero-refined">
    <div class="container">
        <div class="hero-content-card shadow-lg reveal-fade">
            <div class="row align-items-center g-0">
                
                <div class="col-lg-6 p-4 p-md-5">
                    <div class="text-area">
                        <h1 class="hero-main-title mb-4">{!! $hero->main_title !!}</h1>
                        <p class="hero-sub-para mb-5">{!! $hero->subtitle ?? 'Comprehensive Online Reputation Management for Leaders & Brands' !!}</p>
                        
                        <div class="d-flex gap-3 align-items-center btn-flex-mobile">
                            <a href="#appointment" class="btn btn-brand-solid">{{ $hero->button_name_one ?? 'SCHEDULE CONSULTATION' }}</a>
                            <a href="#portfolio" class="btn btn-brand-outline">
                                <i class="bi bi-play-circle-fill me-2"></i> {{ $hero->button_name_two ?? 'LEARN MORE' }}
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 p-4 p-md-5">
    <div class="behance-grid-layout">
        <div class="member-card-wrapper main-expert border-accent shadow-deep">
            <div class="google-review-badge">
                {{-- আইকন এখন ইমেজ হিসেবে --}}
                <img src="{{ asset('public/'.$hero->member_one_icon) }}" alt="icon" class="hero-icon-img">
            </div>
            <img src="{{ asset('public/'.$hero->member_one_image) }}" alt="{{ $hero->member_one_name }}" class="member-img">
            <div class="member-details-box">
                <h5 class="fw-bold mb-0 text-uppercase">{{ $hero->member_one_name }}</h5>
                <p class="small text-muted mb-0">— {{ $hero->member_one_designation }}</p>
                <div class="accent-bar"></div>
            </div>
        </div>

        <div class="side-member-stack">
            <div class="member-card-wrapper side-expert border-accent shadow-deep mb-4">
                <div class="google-review-badge-sm">
                    <img src="{{ asset('public/'.$hero->member_two_icon) }}" alt="icon" class="hero-icon-img-sm">
                </div>
                <img src="{{ asset('public/'.$hero->member_two_image) }}" alt="{{ $hero->member_two_name }}" class="member-img">
                <div class="member-details-box-sm">
                    <h6 class="fw-bold mb-0 text-uppercase">{{ $hero->member_two_name }}</h6>
                    <p class="x-small text-muted mb-0">{{ $hero->member_two_designation }}</p>
                    <div class="accent-bar-sm"></div>
                </div>
            </div>

            <div class="member-card-wrapper side-expert border-accent shadow-deep">
                <div class="google-review-badge-sm">
                    <img src="{{ asset('public/'.$hero->member_three_icon) }}" alt="icon" class="hero-icon-img-sm">
                </div>
                <img src="{{ asset('public/'.$hero->member_three_image) }}" alt="{{ $hero->member_three_name }}" class="member-img">
                <div class="member-details-box-sm">
                    <h6 class="fw-bold mb-0 text-uppercase">{{ $hero->member_three_name }}</h6>
                    <p class="x-small text-muted mb-0">{{ $hero->member_three_designation }}</p>
                    <div class="accent-bar-sm"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="hero-floating-stats">
    <div class="stat-pill shadow-defined">
        <div class="stat-pill-icon">
            <img src="{{ asset('public/'.$hero->success_icon) }}" alt="Success" class="stat-icon-img">
        </div>
        <div class="stat-pill-info">
            <h4 class="mb-0">{{ $hero->success_count }}</h4>
            <span>{{ $hero->success_text }}</span>
        </div>
    </div>
    
    <div class="stat-pill shadow-defined">
        <div class="stat-pill-icon">
            <img src="{{ asset('public/'.$hero->client_icon) }}" alt="Client" class="stat-icon-img">
        </div>
        <div class="stat-pill-info">
            <h4 class="mb-0">{{ $hero->client_count }}</h4>
            <span>{{ $hero->client_text }}</span>
        </div>
    </div>

    <div class="stat-pill shadow-defined">
        <div class="stat-pill-icon">
            <img src="{{ asset('public/'.$hero->positive_icon) }}" alt="Positive" class="stat-icon-img">
        </div>
        <div class="stat-pill-info">
            <h4 class="mb-0">{{ $hero->positive_count }}</h4>
            <span>{{ $hero->positive_text }}</span>
        </div>
    </div>
</div>
            </div>

            
        </div>
    </div>
</section>

   

   <section id="about" class="section-padding overflow-hidden">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 position-relative reveal-left">
                <div class="about-img-wrapper">
                    <img src="{{ asset('public/'.$whoWeAre->image) }}" class="img-fluid rounded-4 shadow-lg main-about-img" alt="Professional Video Editing Agency">
                    
                    <div class="floating-badge d-none d-md-flex">
                        <div class="badge-icon"><i class="bi bi-play-fill"></i></div>
                        <div class="badge-text">
                            <span class="d-block fw-bold">{{ $whoWeAre->edit_count_text ?? '1k+ Videos' }}</span>
                            <small>Expertly Edited</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 mt-5 mt-lg-0 ps-lg-5 reveal-right">
                <h6 class="text-primary fw-bold text-uppercase ls-2">{{ $whoWeAre->title ?? 'Who We Are' }}</h6>
                <h2 class="fw-bold mb-4 display-5">{{ $whoWeAre->subtitle_one ?? 'Who We Are' }} {{ $whoWeAre->subtitle_two ?? 'Videos Edited' }} <span class="text-gradient">{{ $whoWeAre->subtitle_three ?? 'Videos Edited' }}</span></h2>
                <p class="text-muted mb-4 lead">
                   {{ $whoWeAre->short_description ?? 'We breathe life into your brand\'s narrative in the digital landscape.' }}
                </p>
                
                <ul class="list-unstyled custom-list">
                    @if($whoWeAre && $whoWeAre->listItems)
                        @foreach($whoWeAre->listItems as $item)
                            <li class="mb-3 d-flex align-items-start">
                                {{-- এখানে icon সরাসরি ক্লাস হিসেবে ডাটাবেস থেকে আসবে --}}
                                <div class="icon-box me-3"><i class="{{ $item->icon }}"></i></div>
                                <div>
                                    <h5 class="mb-1 fw-bold">{{ $item->title }}</h5>
                                    <p class="small text-muted mb-0">{{ $item->short_description }}</p>
                                </div>
                            </li>
                        @endforeach
                    @endif
                </ul>
                
                <a href="#portfolio" class="btn btn-primary rounded-pill px-4 py-2 mt-3">{{ $whoWeAre->button_name ?? 'Explore Our Craft' }}</a>
            </div>
        </div>
    </div>
</section>

 <section id="services" class="section-padding bg-light overflow-hidden">
    <div class="container text-center">
        <h6 class="text-primary fw-bold text-uppercase ls-2 reveal-fade">
            {{ $serviceHeader->title ?? 'What We Do' }}
        </h6>
        <h2 class="fw-bold mb-5 display-5 reveal-fade">
            {{ $serviceHeader->subtitle_one ?? 'Premium Solutions for' }} <span class="text-gradient">{{$serviceHeader->subtitle_two ?? '' }}</span>
        </h2>
        
        <div class="row g-4">
            {{-- AppServiceProvider থেকে $allServicesWithChildren ডাটা আসছে --}}
            @foreach($allServicesWithChildren as $index => $service)
                <div class="col-md-6 col-lg-4 reveal-up" style="transition-delay: {{ $index * 0.2 }}s;">
                    <div class="card service-card h-100 p-4 border-0">
                        <div class="service-icon-wrapper mb-4">
                            {{-- আইকন ইমেজ হিসেবে --}}
                            @if($service->icon)
                                <img src="{{ asset('public/'.$service->icon) }}" alt="{{ $service->name }}" class="service-icon-img">
                            @else
                                <i class="bi bi-gear-fill"></i>
                            @endif
                            <div class="icon-pulse"></div>
                        </div>
                        <h4 class="fw-bold mb-3">{{ $service->name }}</h4>
                        <p class="text-muted">{{ $service->short_description }}</p>
                        
                        {{-- চাইল্ড ক্যাটাগরি গুলো ট্যাগ হিসেবে --}}
                        <ul class="list-unstyled service-tags mt-3">
                            @foreach($service->children as $child)
                                <li><span class="badge bg-soft-primary">{{ $child->name }}</span></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
<section id="portfolio" class="section-padding overflow-hidden bg-white">
    <div class="container">
        <div class="row align-items-end mb-5">
            <div class="col-lg-5">
                <h6 class="text-primary fw-bold text-uppercase ls-2">{{ $portfolioHeader->title ?? 'OUR MASTERPIECES' }}</h6>
                <h2 class="fw-bold display-5">{{ $portfolioHeader->subtitle_one ?? 'Explore our latest creative works' }} <span class="text-gradient">{{ $portfolioHeader->subtitle_two ?? 'Crafting digital excellence one project at a time.' }}</span></h2>
            </div>
            <div class="col-lg-7 text-lg-end mt-4 mt-lg-0">
                <div class="portfolio-filters main-filters mb-3">
                    @foreach($allServicesWithChildren as $index => $pService)
                        <button class="filter-btn {{ $index == 0 ? 'active' : '' }}" 
                                data-main="{{ $pService->id }}"
                                onclick="loadWorks({{ $pService->id }}, this, true)">
                            {{ $pService->name }}
                        </button>
                    @endforeach
                </div>

                <div class="sub-filter-container">
                    @foreach($allServicesWithChildren as $index => $pService)
                        <div id="sub-{{ $pService->id }}" class="sub-filters {{ $index == 0 ? 'active-flex' : 'd-none' }}">
                            @foreach($pService->children as $sub)
                                <button class="sub-btn" onclick="loadWorks({{ $sub->id }}, this, false)">
                                    {{ $sub->name }}
                                </button>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="row g-4" id="portfolio-display">
            </div>
    </div>
</section>

   <section id="team" class="section-padding position-relative overflow-hidden bg-white">
    <div class="bg-watermark">CREATIVE</div>

    <div class="container position-relative">
        <div class="text-center mb-5 reveal-fade">
            <h6 class="text-primary fw-bold text-uppercase ls-2">
                {{ $teamHeader->title ?? 'The Talent' }}
            </h6>
            <h2 class="fw-bold display-5">
                {{ $teamHeader->subtitle_one ?? 'Our Creative' }} 
                <span class="text-gradient">{{ $teamHeader->subtitle_two ?? 'Minds' }}</span>
            </h2>
        </div>
        
        <div class="row g-5">
            @foreach($teamMembers as $key => $member)
            <div class="col-md-4 reveal-up" style="transition-delay: {{ $key * 0.2 }}s;">
                <div class="team-card-advanced">
                    <div class="team-image-container">
                        <img src="{{ asset('public/'.$member->image) }}" alt="{{ $member->name }}">
                        
                        <div class="team-social-sidebar">
                            @foreach($member->socialLinks as $social)
                                @php
                                    $icon = 'bi-link-45deg'; // Default icon
                                    $title = strtolower($social->title);
                                    if(str_contains($title, 'facebook')) $icon = 'bi-facebook';
                                    elseif(str_contains($title, 'twitter')) $icon = 'bi-twitter-x';
                                    elseif(str_contains($title, 'linkedin')) $icon = 'bi-linkedin';
                                    elseif(str_contains($title, 'instagram')) $icon = 'bi-instagram';
                                    elseif(str_contains($title, 'behance')) $icon = 'bi-behance';
                                    elseif(str_contains($title, 'youtube')) $icon = 'bi-play-circle-fill';
                                @endphp
                                <a href="{{ $social->link }}" target="_blank" title="{{ $social->title }}">
                                    <i class="bi {{ $icon }}"></i>
                                </a>
                            @endforeach
                        </div>
                    </div>
                    
                    <div class="team-info-glass">
                        <h4 class="fw-bold">{{ $member->name }}</h4>
                        <span class="designation">{{ $member->designation }}</span>
                        
                        <div class="skills-mini">
    @php
        $skills = is_array($member->skills) ? $member->skills : explode(',', $member->skills ?? '');
    @endphp

    @foreach($skills as $skill)
        @if(!empty(trim($skill)))
            <span>{{ trim($skill) }}</span>
        @endif
    @endforeach
</div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
<section id="reviews" class="section-padding bg-light position-relative overflow-hidden">
    <div class="bg-watermark">REVIEWS</div>

    <div class="container position-relative">
        <div class="text-center mb-5 reveal-fade">
            <h6 class="text-primary fw-bold text-uppercase ls-2">
                {{ $testimonialHeader->title ?? 'Testimonials' }}
            </h6>
            <h2 class="fw-bold display-5">
                {{ $testimonialHeader->subtitle_one ?? 'What Our' }} 
                <span class="text-gradient">{{ $testimonialHeader->subtitle_two ?? 'Clients Say' }}</span>
            </h2>
        </div>

        <div class="swiper reviewSlider pb-5">
            <div class="swiper-wrapper">
                @foreach($testimonials as $testimonial)
                <div class="swiper-slide">
                    <div class="review-card h-100 d-flex flex-column justify-content-between">
                        <div>
                            <div class="quote-icon"><i class="bi bi-quote"></i></div>
                            <p class="review-text">
                                "{{ $testimonial->short_description }}"
                            </p>
                        </div>
                        
                        <div class="mt-auto">
                            @if($testimonial->link)
                                <div class="mb-3">
                                    <a href="{{ $testimonial->link }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                        <i class="bi bi-box-arrow-up-right me-1"></i> Detail
                                    </a>
                                </div>
                            @endif

                            <div class="reviewer-info">
                                <img src="{{ $testimonial->image ? asset('public/'.$testimonial->image) : 'https://i.pravatar.cc/100' }}" 
                                     alt="{{ $testimonial->name }}">
                                <div>
                                    <h5 class="fw-bold mb-0">{{ $testimonial->name }}</h5>
                                    <small class="text-primary">{{ $testimonial->designation }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
</section>
    @php
    $contactHeader = \App\Models\ContactHeader::first();
@endphp

<section id="contact" class="section-padding position-relative overflow-hidden bg-white">
    <div class="contact-bg-circle"></div>

    <div class="container position-relative">
        <div class="row justify-content-between align-items-center">
            
            <div class="col-lg-5 reveal-left">
                <h6 class="text-primary fw-bold text-uppercase ls-2">{{ $contactHeader->title ?? 'Contact Us' }}</h6>
                <h2 class="fw-bold display-5 mb-4">{!! $contactHeader->subtitle_one ?? "Let's Build Something" !!} <span class='text-gradient'>{{ $contactHeader->subtitle_two ?? 'Epic' }}</span></h2>
                <p class="text-muted mb-5">{{ $contactHeader->short_description ?? 'Ready to scale your brand? Drop us a message.' }}</p>
                
                <div class="contact-info-list">
                    <div class="d-flex align-items-center mb-4 contact-item">
                        <div class="icon-box-sm me-3"><i class="bi bi-geo-alt-fill"></i></div>
                        <div>
                            <p class="mb-0 small text-muted text-uppercase fw-bold">Office</p>
                            <p class="mb-0 fw-bold">{{ $siteConfig->address ?? 'Dhaka, Bangladesh' }}</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-4 contact-item">
                        <div class="icon-box-sm me-3"><i class="bi bi-envelope-at-fill"></i></div>
                        <div>
                            <p class="mb-0 small text-muted text-uppercase fw-bold">Email Us</p>
                            <p class="mb-0 fw-bold">{{ $siteConfig->email ?? 'hello@growbigdigital.com' }}</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center contact-item">
                        <div class="icon-box-sm me-3"><i class="bi bi-whatsapp"></i></div>
                        <div>
                            <p class="mb-0 small text-muted text-uppercase fw-bold">Phone</p>
                            <p class="mb-0 fw-bold">{{ $siteConfig->phone ?? '+880 1XXX XXXXXX' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 mt-5 mt-lg-0 reveal-right">
                <form class="contact-form-advanced p-5" id="mainContactForm">
                    @csrf
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="input-group-advanced">
                                <input type="text" name="full_name" class="form-control" placeholder=" " required>
                                <label>Full Name</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group-advanced">
                                <input type="email" name="email" class="form-control" placeholder=" " required>
                                <label>Email Address</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="input-group-advanced">
                                <select name="interested_in" class="form-select" required>
                                    <option selected disabled value="">Interest In...</option>
                                    @foreach($allServicesWithChildren as $parentService)
                                        <optgroup label="{{ $parentService->name }}">
                                            @foreach($parentService->children as $child)
                                                <option value="{{ $child->name }}">{{ $child->name }}</option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="input-group-advanced">
                                <textarea name="description" class="form-control" rows="4" placeholder=" " required></textarea>
                                <label>Your Project Vision</label>
                            </div>
                        </div>
                        <div class="col-12 text-center">
                            <button type="submit" id="contactSubmitBtn" class="btn btn-primary btn-lg w-100 rounded-pill py-3 mt-2 shadow-glow">
                                {{ $contactHeader->button_name ?? 'Send Message' }} <i class="bi bi-send-fill ms-2"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<div class="modal fade" id="portfolioModal" tabindex="-1" aria-hidden="true" style="background: rgba(0,0,0,0.9);">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content bg-transparent border-0">
            <div class="modal-header border-0 p-0">
                <button type="button" class="btn-close btn-close-white ms-auto mb-2" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0 text-center">
                <div id="popupImageContainer" class="d-none">
                    <img src="" id="popupImage" class="img-fluid rounded shadow-lg" style="max-height: 85vh;">
                </div>
                <div id="popupVideoContainer" class="d-none">
                    <div class="ratio ratio-16x9 shadow-lg">
                        <div id="popupVideo"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('#mainContactForm').on('submit', function(e) {
        e.preventDefault();
        
        let formData = $(this).serialize();
        let btn = $('#contactSubmitBtn');
        
        btn.prop('disabled', true).html('Sending... <i class="bi bi-hourglass-split ms-2"></i>');

        $.ajax({
            url: "{{ route('contact.store') }}",
            type: "POST",
            data: formData,
            success: function(response) {
                btn.prop('disabled', false).html('Send Message <i class="bi bi-send-fill ms-2"></i>');
                
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                        confirmButtonColor: '#00a651',
                        timer: 3000
                    });
                    $('#mainContactForm')[0].reset();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        text: 'Please check your input fields.'
                    });
                }
            },
            error: function() {
                btn.prop('disabled', false).html('Send Message <i class="bi bi-send-fill ms-2"></i>');
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'An error occurred. Please try again later.'
                });
            }
        });
    });
});
</script>
<script>
/**
 * ১. লোড ওয়ার্কস ফাংশন
 * @param {number} id - সার্ভিস আইডি
 * @param {object} btn - যে বাটনে ক্লিক করা হয়েছে
 * @param {boolean} isParent - এটি কি মেইন ট্যাব নাকি সাব ট্যাব
 */
function loadWorks(id, btn, isParent = false) {
    // ১. একটিভ ক্লাস ম্যানেজমেন্ট
    if (isParent) {
        // মেইন ফিল্টার বাটনগুলো থেকে active ক্লাস রিমুভ করে ক্লিক করা বাটনে যোগ করা
        document.querySelectorAll('.main-filters .filter-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        // সব সাব-ফিল্টার কন্টেইনার লুকানো
        document.querySelectorAll('.sub-filters').forEach(sf => {
            sf.classList.add('d-none');
            sf.classList.remove('active-flex');
        });

        // ক্লিক করা প্যারেন্টের নিজস্ব সাব-ফিল্টার কন্টেইনার দেখানো
        const activeSubContainer = document.getElementById('sub-' + id);
        if (activeSubContainer) {
            activeSubContainer.classList.remove('d-none');
            activeSubContainer.classList.add('active-flex');
            
            // প্যারেন্টে ক্লিক করলে ঐ গ্রুপের সাব-বাটনগুলোর একটিভ ক্লাস রিসেট করা
            activeSubContainer.querySelectorAll('.sub-btn').forEach(sb => sb.classList.remove('active'));
        }
    } else {
        // চাইল্ড/সাব-বাটন একটিভ করা
        const container = btn.closest('.sub-filters');
        container.querySelectorAll('.sub-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
    }

    // ২. পোর্টফোলিও ডিসপ্লে এরিয়াতে লোডার দেখানো
    const displayArea = $('#portfolio-display');
    displayArea.html(`
        <div class="col-12 text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2 text-muted">Loading Masterpieces...</p>
        </div>
    `);

    // ৩. AJAX এর মাধ্যমে ডাটা নিয়ে আসা
    $.ajax({
        url: "{{ route('get.portfolio.by.service') }}", // নিশ্চিত করুন এই রাউটটি web.php তে আছে
        type: "GET",
        data: { 
            service_id: id,
            is_parent: isParent ? 1 : 0
        },
        success: function(response) {
            // ডাটা সফলভাবে আসলে অ্যানিমেশন সহ ডিসপ্লে করা
            displayArea.hide().html(response).fadeIn(500);
        },
        error: function() {
            displayArea.html('<div class="col-12 text-center text-danger py-5">Failed to load content. Please try again.</div>');
        }
    });
}

/**
 * ২. পেজ লোড হওয়ার সময় ডিফল্ট ডাটা লোড করা
 */
$(document).ready(function() {
    // পেজ লোড হলে প্রথম যে প্যারেন্ট ট্যাবটি active আছে, সেটির ডাটা অটোমেটিক লোড হবে
    const activeParent = $('.main-filters .filter-btn.active');
    if (activeParent.length) {
        const parentId = activeParent.data('main');
        loadWorks(parentId, activeParent[0], true);
    }
});

/**
 * ৩. ভিডিও বা ইমেজ পপআপ হ্যান্ডলার (আপনার ডিজাইন অনুযায়ী)
 */
function openPopup(type, source) {
    // বুটস্ট্র্যাপ মডাল এবং কন্টেইনারগুলো ধরা
    const modalElement = document.getElementById('portfolioModal');
    const modal = new bootstrap.Modal(modalElement);
    const imgContainer = document.getElementById('popupImageContainer');
    const videoContainer = document.getElementById('popupVideoContainer');
    const popupImg = document.getElementById('popupImage');
    const popupVid = document.getElementById('popupVideo');

    // আগের কন্টেন্টগুলো রিসেট করা
    imgContainer.classList.add('d-none');
    videoContainer.classList.add('d-none');
    popupVid.innerHTML = ''; 
    popupImg.src = '';

    if(type === 'video') {
        // ভিডিও পপআপ লজিক: সরাসরি ডাটাবেসের iframe স্ট্রিংটি বসিয়ে দেওয়া হচ্ছে
        videoContainer.classList.remove('d-none');
        popupVid.innerHTML = source; 
        
        // যদি iframe থাকে তবে তাতে width/height 100% সেট করা (ডিজাইন ঠিক রাখার জন্য)
        const iframe = popupVid.querySelector('iframe');
        if(iframe) {
            iframe.style.width = '100%';
            iframe.style.height = '100%';
        }
        
        console.log("Opening Video Iframe");
    } else {
        // ইমেজ পপআপ লজিক
        imgContainer.classList.remove('d-none');
        popupImg.src = source;
        console.log("Opening Image:", source);
    }

    // মডাল দেখানো
    modal.show();

    // মডাল বন্ধ হলে ভিডিও (iframe) রিমুভ করা যাতে শব্দ বন্ধ হয়ে যায়
    modalElement.addEventListener('hidden.bs.modal', function () {
        popupVid.innerHTML = '';
        popupImg.src = '';
    }, { once: true });
}
</script>
@endsection