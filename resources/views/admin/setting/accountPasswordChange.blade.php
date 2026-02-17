<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Set New Password | {{ $siteConfig->site_name ?? 'Admin Portal' }}</title>
    <meta name="description" content="{{ $siteConfig->meta_description ?? '' }}">
    <meta name="keywords" content="{{ $siteConfig->meta_keywords ?? '' }}">
    <meta name="author" content="{{ $siteConfig->site_name ?? 'Gemini Admin' }}">

    <meta property="og:title" content="Create New Password | {{ $siteConfig->site_name }}">
    <meta property="og:description" content="{{ $siteConfig->meta_description ?? '' }}">
    <meta property="og:image" content="{{ asset($siteConfig->og_image ?? $siteConfig->logo) }}">

    <link rel="shortcut icon" href="{{ asset($siteConfig->icon) }}">
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        :root {
            --primary: #00a651;
            --primary-hover: #008541;
            --navy: #0a0f2c;
            --bg-light: #f4f7fe;
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

        .bg-decoration {
            position: absolute;
            width: 450px;
            height: 450px;
            background: linear-gradient(135deg, rgba(0, 166, 81, 0.08), rgba(0, 166, 81, 0.03));
            border-radius: 50%;
            z-index: -1;
        }
        .dec-1 { top: -150px; left: -150px; }
        .dec-2 { bottom: -150px; right: -150px; }

        .change-card {
            width: 100%;
            max-width: 440px;
            background: #ffffff;
            border-radius: 28px;
            padding: 3.5rem 2.8rem;
            box-shadow: 0 20px 50px rgba(10, 15, 44, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.7);
            text-align: center;
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .icon-box {
            width: 70px;
            height: 70px;
            background: rgba(0, 166, 81, 0.1);
            color: var(--primary);
            font-size: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 20px;
            margin: 0 auto 1.5rem;
        }

        .change-header h2 {
            color: var(--navy);
            font-weight: 800;
            font-size: 1.6rem;
            margin-bottom: 0.5rem;
        }

        .change-header p {
            color: #a3aed0;
            font-size: 0.9rem;
            margin-bottom: 2rem;
        }

        .form-label {
            display: block;
            text-align: left;
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--navy);
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 10px;
            margin-left: 4px;
        }

        .input-group-custom {
            position: relative;
            margin-bottom: 1.4rem;
        }

        .form-control {
            border: 1px solid #eef2f7 !important;
            background-color: #f8fafc !important;
            padding: 13px 18px;
            border-radius: 14px !important;
            font-size: 0.95rem;
            transition: var(--transition);
        }

        .form-control:focus {
            background-color: #fff !important;
            border-color: var(--primary) !important;
            box-shadow: 0 8px 20px rgba(0, 166, 81, 0.06) !important;
        }

        .password-toggle {
            position: absolute;
            right: 18px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #a3aed0;
            z-index: 10;
        }

        .btn-update {
            background: linear-gradient(135deg, var(--primary) 0%, #008541 100%);
            border: none;
            color: white;
            width: 100%;
            padding: 14px;
            border-radius: 14px;
            font-weight: 700;
            font-size: 1rem;
            margin-top: 1rem;
            box-shadow: 0 10px 20px rgba(0, 166, 81, 0.2);
            transition: var(--transition);
        }

        .btn-update:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 25px rgba(0, 166, 81, 0.3);
        }

        .back-link {
            display: inline-block;
            margin-top: 1.5rem;
            color: #a3aed0;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 600;
            transition: var(--transition);
        }

        .back-link:hover {
            color: var(--primary);
        }
    </style>
</head>
<body>

    <div class="bg-decoration dec-1"></div>
    <div class="bg-decoration dec-2"></div>

    <div class="change-card">
        <div class="icon-box">
            <i class="bi bi-shield-check"></i>
        </div>
        
        <div class="change-header">
            <h2>Create New Password</h2>
            <p>Set a secure password for your account on <strong>{{ $siteConfig->site_name ?? 'the portal' }}</strong>.</p>
        </div>

        <form action="{{ route('postPasswordChange') }}" method="POST" enctype="multipart/form-data" id="form">
            @csrf
            <input type="hidden" value="{{ $email }}" name="mainEmail" />
            @include('flash_message') 

            <div class="mb-1 text-start">
                <label class="form-label">New Password</label>
                <div class="input-group-custom">
                    <input type="password" name="password" id="newPassword" class="form-control" placeholder="••••••••" required>
                    <i class="bi bi-eye password-toggle toggle-eye" data-target="newPassword"></i>
                </div>
            </div>

            <div class="mb-1 text-start">
                <label class="form-label">Confirm New Password</label>
                <div class="input-group-custom">
                    <input type="password" name="password_confirmation" id="confirmPassword" class="form-control" placeholder="••••••••" required>
                    <i class="bi bi-eye password-toggle toggle-eye" data-target="confirmPassword"></i>
                </div>
            </div>

            <button type="submit" class="btn btn-update">Update Password</button>
        </form>

        <a href="{{ route('login') }}" class="back-link">
            <i class="bi bi-arrow-left me-1"></i> Back to Login
        </a>
    </div>

    <script>
        // Password Visibility Toggle
        document.querySelectorAll('.toggle-eye').forEach(icon => {
            icon.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const input = document.getElementById(targetId);
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);
                this.classList.toggle('bi-eye');
                this.classList.toggle('bi-eye-slash');
            });
        });

        // Form Match Validation
        document.getElementById('form').addEventListener('submit', function(e) {
            const newPass = document.getElementById('newPassword').value;
            const confirmPass = document.getElementById('confirmPassword').value;

            if (newPass !== confirmPass) {
                e.preventDefault();
                alert("Passwords do not match! Please check and try again.");
            }
        });
    </script>
</body>
</html>