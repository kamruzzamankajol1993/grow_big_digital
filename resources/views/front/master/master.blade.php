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


    <link rel="stylesheet" href="{{ asset('/') }}public/front/css/style.css?v=1.0.1">
    
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


</script>
    

@yield('scripts')
</body>
</html>

