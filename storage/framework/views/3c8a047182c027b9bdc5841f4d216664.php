

<?php $__env->startSection('title', 'Data Buku KIA - MomSpire'); ?>
<?php $__env->startSection('header_title', 'Data Buku KIA'); ?>
<?php $__env->startSection('header_subtitle', 'Akses cepat dokumen resmi <strong>Buku KIA (Permenkes)</strong>.'); ?>

<?php $__env->startPush('head'); ?>
<style>
    .kia-hero {
        background: linear-gradient(135deg, rgba(230,57,128,0.12), rgba(0,184,148,0.12));
        border: 1px solid rgba(230,57,128,0.18);
        border-radius: 18px;
        padding: 1.25rem;
    }
    .kia-actions .btn {
        min-width: 170px;
    }
    .kia-viewer {
        width: 100%;
        min-height: 76vh;
        border: 1px solid var(--border);
        border-radius: 14px;
        background: #fff;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <section class="kia-hero mb-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <h5 class="mb-1"><i class="bi bi-file-earmark-pdf-fill text-danger me-2"></i>Buku KIA (Permenkes)</h5>
                <p class="mb-0 text-muted">Gunakan viewer di bawah untuk membaca langsung, atau buka tab baru untuk tampilan penuh.</p>
            </div>
            <div class="d-flex flex-wrap gap-2 kia-actions">
                    <a class="btn btn-outline-primary" href="<?php echo e(route('admin.kia.pdf')); ?>" target="_blank" rel="noopener noreferrer">
                    <i class="bi bi-box-arrow-up-right me-1"></i>Buka Tab Baru
                </a>
                    <a class="btn btn-primary-custom" href="<?php echo e(route('admin.kia.pdf')); ?>" download>
                    <i class="bi bi-download me-1"></i>Download PDF
                </a>
            </div>
        </div>
    </section>

    <div class="admin-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-book-half me-2"></i>Preview Dokumen</h5>
            <small class="text-muted">Sumber: folder root `buku/`</small>
        </div>
        <div class="card-body">
                <iframe class="kia-viewer" src="<?php echo e(route('admin.kia.pdf')); ?>" title="Buku KIA Permenkes"></iframe>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Admin.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\MomSpire\resources\views\Admin\BukuKIA.blade.php ENDPATH**/ ?>