<?php $__env->startSection('title', 'Pengaturan Admin - MomSpire'); ?>
<?php $__env->startSection('header_title', 'Pengaturan Admin'); ?>
<?php $__env->startSection('header_subtitle', 'Kelola profil akun admin, email, dan keamanan password.'); ?>

<?php $__env->startPush('head'); ?>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <style>
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

        .security-chip.enabled {
            background: rgba(0, 184, 148, 0.12);
            color: #00896e;
        }

        .security-chip.disabled {
            background: rgba(108, 117, 125, 0.12);
            color: #6c757d;
        }

        .security-chip.warning {
            background: rgba(255, 193, 7, 0.16);
            color: #b27a00;
        }

        .code-box {
            background: #f8fafc;
            border: 1px dashed rgba(15, 23, 42, 0.16);
            border-radius: 14px;
            padding: 1rem;
            word-break: break-word;
        }

        .session-card {
            border-radius: 18px;
            overflow: hidden;
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <ul class="mb-0 ps-3">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row g-4 mb-4 align-items-start">
        <div class="col-lg-4">
            <div class="ps-3">
                <h6 class="fw-semibold">Profile</h6>
                <p class="text-muted small">Perbarui informasi profil dan alamat email akun admin.</p>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="admin-card settings-section h-100">
                <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <h5 class="mb-0"><i class="bi bi-person-circle me-2"></i>Profil Admin</h5>
                    <span class="text-muted small">Kelola identitas akun yang dipakai login admin.</span>
                </div>
                <div class="card-body">
                    <form id="adminProfileForm" action="<?php echo e(route('admin.settings.profile')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" id="adminProfileNama" name="name" value="<?php echo e(old('name', $adminUser->name)); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" id="adminProfileEmail" name="email" value="<?php echo e(old('email', $adminUser->email)); ?>" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">No. Telepon</label>
                                <input type="tel" class="form-control" id="adminProfileTelp" name="no_telp" value="<?php echo e(old('no_telp', $adminUser->no_telp ?? '')); ?>" placeholder="08xxxxxxxxxx">
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
            <div class="admin-card settings-section h-100">
                <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <h5 class="mb-0"><i class="bi bi-shield-lock-fill me-2"></i>Ubah Password</h5>
                    <span class="text-muted small">Password baru otomatis berlaku ke semua sesi.</span>
                </div>
                <div class="card-body">
                    <form id="adminPasswordForm" action="<?php echo e(route('admin.settings.password')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="mb-3">
                            <label class="form-label">Password Saat Ini</label>
                            <input type="password" class="form-control" id="adminCurrentPassword" name="current_password" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password Baru</label>
                            <input type="password" class="form-control" id="adminNewPassword" name="new_password" minlength="8" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" class="form-control" id="adminConfirmPassword" name="new_password_confirmation" minlength="8" required>
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
            <div class="admin-card settings-section h-100">
                <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <h5 class="mb-0"><i class="bi bi-shield-check me-2"></i>Autentikasi Dua Faktor</h5>
                    <?php if($twoFactorEnabled): ?>
                        <?php if($twoFactorConfirmed): ?>
                            <span class="security-chip enabled">Aktif</span>
                        <?php else: ?>
                            <span class="security-chip warning">Aktif, belum dikonfirmasi</span>
                        <?php endif; ?>
                    <?php else: ?>
                        <span class="security-chip disabled">Belum aktif</span>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">Tambahkan lapisan keamanan ekstra dengan kode OTP dari aplikasi authenticator.</p>

                    <div id="twoFactorDisabledState" class="<?php echo e($twoFactorEnabled ? 'd-none' : ''); ?>">
                        <div class="alert alert-light border mb-3">
                            Fitur ini akan meminta password admin sebelum aktif, lalu menampilkan QR code untuk dipindai.
                        </div>
                        <button type="button" class="btn btn-primary-custom" id="enableTwoFactorBtn">Aktifkan 2FA</button>
                    </div>

                    <div id="twoFactorEnabledState" class="<?php echo e($twoFactorEnabled ? '' : 'd-none'); ?>">
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <button type="button" class="btn btn-outline-primary btn-sm" id="showTwoFactorQrBtn">Tampilkan QR Code</button>
                            <?php if($twoFactorEnabled && ! $twoFactorConfirmed): ?>
                                <button type="button" class="btn btn-outline-primary btn-sm" id="resetTwoFactorSetupBtn">Buat QR Baru</button>
                            <?php endif; ?>
                            <button type="button" class="btn btn-outline-secondary btn-sm" id="showRecoveryCodesBtn">Lihat Recovery Codes</button>
                            <button type="button" class="btn btn-outline-warning btn-sm" id="regenerateRecoveryCodesBtn">Regenerasi Codes</button>
                            <button type="button" class="btn btn-outline-danger btn-sm" id="disableTwoFactorBtn">Nonaktifkan</button>
                        </div>

                        <div id="twoFactorStatusBox" class="alert <?php echo e($twoFactorConfirmed ? 'alert-success' : 'alert-warning'); ?> mb-3">
                            <div class="fw-semibold"><?php echo e($twoFactorConfirmed ? '2FA sudah aktif.' : '2FA sedang menunggu konfirmasi.'); ?></div>
                            <div class="small mb-0">Gunakan QR code atau setup key untuk menautkan aplikasi authenticator.</div>
                        </div>

                        <div id="twoFactorConfirmBox" class="<?php echo e($requiresTwoFactorConfirmation && $twoFactorEnabled && ! $twoFactorConfirmed ? '' : 'd-none'); ?>">
                            <div class="alert alert-warning mb-3">
                                Aktifkan 2FA selesai, lalu masukkan kode OTP untuk konfirmasi terakhir.
                            </div>
                            <label class="form-label">Kode OTP</label>
                            <input type="text" class="form-control" id="twoFactorOtpCode" inputmode="numeric" autocomplete="one-time-code" placeholder="123456">
                            <button type="button" class="btn btn-primary-custom mt-3" id="confirmTwoFactorBtn">Konfirmasi 2FA</button>
                        </div>

                        <div id="twoFactorQrArea" class="mt-3 d-none">
                            <div class="code-box" id="twoFactorQrCode"></div>
                            <div class="code-box mt-3 d-none" id="twoFactorSetupKey"></div>
                        </div>

                        <div id="twoFactorRecoveryArea" class="mt-3 d-none">
                            <div class="fw-semibold mb-2">Recovery Codes</div>
                            <div class="code-box">
                                <div id="twoFactorRecoveryCodes" class="font-monospace small"></div>
                            </div>
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
            <div class="admin-card settings-section session-card h-100">
                <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <h5 class="mb-0"><i class="bi bi-pc-display-horizontal me-2"></i>Browser Sessions</h5>
                    <span class="text-muted small">Lihat perangkat yang sedang login ke akun admin.</span>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">Jika perlu, keluarkan sesi lain dari perangkat berbeda. Data ini muncul dari tabel sessions Laravel.</p>

                    <?php if(count($browserSessions) > 0): ?>
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
                                    <?php $__currentLoopData = $browserSessions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $session): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td>
                                                <div class="fw-semibold">
                                                    <?php echo e($session['agent']['platform'] ?: 'Unknown'); ?> - <?php echo e($session['agent']['browser'] ?: 'Unknown'); ?>

                                                </div>
                                                <div class="text-muted small"><?php echo e($session['agent']['is_desktop'] ? 'Desktop' : 'Mobile'); ?></div>
                                            </td>
                                            <td><?php echo e($session['ip_address'] ?? '-'); ?></td>
                                            <td>
                                                <?php if($session['is_current_device']): ?>
                                                    <span class="badge text-bg-success">Perangkat ini</span>
                                                <?php else: ?>
                                                    <span class="badge text-bg-secondary">Sesi aktif</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo e($session['last_active'] ?? '-'); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex flex-wrap gap-2 mt-3">
                            <button type="button" class="btn btn-outline-danger" id="logoutOtherSessionsBtn">Logout Perangkat Lain</button>
                            <span class="text-muted small align-self-center">Butuh password admin untuk konfirmasi.</span>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-light border mb-0">
                            Tidak ada data sesi yang bisa ditampilkan saat ini.
                        </div>
                    <?php endif; ?>
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
                        <p class="text-muted mb-3" id="securityConfirmModalText">Masukkan password admin untuk melanjutkan.</p>
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
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    window.MOMSPIRE_ADMIN_SETTINGS = {
        csrfToken: <?php echo json_encode(csrf_token(), 15, 512) ?>,
        passwordConfirmationUrl: <?php echo json_encode(route('password.confirmation'), 15, 512) ?>,
        passwordConfirmUrl: <?php echo json_encode(route('password.confirm.store'), 15, 512) ?>,
        twoFactorEnableUrl: <?php echo json_encode(route('settings.security.two-factor.enable'), 15, 512) ?>,
        twoFactorConfirmUrl: <?php echo json_encode(route('settings.security.two-factor.confirm'), 15, 512) ?>,
        twoFactorDisableUrl: <?php echo json_encode(route('settings.security.two-factor.disable'), 15, 512) ?>,
        twoFactorQrCodeUrl: <?php echo json_encode(route('settings.security.two-factor.qr-code'), 15, 512) ?>,
        twoFactorSecretKeyUrl: <?php echo json_encode(route('settings.security.two-factor.secret-key'), 15, 512) ?>,
        twoFactorRecoveryCodesUrl: <?php echo json_encode(route('settings.security.two-factor.recovery-codes'), 15, 512) ?>,
        otherBrowserSessionsUrl: <?php echo json_encode(route('other-browser-sessions.destroy'), 15, 512) ?>,
        requiresTwoFactorConfirmation: <?php echo json_encode($requiresTwoFactorConfirmation, 15, 512) ?>,
        twoFactorEnabled: <?php echo json_encode($twoFactorEnabled, 15, 512) ?>,
        twoFactorConfirmed: <?php echo json_encode($twoFactorConfirmed, 15, 512) ?>,
    };

    document.addEventListener('DOMContentLoaded', function() {
        const settings = window.MOMSPIRE_ADMIN_SETTINGS || {};

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
            if (confirmText) confirmText.textContent = options.text || 'Masukkan password admin untuk melanjutkan.';
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
                const response = await window.axios.get(settings.passwordConfirmationUrl);
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
                const response = await window.axios.post(settings.passwordConfirmUrl,
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
            const response = await window.axios.get(settings.twoFactorQrCodeUrl);
            if (twoFactorQrCode && response.data && response.data.svg) {
                twoFactorQrCode.innerHTML = response.data.svg;
            }

            if (twoFactorSetupKey) {
                const secretResponse = await window.axios.get(settings.twoFactorSecretKeyUrl);
                twoFactorSetupKey.innerHTML = `<strong>Setup Key:</strong> <span class="font-monospace">${secretResponse.data?.secretKey || ''}</span>`;
                twoFactorSetupKey.classList.remove('d-none');
            }

            if (twoFactorQrArea) {
                twoFactorQrArea.classList.remove('d-none');
            }
        }

        async function fetchRecoveryCodes() {
            const response = await window.axios.get(settings.twoFactorRecoveryCodesUrl);
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
            if (twoFactorStatusBox) {
                twoFactorStatusBox.classList.remove('alert-secondary', 'alert-warning');
                twoFactorStatusBox.classList.add('alert-success');
                twoFactorStatusBox.innerHTML = '<div class="fw-semibold">2FA sudah aktif.</div><div class="small mb-0">Gunakan QR code atau recovery code untuk login dengan lebih aman.</div>';
            }
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
                const response = await window.axios.post(settings.twoFactorEnableUrl, { password });
                syncCsrfToken(response.data?.csrfToken);
                markTwoFactorEnabledState();
                if (settings.requiresTwoFactorConfirmation) {
                    markTwoFactorNeedsConfirmation();
                }
                await Promise.all([fetchQrCode(), fetchRecoveryCodes()]);
                setTwoFactorStatus('warning', 'QR 2FA terbaru sudah dibuat.', 'Hapus entry MomSpire lama di authenticator, lalu scan QR code terbaru dan masukkan OTP-nya.');
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
                const response = await window.axios.post(settings.twoFactorConfirmUrl, { code });
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
                const response = await window.axios.delete(settings.twoFactorDisableUrl, { data: { password } });
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
            const response = await window.axios.post(settings.twoFactorRecoveryCodesUrl, { password });
            syncCsrfToken(response.data?.csrfToken);
            await fetchRecoveryCodes();
        }

        async function logoutOtherSessions() {
            if (!logoutOtherSessionsBtn) return;

            setLoadingState(logoutOtherSessionsBtn, true, 'Logout...');

            try {
                await window.axios.delete(settings.otherBrowserSessionsUrl, {
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
                requirePasswordConfirmation({
                    title: 'Aktifkan 2FA',
                    text: 'Masukkan password admin untuk mengaktifkan autentikasi dua faktor.',
                    onConfirmed: enableTwoFactor,
                });
            });
        }

        if (confirmTwoFactorBtn) {
            confirmTwoFactorBtn.addEventListener('click', confirmTwoFactor);
        }

        if (disableTwoFactorBtn) {
            disableTwoFactorBtn.addEventListener('click', function() {
                requirePasswordConfirmation({
                    title: 'Nonaktifkan 2FA',
                    text: 'Masukkan password admin untuk menonaktifkan autentikasi dua faktor.',
                    onConfirmed: disableTwoFactor,
                });
            });
        }

        if (showTwoFactorQrBtn) {
            showTwoFactorQrBtn.addEventListener('click', function() {
                requirePasswordConfirmation({
                    title: 'Tampilkan QR Code',
                    text: 'Masukkan password admin untuk melihat QR code 2FA.',
                    onConfirmed: showTwoFactorCodes,
                });
            });
        }

        if (resetTwoFactorSetupBtn) {
            resetTwoFactorSetupBtn.addEventListener('click', function() {
                requirePasswordConfirmation({
                    title: 'Buat QR 2FA Baru',
                    text: 'Masukkan password admin. Secret pending akan diganti, jadi scan ulang QR baru di authenticator.',
                    onConfirmed: resetTwoFactorSetup,
                });
            });
        }

        if (showRecoveryCodesBtn) {
            showRecoveryCodesBtn.addEventListener('click', function() {
                requirePasswordConfirmation({
                    title: 'Lihat Recovery Codes',
                    text: 'Masukkan password admin untuk melihat recovery codes.',
                    onConfirmed: fetchRecoveryCodes,
                });
            });
        }

        if (regenerateRecoveryCodesBtn) {
            regenerateRecoveryCodesBtn.addEventListener('click', function() {
                requirePasswordConfirmation({
                    title: 'Regenerasi Recovery Codes',
                    text: 'Masukkan password admin untuk membuat recovery codes baru.',
                    onConfirmed: reloadRecoveryCodes,
                });
            });
        }

        if (logoutOtherSessionsBtn) {
            logoutOtherSessionsBtn.addEventListener('click', function() {
                requirePasswordConfirmation({
                    title: 'Logout Perangkat Lain',
                    text: 'Masukkan password admin untuk mengeluarkan sesi di perangkat lain.',
                    onConfirmed: logoutOtherSessions,
                });
            });
        }
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('Admin.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\MomSpire\resources\views/Admin/PengaturanAdmin.blade.php ENDPATH**/ ?>