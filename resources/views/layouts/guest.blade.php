<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'E-SISWI') }}</title>

    <!-- Google Fonts - Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">

    <style>
        body,
        html {
            height: 100%;
            margin: 0;
            background-color: #f4f7fe;
            font-family: 'Inter', sans-serif;
        }

        .auth-wrapper {
            display: flex;
            min-height: 100vh;
        }

        .auth-left {
            flex: 1;
            background: linear-gradient(135deg, var(--purple) 0%, #4338ca 100%);
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 3rem;
            position: relative;
            overflow: hidden;
        }

        .auth-left::after {
            content: '';
            position: absolute;
            top: -20%;
            right: -20%;
            width: 60%;
            height: 60%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            border-radius: 50%;
        }

        .auth-left::before {
            content: '';
            position: absolute;
            bottom: -10%;
            left: -10%;
            width: 40%;
            height: 40%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.08) 0%, transparent 60%);
            border-radius: 50%;
        }

        .auth-brand {
            z-index: 1;
            text-align: center;
        }

        .auth-brand h1 {
            font-size: 3.5rem;
            font-weight: 800;
            letter-spacing: -1px;
            margin-bottom: 1rem;
            text-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .auth-brand p {
            font-size: 1.15rem;
            opacity: 1;
            font-weight: 600;
            max-width: 440px;
            line-height: 1.6;
            margin: 0 auto;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.15);
        }

        .auth-right {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 3rem;
            background: white;
        }

        .auth-form-container {
            width: 100%;
            max-width: 420px;
        }

        @media (max-width: 991.98px) {
            .auth-left {
                display: none;
            }
        }

        /* Custom Input Styles for Auth */
        .auth-input {
            border-radius: 12px;
            padding: 0.75rem 1rem;
            border: 1.5px solid #e2e8f0;
            font-size: 0.95rem;
            transition: all 0.2s ease;
            background-color: #f8fafc;
        }

        .auth-input:focus {
            background-color: white;
            border-color: var(--purple);
            box-shadow: 0 0 0 4px rgba(106, 90, 224, 0.1);
        }

        .auth-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: #475569;
            margin-bottom: 0.5rem;
        }

        .auth-btn {
            background-color: var(--purple);
            color: white;
            border-radius: 12px;
            padding: 0.85rem 1rem;
            font-weight: 600;
            font-size: 1rem;
            border: none;
            transition: all 0.3s ease;
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 4px 12px rgba(106, 90, 224, 0.2);
        }

        .auth-btn:hover {
            background-color: var(--purple-dark);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(106, 90, 224, 0.3);
            color: white;
        }
    </style>
</head>

<body>
    <div class="auth-wrapper">
        <!-- Left Side (Branding/Visual) -->
        <div class="auth-left">
            <div class="auth-brand">
                <div class="d-flex align-items-center justify-content-center gap-4 mb-4">
                    <img src="{{ asset('img/uptm-logo.png') }}" alt="UPTM Logo"
                        style="max-height: 100px; width: auto; object-fit: contain;">
                    <img src="{{ asset('img/kelab-kediaman-logo.png') }}" alt="Kelab Kediaman Logo"
                        style="max-height: 110px; width: auto; object-fit: contain;">
                </div>
                <h1><i class="bi bi-journal-bookmark-fill me-2"></i>E-SISWI</h1>
                <p>Welcome to the UPTM Hostel Complaint & Facilities Management Portal. Sign in to track your requests,
                    manage payments, and stay updated.</p>
            </div>
        </div>

        <!-- Right Side (Form) -->
        <div class="auth-right">
            <div class="auth-form-container">
                <!-- Mobile Only Branding -->
                <div class="text-center mb-5 d-lg-none">
                    <div class="d-flex align-items-center justify-content-center gap-3 mb-3">
                        <img src="{{ asset('img/uptm-logo.png') }}" alt="UPTM Logo"
                            style="max-height: 60px; width: auto; object-fit: contain;">
                        <img src="{{ asset('img/kelab-kediaman-logo.png') }}" alt="Kelab Kediaman Logo"
                            style="max-height: 65px; width: auto; object-fit: contain;">
                    </div>
                    <h2 class="fw-800" style="color: var(--purple);">
                        <i class="bi bi-journal-bookmark-fill me-2"></i>E-SISWI
                    </h2>
                </div>

                {{ $slot }}

                <div class="text-center mt-5">
                    <p class="small text-muted mb-0">&copy; {{ date('Y') }} E-SISWI UPTM. All Rights Reserved.</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword(inputId, btn) {
            const input = document.getElementById(inputId);
            const icon = btn.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('bi-eye', 'bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('bi-eye-slash', 'bi-eye');
            }
        }
    </script>
    @stack('scripts')
</body>

</html>