

<?php $__env->startSection('title', 'Dashboard Admin - MomSpire'); ?>
<?php $__env->startSection('header_title', 'Dashboard'); ?>
<?php $__env->startSection('header_subtitle'); ?>
Selamat datang kembali, <strong><?php echo e(auth()->user()->name); ?></strong>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('head'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row g-2 g-sm-3 g-lg-4 mb-4">
        <div class="col-6 col-sm-4 col-md-3 col-lg-2">
            <div class="stat-card" data-role-filter="pengguna" title="Klik untuk lihat user role Pengguna" style="cursor: pointer;">
                <div class="stat-icon pink"><i class="bi bi-people-fill"></i></div>
                <div class="stat-info">
                    <h3 class="stat-value" id="statTotalPengguna" style="font-size: clamp(1rem, 2.5vw, 1.5rem);"><?php echo e($penggunaCount); ?></h3>
                    <p class="stat-label" style="font-size: clamp(0.7rem, 2vw, 0.85rem);">Total Pengguna</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-sm-4 col-md-3 col-lg-2">
            <div class="stat-card" data-role-filter="bidan" title="Klik untuk lihat user role Bidan" style="cursor: pointer;">
                <div class="stat-icon blue"><i class="bi bi-person-badge-fill"></i></div>
                <div class="stat-info">
                    <h3 class="stat-value" id="statTotalBidan" style="font-size: clamp(1rem, 2.5vw, 1.5rem);"><?php echo e($bidanCount); ?></h3>
                    <p class="stat-label" style="font-size: clamp(0.7rem, 2vw, 0.85rem);">Total Bidan</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-sm-4 col-md-3 col-lg-2">
            <div class="stat-card" data-role-filter="dokter" title="Klik untuk lihat user role Dokter" style="cursor: pointer;">
                <div class="stat-icon green"><i class="bi bi-person-workspace"></i></div>
                <div class="stat-info">
                    <h3 class="stat-value" id="statTotalDokter" style="font-size: clamp(1rem, 2.5vw, 1.5rem);"><?php echo e($dokterCount); ?></h3>
                    <p class="stat-label" style="font-size: clamp(0.7rem, 2vw, 0.85rem);">Total Dokter</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-sm-4 col-md-3 col-lg-2">
            <div class="stat-card" data-role-filter="articles" title="Klik untuk lihat daftar artikel" style="cursor: pointer;">
                <div class="stat-icon orange"><i class="bi bi-journal-text"></i></div>
                <div class="stat-info">
                    <h3 class="stat-value" id="statTotalArtikel" style="font-size: clamp(1rem, 2.5vw, 1.5rem);"><?php echo e($articleCount ?? 0); ?></h3>
                    <p class="stat-label" style="font-size: clamp(0.7rem, 2vw, 0.85rem);">Total Artikel</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-sm-4 col-md-3 col-lg-2">
            <div class="stat-card">
                <div class="stat-icon purple"><i class="bi bi-chat-dots-fill"></i></div>
                <div class="stat-info">
                    <h3 class="stat-value" style="font-size: clamp(1rem, 2.5vw, 1.5rem);"><?php echo e($consultationCount ?? 0); ?></h3>
                    <p class="stat-label" style="font-size: clamp(0.7rem, 2vw, 0.85rem);">Total Konsultasi</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-sm-4 col-md-3 col-lg-2">
            <div class="stat-card" data-role-filter="pengguna_hamil" title="Klik untuk lihat pengguna yang sedang hamil" style="cursor: pointer;">
                <div class="stat-icon teal"><i class="bi bi-heart-pulse"></i></div>
                <div class="stat-info">
                    <h3 class="stat-value" id="statIbuHamilAktif" style="font-size: clamp(1rem, 2.5vw, 1.5rem);"><?php echo e($ibuHamilAktifCount); ?></h3>
                    <p class="stat-label" style="font-size: clamp(0.7rem, 2vw, 0.85rem);">Ibu Hamil Aktif</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 g-lg-4 mb-4">
        <div class="col-12 col-lg-6">
            <div class="admin-card">
                <div class="card-header">
                    <h5 style="font-size: clamp(0.95rem, 3vw, 1.1rem); margin-bottom: 0.25rem;">Pertumbuhan Pengguna (Per Bulan)</h5>
                    <span class="text-muted small">Statistik 6 bulan terakhir</span>
                </div>
                <div class="card-body">
                    <canvas id="userGrowthChart" height="80"></canvas>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-6">
            <div class="admin-card">
                <div class="card-header">
                    <h5 style="font-size: clamp(0.95rem, 3vw, 1.1rem); margin-bottom: 0.25rem;">Artikel Dibuat 7 Hari Terakhir</h5>
                    <span class="text-muted small">Jumlah artikel edukasi dari database</span>
                </div>
                <div class="card-body">
                    <canvas id="articleReadersChart" height="80"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 g-lg-4">
        <div class="col-12">
            <div class="admin-card">
                <div class="card-header">
                    <h5 style="margin-bottom: 0;">Activity Log - Aktivitas Terbaru</h5>
                    <button class="btn btn-sm btn-outline-primary">Lihat Semua</button>
                </div>
                <div class="card-body p-0">
                    <div class="activity-log">
                        <?php $__empty_1 = true; $__currentLoopData = $recentActivities ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="activity-item">
                                <div class="activity-icon" style="background: <?php echo e($activity['color'] ?? '#e63980'); ?>26; color: <?php echo e($activity['color'] ?? '#e63980'); ?>;">
                                    <i class="bi <?php echo e($activity['icon'] ?? 'bi-info-circle-fill'); ?>"></i>
                                </div>
                                <div class="activity-content">
                                    <h6 style="font-size: clamp(0.9rem, 2vw, 1rem); margin-bottom: 0.25rem;"><?php echo e($activity['title'] ?? 'Aktivitas'); ?></h6>
                                    <p class="text-muted small"><?php echo e($activity['description'] ?? '-'); ?></p>
                                    <span class="activity-time"><?php echo e($activity['time'] ?? '-'); ?></span>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="text-center py-4 text-muted">Belum ada aktivitas terbaru.</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="dashboardStatsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title mb-0" id="dashboardStatsModalTitle">Detail Statistik</h5>
                        <small class="text-muted" id="dashboardStatsModalSubtitle">Daftar user berdasarkan kategori yang dipilih</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="dashboardStatsModalBody"></tbody>
                        </table>
                    </div>
                    <div id="dashboardStatsModalEmpty" class="text-center py-4 text-muted d-none">
                        Tidak ada data untuk kategori ini.
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    window.MOMSPIRE_ADMIN_DASHBOARD_DATA = <?php echo json_encode($dashboardUsers, 15, 512) ?>;
    window.MOMSPIRE_ADMIN_ARTICLES = <?php echo json_encode($dashboardArticles ?? [], 15, 512) ?>;
    window.MOMSPIRE_ADMIN_USER_GROWTH_CHART = <?php echo json_encode($userGrowthChart ?? ['labels' => [], 'data' => []], 512) ?>;
    window.MOMSPIRE_ADMIN_ARTICLE_CHART = <?php echo json_encode($articleChart ?? ['labels' => [], 'data' => []], 512) ?>;

    document.addEventListener('DOMContentLoaded', function() {
        const userGrowthData = window.MOMSPIRE_ADMIN_USER_GROWTH_CHART || { labels: [], data: [] };
        const articleChartData = window.MOMSPIRE_ADMIN_ARTICLE_CHART || { labels: [], data: [] };

        const ctx1 = document.getElementById('userGrowthChart');
        if(ctx1) {
            new Chart(ctx1, {
                type: 'line',
                data: { labels: userGrowthData.labels || [], datasets: [{ label: 'User Baru', data: userGrowthData.data || [], borderColor: '#e63980', backgroundColor: 'rgba(230, 57, 128, 0.1)', fill: true, tension: 0.4 }] },
                options: { responsive: true, plugins: { legend: { display: false } } }
            });
        }
        const ctx2 = document.getElementById('articleReadersChart');
         if(ctx2) {
            new Chart(ctx2, {
                type: 'bar',
                data: { labels: articleChartData.labels || [], datasets: [{ label: 'Artikel Dibuat', data: articleChartData.data || [], backgroundColor: '#e63980' }] },
                options: { responsive: true, plugins: { legend: { display: false } } }
            });
        }

        const dashboardData = Array.isArray(window.MOMSPIRE_ADMIN_DASHBOARD_DATA) ? window.MOMSPIRE_ADMIN_DASHBOARD_DATA : [];
        const dashboardArticles = Array.isArray(window.MOMSPIRE_ADMIN_ARTICLES) ? window.MOMSPIRE_ADMIN_ARTICLES : [];
        const modalEl = document.getElementById('dashboardStatsModal');
        const modalTitle = document.getElementById('dashboardStatsModalTitle');
        const modalSubtitle = document.getElementById('dashboardStatsModalSubtitle');
        const modalBody = document.getElementById('dashboardStatsModalBody');
        const modalEmpty = document.getElementById('dashboardStatsModalEmpty');

        const labels = {
            pengguna: { title: 'Daftar Pengguna', subtitle: 'Semua akun dengan role pengguna' },
            bidan: { title: 'Daftar Bidan', subtitle: 'Semua akun dengan role bidan' },
            dokter: { title: 'Daftar Dokter', subtitle: 'Semua akun dengan role dokter' },
            pengguna_hamil: { title: 'Ibu Hamil Aktif', subtitle: 'Pengguna yang sedang hamil' },
            articles: { title: 'Daftar Artikel', subtitle: 'Artikel yang tersimpan di database' },
        };

        function getRows(filter) {
            if (filter === 'pengguna_hamil') {
                return dashboardData.filter((user) => user.role === 'pengguna' && Number(user.is_hamil) === 1);
            }

            if (filter === 'articles') {
                return dashboardArticles.map(a => ({
                    title: a.title || '-',
                    category: a.category || '-',
                    url: a.url || '#',
                    createdAt: a.createdAt || null,
                }));
            }

            return dashboardData.filter((user) => user.role === filter);
        }

        function badgeForUser(user) {
            if (user.role !== 'pengguna') {
                return '<span class="badge text-bg-light">-</span>';
            }

            return Number(user.is_hamil) === 1
                ? '<span class="badge text-bg-danger">Ibu Hamil Aktif</span>'
                : '<span class="badge text-bg-secondary">Tidak Hamil</span>';
        }

        // Article stat - use server-side value from PHP (no localStorage override)
        // const statTotalArtikelEl = document.getElementById('statTotalArtikel');
        // function updateArticleStat() {
        //     try {
        //         const arts = getArticlesFromLocalStorage();
        //         if (statTotalArtikelEl) statTotalArtikelEl.textContent = String(arts.length || 0);
        //     } catch (e) {
        //         console.error('Gagal update stat artikel', e);
        //     }
        // }

        // updateArticleStat();
        // // update when storage changes (other tab or when ManajemenArtikel updates)
        // window.addEventListener('storage', function(e) {
        //     if (e.key === 'momspire_articles') updateArticleStat();
        // });

        document.querySelectorAll('.stat-card[data-role-filter]').forEach((card) => {
            card.addEventListener('click', function() {
                const filter = this.getAttribute('data-role-filter') || 'pengguna';
                const rows = getRows(filter);
                const meta = labels[filter] || { title: 'Detail Statistik', subtitle: 'Daftar data' };

                if (modalTitle) modalTitle.textContent = meta.title;
                if (modalSubtitle) modalSubtitle.textContent = meta.subtitle;
                const modalTable = modalEl ? modalEl.querySelector('table') : null;
                const modalThead = modalTable ? modalTable.querySelector('thead') : null;

                if (modalBody) {
                    if (filter === 'articles') {
                        if (modalThead) {
                            modalThead.innerHTML = `
                                <tr>
                                    <th>Judul</th>
                                    <th>Kategori</th>
                                    <th>Link</th>
                                    <th>Dibuat</th>
                                </tr>
                            `;
                        }

                        modalBody.innerHTML = rows.map((a) => `
                            <tr>
                                <td>
                                    <div class="fw-semibold">${a.title || '-'}</div>
                                </td>
                                <td><span class="badge text-bg-light">${a.category || '-'}</span></td>
                                <td><a href="${a.url || '#'}" target="_blank" rel="noopener noreferrer" class="text-decoration-none">${(a.url || '#').length > 60 ? (a.url || '#').slice(0,60) + '...' : (a.url || '#')}</a></td>
                                <td>${a.createdAt ? new Date(a.createdAt).toLocaleDateString('id-ID') : '-'}</td>
                            </tr>
                        `).join('');
                    } else {
                        if (modalThead) {
                            modalThead.innerHTML = `
                                <tr>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                </tr>
                            `;
                        }

                        modalBody.innerHTML = rows.map((user) => `
                            <tr>
                                <td>${user.name || '-'}</td>
                                <td>${user.email || '-'}</td>
                                <td><span class="badge" style="text-transform:capitalize; background:${user.role === 'bidan' ? '#00b894' : user.role === 'dokter' ? '#00d4aa' : '#e63980'}; color:#fff;">${user.role || '-'}</span></td>
                                <td>${badgeForUser(user)}</td>
                            </tr>
                        `).join('');
                    }
                }

                if (modalEmpty) modalEmpty.classList.toggle('d-none', rows.length > 0);

                if (modalEl) {
                    bootstrap.Modal.getOrCreateInstance(modalEl).show();
                }
            });
        });
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('Admin.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\MomSpire\resources\views/Admin/DashboardAdmin.blade.php ENDPATH**/ ?>