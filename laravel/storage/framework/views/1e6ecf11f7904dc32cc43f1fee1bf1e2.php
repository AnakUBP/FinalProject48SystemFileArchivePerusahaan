<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistem Karyawan</title>
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/dashboard.css', 'resources/js/dashboard.js']); ?>
</head>

<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <img src="img/logo.svg" alt="Logo Perusahaan" class="logo-perusahaan" />
                <p>Admin</p>
            </div>
            <nav class="sidebar-nav">
                <ul>
                    <li class="active">
                        <a href="<?php echo e(route('dashboard')); ?>">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo e(route('template')); ?>">
                            <i class="fas bi-filetype-docx"></i> Template
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo e(route('jeniscuti.index')); ?>">
                            <i class="fas bi-calendar2-event"></i> Jenis Cuti
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo e(route('UsersProfiles')); ?>">
                            <i class="fas bi-calendar2-event"></i> Users
                        </a>
                    </li>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin')): ?>
                        <li>
                            <a href="<?php echo e(route('users.index')); ?>">
                                <i class="fas fa-users"></i> User Management
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Top Navigation -->
            <header class="top-nav">
                <div class="user-menu">
                    <div class="dropdown">
                        <button class="dropdown-btn">
                            <img src="<?php echo e(auth()->user()->profile->foto_profil ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name)); ?>"
                                alt="Profile">
                            <span><?php echo e(auth()->user()->name); ?></span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="dropdown-content">
                            <a href="<?php echo e(route('profile.show')); ?>"><i class="fas fa-user"></i> Profile</a>
                            <form method="POST" action="<?php echo e(route('logout')); ?>">
                                <?php echo csrf_field(); ?>
                                <button type="submit"><i class="fas fa-sign-out-alt"></i> Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>
            <div class="content">
                <h1 class="page-title">Dashboard</h1>
            </div>
        </main>
    </div>
</body>

</html><?php /**PATH C:\laragon\www\Tata2\resources\views/dashboard.blade.php ENDPATH**/ ?>