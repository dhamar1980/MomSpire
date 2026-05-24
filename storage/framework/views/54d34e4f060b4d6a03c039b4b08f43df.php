<?php $__env->startSection('title', 'Manajemen Artikel - MomSpire'); ?>
<?php $__env->startSection('header_title', 'Manajemen Artikel'); ?>
<?php $__env->startSection('header_subtitle', 'Tambah dan kelola artikel edukasi yang tampil di halaman pengguna'); ?>

<?php $__env->startSection('content'); ?>
    <?php
        $categoryLabels = [
            'umum' => 'Umum',
            'trimester_1' => 'Trimester 1',
            'trimester_2' => 'Trimester 2',
            'trimester_3' => 'Trimester 3',
        ];

        $articleStats = $articleStats ?? [
            'total' => 0,
            'trimester1' => 0,
            'trimester2' => 0,
            'trimester3' => 0,
        ];
    ?>

    <div class="row g-4 mb-4">
        <div class="col-md-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon orange"><i class="bi bi-journal-richtext"></i></div>
                <div class="stat-info">
                    <h3 class="stat-value"><?php echo e($articleStats['total']); ?></h3>
                    <p class="stat-label">Total Artikel</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon blue"><i class="bi bi-link-45deg"></i></div>
                <div class="stat-info">
                    <h3 class="stat-value"><?php echo e($articleStats['trimester1']); ?></h3>
                    <p class="stat-label">Trimester 1</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon green"><i class="bi bi-link-45deg"></i></div>
                <div class="stat-info">
                    <h3 class="stat-value"><?php echo e($articleStats['trimester2']); ?></h3>
                    <p class="stat-label">Trimester 2</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon pink"><i class="bi bi-link-45deg"></i></div>
                <div class="stat-info">
                    <h3 class="stat-value"><?php echo e($articleStats['trimester3']); ?></h3>
                    <p class="stat-label">Trimester 3</p>
                </div>
            </div>
        </div>
        <div class="col-12">
            <button class="quick-action w-100 h-100" data-bs-toggle="modal" data-bs-target="#addArticleModal">
                <div class="qa-icon"><i class="bi bi-file-earmark-plus-fill"></i></div>
                <span>Tambah Artikel Edukasi</span>
            </button>
        </div>
    </div>

    <div class="admin-card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                <h5 class="mb-0"><i class="bi bi-journal-text me-2"></i>Daftar Artikel</h5>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="articleTable">
                    <thead>
                        <tr>
                            <th>Judul</th>
                            <th>Kategori</th>
                            <th>Ringkasan</th>
                            <th>Usia Kehamilan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $articles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $article): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php
                                $categoryLabel = $categoryLabels[$article->category] ?? ucfirst(str_replace('_', ' ', $article->category));
                                $weekLabel = $article->min_week && $article->max_week
                                    ? $article->min_week . '-' . $article->max_week . ' minggu'
                                    : 'Umum';
                            ?>
                            <tr>
                                <td>
                                    <div class="fw-semibold"><?php echo e($article->title); ?></div>
                                    <div class="small text-muted"><?php echo e($article->article_url ?: 'Tanpa tautan'); ?></div>
                                </td>
                                <td><span class="badge text-bg-light"><?php echo e($categoryLabel); ?></span></td>
                                <td><?php echo e(\Illuminate\Support\Str::limit($article->summary, 100)); ?></td>
                                <td><?php echo e($weekLabel); ?></td>
                                <td>
                                    <?php if($article->article_url): ?>
                                        <a href="<?php echo e($article->article_url); ?>" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-primary me-1">
                                            <i class="bi bi-box-arrow-up-right"></i> Buka
                                        </a>
                                    <?php endif; ?>
                                    <form action="<?php echo e(route('admin.articles.destroy', $article)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus artikel ini?');">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <i class="bi bi-journal-x fs-2 d-block mb-2 text-muted"></i>
                                    <p class="text-muted mb-0">Belum ada artikel. Tambahkan artikel pertama Anda.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addArticleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-file-earmark-plus-fill me-2"></i>Tambah Artikel Edukasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addArticleForm" method="POST" action="<?php echo e(route('admin.articles.store')); ?>">
                        <?php echo csrf_field(); ?>
                        <div class="mb-3">
                            <label class="form-label">Judul Artikel</label>
                            <input type="text" class="form-control" name="title" id="articleTitle" placeholder="Contoh: Tips Nutrisi Ibu Hamil" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kategori</label>
                            <select class="form-select" name="category" id="articleCategory" required>
                                <option value="">-- Pilih Kategori --</option>
                                <option value="umum">Umum</option>
                                <option value="trimester_1">Kehamilan Trimester 1</option>
                                <option value="trimester_2">Kehamilan Trimester 2</option>
                                <option value="trimester_3">Kehamilan Trimester 3</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Gambar Artikel</label>
                            <input type="url" class="form-control" name="image_url" id="articleImageUrl" placeholder="https://example.com/gambar-artikel.jpg">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Link Artikel</label>
                            <input type="url" class="form-control" name="article_url" id="articleUrl" placeholder="https://example.com/artikel" required>
                            <div class="form-text">Wajib diisi agar artikel dapat langsung dibuka oleh pengguna.</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi Singkat</label>
                            <textarea class="form-control" name="summary" id="articleDescription" rows="3" placeholder="Ringkasan artikel..." required></textarea>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-secondary flex-fill" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary-custom flex-fill">Simpan Artikel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('Admin.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\MomSpire\resources\views\Admin\ManajemenArtikel.blade.php ENDPATH**/ ?>