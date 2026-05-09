<?php

use Illuminate\Foundation\Application;
use App\Models\ArtikelEdukasi;
use App\Models\Pengguna;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Support\Facades\Password;
use Laravel\Fortify\Features;
use Laravel\Jetstream\Agent;

Route::get('/', function () {
    session()->regenerateToken();

    return response()
        ->view('landingPage')
        ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
        ->header('Pragma', 'no-cache')
        ->header('Expires', '0');
});

// Role-specific dashboards and admin-only user management
Route::middleware(['auth'])->group(function () {
    $ensureRole = function (string $role) {
        abort_unless(auth()->check() && auth()->user()->role === $role, 403);
    };

    Route::get('/admin/dashboard', function () use ($ensureRole) {
        $ensureRole('admin');

        $pengguna = DB::table('pengguna')->orderBy('name')->get()->map(function ($user) {
            return (object) [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => 'pengguna',
                'is_hamil' => (int) ($user->is_hamil ?? 0),
            ];
        });

        $bidan = DB::table('bidan')->orderBy('name')->get()->map(function ($user) {
            return (object) [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => 'bidan',
                'is_hamil' => 0,
            ];
        });

        $dokter = DB::table('dokter')->orderBy('name')->get()->map(function ($user) {
            return (object) [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => 'dokter',
                'is_hamil' => 0,
            ];
        });

        $admin = DB::table('admin')->orderBy('name')->get()->map(function ($user) {
            return (object) [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => 'admin',
                'is_hamil' => 0,
            ];
        });

        $allUsers = collect()->merge($pengguna)->merge($bidan)->merge($dokter)->merge($admin)->values();

        return view('Admin.DashboardAdmin', [
            'penggunaCount' => $pengguna->count(),
            'bidanCount' => $bidan->count(),
            'dokterCount' => $dokter->count(),
            'adminCount' => $admin->count(),
            'ibuHamilAktifCount' => $pengguna->where('is_hamil', 1)->count(),
            'dashboardUsers' => $allUsers,
        ]);
    })->name('admin.dashboard');

    Route::get('/admin/users', function () use ($ensureRole) {
        $ensureRole('admin');

        $pengguna = DB::table('pengguna')->orderBy('name')->get();
        $bidan = DB::table('bidan')->orderBy('name')->get();
        $dokter = DB::table('dokter')->orderBy('name')->get();
        $admin = DB::table('admin')->orderBy('name')->get();

        $users = collect()
            ->merge($pengguna->map(fn($u) => (object)[...(array)$u, 'type' => 'pengguna', 'is_hamil' => (int)($u->is_hamil ?? 0)]))
            ->merge($bidan->map(fn($u) => (object)[...(array)$u, 'type' => 'bidan', 'is_hamil' => 0]))
            ->merge($dokter->map(fn($u) => (object)[...(array)$u, 'type' => 'dokter', 'is_hamil' => 0]))
            ->merge($admin->map(fn($u) => (object)[...(array)$u, 'type' => 'admin', 'is_hamil' => 0]))
            ->sortBy('name');

        return view('Admin.ManajemenUser', [
            'users' => $users,
        ]);
    })->name('admin.users');

    Route::post('/admin/users', function (Request $request) use ($ensureRole) {
        $ensureRole('admin');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'role' => ['required', 'in:pengguna,bidan,dokter,admin'],
            'password' => ['required', 'string', 'min:8'],
        ]);
        $isHamil = $request->boolean('is_hamil');

        $hashedPassword = Hash::make($validated['password']);
        $roleTable = $validated['role'];

        // Check email uniqueness across all tables
        foreach (['pengguna', 'bidan', 'dokter', 'admin'] as $table) {
            abort_if(DB::table($table)->where('email', $validated['email'])->exists(), 422, 'Email sudah terdaftar.');
        }

        // Insert into users table
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'password' => $hashedPassword,
        ]);

        // Insert into role-specific table
        $roleInsert = [
            'id' => $user->id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $hashedPassword,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        if ($roleTable === 'pengguna') {
            $roleInsert['is_hamil'] = $isHamil ? 1 : 0;
        }

        DB::table($roleTable)->insert($roleInsert);

        return redirect()->route('admin.users')->with('success', 'Akun berhasil ditambahkan.');
    })->name('admin.users.store');

    Route::post('/admin/users/update', function (Request $request) use ($ensureRole) {
        $ensureRole('admin');

        $validated = $request->validate([
            'id' => ['required', 'integer'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'role' => ['required', 'in:pengguna,bidan,dokter,admin'],
            'password' => ['nullable', 'string', 'min:8'],
            'type' => ['required', 'in:pengguna,bidan,dokter,admin'],
        ]);
        $isHamil = $request->boolean('is_hamil');

        $user = User::findOrFail($validated['id']);
        abort_unless(auth()->id() !== $user->id, 403);
        abort_if($user->role === 'admin' && $validated['role'] !== 'admin', 403, 'Role admin tidak dapat diubah.');

        // Find user in the original table
        $originalTable = $validated['type'];
        $userInTable = DB::table($originalTable)->where('id', $validated['id'])->first();
        abort_if(!$userInTable, 404, 'User tidak ditemukan.');

        // Check email uniqueness across all tables
        foreach (['pengguna', 'bidan', 'dokter', 'admin'] as $table) {
            $exists = DB::table($table)->where('email', $validated['email'])->where('id', '!=', $validated['id'])->exists();
            abort_if($exists, 422, 'Email sudah dipakai.');
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $user->role === 'admin' ? 'admin' : $validated['role'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        // Update in role-specific table
        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'updated_at' => now(),
        ];

        if ($originalTable === 'pengguna') {
            $updateData['is_hamil'] = $isHamil ? 1 : 0;
        }

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        DB::table($originalTable)->where('id', $validated['id'])->update($updateData);

        return redirect()->route('admin.users')->with('success', 'Akun berhasil diupdate.');
    })->name('admin.users.update');

    Route::post('/admin/users/delete', function (Request $request) use ($ensureRole) {
        $ensureRole('admin');

        $validated = $request->validate([
            'id' => ['required', 'integer'],
            'type' => ['required', 'in:pengguna,bidan,dokter,admin'],
        ]);

        $user = User::findOrFail($validated['id']);
        abort_unless(auth()->id() !== $user->id, 403);
        abort_if($user->role === 'admin', 403, 'Akun admin tidak dapat dihapus.');

        $user->delete();

        // Delete from role-specific table
        DB::table($validated['type'])->where('id', $validated['id'])->delete();

        return redirect()->route('admin.users')->with('success', 'Akun berhasil dihapus.');
    })->name('admin.users.destroy');

    Route::get('/admin/articles', function () use ($ensureRole) {
        $ensureRole('admin');

        $articles = ArtikelEdukasi::query()->latest()->get();

        return view('Admin.ManajemenArtikel', [
            'articles' => $articles,
            'articleStats' => [
                'total' => $articles->count(),
                'trimester1' => $articles->where('category', 'trimester_1')->count(),
                'trimester2' => $articles->where('category', 'trimester_2')->count(),
                'trimester3' => $articles->where('category', 'trimester_3')->count(),
            ],
        ]);
    })->name('admin.articles');

    Route::post('/admin/articles', function (Request $request) use ($ensureRole) {
        $ensureRole('admin');

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'in:umum,trimester_1,trimester_2,trimester_3'],
            'image_url' => ['nullable', 'url', 'max:2048'],
            'summary' => ['required', 'string', 'max:2000'],
            'article_url' => ['nullable', 'url', 'max:2048'],
        ]);

        $categoryMap = [
            'trimester_1' => [1, 13],
            'trimester_2' => [14, 27],
            'trimester_3' => [28, 42],
        ];

        $range = $categoryMap[$validated['category']] ?? [null, null];

        ArtikelEdukasi::create([
            'title' => $validated['title'],
            'category' => $validated['category'],
            'image_url' => $validated['image_url'] ?? null,
            'summary' => $validated['summary'],
            'article_url' => $validated['article_url'] ?? null,
            'min_week' => $range[0],
            'max_week' => $range[1],
        ]);

        return back()->with('success', 'Artikel edukasi berhasil ditambahkan.');
    })->name('admin.articles.store');

    Route::delete('/admin/articles/{article}', function (ArtikelEdukasi $article) use ($ensureRole) {
        $ensureRole('admin');

        $article->delete();

        return back()->with('success', 'Artikel edukasi berhasil dihapus.');
    })->name('admin.articles.destroy');

    Route::get('/admin/kia', function () use ($ensureRole) {
        $ensureRole('admin');

        return view('Admin.BukuKIA');
    })->name('admin.kia');

    Route::get('/admin/kia/pdf', function () use ($ensureRole) {
        $ensureRole('admin');

        $pdfPath = resource_path('views/buku/Buku KIA (Permenkes).pdf');

        abort_unless(file_exists($pdfPath), 404, 'File Buku KIA tidak ditemukan.');

        return response()->file($pdfPath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="Buku KIA (Permenkes).pdf"',
        ]);
    })->name('admin.kia.pdf');

    Route::get('/admin/settings', function (Request $request) use ($ensureRole) {
        $ensureRole('admin');

        $adminUser = $request->user();
        $browserSessions = [];

        if (config('session.driver') === 'database') {
            $browserSessions = DB::connection(config('session.connection'))
                ->table(config('session.table', 'sessions'))
                ->where('user_id', $adminUser->getAuthIdentifier())
                ->orderByDesc('last_activity')
                ->get()
                ->map(function ($session) use ($request) {
                    $agent = tap(new Agent(), fn ($agent) => $agent->setUserAgent($session->user_agent));

                    return [
                        'agent' => [
                            'is_desktop' => $agent->isDesktop(),
                            'platform' => $agent->platform(),
                            'browser' => $agent->browser(),
                        ],
                        'ip_address' => $session->ip_address,
                        'is_current_device' => $session->id === $request->session()->getId(),
                        'last_active' => Carbon::createFromTimestamp($session->last_activity)->diffForHumans(),
                    ];
                })
                ->all();
        }

        return view('Admin.PengaturanAdmin', [
            'adminUser' => $adminUser,
            'browserSessions' => $browserSessions,
            'twoFactorEnabled' => filled($adminUser->two_factor_secret),
            'twoFactorConfirmed' => filled($adminUser->two_factor_confirmed_at),
            'requiresTwoFactorConfirmation' => Features::optionEnabled(Features::twoFactorAuthentication(), 'confirm'),
        ]);
    })->name('admin.settings');

    Route::post('/admin/settings/profile', function (Request $request) use ($ensureRole) {
        $ensureRole('admin');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'no_telp' => ['nullable', 'string', 'max:30'],
        ]);

        $adminId = auth()->id();
        $user = User::findOrFail($adminId);

        foreach (['users', 'admin'] as $table) {
            $query = DB::table($table)->where('email', $validated['email']);
            if ($table === 'users' || Schema::hasColumn($table, 'id')) {
                $query->where('id', '!=', $adminId);
            }
            abort_if($query->exists(), 422, 'Email sudah dipakai.');
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        if (Schema::hasColumn('users', 'no_telp')) {
            $user->no_telp = $validated['no_telp'] ?? null;
        }
        $user->save();

        DB::table('admin')->where('id', $adminId)->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            ...(Schema::hasColumn('admin', 'no_telp') ? ['no_telp' => $validated['no_telp'] ?? null] : []),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Profil admin berhasil diperbarui.');
    })->name('admin.settings.profile');

    Route::post('/admin/settings/password', function (Request $request) use ($ensureRole) {
        $ensureRole('admin');

        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $adminId = auth()->id();
        $user = User::findOrFail($adminId);

        abort_unless(Hash::check($validated['current_password'], $user->password), 422, 'Password saat ini salah.');

        $hashedPassword = Hash::make($validated['new_password']);
        $user->password = $hashedPassword;
        $user->save();

        DB::table('admin')->where('id', $adminId)->update([
            'password' => $hashedPassword,
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Password admin berhasil diperbarui.');
    })->name('admin.settings.password');

    Route::get('/bidan/dashboard', function () use ($ensureRole) {
        $ensureRole('bidan');

        return view('bidan.dashboardBidan', [
            'penggunaCount' => DB::table('pengguna')->count(),
            'bidanCount' => DB::table('bidan')->count(),
            'dokterCount' => DB::table('dokter')->count(),
            'recentPengguna' => DB::table('pengguna')->latest('created_at')->take(5)->get(['name', 'email', 'created_at']),
            'recentBidan' => DB::table('bidan')->latest('created_at')->take(5)->get(['name', 'email', 'created_at']),
        ]);
    })->name('bidan.dashboard');

    Route::get('/dokter/dashboard', function () use ($ensureRole) {
        $ensureRole('dokter');

        return view('dokter.dashboardDokter', [
            'penggunaCount' => DB::table('pengguna')->count(),
            'bidanCount' => DB::table('bidan')->count(),
            'dokterCount' => DB::table('dokter')->count(),
            'recentPengguna' => DB::table('pengguna')->latest('created_at')->take(5)->get(['name', 'email', 'created_at']),
            'recentDokter' => DB::table('dokter')->latest('created_at')->take(5)->get(['name', 'email', 'created_at']),
        ]);
    })->name('dokter.dashboard');

    Route::get('/bidan/settings', function () use ($ensureRole) {
        $ensureRole('bidan');

        return view('shared.panel-placeholder', [
            'layout' => 'bidan.master',
            'title' => 'Pengaturan Bidan - MomSpire',
            'headerTitle' => 'Pengaturan Bidan',
            'headerSubtitle' => 'Kelola profil dan preferensi akun bidan.',
            'pageTitle' => 'Pengaturan Bidan',
            'message' => 'Halaman pengaturan bidan sedang disiapkan.',
            'primaryActionUrl' => route('bidan.dashboard'),
            'primaryActionLabel' => 'Kembali ke Dashboard',
        ]);
    })->name('bidan.settings');

    Route::get('/dokter/settings', function () use ($ensureRole) {
        $ensureRole('dokter');

        return view('shared.panel-placeholder', [
            'layout' => 'dokter.master',
            'title' => 'Pengaturan Dokter - MomSpire',
            'headerTitle' => 'Pengaturan Dokter',
            'headerSubtitle' => 'Kelola profil dan preferensi akun dokter.',
            'pageTitle' => 'Pengaturan Dokter',
            'message' => 'Halaman pengaturan dokter sedang disiapkan.',
            'primaryActionUrl' => route('dokter.dashboard'),
            'primaryActionLabel' => 'Kembali ke Dashboard',
        ]);
    })->name('dokter.settings');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    $ensureUserRole = function (string $role) {
        abort_unless(auth()->check() && auth()->user()->role === $role, 403);
    };

    Route::get('/dashboard', function () {
        $user = auth()->user();
        if ($user) {
            if ($user->role === 'admin') {
                return redirect()->to(url('/admin/dashboard'));
            }
            if ($user->role === 'bidan') {
                return redirect()->to(url('/bidan/dashboard'));
            }
            if ($user->role === 'dokter') {
                return redirect()->to(url('/dokter/dashboard'));
            }
            if ($user->role === 'pengguna') {
                return redirect()->route('pengguna.dashboard');
            }
            // Unknown role
            abort(403, 'Invalid user role.');
        }

        return redirect()->route('login');
    })->name('dashboard');
});

