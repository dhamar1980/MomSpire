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
use App\Http\Controllers\Auth\CustomLoginController;
use App\Http\Controllers\PasswordConfirmController;

// Custom password confirmation routes (bypass Fortify guard issues)
Route::get("/user/confirmed-password-status", function (
    \Illuminate\Http\Request $request,
) {
    return response()->json([
        "confirmed" => $request->session()->has("auth.password_confirmed_at"),
    ]);
})
    ->middleware("auth")
    ->name("password.confirmation");

Route::post("/user/confirm-password", [
    PasswordConfirmController::class,
    "confirm",
])
    ->middleware("auth")
    ->name("password.confirm.store");

Route::get("/", function () {
    session()->regenerateToken();

    return response()
        ->view("landingPage")
        ->header(
            "Cache-Control",
            "no-store, no-cache, must-revalidate, max-age=0",
        )
        ->header("Pragma", "no-cache")
        ->header("Expires", "0");
});

// Custom login routes (override Fortify)
Route::get("/login", [CustomLoginController::class, "showLoginForm"])->name(
    "login",
);
Route::post("/login", [CustomLoginController::class, "login"])->name(
    "login.submit",
);
Route::post("/logout", [CustomLoginController::class, "logout"])->name(
    "logout",
);
Route::get("/register", [CustomLoginController::class, "showLoginForm"])->name(
    "register",
); // Placeholder, adjust as needed

