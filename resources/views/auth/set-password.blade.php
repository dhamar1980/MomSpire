<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Set Password - MomSpire</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="mom-auth-page">
    <main class="mom-auth-card" aria-labelledby="set-password-title">
        <div class="mom-auth-header">
            <a href="{{ url('/') }}" class="mom-auth-logo" aria-label="MomSpire">
                <img src="{{ asset('foto/logo.jpg') }}" alt="Logo MomSpire">
            </a>
            <h1 id="set-password-title" class="mom-auth-title">Atur Password</h1>
            <p class="mom-auth-subtitle">
                Akun Google SSO ini belum memiliki password. Silakan atur password terlebih dahulu agar bisa mengaktifkan 2FA dan login tanpa Google.
            </p>
        </div>

        @if ($errors->any())
            <div class="mom-alert-error">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        @if (session('status'))
            <div class="mom-alert-success">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('settings.set-password.store') }}" id="setPasswordForm">
            @csrf

            <div class="mom-form-group">
                <label class="mom-form-label" for="password">Password Baru</label>
                <input
                    class="mom-form-input"
                    type="password"
                    id="password"
                    name="password"
                    autocomplete="new-password"
                    placeholder="Minimal 8 karakter"
                    minlength="8"
                    required
                    autofocus
                >
            </div>

            <div class="mom-form-group">
                <label class="mom-form-label" for="password_confirmation">Konfirmasi Password</label>
                <input
                    class="mom-form-input"
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    autocomplete="new-password"
                    placeholder="Masukkan lagi password baru"
                    minlength="8"
                    required
                >
            </div>

            <button type="submit" class="mom-submit-btn">Simpan Password</button>
        </form>

        <div class="mom-auth-footer">
            <a href="{{ route('dashboard') }}" class="mom-link-button">Lewati</a>
        </div>
    </main>
</body>
</html>