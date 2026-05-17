<?php $__env->startSection('title', 'Manajemen Buku KIA - MomSpire'); ?>

<?php $__env->startSection('header_title', 'Manajemen Buku KIA'); ?>
<?php $__env->startSection('header_subtitle', 'Daftar data KIA pengguna yang perlu diverifikasi dan dilengkapi.'); ?>

<?php $__env->startSection('content'); ?>
<div class="card role-card">
    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Nama Ibu</th>
                        <th>Email</th>
                        <th>Status Screening</th>
                        <th>Terakhir Update</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $dataKias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kia): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <div class="fw-bold"><?php echo e($kia->ibu->nama ?? 'N/A'); ?></div>
                            </td>
                            <td><?php echo e($kia->user->email ?? '-'); ?></td>
                            <td>
                                <span class="badge <?php echo e($kia->riwayat ? 'text-bg-success' : 'text-bg-warning'); ?>">
                                    <?php echo e($kia->riwayat ? 'Lengkap' : 'Menunggu Nakes'); ?>

                                </span>
                            </td>
                            <td><?php echo e($kia->updated_at ? \Illuminate\Support\Carbon::parse($kia->updated_at)->diffForHumans() : '-'); ?></td>
                            <td class="text-end">
                                <a href="<?php echo e(route($role . '.kia.edit_riwayat', $kia->id)); ?>" class="btn btn-sm btn-outline-primary mb-1">
                                    <i class="bi bi-pencil-square me-1"></i> Riwayat
                                </a>
                                <a href="<?php echo e(route($role . '.kia.edit_pelayanan', $kia->id)); ?>" class="btn btn-sm btn-outline-info mb-1">
                                    <i class="bi bi-journal-medical me-1"></i> Pelayanan
                                </a>
                                <a href="<?php echo e(route($role . '.kia.edit_evaluasi', $kia->id)); ?>" class="btn btn-sm btn-outline-warning mb-1">
                                    <i class="bi bi-clipboard2-pulse me-1"></i> Evaluasi
                                </a>
                                <a href="<?php echo e(route($role . '.kia.edit_trimester1', $kia->id)); ?>" class="btn btn-sm btn-outline-secondary mb-1">
                                    <i class="bi bi-images me-1"></i> T1 (Hal. 52)
                                </a>
                                <a href="<?php echo e(route($role . '.kia.edit_trimester2', $kia->id)); ?>" class="btn btn-sm btn-outline-secondary mb-1">
                                    <i class="bi bi-clipboard-check me-1"></i> T2 (Hal. 53)
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">Belum ada data KIA yang masuk.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make($role . '.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\MomSpire\resources\views/nakes/kia-index.blade.php ENDPATH**/ ?>