// Role-specific dashboards and admin-only user management
Route::middleware(["auth"])->group(function () {
    $ensureRole = function (string $role) {
        abort_unless(auth()->check() && auth()->user()->role === $role, 403);
    };

    $buildBidanUserQuery = function (Request $request) {
        $search = trim((string) $request->query("q", ""));

        return \App\Models\Pengguna::query()
            ->withCount(["anak", "conversations"])
            ->when($search !== "", function ($query) use ($search) {
                $query->where(function ($nested) use ($search) {
                    $nested
                        ->where("name", "like", "%" . $search . "%")
                        ->orWhere("email", "like", "%" . $search . "%");
                });
            })
            ->orderBy("name");
    };

    $buildBidanScheduleQuery = function (Request $request) {
        if (!Schema::hasTable("jadwal_pemantauan")) {
            return collect();
        }

        $search = trim((string) $request->query("q", ""));

        return \App\Models\JadwalPemantauan::with(["pengguna", "bidan"])
            ->when($search !== "", function ($query) use ($search) {
                $query->whereHas("pengguna", function ($penggunaQuery) use (
                    $search,
                ) {
                    $penggunaQuery
                        ->where("name", "like", "%" . $search . "%")
                        ->orWhere("email", "like", "%" . $search . "%");
                });
            })
            ->orderBy("tanggal")
            ->orderByRaw('COALESCE(waktu, "23:59:59") asc');
    };

    $buildDokterUserQuery = function (Request $request) {
        $search = trim((string) $request->query("q", ""));

        return \App\Models\Pengguna::query()
            ->withCount(["anak", "conversations"])
            ->when($search !== "", function ($query) use ($search) {
                $query->where(function ($nested) use ($search) {
                    $nested
                        ->where("name", "like", "%" . $search . "%")
                        ->orWhere("email", "like", "%" . $search . "%");
                });
            })
            ->orderBy("name");
    };

    $buildDokterScheduleQuery = function (Request $request) {
        if (!Schema::hasTable("jadwal_pemantauan")) {
            return collect();
        }

        $search = trim((string) $request->query("q", ""));

        return \App\Models\JadwalPemantauan::with(["pengguna", "dokter"])
            ->when($search !== "", function ($query) use ($search) {
                $query->whereHas("pengguna", function ($penggunaQuery) use (
                    $search,
                ) {
                    $penggunaQuery
                        ->where("name", "like", "%" . $search . "%")
                        ->orWhere("email", "like", "%" . $search . "%");
                });
            })
            ->orderBy("tanggal")
            ->orderByRaw('COALESCE(waktu, "23:59:59") asc');
    };

    Route::get("/admin/dashboard", function () use ($ensureRole) {
        $ensureRole("admin");

        $pengguna = DB::table("pengguna")
            ->orderBy("name")
            ->get()
            ->map(function ($user) {
                return (object) [
                    "id" => $user->id,
                    "name" => $user->name,
                    "email" => $user->email,
                    "role" => "pengguna",
                    "is_hamil" => (int) ($user->is_hamil ?? 0),
                ];
            });

        $bidan = DB::table("bidan")
            ->orderBy("name")
            ->get()
            ->map(function ($user) {
                return (object) [
                    "id" => $user->id,
                    "name" => $user->name,
                    "email" => $user->email,
                    "role" => "bidan",
                    "is_hamil" => 0,
                ];
            });

        $dokter = DB::table("dokter")
            ->orderBy("name")
            ->get()
            ->map(function ($user) {
                return (object) [
                    "id" => $user->id,
                    "name" => $user->name,
                    "email" => $user->email,
                    "role" => "dokter",
                    "is_hamil" => 0,
                ];
            });

        $admin = DB::table("admin")
            ->orderBy("name")
            ->get()
            ->map(function ($user) {
                return (object) [
                    "id" => $user->id,
                    "name" => $user->name,
                    "email" => $user->email,
                    "role" => "admin",
                    "is_hamil" => 0,
                ];
            });

        $allUsers = collect()
            ->merge($pengguna)
            ->merge($bidan)
            ->merge($dokter)
            ->merge($admin)
            ->values();

        return view("Admin.DashboardAdmin", [
            "penggunaCount" => $pengguna->count(),
            "bidanCount" => $bidan->count(),
            "dokterCount" => $dokter->count(),
            "adminCount" => $admin->count(),
            "ibuHamilAktifCount" => $pengguna->where("is_hamil", 1)->count(),
            "articleCount" => ArtikelEdukasi::count(),
            "dashboardUsers" => $allUsers,
        ]);
    })->name("admin.dashboard");

    Route::get("/admin/users", function () use ($ensureRole) {
        $ensureRole("admin");

        $pengguna = DB::table("pengguna")->orderBy("name")->get();
        $bidan = DB::table("bidan")->orderBy("name")->get();
        $dokter = DB::table("dokter")->orderBy("name")->get();
        $admin = DB::table("admin")->orderBy("name")->get();

        $users = collect()
            ->merge(
                $pengguna->map(
                    fn($u) => (object) [
                        ...(array) $u,
                        "type" => "pengguna",
                        "is_hamil" => (int) ($u->is_hamil ?? 0),
                    ],
                ),
            )
            ->merge(
                $bidan->map(
                    fn($u) => (object) [
                        ...(array) $u,
                        "type" => "bidan",
                        "is_hamil" => 0,
                    ],
                ),
            )
            ->merge(
                $dokter->map(
                    fn($u) => (object) [
                        ...(array) $u,
                        "type" => "dokter",
                        "is_hamil" => 0,
                    ],
                ),
            )
            ->merge(
                $admin->map(
                    fn($u) => (object) [
                        ...(array) $u,
                        "type" => "admin",
                        "is_hamil" => 0,
                    ],
                ),
            )
            ->sortBy("name");

        return view("Admin.ManajemenUser", [
            "users" => $users,
        ]);
    })->name("admin.users");

    Route::post("/admin/users", function (Request $request) use ($ensureRole) {
        $ensureRole("admin");

        $validated = $request->validate([
            "name" => ["required", "string", "max:255"],
            "email" => ["required", "string", "email", "max:255"],
            "role" => ["required", "in:pengguna,bidan,dokter,admin"],
            "password" => ["required", "string", "min:8"],
        ]);
        $isHamil = $request->boolean("is_hamil");

        $hashedPassword = Hash::make($validated["password"]);
        $roleTable = $validated["role"];

        // Check email uniqueness across all tables
        foreach (["pengguna", "bidan", "dokter", "admin"] as $table) {
            abort_if(
                DB::table($table)
                    ->where("email", $validated["email"])
                    ->exists(),
                422,
                "Email sudah terdaftar.",
            );
        }

        // Insert into users table
        $user = User::create([
            "name" => $validated["name"],
            "email" => $validated["email"],
            "role" => $validated["role"],
            "password" => $hashedPassword,
        ]);

        // Insert into role-specific table
        $roleInsert = [
            "id" => $user->id,
            "name" => $validated["name"],
            "email" => $validated["email"],
            "password" => $hashedPassword,
            "created_at" => now(),
            "updated_at" => now(),
        ];

        if ($roleTable === "pengguna") {
            $roleInsert["is_hamil"] = $isHamil ? 1 : 0;
        }

        DB::table($roleTable)->insert($roleInsert);

        return redirect()
            ->route("admin.users")
            ->with("success", "Akun berhasil ditambahkan.");
    })->name("admin.users.store");

    Route::post("/admin/users/update", function (Request $request) use (
        $ensureRole,
    ) {
        $ensureRole("admin");

        $validated = $request->validate([
            "id" => ["required", "integer"],
            "name" => ["required", "string", "max:255"],
            "email" => ["required", "string", "email", "max:255"],
            "role" => ["required", "in:pengguna,bidan,dokter,admin"],
            "password" => ["nullable", "string", "min:8"],
            "type" => ["required", "in:pengguna,bidan,dokter,admin"],
        ]);
        $isHamil = $request->boolean("is_hamil");

        $user = User::findOrFail($validated["id"]);
        abort_unless(auth()->id() !== $user->id, 403);
        abort_if(
            $user->role === "admin" && $validated["role"] !== "admin",
            403,
            "Role admin tidak dapat diubah.",
        );

        // Find user in the original table
        $originalTable = $validated["type"];
        $userInTable = DB::table($originalTable)
            ->where("id", $validated["id"])
            ->first();
        abort_if(!$userInTable, 404, "User tidak ditemukan.");

        // Check email uniqueness across all tables
        foreach (["pengguna", "bidan", "dokter", "admin"] as $table) {
            $exists = DB::table($table)
                ->where("email", $validated["email"])
                ->where("id", "!=", $validated["id"])
                ->exists();
            abort_if($exists, 422, "Email sudah dipakai.");
        }

        $user->name = $validated["name"];
        $user->email = $validated["email"];
        $user->role = $user->role === "admin" ? "admin" : $validated["role"];

        if (!empty($validated["password"])) {
            $user->password = Hash::make($validated["password"]);
        }

        $user->save();

        // Update in role-specific table
        $updateData = [
            "name" => $validated["name"],
            "email" => $validated["email"],
            "updated_at" => now(),
        ];

        if ($originalTable === "pengguna") {
            $updateData["is_hamil"] = $isHamil ? 1 : 0;
        }

        if (!empty($validated["password"])) {
            $updateData["password"] = Hash::make($validated["password"]);
        }

        DB::table($originalTable)
            ->where("id", $validated["id"])
            ->update($updateData);

        return redirect()
            ->route("admin.users")
            ->with("success", "Akun berhasil diupdate.");
    })->name("admin.users.update");

    Route::post("/admin/users/delete", function (Request $request) use (
        $ensureRole,
    ) {
        $ensureRole("admin");

        $validated = $request->validate([
            "id" => ["required", "integer"],
            "type" => ["required", "in:pengguna,bidan,dokter,admin"],
        ]);

        $user = User::findOrFail($validated["id"]);
        abort_unless(auth()->id() !== $user->id, 403);
        abort_if(
            $user->role === "admin",
            403,
            "Akun admin tidak dapat dihapus.",
        );

        $user->delete();

        // Delete from role-specific table
        DB::table($validated["type"])->where("id", $validated["id"])->delete();

        return redirect()
            ->route("admin.users")
            ->with("success", "Akun berhasil dihapus.");
    })->name("admin.users.destroy");

    Route::get("/admin/articles", function () use ($ensureRole) {
        $ensureRole("admin");

        $articles = ArtikelEdukasi::query()->latest()->get();

        return view("Admin.ManajemenArtikel", [
            "articles" => $articles,
            "articleStats" => [
                "total" => $articles->count(),
                "trimester1" => $articles
                    ->where("category", "trimester_1")
                    ->count(),
                "trimester2" => $articles
                    ->where("category", "trimester_2")
                    ->count(),
                "trimester3" => $articles
                    ->where("category", "trimester_3")
                    ->count(),
            ],
        ]);
    })->name("admin.articles");

    Route::post("/admin/articles", function (Request $request) use (
        $ensureRole,
    ) {
        $ensureRole("admin");

        $validated = $request->validate([
            "title" => ["required", "string", "max:255"],
            "category" => [
                "required",
                "in:umum,trimester_1,trimester_2,trimester_3",
            ],
            "image_url" => ["nullable", "url", "max:2048"],
            "summary" => ["required", "string", "max:2000"],
            "article_url" => ["required", "url", "max:2048"],
        ]);

        $categoryMap = [
            "trimester_1" => [1, 13],
            "trimester_2" => [14, 27],
            "trimester_3" => [28, 42],
        ];

        $range = $categoryMap[$validated["category"]] ?? [null, null];

        ArtikelEdukasi::create([
            "title" => $validated["title"],
            "category" => $validated["category"],
            "image_url" => $validated["image_url"] ?? null,
            "summary" => $validated["summary"],
            "article_url" => $validated["article_url"],
            "min_week" => $range[0],
            "max_week" => $range[1],
        ]);

        return back()->with("success", "Artikel edukasi berhasil ditambahkan.");
    })->name("admin.articles.store");

    Route::delete("/admin/articles/{article}", function (
        ArtikelEdukasi $article,
    ) use ($ensureRole) {
        $ensureRole("admin");

        $article->delete();

        return back()->with("success", "Artikel edukasi berhasil dihapus.");
    })->name("admin.articles.destroy");

    Route::get("/admin/kia", function () use ($ensureRole) {
        $ensureRole("admin");

        return view("Admin.BukuKIA");
    })->name("admin.kia");

    Route::get("/admin/kia/pdf", function () use ($ensureRole) {
        $ensureRole("admin");

        $pdfPath = resource_path("views/buku/Buku KIA (Permenkes).pdf");

        abort_unless(
            file_exists($pdfPath),
            404,
            "File Buku KIA tidak ditemukan.",
        );

        return response()->file($pdfPath, [
            "Content-Type" => "application/pdf",
            "Content-Disposition" =>
                'inline; filename="Buku KIA (Permenkes).pdf"',
        ]);
    })->name("admin.kia.pdf");

    Route::get("/admin/settings", function (Request $request) use (
        $ensureRole,
    ) {
        $ensureRole("admin");

        $adminUser = $request->user();
        $browserSessions = [];

        if (config("session.driver") === "database") {
            $browserSessions = DB::connection(config("session.connection"))
                ->table(config("session.table", "sessions"))
                ->where("user_id", $adminUser->getAuthIdentifier())
                ->orderByDesc("last_activity")
                ->get()
                ->map(function ($session) use ($request) {
                    $agent = tap(
                        new Agent(),
                        fn($agent) => $agent->setUserAgent(
                            $session->user_agent,
                        ),
                    );

                    return [
                        "agent" => [
                            "is_desktop" => $agent->isDesktop(),
                            "platform" => $agent->platform(),
                            "browser" => $agent->browser(),
                        ],
                        "ip_address" => $session->ip_address,
                        "is_current_device" =>
                            $session->id === $request->session()->getId(),
                        "last_active" => Carbon::createFromTimestamp(
                            $session->last_activity,
                        )->diffForHumans(),
                    ];
                })
                ->all();
        }

        return view("Admin.PengaturanAdmin", [
            "adminUser" => $adminUser,
            "browserSessions" => $browserSessions,
            "twoFactorEnabled" => filled($adminUser->two_factor_secret),
            "twoFactorConfirmed" => filled($adminUser->two_factor_confirmed_at),
            "requiresTwoFactorConfirmation" => Features::optionEnabled(
                Features::twoFactorAuthentication(),
                "confirm",
            ),
        ]);
    })->name("admin.settings");

    Route::post("/admin/settings/profile", function (Request $request) use (
        $ensureRole,
    ) {
        $ensureRole("admin");

        $validated = $request->validate([
            "name" => ["required", "string", "max:255"],
            "email" => ["required", "string", "email", "max:255"],
            "no_telp" => ["nullable", "string", "max:30"],
        ]);

        $adminId = auth()->id();
        $user = User::findOrFail($adminId);

        foreach (["users", "admin"] as $table) {
            $query = DB::table($table)->where("email", $validated["email"]);
            if ($table === "users" || Schema::hasColumn($table, "id")) {
                $query->where("id", "!=", $adminId);
            }
            abort_if($query->exists(), 422, "Email sudah dipakai.");
        }

        $user->name = $validated["name"];
        $user->email = $validated["email"];
        if (Schema::hasColumn("users", "no_telp")) {
            $user->no_telp = $validated["no_telp"] ?? null;
        }
        $user->save();

        DB::table("admin")
            ->where("id", $adminId)
            ->update([
                "name" => $validated["name"],
                "email" => $validated["email"],
                ...Schema::hasColumn("admin", "no_telp")
                    ? ["no_telp" => $validated["no_telp"] ?? null]
                    : [],
                "updated_at" => now(),
            ]);

        return back()->with("success", "Profil admin berhasil diperbarui.");
    })->name("admin.settings.profile");

    Route::post("/admin/settings/password", function (Request $request) use (
        $ensureRole,
    ) {
        $ensureRole("admin");

        $validated = $request->validate([
            "current_password" => ["required", "string"],
            "new_password" => ["required", "string", "min:8", "confirmed"],
        ]);

        $adminId = auth()->id();
        $user = User::findOrFail($adminId);

        abort_unless(
            Hash::check($validated["current_password"], $user->password),
            422,
            "Password saat ini salah.",
        );

        $hashedPassword = Hash::make($validated["new_password"]);
        $user->password = $hashedPassword;
        $user->save();

        DB::table("admin")
            ->where("id", $adminId)
            ->update([
                "password" => $hashedPassword,
                "updated_at" => now(),
            ]);

        return back()->with("success", "Password admin berhasil diperbarui.");
    })->name("admin.settings.password");

    Route::get("/bidan/dashboard", function () use ($ensureRole) {
        $ensureRole("bidan");

        return view("bidan.dashboardBidan", [
            "penggunaCount" => DB::table("pengguna")->count(),
            "bidanCount" => DB::table("bidan")->count(),
            "dokterCount" => DB::table("dokter")->count(),
            "jadwalCount" => Schema::hasTable("jadwal_pemantauan")
                ? DB::table("jadwal_pemantauan")->count()
                : 0,
            "konsultasiCount" => DB::table("conversations")
                ->where("professional_type", "bidan")
                ->where("professional_id", auth()->id())
                ->count(),
            "recentPengguna" => DB::table("pengguna")
                ->latest("created_at")
                ->take(5)
                ->get(["id", "name", "email", "created_at"])
                ->map(
                    fn($u) => (object) [
                        ...(array) $u,
                        "created_at" => \Carbon\Carbon::parse($u->created_at),
                    ],
                ),
            "recentBidan" => DB::table("bidan")
                ->latest("created_at")
                ->take(5)
                ->get(["id", "name", "email", "created_at"])
                ->map(
                    fn($u) => (object) [
                        ...(array) $u,
                        "created_at" => \Carbon\Carbon::parse($u->created_at),
                    ],
                ),
        ]);
    })->name("bidan.dashboard");
    Route::get("/bidan/pengguna", function (Request $request) use (
        $ensureRole,
        $buildBidanUserQuery,
    ) {
        $ensureRole("bidan");

        $penggunaList = $buildBidanUserQuery($request)->get();

        return view("bidan.detailPengguna", [
            "penggunaList" => $penggunaList,
            "search" => trim((string) $request->query("q", "")),
        ]);
    })->name("bidan.pengguna");

    Route::get("/bidan/buku-kia", function (Request $request) use (
        $ensureRole,
    ) {
        $ensureRole("bidan");

        $templates = \App\Models\TemplateBukuKIA::orderBy(
            "created_at",
            "desc",
        )->get();

        $bukuKIA = \App\Models\BukuKIA::where("created_by", auth()->id())
            ->with("pengguna")
            ->orderByDesc("created_at")
            ->get();

        return view("bidan.bukuKIA", [
            "templates" => $templates,
            "bukuKIA" => $bukuKIA,
        ]);
    })->name("bidan.bukuKIA");

    Route::get("/bidan/pengguna/{id}/buku-kia", function (
        Request $request,
        $id,
    ) use ($ensureRole) {
        $ensureRole("bidan");

        $pengguna = \App\Models\Pengguna::withCount([
            "anak",
            "conversations",
        ])->findOrFail($id);

        $entries = \App\Models\BukuKIA::where("pengguna_id", $id)
            ->orderByDesc("created_at")
            ->get();
        $templates = \App\Models\TemplateBukuKIA::orderBy(
            "created_at",
            "desc",
        )->get();

        return view("bidan.bukuKIAPengguna", [
            "pengguna" => $pengguna,
            "entries" => $entries,
            "templates" => $templates,
        ]);
    })->name("bidan.pengguna.bukuKIA");

    Route::get("/bidan/pengguna/{id}/buku-kia/input", function (
        Request $request,
        $id,
    ) use ($ensureRole) {
        $ensureRole("bidan");

        $pengguna = \App\Models\Pengguna::withCount([
            "anak",
            "conversations",
        ])->findOrFail($id);

        // Get all DataKIA for this pengguna
        $bukuKiaList = \App\Models\DataKia::where(
            "user_id",
            $pengguna->user_id ?? $id,
        )
            ->with(["ibu"])
            ->orderByDesc("created_at")
            ->get();

        // Determine selected KIA
        $selectedKiaId = $request->query("buku_id");
        $selectedKia = null;

        if ($selectedKiaId) {
            $selectedKia = $bukuKiaList->firstWhere("id", $selectedKiaId);
        }

        // If no selected KIA, use the first one or null
        if (!$selectedKia && $bukuKiaList->isNotEmpty()) {
            $selectedKia = $bukuKiaList->first();
            $selectedKiaId = $selectedKia->id;
        }

        // Load relations for selected KIA
        if ($selectedKia) {
            $selectedKia->load([
                "ibu",
                "riwayat",
                "pelayananKesehatanIbu",
                "evaluasiKesehatanIbu",
                "pemeriksaanTrimester1",
                "catatanPelayananTrimester1",
                "pemeriksaanTrimester2",
                "catatanPelayananTrimester2",
            ]);
        }

        return view("nakes.buku-kia-pengguna", [
            "pengguna" => $pengguna,
            "role" => "bidan",
            "bukuKiaList" => $bukuKiaList,
            "selectedKia" => $selectedKia,
            "selectedKiaId" => $selectedKiaId,
        ]);
    })->name("bidan.pengguna.bukuKIA.input");

    Route::get("/bidan/pengguna/{penggunaId}/buku-kia/{kiaId}/pdf", function (
        $penggunaId,
        $kiaId,
    ) use ($ensureRole) {
        $ensureRole("bidan");

        $pengguna = \App\Models\Pengguna::findOrFail($penggunaId);
        $dataKia = \App\Models\DataKia::where("id", $kiaId)
            ->where("user_id", $pengguna->user_id ?? $penggunaId)
            ->firstOrFail();

        return app(\App\Http\Controllers\DataKiaController::class)->exportPdf(
            $dataKia->id,
        );
    })->name("bidan.pengguna.bukuKIA.pdf");

    Route::get(
        "/bidan/pengguna/{penggunaId}/buku-kia/{kiaId}/download",
        function ($penggunaId, $kiaId) use ($ensureRole) {
            $ensureRole("bidan");

            $pengguna = \App\Models\Pengguna::findOrFail($penggunaId);
            $dataKia = \App\Models\DataKia::with("ibu")
                ->where("id", $kiaId)
                ->where("user_id", $pengguna->user_id ?? $penggunaId)
                ->firstOrFail();

            $response = app(
                \App\Http\Controllers\DataKiaController::class,
            )->exportPdf($dataKia->id);
            $response->headers->set(
                "Content-Disposition",
                'attachment; filename="Buku_KIA_' .
                    ($dataKia->ibu->nama ?? "KIA") .
                    '.pdf"',
            );

            return $response;
        },
    )->name("bidan.pengguna.bukuKIA.download");

    Route::post("/bidan/pengguna/{penggunaId}/buku-kia/tambah", function (
        Request $request,
        $penggunaId,
    ) use ($ensureRole) {
        $ensureRole("bidan");

        $pengguna = \App\Models\Pengguna::findOrFail($penggunaId);

        // Create new DataKIA for this pengguna
        $dataKia = \App\Models\DataKia::create([
            "user_id" => $pengguna->user_id ?? $penggunaId,
        ]);

        return response()->json([
            "success" => true,
            "id" => $dataKia->id,
            "message" => "Buku KIA baru berhasil dibuat",
        ]);
    })->name("bidan.pengguna.bukuKIA.tambah");

    Route::post("/bidan/pengguna/{id}/buku-kia/input", function (
        Request $request,
        $id,
    ) use ($ensureRole) {
        $ensureRole("bidan");

        $pengguna = \App\Models\Pengguna::findOrFail($id);

        // Get the DataKIA from the form
        $kiaId = $request->input("data_kia_id");
        $dataKia = \App\Models\DataKia::findOrFail($kiaId);

        $clean = function ($val) {
            return $val === "" ? null : $val;
        };

        // 1. Core Data
        $dataKia->update([
            "faskes_dikeluarkan" => $clean($request->faskes_dikeluarkan),
            "tanggal_dikeluarkan" => $clean($request->tanggal_dikeluarkan),
            "kab_kota_dikeluarkan" => $clean($request->kab_kota_dikeluarkan),
            "provinsi_dikeluarkan" => $clean($request->provinsi_dikeluarkan),
        ]);

        // 2. Identitas Ibu
        $dataKia->ibu()->updateOrCreate(
            [],
            [
                "nama" => $clean($request->nama_ibu),
                "nik" => $clean($request->nik),
                "no_jkn" => $clean($request->no_jkn_ibu),
                "faskes_tk1" => $clean($request->faskes_tk1_ibu),
                "faskes_rujukan" => $clean($request->faskes_rujukan_ibu),
                "tempat_lahir" => $clean($request->tempat_lahir),
                "tanggal_lahir" => $clean($request->tanggal_lahir),
                "pendidikan" => $clean($request->pendidikan),
                "pekerjaan" => $clean($request->pekerjaan),
                "alamat" => $clean($request->alamat),
                "telepon" => $clean($request->telepon_ibu),
                "golongan_darah" => $clean($request->golongan_darah),
            ],
        );

        // 3. Identitas Suami
        $dataKia->suami()->updateOrCreate(
            [],
            [
                "nama" => $clean($request->nama_suami),
                "nik" => $clean($request->nik_suami),
                "no_jkn" => $clean($request->no_jkn_suami),
                "faskes_tk1" => $clean($request->faskes_tk1_suami),
                "faskes_rujukan" => $clean($request->faskes_rujukan_suami),
                "tempat_lahir" => $clean($request->tempat_lahir_suami),
                "tanggal_lahir" => $clean($request->tanggal_lahir_suami),
                "pendidikan" => $clean($request->pendidikan_suami),
                "pekerjaan" => $clean($request->pekerjaan_suami),
                "alamat" => $clean($request->alamat_rumah_suami),
                "telepon" => $clean($request->telepon_suami),
                "golongan_darah" => $clean($request->golongan_darah_suami),
            ],
        );

        // 4. Identitas Anak
        $dataKia->anak()->updateOrCreate(
            [],
            [
                "nama" => $clean($request->nama_anak),
                "nik" => $clean($request->nik_anak),
                "no_jkn" => $clean($request->no_jkn_anak),
                "faskes_tk1" => $clean($request->faskes_tk1_anak),
                "faskes_rujukan" => $clean($request->faskes_rujukan_anak),
                "tempat_lahir" => $clean($request->tempat_lahir_anak),
                "tanggal_lahir" => $clean($request->tanggal_lahir_anak),
                "anak_ke" => $clean($request->anak_ke),
                "no_akta_kelahiran" => $clean($request->no_akta_kelahiran_anak),
                "telepon" => $clean($request->telepon_anak),
                "alamat" => $clean($request->alamat_anak),
                "golongan_darah" => $clean($request->golongan_darah_anak),
            ],
        );

        // 5. Layanan & Pembiayaan
        $dataKia->layanan()->updateOrCreate(
            [],
            [
                "puskesmas_domisili" => $clean($request->puskesmas_domisili),
                "no_reg_kohort_ibu" => $clean($request->no_reg_kohort_ibu),
                "no_reg_kohort_bayi" => $clean($request->no_reg_kohort_bayi),
                "no_reg_kohort_balita" => $clean(
                    $request->no_reg_kohort_balita,
                ),
                "no_catatan_medik_rs" => $clean($request->no_catatan_medik_rs),
                "asuransi_lain" => $clean($request->asuransi_lain),
                "no_asuransi_lain" => $clean($request->no_asuransi_lain),
                "tanggal_berlaku_asuransi_lain" => $clean(
                    $request->tanggal_berlaku_asuransi_lain,
                ),
            ],
        );

        // 6. Riwayat Kesehatan
        $dataKia->riwayat()->updateOrCreate(
            [],
            [
                "hpht" => $clean($request->hpht),
                "htp" => $clean($request->htp),
                "lingkar_lengan_atas" => $clean($request->lingkar_lengan_atas),
                "tinggi_badan" => $clean($request->tinggi_badan),
            ],
        );

        return back()->with("success", "Data Buku KIA berhasil disimpan.");
    })->name("bidan.pengguna.bukuKIA.input.store");

    Route::post("/bidan/pengguna/{id}/buku-kia", function (
        Request $request,
        $id,
    ) use ($ensureRole) {
        $ensureRole("bidan");

        $pengguna = \App\Models\Pengguna::findOrFail($id);

        $validated = $request->validate([
            "judul" => ["nullable", "string", "max:255"],
            "catatan" => ["nullable", "string", "max:5000"],
            "file" => [
                "nullable",
                "file",
                "mimes:pdf,jpg,jpeg,png",
                "max:5120",
            ],
        ]);

        $filePath = null;
        if ($request->hasFile("file")) {
            $filePath = $request->file("file")->store("buku_kia", "public");
        }

        \App\Models\BukuKIA::create([
            "pengguna_id" => $pengguna->id,
            "created_by" => auth()->id(),
            "judul" => $validated["judul"] ?? null,
            "catatan" => $validated["catatan"] ?? null,
            "file_path" => $filePath,
        ]);

        return back()->with("success", "Catatan Buku KIA berhasil disimpan.");
    })->name("bidan.pengguna.bukuKIA.store");

    Route::delete("/bidan/pengguna/buku-kia/{bukuId}", function (
        Request $request,
        $bukuId,
    ) use ($ensureRole) {
        $ensureRole("bidan");

        $buku = \App\Models\BukuKIA::findOrFail($bukuId);
        abort_unless(
            $buku->created_by === auth()->id() ||
                auth()->user()->role === "admin",
            403,
        );

        $buku->delete();

        return back()->with("success", "Catatan Buku KIA berhasil dihapus.");
    })->name("bidan.pengguna.bukuKIA.destroy");

    Route::get("/bidan/konsultasi", function (Request $request) use (
        $ensureRole,
    ) {
        $ensureRole("bidan");

        $bidanId = auth()->id();
        $conversations = \App\Models\Conversation::with([
            "pengguna" => function ($query) {
                $query->select("id", "name", "is_online", "last_seen");
            },
            "messages",
        ])
            ->where("professional_type", "bidan")
            ->where("professional_id", $bidanId)
            ->orderByDesc("last_message_at")
            ->get()
            ->map(function ($conversation) {
                $conversation->unread_count = $conversation->messages
                    ->where("is_read", false)
                    ->where("sender_type", "pengguna")
                    ->count();
                return $conversation;
            });

        $selectedConversation =
            $conversations->firstWhere(
                "id",
                (int) $request->query("conversation_id"),
            ) ?? $conversations->first();

        $messages = collect();
        if ($selectedConversation) {
            $messages = $selectedConversation->messages->map(function ($msg) {
                return [
                    "id" => $msg->id,
                    "sender_type" => $msg->sender_type,
                    "sender_id" => $msg->sender_id,
                    "message" => $msg->message,
                    "created_at" => $msg->created_at,
                    "is_read" => $msg->is_read,
                ];
            });
        }

        return view("bidan.konsultasi", [
            "conversations" => $conversations,
            "selectedConversation" => $selectedConversation,
            "messages" => $messages,
        ]);
    })->name("bidan.konsultasi");

    Route::post("/bidan/konsultasi/send-message", function (
        Request $request,
    ) use ($ensureRole) {
        $ensureRole("bidan");

        $validated = $request->validate([
            "conversation_id" => [
                "required",
                "integer",
                "exists:conversations,id",
            ],
            "message" => ["required", "string", "max:5000"],
        ]);

        $bidanId = auth()->id();
        $conversation = \App\Models\Conversation::findOrFail(
            $validated["conversation_id"],
        );
        abort_unless(
            $conversation->professional_type === "bidan" &&
                (int) $conversation->professional_id === (int) $bidanId,
            403,
        );

        $message = \App\Models\Message::create([
            "conversation_id" => $validated["conversation_id"],
            "sender_type" => "bidan",
            "sender_id" => $bidanId,
            "message" => $validated["message"],
            "is_read" => false,
        ]);

        $conversation->update([
            "last_message" => $validated["message"],
            "last_message_at" => now(),
        ]);

        // created_at dari accessor Model (sudah otomatis WIB karena getCreatedAtAttribute)
        return response()->json([
            "success" => true,
            "message" => [
                "id" => $message->id,
                "sender_type" => $message->sender_type,
                "message" => $message->message,
                "created_at" => $message->created_at,
            ],
        ]);
    })->name("bidan.konsultasi.send_message");

    Route::get("/bidan/konsultasi/{conversation_id}/messages", function (
        Request $request,
        $conversation_id,
    ) use ($ensureRole) {
        $ensureRole("bidan");

        $bidanId = auth()->id();
        $conversation = \App\Models\Conversation::with("pengguna")->findOrFail(
            $conversation_id,
        );
        abort_unless(
            $conversation->professional_type === "bidan" &&
                (int) $conversation->professional_id === (int) $bidanId,
            403,
        );

        $messages = \App\Models\Message::where(
            "conversation_id",
            $conversation_id,
        )
            ->orderBy("created_at", "asc")
            ->get();

        \App\Models\Message::where("conversation_id", $conversation_id)
            ->where("sender_type", "pengguna")
            ->where("is_read", false)
            ->update(["is_read" => true]);

        return response()->json([
            "success" => true,
            "messages" => $messages->map(function ($msg) {
                return [
                    "id" => $msg->id,
                    "sender_type" => $msg->sender_type,
                    "sender_id" => $msg->sender_id,
                    "message" => $msg->message,
                    "created_at" => $msg->created_at,
                    "is_read" => $msg->is_read,
                ];
            }),
        ]);
    })->name("bidan.konsultasi.get_messages");

    Route::get("/bidan/jadwal", function (Request $request) use (
        $ensureRole,
        $buildBidanScheduleQuery,
    ) {
        $ensureRole("bidan");

        $jadwalList = $buildBidanScheduleQuery($request);

        return view("bidan.jadwal", [
            "jadwalList" =>
                $jadwalList instanceof \Illuminate\Database\Eloquent\Builder
                    ? $jadwalList->get()
                    : $jadwalList,
            "penggunaList" => \App\Models\Pengguna::orderBy("name")->get([
                "id",
                "name",
                "email",
                "is_hamil",
            ]),
            "search" => trim((string) $request->query("q", "")),
            "selectedSchedule" => null,
        ]);
    })->name("bidan.jadwal");

    Route::post("/bidan/jadwal", function (Request $request) use ($ensureRole) {
        $ensureRole("bidan");

        $validated = $request->validate([
            "jadwal_id" => ["nullable", "integer"],
            "pengguna_id" => ["required", "integer", "exists:pengguna,id"],
            "jenis" => ["required", "in:kontrol,imunisasi"],
            "judul" => ["required", "string", "max:255"],
            "tanggal" => ["required", "date"],
            "waktu" => ["nullable", "date_format:H:i"],
            "catatan" => ["nullable", "string", "max:2000"],
            "status" => ["required", "in:terjadwal,selesai,dibatalkan"],
        ]);

        $payload = [
            "pengguna_id" => $validated["pengguna_id"],
            "bidan_id" => auth()->id(),
            "jenis" => $validated["jenis"],
            "judul" => $validated["judul"],
            "tanggal" => $validated["tanggal"],
            "waktu" => $validated["waktu"] ?? null,
            "catatan" => $validated["catatan"] ?? null,
            "status" => $validated["status"],
        ];

        if (!empty($validated["jadwal_id"])) {
            $jadwal = \App\Models\JadwalPemantauan::where(
                "bidan_id",
                auth()->id(),
            )->findOrFail($validated["jadwal_id"]);
            $jadwal->update($payload);
            $message = "Jadwal berhasil diperbarui.";
        } else {
            \App\Models\JadwalPemantauan::create($payload);
            $message = "Jadwal berhasil ditambahkan.";
        }

        return back()->with("success", $message);
    })->name("bidan.jadwal.store");

    Route::get("/bidan/settings", function (Request $request) use (
        $ensureRole,
    ) {
        $ensureRole("bidan");

        $bidanUser = $request->user();
        $browserSessions = [];

        if (config("session.driver") === "database") {
            $browserSessions = DB::connection(config("session.connection"))
                ->table(config("session.table", "sessions"))
                ->where("user_id", $bidanUser->getAuthIdentifier())
                ->orderByDesc("last_activity")
                ->get()
                ->map(function ($session) use ($request) {
                    $agent = tap(
                        new Agent(),
                        fn($agent) => $agent->setUserAgent(
                            $session->user_agent,
                        ),
                    );

                    return [
                        "agent" => [
                            "is_desktop" => $agent->isDesktop(),
                            "platform" => $agent->platform(),
                            "browser" => $agent->browser(),
                        ],
                        "ip_address" => $session->ip_address,
                        "is_current_device" =>
                            $session->id === $request->session()->getId(),
                        "last_active" => Carbon::createFromTimestamp(
                            $session->last_activity,
                        )->diffForHumans(),
                    ];
                })
                ->all();
        }

        return view("bidan.pengaturan", [
            "bidanUser" => $bidanUser,
            "browserSessions" => $browserSessions,
            "twoFactorEnabled" => filled($bidanUser->two_factor_secret),
            "twoFactorConfirmed" => filled($bidanUser->two_factor_confirmed_at),
            "requiresTwoFactorConfirmation" => Features::optionEnabled(
                Features::twoFactorAuthentication(),
                "confirm",
            ),
        ]);
    })->name("bidan.settings");

    Route::post("/bidan/settings/profile", function (Request $request) use (
        $ensureRole,
    ) {
        $ensureRole("bidan");

        $validated = $request->validate([
            "name" => ["required", "string", "max:255"],
            "email" => ["required", "string", "email", "max:255"],
            "no_telp" => ["nullable", "string", "max:30"],
        ]);

        $bidanId = auth()->id();
        $user = User::findOrFail($bidanId);

        foreach (["users", "pengguna", "bidan", "dokter", "admin"] as $table) {
            $query = DB::table($table)->where("email", $validated["email"]);
            if ($table === "users" || Schema::hasColumn($table, "id")) {
                $query->where("id", "!=", $bidanId);
            }
            abort_if($query->exists(), 422, "Email sudah dipakai.");
        }

        $user->name = $validated["name"];
        $user->email = $validated["email"];
        if (Schema::hasColumn("users", "no_telp")) {
            $user->no_telp = $validated["no_telp"] ?? null;
        }
        $user->save();

        DB::table("bidan")
            ->where("id", $bidanId)
            ->update([
                "name" => $validated["name"],
                "email" => $validated["email"],
                ...Schema::hasColumn("bidan", "no_telp")
                    ? ["no_telp" => $validated["no_telp"] ?? null]
                    : [],
                "updated_at" => now(),
            ]);

        return back()->with("success", "Profil bidan berhasil diperbarui.");
    })->name("bidan.settings.profile");

    Route::post("/bidan/settings/password", function (Request $request) use (
        $ensureRole,
    ) {
        $ensureRole("bidan");

        $validated = $request->validate([
            "current_password" => ["required", "string"],
            "new_password" => ["required", "string", "min:8", "confirmed"],
        ]);

        $bidanId = auth()->id();
        $user = User::findOrFail($bidanId);

        abort_unless(
            Hash::check($validated["current_password"], $user->password),
            422,
            "Password saat ini salah.",
        );

        $hashedPassword = Hash::make($validated["new_password"]);
        $user->password = $hashedPassword;
        $user->save();

        DB::table("bidan")
            ->where("id", $bidanId)
            ->update([
                "password" => $hashedPassword,
                "updated_at" => now(),
            ]);

        return back()->with("success", "Password bidan berhasil diperbarui.");
    })->name("bidan.settings.password");

    Route::get("/dokter/dashboard", function () use ($ensureRole) {
        $ensureRole("dokter");

        return view("dokter.dashboardDokter", [
            "penggunaCount" => DB::table("pengguna")->count(),
            "bidanCount" => DB::table("bidan")->count(),
            "dokterCount" => DB::table("dokter")->count(),
            "jadwalCount" => Schema::hasTable("jadwal_pemantauan")
                ? DB::table("jadwal_pemantauan")->count()
                : 0,
            "konsultasiCount" => DB::table("conversations")
                ->where("professional_type", "dokter")
                ->where("professional_id", auth()->id())
                ->count(),
            "recentPengguna" => DB::table("pengguna")
                ->latest("created_at")
                ->take(5)
                ->get(["id", "name", "email", "created_at"])
                ->map(
                    fn($u) => (object) [
                        ...(array) $u,
                        "created_at" => \Carbon\Carbon::parse($u->created_at),
                    ],
                ),
            "recentDokter" => DB::table("dokter")
                ->latest("created_at")
                ->take(5)
                ->get(["id", "name", "email", "created_at"])
                ->map(
                    fn($u) => (object) [
                        ...(array) $u,
                        "created_at" => \Carbon\Carbon::parse($u->created_at),
                    ],
                ),
        ]);
    })->name("dokter.dashboard");
    Route::get("/dokter/pengguna", function (Request $request) use (
        $ensureRole,
        $buildDokterUserQuery,
    ) {
        $ensureRole("dokter");

        $penggunaList = $buildDokterUserQuery($request)->get();

        return view("dokter.detailPengguna", [
            "penggunaList" => $penggunaList,
            "search" => trim((string) $request->query("q", "")),
        ]);
    })->name("dokter.pengguna");

    Route::get("/dokter/buku-kia", function (Request $request) use (
        $ensureRole,
    ) {
        $ensureRole("dokter");

        $templates = \App\Models\TemplateBukuKIA::orderBy(
            "created_at",
            "desc",
        )->get();

        $bukuKIA = \App\Models\BukuKIA::where("created_by", auth()->id())
            ->with("pengguna")
            ->orderByDesc("created_at")
            ->get();

        return view("dokter.bukuKIA", [
            "templates" => $templates,
            "bukuKIA" => $bukuKIA,
        ]);
    })->name("dokter.bukuKIA");

    Route::get("/dokter/pengguna/{id}/buku-kia", function (
        Request $request,
        $id,
    ) use ($ensureRole) {
        $ensureRole("dokter");

        $pengguna = \App\Models\Pengguna::withCount([
            "anak",
            "conversations",
        ])->findOrFail($id);

        $entries = \App\Models\BukuKIA::where("pengguna_id", $id)
            ->orderByDesc("created_at")
            ->get();
        $templates = \App\Models\TemplateBukuKIA::orderBy(
            "created_at",
            "desc",
        )->get();

        return view("dokter.bukuKIAPengguna", [
            "pengguna" => $pengguna,
            "entries" => $entries,
            "templates" => $templates,
        ]);
    })->name("dokter.pengguna.bukuKIA");

    Route::get("/dokter/pengguna/{id}/buku-kia/input", function (
        Request $request,
        $id,
    ) use ($ensureRole) {
        $ensureRole("dokter");

        $pengguna = \App\Models\Pengguna::withCount([
            "anak",
            "conversations",
        ])->findOrFail($id);

        // Get all DataKIA for this pengguna
        $bukuKiaList = \App\Models\DataKia::where(
            "user_id",
            $pengguna->user_id ?? $id,
        )
            ->with(["ibu"])
            ->orderByDesc("created_at")
            ->get();

        // Determine selected KIA
        $selectedKiaId = $request->query("buku_id");
        $selectedKia = null;

        if ($selectedKiaId) {
            $selectedKia = $bukuKiaList->firstWhere("id", $selectedKiaId);
        }

        // If no selected KIA, use the first one or null
        if (!$selectedKia && $bukuKiaList->isNotEmpty()) {
            $selectedKia = $bukuKiaList->first();
            $selectedKiaId = $selectedKia->id;
        }

        // Load relations for selected KIA
        if ($selectedKia) {
            $selectedKia->load([
                "ibu",
                "riwayat",
                "pelayananKesehatanIbu",
                "evaluasiKesehatanIbu",
                "pemeriksaanTrimester1",
                "catatanPelayananTrimester1",
                "pemeriksaanTrimester2",
                "catatanPelayananTrimester2",
            ]);
        }

        return view("nakes.buku-kia-pengguna", [
            "pengguna" => $pengguna,
            "role" => "dokter",
            "bukuKiaList" => $bukuKiaList,
            "selectedKia" => $selectedKia,
            "selectedKiaId" => $selectedKiaId,
        ]);
    })->name("dokter.pengguna.bukuKIA.input");

    Route::get("/dokter/pengguna/{penggunaId}/buku-kia/{kiaId}/pdf", function (
        $penggunaId,
        $kiaId,
    ) use ($ensureRole) {
        $ensureRole("dokter");

        $pengguna = \App\Models\Pengguna::findOrFail($penggunaId);
        $dataKia = \App\Models\DataKia::where("id", $kiaId)
            ->where("user_id", $pengguna->user_id ?? $penggunaId)
            ->firstOrFail();

        return app(\App\Http\Controllers\DataKiaController::class)->exportPdf(
            $dataKia->id,
        );
    })->name("dokter.pengguna.bukuKIA.pdf");

    Route::get(
        "/dokter/pengguna/{penggunaId}/buku-kia/{kiaId}/download",
        function ($penggunaId, $kiaId) use ($ensureRole) {
            $ensureRole("dokter");

            $pengguna = \App\Models\Pengguna::findOrFail($penggunaId);
            $dataKia = \App\Models\DataKia::with("ibu")
                ->where("id", $kiaId)
                ->where("user_id", $pengguna->user_id ?? $penggunaId)
                ->firstOrFail();

            $response = app(
                \App\Http\Controllers\DataKiaController::class,
            )->exportPdf($dataKia->id);
            $response->headers->set(
                "Content-Disposition",
                'attachment; filename="Buku_KIA_' .
                    ($dataKia->ibu->nama ?? "KIA") .
                    '.pdf"',
            );

            return $response;
        },
    )->name("dokter.pengguna.bukuKIA.download");

    Route::post("/dokter/pengguna/{penggunaId}/buku-kia/tambah", function (
        Request $request,
        $penggunaId,
    ) use ($ensureRole) {
        $ensureRole("dokter");

        $pengguna = \App\Models\Pengguna::findOrFail($penggunaId);

        // Create new DataKIA for this pengguna
        $dataKia = \App\Models\DataKia::create([
            "user_id" => $pengguna->user_id ?? $penggunaId,
        ]);

        return response()->json([
            "success" => true,
            "id" => $dataKia->id,
            "message" => "Buku KIA baru berhasil dibuat",
        ]);
    })->name("dokter.pengguna.bukuKIA.tambah");

    Route::post("/dokter/pengguna/{id}/buku-kia/input", function (
        Request $request,
        $id,
    ) use ($ensureRole) {
        $ensureRole("dokter");

        $pengguna = \App\Models\Pengguna::findOrFail($id);

        // Get the DataKIA from the form
        $kiaId = $request->input("data_kia_id");
        $dataKia = \App\Models\DataKia::findOrFail($kiaId);

        $clean = function ($val) {
            return $val === "" ? null : $val;
        };

        // 1. Core Data
        $dataKia->update([
            "faskes_dikeluarkan" => $clean($request->faskes_dikeluarkan),
            "tanggal_dikeluarkan" => $clean($request->tanggal_dikeluarkan),
            "kab_kota_dikeluarkan" => $clean($request->kab_kota_dikeluarkan),
            "provinsi_dikeluarkan" => $clean($request->provinsi_dikeluarkan),
        ]);

        // 2. Identitas Ibu
        $dataKia->ibu()->updateOrCreate(
            [],
            [
                "nama" => $clean($request->nama_ibu),
                "nik" => $clean($request->nik),
                "no_jkn" => $clean($request->no_jkn_ibu),
                "faskes_tk1" => $clean($request->faskes_tk1_ibu),
                "faskes_rujukan" => $clean($request->faskes_rujukan_ibu),
                "tempat_lahir" => $clean($request->tempat_lahir),
                "tanggal_lahir" => $clean($request->tanggal_lahir),
                "pendidikan" => $clean($request->pendidikan),
                "pekerjaan" => $clean($request->pekerjaan),
                "alamat" => $clean($request->alamat),
                "telepon" => $clean($request->telepon_ibu),
                "golongan_darah" => $clean($request->golongan_darah),
            ],
        );

        // 3. Identitas Suami
        $dataKia->suami()->updateOrCreate(
            [],
            [
                "nama" => $clean($request->nama_suami),
                "nik" => $clean($request->nik_suami),
                "no_jkn" => $clean($request->no_jkn_suami),
                "faskes_tk1" => $clean($request->faskes_tk1_suami),
                "faskes_rujukan" => $clean($request->faskes_rujukan_suami),
                "tempat_lahir" => $clean($request->tempat_lahir_suami),
                "tanggal_lahir" => $clean($request->tanggal_lahir_suami),
                "pendidikan" => $clean($request->pendidikan_suami),
                "pekerjaan" => $clean($request->pekerjaan_suami),
                "alamat" => $clean($request->alamat_rumah_suami),
                "telepon" => $clean($request->telepon_suami),
                "golongan_darah" => $clean($request->golongan_darah_suami),
            ],
        );

        // 4. Identitas Anak
        $dataKia->anak()->updateOrCreate(
            [],
            [
                "nama" => $clean($request->nama_anak),
                "nik" => $clean($request->nik_anak),
                "no_jkn" => $clean($request->no_jkn_anak),
                "faskes_tk1" => $clean($request->faskes_tk1_anak),
                "faskes_rujukan" => $clean($request->faskes_rujukan_anak),
                "tempat_lahir" => $clean($request->tempat_lahir_anak),
                "tanggal_lahir" => $clean($request->tanggal_lahir_anak),
                "anak_ke" => $clean($request->anak_ke),
                "no_akta_kelahiran" => $clean($request->no_akta_kelahiran_anak),
                "telepon" => $clean($request->telepon_anak),
                "alamat" => $clean($request->alamat_anak),
                "golongan_darah" => $clean($request->golongan_darah_anak),
            ],
        );

        // 5. Layanan & Pembiayaan
        $dataKia->layanan()->updateOrCreate(
            [],
            [
                "puskesmas_domisili" => $clean($request->puskesmas_domisili),
                "no_reg_kohort_ibu" => $clean($request->no_reg_kohort_ibu),
                "no_reg_kohort_bayi" => $clean($request->no_reg_kohort_bayi),
                "no_reg_kohort_balita" => $clean(
                    $request->no_reg_kohort_balita,
                ),
                "no_catatan_medik_rs" => $clean($request->no_catatan_medik_rs),
                "asuransi_lain" => $clean($request->asuransi_lain),
                "no_asuransi_lain" => $clean($request->no_asuransi_lain),
                "tanggal_berlaku_asuransi_lain" => $clean(
                    $request->tanggal_berlaku_asuransi_lain,
                ),
            ],
        );

        // 6. Riwayat Kesehatan
        $dataKia->riwayat()->updateOrCreate(
            [],
            [
                "hpht" => $clean($request->hpht),
                "htp" => $clean($request->htp),
                "lingkar_lengan_atas" => $clean($request->lingkar_lengan_atas),
                "tinggi_badan" => $clean($request->tinggi_badan),
            ],
        );

        return back()->with("success", "Data Buku KIA berhasil disimpan.");
    })->name("dokter.pengguna.bukuKIA.input.store");

    Route::post("/dokter/pengguna/{id}/buku-kia", function (
        Request $request,
        $id,
    ) use ($ensureRole) {
        $ensureRole("dokter");

        $pengguna = \App\Models\Pengguna::findOrFail($id);

        $validated = $request->validate([
            "judul" => ["nullable", "string", "max:255"],
            "catatan" => ["nullable", "string", "max:5000"],
            "file" => [
                "nullable",
                "file",
                "mimes:pdf,jpg,jpeg,png",
                "max:5120",
            ],
        ]);

        $filePath = null;
        if ($request->hasFile("file")) {
            $filePath = $request->file("file")->store("buku_kia", "public");
        }

        \App\Models\BukuKIA::create([
            "pengguna_id" => $pengguna->id,
            "created_by" => auth()->id(),
            "judul" => $validated["judul"] ?? null,
            "catatan" => $validated["catatan"] ?? null,
            "file_path" => $filePath,
        ]);

        return back()->with("success", "Catatan Buku KIA berhasil disimpan.");
    })->name("dokter.pengguna.bukuKIA.store");

    Route::delete("/dokter/pengguna/buku-kia/{bukuId}", function (
        Request $request,
        $bukuId,
    ) use ($ensureRole) {
        $ensureRole("dokter");

        $buku = \App\Models\BukuKIA::findOrFail($bukuId);
        abort_unless(
            $buku->created_by === auth()->id() ||
                auth()->user()->role === "admin",
            403,
        );

        $buku->delete();

        return back()->with("success", "Catatan Buku KIA berhasil dihapus.");
    })->name("dokter.pengguna.bukuKIA.destroy");

    Route::get("/dokter/konsultasi", function (Request $request) use (
        $ensureRole,
    ) {
        $ensureRole("dokter");

        $dokterId = auth()->id();
        $conversations = \App\Models\Conversation::with([
            "pengguna" => function ($query) {
                $query->select("id", "name", "is_online", "last_seen");
            },
            "messages",
        ])
            ->where("professional_type", "dokter")
            ->where("professional_id", $dokterId)
            ->orderByDesc("last_message_at")
            ->get()
            ->map(function ($conversation) {
                $conversation->unread_count = $conversation->messages
                    ->where("is_read", false)
                    ->where("sender_type", "pengguna")
                    ->count();
                return $conversation;
            });

        $selectedConversation =
            $conversations->firstWhere(
                "id",
                (int) $request->query("conversation_id"),
            ) ?? $conversations->first();

        $messages = collect();
        if ($selectedConversation) {
            $messages = $selectedConversation->messages->map(function ($msg) {
                return [
                    "id" => $msg->id,
                    "sender_type" => $msg->sender_type,
                    "sender_id" => $msg->sender_id,
                    "message" => $msg->message,
                    "created_at" => $msg->created_at,
                    "is_read" => $msg->is_read,
                ];
            });
        }

        return view("dokter.konsultasi", [
            "conversations" => $conversations,
            "selectedConversation" => $selectedConversation,
            "messages" => $messages,
        ]);
    })->name("dokter.konsultasi");

    Route::post("/dokter/konsultasi/send-message", function (
        Request $request,
    ) use ($ensureRole) {
        $ensureRole("dokter");

        $validated = $request->validate([
            "conversation_id" => [
                "required",
                "integer",
                "exists:conversations,id",
            ],
            "message" => ["required", "string", "max:5000"],
        ]);

        $dokterId = auth()->id();
        $conversation = \App\Models\Conversation::findOrFail(
            $validated["conversation_id"],
        );
        abort_unless(
            $conversation->professional_type === "dokter" &&
                (int) $conversation->professional_id === (int) $dokterId,
            403,
        );

        $message = \App\Models\Message::create([
            "conversation_id" => $validated["conversation_id"],
            "sender_type" => "dokter",
            "sender_id" => $dokterId,
            "message" => $validated["message"],
            "is_read" => false,
        ]);

        $conversation->update([
            "last_message" => $validated["message"],
            "last_message_at" => now(),
        ]);

        // created_at dari accessor Model (sudah otomatis WIB karena getCreatedAtAttribute)
        return response()->json([
            "success" => true,
            "message" => [
                "id" => $message->id,
                "sender_type" => $message->sender_type,
                "message" => $message->message,
                "created_at" => $message->created_at,
            ],
        ]);
    })->name("dokter.konsultasi.send_message");

    Route::get("/dokter/konsultasi/{conversation_id}/messages", function (
        Request $request,
        $conversation_id,
    ) use ($ensureRole) {
        $ensureRole("dokter");

        $dokterId = auth()->id();
        $conversation = \App\Models\Conversation::with("pengguna")->findOrFail(
            $conversation_id,
        );
        abort_unless(
            $conversation->professional_type === "dokter" &&
                (int) $conversation->professional_id === (int) $dokterId,
            403,
        );

        $messages = \App\Models\Message::where(
            "conversation_id",
            $conversation_id,
        )
            ->orderBy("created_at", "asc")
            ->get();

        \App\Models\Message::where("conversation_id", $conversation_id)
            ->where("sender_type", "pengguna")
            ->where("is_read", false)
            ->update(["is_read" => true]);

        return response()->json([
            "success" => true,
            "messages" => $messages->map(function ($msg) {
                return [
                    "id" => $msg->id,
                    "sender_type" => $msg->sender_type,
                    "sender_id" => $msg->sender_id,
                    "message" => $msg->message,
                    "created_at" => $msg->created_at,
                    "is_read" => $msg->is_read,
                ];
            }),
        ]);
    })->name("dokter.konsultasi.get_messages");

    Route::get("/dokter/jadwal", function (Request $request) use (
        $ensureRole,
        $buildDokterScheduleQuery,
    ) {
        $ensureRole("dokter");

        $jadwalList = $buildDokterScheduleQuery($request);

        return view("dokter.jadwal", [
            "jadwalList" =>
                $jadwalList instanceof \Illuminate\Database\Eloquent\Builder
                    ? $jadwalList->get()
                    : $jadwalList,
            "penggunaList" => \App\Models\Pengguna::orderBy("name")->get([
                "id",
                "name",
                "email",
                "is_hamil",
            ]),
            "search" => trim((string) $request->query("q", "")),
            "selectedSchedule" => null,
        ]);
    })->name("dokter.jadwal");

    Route::post("/dokter/jadwal", function (Request $request) use (
        $ensureRole,
    ) {
        $ensureRole("dokter");

        $validated = $request->validate([
            "jadwal_id" => ["nullable", "integer"],
            "pengguna_id" => ["required", "integer", "exists:pengguna,id"],
            "jenis" => ["required", "in:kontrol,imunisasi"],
            "judul" => ["required", "string", "max:255"],
            "tanggal" => ["required", "date"],
            "waktu" => ["nullable", "date_format:H:i"],
            "catatan" => ["nullable", "string", "max:2000"],
            "status" => ["required", "in:terjadwal,selesai,dibatalkan"],
        ]);

        $payload = [
            "pengguna_id" => $validated["pengguna_id"],
            "dokter_id" => auth()->id(),
            "jenis" => $validated["jenis"],
            "judul" => $validated["judul"],
            "tanggal" => $validated["tanggal"],
            "waktu" => $validated["waktu"] ?? null,
            "catatan" => $validated["catatan"] ?? null,
            "status" => $validated["status"],
        ];

        if (!empty($validated["jadwal_id"])) {
            $jadwal = \App\Models\JadwalPemantauan::where(
                "dokter_id",
                auth()->id(),
            )->findOrFail($validated["jadwal_id"]);
            $jadwal->update($payload);
            $message = "Jadwal berhasil diperbarui.";
        } else {
            \App\Models\JadwalPemantauan::create($payload);
            $message = "Jadwal berhasil ditambahkan.";
        }

        return back()->with("success", $message);
    })->name("dokter.jadwal.store");

    Route::get("/dokter/settings", function (Request $request) use (
        $ensureRole,
    ) {
        $ensureRole("dokter");

        $dokterUser = $request->user();
        $browserSessions = [];

        if (config("session.driver") === "database") {
            $browserSessions = DB::connection(config("session.connection"))
                ->table(config("session.table", "sessions"))
                ->where("user_id", $dokterUser->getAuthIdentifier())
                ->orderByDesc("last_activity")
                ->get()
                ->map(function ($session) use ($request) {
                    $agent = tap(
                        new Agent(),
                        fn($agent) => $agent->setUserAgent(
                            $session->user_agent,
                        ),
                    );

                    return [
                        "agent" => [
                            "is_desktop" => $agent->isDesktop(),
                            "platform" => $agent->platform(),
                            "browser" => $agent->browser(),
                        ],
                        "ip_address" => $session->ip_address,
                        "is_current_device" =>
                            $session->id === $request->session()->getId(),
                        "last_active" => Carbon::createFromTimestamp(
                            $session->last_activity,
                        )->diffForHumans(),
                    ];
                })
                ->all();
        }

        return view("dokter.pengaturan", [
            "dokterUser" => $dokterUser,
            "browserSessions" => $browserSessions,
            "twoFactorEnabled" => filled($dokterUser->two_factor_secret),
            "twoFactorConfirmed" => filled(
                $dokterUser->two_factor_confirmed_at,
            ),
            "requiresTwoFactorConfirmation" => Features::optionEnabled(
                Features::twoFactorAuthentication(),
                "confirm",
            ),
        ]);
    })->name("dokter.settings");

    Route::post("/dokter/settings/profile", function (Request $request) use (
        $ensureRole,
    ) {
        $ensureRole("dokter");

        $validated = $request->validate([
            "name" => ["required", "string", "max:255"],
            "email" => ["required", "string", "email", "max:255"],
            "no_telp" => ["nullable", "string", "max:30"],
        ]);

        $dokterId = auth()->id();
        $user = User::findOrFail($dokterId);

        foreach (["users", "pengguna", "bidan", "dokter", "admin"] as $table) {
            $query = DB::table($table)->where("email", $validated["email"]);
            if ($table === "users" || Schema::hasColumn($table, "id")) {
                $query->where("id", "!=", $dokterId);
            }
            abort_if($query->exists(), 422, "Email sudah dipakai.");
        }

        $user->name = $validated["name"];
        $user->email = $validated["email"];
        if (Schema::hasColumn("users", "no_telp")) {
            $user->no_telp = $validated["no_telp"] ?? null;
        }
        $user->save();

        DB::table("dokter")
            ->where("id", $dokterId)
            ->update([
                "name" => $validated["name"],
                "email" => $validated["email"],
                ...Schema::hasColumn("dokter", "no_telp")
                    ? ["no_telp" => $validated["no_telp"] ?? null]
                    : [],
                "updated_at" => now(),
            ]);

        return back()->with("success", "Profil dokter berhasil diperbarui.");
    })->name("dokter.settings.profile");

    Route::post("/dokter/settings/password", function (Request $request) use (
        $ensureRole,
    ) {
        $ensureRole("dokter");

        $validated = $request->validate([
            "current_password" => ["required", "string"],
            "new_password" => ["required", "string", "min:8", "confirmed"],
        ]);

        $dokterId = auth()->id();
        $user = User::findOrFail($dokterId);

        abort_unless(
            Hash::check($validated["current_password"], $user->password),
            422,
            "Password saat ini salah.",
        );

        $hashedPassword = Hash::make($validated["new_password"]);
        $user->password = $hashedPassword;
        $user->save();

        DB::table("dokter")
            ->where("id", $dokterId)
            ->update([
                "password" => $hashedPassword,
                "updated_at" => now(),
            ]);

        return back()->with("success", "Password dokter berhasil diperbarui.");
    })->name("dokter.settings.password");
});

