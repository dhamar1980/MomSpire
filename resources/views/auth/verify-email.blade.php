<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email - MomSpire</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="mom-auth-page">
    <main class="mom-auth-card" aria-labelledby="verify-email-title">
        <div class="mom-auth-header">
            <a href="{{ url('/') }}" class="mom-auth-logo" aria-label="MomSpire">
                <img src="{{ asset('foto/logo.jpg') }}" alt="Logo MomSpire">
            </a>
            <h1 id="verify-email-title" class="mom-auth-title">Verifikasi Email</h1>
            <p class="mom-auth-subtitle">Cek inbox email yang kamu pakai untuk daftar.</p>
        </div>

        @if ($status === 'verification-link-sent')
            <div class="mom-alert-success">
                Link verifikasi baru sudah dikirim ke email kamu.
            </div>
        @endif

        @if (session('verification_mail_error'))
            <div class="mom-alert-error">
                {{ session('verification_mail_error') }}
            </div>
        @endif

        <p class="mom-auth-copy">
            Sebelum lanjut memakai MomSpire, klik link verifikasi yang sudah kami kirim. Kalau belum masuk, kamu bisa kirim ulang link verifikasi.
        </p>

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="mom-submit-btn">Kirim Ulang Link</button>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="mom-auth-footer">
            @csrf
            <button type="submit" class="mom-link-button">Keluar</button>
        </form>
    </main>
</body>
</html>