$ensureUserRole = function (string $role) {
    abort_unless(auth()->check() && auth()->user()->role === $role, 403);
};

    Route::get('/pengguna/dashboard', function () use ($ensureUserRole) {
        $ensureUserRole('pengguna');

        // Use the modernized pengguna dashboard view
        $pengguna = \App\Models\Pengguna::find(auth()->id());

        return view('pengguna.dashboardPengguna', [
            'pengguna' => $pengguna,
        ]);
    })->name('pengguna.dashboard');

    Route::get('/pengguna/artikel', function (Request $request) use ($ensureUserRole) {
        $ensureUserRole('pengguna');

        $user = auth()->user();
        $pengguna = Pengguna::find($user->id);

        $selectedWeek = (int) (
            $user->usia_kehamilan_minggu
            ?? $pengguna->usia_kehamilan_minggu
            ?? 0
        );
        $selectedWeek = $selectedWeek > 0 ? $selectedWeek : null;

        $articles = ArtikelEdukasi::query()->inRandomOrder()->get();

        $selectedTrimester = null;
        if ($selectedWeek) {
            $selectedTrimester = $selectedWeek <= 13 ? 'trimester_1' : ($selectedWeek <= 27 ? 'trimester_2' : 'trimester_3');
        }

        $recommendedArticles = collect();
        if ($selectedTrimester) {
            $recommendedArticles = $articles->filter(function (ArtikelEdukasi $article) use ($selectedTrimester) {
                return $article->category === $selectedTrimester || $article->category === 'umum';
            })->values();
        }

        $recommendedIds = $recommendedArticles->pluck('id')->all();

        return view('pengguna.artikel', [
            'articles' => $articles,
            'selectedWeek' => $selectedWeek,
            'selectedTrimester' => $selectedTrimester,
            'recommendedIds' => $recommendedIds,
            'recommendedArticles' => $recommendedArticles,
        ]);
    })->name('pengguna.artikel');

    Route::get('/pengguna/konsultasi', function () use ($ensureUserRole) {
        $ensureUserRole('pengguna');

        $user = auth()->user();
        $pengguna = \App\Models\Pengguna::find($user->id);
        
        $bidan = \App\Models\Bidan::all();
        $dokter = \App\Models\Dokter::all();
        
        $conversations = \App\Models\Conversation::where('pengguna_id', $user->id)
            ->with('messages')
            ->orderBy('last_message_at', 'desc')
            ->get()
            ->map(function ($conv) {
                $conv->unread_count = $conv->messages->where('is_read', false)->where('sender_type', '!=', 'pengguna')->count();
                return $conv;
            });

        return view('pengguna.konsultasi', [
            'bidan' => $bidan,
            'dokter' => $dokter,
            'conversations' => $conversations,
        ]);
    })->name('pengguna.konsultasi');

    Route::post('/pengguna/konsultasi/start', function (Request $request) use ($ensureUserRole) {
        $ensureUserRole('pengguna');

        $validated = $request->validate([
            'professional_type' => ['required', 'in:bidan,dokter'],
            'professional_id' => ['required', 'integer'],
        ]);

        $user = auth()->user();

        $conversation = \App\Models\Conversation::firstOrCreate(
            [
                'pengguna_id' => $user->id,
                'professional_type' => $validated['professional_type'],
                'professional_id' => $validated['professional_id'],
            ]
        );

        return response()->json([
            'success' => true,
            'conversation_id' => $conversation->id,
        ]);
    })->name('pengguna.konsultasi.start');

    Route::post('/pengguna/konsultasi/send-message', function (Request $request) use ($ensureUserRole) {
        $ensureUserRole('pengguna');

        $validated = $request->validate([
            'conversation_id' => ['required', 'integer', 'exists:conversations,id'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $user = auth()->user();
        $conversation = \App\Models\Conversation::findOrFail($validated['conversation_id']);

        abort_unless($conversation->pengguna_id === $user->id, 403);

        $message = \App\Models\Message::create([
            'conversation_id' => $validated['conversation_id'],
            'sender_type' => 'pengguna',
            'sender_id' => $user->id,
            'message' => $validated['message'],
            'is_read' => false,
        ]);

        $conversation->update([
            'last_message' => $validated['message'],
            'last_message_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $message->id,
                'sender_type' => $message->sender_type,
                'message' => $message->message,
                'created_at' => $message->created_at->format('Y-m-d H:i:s'),
            ],
        ]);
    })->name('pengguna.konsultasi.send_message');

    Route::get('/pengguna/konsultasi/{conversation_id}/messages', function (Request $request, $conversation_id) use ($ensureUserRole) {
        $ensureUserRole('pengguna');

        $user = auth()->user();
        $conversation = \App\Models\Conversation::findOrFail($conversation_id);

        abort_unless($conversation->pengguna_id === $user->id, 403);

        $messages = \App\Models\Message::where('conversation_id', $conversation_id)
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($msg) {
                return [
                    'id' => $msg->id,
                    'sender_type' => $msg->sender_type,
                    'sender_id' => $msg->sender_id,
                    'message' => $msg->message,
                    'created_at' => $msg->created_at->format('Y-m-d H:i:s'),
                    'is_read' => $msg->is_read,
                ];
            });

        return response()->json([
            'success' => true,
            'messages' => $messages,
        ]);
    })->name('pengguna.konsultasi.get_messages');

    Route::get('/pengguna/kalkulator', function () use ($ensureUserRole) {
        $ensureUserRole('pengguna');

        return view('pengguna.kalkulator');
    })->name('pengguna.kalkulator');

    Route::get('/pengguna/jadwal', function () use ($ensureUserRole) {
        $ensureUserRole('pengguna');

        return view('pengguna.jadwal');
    })->name('pengguna.jadwal');

    Route::get('/pengguna/status-kehamilan', function () use ($ensureUserRole) {
        $ensureUserRole('pengguna');

        $user = auth()->user();
        $pengguna = \App\Models\Pengguna::with('anak')->find($user->id);

        if (!$pengguna) {
            abort(404, 'Pengguna tidak ditemukan.');
        }

        $isHamil = (bool) ($pengguna->is_hamil ?? false);
        $daftarAnak = $pengguna->anak ?? collect();
        $kehamilanKe = max((int) ($daftarAnak->max('anak_ke') ?? 0), 0) + ($isHamil ? 1 : 0);

        $ringkasan = [
            [
                'label' => 'Status Saat Ini',
                'value' => $isHamil ? 'Sedang Hamil' : 'Tidak Hamil',
                'icon' => 'bi-heart-pulse-fill',
                'tone' => $isHamil ? 'active' : 'muted',
            ],
            [
                'label' => 'Kehamilan Ke',
                'value' => $isHamil && $kehamilanKe > 0 ? 'Kehamilan ke-' . $kehamilanKe : 'Belum aktif',
                'icon' => 'bi-clipboard2-pulse-fill',
                'tone' => 'accent',
            ],
            [
                'label' => 'HPL',
                'value' => '—',
                'icon' => 'bi-calendar2-week-fill',
                'tone' => 'warning',
            ],
            [
                'label' => 'Risiko Kehamilan',
                'value' => 'Normal',
                'icon' => 'bi-shield-check',
                'tone' => 'success',
            ],
            [
                'label' => 'GPA',
                'value' => '0/0/0',
                'icon' => 'bi-diagram-3-fill',
                'tone' => 'primary',
            ],
        ];

        $riwayatKehamilan = $daftarAnak->map(function ($anak) {
            return [
                'judul' => $anak->nama_anak ? "Anak ke-{$anak->anak_ke} - {$anak->nama_anak}" : "Anak ke-{$anak->anak_ke}",
                'status' => $anak->status === 'dalam_kandungan' ? 'Dalam kandungan' : 'Riwayat anak',
                'tanggal' => $anak->tanggal_lahir ? $anak->tanggal_lahir->format('d M Y') : 'Belum diisi',
            ];
        })->values()->all();

        if ($isHamil) {
            array_unshift($riwayatKehamilan, [
                'judul' => 'Kehamilan aktif saat ini',
                'status' => 'Sedang dipantau',
                'tanggal' => 'Data dari profil pengguna',
            ]);
        }

        $riwayatKontrol = $isHamil ? [
            [
                'judul' => 'Kontrol ANC berikutnya',
                'waktu' => 'Belum dijadwalkan',
                'status' => 'Menunggu jadwal',
                'catatan' => 'Tambahkan jadwal kontrol untuk memantau tekanan darah, berat badan, dan perkembangan janin.',
            ],
            [
                'judul' => 'Pemeriksaan laboratorium',
                'waktu' => 'Belum tercatat',
                'status' => 'Belum ada data',
                'catatan' => 'Hasil pemeriksaan akan tampil di sini setelah dicatat.',
            ],
            [
                'judul' => 'Konsultasi nutrisi',
                'waktu' => 'Belum tercatat',
                'status' => 'Belum ada data',
                'catatan' => 'Catatan makan dan suplementasi akan muncul saat riwayat kontrol diisi.',
            ],
        ] : [];

        return view('pengguna.statusKehamilan', [
            'pengguna' => $pengguna,
            'isHamil' => $isHamil,
            'kehamilanKe' => $kehamilanKe,
            'ringkasan' => $ringkasan,
            'riwayatKontrol' => $riwayatKontrol,
            'riwayatKehamilan' => $riwayatKehamilan,
        ]);
    })->name('pengguna.status_kehamilan');

    Route::get('/pengguna/buku-kia', function () use ($ensureUserRole) {
        $ensureUserRole('pengguna');

        $user = auth()->user();
        $pengguna = \App\Models\Pengguna::with('anak')->find($user->id);

        if (!$pengguna) {
            abort(404, 'Pengguna tidak ditemukan.');
        }

        $isHamil = (bool) ($pengguna->is_hamil ?? false);
        $daftarAnak = $pengguna->anak ?? [];

        $bukuKiaCards = [];

        // Add child records from database
        foreach ($daftarAnak as $index => $anak) {
            $bukuKiaCards[] = [
                'anak_ke' => $anak->anak_ke,
                'nama_anak' => $anak->nama_anak,
                'label' => $anak->nama_anak ? "Anak ke-{$anak->anak_ke} - {$anak->nama_anak}" : "Anak ke-{$anak->anak_ke}",
                'status' => $anak->status === 'dalam_kandungan' ? 'Dalam kandungan' : 'Data anak',
                'tanggal_lahir' => $anak->tanggal_lahir,
            ];
        }

        // Add current pregnancy if is_hamil = true
        if ($isHamil) {
            $maxAnakKe = $daftarAnak->max('anak_ke') ?? 0;
            $nextAnakKe = $maxAnakKe + 1;

            $bukuKiaCards[] = [
                'anak_ke' => $nextAnakKe,
                'nama_anak' => null,
                'label' => "Kehamilan ke-{$nextAnakKe}",
                'status' => 'Dalam kandungan',
                'tanggal_lahir' => null,
            ];
        }

        $totalBukuKia = count($bukuKiaCards);

        return view('pengguna.bukuKIA', [
            'bukuKiaCards' => $bukuKiaCards,
            'totalBukuKia' => $totalBukuKia,
        ]);
    })->name('pengguna.buku_kia');

    Route::get('/pengguna/kia/pdf', function () use ($ensureUserRole) {
        $ensureUserRole('pengguna');

        $pdfPath = resource_path('views/buku/Buku KIA (Permenkes).pdf');

        abort_unless(file_exists($pdfPath), 404, 'File Buku KIA tidak ditemukan.');

        return response()->file($pdfPath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="Buku KIA (Permenkes).pdf"',
        ]);
    })->name('pengguna.kia.pdf');

    Route::get('/pengguna/kia/download', function () use ($ensureUserRole) {
        $ensureUserRole('pengguna');

        $pdfPath = resource_path('views/buku/Buku KIA (Permenkes).pdf');

        abort_unless(file_exists($pdfPath), 404, 'File Buku KIA tidak ditemukan.');

        return response()->download($pdfPath, 'Buku KIA (Permenkes).pdf');
    })->name('pengguna.kia.download');

    Route::get('/pengguna/pengaturan', function () use ($ensureUserRole) {
        $ensureUserRole('pengguna');

        return view('pengguna.pengaturan');
    })->name('pengguna.pengaturan');

// Forgot password routes (simple UI + send reset link)
Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('password.request');

Route::post('/forgot-password', function (Illuminate\Http\Request $request) {
    $request->validate(['email' => 'required|email']);

    $status = Password::sendResetLink($request->only('email'));

    return $status === Password::RESET_LINK_SENT
        ? back()->with('status', __($status))
        : back()->withErrors(['email' => __($status)]);
})->name('password.email');

// Explicit logout route to ensure named route exists and redirects to landing page
Route::post('/logout', function (Illuminate\Http\Request $request) {
    \Illuminate\Support\Facades\Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logout');
