<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DataKia;

class DataKiaController extends Controller
{
    public function wizard(Request $request, $id = null)
    {
        abort_unless(auth()->check() && auth()->user()->role === 'pengguna', 403);

        // Ambil semua buku KIA untuk list selector
        $allDataKia = DataKia::where('user_id', auth()->id())
            ->with(['ibu', 'anak'])
            ->get();

        // Jika tidak ada buku KIA, buat baru
        if ($allDataKia->isEmpty()) {
            $dataKia = DataKia::create(['user_id' => auth()->id()]);
            $allDataKia = DataKia::where('user_id', auth()->id())
                ->with(['ibu', 'anak'])
                ->get();
        }

        // Tentukan buku KIA yang aktif
        $activeKia = null;
        if ($id) {
            // Cari berdasarkan ID dari parameter
            $activeKia = $allDataKia->firstWhere('id', $id);
            // Pastikan buku ini milik user yang login
            if ($activeKia && $activeKia->user_id !== auth()->id()) {
                abort(403);
            }
        }

        // Jika tidak ada ID yang dipilih atau ID tidak valid, gunakan yang pertama
        if (!$activeKia) {
            $activeKia = $allDataKia->first();
        }

        // Load relasi untuk buku yang aktif
        $activeKia = DataKia::with(['ibu', 'suami', 'anak', 'layanan', 'riwayat', 'ttdTrackings', 'absenKelasIbuHamils', 'persiapanMelahirkan', 'pemantauanIbuNifas', 'keluargaBerencana', 'bayiBaruLahir', 'pemantauanBayis', 'warnaTinja', 'absenKelasBalitas', 'pemantauanMingguanBayis', 'perkembanganBayi', 'pemantauanBulananBayis', 'perkembanganBayi6Bulan', 'pemantauanBulananBayi12s', 'perkembanganBayi9Bulan', 'perkembanganBayi12Bulan', 'pemantauanBulananAnak24s', 'perkembanganBayi18Bulan', 'perkembanganBayi24Bulan', 'pemantauanBulananAnak72s', 'kesehatanLingkungan', 'pelayananKesehatanIbu', 'evaluasiKesehatanIbu'])
            ->find($activeKia->id);

        // Build bukuKiaCards - untuk selector
        $bukuKiaCards = [];
        foreach ($allDataKia as $kia) {
            $label = 'Buku KIA Utama';
            $namaAnak = null;
            $status = 'Draft';

            if ($kia->ibu) {
                $label = $kia->ibu->nama ? 'Buku KIA - ' . $kia->ibu->nama : 'Buku KIA Utama';
                $namaAnak = $kia->anak->nama ?? null;
                if ($kia->ibu->tanggal_lahir) {
                    $status = 'Aktif';
                }
            } else if ($kia->anak) {
                $namaAnak = $kia->anak->nama;
            }

            $bukuKiaCards[] = [
                'id' => $kia->id,
                'label' => $label,
                'status' => $status,
                'nama_anak' => $namaAnak,
                'is_active' => ($kia->id === $activeKia->id),
            ];
        }

        $totalBukuKia = count($bukuKiaCards);

        // Alias untuk backward compatibility dengan view yang sudah ada
        $dataKia = $activeKia;

        return view('pengguna.bukuKIA', compact('dataKia', 'bukuKiaCards', 'totalBukuKia', 'activeKia'));
    }

    public function tambahBukuKia(Request $request)
    {
        abort_unless(auth()->check() && auth()->user()->role === 'pengguna', 403);

        // Buat buku KIA baru
        $dataKia = DataKia::create(['user_id' => auth()->id()]);

        return response()->json([
            'success' => true,
            'message' => 'Buku KIA baru berhasil dibuat',
            'id' => $dataKia->id,
        ]);
    }