Route::middleware([
    "auth:sanctum",
    config("jetstream.auth_session"),
    "verified",
])->group(function () {
    $ensureUserRole = function (string $role) {
        abort_unless(auth()->check() && auth()->user()->role === $role, 403);
    };

    Route::get("/dashboard", function () {
        $user = auth()->user();
        if ($user) {
            if ($user->role === "admin") {
                return redirect()->to(url("/admin/dashboard"));
            }
            if ($user->role === "bidan") {
                return redirect()->to(url("/bidan/dashboard"));
            }
            if ($user->role === "dokter") {
                return redirect()->to(url("/dokter/dashboard"));
            }
            if ($user->role === "pengguna") {
                return redirect()->route("pengguna.dashboard");
            }
            // Unknown role
            abort(403, "Invalid user role.");
        }

        return redirect()->route("login");
    })->name("dashboard");
});

// Auth middleware for pengguna routes
Route::middleware([
    "auth:sanctum",
    config("jetstream.auth_session"),
    "verified",
])->group(function () {
    $ensureUserRole = function (string $role) {
        abort_unless(auth()->check() && auth()->user()->role === $role, 403);
    };

    Route::get("/pengguna/dashboard", function () use ($ensureUserRole) {
        $ensureUserRole("pengguna");

        $user = auth()->user();

        // Cari pengguna berdasarkan email
        $penggunaLogin = \App\Models\Pengguna::where(
            "email",
            $user->email,
        )->first();
        // Screening check: Jika data KIA belum diisi (identitas ibu kosong), arahkan ke wizard
        $dataKia = \App\Models\DataKia::with("ibu")
            ->where("user_id", auth()->id())
            ->first();
        if (!$dataKia || !$dataKia->ibu || empty($dataKia->ibu->nama)) {
            return redirect()
                ->route("pengguna.buku_kia")
                ->with(
                    "info",
                    "Mohon lengkapi screening Buku KIA terlebih dahulu.",
                );
        }

        // Ambil jadwal pemantauan untuk pengguna ini
        $jadwalList = collect();
        $usedPenggunaId = $penggunaLogin ? $penggunaLogin->id : null;

        // Fallback: jika tidak ada di tabel pengguna, coba cari berdasarkan user_id
        if (!$usedPenggunaId) {
            $penggunaByUserId = \App\Models\Pengguna::where(
                "user_id",
                auth()->id(),
            )->first();
            if ($penggunaByUserId) {
                $usedPenggunaId = $penggunaByUserId->id;
            }
        }

        // Debug: Log untuk troubleshooting
        \Log::info("Jadwal Debug", [
            "user_id" => auth()->id(),
            "user_email" => $user->email,
            "penggunaLogin_id" => $penggunaLogin?->id,
            "penggunaByUserId_id" => $penggunaByUserId?->id ?? null,
            "usedPenggunaId" => $usedPenggunaId,
            "table_exists" => Schema::hasTable("jadwal_pemantauan"),
        ]);

        if ($usedPenggunaId && Schema::hasTable("jadwal_pemantauan")) {
            // Ambil data jadwal dan convert tanggal ke format string biasa
            $jadwalRaw = \App\Models\JadwalPemantauan::with(["bidan", "dokter"])
                ->where("pengguna_id", $usedPenggunaId)
                ->whereIn("status", ["terjadwal"])
                ->orderBy("tanggal", "asc")
                ->orderBy("waktu", "asc")
                ->get();

            // Debug: Log jadwal yang ditemukan
            \Log::info("Jadwal Raw", [
                "count" => $jadwalRaw->count(),
                "items" => $jadwalRaw->toArray(),
            ]);

            // Convert ke format yang aman untuk JavaScript (abaikan timezone)
            $jadwalList = $jadwalRaw->map(function ($j) {
                // Parse sebagai tanggal biasa (abaikan timezone) agar tidak terjadi penggeseran hari
                // $j->tanggal adalah column date (Y-m-d), bukan datetime
                $tanggalStr = \Carbon\Carbon::parse($j->tanggal)->format(
                    "Y-m-d",
                );
                $waktuStr = $j->waktu
                    ? (is_string($j->waktu)
                        ? substr($j->waktu, 0, 5)
                        : $j->waktu->format("H:i"))
                    : null;

                return [
                    "id" => $j->id,
                    "jenis" => $j->jenis,
                    "judul" => $j->judul,
                    "tanggal" => $tanggalStr,
                    "waktu" => $waktuStr,
                    "catatan" => $j->catatan,
                    "bidan" => $j->bidan ? ["name" => $j->bidan->name] : null,
                    "dokter" => $j->dokter
                        ? ["name" => $j->dokter->name]
                        : null,
                ];
            });
        }

        $pengguna = \App\Models\Pengguna::find($user->id);

        return view("pengguna.dashboardPengguna", [
            "pengguna" => $pengguna,
            "jadwalList" => $jadwalList,
        ]);
    })->name("pengguna.dashboard");

    Route::get("/pengguna/artikel", function (Request $request) use (
        $ensureUserRole,
    ) {
        $ensureUserRole("pengguna");

        $user = auth()->user();
        $pengguna = Pengguna::find($user->id);

        $requestedWeek = (int) $request->integer("minggu");
        $profileWeek =
            (int) ($user->usia_kehamilan_minggu ??
                (optional($pengguna)->usia_kehamilan_minggu ?? 0));

        // Prioritaskan minggu dari query param agar bisa dipakai saat user diarahkan dari halaman lain.
        $selectedWeek =
            $requestedWeek > 0
                ? $requestedWeek
                : ($profileWeek > 0
                    ? $profileWeek
                    : null);

        // Semua artikel berasal dari manajemen admin (tabel artikel_edukasi).
        $articles = ArtikelEdukasi::query()->latest()->get();

        $selectedTrimester = null;
        if ($selectedWeek) {
            $selectedTrimester =
                $selectedWeek <= 13
                    ? "trimester_1"
                    : ($selectedWeek <= 27
                        ? "trimester_2"
                        : "trimester_3");
        }

        $recommendedArticles = collect();
        if ($selectedTrimester) {
            $recommendedArticles = $articles
                ->whereIn("category", [$selectedTrimester, "umum"])
                ->values();
        }

        $recommendedIds = $recommendedArticles->pluck("id")->all();

        return view("pengguna.artikel", [
            "articles" => $articles,
            "selectedWeek" => $selectedWeek,
            "selectedTrimester" => $selectedTrimester,
            "recommendedIds" => $recommendedIds,
            "recommendedArticles" => $recommendedArticles,
        ]);
    })->name("pengguna.artikel");

    Route::get("/pengguna/konsultasi", function () use ($ensureUserRole) {
        $ensureUserRole("pengguna");

        $user = auth()->user();
        $pengguna = \App\Models\Pengguna::find($user->id);

        $bidan = \App\Models\Bidan::select(
            "id",
            "name",
            "email",
            "is_online",
            "last_seen",
        )->get();
        $dokter = \App\Models\Dokter::select(
            "id",
            "name",
            "email",
            "is_online",
            "last_seen",
        )->get();

        $conversations = \App\Models\Conversation::where(
            "pengguna_id",
            $user->id,
        )
            ->with("messages")
            ->orderBy("last_message_at", "desc")
            ->get()
            ->map(function ($conv) {
                $conv->unread_count = $conv->messages
                    ->where("is_read", false)
                    ->where("sender_type", "!=", "pengguna")
                    ->count();
                return $conv;
            });

        return view("pengguna.konsultasi", [
            "bidan" => $bidan,
            "dokter" => $dokter,
            "conversations" => $conversations,
        ]);
    })->name("pengguna.konsultasi");

    Route::post("/pengguna/konsultasi/start", function (Request $request) use (
        $ensureUserRole,
    ) {
        $ensureUserRole("pengguna");

        $validated = $request->validate([
            "professional_type" => ["required", "in:bidan,dokter"],
            "professional_id" => ["required", "integer"],
        ]);

        $user = auth()->user();

        // Debug log
        \Log::info("Konsultasi Start Debug", [
            "user_id" => $user->id,
            "user_email" => $user->email,
            "user_role" => $user->role,
            "professional_type" => $validated["professional_type"],
            "professional_id" => $validated["professional_id"],
        ]);

        // Cek apakah pengguna ada di tabel pengguna
        $penggunaLogin = \App\Models\Pengguna::where(
            "email",
            $user->email,
        )->first();
        if (!$penggunaLogin) {
            // Fallback: cari berdasarkan user_id
            $penggunaLogin = \App\Models\Pengguna::where(
                "user_id",
                $user->id,
            )->first();
        }

        \Log::info("Konsultasi Start - Pengguna lookup", [
            "penggunaLogin_id" => $penggunaLogin?->id,
        ]);

        $penggunaId = $penggunaLogin ? $penggunaLogin->id : $user->id;

        $conversation = \App\Models\Conversation::firstOrCreate([
            "pengguna_id" => $penggunaId,
            "professional_type" => $validated["professional_type"],
            "professional_id" => $validated["professional_id"],
        ]);

        \Log::info("Konsultasi Start - Conversation created", [
            "conversation_id" => $conversation->id,
        ]);

        return response()->json([
            "success" => true,
            "conversation_id" => $conversation->id,
        ]);
    })->name("pengguna.konsultasi.start");

    Route::post("/pengguna/konsultasi/send-message", function (
        Request $request,
    ) use ($ensureUserRole) {
        $ensureUserRole("pengguna");

        $validated = $request->validate([
            "conversation_id" => [
                "required",
                "integer",
                "exists:conversations,id",
            ],
            "message" => ["required", "string", "max:5000"],
        ]);

        $user = auth()->user();
        $conversation = \App\Models\Conversation::findOrFail(
            $validated["conversation_id"],
        );

        abort_unless($conversation->pengguna_id === $user->id, 403);

        $message = \App\Models\Message::create([
            "conversation_id" => $validated["conversation_id"],
            "sender_type" => "pengguna",
            "sender_id" => $user->id,
            "message" => $validated["message"],
            "is_read" => false,
        ]);

        $conversation->update([
            "last_message" => $validated["message"],
            "last_message_at" => now(),
        ]);

        // created_at dari accessor Model (sudah otomatis WIB karena getCreatedAtAttribute)
        return response()->json([
            "success" => true,
            "message" => [
                "id" => $message->id,
                "sender_type" => $message->sender_type,
                "message" => $message->message,
                "created_at" => $message->created_at,
            ],
        ]);
    })->name("pengguna.konsultasi.send_message");

    Route::get("/pengguna/konsultasi/{conversation_id}/messages", function (
        Request $request,
        $conversation_id,
    ) use ($ensureUserRole) {
        $ensureUserRole("pengguna");

        $user = auth()->user();
        $conversation = \App\Models\Conversation::findOrFail($conversation_id);

        abort_unless($conversation->pengguna_id === $user->id, 403);

        $messages = \App\Models\Message::where(
            "conversation_id",
            $conversation_id,
        )
            ->orderBy("created_at", "asc")
            ->get()
            ->map(function ($msg) {
                return [
                    "id" => $msg->id,
                    "sender_type" => $msg->sender_type,
                    "sender_id" => $msg->sender_id,
                    "message" => $msg->message,
                    "created_at" => $msg->created_at,
                    "is_read" => $msg->is_read,
                ];
            });

        return response()->json([
            "success" => true,
            "messages" => $messages,
        ]);
    })->name("pengguna.konsultasi.get_messages");

    Route::get("/pengguna/kalkulator", function () use ($ensureUserRole) {
        $ensureUserRole("pengguna");

        return view("pengguna.kalkulator");
    })->name("pengguna.kalkulator");

    Route::get("/pengguna/jadwal", function () use ($ensureUserRole) {
        $ensureUserRole("pengguna");
        $tanggal =
            request("tanggal") ?? \Carbon\Carbon::today()->toDateString();
        $user = auth()->user();
        $jadwalList = Schema::hasTable("jadwal_pemantauan")
            ? \App\Models\JadwalPemantauan::with("bidan")
                ->where("pengguna_id", $user->id)
                ->orderBy("tanggal")
                ->orderBy("waktu")
                ->get()
            : collect();

        return view("pengguna.jadwal", [
            "tanggal" => $tanggal,
            "jadwalList" => $jadwalList,
        ]);
    })->name("pengguna.jadwal");

    Route::get("/pengguna/status-kehamilan", function () use ($ensureUserRole) {
        $ensureUserRole("pengguna");

        $user = auth()->user();
        $pengguna = \App\Models\Pengguna::with("anak")->find($user->id);

        if (!$pengguna) {
            abort(404, "Pengguna tidak ditemukan.");
        }

        $isHamil = (bool) ($pengguna->is_hamil ?? false);
        $daftarAnak = $pengguna->anak ?? collect();
        $kehamilanKe =
            max((int) ($daftarAnak->max("anak_ke") ?? 0), 0) +
            ($isHamil ? 1 : 0);

        $ringkasan = [
            [
                "label" => "Status Saat Ini",
                "value" => $isHamil ? "Sedang Hamil" : "Tidak Hamil",
                "icon" => "bi-heart-pulse-fill",
                "tone" => $isHamil ? "active" : "muted",
            ],
            [
                "label" => "Kehamilan Ke",
                "value" =>
                    $isHamil && $kehamilanKe > 0
                        ? "Kehamilan ke-" . $kehamilanKe
                        : "Belum aktif",
                "icon" => "bi-clipboard2-pulse-fill",
                "tone" => "accent",
            ],
            [
                "label" => "HPL",
                "value" => "—",
                "icon" => "bi-calendar2-week-fill",
                "tone" => "warning",
            ],
            [
                "label" => "Risiko Kehamilan",
                "value" => "Normal",
                "icon" => "bi-shield-check",
                "tone" => "success",
            ],
            [
                "label" => "GPA",
                "value" => "0/0/0",
                "icon" => "bi-diagram-3-fill",
                "tone" => "primary",
            ],
        ];

        $riwayatKehamilan = $daftarAnak
            ->map(function ($anak) {
                return [
                    "judul" => $anak->nama_anak
                        ? "Anak ke-{$anak->anak_ke} - {$anak->nama_anak}"
                        : "Anak ke-{$anak->anak_ke}",
                    "status" =>
                        $anak->status === "dalam_kandungan"
                            ? "Dalam kandungan"
                            : "Riwayat anak",
                    "tanggal" => $anak->tanggal_lahir
                        ? $anak->tanggal_lahir->format("d M Y")
                        : "Belum diisi",
                ];
            })
            ->values()
            ->all();

        if ($isHamil) {
            array_unshift($riwayatKehamilan, [
                "judul" => "Kehamilan aktif saat ini",
                "status" => "Sedang dipantau",
                "tanggal" => "Data dari profil pengguna",
            ]);
        }

        $riwayatKontrol = $isHamil
            ? [
                [
                    "judul" => "Kontrol ANC berikutnya",
                    "waktu" => "Belum dijadwalkan",
                    "status" => "Menunggu jadwal",
                    "catatan" =>
                        "Tambahkan jadwal kontrol untuk memantau tekanan darah, berat badan, dan perkembangan janin.",
                ],
                [
                    "judul" => "Pemeriksaan laboratorium",
                    "waktu" => "Belum tercatat",
                    "status" => "Belum ada data",
                    "catatan" =>
                        "Hasil pemeriksaan akan tampil di sini setelah dicatat.",
                ],
                [
                    "judul" => "Konsultasi nutrisi",
                    "waktu" => "Belum tercatat",
                    "status" => "Belum ada data",
                    "catatan" =>
                        "Catatan makan dan suplementasi akan muncul saat riwayat kontrol diisi.",
                ],
            ]
            : [];

        return view("pengguna.statusKehamilan", [
            "pengguna" => $pengguna,
            "isHamil" => $isHamil,
            "kehamilanKe" => $kehamilanKe,
            "ringkasan" => $ringkasan,
            "riwayatKontrol" => $riwayatKontrol,
            "riwayatKehamilan" => $riwayatKehamilan,
        ]);
    })->name("pengguna.status_kehamilan");

    // Note: /pengguna/buku-kia route is now inside Route::middleware(['auth'])->group at the bottom
    // to fix 404 issue. Duplicate route definition removed.

    // Pengaturan pengguna (profile, password, sessions)
    $buildPenggunaSettingsData = function (Request $request) {
        $user = $request->user();
        $pengguna = \App\Models\Pengguna::find($user->id);

        $browserSessions = [];
        if (config("session.driver") === "database") {
            $browserSessions = DB::connection(config("session.connection"))
                ->table(config("session.table", "sessions"))
                ->where("user_id", $user->getAuthIdentifier())
                ->orderByDesc("last_activity")
                ->get()
                ->map(function ($session) use ($request) {
                    $agent = tap(
                        new Agent(),
                        fn($agent) => $agent->setUserAgent(
                            $session->user_agent,
                        ),
                    );

                    return [
                        "agent" => [
                            "is_desktop" => $agent->isDesktop(),
                            "platform" => $agent->platform(),
                            "browser" => $agent->browser(),
                        ],
                        "ip_address" => $session->ip_address,
                        "is_current_device" =>
                            $session->id === $request->session()->getId(),
                        "last_active" => Carbon::createFromTimestamp(
                            $session->last_activity,
                        )->diffForHumans(),
                    ];
                })
                ->all();
        }

        return [
            "pengguna" => $pengguna,
            "browserSessions" => $browserSessions,
            "twoFactorEnabled" => filled($user->two_factor_secret ?? null),
            "twoFactorConfirmed" => filled(
                $user->two_factor_confirmed_at ?? null,
            ),
            "requiresTwoFactorConfirmation" => Features::optionEnabled(
                Features::twoFactorAuthentication(),
                "confirm",
            ),
        ];
    };

    Route::get("/pengguna/settings", function (Request $request) use (
        $ensureUserRole,
        $buildPenggunaSettingsData,
    ) {
        $ensureUserRole("pengguna");

        return view(
            "pengguna.pengaturan",
            $buildPenggunaSettingsData($request),
        );
    })->name("pengguna.settings");

    Route::post("/pengguna/settings/profile", function (Request $request) use (
        $ensureUserRole,
    ) {
        $ensureUserRole("pengguna");

        $validated = $request->validate([
            "name" => ["required", "string", "max:255"],
            "email" => ["required", "string", "email", "max:255"],
            "no_telp" => ["nullable", "string", "max:30"],
        ]);

        $userId = auth()->id();
        $user = User::findOrFail($userId);

        // ensure email not used by other accounts
        foreach (["users", "pengguna", "bidan", "dokter", "admin"] as $table) {
            $query = DB::table($table)->where("email", $validated["email"]);
            if ($table === "users" || Schema::hasColumn($table, "id")) {
                $query->where("id", "!=", $userId);
            }
            abort_if($query->exists(), 422, "Email sudah dipakai.");
        }

        $user->name = $validated["name"];
        $user->email = $validated["email"];
        if (Schema::hasColumn("users", "no_telp")) {
            $user->no_telp = $validated["no_telp"] ?? null;
        }
        $user->save();

        DB::table("pengguna")
            ->where("id", $userId)
            ->update([
                "name" => $validated["name"],
                "email" => $validated["email"],
                ...Schema::hasColumn("pengguna", "no_telp")
                    ? ["no_telp" => $validated["no_telp"] ?? null]
                    : [],
                "updated_at" => now(),
            ]);

        return back()->with("success", "Profil berhasil diperbarui.");
    })->name("pengguna.settings.profile");

    Route::post("/pengguna/settings/password", function (Request $request) use (
        $ensureUserRole,
    ) {
        $ensureUserRole("pengguna");

        $validated = $request->validate([
            "current_password" => ["required", "string"],
            "new_password" => ["required", "string", "min:8", "confirmed"],
        ]);

        $userId = auth()->id();
        $user = User::findOrFail($userId);

        abort_unless(
            Hash::check($validated["current_password"], $user->password),
            422,
            "Password saat ini salah.",
        );

        $hashed = Hash::make($validated["new_password"]);
        $user->password = $hashed;
        $user->save();

        DB::table("pengguna")
            ->where("id", $userId)
            ->update([
                "password" => $hashed,
                "updated_at" => now(),
            ]);

        return back()->with("success", "Password berhasil diperbarui.");
    })->name("pengguna.settings.password");

    Route::post("/pengguna/settings/logout-others", function (
        Request $request,
    ) use ($ensureUserRole) {
        $ensureUserRole("pengguna");

        if (config("session.driver") !== "database") {
            return back()->with(
                "success",
                "Fitur hanya tersedia saat session driver = database.",
            );
        }

        $userId = auth()->id();
        $currentId = $request->session()->getId();

        DB::table(config("session.table", "sessions"))
            ->where("user_id", $userId)
            ->where("id", "!=", $currentId)
            ->delete();

        return back()->with(
            "success",
            "Sesi perangkat lain berhasil dikeluarkan.",
        );
    })->name("pengguna.settings.logout_others");
    // Note: /pengguna/kia/pdf and /pengguna/kia/download routes moved to bottom group
    // to fix 404 issue. Duplicate routes removed.

    Route::get("/pengguna/pengaturan", function (Request $request) use (
        $ensureUserRole,
        $buildPenggunaSettingsData,
    ) {
        $ensureUserRole("pengguna");

        return view(
            "pengguna.pengaturan",
            $buildPenggunaSettingsData($request),
        );
    })->name("pengguna.pengaturan");
}); // End of auth:sanctum middleware group for pengguna routes

