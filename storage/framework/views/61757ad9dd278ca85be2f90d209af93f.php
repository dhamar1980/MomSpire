<?php $__env->startSection('title', 'Pelayanan Kesehatan Ibu - MomSpire'); ?>

<?php $__env->startSection('header_title', 'Pencatatan Pelayanan Kesehatan Ibu'); ?>
<?php $__env->startSection('header_subtitle', 'Lengkapi data pelayanan kesehatan ibu untuk dicetak pada Buku KIA Halaman 50.'); ?>

<?php $__env->startSection('content'); ?>
<div class="row justify-content-center">
    <div class="col-12 col-xl-10">
        <div class="card role-card">
            <div class="card-header bg-white py-3 border-bottom">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="bg-success text-white rounded-circle p-2 me-3">
                            <i class="bi bi-journal-medical fs-4"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold">Data Ibu: <?php echo e($dataKia->ibu->nama ?? 'N/A'); ?></h5>
                            <p class="text-muted small mb-0">Diisi oleh Tenaga Kesehatan</p>
                        </div>
                    </div>
                    <a href="<?php echo e(route($role . '.kia')); ?>" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body p-4">

                <!-- Tab Navigation for Visits -->
                <?php $maxKunjungan = $role === 'bidan' ? 3 : 6; ?>
                <ul class="nav nav-pills nav-fill mb-4 bg-light p-2 rounded-4" id="kunjungan-tab" role="tablist">
                    <?php for($i = 1; $i <= $maxKunjungan; $i++): ?>
                        <?php
                            $tabLabel = '';
                            if ($i == 1) $tabLabel = 'Trimester I (1)';
                            elseif ($i == 2 || $i == 3) $tabLabel = "Trimester II ($i)";
                            else $tabLabel = "Trimester III ($i)";

                            $isActive = (session('active_tab', 'kunjungan1') == 'kunjungan' . $i);
                        ?>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-semibold rounded-pill <?php echo e($isActive ? 'active bg-success' : 'text-dark'); ?>"
                                id="tab-kunjungan-<?php echo e($i); ?>"
                                data-bs-toggle="tab"
                                data-bs-target="#pane-kunjungan-<?php echo e($i); ?>"
                                type="button"
                                role="tab">
                                <?php echo e($tabLabel); ?>

                            </button>
                        </li>
                    <?php endfor; ?>
                </ul>

                <div class="tab-content" id="kunjungan-tabContent">
                    <?php for($i = 1; $i <= $maxKunjungan; $i++): ?>
                        <?php
                            $isActive = (session('active_tab', 'kunjungan1') == 'kunjungan' . $i);
                            $pData = $pelayanan->get($i);
                        ?>
                        <div class="tab-pane fade <?php echo e($isActive ? 'show active' : ''); ?>" id="pane-kunjungan-<?php echo e($i); ?>" role="tabpanel">
                            <form action="<?php echo e(route($role . '.kia.save_pelayanan', $dataKia->id)); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="kunjungan_ke" value="<?php echo e($i); ?>">

                                <?php
                                    $tripelArr = explode(',', $pData->tripel_eliminasi ?? ',,');
                                    $valH = $tripelArr[0] ?? '';
                                    $valS = $tripelArr[1] ?? '';
                                    $valHepB = $tripelArr[2] ?? '';
                                ?>

                                <div class="row g-4">
                                    <div class="col-12 col-md-6">
                                        <div class="card bg-light border-0 shadow-sm rounded-4 h-100">
                                            <div class="card-body p-4">
                                                <h6 class="fw-bold mb-4 border-bottom pb-2 text-success">Tanggal dan Tempat</h6>

                                                <div class="mb-3">
                                                    <label class="form-label small fw-semibold">Tanggal periksa</label>
                                                    <input type="date" name="tanggal_periksa" class="form-control" value="<?php echo e($pData->tanggal_periksa ?? ''); ?>">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label small fw-semibold">Tempat periksa</label>
                                                    <input type="text" name="tempat_periksa" class="form-control" value="<?php echo e($pData->tempat_periksa ?? ''); ?>" placeholder="Nama faskes">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <div class="card bg-light border-0 shadow-sm rounded-4 h-100">
                                            <div class="card-body p-4">
                                                <h6 class="fw-bold mb-4 border-bottom pb-2 text-success">Pemeriksaan Fisik</h6>

                                                <div class="row g-3">
                                                    <div class="col-6">
                                                        <label class="form-label small fw-semibold">Berat Badan (kg)</label>
                                                        <input type="number" step="0.1" name="berat_badan" class="form-control" value="<?php echo e($pData->berat_badan ?? ''); ?>">
                                                    </div>
                                                    <?php if(in_array($i, [1])): ?>
                                                    <div class="col-6">
                                                        <label class="form-label small fw-semibold">Tinggi Badan (cm)</label>
                                                        <input type="number" step="0.1" name="tinggi_badan" class="form-control" value="<?php echo e($pData->tinggi_badan ?? ''); ?>">
                                                    </div>
                                                    <?php endif; ?>
                                                    <div class="col-6">
                                                        <label class="form-label small fw-semibold">LiLA (cm)</label>
                                                        <input type="number" step="0.1" name="lingkar_lengan_atas" class="form-control" value="<?php echo e($pData->lingkar_lengan_atas ?? ''); ?>">
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="form-label small fw-semibold">Tekanan Darah</label>
                                                        <input type="text" name="tekanan_darah" class="form-control" value="<?php echo e($pData->tekanan_darah ?? ''); ?>" placeholder="120/80">
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="form-label small fw-semibold">Tinggi Rahim (cm)</label>
                                                        <input type="number" step="0.1" name="tinggi_rahim" class="form-control" value="<?php echo e($pData->tinggi_rahim ?? ''); ?>">
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="form-label small fw-semibold">Letak/Denyut Jantung Bayi</label>
                                                        <input type="text" name="denyut_jantung_bayi" class="form-control" value="<?php echo e($pData->denyut_jantung_bayi ?? ''); ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="card bg-light border-0 shadow-sm rounded-4">
                                            <div class="card-body p-4">
                                                <h6 class="fw-bold mb-4 border-bottom pb-2 text-success">Tindakan, Skrining & Laboratorium</h6>

                                                <div class="row g-3">
                                                    <div class="col-12 col-md-4">
                                                        <label class="form-label small fw-semibold">Status/Imunisasi Tetanus</label>
                                                        <input type="text" name="status_imunisasi_tt" class="form-control" value="<?php echo e($pData->status_imunisasi_tt ?? ''); ?>">
                                                    </div>
                                                    <div class="col-12 col-md-4">
                                                        <label class="form-label small fw-semibold">Konseling</label>
                                                        <input type="text" name="konseling" class="form-control" value="<?php echo e($pData->konseling ?? ''); ?>">
                                                    </div>
                                                    <div class="col-12 col-md-4">
                                                        <label class="form-label small fw-semibold">Skrining Dokter</label>
                                                        <input type="text" name="skrining_dokter" class="form-control" value="<?php echo e($pData->skrining_dokter ?? ''); ?>">
                                                    </div>
                                                    <div class="col-12 col-md-4">
                                                        <label class="form-label small fw-semibold">Tablet Tambah Darah (TTD)</label>
                                                        <input type="text" name="tablet_tambah_darah" class="form-control" value="<?php echo e($pData->tablet_tambah_darah ?? ''); ?>" placeholder="Jumlah">
                                                    </div>
                                                    <?php if(in_array($i, [1, 4, 5])): ?>
                                                    <div class="col-12 col-md-4">
                                                        <label class="form-label small fw-semibold">Tes Lab Hemoglobin (Hb)</label>
                                                        <input type="text" name="tes_lab_hb" class="form-control" value="<?php echo e($pData->tes_lab_hb ?? ''); ?>">
                                                    </div>
                                                    <?php endif; ?>
                                                    <?php if(in_array($i, [1])): ?>
                                                    <div class="col-12 col-md-4">
                                                        <label class="form-label small fw-semibold">Tes Golongan Darah</label>
                                                        <input type="text" name="tes_golongan_darah" class="form-control" value="<?php echo e($pData->tes_golongan_darah ?? ''); ?>">
                                                    </div>
                                                    <?php endif; ?>
                                                    <?php if(in_array($i, [2, 3, 4, 5, 6])): ?>
                                                    <div class="col-12 col-md-4">
                                                        <label class="form-label small fw-semibold">Tes Lab Protein Urine</label>
                                                        <input type="text" name="tes_lab_protein_urine" class="form-control" value="<?php echo e($pData->tes_lab_protein_urine ?? ''); ?>">
                                                    </div>
                                                    <?php endif; ?>
                                                    <?php if(in_array($i, [4, 5, 6])): ?>
                                                    <div class="col-12 col-md-4">
                                                        <label class="form-label small fw-semibold">Tes Lab Gula Darah</label>
                                                        <input type="text" name="tes_lab_gula_darah" class="form-control" value="<?php echo e($pData->tes_lab_gula_darah ?? ''); ?>">
                                                    </div>
                                                    <?php endif; ?>
                                                    <?php if(in_array($i, [1, 5])): ?>
                                                    <div class="col-12 col-md-4">
                                                        <label class="form-label small fw-semibold">USG</label>
                                                        <input type="text" name="usg" class="form-control" value="<?php echo e($pData->usg ?? ''); ?>">
                                                    </div>
                                                    <?php endif; ?>
                                                    <div class="col-12 col-md-6">
                                                        <label class="form-label small fw-semibold">Tripel Eliminasi</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text" style="font-size: 0.8rem;">H</span>
                                                            <input type="text" name="tripel_eliminasi_h" class="form-control" value="<?php echo e($valH); ?>">
                                                            <span class="input-group-text" style="font-size: 0.8rem;">S</span>
                                                            <input type="text" name="tripel_eliminasi_s" class="form-control" value="<?php echo e($valS); ?>">
                                                            <span class="input-group-text" style="font-size: 0.8rem;">Hep B</span>
                                                            <input type="text" name="tripel_eliminasi_hep_b" class="form-control" value="<?php echo e($valHepB); ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-md-6">
                                                        <label class="form-label small fw-semibold">Tata Laksana Kasus</label>
                                                        <input type="text" name="tata_laksana_kasus" class="form-control" value="<?php echo e($pData->tata_laksana_kasus ?? ''); ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4 text-end">
                                    <button type="submit" class="btn btn-success px-5 py-2 rounded-pill shadow-sm fw-semibold">
                                        <i class="bi bi-save2-fill me-2"></i> Simpan Kunjungan <?php echo e($i); ?>

                                    </button>
                                </div>
                            </form>
                        </div>
                    <?php endfor; ?>
                </div>

            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make($role . '.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\MomSpire\resources\views\nakes\kia-edit-pelayanan.blade.php ENDPATH**/ ?>