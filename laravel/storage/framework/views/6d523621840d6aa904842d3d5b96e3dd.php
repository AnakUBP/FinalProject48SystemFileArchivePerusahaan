<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>

<body>
    <div class="container">
        <div class="left-side">
            <!-- Tempat Logo -->
            <div class="logo">
                <img src="../img/logo.svg" alt="Logo" class="logo-img" />
            </div>
            <h2 class="brand-name">Lettera</h2>
        </div>
        <div class="right-side">
            <div class="login-card">
                <h2 class="login-header">Login</h2>

                <?php if($errors->any()): ?>
                    <div class="alert alert-error">
                        <?php echo e($errors->first()); ?>

                    </div>
                <?php endif; ?>

                <form method="POST" action="<?php echo e(route('login')); ?>" class="login-form">
                    <?php echo csrf_field(); ?>
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo e(old('email')); ?>" required autofocus>
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                    <div class="forgot">
                        <a href="<?php echo e(route('password.request')); ?>">Forget Password?</a>
                    </div>
                    <button type="submit" class="btn-login">Masuk</button>
                </form>
            </div>
</body>

</html><?php /**PATH C:\laragon\www\Tata2\resources\views/welcome.blade.php ENDPATH**/ ?>