Route::middleware(["auth"])->group(function () {
    $ensureUserRole = function (string $role) {
        abort_unless(auth()->check() && auth()->user()->role === $role, 403);
    };

    Route::get("/pengguna/buku-kia", [
        \App\Http\Controllers\DataKiaController::class,
        "wizard",
    ])
        ->middleware("auth")
        ->name("pengguna.buku_kia");
    Route::get("/pengguna/buku-kia/{id}", [
        \App\Http\Controllers\DataKiaController::class,
        "wizard",
    ])
        ->middleware("auth")
        ->name("pengguna.buku_kia.show");
    Route::post("/pengguna/buku-kia/tambah", [
        \App\Http\Controllers\DataKiaController::class,
        "tambahBukuKia",
    ])
        ->middleware("auth")
        ->name("pengguna.buku_kia.tambah");
    Route::post("/pengguna/buku-kia/save", [
        \App\Http\Controllers\DataKiaController::class,
        "saveWizard",
    ])
        ->middleware("auth")
        ->name("pengguna.kia.save_wizard");

    //     Route::get('/pengguna/kia/{id}/pdf', function ($id) use ($ensureUserRole) {
    //         $ensureUserRole('pengguna');

    //         // Verifikasi buku KIA milik user
    //         $dataKia = \App\Models\DataKia::where('id', $id)
    //             ->where('user_id', auth()->id())
    //             ->first();
    //         abort_unless($dataKia, 403, 'Akses ditolak.');

    //         $pdfPath = resource_path('views/buku/Buku KIA (Permenkes).pdf');
    //         abort_unless(file_exists($pdfPath), 404, 'File Buku KIA tidak ditemukan.');

    //         return response()->file($pdfPath, [
    //             'Content-Type' => 'application/pdf',
    //             'Content-Disposition' => 'inline; filename="Buku KIA (Permenkes).pdf"',
    //         ]);
    //     })->name('pengguna.kia.pdf');

    //     Route::get('/pengguna/kia/{id}/download', function ($id) use ($ensureUserRole) {
    //         $ensureUserRole('pengguna');

    //         // Verifikasi buku KIA milik user
    //         $dataKia = \App\Models\DataKia::where('id', $id)
    //             ->where('user_id', auth()->id())
    //             ->first();
    //         abort_unless($dataKia, 403, 'Akses ditolak.');

    //         $pdfPath = resource_path('views/buku/Buku KIA (Permenkes).pdf');
    //         abort_unless(file_exists($pdfPath), 404, 'File Buku KIA tidak ditemukan.');

    //         return response()->download($pdfPath, 'Buku KIA (Permenkes).pdf');
    //     })->name('pengguna.kia.download');
    // });

    Route::get("/pengguna/kia/Buku-KIA", function () use ($ensureUserRole) {
        $ensureUserRole("pengguna");

        $dataKia = \App\Models\DataKia::with([
            "ibu",
            "suami",
            "anak",
            "layanan",
            "riwayat",
            "ttdTrackings",
            "pemantauanMingguans",
            "absenKelasIbuHamils",
            "persiapanMelahirkan",
            "pemantauanIbuNifas",
            "keluargaBerencana",
            "bayiBaruLahir",
            "pemantauanBayis",
            "warnaTinja",
            "absenKelasBalitas",
            "pemantauanMingguanBayis",
            "perkembanganBayi",
            "pemantauanBulananBayis",
            "perkembanganBayi6Bulan",
            "pemantauanBulananBayi12s",
            "perkembanganBayi9Bulan",
            "perkembanganBayi12Bulan",
            "pemantauanBulananAnak24s",
            "perkembanganBayi18Bulan",
            "perkembanganBayi24Bulan",
            "pemantauanBulananAnak72s",
            "perkembanganAnak36Bulan",
            "perkembanganAnak48Bulan",
            "perkembanganAnak60Bulan",
            "perkembanganAnak72Bulan",
            "kesehatanLingkungan",
            "pelayananKesehatanIbu",
            "evaluasiKesehatanIbu",
            "pemeriksaanTrimester1",
            "catatanPelayananTrimester1",
            "pemeriksaanTrimester2",
            "catatanPelayananTrimester2",
        ])
            ->where("user_id", auth()->id())
            ->first();

        abort_unless(
            $dataKia,
            404,
            "Data Buku KIA tidak ditemukan. Silakan isi data terlebih dahulu.",
        );

        $pdfService = new \App\Services\KiaPdfService();
        $pdfContent = $pdfService->generate($dataKia);

        $namaIbu = $dataKia->ibu->nama ?? "KIA";

        return response($pdfContent, 200, [
            "Content-Type" => "application/pdf",
            "Content-Disposition" =>
                'inline; filename="Buku_KIA_' . $namaIbu . '.pdf"',
        ]);
    })->name("pengguna.kia.Buku-KIA");

    // Route download PDF terisi data pengguna
    Route::get("/pengguna/kia/download", function () use ($ensureUserRole) {
        $ensureUserRole("pengguna");

        $dataKia = \App\Models\DataKia::with([
            "ibu",
            "suami",
            "anak",
            "layanan",
            "riwayat",
            "ttdTrackings",
            "pemantauanMingguans",
            "absenKelasIbuHamils",
            "persiapanMelahirkan",
            "pemantauanIbuNifas",
            "keluargaBerencana",
            "bayiBaruLahir",
            "pemantauanBayis",
            "warnaTinja",
            "absenKelasBalitas",
            "pemantauanMingguanBayis",
            "perkembanganBayi",
            "pemantauanBulananBayis",
            "perkembanganBayi6Bulan",
            "pemantauanBulananBayi12s",
            "perkembanganBayi9Bulan",
            "perkembanganBayi12Bulan",
            "pemantauanBulananAnak24s",
            "perkembanganBayi18Bulan",
            "perkembanganBayi24Bulan",
            "pemantauanBulananAnak72s",
            "perkembanganAnak36Bulan",
            "perkembanganAnak48Bulan",
            "perkembanganAnak60Bulan",
            "perkembanganAnak72Bulan",
            "kesehatanLingkungan",
            "pelayananKesehatanIbu",
            "evaluasiKesehatanIbu",
            "pemeriksaanTrimester1",
            "catatanPelayananTrimester1",
            "pemeriksaanTrimester2",
            "catatanPelayananTrimester2",
        ])
            ->where("user_id", auth()->id())
            ->first();

        abort_unless(
            $dataKia,
            404,
            "Data Buku KIA tidak ditemukan. Silakan isi data terlebih dahulu.",
        );

        $pdfService = new \App\Services\KiaPdfService();
        $pdfContent = $pdfService->generate($dataKia);

        $namaIbu = $dataKia->ibu->nama ?? "KIA";

        return response($pdfContent, 200, [
            "Content-Type" => "application/pdf",
            "Content-Disposition" =>
                'attachment; filename="Buku_KIA_' . $namaIbu . '.pdf"',
        ]);
    })->name("pengguna.kia.download");

    // Route PDF template kosong (untuk referensi)
    Route::get("/pengguna/kia/pdf", function () use ($ensureUserRole) {
        $ensureUserRole("pengguna");

        $pdfPath = resource_path("views/buku/Buku KIA (Permenkes).pdf");

        abort_unless(
            file_exists($pdfPath),
            404,
            "File Buku KIA tidak ditemukan.",
        );

        return response()->file($pdfPath, [
            "Content-Type" => "application/pdf",
            "Content-Disposition" =>
                'inline; filename="Buku KIA (Permenkes).pdf"',
        ]);
    })->name("pengguna.kia.pdf");
});

