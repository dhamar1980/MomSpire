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
    :root {
        --pengguna-primary: #e63980;
        --pengguna-primary-dark: #c41e5c;
        --pengguna-secondary: #00b894;
        --pengguna-purple: #6f42c1;
        --pengguna-ink: #1e293b;
        --pengguna-muted: #64748b;
        --gradient-primary: linear-gradient(135deg, #e63980 0%, #ff6b9d 100%);
        --gradient-secondary: linear-gradient(135deg, #00b894 0%, #00d4aa 100%);
        --shadow-sm: 0 2px 8px rgba(230, 57, 128, 0.08);
        --shadow-md: 0 4px 15px rgba(230, 57, 128, 0.12);
        --shadow-lg: 0 8px 25px rgba(230, 57, 128, 0.15);
    }

    /* Background - SAMA dengan dashboard */
    .buku-kia-shell {
        position: relative;
        isolation: isolate;
        background: linear-gradient(180deg, #ffffff 0%, #f0f4f8 100%);
        min-height: 100vh;
    }

    .buku-kia-shell::before {
        content: '';
        position: fixed;
        inset: 0;
        background:
            radial-gradient(circle at 12% 8%, rgba(230, 57, 128, 0.12), transparent 28%),
            radial-gradient(circle at 88% 14%, rgba(230, 57, 128, 0.08), transparent 26%),
            radial-gradient(circle at 20% 80%, rgba(0, 184, 148, 0.06), transparent 32%);
        z-index: -2;
        pointer-events: none;
    }

    .buku-kia-shell::after {
        content: '';
        position: fixed;
        inset: 0;
        background-image: linear-gradient(rgba(230, 57, 128, 0.012) 1px, transparent 1px), linear-gradient(90deg, rgba(230, 57, 128, 0.012) 1px, transparent 1px);
        background-size: 42px 42px;
        opacity: .3;
        pointer-events: none;
        z-index: -1;
    }

    .btn-back-page {
        background: #fff;
        border: 1px solid rgba(230, 57, 128, 0.2);
        border-radius: 999px;
        color: var(--pengguna-primary);
        font-weight: 700;
        padding: 10px 16px;
        box-shadow: var(--shadow-sm);
        transition: all .2s ease;
        transform: translateX(8px);
        margin-right: 6px;
    }

    .btn-back-page:hover {
        transform: translateX(8px) translateY(-2px);
        border-color: var(--pengguna-primary);
        box-shadow: var(--shadow-md);
        color: var(--pengguna-primary);
        background: #fff;
    }

    /* Main Container - soft pink card style */
    .buku-kia-container {
        padding: 28px 0;
    }

    .kia-hero {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        padding: 24px;
        margin-bottom: 24px;
        box-shadow: var(--shadow-sm);
    }

    .kia-hero h2 {
        font-size: 26px;
        font-weight: 800;
        margin-bottom: 4px;
        color: var(--pengguna-primary);
    }

    .kia-hero p {
        margin-bottom: 0;
        color: var(--pengguna-muted);
        font-size: .9rem;
    }

    /* Buku KIA Preview Section */
    .kia-preview-section {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        margin-bottom: 24px;
        overflow: hidden;
        box-shadow: var(--shadow-sm);
    }

    .kia-preview-header {
        background: linear-gradient(135deg, rgba(230, 57, 128, 0.06) 0%, rgba(230, 57, 128, 0.03) 100%);
        padding: 18px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid rgba(230, 57, 128, 0.1);
    }

    .kia-preview-header h4 {
        margin: 0;
        font-size: 1rem;
        font-weight: 800;
        color: var(--pengguna-primary);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .kia-preview-body {
        padding: 24px;
    }

    .kia-book-preview {
        background: linear-gradient(135deg, rgba(230, 57, 128, 0.05) 0%, rgba(230, 57, 128, 0.02) 100%);
        border: 2px solid rgba(230, 57, 128, 0.12);
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
        color: var(--pengguna-primary);
        margin-bottom: 16px;
    }

    .kia-book-preview h5 {
        font-weight: 800;
        color: var(--pengguna-primary);
        margin-bottom: 8px;
    }

    .kia-book-preview p {
        color: var(--pengguna-muted);
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
        border: 2px solid var(--pengguna-primary);
        color: var(--pengguna-primary);
    }

    .btn-preview-outline:hover {
        background: rgba(230, 57, 128, 0.08);
    }

    .btn-preview-filled {
        background: var(--gradient-primary);
        border: none;
        color: #fff;
    }

    .btn-preview-filled:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }

    /* Form Section Styles */
    .kia-form-section {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        margin-bottom: 24px;
        overflow: hidden;
        box-shadow: var(--shadow-sm);
    }

    .kia-form-header {
        background: linear-gradient(135deg, rgba(230, 57, 128, 0.06) 0%, rgba(230, 57, 128, 0.03) 100%);
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
        color: var(--pengguna-primary);
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
        border-bottom: 2px solid rgba(230, 57, 128, 0.08);
    }

    .category-tab {
        padding: 10px 18px;
        border-radius: 12px;
        font-size: .82rem;
        font-weight: 700;
        color: var(--pengguna-primary);
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
        box-shadow: var(--shadow-md);
    }

    /* Form Cards */
    .form-card {
        background: linear-gradient(180deg, #fdfafc 0%, #fff 100%);
        border: 1px solid rgba(230, 57, 128, 0.08);
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 20px;
    }

    .form-card:last-child {
        margin-bottom: 0;
    }

    .form-card-title {
        font-size: .9rem;
        font-weight: 800;
        color: var(--pengguna-primary);
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
        color: var(--pengguna-primary);
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
    }

    .form-control-custom:focus {
        border-color: var(--pengguna-primary);
        box-shadow: 0 0 0 3px rgba(230, 57, 128, 0.1);
        outline: none;
    }

    .form-select-custom {
        border: 1.5px solid rgba(230, 57, 128, 0.15);
        border-radius: 10px;
        padding: 10px 14px;
        font-size: .9rem;
        background: #fff;
        transition: border-color .2s ease, box-shadow .2s ease;
        width: 100%;
        cursor: pointer;
    }

    .form-select-custom:focus {
        border-color: var(--pengguna-primary);
        box-shadow: 0 0 0 3px rgba(230, 57, 128, 0.1);
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
        box-shadow: var(--shadow-lg);
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

    /* Buku KIA Selector Styles */
    .kia-selector-section {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        margin-bottom: 24px;
        overflow: hidden;
        box-shadow: var(--shadow-sm);
    }

    .kia-selector-header {
        background: linear-gradient(135deg, rgba(230, 57, 128, 0.06) 0%, rgba(230, 57, 128, 0.03) 100%);
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
        color: var(--pengguna-primary);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .kia-selector-body {
        padding: 24px;
    }

    .kia-card-list {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 16px;
    }

    .kia-card-item {
        background: linear-gradient(180deg, #fdfafc 0%, #fff 100%);
        border: 2px solid rgba(230, 57, 128, 0.1);
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
        border-color: var(--pengguna-primary);
        transform: translateY(-3px);
        box-shadow: var(--shadow-md);
    }

    .kia-card-item.active {
        border-color: var(--pengguna-primary);
        background: linear-gradient(180deg, rgba(230, 57, 128, 0.06) 0%, #fff 100%);
        box-shadow: var(--shadow-md);
    }

    .kia-card-item.active::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
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

    .kia-card-icon i {
        font-size: 1.5rem;
        color: var(--pengguna-primary);
    }

    .kia-card-title {
        font-weight: 800;
        font-size: 1rem;
        color: var(--pengguna-primary);
        margin-bottom: 6px;
    }

    .kia-card-subtitle {
        font-size: .82rem;
        color: var(--pengguna-muted);
        margin-bottom: 10px;
    }

    .kia-card-badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: .72rem;
        font-weight: 700;
    }

    .kia-card-badge.aktif {
        background: rgba(0, 184, 148, 0.12);
        color: var(--pengguna-secondary);
    }

    .kia-card-badge.draft {
        background: rgba(230, 57, 128, 0.1);
        color: var(--pengguna-primary);
    }

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

    .kia-card-active-indicator.hidden {
        display: none;
    }

    .kia-card-actions {
        border-color: rgba(230, 57, 128, 0.1) !important;
    }

    .btn-edit-kia {
        background: var(--gradient-primary);
        border: none;
        color: #fff;
        font-weight: 700;
        padding: 8px 16px;
        border-radius: 10px;
        transition: all .2s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .btn-edit-kia:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
        color: #fff;
    }

    .btn-download-kia {
        background: #fff;
        border: 2px solid var(--pengguna-primary);
        color: var(--pengguna-primary);
        font-weight: 700;
        padding: 8px 16px;
        border-radius: 10px;
        transition: all .2s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .btn-download-kia:hover {
        background: rgba(230, 57, 128, 0.06);
        transform: translateY(-2px);
        color: var(--pengguna-primary);
    }

    /* Preview Info */
    .preview-info {
        max-width: 600px;
        margin: 0 auto;
    }

    .preview-info-item {
        padding: 8px;
        background: rgba(255,255,255,0.7);
        border-radius: 8px;
    }

    .preview-info-item small {
        display: block;
        font-size: .72rem;
        margin-bottom: 2px;
    }

    /* Book Visual */
    .kia-book-visual {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 40px 20px;
    }

    /* KIA Book Content */
    .kia-book-content {
        max-height: 600px;
        overflow-y: auto;
        padding-right: 10px;
    }

    /* PDF Viewer */
    .kia-pdf-viewer {
        width: 100%;
        height: 600px;
        border: none;
        border-radius: 12px;
        background: #f8f9fa;
    }

    @media (max-width: 768px) {
        .kia-pdf-viewer {
            height: 400px;
        }
    }

    @media (max-width: 576px) {
        .kia-card-list {
            grid-template-columns: 1fr;
        }
    }

    /* Table Styling */
    .table {
        border-color: rgba(230, 57, 128, 0.08);
    }

    .table thead th {
        background: linear-gradient(135deg, rgba(230, 57, 128, 0.06) 0%, rgba(230, 57, 128, 0.03) 100%);
        color: var(--pengguna-primary);
        font-weight: 700;
        font-size: .78rem;
        text-transform: uppercase;
        letter-spacing: .03em;
        border-color: rgba(230, 57, 128, 0.1);
        padding: 10px 6px;
    }

    .table tbody td {
        padding: 6px;
        border-color: rgba(230, 57, 128, 0.06);
        vertical-align: middle;
    }

    .table tbody tr:hover {
        background: rgba(230, 57, 128, 0.03);
    }

    .table-light {
        background: linear-gradient(135deg, rgba(230, 57, 128, 0.06) 0%, rgba(230, 57, 128, 0.03) 100%);
    }

    /* Form Checkbox */
    .form-check-input:checked {
        background-color: var(--pengguna-primary);
        border-color: var(--pengguna-primary);
    }

    .form-check-input:focus {
        box-shadow: 0 0 0 3px rgba(230, 57, 128, 0.1);
        border-color: var(--pengguna-primary);
    }

    /* Section Headers in Form */
    .form-category-content h6 {
        color: var(--pengguna-primary);
        font-size: .95rem;
        font-weight: 700;
        margin-bottom: 12px;
        padding-bottom: 8px;
        border-bottom: 2px solid rgba(230, 57, 128, 0.08);
    }

    /* Border radius for nested elements */
    .border.rounded-3 {
        border-color: rgba(230, 57, 128, 0.1) !important;
        background: linear-gradient(180deg, rgba(230, 57, 128, 0.02) 0%, #fff 100%);
    }

    .border.rounded-3 h6 {
        color: var(--pengguna-primary);
    }

    /* Scrollbar styling for tables */
    .table-responsive {
        overflow-x: auto;
    }

    .table-responsive::-webkit-scrollbar {
        height: 8px;
    }

    .table-responsive::-webkit-scrollbar-track {
        background: rgba(230, 57, 128, 0.06);
        border-radius: 4px;
    }

    .table-responsive::-webkit-scrollbar-thumb {
        background: var(--pengguna-primary);
        border-radius: 4px;
    }

    /* HR divider */
    hr {
        border-color: rgba(230, 57, 128, 0.1);
    }

    /* Badge for active/success states */
    .badge-active {
        background: var(--gradient-primary);
        color: #fff;
    }

    /* Text helpers */
    .text-primary {
        color: var(--pengguna-primary) !important;
    }

    /* Form Control in Tables */
    .form-control-sm {
        font-size: .78rem;
        padding: 4px 8px;
        border-color: rgba(230, 57, 128, 0.15);
    }

    .form-control-sm:focus {
        border-color: var(--pengguna-primary);
        box-shadow: 0 0 0 2px rgba(230, 57, 128, 0.1);
    }

    /* Toast styling */
    .custom-toast.success {
        border-left: 4px solid var(--pengguna-primary);
    }

    .custom-toast.success .toast-icon {
        background: rgba(230, 57, 128, 0.15);
        color: var(--pengguna-primary);
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="buku-kia-shell">
    <!-- Hero Section -->
    <div class="kia-hero">
        <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
            <div>
                <h2>Daftar Buku KIA</h2>
            </div>
        </div>
    </div>

    <!-- Buku KIA Selector Section -->
    <div class="kia-selector-section">
        <div class="kia-selector-body">
            <div class="kia-card-list">
                <?php $__empty_1 = true; $__currentLoopData = $bukuKiaCards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $card): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="kia-card-item <?php echo e($card['is_active'] ? 'active' : ''); ?>" onclick="pilihBukuKia(<?php echo e($card['id']); ?>)">
                    <?php if($card['is_active']): ?>
                    <div class="kia-card-active-indicator">
                        <i class="bi bi-check"></i>
                    </div>
                    <?php endif; ?>
                    <div class="kia-card-icon">
                        <i class="bi bi-journal-bookmark-fill"></i>
                    </div>
                    <div class="kia-card-title"><?php echo e($card['label']); ?></div>
                    <?php if($card['nama_anak']): ?>
                    <div class="kia-card-subtitle">Anak: <?php echo e($card['nama_anak']); ?></div>
                    <?php endif; ?>
                    <span class="kia-card-badge <?php echo e(strtolower($card['status'])); ?>">
                        <i class="bi <?php echo e($card['status'] == 'Aktif' ? 'bi-check-circle-fill' : 'bi-clock'); ?> me-1"></i>
                        <?php echo e($card['status']); ?>

                    </span>

                    <?php if($card['is_active']): ?>
                    <div class="kia-card-actions mt-3 pt-3 border-top">
                        <a href="#form-section" class="btn btn-sm btn-edit-kia w-100 mb-2" onclick="event.stopPropagation();">
                            <i class="bi bi-pencil-square me-1"></i>
                            Edit Buku KIA
                        </a>
                        <a href="<?php echo e(route('pengguna.kia.download', $card['id'])); ?>" class="btn btn-sm btn-download-kia w-100" onclick="event.stopPropagation();">
                            <i class="bi bi-download me-1"></i>
                            Download PDF
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Preview Section - Isi Buku KIA (PDF) -->
    <div class="kia-preview-section" id="preview-section">
        <div class="kia-preview-header">
            <h4>
                <i class="bi bi-book"></i>
                Isi Buku KIA - <?php echo e($activeKia->ibu->nama ?? 'Data Pengguna'); ?>

            </h4>
        </div>
        <div class="kia-preview-body">
            <iframe
                src="<?php echo e(route('pengguna.kia.Buku-KIA', $activeKia->id)); ?>"
                class="kia-pdf-viewer"
                title="Buku KIA">
            </iframe>
        </div>
    </div>

    <!-- All Form Section -->
    <div class="kia-form-section" id="form-section">
        <div class="kia-form-header">
            <h4>
                <i class="bi bi-pencil-square"></i>
                Form Input Data Buku KIA
            </h4>
        </div>
        <div class="kia-form-body">
            <form id="kiaForm" action="<?php echo e(route('pengguna.kia.save_wizard')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="data_kia_id" value="<?php echo e($activeKia->id); ?>">

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
                    <button type="button" class="category-tab" onclick="showCategory('catatan_ttd')">
                        <i class="bi bi-capsule"></i>
                        Catatan TTD
                    </button>
                    <button type="button" class="category-tab" onclick="showCategory('pemantauan_mingguan')">
                        <i class="bi bi-calendar-week"></i>
                        Pemantauan Mingguan
                    </button>
                    <button type="button" class="category-tab" onclick="showCategory('kelas_ibu')">
                        <i class="bi bi-mortarboard"></i>
                        Kelas Ibu Hamil
                    </button>
                    <button type="button" class="category-tab" onclick="showCategory('persiapan')">
                        <i class="bi bi-truck"></i>
                        Persiapan Melahirkan
                    </button>
                    <button type="button" class="category-tab" onclick="showCategory('pemantauan_nifas')">
                        <i class="bi bi-droplet"></i>
                        Pemantauan Nifas
                    </button>
                    <button type="button" class="category-tab" onclick="showCategory('kb')">
                        <i class="bi bi-flower1"></i>
                        KB
                    </button>
                    <button type="button" class="category-tab" onclick="showCategory('bayi_baru')">
                        <i class="bi bi-baby"></i>
                        Bayi Baru Lahir
                    </button>
                    <button type="button" class="category-tab" onclick="showCategory('pemantauan_bayi')">
                        <i class="bi bi-thermometer"></i>
                        Pemantauan Bayi
                    </button>
                    <button type="button" class="category-tab" onclick="showCategory('warna_tinja')">
                        <i class="bi bi-palette"></i>
                        Warna Tinja
                    </button>
                    <button type="button" class="category-tab" onclick="showCategory('kelas_balita')">
                        <i class="bi bi-people"></i>
                        Kelas Balita
                    </button>
                    <button type="button" class="category-tab" onclick="showCategory('perkembangan_bayi')">
                        <i class="bi bi-graph-up"></i>
                        Perkembangan Bayi
                    </button>
                    <button type="button" class="category-tab" onclick="showCategory('kesehatan_lingkungan')">
                        <i class="bi bi-house"></i>
                        Kesehatan Lingkungan
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

                <!-- Form Content: Catatan TTD -->
                <div class="form-category-content" id="form_catatan_ttd" style="display: none;">
                    <div class="form-card">
                        <div class="form-card-title">Catatan Minum Tablet Tambah Darah (TTD)</div>
                        <p class="text-muted small mb-3">Catat minum TTD setiap hari. Centang √ jika sudah minum pada tanggal tersebut.</p>
                        <div class="row">
                            <?php for($bulan = 1; $bulan <= 9; $bulan++): ?>
                            <div class="col-md-4 mb-4">
                                <div class="border rounded-3 p-3">
                                    <h6 class="fw-bold text-center mb-3" style="color: #be185d;">Bulan ke-<?php echo e($bulan); ?></h6>
                                    <div class="d-flex justify-content-between mb-2">
                                        <label class="form-label-custom small">Usia Kehamilan (minggu)</label>
                                        <input type="number" class="form-control form-control-sm w-50" name="ttd_bulan_<?php echo e($bulan); ?>_usia"
                                            placeholder="Contoh: 12" style="font-size: 0.8rem;">
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <label class="form-label-custom small">Bulan/Tahun</label>
                                        <input type="text" class="form-control form-control-sm w-50" name="ttd_bulan_<?php echo e($bulan); ?>_bulan_tahun"
                                            placeholder="Contoh: Januari 2026" style="font-size: 0.8rem;">
                                    </div>
                                    <div class="border-top pt-2 mt-2">
                                        <div class="row g-1">
                                            <?php for($hari = 1; $hari <= 31; $hari++): ?>
                                            <div class="col-1 text-center p-0">
                                                <small class="d-block" style="font-size: 0.6rem; color: #999;"><?php echo e($hari); ?></small>
                                                <div class="form-check d-flex justify-content-center p-0">
                                                    <input class="form-check-input" type="checkbox" name="ttd_bulan_<?php echo e($bulan); ?>_h<?php echo e($hari); ?>" id="ttd_bulan_<?php echo e($bulan); ?>_h<?php echo e($hari); ?>" style="width: 14px; height: 14px;">
                                                </div>
                                            </div>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endfor; ?>
                        </div>
                    </div>
                </div>

                <!-- Form Content: Pemantauan Mingguan -->
                <div class="form-category-content" id="form_pemantauan_mingguan" style="display: none;">
                    <div class="form-card">
                        <div class="form-card-title">Pemantauan Ibu Hamil Mingguan</div>
                        <p class="text-muted small mb-3">Pemeriksaan dan konsultasi yang dilakukan setiap minggu selama kehamilan.</p>
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" style="width: 60px;">Minggu ke</th>
                                        <th>Pemeriksaan Kehamilan</th>
                                        <th>Kelas Ibu Hamil</th>
                                        <th>Demam >2 Hari</th>
                                        <th>Pusing/Sakit Kepala</th>
                                        <th>Sulit Tidur/Cemas</th>
                                        <th>Risiko TB</th>
                                        <th>Gerakan Bayi Berkurang</th>
                                        <th>Nyeri Perut Hebat</th>
                                        <th>Keluar Cairan Lahir</th>
                                        <th>Sakit Saat Kencing</th>
                                        <th>Diare Berulang</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php for($minggu = 1; $minggu <= 42; $minggu++): ?>
                                    <tr>
                                        <td class="text-center fw-bold"><?php echo e($minggu); ?></td>
                                        <?php for($col = 1; $col <= 10; $col++): ?>
                                        <td class="text-center">
                                            <input class="form-check-input" type="checkbox" name="pemantauan_minggu_<?php echo e($minggu); ?>_<?php echo e($col); ?>">
                                        </td>
                                        <?php endfor; ?>
                                    </tr>
                                    <?php endfor; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Form Content: Kelas Ibu Hamil -->
                <div class="form-category-content" id="form_kelas_ibu" style="display: none;">
                    <div class="form-card">
                        <div class="form-card-title">Kelas Ibu Hamil - Absensi Kehadiran</div>
                        <p class="text-muted small mb-3">Catat kehadiran Ibu Hamil dalam kelas.</p>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center">Sesi ke</th>
                                        <th>Tanggal</th>
                                        <th>Kader/Tenaga Kesehatan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php for($sesi = 1; $sesi <= 9; $sesi++): ?>
                                    <tr>
                                        <td class="text-center fw-bold"><?php echo e($sesi); ?></td>
                                        <td>
                                            <input type="date" class="form-control form-control-custom" name="kelas_ibu_tanggal_<?php echo e($sesi); ?>">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control form-control-custom" name="kelas_ibu_kader_<?php echo e($sesi); ?>" placeholder="Nama kader/nakes">
                                        </td>
                                    </tr>
                                    <?php endfor; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Form Content: Persiapan Melahirkan -->
                <div class="form-category-content" id="form_persiapan" style="display: none;">
                    <div class="form-card">
                        <div class="form-card-title">Persiapan Melahirkan</div>
                        <div class="form-row-custom">
                            <div>
                                <label class="form-label-custom">HPL Tanggal</label>
                                <input type="number" class="form-control form-control-custom" name="hpl_tanggal" min="1" max="31" placeholder="Tanggal">
                            </div>
                            <div>
                                <label class="form-label-custom">HPL Bulan</label>
                                <input type="number" class="form-control form-control-custom" name="hpl_bulan" min="1" max="12" placeholder="Bulan">
                            </div>
                            <div>
                                <label class="form-label-custom">HPL Tahun</label>
                                <input type="number" class="form-control form-control-custom" name="hpl_tahun" min="2024" placeholder="Tahun">
                            </div>
                        </div>
                        <hr>
                        <h6 class="fw-bold mb-3">Persiapan Checklist</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="tanya_tanggal_perkiraan" id="tanya_tanggal_perkiraan">
                                    <label class="form-check-label" for="tanya_tanggal_perkiraan">Tanya tanggal perkiraan melahirkan</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="minta_dampingi" id="minta_dampingi">
                                    <label class="form-check-label" for="minta_dampaikan">Minta keluarga/dukun accompagngi</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="siap_tabungan" id="siap_tabungan">
                                    <label class="form-check-label" for="siap_tabungan">Siap menyimpan uang</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="kartu_jkn" id="kartu_jkn">
                                    <label class="form-check-label" for="kartu_jkn">Siap membawa kartu JKN/BPJS</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="tempat_melahirkan" id="tempat_melahirkan">
                                    <label class="form-check-label" for="tempat_melahirkan">Tahu tempat melahirkan</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="siap_ktp_kk" id="siap_ktp_kk">
                                    <label class="form-check-label" for="siap_ktp_kk">Siap membawa KTP dan KK</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="siap_pendonor" id="siap_pendonor">
                                    <label class="form-check-label" for="siap_pendonor">Siap mencari donor darah</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="siap_kendaraan" id="siap_kendaraan">
                                    <label class="form-check-label" for="siap_kendaraan">Siap transportasi ke faskes</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="sepakat_stiker_p4k" id="sepakat_stiker_p4k">
                                    <label class="form-check-label" for="sepakat_stiker_p4k">Sepakat pasang stiker P4K</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="rencana_kb" id="rencana_kb">
                                    <label class="form-check-label" for="rencana_kb">Rencanakan KB setelah melahirkan</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Content: Pemantauan Nifas -->
                <div class="form-category-content" id="form_pemantauan_nifas" style="display: none;">
                    <div class="form-card">
                        <div class="form-card-title">Pemantauan Ibu Nifas</div>
                        <p class="text-muted small mb-3">Pemantauan ibu setelah melahirkan selama 42 hari (6 minggu).</p>
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" style="width: 80px;">Hari ke</th>
                                        <th>Pemeriksaan Nifas</th>
                                        <th>Konsumsi Vitamin A</th>
                                        <th>Konsumsi TTD</th>
                                        <th>Pemenuhan Gizi</th>
                                        <th>Masalah Jiwa</th>
                                        <th>Demam</th>
                                        <th>Sakit Kepala</th>
                                        <th>Pandangan Kabur</th>
                                        <th>Nyeri Ulu Hati</th>
                                        <th>Jantung Berdebar</th>
                                        <th>Keluar Cairan Lahir</th>
                                        <th>Napas Pendek</th>
                                        <th>Payudara Bengkak</th>
                                        <th>Gangguan BAK</th>
                                        <th>Kelamin Bengkak</th>
                                        <th>Darah Nifas Berbau</th>
                                        <th>Pendarahan Hebat</th>
                                        <th>Keputihan</th>
                                        <th>Paraf Kader/Nakes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php for($hari = 1; $hari <= 42; $hari++): ?>
                                    <tr>
                                        <td class="text-center fw-bold"><?php echo e($hari); ?></td>
                                        <?php for($col = 1; $col <= 18; $col++): ?>
                                        <td class="text-center">
                                            <input class="form-check-input" type="checkbox" name="nifas_hari_<?php echo e($hari); ?>_<?php echo e($col); ?>">
                                        </td>
                                        <?php endfor; ?>
                                        <td>
                                            <input type="text" class="form-control form-control-sm" name="nifas_paraf_<?php echo e($hari); ?>" placeholder="Paraf">
                                        </td>
                                    </tr>
                                    <?php endfor; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Form Content: KB -->
                <div class="form-category-content" id="form_kb" style="display: none;">
                    <div class="form-card">
                        <div class="form-card-title">Rencana Keluarga Berencana (KB)</div>
                        <div class="form-row-custom">
                            <div>
                                <label class="form-label-custom">Metode KB yang Dipilih</label>
                                <select class="form-select form-select-custom" name="metode_kb">
                                    <option value="">Pilih Metode</option>
                                    <option value="MOW">MOW (Tubektomi)</option>
                                    <option value="MOP">MOP (Vasektomi)</option>
                                    <option value="IUD">IUD (Alat Kontrasepsi Dalam Rahim)</option>
                                    <option value="Implant">Implant (Kontrasepsi Implant)</option>
                                    <option value="Suntik">Suntik KB</option>
                                    <option value="Pil">Pil KB</option>
                                    <option value="Kondom">Kondom</option>
                                    <option value="Metode Amenore Laktasi">Metode Amenore Laktasi (MAL)</option>
                                    <option value="Pil Progestin">Pil Progestin (Mini Pil)</option>
                                </select>
                            </div>
                        </div>
                        <hr>
                        <div class="form-row-custom">
                            <div>
                                <label class="form-label-custom">Paraf Ibu</label>
                                <input type="text" class="form-control form-control-custom" name="paraf_ibu" placeholder="Paraf/Tanda tangan ibu">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Content: Bayi Baru Lahir -->
                <div class="form-category-content" id="form_bayi_baru" style="display: none;">
                    <div class="form-card">
                        <div class="form-card-title">Pemeriksaan Bayi Baru Lahir</div>
                        <p class="text-muted small mb-3">Ceklist pemeriksaan bayi baru lahir pada waktu tertentu.</p>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="border rounded-3 p-3 mb-3">
                                    <h6 class="fw-bold mb-2">0 - 6 Jam</h6>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="bayi_0_6_jam" id="bayi_0_6_jam">
                                        <label class="form-check-label" for="bayi_0_6_jam">Pemeriksaan pada 0-6 jam setelah lahir</label>
                                    </div>
                                </div>
                                <div class="border rounded-3 p-3 mb-3">
                                    <h6 class="fw-bold mb-2">6 - 48 Jam</h6>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="bayi_6_48_jam" id="bayi_6_48_jam">
                                        <label class="form-check-label" for="bayi_6_48_jam">Pemeriksaan pada 6-48 jam setelah lahir</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="border rounded-3 p-3 mb-3">
                                    <h6 class="fw-bold mb-2">3 - 7 Hari</h6>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="bayi_hari_3_7" id="bayi_hari_3_7">
                                        <label class="form-check-label" for="bayi_hari_3_7">Pemeriksaan pada 3-7 hari setelah lahir</label>
                                    </div>
                                </div>
                                <div class="border rounded-3 p-3 mb-3">
                                    <h6 class="fw-bold mb-2">8 - 28 Hari</h6>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="bayi_hari_8_28" id="bayi_hari_8_28">
                                        <label class="form-check-label" for="bayi_hari_8_28">Pemeriksaan pada 8-28 hari setelah lahir</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Content: Pemantauan Bayi -->
                <div class="form-category-content" id="form_pemantauan_bayi" style="display: none;">
                    <div class="form-card">
                        <div class="form-card-title">Pemantauan Harian Bayi</div>
                        <p class="text-muted small mb-3">Catatan pemantauan kesehatan bayi baru lahir setiap hari.</p>
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" style="width: 80px;">Hari ke</th>
                                        <th>Sesak Napas</th>
                                        <th>Aktivitas Lemah</th>
                                        <th>Warna Kulit Biru</th>
                                        <th>Hisapan Lemah</th>
                                        <th>Kejang</th>
                                        <th>Suhu Abnormal</th>
                                        <th>BAB Abnormal</th>
                                        <th>Kencing Sedikit</th>
                                        <th>Tali Pusat Merah</th>
                                        <th>Mata Merah</th>
                                        <th>Kulit Bintil</th>
                                        <th>Belum Imunisasi</th>
                                        <th>Paraf</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php for($hari = 1; $hari <= 28; $hari++): ?>
                                    <tr>
                                        <td class="text-center fw-bold"><?php echo e($hari); ?></td>
                                        <?php for($col = 1; $col <= 11; $col++): ?>
                                        <td class="text-center">
                                            <input class="form-check-input" type="checkbox" name="bayi_hari_<?php echo e($hari); ?>_<?php echo e($col); ?>">
                                        </td>
                                        <?php endfor; ?>
                                        <td>
                                            <input type="text" class="form-control form-control-sm" name="bayi_paraf_<?php echo e($hari); ?>" placeholder="Paraf">
                                        </td>
                                    </tr>
                                    <?php endfor; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Form Content: Warna Tinja -->
                <div class="form-category-content" id="form_warna_tinja" style="display: none;">
                    <div class="form-card">
                        <div class="form-card-title">Pemantauan Warna Tinja Bayi</div>
                        <p class="text-muted small mb-3">Catat perubahan warna tinja bayi untuk mengetahui perkembangan pencernaan.</p>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Usia Bayi</th>
                                        <th>Tanggal</th>
                                        <th>Nomor Urut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="fw-bold">2 Minggu</td>
                                        <td><input type="date" class="form-control form-control-custom" name="tinja_2_minggu_tanggal"></td>
                                        <td><input type="number" class="form-control form-control-custom" name="tinja_2_minggu_nomor" placeholder="Nomor"></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">1 Bulan</td>
                                        <td><input type="date" class="form-control form-control-custom" name="tinja_1_bulan_tanggal"></td>
                                        <td><input type="number" class="form-control form-control-custom" name="tinja_1_bulan_nomor" placeholder="Nomor"></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">2-4 Bulan</td>
                                        <td><input type="date" class="form-control form-control-custom" name="tinja_2_4_bulan_tanggal"></td>
                                        <td><input type="number" class="form-control form-control-custom" name="tinja_2_4_bulan_nomor" placeholder="Nomor"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Form Content: Kelas Balita -->
                <div class="form-category-content" id="form_kelas_balita" style="display: none;">
                    <div class="form-card">
                        <div class="form-card-title">Kelas Balita - Absensi Kehadiran</div>
                        <p class="text-muted small mb-3">Catat kehadiran balita dalam kelas.</p>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center">Sesi ke</th>
                                        <th>Tanggal</th>
                                        <th>Kader/Tenaga Kesehatan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php for($sesi = 1; $sesi <= 9; $sesi++): ?>
                                    <tr>
                                        <td class="text-center fw-bold"><?php echo e($sesi); ?></td>
                                        <td>
                                            <input type="date" class="form-control form-control-custom" name="kelas_balita_tanggal_<?php echo e($sesi); ?>">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control form-control-custom" name="kelas_balita_kader_<?php echo e($sesi); ?>" placeholder="Nama kader/nakes">
                                        </td>
                                    </tr>
                                    <?php endfor; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Form Content: Perkembangan Bayi -->
                <div class="form-category-content" id="form_perkembangan_bayi" style="display: none;">
                    <div class="form-card">
                        <div class="form-card-title">Pemenuhan Tumbuh Kembang Bayi dan Anak</div>
                        <p class="text-muted small mb-3">Pantau perkembangan bayi dan anak sesuai usia.</p>

                        <!-- Mingguan Bayi (0-2 bulan) -->
                        <h6 class="fw-bold text-primary mb-3">Pemantauan Mingguan (0-2 Bulan)</h6>
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" style="width: 60px;">Minggu</th>
                                        <th>Sesak Napas</th>
                                        <th>Batuk</th>
                                        <th>Suhu Abnormal</th>
                                        <th>BAB Sering</th>
                                        <th>Kencing Sedikit</th>
                                        <th>Kulit Biru</th>
                                        <th>Aktivitas Lemah</th>
                                        <th>Hisapan Lemah</th>
                                        <th>Tidak Makan</th>
                                        <th>Paraf</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php for($m = 1; $m <= 8; $m++): ?>
                                    <tr>
                                        <td class="text-center fw-bold"><?php echo e($m); ?></td>
                                        <?php for($c = 1; $c <= 9; $c++): ?>
                                        <td class="text-center"><input class="form-check-input" type="checkbox" name="mingguan_bayi_<?php echo e($m); ?>_<?php echo e($c); ?>"></td>
                                        <?php endfor; ?>
                                        <td><input type="text" class="form-control form-control-sm" name="mingguan_bayi_paraf_<?php echo e($m); ?>"></td>
                                    </tr>
                                    <?php endfor; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Perkembangan Bayi 0-2 Bulan -->
                        <h6 class="fw-bold text-primary mb-3">Perkembangan Bayi 0-2 Bulan</h6>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="bayi_angkat_kepala_45"><label class="form-check-label">Mengangkat kepala 45 derajat</label></div>
                                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="bayi_gerakkan_kepala"><label class="form-check-label">Menggerakkan kepala</label></div>
                                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="bayi_tatap_wajah"><label class="form-check-label">Menatap wajah ibu</label></div>
                                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="bayi_ngoceh"><label class="form-check-label">Mengoceh</label></div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="bayi_tertawa_keras"><label class="form-check-label">Tertawa keras</label></div>
                                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="bayi_terkejut_suara"><label class="form-check-label">Terkejut suara keras</label></div>
                                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="bayi_tersenyum"><label class="form-check-label">Tersenyum</label></div>
                                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="bayi_mengenal_ibu"><label class="form-check-label">Mengenal ibu</label></div>
                            </div>
                        </div>

                        <!-- Bulanan Bayi 2-6 Bulan -->
                        <h6 class="fw-bold text-primary mb-3">Pemantauan Bulanan (2-6 Bulan)</h6>
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" style="width: 60px;">Bulan</th>
                                        <th>Sesak Napas</th>
                                        <th>Batuk</th>
                                        <th>Suhu Abnormal</th>
                                        <th>BAB Sering</th>
                                        <th>Kencing Sedikit</th>
                                        <th>Kulit Biru</th>
                                        <th>Aktivitas Lemah</th>
                                        <th>Hisapan Lemah</th>
                                        <th>Tidak Makan</th>
                                        <th>Paraf</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php for($b = 1; $b <= 6; $b++): ?>
                                    <tr>
                                        <td class="text-center fw-bold"><?php echo e($b); ?></td>
                                        <?php for($c = 1; $c <= 9; $c++): ?>
                                        <td class="text-center"><input class="form-check-input" type="checkbox" name="bulanan_bayi_<?php echo e($b); ?>_<?php echo e($c); ?>"></td>
                                        <?php endfor; ?>
                                        <td><input type="text" class="form-control form-control-sm" name="bulanan_bayi_paraf_<?php echo e($b); ?>"></td>
                                    </tr>
                                    <?php endfor; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Perkembangan Bayi 3-6 Bulan -->
                        <h6 class="fw-bold text-primary mb-3">Perkembangan Bayi 3-6 Bulan</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="bayi_berbalik"><label class="form-check-label">Berbalik sendiri</label></div>
                                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="bayi_kepala_tegak_90"><label class="form-check-label">Kepala tegak 90°</label></div>
                                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="bayi_kepala_stabil"><label class="form-check-label">Kepala stabil</label></div>
                                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="bayi_genggam_mainan"><label class="form-check-label">Menggenggam mainan</label></div>
                                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="bayi_raih_benda"><label class="form-check-label">Meraih benda</label></div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="bayi_amati_tangan"><label class="form-check-label">Memperhatikan tangan</label></div>
                                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="bayi_luas_pandang"><label class="form-check-label">Luas pandang 180°</label></div>
                                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="bayi_arah_mata"><label class="form-check-label">Mengarahkan mata</label></div>
                                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="bayi_suara_gembira"><label class="form-check-label">Suara gembira</label></div>
                                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="bayi_senyum_mainan"><label class="form-check-label">Senyum melihat mainan</label></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Content: Kesehatan Lingkungan -->
                <div class="form-category-content" id="form_kesehatan_lingkungan" style="display: none;">
                    <div class="form-card">
                        <div class="form-card-title">Kondisi Kesehatan Lingkungan</div>
                        <p class="text-muted small mb-3">Pastikan rumah dan lingkungan sehat untuk ibu dan anak.</p>

                        <!-- BAB -->
                        <h6 class="fw-bold text-primary mb-2">Pencemaran BAB</h6>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="bab_sembarangan"><label class="form-check-label">BAB di sembarangan</label></div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="bab_jamban_sendiri"><label class="form-check-label">BAB di jamban sendiri</label></div>
                            </div>
                        </div>

                        <!-- Penampung -->
                        <h6 class="fw-bold text-primary mb-2">Tempat Penampungan Sampah</h6>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="penampung_tangki_septik"><label class="form-check-label">Tangki septik</label></div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="penampung_cubluk"><label class="form-check-label">Cubluk</label></div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="penampung_drainase"><label class="form-check-label">Drainase</label></div>
                            </div>
                        </div>
                        <div class="form-check mb-3"><input class="form-check-input" type="checkbox" name="kloset_leher_angsa"><label class="form-check-label">Kloset leher angsa</label></div>

                        <!-- CTPS -->
                        <h6 class="fw-bold text-primary mb-2">Cuci Tangan Pakai Sabun (CTPS)</h6>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="ctps_sarana"><label class="form-check-label">Sarana CTPS tersedia</label></div>
                                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="ctps_air_mengalir"><label class="form-check-label">Air mengalir</label></div>
                                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="ctps_sabun"><label class="form-check-label">Sabun tersedia</label></div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="ctps_sebelum_makan"><label class="form-check-label">Sebelum makan</label></div>
                                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="ctps_sebelum_mengolah"><label class="form-check-label">Sebelum mengolah</label></div>
                                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="ctps_sebelum_menyusui"><label class="form-check-label">Sebelum menyusui</label></div>
                                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="ctps_setelah_bab"><label class="form-check-label">Setelah BAB</label></div>
                            </div>
                        </div>

                        <!-- Sumber Air -->
                        <h6 class="fw-bold text-primary mb-2">Sumber Air Bersih</h6>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="sumber_air_pipa"><label class="form-check-label">Air PAM/perpipan</label></div>
                                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="sumber_air_kran"><label class="form-check-label">Kran/pompa</label></div>
                                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="sumber_air_sumur"><label class="form-check-label">Sumur terlindung</label></div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="sumber_air_mata_air"><label class="form-check-label">Mata air terlindung</label></div>
                                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="sumber_air_sungai"><label class="form-check-label">Sungai</label></div>
                                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="sumber_air_danau"><label class="form-check-label">Danau/Waduk</label></div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="sumber_air_hujan"><label class="form-check-label">Air hujan</label></div>
                                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="sumber_air_kolam"><label class="form-check-label">Kolam/Irigasi</label></div>
                            </div>
                        </div>

                        <!-- Kelola Air -->
                        <h6 class="fw-bold text-primary mb-2">Pengelolaan Air</h6>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="kelola_air_rebus"><label class="form-check-label">Air dimasak/direbus</label></div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="kelola_air_endap_saring"><label class="form-check-label">Diendapkan/disaring</label></div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="kelola_air_wadah_tertutup"><label class="form-check-label">Wadah tertutup</label></div>
                            </div>
                        </div>

                        <!-- Kelola Makanan -->
                        <h6 class="fw-bold text-primary mb-2">Pengelolaan Makanan</h6>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="kelola_makanan_tertutup"><label class="form-check-label">Makanan tertutup</label></div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="kelola_makanan_jauh"><label class="form-check-label">Jauh dari bahan berbahaya</label></div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="kelola_makanan_baik"><label class="form-check-label">Diolah baik & benar</label></div>
                            </div>
                        </div>

                        <!-- Sampah -->
                        <h6 class="fw-bold text-primary mb-2">Pengelolaan Sampah</h6>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="sampah_tidak_berserakan"><label class="form-check-label">Tidak berserakan</label></div>
                                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="sampah_tempat_tertutup"><label class="form-check-label">Tempat sampah tertutup</label></div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="sampah_dipilah"><label class="form-check-label">Sampah dipilah</label></div>
                                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="sampah_tidak_dibakar"><label class="form-check-label">Tidak dibakar</label></div>
                            </div>
                        </div>

                        <!-- Limbah -->
                        <h6 class="fw-bold text-primary mb-2">Pengelolaan Limbah</h6>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="limbah_tidak_menggenang"><label class="form-check-label">Tidak menggenang</label></div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="limbah_saluran_tertutup"><label class="form-check-label">Saluran tertutup</label></div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="limbah_terhubung_resapan"><label class="form-check-label">Terhubung resapan</label></div>
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
        const activeKiaId = <?php echo e($activeKia->id); ?>;
        const pdfUrl = '/pengguna/kia/' + activeKiaId + '/pdf';
        document.getElementById('pdfFrame').src = pdfUrl;
        const modal = new bootstrap.Modal(document.getElementById('pdfModal'));
        modal.show();
    }

    // Pilih Buku KIA
    function pilihBukuKia(id) {
        window.location.href = '/pengguna/buku-kia/' + id;
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
            } else {
                showToast(data.message || 'Terjadi kesalahan.', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Terjadi kesalahan jaringan atau server.', 'error');
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('pengguna.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\MomSpire\resources\views\pengguna\bukuKIA.blade.php ENDPATH**/ ?>