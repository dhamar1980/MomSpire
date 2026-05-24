<?php $__env->startSection('title', 'Kelola Buku KIA - MomSpire'); ?>
<?php $__env->startSection('header_title', 'Buku KIA'); ?>
<?php $__env->startSection('header_subtitle', 'Kelola dan tambahkan Buku KIA menggunakan template yang tersedia.'); ?>

<?php $__env->startPush('head'); ?>
<style>
    .template-card {
        border: 1px solid rgba(15, 23, 42, 0.08);
        border-radius: 18px;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .template-card:hover {
        border-color: rgba(214, 51, 108, 0.3);
        box-shadow: 0 10px 30px rgba(214, 51, 108, 0.08);
    }

    .buku-list-item {
        border-left: 3px solid rgba(214, 51, 108, 0.2);
        transition: all 0.3s ease;
    }

    .buku-list-item:hover {
        border-left-color: rgba(214, 51, 108, 0.8);
        background-color: rgba(214, 51, 108, 0.02);
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="p-4 p-lg-5">
    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <ul class="mb-0 ps-3">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Section: Tambah Buku KIA Baru -->
    <div class="row g-4 mb-5">
        <div class="col-12">
            <div style="border: 1px solid rgba(15, 23, 42, 0.08); border-radius: 18px; background: #fff; box-shadow: 0 10px 30px rgba(15, 23, 42, 0.04); padding: 2rem;">
                <h4 class="mb-4">Tambah Buku KIA Baru</h4>

                <?php if($templates->count() > 0): ?>
                    <div class="row g-3 mb-4">
                        <?php $__currentLoopData = $templates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $template): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-md-4 col-lg-3">
                                <div class="template-card p-4 text-center h-100" data-template-id="<?php echo e($template->id); ?>" data-template-name="<?php echo e($template->nama); ?>" data-template-file="<?php echo e($template->file_path); ?>" onclick="selectTemplate(this)">
                                    <i class="bi bi-file-pdf" style="font-size: 2.5rem; color: #e74c3c; margin-bottom: 1rem;"></i>
                                    <h6 class="fw-semibold"><?php echo e($template->nama ?? 'Template'); ?></h6>
                                    <?php if($template->deskripsi): ?>
                                        <p class="text-muted small mb-0"><?php echo e(Str::limit($template->deskripsi, 60)); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    <!-- Form: Tambah Buku KIA -->
                    <form id="tambahBukuForm" method="POST" action="<?php echo e(route('bidan.bukuKIA.store')); ?>" class="d-none">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" id="templateId" name="template_id">
                        <input type="hidden" id="templateFilePath" name="template_file_path">
                        
                        <div class="row g-3 mb-3">
                            <div class="col-md-8">
                                <label class="form-label">Judul Buku KIA</label>
                                <input type="text" class="form-control" id="bukuJudul" name="judul" placeholder="Contoh: BUKU KIA ANAK PERTAMA" required>
                                <small class="text-muted">Berikan judul yang jelas untuk membedakan setiap buku KIA</small>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Pengguna</label>
                                <select class="form-select" id="bukuPengguna" name="pengguna_id" required>
                                    <option value="">Pilih Pengguna</option>
                                    <?php $__currentLoopData = \App\Models\Pengguna::orderBy('name')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pengguna): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($pengguna->id); ?>"><?php echo e($pengguna->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary-custom">Simpan Buku KIA</button>
                            <button type="button" class="btn btn-outline-secondary" onclick="cancelSelect()">Batal</button>
                        </div>
                    </form>

                    <div id="noTemplateSelected" class="alert alert-light border mb-0">
                        <i class="bi bi-info-circle me-2"></i> Pilih salah satu template di atas untuk membuat Buku KIA baru
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i> Belum ada template Buku KIA yang tersedia. Hubungi admin untuk mengunggah template.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Section: Daftar Buku KIA -->
    <div class="row g-4">
        <div class="col-12">
            <div style="border: 1px solid rgba(15, 23, 42, 0.08); border-radius: 18px; background: #fff; box-shadow: 0 10px 30px rgba(15, 23, 42, 0.04); padding: 2rem;">
                <h4 class="mb-4">Buku KIA yang Sudah Dibuat</h4>

                <?php if($bukuKIA->count() > 0): ?>
                    <div class="list-group list-group-flush">
                        <?php $__currentLoopData = $bukuKIA; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $buku): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="buku-list-item p-3 border-bottom">
                                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                                    <div>
                                        <h6 class="fw-semibold mb-2"><?php echo e($buku->judul ?? 'Tanpa Judul'); ?></h6>
                                        <div class="text-muted small mb-2">
                                            <i class="bi bi-person me-1"></i> <?php echo e($buku->pengguna->name ?? 'N/A'); ?> •
                                            <i class="bi bi-calendar me-1"></i> <?php echo e($buku->created_at->format('d M Y')); ?>

                                        </div>
                                        <?php if($buku->catatan): ?>
                                            <p class="text-muted small mb-0"><?php echo e(Str::limit($buku->catatan, 150)); ?></p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editBukuModal" onclick="editBuku(<?php echo e($buku->id); ?>, '<?php echo e(addslashes($buku->judul)); ?>', '<?php echo e(addslashes($buku->catatan)); ?>')">Edit</button>
                                        <form action="<?php echo e(route('bidan.bukuKIA.delete', $buku->id)); ?>" method="POST" style="display: inline;" onsubmit="return confirm('Yakin hapus?')">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <div class="alert alert-light border mb-0">
                        <i class="bi bi-folder2-open me-2"></i> Belum ada Buku KIA yang dibuat. Mulai dengan membuat Buku KIA baru menggunakan template di atas.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Edit Buku KIA -->