// Forgot password routes (simple UI + send reset link)
Route::get("/forgot-password", function () {
    return view("auth.forgot-password");
})->name("password.request");

Route::post("/forgot-password", function (Illuminate\Http\Request $request) {
    $request->validate(["email" => "required|email"]);

    $status = Password::sendResetLink($request->only("email"));

    return $status === Password::RESET_LINK_SENT
        ? back()->with("status", __($status))
        : back()->withErrors(["email" => __($status)]);
})->name("password.email");

Route::get("/who-am-i", function () {
    return response()->json([
        "authenticated" => auth()->check(),
        "user" => auth()->check()
            ? [
                "id" => auth()->user()->id,
                "email" => auth()->user()->email,
                "role" => auth()->user()->role,
                "name" => auth()->user()->name,
            ]
            : null,
        "guard" => config("auth.defaults.guard"),
    ]);
});

Route::get("/debug-pdf/{id}", function ($id) {
    $dataKia = \App\Models\DataKia::with([
        "ibu",
        "suami",
        "anak",
        "layanan",
        "riwayat",
        "ttdTrackings",
        "pemantauanMingguans",
        "absenKelasIbuHamils",
        "persiapanMelahirkan",
        "pemantauanIbuNifas",
        "keluargaBerencana",
        "bayiBaruLahir",
        "pemantauanBayis",
        "warnaTinja",
        "absenKelasBalitas",
        "pemantauanMingguanBayis",
        "perkembanganBayi",
        "pemantauanBulananBayis",
        "perkembanganBayi6Bulan",
        "pemantauanBulananBayi12s",
        "perkembanganBayi9Bulan",
        "perkembanganBayi12Bulan",
        "pemantauanBulananAnak24s",
        "perkembanganBayi18Bulan",
        "perkembanganBayi24Bulan",
        "pemantauanBulananAnak72s",
        "perkembanganAnak36Bulan",
        "perkembanganAnak48Bulan",
        "perkembanganAnak60Bulan",
        "perkembanganAnak72Bulan",
        "kesehatanLingkungan",
    ])->findOrFail($id);

    $pdfService = new \App\Services\KiaPdfService();
    $pdfContent = $pdfService->generate($dataKia);

    return response($pdfContent, 200, [
        "Content-Type" => "application/pdf",
        "Content-Disposition" => 'inline; filename="debug_kia_' . $id . '.pdf"',
    ]);
});

