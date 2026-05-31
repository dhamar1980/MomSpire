<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Security - MomSpire</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 font-sans text-slate-900">
    <main class="mx-auto flex w-full max-w-6xl flex-col gap-6 px-4 py-8 sm:px-6 lg:px-8">
        <header class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-bold uppercase tracking-[0.2em] text-[#d6336c]">Profile / Security</p>
                <h1 class="mt-2 text-3xl font-extrabold tracking-normal text-slate-950">Two Factor Authentication</h1>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">
                    Lindungi akun MomSpire dengan kode TOTP dari Google Authenticator, Authy, Microsoft Authenticator, Aegis, 1Password, Bitwarden, atau aplikasi authenticator lain yang mendukung RFC 6238.
                </p>
            </div>
            <a href="{{ url('/dashboard') }}" class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-bold text-slate-700 shadow-sm hover:bg-slate-100">
                Kembali ke Dashboard
            </a>
        </header>

        @if (session('status'))
            <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-semibold text-rose-800">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <section class="grid gap-6 lg:grid-cols-[0.9fr_1.1fr]">
            <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-extrabold text-slate-950">Status 2FA</h2>
                        <p class="mt-1 text-sm text-slate-600">Akun: {{ $user->email }}</p>
                    </div>
                    @if ($twoFactorEnabled)
                        <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-extrabold text-emerald-700">Aktif</span>
                    @elseif ($twoFactorPending)
                        <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-extrabold text-amber-700">Menunggu OTP</span>
                    @else
                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-extrabold text-slate-600">Nonaktif</span>
                    @endif
                </div>

                <div class="mt-6 rounded-lg bg-slate-50 p-4 text-sm leading-6 text-slate-600">
                    2FA memakai TOTP 6 digit, algoritma SHA-1, interval 30 detik. Secret key disimpan terenkripsi dengan Laravel Crypt dan tidak dikirim ke layanan pihak ketiga.
                </div>

                @if (! $twoFactorPending && ! $twoFactorEnabled)
                    <form method="POST" action="{{ route('settings.security.two-factor.enable') }}" class="mt-6 space-y-4">
                        @csrf
                        <div>
                            <label for="enable-password" class="mom-form-label">Password saat ini</label>
                            <input id="enable-password" type="password" name="password" class="mom-form-input" required autocomplete="current-password">
                        </div>
                        <button type="submit" class="mom-submit-btn">Aktifkan 2FA</button>
                    </form>
                @else
                    <form method="POST" action="{{ route('settings.security.two-factor.disable') }}" class="mt-6 space-y-4">
                        @csrf
                        @method('DELETE')
                        <div>
                            <label for="disable-password" class="mom-form-label">Password saat ini</label>
                            <input id="disable-password" type="password" name="password" class="mom-form-input" required autocomplete="current-password">
                        </div>
                        <button type="submit" class="w-full rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-extrabold text-rose-700 hover:bg-rose-100">
                            Nonaktifkan 2FA
                        </button>
                    </form>
                @endif
            </div>

            <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-extrabold text-slate-950">Setup Authenticator</h2>

                @if ($twoFactorPending || $twoFactorEnabled)
                    <div class="mt-5 grid gap-5 md:grid-cols-[240px_1fr]">
                        <div class="flex min-h-60 items-center justify-center rounded-lg border border-slate-200 bg-white p-4">
                            <div class="[&_svg]:h-52 [&_svg]:w-52">
                                {!! $qrCodeSvg !!}
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="mom-form-label">Secret Key</label>
                                <div class="break-all rounded-lg border border-slate-200 bg-slate-50 px-3 py-3 font-mono text-sm font-bold text-slate-800">
                                    {{ $secretKey }}
                                </div>
                            </div>

                            @if ($twoFactorPending)
                                <form method="POST" action="{{ route('settings.security.two-factor.confirm') }}" class="space-y-3">
                                    @csrf
                                    <div>
                                        <label for="confirm-code" class="mom-form-label">OTP pertama</label>
                                        <input id="confirm-code" type="text" name="code" inputmode="numeric" maxlength="6" class="mom-form-input text-center text-xl font-bold tracking-[0.35em]" placeholder="000000" required autocomplete="one-time-code">
                                    </div>
                                    <button type="submit" class="mom-submit-btn">Konfirmasi 2FA</button>
                                </form>
                            @else
                                <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800">
                                    2FA sudah aktif. Login berikutnya akan meminta OTP atau recovery code.
                                </div>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="mt-5 rounded-lg border border-dashed border-slate-300 bg-slate-50 p-8 text-center text-sm text-slate-600">
                        Aktifkan 2FA terlebih dahulu untuk mendapatkan QR code dan secret key.
                    </div>
                @endif
            </div>
        </section>

        @if ($twoFactorPending || $twoFactorEnabled)
            <section class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                    <div>
                        <h2 class="text-lg font-extrabold text-slate-950">Recovery Codes</h2>
                        <p class="mt-1 max-w-2xl text-sm leading-6 text-slate-600">
                            Simpan kode ini di tempat aman. Setiap kode hanya dapat dipakai satu kali dan akan dihapus setelah digunakan.
                        </p>
                    </div>
                    <form method="POST" action="{{ route('settings.security.two-factor.recovery-codes.regenerate') }}" class="flex w-full flex-col gap-3 md:w-80">
                        @csrf
                        <input type="password" name="password" class="mom-form-input" placeholder="Password saat ini" required autocomplete="current-password">
                        <button type="submit" class="rounded-lg border border-slate-200 bg-white px-4 py-3 text-sm font-extrabold text-slate-700 hover:bg-slate-50">
                            Regenerate Recovery Codes
                        </button>
                    </form>
                </div>

                <div class="mt-5 grid gap-3 sm:grid-cols-2 lg:grid-cols-5">
                    @foreach ($recoveryCodes as $code)
                        <div class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-3 text-center font-mono text-sm font-extrabold tracking-[0.12em] text-slate-800">
                            {{ $code }}
                        </div>
                    @endforeach
                </div>
            </section>
        @endif
    </main>
</body>
</html>
