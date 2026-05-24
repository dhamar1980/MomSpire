<?php $__env->startSection('title', 'Jadwal - MomSpire'); ?>
<?php $__env->startSection('header_title', 'Jadwal Kegiatan'); ?>
<?php $__env->startSection('header_subtitle', 'Jadwal kontrol dan imunisasi yang dijadwalkan bidan.'); ?>

<?php $__env->startPush('head'); ?>
<style>
    :root {
        --pengguna-primary: #e63980;
        --pengguna-primary-dark: #c41e5c;
        --pengguna-secondary: #00b894;
        --pengguna-accent: #00d4aa;
        --pengguna-purple: #6f42c1;
        --pengguna-blue-soft: #60a5fa;
        --pengguna-ink: #1e293b;
        --pengguna-muted: #64748b;
        --pengguna-text-light: #94a3b8;
        --gradient-primary: linear-gradient(135deg, #e63980 0%, #ff6b9d 100%);
        --gradient-secondary: linear-gradient(135deg, #00b894 0%, #00d4aa 100%);
        --gradient-accent: linear-gradient(135deg, #6f42c1 0%, #a855f7 100%);
        --shadow-md: 0 4px 15px rgba(0,0,0,0.08);
        --shadow-lg: 0 20px 40px rgba(0,0,0,0.1);
        --shadow-xl: 0 25px 50px rgba(0,0,0,0.12);
    }

    .jadwal-header {
        background: linear-gradient(135deg, #f8fafc 0%, #fdf2f8 100%);
        padding: 2rem;
        border-radius: 16px;
        margin-bottom: 2rem;
        border: 1px solid #e2e8f0;
    <?php $__env->startSection('content'); ?>
    <?php
        use Carbon\Carbon;

        $selectedDate = request('tanggal') ? Carbon::parse(request('tanggal')) : Carbon::today();
        $selectedSchedule = collect($jadwalList ?? [])->filter(function ($jadwal) use ($selectedDate) {
            return optional($jadwal->tanggal)->toDateString() === $selectedDate->toDateString();
        });
        $upcomingCount = collect($jadwalList ?? [])->filter(function ($jadwal) {
            return optional($jadwal->tanggal)->toDateString() >= Carbon::today()->toDateString();
        })->count();
    ?>

    <div class="container-fluid py-4">
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
            <div>
                <h2 class="fw-bold mb-0" style="color: var(--pengguna-ink); font-size: 1.75rem;">Jadwal Kegiatan</h2>
                <p class="text-muted mb-0" style="font-size: 0.95rem;">Jadwal yang dibuat bidan akan tampil di sini.</p>
            </div>
            <a href="<?php echo e(route('pengguna.dashboard')); ?>" class="back-button d-inline-flex align-items-center gap-2">
                <i class="bi bi-arrow-left"></i>
                <span class="d-none d-sm-inline">Kembali</span>
            </a>
        </div>

        <div class="jadwal-header">
            <div class="jadwal-date-display">
                <div>
                    <div class="jadwal-date-title"><?php echo e($selectedDate->translatedFormat('l')); ?></div>
                    <div class="jadwal-date-subtitle"><?php echo e($selectedDate->translatedFormat('d F Y')); ?></div>
                </div>
                <div style="text-align: right;">
                    <div style="font-size: 0.9rem; color: var(--pengguna-muted);">Jadwal terdekat</div>
                    <div style="font-size: 1.5rem; font-weight: 700; color: var(--pengguna-primary);"><?php echo e($upcomingCount); ?></div>
                </div>
            </div>

            <div class="d-flex gap-2 flex-wrap">
                <a href="<?php echo e(route('pengguna.jadwal', ['tanggal' => $selectedDate->copy()->subDay()->toDateString()])); ?>" class="btn btn-outline-secondary btn-sm"><i class="bi bi-chevron-left"></i> Hari Sebelumnya</a>
                <a href="<?php echo e(route('pengguna.jadwal', ['tanggal' => Carbon::today()->toDateString()])); ?>" class="btn btn-outline-secondary btn-sm">Hari Ini</a>
                <a href="<?php echo e(route('pengguna.jadwal', ['tanggal' => $selectedDate->copy()->addDay()->toDateString()])); ?>" class="btn btn-outline-secondary btn-sm">Hari Berikutnya <i class="bi bi-chevron-right"></i></a>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-5">
                <div class="section-card p-4 h-100">
                    <h5 class="fw-bold mb-3">Ringkasan Hari Ini</h5>
                    <div class="mb-3"><span class="value-pill"><i class="bi bi-heart-pulse"></i> <?php echo e(auth()->user()->is_hamil ? 'Sedang hamil' : 'Tidak hamil'); ?></span></div>
                    <div class="mb-3"><span class="value-pill"><i class="bi bi-clipboard2-pulse"></i> Usia kehamilan: <?php echo e(auth()->user()->usia_kehamilan_minggu ?? '—'); ?> minggu</span></div>
                    <div class="mb-3"><span class="value-pill"><i class="bi bi-calendar2-week"></i> Total jadwal tersimpan: <?php echo e(count($jadwalList ?? [])); ?></span></div>
                    <p class="text-muted mb-0">Jadwal kontrol dan imunisasi yang dibuat bidan akan otomatis muncul di daftar ini.</p>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="section-card p-4 h-100">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                        <h5 class="fw-bold mb-0">Agenda Tanggal Ini</h5>
                        <span class="text-muted small"><?php echo e($selectedSchedule->count()); ?> agenda</span>
                    </div>

                    <?php $__empty_1 = true; $__currentLoopData = $selectedSchedule; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jadwal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="schedule-row mb-3">
                            <div class="d-flex justify-content-between align-items-start gap-2 flex-wrap">
                                <div>
                                    <div class="fw-bold"><?php echo e($jadwal->judul); ?></div>
                                    <div class="text-muted small"><?php echo e(ucfirst($jadwal->jenis)); ?> • <?php echo e($jadwal->waktu ?: 'Belum ada jam'); ?></div>
                                    <div class="text-muted small">Bidan: <?php echo e($jadwal->bidan->name ?? '-'); ?></div>
                                </div>
                                <span class="badge text-bg-<?php echo e($jadwal->status === 'terjadwal' ? 'primary' : ($jadwal->status === 'selesai' ? 'success' : 'secondary')); ?>"><?php echo e(ucfirst($jadwal->status)); ?></span>
                            </div>
                            <?php if($jadwal->catatan): ?>
                                <div class="text-muted small mt-2"><?php echo e($jadwal->catatan); ?></div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="alert alert-light border mb-0">Belum ada jadwal pada tanggal ini.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="section-card p-4 mt-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                <div>
                    <h5 class="fw-bold mb-0">Semua Jadwal</h5>
                    <div class="text-muted small">Urutan jadwal dari yang paling dekat.</div>
                </div>
                <a href="<?php echo e(route('pengguna.konsultasi')); ?>" class="btn btn-outline-secondary btn-sm">Buka Konsultasi</a>
            </div>

            <div class="row g-3">
                <?php $__empty_1 = true; $__currentLoopData = $jadwalList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jadwal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="col-12">
                        <div class="schedule-row">
                            <div class="d-flex justify-content-between align-items-start gap-2 flex-wrap">
                                <div>
                                    <div class="fw-bold"><?php echo e($jadwal->judul); ?></div>
                                    <div class="text-muted small"><?php echo e($jadwal->tanggal->format('d M Y')); ?><?php echo e($jadwal->waktu ? ' • ' . $jadwal->waktu : ''); ?> • <?php echo e(ucfirst($jadwal->jenis)); ?></div>
                                    <div class="text-muted small">Bidan: <?php echo e($jadwal->bidan->name ?? '-'); ?></div>
                                    <?php if($jadwal->catatan): ?>
                                        <div class="text-muted small mt-2"><?php echo e($jadwal->catatan); ?></div>
                                    <?php endif; ?>
                                </div>
                                <span class="badge text-bg-<?php echo e($jadwal->status === 'terjadwal' ? 'primary' : ($jadwal->status === 'selesai' ? 'success' : 'secondary')); ?>"><?php echo e(ucfirst($jadwal->status)); ?></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="col-12">
                        <div class="alert alert-light border mb-0">Belum ada jadwal yang dibuat bidan.</div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
        </div>

        <!-- Asupan Gizi -->
        <div class="jadwal-card">
            <div class="timeline-item">
                <div class="timeline-dot"></div>
                <div class="jadwal-item-time">
                    <i class="bi bi-clock"></i>
                    <span>12:00 - 13:00</span>
                </div>
                <h5 class="jadwal-item-title">Asupan Gizi & Makan Siang</h5>
                <p class="jadwal-item-description">
                    Waktu untuk mengonsumsi makanan bergizi untuk mendukung perkembangan janin. Pastikan asupan kalsium dan protein tercukupi.
                </p>
                <div class="d-flex gap-2">
                    <span class="jadwal-item-status upcoming">
                        <i class="bi bi-calendar-check"></i> Direkomendasikan
                    </span>
                    <span style="font-size: 0.85rem; color: var(--pengguna-muted); display: inline-flex; align-items: center; gap: 0.4rem;">
                        <i class="bi bi-house"></i>
                        Di Rumah
                    </span>
                </div>
            </div>
        </div>

        <!-- Istirahat & Relaksasi -->
        <div class="jadwal-card">
            <div class="timeline-item">
                <div class="timeline-dot"></div>
                <div class="jadwal-item-time">
                    <i class="bi bi-clock"></i>
                    <span>14:00 - 15:00</span>
                </div>
                <h5 class="jadwal-item-title">Istirahat & Relaksasi</h5>
                <p class="jadwal-item-description">
                    Waktu istirahat yang cukup sangat penting untuk kesehatan ibu dan janin. Hindari aktivitas berat.
                </p>
                <div class="d-flex gap-2">
                    <span class="jadwal-item-status pending">
                        <i class="bi bi-info-circle"></i> Penting
                    </span>
                    <span style="font-size: 0.85rem; color: var(--pengguna-muted); display: inline-flex; align-items: center; gap: 0.4rem;">
                        <i class="bi bi-heart-pulse"></i>
                        Kesehatan
                    </span>
                </div>
            </div>
        </div>

        <!-- Olahraga Ringan -->
        <div class="jadwal-card">
            <div class="timeline-item">
                <div class="timeline-dot"></div>
                <div class="jadwal-item-time">
                    <i class="bi bi-clock"></i>
                    <span>16:00 - 16:30</span>
                </div>
                <h5 class="jadwal-item-title">Olahraga Ringan (Yoga Prenatal)</h5>
                <p class="jadwal-item-description">
                    Lakukan yoga ringan atau jalan santai selama 20-30 menit. Ini membantu melancarkan sirkulasi darah dan mempersiapkan persalinan.
                </p>
                <div class="d-flex gap-2">
                    <span class="jadwal-item-status upcoming">
                        <i class="bi bi-lightning"></i> Direkomendasikan
                    </span>
                    <span style="font-size: 0.85rem; color: var(--pengguna-muted); display: inline-flex; align-items: center; gap: 0.4rem;">
                        <i class="bi bi-compass"></i>
                        Outdoor/Indoor
                    </span>
                </div>
            </div>
        </div>

        <!-- Makan Malam & Suplemen -->
        <div class="jadwal-card">
            <div class="timeline-item">
                <div class="timeline-dot"></div>
                <div class="jadwal-item-time">
                    <i class="bi bi-clock"></i>
                    <span>19:00 - 20:00</span>
                </div>
                <h5 class="jadwal-item-title">Makan Malam & Konsumsi Suplemen</h5>
                <p class="jadwal-item-description">
                    Konsumsi makan malam yang bergizi dan jangan lupa minum suplemen prenatal yang telah direkomendasikan oleh dokter.
                </p>
                <div class="d-flex gap-2">
                    <span class="jadwal-item-status pending">
                        <i class="bi bi-capsule"></i> Penting
                    </span>
                    <span style="font-size: 0.85rem; color: var(--pengguna-muted); display: inline-flex; align-items: center; gap: 0.4rem;">
                        <i class="bi bi-cup"></i>
                        Obat & Makanan
                    </span>
                </div>
            </div>
        </div>

        <!-- Istirahat Malam -->
        <div class="jadwal-card">
            <div class="timeline-item">
                <div class="timeline-dot"></div>
                <div class="jadwal-item-time">
                    <i class="bi bi-clock"></i>
                    <span>22:00 - 06:00</span>
                </div>
                <h5 class="jadwal-item-title">Tidur Malam yang Berkualitas</h5>
                <p class="jadwal-item-description">
                    Pastikan tidur minimal 7-8 jam per hari. Gunakan bantal untuk menopang tubuh dan berbaring miring ke kiri untuk sirkulasi optimal.
                </p>
                <div class="d-flex gap-2">
                    <span class="jadwal-item-status completed">
                        <i class="bi bi-check-circle"></i> Penting
                    </span>
                    <span style="font-size: 0.85rem; color: var(--pengguna-muted); display: inline-flex; align-items: center; gap: 0.4rem;">
                        <i class="bi bi-moon-stars"></i>
                        Istirahat
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Tips Section -->
    <div class="card border-0 mt-4" style="background: linear-gradient(135deg, rgba(230, 57, 128, 0.08) 0%, rgba(107, 66, 193, 0.08) 100%); border-radius: 16px;">
        <div class="card-body">
            <h6 class="fw-bold mb-3" style="color: var(--pengguna-primary);">
                <i class="bi bi-lightbulb"></i> Tips Penting untuk Hari Ini
            </h6>
            <ul class="mb-0" style="list-style: none; padding: 0;">
                <li class="mb-2" style="padding-left: 1.5rem;">
                    <i class="bi bi-check" style="position: absolute; margin-left: -1.5rem; color: var(--pengguna-primary);"></i>
                    Minum air putih minimal 8-10 gelas per hari
                </li>
                <li class="mb-2" style="padding-left: 1.5rem;">
                    <i class="bi bi-check" style="position: absolute; margin-left: -1.5rem; color: var(--pengguna-primary);"></i>
                    Hindari stres dan istirahat yang cukup
                </li>
                <li class="mb-2" style="padding-left: 1.5rem;">
                    <i class="bi bi-check" style="position: absolute; margin-left: -1.5rem; color: var(--pengguna-primary);"></i>
                    Jangan lupa kontrol rutin dengan bidan/dokter
                </li>
                <li style="padding-left: 1.5rem;">
                    <i class="bi bi-check" style="position: absolute; margin-left: -1.5rem; color: var(--pengguna-primary);"></i>
                    Catat perubahan kesehatan atau keluhan yang dirasakan
                </li>
            </ul>
        </div>
    </div>

    <!-- Navigation Buttons -->
    <div class="d-flex gap-2 mt-4 flex-wrap">
        <?php if($selectedDate->copy()->subDay()->toDateString() >= \Carbon\Carbon::today()->toDateString()): ?>
            <a href="<?php echo e(route('pengguna.jadwal', ['tanggal' => $selectedDate->copy()->subDay()->toDateString()])); ?>" class="btn btn-outline-pink">
                <i class="bi bi-chevron-left"></i> Hari Sebelumnya
            </a>
        <?php endif; ?>
        
        <?php if($selectedDate->toDateString() !== \Carbon\Carbon::today()->toDateString()): ?>
            <a href="<?php echo e(route('pengguna.jadwal', ['tanggal' => \Carbon\Carbon::today()->toDateString()])); ?>" class="btn btn-outline-pink">
                <i class="bi bi-calendar-check"></i> Hari Ini
            </a>
        <?php endif; ?>
        
        <a href="<?php echo e(route('pengguna.jadwal', ['tanggal' => $selectedDate->copy()->addDay()->toDateString()])); ?>" class="btn btn-outline-pink ms-auto">
            Hari Berikutnya <i class="bi bi-chevron-right"></i>
        </a>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    // Add any interactivity here if needed
    document.addEventListener('DOMContentLoaded', function() {
        // Optional: Add smooth scrolling or other enhancements
        console.log('Jadwal page loaded');
    });
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('pengguna.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\MomSpire\resources\views\pengguna\jadwal.blade.php ENDPATH**/ ?>