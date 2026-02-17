<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title') | {{ $siteConfig->site_name ?? 'Admin Portal' }}</title>
    <meta name="description" content="{{ $siteConfig->meta_description ?? '' }}">
    <meta name="keywords" content="{{ $siteConfig->meta_keywords ?? '' }}">
    <meta name="author" content="{{ $siteConfig->site_name ?? 'Admin' }}">

    <meta property="og:type" content="website">
    <meta property="og:title" content="@yield('title') | {{ $siteConfig->site_name }}">
    <meta property="og:description" content="{{ $siteConfig->meta_description ?? '' }}">
    <meta property="og:image" content="{{ asset($siteConfig->og_image ?? $siteConfig->logo) }}">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title') | {{ $siteConfig->site_name }}">
    <meta name="twitter:description" content="{{ $siteConfig->meta_description ?? '' }}">
    <meta name="twitter:image" content="{{ asset($siteConfig->og_image ?? $siteConfig->logo) }}">

    <link rel="icon" type="image/x-icon" href="{{ asset($siteConfig->icon) }}">


    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

     <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css"> <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css"> 


    <link rel="stylesheet" href="{{ asset('/') }}public/front/css/style.css">
    
    <style>
        /* ১. টগল বাটন পুরোপুরি সাদা */
        .navbar-toggler {
            border: 2px solid #ffffff !important;
            padding: 5px 8px;
        }
        .navbar-toggler-icon {
            filter: invert(1) brightness(200%) !important;
        }

        /* ২. হেডার বাটনগুলোর হাইট ও উইডথ একদম সমান করা */
        .header-btn {
            height: 46px !important;
            min-width: 170px !important;
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
            font-weight: 700 !important;
            font-size: 14px !important;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }

        /* ৩. নেভিগেশন লিংক বোল্ড এবং হোভার ইফেক্ট (বড় হওয়া) */
        .navbar-nav .nav-link {
            font-weight: 700 !important;
            color: black;
            transition: transform 0.3s ease, color 0.3s ease !important;
            display: inline-block;
        }
        .navbar-nav .nav-link:hover {
            transform: scale(1.15);
            color: var(--accent-color) !important;
        }

        /* ৪. অফক্যানভাস স্টাইল ও কালার */
        .offcanvas {
            background-color: #080e32 !important; /* আপনার থিম কালার */
            color: white;
            border-right: 1px solid var(--accent-color);
        }
        .offcanvas-header { border-bottom: 1px solid rgba(255,255,255,0.1); }
        
        /* অফক্যানভাসে বাটন গুলোর মার্জিন */
        .offcanvas .btn {
            padding: 12px;
            font-weight: 600;
        }
    </style>

     @yield('css')

     @if($siteConfig->google_analytics_code)
        {!! $siteConfig->google_analytics_code !!}
    @endif
</head>
<body>
<canvas id="particleCanvas"></canvas>

<!-- Navbar -->
    
@include('front.include.header')
    <!-- end of navbar -->

    <!-- Offcanvas Sidebar -->

   @include('front.include.offcanvas')

    <!-- end of offcanvas -->


    @yield('body')


<!-- footer -->
    
    @include('front.include.footer')

    <!-- end of footer -->

<!-- Floating Quick Audit Button -->

@include('front.include.quick')




<a href="https://wa.me/{{ $siteConfig->whatsapp_number }}" class="whatsapp-float" target="_blank">
    <i class="bi bi-whatsapp"></i>
</a>
<div id="progress-wrap">
    <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
        <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98"/>
    </svg>
    <i class="bi bi-arrow-up"></i>
</div>
  

