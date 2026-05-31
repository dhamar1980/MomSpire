<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Verifikasi 2FA - MomSpire</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="mom-auth-page">
    <main class="mom-auth-card" aria-labelledby="two-factor-title">
        <div class="mom-auth-header">
            <a href="{{ url('/') }}" class="mom-auth-logo" aria-label="MomSpire">
                <img src="{{ asset('foto/logo.jpg') }}" alt="Logo MomSpire">
            </a>
            <h1 id="two-factor-title" class="mom-auth-title">Verifikasi 2FA</h1>
            <p class="mom-auth-subtitle">
                Masukkan kode dari aplikasi authenticator Anda.
                @if(!empty($email))
                    <span class="block mt-1 text-sm">{{ $email }}</span>
                @endif
            </p>
        </div>

        @if ($errors->any())
            <div class="mom-alert-error">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('two-factor-challenge.verify') }}" id="twoFactorForm">
            @csrf

            <div id="otp-state">
                <div class="mom-form-group">
                    <label class="mom-form-label" for="code">Kode OTP</label>
                    <input
                        class="mom-form-input text-center text-xl font-bold tracking-[0.35em]"
                        type="text"
                        id="code"
                        name="code"
                        inputmode="numeric"
                        pattern="[0-9]*"
                        autocomplete="one-time-code"
                        placeholder="000000"
                        maxlength="6"
                        autofocus
                    >
                </div>
            </div>

            <div id="recovery-state" hidden>
                <div class="mom-form-group">
                    <label class="mom-form-label" for="recovery_code">Recovery Code</label>
                    <input
                        class="mom-form-input font-mono uppercase tracking-[0.18em]"
                        type="text"
                        id="recovery_code"
                        name="recovery_code"
                        autocomplete="one-time-code"
                        placeholder="A1B2-C3D4"
                    >
                </div>
            </div>

            <button type="submit" class="mom-submit-btn" id="verifyBtn">Verifikasi</button>
        </form>

        <div class="mom-auth-footer">
            <button type="button" class="mom-link-button" id="switchToRecovery">Gunakan recovery code</button>
            <button type="button" class="mom-link-button" id="switchToOtp" hidden>Gunakan kode OTP</button>
        </div>

        <form method="POST" action="{{ route('logout') }}" class="mt-4">
            @csrf
            <button type="submit" class="mom-secondary-btn w-full">Batal dan keluar</button>
        </form>
    </main>

    <script>
        (function () {
            const switchToRecovery = document.getElementById('switchToRecovery');
            const switchToOtp = document.getElementById('switchToOtp');
            const otpState = document.getElementById('otp-state');
            const recoveryState = document.getElementById('recovery-state');
            const codeInput = document.getElementById('code');
            const recoveryInput = document.getElementById('recovery_code');
            const verifyBtn = document.getElementById('verifyBtn');

            if (codeInput) {
                codeInput.addEventListener('input', function (event) {
                    event.target.value = event.target.value.replace(/\D/g, '').slice(0, 6);
                });
            }

            if (recoveryInput) {
                recoveryInput.addEventListener('input', function (event) {
                    event.target.value = event.target.value.replace(/[^a-zA-Z0-9-]/g, '').toUpperCase();
                });
            }

            switchToRecovery.addEventListener('click', function () {
                otpState.hidden = true;
                recoveryState.hidden = false;
                switchToRecovery.hidden = true;
                switchToOtp.hidden = false;
                codeInput.value = '';
                recoveryInput.focus();
                verifyBtn.textContent = 'Verifikasi Recovery Code';
            });

            switchToOtp.addEventListener('click', function () {
                recoveryState.hidden = true;
                otpState.hidden = false;
                switchToOtp.hidden = true;
                switchToRecovery.hidden = false;
                recoveryInput.value = '';
                codeInput.focus();
                verifyBtn.textContent = 'Verifikasi';
            });
        })();
    </script>
</body>
</html>
