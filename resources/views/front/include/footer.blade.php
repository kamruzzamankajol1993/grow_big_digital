
    <footer class="footer-advanced pt-5 pb-3">
    <div class="container">
        <div class="row g-4 mb-5">
           <div class="col-lg-5 col-md-6 reveal-up">
    <a class="navbar-brand mb-4 d-block" href="#">
        <img src="{{ asset($siteConfig->logo) }}" alt="GrowBig Digital" style="height: 60px; filter: brightness(0) invert(1);">
    </a>
    <p class="footer-text opacity-75">
       {{ $siteConfig->footer_short_description ?? 'Default short description text...' }}
    </p>

    @php
        $iconMap = [
            'Facebook'  => 'bi-facebook',
            'Twitter'   => 'bi-twitter-x',
            'LinkedIn'  => 'bi-linkedin',
            'Instagram' => 'bi-instagram',
            'YouTube'   => 'bi-youtube',
            'Pinterest' => 'bi-pinterest'
        ];
    @endphp
                <div class="footer-socials mt-4">
                   @foreach($socialLinks as $social)
        @php 
            // ম্যাপে না থাকলে ডিফল্ট লিংক আইকন দেখাবে
            $iconClass = $iconMap[$social->title] ?? 'bi-link-45deg'; 
        @endphp
        <a href="{{ $social->link }}" target="_blank" title="{{ $social->title }}">
            <i class="bi {{ $iconClass }}"></i>
        </a>
    @endforeach
                </div>
            </div>

            <div class="col-lg-3 col-md-6 ps-lg-5 reveal-up" style="transition-delay: 0.1s;">
                <h5 class="fw-bold mb-4">Quick Links</h5>
                <ul class="list-unstyled footer-links">
                    <li><a href="#about">About Us</a></li>
                    <li><a href="#portfolio">Our Work</a></li>
                    <li><a href="#reviews">Testimonials</a></li>
                    <li><a href="#contact">Contact</a></li>
                </ul>
            </div>

            <div class="col-lg-4 col-md-6 reveal-up" style="transition-delay: 0.2s;">
                <h5 class="fw-bold mb-4">Expertise</h5>
                <ul class="list-unstyled footer-links">
                    @foreach($parentServices as $pService)
                    <li><a href="#services">{{ $pService->name }}</a></li>
                    @endforeach
                </ul>
            </div>
        </div>

       <hr class="footer-hr">

<div class="row align-items-center mt-4 pb-5"> <div class="col-md-6 text-center text-md-start">
        <p class="mb-0 small opacity-75 text-white">&copy; {{ date('Y') }} {{ $siteConfig->site_name }}. All Rights Reserved.</p>
    </div>
    
    <div class="col-md-6 text-center text-md-end mt-3 mt-md-0">
        <ul class="list-inline mb-0 footer-bottom-links">
            <li class="list-inline-item me-3">
                <a href="#" class="small text-white opacity-75 text-decoration-none">Privacy Policy</a>
            </li>
            <li class="list-inline-item">
                <a href="#" class="small text-white opacity-75 text-decoration-none">Terms of Service</a>
            </li>
        </ul>
    </div>
</div>
    </div>
</footer>

