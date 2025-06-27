<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/dashboard.css', 'resources/js/dashboard.js', 'resources/css/dashboardcontent.css', 'resources/js/dashboardcalender.js']); ?>
</head>

<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <img src="/img/logo.svg" alt="Logo Perusahaan" class="logo-perusahaan" />
                <p>Admin</p>
            </div>
            <nav class="sidebar-nav">
                <ul>
                    <li class="active"><a href="<?php echo e(route('dashboard')); ?>"><i class="fas fa-tachometer-alt"></i>
                            Dashboard</a></li>
                    <li><a href="<?php echo e(route('template')); ?>"><i class="fas fa-file-word"></i> Template</a></li>
                    <li><a href="<?php echo e(route('jeniscuti.index')); ?>"><i class="fas fa-calendar-alt"></i>Jenis Cuti</a></li>
                    <li><a href="<?php echo e(route('user-profiles.index')); ?>"><i class="fas fa-users-cog"></i> User & Profil</a>
                    </li>
                    <li><a href="<?php echo e(route('manajemen-cuti')); ?>"><i class="fas fa-envelope-open-text"></i>Kelola Cuti</a>
                    </li>
                    <li><a href="<?php echo e(route('laporan')); ?>"><i class="fas fa-chart-bar"></i> Laporan</a></li>
                    <li><a href="<?php echo e(route('riwayat')); ?>"><i class="fas fa-history"></i> Riwayat Surat</a></li>
                    <li><a href="<?php echo e(route('log.aktivitas')); ?>"><i class="fas fa-history"></i> Log Aktivitas</a></li>
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
                <div class="stats-container">
                    <div class="stat-card primary">
                        <h2><?php echo e($suratMasuk); ?></h2>
                        <p>Surat Masuk</p>
                        <a href="<?php echo e(route('manajemen-cuti')); ?>">selengkapnya</a>
                    </div>
                    <div class="stat-card secondary">
                        <h2><?php echo e($suratKeluar); ?></h2>
                        <p>Surat Keluar</p>
                        <a href="<?php echo e(route('riwayat')); ?>">selengkapnya</a>
                    </div>
                </div>
                <div class="calendars-wrapper" id="calendars-container" data-leave-details="<?php echo e(json_encode($detailCutiPerTanggal)); ?>">
                    <div id="calendar-current-month" class="calendar-container"></div>
                    <div id="calendar-next-month" class="calendar-container"></div>
                </div>
            </div>
        </main>
    </div>
</body>

</html><?php /**PATH C:\laragon\www\Tata2\resources\views/dashboard.blade.php ENDPATH**/ ?>