<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <a class="navbar-brand" href="{{route('front.index')}}">
                @if($siteConfig && $siteConfig->logo)
                <img src="{{ asset($siteConfig->logo) }}" alt="{{ $siteConfig->site_name }}">
            @else
                <span class="fw-bold">{{ $siteConfig->site_name ?? 'GrowBig' }}</span>
            @endif
            </a>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="#services">Services</a></li>
                    <li class="nav-item"><a class="nav-link" href="#portfolio">Portfolio</a></li>
                    <li class="nav-item"><a class="nav-link" href="#team">Team</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
                    <!-- <li class="nav-item ms-lg-3">
                        <a class="btn btn-primary rounded-pill header-btn" data-bs-toggle="modal" data-bs-target="#auditModal">Free Quick Audit</a>
                    </li> -->
                    <li class="nav-item ms-lg-2">
                        <a class="btn btn-primary rounded-pill header-btn" target="_blank" href="{{ $siteConfig->book_appointment_link ?? '#appointment' }}">
                        {{ $siteConfig->book_appointment_button_text ?? 'Book Appointment' }}
                    </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>