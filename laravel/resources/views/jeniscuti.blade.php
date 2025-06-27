<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Jenis Cuti</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    {{-- FIX: Menyesuaikan path file CSS dan JS agar sesuai dengan standar Vite --}}
    @vite(['resources/css/dashboard.css', 'resources/js/dashboard.js', 'resources/css/jeniscuti.css', 'resources/js/jeniscuti.js'])
</head>
<script>
    window.jeniscutiRoutes = {
        store: "{{ route('jeniscuti.store') }}"
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
                    <li><a href="{{ route('dashboard') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                    <li><a href="{{ route('template') }}"><i class="fas fa-file-word"></i> Template</a></li>
                    <li class="active"><a href="{{ route('jeniscuti.index') }}"><i class="fas fa-calendar-alt"></i>
                            Jenis Cuti</a></li>
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
            <div class="content">
                <div class="page-title">
                    <h1>Manajemen Jenis Cuti</h1>
                </div>
                <div class="jenis-cuti-container">
                    <div class="jenis-cuti-header">
                        <div class="jenis-cuti-actions">
                            <button class="btn btn-primary" onclick="openJenisCutiModal()">
                                <i class="fas fa-plus"></i> Tambah Jenis Cuti
                            </button>
                        </div>
                    </div>

                    <script>
                        window.alertStatus = {
                            success: @json(session('success')),
                            error: @json(session('error'))
                        };
                    </script>

                    <table class="jenis-cuti-table">
                        <thead>
                            <tr>
                                <th>Nama Jenis Cuti</th>
                                <th>Template</th>
                                <th>Keterangan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($jenisCuti as $jenisCuti)
                                <tr>
                                    <td>{{ $jenisCuti->nama }}</td>
                                    <td>
                                        {{-- Cek apakah jenis cuti ini MEMILIKI template --}}
                                        @if($jenisCuti->templates)
                                            {{-- Jika ada, tampilkan nama dan versinya --}}
                                            {{ $jenisCuti->templates->nama_template }} (v{{ $jenisCuti->templates->versi }})
                                        @else
                                            {{-- Jika tidak ada, tampilkan strip atau pesan lain --}}
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $jenisCuti->keterangan ?? '-' }}</td>
                                    <td class="jenis-cuti-actions-cell">
                                        <button class="btn btn-primary"
                                            onclick="editJenisCuti({{ json_encode($jenisCuti) }})">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('jeniscuti.destroy', $jenisCuti->id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus jenis cuti ini?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Modal Tambah/Edit Jenis Cuti -->
                <div id="jenisCutiModal" class="modal-jenis-cuti">
                    <div class="modal-jenis-cuti-content">
                        <div class="modal-header">
                            <h3 class="modal-title" id="jenisCutiModalTitle">Tambah Jenis Cuti Baru</h3>
                            <button class="close-btn" onclick="closeJenisCutiModal()">&times;</button>
                        </div>
                        <form id="jenisCutiForm" method="POST">
                            @csrf
                            <div id="jenisCutiFormMethod" style="display:none;"></div>

                            <div class="form-group">
                                <label for="nama">Nama Jenis Cuti</label>
                                <input type="text" id="nama" name="nama" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="template_id">Template Surat</label>
                                <select id="template_id" name="template_id" class="select-template">
                                    <option value="">Pilih Template...</option>
                                    @foreach($templates as $template)
                                        <option value="{{ $template->id }}">{{ $template->nama_template }}
                                            (v{{ $template->versi }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="keterangan">Keterangan</label>
                                <textarea id="keterangan" name="keterangan" class="form-control" rows="3"></textarea>
                            </div>

                            <div class="form-actions">
                                <button type="button" class="btn btn-secondary"
                                    onclick="closeJenisCutiModal()">Batal</button>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>