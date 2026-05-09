@extends('pengguna.master')

@section('title', 'Buku KIA - MomSpire')
@section('header_title')
	Buku KIA
@endsection
@section('header_subtitle', '')
@section('header_action')
	<a href="{{ route('pengguna.dashboard') }}" class="btn btn-back-page">
		<i class="bi bi-arrow-left"></i>
		<span>Kembali</span>
	</a>
@endsection

@push('head')
<script>
	window.__MOMSPIRE_SIDEBAR_OPEN = false;
	window.__MOMSPIRE_HEADER_TITLE_LOCK = 'Buku KIA';
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

	.buku-kia-shell {
		background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
		border: 1px solid rgba(15, 23, 42, .05);
		border-radius: 28px;
		box-shadow: 0 18px 40px rgba(15, 23, 42, .06);
		padding: 28px;
	}

	.kia-hero {
		background: linear-gradient(135deg, #fff1f7 0%, #fef3c7 100%);
		border: 1px solid rgba(236, 72, 153, .2);
		border-radius: 24px;
		padding: 24px;
		margin-bottom: 24px;
		position: relative;
		overflow: hidden;
	}

	.kia-hero::after {
		content: '';
		position: absolute;
		inset: auto -100px -110px auto;
		width: 220px;
		height: 220px;
		border-radius: 50%;
		background: rgba(244, 114, 182, .12);
		pointer-events: none;
	}

	.kia-hero h2 {
		font-size: 30px;
		font-weight: 800;
		margin-bottom: 8px;
		color: #be185d;
	}

	.kia-hero p {
		margin-bottom: 0;
		color: #334155;
	}

	.total-chip {
		display: inline-flex;
		align-items: center;
		gap: 8px;
		padding: 10px 14px;
		border-radius: 999px;
		background: #fff;
		border: 1px solid rgba(236, 72, 153, .25);
		font-size: 13px;
		font-weight: 700;
		color: #be185d;
	}

	.kia-card {
		background: linear-gradient(180deg, #fff 0%, #fff8fb 100%);
		border: 1px solid rgba(236, 72, 153, .2);
		border-radius: 22px;
		height: 100%;
		padding: 18px;
		box-shadow: 0 14px 28px rgba(244, 114, 182, .12);
	}

	.kia-cover {
		border-radius: 16px;
		overflow: hidden;
		height: 156px;
		border: 1px solid rgba(15, 23, 42, .08);
		margin-bottom: 14px;
	}

	.kia-cover img {
		width: 100%;
		height: 100%;
		object-fit: cover;
	}

	.kia-label {
		font-size: 1.05rem;
		font-weight: 800;
		color: #0f172a;
		margin-bottom: 8px;
	}

	.kia-meta {
		font-size: .92rem;
		color: #475569;
		margin-bottom: 0;
	}

	.kia-status-badge {
		display: inline-flex;
		align-items: center;
		gap: 6px;
		padding: 7px 12px;
		border-radius: 999px;
		font-size: .75rem;
		font-weight: 700;
		letter-spacing: .03em;
		text-transform: uppercase;
		background: #fff;
		border: 1px solid rgba(15, 23, 42, .12);
		color: #334155;
		margin-bottom: 12px;
	}

	.kia-actions {
		margin-top: 12px;
	}

	.btn-outline-pink {
		background: transparent;
		border: 1.5px solid #be185d;
		color: #be185d;
		font-size: .85rem;
		font-weight: 700;
		padding: 8px 12px;
		border-radius: 10px;
		transition: all .2s ease;
		text-decoration: none;
		display: inline-flex;
		align-items: center;
		justify-content: center;
	}

	.btn-outline-pink:hover {
		background: rgba(190, 24, 93, .08);
		border-color: #c41e5c;
		color: #c41e5c;
	}

	.btn-pink {
		background: linear-gradient(135deg, #be185d 0%, #e63980 100%);
		border: 1.5px solid #be185d;
		color: #fff;
		font-size: .85rem;
		font-weight: 700;
		padding: 8px 12px;
		border-radius: 10px;
		transition: all .2s ease;
		text-decoration: none;
		display: inline-flex;
		align-items: center;
		justify-content: center;
	}

	.btn-pink:hover {
		transform: translateY(-1px);
		box-shadow: 0 6px 16px rgba(230, 57, 128, .3);
		color: #fff;
	}

	.kia-empty {
		background: #fff;
		border: 1px dashed rgba(236, 72, 153, .35);
		border-radius: 20px;
		padding: 24px;
		text-align: center;
		color: #475569;
	}
</style>
@endpush

@section('content')
	<div class="buku-kia-shell">
		<div class="kia-hero">
			<div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
				<div>
					<h2>Buku KIA Anda</h2>
					<p>Setiap kehamilan atau anak memiliki 1 Buku KIA agar catatan tumbuh kembang dan kesehatan tetap rapi.</p>
				</div>
				<div class="total-chip">
					<i class="bi bi-journal-bookmark-fill"></i>
					<span>{{ $totalBukuKia }} Buku KIA</span>
				</div>
			</div>
		</div>

		@if ($totalBukuKia > 0)
			<div class="row g-4">
				@foreach ($bukuKiaCards as $card)
					<div class="col-12 col-md-6 col-xl-4">
						<div class="kia-card">
							<div class="kia-cover">
								<img src="{{ asset('foto/BUKU KIA.jpg') }}" alt="Buku KIA {{ $card['label'] }}">
							</div>

							<div class="kia-status-badge">
								<i class="bi bi-heart-pulse-fill"></i>
								<span>{{ $card['status'] }}</span>
							</div>

							<h3 class="kia-label">{{ $card['label'] }}</h3>

							<p class="kia-meta">
								@if (!empty($card['nama_anak']))
									Nama anak: <strong>{{ $card['nama_anak'] }}</strong>
								@else
									Catatan Buku KIA untuk {{ strtolower($card['label']) }}.
								@endif
							</p>

							<div class="kia-actions mt-3 d-flex gap-2">
								<a href="{{ route('pengguna.kia.pdf') }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-pink flex-grow-1">
									<i class="bi bi-box-arrow-up-right me-1"></i>Buka
								</a>
								<a href="{{ route('pengguna.kia.download') }}" class="btn btn-sm btn-pink flex-grow-1">
									<i class="bi bi-download me-1"></i>Download
								</a>
							</div>
						</div>
					</div>
				@endforeach
			</div>
		@else
			<div class="kia-empty">
				<h5 class="mb-2">Belum ada Buku KIA</h5>
				<p class="mb-0">Data kehamilan atau data anak belum tersedia. Saat status hamil atau data anak terisi, Buku KIA akan muncul otomatis di halaman ini.</p>
			</div>
		@endif
	</div>
@endsection
