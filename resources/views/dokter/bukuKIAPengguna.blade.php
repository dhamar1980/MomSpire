@extends('dokter.master')

@section('title', 'Buku KIA ' . ($pengguna->name ?? 'Pengguna') . ' - MomSpire')
@section('header_title', 'Kelola Buku KIA')
@section('header_subtitle', 'Kelola catatan dan template Buku KIA untuk pengguna.')
@section('header_action')
	<a href="{{ route('dokter.pengguna') }}" class="btn btn-back-page">
		<i class="bi bi-arrow-left"></i>
		<span>Kembali</span>
	</a>
@endsection

@push('head')
<style>
    .buku-pengguna-page {
        background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
        min-height: 100vh;
    }

    .bp-hero {
        background: rgba(255, 255, 255, 0.82);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.9);
        border-radius: 20px;
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.04);
        position: relative;
        overflow: hidden;
        padding: 1.75rem 2rem;
    }

    .bp-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, #d6336c 0%, #6f42c1 50%, #00b894 100%);
    }

    .bp-user-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.4rem 1rem;
        border-radius: 50px;
        background: linear-gradient(135deg, rgba(214, 51, 108, 0.1) 0%, rgba(107, 66, 193, 0.08) 100%);
        border: 1px solid rgba(214, 51, 108, 0.15);
        color: #d6336c;
        font-weight: 600;
        font-size: 0.85rem;
        margin-bottom: 0.75rem;
    }

    .bp-heading {
        font-size: 1.75rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.35rem;
    }

    .bp-subheading {
        color: rgba(100, 116, 139, 0.85);
        font-size: 0.95rem;
    }

    .bp-card {
        background: rgba(255, 255, 255, 0.82);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.9);
        border-radius: 20px;
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.04);
        overflow: hidden;
    }

    .bp-card-header {
        padding: 1.25rem 1.75rem;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        background: rgba(255, 255, 255, 0.5);
    }

    .bp-card-title {
        font-size: 1.05rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .bp-card-title i {
        color: #d6336c;
    }

    .bp-card-body {
        padding: 1.5rem 1.75rem;
    }

    /* Tab Navigation */
    .bp-tabs {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
        border-bottom: 2px solid rgba(0, 0, 0, 0.06);
        padding-bottom: 0;
    }

    .bp-tab-btn {
        padding: 0.75rem 1.25rem;
        border: none;
        background: transparent;
        font-weight: 600;
        font-size: 0.9rem;
        color: #64748b;
        cursor: pointer;
        border-bottom: 2px solid transparent;
        margin-bottom: -2px;
        transition: all 0.2s ease;
        border-radius: 8px 8px 0 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .bp-tab-btn:hover {
        color: #d6336c;
        background: rgba(214, 51, 108, 0.05);
    }

    .bp-tab-btn.active {
        color: #d6336c;
        border-bottom-color: #d6336c;
        background: rgba(214, 51, 108, 0.05);
    }

    .bp-tab-content {
        display: none;
    }

    .bp-tab-content.active {
        display: block;
    }

    /* Buku KIA Entry Item */
    .bp-entry-item {
        border: 1px solid rgba(0, 0, 0, 0.07);
        border-left: 3px solid #d6336c;
        border-radius: 14px;
        padding: 1.25rem;
        background: rgba(255, 255, 255, 0.7);
        transition: all 0.25s ease;
        margin-bottom: 1rem;
    }

    .bp-entry-item:hover {
        border-color: rgba(214, 51, 108, 0.25);
        box-shadow: 0 8px 24px rgba(214, 51, 108, 0.08);
        transform: translateY(-2px);
        background: rgba(255, 255, 255, 0.9);
    }

    .bp-entry-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 1rem;
        margin-bottom: 0.75rem;
    }

    .bp-entry-title {
        font-size: 1rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
    }

    .bp-entry-meta {
        font-size: 0.82rem;
        color: #64748b;
    }

    .bp-entry-catatan {
        font-size: 0.88rem;
        color: #475569;
        margin-bottom: 1rem;
        line-height: 1.6;
    }

    .bp-entry-actions {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    /* Template Card */
    .bp-template-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .bp-template-card {
        border: 1.5px solid rgba(0, 0, 0, 0.08);
        border-radius: 14px;
        padding: 1.25rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.25s ease;
        background: rgba(255, 255, 255, 0.7);
    }

    .bp-template-card:hover {
        border-color: rgba(214, 51, 108, 0.4);
        box-shadow: 0 8px 24px rgba(214, 51, 108, 0.1);
        transform: translateY(-2px);
    }

    .bp-template-card.selected {
        border-color: #d6336c;
        background: rgba(214, 51, 108, 0.04);
        box-shadow: 0 8px 24px rgba(214, 51, 108, 0.15);
    }

    .bp-template-icon {
        font-size: 2.5rem;
        color: #e63980;
        margin-bottom: 0.75rem;
    }

    .bp-template-name {
        font-size: 0.9rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.35rem;
    }

    .bp-template-desc {
        font-size: 0.78rem;
        color: #64748b;
    }

    
    /* Form Styles */
    .bp-form-card {
        border: 1px solid rgba(0, 0, 0, 0.08);
        border-radius: 16px;
        padding: 1.5rem;
        background: rgba(255, 255, 255, 0.6);
        margin-top: 1.5rem;
    }

    .bp-form-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* Buttons */
    .btn-bp-primary {
        background: linear-gradient(135deg, #d6336c 0%, #e63980 100%);
        border: none;
        color: white;
        font-weight: 600;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        transition: all 0.25s ease;
        box-shadow: 0 4px 14px rgba(214, 51, 108, 0.25);
    }

    .btn-bp-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(214, 51, 108, 0.35);
        color: white;
    }

    .btn-bp-outline {
        background: transparent;
        border: 1.5px solid rgba(214, 51, 108, 0.3);
        color: #d6336c;
        font-weight: 600;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        transition: all 0.25s ease;
    }

    .btn-bp-outline:hover {
        background: rgba(214, 51, 108, 0.06);
        border-color: #d6336c;
        color: #b52a5a;
        transform: translateY(-1px);
    }

    .btn-bp-danger {
        background: transparent;
        border: 1.5px solid rgba(239, 68, 68, 0.3);
        color: #dc2626;
        font-weight: 600;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        transition: all 0.25s ease;
    }

    .btn-bp-danger:hover {
        background: rgba(239, 68, 68, 0.06);
        border-color: #dc2626;
        color: #b91c1c;
    }

    /* Empty State */
    .bp-empty {
        text-align: center;
        padding: 2.5rem 1rem;
        color: #64748b;
    }

    .bp-empty-icon {
        font-size: 3rem;
        color: #d6336c;
        opacity: 0.5;
        margin-bottom: 1rem;
    }

    .bp-empty-title {
        font-size: 1rem;
        font-weight: 700;
        color: #475569;
        margin-bottom: 0.35rem;
    }

    .bp-empty-text {
        font-size: 0.88rem;
        color: #94a3b8;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .bp-tabs {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .bp-template-grid {
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        }

        .bp-article-grid {
            grid-template-columns: 1fr;
        }

        .bp-entry-header {
            flex-direction: column;
        }
    }
</style>
@endpush

@section('content')
<div class="buku-pengguna-page p-4 p-lg-5">
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

    <!-- Hero Header -->
    <div class="bp-hero mb-4">
        <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
            <div>
                <div class="bp-user-badge">
                    <i class="bi bi-person-heart"></i>
                    {{ $pengguna->name ?? 'Pengguna' }}
                </div>
                <h1 class="bp-heading">Buku KIA</h1>
                <p class="bp-subheading mb-0">Kelola catatan Buku KIA untuk {{ $pengguna->name }}.</p>
            </div>
        </div>
    </div>

    <!-- Main Card with Tabs -->
    <div class="bp-card">
        <div class="bp-card-header">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h5 class="bp-card-title mb-0">
                    <i class="bi bi-book-half"></i>
                    Manajemen Buku KIA
                </h5>
            </div>
        </div>
        <div class="bp-card-body">

            <!-- Tabs -->
            <div class="bp-tabs">
                <button type="button" class="bp-tab-btn active" onclick="showTab('buku-kia')">
                    <i class="bi bi-journal-bookmark-fill"></i>
                    Buku KIA
                    <span class="badge bg-danger rounded-pill ms-1">{{ $entries->count() }}</span>
                </button>
                <button type="button" class="bp-tab-btn" onclick="showTab('tambah-buku')">
                    <i class="bi bi-plus-circle-fill"></i>
                    Tambah Buku KIA
                </button>
            </div>

            <!-- Tab: Daftar Buku KIA -->
            <div id="tab-buku-kia" class="bp-tab-content active">
                @if($entries->count() > 0)
                    @foreach($entries as $entry)
                        <div class="bp-entry-item">
                            <div class="bp-entry-header">
                                <div>
                                    <h5 class="bp-entry-title">{{ $entry->judul ?? 'Tanpa Judul' }}</h5>
                                    <div class="bp-entry-meta">
                                        <i class="bi bi-calendar3 me-1"></i>{{ $entry->created_at->format('d M Y, H:i') }}
                                    </div>
                                </div>
                            </div>
                            @if($entry->catatan)
                                <p class="bp-entry-catatan">{{ Str::limit($entry->catatan, 200) }}</p>
                            @endif
                            <div class="bp-entry-actions">
                                <a href="{{ $entry->file_path ? asset('storage/' . $entry->file_path) : '#' }}"
                                   class="btn btn-bp-primary btn-sm"
                                   target="_blank"
                                   {{ !$entry->file_path ? 'onclick="return false;" style="opacity:0.5;cursor:not-allowed;"' : '' }}>
                                    <i class="bi bi-eye-fill me-1"></i>Lihat
                                </a>
                                <form action="{{ route('dokter.pengguna.bukuKIA.destroy', $entry->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus catatan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-bp-danger btn-sm">
                                        <i class="bi bi-trash-fill me-1"></i>Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="bp-empty">
                        <div class="bp-empty-icon">
                            <i class="bi bi-book"></i>
                        </div>
                        <h5 class="bp-empty-title">Belum Ada Catatan Buku KIA</h5>
                        <p class="bp-empty-text">Tambahkan catatan Buku KIA untuk {{ $pengguna->name }} menggunakan tab "Tambah Buku KIA".</p>
                    </div>
                @endif
            </div>

            <!-- Tab: Tambah Buku KIA -->
            <div id="tab-tambah-buku" class="bp-tab-content">
                @if($templates->count() > 0)
                    <p class="text-muted mb-3" style="font-size: 0.9rem;">
                        <i class="bi bi-info-circle me-1"></i>
                        Pilih template di bawah, lalu isi judul dan catatan untuk menambahkan Buku KIA.
                    </p>
                    <div class="bp-template-grid">
                        @foreach($templates as $template)
                            <div class="bp-template-card"
                                 data-template-id="{{ $template->id }}"
                                 data-template-name="{{ $template->nama }}"
                                 onclick="selectBukuTemplate(this)">
                                <div class="bp-template-icon">
                                    <i class="bi bi-file-earmark-pdf-fill"></i>
                                </div>
                                <div class="bp-template-name">{{ $template->nama ?? 'Template' }}</div>
                                @if($template->deskripsi)
                                    <div class="bp-template-desc">{{ Str::limit($template->deskripsi, 50) }}</div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bp-empty">
                        <div class="bp-empty-icon"><i class="bi bi-folder2-open"></i></div>
                        <h5 class="bp-empty-title">Belum Ada Template</h5>
                        <p class="bp-empty-text">Template Buku KIA belum tersedia. Hubungi admin untuk mengunggah template.</p>
                    </div>
                @endif

                <!-- Form Tambah Buku KIA -->
                <div class="bp-form-card">
                    <h6 class="bp-form-title">
                        <i class="bi bi-pencil-square text-danger"></i>
                        Form Tambah Catatan
                    </h6>
                    <form method="POST" action="{{ route('dokter.pengguna.bukuKIA.store', $pengguna->id) }}">
                        @csrf
                        <input type="hidden" id="bukuTemplateId" name="template_id">

                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="font-size: 0.88rem; color: #475569;">Judul Buku KIA</label>
                            <input type="text" class="form-control" name="judul" placeholder="Contoh: Catatan Kehamilan Minggu ke-12" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="font-size: 0.88rem; color: #475569;">Catatan</label>
                            <textarea class="form-control" name="catatan" rows="4" placeholder="Tulis catatan kesehatan, perkembangan, atau informasi penting..."></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="font-size: 0.88rem; color: #475569;">Upload File (opsional)</label>
                            <input type="file" class="form-control" name="file" accept=".pdf,.jpg,.jpeg,.png">
                            <small class="text-muted">Format: PDF, JPG, PNG. Maks 5MB.</small>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-bp-primary">
                                <i class="bi bi-check-circle-fill me-1"></i>Simpan Catatan
                            </button>
                            <button type="button" class="btn btn-bp-outline" onclick="showTab('buku-kia')">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function showTab(tabName) {
        var allTabs = document.querySelectorAll('.bp-tab-content');
        var allBtns = document.querySelectorAll('.bp-tab-btn');

        allTabs.forEach(function(tab) { tab.classList.remove('active'); });
        allBtns.forEach(function(btn) { btn.classList.remove('active'); });

        var targetTab = document.getElementById('tab-' + tabName);
        if (targetTab) { targetTab.classList.add('active'); }

        var tabs = ['buku-kia', 'tambah-buku'];
        var idx = tabs.indexOf(tabName);
        if (idx !== -1 && allBtns[idx]) { allBtns[idx].classList.add('active'); }
    }

    function selectBukuTemplate(element) {
        document.querySelectorAll('.bp-template-card').forEach(function(card) {
            card.classList.remove('selected');
        });
        element.classList.add('selected');
        document.getElementById('bukuTemplateId').value = element.dataset.templateId;
    }

    document.addEventListener('DOMContentLoaded', function() {
        window.__momspireSidebarOpen = false;
        window.__momspireSyncSidebar();
    });
</script>
@endpush