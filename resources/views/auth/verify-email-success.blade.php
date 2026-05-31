<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Berhasil - MomSpire</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="mom-auth-page">
    <main class="mom-auth-card" aria-labelledby="verify-success-title">
        <div class="mom-auth-header">
            <a href="{{ url('/') }}" class="mom-auth-logo" aria-label="MomSpire">
                <img src="{{ asset('foto/logo.jpg') }}" alt="Logo MomSpire">
            </a>
            <h1 id="verify-success-title" class="mom-auth-title">Verifikasi Berhasil</h1>
            <p class="mom-auth-subtitle">Email kamu sudah aktif di MomSpire.</p>
        </div>

        <div class="mom-alert-success">
            Akun sudah terverifikasi. Silakan login ulang untuk masuk ke dashboard.
        </div>

        <a href="{{ route('login') }}" class="mom-submit-btn mom-auth-button-link">Masuk Sekarang</a>
    </main>
</body>
</html>
