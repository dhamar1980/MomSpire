@extends('pengguna.master')

@section('title', 'Dashboard Pengguna - MomSpire')
@section('header_title', 'Dashboard Pengguna')
@section('header_subtitle', '')

@section('content')
	<div class="role-hero p-4 mb-4">
		<div class="hero-content d-flex align-items-center justify-content-between">
			<div>
				<h2 class="h3 fw-bold mb-1">Halo, {{ auth()->user()->name }}! ðŸ‘‹</h2>
				<p class="mb-0" style="font-size: .98rem; line-height: 1.45; max-width: 720px;">Pantau informasi kesehatan kehamilan Anda dan akses fitur-fitur penting untuk kesehatan ibu dan bayi.</p>
			</div>
			<div class="text-end d-none d-md-block">
				<a href="{{ route('pengguna.pengaturan') }}" class="btn btn-sm btn-light" style="border-radius:12px;">Pengaturan Akun</a>
			</div>
		</div>
	</div>

	<div class="row g-4 mb-4">
		<div class="col-12">
			<div class="card" style="border-radius:16px; box-shadow: 0 12px 40px rgba(15,23,42,.06);">
				<div class="card-body d-flex flex-column flex-md-row align-items-start gap-3">
					<div class="me-3">
						<div class="small text-muted">Perkiraan Hari Lahir (HPL)</div>
						<div class="fw-bold h5">{{ auth()->user()->hpl ?? 'Belum diisi' }}</div>
					</div>
					<div class="me-3">
						<div class="small text-muted">Status Risiko</div>
						<div class="fw-bold text-warning">{{ auth()->user()->resiko ?? 'Normal' }}</div>
					</div>
					<div class="me-3">
						<div class="small text-muted">GPA</div>
						<div class="fw-bold">{{ auth()->user()->gpa ?? '0 / 0 / 0' }}</div>
					</div>
					<div class="ms-auto text-end">
						<a href="{{ route('pengguna.status_kehamilan') }}" class="btn btn-outline-primary">Lihat Detail Status</a>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row g-4">
		<div class="col-lg-3 col-md-6">
			<a href="{{ route('pengguna.kalkulator') }}" class="card feature-card">
				<div class="feature-icon" style="color: #e63980;">
					<i class="bi bi-calculator-fill"></i>
				</div>
				<div class="feature-title">Kalkulator Kehamilan</div>
				<div class="feature-desc">Hitung usia kehamilan dan estimasi tanggal persalinan Anda dengan akurat.</div>
			</a>
		</div>
		<div class="col-lg-3 col-md-6">
			<a href="{{ route('pengguna.status_kehamilan') }}" class="card feature-card">
				<div class="feature-icon" style="color: #ff6b9d;">
					<i class="bi bi-heart-pulse-fill"></i>
				</div>
				<div class="feature-title">Status Kehamilan</div>
				<div class="feature-desc">Pantau ringkasan lengkap status kesehatan dan catatan kehamilan Anda.</div>
			</a>
		</div>
		<div class="col-lg-3 col-md-6">
			<a href="{{ route('pengguna.jadwal') }}" class="card feature-card">
				<div class="feature-icon" style="color: #ff8c42;">
					<i class="bi bi-calendar2-check-fill"></i>
				</div>
				<div class="feature-title">Jadwal Kontrol</div>
				<div class="feature-desc">Lihat jadwal pemeriksaan rutin dan kelola janji temu dengan tenaga kesehatan.</div>
			</a>
		</div>
		<div class="col-lg-3 col-md-6">
			<a href="{{ route('pengguna.buku_kia') }}" class="card feature-card">
				<div class="feature-icon" style="color: #ffc107;">
					<i class="bi bi-book-fill"></i>
				</div>
				<div class="feature-title">Buku KIA</div>
				<div class="feature-desc">Akses dan download dokumen Buku KIA untuk panduan kesehatan ibu dan anak.</div>
			</a>
		</div>
	</div>
@endsection