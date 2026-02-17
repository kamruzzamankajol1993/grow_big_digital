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

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css"> <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css"> 
   
    <link rel="stylesheet" href="{{ asset('public/admin/css/style.css') }}">
    @yield('css')
</head>
<body>

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    @include('admin.layout.header')
    @include('admin.layout.sidebar')

    <main class="main-wrapper">
        <div class="container-fluid">
            @yield('body')
        </div>
    </main>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script> <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
    <script>
    document.addEventListener("DOMContentLoaded", function() {
     

        // --- UI Interactions ---
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const toggleBtn = document.getElementById('sidebarToggle');
        const closeBtn = document.getElementById('sidebarClose');
        const cacheBtn = document.getElementById('cacheBtn');

        function toggleSidebar() { sidebar.classList.toggle('active'); overlay.classList.toggle('active'); }
        [toggleBtn, closeBtn, overlay].forEach(el => el?.addEventListener('click', toggleSidebar));

        // Cache Logic
        if (cacheBtn) {
            cacheBtn.addEventListener('click', function() {
                const original = this.innerHTML;
                this.innerHTML = `<span class="spinner-border spinner-border-sm me-2"></span> Cleaning...`;
                setTimeout(() => {
                    this.innerHTML = `<i class="bi bi-check-circle me-2"></i> Done!`;
                    this.classList.replace('btn-outline-danger', 'btn-success');
                    setTimeout(() => {
                        this.innerHTML = original;
                        this.classList.replace('btn-success', 'btn-outline-danger');
                    }, 2000);
                }, 1500);
            });
        }
    });
</script>
    @yield('scripts')
</body>
</html>