<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Tong Sampah App</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('assets/images/favicon.ico') }}">

    <!-- Local Third-Party Libraries (100% Offline Compatible) -->
    <link rel="stylesheet" href="{{ asset('assets/libs/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/bootstrap-icons/bootstrap-icons.css') }}">

    <!-- Main Design System & Custom Stylesheet -->
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
</head>
<body>

    <div class="login-wrapper">
        <div class="login-bg-shape login-bg-shape-1"></div>
        <div class="login-bg-shape login-bg-shape-2"></div>

        <div class="login-card">

            <a href="{{ route('admin.login') }}" class="login-brand text-decoration-none">
                <i class="bi bi-trash"></i>
                <span>Tong Sampah</span>
            </a>

            <p class="login-subtitle">Buat password baru untuk akun Anda</p>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.password.update') }}" method="POST" novalidate>
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="login-form-group">
                    <label for="email" class="login-form-label">Email Address</label>
                    <div class="login-input-group">
                        <i class="bi bi-envelope input-icon"></i>
                        <input type="email" name="email" id="email" class="login-input" value="{{ old('email', $email) }}" required>
                    </div>
                </div>

                <div class="login-form-group">
                    <label for="password" class="login-form-label">Password Baru</label>
                    <div class="login-input-group">
                        <i class="bi bi-shield-lock input-icon"></i>
                        <input type="password" name="password" id="password" class="login-input login-input-password" placeholder="••••••••" required minlength="8">
                    </div>
                </div>

                <div class="login-form-group">
                    <label for="password_confirmation" class="login-form-label">Konfirmasi Password</label>
                    <div class="login-input-group">
                        <i class="bi bi-shield-lock input-icon"></i>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="login-input login-input-password" placeholder="••••••••" required minlength="8">
                    </div>
                </div>

                <button type="submit" class="btn-login" id="btn-submit">
                    <span>Reset Password</span>
                    <i class="bi bi-arrow-right"></i>
                </button>

            </form>

        </div>
    </div>

    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
