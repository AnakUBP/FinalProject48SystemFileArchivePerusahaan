<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Template - Sistem Karyawan</title>
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
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
                <img src="img/logo.svg" alt="Logo Perusahaan" class="logo-perusahaan" />
                <p>Admin</p>
            </div>
            <nav class="sidebar-nav">
                <ul>
                    <li>
                        <a href="<?php echo e(route('dashboard')); ?>">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    <li class="active">
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
                <div class="page-title">
                    <h1>Manajemen Template Surat</h1>
                </div>
                <div class="template-container">
                    <div class="template-header">
                        <div class="template-actions">
                            <button class="btn btn-primary" onclick="openModal()">
                                <i class="fas fa-plus"></i> Tambah Template
                            </button>
                        </div>
                    </div>
                    <script>
                        window.alertStatus = {
                            success: <?php echo json_encode(session('success'), 15, 512) ?>,
                            error: <?php echo json_encode(session('error'), 15, 512) ?>
                        };
                    </script>
                    <table class="template-table">
                        <thead>
                            <tr>
                                <th>Nama Template</th>
                                <th>Versi</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $templates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $template): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($template->nama_template); ?></td>
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
                                            onclick="editTemplate(<?php echo e(json_encode($template)); ?>)">
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
                </div>

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
                                <input type="text" id="versi" name="versi" class="form-control" required value="1.0.0">
                            </div>

                            <div class="form-group">
                                <label for="template_file">File Template (.docx)</label>
                                <input type="file" id="template_file" name="template_file" class="form-control"
                                    accept=".docx">
                                <small id="fileHelp" class="text-muted">Hanya file .docx yang diterima</small>
                            </div>

                            <div class="form-group">
                                <label>
                                    <input type="checkbox" id="active" name="active" checked> Aktif
                                </label>
                            </div>

                            <div class="form-actions">
                                <button type="button" class="btn btn-secondary" onclick="closeModal()">Batal</button>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body><?php /**PATH C:\laragon\www\Tata2\resources\views/template.blade.php ENDPATH**/ ?>