<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - Tong Sampah App</title>

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

            <p class="login-subtitle">Masukkan email Anda untuk menerima link reset password</p>

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('admin.password.email') }}" method="POST" novalidate>
                @csrf

                <div class="login-form-group">
                    <label for="email" class="login-form-label">Email Address</label>
                    <div class="login-input-group">
                        <i class="bi bi-envelope input-icon"></i>
                        <input type="email" name="email" id="email" class="login-input" placeholder="name@company.com" value="{{ old('email') }}" required>
                    </div>
                </div>

                <button type="submit" class="btn-login" id="btn-submit">
                    <span>Kirim Link Reset</span>
                    <i class="bi bi-arrow-right"></i>
                </button>

            </form>

            <p class="login-footer-text">
                Ingat password Anda? <a href="{{ route('admin.login') }}">Masuk di sini</a>
            </p>

        </div>
    </div>

    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
