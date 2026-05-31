@extends('bidan.master')

@section('title', 'Dashboard Bidan - MomSpire')
@section('header_title', 'Dashboard')

@section('content')
	@php
		$penggunaCount = $penggunaCount ?? 0;
		$bidanCount = $bidanCount ?? 0;
		$dokterCount = $dokterCount ?? 0;
		$recentPengguna = $recentPengguna ?? collect();
		$todaySchedules = $todaySchedules ?? collect();
		$hamilSoon = $hamilSoon ?? collect();
		$handledCount = $handledCount ?? 0;
		$focusItems = $focusItems ?? [];

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
	@endphp

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
						<h2 class="fw-bold mb-2 dash-hero-title">{{ $greeting }}, Bidan {{ auth()->user()->name }}</h2>
						<p class="mb-0 opacity-90">{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM YYYY') }}</p>
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
						@forelse ($hamilSoon as $user)
							<div class="list-group-item px-0 py-3 d-flex justify-content-between align-items-center flex-wrap gap-2 border-bottom-light">
								<div class="flex-grow-1">
									<div class="fw-semibold dash-item-title">{{ $user->name }}</div>
									<div class="text-muted small dash-item-sub"><i class="bi bi-telephone me-1"></i>{{ $user->no_telp ?? 'N/A' }}</div>
								</div>
								<span class="badge badge-soft-primary dash-badge-sm">Hamil</span>
							</div>
						@empty
							<div class="text-muted text-center py-4">Belum ada data pengguna yang hamil</div>
						@endforelse
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
					<div class="text-center my-4 dash-count-wrap">
						<div class="display-4 fw-bold text-gradient">{{ $handledCount }}</div>
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
						@forelse ($todaySchedules as $schedule)
							<div class="list-group-item px-0 py-3 border-bottom-light">
								<div class="d-flex justify-content-between align-items-start mb-2">
									<div>
										<div class="fw-semibold small dash-item-title">{{ $schedule->pengguna->name ?? 'User' }}</div>
										<div class="text-muted small dash-item-sub">{{ $schedule->judul }}</div>
									</div>
									<span class="badge badge-soft-primary rounded-pill text-uppercase dash-badge-xs">
										{{ $schedule->jenis }}
									</span>
								</div>
								<div class="text-muted small dash-item-sub">
									<i class="bi bi-clock-fill me-1 dash-icon-soft"></i>
									<strong class="dash-strong">{{ $schedule->waktu ? \Carbon\Carbon::parse($schedule->waktu)->format('H:i') : 'Belum ditentukan' }}</strong>
								</div>
								@if ($schedule->catatan)
									<div class="text-muted small mt-2 dash-note-chip">
										<i class="bi bi-chat-left-text me-1"></i>{{ Str::limit($schedule->catatan, 50) }}
									</div>
								@endif
							</div>
						@empty
							<div class="text-center py-5 dash-empty-state">
								<i class="bi bi-calendar-x dash-empty-icon"></i>
								<div class="dash-item-sub">Tidak ada jadwal hari ini</div>
							</div>
						@endforelse
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
						@forelse ($focusItems as $item)
							<div class="list-group-item px-0 py-3 {{ !$loop->last ? 'border-bottom-light' : '' }}">
								<div class="d-flex gap-2">
									<i class="bi bi-check-circle-fill dash-icon-soft dash-check-icon"></i>
									<div>
										<div class="fw-semibold small dash-item-title">{{ $item['title'] ?? 'Fokus Hari Ini' }}</div>
										<div class="text-muted small dash-item-sub">{{ $item['description'] ?? '-' }}</div>
									</div>
								</div>
							</div>
						@empty
							<div class="text-muted text-center py-4">Belum ada fokus hari ini.</div>
						@endforelse
					</div>
				</div>
			</div>
		</div>
	</div>
	</div>
@endsection
