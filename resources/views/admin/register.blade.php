<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Tong Sampah App</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('assets/images/favicon.ico') }}">

    <!-- Local Third-Party Libraries (100% Offline Compatible) -->
    <link rel="stylesheet" href="{{ asset('assets/libs/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/bootstrap-icons/bootstrap-icons.css') }}">

    <!-- Main Design System & Custom Stylesheet -->
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
</head>
<body>

    <!-- ==========================================
         START: Authentication Container & Register Card
         ========================================== -->
    <div class="login-wrapper">
        <!-- Glowing background shapes for modern visual appearance -->
        <div class="login-bg-shape login-bg-shape-1"></div>
        <div class="login-bg-shape login-bg-shape-2"></div>

        <!-- Main centered register card -->
        <div class="login-card">

            <!-- Brand Identity -->
            <a href="{{ route('admin.login') }}" class="login-brand text-decoration-none">
                <i class="bi bi-trash"></i>
                <span>Tong Sampah</span>
            </a>

            <p class="login-subtitle">Buat akun untuk mengakses dashboard</p>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Register Form -->
            <form action="{{ route('admin.register.attempt') }}" method="POST" id="registerForm" class="needs-validation" novalidate>
                @csrf

                <!-- Name Input Group -->
                <div class="login-form-group">
                    <label for="name" class="login-form-label">Nama Lengkap</label>
                    <div class="login-input-group">
                        <i class="bi bi-person input-icon"></i>
                        <input type="text" name="name" id="name" class="login-input" placeholder="Nama Anda" value="{{ old('name') }}" required>
                    </div>
                </div>

                <!-- Email Input Group -->
                <div class="login-form-group">
                    <label for="email" class="login-form-label">Email Address</label>
                    <div class="login-input-group">
                        <i class="bi bi-envelope input-icon"></i>
                        <input type="email" name="email" id="email" class="login-input" placeholder="name@company.com" value="{{ old('email') }}" required>
                    </div>
                </div>

                <!-- Password Input Group -->
                <div class="login-form-group">
                    <label for="password" class="login-form-label">Password</label>
                    <div class="login-input-group">
                        <i class="bi bi-shield-lock input-icon"></i>
                        <input type="password" name="password" id="password" class="login-input login-input-password" placeholder="••••••••" required minlength="8">
                        <button type="button" class="password-toggle-btn" id="toggle-password" aria-label="Show password">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>

                <!-- Password Confirmation Group -->
                <div class="login-form-group">
                    <label for="password_confirmation" class="login-form-label">Konfirmasi Password</label>
                    <div class="login-input-group">
                        <i class="bi bi-shield-lock input-icon"></i>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="login-input login-input-password" placeholder="••••••••" required minlength="8">
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn-login" id="btn-submit">
                    <span>Buat Akun</span>
                    <i class="bi bi-arrow-right"></i>
                </button>

            </form>

            <!-- Footer Link -->
            <p class="login-footer-text">
                Sudah punya akun? <a href="{{ route('admin.login') }}">Masuk di sini</a>
            </p>

        </div>
    </div>
    <!-- END: Authentication Container -->

    <!-- Local Bootstrap bundle -->
    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Custom Authentication interactions script -->
    <script src="{{ asset('assets/js/auth.js') }}"></script>
</body>
</html>
