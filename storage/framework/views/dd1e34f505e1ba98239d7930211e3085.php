<?php $__env->startSection('title', 'Buku KIA - MomSpire'); ?>
<?php $__env->startSection('header_title'); ?>
    Buku KIA
<?php $__env->stopSection(); ?>
<?php $__env->startSection('header_subtitle', ''); ?>
<?php $__env->startSection('header_action'); ?>
    <a href="<?php echo e(route('pengguna.dashboard')); ?>" class="btn btn-back-page">
        <i class="bi bi-arrow-left"></i>
        <span>Kembali</span>
    </a>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('head'); ?>
<script>
    window.__MOMSPIRE_SIDEBAR_OPEN = false;
    window.__MOMSPIRE_HEADER_TITLE_LOCK = 'Buku KIA';
</script>
<style>
    .btn-back-page {
        background: #fff;
        border: 1px solid rgba(15, 23, 42, .12);
        border-radius: 999px;
        color: #0f172a;
        font-weight: 700;
        padding: 10px 16px;
        box-shadow: 0 10px 20px rgba(15, 23, 42, .05);
        transition: all .2s ease;
        transform: translateX(8px);
        margin-right: 6px;
    }

    .btn-back-page:hover {
        transform: translateX(8px) translateY(-1px);
        border-color: rgba(15, 23, 42, .25);
        box-shadow: 0 14px 24px rgba(15, 23, 42, .12);
        color: #0f172a;
    }

    /* Main Container */
    .buku-kia-shell {
        background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
        border: 1px solid rgba(15, 23, 42, .05);
        border-radius: 28px;
        box-shadow: 0 18px 40px rgba(15, 23, 42, .06);
        padding: 28px;
    }

    .kia-hero {
        background: linear-gradient(135deg, #fff1f7 0%, #fef3c7 100%);
        border: 1px solid rgba(236, 72, 153, .2);
        border-radius: 24px;
        padding: 24px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .kia-hero::after {
        content: '';
        position: absolute;
        inset: auto -100px -110px auto;
        width: 220px;
        height: 220px;
        border-radius: 50%;
        background: rgba(244, 114, 182, .12);
        pointer-events: none;
    }

    .kia-hero h2 {
        font-size: 30px;
        font-weight: 800;
        margin-bottom: 8px;
        color: #be185d;
    }

    .kia-hero p {
        margin-bottom: 0;
        color: #334155;
    }

    /* Buku KIA Preview Section */
    .kia-preview-section {
        background: #fff;
        border: 1px solid rgba(236, 72, 153, .15);
        border-radius: 24px;
        margin-bottom: 24px;
        overflow: hidden;
    }

    .kia-preview-header {
        background: linear-gradient(135deg, #fff1f7 0%, #fef3c7 100%);
        padding: 20px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid rgba(236, 72, 153, .1);
    }

    .kia-preview-header h4 {
        margin: 0;
        font-size: 1.1rem;
        font-weight: 800;
        color: #be185d;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .kia-preview-body {
        padding: 24px;
    }

    .kia-book-preview {
        background: linear-gradient(135deg, #fef3c7 0%, #fff1f7 100%);
        border: 2px solid rgba(236, 72, 153, .2);
        border-radius: 16px;
        padding: 32px;
        text-align: center;
        min-height: 300px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .kia-book-preview i {
        font-size: 4rem;
        color: #fda4af;
        margin-bottom: 16px;
    }

    .kia-book-preview h5 {
        font-weight: 800;
        color: #be185d;
        margin-bottom: 8px;
    }

    .kia-book-preview p {
        color: #64748b;
        max-width: 400px;
        margin: 0 auto;
    }

    .kia-book-actions {
        display: flex;
        gap: 12px;
        justify-content: center;
        margin-top: 20px;
        flex-wrap: wrap;
    }

    .btn-preview {
        padding: 10px 20px;
        border-radius: 12px;
        font-weight: 700;
        font-size: .9rem;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all .2s ease;
    }

    .btn-preview-outline {
        background: transparent;
        border: 2px solid #e63980;
        color: #e63980;
    }

    .btn-preview-outline:hover {
        background: rgba(230, 57, 128, .08);
    }

    .btn-preview-filled {
        background: linear-gradient(135deg, #be185d 0%, #e63980 100%);
        border: none;
        color: #fff;
    }

    .btn-preview-filled:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(230, 57, 128, .35);
    }

    /* Form Section Styles */
    .kia-form-section {
        background: #fff;
        border: 1px solid rgba(236, 72, 153, .15);
        border-radius: 24px;
        margin-bottom: 24px;
        overflow: hidden;
    }

    .kia-form-header {
        background: linear-gradient(135deg, #fff1f7 0%, #fef3c7 100%);
        padding: 20px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid rgba(236, 72, 153, .1);
    }

    .kia-form-header h4 {
        margin: 0;
        font-size: 1.1rem;
        font-weight: 800;
        color: #be185d;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .kia-form-body {
        padding: 24px;
    }

    /* Category Tabs */
    .category-tabs {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 24px;
        padding-bottom: 20px;
        border-bottom: 2px solid #f1f5f9;
    }

    .category-tab {
        padding: 10px 18px;
        border-radius: 12px;
        font-size: .82rem;
        font-weight: 700;
        color: #64748b;
        background: #f8fafc;
        border: 2px solid #e2e8f0;
        cursor: pointer;
        transition: all .2s ease;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .category-tab:hover {
        background: #f1f5f9;
        border-color: #cbd5e1;
    }

    .category-tab.active {
        background: linear-gradient(135deg, #be185d 0%, #e63980 100%);
        color: #fff;
        border-color: transparent;
    }

    /* Form Cards */
    .form-card {
        background: linear-gradient(180deg, #fafafa 0%, #fff 100%);
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        padding: 24px;
        margin-bottom: 20px;
    }

    .form-card:last-child {
        margin-bottom: 0;
    }

    .form-card-title {
        font-size: .9rem;
        font-weight: 800;
        color: #be185d;
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
        background: linear-gradient(180deg, #be185d, #e63980);
        border-radius: 2px;
    }

    .form-label-custom {
        font-size: .82rem;
        font-weight: 700;
        color: #475569;
        margin-bottom: 6px;
        display: block;
    }

    .form-control-custom {
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        padding: 10px 14px;
        font-size: .9rem;
        transition: border-color .2s ease, box-shadow .2s ease;
        width: 100%;
    }

    .form-control-custom:focus {
        border-color: #e63980;
        box-shadow: 0 0 0 3px rgba(230, 57, 128, .15);
        outline: none;
    }

    .form-select-custom {
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        padding: 10px 14px;
        font-size: .9rem;
        background: #fff;
        transition: border-color .2s ease, box-shadow .2s ease;
        width: 100%;
        cursor: pointer;
    }

    .form-select-custom:focus {
        border-color: #e63980;
        box-shadow: 0 0 0 3px rgba(230, 57, 128, .15);
        outline: none;
    }

    .form-row-custom {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        margin-bottom: 16px;
    }

    /* Action Buttons */
    .btn-save-form {
        background: linear-gradient(135deg, #be185d 0%, #e63980 100%);
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
        box-shadow: 0 8px 20px rgba(230, 57, 128, .35);
    }

    /* Toast */
    .toast-container {
        position: fixed;
        bottom: 24px;
        right: 24px;
        z-index: 9999;
    }

    .custom-toast {
        background: #fff;
        border-radius: 12px;
        padding: 16px 20px;
        box-shadow: 0 10px 40px rgba(0,0,0,.15);
        display: flex;
        align-items: center;
        gap: 12px;
        animation: slideInRight .3s ease;
        margin-top: 8px;
    }

    .custom-toast.success {
        border-left: 4px solid #10b981;
    }

    .custom-toast.error {
        border-left: 4px solid #ef4444;
    }

    .custom-toast .toast-icon {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
    }

    .custom-toast.success .toast-icon {
        background: rgba(16, 185, 129, .15);
        color: #10b981;
    }

    .custom-toast.error .toast-icon {
        background: rgba(239, 68, 68, .15);
        color: #ef4444;
    }

    .custom-toast .toast-message {
        font-size: .9rem;
        font-weight: 600;
        color: #1e293b;
    }

    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(100px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    /* Responsive */
    @media (max-width: 768px) {
        .kia-form-body {
            padding: 16px;
        }
        .form-card {
            padding: 18px;
        }
        .form-row-custom {
            grid-template-columns: 1fr;
        }
        .category-tabs {
            overflow-x: auto;
            flex-wrap: nowrap;
            padding-bottom: 16px;
        }
        .category-tab {
            white-space: nowrap;
        }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="buku-kia-shell">
    <!-- Hero Section -->
    <div class="kia-hero">
        <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
            <div>
                <h2>Buku KIA Anda</h2>
                <p>Kelola semua data Buku KIA untuk kehamilan dan anak Anda di halaman ini.</p>
            </div>
        </div>
    </div>

    <!-- Preview Section - Inline View -->
    <div class="kia-preview-section">
        <div class="kia-preview-header">
            <h4>
                <i class="bi bi-journal-bookmark-fill"></i>
                Tampilan Buku KIA
            </h4>
        </div>
        <div class="kia-preview-body">
            <div class="kia-book-preview">
                <i class="bi bi-book"></i>
                <h5>Buku KIA - <?php echo e($dataKia->ibu->nama ?? 'Data Pengguna'); ?></h5>
                <p>Buku Kesehatan Ibu dan Anak (KIA) berisi catatan kesehatan ibu hamil, persalinan, dan tumbuh kembang anak.</p>
                <div class="kia-book-actions">
                    <button class="btn-preview btn-preview-outline" onclick="openPdfInline()">
                        <i class="bi bi-eye"></i>
                        Lihat Buku KIA
                    </button>
                    <a href="<?php echo e(route('pengguna.kia.download')); ?>" class="btn-preview btn-preview-filled">
                        <i class="bi bi-download"></i>
                        Download PDF
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- All Form Section -->
    <div class="kia-form-section">
        <div class="kia-form-header">
            <h4>
                <i class="bi bi-pencil-square"></i>
                Form Input Data Buku KIA
            </h4>
        </div>
        <div class="kia-form-body">
            <form id="kiaForm" action="<?php echo e(route('pengguna.kia.save_wizard')); ?>" method="POST">
                <?php echo csrf_field(); ?>

                <!-- Category Tabs -->
                <div class="category-tabs">
                    <button type="button" class="category-tab active" onclick="showCategory('info_buku')">
                        <i class="bi bi-info-circle"></i>
                        Info Buku KIA
                    </button>
                    <button type="button" class="category-tab" onclick="showCategory('identitas_ibu')">
                        <i class="bi bi-person"></i>
                        Identitas Ibu
                    </button>
                    <button type="button" class="category-tab" onclick="showCategory('identitas_suami')">
                        <i class="bi bi-person-badge"></i>
                        Identitas Suami
                    </button>
                    <button type="button" class="category-tab" onclick="showCategory('identitas_anak')">
                        <i class="bi bi-emoji-smile"></i>
                        Identitas Anak
                    </button>
                    <button type="button" class="category-tab" onclick="showCategory('layanan')">
                        <i class="bi bi-hospital"></i>
                        Layanan & Faskes
                    </button>
                    <button type="button" class="category-tab" onclick="showCategory('asuransi')">
                        <i class="bi bi-shield-check"></i>
                        Asuransi
                    </button>
                    <button type="button" class="category-tab" onclick="showCategory('kehamilan')">
                        <i class="bi bi-heart-pulse"></i>
                        Riwayat Kehamilan
                    </button>
                </div>

                <!-- Form Content: Info Buku KIA -->
                <div class="form-category-content" id="form_info_buku">
                    <div class="form-card">
                        <div class="form-card-title">Informasi Pembuatan Buku KIA</div>
                        <div class="form-row-custom">
                            <div>
                                <label class="form-label-custom">Faskes Dikeluarkan</label>
                                <input type="text" class="form-control form-control-custom" name="faskes_dikeluarkan"
                                    value="<?php echo e($dataKia->faskes_dikeluarkan ?? old('faskes_dikeluarkan')); ?>" placeholder="Nama faskes">
                            </div>
                            <div>
                                <label class="form-label-custom">Tanggal Dikeluarkan</label>
                                <input type="date" class="form-control form-control-custom" name="tanggal_dikeluarkan"
                                    value="<?php echo e($dataKia->tanggal_dikeluarkan ?? old('tanggal_dikeluarkan')); ?>">
                            </div>
                            <div>
                                <label class="form-label-custom">Kabupaten/Kota</label>
                                <input type="text" class="form-control form-control-custom" name="kab_kota_dikeluarkan"
                                    value="<?php echo e($dataKia->kab_kota_dikeluarkan ?? old('kab_kota_dikeluarkan')); ?>" placeholder="Kabupaten/Kota">
                            </div>
                            <div>
                                <label class="form-label-custom">Provinsi</label>
                                <input type="text" class="form-control form-control-custom" name="provinsi_dikeluarkan"
                                    value="<?php echo e($dataKia->provinsi_dikeluarkan ?? old('provinsi_dikeluarkan')); ?>" placeholder="Provinsi">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Content: Identitas Ibu -->
                <div class="form-category-content" id="form_identitas_ibu" style="display: none;">
                    <div class="form-card">
                        <div class="form-card-title">Data Diri Ibu Hamil</div>
                        <div class="form-row-custom">
                            <div>
                                <label class="form-label-custom">Nama Lengkap</label>
                                <input type="text" class="form-control form-control-custom" name="nama_ibu"
                                    value="<?php echo e($dataKia->ibu->nama ?? old('nama_ibu')); ?>" placeholder="Nama lengkap">
                            </div>
                            <div>
                                <label class="form-label-custom">NIK</label>
                                <input type="text" class="form-control form-control-custom" name="nik"
                                    value="<?php echo e($dataKia->ibu->nik ?? old('nik')); ?>" placeholder="16 digit NIK" maxlength="16">
                            </div>
                            <div>
                                <label class="form-label-custom">No. JKN / BPJS</label>
                                <input type="text" class="form-control form-control-custom" name="no_jkn_ibu"
                                    value="<?php echo e($dataKia->ibu->no_jkn ?? old('no_jkn_ibu')); ?>" placeholder="No. JKN/BPJS">
                            </div>
                        </div>
                        <div class="form-row-custom">
                            <div>
                                <label class="form-label-custom">Tempat Lahir</label>
                                <input type="text" class="form-control form-control-custom" name="tempat_lahir"
                                    value="<?php echo e($dataKia->ibu->tempat_lahir ?? old('tempat_lahir')); ?>" placeholder="Kota kelahiran">
                            </div>
                            <div>
                                <label class="form-label-custom">Tanggal Lahir</label>
                                <input type="date" class="form-control form-control-custom" name="tanggal_lahir"
                                    value="<?php echo e($dataKia->ibu->tanggal_lahir ?? old('tanggal_lahir')); ?>">
                            </div>
                            <div>
                                <label class="form-label-custom">Golongan Darah</label>
                                <select class="form-select form-select-custom" name="golongan_darah">
                                    <option value="">Pilih</option>
                                    <?php $__currentLoopData = ['A', 'B', 'AB', 'O']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $goldar): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($goldar); ?>" <?php echo e(($dataKia->ibu->golongan_darah ?? old('golongan_darah')) == $goldar ? 'selected' : ''); ?>><?php echo e($goldar); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-row-custom">
                            <div>
                                <label class="form-label-custom">Pendidikan</label>
                                <select class="form-select form-select-custom" name="pendidikan">
                                    <option value="">Pilih</option>
                                    <?php $__currentLoopData = ['Tidak Sekolah', 'SD', 'SMP', 'SMA/SMK', 'D1-D3', 'S1', 'S2', 'S3']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pend): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($pend); ?>" <?php echo e(($dataKia->ibu->pendidikan ?? old('pendidikan')) == $pend ? 'selected' : ''); ?>><?php echo e($pend); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div>
                                <label class="form-label-custom">Pekerjaan</label>
                                <input type="text" class="form-control form-control-custom" name="pekerjaan"
                                    value="<?php echo e($dataKia->ibu->pekerjaan ?? old('pekerjaan')); ?>" placeholder="Pekerjaan">
                            </div>
                            <div>
                                <label class="form-label-custom">Telepon/HP</label>
                                <input type="tel" class="form-control form-control-custom" name="telepon_ibu"
                                    value="<?php echo e($dataKia->ibu->telepon ?? old('telepon_ibu')); ?>" placeholder="08xxxxxxxxxx">
                            </div>
                        </div>
                        <div class="form-row-custom">
                            <div>
                                <label class="form-label-custom">Alamat Lengkap</label>
                                <input type="text" class="form-control form-control-custom" name="alamat"
                                    value="<?php echo e($dataKia->ibu->alamat ?? old('alamat')); ?>" placeholder="Alamat lengkap">
                            </div>
                        </div>
                    </div>
                    <div class="form-card">
                        <div class="form-card-title">Faskes Ibu</div>
                        <div class="form-row-custom">
                            <div>
                                <label class="form-label-custom">Faskes TK1</label>
                                <input type="text" class="form-control form-control-custom" name="faskes_tk1_ibu"
                                    value="<?php echo e($dataKia->ibu->faskes_tk1 ?? old('faskes_tk1_ibu')); ?>" placeholder="Faskes Tingkat Pertama">
                            </div>
                            <div>
                                <label class="form-label-custom">Faskes Rujukan</label>
                                <input type="text" class="form-control form-control-custom" name="faskes_rujukan_ibu"
                                    value="<?php echo e($dataKia->ibu->faskes_rujukan ?? old('faskes_rujukan_ibu')); ?>" placeholder="Faskes Rujukan">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Content: Identitas Suami -->
                <div class="form-category-content" id="form_identitas_suami" style="display: none;">
                    <div class="form-card">
                        <div class="form-card-title">Data Diri Suami</div>
                        <div class="form-row-custom">
                            <div>
                                <label class="form-label-custom">Nama Lengkap Suami</label>
                                <input type="text" class="form-control form-control-custom" name="nama_suami"
                                    value="<?php echo e($dataKia->suami->nama ?? old('nama_suami')); ?>" placeholder="Nama lengkap suami">
                            </div>
                            <div>
                                <label class="form-label-custom">NIK Suami</label>
                                <input type="text" class="form-control form-control-custom" name="nik_suami"
                                    value="<?php echo e($dataKia->suami->nik ?? old('nik_suami')); ?>" placeholder="16 digit NIK" maxlength="16">
                            </div>
                            <div>
                                <label class="form-label-custom">No. JKN / BPJS Suami</label>
                                <input type="text" class="form-control form-control-custom" name="no_jkn_suami"
                                    value="<?php echo e($dataKia->suami->no_jkn ?? old('no_jkn_suami')); ?>" placeholder="No. JKN/BPJS">
                            </div>
                        </div>
                        <div class="form-row-custom">
                            <div>
                                <label class="form-label-custom">Tempat Lahir</label>
                                <input type="text" class="form-control form-control-custom" name="tempat_lahir_suami"
                                    value="<?php echo e($dataKia->suami->tempat_lahir ?? old('tempat_lahir_suami')); ?>" placeholder="Kota kelahiran">
                            </div>
                            <div>
                                <label class="form-label-custom">Tanggal Lahir</label>
                                <input type="date" class="form-control form-control-custom" name="tanggal_lahir_suami"
                                    value="<?php echo e($dataKia->suami->tanggal_lahir ?? old('tanggal_lahir_suami')); ?>">
                            </div>
                            <div>
                                <label class="form-label-custom">Golongan Darah</label>
                                <select class="form-select form-select-custom" name="golongan_darah_suami">
                                    <option value="">Pilih</option>
                                    <?php $__currentLoopData = ['A', 'B', 'AB', 'O']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $goldar): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($goldar); ?>" <?php echo e(($dataKia->suami->golongan_darah ?? old('golongan_darah_suami')) == $goldar ? 'selected' : ''); ?>><?php echo e($goldar); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-row-custom">
                            <div>
                                <label class="form-label-custom">Pendidikan</label>
                                <select class="form-select form-select-custom" name="pendidikan_suami">
                                    <option value="">Pilih</option>
                                    <?php $__currentLoopData = ['Tidak Sekolah', 'SD', 'SMP', 'SMA/SMK', 'D1-D3', 'S1', 'S2', 'S3']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pend): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($pend); ?>" <?php echo e(($dataKia->suami->pendidikan ?? old('pendidikan_suami')) == $pend ? 'selected' : ''); ?>><?php echo e($pend); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div>
                                <label class="form-label-custom">Pekerjaan</label>
                                <input type="text" class="form-control form-control-custom" name="pekerjaan_suami"
                                    value="<?php echo e($dataKia->suami->pekerjaan ?? old('pekerjaan_suami')); ?>" placeholder="Pekerjaan">
                            </div>
                            <div>
                                <label class="form-label-custom">Telepon/HP</label>
                                <input type="tel" class="form-control form-control-custom" name="telepon_suami"
                                    value="<?php echo e($dataKia->suami->telepon ?? old('telepon_suami')); ?>" placeholder="08xxxxxxxxxx">
                            </div>
                        </div>
                        <div class="form-row-custom">
                            <div>
                                <label class="form-label-custom">Alamat Rumah</label>
                                <input type="text" class="form-control form-control-custom" name="alamat_rumah_suami"
                                    value="<?php echo e($dataKia->suami->alamat ?? old('alamat_rumah_suami')); ?>" placeholder="Alamat rumah">
                            </div>
                        </div>
                    </div>
                    <div class="form-card">
                        <div class="form-card-title">Faskes Suami</div>
                        <div class="form-row-custom">
                            <div>
                                <label class="form-label-custom">Faskes TK1 Suami</label>
                                <input type="text" class="form-control form-control-custom" name="faskes_tk1_suami"
                                    value="<?php echo e($dataKia->suami->faskes_tk1 ?? old('faskes_tk1_suami')); ?>" placeholder="Faskes TK1">
                            </div>
                            <div>
                                <label class="form-label-custom">Faskes Rujukan Suami</label>
                                <input type="text" class="form-control form-control-custom" name="faskes_rujukan_suami"
                                    value="<?php echo e($dataKia->suami->faskes_rujukan ?? old('faskes_rujukan_suami')); ?>" placeholder="Faskes Rujukan">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Content: Identitas Anak -->
                <div class="form-category-content" id="form_identitas_anak" style="display: none;">
                    <div class="form-card">
                        <div class="form-card-title">Data Diri Anak</div>
                        <div class="form-row-custom">
                            <div>
                                <label class="form-label-custom">Nama Lengkap Anak</label>
                                <input type="text" class="form-control form-control-custom" name="nama_anak"
                                    value="<?php echo e($dataKia->anak->nama ?? old('nama_anak')); ?>" placeholder="Nama lengkap anak">
                            </div>
                            <div>
                                <label class="form-label-custom">NIK Anak</label>
                                <input type="text" class="form-control form-control-custom" name="nik_anak"
                                    value="<?php echo e($dataKia->anak->nik ?? old('nik_anak')); ?>" placeholder="16 digit NIK" maxlength="16">
                            </div>
                            <div>
                                <label class="form-label-custom">No. JKN / BPJS Anak</label>
                                <input type="text" class="form-control form-control-custom" name="no_jkn_anak"
                                    value="<?php echo e($dataKia->anak->no_jkn ?? old('no_jkn_anak')); ?>" placeholder="No. JKN/BPJS">
                            </div>
                        </div>
                        <div class="form-row-custom">
                            <div>
                                <label class="form-label-custom">Tempat Lahir</label>
                                <input type="text" class="form-control form-control-custom" name="tempat_lahir_anak"
                                    value="<?php echo e($dataKia->anak->tempat_lahir ?? old('tempat_lahir_anak')); ?>" placeholder="Kota kelahiran">
                            </div>
                            <div>
                                <label class="form-label-custom">Tanggal Lahir</label>
                                <input type="date" class="form-control form-control-custom" name="tanggal_lahir_anak"
                                    value="<?php echo e($dataKia->anak->tanggal_lahir ?? old('tanggal_lahir_anak')); ?>">
                            </div>
                            <div>
                                <label class="form-label-custom">Anak ke-</label>
                                <input type="number" class="form-control form-control-custom" name="anak_ke" min="1"
                                    value="<?php echo e($dataKia->anak->anak_ke ?? old('anak_ke')); ?>" placeholder="1">
                            </div>
                        </div>
                        <div class="form-row-custom">
                            <div>
                                <label class="form-label-custom">No. Akta Kelahiran</label>
                                <input type="text" class="form-control form-control-custom" name="no_akta_kelahiran_anak"
                                    value="<?php echo e($dataKia->anak->no_akta_kelahiran ?? old('no_akta_kelahiran_anak')); ?>" placeholder="No. Akta">
                            </div>
                            <div>
                                <label class="form-label-custom">Golongan Darah</label>
                                <select class="form-select form-select-custom" name="golongan_darah_anak">
                                    <option value="">Pilih</option>
                                    <?php $__currentLoopData = ['A', 'B', 'AB', 'O']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $goldar): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($goldar); ?>" <?php echo e(($dataKia->anak->golongan_darah ?? old('golongan_darah_anak')) == $goldar ? 'selected' : ''); ?>><?php echo e($goldar); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div>
                                <label class="form-label-custom">Telepon</label>
                                <input type="tel" class="form-control form-control-custom" name="telepon_anak"
                                    value="<?php echo e($dataKia->anak->telepon ?? old('telepon_anak')); ?>" placeholder="08xxxxxxxxxx">
                            </div>
                        </div>
                        <div class="form-row-custom">
                            <div>
                                <label class="form-label-custom">Alamat Rumah Anak</label>
                                <input type="text" class="form-control form-control-custom" name="alamat_anak"
                                    value="<?php echo e($dataKia->anak->alamat ?? old('alamat_anak')); ?>" placeholder="Alamat (kosongkan jika sama dengan ibu)">
                            </div>
                        </div>
                    </div>
                    <div class="form-card">
                        <div class="form-card-title">Faskes Anak</div>
                        <div class="form-row-custom">
                            <div>
                                <label class="form-label-custom">Faskes TK1 Anak</label>
                                <input type="text" class="form-control form-control-custom" name="faskes_tk1_anak"
                                    value="<?php echo e($dataKia->anak->faskes_tk1 ?? old('faskes_tk1_anak')); ?>" placeholder="Faskes TK1">
                            </div>
                            <div>
                                <label class="form-label-custom">Faskes Rujukan Anak</label>
                                <input type="text" class="form-control form-control-custom" name="faskes_rujukan_anak"
                                    value="<?php echo e($dataKia->anak->faskes_rujukan ?? old('faskes_rujukan_anak')); ?>" placeholder="Faskes Rujukan">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Content: Layanan & Faskes -->
                <div class="form-category-content" id="form_layanan" style="display: none;">
                    <div class="form-card">
                        <div class="form-card-title">Fasilitas Pelayanan Kesehatan - Ibu</div>
                        <div class="form-row-custom">
                            <div>
                                <label class="form-label-custom">Puskesmas Domisili</label>
                                <input type="text" class="form-control form-control-custom" name="puskesmas_domisili"
                                    value="<?php echo e($dataKia->layanan->puskesmas_domisili ?? old('puskesmas_domisili')); ?>" placeholder="Puskesmas domisili">
                            </div>
                            <div>
                                <label class="form-label-custom">No. Reg Kohort Ibu</label>
                                <input type="text" class="form-control form-control-custom" name="no_reg_kohort_ibu"
                                    value="<?php echo e($dataKia->layanan->no_reg_kohort_ibu ?? old('no_reg_kohort_ibu')); ?>" placeholder="No. Reg">
                            </div>
                        </div>
                        <div class="form-row-custom">
                            <div>
                                <label class="form-label-custom">No. Reg Kohort Bayi</label>
                                <input type="text" class="form-control form-control-custom" name="no_reg_kohort_bayi"
                                    value="<?php echo e($dataKia->layanan->no_reg_kohort_bayi ?? old('no_reg_kohort_bayi')); ?>" placeholder="No. Reg Kohort Bayi">
                            </div>
                            <div>
                                <label class="form-label-custom">No. Reg Kohort Balita</label>
                                <input type="text" class="form-control form-control-custom" name="no_reg_kohort_balita"
                                    value="<?php echo e($dataKia->layanan->no_reg_kohort_balita ?? old('no_reg_kohort_balita')); ?>" placeholder="No. Reg Kohort Balita">
                            </div>
                            <div>
                                <label class="form-label-custom">No. Catatan Medik RS</label>
                                <input type="text" class="form-control form-control-custom" name="no_catatan_medik_rs"
                                    value="<?php echo e($dataKia->layanan->no_catatan_medik_rs ?? old('no_catatan_medik_rs')); ?>" placeholder="No. Catatan Medik RS">
                            </div>
                        </div>
                    </div>
                    <div class="form-card">
                        <div class="form-card-title">Fasilitas Pelayanan Kesehatan - Suami</div>
                        <div class="form-row-custom">
                            <div>
                                <label class="form-label-custom">Puskesmas Domisili Suami</label>
                                <input type="text" class="form-control form-control-custom" name="puskesmas_domisili_suami"
                                    value="<?php echo e($dataKia->layanan->puskesmas_domisili_suami ?? old('puskesmas_domisili_suami')); ?>" placeholder="Puskesmas domisili">
                            </div>
                            <div>
                                <label class="form-label-custom">No. Catatan Medik RS Suami</label>
                                <input type="text" class="form-control form-control-custom" name="no_catatan_medik_rs_suami"
                                    value="<?php echo e($dataKia->layanan->no_catatan_medik_rs_suami ?? old('no_catatan_medik_rs_suami')); ?>" placeholder="No. Catatan Medik RS">
                            </div>
                        </div>
                    </div>
                    <div class="form-card">
                        <div class="form-card-title">Fasilitas Pelayanan Kesehatan - Anak</div>
                        <div class="form-row-custom">
                            <div>
                                <label class="form-label-custom">Puskesmas Domisili Anak</label>
                                <input type="text" class="form-control form-control-custom" name="puskesmas_domisili_anak"
                                    value="<?php echo e($dataKia->layanan->puskesmas_domisili_anak ?? old('puskesmas_domisili_anak')); ?>" placeholder="Puskesmas domisili">
                            </div>
                            <div>
                                <label class="form-label-custom">No. Catatan Medik RS Anak</label>
                                <input type="text" class="form-control form-control-custom" name="no_catatan_medik_rs_anak"
                                    value="<?php echo e($dataKia->layanan->no_catatan_medik_rs_anak ?? old('no_catatan_medik_rs_anak')); ?>" placeholder="No. Catatan Medik RS">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Content: Asuransi -->
                <div class="form-category-content" id="form_asuransi" style="display: none;">
                    <div class="form-card">
                        <div class="form-card-title">Asuransi - Ibu</div>
                        <div class="form-row-custom">
                            <div>
                                <label class="form-label-custom">Nama Asuransi</label>
                                <input type="text" class="form-control form-control-custom" name="asuransi_lain"
                                    value="<?php echo e($dataKia->layanan->asuransi_lain ?? old('asuransi_lain')); ?>" placeholder="Nama asuransi">
                            </div>
                            <div>
                                <label class="form-label-custom">No. Kartu Asuransi</label>
                                <input type="text" class="form-control form-control-custom" name="no_asuransi_lain"
                                    value="<?php echo e($dataKia->layanan->no_asuransi_lain ?? old('no_asuransi_lain')); ?>" placeholder="No. Kartu">
                            </div>
                            <div>
                                <label class="form-label-custom">Tanggal Berlaku</label>
                                <input type="date" class="form-control form-control-custom" name="tanggal_berlaku_asuransi_lain"
                                    value="<?php echo e($dataKia->layanan->tanggal_berlaku_asuransi_lain ?? old('tanggal_berlaku_asuransi_lain')); ?>">
                            </div>
                        </div>
                    </div>
                    <div class="form-card">
                        <div class="form-card-title">Asuransi - Suami</div>
                        <div class="form-row-custom">
                            <div>
                                <label class="form-label-custom">Nama Asuransi Suami</label>
                                <input type="text" class="form-control form-control-custom" name="asuransi_suami"
                                    value="<?php echo e($dataKia->layanan->asuransi_suami ?? old('asuransi_suami')); ?>" placeholder="Nama asuransi">
                            </div>
                            <div>
                                <label class="form-label-custom">No. Kartu Asuransi Suami</label>
                                <input type="text" class="form-control form-control-custom" name="no_asuransi_suami"
                                    value="<?php echo e($dataKia->layanan->no_asuransi_suami ?? old('no_asuransi_suami')); ?>" placeholder="No. Kartu">
                            </div>
                            <div>
                                <label class="form-label-custom">Tanggal Berlaku Suami</label>
                                <input type="date" class="form-control form-control-custom" name="tanggal_berlaku_asuransi_suami"
                                    value="<?php echo e($dataKia->layanan->tanggal_berlaku_asuransi_suami ?? old('tanggal_berlaku_asuransi_suami')); ?>">
                            </div>
                        </div>
                    </div>
                    <div class="form-card">
                        <div class="form-card-title">Asuransi - Anak</div>
                        <div class="form-row-custom">
                            <div>
                                <label class="form-label-custom">Nama Asuransi Anak</label>
                                <input type="text" class="form-control form-control-custom" name="asuransi_anak"
                                    value="<?php echo e($dataKia->layanan->asuransi_anak ?? old('asuransi_anak')); ?>" placeholder="Nama asuransi">
                            </div>
                            <div>
                                <label class="form-label-custom">No. Kartu Asuransi Anak</label>
                                <input type="text" class="form-control form-control-custom" name="no_asuransi_anak"
                                    value="<?php echo e($dataKia->layanan->no_asuransi_anak ?? old('no_asuransi_anak')); ?>" placeholder="No. Kartu">
                            </div>
                            <div>
                                <label class="form-label-custom">Tanggal Berlaku Anak</label>
                                <input type="date" class="form-control form-control-custom" name="tanggal_berlaku_asuransi_anak"
                                    value="<?php echo e($dataKia->layanan->tanggal_berlaku_asuransi_anak ?? old('tanggal_berlaku_asuransi_anak')); ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Content: Riwayat Kehamilan -->
                <div class="form-category-content" id="form_kehamilan" style="display: none;">
                    <div class="form-card">
                        <div class="form-card-title">Data Kehamilan Saat Ini</div>
                        <div class="form-row-custom">
                            <div>
                                <label class="form-label-custom">HPHT (Hari Pertama Haid Terakhir)</label>
                                <input type="date" class="form-control form-control-custom" name="hpht"
                                    value="<?php echo e($dataKia->riwayat->hpht ?? old('hpht')); ?>">
                            </div>
                            <div>
                                <label class="form-label-custom">HTP (Hari Perkiraan Lahir)</label>
                                <input type="date" class="form-control form-control-custom" name="htp"
                                    value="<?php echo e($dataKia->riwayat->htp ?? old('htp')); ?>">
                            </div>
                            <div>
                                <label class="form-label-custom">Tinggi Badan (cm)</label>
                                <input type="number" class="form-control form-control-custom" name="tinggi_badan"
                                    value="<?php echo e($dataKia->riwayat->tinggi_badan ?? old('tinggi_badan')); ?>" placeholder="150">
                            </div>
                        </div>
                        <div class="form-row-custom">
                            <div>
                                <label class="form-label-custom">Lingkar Lengan Atas (cm)</label>
                                <input type="number" class="form-control form-control-custom" name="lingkar_lengan_atas" step="0.1"
                                    value="<?php echo e($dataKia->riwayat->lingkar_lengan_atas ?? old('lingkar_lengan_atas')); ?>" placeholder="23.5">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Save Button -->
                <div class="d-flex justify-content-end mt-4 pt-4 border-top">
                    <button type="submit" class="btn-save-form">
                        <i class="bi bi-check-lg"></i>
                        Simpan Semua Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Toast Container -->
<div class="toast-container" id="toastContainer"></div>

<!-- Modal for PDF View -->
<div class="modal fade" id="pdfModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content" style="height: 90vh;">
            <div class="modal-header">
                <h5 class="modal-title">Buku KIA</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0" style="height: calc(100% - 60px);">
                <iframe id="pdfFrame" src="" style="width: 100%; height: 100%; border: none;"></iframe>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    // Show category tab content
    function showCategory(category) {
        // Hide all category contents
        document.querySelectorAll('.form-category-content').forEach(el => {
            el.style.display = 'none';
        });

        // Show selected category
        document.getElementById('form_' + category).style.display = 'block';

        // Update tab active state
        document.querySelectorAll('.category-tab').forEach(tab => {
            tab.classList.remove('active');
        });
        event.target.closest('.category-tab').classList.add('active');
    }

    // Open PDF inline in modal
    function openPdfInline() {
        const pdfUrl = '<?php echo e(route('pengguna.kia.pdf')); ?>';
        document.getElementById('pdfFrame').src = pdfUrl;
        const modal = new bootstrap.Modal(document.getElementById('pdfModal'));
        modal.show();
    }

    // Show toast notification
    function showToast(message, type = 'success') {
        const container = document.getElementById('toastContainer');
        const toast = document.createElement('div');
        toast.className = `custom-toast ${type}`;
        toast.innerHTML = `
            <div class="toast-icon">
                <i class="bi bi-${type === 'success' ? 'check-lg' : 'exclamation-triangle'}"></i>
            </div>
            <span class="toast-message">${message}</span>
        `;
        container.appendChild(toast);

        setTimeout(() => {
            toast.style.animation = 'slideInRight .3s ease reverse';
            setTimeout(() => toast.remove(), 300);
        }, 4000);
    }

    // Handle form submission
    document.getElementById('kiaForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const form = this;
        const formData = new FormData(form);
        const url = form.action;

        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': formData.get('_token'),
                'Accept': 'application/json',
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message || 'Data berhasil disimpan!', 'success');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Terjadi kesalahan. Silakan coba lagi.', 'error');
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('pengguna.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\MomSpire\resources\views/pengguna/bukuKIA.blade.php ENDPATH**/ ?>