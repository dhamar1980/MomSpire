<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
	<title><?php echo $__env->yieldContent('title', config('app.name', 'MomSpire')); ?></title>
	<?php
		$currentUser = auth()->user();
		$currentUserPayload = $currentUser ? [
			'id' => $currentUser->id,
			'nama' => $currentUser->name,
			'email' => $currentUser->email,
			'role' => $currentUser->role,
			'no_telp' => $currentUser->no_telp ?? '',
		] : null;
	?>
	<script>
		window.MOMSPIRE_CURRENT_USER = <?php echo json_encode($currentUserPayload, 15, 512) ?>;
	</script>

	<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
	<?php echo app('Illuminate\Foundation\Vite')(['resources/css/style.css', 'resources/js/script.js']); ?>
	<style>
		.role-hero { background: linear-gradient(135deg, #0984e3 0%, #6c5ce7 100%); color: #fff; border-radius: 24px; }
		.role-card { border: 0; border-radius: 20px; box-shadow: 0 18px 40px rgba(15, 23, 42, .08); }

		.btn-back-page {
			background: #fff;
			border: 1px solid rgba(15, 23, 42, .12);
			border-radius: 999px;
			color: #0f172a;
			font-weight: 700;
			padding: 10px 16px;
			box-shadow: 0 10px 20px rgba(15, 23, 42, .05);
			transition: all .2s ease;
			text-decoration: none;
			display: inline-flex;
			align-items: center;
			gap: 6px;
		}

		.btn-back-page:hover {
			transform: translateY(-1px);
			border-color: rgba(15, 23, 42, .25);
			box-shadow: 0 14px 24px rgba(15, 23, 42, .12);
			color: #0f172a;
		}
	</style>
	<?php echo $__env->yieldPushContent('head'); ?>
</head>
<body class="admin-body">

	<aside class="admin-sidebar" id="adminSidebar">
		<div class="sidebar-header">
			<a href="<?php echo e(url('/')); ?>" class="sidebar-brand">
				<img src="<?php echo e(asset('foto/logo.jpg')); ?>" alt="Logo MomSpire" class="brand-logo brand-logo-image">
				<span>MomSpire Dokter</span>
			</a>
			<span class="sidebar-tag">Dokter Panel</span>
		</div>
		<nav class="sidebar-nav">
			<div class="nav-section">
				<span class="nav-label">MENU UTAMA</span>
				<a href="<?php echo e(route('dokter.dashboard')); ?>" class="nav-link <?php echo e(request()->routeIs('dokter.dashboard') ? 'active' : ''); ?>">
					<i class="bi bi-grid-1x2-fill"></i>
					<span>Dashboard</span>
				</a>
				<a href="<?php echo e(route('dokter.pengguna')); ?>" class="nav-link <?php echo e(request()->routeIs('dokter.pengguna*') ? 'active' : ''); ?>">
					<i class="bi bi-person-fill"></i>
					<span>Detail Pengguna</span>
				</a>
				<a href="<?php echo e(route('dokter.konsultasi')); ?>" class="nav-link <?php echo e(request()->routeIs('dokter.konsultasi*') ? 'active' : ''); ?>">
					<i class="bi bi-chat-dots-fill"></i>
					<span>Konsultasi</span>
				</a>
			</div>
			<div class="nav-section">
				<span class="nav-label">SISTEM</span>
				<a href="<?php echo e(route('dokter.settings')); ?>" class="nav-link <?php echo e(request()->routeIs('dokter.settings') ? 'active' : ''); ?>">
					<i class="bi bi-gear-fill"></i>
					<span>Pengaturan</span>
				</a>
			</div>
		</nav>
		<div class="sidebar-footer">
			<a href="#" id="logoutBtn" class="btn btn-logout" data-bs-toggle="modal" data-bs-target="#logoutModal">
				<i class="bi bi-box-arrow-left"></i>
				<span>Keluar</span>
			</a>
		</div>
	</aside>

	<main class="admin-main">
		<header class="admin-header">
			<div class="header-left">
				<?php if(View::hasSection('header_action')): ?>
					<?php echo $__env->yieldContent('header_action'); ?>
				<?php else: ?>
					<button type="button" class="btn btn-toggle-sidebar" id="toggleSidebar" onclick="return window.__momspireToggleSidebar(event)">
						<i class="bi bi-list"></i>
					</button>
				<?php endif; ?>
				<div>
					<?php if(View::hasSection('header_title')): ?>
						<h1 class="header-title"><?php echo $__env->yieldContent('header_title'); ?></h1>
					<?php endif; ?>
					<?php if(View::hasSection('header_subtitle')): ?>
						<p class="header-subtitle"><?php echo $__env->yieldContent('header_subtitle'); ?></p>
					<?php endif; ?>
				</div>
			</div>
		</header>

		<div class="admin-content">
			<?php echo $__env->yieldContent('content'); ?>
		</div>
	</main>

	<div class="modal fade" id="logoutModal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Konfirmasi Keluar</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
				</div>
				<div class="modal-body">
					<p>Anda akan keluar dari akun. Apakah yakin?</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
					<button type="button" id="logoutConfirmBtn" class="btn btn-danger">Keluar</button>
				</div>
			</div>
		</div>
	</div>

	<form id="adminLogoutForm" method="POST" action="<?php echo e(route('logout')); ?>" class="d-none">
		<?php echo csrf_field(); ?>
	</form>

	<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1100;">
		<div id="adminToast" class="toast align-items-center text-bg-dark border-0" role="alert">
			<div class="d-flex">
				<div class="toast-body">Berhasil!</div>
				<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
			</div>
		</div>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

	<!-- Firebase Realtime Database SDK for Real-time Chat -->
	<script src="https://www.gstatic.com/firebasejs/10.7.0/firebase-app-compat.js"></script>
	<script src="https://www.gstatic.com/firebasejs/10.7.0/firebase-database-compat.js"></script>
	<script src="/js/momspire-firebase.js"></script>

	<script>
		window.MOMSPIRE_USE_LEGACY_ADMIN_API = false;
		window.__momspireSidebarOpen = true;

		window.__momspireSyncSidebar = function() {
			const sidebar = document.getElementById('adminSidebar');
			const main = document.querySelector('.admin-main');
			if (!sidebar) return;

			const isMobile = window.matchMedia('(max-width: 991px)').matches;

			if (isMobile) {
				sidebar.style.transform = window.__momspireSidebarOpen ? 'translateX(0)' : 'translateX(-100%)';
				if (main) main.style.marginLeft = '0';
				return;
			}

			sidebar.style.transform = window.__momspireSidebarOpen ? 'translateX(0)' : 'translateX(-100%)';
			if (main) main.style.marginLeft = window.__momspireSidebarOpen ? '280px' : '0';
		};

		window.__momspireToggleSidebar = function(event) {
			if (event) {
				event.preventDefault();
				event.stopImmediatePropagation();
			}

			const sidebar = document.getElementById('adminSidebar');
			if (!sidebar) return false;

			window.__momspireSidebarOpen = !window.__momspireSidebarOpen;
			window.__momspireSyncSidebar();
			return false;
		};

		document.addEventListener('DOMContentLoaded', function() {
			window.__momspireSyncSidebar();
			window.addEventListener('resize', window.__momspireSyncSidebar);
		});
	</script>
	<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html><?php /**PATH C:\xampp\htdocs\MomSpire\resources\views\dokter\master.blade.php ENDPATH**/ ?>