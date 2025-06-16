<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen User & Profil</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/dashboard.css', 'resources/css/user-profiles.css', 'resources/js/user-profiles.js']); ?>
</head>
<script>
    // Kirim data yang dibutuhkan ke JavaScript
    window.routes = {
        store: "<?php echo e(route('user-profiles.store')); ?>",
        update_base: "<?php echo e(url('user-profiles')); ?>"
    };
    window.usersWithProfile = <?php echo json_encode($users->keyBy('id'), 15, 512) ?>;
    window.asset_base = "<?php echo e(Storage::url('')); ?>";
</script>

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
                    <li>
                        <a href="<?php echo e(route('dashboard')); ?>">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo e(route('template')); ?>">
                            <i class="fas fa-file-word"></i> Template
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo e(route('jeniscuti.index')); ?>">
                            <i class="fas fa-calendar-alt"></i> Jenis Cuti
                        </a>
                    </li>
                    
                    <li class="active">
                        <a href="<?php echo e(route('user-profiles.index')); ?>">
                            <i class="fas fa-users-cog"></i> User & Profil
                        </a>
                    </li>
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
                            
                            <img src="<?php echo e(Auth::user()->profile?->foto ? Storage::url(Auth::user()->profile->foto) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name)); ?>"
                                alt="Profile">
                            <span><?php echo e(auth()->user()->name); ?></span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="dropdown-content">
                            
                            <a href="#" onclick="editModal(<?php echo e(Auth::id()); ?>)"><i class="fas fa-user-edit"></i> Edit
                                Profil Saya</a>
                            <form method="POST" action="<?php echo e(route('logout')); ?>">
                                <?php echo csrf_field(); ?>
                                <button type="submit"><i class="fas fa-sign-out-alt"></i> Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>
            <div class="content">
                <div class="page-title">
                    <h1>Manajemen User & Profil</h1>
                </div>

                
                <?php if(session('success')): ?>
                    <div class="alert alert-success"
                        style="background-color: #d1f7e8; color: #0d694b; padding: 1rem; border-radius: 5px; margin-bottom: 1rem;">
                        <?php echo e(session('success')); ?>

                    </div>
                <?php endif; ?>
                <?php if(session('error')): ?>
                    <div class="alert alert-danger"
                        style="background-color: #fdeaea; color: #c73434; padding: 1rem; border-radius: 5px; margin-bottom: 1rem;">
                        <strong>Error:</strong> <?php echo e(session('error')); ?>

                    </div>
                <?php endif; ?>

                <div class="user-profiles-container">
                    
                    <div class="user-profiles-header">
                        <button class="btn btn-primary" onclick="openModal()">
                            <i class="fas fa-plus"></i> Tambah User</button>
                    </div>

                    <table class="user-profiles-table">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Jabatan</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Tanggal Dibuat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td>
                                        <div class="user-info">
                                            
                                            <img src="<?php echo e($user->profile?->foto ? Storage::url($user->profile->foto) : 'https://ui-avatars.com/api/?background=random&name=' . urlencode($user->name)); ?>"
                                                alt="Foto Profil">
                                            <div>
                                                <strong><?php echo e($user->profile?->nama_lengkap ?? $user->name); ?></strong>
                                                <small><?php echo e($user->email); ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?php echo e($user->profile?->jabatan ?? '-'); ?></td>
                                    <td><span class="badge badge-<?php echo e($user->role); ?>"><?php echo e(ucfirst($user->role)); ?></span></td>
                                    <td>
                                        <?php if(!$user->aktif): ?>
                                            <span class="status-badge status-disabled">Nonaktif</span>
                                        <?php elseif($user->isOnline()): ?>
                                            <span class="status-badge status-online">Online</span>
                                        <?php else: ?>
                                            <span class="status-badge status-offline">Offline</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e($user->created_at->format('d M Y')); ?></td>
                                    <td class="user-profiles-actions-cell">
                                        <button class="action-btn edit-btn" onclick="editModal(<?php echo e($user->id); ?>)"><i
                                                class="fas fa-edit"></i></button>
                                        <form action="<?php echo e(route('user-profiles.destroy', $user->id)); ?>" method="POST"
                                            style="display:inline;">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="action-btn view-btn"
                                                style="background-color: var(--accent-color);"
                                                onclick="return confirm('Anda yakin ingin menghapus user ini?')"><i
                                                    class="fas fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                
                                <tr>
                                    <td colspan="6" style="text-align: center;">Tidak ada data user.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    
    <div id="userProfileModal" class="modal-user-profile">
        
    </div>
</body>

</html><?php /**PATH C:\laragon\www\Tata2\resources\views/UsersProfiles.blade.php ENDPATH**/ ?>