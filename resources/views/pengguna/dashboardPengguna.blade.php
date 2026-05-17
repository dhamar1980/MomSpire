@extends('pengguna.master')

@section('title', 'Dashboard Pengguna - MomSpire')
@section('header_title', 'Dashboard')
@section('header_subtitle', '')

@push('head')
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

	/* Bootstrap custom utilities */
	.btn-outline-pink {
		border-color: var(--pengguna-primary);
		color: var(--pengguna-primary);
	}
	.btn-outline-pink:hover {
		background-color: var(--pengguna-primary);
		border-color: var(--pengguna-primary);
		color: white;
	}

	.text-pink-primary { color: var(--pengguna-primary); }
	.bg-pink-light { background-color: rgba(230, 57, 128, 0.1); }

	.border-bottom-light {
		border-bottom: 1px solid #e2e8f0 !important;
	}

	.w-sm-auto {
		width: auto !important;
	}

	.tiny { font-size: 0.7rem; }

	/* Responsive schedule timeline */
	.schedule-timeline-responsive {
		display: flex;
		gap: 0.75rem;
		padding: 0.5rem 0;
		-webkit-overflow-scrolling: touch;
	}

	.schedule-day-btn {
		border: 2px solid #e2e8f0;
		border-radius: 14px;
		padding: 12px 8px;
		background: white;
		transition: all 0.2s ease;
		color: var(--pengguna-ink);
	}

	.schedule-day-btn:hover {
		border-color: #94a3b8;
		box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
		transform: translateY(-2px);
	}

	.schedule-day-btn.today {
		border-color: var(--pengguna-primary);
		border-width: 3px;
		box-shadow: 0 0 0 2px rgba(230, 57, 128, 0.15);
	}

	.schedule-day-btn.active {
		border-color: var(--pengguna-primary);
		background-color: var(--pengguna-primary);
		color: white;
	}

	.schedule-day-btn.active .text-muted {
		color: rgba(255, 255, 255, 0.7) !important;
	}

	/* Hero section responsive improvements */
	.pengguna-dashboard-shell {
		position: relative;
		isolation: isolate;
		background: linear-gradient(180deg, #ffffff 0%, #f0f4f8 100%);
		min-height: 100vh;
	}

	.pengguna-dashboard-shell::before {
		content: '';
		position: fixed;
		inset: 0;
		background:
			radial-gradient(circle at 12% 8%, rgba(230, 57, 128, 0.15), transparent 28%),
			radial-gradient(circle at 92% 14%, rgba(107, 66, 193, 0.12), transparent 26%),
			radial-gradient(circle at 20% 80%, rgba(0, 184, 148, 0.08), transparent 32%);
		z-index: -2;
		pointer-events: none;
	}

	.pengguna-dashboard-shell::after {
		content: '';
		position: fixed;
		inset: 0;
		background-image: linear-gradient(rgba(15, 23, 42, 0.015) 1px, transparent 1px), linear-gradient(90deg, rgba(15, 23, 42, 0.015) 1px, transparent 1px);
		background-size: 42px 42px;
		opacity: .3;
		pointer-events: none;
		z-index: -1;
	}

	.dashboard-card-modern {
		border: 1px solid #e2e8f0;
		border-radius: 20px;
		box-shadow: 0 2px 12px rgba(0, 0, 0, 0.05);
		transition: box-shadow .25s ease, border-color .25s ease;
		overflow: hidden;
		background: #fff;
	}

	.dashboard-card-modern:hover {
		box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
		border-color: #cbd5e1;
	}

	.hero-shell {
		position: relative;
		background: linear-gradient(135deg, #f8fafc 0%, #fdf2f8 100%);
		color: var(--pengguna-ink);
		overflow: hidden;
	}

	.hero-shell::after {
		content: '';
		position: absolute;
		inset: 0;
		background: linear-gradient(180deg, rgba(230,57,128,.04), transparent 62%);
		pointer-events: none;
		z-index: 0;
	}

	.hero-shell .card-body {
		position: relative;
		z-index: 1;
	}

	.hero-chip {
		display: inline-flex;
		align-items: center;
		gap: .45rem;
		padding: .36rem .75rem;
		border-radius: 999px;
		background: rgba(230, 57, 128, 0.12);
		color: var(--pengguna-primary-dark);
		font-size: .75rem;
		font-weight: 700;
		letter-spacing: .05em;
		text-transform: uppercase;
		border: none;
	}

	.hero-stat {
		padding: .78rem .82rem;
		border-radius: 14px;
		background: rgba(255, 255, 255, 0.92);
		border: 1px solid rgba(148, 163, 184, 0.25);
		transition: transform .2s ease, border-color .2s ease, box-shadow .2s ease;
	}

	.hero-stat:hover {
		transform: translateY(-3px);
		border-color: rgba(230, 57, 128, 0.35);
		box-shadow: 0 10px 22px rgba(15, 23, 42, 0.08);
	}

	.hero-figure {
		position: relative;
		display: flex;
		flex-direction: column;
		align-items: center;
		justify-content: center;
		gap: .5rem;
		min-height: 120px;
		border-radius: 16px;
		background: linear-gradient(180deg, rgba(255,255,255,.8), rgba(255,255,255,.55));
		border: 1px solid rgba(148, 163, 184, 0.22);
	}

	.hero-stats-row {
		display: grid;
		grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
		gap: .75rem;
	}

	@media (max-width: 576px) {
		.hero-stats-row {
			grid-template-columns: repeat(2, 1fr);
		}
	}

	.hero-hearts-container {
		position: relative;
		width: 100%;
		height: 120px;
		overflow: hidden;
	}

	.floating-heart {
		position: absolute;
		cursor: pointer;
		line-height: 1;
	}

	.floating-heart.h1 { top: 10px; left: 14px; font-size: 42px; color: #ffc0cb; opacity: 0.9; }
	.floating-heart.h2 { top: 18px; left: 116px; font-size: 16px; color: #ffd6e7; opacity: 0.82; }
	.floating-heart.h3 { top: 44px; right: 30px; font-size: 28px; color: #ffb3d9; opacity: 0.84; }
	.floating-heart.h4 { top: 76px; left: 58px; font-size: 22px; color: #ffd6e7; opacity: 0.8; }
	.floating-heart.h5 { top: 92px; right: 82px; font-size: 36px; color: #ffc0cb; opacity: 0.88; }
	.floating-heart.h6 { top: 118px; left: 10px; font-size: 14px; color: #ffd6e7; opacity: 0.76; }
	.floating-heart.h7 { top: 134px; left: 102px; font-size: 30px; color: #ffb3d9; opacity: 0.86; }
	.floating-heart.h8 { top: 152px; right: 14px; font-size: 18px; color: #ffd6e7; opacity: 0.78; }
	.floating-heart.h9 { top: 172px; left: 46px; font-size: 24px; color: #ffc0cb; opacity: 0.82; }
	.floating-heart.h10 { top: 178px; right: 62px; font-size: 40px; color: #ffb3d9; opacity: 0.88; }
	.floating-heart.h11 { top: 38px; left: 182px; font-size: 20px; color: #ffd6e7; opacity: 0.8; }
	.floating-heart.h12 { top: 116px; right: 148px; font-size: 26px; color: #ffc0cb; opacity: 0.84; }

	/* Centered mom-child SVG wrapper */
	.mom-hug-wrap {
		position: absolute;
		inset: 0;
		display: flex;
		align-items: center;
		justify-content: center;
		z-index: 2;
		pointer-events: none;
	}

	.mom-hug-wrap::before {
		content: "";
		position: absolute;
		inset: 0;
		z-index: 1;
		pointer-events: none;
		background: radial-gradient(circle at center, rgba(255,192,203,0.36) 0%, rgba(255,192,203,0.18) 28%, rgba(255,192,203,0.08) 42%, transparent 60%);
		filter: blur(18px);
		opacity: 0.95;
	}

	.mom-hug-svg {
		width: 88px;
		height: 88px;
		transform-origin: center;
		filter: drop-shadow(0 12px 28px rgba(15,23,42,0.12));
		pointer-events: none;
		animation: mom-pulse 3.6s ease-in-out infinite, mom-sway 8s ease-in-out infinite, halo-fade 6s ease-in-out infinite;
		z-index: 3;
		border-radius: 12px;
		box-shadow: 0 8px 30px rgba(255,182,193,0.08), inset 0 1px 0 rgba(255,255,255,0.12);
	}

	@keyframes halo-fade {
		0% { box-shadow: 0 8px 30px rgba(255,182,193,0.06); }
		50% { box-shadow: 0 14px 44px rgba(255,182,193,0.12); }
		100% { box-shadow: 0 8px 30px rgba(255,182,193,0.06); }
	}

	@keyframes mom-pulse {
		0% { transform: translateY(0) scale(0.985); }
		50% { transform: translateY(-6px) scale(1.02); }
		100% { transform: translateY(0) scale(0.985); }
	}

	@keyframes mom-sway {
		0% { transform: translateX(0) rotate(0deg); }
		25% { transform: translateX(-2px) rotate(-0.8deg); }
		50% { transform: translateX(0) rotate(0deg); }
		75% { transform: translateX(2px) rotate(0.8deg); }
		100% { transform: translateX(0) rotate(0deg); }
	}

	.mom-arm {
		transform-origin: 50% 20%;
		animation: mom-arm-hug 4.2s ease-in-out infinite;
	}

	@keyframes mom-arm-hug {
		0% { transform: rotate(6deg); }
		40% { transform: rotate(-6deg); }
		60% { transform: rotate(-2deg); }
		100% { transform: rotate(6deg); }
	}

	.child-rock {
		transform-origin: center 50%;
		animation: child-rock 3.8s ease-in-out infinite;
	}

	@keyframes child-rock {
		0% { transform: translateY(0) rotate(0deg); }
		50% { transform: translateY(-3px) rotate(-2.2deg); }
		100% { transform: translateY(0) rotate(0deg); }
	}

	@media (max-width: 767px) {
		.hero-hearts-container {
			height: 100px;
			max-width: 280px;
			margin: 0 auto;
		}
		.mom-hug-svg { width: 70px; height: 70px; }
		.floating-heart.h1 { font-size: 28px; }
		.floating-heart.h5 { font-size: 24px; }
		.floating-heart.h10 { font-size: 28px; }
	}

	.action-shell {
		background: #fff;
		color: var(--pengguna-ink);
	}

	.action-shell:hover {
		border-color: rgba(148, 163, 184, 0.45);
	}

	.action-shell .card-body {
		transition: transform .2s ease;
	}

	.action-shell:hover .card-body {
		transform: translateY(-2px);
	}

	.action-shell .action-badge {
		display: inline-flex;
		align-items: center;
		gap: .4rem;
		padding: .35rem .75rem;
		border-radius: 999px;
		font-size: .77rem;
		font-weight: 600;
		letter-spacing: .02em;
		background: rgba(100, 116, 139, .12);
		color: var(--pengguna-muted);
		border: none;
	}

	.action-shell.buku .action-badge {
		background: rgba(230, 57, 128, 0.1);
		color: #be185d;
		border: none;
	}

	.action-illustration {
		width: 90px;
		height: 90px;
		border-radius: 18px;
		display: grid;
		place-items: center;
		flex: 0 0 auto;
	}

	.action-illustration.buku {
		background: #f8fafc;
		box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
	}

	@media (max-width: 768px) {
		.action-shell .card-body {
			flex-direction: column !important;
		}
		.action-illustration {
			width: 80px;
			height: 80px;
		}
	}

	/* Scrollbar styling for timeline */
	.schedule-timeline-responsive::-webkit-scrollbar {
		height: 6px;
	}
	.schedule-timeline-responsive::-webkit-scrollbar-track {
		background: #f1f5f9;
		border-radius: 10px;
	}
	.schedule-timeline-responsive::-webkit-scrollbar-thumb {
		background: linear-gradient(90deg, rgba(230, 57, 128, 0.6), rgba(107, 66, 193, 0.6));
		border-radius: 10px;
	}
	.schedule-timeline-responsive::-webkit-scrollbar-thumb:hover {
		background: linear-gradient(90deg, #e63980, #6f42c1);
	}

	/* Floating heart animations */
	@keyframes float {
		0%, 100% {
			transform: translateY(0px);
		}
		50% {
			transform: translateY(-15px);
		}
	}

	@keyframes float-delay-1 {
		0%, 100% {
			transform: translateY(0px);
		}
		50% {
			transform: translateY(-12px);
		}
	}

	@keyframes float-delay-2 {
		0%, 100% {
			transform: translateY(0px);
		}
		50% {
			transform: translateY(-18px);
		}
	}

	@keyframes float-delay-3 {
		0%, 100% {
			transform: translateY(0px);
		}
		50% {
			transform: translateY(-10px);
		}
	}

	@keyframes float-delay-4 {
		0%, 100% {
			transform: translateY(0px);
		}
		50% {
			transform: translateY(-14px);
		}
	}

	.heart-float-1 {
		animation: float 3s ease-in-out infinite;
	}

	.heart-float-2 {
		animation: float-delay-1 3.5s ease-in-out infinite 0.3s;
	}

	.heart-float-3 {
		animation: float-delay-2 4s ease-in-out infinite 0.6s;
	}

	.heart-float-4 {
		animation: float-delay-3 3.2s ease-in-out infinite 0.9s;
	}

	.heart-float-5 {
		animation: float-delay-4 3.8s ease-in-out infinite 1.2s;
	}

	/* heart hover interaction */
	.bi-heart-fill {
		transition: transform .25s ease, opacity .25s ease;
	}

	.bi-heart-fill:hover {
		transform: scale(1.12) translateY(-6px);
		opacity: 0.9;
	}

	.calendar-day-btn {
		border: 1px solid #eef2f6;
		border-radius: 12px;
		background: #fff;
		transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease, background-color .18s ease;
		min-height: 72px;
	}

	.calendar-day-btn:hover {
		transform: translateY(-2px);
		box-shadow: 0 10px 20px rgba(15, 23, 42, 0.08);
	}

	.calendar-day-btn.is-today {
		background: linear-gradient(180deg, rgba(230, 57, 128, 0.16), rgba(255, 255, 255, 0.96));
		border-color: rgba(230, 57, 128, 0.55);
		box-shadow: 0 0 0 2px rgba(230, 57, 128, 0.16);
	}

	.calendar-day-btn.is-weekend {
		color: #dc2626;
	}

	.calendar-day-btn.is-holiday {
		background: linear-gradient(180deg, rgba(255, 235, 238, 0.96), rgba(255, 255, 255, 0.98));
		border-color: rgba(244, 63, 94, 0.4);
	}

	.calendar-day-btn.is-control {
		background: linear-gradient(180deg, rgba(219, 234, 254, 0.95), rgba(255, 255, 255, 0.98));
		border-color: rgba(59, 130, 246, 0.42);
	}

	.calendar-day-btn.is-immunization {
		background: linear-gradient(180deg, rgba(220, 252, 231, 0.95), rgba(255, 255, 255, 0.98));
		border-color: rgba(34, 197, 94, 0.42);
	}

	.calendar-day-btn.is-selected {
		border-color: rgba(230, 57, 128, 0.75);
		box-shadow: 0 12px 24px rgba(230, 57, 128, 0.18);
	}

	.calendar-marker {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		padding: 0.15rem 0.45rem;
		border-radius: 999px;
		font-size: 0.65rem;
		font-weight: 700;
		line-height: 1;
	}

	.calendar-marker.holiday {
		background: rgba(244, 63, 94, 0.12);
		color: #dc2626;
	}

	.calendar-marker.control {
		background: rgba(59, 130, 246, 0.12);
		color: #2563eb;
	}

	.calendar-marker.immunization {
		background: rgba(34, 197, 94, 0.12);
		color: #16a34a;
	}

	.detail-item {
		border: 1px solid #e2e8f0;
		border-radius: 14px;
		padding: .75rem .85rem;
		background: #fff;
	}

	.detail-item + .detail-item {
		margin-top: .6rem;
	}

	.detail-item .time {
		font-size: .78rem;
		font-weight: 700;
		color: var(--pengguna-muted);
	}

	.detail-item.control {
		border-color: rgba(59, 130, 246, 0.25);
		background: linear-gradient(180deg, rgba(239, 246, 255, 0.95), #fff);
	}

	.detail-item.immunization {
		border-color: rgba(34, 197, 94, 0.25);
		background: linear-gradient(180deg, rgba(240, 253, 244, 0.95), #fff);
	}

	.detail-item.meal {
		border-color: rgba(148, 163, 184, 0.22);
	}
</style>
@endpush

@section('content')
	<div class="pengguna-dashboard-shell">
		@php
			use Carbon\Carbon;
			$hour = Carbon::now()->hour;
			if ($hour < 10) { $greet = 'Selamat pagi'; }
			elseif ($hour < 15) { $greet = 'Selamat siang'; }
			elseif ($hour < 18) { $greet = 'Selamat sore'; }
			else { $greet = 'Selamat malam'; }
			$displayName = 'Ny. ' . (auth()->user()->name ?? 'Pengguna');
		@endphp

		<!-- Hero Greeting Section -->
		<a href="{{ route('pengguna.status_kehamilan') }}" class="text-decoration-none text-reset">
		<div class="card dashboard-card-modern hero-shell mb-0">
			<div class="card-body" style="padding: 12px;">
				<div class="row align-items-center g-2">
					<div class="col-12 col-lg-7 d-flex flex-column justify-content-between">
						<div>
							<h4 class="fw-bold mb-1" style="font-size: clamp(1.25rem, 5vw, 1.6rem); line-height: 1.2;">{{ $greet }}, {{ $displayName }}</h4>
							<div class="hero-stats-row">
								<div class="hero-stat">
									<small class="text-muted d-block" style="font-size: .74rem; margin-bottom: 0.3rem;">Status Kehamilan</small>
									<div class="fw-bold" style="font-size: .98rem; color: #1e293b;">{{ ($pengguna->is_hamil ?? auth()->user()->is_hamil) ? 'Sedang Hamil' : 'Tidak Hamil' }}</div>
								</div>
								<div class="hero-stat">
									<small class="text-muted d-block" style="font-size: .74rem; margin-bottom: 0.3rem;">HPL</small>
									<div class="fw-bold" style="font-size: .98rem; color: #1e293b;">{{ auth()->user()->hpl ?? '—' }}</div>
								</div>
								<div class="hero-stat">
									<small class="text-muted d-block" style="font-size: .74rem; margin-bottom: 0.3rem;">Risiko Kehamilan</small>
									<div class="fw-bold" style="font-size: .98rem; color: #1e293b;">{{ auth()->user()->resiko ?? 'Normal' }}</div>
								</div>
								<div class="hero-stat">
									<small class="text-muted d-block" style="font-size: .74rem; margin-bottom: 0.3rem;">GPA</small>
									<div class="fw-bold" style="font-size: .98rem; color: #1e293b;">{{ auth()->user()->gpa ?? '0/0/0' }}</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-12 col-lg-5">
						<div class="hero-figure">
							<div class="hero-hearts-container">
								<div class="mom-hug-wrap" aria-hidden="true">
									<img src="{{ asset('foto/mom.jpg') }}" alt="Ibu memeluk anak" class="mom-hug-svg mom-sway" style="border-radius: 12px; object-fit: cover;" />
								</div>
								<i class="bi bi-heart-fill floating-heart h1 heart-float-1" aria-hidden="true"></i>
								<i class="bi bi-heart-fill floating-heart h2 heart-float-2" aria-hidden="true"></i>
								<i class="bi bi-heart-fill floating-heart h3 heart-float-3" aria-hidden="true"></i>
								<i class="bi bi-heart-fill floating-heart h4 heart-float-4" aria-hidden="true"></i>
								<i class="bi bi-heart-fill floating-heart h5 heart-float-5" aria-hidden="true"></i>
								<i class="bi bi-heart-fill floating-heart h6 heart-float-2" aria-hidden="true"></i>
								<i class="bi bi-heart-fill floating-heart h7 heart-float-1" aria-hidden="true"></i>
								<i class="bi bi-heart-fill floating-heart h8 heart-float-4" aria-hidden="true"></i>
								<i class="bi bi-heart-fill floating-heart h9 heart-float-3" aria-hidden="true"></i>
								<i class="bi bi-heart-fill floating-heart h10 heart-float-5" aria-hidden="true"></i>
								<i class="bi bi-heart-fill floating-heart h11 heart-float-2" aria-hidden="true"></i>
								<i class="bi bi-heart-fill floating-heart h12 heart-float-4" aria-hidden="true"></i>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		</a>

	<!-- Quick Action Cards -->
	<div class="row g-2 mb-4 mt-3">
		<div class="col-12 col-lg-6">
			<a href="{{ route('pengguna.kalkulator') }}" class="card dashboard-card-modern action-shell d-block text-decoration-none" style="min-height: 160px; overflow: hidden;">
				<div class="card-body d-flex flex-column flex-sm-row gap-3 align-items-start align-sm-items-center" style="padding: 16px;">
					<div class="action-illustration buku">
						<img src="{{ asset('foto/kalkulator.jpg') }}" alt="Kalkulator Gizi" style="width:100%; height:100%; object-fit:cover; border-radius:12px;">
					</div>
					<div class="flex-grow-1">
						<div class="action-badge mb-2">KALKULATOR GIZI</div>
						<h5 class="fw-bold mb-2" style="letter-spacing: .02em;">KALKULATOR GIZI</h5>
						<p class="text-muted mb-0" style="font-size: 0.95rem;">Hitung kebutuhan dan status gizi ibu hamil atau anak dalam tampilan yang simpel, cepat, dan enak dipakai.</p>
					</div>
				</div>
			</a>
		</div>
		<div class="col-12 col-lg-6">
			<a href="{{ route('pengguna.buku_kia') }}" class="card dashboard-card-modern action-shell d-block text-decoration-none" style="min-height: 160px; overflow: hidden;">
				<div class="card-body d-flex flex-column flex-sm-row gap-3 align-items-start align-sm-items-center" style="padding: 16px;">
					<div class="action-illustration buku">
						<img src="{{ asset('foto/BUKU KIA.jpg') }}" alt="Buku KIA" style="width:100%; height:100%; object-fit:cover; border-radius:24px;">
					</div>
					<div class="flex-grow-1">
						<div class="action-badge mb-2">BUKU KIA</div>
						<h5 class="fw-bold mb-2">BUKU KIA</h5>
						<p class="text-muted mb-0" style="font-size: 0.95rem;">Buka panduan Kesehatan Ibu dan Anak untuk akses informasi yang lebih jelas, rapi, dan nyaman dibaca.</p>
					</div>
				</div>
			</a>
		</div>
	</div>

	<!-- Schedule Section -->
	<div class="row g-4">
		<div class="col-12">
			<div class="card border-0 shadow-sm" style="border-radius: 20px;">
				<div class="card-header border-bottom-light bg-white" style="border-bottom: 1px solid #e2e8f0; border-radius: 20px 20px 0 0;">
					<div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
						<h5 class="mb-0 fw-bold">JADWAL</h5>
						@if(count($jadwalList) > 0)
							<span class="badge bg-pink-light text-pink-primary rounded-pill">
								<i class="bi bi-calendar-check"></i> {{ count($jadwalList) }} jadwal
							</span>
						@endif
					</div>
				</div>
				<div class="card-body p-4">
					@php
						$holidays = [];
					@endphp

					<!-- Kalender dan Detail Jadwal -->
					<div class="row">
						<div class="col-12 col-md-5">
							<div class="d-flex align-items-center justify-content-between mb-3">
								<div>
									<button class="btn btn-sm btn-outline-pink" id="calPrev"><i class="bi bi-chevron-left"></i></button>
								</div>
								<div class="text-center">
									<div class="h5 fw-bold mb-0"><span id="displayMonth">April</span>&nbsp;<span id="displayYear">2026</span></div>
								</div>
								<div>
									<button class="btn btn-sm btn-outline-pink" id="calNext"><i class="bi bi-chevron-right"></i></button>
								</div>
							</div>
							<div class="border rounded p-3">
								<div class="d-flex" style="gap:.5rem; font-weight:700;">
									<div class="text-center text-danger" style="width:14.28%;">Min</div>
									<div class="text-center" style="width:14.28%;">Sen</div>
									<div class="text-center" style="width:14.28%;">Sel</div>
									<div class="text-center" style="width:14.28%;">Rab</div>
									<div class="text-center" style="width:14.28%;">Kam</div>
									<div class="text-center" style="width:14.28%;">Jum</div>
									<div class="text-center" style="width:14.28%;">Sab</div>
								</div>
								<div id="calendarGrid" class="mt-2"></div>
							</div>

							<!-- Legend -->
							<div class="mt-3 d-flex gap-3 flex-wrap">
								<div class="d-flex align-items-center gap-2">
									<div style="width: 12px; height: 12px; border-radius: 4px; background: rgba(59, 130, 246, 0.25); border: 2px solid #3b82f6;"></div>
									<span class="small text-muted">Kontrol</span>
								</div>
								<div class="d-flex align-items-center gap-2">
									<div style="width: 12px; height: 12px; border-radius: 4px; background: rgba(34, 197, 94, 0.25); border: 2px solid #22c55e;"></div>
									<span class="small text-muted">Imunisasi</span>
								</div>
							</div>
						</div>
						<div class="col-12 col-md-7">
							<div class="alert alert-light border rounded-3" role="alert" style="background: linear-gradient(180deg, #fdf2f8 0%, #fff 100%);">
								<h6 class="fw-semibold mb-3 text-pink-primary d-flex align-items-center gap-2">
									<i class="bi bi-calendar-event"></i>
									Detail Kegiatan
								</h6>
								<div id="selectedDateLabel" class="fw-bold mb-3" style="color: var(--pengguna-ink);"></div>
								<div id="eventsList" class="text-muted">Klik tanggal di kalender untuk melihat detail jadwal.</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

@push('scripts')
<script>
	// Calendar month view with detail pane
	const holidays = @json($holidays ?? []);
	const monthNames = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
	const dayNames = ['Min','Sen','Sel','Rab','Kam','Jum','Sab'];

	// Jadwal dari database (dari bidan/dokter) - sudah dalam format Y-m-d
	const jadwalList = @json($jadwalList ?? []);

	// Build specialDayEvents dari jadwal database
	const specialDayEvents = {};
	jadwalList.forEach(function(jadwal) {
		const dateKey = jadwal.tanggal; // Sudah format Y-m-d dari PHP
		if (!specialDayEvents[dateKey]) {
			specialDayEvents[dateKey] = [];
		}
		specialDayEvents[dateKey].push({
			kind: jadwal.jenis, // 'kontrol' atau 'imunisasi'
			title: jadwal.judul,
			time: jadwal.waktu || '',
			note: jadwal.catatan || '',
			bidan: jadwal.bidan ? jadwal.bidan.name : (jadwal.dokter ? jadwal.dokter.name : ''),
		});
	});

	const mealSchedule = [
		{ kind: 'meal', title: 'Makan pagi', time: '07:00', note: 'Sarapan bergizi untuk mulai hari dengan baik.' },
		{ kind: 'meal', title: 'Makan siang', time: '12:00', note: 'Makan siang seimbang untuk menjaga energi.' },
		{ kind: 'meal', title: 'Makan malam', time: '18:00', note: 'Makan malam ringan dan cukup agar tetap nyaman.' },
	];

	function padNumber(value) {
		return String(value).padStart(2, '0');
	}

	function toIsoDate(year, month, day) {
		return `${year}-${padNumber(month + 1)}-${padNumber(day)}`;
	}

	function formatDateLabel(dateIso) {
		return new Date(`${dateIso}T00:00:00`).toLocaleDateString('id-ID', {
			weekday: 'long',
			year: 'numeric',
			month: 'long',
			day: 'numeric',
		});
	}

	function getTodayIso() {
		const today = new Date();
		return toIsoDate(today.getFullYear(), today.getMonth(), today.getDate());
	}

	function getSpecialEvents(dateIso) {
		const events = specialDayEvents[dateIso] ? [...specialDayEvents[dateIso]] : [];
		return events.sort((left, right) => {
			const priority = { kontrol: 0, imunisasi: 1, control: 1, immunization: 1, holiday: 2, meal: 3 };
			return (priority[left.kind] ?? 9) - (priority[right.kind] ?? 9);
		});
	}

	let now = new Date();
	let currentYear = now.getFullYear();
	let currentMonth = now.getMonth(); // 0-based
	let selectedDateIso = getTodayIso();

	function getDateMeta(dateIso) {
		const specialEvents = getSpecialEvents(dateIso);
		return {
			isHoliday: Boolean(holidays && holidays[dateIso]),
			holidayNote: holidays[dateIso] || '',
			hasControl: specialEvents.some((event) => event.kind === 'kontrol'),
			hasImmunization: specialEvents.some((event) => event.kind === 'imunisasi'),
			specialEvents,
		};
	}

	function renderCalendar(year, month) {
		const grid = document.getElementById('calendarGrid');
		grid.innerHTML = '';
		document.getElementById('displayMonth').textContent = monthNames[month];
		document.getElementById('displayYear').textContent = year;

		const firstDay = new Date(year, month, 1);
		const startDay = firstDay.getDay(); // 0-6 (Sun-Sat)
		const daysInMonth = new Date(year, month + 1, 0).getDate();
		const todayIso = getTodayIso();

		// Create 6 rows of 7 days
		let day = 1 - startDay;
		for (let week = 0; week < 6; week++) {
			const row = document.createElement('div');
			row.className = 'd-flex';
			for (let dow = 0; dow < 7; dow++, day++) {
				const cell = document.createElement('div');
				cell.style.width = '14.28%';
				cell.style.padding = '6px';
				cell.style.boxSizing = 'border-box';
				cell.className = 'text-center';

				if (day < 1 || day > daysInMonth) {
					cell.innerHTML = '&nbsp;';
				} else {
					const dateObj = new Date(year, month, day);
					const iso = toIsoDate(year, month, day);
					const weekdayIndex = dateObj.getDay();
					const meta = getDateMeta(iso);

					const btn = document.createElement('button');
					btn.type = 'button';
					btn.className = 'btn w-100 p-2 calendar-day-btn';
					btn.dataset.date = iso;

					const isSunday = weekdayIndex === 0;

					let inner = `<div class="small mb-1 ${isSunday ? 'text-danger' : 'text-muted'}">${dayNames[weekdayIndex]}</div>`;
					inner += `<div class="h6 fw-bold mb-0">${day}</div>`;
					// Tampilkan marker untuk setiap jenis jadwal
					if (meta.hasControl) inner += `<div class="calendar-marker control">Kontrol</div>`;
					if (meta.hasImmunization) inner += `<div class="calendar-marker immunization">Imunisasi</div>`;
					if (meta.isHoliday && !meta.hasControl && !meta.hasImmunization) inner += `<div class="calendar-marker holiday">Libur</div>`;

					btn.innerHTML = inner;

					if (isSunday) {
						btn.classList.add('text-danger');
						btn.classList.add('is-weekend');
					}
					if (meta.isHoliday) {
						btn.classList.add('is-holiday');
					}
					if (meta.hasControl) {
						btn.classList.add('is-control');
					}
					if (meta.hasImmunization) {
						btn.classList.add('is-immunization');
					}

					// mark today
					if (iso === todayIso) {
						btn.classList.add('is-today');
					}

					if (selectedDateIso && iso === selectedDateIso) {
						btn.classList.add('is-selected');
						btn.classList.add('active');
					}

					btn.addEventListener('click', function(){
						selectedDateIso = iso;
						renderCalendar(currentYear, currentMonth);
						showDetails(iso);
					});

					if (meta.isHoliday) {
						btn.title = meta.holidayNote;
					} else if (meta.hasControl || meta.hasImmunization) {
						btn.title = meta.specialEvents.map((event) => `${event.title} ${event.time}`).join(' | ');
					}

					cell.appendChild(btn);
				}
				row.appendChild(cell);
			}
			grid.appendChild(row);
		}
	}

	function showDetails(dateIso) {
		const label = formatDateLabel(dateIso);
		document.getElementById('selectedDateLabel').textContent = label;
		const meta = getDateMeta(dateIso);

		const eventsContainer = document.getElementById('eventsList');

		// Gabungkan jadwal dari database (kontrol/imunisasi) + jadwal makan
		const kontrolImunisasi = meta.specialEvents.map((event) => {
			const eventClass = event.kind === 'kontrol' || event.kind === 'control' ? 'control' : 'immunization';
			const badgeText = event.kind === 'kontrol' || event.kind === 'control' ? 'Kontrol' : 'Imunisasi';
			const badgeClass = event.kind === 'kontrol' || event.kind === 'control' ? 'bg-primary-subtle text-primary' : 'bg-success-subtle text-success';
			let eventHtml = `
				<div class="detail-item ${eventClass}">
					<div class="d-flex align-items-start justify-content-between gap-2">
						<div>
							<div class="time">${event.time ? event.time + ' WIB' : 'Waktu belum ditentukan'}</div>
							<div class="fw-bold">${event.title}</div>
						</div>
						<span class="badge rounded-pill ${badgeClass}">${badgeText}</span>
					</div>`;
			if (event.bidan) {
				eventHtml += `<div class="text-muted small mt-2"><i class="bi bi-person"></i> ${event.bidan}</div>`;
			}
			if (event.note) {
				eventHtml += `<div class="text-muted small mt-1">${event.note}</div>`;
			}
			eventHtml += `</div>`;
			return eventHtml;
		});

		// Jadwal makan untuk setiap hari
		const mealEvents = mealSchedule.map((meal) => {
			const badgeClass = 'bg-light text-secondary';
			return `
				<div class="detail-item meal">
					<div class="d-flex align-items-start justify-content-between gap-2">
						<div>
							<div class="time">${meal.time}</div>
							<div class="fw-bold">${meal.title}</div>
						</div>
						<span class="badge rounded-pill ${badgeClass}">Makan</span>
					</div>
					<div class="text-muted small mt-2">${meal.note}</div>
				</div>
			`;
		});

		// Kontrol/imunisasi di atas, kemudian jadwal makan
		if (kontrolImunisasi.length > 0) {
			eventsContainer.innerHTML = kontrolImunisasi.join('') + mealEvents.join('');
		} else {
			eventsContainer.innerHTML = mealEvents.join('');
		}
	}

	document.getElementById('calPrev').addEventListener('click', function(){
		currentMonth--; if (currentMonth < 0) { currentMonth = 11; currentYear--; }
		renderCalendar(currentYear, currentMonth);
	});
	document.getElementById('calNext').addEventListener('click', function(){
		currentMonth++; if (currentMonth > 11) { currentMonth = 0; currentYear++; }
		renderCalendar(currentYear, currentMonth);
	});

	// Initial render
	renderCalendar(currentYear, currentMonth);
	// Show today's details by default
	showDetails(getTodayIso());
</script>
@endpush

	</div>
@endsection
