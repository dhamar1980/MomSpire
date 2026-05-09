@extends('pengguna.master')

@section('title', 'Status Kehamilan - MomSpire')
@section('header_title', 'Status Kehamilan')
@section('header_subtitle', 'Detail status kehamilan, riwayat kontrol, dan ringkasan kesehatan ibu')
@section('header_action')
	<a href="{{ route('pengguna.dashboard') }}" class="btn btn-back-page">
		<i class="bi bi-arrow-left"></i>
		<span>Kembali</span>
	</a>
@endsection

@push('head')
<script>
	window.__MOMSPIRE_SIDEBAR_OPEN = false;
	window.__MOMSPIRE_HEADER_TITLE_LOCK = 'Status Kehamilan';
</script>
<style>
	.btn-back-page {
		background: #fff;
		border: 1px solid rgba(15, 23, 42, .12);
		border-radius: 999px;
		color: #0f172a;
		font-weight: 700;
		padding: 10px 16px;
		box-shadow: 0 10px 20px rgba(15, 23, 42, .05);
		transition: all .2s ease;
		transform: translateX(8px);
		margin-right: 6px;
	}

	.btn-back-page:hover {
		transform: translateX(8px) translateY(-1px);
		border-color: rgba(15, 23, 42, .25);
		box-shadow: 0 14px 24px rgba(15, 23, 42, .12);
		color: #0f172a;
	}

	.status-shell {
		background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
		border: 1px solid rgba(15, 23, 42, .05);
		border-radius: 28px;
		box-shadow: 0 18px 40px rgba(15, 23, 42, .06);
		padding: 28px;
	}

	.status-hero {
		background: linear-gradient(135deg, #fff1f7 0%, #ecfeff 100%);
		border: 1px solid rgba(230, 57, 128, .16);
		border-radius: 24px;
		padding: 24px;
		margin-bottom: 24px;
		position: relative;
		overflow: hidden;
	}

	.status-hero::after {
		content: '';
		position: absolute;
		inset: auto -100px -110px auto;
		width: 240px;
		height: 240px;
		border-radius: 50%;
		background: rgba(14, 165, 233, .10);
		pointer-events: none;
	}

	.status-hero h2 {
		font-size: 30px;
		font-weight: 800;
		margin-bottom: 10px;
		color: #be185d;
	}

	.status-hero p {
		margin-bottom: 0;
		color: #334155;
		max-width: 58rem;
	}

	.status-chip {
		display: inline-flex;
		align-items: center;
		gap: 8px;
		padding: 10px 14px;
		border-radius: 999px;
		background: #fff;
		border: 1px solid rgba(230, 57, 128, .18);
		font-size: 13px;
		font-weight: 700;
		color: #be185d;
	}

	.status-card {
		background: linear-gradient(180deg, #fff 0%, #fff8fb 100%);
		border: 1px solid rgba(230, 57, 128, .15);
		border-radius: 22px;
		height: 100%;
		padding: 18px;
		box-shadow: 0 14px 28px rgba(244, 114, 182, .10);
	}

	.status-card-label {
		font-size: .78rem;
		text-transform: uppercase;
		letter-spacing: .12em;
		font-weight: 800;
		color: #64748b;
	}

	.status-card-value {
		font-size: 1.12rem;
		font-weight: 800;
		color: #0f172a;
	}

	.status-icon {
		width: 48px;
		height: 48px;
		border-radius: 16px;
		display: inline-flex;
		align-items: center;
		justify-content: center;
		font-size: 1.2rem;
		margin-bottom: 12px;
	}

	.status-icon.active { background: rgba(236, 72, 153, .12); color: #be185d; }
	.status-icon.accent { background: rgba(14, 165, 233, .12); color: #0284c7; }
	.status-icon.warning { background: rgba(245, 158, 11, .16); color: #d97706; }
	.status-icon.success { background: rgba(34, 197, 94, .14); color: #15803d; }
	.status-icon.primary { background: rgba(99, 102, 241, .12); color: #4338ca; }
	.status-icon.muted { background: rgba(100, 116, 139, .12); color: #475569; }

	.status-panel {
		background: #fff;
		border: 1px solid rgba(148, 163, 184, .18);
		border-radius: 22px;
		padding: 20px;
		box-shadow: 0 12px 30px rgba(15, 23, 42, .05);
	}

	.section-title {
		font-size: 1.02rem;
		font-weight: 800;
		margin-bottom: 6px;
		color: #0f172a;
	}

	.section-subtitle {
		color: #64748b;
		font-size: .92rem;
		margin-bottom: 0;
	}

	.history-item {
		border: 1px solid rgba(148, 163, 184, .16);
		border-radius: 18px;
		padding: 16px;
		background: linear-gradient(180deg, #fff, #f8fafc);
	}

	.history-badge {
		display: inline-flex;
		align-items: center;
		padding: 6px 10px;
		border-radius: 999px;
		font-size: 12px;
		font-weight: 700;
		background: rgba(230, 57, 128, .10);
		color: #be185d;
	}

	.timeline-line {
		position: relative;
		padding-left: 18px;
	}

	.timeline-line::before {
		content: '';
		position: absolute;
		left: 6px;
		top: 8px;
		bottom: 8px;
		width: 2px;
		background: linear-gradient(180deg, rgba(230, 57, 128, .45), rgba(14, 165, 233, .35));
	}

	.timeline-dot {
		position: absolute;
		left: 0;
		top: 10px;
		width: 14px;
		height: 14px;
		border-radius: 999px;
		background: #fff;
		border: 3px solid #e63980;
		box-shadow: 0 0 0 6px rgba(230, 57, 128, .08);
	}

	.empty-state {
		background: linear-gradient(180deg, #fff, #fff7fb);
		border: 1px dashed rgba(230, 57, 128, .22);
		border-radius: 20px;
		padding: 20px;
		color: #475569;
	}

	@media (max-width: 768px) {
		.status-shell {
			padding: 18px;
		}

		.status-hero h2 {
			font-size: 24px;
		}
	}
</style>
@endpush

@section('content')
	<div class="status-shell">
		<div class="status-hero">
			<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3 position-relative" style="z-index: 1;">
				<div>
					<div class="status-chip mb-3">
						<i class="bi bi-heart-pulse-fill"></i>
						Status Kehamilan
					</div>
					<h2 class="mb-2">{{ $isHamil ? 'Pemantauan kehamilan aktif' : 'Status belum menunjukkan kehamilan aktif' }}</h2>
					<p>
						Halaman ini merangkum status kehamilan saat ini, ringkasan kesehatan, serta riwayat kontrol yang tercatat agar mudah dipantau dari satu tempat.
					</p>
				</div>
				<div class="text-end">
					<div class="status-chip mb-2"><i class="bi bi-person-heart"></i>{{ $pengguna->name }}</div>
					<div class="text-muted small">Data disesuaikan dari profil pengguna aktif</div>
				</div>
			</div>

			<div class="row g-3 position-relative" style="z-index: 1;">
				@foreach ($ringkasan as $item)
					<div class="col-12 col-md-6 col-xl-4">
						<div class="status-card h-100">
							<div class="status-icon {{ $item['tone'] }}">
								<i class="bi {{ $item['icon'] }}"></i>
							</div>
							<div class="status-card-label mb-1">{{ $item['label'] }}</div>
							<div class="status-card-value">{{ $item['value'] }}</div>
						</div>
					</div>
				@endforeach
			</div>
		</div>

		<div class="row g-4 mb-4">
			<div class="col-12 col-xl-7">
				<div class="status-panel h-100">
					<div class="d-flex align-items-start justify-content-between gap-3 mb-4">
						<div>
							<div class="section-title">Detail Kehamilan Saat Ini</div>
							<p class="section-subtitle">Ringkasan kondisi yang bisa dipakai untuk memantau kehamilan secara cepat.</p>
						</div>
						<span class="history-badge">{{ $isHamil ? 'Aktif' : 'Tidak Aktif' }}</span>
					</div>

					@if ($isHamil)
						<div class="row g-3">
							<div class="col-md-6">
								<div class="history-item h-100">
									<div class="text-muted small mb-1">Status kehamilan</div>
									<div class="fw-bold fs-5" style="color:#be185d;">Sedang hamil</div>
									<p class="mb-0 text-muted small mt-2">Pantau perkembangan janin, jadwal ANC, dan catatan konsultasi berikutnya secara berkala.</p>
								</div>
							</div>
							<div class="col-md-6">
								<div class="history-item h-100">
									<div class="text-muted small mb-1">Kehamilan aktif</div>
									<div class="fw-bold fs-5">Kehamilan ke-{{ $kehamilanKe }}</div>
									<p class="mb-0 text-muted small mt-2">Nomor ini dihitung dari data anak yang sudah tersimpan dan kehamilan yang sedang berjalan.</p>
								</div>
							</div>
							<div class="col-md-6">
								<div class="history-item h-100">
									<div class="text-muted small mb-1">HPL</div>
									<div class="fw-bold fs-5">Belum tersedia</div>
									<p class="mb-0 text-muted small mt-2">Tanggal perkiraan lahir akan tampil setelah data HPL diisi pada profil atau catatan kontrol.</p>
								</div>
							</div>
							<div class="col-md-6">
								<div class="history-item h-100">
									<div class="text-muted small mb-1">Risiko kehamilan</div>
									<div class="fw-bold fs-5">Normal</div>
									<p class="mb-0 text-muted small mt-2">Status awal sistem menggunakan kategori normal sampai ada penilaian medis lain.</p>
								</div>
							</div>
						</div>
					@else
						<div class="empty-state">
							<div class="fw-bold mb-2">Belum ada kehamilan aktif yang tercatat</div>
							<p class="mb-0">Jika status berubah, halaman ini akan menampilkan detail kehamilan aktif, HPL, risiko, dan riwayat pemantauan terbaru.</p>
						</div>
					@endif
				</div>
			</div>

			<div class="col-12 col-xl-5">
				<div class="status-panel h-100">
					<div class="section-title">Riwayat Kontrol</div>
					<p class="section-subtitle mb-4">Catatan kontrol dan pemeriksaan terakhir yang tersedia di sistem.</p>

					@if (count($riwayatKontrol))
						<div class="d-grid gap-3">
							@foreach ($riwayatKontrol as $item)
								<div class="history-item">
									<div class="d-flex justify-content-between align-items-start gap-3">
										<div>
											<div class="fw-bold mb-1">{{ $item['judul'] }}</div>
											<div class="text-muted small">{{ $item['waktu'] }}</div>
										</div>
										<span class="history-badge">{{ $item['status'] }}</span>
									</div>
									<p class="mb-0 text-muted small mt-3">{{ $item['catatan'] }}</p>
								</div>
							@endforeach
						</div>
					@else
						<div class="empty-state">
							<div class="fw-bold mb-2">Belum ada riwayat kontrol</div>
							<p class="mb-0">Jadwal kontrol, hasil pemeriksaan, dan catatan konsultasi akan tampil di sini setelah dicatat di sistem.</p>
						</div>
					@endif
				</div>
			</div>
		</div>

		<div class="row g-4">
			<div class="col-12 col-lg-12">
				<div class="status-panel">
					<div class="section-title">Riwayat Kehamilan dan Anak</div>
					<p class="section-subtitle mb-4">Data ini membantu melihat urutan kehamilan sebelumnya dan status anak yang sudah tercatat.</p>

					@if (count($riwayatKehamilan))
						<div class="timeline-line d-grid gap-3">
							@foreach ($riwayatKehamilan as $item)
								<div class="history-item position-relative">
									<span class="timeline-dot"></span>
									<div class="d-flex flex-wrap justify-content-between align-items-start gap-2">
										<div>
											<div class="fw-bold">{{ $item['judul'] }}</div>
											<div class="text-muted small">{{ $item['tanggal'] }}</div>
										</div>
										<span class="history-badge">{{ $item['status'] }}</span>
									</div>
								</div>
							@endforeach
						</div>
					@else
						<div class="empty-state">
							<div class="fw-bold mb-2">Belum ada riwayat anak yang tersimpan</div>
							<p class="mb-0">Saat data anak atau kehamilan sebelumnya diinput, timeline ini akan menampilkan perjalanan kehamilan secara lebih lengkap.</p>
						</div>
					@endif
				</div>
			</div>
		</div>
	</div>
@endsection