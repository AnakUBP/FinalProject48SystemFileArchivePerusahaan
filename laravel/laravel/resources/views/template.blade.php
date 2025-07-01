<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Template</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    {{-- FIX: Menyesuaikan path file CSS dan JS agar sesuai dengan standar Vite --}}
    @vite(['resources/css/dashboard.css', 'resources/js/dashboard.js', 'resources/css/template.css', 'resources/js/template.js'])
</head>
<script>
    window.templateRoutes = {
        store: "{{ route('templates.store') }}"
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
                    <li><a href="{{ route('dashboard') }}"><i class="fas fa-tachometer-alt"></i>Dashboard</a></li>
                    <li class="active"><a href="{{ route('template') }}"><i class="fas fa-file-word"></i> Template</a>
                    </li>
                    <li><a href="{{ route('jeniscuti.index') }}"><i class="fas fa-calendar-alt"></i>Jenis Cuti</a></li>
                    <li><a href="{{ route('user-profiles.index') }}"><i class="fas fa-users-cog"></i> User & Profil</a>
                    </li>
                    <li><a href="{{ route('manajemen-cuti') }}"><i class="fas fa-envelope-open-text"></i>Kelola Cuti</a>
                    </li>
                    <li><a href="{{ route('laporan') }}"><i class="fas fa-chart-bar"></i> Laporan</a></li>
                    <li><a href="{{ route('riwayat') }}"><i class="fas fa-history"></i> Riwayat Surat</a></li>
                    <li><a href="{{ route('log.aktivitas') }}"><i class="fas fa-history"></i> Log Aktivitas</a></li>
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
                            <img src="{{ auth()->user()->profile->foto_profil ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) }}"
                                alt="Profile">
                            <span>{{ auth()->user()->name }}</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="dropdown-content">
                            <a href="{{ route('profile.show') }}"><i class="fas fa-user"></i> Profile</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"><i class="fas fa-sign-out-alt"></i> Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>
            <script>
                window.alertStatus = {
                    success: @json(session('success')),
                    error: @json(session('error'))
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
                        <form action="{{ route('templates.index') }}" method="GET" class="filter-form">

                            <div class="form-group">
                                <input type="text" name="search" class="form-control"
                                    placeholder="Cari nama template..." value="{{ $filters['search'] ?? '' }}">
                            </div>

                            {{-- FIX: Dropdown untuk filter kategori --}}
                            <div class="form-group">
                                <select name="kategori" class="form-control">
                                    <option value="">Semua Kategori</option>
                                    @foreach($kategoriList as $kategori)
                                        <option value="{{ $kategori }}" {{ ($filters['kategori'] ?? '') == $kategori ? 'selected' : '' }}>
                                            {{ $kategori }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <select name="status" class="form-control">
                                    <option value="all" {{ ($filters['status'] ?? 'all') == 'all' ? 'selected' : '' }}>
                                        Semua Status</option>
                                    <option value="1" {{ ($filters['status'] ?? '') == '1' ? 'selected' : '' }}>Aktif
                                    </option>
                                    <option value="0" {{ ($filters['status'] ?? '') == '0' ? 'selected' : '' }}>Nonaktif
                                    </option>
                                </select>
                            </div>

                            <div class="form-group">
                                <select name="sort" class="form-control">
                                    <option value="terbaru" {{ ($filters['sort'] ?? 'terbaru') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                                    <option value="terlama" {{ ($filters['sort'] ?? '') == 'terlama' ? 'selected' : '' }}>
                                        Terlama</option>
                                    <option value="nama_asc" {{ ($filters['sort'] ?? '') == 'nama_asc' ? 'selected' : '' }}>Nama A-Z</option>
                                    <option value="nama_desc" {{ ($filters['sort'] ?? '') == 'nama_desc' ? 'selected' : '' }}>Nama Z-A</option>
                                </select>
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i></button>
                                <a href="{{ route('templates.index') }}" class="btn btn-secondary"><i
                                        class="fas fa-sync-alt"></i></a>
                            </div>
                        </form>
                    </div>
                    <table class="template-table">
                        <thead>
                            <tr>
                                <th>Nama Template</th>
                                <th>Kategori</th> {{-- FIX: Tambah header kolom --}}
                                <th>Versi</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($templates as $template)
                                <tr>
                                    <td>{{ $template->nama_template }}</td>
                                    <td>{{ $template->kategori ?? '-' }}</td> {{-- FIX: Tampilkan data kategori --}}
                                    <td>{{ $template->versi }}</td>
                                    <td>
                                        <span
                                            class="template-status {{ $template->active ? 'status-active' : 'status-inactive' }}">
                                            {{ $template->active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                    <td class="template-actions-cell">
                                        <a href="{{ asset('storage/' . $template->file_path) }}" download
                                            class="btn btn-secondary">
                                            <i class="fas fa-download"></i>
                                        </a>
                                        <button class="btn btn-primary"
                                            onclick="edittemplate({{ json_encode($template) }})">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('templates.destroy', $template->id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-secondary"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus template ini?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
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
                                @csrf
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
</body>
