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
    <link rel="canonical" href="{{ url()->current() }}">

    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title') | {{ $siteConfig->site_name }}">
    <meta property="og:description" content="{{ $siteConfig->meta_description ?? '' }}">
    <meta property="og:image" content="{{ asset($siteConfig->og_image ?? $siteConfig->logo) }}">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:title" content="@yield('title') | {{ $siteConfig->site_name }}">
    <meta name="twitter:description" content="{{ $siteConfig->meta_description ?? '' }}">
    <meta name="twitter:image" content="{{ asset($siteConfig->og_image ?? $siteConfig->logo) }}">

    <link rel="shortcut icon" href="{{ asset($siteConfig->icon) }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css"> <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css"> <link rel="stylesheet" href="{{ asset('public/admin/assets/css/auth-style.css') }}">
    
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f4f7fe; }
        .main-content { margin-left: 260px; padding: 30px; min-height: 100vh; transition: 0.3s; }
        .card { border-radius: 15px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        @media (max-width: 991px) { .main-content { margin-left: 0; } }
    </style>
    @yield('css')
</head>
<body>

    @include('admin.layout.header')
    @include('admin.layout.sidebar')

    <div class="main-content">
        @yield('body')
        
        <footer class="mt-5 pt-3 border-top text-center text-muted">
            <p>&copy; {{ date('Y') }} {{ $siteConfig->site_name }}. All Rights Reserved.</p>
        </footer>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script> <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
    @yield('scripts')
</body>
</html>