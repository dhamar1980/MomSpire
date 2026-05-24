

<?php $__env->startSection('title', 'Manajemen User - MomSpire'); ?>
<?php $__env->startSection('header_title', 'Manajemen User'); ?>
<?php $__env->startSection('header_subtitle', 'Kelola data akun dan role pengguna'); ?>

<?php $__env->startSection('content'); ?>
    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <ul class="mb-0 ps-3">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php
        $penggunaCount = $users->where('type', 'pengguna')->count();
        $bidanCount = $users->where('type', 'bidan')->count();
        $dokterCount = $users->where('type', 'dokter')->count();
        $adminCount = $users->where('type', 'admin')->count();
    ?>

    <div class="row g-4 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card" data-role-filter="pengguna" title="Tampilkan user role Pengguna" style="cursor: pointer;">
                <div class="stat-icon pink"><i class="bi bi-people-fill"></i></div>
                <div class="stat-info">
                    <h3 class="stat-value" id="statTotalPengguna"><?php echo e($penggunaCount); ?></h3>
                    <p class="stat-label">Total Pengguna</p>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card" data-role-filter="bidan" title="Filter user Bidan" style="cursor: pointer;">
                <div class="stat-icon blue"><i class="bi bi-person-badge-fill"></i></div>
                <div class="stat-info">
                    <h3 class="stat-value" id="statTotalBidan"><?php echo e($bidanCount); ?></h3>
                    <p class="stat-label">Total Bidan</p>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card" data-role-filter="dokter" title="Filter user Dokter" style="cursor: pointer;">
                <div class="stat-icon green"><i class="bi bi-person-workspace"></i></div>
                <div class="stat-info">
                    <h3 class="stat-value" id="statTotalDokter"><?php echo e($dokterCount); ?></h3>
                    <p class="stat-label">Total Dokter</p>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <button class="quick-action w-100 h-100" data-bs-toggle="modal" data-bs-target="#addUserModal">
                <div class="qa-icon"><i class="bi bi-person-plus-fill"></i></div>
                <span>Tambah User Baru</span>
            </button>
        </div>
    </div>

    <div class="admin-card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="mb-0"><i class="bi bi-people-fill me-2"></i>Daftar User</h5>
            <div class="d-flex gap-2">
                <button class="btn btn-sm btn-outline-primary user-filter-btn" data-role="all">Semua</button>
                <button class="btn btn-sm btn-outline-success user-filter-btn" data-role="bidan">Bidan</button>
                <button class="btn btn-sm btn-outline-info user-filter-btn" data-role="dokter">Dokter</button>
                <button class="btn btn-sm btn-outline-secondary user-filter-btn" data-role="pengguna">Pengguna</button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive" id="userManagementTable">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $users->where('type', '!=', 'admin'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($user->name); ?></td>
                                <td><?php echo e($user->email); ?></td>
                                <td>
                                    <span class="badge"
                                        style="<?php echo e($user->type === 'admin' ? 'background:#6f42c1' : ($user->type === 'bidan' ? 'background:#00b894' : ($user->type === 'dokter' ? 'background:#00d4aa' : 'background:#e63980'))); ?>; color:white; text-transform:capitalize;">
                                        <?php echo e($user->type); ?>

                                    </span>
                                </td>
                                <td>
                                    <?php if($user->type === 'pengguna'): ?>
                                        <span class="badge <?php echo e((int)($user->is_hamil ?? 0) === 1 ? 'text-bg-danger' : 'text-bg-secondary'); ?>">
                                            <?php echo e((int)($user->is_hamil ?? 0) === 1 ? 'Ibu Hamil Aktif' : 'Tidak Hamil'); ?>

                                        </span>
                                    <?php else: ?>
                                        <span class="badge text-bg-light">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <!-- Edit disabled for admin on this management page -->
                                    <form action="<?php echo e(route('admin.users.destroy')); ?>" method="POST" class="d-inline"
                                        onsubmit="return confirm('Yakin hapus akun ini?')">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="id" value="<?php echo e($user->id); ?>">
                                        <input type="hidden" name="type" value="<?php echo e($user->type); ?>">
                                        <button type="submit" class="btn btn-sm btn-danger" <?php if(auth()->id() === $user->id): echo 'disabled'; endif; ?>>
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Belum ada data user.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-person-plus-fill me-2"></i>Tambah User Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="laravelAddUserForm" action="<?php echo e(route('admin.users.store')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" id="inputNama" name="name" placeholder="Masukkan nama lengkap" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" id="inputEmail" name="email" placeholder="email@example.com" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Role</label>
                            <select class="form-select" id="inputRole" name="role" required>
                                <option value="">Pilih role...</option>
                                <option value="bidan">Bidan</option>
                                <option value="dokter">Dokter Spesialis</option>
                            </select>
                        </div>
                        <div class="mb-3 d-none" id="inputPregnancyGroup">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="inputStatusHamil" name="is_hamil" value="1">
                                <label class="form-check-label" for="inputStatusHamil">Sedang hamil</label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control" id="inputPassword" name="password" placeholder="Minimal 8 karakter" required>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-secondary flex-fill" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary-custom flex-fill">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="laravelEditUserForm" action="<?php echo e(route('admin.users.update')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" id="editUserId" name="id">
                        <input type="hidden" id="editUserType" name="type">
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" id="editNama" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" id="editEmail" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Role</label>
                            <select class="form-select" id="editRole" name="role" required>
                                <option value="pengguna">Pengguna</option>
                                <option value="bidan">Bidan</option>
                                <option value="dokter">Dokter Spesialis</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <div class="mb-3 d-none" id="editPregnancyGroup">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="editStatusHamil" name="is_hamil" value="1">
                                <label class="form-check-label" for="editStatusHamil">Sedang hamil</label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password Baru (Opsional)</label>
                            <input type="password" class="form-control" id="editPassword" name="password" placeholder="Kosongkan jika tidak ingin ganti password">
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-secondary flex-fill" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary-custom flex-fill">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Admin.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\MomSpire\resources\views\Admin\ManajemenUser.blade.php ENDPATH**/ ?>