<!-- JavaScript Libraries -->

 <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script> <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    $('#quickAuditForm').on('submit', function(e) {
        e.preventDefault();
        
        let formData = $(this).serialize();
        let btn = $('#submitBtn');
        
        btn.prop('disabled', true).html('Sending...');

        $.ajax({
            url: "{{ route('quickAudit.store') }}",
            type: "POST",
            data: formData,
            success: function(response) {
                btn.prop('disabled', false).html('SEND REQUEST');
                
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                        confirmButtonColor: '#00a651'
                    });
                    $('#quickAuditForm')[0].reset();
                    $('#quickAuditModal').modal('hide'); // মডাল বন্ধ হবে
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        text: 'Please check your inputs.'
                    });
                }
            },
            error: function() {
                btn.prop('disabled', false).html('SEND REQUEST');
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong. Please try again.'
                });
            }
        });
    });
});
</script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script> <script>
        $(document).ready(function() {
            // CSRF Token Setup for Ajax
            $.ajaxSetup({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
            });

            // Initialize Modern Datepicker (Flatpickr)
            $(".datepicker").flatpickr({
                dateFormat: "Y-m-d",
                allowInput: true
            });

            // Initialize Select2
            $('.select2').select2({ width: '100%' });

            // Initialize Summernote
            $('.summernote').summernote({
                height: 250,
                placeholder: 'Start typing here...',
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link', 'picture']],
                    ['view', ['fullscreen', 'codeview']]
                ]
            });

            // Global SweetAlert for Success/Error
            @if(session('success'))
                Swal.fire({ icon: 'success', title: 'Success!', text: "{{ session('success') }}", timer: 3000 });
            @endif
            @if(session('error'))
                Swal.fire({ icon: 'error', title: 'Oops...', text: "{{ session('error') }}" });
            @endif
        });
    </script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<script>

   document.addEventListener('DOMContentLoaded', function() {
    const swiperOptions = {
        loop: true,
        effect: "flip",
        grabCursor: true,
        speed: 1500,
        flipEffect: { slideShadows: true, limitRotation: true },
        autoplay: { delay: 5000, disableOnInteraction: false }
    };

    new Swiper(".heroPortraitSlider", swiperOptions);
    new Swiper(".heroLandscapeSlider", { ...swiperOptions, autoplay: { delay: 6500 } });
});
    /**
     * 1. HERO SLIDER INITIALIZATION
     * Uses the Flip effect with controlled speed for a cinematic feel.
     */
    var swiper = new Swiper(".myHeroSlider", {
        loop: true,
        effect: "flip",
        grabCursor: true,
        speed: 1500, 
        flipEffect: {
            slideShadows: true,
            limitRotation: true,
        },
        autoplay: { 
            delay: 5000, 
            disableOnInteraction: false 
        },
        pagination: { 
            el: ".swiper-pagination", 
            clickable: true 
        },
    });

    /**
     * 2. UNIFIED SCROLL REVEAL LOGIC
     * Fixes "Uncaught TypeError" by checking if elements exist before processing.
     */
    function revealAll() {
        // Targets all animation classes used in the project
        const elements = document.querySelectorAll('.reveal-left, .reveal-right, .reveal-fade, .reveal-up, .reveal-on-scroll');
        
        elements.forEach(el => {
            let windowHeight = window.innerHeight;
            let elementTop = el.getBoundingClientRect().top;
            let elementVisible = 100;

            if (elementTop < windowHeight - elementVisible) {
                el.classList.add('reveal-visible');
                
                // Backup logic for the old reveal-on-scroll class
                if (el.classList.contains('reveal-on-scroll')) {
                    el.style.opacity = "1";
                    el.style.transform = "translateY(0)";
                }
            }
        });
    }

    /**
     * 3. PORTFOLIO FILTERING LOGIC
     * Smoothly handles category transitions.
     */
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const activeBtn = document.querySelector('.filter-btn.active');
            if (activeBtn) activeBtn.classList.remove('active');
            
            this.classList.add('active');
            const filterValue = this.getAttribute('data-filter');
            const items = document.querySelectorAll('.portfolio-item');

            items.forEach(item => {
                item.style.opacity = '0';
                item.style.transform = 'scale(0.9)';
                
                setTimeout(() => {
                    if (filterValue === 'all' || item.classList.contains(filterValue)) {
                        item.style.display = 'block';
                        setTimeout(() => {
                            item.style.opacity = '1';
                            item.style.transform = 'scale(1)';
                        }, 50);
                    } else {
                        item.style.display = 'none';
                    }
                }, 300);
            });
        });
    });

    /**
     * 4. REVIEW SLIDER (TESTIMONIALS)
     * Auto-sliding with responsive breakpoints.
     */
    var reviewSwiper = new Swiper(".reviewSlider", {
        slidesPerView: 1,
        spaceBetween: 30,
        loop: true,
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
        speed: 1000,
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        breakpoints: {
            768: { slidesPerView: 2 },
            1024: { slidesPerView: 3 }
        }
    });

    /**
     * 5. BACK TO TOP PROGRESS RING
     * Calculates scroll percentage and updates SVG path.
     */
    document.addEventListener('DOMContentLoaded', function() {
        var progressPath = document.querySelector('#progress-wrap path');
        
        if (progressPath) {
            var pathLength = progressPath.getTotalLength();
            progressPath.style.transition = progressPath.style.WebkitTransition = 'none';
            progressPath.style.strokeDasharray = pathLength + ' ' + pathLength;
            progressPath.style.strokeDashoffset = pathLength;
            progressPath.getBoundingClientRect();
            progressPath.style.transition = progressPath.style.WebkitTransition = 'stroke-dashoffset 10ms linear';
            
            var updateProgress = function () {
                var scroll = window.scrollY;
                var height = document.documentElement.scrollHeight - window.innerHeight;
                var progress = pathLength - (scroll * pathLength / height);
                progressPath.style.strokeDashoffset = progress;

                // Show/Hide button logic
                if (scroll > 150) {
                    document.querySelector('#progress-wrap').classList.add('active-progress');
                } else {
                    document.querySelector('#progress-wrap').classList.remove('active-progress');
                }
            };
            
            updateProgress();
            window.addEventListener('scroll', updateProgress);
            
            document.querySelector('#progress-wrap').addEventListener('click', function(e) {
                e.preventDefault();
                window.scrollTo({top: 0, behavior: 'smooth'});
            });
        }
    });

    /**
     * 6. OFFCANVAS & GLOBAL LISTENERS
     */
    // Initial call for animations on page load
    window.addEventListener('load', revealAll);
    window.addEventListener('scroll', revealAll);

    // Close mobile menu on link click
    document.querySelectorAll('.offcanvas-body .nav-link').forEach(link => {
        link.addEventListener('click', () => {
            const sidebar = document.getElementById('sidebarMenu');
            const bsOffcanvas = bootstrap.Offcanvas.getInstance(sidebar);
            if(bsOffcanvas) bsOffcanvas.hide();
        });
    });
    const canvas = document.getElementById('particleCanvas');
