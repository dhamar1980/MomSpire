<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - MomSpire</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="mom-auth-page">
    <main class="mom-auth-card" aria-labelledby="login-title">
        <div class="mom-auth-header">
            <a href="{{ url('/') }}" class="mom-auth-logo" aria-label="MomSpire">
                <img src="{{ asset('foto/logo.jpg') }}" alt="Logo MomSpire">
            </a>
            <h1 id="login-title" class="mom-auth-title">MomSpire</h1>
            <p class="mom-auth-subtitle">Masuk ke akun Anda</p>
        </div>

        @php
            $loginError = $errors->first('email') ?: ($errors->first('password') ?: '');
        @endphp

        @if($loginError)
            <div class="mom-alert-error" id="login-error-alert">
                {{ $loginError }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.submit') }}">
            @csrf

            <div class="mom-form-group">
                <label class="mom-form-label" for="email">Email</label>
                <input class="mom-form-input" type="email" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
                @error('email')
                    <div class="mom-error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="mom-form-group">
                <label class="mom-form-label" for="password">Password</label>
                <div class="mom-password-field">
                    <input class="mom-form-input" type="password" id="password" name="password" required autocomplete="current-password">
                    <button type="button" class="mom-password-toggle" data-password-toggle="#password" aria-label="Tampilkan password" aria-pressed="false">
                        <i class="bi bi-eye" data-password-icon-show aria-hidden="true"></i>
                        <i class="bi bi-eye-slash d-none" data-password-icon-hide aria-hidden="true"></i>
                    </button>
                </div>
                @error('password')
                    <div class="mom-error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="mom-form-group mom-auth-row">
                <label class="mom-check-label">
                    <input class="mom-checkbox" type="checkbox" name="remember">
                    <span>Ingat saya</span>
                </label>
                <button id="forgot-link" class="mom-link-button" type="button">Lupa password?</button>
            </div>

            <button type="submit" class="mom-submit-btn">Masuk</button>
        </form>

        <div class="mom-auth-divider">
            <span>atau</span>
        </div>

        <a class="mom-google-btn" href="{{ route('auth.google.redirect', ['intent' => 'login']) }}">
            <span class="mom-google-mark" aria-hidden="true">G</span>
            <span>Masuk dengan Google</span>
        </a>

        <div class="mom-auth-footer">
            Belum punya akun? <a class="mom-auth-link" href="{{ route('register') }}">Daftar di sini</a>
        </div>

        <section id="forgot-password-card" class="mom-forgot-card" aria-labelledby="forgot-title" hidden>
            <h2 id="forgot-title" class="mom-forgot-title">Lupa Password</h2>
            <p class="mom-forgot-text">Masukkan email Anda untuk menerima tautan reset.</p>

            @if(session('status'))
                <div class="mom-alert-success">{{ session('status') }}</div>
            @endif

            @if ($errors->has('email'))
                <div class="mom-error-message">{{ $errors->first('email') }}</div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="mom-form-group">
                    <label class="mom-form-label" for="forgot_email">Email</label>
                    <input id="forgot_email" class="mom-form-input" type="email" name="email" value="{{ old('email') }}" required autocomplete="username">
                </div>
                <div class="mom-auth-row">
                    <button type="submit" class="mom-submit-btn">Kirim Tautan Reset</button>
                    <button type="button" id="cancel-forgot" class="mom-secondary-btn">Batal</button>
                </div>
            </form>
        </section>
    </main>

    <script>
        (function () {
            const forgotLink = document.getElementById('forgot-link');
            const forgotCard = document.getElementById('forgot-password-card');
            const loginEmail = document.getElementById('email');
            const forgotEmail = document.getElementById('forgot_email');
            const cancelBtn = document.getElementById('cancel-forgot');

            if (forgotLink) {
                forgotLink.addEventListener('click', function (event) {
                    event.preventDefault();
                    openForgot();
                });
            }

            if (cancelBtn) {
                cancelBtn.addEventListener('click', closeForgot);
            }

            @if($errors->has('email') || session('status'))
                openForgot();
            @endif

            function openForgot() {
                if (loginEmail && forgotEmail && loginEmail.value) {
                    forgotEmail.value = loginEmail.value;
                }

                forgotCard.hidden = false;
                forgotCard.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }

            function closeForgot() {
                forgotCard.hidden = true;
            }
        })();
    </script>
</body>
</html>
