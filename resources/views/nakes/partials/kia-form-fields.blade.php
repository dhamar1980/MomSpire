<!-- Form Fields Partial for buku-kia-pengguna -->
<div class="form-category-content" id="form_info_buku" style="display:none;">
    <div class="form-card">
        <div class="form-card-title">Informasi Pembuatan Buku KIA</div>
        <div class="form-row-custom">
            <div>
                <label class="form-label-custom">Faskes Dikeluarkan</label>
                <input type="text" class="form-control form-control-custom" name="faskes_dikeluarkan" value="{{ $selectedKia->faskes_dikeluarkan ?? old('faskes_dikeluarkan') }}" placeholder="Nama faskes">
            </div>
            <div>
                <label class="form-label-custom">Tanggal Dikeluarkan</label>
                <input type="date" class="form-control form-control-custom" name="tanggal_dikeluarkan" value="{{ $selectedKia->tanggal_dikeluarkan ?? old('tanggal_dikeluarkan') }}">
            </div>
            <div>
                <label class="form-label-custom">Kabupaten/Kota</label>
                <input type="text" class="form-control form-control-custom" name="kab_kota_dikeluarkan" value="{{ $selectedKia->kab_kota_dikeluarkan ?? old('kab_kota_dikeluarkan') }}" placeholder="Kabupaten/Kota">
            </div>
            <div>
                <label class="form-label-custom">Provinsi</label>
                <input type="text" class="form-control form-control-custom" name="provinsi_dikeluarkan" value="{{ $selectedKia->provinsi_dikeluarkan ?? old('provinsi_dikeluarkan') }}" placeholder="Provinsi">
            </div>
        </div>
    </div>
</div>

