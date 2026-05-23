<?php $__env->startSection('title', 'Kelola Buku KIA - ' . ($pengguna->name ?? 'Pengguna') . ' - MomSpire'); ?>
<?php $__env->startSection('header_title', 'Kelola Buku KIA'); ?>
<?php $__env->startSection('header_subtitle', 'Input dan kelola data Buku KIA untuk pengguna.'); ?>
<?php $__env->startSection('header_action'); ?>
<a href="<?php echo e(route($role . '.pengguna')); ?>" class="btn btn-back-page">
    <i class="bi bi-arrow-left"></i>
    <span>Kembali</span>
</a>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('head'); ?>
<style>
    .kia-nakes-page {
        --role-primary: #e63980;
        --role-bg-light: rgba(230, 57, 128, 0.06);
        --role-bg-hover: rgba(230, 57, 128, 0.12);
        --role-border: rgba(230, 57, 128, 0.12);
        --gradient-primary: linear-gradient(135deg, #e63980 0%, #ff6b9d 100%);
        --gradient-accent: linear-gradient(135deg, #e63980 0%, #00b894 100%);
        background:
            radial-gradient(circle at top left, rgba(230, 57, 128, 0.06), transparent 34%),
            radial-gradient(circle at top right, rgba(0, 184, 148, 0.05), transparent 30%),
            linear-gradient(180deg, rgba(248, 250, 252, 0.88) 0%, rgba(255, 255, 255, 0.94) 56%, rgba(248, 250, 252, 0.92) 100%);
        min-height: 100vh;
    }

    .kia-page-container { padding: 28px 0; }

    .pengguna-info-bar {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(230, 57, 128, 0.12);
        border-radius: 16px;
        padding: 1.2rem 1.5rem;
        margin-bottom: 24px;
    }

    .pengguna-detail-name { font-size: 1.1rem; font-weight: 700; color: #0f172a; }

    .kia-selector-section {
        background: #fff;
        border: 1px solid rgba(230, 57, 128, 0.12);
        border-radius: 20px;
        margin-bottom: 24px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.04);
    }

    .kia-selector-header {
        background: rgba(230, 57, 128, 0.06);
        padding: 18px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid rgba(230, 57, 128, 0.1);
    }

    .kia-selector-header h4 {
        margin: 0;
        font-size: 1rem;
        font-weight: 800;
        color: #e63980;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .kia-selector-body { padding: 24px; }

    .kia-card-list {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 16px;
    }

    .kia-card-item {
        background: linear-gradient(180deg, #fdfafc 0%, #fff 100%);
        border: 2px solid rgba(230, 57, 128, 0.12);
        border-radius: 16px;
        padding: 20px;
        cursor: pointer;
        transition: all .25s ease;
        text-decoration: none;
        display: block;
        position: relative;
        overflow: hidden;
    }

    .kia-card-item:hover {
        border-color: #e63980;
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    .kia-card-item.active {
        border-color: #e63980;
        background: rgba(230, 57, 128, 0.06);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    .kia-card-item.active::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 4px;
        background: var(--gradient-primary);
    }

    .kia-card-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: rgba(230, 57, 128, 0.08);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 12px;
    }

    .kia-card-icon i { font-size: 1.5rem; color: #e63980; }

    .kia-card-title { font-weight: 800; font-size: 1rem; color: #e63980; margin-bottom: 6px; }

    .kia-card-subtitle { font-size: .82rem; color: #64748b; margin-bottom: 10px; }

    .kia-card-badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: .72rem;
        font-weight: 700;
    }

    .kia-card-badge.aktif { background: rgba(0, 184, 148, 0.12); color: #00b894; }
    .kia-card-badge.draft { background: rgba(230, 57, 128, 0.1); color: #e63980; }

    .kia-card-active-indicator {
        position: absolute;
        top: 12px;
        right: 12px;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: var(--gradient-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: .85rem;
    }

    .kia-card-active-indicator.hidden { display: none; }

    .btn-tambah-kia {
        background: var(--gradient-primary);
        border: none;
        color: #fff;
        font-weight: 700;
        padding: 10px 20px;
        border-radius: 12px;
        transition: all .2s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-tambah-kia:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        color: #fff;
    }

    .kia-form-section {
        background: #fff;
        border: 1px solid rgba(230, 57, 128, 0.12);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.04);
    }

    .kia-form-header {
        background: rgba(230, 57, 128, 0.06);
        padding: 18px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid rgba(230, 57, 128, 0.1);
    }

    .kia-form-header h4 {
        margin: 0;
        font-size: 1rem;
        font-weight: 800;
        color: #e63980;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .kia-form-body { padding: 24px; }

    .category-tabs {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 24px;
        padding-bottom: 20px;
        border-bottom: 2px solid rgba(230, 57, 128, 0.08);
    }

    .category-tab {
        padding: 10px 18px;
        border-radius: 12px;
        font-size: .82rem;
        font-weight: 700;
        color: #e63980;
        background: rgba(230, 57, 128, 0.06);
        border: 2px solid transparent;
        cursor: pointer;
        transition: all .2s ease;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .category-tab:hover {
        background: rgba(230, 57, 128, 0.12);
        border-color: rgba(230, 57, 128, 0.2);
    }

    .category-tab.active {
        background: var(--gradient-primary);
        color: #fff;
        border-color: transparent;
        box-shadow: 0 4px 15px rgba(230, 57, 128, 0.2);
    }

    .form-card {
        background: linear-gradient(180deg, #fdfafc 0%, #fff 100%);
        border: 1px solid rgba(230, 57, 128, 0.08);
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 20px;
    }

    .form-card:last-child { margin-bottom: 0; }

    .form-card-title {
        font-size: .9rem;
        font-weight: 800;
        color: #e63980;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
        text-transform: uppercase;
        letter-spacing: .05em;
    }

    .form-card-title::before {
        content: '';
        width: 4px;
        height: 18px;
        background: var(--gradient-primary);
        border-radius: 2px;
    }

    .form-label-custom {
        font-size: .82rem;
        font-weight: 700;
        color: #e63980;
        margin-bottom: 6px;
        display: block;
    }

    .form-control-custom {
        border: 1.5px solid rgba(230, 57, 128, 0.15);
        border-radius: 10px;
        padding: 10px 14px;
        font-size: .9rem;
        transition: border-color .2s ease, box-shadow .2s ease;
        width: 100%;
        background: rgba(255, 255, 255, 0.8);
    }

    .form-control-custom:focus {
        border-color: #e63980;
        box-shadow: 0 0 0 3px rgba(230, 57, 128, 0.1);
        outline: none;
    }

    .form-select-custom {
        border: 1.5px solid rgba(230, 57, 128, 0.15);
        border-radius: 10px;
        padding: 10px 14px;
        font-size: .9rem;
        background: #fff;
        width: 100%;
        cursor: pointer;
    }

    .form-select-custom:focus {
        border-color: #e63980;
        box-shadow: 0 0 0 3px rgba(230, 57, 128, 0.1);
        outline: none;
    }

    .form-row-custom {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        margin-bottom: 16px;
    }

    .btn-save-form {
        background: var(--gradient-primary);
        border: none;
        color: #fff;
        font-size: .95rem;
        font-weight: 700;
        padding: 12px 28px;
        border-radius: 12px;
        transition: all .2s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-save-form:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(230, 57, 128, 0.25);
        color: #fff;
    }

    .table { border-color: rgba(230, 57, 128, 0.08); }

    .table thead th {
        background: rgba(230, 57, 128, 0.06);
        color: #e63980;
        font-weight: 700;
        font-size: .78rem;
        text-transform: uppercase;
        border-color: rgba(230, 57, 128, 0.1);
        padding: 10px 6px;
    }

    .table tbody td {
        padding: 6px;
        border-color: rgba(230, 57, 128, 0.06);
        vertical-align: middle;
    }

    .table tbody tr:hover { background: rgba(230, 57, 128, 0.03); }
    .table-light { background: rgba(230, 57, 128, 0.06); }

    .form-check-input:checked { background-color: #e63980; border-color: #e63980; }
    .form-check-input:focus { box-shadow: 0 0 0 3px rgba(230, 57, 128, 0.1); border-color: #e63980; }

    .empty-state { text-align: center; padding: 3rem 2rem; color: #64748b; }
    .empty-icon { font-size: 3rem; margin-bottom: 1rem; color: #e63980; opacity: 0.72; }

    @media (max-width: 768px) {
        .kia-form-body { padding: 16px; }
        .form-card { padding: 18px; }
        .form-row-custom { grid-template-columns: 1fr; }
        .category-tabs { overflow-x: auto; flex-wrap: nowrap; padding-bottom: 16px; }
        .category-tab { white-space: nowrap; }
        .kia-card-list { grid-template-columns: 1fr; }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="kia-nakes-page kia-page-container">
    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i><?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <ul class="mb-0 ps-3"><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($error); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="pengguna-info-bar">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div class="d-flex align-items-center gap-3">
                <div>
                    <div class="pengguna-detail-name"><?php echo e($pengguna->name); ?></div>
                    <span class="badge <?php echo e($pengguna->is_hamil ? 'bg-danger-subtle text-danger' : 'bg-secondary-subtle text-secondary'); ?> mt-2">
                        <?php echo e($pengguna->is_hamil ? 'Hamil' : 'Tidak Hamil'); ?>

                    </span>
                </div>
            </div>
            <div class="d-flex gap-4">
                <div><small class="text-muted text-uppercase fw-bold" style="font-size:0.72rem;">Email</small><div><?php echo e($pengguna->email ?? '-'); ?></div></div>
                <div><small class="text-muted text-uppercase fw-bold" style="font-size:0.72rem;">Total Anak</small><div><?php echo e($pengguna->anak_count ?? 0); ?> anak</div></div>
            </div>
        </div>
    </div>

    <div class="kia-selector-section">
        <div class="kia-selector-header">
            <h4><i class="bi bi-journal-bookmark-fill"></i> Pilih Buku KIA</h4>
            <button type="button" class="btn-tambah-kia" onclick="tambahBukuKIA()">
                <i class="bi bi-plus-lg"></i> Tambah Buku KIA Baru
            </button>
        </div>
        <div class="kia-selector-body">
            <?php if($bukuKiaList->count() > 0): ?>
            <div class="kia-card-list">
                <?php $__currentLoopData = $bukuKiaList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kia): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="kia-card-item <?php echo e($selectedKiaId == $kia->id ? 'active' : ''); ?>" onclick="pilihBukuKIA(<?php echo e($kia->id); ?>)">
                    <?php if($selectedKiaId == $kia->id): ?>
                    <div class="kia-card-active-indicator">
                        <i class="bi bi-check"></i>
                    </div>
                    <?php endif; ?>
                    <div class="kia-card-icon">
                        <i class="bi bi-journal-bookmark-fill"></i>
                    </div>
                    <div class="kia-card-title"><?php echo e($kia->ibu->nama ?? 'Buku KIA #' . $kia->id); ?></div>
                    <?php if($kia->ibu && $kia->ibu->tanggal_lahir): ?>
                    <div class="kia-card-subtitle">TTL: <?php echo e($kia->ibu->tempat_lahir ?? '-'); ?>, <?php echo e(\Carbon\Carbon::parse($kia->ibu->tanggal_lahir)->format('d/m/Y')); ?></div>
                    <?php endif; ?>
                    <span class="kia-card-badge <?php echo e($kia->ibu && $kia->ibu->tanggal_lahir ? 'aktif' : 'draft'); ?>">
                        <i class="bi <?php echo e($kia->ibu && $kia->ibu->tanggal_lahir ? 'bi-check-circle-fill' : 'bi-clock'); ?> me-1"></i>
                        <?php echo e($kia->ibu && $kia->ibu->tanggal_lahir ? 'Aktif' : 'Draft'); ?>

                    </span>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <?php else: ?>
            <div class="empty-state">
                <div class="empty-icon"><i class="bi bi-journal-x"></i></div>
                <h5 class="fw-bold mb-2">Belum ada Buku KIA</h5>
                <p class="mb-0">Klik tombol "Tambah Buku KIA Baru" untuk membuat buku KIA baru untuk pengguna ini.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <?php if($selectedKia): ?>
    <div class="kia-form-section">
        <div class="kia-form-header">
            <h4><i class="bi bi-pencil-square"></i> Form Input Data Buku KIA</h4>
            <span class="badge" style="background: rgba(230, 57, 128, 0.08); color: #e63980;">
                <i class="bi bi-book me-1"></i> <?php echo e($selectedKia->ibu->nama ?? 'Buku KIA #' . $selectedKia->id); ?>

            </span>
        </div>
        <div class="kia-form-body">
            <form id="kiaForm" action="<?php echo e(route($role . '.pengguna.bukuKIA.input.store', $pengguna->id)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="data_kia_id" value="<?php echo e($selectedKia->id); ?>">

                <div class="category-tabs">
                    <button type="button" class="category-tab active" onclick="showCategory('info_buku')"><i class="bi bi-info-circle"></i> Info</button>
                    <button type="button" class="category-tab" onclick="showCategory('identitas_ibu')"><i class="bi bi-person"></i> Ibu</button>
                    <button type="button" class="category-tab" onclick="showCategory('identitas_suami')"><i class="bi bi-person-badge"></i> Suami</button>
                    <button type="button" class="category-tab" onclick="showCategory('identitas_anak')"><i class="bi bi-emoji-smile"></i> Anak</button>
                    <button type="button" class="category-tab" onclick="showCategory('layanan')"><i class="bi bi-hospital"></i> Layanan</button>
                    <button type="button" class="category-tab" onclick="showCategory('asuransi')"><i class="bi bi-shield-check"></i> Asuransi</button>
                    <button type="button" class="category-tab" onclick="showCategory('kehamilan')"><i class="bi bi-heart-pulse"></i> Kehamilan</button>
                    <button type="button" class="category-tab" onclick="showCategory('catatan_ttd')"><i class="bi bi-capsule"></i> TTD</button>
                    <button type="button" class="category-tab" onclick="showCategory('pemantauan_mingguan')"><i class="bi bi-calendar-week"></i> P. Mingguan</button>
                    <button type="button" class="category-tab" onclick="showCategory('kelas_ibu')"><i class="bi bi-mortarboard"></i> Kelas</button>
                    <button type="button" class="category-tab" onclick="showCategory('persiapan')"><i class="bi bi-truck"></i> Persiapan</button>
                    <button type="button" class="category-tab" onclick="showCategory('pemantauan_nifas')"><i class="bi bi-droplet"></i> Nifas</button>
                    <button type="button" class="category-tab" onclick="showCategory('kb')"><i class="bi bi-flower1"></i> KB</button>
                    <button type="button" class="category-tab" onclick="showCategory('bayi_baru')"><i class="bi bi-baby"></i> Bayi</button>
                    <button type="button" class="category-tab" onclick="showCategory('pemantauan_bayi')"><i class="bi bi-thermometer"></i> P. Bayi</button>
                    <button type="button" class="category-tab" onclick="showCategory('warna_tinja')"><i class="bi bi-palette"></i> Tinja</button>
                    <button type="button" class="category-tab" onclick="showCategory('kelas_balita')"><i class="bi bi-people"></i> Balita</button>
                    <button type="button" class="category-tab" onclick="showCategory('perkembangan_bayi')"><i class="bi bi-graph-up"></i> Perkembangan</button>
                    <button type="button" class="category-tab" onclick="showCategory('kesehatan_lingkungan')"><i class="bi bi-house"></i> Lingkungan</button>
                </div>

                <?php echo $__env->make('nakes.partials.kia-form-fields', ['selectedKia' => $selectedKia], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                <div class="mt-4">
                    <button type="submit" class="btn-save-form">
                        <i class="bi bi-save"></i> Simpan Data Buku KIA
                    </button>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (window.__momspireSidebarOpen !== undefined) {
            window.__momspireSidebarOpen = false;
            window.__momspireSyncSidebar && window.__momspireSyncSidebar();
        }
    });

    function showCategory(category) {
        document.querySelectorAll('.form-category-content').forEach(function(el) { el.style.display = 'none'; });
        document.querySelectorAll('.category-tab').forEach(function(el) { el.classList.remove('active'); });
        var target = document.getElementById('form_' + category);
        if (target) target.style.display = 'block';
        var btns = document.querySelectorAll('.category-tab');
        btns.forEach(function(btn) {
            if (btn.getAttribute('onclick') && btn.getAttribute('onclick').indexOf(category) !== -1) {
                btn.classList.add('active');
            }
        });
    }

    function pilihBukuKIA(id) {
        window.location.href = '<?php echo e(route($role . ".pengguna.bukuKIA.input", $pengguna->id)); ?>?buku_id=' + id;
    }

    function tambahBukuKIA() {
        if (confirm('Apakah Anda yakin ingin membuat buku KIA baru untuk pengguna ini?')) {
            fetch('<?php echo e(route($role . ".pengguna.bukuKIA.tambah", $pengguna->id)); ?>', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                }
            })
            .then(function(response) { return response.json(); })
            .then(function(data) {
                if (data.success) {
                    window.location.href = '<?php echo e(route($role . ".pengguna.bukuKIA.input", $pengguna->id)); ?>?buku_id=' + data.id;
                }
            })
            .catch(function(error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan. Silakan coba lagi.');
            });
        }
    }
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make($role . '.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\MomSpire\resources\views/nakes/buku-kia-pengguna.blade.php ENDPATH**/ ?>