<div class="modal fade" id="editBukuModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Buku KIA</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editBukuForm" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Judul</label>
                        <input type="text" class="form-control" id="editJudul" name="judul" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Catatan</label>
                        <textarea class="form-control" id="editCatatan" name="catatan" rows="4"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary-custom">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    function selectTemplate(element) {
        document.querySelectorAll('.template-card').forEach(card => {
            card.style.borderColor = 'rgba(15, 23, 42, 0.08)';
            card.style.boxShadow = 'none';
        });
        
        element.style.borderColor = 'rgba(214, 51, 108, 0.6)';
        element.style.boxShadow = '0 10px 30px rgba(214, 51, 108, 0.15)';
        
        document.getElementById('templateId').value = element.dataset.templateId;
        document.getElementById('templateFilePath').value = element.dataset.templateFile;
        document.getElementById('tambahBukuForm').classList.remove('d-none');
        document.getElementById('noTemplateSelected').classList.add('d-none');
    }

    function cancelSelect() {
        document.querySelectorAll('.template-card').forEach(card => {
            card.style.borderColor = 'rgba(15, 23, 42, 0.08)';
            card.style.boxShadow = 'none';
        });
        
        document.getElementById('tambahBukuForm').classList.add('d-none');
        document.getElementById('noTemplateSelected').classList.remove('d-none');
        document.getElementById('bukuJudul').value = '';
        document.getElementById('bukuPengguna').value = '';
    }

    function editBuku(id, judul, catatan) {
        document.getElementById('editBukuForm').action = '<?php echo e(route("bidan.bukuKIA.update", ":id")); ?>'.replace(':id', id);
        document.getElementById('editJudul').value = judul;
        document.getElementById('editCatatan').value = catatan;
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Close sidebar on page load
        window.__momspireSidebarOpen = false;
        window.__momspireSyncSidebar();
        
        // Replace toggle button with back button
        const toggleBtn = document.getElementById('toggleSidebar');
        if (toggleBtn) {
            toggleBtn.innerHTML = '<i class="bi bi-arrow-left"></i>';
            toggleBtn.title = 'Kembali';
            toggleBtn.onclick = function(e) {
                e.preventDefault();
                window.history.back();
                return false;
            };
        }
    });
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('bidan.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\MomSpire\resources\views\bidan\bukuKIA.blade.php ENDPATH**/ ?>