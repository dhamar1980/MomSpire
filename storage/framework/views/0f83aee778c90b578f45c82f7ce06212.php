<?php $__env->startSection('title', 'Pemantauan Anak 1 - 2 Tahun - MomSpire'); ?>
<?php $__env->startSection('header_title', 'Anak Umur 1 - 2 Tahun'); ?>
<?php $__env->startSection('header_subtitle', 'Catat pemantauan bulanan kesehatan dan perkembangan anak secara mandiri dari bulan ke-12 hingga ke-23.'); ?>

<?php $__env->startSection('content'); ?>
<div class="row g-4" x-data="{ activeTab: '<?php echo e(session('active_tab', 'bulanan')); ?>', activeMonth: <?php echo e(session('active_month', 12)); ?> }">
    <!-- Info Banner -->
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4 bg-gradient-info text-white p-4">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-white bg-opacity-25 rounded-circle p-3">
                    <i class="bi bi-calendar2-star-fill fs-3 text-white"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-1">🥘 Menu Makanan Keluarga & Pemantauan Bulanan (Usia 1 - 2 Tahun)</h6>
                    <p class="mb-0 small opacity-90">Pada usia ini, anak mulai dapat mengonsumsi makanan keluarga yang bergizi seperti Nasi Lemak / Nasi Uduk / Sup Ayam Makaroni. Lakukan pemantauan kesehatan setiap bulan secara rutin serta perhatikan tahapan tumbuh kembang anak. Segera bawa ke Faskes jika anak belum bisa melakukan kemampuan sesuai usianya.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="col-12">
        <div class="d-flex justify-content-center gap-3 border-bottom pb-3 flex-wrap">
            <button @click="activeTab = 'bulanan'" 
                    :class="activeTab === 'bulanan' ? 'btn-gradient-primary text-white shadow' : 'btn-outline-secondary'"
                    class="btn btn-lg rounded-pill px-4 transition-all fw-semibold mb-2">
                <i class="bi bi-calendar2-event me-2"></i> Pemantauan Bulanan (Bulan 12-23)
            </button>
            <button @click="activeTab = 'perkembangan18'" 
                    :class="activeTab === 'perkembangan18' ? 'btn-gradient-primary text-white shadow' : 'btn-outline-secondary'"
                    class="btn btn-lg rounded-pill px-4 transition-all fw-semibold mb-2">
                <i class="bi bi-check2-square me-2"></i> Tumbuh Kembang (12 - 18 Bulan)
            </button>
            <button @click="activeTab = 'perkembangan24'" 
                    :class="activeTab === 'perkembangan24' ? 'btn-gradient-primary text-white shadow' : 'btn-outline-secondary'"
                    class="btn btn-lg rounded-pill px-4 transition-all fw-semibold mb-2">
                <i class="bi bi-check2-all me-2"></i> Tumbuh Kembang (18 - 24 Bulan)
            </button>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="col-12">
            <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm p-4 mb-0" role="alert">
                <div class="d-flex align-items-center gap-3">
                    <i class="bi bi-check-circle-fill fs-3 text-success"></i>
                    <div>
                        <h6 class="fw-bold mb-0">Berhasil Disimpan!</h6>
                        <span class="small text-muted"><?php echo e(session('success')); ?></span>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    <?php endif; ?>

    <!-- ================= TAB 1: PEMANTAUAN BULANAN (12 - 23) ================= -->
    <div class="col-12" x-show="activeTab === 'bulanan'" x-transition>
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
            <div class="card-header bg-white border-0 p-4 pb-0 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h5 class="fw-bold mb-1 text-gradient">Pilih Bulan Pemantauan</h5>
                    <p class="text-muted small mb-0">Klik pada tombol bulan untuk mencatat hasil pemantauan di bulan tersebut.</p>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <?php $__currentLoopData = range(12, 23); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <button @click="activeMonth = <?php echo e($m); ?>" 
                                :class="activeMonth === <?php echo e($m); ?> ? 'btn-primary shadow-sm' : 'btn-outline-primary'"
                                class="btn rounded-circle fw-bold" style="width: 45px; height: 45px;">
                            <?php echo e($m); ?>

                        </button>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            <div class="card-body p-4">
                <?php $__currentLoopData = range(12, 23); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $item = $bulanan->get($m); ?>
                    <div x-show="activeMonth === <?php echo e($m); ?>" x-transition>
                        <div class="alert alert-light border rounded-4 p-3 mb-4 d-flex justify-content-between align-items-center">
                            <h6 class="fw-bold text-primary mb-0"><i class="bi bi-calendar-event-fill me-2"></i>Form Pemantauan - Bulan Ke-<?php echo e($m); ?></h6>
                            <?php if($item): ?>
                                <span class="badge bg-success rounded-pill px-3 py-2"><i class="bi bi-check-circle-fill me-1"></i> Telah Disimpan</span>
                            <?php else: ?>
                                <span class="badge bg-warning text-dark rounded-pill px-3 py-2"><i class="bi bi-exclamation-circle me-1"></i> Belum Disimpan</span>
                            <?php endif; ?>
                        </div>

                        <form action="<?php echo e(route('pengguna.bulanan_anak_24.save')); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="bulan_ke" value="<?php echo e($m); ?>">

                            <div class="row g-4">
                                <div class="col-12 col-md-6">
                                    <div class="list-group">
                                        <label class="list-group-item d-flex gap-3 align-items-center p-3 border-0 bg-light mb-2 rounded-3">
                                            <input class="form-check-input flex-shrink-0 fs-4" type="checkbox" name="sesak_napas" value="1" <?php echo e(($item->sesak_napas ?? false) ? 'checked' : ''); ?>>
                                            <span class="small fw-medium">Sesak napas / tarikan dada ke dalam</span>
                                        </label>
                                        <label class="list-group-item d-flex gap-3 align-items-center p-3 border-0 bg-light mb-2 rounded-3">
                                            <input class="form-check-input flex-shrink-0 fs-4" type="checkbox" name="batuk" value="1" <?php echo e(($item->batuk ?? false) ? 'checked' : ''); ?>>
                                            <span class="small fw-medium">Batuk dengan bunyi grok-grok / mengi / batuk > 2 minggu</span>
                                        </label>
                                        <label class="list-group-item d-flex gap-3 align-items-center p-3 border-0 bg-light mb-2 rounded-3">
                                            <input class="form-check-input flex-shrink-0 fs-4" type="checkbox" name="suhu_abnormal" value="1" <?php echo e(($item->suhu_abnormal ?? false) ? 'checked' : ''); ?>>
                                            <span class="small fw-medium">Suhu tubuh panas > 38.5 C / perdarahan (mimisan/gusi berdarah/muntah darah/BAB hitam) / kejang</span>
                                        </label>
                                        <label class="list-group-item d-flex gap-3 align-items-center p-3 border-0 bg-light mb-2 rounded-3">
                                            <input class="form-check-input flex-shrink-0 fs-4" type="checkbox" name="bab_sering" value="1" <?php echo e(($item->bab_sering ?? false) ? 'checked' : ''); ?>>
                                            <span class="small fw-medium">BAB lebih sering / encer, mata cekung, haus minum lahap, atau diare berdarah</span>
                                        </label>
                                        <label class="list-group-item d-flex gap-3 align-items-center p-3 border-0 bg-light mb-2 rounded-3">
                                            <input class="form-check-input flex-shrink-0 fs-4" type="checkbox" name="kencing_sedikit" value="1" <?php echo e(($item->kencing_sedikit ?? false) ? 'checked' : ''); ?>>
                                            <span class="small fw-medium">Kencing sedikit / tidak kencing 6 jam, warna kuning pekat / kecoklatan</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="list-group">
                                        <label class="list-group-item d-flex gap-3 align-items-center p-3 border-0 bg-light mb-2 rounded-3">
                                            <input class="form-check-input flex-shrink-0 fs-4" type="checkbox" name="kulit_pucat_biru" value="1" <?php echo e(($item->kulit_pucat_biru ?? false) ? 'checked' : ''); ?>>
                                            <span class="small fw-medium">Warna kulit tampak pucat / biru / memar di sekitar mulut / tangan / kaki</span>
                                        </label>
                                        <label class="list-group-item d-flex gap-3 align-items-center p-3 border-0 bg-light mb-2 rounded-3">
                                            <input class="form-check-input flex-shrink-0 fs-4" type="checkbox" name="aktivitas_lemah" value="1" <?php echo e(($item->aktivitas_lemah ?? false) ? 'checked' : ''); ?>>
                                            <span class="small fw-medium">Aktivitas tampak lemah / tidak aktif / tidak bereaksi / tidak sadar</span>
                                        </label>
                                        <label class="list-group-item d-flex gap-3 align-items-center p-3 border-0 bg-light mb-2 rounded-3">
                                            <input class="form-check-input flex-shrink-0 fs-4" type="checkbox" name="telinga_cairan" value="1" <?php echo e(($item->telinga_cairan ?? false) ? 'checked' : ''); ?>>
                                            <span class="small fw-medium">Telinga keluar cairan / bau / gatal / bengkak di belakang telinga</span>
                                        </label>
                                        <label class="list-group-item d-flex gap-3 align-items-center p-3 border-0 bg-light mb-2 rounded-3">
                                            <input class="form-check-input flex-shrink-0 fs-4" type="checkbox" name="tidak_makan" value="1" <?php echo e(($item->tidak_makan ?? false) ? 'checked' : ''); ?>>
                                            <span class="small fw-medium">Tidak mau makan / minum, berat badan tidak naik sesuai pertumbuhan</span>
                                        </label>

                                        <div class="p-3 bg-light rounded-3 mt-2 border-primary border-start border-4">
                                            <label class="form-label small fw-bold text-primary mb-1">Tanggal, Nama & Paraf Kader/Nakes</label>
                                            <input type="text" name="paraf_kader_nakes" value="<?php echo e($item->paraf_kader_nakes ?? ''); ?>" class="form-control bg-white" placeholder="Contoh: 15/09/26 - Bidan Ratna">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 mt-4 pt-3 border-top d-flex justify-content-end">
                                    <button type="submit" class="btn btn-gradient-primary rounded-pill px-5 py-2 shadow">
                                        <i class="bi bi-save2-fill me-2"></i> Simpan Catatan Bulan <?php echo e($m); ?>

                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>

    <!-- ================= TAB 2: PERKEMBANGAN 12 - 18 BULAN ================= -->
    <div class="col-12" x-show="activeTab === 'perkembangan18'" x-transition>
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
            <div class="card-header bg-white border-0 p-4 pb-0">
                <h5 class="fw-bold mb-1 text-gradient">Pantau Tumbuh Kembang Bayi Umur 12 - 18 Bulan</h5>
                <p class="text-muted small mb-0">Beri tanda centang pada kolom Ya/Tidak. Jika anak belum bisa melakukan salah satu hal berikut, segera bawa ke Puskesmas.</p>
            </div>

            <div class="card-body p-4">
                <?php $p18 = $perkembangan18 ?? null; ?>
                <form action="<?php echo e(route('pengguna.perkembangan_bayi_18_bulan.save')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle mb-4">
                            <thead class="table-warning text-dark text-center">
                                <tr>
                                    <th style="width: 60px;" class="py-3">No.</th>
                                    <th class="py-3 text-start">Penanda Perkembangan Anak</th>
                                    <th style="width: 100px;" class="py-3">Ya</th>
                                    <th style="width: 100px;" class="py-3">Tidak</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center fw-bold bg-light">1</td>
                                    <td class="fw-medium">Anak bisa berdiri sendiri tanpa berpegangan?</td>
                                    <td class="text-center">
                                        <input type="radio" name="berdiri_tanpa_pegangan" value="1" class="form-check-input fs-4" <?php echo e((isset($p18->berdiri_tanpa_pegangan) && $p18->berdiri_tanpa_pegangan == 1) ? 'checked' : ''); ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="berdiri_tanpa_pegangan" value="0" class="form-check-input fs-4" <?php echo e((isset($p18->berdiri_tanpa_pegangan) && $p18->berdiri_tanpa_pegangan == 0) ? 'checked' : ''); ?>>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">2</td>
                                    <td class="fw-medium">Anak bisa membungkuk memungut mainan kemudian berdiri kembali?</td>
                                    <td class="text-center">
                                        <input type="radio" name="bungkuk_pungut_mainan" value="1" class="form-check-input fs-4" <?php echo e((isset($p18->bungkuk_pungut_mainan) && $p18->bungkuk_pungut_mainan == 1) ? 'checked' : ''); ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="bungkuk_pungut_mainan" value="0" class="form-check-input fs-4" <?php echo e((isset($p18->bungkuk_pungut_mainan) && $p18->bungkuk_pungut_mainan == 0) ? 'checked' : ''); ?>>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">3</td>
                                    <td class="fw-medium">Anak bisa berjalan mundur lima langkah?</td>
                                    <td class="text-center">
                                        <input type="radio" name="jalan_mundur_5_langkah" value="1" class="form-check-input fs-4" <?php echo e((isset($p18->jalan_mundur_5_langkah) && $p18->jalan_mundur_5_langkah == 1) ? 'checked' : ''); ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="jalan_mundur_5_langkah" value="0" class="form-check-input fs-4" <?php echo e((isset($p18->jalan_mundur_5_langkah) && $p18->jalan_mundur_5_langkah == 0) ? 'checked' : ''); ?>>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">4</td>
                                    <td class="fw-medium">Anak bisa memanggil ayah dengan kata "papa", memanggil ibu dengan kata "mama"?</td>
                                    <td class="text-center">
                                        <input type="radio" name="panggil_papa_mama" value="1" class="form-check-input fs-4" <?php echo e((isset($p18->panggil_papa_mama) && $p18->panggil_papa_mama == 1) ? 'checked' : ''); ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="panggil_papa_mama" value="0" class="form-check-input fs-4" <?php echo e((isset($p18->panggil_papa_mama) && $p18->panggil_papa_mama == 0) ? 'checked' : ''); ?>>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">5</td>
                                    <td class="fw-medium">Anak bisa menumpuk dua kubus?</td>
                                    <td class="text-center">
                                        <input type="radio" name="tumpuk_2_kubus" value="1" class="form-check-input fs-4" <?php echo e((isset($p18->tumpuk_2_kubus) && $p18->tumpuk_2_kubus == 1) ? 'checked' : ''); ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="tumpuk_2_kubus" value="0" class="form-check-input fs-4" <?php echo e((isset($p18->tumpuk_2_kubus) && $p18->tumpuk_2_kubus == 0) ? 'checked' : ''); ?>>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">6</td>
                                    <td class="fw-medium">Anak bisa memasukkan kubus di kotak?</td>
                                    <td class="text-center">
                                        <input type="radio" name="masuk_kubus_kotak" value="1" class="form-check-input fs-4" <?php echo e((isset($p18->masuk_kubus_kotak) && $p18->masuk_kubus_kotak == 1) ? 'checked' : ''); ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="masuk_kubus_kotak" value="0" class="form-check-input fs-4" <?php echo e((isset($p18->masuk_kubus_kotak) && $p18->masuk_kubus_kotak == 0) ? 'checked' : ''); ?>>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">7</td>
                                    <td class="fw-medium">Anak bisa menunjuk apa yang diinginkan tanpa menangis/merengek, anak bisa mengeluarkan suara yang menyenangkan atau menarik tangan ibu?</td>
                                    <td class="text-center">
                                        <input type="radio" name="tunjuk_tanpa_nangis" value="1" class="form-check-input fs-4" <?php echo e((isset($p18->tunjuk_tanpa_nangis) && $p18->tunjuk_tanpa_nangis == 1) ? 'checked' : ''); ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="tunjuk_tanpa_nangis" value="0" class="form-check-input fs-4" <?php echo e((isset($p18->tunjuk_tanpa_nangis) && $p18->tunjuk_tanpa_nangis == 0) ? 'checked' : ''); ?>>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">8</td>
                                    <td class="fw-medium">Anak bisa memperlihatkan rasa cemburu/bersaing?</td>
                                    <td class="text-center">
                                        <input type="radio" name="rasa_cemburu" value="1" class="form-check-input fs-4" <?php echo e((isset($p18->rasa_cemburu) && $p18->rasa_cemburu == 1) ? 'checked' : ''); ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="rasa_cemburu" value="0" class="form-check-input fs-4" <?php echo e((isset($p18->rasa_cemburu) && $p18->rasa_cemburu == 0) ? 'checked' : ''); ?>>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-gradient-primary rounded-pill px-5 py-2 shadow">
                            <i class="bi bi-save2-fill me-2"></i> Simpan Tumbuh Kembang 12 - 18 Bulan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ================= TAB 3: PERKEMBANGAN 18 - 24 BULAN ================= -->
    <div class="col-12" x-show="activeTab === 'perkembangan24'" x-transition>
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
            <div class="card-header bg-white border-0 p-4 pb-0">
                <h5 class="fw-bold mb-1 text-gradient">Pantau Tumbuh Kembang Bayi Umur 18 - 24 Bulan</h5>
                <p class="text-muted small mb-0">Beri tanda centang pada kolom Ya/Tidak. Jika anak belum bisa melakukan salah satu hal berikut, segera bawa ke Puskesmas.</p>
            </div>

            <div class="card-body p-4">
                <?php $p24 = $perkembangan24 ?? null; ?>
                <form action="<?php echo e(route('pengguna.perkembangan_bayi_24_bulan.save')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle mb-4">
                            <thead class="table-warning text-dark text-center">
                                <tr>
                                    <th style="width: 60px;" class="py-3">No.</th>
                                    <th class="py-3 text-start">Penanda Perkembangan Anak</th>
                                    <th style="width: 100px;" class="py-3">Ya</th>
                                    <th style="width: 100px;" class="py-3">Tidak</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center fw-bold bg-light">1</td>
                                    <td class="fw-medium">Anak bisa berdiri sendiri tanpa berpegangan 30 detik?</td>
                                    <td class="text-center">
                                        <input type="radio" name="berdiri_30_detik" value="1" class="form-check-input fs-4" <?php echo e((isset($p24->berdiri_30_detik) && $p24->berdiri_30_detik == 1) ? 'checked' : ''); ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="berdiri_30_detik" value="0" class="form-check-input fs-4" <?php echo e((isset($p24->berdiri_30_detik) && $p24->berdiri_30_detik == 0) ? 'checked' : ''); ?>>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">2</td>
                                    <td class="fw-medium">Anak bisa berjalan tanpa terhuyung-huyung?</td>
                                    <td class="text-center">
                                        <input type="radio" name="jalan_tanpa_huyung" value="1" class="form-check-input fs-4" <?php echo e((isset($p24->jalan_tanpa_huyung) && $p24->jalan_tanpa_huyung == 1) ? 'checked' : ''); ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="jalan_tanpa_huyung" value="0" class="form-check-input fs-4" <?php echo e((isset($p24->jalan_tanpa_huyung) && $p24->jalan_tanpa_huyung == 0) ? 'checked' : ''); ?>>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">3</td>
                                    <td class="fw-medium">Anak bisa menumpuk 4 buah kubus?</td>
                                    <td class="text-center">
                                        <input type="radio" name="tumpuk_4_kubus" value="1" class="form-check-input fs-4" <?php echo e((isset($p24->tumpuk_4_kubus) && $p24->tumpuk_4_kubus == 1) ? 'checked' : ''); ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="tumpuk_4_kubus" value="0" class="form-check-input fs-4" <?php echo e((isset($p24->tumpuk_4_kubus) && $p24->tumpuk_4_kubus == 0) ? 'checked' : ''); ?>>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">4</td>
                                    <td class="fw-medium">Anak bisa memungut benda kecil dengan ibu jari dan jari telunjuk?</td>
                                    <td class="text-center">
                                        <input type="radio" name="pungut_benda_kecil" value="1" class="form-check-input fs-4" <?php echo e((isset($p24->pungut_benda_kecil) && $p24->pungut_benda_kecil == 1) ? 'checked' : ''); ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="pungut_benda_kecil" value="0" class="form-check-input fs-4" <?php echo e((isset($p24->pungut_benda_kecil) && $p24->pungut_benda_kecil == 0) ? 'checked' : ''); ?>>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">5</td>
                                    <td class="fw-medium">Anak bisa menggelindingkan bola ke arah sasaran?</td>
                                    <td class="text-center">
                                        <input type="radio" name="gelinding_bola" value="1" class="form-check-input fs-4" <?php echo e((isset($p24->gelinding_bola) && $p24->gelinding_bola == 1) ? 'checked' : ''); ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="gelinding_bola" value="0" class="form-check-input fs-4" <?php echo e((isset($p24->gelinding_bola) && $p24->gelinding_bola == 0) ? 'checked' : ''); ?>>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">6</td>
                                    <td class="fw-medium">Anak bisa menyebut 3 - 6 kata yang mempunyai arti?</td>
                                    <td class="text-center">
                                        <input type="radio" name="sebut_3_6_kata" value="1" class="form-check-input fs-4" <?php echo e((isset($p24->sebut_3_6_kata) && $p24->sebut_3_6_kata == 1) ? 'checked' : ''); ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="sebut_3_6_kata" value="0" class="form-check-input fs-4" <?php echo e((isset($p24->sebut_3_6_kata) && $p24->sebut_3_6_kata == 0) ? 'checked' : ''); ?>>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">7</td>
                                    <td class="fw-medium">Anak bisa membantu/menirukan pekerjaan rumah tangga?</td>
                                    <td class="text-center">
                                        <input type="radio" name="bantu_pekerjaan_rumah" value="1" class="form-check-input fs-4" <?php echo e((isset($p24->bantu_pekerjaan_rumah) && $p24->bantu_pekerjaan_rumah == 1) ? 'checked' : ''); ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="bantu_pekerjaan_rumah" value="0" class="form-check-input fs-4" <?php echo e((isset($p24->bantu_pekerjaan_rumah) && $p24->bantu_pekerjaan_rumah == 0) ? 'checked' : ''); ?>>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold bg-light">8</td>
                                    <td class="fw-medium">Anak bisa memegang cangkir sendiri, belajar makan-minum sendiri?</td>
                                    <td class="text-center">
                                        <input type="radio" name="pegang_cangkir_sendiri" value="1" class="form-check-input fs-4" <?php echo e((isset($p24->pegang_cangkir_sendiri) && $p24->pegang_cangkir_sendiri == 1) ? 'checked' : ''); ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="pegang_cangkir_sendiri" value="0" class="form-check-input fs-4" <?php echo e((isset($p24->pegang_cangkir_sendiri) && $p24->pegang_cangkir_sendiri == 0) ? 'checked' : ''); ?>>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-gradient-primary rounded-pill px-5 py-2 shadow">
                            <i class="bi bi-save2-fill me-2"></i> Simpan Tumbuh Kembang 18 - 24 Bulan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .btn-gradient-primary {
        background: linear-gradient(135deg, var(--accent1) 0%, var(--accent2) 100%);
        border: 0;
        color: white;
    }
    .btn-gradient-primary:hover {
        opacity: 0.9;
        transform: translateY(-1px);
        color: white;
    }
    .bg-gradient-info {
        background: linear-gradient(135deg, #047857 0%, #065f46 100%);
    }
    .transition-all {
        transition: all 0.25s ease-in-out;
    }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('pengguna.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\MomSpire\resources\views\pengguna\kia-bulanan-anak-24.blade.php ENDPATH**/ ?>