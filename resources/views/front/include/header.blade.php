
<nav class="navbar navbar-expand-lg navbar-custom">
    <div class="container">
        <!-- LEFT: Logo -->
        <a class="navbar-brand" href="{{ route('front.index') }}">
            <div class="logo-icon-wrapper">
                <img src="{{ asset('/') }}{{ $front_logo_name }}" alt="">
            </div>
        </a>

        <!-- Mobile Toggle -->
        <div class="d-lg-none d-flex align-items-center gap-2 ms-auto action-icons-mobile-row">
            <button class="icon-circle-btn border-0" type="button" data-bs-toggle="offcanvas"
         data-bs-target="#cellexaProfileCanvas"><i class="fa-regular fa-user"></i></button>
            <button class="icon-circle-btn border-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#cellexaCartCanvas"><i class="fa-solid fa-cart-shopping"></i></button>
        </div>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="offcanvas"
            data-bs-target="#navbarOffcanvas"><span class="navbar-toggler-icon"></span></button>

        <!-- RIGHT: Navigation & Icons -->
        <div class="offcanvas offcanvas-end" tabindex="-1" id="navbarOffcanvas">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title offcanvas-custom-title">Menu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
            </div>
            <div class="offcanvas-body">
                <ul class="navbar-nav align-items-center ms-auto">
                    <li class="nav-item w-100-mobile"><a class="nav-custom-btn active-btn" href="{{ route('front.index') }}">Home</a>
                    </li>


                     @if(isset($header_categories))
                                @foreach($header_categories as $category)
                    <li class="nav-item w-100-mobile">
                        
                      
                        
                        
                         @if($category->brands->count() > 0)
                                            {{-- Route 1: If category has companies/brands --}}
                                            <a class="nav-custom-btn outline-btn" 
                                               href="{{ route('front.category.companies', $category->slug) }}">
                                               {{ $category->name }}
                                            </a>
                                        @else
                                            {{-- Route 2: If category has no companies (show products directly) --}}
                                            <a class="nav-custom-btn outline-btn" 
                                               href="{{ route('front.category.products', $category->slug) }}">
                                               {{ $category->name }}
                                            </a>
                                        @endif
                        
                        </li>

                              @endforeach
                            @endif
                    
                    <li class="nav-item w-100-mobile"><a class="nav-custom-btn outline-btn" href="{{ route('front.aboutUs') }}">About
                            us</a></li>
                    <li class="nav-item w-100-mobile"><a class="nav-custom-btn outline-btn"
                            href="{{ route('front.contactUs') }}">Contact us</a></li>
                </ul>
                <div class="action-icons d-none d-lg-flex ms-3">
                    <button class="icon-circle-btn" type="button" data-bs-toggle="offcanvas"
         data-bs-target="#cellexaProfileCanvas"><i class="fa-regular fa-user"></i></button>
                    <button class="icon-circle-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#cellexaCartCanvas"><i class="fa-solid fa-cart-shopping"></i></button>
                </div>
            </div>
        </div>
    </div>
</nav>

