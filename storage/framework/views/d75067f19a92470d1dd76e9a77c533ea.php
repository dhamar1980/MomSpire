<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - MomSpire</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: linear-gradient(135deg, #fff5f8 0%, #ffeef6 100%); min-height:100vh; display:flex; align-items:center; justify-content:center; }
        .card { background: white; padding: 36px; border-radius: 10px; box-shadow: 0 10px 25px rgba(0,0,0,0.08); width:100%; max-width:420px; }
        .auth-logo{ width:84px; height:84px; margin:0 auto 10px; border-radius:9999px; padding:6px; overflow:hidden; border:4px solid rgba(255,107,129,0.08); background:#fff; }
        .auth-logo img{ width:100%; height:100%; object-fit:cover; border-radius:9999px }
        h1{ text-align:center; color:#333; margin:6px 0 6px; }
        p.lead{ text-align:center; color:#666; margin:0 0 18px }
        .form-group{ margin-bottom:18px }
        .form-group label{ display:block; margin-bottom:8px; color:#333 }
        .form-group input{ width:100%; padding:12px; border:1px solid #e6e9ef; border-radius:8px }
        .submit-btn{ width:100%; padding:12px; background:linear-gradient(90deg,#ff6b8f,#ff4d7a); color:#fff; border:none; border-radius:8px; font-weight:600 }
        .text-center{ text-align:center }
        .muted{ color:#888; font-size:14px }
        .error-message{ color:#e74c3c; font-size:13px; margin-top:6px }
        .success-message{ background:#e6fffa; color:#065f46; padding:10px; border-radius:6px; margin-bottom:12px }
    </style>
</head>
<body>
    <div class="card">
        <div class="auth-logo"><img src="<?php echo e(asset('foto/logo.jpg')); ?>" alt="Logo"></div>
        <h1>Lupa Password</h1>
        <p class="lead">Masukkan email Anda. Kami akan mengirimkan tautan untuk mereset password.</p>

        <?php if(session('status')): ?>
            <div class="success-message"><?php echo e(session('status')); ?></div>
        <?php endif; ?>

        <?php if($errors->any()): ?>
            <div style="background:#fff1f2;color:#611a15;padding:10px;border-radius:6px;margin-bottom:12px">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div style="margin:0"><?php echo e($error); ?></div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('password.email')); ?>">
            <?php echo csrf_field(); ?>

            <div class="form-group">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="<?php echo e(old('email')); ?>" required autofocus>
                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="error-message"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <button type="submit" class="submit-btn">Kirim Tautan Reset</button>

            <div class="text-center" style="margin-top:14px">
                <a href="<?php echo e(route('login')); ?>" class="muted">Kembali ke masuk</a>
            </div>
        </form>
    </div>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\MomSpire\resources\views\auth\forgot-password.blade.php ENDPATH**/ ?>