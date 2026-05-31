@extends('pengguna.master')

@section('title', 'Pengaturan Akun - MomSpire')
@section('header_title', 'Pengaturan')

@push('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        /* background dari halaman Artikel Edukasi / Konsultasi pengguna */
        .pengguna-dashboard-shell {
            position: relative;
            isolation: isolate;
            background: linear-gradient(180deg, #ffffff 0%, #f0f4f8 100%);
            min-height: 100vh;
        }

        .pengguna-dashboard-shell::before {
            content: '';
            position: fixed;
            inset: 0;
            background:
                radial-gradient(circle at 12% 8%, rgba(230, 57, 128, 0.15), transparent 28%),
                radial-gradient(circle at 92% 14%, rgba(107, 66, 193, 0.12), transparent 26%),
                radial-gradient(circle at 20% 80%, rgba(0, 184, 148, 0.08), transparent 32%);
            z-index: -2;
            pointer-events: none;
        }

        .pengguna-dashboard-shell::after {
            content: '';
            position: fixed;
            inset: 0;
            background-image: linear-gradient(rgba(15, 23, 42, 0.015) 1px, transparent 1px), linear-gradient(90deg, rgba(15, 23, 42, 0.015) 1px, transparent 1px);
            background-size: 42px 42px;
            opacity: .3;
            pointer-events: none;
            z-index: -1;
        }

        /* admin-like settings card (diambil dari Admin Pengaturan) */
        .settings-section {
            border: 1px solid rgba(15, 23, 42, 0.08);
            border-radius: 18px;
            background: #ffffff;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.04);
        }

        .settings-section .card-header {
            background: transparent;
            border-bottom: 1px solid rgba(15, 23, 42, 0.06);
            padding: 1.2rem 1.35rem;
        }

        .settings-section .card-body {
            padding: 1.35rem;
        }

        .security-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.4rem 0.75rem;
            border-radius: 999px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .security-chip.enabled { background: rgba(0, 184, 148, 0.12); color: #00896e; }
        .security-chip.disabled { background: rgba(108, 117, 125, 0.12); color: #6c757d; }
        .security-chip.warning { background: rgba(255, 193, 7, 0.16); color: #b27a00; }

        .code-box { background: #f8fafc; border: 1px dashed rgba(15, 23, 42, 0.16); border-radius: 14px; padding: 1rem; word-break: break-word; }
        .session-card { border-radius: 18px; overflow: hidden; }
    </style>
@endpush

@section('content')
    @php
        $pengguna = $pengguna ?? auth()->user();
        $twoFactorEnabled = $twoFactorEnabled ?? false;
        $twoFactorConfirmed = $twoFactorConfirmed ?? false;
        $twoFactorPending = $twoFactorPending ?? ($twoFactorEnabled && !$twoFactorConfirmed);
        $requiresTwoFactorConfirmation = $requiresTwoFactorConfirmation ?? false;
        $browserSessions = $browserSessions ?? [];
        $hasCustomPassword = $hasCustomPassword ?? false;
        $googleSsoUser = $googleSsoUser ?? false;
    @endphp

    <div class="pengguna-dashboard-shell">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-4 mb-4 align-items-start">
            <div class="col-lg-4">
                <div class="ps-3">
                    <h6 class="fw-semibold">Profile</h6>
                    <p class="text-muted small">Perbarui informasi profil dan alamat email akun Anda.</p>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="settings-section h-100">
                    <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <h5 class="mb-0"><i class="bi bi-person-circle me-2"></i>Profil Pengguna</h5>
                        <span class="text-muted small">Kelola identitas akun yang dipakai login.</span>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('pengguna.settings.profile') }}">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control" name="name" value="{{ old('name', $pengguna->name ?? '') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" name="email" value="{{ old('email', $pengguna->email ?? '') }}" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">No. Telepon</label>
                                    <input type="tel" class="form-control" name="no_telp" value="{{ old('no_telp', $pengguna->no_telp ?? '') }}" placeholder="08xxxxxxxxxx">
                                </div>
                            </div>
                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary-custom">Simpan Profil</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4 align-items-start">
            <div class="col-lg-4">
                <div class="ps-3">
                    <h6 class="fw-semibold">Ubah Password</h6>
                    <p class="text-muted small">Gunakan password kuat agar akun tetap aman.</p>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="settings-section h-100">
                    <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <h5 class="mb-0"><i class="bi bi-shield-lock-fill me-2"></i>Ubah Password</h5>
                        <span class="text-muted small">Password baru akan menggantikan yang lama.</span>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('pengguna.settings.password') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Password Saat Ini</label>
                                <input type="password" class="form-control" name="current_password" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password Baru</label>
                                <input type="password" class="form-control" name="new_password" minlength="8" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Konfirmasi Password Baru</label>
                                <input type="password" class="form-control" name="new_password_confirmation" minlength="8" required>
                            </div>
                            <button type="submit" class="btn btn-primary-custom">Ubah Password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4 align-items-start">
            <div class="col-lg-4">
                <div class="ps-3">
                    <h6 class="fw-semibold">Autentikasi Dua Faktor</h6>
                    <p class="text-muted small">Tambahkan lapisan keamanan ekstra menggunakan 2FA.</p>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="settings-section h-100">
                    <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <h5 class="mb-0"><i class="bi bi-shield-check me-2"></i>Autentikasi Dua Faktor</h5>
                        @if($twoFactorEnabled)
                            @if($twoFactorConfirmed)
                                <span class="security-chip enabled">Aktif</span>
                            @else
                                <span class="security-chip warning">Aktif, belum dikonfirmasi</span>
                            @endif
                        @else
                            <span class="security-chip disabled">Belum aktif</span>
                        @endif
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-3">Tambahkan lapisan keamanan ekstra dengan kode OTP dari aplikasi authenticator.</p>

                        <div id="twoFactorDisabledState" class="{{ $twoFactorEnabled ? 'd-none' : '' }}">
                            @if($googleSsoUser && !$hasCustomPassword)
                                <div class="alert alert-warning border mb-3">
                                    <strong>Password belum diset.</strong> Akun Google SSO perlu mengatur password terlebih dahulu sebelum bisa mengaktifkan 2FA.
                                </div>
                                <a href="{{ route('settings.set-password') }}" class="btn btn-warning">
                                    <i class="bi bi-key me-1"></i> Atur Password Dulu
                                </a>
                            @else
                                <div class="alert alert-light border mb-3">Fitur ini akan meminta password sebelum aktif, lalu menampilkan QR code untuk dipindai.</div>
                                <button type="button" class="btn btn-primary-custom" id="enableTwoFactorBtn">Aktifkan 2FA</button>
                            @endif
                        </div>

                        <div id="twoFactorEnabledState" class="{{ $twoFactorEnabled ? '' : 'd-none' }}">
                            <div class="d-flex flex-wrap gap-2 mb-3">
                                <button type="button" class="btn btn-outline-primary btn-sm" id="showTwoFactorQrBtn">Tampilkan QR Code</button>
                                @if($twoFactorEnabled && ! $twoFactorConfirmed)
                                    <button type="button" class="btn btn-outline-primary btn-sm" id="resetTwoFactorSetupBtn">Buat QR Baru</button>
                                @endif
                                <button type="button" class="btn btn-outline-secondary btn-sm" id="showRecoveryCodesBtn">Lihat Recovery Codes</button>
                                <button type="button" class="btn btn-outline-warning btn-sm" id="regenerateRecoveryCodesBtn">Regenerasi Codes</button>
                                <button type="button" class="btn btn-outline-danger btn-sm" id="disableTwoFactorBtn">Nonaktifkan</button>
                            </div>

                            <div id="twoFactorConfirmBox" class="{{ ($twoFactorEnabled && !$twoFactorConfirmed) || ($twoFactorEnabled && $twoFactorPending) ? '' : 'd-none' }} mb-3">
                                <div class="row g-2 align-items-end">
                                    <div class="col-md-8">
                                        <label class="form-label">Kode OTP</label>
                                        <input type="text" class="form-control" id="twoFactorOtpCode" placeholder="Masukkan kode dari authenticator" maxlength="6">
                                    </div>
                                    <div class="col-md-4">
                                        <button type="button" class="btn btn-primary-custom w-100" id="confirmTwoFactorBtn">Konfirmasi 2FA</button>
                                    </div>
                                </div>
                                <div class="form-text">Masukkan 6 digit kode dari aplikasi Google Authenticator</div>
                            </div>

                            <div id="twoFactorStatusBox" class="alert {{ $twoFactorConfirmed ? 'alert-success' : 'alert-warning' }} mb-3">
                                <div class="fw-semibold">{{ $twoFactorConfirmed ? '2FA sudah aktif.' : '2FA sedang menunggu konfirmasi.' }}</div>
                                <div class="small mb-0">Gunakan QR code atau setup key untuk menautkan aplikasi authenticator.</div>
                            </div>

                            <div id="twoFactorQrArea" class="d-none mb-3">
                                <div class="code-box mb-3" id="twoFactorQrCode"></div>
                                <div class="code-box d-none" id="twoFactorSetupKey"></div>
                            </div>

                            <div id="twoFactorRecoveryArea" class="d-none">
                                <h6 class="fw-semibold mb-2">Recovery Codes</h6>
                                <div class="code-box" id="twoFactorRecoveryCodes"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4 align-items-start">
            <div class="col-lg-4">
                <div class="ps-3">
                    <h6 class="fw-semibold">Browser Sessions</h6>
                    <p class="text-muted small">Kelola sesi aktif dari perangkat lain.</p>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="settings-section session-card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <h5 class="mb-0"><i class="bi bi-pc-display-horizontal me-2"></i>Browser Sessions</h5>
                        <span class="text-muted small">Lihat perangkat yang sedang login ke akun Anda.</span>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-3">Jika perlu, keluarkan sesi lain dari perangkat berbeda.</p>

                        @if(count($browserSessions) > 0)
                            <div class="table-responsive">
                                <table class="table align-middle">
                                    <thead>
                                        <tr>
                                            <th>Perangkat</th>
                                            <th>IP</th>
                                            <th>Status</th>
                                            <th>Terakhir Aktif</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($browserSessions as $session)
                                            <tr>
                                                <td>
                                                    <div class="fw-semibold">{{ $session['agent']['platform'] ?: 'Unknown' }} - {{ $session['agent']['browser'] ?: 'Unknown' }}</div>
                                                    <div class="text-muted small">{{ $session['agent']['is_desktop'] ? 'Desktop' : 'Mobile' }}</div>
                                                </td>
                                                <td>{{ $session['ip_address'] ?? '-' }}</td>
                                                <td>
                                                    @if($session['is_current_device'])
                                                        <span class="badge text-bg-success">Perangkat ini</span>
                                                    @else
                                                        <span class="badge text-bg-secondary">Sesi aktif</span>
                                                    @endif
                                                </td>
                                                <td>{{ $session['last_active'] ?? '-' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-flex flex-wrap gap-2 mt-3">
                                <button type="button" class="btn btn-outline-danger" id="logoutOtherSessionsBtn">Logout Perangkat Lain</button>
                                <span class="text-muted small align-self-center">Butuh password untuk konfirmasi.</span>
                            </div>
                        @else
                            <div class="alert alert-light border mb-0">Tidak ada data sesi yang bisa ditampilkan saat ini.</div>
                            <div class="d-flex flex-wrap gap-2 mt-3">
                                <button type="button" class="btn btn-outline-danger" id="logoutOtherSessionsBtn">Logout Perangkat Lain</button>
                                <span class="text-muted small align-self-center">Butuh password untuk konfirmasi.</span>
                            </div>
                        @endif

                        @if(config('session.driver') !== 'database')
                            <div class="alert alert-warning border mt-3 mb-0">
                                Session driver saat ini belum database, sehingga daftar browser sessions tidak bisa ditampilkan.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="securityConfirmModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="securityConfirmModalTitle">Konfirmasi Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="securityConfirmForm">
                    <div class="modal-body">
                        <p class="text-muted mb-3" id="securityConfirmModalText">Masukkan password untuk melanjutkan.</p>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control" id="securityConfirmPassword" autocomplete="current-password" required>
                            <div class="text-danger small mt-2 d-none" id="securityConfirmError"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary-custom" id="securityConfirmSubmitBtn">Lanjutkan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    window.MOMSPIRE_PENGATURAN_PENGGUNA = {
        csrfToken: @json(csrf_token()),
        passwordConfirmationUrl: @json(route('password.confirmation')),
        passwordConfirmUrl: @json(route('password.confirm.store')),
        twoFactorEnableUrl: @json(route('settings.security.two-factor.enable')),
        twoFactorConfirmUrl: @json(route('settings.security.two-factor.confirm')),
        twoFactorDisableUrl: @json(route('settings.security.two-factor.disable')),
        twoFactorQrCodeUrl: @json(route('settings.security.two-factor.qr-code')),
        twoFactorSecretKeyUrl: @json(route('settings.security.two-factor.secret-key')),
        twoFactorRecoveryCodesUrl: @json(route('settings.security.two-factor.recovery-codes')),
        otherBrowserSessionsUrl: @json(route('other-browser-sessions.destroy')),
        requiresTwoFactorConfirmation: @json($requiresTwoFactorConfirmation),
        twoFactorEnabled: @json($twoFactorEnabled),
        twoFactorConfirmed: @json($twoFactorConfirmed),
        hasCustomPassword: @json($hasCustomPassword),
        googleSsoUser: @json($googleSsoUser),
        setPasswordUrl: @json(route('settings.set-password')),
    };

    document.addEventListener('DOMContentLoaded', function() {
        const settings = window.MOMSPIRE_PENGATURAN_PENGGUNA || {};
        const http = createHttpClient(settings);

        if (window.axios && settings.csrfToken) {
            window.axios.defaults.headers.common['X-CSRF-TOKEN'] = settings.csrfToken;
        }

        const confirmModalEl = document.getElementById('securityConfirmModal');
        const confirmModal = confirmModalEl ? new bootstrap.Modal(confirmModalEl) : null;
        const confirmForm = document.getElementById('securityConfirmForm');
        const confirmTitle = document.getElementById('securityConfirmModalTitle');
        const confirmText = document.getElementById('securityConfirmModalText');
        const confirmPassword = document.getElementById('securityConfirmPassword');
        const confirmError = document.getElementById('securityConfirmError');
        const confirmSubmitBtn = document.getElementById('securityConfirmSubmitBtn');

        const twoFactorDisabledState = document.getElementById('twoFactorDisabledState');
        const twoFactorEnabledState = document.getElementById('twoFactorEnabledState');
        const twoFactorConfirmBox = document.getElementById('twoFactorConfirmBox');
        const twoFactorOtpCode = document.getElementById('twoFactorOtpCode');
        const twoFactorStatusBox = document.getElementById('twoFactorStatusBox');
        const twoFactorQrArea = document.getElementById('twoFactorQrArea');
        const twoFactorQrCode = document.getElementById('twoFactorQrCode');
        const twoFactorSetupKey = document.getElementById('twoFactorSetupKey');
        const twoFactorRecoveryArea = document.getElementById('twoFactorRecoveryArea');
        const twoFactorRecoveryCodes = document.getElementById('twoFactorRecoveryCodes');

        const enableTwoFactorBtn = document.getElementById('enableTwoFactorBtn');
        const confirmTwoFactorBtn = document.getElementById('confirmTwoFactorBtn');
        const disableTwoFactorBtn = document.getElementById('disableTwoFactorBtn');
        const showTwoFactorQrBtn = document.getElementById('showTwoFactorQrBtn');
        const resetTwoFactorSetupBtn = document.getElementById('resetTwoFactorSetupBtn');
        const showRecoveryCodesBtn = document.getElementById('showRecoveryCodesBtn');
        const regenerateRecoveryCodesBtn = document.getElementById('regenerateRecoveryCodesBtn');
        const logoutOtherSessionsBtn = document.getElementById('logoutOtherSessionsBtn');

        let pendingConfirmedAction = null;

        function createHttpClient(settings) {
            if (window.axios) {
                return window.axios;
            }

            const request = async (method, url, data = null, options = {}) => {
                const headers = {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': settings.csrfToken || document.head.querySelector('meta[name="csrf-token"]')?.content || '',
                    ...(options.headers || {}),
                };

                const response = await fetch(url, {
                    method,
                    credentials: 'same-origin',
                    headers: {
                        ...headers,
                        ...(data ? { 'Content-Type': 'application/json' } : {}),
                    },
                    body: data ? JSON.stringify(data) : undefined,
                });

                const body = await response.json().catch(() => ({}));

                if (!response.ok) {
                    throw { response: { status: response.status, data: body } };
                }

                return { data: body };
            };

            return {
                get: (url, options = {}) => request('GET', url, null, options),
                post: (url, data = {}, options = {}) => request('POST', url, data, options),
                delete: (url, options = {}) => request('DELETE', url, options.data || null, options),
            };
        }

        function setLoadingState(button, isLoading, text) {
            if (!button) return;

            button.disabled = isLoading;
            if (text) {
                button.dataset.originalText = button.dataset.originalText || button.textContent;
                button.textContent = isLoading ? text : button.dataset.originalText;
            } else if (!isLoading && button.dataset.originalText) {
                button.textContent = button.dataset.originalText;
            }
        }

        function setConfirmError(message) {
            if (!confirmError) return;
            confirmError.textContent = message || '';
            confirmError.classList.toggle('d-none', !message);
        }

        function syncCsrfToken(token) {
            if (!token) return;

            settings.csrfToken = token;

            const meta = document.head.querySelector('meta[name="csrf-token"]');
            if (meta) {
                meta.setAttribute('content', token);
            }

            if (window.axios) {
                window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token;
            }
        }

        function openPasswordModal(options) {
            pendingConfirmedAction = options.onConfirmed || null;
            if (confirmTitle) confirmTitle.textContent = options.title || 'Konfirmasi Password';
            if (confirmText) confirmText.textContent = options.text || 'Masukkan password untuk melanjutkan.';
            if (confirmPassword) confirmPassword.value = '';
            setConfirmError('');
            if (confirmModal) confirmModal.show();
            setTimeout(() => confirmPassword && confirmPassword.focus(), 150);
        }

        function hidePasswordModal() {
            if (confirmModal) confirmModal.hide();
        }

        async function passwordConfirmed() {
            try {
                const response = await http.get(settings.passwordConfirmationUrl);
                return Boolean(response.data && response.data.confirmed);
            } catch (error) {
                return false;
            }
        }

        function requirePasswordConfirmation(options) {
            openPasswordModal(options);
        }

        async function confirmPasswordSubmit(event) {
            event.preventDefault();

            setConfirmError('');
            setLoadingState(confirmSubmitBtn, true, 'Memproses...');

            try {
                const csrfToken = settings.csrfToken || document.head.querySelector('meta[name="csrf-token"]')?.content || '';
                const response = await http.post(settings.passwordConfirmUrl,
                    { password: confirmPassword ? confirmPassword.value : '' },
                    {
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                        },
                    }
                );
                syncCsrfToken(response.data?.csrfToken);

                hidePasswordModal();

                if (typeof pendingConfirmedAction === 'function') {
                    const callback = pendingConfirmedAction;
                    pendingConfirmedAction = null;
                    callback(confirmPassword ? confirmPassword.value : '');
                }
            } catch (error) {
                const message = !error?.response
                    ? 'Komponen keamanan belum siap. Refresh halaman lalu coba lagi.'
                    : (error.response.status === 419
                        ? 'Sesi halaman kedaluwarsa. Refresh halaman lalu coba lagi.'
                        : (error?.response?.data?.errors?.password?.[0] || error?.response?.data?.message || 'Password tidak valid.'));
                setConfirmError(message);
            } finally {
                setLoadingState(confirmSubmitBtn, false);
            }
        }

        async function fetchQrCode() {
            const response = await http.get(settings.twoFactorQrCodeUrl);
            if (twoFactorQrCode && response.data && response.data.svg) {
                twoFactorQrCode.innerHTML = response.data.svg;
            }

            if (twoFactorSetupKey) {
                const secretResponse = await http.get(settings.twoFactorSecretKeyUrl);
                twoFactorSetupKey.innerHTML = `<strong>Setup Key:</strong> <span class="font-monospace">${secretResponse.data?.secretKey || ''}</span>`;
                twoFactorSetupKey.classList.remove('d-none');
            }

            if (twoFactorQrArea) {
                twoFactorQrArea.classList.remove('d-none');
            }
        }

        async function fetchRecoveryCodes() {
            const response = await http.get(settings.twoFactorRecoveryCodesUrl);
            if (twoFactorRecoveryCodes && Array.isArray(response.data)) {
                twoFactorRecoveryCodes.innerHTML = response.data.map((code) => `<div>${code}</div>`).join('');
            }

            if (twoFactorRecoveryArea) {
                twoFactorRecoveryArea.classList.remove('d-none');
            }
        }

        function markTwoFactorEnabledState() {
            if (twoFactorDisabledState) twoFactorDisabledState.classList.add('d-none');
            if (twoFactorEnabledState) twoFactorEnabledState.classList.remove('d-none');
            // Selalu tampilkan box konfirmasi setelah QR dibuat
            if (twoFactorConfirmBox) twoFactorConfirmBox.classList.remove('d-none');
            if (twoFactorStatusBox) {
                twoFactorStatusBox.classList.remove('alert-secondary', 'alert-warning', 'alert-success');
                twoFactorStatusBox.classList.add('alert-warning');
                twoFactorStatusBox.innerHTML = '<div class="fw-semibold">Scan QR code terlebih dahulu.</div><div class="small mb-0">Masukkan kode OTP dari aplikasi authenticator untuk menyelesaikan aktivasi.</div>';
            }
            // Auto-fetch QR dan recovery codes
            fetchQrCode();
            fetchRecoveryCodes();
        }

        function markTwoFactorDisabledState() {
            if (twoFactorDisabledState) twoFactorDisabledState.classList.remove('d-none');
            if (twoFactorEnabledState) twoFactorEnabledState.classList.add('d-none');
        }

        function markTwoFactorNeedsConfirmation() {
            if (twoFactorConfirmBox) twoFactorConfirmBox.classList.remove('d-none');
            if (twoFactorStatusBox) {
                twoFactorStatusBox.classList.remove('alert-success');
                twoFactorStatusBox.classList.add('alert-warning');
                twoFactorStatusBox.innerHTML = '<div class="fw-semibold">2FA sedang menunggu konfirmasi.</div><div class="small mb-0">Masukkan kode OTP dari authenticator untuk menyelesaikan aktivasi.</div>';
            }
        }

        function setTwoFactorStatus(type, title, message) {
            if (!twoFactorStatusBox) return;

            twoFactorStatusBox.classList.remove('alert-secondary', 'alert-warning', 'alert-success', 'alert-danger', 'alert-info');
            twoFactorStatusBox.classList.add(`alert-${type}`);
            twoFactorStatusBox.replaceChildren();

            const titleEl = document.createElement('div');
            titleEl.className = 'fw-semibold';
            titleEl.textContent = title;

            const messageEl = document.createElement('div');
            messageEl.className = 'small mb-0';
            messageEl.textContent = message;

            twoFactorStatusBox.append(titleEl, messageEl);
        }

        function twoFactorErrorMessage(error, fallback) {
            return error?.response?.data?.errors?.code?.[0]
                || error?.response?.data?.errors?.recovery_code?.[0]
                || error?.response?.data?.message
                || fallback;
        }

        async function enableTwoFactor(password) {
            setLoadingState(enableTwoFactorBtn, true, 'Mengaktifkan...');

            try {
                // Google SSO user yang sudah punya password - kirim password
                // User biasa - kirim password
                const payload = (settings.googleSsoUser && settings.hasCustomPassword) || !settings.googleSsoUser
                    ? { password }
                    : {};

                const response = await http.post(settings.twoFactorEnableUrl, payload);
                syncCsrfToken(response.data?.csrfToken);

                // Update UI state
                if (twoFactorDisabledState) twoFactorDisabledState.classList.add('d-none');
                if (twoFactorEnabledState) twoFactorEnabledState.classList.remove('d-none');
                // Tampilkan box konfirmasi OTP
                if (twoFactorConfirmBox) twoFactorConfirmBox.classList.remove('d-none');
                if (twoFactorStatusBox) {
                    twoFactorStatusBox.classList.remove('alert-secondary', 'alert-success');
                    twoFactorStatusBox.classList.add('alert-warning');
                    twoFactorStatusBox.innerHTML = '<div class="fw-semibold">Scan QR code terlebih dahulu.</div><div class="small mb-0">Masukkan kode OTP dari aplikasi authenticator untuk menyelesaikan aktivasi.</div>';
                }

                // Auto-fetch QR dan recovery codes
                await Promise.all([fetchQrCode(), fetchRecoveryCodes()]);

                // Focus ke input OTP
                if (twoFactorOtpCode) {
                    twoFactorOtpCode.value = '';
                    twoFactorOtpCode.focus();
                }
            } catch (error) {
                setTwoFactorStatus('danger', 'Gagal mengaktifkan 2FA.', error?.response?.data?.message || 'Terjadi kesalahan.');
            } finally {
                setLoadingState(enableTwoFactorBtn, false);
            }
        }

        async function resetTwoFactorSetup(password) {
            setLoadingState(resetTwoFactorSetupBtn, true, 'Membuat...');

            try {
                await enableTwoFactor(password);
            } finally {
                setLoadingState(resetTwoFactorSetupBtn, false);
            }
        }

        async function confirmTwoFactor() {
            if (!twoFactorOtpCode) return;

            const code = twoFactorOtpCode.value.trim();
            if (!code) {
                setTwoFactorStatus('warning', 'Kode OTP belum diisi.', 'Masukkan 6 digit kode dari aplikasi authenticator.');
                twoFactorOtpCode.focus();
                return;
            }

            setLoadingState(confirmTwoFactorBtn, true, 'Mengonfirmasi...');
            setTwoFactorStatus('info', 'Memverifikasi kode OTP...', 'Tunggu sebentar, sistem sedang memeriksa kode authenticator.');

            try {
                const response = await http.post(settings.twoFactorConfirmUrl, { code });
                syncCsrfToken(response.data?.csrfToken);
                if (twoFactorConfirmBox) twoFactorConfirmBox.classList.add('d-none');
                setTwoFactorStatus('success', '2FA berhasil dikonfirmasi.', 'Aktivasi sudah selesai dan siap digunakan saat login berikutnya.');
                twoFactorOtpCode.value = '';
            } catch (error) {
                setTwoFactorStatus('danger', 'Konfirmasi 2FA gagal.', twoFactorErrorMessage(error, 'Kode OTP tidak valid atau sudah kedaluwarsa.'));
                if (twoFactorConfirmBox) twoFactorConfirmBox.classList.remove('d-none');
                twoFactorOtpCode.focus();
                twoFactorOtpCode.select();
            } finally {
                setLoadingState(confirmTwoFactorBtn, false);
            }
        }

        async function disableTwoFactor(password) {
            setLoadingState(disableTwoFactorBtn, true, 'Menonaktifkan...');

            try {
                // Google SSO user yang sudah punya password - kirim password
                // User biasa - kirim password
                const payload = (settings.googleSsoUser && settings.hasCustomPassword) || !settings.googleSsoUser
                    ? { password }
                    : {};

                const response = await http.delete(settings.twoFactorDisableUrl, { data: payload });
                syncCsrfToken(response.data?.csrfToken);
                if (twoFactorQrArea) twoFactorQrArea.classList.add('d-none');
                if (twoFactorRecoveryArea) twoFactorRecoveryArea.classList.add('d-none');
                if (twoFactorConfirmBox) twoFactorConfirmBox.classList.add('d-none');
                markTwoFactorDisabledState();
            } finally {
                setLoadingState(disableTwoFactorBtn, false);
            }
        }

        async function showTwoFactorCodes() {
            await fetchQrCode();
            await fetchRecoveryCodes();
        }

        async function reloadRecoveryCodes(password) {
            // Google SSO user yang sudah punya password - kirim password
            // User biasa - kirim password
            const payload = (settings.googleSsoUser && settings.hasCustomPassword) || !settings.googleSsoUser
                ? { password }
                : {};

            const response = await http.post(settings.twoFactorRecoveryCodesUrl, payload);
            syncCsrfToken(response.data?.csrfToken);
            await fetchRecoveryCodes();
        }

        async function logoutOtherSessions() {
            if (!logoutOtherSessionsBtn) return;

            setLoadingState(logoutOtherSessionsBtn, true, 'Logout...');

            try {
                await http.delete(settings.otherBrowserSessionsUrl, {
                    data: {
                        password: confirmPassword ? confirmPassword.value : '',
                    },
                });
                window.location.reload();
            } finally {
                setLoadingState(logoutOtherSessionsBtn, false);
            }
        }

        if (confirmForm) {
            confirmForm.addEventListener('submit', confirmPasswordSubmit);
        }

        if (enableTwoFactorBtn) {
            enableTwoFactorBtn.addEventListener('click', function() {
                // Cek apakah user Google SSO yang belum punya password
                if (settings.googleSsoUser && !settings.hasCustomPassword) {
                    // Redirect ke halaman set password
                    window.location.href = settings.setPasswordUrl || '/settings/set-password';
                    return;
                }

                requirePasswordConfirmation({
                    title: 'Aktifkan 2FA',
                    text: 'Masukkan password untuk mengaktifkan autentikasi dua faktor.',
                    onConfirmed: enableTwoFactor,
                });
            });
        }

        if (confirmTwoFactorBtn) {
            confirmTwoFactorBtn.addEventListener('click', confirmTwoFactor);
        }

        // Enter key support untuk OTP input
        if (twoFactorOtpCode) {
            twoFactorOtpCode.addEventListener('keydown', function(event) {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    confirmTwoFactor();
                }
            });

            // Auto-format: hanya angka
            twoFactorOtpCode.addEventListener('input', function(event) {
                event.target.value = event.target.value.replace(/\D/g, '').slice(0, 6);
            });
        }

        if (disableTwoFactorBtn) {
            disableTwoFactorBtn.addEventListener('click', function() {
                requirePasswordConfirmation({
                    title: 'Nonaktifkan 2FA',
                    text: 'Masukkan password untuk menonaktifkan autentikasi dua faktor.',
                    onConfirmed: disableTwoFactor,
                });
            });
        }

        if (showTwoFactorQrBtn) {
            showTwoFactorQrBtn.addEventListener('click', function() {
                requirePasswordConfirmation({
                    title: 'Tampilkan QR Code',
                    text: 'Masukkan password untuk melihat QR code 2FA.',
                    onConfirmed: showTwoFactorCodes,
                });
            });
        }

        if (resetTwoFactorSetupBtn) {
            resetTwoFactorSetupBtn.addEventListener('click', function() {
                requirePasswordConfirmation({
                    title: 'Buat QR 2FA Baru',
                    text: 'Masukkan password. Secret pending akan diganti, jadi scan ulang QR baru di authenticator.',
                    onConfirmed: resetTwoFactorSetup,
                });
            });
        }

        if (showRecoveryCodesBtn) {
            showRecoveryCodesBtn.addEventListener('click', function() {
                requirePasswordConfirmation({
                    title: 'Lihat Recovery Codes',
                    text: 'Masukkan password untuk melihat recovery codes.',
                    onConfirmed: fetchRecoveryCodes,
                });
            });
        }

        if (regenerateRecoveryCodesBtn) {
            regenerateRecoveryCodesBtn.addEventListener('click', function() {
                requirePasswordConfirmation({
                    title: 'Regenerasi Recovery Codes',
                    text: 'Masukkan password untuk membuat recovery codes baru.',
                    onConfirmed: reloadRecoveryCodes,
                });
            });
        }

        if (logoutOtherSessionsBtn) {
            logoutOtherSessionsBtn.addEventListener('click', function() {
                requirePasswordConfirmation({
                    title: 'Logout Perangkat Lain',
                    text: 'Masukkan password untuk mengeluarkan sesi di perangkat lain.',
                    onConfirmed: logoutOtherSessions,
                });
            });
        }
    });
</script>
@endpush