Route::get("/bidan/kia", [
    \App\Http\Controllers\DataKiaController::class,
    "indexNakes",
])->name("bidan.kia");
Route::get("/bidan/kia/{id}/edit-riwayat", [
    \App\Http\Controllers\DataKiaController::class,
    "editRiwayat",
])->name("bidan.kia.edit_riwayat");
Route::post("/bidan/kia/{id}/save-riwayat", [
    \App\Http\Controllers\DataKiaController::class,
    "saveRiwayat",
])->name("bidan.kia.save_riwayat");
Route::get("/bidan/kia/{id}/edit-pelayanan", [
    \App\Http\Controllers\DataKiaController::class,
    "editPelayanan",
])->name("bidan.kia.edit_pelayanan");
Route::post("/bidan/kia/{id}/save-pelayanan", [
    \App\Http\Controllers\DataKiaController::class,
    "savePelayanan",
])->name("bidan.kia.save_pelayanan");
Route::get("/bidan/kia/{id}/edit-evaluasi", [
    \App\Http\Controllers\DataKiaController::class,
    "editEvaluasi",
])->name("bidan.kia.edit_evaluasi");
Route::post("/bidan/kia/{id}/save-evaluasi", [
    \App\Http\Controllers\DataKiaController::class,
    "saveEvaluasi",
])->name("bidan.kia.save_evaluasi");
Route::get("/bidan/kia/{id}/edit-trimester1", [
    \App\Http\Controllers\DataKiaController::class,
    "editTrimester1",
])->name("bidan.kia.edit_trimester1");
Route::post("/bidan/kia/{id}/save-trimester1", [
    \App\Http\Controllers\DataKiaController::class,
    "saveTrimester1",
])->name("bidan.kia.save_trimester1");
Route::get("/bidan/kia/{id}/edit-trimester2", [
    \App\Http\Controllers\DataKiaController::class,
    "editTrimester2",
])->name("bidan.kia.edit_trimester2");
Route::post("/bidan/kia/{id}/save-trimester2", [
    \App\Http\Controllers\DataKiaController::class,
    "saveTrimester2",
])->name("bidan.kia.save_trimester2");

