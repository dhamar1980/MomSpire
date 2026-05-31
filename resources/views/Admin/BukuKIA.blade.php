@extends('Admin.master')

@section('title', 'Data Buku KIA - MomSpire')
@section('header_title', 'Data Buku KIA')
@section('header_subtitle', 'Kelola template <strong>Buku KIA</strong> yang dapat dipakai pengguna dan tenaga kesehatan.')

@push('head')
<style>
    .kia-hero {
        background: linear-gradient(135deg, rgba(230,57,128,0.12), rgba(0,184,148,0.12));
        border: 1px solid rgba(230,57,128,0.18);
        border-radius: 18px;
        padding: 1.25rem;
    }
    .kia-actions .btn {
        min-width: 170px;
    }
    .kia-viewer {
        width: 100%;
        min-height: 76vh;
        border: 1px solid var(--border);
        border-radius: 14px;
        background: #fff;
    }
    .kia-template-item {
        border: 1px solid rgba(15, 23, 42, 0.08);
        border-radius: 14px;
        padding: 1rem;
        background: #fff;
    }
</style>
@endpush

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <section class="kia-hero mb-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <h5 class="mb-1"><i class="bi bi-file-earmark-pdf-fill text-danger me-2"></i>{{ $previewFilename ?? 'Buku KIA (Permenkes).pdf' }}</h5>
                <p class="mb-0 text-muted">
                    @if($activeTemplate)
                        Template aktif: {{ $activeTemplate->nama }}. Template terbaru otomatis dipakai oleh user.
                    @else
                        Belum ada template upload, sistem memakai PDF bawaan sebagai referensi.
                    @endif
                </p>
            </div>
            <div class="d-flex flex-wrap gap-2 kia-actions">
                <a class="btn btn-outline-primary" href="{{ route('admin.kia.pdf') }}" target="_blank" rel="noopener noreferrer">
                    <i class="bi bi-box-arrow-up-right me-1"></i>Buka Tab Baru
                </a>
                <a class="btn btn-primary-custom" href="{{ route('admin.kia.download') }}">
                    <i class="bi bi-download me-1"></i>Download PDF
                </a>
            </div>
        </div>
    </section>

    <div class="row g-3 g-lg-4 mb-4">
        <div class="col-12 col-lg-5">
            <div class="admin-card h-100">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-cloud-upload me-2"></i>Upload Template</h5>
                    <small class="text-muted">PDF maksimal 10 MB</small>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.kia.templates.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold" for="templateNama">Nama Template</label>
                            <input type="text" class="form-control" id="templateNama" name="nama" value="{{ old('nama') }}" placeholder="Contoh: Buku KIA 2026" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold" for="templateDeskripsi">Deskripsi</label>
                            <textarea class="form-control" id="templateDeskripsi" name="deskripsi" rows="3" placeholder="Catatan singkat untuk bidan, dokter, dan admin">{{ old('deskripsi') }}</textarea>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold" for="templateFile">File PDF</label>
                            <input type="file" class="form-control" id="templateFile" name="file" accept="application/pdf" required>
                        </div>
                        <button type="submit" class="btn btn-primary-custom w-100">
                            <i class="bi bi-upload me-1"></i>Upload Template
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-7">
            <div class="admin-card h-100">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-files me-2"></i>Template Tersedia</h5>
                    <small class="text-muted">{{ $templates->count() }} template</small>
                </div>
                <div class="card-body">
                    @forelse($templates as $template)
                        <div class="kia-template-item mb-3">
                            <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap">
                                <div>
                                    <div class="fw-bold">{{ $template->nama ?? 'Template Buku KIA' }}</div>
                                    <div class="text-muted small mb-2">
                                        Diunggah {{ $template->created_at?->format('d M Y H:i') ?? '-' }}
                                        @if($template->uploader)
                                            oleh {{ $template->uploader->name }}
                                        @endif
                                    </div>
                                    @if($template->deskripsi)
                                        <p class="text-muted small mb-0">{{ $template->deskripsi }}</p>
                                    @endif
                                </div>
                                <div class="d-flex gap-2 flex-wrap">
                                    <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.kia.templates.pdf', $template) }}" target="_blank" rel="noopener noreferrer">
                                        <i class="bi bi-eye me-1"></i>Lihat
                                    </a>
                                    <form method="POST" action="{{ route('admin.kia.templates.destroy', $template) }}" onsubmit="return confirm('Hapus template Buku KIA ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash me-1"></i>Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-file-earmark-plus d-block mb-2" style="font-size: 2rem;"></i>
                            Belum ada template upload.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="admin-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-book-half me-2"></i>Preview Dokumen Aktif</h5>
            <small class="text-muted">{{ $activeTemplate ? 'Template upload terbaru' : 'PDF bawaan sistem' }}</small>
        </div>
        <div class="card-body">
            @if($previewExists)
                <iframe class="kia-viewer" src="{{ route('admin.kia.pdf') }}" title="Buku KIA"></iframe>
            @else
                <div class="alert alert-warning mb-0">File Buku KIA tidak ditemukan.</div>
            @endif
        </div>
    </div>
@endsection