    public function saveWizard(Request $request)
    {
        abort_unless(auth()->check() && auth()->user()->role === 'pengguna', 403);

        // Ambil ID buku KIA dari request atau gunakan yang pertama
        $kiaId = $request->input('data_kia_id');

        if ($kiaId) {
            $dataKia = DataKia::where('id', $kiaId)
                ->where('user_id', auth()->id())
                ->firstOrFail();
        } else {
            // Fallback: buat baru jika tidak ada
            $dataKia = DataKia::firstOrCreate(['user_id' => auth()->id()]);
        }

        // Helper to convert empty strings to null
        $clean = function ($val) {
            return $val === '' ? null : $val;
        };

        // 1. Core Data
        $dataKia->update([
            'faskes_dikeluarkan' => $clean($request->faskes_dikeluarkan),
            'tanggal_dikeluarkan' => $clean($request->tanggal_dikeluarkan),
            'kab_kota_dikeluarkan' => $clean($request->kab_kota_dikeluarkan),
            'provinsi_dikeluarkan' => $clean($request->provinsi_dikeluarkan),
        ]);

        // 2. Identitas Ibu
        $dataKia->ibu()->updateOrCreate([], [
            'nama' => $clean($request->nama_ibu),
            'nik' => $clean($request->nik),
            'no_jkn' => $clean($request->no_jkn_ibu),
            'faskes_tk1' => $clean($request->faskes_tk1_ibu),
            'faskes_rujukan' => $clean($request->faskes_rujukan_ibu),
            'tempat_lahir' => $clean($request->tempat_lahir),
            'tanggal_lahir' => $clean($request->tanggal_lahir),
            'pendidikan' => $clean($request->pendidikan),
            'pekerjaan' => $clean($request->pekerjaan),
            'alamat' => $clean($request->alamat),
            'telepon' => $clean($request->telepon_ibu),
            'golongan_darah' => $clean($request->golongan_darah),
        ]);

        // 3. Identitas Suami
        $dataKia->suami()->updateOrCreate([], [
            'nama' => $clean($request->nama_suami),
            'nik' => $clean($request->nik_suami),
            'no_jkn' => $clean($request->no_jkn_suami),
            'faskes_tk1' => $clean($request->faskes_tk1_suami),
            'faskes_rujukan' => $clean($request->faskes_rujukan_suami),
            'tempat_lahir' => $clean($request->tempat_lahir_suami),
            'tanggal_lahir' => $clean($request->tanggal_lahir_suami),
            'pendidikan' => $clean($request->pendidikan_suami),
            'pekerjaan' => $clean($request->pekerjaan_suami),
            'alamat' => $clean($request->alamat_rumah_suami),
            'telepon' => $clean($request->telepon_suami),
            'golongan_darah' => $clean($request->golongan_darah_suami),
        ]);

        // 4. Identitas Anak
        $dataKia->anak()->updateOrCreate([], [
            'nama' => $clean($request->nama_anak),
            'nik' => $clean($request->nik_anak),
            'no_jkn' => $clean($request->no_jkn_anak),
            'faskes_tk1' => $clean($request->faskes_tk1_anak),
            'faskes_rujukan' => $clean($request->faskes_rujukan_anak),
            'tempat_lahir' => $clean($request->tempat_lahir_anak),
            'tanggal_lahir' => $clean($request->tanggal_lahir_anak),
            'anak_ke' => $clean($request->anak_ke),
            'no_akta_kelahiran' => $clean($request->no_akta_kelahiran_anak),
            'telepon' => $clean($request->telepon_anak),
            'alamat' => $clean($request->alamat_anak),
            'golongan_darah' => $clean($request->golongan_darah_anak),
        ]);

        // 5. Layanan & Pembiayaan
        $dataKia->layanan()->updateOrCreate([], [
            // Ibu
            'puskesmas_domisili' => $clean($request->puskesmas_domisili),
            'no_reg_kohort_ibu' => $clean($request->no_reg_kohort_ibu),
            'no_reg_kohort_bayi' => $clean($request->no_reg_kohort_bayi),
            'no_reg_kohort_balita' => $clean($request->no_reg_kohort_balita),
            'no_catatan_medik_rs' => $clean($request->no_catatan_medik_rs),
            'asuransi_lain' => $clean($request->asuransi_lain),
            'no_asuransi_lain' => $clean($request->no_asuransi_lain),
            'tanggal_berlaku_asuransi_lain' => $clean($request->tanggal_berlaku_asuransi_lain),

            // Suami
            'asuransi_suami' => $clean($request->asuransi_suami),
            'no_asuransi_suami' => $clean($request->no_asuransi_suami),
            'tanggal_berlaku_asuransi_suami' => $clean($request->tanggal_berlaku_asuransi_suami),
            'puskesmas_domisili_suami' => $clean($request->puskesmas_domisili_suami),
            'no_catatan_medik_rs_suami' => $clean($request->no_catatan_medik_rs_suami),

            // Anak
            'asuransi_anak' => $clean($request->asuransi_anak),
            'no_asuransi_anak' => $clean($request->no_asuransi_anak),
            'tanggal_berlaku_asuransi_anak' => $clean($request->tanggal_berlaku_asuransi_anak),
            'puskesmas_domisili_anak' => $clean($request->puskesmas_domisili_anak),
            'no_catatan_medik_rs_anak' => $clean($request->no_catatan_medik_rs_anak),
        ]);

        // 6. Riwayat Kesehatan (Nakes fields excluded to prevent overwrite)
        $dataKia->riwayat()->updateOrCreate([], [
            'hpht' => $clean($request->hpht),
            'htp' => $clean($request->htp),
            // Existing fields from old migration that I kept in riwayat
            'lingkar_lengan_atas' => $clean($request->lingkar_lengan_atas),
            'tinggi_badan' => $clean($request->tinggi_badan),
            'trimester_1' => $clean($request->trimester_1),
            'trimester_2' => $clean($request->trimester_2),
            'trimester_3' => $clean($request->trimester_3),
        ]);

        // 7. Catatan TTD (Tablet Tambah Darah)
        for ($bulan = 1; $bulan <= 9; $bulan++) {
            $ttdData = [
                'usia_kehamilan' => $clean($request->input("ttd_bulan_{$bulan}_usia")),
                'bulan_tahun' => $clean($request->input("ttd_bulan_{$bulan}_bulan_tahun")),
            ];
            for ($hari = 1; $hari <= 31; $hari++) {
                $ttdData["h{$hari}"] = $request->has("ttd_bulan_{$bulan}_h{$hari}");
            }
            $dataKia->ttdTrackings()->updateOrCreate(
                ['bulan_ke' => $bulan],
                $ttdData
            );
        }

        // 8. Pemantauan Mingguan
        $pemantauanFields = [
            'pemeriksaan_kehamilan', 'kelas_ibu_hamil', 'demam_lebih_2_hari',
            'pusing_sakit_kepala', 'sulit_tidur_cemas', 'risiko_tb',
            'gerakan_bayi', 'nyeri_perut_hebat', 'keluar_cairan_lahir',
            'sakit_saat_kencing', 'diare_berulang'
        ];
        for ($minggu = 1; $minggu <= 42; $minggu++) {
            $data = [];
            $colIndex = 1;
            foreach ($pemantauanFields as $field) {
                $data[$field] = $request->has("pemantauan_minggu_{$minggu}_{$colIndex}");
                $colIndex++;
            }
            $dataKia->pemantauanMingguans()->updateOrCreate(
                ['minggu_ke' => $minggu],
                $data
            );
        }

        // 9. Kelas Ibu Hamil
        for ($sesi = 1; $sesi <= 9; $sesi++) {
            $dataKia->absenKelasIbuHamils()->updateOrCreate(
                ['kehadiran_ke' => $sesi],
                [
                    'tanggal' => $clean($request->input("kelas_ibu_tanggal_{$sesi}")),
                    'kader_info' => $clean($request->input("kelas_ibu_kader_{$sesi}")),
                ]
            );
        }

        // 10. Persiapan Melahirkan
        $persiapanFields = [
            'tanya_tanggal_perkiraan', 'minta_dampingi', 'siap_tabungan',
            'kartu_jkn', 'tempat_melahirkan', 'siap_ktp_kk',
            'siap_pendonor', 'siap_kendaraan', 'sepakat_stiker_p4k', 'rencana_kb'
        ];
        $persiapanData = [
            'hpl_tanggal' => $clean($request->hpl_tanggal),
            'hpl_bulan' => $clean($request->hpl_bulan),
            'hpl_tahun' => $clean($request->hpl_tahun),
        ];
        foreach ($persiapanFields as $field) {
            $persiapanData[$field] = $request->has($field);
        }
        $persiapanData['metode_kb'] = $clean($request->metode_kb);
        $dataKia->persiapanMelahirkan()->updateOrCreate(
            ['data_kia_id' => $dataKia->id],
            $persiapanData
        );

        // 11. Pemantauan Nifas
        $nifasFields = [
            'pemeriksaan_nifas', 'konsumsi_vitamin_a', 'konsumsi_ttd',
            'pemenuhan_gizi', 'masalah_jiwa', 'demam', 'sakit_kepala',
            'pandangan_kabur', 'nyeri_ulu_hati', 'jantung_berdebar',
            'keluar_cairan_lahir', 'napas_pendek', 'payudara_bengkak',
            'gangguan_bak', 'kelamin_bengkak', 'darah_nifas_berbau',
            'pendarahan_hebat', 'keputihan'
        ];
        for ($hari = 1; $hari <= 42; $hari++) {
            $data = [];
            $colIndex = 1;
            foreach ($nifasFields as $field) {
                $data[$field] = $request->has("nifas_hari_{$hari}_{$colIndex}");
                $colIndex++;
            }
            $data['paraf_kader_nakes'] = $clean($request->input("nifas_paraf_{$hari}"));
            $dataKia->pemantauanIbuNifas()->updateOrCreate(
                ['hari_ke' => $hari],
                $data
            );
        }

        // 12. KB
        $dataKia->keluargaBerencana()->updateOrCreate(
            ['data_kia_id' => $dataKia->id],
            [
                'metode_kb' => $clean($request->metode_kb),
                'paraf_ibu' => $clean($request->paraf_ibu),
            ]
        );

        // 13. Bayi Baru Lahir
        $dataKia->bayiBaruLahir()->updateOrCreate(
            ['data_kia_id' => $dataKia->id],
            [
                'jam_0_6' => $request->has('bayi_0_6_jam'),
                'jam_6_48' => $request->has('bayi_6_48_jam'),
                'hari_3_7' => $request->has('bayi_hari_3_7'),
                'hari_8_28' => $request->has('bayi_hari_8_28'),
            ]
        );

        // 14. Pemantauan Bayi Harian
        $bayiFields = [
            'sesak_napas', 'aktivitas_lemah', 'warna_kulit_biru', 'hisapan_lemah',
            'kejang', 'suhu_abnormal', 'bab_abnormal', 'kencing_sedikit',
            'tali_pusat_merah', 'mata_merah', 'kulit_bintil', 'belum_imunisasi'
        ];
        for ($hari = 1; $hari <= 28; $hari++) {
            $data = [];
            $colIndex = 1;
            foreach ($bayiFields as $field) {
                $data[$field] = $request->has("bayi_hari_{$hari}_{$colIndex}");
                $colIndex++;
            }
            $data['paraf_kader_nakes'] = $clean($request->input("bayi_paraf_{$hari}"));
            $dataKia->pemantauanBayis()->updateOrCreate(
                ['data_kia_id' => $dataKia->id, 'hari_ke' => $hari],
                $data
            );
        }

        // 15. Warna Tinja
        $dataKia->warnaTinja()->updateOrCreate(
            ['data_kia_id' => $dataKia->id],
            [
                'tanggal_2_minggu' => $clean($request->tinja_2_minggu_tanggal),
                'nomor_2_minggu' => $clean($request->tinja_2_minggu_nomor),
                'tanggal_1_bulan' => $clean($request->tinja_1_bulan_tanggal),
                'nomor_1_bulan' => $clean($request->tinja_1_bulan_nomor),
                'tanggal_2_4_bulan' => $clean($request->tinja_2_4_bulan_tanggal),
                'nomor_2_4_bulan' => $clean($request->tinja_2_4_bulan_nomor),
            ]
        );

        // 16. Kelas Balita
        for ($sesi = 1; $sesi <= 9; $sesi++) {
            $dataKia->absenKelasBalitas()->updateOrCreate(
                ['data_kia_id' => $dataKia->id, 'kehadiran_ke' => $sesi],
                [
                    'tanggal' => $clean($request->input("kelas_balita_tanggal_{$sesi}")),
                    'kader_info' => $clean($request->input("kelas_balita_kader_{$sesi}")),
                ]
            );
        }

        // 17. Perkembangan Bayi - Mingguan
        $mingguanBayiFields = [
            'sesak_napas', 'batuk', 'suhu_abnormal', 'bab_sering',
            'kencing_sedikit', 'kulit_biru', 'aktivitas_lemah',
            'hisapan_lemah', 'tidak_makan'
        ];
        for ($minggu = 1; $minggu <= 8; $minggu++) {
            $data = [];
            $colIndex = 1;
            foreach ($mingguanBayiFields as $field) {
                $data[$field] = $request->has("mingguan_bayi_{$minggu}_{$colIndex}");
                $colIndex++;
            }
            $data['paraf_kader_nakes'] = $clean($request->input("mingguan_bayi_paraf_{$minggu}"));
            $dataKia->pemantauanMingguanBayis()->updateOrCreate(
                ['data_kia_id' => $dataKia->id, 'minggu_ke' => $minggu],
                $data
            );
        }

        // Perkembangan Bayi 0-2 bulan
        $dataKia->perkembanganBayi()->updateOrCreate(
            ['data_kia_id' => $dataKia->id],
            [
                'angkat_kepala_45' => $request->has('bayi_angkat_kepala_45') ? ($request->bayi_angkat_kepala_45 === '1') : null,
                'gerak_kepala' => $request->has('bayi_gerakkan_kepala') ? ($request->bayi_gerakkan_kepala === '1') : null,
                'tatap_wajah' => $request->has('bayi_tatap_wajah') ? ($request->bayi_tatap_wajah === '1') : null,
                'ngoceh' => $request->has('bayi_ngoceh') ? ($request->bayi_ngoceh === '1') : null,
                'tertawa_keras' => $request->has('bayi_tertawa_keras') ? ($request->bayi_tertawa_keras === '1') : null,
                'terkejut_suara' => $request->has('bayi_terkejut_suara') ? ($request->bayi_terkejut_suara === '1') : null,
                'tersenyum' => $request->has('bayi_tersenyum') ? ($request->bayi_tersenyum === '1') : null,
                'mengenal_ibu' => $request->has('bayi_mengenal_ibu') ? ($request->bayi_mengenal_ibu === '1') : null,
            ]
        );

        // Perkembangan Bayi 3-6 bulan
        $dataKia->perkembanganBayi6Bulan()->updateOrCreate(
            ['data_kia_id' => $dataKia->id],
            [
                'berbalik' => $request->has('bayi_berbalik') ? ($request->bayi_berbalik === '1') : null,
                'kepala_tegak_90' => $request->has('bayi_kepala_tegak_90') ? ($request->bayi_kepala_tegak_90 === '1') : null,
                'kepala_stabil' => $request->has('bayi_kepala_stabil') ? ($request->bayi_kepala_stabil === '1') : null,
                'genggam_mainan' => $request->has('bayi_genggam_mainan') ? ($request->bayi_genggam_mainan === '1') : null,
                'raih_benda' => $request->has('bayi_raih_benda') ? ($request->bayi_raih_benda === '1') : null,
                'amati_tangan' => $request->has('bayi_amati_tangan') ? ($request->bayi_amati_tangan === '1') : null,
                'luas_pandang' => $request->has('bayi_luas_pandang') ? ($request->bayi_luas_pandang === '1') : null,
                'arah_mata' => $request->has('bayi_arah_mata') ? ($request->bayi_arah_mata === '1') : null,
                'suara_gembira' => $request->has('bayi_suara_gembira') ? ($request->bayi_suara_gembira === '1') : null,
                'senyum_mainan' => $request->has('bayi_senyum_mainan') ? ($request->bayi_senyum_mainan === '1') : null,
            ]
        );

        // 18. Pemantauan Bulanan Bayi (2-6 bulan)
        $bulananBayiFields = [
            'sesak_napas', 'batuk', 'suhu_abnormal', 'bab_sering',
            'kencing_sedikit', 'kulit_biru', 'aktivitas_lemah',
            'hisapan_lemah', 'tidak_makan'
        ];
        for ($bulan = 1; $bulan <= 6; $bulan++) {
            $data = [];
            $colIndex = 1;
            foreach ($bulananBayiFields as $field) {
                $data[$field] = $request->has("bulanan_bayi_{$bulan}_{$colIndex}");
                $colIndex++;
            }
            $data['paraf_kader_nakes'] = $clean($request->input("bulanan_bayi_paraf_{$bulan}"));
            $dataKia->pemantauanBulananBayis()->updateOrCreate(
                ['data_kia_id' => $dataKia->id, 'bulan_ke' => $bulan],
                $data
            );
        }

        // 19. Kesehatan Lingkungan
        $kesehatanLingkunganFields = [
            'bab_sembarangan', 'bab_jamban_sendiri', 'penampung_tangki_septik',
            'penampung_cubluk', 'penampung_drainase', 'kloset_leher_angsa',
            'ctps_sarana', 'ctps_air_mengalir', 'ctps_sabun',
            'ctps_sebelum_makan', 'ctps_sebelum_mengolah', 'ctps_sebelum_menyusui', 'ctps_setelah_bab',
            'sumber_air_pipa', 'sumber_air_kran', 'sumber_air_sumur', 'sumber_air_mata_air',
            'sumber_air_sungai', 'sumber_air_danau', 'sumber_air_hujan', 'sumber_air_kolam',
            'kelola_air_rebus', 'kelola_air_endap_saring', 'kelola_air_wadah_tertutup',
            'kelola_makanan_tertutup', 'kelola_makanan_jauh', 'kelola_makanan_baik',
            'sampah_tidak_berserakan', 'sampah_tempat_tertutup', 'sampah_dipilah', 'sampah_tidak_dibakar',
            'limbah_tidak_menggenang', 'limbah_saluran_tertutup', 'limbah_terhubung_resapan'
        ];
        $lingkunganData = [];
        foreach ($kesehatanLingkunganFields as $field) {
            // Checkbox unchecked = not in request = false, checked = 'on' = true
            $lingkunganData[$field] = $request->has($field) ? true : false;
        }
        $dataKia->kesehatanLingkungan()->updateOrCreate(
            ['data_kia_id' => $dataKia->id],
            $lingkunganData
        );

        // Check if AJAX request
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disimpan.'
            ]);
        }

        return redirect()->back()->with('success', 'Data berhasil disimpan.');
    }

    public function exportPdf($id)
    {
        $user = auth()->user();
        abort_unless($user, 403);

        $dataKia = DataKia::with(['ibu', 'suami', 'anak', 'layanan', 'riwayat', 'ttdTrackings', 'absenKelasIbuHamils', 'persiapanMelahirkan', 'pemantauanIbuNifas', 'keluargaBerencana', 'bayiBaruLahir', 'pemantauanBayis', 'warnaTinja', 'absenKelasBalitas', 'pemantauanMingguanBayis', 'perkembanganBayi', 'pemantauanBulananBayis', 'perkembanganBayi6Bulan', 'pemantauanBulananBayi12s', 'perkembanganBayi9Bulan', 'perkembanganBayi12Bulan', 'pemantauanBulananAnak24s', 'perkembanganBayi18Bulan', 'perkembanganBayi24Bulan', 'pemantauanBulananAnak72s', 'kesehatanLingkungan', 'pelayananKesehatanIbu', 'evaluasiKesehatanIbu', 'pemeriksaanUsgs'])
            ->findOrFail($id);

        // Pastikan relasi ...
        $dataKia->load(['ttdTrackings', 'pemantauanMingguans', 'absenKelasIbuHamils', 'persiapanMelahirkan', 'pemantauanIbuNifas', 'keluargaBerencana', 'bayiBaruLahir', 'pemantauanBayis', 'warnaTinja', 'absenKelasBalitas', 'pemantauanMingguanBayis', 'perkembanganBayi', 'pemantauanBulananBayis', 'perkembanganBayi6Bulan', 'pemantauanBulananBayi12s', 'perkembanganBayi9Bulan', 'perkembanganBayi12Bulan', 'pemantauanBulananAnak24s', 'perkembanganBayi18Bulan', 'perkembanganBayi24Bulan', 'pemantauanBulananAnak72s', 'kesehatanLingkungan', 'pelayananKesehatanIbu', 'evaluasiKesehatanIbu', 'pemeriksaanUsgs', 'pemeriksaanTrimester2', 'catatanPelayananTrimester2']);

        if ($user->role === 'pengguna') {
            abort_unless($dataKia->user_id === $user->id, 403);
        } else {
            abort_unless(in_array($user->role, ['admin', 'bidan', 'dokter']), 403);
        }

        $pdfService = new \App\Services\KiaPdfService();
        $pdfContent = $pdfService->generate($dataKia);

        return response($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="Buku_KIA_' . ($dataKia->ibu->nama ?? 'Identitas') . '.pdf"',
        ]);
    }

    public function indexNakes()
    {
        $role = auth()->user()->role;
        $dataKias = DataKia::with(['ibu'])->latest()->get();

        return view('nakes.kia-index', compact('dataKias', 'role'));
    }

    public function editRiwayat($id)
    {
        $role = auth()->user()->role;
        $dataKia = DataKia::with(['ibu', 'riwayat'])->findOrFail($id);

        return view('nakes.kia-edit-riwayat', compact('dataKia', 'role'));
    }

    public function saveRiwayat(Request $request, $id)
    {
        $dataKia = DataKia::findOrFail($id);

        $clean = function ($val) {
            return $val === '' ? null : $val;
        };

        $dataKia->riwayat()->updateOrCreate(
            ['data_kia_id' => $dataKia->id],
            [
                'usia_ibu' => $clean($request->usia_ibu),
                'kehamilan_ke' => $clean($request->kehamilan_ke),
                'jumlah_anak_hidup' => $clean($request->jumlah_anak_lahir_hidup),
                'riwayat_keguguran' => $clean($request->riwayat_keguguran),
                'riwayat_penyakit_ibu' => $clean($request->riwayat_penyakit_ibu),
            ]
        );

        $role = auth()->user()->role;
        return redirect()->route($role . '.kia')->with('success', 'Riwayat kesehatan berhasil diperbarui.');
    }

    public function editPelayanan($id)
    {
        $role = auth()->user()->role;
        $dataKia = DataKia::with(['ibu', 'pelayananKesehatanIbu'])->findOrFail($id);
        $pelayanan = $dataKia->pelayananKesehatanIbu->keyBy('kunjungan_ke');

        return view('nakes.kia-edit-pelayanan', compact('dataKia', 'role', 'pelayanan'));
    }

    public function savePelayanan(Request $request, $id)
    {
        $dataKia = DataKia::findOrFail($id);

        $kunjunganKe = $request->kunjungan_ke;
        $tripelCombined = null;
        if ($request->has('tripel_eliminasi_h') || $request->has('tripel_eliminasi_s') || $request->has('tripel_eliminasi_hep_b')) {
            $tripelCombined = ($request->tripel_eliminasi_h ?? '') . ',' . ($request->tripel_eliminasi_s ?? '') . ',' . ($request->tripel_eliminasi_hep_b ?? '');
            if ($tripelCombined === ',,')
                $tripelCombined = null;
        }

        $dataKia->pelayananKesehatanIbu()->updateOrCreate(
            [
                'data_kia_id' => $dataKia->id,
                'kunjungan_ke' => $kunjunganKe,
            ],
            [
                'tanggal_periksa' => $request->tanggal_periksa,
                'tempat_periksa' => $request->tempat_periksa,
                'berat_badan' => $request->berat_badan,
                'tinggi_badan' => $request->tinggi_badan,
                'lingkar_lengan_atas' => $request->lingkar_lengan_atas,
                'tekanan_darah' => $request->tekanan_darah,
                'tinggi_rahim' => $request->tinggi_rahim,
                'letak_janin' => $request->letak_janin,
                'denyut_jantung_bayi' => $request->denyut_jantung_bayi,
                'status_imunisasi_tt' => $request->status_imunisasi_tt,
                'konseling' => $request->konseling,
                'skrining_dokter' => $request->skrining_dokter,
                'tablet_tambah_darah' => $request->tablet_tambah_darah,
                'tes_lab_hb' => $request->tes_lab_hb,
                'tes_golongan_darah' => $request->tes_golongan_darah,
                'tes_lab_protein_urine' => $request->tes_lab_protein_urine,
                'tes_lab_gula_darah' => $request->tes_lab_gula_darah,
                'usg' => $request->usg,
                'tripel_eliminasi' => $tripelCombined,
                'tata_laksana_kasus' => $request->tata_laksana_kasus,
            ]
        );

        return back()->with('success', 'Pelayanan Kesehatan Ibu kunjungan ke-' . $kunjunganKe . ' berhasil disimpan.')->with('active_tab', 'kunjungan' . $kunjunganKe);
    }

    public function editEvaluasi($id)
    {
        $user = auth()->user();
        abort_unless(in_array($user->role, ['bidan', 'dokter']), 403);

        $dataKia = DataKia::with(['evaluasiKesehatanIbu', 'ibu', 'pemeriksaanTrimester1'])->findOrFail($id);
        $evaluasi = $dataKia->evaluasiKesehatanIbu;
        $pemeriksaan = $dataKia->pemeriksaanTrimester1 ?? new \App\Models\KiaPemeriksaanTrimester1();

        return view('nakes.kia-edit-evaluasi', [
            'dataKia' => $dataKia,
            'evaluasi' => $evaluasi,
            'pemeriksaan' => $pemeriksaan,
            'role' => $user->role,
        ]);
    }

    public function saveEvaluasi(Request $request, $id)
    {
        $user = auth()->user();
        abort_unless(in_array($user->role, ['bidan', 'dokter']), 403);

        $dataKia = DataKia::findOrFail($id);

        $dataKia->evaluasiKesehatanIbu()->updateOrCreate(
            ['data_kia_id' => $dataKia->id],
            [
                'nama_dokter' => $request->nama_dokter,
                'tanggal_periksa' => $request->tanggal_periksa,
                'fasilitas_kesehatan' => $request->fasilitas_kesehatan,
                'tb' => $request->tb,
                'bb' => $request->bb,
                'imt' => $request->imt,
                'lila' => $request->lila,
                'lila_kurus' => $request->lila_kurus,
                'lila_normal' => $request->lila_normal,
                'lila_gemuk' => $request->lila_gemuk,
                'lila_obesitas' => $request->lila_obesitas,
                'imunisasi_tt_1' => $request->has('imunisasi_tt_1'),
                'imunisasi_tt_2' => $request->has('imunisasi_tt_2'),
                'imunisasi_tt_3' => $request->has('imunisasi_tt_3'),
                'imunisasi_tt_4' => $request->has('imunisasi_tt_4'),
                'imunisasi_tt_5' => $request->has('imunisasi_tt_5'),
                'riwayat_kesehatan_ibu' => $request->riwayat_kesehatan_ibu ?? [],
                'riwayat_kesehatan_ibu_lainnya' => $request->riwayat_kesehatan_ibu_lainnya,
                'riwayat_perilaku' => $request->riwayat_perilaku ?? [],
                'riwayat_perilaku_lainnya' => $request->riwayat_perilaku_lainnya,
                'riwayat_penyakit_keluarga' => $request->riwayat_penyakit_keluarga ?? [],
                'riwayat_penyakit_keluarga_lainnya' => $request->riwayat_penyakit_keluarga_lainnya,
                'inspeksi_porsio' => $request->inspeksi_porsio,
                'inspeksi_uretra' => $request->inspeksi_uretra,
                'inspeksi_vagina' => $request->inspeksi_vagina,
                'inspeksi_vulva' => $request->inspeksi_vulva,
                'inspeksi_fluksus' => $request->inspeksi_fluksus,
                'inspeksi_fluor' => $request->inspeksi_fluor,
                'riwayat_kehamilan' => $request->riwayat_kehamilan ?? [],
            ]
        );

        $pemeriksaanData = $request->only([
            'konjungtiva',
            'sklera',
            'kulit',
            'leher',
            'gigi_mulut',
            'tht',
            'dada_jantung',
            'dada_paru',
            'perut',
            'tungkai',
            'keterangan_konjungtiva',
            'keterangan_sklera',
            'keterangan_kulit',
            'keterangan_leher',
            'keterangan_gigi_mulut',
            'keterangan_tht',
            'keterangan_dada_jantung',
            'keterangan_dada_paru',
            'keterangan_perut',
            'keterangan_tungkai',
            'hpht',
            'keteraturan_haid',
            'usia_kehamilan_hpht',
            'hpl_hpht',
            'usia_kehamilan_usg',
            'hpl_usg',
            'jumlah_gs',
            'diameter_gs_cm',
            'diameter_gs_minggu',
            'diameter_gs_hari',
            'jumlah_bayi',
            'crl_cm',
            'crl_minggu',
            'crl_hari',
            'letak_produk_kehamilan',
            'pulsasi_jantung',
            'kecurigaan_temuan_abnormal',
            'kecurigaan_temuan_abnormal_sebutkan'
        ]);

        if (array_filter($pemeriksaanData)) {
            $pemeriksaanModel = $dataKia->pemeriksaanTrimester1 ?? new \App\Models\KiaPemeriksaanTrimester1();
            $pemeriksaanModel->data_kia_id = $dataKia->id;
            $pemeriksaanModel->fill($pemeriksaanData);
            $pemeriksaanModel->save();
        }

        return redirect()->route($user->role . '.kia')->with('success', 'Data Evaluasi Halaman 51 berhasil disimpan!');
    }

    public function editTrimester1($id)
    {
        $user = auth()->user();
        abort_unless(in_array($user->role, ['bidan', 'dokter']), 403);

        $dataKia = DataKia::with(['pemeriksaanTrimester1', 'catatanPelayananTrimester1', 'ibu'])->findOrFail($id);
        $pemeriksaan = $dataKia->pemeriksaanTrimester1 ?? new \App\Models\KiaPemeriksaanTrimester1();
        $catatan = $dataKia->catatanPelayananTrimester1;

        return view('nakes.kia-edit-trimester1', [
            'dataKia' => $dataKia,
            'pemeriksaan' => $pemeriksaan,
            'catatan' => $catatan,
            'role' => $user->role,
        ]);
    }

    public function saveTrimester1(Request $request, $id)
    {
        $user = auth()->user();
        abort_unless(in_array($user->role, ['bidan', 'dokter']), 403);

        $dataKia = DataKia::findOrFail($id);

        $request->validate([
            // Validasi pemeriksaan
            'gambar_usg' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'tgl_periksa_lab' => 'nullable|date',
            'lab_hemoglobin' => 'nullable|string',
            'lab_gol_darah' => 'nullable|string',
            'lab_gula_darah' => 'nullable|string',
            'lab_tripel_h' => 'nullable|in:Reaktif,Non reaktif',
            'lab_tripel_s' => 'nullable|in:Reaktif,Non reaktif',
            'lab_tripel_hep_b' => 'nullable|in:Reaktif,Non reaktif',
            'tgl_skrining_jiwa' => 'nullable|date',
            'skrining_jiwa' => 'nullable|in:Ya,Tidak',
            'tindak_lanjut_jiwa' => 'nullable|in:Edukasi,Konseling',
            'rujukan_jiwa' => 'nullable|in:Ya,Tidak',
            'kesimpulan' => 'nullable|string',
            'rekomendasi' => 'nullable|string',

            // Validasi catatan
            'catatan.*.id' => 'nullable|integer',
            'catatan.*.tanggal_periksa' => 'nullable|date',
            'catatan.*.catatan' => 'nullable|string',
            'catatan.*.tanggal_kembali' => 'nullable|date',
            'deleted_catatan' => 'nullable|string'
        ]);

        // Simpan pemeriksaan trimester 1
        $pemeriksaanData = $request->only([
            'tgl_periksa_lab',
            'lab_hemoglobin',
            'lab_gol_darah',
            'lab_gula_darah',
            'lab_tripel_h',
            'lab_tripel_s',
            'lab_tripel_hep_b',
            'tgl_skrining_jiwa',
            'skrining_jiwa',
            'tindak_lanjut_jiwa',
            'rujukan_jiwa',
            'kesimpulan',
            'rekomendasi',
        ]);

        $pemeriksaanModel = $dataKia->pemeriksaanTrimester1 ?? new \App\Models\KiaPemeriksaanTrimester1();
        $pemeriksaanModel->data_kia_id = $dataKia->id;
        $pemeriksaanModel->fill($pemeriksaanData);

        if ($request->hasFile('gambar_usg')) {
            $file = $request->file('gambar_usg');
            // Gunakan disk 'public' agar tersimpan di storage/app/public/usg_images
            $path = $file->store('usg_images', 'public');
            if ($path) {
                // Simpan path relatif yang bisa diakses (misal: storage/usg_images/nama.jpg)
                $pemeriksaanModel->gambar_usg = 'storage/' . $path;
            }
        }

        $pemeriksaanModel->save();

        // Delete removed catatan records
        if ($request->filled('deleted_catatan')) {
            $deletedIds = explode(',', $request->deleted_catatan);
            \App\Models\KiaCatatanPelayananTrimester1::whereIn('id', $deletedIds)->where('data_kia_id', $dataKia->id)->delete();
        }

        // Add or Update catatan records
        if ($request->has('catatan')) {
            foreach ($request->catatan as $cat) {
                $catatanModel = isset($cat['id']) ? \App\Models\KiaCatatanPelayananTrimester1::find($cat['id']) : new \App\Models\KiaCatatanPelayananTrimester1();
                $catatanModel->data_kia_id = $dataKia->id;
                $catatanModel->tanggal_periksa = $cat['tanggal_periksa'] ?? null;
                $catatanModel->catatan = $cat['catatan'] ?? null;
                $catatanModel->tanggal_kembali = $cat['tanggal_kembali'] ?? null;
                $catatanModel->save();
            }
        }

        return redirect()->route($user->role . '.kia')->with('success', 'Data Trimester 1 (Halaman 52-53) berhasil disimpan!');
    }
    public function editTrimester2($id)
    {
        $user = auth()->user();
        abort_unless(in_array($user->role, ['bidan', 'dokter']), 403);

        $dataKia = DataKia::with(['pemeriksaanTrimester2', 'catatanPelayananTrimester2', 'ibu'])->findOrFail($id);
        $pemeriksaan = $dataKia->pemeriksaanTrimester2 ?? new \App\Models\KiaPemeriksaanTrimester2();
        $catatan = $dataKia->catatanPelayananTrimester2;

        return view('nakes.kia-edit-trimester2', [
            'dataKia' => $dataKia,
            'pemeriksaan' => $pemeriksaan,
            'catatan' => $catatan,
            'role' => $user->role,
        ]);
    }

    public function saveTrimester2(Request $request, $id)
    {
        $user = auth()->user();
        abort_unless(in_array($user->role, ['bidan', 'dokter']), 403);

        $dataKia = DataKia::findOrFail($id);

        $request->validate([
            'skrining_preeklampsia' => 'nullable|array',
            'kesimpulan_preeklampsia' => 'nullable|string',
            'lab_gula_darah_puasa' => 'nullable|string',
            'tindak_lanjut_puasa' => 'nullable|string',
            'lab_gula_darah_2jam' => 'nullable|string',
            'tindak_lanjut_2jam' => 'nullable|string',
            'tgl_periksa_diabetes' => 'nullable|date',
            'nama_dokter_diabetes' => 'nullable|string',
            'catatan.*.id' => 'nullable|integer',
            'catatan.*.tanggal_periksa' => 'nullable|date',
            'catatan.*.catatan' => 'nullable|string',
            'catatan.*.tanggal_kembali' => 'nullable|date',
            'deleted_catatan' => 'nullable|string'
        ]);

        $pemeriksaanModel = $dataKia->pemeriksaanTrimester2 ?? new \App\Models\KiaPemeriksaanTrimester2();
        $pemeriksaanModel->data_kia_id = $dataKia->id;
        $pemeriksaanModel->skrining_preeklampsia = $request->skrining_preeklampsia;
        $pemeriksaanModel->kesimpulan_preeklampsia = $request->kesimpulan_preeklampsia;
        $pemeriksaanModel->lab_gula_darah_puasa = $request->lab_gula_darah_puasa;
        $pemeriksaanModel->tindak_lanjut_puasa = $request->tindak_lanjut_puasa;
        $pemeriksaanModel->lab_gula_darah_2jam = $request->lab_gula_darah_2jam;
        $pemeriksaanModel->tindak_lanjut_2jam = $request->tindak_lanjut_2jam;
        $pemeriksaanModel->tgl_periksa_diabetes = $request->tgl_periksa_diabetes;
        $pemeriksaanModel->nama_dokter_diabetes = $request->nama_dokter_diabetes;
        $pemeriksaanModel->save();

        if ($request->filled('deleted_catatan')) {
            $deletedIds = explode(',', $request->deleted_catatan);
            \App\Models\KiaCatatanPelayananTrimester2::whereIn('id', $deletedIds)->where('data_kia_id', $dataKia->id)->delete();
        }

        if ($request->has('catatan')) {
            foreach ($request->catatan as $cat) {
                $catatanModel = isset($cat['id']) ? \App\Models\KiaCatatanPelayananTrimester2::find($cat['id']) : new \App\Models\KiaCatatanPelayananTrimester2();
                $catatanModel->data_kia_id = $dataKia->id;
                $catatanModel->tanggal_periksa = $cat['tanggal_periksa'] ?? null;
                $catatanModel->catatan = $cat['catatan'] ?? null;
                $catatanModel->tanggal_kembali = $cat['tanggal_kembali'] ?? null;
                $catatanModel->save();
            }
        }

        return redirect()->route($user->role . '.kia')->with('success', 'Data Trimester 2 (Halaman 53) berhasil disimpan!');
    }

    public function ttdIndex()
    {
        abort_unless(auth()->check() && auth()->user()->role === 'pengguna', 403);

        $dataKia = DataKia::with('ttdTrackings')->firstOrCreate(['user_id' => auth()->id()]);
        $trackings = $dataKia->ttdTrackings->keyBy('bulan_ke');

        return view('pengguna.kia-ttd', compact('dataKia', 'trackings'));
    }

    public function ttdStore(Request $request)
    {
        abort_unless(auth()->check() && auth()->user()->role === 'pengguna', 403);
        $dataKia = DataKia::firstOrCreate(['user_id' => auth()->id()]);

        $bulanKe = $request->bulan_ke;
        $data = [
            'usia_kehamilan' => $request->usia_kehamilan,
            'bulan_tahun' => $request->bulan_tahun,
        ];

        for ($i = 1; $i <= 31; $i++) {
            $data["h$i"] = $request->has("h$i");
        }

        $dataKia->ttdTrackings()->updateOrCreate(
            ['bulan_ke' => $bulanKe],
            $data
        );

        return back()->with('success', 'Catatan minum TTD bulan ke-' . $bulanKe . ' berhasil disimpan.');
    }

    public function pemantauanIndex()
    {
        abort_unless(auth()->check() && auth()->user()->role === 'pengguna', 403);

        $dataKia = DataKia::with('pemantauanMingguans')->firstOrCreate(['user_id' => auth()->id()]);
        $pemantauans = $dataKia->pemantauanMingguans->keyBy('minggu_ke');

        return view('pengguna.kia-pemantauan', compact('dataKia', 'pemantauans'));
    }

    public function pemantauanStore(Request $request)
    {
        abort_unless(auth()->check() && auth()->user()->role === 'pengguna', 403);
        $dataKia = DataKia::firstOrCreate(['user_id' => auth()->id()]);

        $mingguKe = intval($request->minggu_ke);

        $fields = [
            'pemeriksaan_kehamilan',
            'kelas_ibu_hamil',
            'demam_lebih_2_hari',
            'pusing_sakit_kepala',
            'sulit_tidur_cemas',
            'risiko_tb',
            'gerakan_bayi',
            'nyeri_perut_hebat',
            'keluar_cairan_lahir',
            'sakit_saat_kencing',
            'diare_berulang',
        ];

        $data = [];
        foreach ($fields as $field) {
            $data[$field] = $request->has($field);
        }

        $dataKia->pemantauanMingguans()->updateOrCreate(
            ['minggu_ke' => $mingguKe],
            $data
        );

        return back()->with('success', 'Catatan pemantauan minggu ke-' . $mingguKe . ' berhasil disimpan.');
    }

    public function kelasIbuIndex()
    {
        $userId = auth()->id();
        $dataKia = DataKia::with('absenKelasIbuHamils')->where('user_id', $userId)->first();

        if (!$dataKia) {
            return redirect()->route('pengguna.buku_kia')->with('info', 'Silakan lengkapi screening Buku KIA terlebih dahulu.');
        }

        $absen = $dataKia->absenKelasIbuHamils->keyBy('kehadiran_ke');

        return view('pengguna.kia-kelas-ibu', compact('dataKia', 'absen'));
    }

    public function kelasIbuStore(Request $request)
    {
        $request->validate([
            'kehadiran_ke' => 'required|integer|between:1,9',
            'tanggal' => 'nullable|string|max:100',
            'kader_info' => 'nullable|string|max:255',
        ]);

        $userId = auth()->id();
        $dataKia = DataKia::where('user_id', $userId)->firstOrFail();

        $dataKia->absenKelasIbuHamils()->updateOrCreate(
            ['kehadiran_ke' => $request->kehadiran_ke],
            [
                'tanggal' => $request->tanggal,
                'kader_info' => $request->kader_info,
            ]
        );

        return back()->with('success', 'Data absensi kelas ibu hamil ke-' . $request->kehadiran_ke . ' berhasil disimpan.');
    }

    public function persiapanIndex()
    {
        $userId = auth()->id();
        $dataKia = DataKia::with('persiapanMelahirkan')->where('user_id', $userId)->first();

        if (!$dataKia) {
            return redirect()->route('pengguna.buku_kia')->with('info', 'Silakan lengkapi screening Buku KIA terlebih dahulu.');
        }

        $persiapan = $dataKia->persiapanMelahirkan;

        return view('pengguna.kia-persiapan', compact('dataKia', 'persiapan'));
    }

    public function persiapanStore(Request $request)
    {
        $userId = auth()->id();
        $dataKia = DataKia::where('user_id', $userId)->firstOrFail();

        $fields = [
            'tanya_tanggal_perkiraan',
            'minta_dampingi',
            'siap_tabungan',
            'kartu_jkn',
            'tempat_melahirkan',
            'siap_ktp_kk',
            'siap_pendonor',
            'siap_kendaraan',
            'sepakat_stiker_p4k',
            'rencana_kb',
        ];

        $data = [];
        foreach ($fields as $field) {
            $data[$field] = $request->has($field);
        }

        $data['hpl_tanggal'] = $request->hpl_tanggal;
        $data['hpl_bulan'] = $request->hpl_bulan;
        $data['hpl_tahun'] = $request->hpl_tahun;
        $data['metode_kb'] = $request->metode_kb;

        $dataKia->persiapanMelahirkan()->updateOrCreate(
            ['data_kia_id' => $dataKia->id],
            $data
        );

        return back()->with('success', 'Persiapan melahirkan berhasil disimpan.');
    }


    public function nifasIndex()
    {
        $userId = auth()->id();
        $dataKia = DataKia::with('pemantauanIbuNifas')->where('user_id', $userId)->first();

        if (!$dataKia) {
            return redirect()->route('pengguna.buku_kia')->with('info', 'Silakan lengkapi screening Buku KIA terlebih dahulu.');
        }

        $records = $dataKia->pemantauanIbuNifas->keyBy('hari_ke');

        return view('pengguna.kia-pemantauan-nifas', compact('dataKia', 'records'));
    }

    public function nifasStore(Request $request)
    {
        $request->validate([
            'hari_ke' => 'required|integer|between:1,42',
            'paraf_kader_nakes' => 'nullable|string|max:100',
        ]);

        $userId = auth()->id();
        $dataKia = DataKia::where('user_id', $userId)->firstOrFail();

        $fields = [
            'pemeriksaan_nifas',
            'konsumsi_vitamin_a',
            'konsumsi_ttd',
            'pemenuhan_gizi',
            'masalah_jiwa',
            'demam',
            'sakit_kepala',
            'pandangan_kabur',
            'nyeri_ulu_hati',
            'jantung_berdebar',
            'keluar_cairan_lahir',
            'napas_pendek',
            'payudara_bengkak',
            'gangguan_bak',
            'kelamin_bengkak',
            'darah_nifas_berbau',
            'pendarahan_hebat',
            'keputihan',
        ];

        $data = [];
        foreach ($fields as $field) {
            $data[$field] = $request->has($field);
        }

        $data['paraf_kader_nakes'] = $request->paraf_kader_nakes;

        $dataKia->pemantauanIbuNifas()->updateOrCreate(
            ['hari_ke' => $request->hari_ke],
            $data
        );

        return back()->with('success', 'Catatan pemantauan ibu nifas hari ke-' . $request->hari_ke . ' berhasil disimpan.');
    }

    public function kbIndex()
    {
        $userId = auth()->id();
        $dataKia = DataKia::with('keluargaBerencana')->where('user_id', $userId)->first();

        if (!$dataKia) {
            return redirect()->route('pengguna.buku_kia')->with('info', 'Silakan lengkapi screening Buku KIA terlebih dahulu.');
        }

        $kb = $dataKia->keluargaBerencana;

        return view('pengguna.kia-kb', compact('dataKia', 'kb'));
    }

    public function kbStore(Request $request)
    {
        $userId = auth()->id();
        $dataKia = DataKia::where('user_id', $userId)->firstOrFail();

        $dataKia->keluargaBerencana()->updateOrCreate(
            ['data_kia_id' => $dataKia->id],
            ['paraf_ibu' => $request->paraf_ibu]
        );

        return back()->with('success', 'Catatan rencana Keluarga Berencana (KB) berhasil disimpan.');
    }

    public function bayiIndex()
    {
        $userId = auth()->id();
        $dataKia = DataKia::with('bayiBaruLahir')->where('user_id', $userId)->first();

        if (!$dataKia) {
            return redirect()->route('pengguna.buku_kia')->with('info', 'Silakan lengkapi screening Buku KIA terlebih dahulu.');
        }

        $bayi = $dataKia->bayiBaruLahir;

        return view('pengguna.kia-bayi', compact('dataKia', 'bayi'));
    }

    public function bayiStore(Request $request)
    {
        $userId = auth()->id();
        $dataKia = DataKia::where('user_id', $userId)->firstOrFail();

        $dataKia->bayiBaruLahir()->updateOrCreate(
            ['data_kia_id' => $dataKia->id],
            [
                'jam_0_6' => $request->has('jam_0_6'),
                'jam_6_48' => $request->has('jam_6_48'),
                'hari_3_7' => $request->has('hari_3_7'),
                'hari_8_28' => $request->has('hari_8_28'),
            ]
        );

        return back()->with('success', 'Ceklist pemeriksaan bayi baru lahir berhasil disimpan.');
    }

    public function pemantauanBayiIndex()
    {
        $userId = auth()->id();
        $dataKia = DataKia::with('pemantauanBayis')->where('user_id', $userId)->first();

        if (!$dataKia) {
            return redirect()->route('pengguna.buku_kia')->with('info', 'Silakan lengkapi screening Buku KIA terlebih dahulu.');
        }

        $pemantauans = $dataKia->pemantauanBayis->keyBy('hari_ke');

        return view('pengguna.kia-pemantauan-bayi', compact('dataKia', 'pemantauans'));
    }

    public function pemantauanBayiStore(Request $request)
    {
        $userId = auth()->id();
        $dataKia = DataKia::where('user_id', $userId)->firstOrFail();

        $data = [
            'sesak_napas' => $request->has('sesak_napas'),
            'aktivitas_lemah' => $request->has('aktivitas_lemah'),
            'warna_kulit_biru' => $request->has('warna_kulit_biru'),
            'hisapan_lemah' => $request->has('hisapan_lemah'),
            'kejang' => $request->has('kejang'),
            'suhu_abnormal' => $request->has('suhu_abnormal'),
            'bab_abnormal' => $request->has('bab_abnormal'),
            'kencing_sedikit' => $request->has('kencing_sedikit'),
            'tali_pusat_merah' => $request->has('tali_pusat_merah'),
            'mata_merah' => $request->has('mata_merah'),
            'kulit_bintil' => $request->has('kulit_bintil'),
            'belum_imunisasi' => $request->has('belum_imunisasi'),
        ];

        if ($request->has('paraf_kader_nakes')) {
            $data['paraf_kader_nakes'] = $request->paraf_kader_nakes;
        }

        $dataKia->pemantauanBayis()->updateOrCreate(
            ['data_kia_id' => $dataKia->id, 'hari_ke' => $request->hari_ke],
            $data
        );

        return back()->with('success', 'Catatan pemantauan harian bayi hari ke-' . $request->hari_ke . ' berhasil disimpan.');
    }

    public function warnaTinjaIndex()
    {
        $userId = auth()->id();
        $dataKia = DataKia::with('warnaTinja')->where('user_id', $userId)->first();

        if (!$dataKia) {
            return redirect()->route('pengguna.buku_kia')->with('info', 'Silakan lengkapi screening Buku KIA terlebih dahulu.');
        }

        return view('pengguna.kia-warna-tinja', compact('dataKia'));
    }

    public function warnaTinjaStore(Request $request)
    {
        $userId = auth()->id();
        $dataKia = DataKia::where('user_id', $userId)->firstOrFail();

        $dataKia->warnaTinja()->updateOrCreate(
            ['data_kia_id' => $dataKia->id],
            [
                'tanggal_2_minggu' => $request->tanggal_2_minggu,
                'nomor_2_minggu' => $request->nomor_2_minggu,
                'tanggal_1_bulan' => $request->tanggal_1_bulan,
                'nomor_1_bulan' => $request->nomor_1_bulan,
                'tanggal_2_4_bulan' => $request->tanggal_2_4_bulan,
                'nomor_2_4_bulan' => $request->nomor_2_4_bulan,
            ]
        );

        return back()->with('success', 'Pemantauan warna tinja bayi berhasil disimpan.');
    }

    public function kelasBalitaIndex()
    {
        $userId = auth()->id();
        $dataKia = DataKia::with('absenKelasBalitas')->where('user_id', $userId)->first();

        if (!$dataKia) {
            return redirect()->route('pengguna.buku_kia')->with('info', 'Silakan lengkapi screening Buku KIA terlebih dahulu.');
        }

        $absensi = $dataKia->absenKelasBalitas->keyBy('kehadiran_ke');

        return view('pengguna.kia-kelas-balita', compact('dataKia', 'absensi'));
    }

    public function kelasBalitaStore(Request $request)
    {
        $userId = auth()->id();
        $dataKia = DataKia::where('user_id', $userId)->firstOrFail();

        $dataKia->absenKelasBalitas()->updateOrCreate(
            ['data_kia_id' => $dataKia->id, 'kehadiran_ke' => $request->kehadiran_ke],
            [
                'tanggal' => $request->tanggal,
                'kader_info' => $request->kader_info,
            ]
        );

        return back()->with('success', 'Absensi kehadiran kelas ibu balita sesi ke-' . $request->kehadiran_ke . ' berhasil disimpan.');
    }

    public function pemantauanMingguanBayiIndex()
    {
        $userId = auth()->id();
        $dataKia = DataKia::with(['pemantauanMingguanBayis', 'perkembanganBayi'])->where('user_id', $userId)->first();

        if (!$dataKia) {
            return redirect()->route('pengguna.buku_kia')->with('info', 'Silakan lengkapi screening Buku KIA terlebih dahulu.');
        }

        $mingguan = $dataKia->pemantauanMingguanBayis->keyBy('minggu_ke');
        $perkembangan = $dataKia->perkembanganBayi;

        return view('pengguna.kia-mingguan-bayi', compact('dataKia', 'mingguan', 'perkembangan'));
    }

    public function pemantauanMingguanBayiStore(Request $request)
    {
        $userId = auth()->id();
        $dataKia = DataKia::where('user_id', $userId)->firstOrFail();

        $data = [
            'sesak_napas' => $request->has('sesak_napas'),
            'batuk' => $request->has('batuk'),
            'suhu_abnormal' => $request->has('suhu_abnormal'),
            'bab_sering' => $request->has('bab_sering'),
            'kencing_sedikit' => $request->has('kencing_sedikit'),
            'kulit_biru' => $request->has('kulit_biru'),
            'aktivitas_lemah' => $request->has('aktivitas_lemah'),
            'hisapan_lemah' => $request->has('hisapan_lemah'),
            'tidak_makan' => $request->has('tidak_makan'),
        ];

        if ($request->has('paraf_kader_nakes')) {
            $data['paraf_kader_nakes'] = $request->paraf_kader_nakes;
        }

        $dataKia->pemantauanMingguanBayis()->updateOrCreate(
            ['data_kia_id' => $dataKia->id, 'minggu_ke' => $request->minggu_ke],
            $data
        );

        return back()->with('success', 'Catatan pemantauan mingguan bayi minggu ke-' . $request->minggu_ke . ' berhasil disimpan.')
            ->with('active_tab', 'mingguan')
            ->with('active_week', $request->minggu_ke);
    }

    public function perkembanganBayiStore(Request $request)
    {
        $userId = auth()->id();
        $dataKia = DataKia::where('user_id', $userId)->firstOrFail();

        $dataKia->perkembanganBayi()->updateOrCreate(
            ['data_kia_id' => $dataKia->id],
            [
                'angkat_kepala_45' => $request->has('angkat_kepala_45') ? ($request->angkat_kepala_45 === '1') : null,
                'gerak_kepala' => $request->has('gerak_kepala') ? ($request->gerak_kepala === '1') : null,
                'tatap_wajah' => $request->has('tatap_wajah') ? ($request->tatap_wajah === '1') : null,
                'ngoceh' => $request->has('ngoceh') ? ($request->ngoceh === '1') : null,
                'tertawa_keras' => $request->has('tertawa_keras') ? ($request->tertawa_keras === '1') : null,
                'terkejut_suara' => $request->has('terkejut_suara') ? ($request->terkejut_suara === '1') : null,
                'tersenyum' => $request->has('tersenyum') ? ($request->tersenyum === '1') : null,
                'mengenal_ibu' => $request->has('mengenal_ibu') ? ($request->mengenal_ibu === '1') : null,
            ]
        );

        return back()->with('success', 'Checklist perkembangan bayi berhasil disimpan.')
            ->with('active_tab', 'perkembangan');
    }

    public function pemantauanBulananBayiIndex()
    {
        $userId = auth()->id();
        $dataKia = DataKia::with(['pemantauanBulananBayis', 'perkembanganBayi6Bulan'])->where('user_id', $userId)->first();

        if (!$dataKia) {
            return redirect()->route('pengguna.buku_kia')->with('info', 'Silakan lengkapi screening Buku KIA terlebih dahulu.');
        }

        $bulanan = $dataKia->pemantauanBulananBayis->keyBy('bulan_ke');
        $perkembangan = $dataKia->perkembanganBayi6Bulan;

        return view('pengguna.kia-bulanan-bayi', compact('dataKia', 'bulanan', 'perkembangan'));
    }

    public function pemantauanBulananBayiStore(Request $request)
    {
        $userId = auth()->id();
        $dataKia = DataKia::where('user_id', $userId)->firstOrFail();

        $data = [
            'sesak_napas' => $request->has('sesak_napas'),
            'batuk' => $request->has('batuk'),
            'suhu_abnormal' => $request->has('suhu_abnormal'),
            'bab_sering' => $request->has('bab_sering'),
            'kencing_sedikit' => $request->has('kencing_sedikit'),
            'kulit_biru' => $request->has('kulit_biru'),
            'aktivitas_lemah' => $request->has('aktivitas_lemah'),
            'hisapan_lemah' => $request->has('hisapan_lemah'),
            'tidak_makan' => $request->has('tidak_makan'),
        ];

        if ($request->has('paraf_kader_nakes')) {
            $data['paraf_kader_nakes'] = $request->paraf_kader_nakes;
        }

        $dataKia->pemantauanBulananBayis()->updateOrCreate(
            ['data_kia_id' => $dataKia->id, 'bulan_ke' => $request->bulan_ke],
            $data
        );

        return back()->with('success', 'Catatan pemantauan bulanan bayi bulan ke-' . $request->bulan_ke . ' berhasil disimpan.')
            ->with('active_tab', 'bulanan')
            ->with('active_month', $request->bulan_ke);
    }

    public function perkembanganBayi6BulanStore(Request $request)
    {
        $userId = auth()->id();
        $dataKia = DataKia::where('user_id', $userId)->firstOrFail();

        $dataKia->perkembanganBayi6Bulan()->updateOrCreate(
            ['data_kia_id' => $dataKia->id],
            [
                'berbalik' => $request->has('berbalik') ? ($request->berbalik === '1') : null,
                'kepala_tegak_90' => $request->has('kepala_tegak_90') ? ($request->kepala_tegak_90 === '1') : null,
                'kepala_stabil' => $request->has('kepala_stabil') ? ($request->kepala_stabil === '1') : null,
                'genggam_mainan' => $request->has('genggam_mainan') ? ($request->genggam_mainan === '1') : null,
                'raih_benda' => $request->has('raih_benda') ? ($request->raih_benda === '1') : null,
                'amati_tangan' => $request->has('amati_tangan') ? ($request->amati_tangan === '1') : null,
                'luas_pandang' => $request->has('luas_pandang') ? ($request->luas_pandang === '1') : null,
                'arah_mata' => $request->has('arah_mata') ? ($request->arah_mata === '1') : null,
                'suara_gembira' => $request->has('suara_gembira') ? ($request->suara_gembira === '1') : null,
                'senyum_mainan' => $request->has('senyum_mainan') ? ($request->senyum_mainan === '1') : null,
            ]
        );

        return back()->with('success', 'Checklist perkembangan bayi umur 3-6 bulan berhasil disimpan.')
            ->with('active_tab', 'perkembangan');
    }

    public function pemantauanBulananBayi12Index()
    {
        $userId = auth()->id();
        $dataKia = DataKia::with(['pemantauanBulananBayi12s', 'perkembanganBayi9Bulan', 'perkembanganBayi12Bulan'])->where('user_id', $userId)->first();

        if (!$dataKia) {
            return redirect()->route('pengguna.buku_kia')->with('info', 'Silakan lengkapi screening Buku KIA terlebih dahulu.');
        }

        $bulanan = $dataKia->pemantauanBulananBayi12s->keyBy('bulan_ke');
        $perkembangan9 = $dataKia->perkembanganBayi9Bulan;
        $perkembangan12 = $dataKia->perkembanganBayi12Bulan;

        return view('pengguna.kia-bulanan-bayi-12', compact('dataKia', 'bulanan', 'perkembangan9', 'perkembangan12'));
    }

    public function pemantauanBulananBayi12Store(Request $request)
    {
        $userId = auth()->id();
        $dataKia = DataKia::where('user_id', $userId)->firstOrFail();

        $data = [
            'sesak_napas' => $request->has('sesak_napas'),
            'batuk' => $request->has('batuk'),
            'suhu_abnormal' => $request->has('suhu_abnormal'),
            'bab_sering' => $request->has('bab_sering'),
            'kencing_sedikit' => $request->has('kencing_sedikit'),
            'kulit_biru' => $request->has('kulit_biru'),
            'aktivitas_lemah' => $request->has('aktivitas_lemah'),
            'hisapan_lemah' => $request->has('hisapan_lemah'),
            'tidak_makan' => $request->has('tidak_makan'),
        ];

        if ($request->has('paraf_kader_nakes')) {
            $data['paraf_kader_nakes'] = $request->paraf_kader_nakes;
        }

        $dataKia->pemantauanBulananBayi12s()->updateOrCreate(
            ['data_kia_id' => $dataKia->id, 'bulan_ke' => $request->bulan_ke],
            $data
        );

        return back()->with('success', 'Catatan pemantauan bulanan bayi bulan ke-' . $request->bulan_ke . ' berhasil disimpan.')
            ->with('active_tab', 'bulanan')
            ->with('active_month', $request->bulan_ke);
    }

    public function perkembanganBayi9BulanStore(Request $request)
    {
        $userId = auth()->id();
        $dataKia = DataKia::where('user_id', $userId)->firstOrFail();

        $dataKia->perkembanganBayi9Bulan()->updateOrCreate(
            ['data_kia_id' => $dataKia->id],
            [
                'duduk_mandiri' => $request->has('duduk_mandiri') ? ($request->duduk_mandiri === '1') : null,
                'tengkurap_dada' => $request->has('tengkurap_dada') ? ($request->tengkurap_dada === '1') : null,
                'merangkak' => $request->has('merangkak') ? ($request->merangkak === '1') : null,
                'pindah_benda' => $request->has('pindah_benda') ? ($request->pindah_benda === '1') : null,
                'pungut_2_benda' => $request->has('pungut_2_benda') ? ($request->pungut_2_benda === '1') : null,
                'pungut_kacang' => $request->has('pungut_kacang') ? ($request->pungut_kacang === '1') : null,
                'bersuara_tanpa_arti' => $request->has('bersuara_tanpa_arti') ? ($request->bersuara_tanpa_arti === '1') : null,
                'cari_mainan' => $request->has('cari_mainan') ? ($request->cari_mainan === '1') : null,
                'tepuk_tangan' => $request->has('tepuk_tangan') ? ($request->tepuk_tangan === '1') : null,
                'lempar_benda' => $request->has('lempar_benda') ? ($request->lempar_benda === '1') : null,
                'makan_kue' => $request->has('makan_kue') ? ($request->makan_kue === '1') : null,
            ]
        );

        return back()->with('success', 'Checklist perkembangan bayi umur 6-9 bulan berhasil disimpan.')
            ->with('active_tab', 'perkembangan9');
    }

    public function perkembanganBayi12BulanStore(Request $request)
    {
        $userId = auth()->id();
        $dataKia = DataKia::where('user_id', $userId)->firstOrFail();

        $dataKia->perkembanganBayi12Bulan()->updateOrCreate(
            ['data_kia_id' => $dataKia->id],
            [
                'angkat_badan_berdiri' => $request->has('angkat_badan_berdiri') ? ($request->angkat_badan_berdiri === '1') : null,
                'belajar_berdiri' => $request->has('belajar_berdiri') ? ($request->belajar_berdiri === '1') : null,
                'jalan_dituntun' => $request->has('jalan_dituntun') ? ($request->jalan_dituntun === '1') : null,
                'ulur_tangan_raih' => $request->has('ulur_tangan_raih') ? ($request->ulur_tangan_raih === '1') : null,
                'genggam_pensil' => $request->has('genggam_pensil') ? ($request->genggam_pensil === '1') : null,
                'masuk_benda_mulut' => $request->has('masuk_benda_mulut') ? ($request->masuk_benda_mulut === '1') : null,
                'tiru_bunyi' => $request->has('tiru_bunyi') ? ($request->tiru_bunyi === '1') : null,
                'sebut_2_suku_kata' => $request->has('sebut_2_suku_kata') ? ($request->sebut_2_suku_kata === '1') : null,
                'eksplorasi_sekitar' => $request->has('eksplorasi_sekitar') ? ($request->eksplorasi_sekitar === '1') : null,
                'reaksi_panggilan' => $request->has('reaksi_panggilan') ? ($request->reaksi_panggilan === '1') : null,
                'bermain_cilukba' => $request->has('bermain_cilukba') ? ($request->bermain_cilukba === '1') : null,
                'kenal_keluarga' => $request->has('kenal_keluarga') ? ($request->kenal_keluarga === '1') : null,
            ]
        );

        return back()->with('success', 'Checklist perkembangan bayi umur 9-12 bulan berhasil disimpan.')
            ->with('active_tab', 'perkembangan12');
    }

    public function pemantauanBulananAnak24Index()
    {
        $userId = auth()->id();
        $dataKia = DataKia::with(['pemantauanBulananAnak24s', 'perkembanganBayi18Bulan', 'perkembanganBayi24Bulan'])->where('user_id', $userId)->first();

        if (!$dataKia) {
            return redirect()->route('pengguna.buku_kia')->with('info', 'Silakan lengkapi screening Buku KIA terlebih dahulu.');
        }

        $bulanan = $dataKia->pemantauanBulananAnak24s->keyBy('bulan_ke');
        $perkembangan18 = $dataKia->perkembanganBayi18Bulan;
        $perkembangan24 = $dataKia->perkembanganBayi24Bulan;

        return view('pengguna.kia-bulanan-anak-24', compact('dataKia', 'bulanan', 'perkembangan18', 'perkembangan24'));
    }

    public function pemantauanBulananAnak24Store(Request $request)
    {
        $userId = auth()->id();
        $dataKia = DataKia::where('user_id', $userId)->firstOrFail();

        $data = [
            'sesak_napas' => $request->has('sesak_napas'),
            'batuk' => $request->has('batuk'),
            'suhu_abnormal' => $request->has('suhu_abnormal'),
            'bab_sering' => $request->has('bab_sering'),
            'kencing_sedikit' => $request->has('kencing_sedikit'),
            'kulit_pucat_biru' => $request->has('kulit_pucat_biru'),
            'aktivitas_lemah' => $request->has('aktivitas_lemah'),
            'telinga_cairan' => $request->has('telinga_cairan'),
            'tidak_makan' => $request->has('tidak_makan'),
        ];

        if ($request->has('paraf_kader_nakes')) {
            $data['paraf_kader_nakes'] = $request->paraf_kader_nakes;
        }

        $dataKia->pemantauanBulananAnak24s()->updateOrCreate(
            ['data_kia_id' => $dataKia->id, 'bulan_ke' => $request->bulan_ke],
            $data
        );

        return back()->with('success', 'Catatan pemantauan bulanan anak bulan ke-' . $request->bulan_ke . ' berhasil disimpan.')
            ->with('active_tab', 'bulanan')
            ->with('active_month', $request->bulan_ke);
    }

    public function perkembanganBayi18BulanStore(Request $request)
    {
        $userId = auth()->id();
        $dataKia = DataKia::where('user_id', $userId)->firstOrFail();

        $dataKia->perkembanganBayi18Bulan()->updateOrCreate(
            ['data_kia_id' => $dataKia->id],
            [
                'berdiri_tanpa_pegangan' => $request->has('berdiri_tanpa_pegangan') ? ($request->berdiri_tanpa_pegangan === '1') : null,
                'bungkuk_pungut_mainan' => $request->has('bungkuk_pungut_mainan') ? ($request->bungkuk_pungut_mainan === '1') : null,
                'jalan_mundur_5_langkah' => $request->has('jalan_mundur_5_langkah') ? ($request->jalan_mundur_5_langkah === '1') : null,
                'panggil_papa_mama' => $request->has('panggil_papa_mama') ? ($request->panggil_papa_mama === '1') : null,
                'tumpuk_2_kubus' => $request->has('tumpuk_2_kubus') ? ($request->tumpuk_2_kubus === '1') : null,
                'masuk_kubus_kotak' => $request->has('masuk_kubus_kotak') ? ($request->masuk_kubus_kotak === '1') : null,
                'tunjuk_tanpa_nangis' => $request->has('tunjuk_tanpa_nangis') ? ($request->tunjuk_tanpa_nangis === '1') : null,
                'rasa_cemburu' => $request->has('rasa_cemburu') ? ($request->rasa_cemburu === '1') : null,
            ]
        );

        return back()->with('success', 'Checklist perkembangan bayi umur 12 - 18 bulan berhasil disimpan.')
            ->with('active_tab', 'perkembangan18');
    }

    public function perkembanganBayi24BulanStore(Request $request)
    {
        $userId = auth()->id();
        $dataKia = DataKia::where('user_id', $userId)->firstOrFail();

        $dataKia->perkembanganBayi24Bulan()->updateOrCreate(
            ['data_kia_id' => $dataKia->id],
            [
                'berdiri_30_detik' => $request->has('berdiri_30_detik') ? ($request->berdiri_30_detik === '1') : null,
                'jalan_tanpa_huyung' => $request->has('jalan_tanpa_huyung') ? ($request->jalan_tanpa_huyung === '1') : null,
                'tumpuk_4_kubus' => $request->has('tumpuk_4_kubus') ? ($request->tumpuk_4_kubus === '1') : null,
                'pungut_benda_kecil' => $request->has('pungut_benda_kecil') ? ($request->pungut_benda_kecil === '1') : null,
                'gelinding_bola' => $request->has('gelinding_bola') ? ($request->gelinding_bola === '1') : null,
                'sebut_3_6_kata' => $request->has('sebut_3_6_kata') ? ($request->sebut_3_6_kata === '1') : null,
                'bantu_pekerjaan_rumah' => $request->has('bantu_pekerjaan_rumah') ? ($request->bantu_pekerjaan_rumah === '1') : null,
                'pegang_cangkir_sendiri' => $request->has('pegang_cangkir_sendiri') ? ($request->pegang_cangkir_sendiri === '1') : null,
            ]
        );

        return back()->with('success', 'Checklist perkembangan bayi umur 18 - 24 bulan berhasil disimpan.')
            ->with('active_tab', 'perkembangan24');
    }

    public function pemantauanBulananAnak72Index()
    {
        $userId = auth()->id();
        $dataKia = DataKia::with(['pemantauanBulananAnak72s', 'perkembanganAnak36Bulan', 'perkembanganAnak48Bulan', 'perkembanganAnak60Bulan', 'perkembanganAnak72Bulan'])->where('user_id', $userId)->first();

        if (!$dataKia) {
            return redirect()->route('pengguna.buku_kia')->with('info', 'Silakan lengkapi screening Buku KIA terlebih dahulu.');
        }

        $bulanan = $dataKia->pemantauanBulananAnak72s->keyBy('bulan_ke');
        $perk36 = $dataKia->perkembanganAnak36Bulan;
        $perk48 = $dataKia->perkembanganAnak48Bulan;
        $perk60 = $dataKia->perkembanganAnak60Bulan;
        $perk72 = $dataKia->perkembanganAnak72Bulan;

        return view('pengguna.kia-bulanan-anak-72', compact('dataKia', 'bulanan', 'perk36', 'perk48', 'perk60', 'perk72'));
    }

    public function perkembanganAnak36BulanStore(Request $request)
    {
        $userId = auth()->id();
        $dataKia = DataKia::where('user_id', $userId)->firstOrFail();

        $dataKia->perkembanganAnak36Bulan()->updateOrCreate(
            ['data_kia_id' => $dataKia->id],
            [
                'naik_tangga' => $request->has('naik_tangga') ? ($request->naik_tangga === '1') : null,
                'tendang_bola' => $request->has('tendang_bola') ? ($request->tendang_bola === '1') : null,
                'coret_kertas' => $request->has('coret_kertas') ? ($request->coret_kertas === '1') : null,
                'bicara_2_kata' => $request->has('bicara_2_kata') ? ($request->bicara_2_kata === '1') : null,
                'tunjuk_bagian_tubuh' => $request->has('tunjuk_bagian_tubuh') ? ($request->tunjuk_bagian_tubuh === '1') : null,
                'sebut_nama_benda' => $request->has('sebut_nama_benda') ? ($request->sebut_nama_benda === '1') : null,
                'pungut_mainan' => $request->has('pungut_mainan') ? ($request->pungut_mainan === '1') : null,
                'makan_nasi_sendiri' => $request->has('makan_nasi_sendiri') ? ($request->makan_nasi_sendiri === '1') : null,
                'lepas_pakaian' => $request->has('lepas_pakaian') ? ($request->lepas_pakaian === '1') : null,
            ]
        );

        return back()->with('success', 'Checklist perkembangan anak umur 2 - 3 tahun berhasil disimpan.')
            ->with('active_tab', 'perkembangan36');
    }

    public function perkembanganAnak48BulanStore(Request $request)
    {
        $userId = auth()->id();
        $dataKia = DataKia::where('user_id', $userId)->firstOrFail();

        $dataKia->perkembanganAnak48Bulan()->updateOrCreate(
            ['data_kia_id' => $dataKia->id],
            [
                'berdiri_1_kaki_2_detik' => $request->has('berdiri_1_kaki_2_detik') ? ($request->berdiri_1_kaki_2_detik === '1') : null,
                'lompat_kedua_kaki' => $request->has('lompat_kedua_kaki') ? ($request->lompat_kedua_kaki === '1') : null,
                'kayuh_sepeda_roda_3' => $request->has('kayuh_sepeda_roda_3') ? ($request->kayuh_sepeda_roda_3 === '1') : null,
                'gambar_garis_lurus' => $request->has('gambar_garis_lurus') ? ($request->gambar_garis_lurus === '1') : null,
                'tumpuk_8_kubus' => $request->has('tumpuk_8_kubus') ? ($request->tumpuk_8_kubus === '1') : null,
                'kenal_2_4_warna' => $request->has('kenal_2_4_warna') ? ($request->kenal_2_4_warna === '1') : null,
                'sebut_nama_umur_tempat' => $request->has('sebut_nama_umur_tempat') ? ($request->sebut_nama_umur_tempat === '1') : null,
                'mengerti_arti_kata_posisi' => $request->has('mengerti_arti_kata_posisi') ? ($request->mengerti_arti_kata_posisi === '1') : null,
                'dengar_cerita' => $request->has('dengar_cerita') ? ($request->dengar_cerita === '1') : null,
                'cuci_tangan_sendiri' => $request->has('cuci_tangan_sendiri') ? ($request->cuci_tangan_sendiri === '1') : null,
                'bermain_dengan_teman' => $request->has('bermain_dengan_teman') ? ($request->bermain_dengan_teman === '1') : null,
                'pakai_sepatu_sendiri' => $request->has('pakai_sepatu_sendiri') ? ($request->pakai_sepatu_sendiri === '1') : null,
                'pakai_celana_baju_sendiri' => $request->has('pakai_celana_baju_sendiri') ? ($request->pakai_celana_baju_sendiri === '1') : null,
            ]
        );

        return back()->with('success', 'Checklist perkembangan anak umur 3 - 4 tahun berhasil disimpan.')
            ->with('active_tab', 'perkembangan48');
    }

    public function perkembanganAnak60BulanStore(Request $request)
    {
        $userId = auth()->id();
        $dataKia = DataKia::where('user_id', $userId)->firstOrFail();

        $dataKia->perkembanganAnak60Bulan()->updateOrCreate(
            ['data_kia_id' => $dataKia->id],
            [
                'berdiri_1_kaki_6_detik' => $request->has('berdiri_1_kaki_6_detik') ? ($request->berdiri_1_kaki_6_detik === '1') : null,
                'lompat_1_kaki' => $request->has('lompat_1_kaki') ? ($request->lompat_1_kaki === '1') : null,
                'menari' => $request->has('menari') ? ($request->menari === '1') : null,
                'gambar_tanda_silang' => $request->has('gambar_tanda_silang') ? ($request->gambar_tanda_silang === '1') : null,
                'gambar_lingkaran' => $request->has('gambar_lingkaran') ? ($request->gambar_lingkaran === '1') : null,
                'gambar_orang_3_bagian' => $request->has('gambar_orang_3_bagian') ? ($request->gambar_orang_3_bagian === '1') : null,
                'kancing_baju_boneka' => $request->has('kancing_baju_boneka') ? ($request->kancing_baju_boneka === '1') : null,
                'sebut_nama_lengkap' => $request->has('sebut_nama_lengkap') ? ($request->sebut_nama_lengkap === '1') : null,
                'senang_sebut_kata_baru' => $request->has('senang_sebut_kata_baru') ? ($request->senang_sebut_kata_baru === '1') : null,
                'senang_bertanya' => $request->has('senang_bertanya') ? ($request->senang_bertanya === '1') : null,
                'jawab_pertanyaan_kata_benar' => $request->has('jawab_pertanyaan_kata_benar') ? ($request->jawab_pertanyaan_kata_benar === '1') : null,
                'bicara_mudah_dimengerti' => $request->has('bicara_mudah_dimengerti') ? ($request->bicara_mudah_dimengerti === '1') : null,
                'banding_ukuran_bentuk' => $request->has('banding_ukuran_bentuk') ? ($request->banding_ukuran_bentuk === '1') : null,
                'sebut_angka_hitung_jari' => $request->has('sebut_angka_hitung_jari') ? ($request->sebut_angka_hitung_jari === '1') : null,
            ]
        );

        return back()->with('success', 'Checklist perkembangan anak umur 4 - 5 tahun berhasil disimpan.')
            ->with('active_tab', 'perkembangan60');
    }

    public function perkembanganAnak72BulanStore(Request $request)
    {
        $userId = auth()->id();
        $dataKia = DataKia::where('user_id', $userId)->firstOrFail();

        $dataKia->perkembanganAnak72Bulan()->updateOrCreate(
            ['data_kia_id' => $dataKia->id],
            [
                'berjalan_lurus' => $request->has('berjalan_lurus') ? ($request->berjalan_lurus === '1') : null,
                'berdiri_1_kaki_11_detik' => $request->has('berdiri_1_kaki_11_detik') ? ($request->berdiri_1_kaki_11_detik === '1') : null,
                'gambar_6_bagian_orang_lengkap' => $request->has('gambar_6_bagian_orang_lengkap') ? ($request->gambar_6_bagian_orang_lengkap === '1') : null,
                'tangkap_bola_kecil' => $request->has('tangkap_bola_kecil') ? ($request->tangkap_bola_kecil === '1') : null,
                'gambar_segi_empat' => $request->has('gambar_segi_empat') ? ($request->gambar_segi_empat === '1') : null,
                'mengerti_lawan_kata' => $request->has('mengerti_lawan_kata') ? ($request->mengerti_lawan_kata === '1') : null,
                'mengerti_pembicaraan_7_kata' => $request->has('mengerti_pembicaraan_7_kata') ? ($request->mengerti_pembicaraan_7_kata === '1') : null,
                'jawab_bahan_guna_benda' => $request->has('jawab_bahan_guna_benda') ? ($request->jawab_bahan_guna_benda === '1') : null,
                'kenal_angka_hitung_5_10' => $request->has('kenal_angka_hitung_5_10') ? ($request->kenal_angka_hitung_5_10 === '1') : null,
                'kenal_warna_warni' => $request->has('kenal_warna_warni') ? ($request->kenal_warna_warni === '1') : null,
                'ungkapkan_simpati' => $request->has('ungkapkan_simpati') ? ($request->ungkapkan_simpati === '1') : null,
                'ikut_aturan_permainan' => $request->has('ikut_aturan_permainan') ? ($request->ikut_aturan_permainan === '1') : null,
                'pakaian_sendiri_tanpa_bantu' => $request->has('pakaian_sendiri_tanpa_bantu') ? ($request->pakaian_sendiri_tanpa_bantu === '1') : null,
            ]
        );

        return back()->with('success', 'Checklist perkembangan anak umur 5 - 6 tahun berhasil disimpan.')
            ->with('active_tab', 'perkembangan72');
    }

    public function pemantauanBulananAnak72Store(Request $request)
    {
        $userId = auth()->id();
        $dataKia = DataKia::where('user_id', $userId)->firstOrFail();

        $data = [
            'sesak_napas' => $request->has('sesak_napas'),
            'batuk' => $request->has('batuk'),
            'suhu_abnormal' => $request->has('suhu_abnormal'),
            'bab_sering' => $request->has('bab_sering'),
            'kencing_sedikit' => $request->has('kencing_sedikit'),
            'kulit_pucat_biru' => $request->has('kulit_pucat_biru'),
            'aktivitas_lemah' => $request->has('aktivitas_lemah'),
            'telinga_cairan' => $request->has('telinga_cairan'),
            'tidak_makan' => $request->has('tidak_makan'),
        ];

        if ($request->has('paraf_kader_nakes')) {
            $data['paraf_kader_nakes'] = $request->paraf_kader_nakes;
        }

        $dataKia->pemantauanBulananAnak72s()->updateOrCreate(
            ['data_kia_id' => $dataKia->id, 'bulan_ke' => $request->bulan_ke],
            $data
        );

        $activeTab = 'tahun2';
        if ($request->bulan_ke >= 36 && $request->bulan_ke <= 47) {
            $activeTab = 'tahun3';
        } elseif ($request->bulan_ke >= 48 && $request->bulan_ke <= 59) {
            $activeTab = 'tahun4';
        } elseif ($request->bulan_ke >= 60 && $request->bulan_ke <= 71) {
            $activeTab = 'tahun5';
        }

        return back()->with('success', 'Catatan pemantauan bulanan anak bulan ke-' . $request->bulan_ke . ' berhasil disimpan.')
            ->with('active_tab', $activeTab)
            ->with('active_month', $request->bulan_ke);
    }

    public function kesehatanLingkunganIndex()
    {
        $userId = auth()->id();
        $dataKia = DataKia::with('kesehatanLingkungan')->where('user_id', $userId)->first();

        if (!$dataKia) {
            return redirect()->route('pengguna.buku_kia')->with('info', 'Silakan lengkapi screening Buku KIA terlebih dahulu.');
        }

        $lingkungan = $dataKia->kesehatanLingkungan;

        return view('pengguna.kia-kesehatan-lingkungan', compact('dataKia', 'lingkungan'));
    }

    public function kesehatanLingkunganStore(Request $request)
    {
        $userId = auth()->id();
        $dataKia = DataKia::where('user_id', $userId)->firstOrFail();

        $data = [
            'bab_sembarangan' => $request->has('bab_sembarangan') ? ($request->bab_sembarangan === '1') : null,
            'bab_jamban_sendiri' => $request->has('bab_jamban_sendiri') ? ($request->bab_jamban_sendiri === '1') : null,
            'penampung_tangki_septik' => $request->has('penampung_tangki_septik') ? ($request->penampung_tangki_septik === '1') : null,
            'penampung_cubluk' => $request->has('penampung_cubluk') ? ($request->penampung_cubluk === '1') : null,
            'penampung_drainase' => $request->has('penampung_drainase') ? ($request->penampung_drainase === '1') : null,
            'kloset_leher_angsa' => $request->has('kloset_leher_angsa') ? ($request->kloset_leher_angsa === '1') : null,
            'ctps_sarana' => $request->has('ctps_sarana') ? ($request->ctps_sarana === '1') : null,
            'ctps_air_mengalir' => $request->has('ctps_air_mengalir') ? ($request->ctps_air_mengalir === '1') : null,
            'ctps_sabun' => $request->has('ctps_sabun') ? ($request->ctps_sabun === '1') : null,
            'ctps_waktu_sebelum_makan' => $request->has('ctps_waktu_sebelum_makan') ? ($request->ctps_waktu_sebelum_makan === '1') : null,
            'ctps_waktu_sebelum_mengolah' => $request->has('ctps_waktu_sebelum_mengolah') ? ($request->ctps_waktu_sebelum_mengolah === '1') : null,
            'ctps_waktu_sebelum_menyusui' => $request->has('ctps_waktu_sebelum_menyusui') ? ($request->ctps_waktu_sebelum_menyusui === '1') : null,
            'ctps_waktu_setelah_bab' => $request->has('ctps_waktu_setelah_bab') ? ($request->ctps_waktu_setelah_bab === '1') : null,
            'sumber_air_pipa' => $request->has('sumber_air_pipa') ? ($request->sumber_air_pipa === '1') : null,
            'sumber_air_kran' => $request->has('sumber_air_kran') ? ($request->sumber_air_kran === '1') : null,
            'sumber_air_sumur_terlindungi' => $request->has('sumber_air_sumur_terlindungi') ? ($request->sumber_air_sumur_terlindungi === '1') : null,
            'sumber_air_mata_air_terlindungi' => $request->has('sumber_air_mata_air_terlindungi') ? ($request->sumber_air_mata_air_terlindungi === '1') : null,
            'sumber_air_sungai' => $request->has('sumber_air_sungai') ? ($request->sumber_air_sungai === '1') : null,
            'sumber_air_danau' => $request->has('sumber_air_danau') ? ($request->sumber_air_danau === '1') : null,
            'sumber_air_hujan' => $request->has('sumber_air_hujan') ? ($request->sumber_air_hujan === '1') : null,
            'sumber_air_waduk' => $request->has('sumber_air_waduk') ? ($request->sumber_air_waduk === '1') : null,
            'sumber_air_kolam' => $request->has('sumber_air_kolam') ? ($request->sumber_air_kolam === '1') : null,
            'sumber_air_irigasi' => $request->has('sumber_air_irigasi') ? ($request->sumber_air_irigasi === '1') : null,
            'kelola_air_rebus' => $request->has('kelola_air_rebus') ? ($request->kelola_air_rebus === '1') : null,
            'kelola_air_endap_saring' => $request->has('kelola_air_endap_saring') ? ($request->kelola_air_endap_saring === '1') : null,
            'kelola_air_wadah_tertutup' => $request->has('kelola_air_wadah_tertutup') ? ($request->kelola_air_wadah_tertutup === '1') : null,
            'kelola_makanan_tertutup' => $request->has('kelola_makanan_tertutup') ? ($request->kelola_makanan_tertutup === '1') : null,
            'kelola_makanan_jauh_bahan_berbahaya' => $request->has('kelola_makanan_jauh_bahan_berbahaya') ? ($request->kelola_makanan_jauh_bahan_berbahaya === '1') : null,
            'kelola_makanan_baik_benar' => $request->has('kelola_makanan_baik_benar') ? ($request->kelola_makanan_baik_benar === '1') : null,
            'sampah_tidak_berserakan' => $request->has('sampah_tidak_berserakan') ? ($request->sampah_tidak_berserakan === '1') : null,
            'sampah_tempat_tertutup' => $request->has('sampah_tempat_tertutup') ? ($request->sampah_tempat_tertutup === '1') : null,
            'sampah_dipilah' => $request->has('sampah_dipilah') ? ($request->sampah_dipilah === '1') : null,
            'sampah_tidak_dibakar' => $request->has('sampah_tidak_dibakar') ? ($request->sampah_tidak_dibakar === '1') : null,
            'sampah_tidak_dibuang_sembarangan' => $request->has('sampah_tidak_dibuang_sembarangan') ? ($request->sampah_tidak_dibuang_sembarangan === '1') : null,
            'limbah_tidak_menggenang' => $request->has('limbah_tidak_menggenang') ? ($request->limbah_tidak_menggenang === '1') : null,
            'limbah_saluran_tertutup' => $request->has('limbah_saluran_tertutup') ? ($request->limbah_saluran_tertutup === '1') : null,
            'limbah_terhubung_resapan' => $request->has('limbah_terhubung_resapan') ? ($request->limbah_terhubung_resapan === '1') : null,
        ];

        $dataKia->kesehatanLingkungan()->updateOrCreate(
            ['data_kia_id' => $dataKia->id],
            $data
        );

        return back()->with('success', 'Checklist Kesehatan Lingkungan berhasil disimpan.');
    }
}

