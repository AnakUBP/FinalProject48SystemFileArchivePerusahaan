<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Template</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/dashboard.css', 'resources/js/dashboard.js', 'resources/css/template.css', 'resources/js/template.js']); ?>
</head>
<script>
    window.templateRoutes = {
        store: "<?php echo e(route('templates.store')); ?>"
    };
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
                    <li><a href="<?php echo e(route('dashboard')); ?>"><i class="fas fa-tachometer-alt"></i>Dashboard</a></li>
                    <li class="active"><a href="<?php echo e(route('template')); ?>"><i class="fas fa-file-word"></i> Template</a>
                    </li>
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
            <script>
                window.alertStatus = {
                    success: <?php echo json_encode(session('success'), 15, 512) ?>,
                    error: <?php echo json_encode(session('error'), 15, 512) ?>
                };
            </script>
            <div class="content">
                <div class="page-title">
                    <h1>Manajemen Template Surat</h1>
                </div>
                <div class="template-container">
                    <div class="template-header">
                        <button class="btn btn-primary" onclick="opentemplate()">
                            <i class="fas fa-plus"></i> Tambah Template</button>
                    </div>
                    <div class="filter-header">
                        <form action="<?php echo e(route('templates.index')); ?>" method="GET" class="filter-form">

                            <div class="form-group">
                                <input type="text" name="search" class="form-control"
                                    placeholder="Cari nama template..." value="<?php echo e($filters['search'] ?? ''); ?>">
                            </div>

                            
                            <div class="form-group">
                                <select name="kategori" class="form-control">
                                    <option value="">Semua Kategori</option>
                                    <?php $__currentLoopData = $kategoriList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kategori): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($kategori); ?>" <?php echo e(($filters['kategori'] ?? '') == $kategori ? 'selected' : ''); ?>>
                                            <?php echo e($kategori); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <select name="status" class="form-control">
                                    <option value="all" <?php echo e(($filters['status'] ?? 'all') == 'all' ? 'selected' : ''); ?>>
                                        Semua Status</option>
                                    <option value="1" <?php echo e(($filters['status'] ?? '') == '1' ? 'selected' : ''); ?>>Aktif
                                    </option>
                                    <option value="0" <?php echo e(($filters['status'] ?? '') == '0' ? 'selected' : ''); ?>>Nonaktif
                                    </option>
                                </select>
                            </div>

                            <div class="form-group">
                                <select name="sort" class="form-control">
                                    <option value="terbaru" <?php echo e(($filters['sort'] ?? 'terbaru') == 'terbaru' ? 'selected' : ''); ?>>Terbaru</option>
                                    <option value="terlama" <?php echo e(($filters['sort'] ?? '') == 'terlama' ? 'selected' : ''); ?>>
                                        Terlama</option>
                                    <option value="nama_asc" <?php echo e(($filters['sort'] ?? '') == 'nama_asc' ? 'selected' : ''); ?>>Nama A-Z</option>
                                    <option value="nama_desc" <?php echo e(($filters['sort'] ?? '') == 'nama_desc' ? 'selected' : ''); ?>>Nama Z-A</option>
                                </select>
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i></button>
                                <a href="<?php echo e(route('templates.index')); ?>" class="btn btn-secondary"><i
                                        class="fas fa-sync-alt"></i></a>
                            </div>
                        </form>
                    </div>
                    <table class="template-table">
                        <thead>
                            <tr>
                                <th>Nama Template</th>
                                <th>Kategori</th> 
                                <th>Versi</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $templates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $template): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($template->nama_template); ?></td>
                                    <td><?php echo e($template->kategori ?? '-'); ?></td> 
                                    <td><?php echo e($template->versi); ?></td>
                                    <td>
                                        <span
                                            class="template-status <?php echo e($template->active ? 'status-active' : 'status-inactive'); ?>">
                                            <?php echo e($template->active ? 'Aktif' : 'Nonaktif'); ?>

                                        </span>
                                    </td>
                                    <td class="template-actions-cell">
                                        <a href="<?php echo e(Storage::url('public/' . $template->file_path)); ?>" download
                                            class="btn btn-secondary">
                                            <i class="fas fa-download"></i>
                                        </a>
                                        <button class="btn btn-primary"
                                            onclick="edittemplate(<?php echo e(json_encode($template)); ?>)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="<?php echo e(route('templates.destroy', $template->id)); ?>" method="POST"
                                            style="display:inline;">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-secondary"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus template ini?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>

                    <!-- Modal Tambah/Edit Template -->
                    <div id="templateModal" class="modal">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3 class="modal-title" id="modalTitle">Tambah Template Baru</h3>
                                <button class="close-btn" onclick="closeModal()">&times;</button>
                            </div>
                            <form id="templateForm" method="POST" enctype="multipart/form-data">
                                <?php echo csrf_field(); ?>
                                <div id="formMethod" style="display:none;"></div>

                                <div class="form-group">
                                    <label for="nama_template">Nama Template</label>
                                    <input type="text" id="nama_template" name="nama_template" class="form-control"
                                        required>
                                </div>

                                <div class="form-group">
                                    <label for="versi">Versi</label>
                                    <input type="text" id="versi" name="versi" class="form-control" required
                                        value="1.0.0">
                                </div>
                                <div class="form-group">
                                    <label for="kategori">Kategori</label>
                                    <input type="text" id="kategori" name="kategori" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="template_file">File Template (.docx)</label>
                                    <input type="file" id="template_file" name="template_file" class="form-control"
                                        accept=".docx">
                                    <small id="fileHelp" class="text-muted">Hanya file .docx yang
                                        diterima</small>
                                </div>

                                <div class="form-group">
                                    <label>Status Template</label>
                                    <label class="toggle-switch">
                                        <input type="checkbox" id="active" name="active">
                                        <span class="slider"></span>
                                    </label>
                                </div>

                                <div class="form-actions">
                                    <button type="button" class="btn btn-secondary"
                                        onclick="closeModal()">Batal</button>
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
        </main>
    </div>
</body><?php /**PATH C:\laragon\www\Tata2\resources\views/template.blade.php ENDPATH**/ ?>