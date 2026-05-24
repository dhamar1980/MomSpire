<?php $__env->startSection('title', 'Halaman 52-53 - MomSpire'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-3 p-sm-4 p-lg-5 mb-4">
    <div class="row align-items-center g-3">
        <div class="col-12">
            <h1 class="display-6 fw-bold mb-2">Halaman 52-53: Trimester 1</h1>
            <p class="lead text-muted mb-0">Input Hasil USG, Pemeriksaan Lab, Skrining, dan Catatan Pelayanan untuk Ibu <strong><?php echo e($dataKia->ibu->nama ?? 'Unknown'); ?></strong></p>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-4 p-lg-5">
        <form action="<?php echo e(route($role . '.kia.save_trimester1', $dataKia->id)); ?>" method="POST" enctype="multipart/form-data" id="trimester1Form">
            <?php echo csrf_field(); ?>
            
            <input type="hidden" name="deleted_catatan" id="deleted_catatan" value="">

            <h4 class="fw-bold text-primary mb-4 border-bottom pb-2">Bagian Kiri (Halaman 52)</h4>
            
            <div class="row g-4 mb-5">
                <div class="col-12 col-md-6">
                    <label class="form-label fw-semibold">Hasil USG (Gambar)</label>
                    <input type="file" class="form-control" name="gambar_usg" accept="image/jpeg,image/png,image/jpg">
                    <?php if($pemeriksaan->gambar_usg): ?>
                    <div class="mt-2">
                        <span class="badge bg-success">Sudah ada gambar</span>
                        <img src="<?php echo e(asset($pemeriksaan->gambar_usg)); ?>" alt="USG Image" class="img-thumbnail mt-2" style="max-height: 100px;">
                    </div>
                    <?php endif; ?>
                    <div class="form-text">Biarkan kosong jika tidak ingin mengubah gambar. (Max 2MB)</div>
                </div>

                <div class="col-12">
                    <h5 class="fw-bold mt-4">Pemeriksaan Laboratorium (Halaman 52)</h5>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tanggal Periksa Lab</label>
                    <input type="date" class="form-control" name="tgl_periksa_lab" value="<?php echo e($pemeriksaan->tgl_periksa_lab); ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Hemoglobin (g/dL)</label>
                    <input type="text" class="form-control" name="lab_hemoglobin" value="<?php echo e($pemeriksaan->lab_hemoglobin); ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Golongan Darah & Rhesus</label>
                    <input type="text" class="form-control" name="lab_gol_darah" value="<?php echo e($pemeriksaan->lab_gol_darah); ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Gula Darah Sewaktu (Mg/dL)</label>
                    <input type="text" class="form-control" name="lab_gula_darah" value="<?php echo e($pemeriksaan->lab_gula_darah); ?>">
                </div>
                
                <div class="col-12">
                    <h6 class="fw-bold mt-2">Tripel Eliminasi</h6>
                </div>
                <div class="col-md-4">
                    <label class="form-label">H</label>
                    <select class="form-select" name="lab_tripel_h">
                        <option value="">-- Pilih --</option>
                        <option value="Reaktif" <?php echo e($pemeriksaan->lab_tripel_h == 'Reaktif' ? 'selected' : ''); ?>>Reaktif</option>
                        <option value="Non reaktif" <?php echo e($pemeriksaan->lab_tripel_h == 'Non reaktif' ? 'selected' : ''); ?>>Non reaktif</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">S</label>
                    <select class="form-select" name="lab_tripel_s">
                        <option value="">-- Pilih --</option>
                        <option value="Reaktif" <?php echo e($pemeriksaan->lab_tripel_s == 'Reaktif' ? 'selected' : ''); ?>>Reaktif</option>
                        <option value="Non reaktif" <?php echo e($pemeriksaan->lab_tripel_s == 'Non reaktif' ? 'selected' : ''); ?>>Non reaktif</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Hepatitis B</label>
                    <select class="form-select" name="lab_tripel_hep_b">
                        <option value="">-- Pilih --</option>
                        <option value="Reaktif" <?php echo e($pemeriksaan->lab_tripel_hep_b == 'Reaktif' ? 'selected' : ''); ?>>Reaktif</option>
                        <option value="Non reaktif" <?php echo e($pemeriksaan->lab_tripel_hep_b == 'Non reaktif' ? 'selected' : ''); ?>>Non reaktif</option>
                    </select>
                </div>

                <div class="col-12">
                    <h5 class="fw-bold mt-4">Skrining Kesehatan Jiwa</h5>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tanggal Skrining</label>
                    <input type="date" class="form-control" name="tgl_skrining_jiwa" value="<?php echo e($pemeriksaan->tgl_skrining_jiwa); ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Skrining Kesehatan Jiwa</label>
                    <select class="form-select" name="skrining_jiwa">
                        <option value="">-- Pilih --</option>
                        <option value="Ya" <?php echo e($pemeriksaan->skrining_jiwa == 'Ya' ? 'selected' : ''); ?>>Ya</option>
                        <option value="Tidak" <?php echo e($pemeriksaan->skrining_jiwa == 'Tidak' ? 'selected' : ''); ?>>Tidak</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tindak Lanjut</label>
                    <select class="form-select" name="tindak_lanjut_jiwa">
                        <option value="">-- Pilih --</option>
                        <option value="Edukasi" <?php echo e($pemeriksaan->tindak_lanjut_jiwa == 'Edukasi' ? 'selected' : ''); ?>>Edukasi</option>
                        <option value="Konseling" <?php echo e($pemeriksaan->tindak_lanjut_jiwa == 'Konseling' ? 'selected' : ''); ?>>Konseling</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Perlu Rujukan</label>
                    <select class="form-select" name="rujukan_jiwa">
                        <option value="">-- Pilih --</option>
                        <option value="Ya" <?php echo e($pemeriksaan->rujukan_jiwa == 'Ya' ? 'selected' : ''); ?>>Ya</option>
                        <option value="Tidak" <?php echo e($pemeriksaan->rujukan_jiwa == 'Tidak' ? 'selected' : ''); ?>>Tidak</option>
                    </select>
                </div>

                <div class="col-md-6 mt-4">
                    <label class="form-label fw-bold">Kesimpulan</label>
                    <input type="text" class="form-control" name="kesimpulan" value="<?php echo e($pemeriksaan->kesimpulan); ?>">
                </div>
                <div class="col-md-6 mt-4">
                    <label class="form-label fw-bold">Rekomendasi</label>
                    <input type="text" class="form-control" name="rekomendasi" value="<?php echo e($pemeriksaan->rekomendasi); ?>">
                </div>
            </div>

            <h4 class="fw-bold text-primary mb-4 border-bottom pb-2 mt-5">Bagian Kanan (Halaman 53) - Catatan Pelayanan</h4>
            
            <div id="catatan-container">
                <?php $__empty_1 = true; $__currentLoopData = $catatan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="catatan-item card border shadow-none rounded-3 mb-4 p-4" data-index="<?php echo e($index); ?>">
                    <input type="hidden" name="catatan[<?php echo e($index); ?>][id]" value="<?php echo e($cat->id); ?>">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold m-0 text-secondary">Catatan #<span class="catatan-counter"><?php echo e($index + 1); ?></span></h6>
                        <button type="button" class="btn btn-outline-danger btn-sm rounded-pill btn-remove-catatan" data-id="<?php echo e($cat->id); ?>">Hapus Baris</button>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Tanggal Periksa</label>
                            <input type="date" class="form-control" name="catatan[<?php echo e($index); ?>][tanggal_periksa]" value="<?php echo e($cat->tanggal_periksa); ?>">
                            <small class="text-muted">Juga digunakan untuk area Stamp & Paraf</small>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tanggal Kembali</label>
                            <input type="date" class="form-control" name="catatan[<?php echo e($index); ?>][tanggal_kembali]" value="<?php echo e($cat->tanggal_kembali); ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Keluhan, Pemeriksaan, Tindakan dan Saran</label>
                            <textarea class="form-control" name="catatan[<?php echo e($index); ?>][catatan]" rows="3"><?php echo e($cat->catatan); ?></textarea>
                            <small class="text-muted">Tekan Enter untuk baris baru. Tercetak ke bawah di PDF.</small>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="catatan-item card border shadow-none rounded-3 mb-4 p-4" data-index="0">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold m-0 text-secondary">Catatan #<span class="catatan-counter">1</span></h6>
                        <button type="button" class="btn btn-outline-danger btn-sm rounded-pill btn-remove-catatan d-none">Hapus Baris</button>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Tanggal Periksa</label>
                            <input type="date" class="form-control" name="catatan[0][tanggal_periksa]">
                            <small class="text-muted">Juga digunakan untuk area Stamp & Paraf</small>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tanggal Kembali</label>
                            <input type="date" class="form-control" name="catatan[0][tanggal_kembali]">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Keluhan, Pemeriksaan, Tindakan dan Saran</label>
                            <textarea class="form-control" name="catatan[0][catatan]" rows="3"></textarea>
                            <small class="text-muted">Tekan Enter untuk baris baru. Tercetak ke bawah di PDF.</small>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <div class="mb-4">
                <button type="button" class="btn btn-outline-secondary rounded-pill px-4" id="btnAddCatatan">
                    <i class="bi bi-plus-circle me-2"></i> Tambah Catatan
                </button>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-5">
                <a href="<?php echo e(route($role . '.kia')); ?>" class="btn btn-light px-4 rounded-pill">Batal</a>
                <button type="submit" class="btn btn-success px-4 rounded-pill">Simpan Data</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let catIndex = <?php echo e(count($catatan) > 0 ? count($catatan) : 1); ?>;
    const container = document.getElementById('catatan-container');
    const btnAdd = document.getElementById('btnAddCatatan');
    const deletedInput = document.getElementById('deleted_catatan');

    function updateCounters() {
        const items = container.querySelectorAll('.catatan-item');
        items.forEach((item, idx) => {
            item.querySelector('.catatan-counter').textContent = idx + 1;
            const btnRemove = item.querySelector('.btn-remove-catatan');
            if (items.length === 1) {
                btnRemove.classList.add('d-none');
            } else {
                btnRemove.classList.remove('d-none');
            }
        });
    }

    container.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-remove-catatan')) {
            const item = e.target.closest('.catatan-item');
            const id = e.target.getAttribute('data-id');
            
            if (id) {
                const currentDeleted = deletedInput.value ? deletedInput.value.split(',') : [];
                currentDeleted.push(id);
                deletedInput.value = currentDeleted.join(',');
            }
            
            item.remove();
            updateCounters();
        }
    });

    btnAdd.addEventListener('click', function() {
        const template = `
            <div class="catatan-item card border shadow-none rounded-3 mb-4 p-4" data-index="${catIndex}">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold m-0 text-secondary">Catatan #<span class="catatan-counter"></span></h6>
                    <button type="button" class="btn btn-outline-danger btn-sm rounded-pill btn-remove-catatan">Hapus Baris</button>
                </div>

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Tanggal Periksa</label>
                        <input type="date" class="form-control" name="catatan[${catIndex}][tanggal_periksa]">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tanggal Kembali</label>
                        <input type="date" class="form-control" name="catatan[${catIndex}][tanggal_kembali]">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Keluhan, Pemeriksaan, Tindakan dan Saran</label>
                        <textarea class="form-control" name="catatan[${catIndex}][catatan]" rows="3"></textarea>
                    </div>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', template);
        catIndex++;
        updateCounters();
    });

    updateCounters();
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make($role . '.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\MomSpire\resources\views\nakes\kia-edit-trimester1.blade.php ENDPATH**/ ?>