<div class="form-category-content" id="form_identitas_ibu" style="display:none;">
    <div class="form-card">
        <div class="form-card-title">Data Diri Ibu Hamil</div>
        <div class="form-row-custom">
            <div>
                <label class="form-label-custom">Nama Lengkap</label>
                <input type="text" class="form-control form-control-custom" name="nama_ibu" value="{{ $selectedKia->ibu->nama ?? old('nama_ibu') }}" placeholder="Nama lengkap">
            </div>
            <div>
                <label class="form-label-custom">NIK</label>
                <input type="text" class="form-control form-control-custom" name="nik" value="{{ $selectedKia->ibu->nik ?? old('nik') }}" placeholder="16 digit NIK" maxlength="16">
            </div>
            <div>
                <label class="form-label-custom">No. JKN / BPJS</label>
                <input type="text" class="form-control form-control-custom" name="no_jkn_ibu" value="{{ $selectedKia->ibu->no_jkn ?? old('no_jkn_ibu') }}" placeholder="No. JKN/BPJS">
            </div>
        </div>
        <div class="form-row-custom">
            <div>
                <label class="form-label-custom">Tempat Lahir</label>
                <input type="text" class="form-control form-control-custom" name="tempat_lahir" value="{{ $selectedKia->ibu->tempat_lahir ?? old('tempat_lahir') }}" placeholder="Kota kelahiran">
            </div>
            <div>
                <label class="form-label-custom">Tanggal Lahir</label>
                <input type="date" class="form-control form-control-custom" name="tanggal_lahir" value="{{ $selectedKia->ibu->tanggal_lahir ?? old('tanggal_lahir') }}">
            </div>
            <div>
                <label class="form-label-custom">Golongan Darah</label>
                <select class="form-select form-select-custom" name="golongan_darah">
                    <option value="">Pilih</option>
                    @foreach(['A','B','AB','O'] as $goldar)
                    <option value="{{ $goldar }}" {{ ($selectedKia->ibu->golongan_darah ?? old('golongan_darah')) == $goldar ? 'selected' : '' }}>{{ $goldar }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-row-custom">
            <div>
                <label class="form-label-custom">Pendidikan</label>
                <select class="form-select form-select-custom" name="pendidikan">
                    <option value="">Pilih</option>
                    @foreach(['Tidak Sekolah','SD','SMP','SMA/SMK','D1-D3','S1','S2','S3'] as $pend)
                    <option value="{{ $pend }}" {{ ($selectedKia->ibu->pendidikan ?? old('pendidikan')) == $pend ? 'selected' : '' }}>{{ $pend }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label-custom">Pekerjaan</label>
                <input type="text" class="form-control form-control-custom" name="pekerjaan" value="{{ $selectedKia->ibu->pekerjaan ?? old('pekerjaan') }}" placeholder="Pekerjaan">
            </div>
            <div>
                <label class="form-label-custom">Telepon/HP</label>
                <input type="tel" class="form-control form-control-custom" name="telepon_ibu" value="{{ $selectedKia->ibu->telepon ?? old('telepon_ibu') }}" placeholder="08xxxxxxxxxx">
            </div>
        </div>
        <div class="form-row-custom">
            <div>
                <label class="form-label-custom">Alamat Lengkap</label>
                <input type="text" class="form-control form-control-custom" name="alamat" value="{{ $selectedKia->ibu->alamat ?? old('alamat') }}" placeholder="Alamat lengkap">
            </div>
        </div>
    </div>
    <div class="form-card">
        <div class="form-card-title">Faskes Ibu</div>
        <div class="form-row-custom">
            <div>
                <label class="form-label-custom">Faskes TK1</label>
                <input type="text" class="form-control form-control-custom" name="faskes_tk1_ibu" value="{{ $selectedKia->ibu->faskes_tk1 ?? old('faskes_tk1_ibu') }}" placeholder="Faskes TK1">
            </div>
            <div>
                <label class="form-label-custom">Faskes Rujukan</label>
                <input type="text" class="form-control form-control-custom" name="faskes_rujukan_ibu" value="{{ $selectedKia->ibu->faskes_rujukan ?? old('faskes_rujukan_ibu') }}" placeholder="Faskes Rujukan">
            </div>
        </div>
    </div>
</div>

<div class="form-category-content" id="form_identitas_suami" style="display:none;">
    <div class="form-card">
        <div class="form-card-title">Data Diri Suami</div>
        <div class="form-row-custom">
            <div>
                <label class="form-label-custom">Nama Lengkap Suami</label>
                <input type="text" class="form-control form-control-custom" name="nama_suami" value="{{ $selectedKia->suami->nama ?? old('nama_suami') }}" placeholder="Nama lengkap suami">
            </div>
            <div>
                <label class="form-label-custom">NIK Suami</label>
                <input type="text" class="form-control form-control-custom" name="nik_suami" value="{{ $selectedKia->suami->nik ?? old('nik_suami') }}" placeholder="16 digit NIK" maxlength="16">
            </div>
            <div>
                <label class="form-label-custom">No. JKN / BPJS Suami</label>
                <input type="text" class="form-control form-control-custom" name="no_jkn_suami" value="{{ $selectedKia->suami->no_jkn ?? old('no_jkn_suami') }}" placeholder="No. JKN/BPJS">
            </div>
        </div>
        <div class="form-row-custom">
            <div>
                <label class="form-label-custom">Tempat Lahir</label>
                <input type="text" class="form-control form-control-custom" name="tempat_lahir_suami" value="{{ $selectedKia->suami->tempat_lahir ?? old('tempat_lahir_suami') }}" placeholder="Kota kelahiran">
            </div>
            <div>
                <label class="form-label-custom">Tanggal Lahir</label>
                <input type="date" class="form-control form-control-custom" name="tanggal_lahir_suami" value="{{ $selectedKia->suami->tanggal_lahir ?? old('tanggal_lahir_suami') }}">
            </div>
            <div>
                <label class="form-label-custom">Golongan Darah</label>
                <select class="form-select form-select-custom" name="golongan_darah_suami">
                    <option value="">Pilih</option>
                    @foreach(['A','B','AB','O'] as $goldar)
                    <option value="{{ $goldar }}" {{ ($selectedKia->suami->golongan_darah ?? old('golongan_darah_suami')) == $goldar ? 'selected' : '' }}>{{ $goldar }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-row-custom">
            <div>
                <label class="form-label-custom">Pendidikan</label>
                <select class="form-select form-select-custom" name="pendidikan_suami">
                    <option value="">Pilih</option>
                    @foreach(['Tidak Sekolah','SD','SMP','SMA/SMK','D1-D3','S1','S2','S3'] as $pend)
                    <option value="{{ $pend }}" {{ ($selectedKia->suami->pendidikan ?? old('pendidikan_suami')) == $pend ? 'selected' : '' }}>{{ $pend }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label-custom">Pekerjaan</label>
                <input type="text" class="form-control form-control-custom" name="pekerjaan_suami" value="{{ $selectedKia->suami->pekerjaan ?? old('pekerjaan_suami') }}" placeholder="Pekerjaan">
            </div>
            <div>
                <label class="form-label-custom">Telepon/HP</label>
                <input type="tel" class="form-control form-control-custom" name="telepon_suami" value="{{ $selectedKia->suami->telepon ?? old('telepon_suami') }}" placeholder="08xxxxxxxxxx">
            </div>
        </div>
        <div class="form-row-custom">
            <div>
                <label class="form-label-custom">Alamat Rumah</label>
                <input type="text" class="form-control form-control-custom" name="alamat_rumah_suami" value="{{ $selectedKia->suami->alamat ?? old('alamat_rumah_suami') }}" placeholder="Alamat rumah">
            </div>
        </div>
    </div>
    <div class="form-card">
        <div class="form-card-title">Faskes Suami</div>
        <div class="form-row-custom">
            <div>
                <label class="form-label-custom">Faskes TK1 Suami</label>
                <input type="text" class="form-control form-control-custom" name="faskes_tk1_suami" value="{{ $selectedKia->suami->faskes_tk1 ?? old('faskes_tk1_suami') }}" placeholder="Faskes TK1">
            </div>
            <div>
                <label class="form-label-custom">Faskes Rujukan Suami</label>
                <input type="text" class="form-control form-control-custom" name="faskes_rujukan_suami" value="{{ $selectedKia->suami->faskes_rujukan ?? old('faskes_rujukan_suami') }}" placeholder="Faskes Rujukan">
            </div>
        </div>
    </div>
</div>

<div class="form-category-content" id="form_identitas_anak" style="display:none;">
    <div class="form-card">
        <div class="form-card-title">Data Diri Anak</div>
        <div class="form-row-custom">
            <div>
                <label class="form-label-custom">Nama Lengkap Anak</label>
                <input type="text" class="form-control form-control-custom" name="nama_anak" value="{{ $selectedKia->anak->nama ?? old('nama_anak') }}" placeholder="Nama lengkap anak">
            </div>
            <div>
                <label class="form-label-custom">NIK Anak</label>
                <input type="text" class="form-control form-control-custom" name="nik_anak" value="{{ $selectedKia->anak->nik ?? old('nik_anak') }}" placeholder="16 digit NIK" maxlength="16">
            </div>
            <div>
                <label class="form-label-custom">No. JKN / BPJS Anak</label>
                <input type="text" class="form-control form-control-custom" name="no_jkn_anak" value="{{ $selectedKia->anak->no_jkn ?? old('no_jkn_anak') }}" placeholder="No. JKN/BPJS">
            </div>
        </div>
        <div class="form-row-custom">
            <div>
                <label class="form-label-custom">Tempat Lahir</label>
                <input type="text" class="form-control form-control-custom" name="tempat_lahir_anak" value="{{ $selectedKia->anak->tempat_lahir ?? old('tempat_lahir_anak') }}" placeholder="Kota kelahiran">
            </div>
            <div>
                <label class="form-label-custom">Tanggal Lahir</label>
                <input type="date" class="form-control form-control-custom" name="tanggal_lahir_anak" value="{{ $selectedKia->anak->tanggal_lahir ?? old('tanggal_lahir_anak') }}">
            </div>
            <div>
                <label class="form-label-custom">Anak ke-</label>
                <input type="number" class="form-control form-control-custom" name="anak_ke" min="1" value="{{ $selectedKia->anak->anak_ke ?? old('anak_ke') }}" placeholder="1">
            </div>
        </div>
        <div class="form-row-custom">
            <div>
                <label class="form-label-custom">No. Akta Kelahiran</label>
                <input type="text" class="form-control form-control-custom" name="no_akta_kelahiran_anak" value="{{ $selectedKia->anak->no_akta_kelahiran ?? old('no_akta_kelahiran_anak') }}" placeholder="No. Akta">
            </div>
            <div>
                <label class="form-label-custom">Golongan Darah</label>
                <select class="form-select form-select-custom" name="golongan_darah_anak">
                    <option value="">Pilih</option>
                    @foreach(['A','B','AB','O'] as $goldar)
                    <option value="{{ $goldar }}" {{ ($selectedKia->anak->golongan_darah ?? old('golongan_darah_anak')) == $goldar ? 'selected' : '' }}>{{ $goldar }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label-custom">Telepon</label>
                <input type="tel" class="form-control form-control-custom" name="telepon_anak" value="{{ $selectedKia->anak->telepon ?? old('telepon_anak') }}" placeholder="08xxxxxxxxxx">
            </div>
        </div>
        <div class="form-row-custom">
            <div>
                <label class="form-label-custom">Alamat Rumah Anak</label>
                <input type="text" class="form-control form-control-custom" name="alamat_anak" value="{{ $selectedKia->anak->alamat ?? old('alamat_anak') }}" placeholder="Alamat (kosongkan jika sama dengan ibu)">
            </div>
        </div>
    </div>
    <div class="form-card">
        <div class="form-card-title">Faskes Anak</div>
        <div class="form-row-custom">
            <div>
                <label class="form-label-custom">Faskes TK1 Anak</label>
                <input type="text" class="form-control form-control-custom" name="faskes_tk1_anak" value="{{ $selectedKia->anak->faskes_tk1 ?? old('faskes_tk1_anak') }}" placeholder="Faskes TK1">
            </div>
            <div>
                <label class="form-label-custom">Faskes Rujukan Anak</label>
                <input type="text" class="form-control form-control-custom" name="faskes_rujukan_anak" value="{{ $selectedKia->anak->faskes_rujukan ?? old('faskes_rujukan_anak') }}" placeholder="Faskes Rujukan">
            </div>
        </div>
    </div>
</div>

<div class="form-category-content" id="form_layanan" style="display:none;">
    <div class="form-card">
        <div class="form-card-title">Fasilitas Pelayanan Kesehatan - Ibu</div>
        <div class="form-row-custom">
            <div>
                <label class="form-label-custom">Puskesmas Domisili</label>
                <input type="text" class="form-control form-control-custom" name="puskesmas_domisili" value="{{ $selectedKia->layanan->puskesmas_domisili ?? old('puskesmas_domisili') }}" placeholder="Puskesmas domisili">
            </div>
            <div>
                <label class="form-label-custom">No. Reg Kohort Ibu</label>
                <input type="text" class="form-control form-control-custom" name="no_reg_kohort_ibu" value="{{ $selectedKia->layanan->no_reg_kohort_ibu ?? old('no_reg_kohort_ibu') }}" placeholder="No. Reg">
            </div>
        </div>
        <div class="form-row-custom">
            <div>
                <label class="form-label-custom">No. Reg Kohort Bayi</label>
                <input type="text" class="form-control form-control-custom" name="no_reg_kohort_bayi" value="{{ $selectedKia->layanan->no_reg_kohort_bayi ?? old('no_reg_kohort_bayi') }}" placeholder="No. Reg Kohort Bayi">
            </div>
            <div>
                <label class="form-label-custom">No. Reg Kohort Balita</label>
                <input type="text" class="form-control form-control-custom" name="no_reg_kohort_balita" value="{{ $selectedKia->layanan->no_reg_kohort_balita ?? old('no_reg_kohort_balita') }}" placeholder="No. Reg Kohort Balita">
            </div>
            <div>
                <label class="form-label-custom">No. Catatan Medik RS</label>
                <input type="text" class="form-control form-control-custom" name="no_catatan_medik_rs" value="{{ $selectedKia->layanan->no_catatan_medik_rs ?? old('no_catatan_medik_rs') }}" placeholder="No. Catatan Medik RS">
            </div>
        </div>
    </div>
</div>

<div class="form-category-content" id="form_asuransi" style="display:none;">
    <div class="form-card">
        <div class="form-card-title">Asuransi - Ibu</div>
        <div class="form-row-custom">
            <div>
                <label class="form-label-custom">Nama Asuransi</label>
                <input type="text" class="form-control form-control-custom" name="asuransi_lain" value="{{ $selectedKia->layanan->asuransi_lain ?? old('asuransi_lain') }}" placeholder="Nama asuransi">
            </div>
            <div>
                <label class="form-label-custom">No. Kartu Asuransi</label>
                <input type="text" class="form-control form-control-custom" name="no_asuransi_lain" value="{{ $selectedKia->layanan->no_asuransi_lain ?? old('no_asuransi_lain') }}" placeholder="No. Kartu">
            </div>
            <div>
                <label class="form-label-custom">Tanggal Berlaku</label>
                <input type="date" class="form-control form-control-custom" name="tanggal_berlaku_asuransi_lain" value="{{ $selectedKia->layanan->tanggal_berlaku_asuransi_lain ?? old('tanggal_berlaku_asuransi_lain') }}">
            </div>
        </div>
    </div>
</div>

<div class="form-category-content" id="form_kehamilan" style="display:none;">
    <div class="form-card">
        <div class="form-card-title">Data Kehamilan Saat Ini</div>
        <div class="form-row-custom">
            <div>
                <label class="form-label-custom">HPHT (Hari Pertama Haid Terakhir)</label>
                <input type="date" class="form-control form-control-custom" name="hpht" value="{{ $selectedKia->riwayat->hpht ?? old('hpht') }}">
            </div>
            <div>
                <label class="form-label-custom">HTP (Hari Perkiraan Lahir)</label>
                <input type="date" class="form-control form-control-custom" name="htp" value="{{ $selectedKia->riwayat->htp ?? old('htp') }}">
            </div>
            <div>
                <label class="form-label-custom">Tinggi Badan (cm)</label>
                <input type="number" class="form-control form-control-custom" name="tinggi_badan" value="{{ $selectedKia->riwayat->tinggi_badan ?? old('tinggi_badan') }}" placeholder="150">
            </div>
        </div>
        <div class="form-row-custom">
            <div>
                <label class="form-label-custom">Lingkar Lengan Atas (cm)</label>
                <input type="number" class="form-control form-control-custom" name="lingkar_lengan_atas" step="0.1" value="{{ $selectedKia->riwayat->lingkar_lengan_atas ?? old('lingkar_lengan_atas') }}" placeholder="23.5">
            </div>
        </div>
    </div>
</div>

<div class="form-category-content" id="form_catatan_ttd" style="display:none;">
    <div class="form-card">
        <div class="form-card-title">Catatan Minum Tablet Tambah Darah (TTD)</div>
        <p class="text-muted small mb-3">Catat minum TTD setiap hari. Centang jika sudah minum.</p>
        <div class="row">
            @for($bulan = 1; $bulan <= 3; $bulan++)
            @php $ttd = $selectedKia->ttdTrackings->firstWhere('bulan_ke', $bulan); @endphp
            <div class="col-md-4 mb-4">
                <div class="border rounded-3 p-3">
                    <h6 class="fw-bold text-center mb-3" style="color: #e63980;">Bulan ke-{{ $bulan }}</h6>
                    <div class="d-flex justify-content-between mb-2">
                        <label class="form-label-custom small">Usia Kehamilan (minggu)</label>
                        <input type="number" class="form-control form-control-sm w-50" name="ttd_bulan_{{ $bulan }}_usia" value="{{ $ttd->usia_kehamilan ?? old('ttd_bulan_'.$bulan.'_usia') }}" placeholder="12" style="font-size:0.8rem;">
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <label class="form-label-custom small">Bulan/Tahun</label>
                        <input type="text" class="form-control form-control-sm w-50" name="ttd_bulan_{{ $bulan }}_bulan_tahun" value="{{ $ttd->bulan_tahun ?? old('ttd_bulan_'.$bulan.'_bulan_tahun') }}" placeholder="Januari 2026" style="font-size:0.8rem;">
                    </div>
                    <div class="border-top pt-2 mt-2">
                        <div class="row g-1">
                            @for($hari = 1; $hari <= 31; $hari++)
                            @php $fieldName = 'h' . $hari; @endphp
                            <div class="col-1 text-center p-0">
                                <small class="d-block" style="font-size:0.6rem;color:#999;">{{ $hari }}</small>
                                <div class="form-check d-flex justify-content-center p-0">
                                    <input class="form-check-input" type="checkbox" name="ttd_bulan_{{ $bulan }}_h{{ $hari }}" {{ $ttd && $ttd->$fieldName ? 'checked' : '' }} style="width:14px;height:14px;">
                                </div>
                            </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
            @endfor
        </div>
        <p class="text-muted small mt-3"><em>Halaman TTD bulan 4-9 belum ditampilkan. Klik Simpan dulu, lalu pilih tab TTD untuk mengisi bulan berikutnya.</em></p>
    </div>
</div>

<div class="form-category-content" id="form_pemantauan_mingguan" style="display:none;">
    <div class="form-card">
        <div class="form-card-title">Pemantauan Ibu Hamil Mingguan</div>
        <div class="table-responsive">
            <table class="table table-bordered table-sm">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width:60px;">Minggu</th>
                        <th>Periksa</th><th>Kelas</th><th>Demam</th><th>Pusing</th>
                        <th>Sulit Tidur</th><th>Risiko TB</th><th>Gerakan Bayi</th>
                        <th>Nyeri Perut</th><th>Cairan Lahir</th><th>Sakit Kencing</th><th>Diare</th>
                    </tr>
                </thead>
                <tbody>
                    @for($minggu = 1; $minggu <= 10; $minggu++)
                    @php $pm = $selectedKia->pemantauanMingguans->firstWhere('minggu_ke', $minggu); @endphp
                    <tr>
                        <td class="text-center fw-bold">{{ $minggu }}</td>
                        @for($col = 1; $col <= 10; $col++)
                        @php
                            $fields = ['pemeriksaan_kehamilan','kelas_ibu_hamil','demam_lebih_2_hari','pusing_sakit_kepala','sulit_tidur_cemas','risiko_tb','gerakan_bayi','nyeri_perut_hebat','keluar_cairan_lahir','sakit_saat_kencing','diare_berulang'];
                            $fieldName = $fields[$col-1] ?? null;
                        @endphp
                        <td class="text-center"><input class="form-check-input" type="checkbox" name="pemantauan_minggu_{{ $minggu }}_{{ $col }}" {{ $pm && $fieldName && $pm->$fieldName ? 'checked' : '' }}></td>
                        @endfor
                    </tr>
                    @endfor
                </tbody>
            </table>
        </div>
        <p class="text-muted small mt-3"><em>Halaman pemantauan minggu 11-42 belum ditampilkan. Klik Simpan dulu, lalu pilih tab Pemantauan untuk mengisi minggu berikutnya.</em></p>
    </div>
</div>

<div class="form-category-content" id="form_kelas_ibu" style="display:none;">
    <div class="form-card">
        <div class="form-card-title">Kelas Ibu Hamil - Absensi</div>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-light"><tr><th class="text-center">Sesi</th><th>Tanggal</th><th>Kader/Nakes</th></tr></thead>
                <tbody>
                    @for($sesi = 1; $sesi <= 9; $sesi++)
                    @php $absen = $selectedKia->absenKelasIbuHamils->firstWhere('kehadiran_ke', $sesi); @endphp
                    <tr>
                        <td class="text-center fw-bold">{{ $sesi }}</td>
                        <td><input type="date" class="form-control form-control-custom" name="kelas_ibu_tanggal_{{ $sesi }}" value="{{ $absen->tanggal ?? old('kelas_ibu_tanggal_'.$sesi) }}"></td>
                        <td><input type="text" class="form-control form-control-custom" name="kelas_ibu_kader_{{ $sesi }}" value="{{ $absen->kader_info ?? old('kelas_ibu_kader_'.$sesi) }}" placeholder="Nama kader/nakes"></td>
                    </tr>
                    @endfor
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="form-category-content" id="form_persiapan" style="display:none;">
    @php $persiapan = $selectedKia->persiapanMelahirkan; @endphp
    <div class="form-card">
        <div class="form-card-title">Persiapan Melahirkan</div>
        <div class="form-row-custom">
            <div><label class="form-label-custom">HPL Tanggal</label><input type="number" class="form-control form-control-custom" name="hpl_tanggal" min="1" max="31" value="{{ $persiapan->hpl_tanggal ?? old('hpl_tanggal') }}" placeholder="Tanggal"></div>
            <div><label class="form-label-custom">HPL Bulan</label><input type="number" class="form-control form-control-custom" name="hpl_bulan" min="1" max="12" value="{{ $persiapan->hpl_bulan ?? old('hpl_bulan') }}" placeholder="Bulan"></div>
            <div><label class="form-label-custom">HPL Tahun</label><input type="number" class="form-control form-control-custom" name="hpl_tahun" min="2024" value="{{ $persiapan->hpl_tahun ?? old('hpl_tahun') }}" placeholder="Tahun"></div>
        </div>
        <hr>
        <h6 class="fw-bold mb-3">Persiapan Checklist</h6>
        <div class="row">
            <div class="col-md-6">
                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="tanya_tanggal_perkiraan" {{ $persiapan && $persiapan->tanya_tanggal_perkiraan ? 'checked' : '' }}><label class="form-check-label"> Tanya tanggal perkiraan melahirkan</label></div>
                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="minta_dampingi" {{ $persiapan && $persiapan->minta_dampingi ? 'checked' : '' }}><label class="form-check-label"> Minta keluarga/dukun accompagngi</label></div>
                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="siap_tabungan" {{ $persiapan && $persiapan->siap_tabungan ? 'checked' : '' }}><label class="form-check-label"> Siap menyimpan uang</label></div>
                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="kartu_jkn" {{ $persiapan && $persiapan->kartu_jkn ? 'checked' : '' }}><label class="form-check-label"> Siap membawa kartu JKN/BPJS</label></div>
                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="tempat_melahirkan" {{ $persiapan && $persiapan->tempat_melahirkan ? 'checked' : '' }}><label class="form-check-label"> Tahu tempat melahirkan</label></div>
            </div>
            <div class="col-md-6">
                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="siap_ktp_kk" {{ $persiapan && $persiapan->siap_ktp_kk ? 'checked' : '' }}><label class="form-check-label"> Siap membawa KTP dan KK</label></div>
                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="siap_pendonor" {{ $persiapan && $persiapan->siap_pendonor ? 'checked' : '' }}><label class="form-check-label"> Siap mencari donor darah</label></div>
                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="siap_kendaraan" {{ $persiapan && $persiapan->siap_kendaraan ? 'checked' : '' }}><label class="form-check-label"> Siap transportasi ke faskes</label></div>
                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="sepakat_stiker_p4k" {{ $persiapan && $persiapan->sepakat_stiker_p4k ? 'checked' : '' }}><label class="form-check-label"> Sepakat pasang stiker P4K</label></div>
                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="rencana_kb" {{ $persiapan && $persiapan->rencana_kb ? 'checked' : '' }}><label class="form-check-label"> Rencanakan KB setelah melahirkan</label></div>
            </div>
        </div>
    </div>
</div>

<div class="form-category-content" id="form_pemantauan_nifas" style="display:none;">
    <div class="form-card">
        <div class="form-card-title">Pemantauan Ibu Nifas (42 Hari)</div>
        <div class="table-responsive">
            <table class="table table-bordered table-sm">
                <thead class="table-light">
                    <tr><th class="text-center" style="width:80px;">Hari</th><th>Periksa</th><th>Vit A</th><th>TTD</th><th>Gizi</th><th>Jiwa</th><th>Demam</th><th>SK</th><th>Kabur</th><th>Ulu Hati</th><th>Jantung</th><th>Cairan</th><th>Napas</th><th>Payudara</th><th>BAK</th><th>Kelamin</th><th>Bau</th><th>Pendarahan</th><th>Putih</th><th>Paraf</th></tr>
                </thead>
                <tbody>
                    @for($hari = 1; $hari <= 7; $hari++)
                    @php $nifas = $selectedKia->pemantauanIbuNifas->firstWhere('hari_ke', $hari); @endphp
                    <tr>
                        <td class="text-center fw-bold">{{ $hari }}</td>
                        @for($col = 1; $col <= 18; $col++)
                        @php
                            $fields = ['pemeriksaan_nifas','konsumsi_vitamin_a','konsumsi_ttd','pemenuhan_gizi','masalah_jiwa','demam','sakit_kepala','pandangan_kabur','nyeri_ulu_hati','jantung_berdebar','keluar_cairan_lahir','napas_pendek','payudara_bengkak','gangguan_bak','kelamin_bengkak','darah_nifas_berbau','pendarahan_hebat','keputihan'];
                            $fieldName = $fields[$col-1] ?? null;
                        @endphp
                        <td class="text-center"><input class="form-check-input" type="checkbox" name="nifas_hari_{{ $hari }}_{{ $col }}" {{ $nifas && $fieldName && $nifas->$fieldName ? 'checked' : '' }}></td>
                        @endfor
                        <td><input type="text" class="form-control form-control-sm" name="nifas_paraf_{{ $hari }}" value="{{ $nifas->paraf_kader_nakes ?? old('nifas_paraf_'.$hari) }}" placeholder="Paraf"></td>
                    </tr>
                    @endfor
                </tbody>
            </table>
        </div>
        <p class="text-muted small mt-3"><em>Halaman pemantauan nifas hari ke-8 s/d 42 belum ditampilkan. Klik Simpan dulu, lalu pilih tab Nifas untuk mengisi hari berikutnya.</em></p>
    </div>
</div>

<div class="form-category-content" id="form_kb" style="display:none;">
    @php $kb = $selectedKia->keluargaBerencana; @endphp
    <div class="form-card">
        <div class="form-card-title">Rencana Keluarga Berencana (KB)</div>
        <div class="form-row-custom">
            <div>
                <label class="form-label-custom">Metode KB</label>
                <select class="form-select form-select-custom" name="metode_kb">
                    <option value="">Pilih</option>
                    @foreach(['Pil KB','Suntik KB 1 Bulan','Suntik KB 3 Bulan','IUD','Implant','MOW','MOP','Kondom','Ovulasi','Lainnya'] as $m)
                    <option value="{{ $m }}" {{ ($kb->metode_kb ?? old('metode_kb')) == $m ? 'selected' : '' }}>{{ $m }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label-custom">Paraf Ibu</label>
                <input type="text" class="form-control form-control-custom" name="paraf_ibu" value="{{ $kb->paraf_ibu ?? old('paraf_ibu') }}" placeholder="Paraf">
            </div>
        </div>
    </div>
</div>

<div class="form-category-content" id="form_bayi_baru" style="display:none;">
    @php $bayi = $selectedKia->bayiBaruLahir; @endphp
    <div class="form-card">
        <div class="form-card-title">Ceklist Pemeriksaan Bayi Baru Lahir</div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-check mb-3"><input class="form-check-input" type="checkbox" name="jam_0_6" {{ $bayi && $bayi->jam_0_6 ? 'checked' : '' }}><label class="form-check-label"> 0-6 Jam: Pertama kali</label></div>
                <div class="form-check mb-3"><input class="form-check-input" type="checkbox" name="jam_6_48" {{ $bayi && $bayi->jam_6_48 ? 'checked' : '' }}><label class="form-check-label"> 6-48 Jam: Kunjungan ulang</label></div>
            </div>
            <div class="col-md-6">
                <div class="form-check mb-3"><input class="form-check-input" type="checkbox" name="hari_3_7" {{ $bayi && $bayi->hari_3_7 ? 'checked' : '' }}><label class="form-check-label"> 3-7 Hari: Kunjungan rumah</label></div>
                <div class="form-check mb-3"><input class="form-check-input" type="checkbox" name="hari_8_28" {{ $bayi && $bayi->hari_8_28 ? 'checked' : '' }}><label class="form-check-label"> 8-28 Hari: Kunjungan neonatus</label></div>
            </div>
        </div>
    </div>
</div>

<div class="form-category-content" id="form_pemantauan_bayi" style="display:none;">
    <div class="form-card">
        <div class="form-card-title">Pemantauan Bayi Harian (0-28 Hari)</div>
        <div class="table-responsive">
            <table class="table table-bordered table-sm">
                <thead class="table-light">
                    <tr><th class="text-center" style="width:60px;">Hari</th><th>Sesak</th><th>Aktif</th><th>Biru</th><th>Hisap</th><th>Kejang</th><th>Suhu</th><th>BAB</th><th>Kencing</th><th>Tali</th><th>Mata</th><th>Kulit</th><th>Imun</th><th>Paraf</th></tr>
                </thead>
                <tbody>
                    @for($hari = 1; $hari <= 7; $hari++)
                    @php $pb = $selectedKia->pemantauanBayis->firstWhere('hari_ke', $hari); @endphp
                    <tr>
                        <td class="text-center fw-bold">{{ $hari }}</td>
                        @for($col = 1; $col <= 12; $col++)
                        @php
                            $fields = ['sesak_napas','aktivitas_lemah','warna_kulit_biru','hisapan_lemah','kejang','suhu_abnormal','bab_abnormal','kencing_sedikit','tali_pusat_merah','mata_merah','kulit_bintil','belum_imunisasi'];
                            $fieldName = $fields[$col-1] ?? null;
                        @endphp
                        <td class="text-center"><input class="form-check-input" type="checkbox" name="bayi_hari_{{ $hari }}_{{ $col }}" {{ $pb && $fieldName && $pb->$fieldName ? 'checked' : '' }}></td>
                        @endfor
                        <td><input type="text" class="form-control form-control-sm" name="bayi_paraf_{{ $hari }}" value="{{ $pb->paraf_kader_nakes ?? old('bayi_paraf_'.$hari) }}" placeholder="Paraf"></td>
                    </tr>
                    @endfor
                </tbody>
            </table>
        </div>
        <p class="text-muted small mt-3"><em>Halaman pemantauan bayi hari ke-8 s/d 28 belum ditampilkan. Klik Simpan dulu, lalu pilih tab P. Bayi untuk mengisi hari berikutnya.</em></p>
    </div>
</div>

<div class="form-category-content" id="form_warna_tinja" style="display:none;">
    @php $tinja = $selectedKia->warnaTinja; @endphp
    <div class="form-card">
        <div class="form-card-title">Pemantauan Warna Tinja Bayi</div>
        <div class="form-row-custom">
            <div><label class="form-label-custom">Tanggal 2 Minggu</label><input type="date" class="form-control form-control-custom" name="tinja_2_minggu_tanggal" value="{{ $tinja->tanggal_2_minggu ?? old('tinja_2_minggu_tanggal') }}"></div>
            <div><label class="form-label-custom">Nomor 2 Minggu</label><input type="text" class="form-control form-control-custom" name="tinja_2_minggu_nomor" value="{{ $tinja->nomor_2_minggu ?? old('tinja_2_minggu_nomor') }}" placeholder="Nomor"></div>
            <div><label class="form-label-custom">Tanggal 1 Bulan</label><input type="date" class="form-control form-control-custom" name="tinja_1_bulan_tanggal" value="{{ $tinja->tanggal_1_bulan ?? old('tinja_1_bulan_tanggal') }}"></div>
            <div><label class="form-label-custom">Nomor 1 Bulan</label><input type="text" class="form-control form-control-custom" name="tinja_1_bulan_nomor" value="{{ $tinja->nomor_1_bulan ?? old('tinja_1_bulan_nomor') }}" placeholder="Nomor"></div>
        </div>
        <div class="form-row-custom">
            <div><label class="form-label-custom">Tanggal 2-4 Bulan</label><input type="date" class="form-control form-control-custom" name="tinja_2_4_bulan_tanggal" value="{{ $tinja->tanggal_2_4_bulan ?? old('tinja_2_4_bulan_tanggal') }}"></div>
            <div><label class="form-label-custom">Nomor 2-4 Bulan</label><input type="text" class="form-control form-control-custom" name="tinja_2_4_bulan_nomor" value="{{ $tinja->nomor_2_4_bulan ?? old('tinja_2_4_bulan_nomor') }}" placeholder="Nomor"></div>
        </div>
    </div>
</div>

<div class="form-category-content" id="form_kelas_balita" style="display:none;">
    <div class="form-card">
        <div class="form-card-title">Kelas Balita - Absensi</div>
        <div class="table-responsive">
            <table class="table table-bordered"><thead class="table-light"><tr><th class="text-center">Sesi</th><th>Tanggal</th><th>Kader/Nakes</th></tr></thead>
            <tbody>
                @for($sesi = 1; $sesi <= 9; $sesi++)
                @php $absenBalita = $selectedKia->absenKelasBalitas->firstWhere('kehadiran_ke', $sesi); @endphp
                <tr><td class="text-center fw-bold">{{ $sesi }}</td>
                    <td><input type="date" class="form-control form-control-custom" name="kelas_balita_tanggal_{{ $sesi }}" value="{{ $absenBalita->tanggal ?? old('kelas_balita_tanggal_'.$sesi) }}"></td>
                    <td><input type="text" class="form-control form-control-custom" name="kelas_balita_kader_{{ $sesi }}" value="{{ $absenBalita->kader_info ?? old('kelas_balita_kader_'.$sesi) }}" placeholder="Nama kader/nakes"></td>
                </tr>
                @endfor
            </tbody>
        </table>
    </div>
</div>

<div class="form-category-content" id="form_perkembangan_bayi" style="display:none;">
    @php $pb02 = $selectedKia->perkembanganBayi; $pb36 = $selectedKia->perkembanganBayi6Bulan; @endphp
    <div class="form-card">
        <div class="form-card-title">Perkembangan Bayi 0-2 Bulan</div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="angkat_kepala_45" value="1" {{ $pb02 && $pb02->angkat_kepala_45 ? 'checked' : '' }}><label class="form-check-label"> Mengangkat kepala 45 derajat</label></div>
                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="gerk_kepala" value="1" {{ $pb02 && $pb02->gerk_kepala ? 'checked' : '' }}><label class="form-check-label"> Menggerakkan kepala kanan-kiri</label></div>
                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="tatap_wajah" value="1" {{ $pb02 && $pb02->tatap_wajah ? 'checked' : '' }}><label class="form-check-label"> Menatap wajah ibu/bapak</label></div>
                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="ngoceh" value="1" {{ $pb02 && $pb02->ngoceh ? 'checked' : '' }}><label class="form-check-label"> Mengoceh/ngiler</label></div>
            </div>
            <div class="col-md-6">
                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="tertawa_keras" value="1" {{ $pb02 && $pb02->tertawa_keras ? 'checked' : '' }}><label class="form-check-label"> Tertawa keras</label></div>
                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="terkejut_suara" value="1" {{ $pb02 && $pb02->terkejut_suara ? 'checked' : '' }}><label class="form-check-label"> Terkejut mendengar suara keras</label></div>
                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="tersenyum" value="1" {{ $pb02 && $pb02->tersenyum ? 'checked' : '' }}><label class="form-check-label"> Tersenyum spontan</label></div>
                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="mengenal_ibu" value="1" {{ $pb02 && $pb02->mengenal_ibu ? 'checked' : '' }}><label class="form-check-label"> Mengenal ibu/bapak</label></div>
            </div>
        </div>
    </div>
    <div class="form-card">
        <div class="form-card-title">Perkembangan Bayi 3-6 Bulan</div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="berbalik" value="1" {{ $pb36 && $pb36->berbalik ? 'checked' : '' }}><label class="form-check-label"> Dapat membolak-balik</label></div>
                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="kepala_tegak_90" value="1" {{ $pb36 && $pb36->kepala_tegak_90 ? 'checked' : '' }}><label class="form-check-label"> Kepala tegak 90 derajat</label></div>
                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="kepala_stabil" value="1" {{ $pb36 && $pb36->kepala_stabil ? 'checked' : '' }}><label class="form-check-label"> Kepala stabil saat duduk</label></div>
                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="genggam_mainan" value="1" {{ $pb36 && $pb36->genggam_mainan ? 'checked' : '' }}><label class="form-check-label"> Menggenggam mainan</label></div>
                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="raih_benda" value="1" {{ $pb36 && $pb36->raih_benda ? 'checked' : '' }}><label class="form-check-label"> Reach benda</label></div>
            </div>
            <div class="col-md-6">
                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="amati_tangan" value="1" {{ $pb36 && $pb36->amati_tangan ? 'checked' : '' }}><label class="form-check-label"> Memperhatikan tangannya</label></div>
                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="luas_pandang" value="1" {{ $pb36 && $pb36->luas_pandang ? 'checked' : '' }}><label class="form-check-label"> Memperluas pandangan</label></div>
                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="arah_mata" value="1" {{ $pb36 && $pb36->arah_mata ? 'checked' : '' }}><label class="form-check-label"> Benda besar dapat diikuti</label></div>
                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="suara_gembira" value="1" {{ $pb36 && $pb36->suara_gembira ? 'checked' : '' }}><label class="form-check-label"> Bersuara tanpa arti</label></div>
                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="senyum_mainan" value="1" {{ $pb36 && $pb36->senyum_mainan ? 'checked' : '' }}><label class="form-check-label"> Senyum saat pandang mainan</label></div>
            </div>
        </div>
    </div>
</div>

<div class="form-category-content" id="form_kesehatan_lingkungan" style="display:none;">
    @php $ling = $selectedKia->kesehatanLingkungan; @endphp
    <div class="form-card">
        <div class="form-card-title">Kesehatan Lingkungan</div>
        <div class="row">
            <div class="col-md-6">
                <h6 class="fw-bold mb-3">Tempat BAB</h6>
                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="bab_sembarangan" value="1" {{ $ling && $ling->bab_sembarangan ? 'checked' : '' }}><label class="form-check-label"> BAB di sembarangan</label></div>
                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="bab_jamban_sendiri" value="1" {{ $ling && $ling->bab_jamban_sendiri ? 'checked' : '' }}><label class="form-check-label"> BAB di jamban sendiri</label></div>
                <h6 class="fw-bold mb-3 mt-4">Sumber Air</h6>
                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="sumber_air_pipa" value="1" {{ $ling && $ling->sumber_air_pipa ? 'checked' : '' }}><label class="form-check-label"> Air PAM/PDAM</label></div>
                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="sumber_air_sumur" value="1" {{ $ling && $ling->sumber_air_sumur ? 'checked' : '' }}><label class="form-check-label"> Sumur</label></div>
                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="sumber_air_sungai" value="1" {{ $ling && $ling->sumber_air_sungai ? 'checked' : '' }}><label class="form-check-label"> Sungai/Mata Air/Sumber</label></div>
            </div>
            <div class="col-md-6">
                <h6 class="fw-bold mb-3">Kepemilikan Jamban</h6>
                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="jamban_sehat_keluarga" value="1" {{ $ling && $ling->jamban_sehat_keluarga ? 'checked' : '' }}><label class="form-check-label"> Jamban sehat keluarga</label></div>
                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="jamban_komunal" value="1" {{ $ling && $ling->jamban_komunal ? 'checked' : '' }}><label class="form-check-label"> Jamban komunal/semua lingkungan</label></div>
                <h6 class="fw-bold mb-3 mt-4">Keterangan</h6>
                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="punya_air_bersih" value="1" {{ $ling && $ling->punya_air_bersih ? 'checked' : '' }}><label class="form-check-label"> Memiliki akses air bersih</label></div>
                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="punya_jamban" value="1" {{ $ling && $ling->punya_jamban ? 'checked' : '' }}><label class="form-check-label"> Memiliki jamban yang sehat</label></div>
            </div>
        </div>
    </div>
</div>