Route::get("/dokter/kia", [
    \App\Http\Controllers\DataKiaController::class,
    "indexNakes",
])->name("dokter.kia");
Route::get("/dokter/kia/{id}/edit-riwayat", [
    \App\Http\Controllers\DataKiaController::class,
    "editRiwayat",
])->name("dokter.kia.edit_riwayat");
Route::post("/dokter/kia/{id}/save-riwayat", [
    \App\Http\Controllers\DataKiaController::class,
    "saveRiwayat",
])->name("dokter.kia.save_riwayat");
Route::get("/dokter/kia/{id}/edit-pelayanan", [
    \App\Http\Controllers\DataKiaController::class,
    "editPelayanan",
])->name("dokter.kia.edit_pelayanan");
Route::post("/dokter/kia/{id}/save-pelayanan", [
    \App\Http\Controllers\DataKiaController::class,
    "savePelayanan",
])->name("dokter.kia.save_pelayanan");
Route::get("/dokter/kia/{id}/edit-evaluasi", [
    \App\Http\Controllers\DataKiaController::class,
    "editEvaluasi",
])->name("dokter.kia.edit_evaluasi");
Route::post("/dokter/kia/{id}/save-evaluasi", [
    \App\Http\Controllers\DataKiaController::class,
    "saveEvaluasi",
])->name("dokter.kia.save_evaluasi");
Route::get("/dokter/kia/{id}/edit-trimester1", [
    \App\Http\Controllers\DataKiaController::class,
    "editTrimester1",
])->name("dokter.kia.edit_trimester1");
Route::post("/dokter/kia/{id}/save-trimester1", [
    \App\Http\Controllers\DataKiaController::class,
    "saveTrimester1",
])->name("dokter.kia.save_trimester1");
Route::get("/dokter/kia/{id}/edit-trimester2", [
    \App\Http\Controllers\DataKiaController::class,
    "editTrimester2",
])->name("dokter.kia.edit_trimester2");
Route::post("/dokter/kia/{id}/save-trimester2", [
    \App\Http\Controllers\DataKiaController::class,
    "saveTrimester2",
])->name("dokter.kia.save_trimester2");

