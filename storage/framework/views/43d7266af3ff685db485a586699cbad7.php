<?php $__env->startSection('title', 'Halaman 53 - Trimester 2 - MomSpire'); ?>

<?php $__env->startSection('content'); ?>
    <div class="p-3 p-sm-4 p-lg-5 mb-4">
        <div class="row align-items-center g-3">
            <div class="col-12">
                <h1 class="display-6 fw-bold mb-2">Halaman 53: Trimester 2</h1>
                <p class="lead text-muted mb-0">Skrining Preeklampsia, Diabetes, dan Catatan Pelayanan untuk Ibu
                    <strong><?php echo e($dataKia->ibu->nama ?? 'Unknown'); ?></strong></p>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4 p-lg-5">
            <form action="<?php echo e(route($role . '.kia.save_trimester2', $dataKia->id)); ?>" method="POST" id="trimester2Form">
                <?php echo csrf_field(); ?>

                <input type="hidden" name="deleted_catatan" id="deleted_catatan" value="">

                <h4 class="fw-bold text-primary mb-4 border-bottom pb-2">Bagian Kiri (Halaman 53) - Skrining Preeklampsia &
                    Diabetes</h4>

                <div class="row g-4 mb-5">
                    <div class="col-12">
                        <h5 class="fw-bold">1. Skrining Preeklampsia (Umur kehamilan < 20 minggu)</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Kriteria</th>
                                                <th class="text-center" style="width: 150px;">Risiko Sedang<br><small
                                                        class="fw-normal">(Tampil di Baris)</small></th>
                                                <th class="text-center" style="width: 150px;">Risiko Tinggi<br><small
                                                        class="fw-normal">(Tampil di Bawah)</small></th>
                                                <th class="text-center" style="width: 100px;">Normal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $grup1 = [
                                                    'multipara_pasangan_baru' => 'Multipara Dengan Kehamilan oleh Pasangan Baru',
                                                    'teknologi_reproduksi' => 'Kehamilan Dengan Teknologi Reproduksi Berbantu',
                                                    'umur_35' => 'Umur >= 35 Tahun',
                                                    'nullipara' => 'Nullipara',
                                                    'jarak_10' => 'Multipara dengan Jarak Kehamilan Sebelumnya > 10 Tahun',
                                                    'riwayat_ibu_saudara' => 'Riwayat Preeklampsia pada Ibu atau Saudara Perempuan',
                                                    'obesitas' => 'Obesitas Sebelum Hamil (IMT > 30 kg/m2)',
                                                ];
                                                $grup2 = [
                                                    'riwayat_preeklampsia_sebelumnya' => 'Multipara Dengan Riwayat Preeklampsia Sebelumnya',
                                                    'kehamilan_multipel' => 'Kehamilan Multipel',
                                                    'diabetes' => 'Diabetes dalam Kehamilan',
                                                    'hipertensi' => 'Hipertensi Kronik',
                                                    'ginjal' => 'Penyakit Ginjal',
                                                    'autoimun' => 'Penyakit Autoimun, SLE',
                                                    'aps' => 'Anti Phospholipid Syndrome',
                                                ];
                                                $grup3 = [
                                                    'map_90' => 'Mean Arterial Pressure > 90 mmHg',
                                                    'proteinuria' => 'Proteinuria (Urin Celup +1)',
                                                ];
                                                $skriningData = $pemeriksaan->skrining_preeklampsia ?? [];
                                            ?>

                                            <!-- Grup 1 -->
                                            <tr class="table-light">
                                                <td colspan="4" class="fw-bold small text-uppercase">Anamnesis - Kelompok
                                                    Risiko Sedang</td>
                                            </tr>
                                            <?php $__currentLoopData = $grup1; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td><?php echo e($label); ?></td>
                                                    <td class="text-center">
                                                        <input class="form-check-input check-preeklampsia" type="radio"
                                                            name="skrining_preeklampsia[<?php echo e($key); ?>]" value="Risiko Sedang" <?php echo e(($skriningData[$key] ?? '') === 'Risiko Sedang' ? 'checked' : ''); ?>>
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input check-preeklampsia" type="radio"
                                                            name="skrining_preeklampsia[<?php echo e($key); ?>]" value="Risiko Tinggi" <?php echo e(($skriningData[$key] ?? '') === 'Risiko Tinggi' ? 'checked' : ''); ?>>
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input check-preeklampsia" type="radio"
                                                            name="skrining_preeklampsia[<?php echo e($key); ?>]" value="" <?php echo e(empty($skriningData[$key]) ? 'checked' : ''); ?>>
                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                            <!-- Grup 2 -->
                                            <tr style="background-color: #fce4ec;">
                                                <td colspan="4" class="fw-bold small text-uppercase">Anamnesis - Kelompok
                                                    Risiko Tinggi</td>
                                            </tr>
                                            <?php $__currentLoopData = $grup2; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr style="background-color: #fffafa;">
                                                    <td><?php echo e($label); ?></td>
                                                    <td class="text-center">
                                                        <input class="form-check-input check-preeklampsia" type="radio"
                                                            name="skrining_preeklampsia[<?php echo e($key); ?>]" value="Risiko Sedang" <?php echo e(($skriningData[$key] ?? '') === 'Risiko Sedang' ? 'checked' : ''); ?>>
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input check-preeklampsia" type="radio"
                                                            name="skrining_preeklampsia[<?php echo e($key); ?>]" value="Risiko Tinggi" <?php echo e(($skriningData[$key] ?? '') === 'Risiko Tinggi' ? 'checked' : ''); ?>>
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input check-preeklampsia" type="radio"
                                                            name="skrining_preeklampsia[<?php echo e($key); ?>]" value="" <?php echo e(empty($skriningData[$key]) ? 'checked' : ''); ?>>
                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                            <!-- Grup 3 -->
                                            <tr class="table-secondary">
                                                <td colspan="4" class="fw-bold small text-uppercase">Pemeriksaan Fisik</td>
                                            </tr>
                                            <?php $__currentLoopData = $grup3; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td><?php echo e($label); ?></td>
                                                    <td class="text-center">
                                                        <input class="form-check-input check-preeklampsia" type="radio"
                                                            name="skrining_preeklampsia[<?php echo e($key); ?>]" value="Risiko Sedang" <?php echo e(($skriningData[$key] ?? '') === 'Risiko Sedang' ? 'checked' : ''); ?>>
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input check-preeklampsia" type="radio"
                                                            name="skrining_preeklampsia[<?php echo e($key); ?>]" value="Risiko Tinggi" <?php echo e(($skriningData[$key] ?? '') === 'Risiko Tinggi' ? 'checked' : ''); ?>>
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input check-preeklampsia" type="radio"
                                                            name="skrining_preeklampsia[<?php echo e($key); ?>]" value="" <?php echo e(empty($skriningData[$key]) ? 'checked' : ''); ?>>
                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-bold">Kesimpulan Skrining Preeklampsia</label>
                        <input type="text" class="form-control" name="kesimpulan_preeklampsia"
                            value="<?php echo e($pemeriksaan->kesimpulan_preeklampsia); ?>"
                            placeholder="Contoh: Perlu Rujukan / Normal">
                    </div>

                    <div class="col-12 mt-5">
                        <h5 class="fw-bold">2. Skrining Diabetes Melitus Gestasional (24-28 Minggu)</h5>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Gula Darah Puasa (mg/dL)</label>
                        <input type="text" class="form-control" name="lab_gula_darah_puasa"
                            value="<?php echo e($pemeriksaan->lab_gula_darah_puasa); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Rencana Tindak Lanjut (Gula Darah Puasa)</label>
                        <input type="text" class="form-control" name="tindak_lanjut_puasa"
                            value="<?php echo e($pemeriksaan->tindak_lanjut_puasa); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Gula Darah 2 Jam Post Prandial (mg/dL)</label>
                        <input type="text" class="form-control" name="lab_gula_darah_2jam"
                            value="<?php echo e($pemeriksaan->lab_gula_darah_2jam); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Rencana Tindak Lanjut (Gula Darah 2 Jam)</label>
                        <input type="text" class="form-control" name="tindak_lanjut_2jam"
                            value="<?php echo e($pemeriksaan->tindak_lanjut_2jam); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tanggal Periksa</label>
                        <input type="date" class="form-control" name="tgl_periksa_diabetes"
                            value="<?php echo e($pemeriksaan->tgl_periksa_diabetes); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nama Dokter Pemeriksa</label>
                        <input type="text" class="form-control" name="nama_dokter_diabetes"
                            value="<?php echo e($pemeriksaan->nama_dokter_diabetes); ?>">
                    </div>
                </div>

                <h4 class="fw-bold text-primary mb-4 border-bottom pb-2 mt-5">Bagian Kanan (Halaman 53) - Catatan Pelayanan
                    Trimester 2</h4>

                <div id="catatan-container">
                    <?php $__empty_1 = true; $__currentLoopData = $catatan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="catatan-item card border shadow-none rounded-3 mb-4 p-4" data-index="<?php echo e($index); ?>">
                            <input type="hidden" name="catatan[<?php echo e($index); ?>][id]" value="<?php echo e($cat->id); ?>">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-bold m-0 text-secondary">Catatan #<span
                                        class="catatan-counter"><?php echo e($index + 1); ?></span></h6>
                                <button type="button" class="btn btn-outline-danger btn-sm rounded-pill btn-remove-catatan"
                                    data-id="<?php echo e($cat->id); ?>">Hapus Baris</button>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Tanggal Periksa</label>
                                    <input type="date" class="form-control" name="catatan[<?php echo e($index); ?>][tanggal_periksa]"
                                        value="<?php echo e($cat->tanggal_periksa); ?>">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Tanggal Kembali</label>
                                    <input type="date" class="form-control" name="catatan[<?php echo e($index); ?>][tanggal_kembali]"
                                        value="<?php echo e($cat->tanggal_kembali); ?>">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Keluhan, Pemeriksaan, Tindakan dan Saran</label>
                                    <textarea class="form-control" name="catatan[<?php echo e($index); ?>][catatan]"
                                        rows="3"><?php echo e($cat->catatan); ?></textarea>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="catatan-item card border shadow-none rounded-3 mb-4 p-4" data-index="0">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-bold m-0 text-secondary">Catatan #<span class="catatan-counter">1</span></h6>
                                <button type="button"
                                    class="btn btn-outline-danger btn-sm rounded-pill btn-remove-catatan d-none">Hapus
                                    Baris</button>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Tanggal Periksa</label>
                                    <input type="date" class="form-control" name="catatan[0][tanggal_periksa]">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Tanggal Kembali</label>
                                    <input type="date" class="form-control" name="catatan[0][tanggal_kembali]">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Keluhan, Pemeriksaan, Tindakan dan Saran</label>
                                    <textarea class="form-control" name="catatan[0][catatan]" rows="3"></textarea>
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
        document.addEventListener('DOMContentLoaded', function () {
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

            container.addEventListener('click', function (e) {
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

            btnAdd.addEventListener('click', function () {
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
<?php echo $__env->make($role . '.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\MomSpire\resources\views/nakes/kia-edit-trimester2.blade.php ENDPATH**/ ?>