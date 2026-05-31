<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Lupa Password - MomSpire</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="mom-auth-page">
    <main class="mom-auth-card" aria-labelledby="forgot-title">
        <div class="mom-auth-header">
            <a href="{{ url('/') }}" class="mom-auth-logo" aria-label="MomSpire">
                <img src="{{ asset('foto/logo.jpg') }}" alt="Logo MomSpire">
            </a>
            <h1 id="forgot-title" class="mom-auth-title">Lupa Password</h1>
            <p class="mom-auth-subtitle">Masukkan email Anda. Kami akan mengirimkan tautan untuk mereset password.</p>
        </div>

        @if(session('status'))
            <div class="mom-alert-success">{{ session('status') }}</div>
        @endif

        @if ($errors->any())
            <div class="mom-alert-error">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="mom-form-group">
                <label class="mom-form-label" for="email">Email</label>
                <input id="email" class="mom-form-input" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
                @error('email')
                    <div class="mom-error-message">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="mom-submit-btn">Kirim Tautan Reset</button>

            <div class="mom-auth-footer">
                <a href="{{ route('login') }}" class="mom-auth-link">Kembali ke masuk</a>
            </div>
        </form>
    </main>
</body>
</html>
