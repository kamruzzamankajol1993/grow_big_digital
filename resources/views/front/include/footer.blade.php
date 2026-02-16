<footer id="footer" class="footer">

    <div class="container footer-top">
        <div class="row gy-4">
            <div class="col-lg-4 col-md-6 footer-about">
                <a href="{{ route('front.index') }}" class="logo d-flex align-items-center">
                    <img src="{{ asset('/') }}{{ $front_logo_name }}" alt="">
                </a>
                <div class="social-links d-flex mt-2">
                    {{-- Twitter/X --}}
                    @php $twitter = $social_links->where('title', 'Twitter')->first(); @endphp
                    @if($twitter)
                        <a href="{{ $twitter->link }}" target="_blank"><i class="bi bi-twitter-x"></i></a>
                    @endif

                    {{-- Facebook --}}
                    @php $facebook = $social_links->where('title', 'Facebook')->first(); @endphp
                    @if($facebook)
                        <a href="{{ $facebook->link }}" target="_blank"><i class="bi bi-facebook"></i></a>
                    @endif

                    {{-- Instagram --}}
                    @php $instagram = $social_links->where('title', 'Instagram')->first(); @endphp
                    @if($instagram)
                         <a href="{{ $instagram->link }}" target="_blank"><i class="bi bi-instagram"></i></a>
                    @endif

                    {{-- LinkedIn --}}
                    @php $linkedin = $social_links->where('title', 'LinkedIn')->first(); @endphp
                    @if($linkedin)
                        <a href="{{ $linkedin->link }}" target="_blank"><i class="bi bi-linkedin"></i></a>
                    @endif
                </div>
            </div>

            <div class="col-lg-4 col-md-3 footer-links">
                <h4>Useful Links</h4>
                <ul>
                    <li><a href="{{ route('front.index') }}">Home</a></li>
                    <li><a href="{{ route('front.aboutUs') }}">About us</a></li>
                    <li><a href="{{ route('front.contactUs') }}">Contact us</a></li>
                    <li><a href="#">Terms of service</a></li>
                    <li><a href="#">Privacy policy</a></li>
                </ul>
            </div>

            <div class="col-lg-4 col-md-3 footer-links">
                <h4>Contact Details</h4>
                <div class="footer-contact">
                    <p>{{ $front_ins_add }}</p>
                    <p class="mt-3"><strong>Phone:</strong> <span>{{ $front_ins_phone }}</span></p>
                    <p><strong>Email:</strong> <span>{{ $front_ins_email }}</span></p>
                </div>
            </div>

        </div>
    </div>

    <div class="container-fluid copyright text-center mt-4">
        <p>© <span>Copyright</span> <strong class="px-1 sitename">{{ $front_ins_name }}</strong> <span>All Rights Reserved</span>
        </p>
        <div class="credits">
              Designed by <a href="#"><img style="height: 25px;" src="{{ asset('/') }}public/front/assets/img/footer.png" alt=""></a>
          </div>
    </div>

</footer>