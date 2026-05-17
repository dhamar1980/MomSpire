@extends('bidan.master')

@section('title', 'Kelola Buku KIA - MomSpire')
@section('header_title', 'Buku KIA')
@section('header_subtitle', 'Kelola dan tambahkan Buku KIA menggunakan template yang tersedia.')

@push('head')
<style>
    .template-card {
        border: 1px solid rgba(15, 23, 42, 0.08);
        border-radius: 18px;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .template-card:hover {
        border-color: rgba(214, 51, 108, 0.3);
        box-shadow: 0 10px 30px rgba(214, 51, 108, 0.08);
    }

    .buku-list-item {
        border-left: 3px solid rgba(214, 51, 108, 0.2);
        transition: all 0.3s ease;
    }

    .buku-list-item:hover {
        border-left-color: rgba(214, 51, 108, 0.8);
        background-color: rgba(214, 51, 108, 0.02);
    }
</style>
@endpush

@section('content')
<div class="p-4 p-lg-5">
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

    <!-- Section: Tambah Buku KIA Baru -->
    <div class="row g-4 mb-5">
        <div class="col-12">
            <div style="border: 1px solid rgba(15, 23, 42, 0.08); border-radius: 18px; background: #fff; box-shadow: 0 10px 30px rgba(15, 23, 42, 0.04); padding: 2rem;">
                <h4 class="mb-4">Tambah Buku KIA Baru</h4>

                @if($templates->count() > 0)
                    <div class="row g-3 mb-4">
                        @foreach($templates as $template)
                            <div class="col-md-4 col-lg-3">
                                <div class="template-card p-4 text-center h-100" data-template-id="{{ $template->id }}" data-template-name="{{ $template->nama }}" data-template-file="{{ $template->file_path }}" onclick="selectTemplate(this)">
                                    <i class="bi bi-file-pdf" style="font-size: 2.5rem; color: #e74c3c; margin-bottom: 1rem;"></i>
                                    <h6 class="fw-semibold">{{ $template->nama ?? 'Template' }}</h6>
                                    @if($template->deskripsi)
                                        <p class="text-muted small mb-0">{{ Str::limit($template->deskripsi, 60) }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Form: Tambah Buku KIA -->
                    <form id="tambahBukuForm" method="POST" action="{{ route('bidan.bukuKIA.store') }}" class="d-none">
                        @csrf
                        <input type="hidden" id="templateId" name="template_id">
                        <input type="hidden" id="templateFilePath" name="template_file_path">
                        
                        <div class="row g-3 mb-3">
                            <div class="col-md-8">
                                <label class="form-label">Judul Buku KIA</label>
                                <input type="text" class="form-control" id="bukuJudul" name="judul" placeholder="Contoh: BUKU KIA ANAK PERTAMA" required>
                                <small class="text-muted">Berikan judul yang jelas untuk membedakan setiap buku KIA</small>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Pengguna</label>
                                <select class="form-select" id="bukuPengguna" name="pengguna_id" required>
                                    <option value="">Pilih Pengguna</option>
                                    @foreach(\App\Models\Pengguna::orderBy('name')->get() as $pengguna)
                                        <option value="{{ $pengguna->id }}">{{ $pengguna->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary-custom">Simpan Buku KIA</button>
                            <button type="button" class="btn btn-outline-secondary" onclick="cancelSelect()">Batal</button>
                        </div>
                    </form>

                    <div id="noTemplateSelected" class="alert alert-light border mb-0">
                        <i class="bi bi-info-circle me-2"></i> Pilih salah satu template di atas untuk membuat Buku KIA baru
                    </div>
                @else
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i> Belum ada template Buku KIA yang tersedia. Hubungi admin untuk mengunggah template.
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Section: Daftar Buku KIA -->
    <div class="row g-4">
        <div class="col-12">
            <div style="border: 1px solid rgba(15, 23, 42, 0.08); border-radius: 18px; background: #fff; box-shadow: 0 10px 30px rgba(15, 23, 42, 0.04); padding: 2rem;">
                <h4 class="mb-4">Buku KIA yang Sudah Dibuat</h4>

                @if($bukuKIA->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($bukuKIA as $buku)
                            <div class="buku-list-item p-3 border-bottom">
                                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                                    <div>
                                        <h6 class="fw-semibold mb-2">{{ $buku->judul ?? 'Tanpa Judul' }}</h6>
                                        <div class="text-muted small mb-2">
                                            <i class="bi bi-person me-1"></i> {{ $buku->pengguna->name ?? 'N/A' }} •
                                            <i class="bi bi-calendar me-1"></i> {{ $buku->created_at->format('d M Y') }}
                                        </div>
                                        @if($buku->catatan)
                                            <p class="text-muted small mb-0">{{ Str::limit($buku->catatan, 150) }}</p>
                                        @endif
                                    </div>
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editBukuModal" onclick="editBuku({{ $buku->id }}, '{{ addslashes($buku->judul) }}', '{{ addslashes($buku->catatan) }}')">Edit</button>
                                        <form action="{{ route('bidan.bukuKIA.delete', $buku->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Yakin hapus?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-light border mb-0">
                        <i class="bi bi-folder2-open me-2"></i> Belum ada Buku KIA yang dibuat. Mulai dengan membuat Buku KIA baru menggunakan template di atas.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal: Edit Buku KIA -->
<div class="modal fade" id="editBukuModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Buku KIA</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editBukuForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Judul</label>
                        <input type="text" class="form-control" id="editJudul" name="judul" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Catatan</label>
                        <textarea class="form-control" id="editCatatan" name="catatan" rows="4"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary-custom">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function selectTemplate(element) {
        document.querySelectorAll('.template-card').forEach(card => {
            card.style.borderColor = 'rgba(15, 23, 42, 0.08)';
            card.style.boxShadow = 'none';
        });
        
        element.style.borderColor = 'rgba(214, 51, 108, 0.6)';
        element.style.boxShadow = '0 10px 30px rgba(214, 51, 108, 0.15)';
        
        document.getElementById('templateId').value = element.dataset.templateId;
        document.getElementById('templateFilePath').value = element.dataset.templateFile;
        document.getElementById('tambahBukuForm').classList.remove('d-none');
        document.getElementById('noTemplateSelected').classList.add('d-none');
    }

    function cancelSelect() {
        document.querySelectorAll('.template-card').forEach(card => {
            card.style.borderColor = 'rgba(15, 23, 42, 0.08)';
            card.style.boxShadow = 'none';
        });
        
        document.getElementById('tambahBukuForm').classList.add('d-none');
        document.getElementById('noTemplateSelected').classList.remove('d-none');
        document.getElementById('bukuJudul').value = '';
        document.getElementById('bukuPengguna').value = '';
    }

    function editBuku(id, judul, catatan) {
        document.getElementById('editBukuForm').action = '{{ route("bidan.bukuKIA.update", ":id") }}'.replace(':id', id);
        document.getElementById('editJudul').value = judul;
        document.getElementById('editCatatan').value = catatan;
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Close sidebar on page load
        window.__momspireSidebarOpen = false;
        window.__momspireSyncSidebar();
        
        // Replace toggle button with back button
        const toggleBtn = document.getElementById('toggleSidebar');
        if (toggleBtn) {
            toggleBtn.innerHTML = '<i class="bi bi-arrow-left"></i>';
            toggleBtn.title = 'Kembali';
            toggleBtn.onclick = function(e) {
                e.preventDefault();
                window.history.back();
                return false;
            };
        }
    });
@endpush

@endsection
