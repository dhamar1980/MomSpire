@extends('bidan.master')

@section('title', 'Buku KIA - MomSpire')
@section('header_title', 'Buku KIA')
@section('header_subtitle', 'Kelola Buku KIA untuk pengguna')

@section('content')
<div class="p-4 p-lg-5">
	<div class="d-flex justify-content-between align-items-center mb-4">
		<div>
			<h3 class="mb-0">Buku KIA untuk {{ $pengguna->name ?? 'Pengguna' }}</h3>
			<p class="text-muted mb-0">Email: {{ $pengguna->email ?? '-' }} • Anak: {{ $pengguna->anak_count ?? 0 }}</p>
		</div>
		<div>
			<a href="{{ route('bidan.pengguna') }}" class="btn btn-outline-secondary">Kembali ke Daftar Pengguna</a>
		</div>
	</div>

	@if(session('success'))
		<div class="alert alert-success">{{ session('success') }}</div>
	@endif

	<div class="row g-4">
		<div class="col-lg-6">
			<div class="card p-4">
				<h5 class="mb-3">Tambah Catatan / Unggah</h5>
				<form method="POST" action="{{ route('bidan.pengguna.bukuKIA.store', $pengguna->id) }}" enctype="multipart/form-data">
					@csrf
					<div class="mb-3">
						<label class="form-label">Judul</label>
						<input type="text" name="judul" class="form-control" value="" placeholder="Judul singkat">
					</div>
					<div class="mb-3">
						<label class="form-label">Catatan</label>
						<textarea name="catatan" class="form-control" rows="4"></textarea>
					</div>
					<div class="mb-3">
						<label class="form-label">Unggah File (PDF / JPG / PNG)</label>
						<input type="file" name="file" class="form-control">
					</div>
					<div class="d-flex gap-2">
						<button class="btn btn-primary" type="submit">Simpan</button>
						<a href="{{ route('bidan.pengguna') }}" class="btn btn-outline-secondary">Batal</a>
					</div>
				</form>
			</div>
		</div>

		<div class="col-lg-6">
			<div class="card p-4">
				<h5 class="mb-3">Riwayat Buku KIA</h5>
				@if(isset($entries) && $entries->count())
					<div class="list-group list-group-flush">
						@foreach($entries as $entry)
							<div class="list-group-item">
								<div class="d-flex justify-content-between align-items-start">
									<div>
										<div class="fw-semibold">{{ $entry->judul ?? 'Tanpa Judul' }}</div>
										<div class="text-muted small">{{ $entry->created_at->format('d M Y H:i') }} oleh {{ $entry->creator?->name ?? '—' }}</div>
										@if($entry->catatan)
											<div class="mt-2">{{ Str::limit($entry->catatan, 150) }}</div>
										@endif
									</div>
									<div class="text-end">
										@if($entry->file_path)
											<a href="{{ asset('storage/' . $entry->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">Buka File</a>
										@endif
									</div>
								</div>
							</div>
						@endforeach
					</div>
				@else
					<div class="text-muted">Belum ada catatan Buku KIA.</div>
				@endif
			</div>
		</div>
	</div>
</div>
@endsection
