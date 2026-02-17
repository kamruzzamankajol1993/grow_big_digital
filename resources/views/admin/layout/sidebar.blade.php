<aside class="sidebar" id="sidebar">
    <button class="btn-close-sidebar d-lg-none" id="sidebarClose"><i class="bi bi-x-lg"></i></button>
    
    <div class="sidebar-brand">
        <a href="{{ route('home') }}" class="text-decoration-none">
            @if(isset($siteConfig->logo) && $siteConfig->logo)
                <img src="{{ asset($siteConfig->logo) }}" alt="{{ $siteConfig->site_name }}" class="img-fluid" style="max-height: 50px;">
            @else
                <h4 class="text-white fw-bold mb-0">{{ $siteConfig->site_name ?? 'GEMINI ADMIN' }}</h4>
            @endif
        </a>
    </div>
    
    <div class="nav flex-column mt-3" id="sidebarAccordion">
        <a class="nav-link {{ Route::is('home') ? 'active' : '' }}" href="{{ route('home') }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
<div class="nav-item">
    <a class="nav-link {{ Request::is('admin/hero*') ? 'active' : '' }}" href="{{ route('hero.index') }}">
        <i class="bi bi-house-door"></i> <span>Hero Section</span>
    </a>
</div>
<div class="nav-item">
    <a class="nav-link {{ Request::is('admin/who-we-are*') ? 'active' : '' }}" 
       href="{{ route('whoweare.index') }}">
        <i class="bi bi-info-square"></i> <span>Who We Are</span>
    </a>
</div>

<div class="nav-item">
    <a class="nav-link {{ Request::is('admin/service*') ? '' : 'collapsed' }}" 
       data-bs-toggle="collapse" 
       href="#serviceSub"
       aria-expanded="{{ Request::is('admin/service*') ? 'true' : 'false' }}">
        <i class="bi bi-layers-half"></i> <span>Service Management</span>
        <i class="bi bi-chevron-down ms-auto small"></i>
    </a>
    <div class="collapse {{ Request::is('admin/service*') ? 'show' : '' }}" 
         id="serviceSub" 
         data-bs-parent="#sidebarAccordion">
        <ul class="submenu">
            <li>
                <a href="{{ route('service.index') }}" 
                   class="{{ Request::is('admin/service/items*') ? 'active' : '' }}">
                    <span>All Services</span>
                </a>
            </li>

            <li>
                <a href="{{ route('service.header.settings') }}" 
                   class="{{ Request::is('admin/service/header-settings*') ? 'active' : '' }}">
                    <span>Header Settings</span>
                </a>
            </li>
        </ul>
    </div>
</div>
        <div class="nav-item">
    <a class="nav-link {{ Request::is('admin/portfolio*') ? '' : 'collapsed' }}" 
       data-bs-toggle="collapse" 
       href="#portfolioSub"
       aria-expanded="{{ Request::is('admin/portfolio*') ? 'true' : 'false' }}">
        <i class="bi bi-collection-play"></i> <span>Portfolio</span>
        <i class="bi bi-chevron-down ms-auto small"></i>
    </a>
    <div class="collapse {{ Request::is('admin/portfolio*') ? 'show' : '' }}" 
         id="portfolioSub" 
         data-bs-parent="#sidebarAccordion">
        <ul class="submenu">
            <li>
                <a href="{{ route('portfolio.index') }}" 
                   class="{{ Request::is('admin/portfolio/projects*') ? 'active' : '' }}">
                    <span>All Projects</span>
                </a>
            </li>

            <li>
                <a href="{{ route('portfolio.header.settings') }}" 
                   class="{{ Request::is('admin/portfolio/header-settings*') ? 'active' : '' }}">
                    <span>Header Settings</span>
                </a>
            </li>
        </ul>
    </div>
</div>


        <div class="nav-item">
    <a class="nav-link {{ Request::is('admin/team*') ? '' : 'collapsed' }}" 
       data-bs-toggle="collapse" 
       href="#teamSub"
       aria-expanded="{{ Request::is('admin/team*') ? 'true' : 'false' }}">
        <i class="bi bi-people"></i> <span>Team Management</span>
        <i class="bi bi-chevron-down ms-auto small"></i>
    </a>
    <div class="collapse {{ Request::is('admin/team*') ? 'show' : '' }}" 
         id="teamSub" 
         data-bs-parent="#sidebarAccordion">
        <ul class="submenu">
            <li>
                <a href="{{ route('team.index') }}" 
                   class="{{ Request::is('admin/team/members*') ? 'active' : '' }}">
                    <span>Team Members</span>
                </a>
            </li>

            <li>
                <a href="{{ route('team.header.settings') }}" 
                   class="{{ Request::is('admin/team/header-settings*') ? 'active' : '' }}">
                    <span>Header Settings</span>
                </a>
            </li>
        </ul>
    </div>
