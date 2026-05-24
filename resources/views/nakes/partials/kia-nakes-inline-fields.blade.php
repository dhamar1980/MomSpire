@php
    $dataKia = $selectedKia;
@endphp

<div class="category-tabs">
    <button type="button" class="category-tab active" data-nakes-tab="riwayat" onclick="showNakesForm('riwayat')"><i class="bi bi-clipboard2-heart"></i> Riwayat</button>
    <button type="button" class="category-tab" data-nakes-tab="pelayanan" onclick="showNakesForm('pelayanan')"><i class="bi bi-journal-medical"></i> Pelayanan</button>
    <button type="button" class="category-tab" data-nakes-tab="trimester1" onclick="showNakesForm('trimester1')"><i class="bi bi-images"></i> Trimester 1</button>
    <button type="button" class="category-tab" data-nakes-tab="trimester2" onclick="showNakesForm('trimester2')"><i class="bi bi-clipboard-check"></i> Trimester 2</button>
    @if($role === 'dokter')
    <button type="button" class="category-tab" data-nakes-tab="evaluasi" onclick="showNakesForm('evaluasi')"><i class="bi bi-clipboard2-pulse"></i> Evaluasi</button>
    @endif
</div>

<div class="nakes-form-pane active" id="nakes_form_riwayat">
    <div class="form-card">
        <div class="form-card-title">Riwayat Singkat Kesehatan Ibu</div>
        <form action="{{ route($role . '.kia.save_riwayat', $dataKia->id) }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label for="usia_ibu" class="form-label fw-semibold">Usia Ibu</label>
                        <div class="input-group">
                            <input type="number" name="usia_ibu" id="usia_ibu" class="form-control" value="{{ $dataKia->riwayat->usia_ibu ?? '' }}" placeholder="Contoh: 28">
                            <span class="input-group-text">Tahun</span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="kehamilan_ke" class="form-label fw-semibold">Kehamilan ke-</label>
                        <input type="number" name="kehamilan_ke" id="kehamilan_ke" class="form-control" value="{{ $dataKia->riwayat->kehamilan_ke ?? '' }}" placeholder="Contoh: 1">
                    </div>

                    <div class="mb-4">
                        <label for="jumlah_anak_lahir_hidup" class="form-label fw-semibold">Jumlah Anak Lahir Hidup</label>
                        <input type="number" name="jumlah_anak_lahir_hidup" id="jumlah_anak_lahir_hidup" class="form-control" value="{{ $dataKia->riwayat->jumlah_anak_lahir_hidup ?? '' }}" placeholder="Contoh: 0">
                    </div>

                    <div class="mb-4">
                        <label for="riwayat_keguguran" class="form-label fw-semibold">Riwayat Keguguran</label>
                        <input type="number" name="riwayat_keguguran" id="riwayat_keguguran" class="form-control" value="{{ $dataKia->riwayat->riwayat_keguguran ?? '' }}" placeholder="Contoh: 0">
                    </div>

                    <div class="mb-4">
                        <label for="riwayat_penyakit_ibu" class="form-label fw-semibold">Riwayat Penyakit Ibu</label>
                        <textarea name="riwayat_penyakit_ibu" id="riwayat_penyakit_ibu" class="form-control" rows="4" placeholder="Sebutkan riwayat penyakit jika ada (misal: Hipertensi, Diabetes, dll)">{{ $dataKia->riwayat->riwayat_penyakit_ibu ?? '' }}</textarea>
                    </div>

                    <div class="d-flex justify-content-between mt-5">

                        <button type="submit" class="btn btn-success px-5">
                            <i class="bi bi-check2-circle me-2"></i> Simpan Data
                        </button>
                    </div>
                </form>
    </div>
</div>