const ctx = canvas.getContext('2d');

canvas.width = window.innerWidth;
canvas.height = window.innerHeight;

let particlesArray;

// লোগোর মূল কালার কোডসমূহ
const colors = ['#00a651', '#080e32', '#00a651']; 

class Particle {
    constructor(x, y, directionX, directionY, size, color) {
        this.x = x;
        this.y = y;
        this.directionX = directionX;
        this.directionY = directionY;
        this.size = size;
        this.color = color;
    }
    
    draw() {
        ctx.beginPath();
        ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2, false);
        ctx.fillStyle = this.color;
        ctx.fill();
    }

    update() {
        // স্ক্রিনের সীমানা চেক করা
        if (this.x > canvas.width || this.x < 0) {
            this.directionX = -this.directionX;
        }
        if (this.y > canvas.height || this.y < 0) {
            this.directionY = -this.directionY;
        }

        this.x += this.directionX;
        this.y += this.directionY;
        this.draw();
    }
}

// কানেক্টিং লাইন বা জাল (Mesh) তৈরির ফাংশন
function connect() {
    let opacityValue = 1;
    for (let a = 0; a < particlesArray.length; a++) {
        for (let b = a; b < particlesArray.length; b++) {
            // দুটি পার্টিকেলের মধ্যে দূরত্ব হিসাব করা
            let distance = ((particlesArray[a].x - particlesArray[b].x) * (particlesArray[a].x - particlesArray[b].x))
                + ((particlesArray[a].y - particlesArray[b].y) * (particlesArray[a].y - particlesArray[b].y));
            
            // যদি দূরত্ব একটি নির্দিষ্ট সীমার মধ্যে থাকে, তবে লাইন আঁকা হবে
            if (distance < (canvas.width / 7) * (canvas.height / 7)) {
                opacityValue = 1 - (distance / 20000);
                ctx.strokeStyle = `rgba(0, 166, 81, ${opacityValue})`; // লোগোর সবুজ রঙে লাইন
                ctx.lineWidth = 1;
                ctx.beginPath();
                ctx.moveTo(particlesArray[a].x, particlesArray[a].y);
                ctx.lineTo(particlesArray[b].x, particlesArray[b].y);
                ctx.stroke();
            }
        }
    }
}

function init() {
    particlesArray = [];
    // স্ক্রিন সাইজ অনুযায়ী পার্টিকেল সংখ্যা নির্ধারণ
    let numberOfParticles = (canvas.height * canvas.width) / 15000; 
    for (let i = 0; i < numberOfParticles; i++) {
        let size = (Math.random() * 3) + 1;
        let x = (Math.random() * ((innerWidth - size * 2) - (size * 2)) + size * 2);
        let y = (Math.random() * ((innerHeight - size * 2) - (size * 2)) + size * 2);
        let directionX = (Math.random() * 1) - 0.5;
        let directionY = (Math.random() * 1) - 0.5;
        let color = colors[Math.floor(Math.random() * colors.length)];

        particlesArray.push(new Particle(x, y, directionX, directionY, size, color));
    }
}