</div>

        <div class="nav-item">
    <a class="nav-link {{ Request::is('admin/testimonial*') ? '' : 'collapsed' }}" 
       data-bs-toggle="collapse" 
       href="#testimonialSub"
       aria-expanded="{{ Request::is('admin/testimonial*') ? 'true' : 'false' }}">
        <i class="bi bi-star"></i> <span>Testimonials</span>
        <i class="bi bi-chevron-down ms-auto small"></i>
    </a>
    <div class="collapse {{ Request::is('admin/testimonial*') ? 'show' : '' }}" 
         id="testimonialSub" 
         data-bs-parent="#sidebarAccordion">
        <ul class="submenu">
            <li>
                <a href="{{ route('testimonial.index') }}" 
                   class="{{ Request::is('admin/testimonial/items*') ? 'active' : '' }}">
             
                    <span>Client Testimonials</span>
                </a>
            </li>

            <li>
                <a href="{{ route('testimonial.header.settings') }}" 
                   class="{{ Request::is('admin/testimonial/header-settings*') ? 'active' : '' }}">
               
                    <span>Header Settings</span>
                </a>
            </li>
        </ul>
    </div>
</div>

        <div class="nav-item">
            <a class="nav-link {{ Request::is('admin/contact*') ? '' : 'collapsed' }}" 
               data-bs-toggle="collapse" 
               href="#contactSub">
                <i class="bi bi-envelope"></i> <span>Contact Us</span>
                <i class="bi bi-chevron-down ms-auto small"></i>
            </a>
            <div class="collapse {{ Request::is('admin/contact*') ? 'show' : '' }}" id="contactSub" data-bs-parent="#sidebarAccordion">
                <ul class="submenu">
                    <li>
                        <a href="{{ route('contact.messages.index') }}" class="{{ Request::is('admin/contact/messages*') ? 'active' : '' }} d-flex justify-content-between align-items-center">
                            <span>User Messages</span>
                            @php $msgCount = \App\Models\GrowBigContact::where('is_read', 0)->count(); @endphp
                            @if($msgCount > 0)
                                <span class="badge bg-danger rounded-pill" style="font-size: 10px;">{{ $msgCount }}</span>
                            @endif
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('contact.quick_audits.index') }}" class="{{ Request::is('admin/contact/quick-audits*') ? 'active' : '' }} d-flex justify-content-between align-items-center">
                            <span>Quick Audits</span>
                            @php $auditCount = \App\Models\GrowBigQuickAudit::where('is_read', 0)->count(); @endphp
                            @if($auditCount > 0)
                                <span class="badge bg-warning text-dark rounded-pill" style="font-size: 10px;">{{ $auditCount }}</span>
                            @endif
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('contact.header.settings') }}" class="{{ Request::is('admin/contact/header-settings*') ? 'active' : '' }}">
                            Header Settings
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="sidebar-label"  style="color:#008541 !important;font-weight:bold;">Setting</div>
        
      <div class="nav-item">
    <a class="nav-link {{ Route::is('general.config') || Route::is('socialLink.index') || Request::is('extraPage*') ? '' : 'collapsed' }}" 
       data-bs-toggle="collapse" 
       href="#configSub" 
       aria-expanded="{{ Route::is('general.config') || Route::is('socialLink.index') || Request::is('extraPage*') ? 'true' : 'false' }}">
        <i class="bi bi-gear"></i> <span>Site Settings</span>
        <i class="bi bi-chevron-down ms-auto small"></i>
    </a>
    
    <div class="collapse {{ Route::is('general.config') || Route::is('socialLink.index') || Request::is('extraPage*') ? 'show' : '' }}" 
         id="configSub" 
         data-bs-parent="#sidebarAccordion">
        <ul class="submenu">
            <li>
                <a href="{{ route('general.config') }}" class="{{ Route::is('general.config') ? 'active' : '' }}">
                    <i class="bi bi-sliders2 me-2"></i> General Configuration
                </a>
            </li>
            <li>
                <a href="{{ route('socialLink.index') }}" class="{{ Route::is('socialLink.index') ? 'active' : '' }}">
                    <i class="bi bi-share me-2"></i> Social Links
                </a>
            </li>
            <li>
                <a href="{{ route('extraPage.index') }}" class="{{ Request::is('extraPage*') ? 'active' : '' }}">
                    <i class="bi bi-file-earmark-text me-2"></i> Extra Pages
                </a>
            </li>
        </ul>
    </div>
</div>
    </div>
</aside>