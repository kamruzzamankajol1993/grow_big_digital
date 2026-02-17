<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>{{ $siteConfig->meta_title ?? ($siteConfig->site_name . ' | Login') }}</title>
    <meta name="description" content="{{ $siteConfig->meta_description ?? '' }}">
    <meta name="keywords" content="{{ $siteConfig->meta_keywords ?? '' }}">
    <meta name="author" content="{{ $siteConfig->site_name ?? 'Gemini Admin' }}">
    <link rel="canonical" href="{{ url()->current() }}">

    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ $siteConfig->meta_title ?? $siteConfig->site_name }}">
    <meta property="og:description" content="{{ $siteConfig->meta_description ?? '' }}">
    <meta property="og:image" content="{{ asset($siteConfig->og_image ?? $siteConfig->logo) }}">
    <meta property="og:site_name" content="{{ $siteConfig->site_name }}">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:title" content="{{ $siteConfig->meta_title ?? $siteConfig->site_name }}">
    <meta name="twitter:description" content="{{ $siteConfig->meta_description ?? '' }}">
    <meta name="twitter:image" content="{{ asset($siteConfig->og_image ?? $siteConfig->logo) }}">

    <link rel="icon" type="image/x-icon" href="{{ asset($siteConfig->icon) }}">
    <link rel="apple-touch-icon" href="{{ asset($siteConfig->icon) }}">

    @if(isset($siteConfig->google_analytics_code))
        {!! $siteConfig->google_analytics_code !!}
    @endif
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        :root {
            --primary: #00a651;
            --primary-hover: #008541;
            --navy: #0a0f2c;
            --bg-light: #f4f7fe;
            --text-main: #2b3674;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-light);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            overflow: hidden;
        }

        /* Background Decorations */
        .bg-decoration {
            position: fixed;
            width: 400px;
            height: 400px;
            background: linear-gradient(135deg, rgba(0, 166, 81, 0.1), rgba(0, 166, 81, 0.05));
            border-radius: 50%;
            z-index: -1;
        }
        .dec-1 { top: -100px; left: -100px; }
        .dec-2 { bottom: -100px; right: -100px; }

        .login-card {
            width: 100%;
            max-width: 450px;
            background: #ffffff;
            border-radius: 30px;
            padding: 3rem;
            box-shadow: 0 20px 60px rgba(10, 15, 44, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.8);
            position: relative;
            z-index: 1;
        }

        .brand-logo {
            text-align: center;
            margin-bottom: 2rem;
        }

        .brand-logo img {
            max-height: 55px;
            margin-bottom: 1rem;
            object-fit: contain;
        }

        .login-title {
            color: var(--navy);
            font-weight: 800;
            font-size: 1.75rem;
            letter-spacing: -0.5px;
            margin-bottom: 0.5rem;
        }

        .login-subtitle {
            color: #a3aed0;
            font-size: 0.95rem;
            margin-bottom: 2.5rem;
        }

        .form-label {
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--navy);
            margin-left: 5px;
            margin-bottom: 8px;
        }

        .form-control {
            border: 1px solid #e0e5f2 !important;
            background-color: #ffffff !important;
            padding: 14px 20px;
            border-radius: 16px !important;
            font-size: 1rem;
            transition: var(--transition);
        }

        .form-control:focus {
            border-color: var(--primary) !important;
            box-shadow: 0 10px 20px rgba(0, 166, 81, 0.08) !important;
        }

        .password-toggle {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #a3aed0;
            z-index: 10;
        }

        .btn-login {
            background-color: var(--primary);
            border: none;
            color: white;
            width: 100%;
            padding: 15px;
            border-radius: 16px;
            font-weight: 700;
            font-size: 1.1rem;
            margin-top: 1rem;
            box-shadow: 0 10px 25px rgba(0, 166, 81, 0.25);
            transition: var(--transition);
        }

        .btn-login:hover {
            background-color: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(0, 166, 81, 0.35);
        }

        .forgot-link {
            color: var(--primary);
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .forgot-link:hover {
            text-decoration: underline;
        }

        @media (max-width: 576px) {
            .login-card {
                padding: 2rem;
                margin: 15px;
            }
        }
    </style>
</head>
<body>

    <div class="bg-decoration dec-1"></div>
    <div class="bg-decoration dec-2"></div>

    <div class="login-card">
        <div class="brand-logo">
            @if($siteConfig && $siteConfig->logo)
                <img src="{{ asset($siteConfig->logo) }}" alt="{{ $siteConfig->site_name }}">
            @else
                <img src="https://via.placeholder.com/150x50/00a651/ffffff?text=LOGO" alt="Default Logo">
            @endif
            <h2 class="login-title">Welcome Back</h2>
            <p class="login-subtitle">Enter your credentials to access {{ $siteConfig->site_name ?? 'Portal' }}</p>
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf
            
            <div class="mb-3">
                <label class="form-label text-uppercase">Email Address</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                       placeholder="mail@example.com" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="mb-2">
                <div class="d-flex justify-content-between">
                    <label class="form-label text-uppercase">Password</label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot-link">Forgot Password?</a>
                    @endif
                </div>
                <div class="position-relative">
                    <input type="password" name="password" id="passwordInput" class="form-control @error('password') is-invalid @enderror" 
                           placeholder="Min. 8 characters" required>
                    <i class="bi bi-eye password-toggle" id="togglePassword"></i>
                </div>
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-check mb-4 ms-1">
                <input class="form-check-input" type="checkbox" name="remember" id="rememberMe" {{ old('remember') ? 'checked' : '' }}>
                <label class="form-check-label small text-muted" for="rememberMe">
                    Keep me logged in
                </label>
            </div>

            <button type="submit" class="btn btn-login">Sign In</button>
        </form>

        <div class="text-center mt-4">
            <p class="small text-muted mb-0">
                Issues logging in? 
                <a href="mailto:{{ $siteConfig->email ?? '#' }}" class="text-primary fw-bold text-decoration-none">Contact Admin</a>
            </p>
        </div>
    </div>

    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#passwordInput');

        togglePassword.addEventListener('click', function (e) {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.classList.toggle('bi-eye');
            this.classList.toggle('bi-eye-slash');
        });
    </script>
</body>
</html>