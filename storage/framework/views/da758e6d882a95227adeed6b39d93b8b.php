<?php $__env->startSection('title', 'Jadwal Bidan - MomSpire'); ?>
<?php $__env->startSection('header_title', 'Jadwal'); ?>
<?php $__env->startSection('header_subtitle', 'Atur jadwal kontrol dan imunisasi yang akan dilihat pengguna.'); ?>

<?php $__env->startPush('head'); ?>
<style>
    .schedule-shell {
        background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
        min-height: 100vh;
    }

    .section-card {
        border: 1px solid rgba(15, 23, 42, 0.08);
        border-radius: 18px;
        background: #fff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.04);
    }

    .schedule-row {
        border: 1px solid rgba(15, 23, 42, 0.08);
        border-radius: 14px;
        padding: 0.9rem 1rem;
        background: #fff;
    }

    /* Override tombol Simpan Jadwal menjadi warna pink */
    #submitBtn.btn-primary-custom,
    button.btn-primary-custom {
        background: linear-gradient(135deg, #e63980 0%, #ff6b9d 100%) !important;
        border: none !important;
        color: white;
        box-shadow: 0 4px 15px rgba(230, 57, 128, 0.3);
    }

    #submitBtn.btn-primary-custom:hover,
    button.btn-primary-custom:hover {
        background: linear-gradient(135deg, #c41e5c 0%, #e63980 100%) !important;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(230, 57, 128, 0.4);
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="schedule-shell">
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

    <div class="section-card p-4 p-lg-5 mb-4">
        <div class="row align-items-center g-3">
            <div class="col-lg-8">
                <span class="badge text-bg-light text-success mb-3">Penjadwalan Pengguna</span>
                <h2 class="fw-bold mb-2">Buat jadwal kontrol dan imunisasi.</h2>
                <p class="text-muted mb-0">Jadwal yang dibuat di sini akan muncul di halaman jadwal pengguna.</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <form method="GET" action="<?php echo e(route('bidan.jadwal')); ?>" class="d-flex gap-2">
                    <input type="search" name="q" value="<?php echo e($search); ?>" class="form-control" placeholder="Cari nama pengguna">
                    <button class="btn btn-outline-secondary" type="submit">Cari</button>
                </form>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-5">
            <div class="section-card p-4 h-100">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                    <h5 class="fw-bold mb-0">Form Jadwal</h5>
                    <span class="text-muted small">Create / Update</span>
                </div>

                <form method="POST" action="<?php echo e(route('bidan.jadwal.store')); ?>" id="jadwalForm">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="jadwal_id" id="jadwalId">
                    <div class="mb-3">
                        <label class="form-label">Pengguna</label>
                        <select name="pengguna_id" id="penggunaId" class="form-select" required>
                            <option value="">Pilih pengguna</option>
                            <?php $__currentLoopData = $penggunaList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pengguna): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($pengguna->id); ?>"><?php echo e($pengguna->name); ?> <?php echo e($pengguna->is_hamil ? '(Hamil)' : ''); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Jenis</label>
                            <select name="jenis" id="jenis" class="form-select" required>
                                <option value="kontrol">Kontrol</option>
                                <option value="imunisasi">Imunisasi</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="status" id="status" class="form-select" required>
                                <option value="terjadwal">Terjadwal</option>
                                <option value="selesai">Selesai</option>
                                <option value="dibatalkan">Dibatalkan</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Judul</label>
                            <input type="text" name="judul" id="judul" class="form-control" placeholder="Contoh: Kontrol ANC bulan ini" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tanggal</label>
                            <input type="date" name="tanggal" id="tanggal" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Waktu</label>
                            <input type="time" name="waktu" id="waktu" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Catatan</label>
                            <textarea name="catatan" id="catatan" class="form-control" rows="4" placeholder="Catatan untuk pengguna"></textarea>
                        </div>
                    </div>
                    <div class="d-flex gap-2 mt-4 flex-wrap">
                        <button type="submit" class="btn btn-primary-custom" id="submitBtn">Simpan Jadwal</button>
                        <button type="button" class="btn btn-outline-secondary" id="resetBtn">Reset</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="section-card p-4 h-100">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                    <div>
                        <h5 class="fw-bold mb-0">Daftar Jadwal</h5>
                        <div class="text-muted small">Klik edit untuk memperbarui tanggal, jam, atau catatan.</div>
                    </div>
                    <span class="text-muted small"><?php echo e($jadwalList->count()); ?> jadwal</span>
                </div>

                <?php $__empty_1 = true; $__currentLoopData = $jadwalList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jadwal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="schedule-row mb-3">
                        <div class="d-flex justify-content-between align-items-start gap-2 flex-wrap">
                            <div>
                                <div class="fw-bold"><?php echo e($jadwal->pengguna->name ?? 'Pengguna'); ?> - <?php echo e($jadwal->judul); ?></div>
                                <div class="text-muted small"><?php echo e(ucfirst($jadwal->jenis)); ?> • <?php echo e($jadwal->tanggal->format('d M Y')); ?><?php echo e($jadwal->waktu ? ' • ' . $jadwal->waktu : ''); ?></div>
                                <div class="text-muted small"><?php echo e($jadwal->catatan ?: 'Tanpa catatan'); ?></div>
                            </div>
                            <div class="text-end">
                                <span class="badge text-bg-<?php echo e($jadwal->status === 'terjadwal' ? 'primary' : ($jadwal->status === 'selesai' ? 'success' : 'secondary')); ?> mb-2"><?php echo e(ucfirst($jadwal->status)); ?></span>
                                <div>
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-outline-primary edit-jadwal-btn"
                                        data-jadwal-id="<?php echo e($jadwal->id); ?>"
                                        data-pengguna-id="<?php echo e($jadwal->pengguna_id); ?>"
                                        data-jenis="<?php echo e($jadwal->jenis); ?>"
                                        data-status="<?php echo e($jadwal->status); ?>"
                                        data-judul="<?php echo e(e($jadwal->judul)); ?>"
                                        data-tanggal="<?php echo e($jadwal->tanggal->format('Y-m-d')); ?>"
                                        data-waktu="<?php echo e($jadwal->waktu ? substr((string) $jadwal->waktu, 0, 5) : ''); ?>"
                                        data-catatan="<?php echo e(e($jadwal->catatan ?? '')); ?>"
                                    >Edit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="alert alert-light border mb-0">Belum ada jadwal pemantauan.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    function resetForm() {
        document.getElementById('jadwalForm')?.reset();
        document.getElementById('jadwalId').value = '';
        document.getElementById('submitBtn').textContent = 'Simpan Jadwal';
    }

    document.getElementById('resetBtn')?.addEventListener('click', resetForm);

    document.querySelectorAll('.edit-jadwal-btn').forEach((button) => {
        button.addEventListener('click', function () {
            document.getElementById('jadwalId').value = this.dataset.jadwalId || '';
            document.getElementById('penggunaId').value = this.dataset.penggunaId || '';
            document.getElementById('jenis').value = this.dataset.jenis || 'kontrol';
            document.getElementById('status').value = this.dataset.status || 'terjadwal';
            document.getElementById('judul').value = this.dataset.judul || '';
            document.getElementById('tanggal').value = this.dataset.tanggal || '';
            document.getElementById('waktu').value = this.dataset.waktu || '';
            document.getElementById('catatan').value = this.dataset.catatan || '';
            document.getElementById('submitBtn').textContent = 'Perbarui Jadwal';
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('bidan.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\MomSpire\resources\views\bidan\jadwal.blade.php ENDPATH**/ ?>