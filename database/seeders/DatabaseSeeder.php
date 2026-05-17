<?php

namespace Database\Seeders;

use App\Models\Anak;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create single admin account (fixed credentials)
        $adminEmail = 'admin@gmail.com';
        $adminPassword = 'admin123';

        if (!User::where('email', $adminEmail)->exists()) {
            User::factory()->create([
                'name' => 'Administrator',
                'email' => $adminEmail,
                'password' => bcrypt($adminPassword),
                'role' => 'admin',
            ]);
        }

        // Optional: keep one test pengguna account
        if (!User::where('email', 'test@example.com')->exists()) {
            User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
                'role' => 'pengguna',
            ]);
        }

        // Create specific example accounts requested
        $examples = [
            ['role' => 'pengguna', 'email' => 'pengguna@gmail.com', 'password' => 'pengguna123', 'name' => 'Pengguna Contoh'],
            ['role' => 'bidan', 'email' => 'bidan@gmail.com', 'password' => 'bidan123', 'name' => 'Bidan Contoh'],
            ['role' => 'dokter', 'email' => 'dokter@gmail.com', 'password' => 'dokter123', 'name' => 'Dokter Contoh'],
        ];

        foreach ($examples as $ex) {
            if (!User::where('email', $ex['email'])->exists()) {
                $user = User::create([
                    'name' => $ex['name'],
                    'email' => $ex['email'],
                    'password' => bcrypt($ex['password']),
                    'role' => $ex['role'],
                ]);

                // Also insert into role-specific table so admin panels see them
                $roleTable = $ex['role'];
                $insert = [
                    'id' => $user->id,
                    'name' => $ex['name'],
                    'email' => $ex['email'],
                    'password' => bcrypt($ex['password']),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                if ($roleTable === 'pengguna') {
                    $insert['is_hamil'] = 0;
                }

                \Illuminate\Support\Facades\DB::table($roleTable)->insert($insert);
            }
        }

        $demoEmail = 'pengguna@gmail.com';
        $demoPassword = 'pengguna123';
        $demoUser = User::where('email', $demoEmail)->first();

        if ($demoUser) {
            $penggunaData = [
                'id' => $demoUser->id,
                'name' => $demoUser->name,
                'email' => $demoUser->email,
                'password' => bcrypt($demoPassword),
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if (Schema::hasColumn('pengguna', 'is_hamil')) {
                $penggunaData['is_hamil'] = 1;
            }

            if (Schema::hasColumn('pengguna', 'usia_kehamilan_minggu')) {
                $penggunaData['usia_kehamilan_minggu'] = 24;
            }

            DB::table('pengguna')->updateOrInsert(
                ['id' => $demoUser->id],
                $penggunaData
            );

            if (Schema::hasColumn('users', 'usia_kehamilan_minggu')) {
                DB::table('users')
                    ->where('id', $demoUser->id)
                    ->update([
                        'usia_kehamilan_minggu' => 24,
                        'updated_at' => now(),
                    ]);
            }

            if (Schema::hasTable('anak')) {
                Anak::updateOrCreate(
                    [
                        'pengguna_id' => $demoUser->id,
                        'anak_ke' => 1,
                    ],
                    [
                        'nama_anak' => null,
                        'tanggal_lahir' => null,
                        'status' => 'dalam_kandungan',
                    ]
                );
            }
        }

        $articles = [
            [
                'title' => 'Nutrisi Dasar Ibu Hamil',
                'category' => 'umum',
                'summary' => 'Panduan singkat asupan protein, sayur, buah, cairan, dan vitamin yang penting selama kehamilan.',
                'article_url' => 'https://www.who.int/news-room/fact-sheets/detail/nutrition-in-pregnancy',
                'min_week' => null,
                'max_week' => null,
            ],
            [
                'title' => 'Perkembangan Janin Trimester 1',
                'category' => 'trimester_1',
                'summary' => 'Fokus pada pembentukan organ awal, keluhan umum, dan hal-hal yang perlu dipantau pada 1-13 minggu.',
                'article_url' => 'https://medlineplus.gov/ency/patientinstructions/000512.htm',
                'min_week' => 1,
                'max_week' => 13,
            ],
            [
                'title' => 'Tips Nyaman di Trimester 2',
                'category' => 'trimester_2',
                'summary' => 'Saran menjaga tidur, aktivitas ringan, dan persiapan kontrol rutin untuk usia 14-27 minggu.',
                'article_url' => 'https://www.nhs.uk/pregnancy/week-by-week/13-to-27/overview/',
                'min_week' => 14,
                'max_week' => 27,
            ],
            [
                'title' => 'Persiapan Menjelang Persalinan',
                'category' => 'trimester_3',
                'summary' => 'Checklist perlengkapan, tanda persalinan, dan langkah penting yang perlu disiapkan pada trimester akhir.',
                'article_url' => 'https://www.nhs.uk/pregnancy/labour-and-birth/preparing-for-the-birth/your-hospital-bag/',
                'min_week' => 28,
                'max_week' => 42,
            ],
        ];

        foreach ($articles as $article) {
            DB::table('artikel_edukasi')->updateOrInsert(
                ['title' => $article['title']],
                array_merge($article, [
                    'image_url' => asset('foto/artikel.jpg'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}
