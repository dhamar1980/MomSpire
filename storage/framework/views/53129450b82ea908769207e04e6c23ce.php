<?php $__env->startSection('title', 'Detail Pengguna - MomSpire'); ?>
<?php $__env->startSection('header_title', 'Detail Pengguna'); ?>
<?php $__env->startSection('header_subtitle', 'Lihat dan kelola data pengguna yang sedang ditangani.'); ?>

<?php $__env->startPush('head'); ?>
<script>
    window.__MOMSPIRE_HEADER_TITLE_LOCK = 'Detail Pengguna';
</script>
<style>
    .bidan-detail-page {
        --detail-accent: rgba(214, 51, 108, 0.85);
        --detail-accent-2: rgba(139, 92, 246, 0.82);
        --detail-soft: rgba(255, 255, 255, 0.55);
        background:
            radial-gradient(circle at top left, rgba(214, 51, 108, 0.06), transparent 34%),
            radial-gradient(circle at top right, rgba(139, 92, 246, 0.05), transparent 30%),
            linear-gradient(180deg, rgba(248, 250, 252, 0.88) 0%, rgba(255, 255, 255, 0.94) 56%, rgba(248, 250, 252, 0.92) 100%);
        min-height: 100vh;
    }

    .bidan-detail-page .role-card {
        backdrop-filter: blur(14px);
        -webkit-backdrop-filter: blur(14px);
    }

    .bidan-detail-page .role-card::before {
        background: linear-gradient(90deg, rgba(214, 51, 108, 0.14) 0%, rgba(139, 92, 246, 0.12) 55%, rgba(214, 51, 108, 0.08) 100%);
        opacity: 0.38;
        height: 2px;
    }

    .bidan-detail-page .hero-role::before {
        display: none;
    }

    .bidan-detail-page .role-card:hover::before {
        opacity: 0.52;
        height: 2px;
    }

    .detail-hero {
        background: rgba(255, 255, 255, 0.82);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.9);
        border-radius: 20px;
        box-shadow:
            0 4px 24px rgba(0, 0, 0, 0.04),
            0 1px 2px rgba(0, 0, 0, 0.02),
            inset 0 1px 0 rgba(255, 255, 255, 0.8);
        position: relative;
        overflow: hidden;
    }

    .detail-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, #d6336c 0%, #6f42c1 50%, #00b894 100%);
        border-radius: 20px 20px 0 0;
    }

    .detail-hero::after {
        content: '';
        position: absolute;
        top: -100px;
        right: -100px;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(214, 51, 108, 0.04) 0%, transparent 70%);
        pointer-events: none;
    }

    .detail-hero .text-muted,
    .detail-hero p {
        color: rgba(100, 116, 139, 0.85) !important;
    }

    .detail-shell {
        padding: 0.25rem 0 1rem;
    }

    .detail-topbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .detail-heading {
        font-size: clamp(1.5rem, 2vw, 2rem);
        font-weight: 700;
        letter-spacing: -0.02em;
        color: #1e293b;
        position: relative;
        z-index: 1;
    }

    .detail-heading span,
    .detail-heading .text-gradient {
        background: var(--gradient-primary);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .detail-subtitle {
        max-width: 46rem;
        color: rgba(100, 116, 139, 0.9);
        position: relative;
        z-index: 1;
    }

    .pengguna-card {
        border-radius: 18px;
        transition: transform 0.22s ease, box-shadow 0.22s ease, border-color 0.22s ease;
        background: linear-gradient(135deg, rgba(230, 57, 128, 0.04) 0%, rgba(107, 66, 193, 0.03) 100%);
        border: 1px solid rgba(230, 57, 128, 0.1);
        box-shadow: 0 4px 20px rgba(230, 57, 128, 0.06);
    }

    .pengguna-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 30px rgba(230, 57, 128, 0.1);
    }

    .pengguna-row {
        border: 1px solid rgba(148, 163, 184, 0.18);
        border-radius: 16px;
        padding: 1.2rem;
        background: rgba(255, 255, 255, 0.72);
        backdrop-filter: blur(10px);
        transition: transform 0.22s ease, box-shadow 0.22s ease, border-color 0.22s ease, background 0.22s ease;
    }

    .pengguna-row:hover {
        background: rgba(255, 255, 255, 0.82);
        border-color: rgba(214, 51, 108, 0.14);
        box-shadow: 0 12px 26px rgba(15, 23, 42, 0.05);
        transform: translateY(-1px);
    }

    .pengguna-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1rem;
    }

    .pengguna-name {
        font-size: 1.05rem;
        font-weight: 700;
        color: #0f172a;
        line-height: 1.3;
    }

    .pengguna-info {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-top: 0.5rem;
    }

    .info-item {
        display: flex;
        flex-direction: column;
    }

    .info-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        color: #64748b;
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .info-value {
        font-size: 0.95rem;
        color: #334155;
    }

    .status-badge {
        display: inline-block;
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .status-hamil {
        background: rgba(214, 51, 108, 0.08);
        color: #8f1f4d;
        border: 1px solid rgba(214, 51, 108, 0.1);
    }

    .status-tidak {
        background: rgba(100, 116, 139, 0.05);
        color: #475569;
        border: 1px solid rgba(100, 116, 139, 0.08);
    }

    .pengguna-count {
        font-size: 0.9rem;
        color: #64748b;
        font-weight: 600;
    }

    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
        color: #64748b;
    }

    .empty-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
        color: var(--detail-accent);
        opacity: 0.72;
    }

    .detail-search .form-control {
        border-radius: 999px;
        border-color: rgba(148, 163, 184, 0.22);
        padding-left: 1rem;
        background: rgba(255, 255, 255, 0.76);
        box-shadow: none;
    }

    .detail-search .form-control:focus {
        border-color: rgba(214, 51, 108, 0.22);
        box-shadow: 0 0 0 0.2rem rgba(214, 51, 108, 0.05);
    }

    .btn-role-primary,
    .btn-role-outline {
        border-radius: 999px;
        font-weight: 600;
        padding: 0.55rem 1rem;
        transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease, background 0.2s ease;
    }

    .btn-role-primary {
        background: rgba(255, 255, 255, 0.7);
        border: 1px solid rgba(214, 51, 108, 0.16);
        color: #8f1f4d;
        box-shadow: 0 10px 22px rgba(15, 23, 42, 0.04);
    }

    .btn-role-primary:hover {
        color: #8f1f4d;
        transform: translateY(-1px);
        background: rgba(255, 255, 255, 0.84);
        box-shadow: 0 14px 28px rgba(15, 23, 42, 0.06);
    }

    .btn-role-outline {
        border: 1px solid rgba(148, 163, 184, 0.18);
        background: rgba(255, 255, 255, 0.68);
        color: #8f1f4d;
    }

    .btn-role-outline:hover {
        border-color: rgba(214, 51, 108, 0.18);
        background: rgba(214, 51, 108, 0.04);
        color: #8f1f4d;
        transform: translateY(-1px);
    }

    .detail-meta {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        background: linear-gradient(135deg, rgba(230, 57, 128, 0.1) 0%, rgba(107, 66, 193, 0.08) 100%);
        border: 1px solid rgba(230, 57, 128, 0.18);
        color: #d6336c;
        font-weight: 600;
        font-size: 0.85rem;
        backdrop-filter: blur(8px);
    }

    /* Tombol Simpan Jadwal warna pink */
    .btn-simpan-pink {
        background: linear-gradient(135deg, #e63980 0%, #ff6b9d 100%);
        border: none;
        color: white;
        font-weight: 600;
        border-radius: 8px;
        padding: 0.5rem 1.25rem;
        box-shadow: 0 4px 12px rgba(230, 57, 128, 0.3);
        transition: all 0.3s ease;
    }

    .btn-simpan-pink:hover {
        background: linear-gradient(135deg, #c41e5c 0%, #e63980 100%);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(230, 57, 128, 0.4);
    }

    @media (max-width: 768px) {
        .pengguna-info {
            grid-template-columns: 1fr;
        }

        .detail-topbar {
            align-items: stretch;
        }

        .detail-search {
            flex-direction: column;
        }

        .detail-search .btn-role-primary {
            width: 100%;
        }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="bidan-dashboard bidan-detail-page detail-shell">
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

    <div class="card role-card hero-role detail-hero mb-4">
        <div class="card-body p-4 p-lg-5">
            <div class="detail-topbar">
                <div>
                    <span class="detail-meta mb-3">
                        <i class="bi bi-people-fill"></i>
                        Daftar Pengguna
                    </span>
                    <h2 class="detail-heading mb-2">Detail Data Pengguna</h2>
                    <p class="mb-0 detail-subtitle">Lihat informasi lengkap dan kelola pengguna yang sedang ditangani dengan tampilan yang serasi dengan panel bidan.</p>
                </div>
                <form method="GET" action="<?php echo e(route('bidan.pengguna')); ?>" class="detail-search d-flex gap-2 flex-grow-1 justify-content-lg-end">
                    <input type="search" name="q" value="<?php echo e($search); ?>" class="form-control" placeholder="Cari nama atau email">
                    <button class="btn btn-role-primary" type="submit">
                        <i class="bi bi-search me-1"></i>Cari
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="card role-card p-4 p-lg-5">
        <?php if($penggunaList->count() > 0): ?>
            <div class="mb-3">
                <span class="pengguna-count">
                    <i class="bi bi-people-fill"></i> Menampilkan <?php echo e($penggunaList->count()); ?> pengguna
                </span>
            </div>
            <div class="row g-3">
                <?php $__currentLoopData = $penggunaList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pengguna): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-lg-6">
                        <div class="pengguna-row">
                            <div class="pengguna-header">
                                <div class="pengguna-name"><?php echo e($pengguna->name); ?></div>
                                <span class="status-badge <?php echo e($pengguna->is_hamil ? 'status-hamil' : 'status-tidak'); ?>">
                                    <?php echo e($pengguna->is_hamil ? 'Hamil' : 'Tidak Hamil'); ?>

                                </span>
                            </div>
                            
                            <div class="pengguna-info">
                                <div class="info-item">
                                    <span class="info-label">Email</span>
                                    <span class="info-value text-break"><?php echo e($pengguna->email ?? '-'); ?></span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Total Anak</span>
                                    <span class="info-value"><?php echo e($pengguna->anak_count ?? 0); ?> anak</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Konsultasi</span>
                                    <span class="info-value"><?php echo e($pengguna->conversations_count ?? 0); ?> percakapan</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Status</span>
                                    <span class="info-value">Aktif</span>
                                </div>
                            </div>

                            <div class="d-flex gap-2 mt-3">
                                <button type="button" class="btn btn-role-primary btn-sm addScheduleBtn" data-id="<?php echo e($pengguna->id); ?>" data-name="<?php echo e($pengguna->name); ?>">
                                    <i class="bi bi-calendar-plus me-1"></i>Tambah Jadwal
                                </button>
                                <a href="<?php echo e(route('bidan.pengguna.bukuKIA', $pengguna->id)); ?>" class="btn btn-role-outline btn-sm">
                                    <i class="bi bi-book"></i> Kelola Buku KIA
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="bi bi-inbox"></i>
                </div>
                <h5 class="fw-bold mb-2" style="color: #475569;">Tidak ada pengguna ditemukan</h5>
                <p class="mb-0">Coba ubah kriteria pencarian atau periksa kembali nanti.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<!-- Modal: Tambah Jadwal -->
<div class="modal fade" id="tambahJadwalModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Jadwal untuk <span id="modalPenggunaName"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?php echo e(route('bidan.jadwal.store')); ?>">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="pengguna_id" id="modalPenggunaId">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Jenis</label>
                            <select name="jenis" class="form-select" required>
                                <option value="kontrol">Kontrol</option>
                                <option value="imunisasi">Imunisasi</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="terjadwal">Terjadwal</option>
                                <option value="selesai">Selesai</option>
                                <option value="dibatalkan">Dibatalkan</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Judul</label>
                            <input type="text" name="judul" class="form-control" placeholder="Judul jadwal" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tanggal</label>
                            <input type="date" name="tanggal" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Waktu (opsional)</label>
                            <input type="time" name="waktu" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Catatan (opsional)</label>
                            <textarea name="catatan" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-simpan-pink">Simpan Jadwal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modalEl = document.getElementById('tambahJadwalModal');
        const bsModal = new bootstrap.Modal(modalEl);
        document.querySelectorAll('.addScheduleBtn').forEach(btn => {
            btn.addEventListener('click', function () {
                const id = this.dataset.id;
                const name = this.dataset.name;
                document.getElementById('modalPenggunaId').value = id;
                document.getElementById('modalPenggunaName').textContent = name;
                bsModal.show();
            });
        });
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('bidan.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\MomSpire\resources\views/bidan/detailPengguna.blade.php ENDPATH**/ ?>