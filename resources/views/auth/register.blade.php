<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - MomSpire</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="mom-auth-page">
    <main class="mom-auth-card mom-auth-card-wide" aria-labelledby="register-title">
        <div class="mom-auth-header">
            <a href="{{ url('/') }}" class="mom-auth-logo" aria-label="MomSpire">
                <img src="{{ asset('foto/logo.jpg') }}" alt="Logo MomSpire">
            </a>
            <h1 id="register-title" class="mom-auth-title">MomSpire</h1>
            <p class="mom-auth-subtitle">Buat akun baru</p>
        </div>

        @if ($errors->any())
            <div class="mom-alert-error">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="mom-form-group">
                <label class="mom-form-label" for="name">Nama Lengkap</label>
                <input class="mom-form-input" type="text" id="name" name="name" value="{{ old('name') }}" required autofocus autocomplete="name">
                @error('name')
                    <div class="mom-error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="mom-form-group">
                <label class="mom-form-label" for="email">Email</label>
                <input class="mom-form-input" type="email" id="email" name="email" value="{{ old('email') }}" required autocomplete="username">
                @error('email')
                    <div class="mom-error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="mom-form-group">
                <label class="mom-form-label" for="phone_number">Nomor Telepon</label>
                <input class="mom-form-input" type="tel" id="phone_number" name="phone_number" value="{{ old('phone_number') }}" autocomplete="tel">
                @error('phone_number')
                    <div class="mom-error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="mom-form-group">
                <label class="mom-form-label" for="password">Password</label>
                <div class="mom-password-field">
                    <input class="mom-form-input" type="password" id="password" name="password" required autocomplete="new-password">
                    <button type="button" class="mom-password-toggle" data-password-toggle="#password" aria-label="Tampilkan password" aria-pressed="false">
                        <i class="bi bi-eye" data-password-icon-show aria-hidden="true"></i>
                        <i class="bi bi-eye-slash d-none" data-password-icon-hide aria-hidden="true"></i>
                    </button>
                </div>
                @error('password')
                    <div class="mom-error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="mom-form-group">
                <label class="mom-form-label" for="password_confirmation">Konfirmasi Password</label>
                <div class="mom-password-field">
                    <input class="mom-form-input" type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password">
                    <button type="button" class="mom-password-toggle" data-password-toggle="#password_confirmation" aria-label="Tampilkan password" aria-pressed="false">
                        <i class="bi bi-eye" data-password-icon-show aria-hidden="true"></i>
                        <i class="bi bi-eye-slash d-none" data-password-icon-hide aria-hidden="true"></i>
                    </button>
                </div>
                @error('password_confirmation')
                    <div class="mom-error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="mom-form-group">
                <label class="mom-check-label">
                    <input class="mom-checkbox" type="checkbox" name="terms" required>
                    <span>Saya setuju dengan syarat dan ketentuan</span>
                </label>
                @error('terms')
                    <div class="mom-error-message">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="mom-submit-btn">Daftar</button>
        </form>

        <div class="mom-auth-divider">
            <span>atau</span>
        </div>

        <a class="mom-google-btn" href="{{ route('auth.google.redirect', ['intent' => 'register']) }}">
            <span class="mom-google-mark" aria-hidden="true">G</span>
            <span>Daftar dengan Google</span>
        </a>

        <div class="mom-auth-footer">
            Sudah punya akun? <a class="mom-auth-link" href="{{ route('login') }}">Masuk di sini</a>
        </div>
    </main>
</body>
</html>