function animate() {
    requestAnimationFrame(animate);
    ctx.clearRect(0, 0, innerWidth, innerHeight);

    for (let i = 0; i < particlesArray.length; i++) {
        particlesArray[i].update();
    }
    connect(); // লাইনগুলো ড্র করা হচ্ছে
}

window.addEventListener('resize', function() {
    canvas.width = innerWidth;
    canvas.height = innerHeight;
    init();
});

init();
animate();

/**
 * ১. পোর্টফোলিও ডাটাবেজ
 * আপনার স্ক্রিনশটের লেআউট অনুযায়ী টাইটেল, ডেসক্রিপশন এবং ট্যাগ সেট করা হয়েছে।
 */
const workData = {
    'cinematic': [
        {type: 'video', id: 'ScMzIvxBSi4', title: 'Cinematic Brand Documentary', subtitle: 'Advanced color grading and rhythmic sound design.', tag: 'VIDEO PRODUCTION'},
        {type: 'video', id: 'ScMzIvxBSi4', title: 'Luxury Real Estate', subtitle: 'Modern transition and smooth drone movements.', tag: 'POST PRODUCTION'},
        {type: 'video', id: 'ScMzIvxBSi4', title: 'Fashion Showroom', subtitle: 'Fast-paced editing for premium brands.', tag: 'VIDEO EDITING'},
        {type: 'video', id: 'ScMzIvxBSi4', title: 'Music Video Edit', subtitle: 'Visual effects and beat-synced visuals.', tag: 'VFX WORK'}
    ],
    'reels': [
        {type: 'video', id: 'dQw4w9WgXcQ', title: 'Viral Shorts Pack', subtitle: 'High-retention editing for TikTok.', tag: 'SOCIAL CONTENT'},
        {type: 'video', id: 'dQw4w9WgXcQ', title: 'Product Showcase', subtitle: 'Dynamic transitions for e-commerce.', tag: 'REELS/SHORTS'},
        {type: 'video', id: 'dQw4w9WgXcQ', title: 'Podcast Highlights', subtitle: 'Multi-cam setup with captions.', tag: 'PODCAST'},
        {type: 'video', id: 'dQw4w9WgXcQ', title: 'Fitness Reel', subtitle: 'Beat-synced motivational content.', tag: 'REELS'}
    ],
    'vfx': [
        {type: 'video', id: 'ScMzIvxBSi4', title: 'CGI Integration', subtitle: 'Seamlessly blending 3D with reality.', tag: 'VFX'},
        {type: 'image', url: 'https://images.unsplash.com/photo-1550745165-9bc0b252726f', title: 'Color Workflow', subtitle: 'Davinci Resolve pipeline.', tag: 'GRADING'},
        {type: 'video', id: 'dQw4w9WgXcQ', title: 'Motion Graphics', subtitle: 'Explaining complex ideas simply.', tag: 'ANIMATION'},
        {type: 'image', url: 'https://images.unsplash.com/photo-1550745165-9bc0b252726f', title: 'VFX Masterclass', subtitle: 'Advanced compositing techniques.', tag: 'VFX'}
    ],
    'meta-ads': [
        {type: 'video', id: 'ScMzIvxBSi4', title: 'Scale-Up Campaign', subtitle: 'Generating $1M+ in revenue via Meta Ads.', tag: 'MARKETING'},
        {type: 'image', url: 'https://images.unsplash.com/photo-1460925895917-afdab827c52f', title: 'Ad Strategy', subtitle: 'Targeting high-intent audiences.', tag: 'ADVERTISING'},
        {type: 'image', url: 'https://images.unsplash.com/photo-1460925895917-afdab827c52f', title: 'ROI Analysis', subtitle: 'Tracking every penny spent.', tag: 'DATA'},
        {type: 'image', url: 'https://images.unsplash.com/photo-1460925895917-afdab827c52f', title: 'Visual Creatives', subtitle: 'Stopping the scroll with design.', tag: 'DESIGN'}
    ],
    'seo': [
        {type: 'image', url: 'https://images.unsplash.com/photo-1557838923-2985c318be48', title: 'Global SEO Strategy', subtitle: 'Ranking on the first page globally.', tag: 'SEO'},
        {type: 'image', url: 'https://images.unsplash.com/photo-1557838923-2985c318be48', title: 'Link Building', subtitle: 'Building high-authority backlink profiles.', tag: 'GROWTH'},
        {type: 'image', url: 'https://images.unsplash.com/photo-1557838923-2985c318be48', title: 'Keyword Research', subtitle: 'Finding low-competition goldmines.', tag: 'RESEARCH'},
        {type: 'image', url: 'https://images.unsplash.com/photo-1557838923-2985c318be48', title: 'Technical Audit', subtitle: 'Optimizing site speed and structure.', tag: 'SEO'}
    ],
    'branding': [
        {type: 'image', url: 'https://images.unsplash.com/photo-1557426272-fc759fbb7a8d', title: 'Brand Identity', subtitle: 'Crafting unique stories for startups.', tag: 'BRANDING'},
        {type: 'image', url: 'https://images.unsplash.com/photo-1557426272-fc759fbb7a8d', title: 'Visual Roadmap', subtitle: 'Designing a 12-month visual strategy.', tag: 'STRATEGY'},
        {type: 'image', url: 'https://images.unsplash.com/photo-1557426272-fc759fbb7a8d', title: 'Authority Building', subtitle: 'Positioning you as a market leader.', tag: 'AUTHORITY'},
        {type: 'image', url: 'https://images.unsplash.com/photo-1557426272-fc759fbb7a8d', title: 'Viral Planning', subtitle: 'Scripts designed to go viral.', tag: 'VIRAL'}
    ]
};