<div class="nakes-form-pane" id="nakes_form_pelayanan">
    @php $pelayanan = $dataKia->pelayananKesehatanIbu->keyBy('kunjungan_ke'); @endphp
    <div class="form-card">
        <div class="form-card-title">Pencatatan Pelayanan Kesehatan Ibu</div>
        <!-- Tab Navigation for Visits -->
                @php $maxKunjungan = 6; @endphp
                <div class="category-tabs pelayanan-tabs" id="kunjungan-tab">
                    @for ($i = 1; $i <= $maxKunjungan; $i++)
                        @php
                            $tabLabel = '';
                            if ($i == 1) $tabLabel = 'Trimester I (1)';
                            elseif ($i == 2 || $i == 3) $tabLabel = "Trimester II ($i)";
                            else $tabLabel = "Trimester III ($i)";

                            $isActive = ($i === 1);
                        @endphp
                        <button class="category-tab {{ $isActive ? 'active' : '' }}" type="button" data-pelayanan-visit="{{ $i }}" onclick="showPelayananVisit({{ $i }})">{{ $tabLabel }}</button>
                    @endfor
                </div>

                <div id="kunjungan-tabContent">
                    @for ($i = 1; $i <= $maxKunjungan; $i++)
                        @php
                            $isActive = ($i === 1);
                            $pData = $pelayanan->get($i);
                        @endphp
                        <div class="pelayanan-visit-pane {{ $isActive ? 'active' : '' }}" id="pane-kunjungan-{{ $i }}">
                            <form action="{{ route($role . '.kia.save_pelayanan', $dataKia->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="kunjungan_ke" value="{{ $i }}">

                                @php
                                    $tripelArr = explode(',', $pData->tripel_eliminasi ?? ',,');
                                    $valH = $tripelArr[0] ?? '';
                                    $valS = $tripelArr[1] ?? '';
                                    $valHepB = $tripelArr[2] ?? '';
                                @endphp

                                <div class="row g-4">
                                    <div class="col-12 col-md-6">
                                        <div class="card bg-light border-0 shadow-sm rounded-4 h-100">
                                            <div class="card-body p-4">
                                                <h6 class="fw-bold mb-4 border-bottom pb-2 text-success">Tanggal dan Tempat</h6>

                                                <div class="mb-3">
                                                    <label class="form-label small fw-semibold">Tanggal periksa</label>
                                                    <input type="date" name="tanggal_periksa" class="form-control" value="{{ $pData->tanggal_periksa ?? '' }}">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label small fw-semibold">Tempat periksa</label>
                                                    <input type="text" name="tempat_periksa" class="form-control" value="{{ $pData->tempat_periksa ?? '' }}" placeholder="Nama faskes">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <div class="card bg-light border-0 shadow-sm rounded-4 h-100">
                                            <div class="card-body p-4">
                                                <h6 class="fw-bold mb-4 border-bottom pb-2 text-success">Pemeriksaan Fisik</h6>

                                                <div class="row g-3">
                                                    <div class="col-6">
                                                        <label class="form-label small fw-semibold">Berat Badan (kg)</label>
                                                        <input type="number" step="0.1" name="berat_badan" class="form-control" value="{{ $pData->berat_badan ?? '' }}">
                                                    </div>
                                                    @if(in_array($i, [1]))
                                                    <div class="col-6">
                                                        <label class="form-label small fw-semibold">Tinggi Badan (cm)</label>
                                                        <input type="number" step="0.1" name="tinggi_badan" class="form-control" value="{{ $pData->tinggi_badan ?? '' }}">
                                                    </div>
                                                    @endif
                                                    <div class="col-6">
                                                        <label class="form-label small fw-semibold">LiLA (cm)</label>
                                                        <input type="number" step="0.1" name="lingkar_lengan_atas" class="form-control" value="{{ $pData->lingkar_lengan_atas ?? '' }}">
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="form-label small fw-semibold">Tekanan Darah</label>
                                                        <input type="text" name="tekanan_darah" class="form-control" value="{{ $pData->tekanan_darah ?? '' }}" placeholder="120/80">
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="form-label small fw-semibold">Tinggi Rahim (cm)</label>
                                                        <input type="number" step="0.1" name="tinggi_rahim" class="form-control" value="{{ $pData->tinggi_rahim ?? '' }}">
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="form-label small fw-semibold">Letak/Denyut Jantung Bayi</label>
                                                        <input type="text" name="denyut_jantung_bayi" class="form-control" value="{{ $pData->denyut_jantung_bayi ?? '' }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="card bg-light border-0 shadow-sm rounded-4">
                                            <div class="card-body p-4">
                                                <h6 class="fw-bold mb-4 border-bottom pb-2 text-success">Tindakan, Skrining & Laboratorium</h6>

                                                <div class="row g-3">
                                                    <div class="col-12 col-md-4">
                                                        <label class="form-label small fw-semibold">Status/Imunisasi Tetanus</label>
                                                        <input type="text" name="status_imunisasi_tt" class="form-control" value="{{ $pData->status_imunisasi_tt ?? '' }}">
                                                    </div>
                                                    <div class="col-12 col-md-4">
                                                        <label class="form-label small fw-semibold">Konseling</label>
                                                        <input type="text" name="konseling" class="form-control" value="{{ $pData->konseling ?? '' }}">
                                                    </div>
                                                    <div class="col-12 col-md-4">
                                                        <label class="form-label small fw-semibold">Skrining Dokter</label>
                                                        <input type="text" name="skrining_dokter" class="form-control" value="{{ $pData->skrining_dokter ?? '' }}">
                                                    </div>
                                                    <div class="col-12 col-md-4">
                                                        <label class="form-label small fw-semibold">Tablet Tambah Darah (TTD)</label>
                                                        <input type="text" name="tablet_tambah_darah" class="form-control" value="{{ $pData->tablet_tambah_darah ?? '' }}" placeholder="Jumlah">
                                                    </div>
                                                    @if(in_array($i, [1, 4, 5]))
                                                    <div class="col-12 col-md-4">
                                                        <label class="form-label small fw-semibold">Tes Lab Hemoglobin (Hb)</label>
                                                        <input type="text" name="tes_lab_hb" class="form-control" value="{{ $pData->tes_lab_hb ?? '' }}">
                                                    </div>
                                                    @endif
                                                    @if(in_array($i, [1]))
                                                    <div class="col-12 col-md-4">
                                                        <label class="form-label small fw-semibold">Tes Golongan Darah</label>
                                                        <input type="text" name="tes_golongan_darah" class="form-control" value="{{ $pData->tes_golongan_darah ?? '' }}">
                                                    </div>
                                                    @endif
                                                    @if(in_array($i, [2, 3, 4, 5, 6]))
                                                    <div class="col-12 col-md-4">
                                                        <label class="form-label small fw-semibold">Tes Lab Protein Urine</label>
                                                        <input type="text" name="tes_lab_protein_urine" class="form-control" value="{{ $pData->tes_lab_protein_urine ?? '' }}">
                                                    </div>
                                                    @endif
                                                    @if(in_array($i, [4, 5, 6]))
                                                    <div class="col-12 col-md-4">
                                                        <label class="form-label small fw-semibold">Tes Lab Gula Darah</label>
                                                        <input type="text" name="tes_lab_gula_darah" class="form-control" value="{{ $pData->tes_lab_gula_darah ?? '' }}">
                                                    </div>
                                                    @endif
                                                    @if(in_array($i, [1, 5]))
                                                    <div class="col-12 col-md-4">
                                                        <label class="form-label small fw-semibold">USG</label>
                                                        <input type="text" name="usg" class="form-control" value="{{ $pData->usg ?? '' }}">
                                                    </div>
                                                    @endif
                                                    <div class="col-12 col-md-6">
                                                        <label class="form-label small fw-semibold">Tripel Eliminasi</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text" style="font-size: 0.8rem;">H</span>
                                                            <input type="text" name="tripel_eliminasi_h" class="form-control" value="{{ $valH }}">
                                                            <span class="input-group-text" style="font-size: 0.8rem;">S</span>
                                                            <input type="text" name="tripel_eliminasi_s" class="form-control" value="{{ $valS }}">
                                                            <span class="input-group-text" style="font-size: 0.8rem;">Hep B</span>
                                                            <input type="text" name="tripel_eliminasi_hep_b" class="form-control" value="{{ $valHepB }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-md-6">
                                                        <label class="form-label small fw-semibold">Tata Laksana Kasus</label>
                                                        <input type="text" name="tata_laksana_kasus" class="form-control" value="{{ $pData->tata_laksana_kasus ?? '' }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4 text-end">
                                    <button type="submit" class="btn btn-success px-5 py-2 rounded-pill shadow-sm fw-semibold">
                                        <i class="bi bi-save2-fill me-2"></i> Simpan Kunjungan {{ $i }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endfor
                </div>
    </div>
</div>

@if($role === 'dokter')
<div class="nakes-form-pane" id="nakes_form_evaluasi">
    @php
        $evaluasi = $dataKia->evaluasiKesehatanIbu;
        $pemeriksaan = $dataKia->pemeriksaanTrimester1 ?? new \App\Models\KiaPemeriksaanTrimester1();
    @endphp
    <div class="form-card">
        <div class="form-card-title">Evaluasi Kesehatan Ibu Hamil</div>
        <form action="{{ route($role . '.kia.save_evaluasi', $dataKia->id) }}" method="POST">
                        @csrf

                        <!-- Informasi Dasar -->
                        <div class="card bg-light border-0 shadow-sm rounded-4 mb-4">
                            <div class="card-body p-4">
                                <div class="row g-3">
                                    <div class="col-12 col-md-4">
                                        <label class="form-label small fw-semibold">Nama Dokter</label>
                                        <input type="text" name="nama_dokter" class="form-control"
                                            value="{{ $evaluasi->nama_dokter ?? auth()->user()->name }}">
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label class="form-label small fw-semibold">Tanggal Periksa</label>
                                        <input type="date" name="tanggal_periksa" class="form-control"
                                            value="{{ $evaluasi->tanggal_periksa ?? date('Y-m-d') }}">
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label class="form-label small fw-semibold">Fasilitas Kesehatan</label>
                                        <input type="text" name="fasilitas_kesehatan" class="form-control"
                                            value="{{ $evaluasi->fasilitas_kesehatan ?? '' }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-4 mb-4">
                            <!-- Kondisi Kesehatan Ibu & Status Imunisasi -->
                            <div class="col-12 col-md-6">
                                <div class="card bg-light border-0 shadow-sm rounded-4 h-100">
                                    <div class="card-body p-4">
                                    <h6 class="fw-bold mb-4 border-bottom pb-2 text-warning">Kondisi Kesehatan Ibu</h6>
                                    <div class="row g-3 mb-4">
                                        <div class="col-6 col-md-4">
                                            <label class="form-label small fw-semibold">TB (cm)</label>
                                            <input type="number" step="0.1" name="tb" class="form-control" value="{{ $evaluasi->tb ?? '' }}">
                                        </div>
                                        <div class="col-6 col-md-4">
                                            <label class="form-label small fw-semibold">BB (kg)</label>
                                            <input type="number" step="0.1" name="bb" class="form-control" value="{{ $evaluasi->bb ?? '' }}">
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <label class="form-label small fw-semibold">LiLa (cm)</label>
                                            <input type="number" step="0.1" name="lila" class="form-control" value="{{ $evaluasi->lila ?? '' }}">
                                        </div>
                                    </div>

                                    <div class="table-responsive mb-4">
                                        <label class="form-label small fw-semibold d-block mb-2 text-muted">Isian Baris LiLa (4 Kolom IMT)</label>
                                        <table class="table table-bordered table-sm text-center align-middle">
                                            <thead class="table-light">
                                                <tr class="bg-dark text-white">
                                                    <th colspan="4" class="py-1 small">IMT</th>
                                                </tr>
                                                <tr class="small">
                                                    <th style="width: 25%">Kurus</th>
                                                    <th style="width: 25%">Normal</th>
                                                    <th style="width: 25%">Gemuk</th>
                                                    <th style="width: 25%">Obesitas</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="p-1"><input type="number" step="0.1" name="lila_kurus" class="form-control form-control-sm border-0 text-center" value="{{ $evaluasi->lila_kurus ?? '' }}"></td>
                                                    <td class="p-1"><input type="number" step="0.1" name="lila_normal" class="form-control form-control-sm border-0 text-center" value="{{ $evaluasi->lila_normal ?? '' }}"></td>
                                                    <td class="p-1"><input type="number" step="0.1" name="lila_gemuk" class="form-control form-control-sm border-0 text-center" value="{{ $evaluasi->lila_gemuk ?? '' }}"></td>
                                                    <td class="p-1"><input type="number" step="0.1" name="lila_obesitas" class="form-control form-control-sm border-0 text-center" value="{{ $evaluasi->lila_obesitas ?? '' }}"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                        <h6 class="fw-bold mb-3 border-bottom pb-2 text-warning mt-4">Status Imunisasi TD
                                            (Tetanus Diphtheria)</h6>
                                        <div class="table-responsive">
                                            <table class="table table-sm table-borderless">
                                                <thead>
                                                    <tr>
                                                        <th>TT</th>
                                                        <th>Perlindungan</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>1</td>
                                                        <td><input class="form-check-input" type="checkbox"
                                                                name="imunisasi_tt_1" value="1" {{ ($evaluasi->imunisasi_tt_1 ?? false) ? 'checked' : '' }}>
                                                            Awal</td>
                                                    </tr>
                                                    <tr>
                                                        <td>2</td>
                                                        <td><input class="form-check-input" type="checkbox"
                                                                name="imunisasi_tt_2" value="1" {{ ($evaluasi->imunisasi_tt_2 ?? false) ? 'checked' : '' }}> 3
                                                            Tahun</td>
                                                    </tr>
                                                    <tr>
                                                        <td>3</td>
                                                        <td><input class="form-check-input" type="checkbox"
                                                                name="imunisasi_tt_3" value="1" {{ ($evaluasi->imunisasi_tt_3 ?? false) ? 'checked' : '' }}> 5
                                                            Tahun</td>
                                                    </tr>
                                                    <tr>
                                                        <td>4</td>
                                                        <td><input class="form-check-input" type="checkbox"
                                                                name="imunisasi_tt_4" value="1" {{ ($evaluasi->imunisasi_tt_4 ?? false) ? 'checked' : '' }}> 10
                                                            Tahun</td>
                                                    </tr>
                                                    <tr>
                                                        <td>5</td>
                                                        <td><input class="form-check-input" type="checkbox"
                                                                name="imunisasi_tt_5" value="1" {{ ($evaluasi->imunisasi_tt_5 ?? false) ? 'checked' : '' }}> >
                                                            25 Tahun</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Pemeriksaan Khusus -->
                            <div class="col-12 col-md-6">
                                <div class="card bg-light border-0 shadow-sm rounded-4 h-100">
                                    <div class="card-body p-4">
                                        <h6 class="fw-bold mb-4 border-bottom pb-2 text-warning">Pemeriksaan Khusus
                                            (Inspeksi/Inspekulo)</h6>
                                        <div class="row g-3">
                                            <div class="col-6">
                                                <label class="form-label small fw-semibold">Porsio</label>
                                                <select name="inspeksi_porsio" class="form-select">
                                                    <option value="">Pilih...</option>
                                                    <option value="Normal" {{ ($evaluasi->inspeksi_porsio ?? '') == 'Normal' ? 'selected' : '' }}>Normal</option>
                                                    <option value="Tidak normal" {{ ($evaluasi->inspeksi_porsio ?? '') == 'Tidak normal' ? 'selected' : '' }}>Tidak normal</option>
                                                </select>
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label small fw-semibold">Uretra</label>
                                                <select name="inspeksi_uretra" class="form-select">
                                                    <option value="">Pilih...</option>
                                                    <option value="Normal" {{ ($evaluasi->inspeksi_uretra ?? '') == 'Normal' ? 'selected' : '' }}>Normal</option>
                                                    <option value="Tidak normal" {{ ($evaluasi->inspeksi_uretra ?? '') == 'Tidak normal' ? 'selected' : '' }}>Tidak normal</option>
                                                </select>
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label small fw-semibold">Vagina</label>
                                                <select name="inspeksi_vagina" class="form-select">
                                                    <option value="">Pilih...</option>
                                                    <option value="Normal" {{ ($evaluasi->inspeksi_vagina ?? '') == 'Normal' ? 'selected' : '' }}>Normal</option>
                                                    <option value="Tidak normal" {{ ($evaluasi->inspeksi_vagina ?? '') == 'Tidak normal' ? 'selected' : '' }}>Tidak normal</option>
                                                </select>
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label small fw-semibold">Vulva</label>
                                                <select name="inspeksi_vulva" class="form-select">
                                                    <option value="">Pilih...</option>
                                                    <option value="Normal" {{ ($evaluasi->inspeksi_vulva ?? '') == 'Normal' ? 'selected' : '' }}>Normal</option>
                                                    <option value="Tidak normal" {{ ($evaluasi->inspeksi_vulva ?? '') == 'Tidak normal' ? 'selected' : '' }}>Tidak normal</option>
                                                </select>
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label small fw-semibold">Fluksus</label>
                                                <select name="inspeksi_fluksus" class="form-select">
                                                    <option value="">Pilih...</option>
                                                    <option value="+" {{ ($evaluasi->inspeksi_fluksus ?? '') == '+' ? 'selected' : '' }}>+</option>
                                                    <option value="-" {{ ($evaluasi->inspeksi_fluksus ?? '') == '-' ? 'selected' : '' }}>-</option>
                                                </select>
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label small fw-semibold">Fluor</label>
                                                <select name="inspeksi_fluor" class="form-select">
                                                    <option value="">Pilih...</option>
                                                    <option value="+" {{ ($evaluasi->inspeksi_fluor ?? '') == '+' ? 'selected' : '' }}>+</option>
                                                    <option value="-" {{ ($evaluasi->inspeksi_fluor ?? '') == '-' ? 'selected' : '' }}>-</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Riwayat Checklist -->
                            <div class="col-12 col-md-4">
                                <div class="card bg-light border-0 shadow-sm rounded-4 h-100">
                                    <div class="card-body p-4">
                                        <h6 class="fw-bold mb-3 border-bottom pb-2 text-warning">Riwayat Kesehatan Ibu
                                            Sekarang</h6>
                                        @php $rkes = $evaluasi->riwayat_kesehatan_ibu ?? []; @endphp
                                        <div class="row g-2">
                                            @foreach(['Alergi', 'Autoimun', 'Hepatitis B', 'Jantung', 'Sifilis', 'Asma', 'Diabetes', 'Hipertensi', 'Jiwa', 'TB'] as $item)
                                                <div class="col-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="riwayat_kesehatan_ibu[]" value="{{ $item }}" {{ in_array($item, $rkes) ? 'checked' : '' }}>
                                                        <label class="form-check-label small">{{ $item }}</label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="mt-2">
                                            <input type="text" name="riwayat_kesehatan_ibu_lainnya"
                                                class="form-control form-control-sm" placeholder="Lainnya..."
                                                value="{{ $evaluasi->riwayat_kesehatan_ibu_lainnya ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-4">
                                <div class="card bg-light border-0 shadow-sm rounded-4 h-100">
                                    <div class="card-body p-4">
                                        <h6 class="fw-bold mb-3 border-bottom pb-2 text-warning">Riwayat Penyakit Keluarga
                                        </h6>
                                        @php $rkel = $evaluasi->riwayat_penyakit_keluarga ?? []; @endphp
                                        <div class="row g-2">
                                            @foreach(['Alergi', 'Autoimun', 'Hepatitis B', 'Jantung', 'Sifilis', 'Asma', 'Diabetes', 'Hipertensi', 'Jiwa', 'TB'] as $item)
                                                <div class="col-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="riwayat_penyakit_keluarga[]" value="{{ $item }}" {{ in_array($item, $rkel) ? 'checked' : '' }}>
                                                        <label class="form-check-label small">{{ $item }}</label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="mt-2">
                                            <input type="text" name="riwayat_penyakit_keluarga_lainnya"
                                                class="form-control form-control-sm" placeholder="Lainnya..."
                                                value="{{ $evaluasi->riwayat_penyakit_keluarga_lainnya ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-4">
                                <div class="card bg-light border-0 shadow-sm rounded-4 h-100">
                                    <div class="card-body p-4">
                                        <h6 class="fw-bold mb-3 border-bottom pb-2 text-warning">Riwayat Perilaku Berisiko
                                            (1 Bln Sblm Hamil)</h6>
                                        @php $rper = $evaluasi->riwayat_perilaku ?? []; @endphp
                                        <div class="row g-2">
                                            @foreach(['Aktivitas fisik kurang', 'Kosmetik berbahaya', 'Obat Teratogenik', 'Alkohol', 'Merokok', 'Pola makan berisiko'] as $item)
                                                <div class="col-12">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="riwayat_perilaku[]" value="{{ $item }}" {{ in_array($item, $rper) ? 'checked' : '' }}>
                                                        <label class="form-check-label small">{{ $item }}</label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="mt-2">
                                            <input type="text" name="riwayat_perilaku_lainnya"
                                                class="form-control form-control-sm" placeholder="Lainnya..."
                                                value="{{ $evaluasi->riwayat_perilaku_lainnya ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Riwayat Kehamilan -->
                            <div class="col-12">
                                <div class="card bg-light border-0 shadow-sm rounded-4">
                                    <div class="card-body p-4">
                                        <h6 class="fw-bold mb-3 border-bottom pb-2 text-warning">Riwayat Kehamilan dan
                                            Proses Melahirkan</h6>
                                        <div class="table-responsive">
                                            <table class="table table-bordered bg-white">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Tahun</th>
                                                        <th>BB (gram)</th>
                                                        <th>Proses Melahirkan</th>
                                                        <th>Penolong</th>
                                                        <th>Masalah</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php $rk = $evaluasi->riwayat_kehamilan ?? []; @endphp
                                                    @for($i = 0; $i < 3; $i++)
                                                        <tr>
                                                            <td class="text-center align-middle">{{ $i + 1 }}</td>
                                                            <td><input type="text" name="riwayat_kehamilan[{{ $i }}][tahun]"
                                                                    class="form-control form-control-sm"
                                                                    value="{{ $rk[$i]['tahun'] ?? '' }}"></td>
                                                            <td><input type="number" name="riwayat_kehamilan[{{ $i }}][bb]"
                                                                    class="form-control form-control-sm"
                                                                    value="{{ $rk[$i]['bb'] ?? '' }}"></td>
                                                            <td><input type="text" name="riwayat_kehamilan[{{ $i }}][proses]"
                                                                    class="form-control form-control-sm"
                                                                    value="{{ $rk[$i]['proses'] ?? '' }}"></td>
                                                            <td><input type="text" name="riwayat_kehamilan[{{ $i }}][penolong]"
                                                                    class="form-control form-control-sm"
                                                                    value="{{ $rk[$i]['penolong'] ?? '' }}"></td>
                                                            <td><input type="text" name="riwayat_kehamilan[{{ $i }}][masalah]"
                                                                    class="form-control form-control-sm"
                                                                    value="{{ $rk[$i]['masalah'] ?? '' }}"></td>
                                                        </tr>
                                                    @endfor
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Pemeriksaan Dokter Trimester 1 (Kanan) -->
                            <div class="col-12 mt-5">
                                <h5 class="fw-bold text-primary mb-3">Pemeriksaan Dokter Trimester 1 (Sisi Kanan Halaman 51)
                                </h5>
                                <div class="card bg-light border-0 shadow-sm rounded-4 mb-4">
                                    <div class="card-body p-4">
                                        <h6 class="fw-bold mb-3 border-bottom pb-2 text-primary">Keadaan Umum</h6>
                                        <div class="row g-3">
                                            @php
                                                $keadaanFields = [
                                                    ['name' => 'konjungtiva', 'label' => 'Konjungtiva', 'opt1' => 'Anemia', 'opt2' => 'Tidak Anemia'],
                                                    ['name' => 'sklera', 'label' => 'Sklera', 'opt1' => 'Ikterik', 'opt2' => 'Tidak Ikterik'],
                                                    ['name' => 'kulit', 'label' => 'Kulit', 'opt1' => 'Normal', 'opt2' => 'Tidak normal'],
                                                    ['name' => 'leher', 'label' => 'Leher', 'opt1' => 'Normal', 'opt2' => 'Tidak normal'],
                                                    ['name' => 'gigi_mulut', 'label' => 'Gigi Mulut', 'opt1' => 'Normal', 'opt2' => 'Tidak normal'],
                                                    ['name' => 'tht', 'label' => 'THT', 'opt1' => 'Normal', 'opt2' => 'Tidak normal'],
                                                    ['name' => 'dada_jantung', 'label' => 'Dada (Jantung)', 'opt1' => 'Normal', 'opt2' => 'Tidak normal'],
                                                    ['name' => 'dada_paru', 'label' => 'Dada (Paru)', 'opt1' => 'Normal', 'opt2' => 'Tidak normal'],
                                                    ['name' => 'perut', 'label' => 'Perut', 'opt1' => 'Normal', 'opt2' => 'Tidak normal'],
                                                    ['name' => 'tungkai', 'label' => 'Tungkai', 'opt1' => 'Normal', 'opt2' => 'Tidak normal']
                                                ];
                                            @endphp
                                            @foreach($keadaanFields as $kf)
                                                <div class="col-md-6">
                                                    <label class="form-label small fw-semibold">{{ $kf['label'] }}</label>
                                                    <div class="input-group input-group-sm">
                                                        <select class="form-select" style="max-width: 150px;"
                                                            name="{{ $kf['name'] }}">
                                                            <option value="">Pilih...</option>
                                                            <option value="{{ $kf['opt1'] }}" {{ ($pemeriksaan->{$kf['name']} ?? '') == $kf['opt1'] ? 'selected' : '' }}>{{ $kf['opt1'] }}</option>
                                                            <option value="{{ $kf['opt2'] }}" {{ ($pemeriksaan->{$kf['name']} ?? '') == $kf['opt2'] ? 'selected' : '' }}>{{ $kf['opt2'] }}</option>
                                                        </select>
                                                        <input type="text" class="form-control"
                                                            name="keterangan_{{ $kf['name'] }}"
                                                            value="{{ $pemeriksaan->{'keterangan_' . $kf['name']} ?? '' }}"
                                                            placeholder="Keterangan...">
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="card bg-light border-0 shadow-sm rounded-4">
                                    <div class="card-body p-4">
                                        <h6 class="fw-bold mb-3 border-bottom pb-2 text-primary">USG Trimester 1</h6>
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="form-label small fw-semibold">HPHT</label>
                                                <input type="date" class="form-control form-control-sm" name="hpht"
                                                    value="{{ $pemeriksaan->hpht ?? '' }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label small fw-semibold">Keteraturan Haid</label>
                                                <select class="form-select form-select-sm" name="keteraturan_haid">
                                                    <option value="">Pilih...</option>
                                                    <option value="Teratur" {{ ($pemeriksaan->keteraturan_haid ?? '') == 'Teratur' ? 'selected' : '' }}>Teratur</option>
                                                    <option value="Tidak Teratur" {{ ($pemeriksaan->keteraturan_haid ?? '') == 'Tidak Teratur' ? 'selected' : '' }}>Tidak Teratur</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label small fw-semibold">Umur Kehamilan (HPHT) -
                                                    Minggu</label>
                                                <input type="number" class="form-control form-control-sm"
                                                    name="usia_kehamilan_hpht"
                                                    value="{{ $pemeriksaan->usia_kehamilan_hpht ?? '' }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label small fw-semibold">HPL berdasar HPHT</label>
                                                <input type="date" class="form-control form-control-sm" name="hpl_hpht"
                                                    value="{{ $pemeriksaan->hpl_hpht ?? '' }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label small fw-semibold">Umur Kehamilan (USG) -
                                                    Minggu</label>
                                                <input type="number" class="form-control form-control-sm"
                                                    name="usia_kehamilan_usg"
                                                    value="{{ $pemeriksaan->usia_kehamilan_usg ?? '' }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label small fw-semibold">HPL berdasar USG</label>
                                                <input type="date" class="form-control form-control-sm" name="hpl_usg"
                                                    value="{{ $pemeriksaan->hpl_usg ?? '' }}">
                                            </div>

                                            <div class="col-md-4 mt-4">
                                                <label class="form-label small fw-semibold">Jumlah GS</label>
                                                <select class="form-select form-select-sm" name="jumlah_gs">
                                                    <option value="">Pilih...</option>
                                                    <option value="Tunggal" {{ ($pemeriksaan->jumlah_gs ?? '') == 'Tunggal' ? 'selected' : '' }}>Tunggal</option>
                                                    <option value="Kembar" {{ ($pemeriksaan->jumlah_gs ?? '') == 'Kembar' ? 'selected' : '' }}>Kembar</option>
                                                </select>
                                            </div>
                                            <div class="col-md-8 mt-4">
                                                <label class="form-label small fw-semibold">Diameter GS</label>
                                                <div class="input-group input-group-sm">
                                                    <input type="number" step="0.1" class="form-control"
                                                        name="diameter_gs_cm"
                                                        value="{{ $pemeriksaan->diameter_gs_cm ?? '' }}" placeholder="cm">
                                                    <span class="input-group-text">Sesuai umur kehamilan</span>
                                                    <input type="number" class="form-control" name="diameter_gs_minggu"
                                                        value="{{ $pemeriksaan->diameter_gs_minggu ?? '' }}"
                                                        placeholder="Minggu">
                                                    <span class="input-group-text">+</span>
                                                    <input type="number" class="form-control" name="diameter_gs_hari"
                                                        value="{{ $pemeriksaan->diameter_gs_hari ?? '' }}"
                                                        placeholder="Hari">
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label small fw-semibold">Jumlah Bayi</label>
                                                <select class="form-select form-select-sm" name="jumlah_bayi">
                                                    <option value="">Pilih...</option>
                                                    <option value="Tunggal" {{ ($pemeriksaan->jumlah_bayi ?? '') == 'Tunggal' ? 'selected' : '' }}>Tunggal</option>
                                                    <option value="Kembar" {{ ($pemeriksaan->jumlah_bayi ?? '') == 'Kembar' ? 'selected' : '' }}>Kembar</option>
                                                </select>
                                            </div>
                                            <div class="col-md-8">
                                                <label class="form-label small fw-semibold">CRL</label>
                                                <div class="input-group input-group-sm">
                                                    <input type="number" step="0.1" class="form-control" name="crl_cm"
                                                        value="{{ $pemeriksaan->crl_cm ?? '' }}" placeholder="cm">
                                                    <span class="input-group-text">Sesuai umur kehamilan</span>
                                                    <input type="number" class="form-control" name="crl_minggu"
                                                        value="{{ $pemeriksaan->crl_minggu ?? '' }}" placeholder="Minggu">
                                                    <span class="input-group-text">+</span>
                                                    <input type="number" class="form-control" name="crl_hari"
                                                        value="{{ $pemeriksaan->crl_hari ?? '' }}" placeholder="Hari">
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label small fw-semibold">Letak Produk Kehamilan</label>
                                                <select class="form-select form-select-sm" name="letak_produk_kehamilan">
                                                    <option value="">Pilih...</option>
                                                    <option value="Intrauterin" {{ ($pemeriksaan->letak_produk_kehamilan ?? '') == 'Intrauterin' ? 'selected' : '' }}>Intrauterin</option>
                                                    <option value="Ekstrauterin" {{ ($pemeriksaan->letak_produk_kehamilan ?? '') == 'Ekstrauterin' ? 'selected' : '' }}>Ekstrauterin</option>
                                                    <option value="Tidak dapat ditentukan" {{ ($pemeriksaan->letak_produk_kehamilan ?? '') == 'Tidak dapat ditentukan' ? 'selected' : '' }}>Tidak dapat ditentukan</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label small fw-semibold">Pulsasi Jantung</label>
                                                <select class="form-select form-select-sm" name="pulsasi_jantung">
                                                    <option value="">Pilih...</option>
                                                    <option value="Tampak" {{ ($pemeriksaan->pulsasi_jantung ?? '') == 'Tampak' ? 'selected' : '' }}>Tampak</option>
                                                    <option value="Tidak tampak" {{ ($pemeriksaan->pulsasi_jantung ?? '') == 'Tidak tampak' ? 'selected' : '' }}>Tidak tampak</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label small fw-semibold">Kecurigaan Temuan
                                                    Abnormal</label>
                                                <div class="input-group input-group-sm">
                                                    <select class="form-select" style="max-width: 100px;"
                                                        name="kecurigaan_temuan_abnormal">
                                                        <option value="">Pilih...</option>
                                                        <option value="Ya" {{ ($pemeriksaan->kecurigaan_temuan_abnormal ?? '') == 'Ya' ? 'selected' : '' }}>Ya</option>
                                                        <option value="Tidak" {{ ($pemeriksaan->kecurigaan_temuan_abnormal ?? '') == 'Tidak' ? 'selected' : '' }}>Tidak</option>
                                                    </select>
                                                    <input type="text" class="form-control"
                                                        name="kecurigaan_temuan_abnormal_sebutkan"
                                                        value="{{ $pemeriksaan->kecurigaan_temuan_abnormal_sebutkan ?? '' }}"
                                                        placeholder="Sebutkan jika Ya">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="mt-4 text-end">
                            <button type="submit"
                                class="btn btn-warning px-5 py-2 rounded-pill shadow-sm fw-semibold text-white">
                                <i class="bi bi-save2-fill me-2"></i> Simpan Evaluasi
                            </button>
                        </div>
                    </form>
    </div>
</div>
@endif

<div class="nakes-form-pane" id="nakes_form_trimester1">
    @php
        $pemeriksaan = $dataKia->pemeriksaanTrimester1 ?? new \App\Models\KiaPemeriksaanTrimester1();
        $catatan = $dataKia->catatanPelayananTrimester1;
    @endphp
    <div class="form-card">
        <div class="form-card-title">Trimester 1</div>
        <form action="{{ route($role . '.kia.save_trimester1', $dataKia->id) }}" method="POST" enctype="multipart/form-data" id="trimester1Form">
            @csrf

            <input type="hidden" name="deleted_catatan" id="deleted_catatan_t1" value="">

            <h4 class="fw-bold text-primary mb-4 border-bottom pb-2">Bagian Kiri (Halaman 52)</h4>

            <div class="row g-4 mb-5">
                <div class="col-12 col-md-6">
                    <label class="form-label fw-semibold">Hasil USG (Gambar)</label>
                    <input type="file" class="form-control" name="gambar_usg" accept="image/jpeg,image/png,image/jpg">
                    @if($pemeriksaan->gambar_usg)
                    <div class="mt-2">
                        <span class="badge bg-success">Sudah ada gambar</span>
                        <img src="{{ asset($pemeriksaan->gambar_usg) }}" alt="USG Image" class="img-thumbnail mt-2" style="max-height: 100px;">
                    </div>
                    @endif
                    <div class="form-text">Biarkan kosong jika tidak ingin mengubah gambar. (Max 2MB)</div>
                </div>

                <div class="col-12">
                    <h5 class="fw-bold mt-4">Pemeriksaan Laboratorium (Halaman 52)</h5>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tanggal Periksa Lab</label>
                    <input type="date" class="form-control" name="tgl_periksa_lab" value="{{ $pemeriksaan->tgl_periksa_lab }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Hemoglobin (g/dL)</label>
                    <input type="text" class="form-control" name="lab_hemoglobin" value="{{ $pemeriksaan->lab_hemoglobin }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Golongan Darah & Rhesus</label>
                    <input type="text" class="form-control" name="lab_gol_darah" value="{{ $pemeriksaan->lab_gol_darah }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Gula Darah Sewaktu (Mg/dL)</label>
                    <input type="text" class="form-control" name="lab_gula_darah" value="{{ $pemeriksaan->lab_gula_darah }}">
                </div>

                <div class="col-12">
                    <h6 class="fw-bold mt-2">Tripel Eliminasi</h6>
                </div>
                <div class="col-md-4">
                    <label class="form-label">H</label>
                    <select class="form-select" name="lab_tripel_h">
                        <option value="">-- Pilih --</option>
                        <option value="Reaktif" {{ $pemeriksaan->lab_tripel_h == 'Reaktif' ? 'selected' : '' }}>Reaktif</option>
                        <option value="Non reaktif" {{ $pemeriksaan->lab_tripel_h == 'Non reaktif' ? 'selected' : '' }}>Non reaktif</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">S</label>
                    <select class="form-select" name="lab_tripel_s">
                        <option value="">-- Pilih --</option>
                        <option value="Reaktif" {{ $pemeriksaan->lab_tripel_s == 'Reaktif' ? 'selected' : '' }}>Reaktif</option>
                        <option value="Non reaktif" {{ $pemeriksaan->lab_tripel_s == 'Non reaktif' ? 'selected' : '' }}>Non reaktif</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Hepatitis B</label>
                    <select class="form-select" name="lab_tripel_hep_b">
                        <option value="">-- Pilih --</option>
                        <option value="Reaktif" {{ $pemeriksaan->lab_tripel_hep_b == 'Reaktif' ? 'selected' : '' }}>Reaktif</option>
                        <option value="Non reaktif" {{ $pemeriksaan->lab_tripel_hep_b == 'Non reaktif' ? 'selected' : '' }}>Non reaktif</option>
                    </select>
                </div>

                <div class="col-12">
                    <h5 class="fw-bold mt-4">Skrining Kesehatan Jiwa</h5>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tanggal Skrining</label>
                    <input type="date" class="form-control" name="tgl_skrining_jiwa" value="{{ $pemeriksaan->tgl_skrining_jiwa }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Skrining Kesehatan Jiwa</label>
                    <select class="form-select" name="skrining_jiwa">
                        <option value="">-- Pilih --</option>
                        <option value="Ya" {{ $pemeriksaan->skrining_jiwa == 'Ya' ? 'selected' : '' }}>Ya</option>
                        <option value="Tidak" {{ $pemeriksaan->skrining_jiwa == 'Tidak' ? 'selected' : '' }}>Tidak</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tindak Lanjut</label>
                    <select class="form-select" name="tindak_lanjut_jiwa">
                        <option value="">-- Pilih --</option>
                        <option value="Edukasi" {{ $pemeriksaan->tindak_lanjut_jiwa == 'Edukasi' ? 'selected' : '' }}>Edukasi</option>
                        <option value="Konseling" {{ $pemeriksaan->tindak_lanjut_jiwa == 'Konseling' ? 'selected' : '' }}>Konseling</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Perlu Rujukan</label>
                    <select class="form-select" name="rujukan_jiwa">
                        <option value="">-- Pilih --</option>
                        <option value="Ya" {{ $pemeriksaan->rujukan_jiwa == 'Ya' ? 'selected' : '' }}>Ya</option>
                        <option value="Tidak" {{ $pemeriksaan->rujukan_jiwa == 'Tidak' ? 'selected' : '' }}>Tidak</option>
                    </select>
                </div>

                <div class="col-md-6 mt-4">
                    <label class="form-label fw-bold">Kesimpulan</label>
                    <input type="text" class="form-control" name="kesimpulan" value="{{ $pemeriksaan->kesimpulan }}">
                </div>
                <div class="col-md-6 mt-4">
                    <label class="form-label fw-bold">Rekomendasi</label>
                    <input type="text" class="form-control" name="rekomendasi" value="{{ $pemeriksaan->rekomendasi }}">
                </div>
            </div>

            <h4 class="fw-bold text-primary mb-4 border-bottom pb-2 mt-5">Bagian Kanan (Halaman 53) - Catatan Pelayanan</h4>

            <div id="catatan-container-t1">
                @forelse($catatan as $index => $cat)
                <div class="catatan-item card border shadow-none rounded-3 mb-4 p-4" data-index="{{ $index }}">
                    <input type="hidden" name="catatan[{{ $index }}][id]" value="{{ $cat->id }}">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold m-0 text-secondary">Catatan #<span class="catatan-counter">{{ $index + 1 }}</span></h6>
                        <button type="button" class="btn btn-outline-danger btn-sm rounded-pill btn-remove-catatan" data-id="{{ $cat->id }}">Hapus Baris</button>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Tanggal Periksa</label>
                            <input type="date" class="form-control" name="catatan[{{ $index }}][tanggal_periksa]" value="{{ $cat->tanggal_periksa }}">
                            <small class="text-muted">Juga digunakan untuk area Stamp & Paraf</small>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tanggal Kembali</label>
                            <input type="date" class="form-control" name="catatan[{{ $index }}][tanggal_kembali]" value="{{ $cat->tanggal_kembali }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Keluhan, Pemeriksaan, Tindakan dan Saran</label>
                            <textarea class="form-control" name="catatan[{{ $index }}][catatan]" rows="3">{{ $cat->catatan }}</textarea>
                            <small class="text-muted">Tekan Enter untuk baris baru. Tercetak ke bawah di PDF.</small>
                        </div>
                    </div>
                </div>
                @empty
                <div class="catatan-item card border shadow-none rounded-3 mb-4 p-4" data-index="0">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold m-0 text-secondary">Catatan #<span class="catatan-counter">1</span></h6>
                        <button type="button" class="btn btn-outline-danger btn-sm rounded-pill btn-remove-catatan d-none">Hapus Baris</button>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Tanggal Periksa</label>
                            <input type="date" class="form-control" name="catatan[0][tanggal_periksa]">
                            <small class="text-muted">Juga digunakan untuk area Stamp & Paraf</small>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tanggal Kembali</label>
                            <input type="date" class="form-control" name="catatan[0][tanggal_kembali]">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Keluhan, Pemeriksaan, Tindakan dan Saran</label>
                            <textarea class="form-control" name="catatan[0][catatan]" rows="3"></textarea>
                            <small class="text-muted">Tekan Enter untuk baris baru. Tercetak ke bawah di PDF.</small>
                        </div>
                    </div>
                </div>
                @endforelse
            </div>

            <div class="mb-4">
                <button type="button" class="btn btn-outline-secondary rounded-pill px-4" id="btnAddCatatanT1">
                    <i class="bi bi-plus-circle me-2"></i> Tambah Catatan
                </button>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-5">
                <button type="submit" class="btn btn-success px-4 rounded-pill">Simpan Data</button>
            </div>
        </form>
    </div>
</div>

<div class="nakes-form-pane" id="nakes_form_trimester2">
    @php
        $pemeriksaan = $dataKia->pemeriksaanTrimester2 ?? new \App\Models\KiaPemeriksaanTrimester2();
        $catatan = $dataKia->catatanPelayananTrimester2;
    @endphp
    <div class="form-card">
        <div class="form-card-title">Trimester 2</div>
        <form action="{{ route($role . '.kia.save_trimester2', $dataKia->id) }}" method="POST" id="trimester2Form">
                @csrf

                <input type="hidden" name="deleted_catatan" id="deleted_catatan_t2" value="">

                <h4 class="fw-bold text-primary mb-4 border-bottom pb-2">Bagian Kiri (Halaman 53) - Skrining Preeklampsia &
                    Diabetes</h4>

                <div class="row g-4 mb-5">
                    <div class="col-12">
                        <h5 class="fw-bold">1. Skrining Preeklampsia (Umur kehamilan < 20 minggu)</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Kriteria</th>
                                                <th class="text-center" style="width: 150px;">Risiko Sedang<br><small
                                                        class="fw-normal">(Tampil di Baris)</small></th>
                                                <th class="text-center" style="width: 150px;">Risiko Tinggi<br><small
                                                        class="fw-normal">(Tampil di Bawah)</small></th>
                                                <th class="text-center" style="width: 100px;">Normal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $grup1 = [
                                                    'multipara_pasangan_baru' => 'Multipara Dengan Kehamilan oleh Pasangan Baru',
                                                    'teknologi_reproduksi' => 'Kehamilan Dengan Teknologi Reproduksi Berbantu',
                                                    'umur_35' => 'Umur >= 35 Tahun',
                                                    'nullipara' => 'Nullipara',
                                                    'jarak_10' => 'Multipara dengan Jarak Kehamilan Sebelumnya > 10 Tahun',
                                                    'riwayat_ibu_saudara' => 'Riwayat Preeklampsia pada Ibu atau Saudara Perempuan',
                                                    'obesitas' => 'Obesitas Sebelum Hamil (IMT > 30 kg/m2)',
                                                ];
                                                $grup2 = [
                                                    'riwayat_preeklampsia_sebelumnya' => 'Multipara Dengan Riwayat Preeklampsia Sebelumnya',
                                                    'kehamilan_multipel' => 'Kehamilan Multipel',
                                                    'diabetes' => 'Diabetes dalam Kehamilan',
                                                    'hipertensi' => 'Hipertensi Kronik',
                                                    'ginjal' => 'Penyakit Ginjal',
                                                    'autoimun' => 'Penyakit Autoimun, SLE',
                                                    'aps' => 'Anti Phospholipid Syndrome',
                                                ];
                                                $grup3 = [
                                                    'map_90' => 'Mean Arterial Pressure > 90 mmHg',
                                                    'proteinuria' => 'Proteinuria (Urin Celup +1)',
                                                ];
                                                $skriningData = $pemeriksaan->skrining_preeklampsia ?? [];
                                            @endphp

                                            <!-- Grup 1 -->
                                            <tr class="table-light">
                                                <td colspan="4" class="fw-bold small text-uppercase">Anamnesis - Kelompok
                                                    Risiko Sedang</td>
                                            </tr>
                                            @foreach($grup1 as $key => $label)
                                                <tr>
                                                    <td>{{ $label }}</td>
                                                    <td class="text-center">
                                                        <input class="form-check-input check-preeklampsia" type="radio"
                                                            name="skrining_preeklampsia[{{ $key }}]" value="Risiko Sedang" {{ ($skriningData[$key] ?? '') === 'Risiko Sedang' ? 'checked' : '' }}>
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input check-preeklampsia" type="radio"
                                                            name="skrining_preeklampsia[{{ $key }}]" value="Risiko Tinggi" {{ ($skriningData[$key] ?? '') === 'Risiko Tinggi' ? 'checked' : '' }}>
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input check-preeklampsia" type="radio"
                                                            name="skrining_preeklampsia[{{ $key }}]" value="" {{ empty($skriningData[$key]) ? 'checked' : '' }}>
                                                    </td>
                                                </tr>
                                            @endforeach

                                            <!-- Grup 2 -->
                                            <tr style="background-color: #fce4ec;">
                                                <td colspan="4" class="fw-bold small text-uppercase">Anamnesis - Kelompok
                                                    Risiko Tinggi</td>
                                            </tr>
                                            @foreach($grup2 as $key => $label)
                                                <tr style="background-color: #fffafa;">
                                                    <td>{{ $label }}</td>
                                                    <td class="text-center">
                                                        <input class="form-check-input check-preeklampsia" type="radio"
                                                            name="skrining_preeklampsia[{{ $key }}]" value="Risiko Sedang" {{ ($skriningData[$key] ?? '') === 'Risiko Sedang' ? 'checked' : '' }}>
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input check-preeklampsia" type="radio"
                                                            name="skrining_preeklampsia[{{ $key }}]" value="Risiko Tinggi" {{ ($skriningData[$key] ?? '') === 'Risiko Tinggi' ? 'checked' : '' }}>
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input check-preeklampsia" type="radio"
                                                            name="skrining_preeklampsia[{{ $key }}]" value="" {{ empty($skriningData[$key]) ? 'checked' : '' }}>
                                                    </td>
                                                </tr>
                                            @endforeach

                                            <!-- Grup 3 -->
                                            <tr class="table-secondary">
                                                <td colspan="4" class="fw-bold small text-uppercase">Pemeriksaan Fisik</td>
                                            </tr>
                                            @foreach($grup3 as $key => $label)
                                                <tr>
                                                    <td>{{ $label }}</td>
                                                    <td class="text-center">
                                                        <input class="form-check-input check-preeklampsia" type="radio"
                                                            name="skrining_preeklampsia[{{ $key }}]" value="Risiko Sedang" {{ ($skriningData[$key] ?? '') === 'Risiko Sedang' ? 'checked' : '' }}>
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input check-preeklampsia" type="radio"
                                                            name="skrining_preeklampsia[{{ $key }}]" value="Risiko Tinggi" {{ ($skriningData[$key] ?? '') === 'Risiko Tinggi' ? 'checked' : '' }}>
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="form-check-input check-preeklampsia" type="radio"
                                                            name="skrining_preeklampsia[{{ $key }}]" value="" {{ empty($skriningData[$key]) ? 'checked' : '' }}>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-bold">Kesimpulan Skrining Preeklampsia</label>
                        <input type="text" class="form-control" name="kesimpulan_preeklampsia"
                            value="{{ $pemeriksaan->kesimpulan_preeklampsia }}"
                            placeholder="Contoh: Perlu Rujukan / Normal">
                    </div>

                    <div class="col-12 mt-5">
                        <h5 class="fw-bold">2. Skrining Diabetes Melitus Gestasional (24-28 Minggu)</h5>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Gula Darah Puasa (mg/dL)</label>
                        <input type="text" class="form-control" name="lab_gula_darah_puasa"
                            value="{{ $pemeriksaan->lab_gula_darah_puasa }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Rencana Tindak Lanjut (Gula Darah Puasa)</label>
                        <input type="text" class="form-control" name="tindak_lanjut_puasa"
                            value="{{ $pemeriksaan->tindak_lanjut_puasa }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Gula Darah 2 Jam Post Prandial (mg/dL)</label>
                        <input type="text" class="form-control" name="lab_gula_darah_2jam"
                            value="{{ $pemeriksaan->lab_gula_darah_2jam }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Rencana Tindak Lanjut (Gula Darah 2 Jam)</label>
                        <input type="text" class="form-control" name="tindak_lanjut_2jam"
                            value="{{ $pemeriksaan->tindak_lanjut_2jam }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tanggal Periksa</label>
                        <input type="date" class="form-control" name="tgl_periksa_diabetes"
                            value="{{ $pemeriksaan->tgl_periksa_diabetes }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nama Dokter Pemeriksa</label>
                        <input type="text" class="form-control" name="nama_dokter_diabetes"
                            value="{{ $pemeriksaan->nama_dokter_diabetes }}">
                    </div>
                </div>

                <h4 class="fw-bold text-primary mb-4 border-bottom pb-2 mt-5">Bagian Kanan (Halaman 53) - Catatan Pelayanan
                    Trimester 2</h4>

                <div id="catatan-container-t2">
                    @forelse($catatan as $index => $cat)
                        <div class="catatan-item card border shadow-none rounded-3 mb-4 p-4" data-index="{{ $index }}">
                            <input type="hidden" name="catatan[{{ $index }}][id]" value="{{ $cat->id }}">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-bold m-0 text-secondary">Catatan #<span
                                        class="catatan-counter">{{ $index + 1 }}</span></h6>
                                <button type="button" class="btn btn-outline-danger btn-sm rounded-pill btn-remove-catatan"
                                    data-id="{{ $cat->id }}">Hapus Baris</button>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Tanggal Periksa</label>
                                    <input type="date" class="form-control" name="catatan[{{ $index }}][tanggal_periksa]"
                                        value="{{ $cat->tanggal_periksa }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Tanggal Kembali</label>
                                    <input type="date" class="form-control" name="catatan[{{ $index }}][tanggal_kembali]"
                                        value="{{ $cat->tanggal_kembali }}">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Keluhan, Pemeriksaan, Tindakan dan Saran</label>
                                    <textarea class="form-control" name="catatan[{{ $index }}][catatan]"
                                        rows="3">{{ $cat->catatan }}</textarea>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="catatan-item card border shadow-none rounded-3 mb-4 p-4" data-index="0">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-bold m-0 text-secondary">Catatan #<span class="catatan-counter">1</span></h6>
                                <button type="button"
                                    class="btn btn-outline-danger btn-sm rounded-pill btn-remove-catatan d-none">Hapus
                                    Baris</button>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Tanggal Periksa</label>
                                    <input type="date" class="form-control" name="catatan[0][tanggal_periksa]">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Tanggal Kembali</label>
                                    <input type="date" class="form-control" name="catatan[0][tanggal_kembali]">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Keluhan, Pemeriksaan, Tindakan dan Saran</label>
                                    <textarea class="form-control" name="catatan[0][catatan]" rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div>

                <div class="mb-4">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" id="btnAddCatatanT2">
                        <i class="bi bi-plus-circle me-2"></i> Tambah Catatan
                    </button>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-5">
                    <button type="submit" class="btn btn-success px-4 rounded-pill">Simpan Data</button>
                </div>
            </form>
    </div>
</div>