Route::get("/pengguna/ttd", [
    \App\Http\Controllers\DataKiaController::class,
    "ttdIndex",
])
    ->middleware("auth")
    ->name("pengguna.ttd");
Route::post("/pengguna/ttd/save", [
    \App\Http\Controllers\DataKiaController::class,
    "ttdStore",
])
    ->middleware("auth")
    ->name("pengguna.ttd.store");

Route::get("/pengguna/pemantauan", [
    \App\Http\Controllers\DataKiaController::class,
    "pemantauanIndex",
])
    ->middleware("auth")
    ->name("pengguna.pemantauan");
Route::post("/pengguna/pemantauan/save", [
    \App\Http\Controllers\DataKiaController::class,
    "pemantauanStore",
])
    ->middleware("auth")
    ->name("pengguna.pemantauan.save");

Route::get("/pengguna/kelas-ibu", [
    \App\Http\Controllers\DataKiaController::class,
    "kelasIbuIndex",
])
    ->middleware("auth")
    ->name("pengguna.kelas_ibu");
Route::post("/pengguna/kelas-ibu/save", [
    \App\Http\Controllers\DataKiaController::class,
    "kelasIbuStore",
])
    ->middleware("auth")
    ->name("pengguna.kelas_ibu.save");

Route::get("/pengguna/persiapan-melahirkan", [
    \App\Http\Controllers\DataKiaController::class,
    "persiapanIndex",
])
    ->middleware("auth")
    ->name("pengguna.persiapan");
Route::post("/pengguna/persiapan-melahirkan/save", [
    \App\Http\Controllers\DataKiaController::class,
    "persiapanStore",
])
    ->middleware("auth")
    ->name("pengguna.persiapan.save");

Route::get("/pengguna/pemantauan-nifas", [
    \App\Http\Controllers\DataKiaController::class,
    "nifasIndex",
])
    ->middleware("auth")
    ->name("pengguna.nifas");
Route::post("/pengguna/pemantauan-nifas/save", [
    \App\Http\Controllers\DataKiaController::class,
    "nifasStore",
])
    ->middleware("auth")
    ->name("pengguna.nifas.save");

Route::get("/pengguna/keluarga-berencana", [
    \App\Http\Controllers\DataKiaController::class,
    "kbIndex",
])
    ->middleware("auth")
    ->name("pengguna.kb");
Route::post("/pengguna/keluarga-berencana/save", [
    \App\Http\Controllers\DataKiaController::class,
    "kbStore",
])
    ->middleware("auth")
    ->name("pengguna.kb.save");

Route::get("/pengguna/bayi-baru-lahir", [
    \App\Http\Controllers\DataKiaController::class,
    "bayiIndex",
])
    ->middleware("auth")
    ->name("pengguna.bayi");
Route::post("/pengguna/bayi-baru-lahir/save", [
    \App\Http\Controllers\DataKiaController::class,
    "bayiStore",
])
    ->middleware("auth")
    ->name("pengguna.bayi.save");

Route::get("/pengguna/pemantauan-bayi", [
    \App\Http\Controllers\DataKiaController::class,
    "pemantauanBayiIndex",
])
    ->middleware("auth")
    ->name("pengguna.pemantauan_bayi");
Route::post("/pengguna/pemantauan-bayi/save", [
    \App\Http\Controllers\DataKiaController::class,
    "pemantauanBayiStore",
])
    ->middleware("auth")
    ->name("pengguna.pemantauan_bayi.save");

Route::get("/pengguna/warna-tinja", [
    \App\Http\Controllers\DataKiaController::class,
    "warnaTinjaIndex",
])
    ->middleware("auth")
    ->name("pengguna.warna_tinja");
Route::post("/pengguna/warna-tinja/save", [
    \App\Http\Controllers\DataKiaController::class,
    "warnaTinjaStore",
])
    ->middleware("auth")
    ->name("pengguna.warna_tinja.save");

Route::get("/pengguna/kelas-balita", [
    \App\Http\Controllers\DataKiaController::class,
    "kelasBalitaIndex",
])
    ->middleware("auth")
    ->name("pengguna.kelas_balita");
Route::post("/pengguna/kelas-balita/save", [
    \App\Http\Controllers\DataKiaController::class,
    "kelasBalitaStore",
])
    ->middleware("auth")
    ->name("pengguna.kelas_balita.save");

Route::get("/pengguna/mingguan-bayi", [
    \App\Http\Controllers\DataKiaController::class,
    "pemantauanMingguanBayiIndex",
])
    ->middleware("auth")
    ->name("pengguna.mingguan_bayi");
Route::post("/pengguna/mingguan-bayi/save", [
    \App\Http\Controllers\DataKiaController::class,
    "pemantauanMingguanBayiStore",
])
    ->middleware("auth")
    ->name("pengguna.mingguan_bayi.save");
Route::post("/pengguna/perkembangan-bayi/save", [
    \App\Http\Controllers\DataKiaController::class,
    "perkembanganBayiStore",
])
    ->middleware("auth")
    ->name("pengguna.perkembangan_bayi.save");

Route::get("/pengguna/bulanan-bayi", [
    \App\Http\Controllers\DataKiaController::class,
    "pemantauanBulananBayiIndex",
])
    ->middleware("auth")
    ->name("pengguna.bulanan_bayi");
Route::post("/pengguna/bulanan-bayi/save", [
    \App\Http\Controllers\DataKiaController::class,
    "pemantauanBulananBayiStore",
])
    ->middleware("auth")
    ->name("pengguna.bulanan_bayi.save");
Route::post("/pengguna/perkembangan-bayi-6-bulan/save", [
    \App\Http\Controllers\DataKiaController::class,
    "perkembanganBayi6BulanStore",
])
    ->middleware("auth")
    ->name("pengguna.perkembangan_bayi_6_bulan.save");

Route::get("/pengguna/bulanan-bayi-12", [
    \App\Http\Controllers\DataKiaController::class,
    "pemantauanBulananBayi12Index",
])
    ->middleware("auth")
    ->name("pengguna.bulanan_bayi_12");
Route::post("/pengguna/bulanan-bayi-12/save", [
    \App\Http\Controllers\DataKiaController::class,
    "pemantauanBulananBayi12Store",
])
    ->middleware("auth")
    ->name("pengguna.bulanan_bayi_12.save");
Route::post("/pengguna/perkembangan-bayi-9-bulan/save", [
    \App\Http\Controllers\DataKiaController::class,
    "perkembanganBayi9BulanStore",
])
    ->middleware("auth")
    ->name("pengguna.perkembangan_bayi_9_bulan.save");
Route::post("/pengguna/perkembangan-bayi-12-bulan/save", [
    \App\Http\Controllers\DataKiaController::class,
    "perkembanganBayi12BulanStore",
])
    ->middleware("auth")
    ->name("pengguna.perkembangan_bayi_12_bulan.save");

Route::get("/pengguna/bulanan-anak-24", [
    \App\Http\Controllers\DataKiaController::class,
    "pemantauanBulananAnak24Index",
])
    ->middleware("auth")
    ->name("pengguna.bulanan_anak_24");
Route::post("/pengguna/bulanan-anak-24/save", [
    \App\Http\Controllers\DataKiaController::class,
    "pemantauanBulananAnak24Store",
])
    ->middleware("auth")
    ->name("pengguna.bulanan_anak_24.save");
Route::post("/pengguna/perkembangan-bayi-18-bulan/save", [
    \App\Http\Controllers\DataKiaController::class,
    "perkembanganBayi18BulanStore",
])
    ->middleware("auth")
    ->name("pengguna.perkembangan_bayi_18_bulan.save");
Route::post("/pengguna/perkembangan-bayi-24-bulan/save", [
    \App\Http\Controllers\DataKiaController::class,
    "perkembanganBayi24BulanStore",
])
    ->middleware("auth")
    ->name("pengguna.perkembangan_bayi_24_bulan.save");

Route::get("/pengguna/bulanan-anak-72", [
    \App\Http\Controllers\DataKiaController::class,
    "pemantauanBulananAnak72Index",
])
    ->middleware("auth")
    ->name("pengguna.bulanan_anak_72");
Route::post("/pengguna/bulanan-anak-72/save", [
    \App\Http\Controllers\DataKiaController::class,
    "pemantauanBulananAnak72Store",
])
    ->middleware("auth")
    ->name("pengguna.bulanan_anak_72.save");
Route::post("/pengguna/perkembangan-anak-36-bulan/save", [
    \App\Http\Controllers\DataKiaController::class,
    "perkembanganAnak36BulanStore",
])
    ->middleware("auth")
    ->name("pengguna.perkembangan_anak_36_bulan.save");
Route::post("/pengguna/perkembangan-anak-48-bulan/save", [
    \App\Http\Controllers\DataKiaController::class,
    "perkembanganAnak48BulanStore",
])
    ->middleware("auth")
    ->name("pengguna.perkembangan_anak_48_bulan.save");
Route::post("/pengguna/perkembangan-anak-60-bulan/save", [
    \App\Http\Controllers\DataKiaController::class,
    "perkembanganAnak60BulanStore",
])
    ->middleware("auth")
    ->name("pengguna.perkembangan_anak_60_bulan.save");
Route::post("/pengguna/perkembangan-anak-72-bulan/save", [
    \App\Http\Controllers\DataKiaController::class,
    "perkembanganAnak72BulanStore",
])
    ->middleware("auth")
    ->name("pengguna.perkembangan_anak_72_bulan.save");

Route::get("/pengguna/kesehatan-lingkungan", [
    \App\Http\Controllers\DataKiaController::class,
    "kesehatanLingkunganIndex",
])
    ->middleware("auth")
    ->name("pengguna.kesehatan_lingkungan");
Route::post("/pengguna/kesehatan-lingkungan/save", [
    \App\Http\Controllers\DataKiaController::class,
    "kesehatanLingkunganStore",
])
    ->middleware("auth")
    ->name("pengguna.kesehatan_lingkungan.save");

Route::post("/pengguna/kia/wizard/save", [
    \App\Http\Controllers\DataKiaController::class,
    "saveWizard",
])
    ->middleware("auth")
    ->name("pengguna.kia.wizard.save");

if (!class_exists("MyFpdi")) {
    class MyFpdi extends \setasign\Fpdi\Fpdi
    {
        protected $angle = 0;

        function Rotate($angle, $x = -1, $y = -1)
        {
            if ($x == -1) {
                $x = $this->x;
            }
            if ($y == -1) {
                $y = $this->y;
            }
            if ($this->angle != 0) {
                $this->_out("Q");
            }
            $this->angle = $angle;
            if ($angle != 0) {
                $angle *= M_PI / 180;
                $c = cos($angle);
                $s = sin($angle);
                $cx = $x * $this->k;
                $cy = ($this->h - $y) * $this->k;
                $this->_out(
                    sprintf(
                        "q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm",
                        $c,
                        $s,
                        -$s,
                        $c,
                        $cx,
                        $cy,
                        -$cx,
                        -$cy,
                    ),
                );
            }
        }

        function RotatedText($x, $y, $txt, $angle)
        {
            $this->Rotate($angle, $x, $y);
            $this->Text($x, $y, $txt);
            $this->Rotate(0);
        }

        function _endpage()
        {
            if ($this->angle != 0) {
                $this->angle = 0;
                $this->_out("Q");
            }
            parent::_endpage();
        }
    }
}