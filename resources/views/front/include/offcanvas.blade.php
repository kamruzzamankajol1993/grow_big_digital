 <div class="offcanvas offcanvas-start" tabindex="-1" id="sidebarMenu">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title text-white">{{ $siteConfig->site_name }}</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body d-flex flex-column">
            <ul class="navbar-nav mb-auto">
                <li class="nav-item"><a class="nav-link" href="#about">About Us</a></li>
                <li class="nav-item"><a class="nav-link" href="#services">Services</a></li>
                <li class="nav-item"><a class="nav-link" href="#portfolio">Portfolio</a></li>
                <li class="nav-item"><a class="nav-link" href="#team">Team</a></li>
                <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
            </ul>
            
            <div class="d-grid gap-2 pt-4 border-top">
                <a class="btn btn-outline-light rounded-pill" data-bs-toggle="modal" data-bs-target="#quickAuditModal">{{ $siteConfig->quick_button_text ?? 'Free Quick Audit' }}</a>
                <a class="btn btn-primary rounded-pill" target="_blank" href="{{ $siteConfig->book_appointment_link ?? '#appointment' }}">{{ $siteConfig->book_appointment_button_text ?? 'Book Appointment' }}</a>
            </div>

            @php
        // প্ল্যাটফর্মের নাম অনুযায়ী আইকন ম্যাপ
        $iconMap = [
            'Facebook'  => 'bi-facebook',
            'Instagram' => 'bi-instagram',
            'Twitter'   => 'bi-twitter-x',
            'YouTube'   => 'bi-youtube',
            'LinkedIn'  => 'bi-linkedin',
            'Pinterest' => 'bi-pinterest'
        ];
    @endphp

            <div class="sidebar-socials pt-4 mt-3">
                @forelse($socialLinks as $social)
        @php 
            // ডেটাবেজের নামের সাথে মিললে সেই আইকন দেখাবে, না মিললে ডিফল্ট গ্লোব আইকন
            $iconClass = $iconMap[$social->title] ?? 'bi-globe'; 
        @endphp
        
        <a href="{{ $social->link }}" target="_blank" class="text-white me-3 text-decoration-none" title="{{ $social->title }}">
            <i class="bi {{ $iconClass }}"></i>
        </a>
    @empty
        <span class="text-white-50 small">No links available</span>
    @endforelse
            </div>
        </div>
    </div>