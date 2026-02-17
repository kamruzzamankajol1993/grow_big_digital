<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Forgot Password | {{ $siteConfig->site_name ?? 'Admin Portal' }}</title>
    <meta name="description" content="{{ $siteConfig->meta_description ?? '' }}">
    <meta name="keywords" content="{{ $siteConfig->meta_keywords ?? '' }}">
    <meta name="author" content="{{ $siteConfig->site_name ?? 'Gemini Admin' }}">

    <meta property="og:title" content="Forgot Password | {{ $siteConfig->site_name }}">
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

        .forgot-card {
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

        .forgot-header h2 {
            color: var(--navy);
            font-weight: 800;
            font-size: 1.6rem;
            margin-bottom: 0.5rem;
        }

        .forgot-header p {
            color: #a3aed0;
            font-size: 0.9rem;
            line-height: 1.6;
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

        .btn-reset {
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

        .btn-reset:disabled {
            background: #ccc;
            box-shadow: none;
            cursor: not-allowed;
        }

        .back-to-login {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 2rem;
            color: var(--primary);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 700;
            transition: var(--transition);
        }

        .back-to-login:hover {
            gap: 12px;
            color: var(--primary-hover);
        }
    </style>
</head>
<body>

    <div class="bg-decoration dec-1"></div>
    <div class="bg-decoration dec-2"></div>

    <div class="forgot-card">
        <div class="icon-box">
            <i class="bi bi-shield-lock"></i>
        </div>
        
        <div class="forgot-header">
            <h2>Forgot Password?</h2>
            <p>Enter your email and we'll send instructions to reset your password for <strong>{{ $siteConfig->site_name ?? 'the portal' }}</strong>.</p>
        </div>

        <form id="form" action="{{ route('checkMailPost') }}" method="POST">
            @csrf
            @include('flash_message')  

            <div class="mb-4">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="you@example.com" required>
                <div id="emailFeedback" class="small mt-2"></div>
            </div>

            <button type="submit" id="finalValue" class="btn btn-reset">Send Instructions</button>
        </form>

        <a href="{{ route('login') }}" class="back-to-login">
            <i class="bi bi-arrow-left"></i> Back to Login
        </a>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    
    <script>
        $(document).ready(function () {
            // Your AJAX logic for email validation
            $("#email").keyup(function () {
                var mainId = $(this).val();
                
                if(mainId.length > 3) {
                    $.ajax({
                        url: "{{ route('checkMailForPassword') }}",
                        method: 'GET',
                        data: { mainId: mainId },
                        success: function(data) {
                            if(data == 0) {
                                $('#finalValue').attr('disabled', 'disabled');
                                $('#emailFeedback').html('<span class="text-danger"><i class="bi bi-x-circle"></i> Email not found in our records.</span>');
                            } else {
                                $('#finalValue').removeAttr('disabled');
                                $('#emailFeedback').html('<span class="text-success"><i class="bi bi-check-circle"></i> Email verified.</span>');
                            }
                        }
                    });
                } else {
                    $('#emailFeedback').html('');
                }
            });
        });
    </script>
</body>
</html>