/**
 * ২. মেইন কন্টেন্ট লোড ফাংশন
 * এটি আপনার ভিডিও এবং ইমেজগুলোকে স্ক্রিনশটের ওভারলে ডিজাইনে সাজাবে।
 */
function loadWorks(category, btnElement) {
    const display = document.getElementById('portfolio-display');
    const items = workData[category] || workData['cinematic'];
    
    display.style.opacity = '0';
    
    setTimeout(() => {
        display.innerHTML = '';
        items.forEach((item, index) => {
            let colSize = '';
            
            // লজিক: প্রথম সারিতে (index 0,1) ৮ এবং ৪। দ্বিতীয় সারিতে (index 2,3) ৪ এবং ৮।
            if (index < 2) {
                // ১ম রো
                colSize = (index % 2 === 0) ? 'col-lg-8' : 'col-lg-4';
            } else {
                // ২য় রো (বা তার বেশি হলে এই প্যাটার্ন রিপিট হবে)
                colSize = (index % 2 === 0) ? 'col-lg-4' : 'col-lg-8';
            }
            
            display.innerHTML += `
                <div class="${colSize} col-md-12">
                    <div class="portfolio-box reveal-visible">
                        <div class="portfolio-media">
                            ${item.type === 'video' 
                                ? `<iframe src="https://www.youtube.com/embed/${item.id}?rel=0&modestbranding=1" frameborder="0" allowfullscreen></iframe>
                                   <div class="play-btn-circle"><i class="bi bi-play-fill"></i></div>` 
                                : `<img src="${item.url}" alt="${item.title}">`
                            }
                            <div class="portfolio-overlay">
                                <div class="overlay-info">
                                    <span class="cat-tag">${item.tag}</span>
                                    <h4 class="work-title">${item.title}</h4>
                                    <p class="work-desc">${item.desc}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>`;
        });
        display.style.opacity = '1';
    }, 200);

    if(btnElement) {
        const parent = btnElement.closest('.sub-filters');
        parent.querySelectorAll('.sub-btn').forEach(s => s.classList.remove('active'));
        btnElement.classList.add('active');
    }
}

/**
 * ৩. গ্লোবাল ইভেন্ট লিসেনার
 */
document.addEventListener('DOMContentLoaded', () => {
    // পেজ লোড হওয়া মাত্র প্রথম সাব-সার্ভিসটি দেখাবে
    const defaultSubBtn = document.querySelector('#sub-video .sub-btn');
    if (defaultSubBtn) {
        loadWorks('cinematic', defaultSubBtn);
    }
});

// মেইন ক্যাটাগরি সুইচ লজিক
document.querySelectorAll('.main-filters .filter-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        // মেইন বাটন হাইলাইট
        document.querySelectorAll('.main-filters .filter-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        
        const main = this.getAttribute('data-main');
        
        // সব সাব-ফিল্টার লুকানো
        document.querySelectorAll('.sub-filters').forEach(sf => {
            sf.classList.add('d-none');
            sf.classList.remove('active-flex');
        });

        // টার্গেট সাব-ফিল্টার এবং প্রথম কাজ লোড করা
        const activeSubContainer = document.getElementById('sub-' + main);
        if(activeSubContainer) {
            activeSubContainer.classList.remove('d-none');
            activeSubContainer.classList.add('active-flex');
            
            // অটোমেটিক প্রথম সাব-ক্যাটাগরি সিলেক্ট করা
            const firstSubBtn = activeSubContainer.querySelector('.sub-btn');
            if(firstSubBtn) firstSubBtn.click();
        }
    });
});
</script>
    

@yield('scripts')
</body>
</html>

