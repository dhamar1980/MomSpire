<?php $__env->startSection('title', 'Dashboard Bidan - MomSpire'); ?>
<?php $__env->startSection('header_title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
	<?php
		$penggunaCount = $penggunaCount ?? 0;
		$bidanCount = $bidanCount ?? 0;
		$dokterCount = $dokterCount ?? 0;
		$recentPengguna = $recentPengguna ?? collect();
		
		// Get today's schedules for bidan (with table existence check)
		$todaySchedules = collect();
		if (\Illuminate\Support\Facades\Schema::hasTable('jadwal_pemantauan')) {
			$todaySchedules = \App\Models\JadwalPemantauan::where('bidan_id', auth()->id())
				->where('status', '!=', 'dibatalkan')
				->whereDate('tanggal', \Carbon\Carbon::today())
				->orderBy('waktu')
				->take(5)
				->get();
		}

		// Dynamic greeting based on time
		$hour = \Carbon\Carbon::now()->hour;
		if ($hour >= 5 && $hour < 11) {
			$greeting = 'Selamat pagi';
		} elseif ($hour >= 11 && $hour < 15) {
			$greeting = 'Selamat siang';
		} elseif ($hour >= 15 && $hour < 18) {
			$greeting = 'Selamat sore';
		} else {
			$greeting = 'Selamat malam';
		}
	?>

	<style>
			/* Hapus garis border-bottom-light di dalam card */
			.list-group-item { border: none !important; }
			.list-group-flush > .list-group-item { border-bottom-width: 0; }
			.list-group-flush > .list-group-item:last-child { border-bottom-width: 0; }
		</style>

		<div class="bidan-dashboard">

	<div class="mb-4">
		<div class="card role-card hero-role">
			<div class="card-body p-4 p-lg-5">
				<div class="d-flex justify-content-between align-items-start">
					<div>
						<h2 class="fw-bold mb-2 dash-hero-title"><?php echo e($greeting); ?>, Bidan <?php echo e(auth()->user()->name); ?></h2>
						<p class="mb-0 opacity-90"><?php echo e(\Carbon\Carbon::now()->isoFormat('dddd, D MMMM YYYY')); ?></p>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Grid Layout: Left 60% & Right 40% -->
	<div class="row g-3 g-lg-4 mb-4">
		<!-- LEFT: Pengguna yang akan melahirkan -->
		<div class="col-12 col-lg-7">
			<div class="card role-card h-100">
				<div class="card-body p-4 p-lg-5">
					<h5 class="fw-bold mb-4 text-uppercase text-dark dash-section-title">
						<i class="bi bi-exclamation-circle-fill me-2 dash-icon-soft"></i>
						Daftar Pengguna yang Akan Melahirkan dalam Waktu Dekat
					</h5>
					<div class="list-group list-group-flush">
						<?php
							$hamiltSoon = \App\Models\Pengguna::where('is_hamil', true)
								->orderBy('updated_at', 'desc')
								->take(8)
								->get();
						?>
						<?php $__empty_1 = true; $__currentLoopData = $hamiltSoon; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
							<div class="list-group-item px-0 py-3 d-flex justify-content-between align-items-center flex-wrap gap-2 border-bottom-light">
								<div class="flex-grow-1">
									<div class="fw-semibold dash-item-title"><?php echo e($user->name); ?></div>
									<div class="text-muted small dash-item-sub"><i class="bi bi-telephone me-1"></i><?php echo e($user->no_telp ?? 'N/A'); ?></div>
								</div>
								<span class="badge badge-soft-primary dash-badge-sm">Hamil</span>
							</div>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
							<div class="text-muted text-center py-4">Belum ada data pengguna yang hamil</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>

		<!-- RIGHT TOP: Pengguna Ditangani (compact list) -->
		<div class="col-12 col-lg-5">
			<div class="card role-card h-100 stat-card">
				<div class="card-body p-4 p-lg-5">
					<div class="d-flex justify-content-between align-items-start mb-3">
						<h5 class="fw-bold mb-0 text-uppercase text-dark dash-section-title">
							<i class="bi bi-people-fill text-muted me-2"></i>
							Pengguna yang Sudah Ditangani
						</h5>
					</div>
					<?php
						$handledCount = 0;
						if (\Illuminate\Support\Facades\Schema::hasTable('jadwal_pemantauan')) {
							$handledCount = \App\Models\JadwalPemantauan::where('bidan_id', auth()->id())->distinct('pengguna_id')->count('pengguna_id');
						}
					?>
					<div class="text-center my-4 dash-count-wrap">
						<div class="display-4 fw-bold text-gradient"><?php echo e($handledCount); ?></div>
						<div class="text-muted dash-item-title">Pengguna ditangani oleh Anda</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Grid Layout: 3 columns for bottom sections -->
	<div class="row g-3 g-lg-4">
		<!-- LEFT: Jadwal Kegiatan Hari Ini -->
		<div class="col-12 col-md-6 col-lg-4">
			<div class="card role-card h-100">
				<div class="card-body p-4 p-lg-4">
					<h5 class="fw-bold mb-4 text-uppercase text-dark dash-section-title">
						<i class="bi bi-calendar2-check me-2 dash-icon-soft"></i>
						Jadwal Kegiatan Hari Ini
					</h5>
					<div class="list-group list-group-flush">
						<?php $__empty_1 = true; $__currentLoopData = $todaySchedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $schedule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
							<div class="list-group-item px-0 py-3 border-bottom-light">
								<div class="d-flex justify-content-between align-items-start mb-2">
									<div>
										<div class="fw-semibold small dash-item-title"><?php echo e($schedule->pengguna->name ?? 'User'); ?></div>
										<div class="text-muted small dash-item-sub"><?php echo e($schedule->judul); ?></div>
									</div>
									<span class="badge badge-soft-primary rounded-pill text-uppercase dash-badge-xs">
										<?php echo e($schedule->jenis); ?>

									</span>
								</div>
								<div class="text-muted small dash-item-sub">
									<i class="bi bi-clock-fill me-1 dash-icon-soft"></i>
									<strong class="dash-strong"><?php echo e($schedule->waktu ? \Carbon\Carbon::parse($schedule->waktu)->format('H:i') : 'Belum ditentukan'); ?></strong>
								</div>
								<?php if($schedule->catatan): ?>
									<div class="text-muted small mt-2 dash-note-chip">
										<i class="bi bi-chat-left-text me-1"></i><?php echo e(Str::limit($schedule->catatan, 50)); ?>

									</div>
								<?php endif; ?>
							</div>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
							<div class="text-center py-5 dash-empty-state">
								<i class="bi bi-calendar-x dash-empty-icon"></i>
								<div class="dash-item-sub">Tidak ada jadwal hari ini</div>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>

		<!-- MIDDLE: Note Pribadi -->
		<div class="col-12 col-md-6 col-lg-4">
			<div class="card role-card h-100">
				<div class="card-body p-3 p-sm-4 p-lg-4">
					<h5 class="fw-bold mb-4 text-uppercase text-dark dash-section-title">
						<i class="bi bi-pencil-square me-2 dash-icon-soft"></i>
						Note Pribadi
					</h5>
					<textarea class="form-control form-control-sm flex-grow-1 note-textarea" placeholder="Tulis catatan pribadi untuk hari ini..."></textarea>
				</div>
			</div>
		</div>

		<!-- RIGHT: Fokus Hari Ini -->
		<div class="col-12 col-md-12 col-lg-4">
			<div class="card role-card h-100">
				<div class="card-body p-4 p-lg-4">
					<h5 class="fw-bold mb-4 text-uppercase text-dark dash-section-title">
						<i class="bi bi-star-fill me-2 dash-icon-soft"></i>
						Fokus Hari Ini
					</h5>
					<div class="list-group list-group-flush">
						<div class="list-group-item px-0 py-3 border-bottom-light">
							<div class="d-flex gap-2">
								<i class="bi bi-check-circle-fill dash-icon-soft dash-check-icon"></i>
								<div>
									<div class="fw-semibold small dash-item-title">Periksa Konsultasi Baru</div>
									<div class="text-muted small dash-item-sub">Respons pesan dari pengguna yang menunggu</div>
								</div>
							</div>
						</div>
						<div class="list-group-item px-0 py-3 border-bottom-light">
							<div class="d-flex gap-2">
								<i class="bi bi-check-circle-fill dash-icon-soft dash-check-icon"></i>
								<div>
									<div class="fw-semibold small dash-item-title">Update Jadwal Imunisasi</div>
									<div class="text-muted small dash-item-sub">Verifikasi jadwal imunisasi pengguna</div>
								</div>
							</div>
						</div>
						<div class="list-group-item px-0 py-3">
							<div class="d-flex gap-2">
								<i class="bi bi-check-circle-fill dash-icon-soft dash-check-icon"></i>
								<div>
									<div class="fw-semibold small dash-item-title">Review Data Pengguna Hamil</div>
									<div class="text-muted small dash-item-sub">Pantau status kesehatan ibu hamil</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('bidan.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\MomSpire\resources\views/bidan/dashboardBidan.blade.php ENDPATH**/ ?>