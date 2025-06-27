<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen User & Profil</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    {{-- FIX: Menyesuaikan path file CSS dan JS agar sesuai dengan standar Vite --}}
    @vite(['resources/css/dashboard.css', 'resources/js/dashboard.js', 'resources/css/userprofiles.css', 'resources/js/userprofiles.js'])
</head>
<script>
    // Kirim data yang dibutuhkan ke JavaScript
    window.routes = {
        store: "{{ route('user-profiles.store') }}",
        update_base: "{{ url('user-profiles') }}"
    };
    window.usersWithProfile = @json($users->keyBy('id'));
    window.asset_base = "{{ Storage::url('') }}";
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
                    <li><a href="{{ route('template') }}"><i class="fas fa-file-word"></i> Template</a></li>
                    <li><a href="{{ route('jeniscuti.index') }}"><i class="fas fa-calendar-alt"></i>Jenis Cuti</a></li>
                    <li class="active"><a href="{{ route('user-profiles.index') }}"><i class="fas fa-users-cog"></i>
                            User & Profil</a></li>
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
                            {{-- FIX: Menggunakan kolom 'foto' dan URL yang benar --}}
                            <img src="{{ Auth::user()->profile?->foto ? Storage::url(Auth::user()->profile->foto) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) }}"
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
                    <h1>Manajemen User & Profil</h1>
                </div>
                <div class="user-profiles-container">
                    <div class="user-profiles-header ">
                        <button class="btn btn-primary" onclick="openup()">
                            <i class="fas fa-plus"></i> Tambah User</button>
                    </div>
                    <div class="filter-header">
                        <form action="{{ route('user-profiles.index') }}" method="GET" class="filter-form">

                            <div class="form-group">
                                <input type="text" name="search" class="form-control" placeholder="Cari nama, email..."
                                    value="{{ $filters['search'] ?? '' }}">
                            </div>

                            <div class="form-group">
                                <select name="role" class="form-control">
                                    <option value="">Semua Role</option>
                                    <option value="admin" {{ ($filters['role'] ?? '') == 'admin' ? 'selected' : '' }}>
                                        Admin</option>
                                    <option value="karyawan" {{ ($filters['role'] ?? '') == 'karyawan' ? 'selected' : '' }}>Karyawan</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <select name="online_status" class="form-control">
                                    <option value="">Semua Status</option>
                                    <option value="online" {{ ($filters['online_status'] ?? '') == 'online' ? 'selected' : '' }}>Online</option>
                                    <option value="offline" {{ ($filters['online_status'] ?? '') == 'offline' ? 'selected' : '' }}>Offline</option>
                                    <option value="nonaktif" {{ ($filters['online_status'] ?? '') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
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
                                <a href="{{ route('user-profiles.index') }}" class="btn btn-secondary"><i
                                        class="fas fa-sync-alt"></i></a>
                            </div>
                        </form>
                    </div>

                    <table class="user-profiles-table">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Jabatan</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Sisa Cuti</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                <tr>
                                    <td>
                                        <div class="user-info">
                                            {{-- FIX: Menggunakan cara yang benar untuk menampilkan gambar dari storage --}}
                                            <img src="{{ $user->profile?->foto ? Storage::url($user->profile->foto) : 'https://ui-avatars.com/api/?background=random&name=' . urlencode($user->name) }}"
                                                alt="Foto Profil">
                                            <div>
                                                <strong>{{ $user->profile?->nama_lengkap ?? $user->name }}</strong>
                                                <small>{{ $user->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $user->profile?->jabatan ?? '-' }}</td>
                                    <td><span class="badge badge-{{$user->role}}">{{ ucfirst($user->role) }}</span></td>
                                    <td>
                                        @if(!$user->active)
                                            <span class="status-badge status-disabled">Nonaktif</span>
                                        @elseif($user->isOnline())
                                            <span class="status-badge status-online">Online</span>
                                        @else
                                            <span class="status-badge status-offline">Offline</span>
                                        @endif
                                    </td>
                                    <td>{{ $user->profile->sisa_kuota_cuti ?? 'N/A' }}</td>
                                    <td class="user-profiles-actions-cell">
                                        <button class="btn btn-primary" onclick="editup({{ $user->id }})"><i
                                                class="fas fa-edit"></i></button>
                                        <form action="{{ route('user-profiles.destroy', $user->id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-secondary"
                                                onclick="return confirm('Anda yakin ingin menghapus user ini?')"><i
                                                    class="fas fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                {{-- FIX: Menyesuaikan colspan menjadi 6 --}}
                                <tr>
                                    <td colspan="6" style="text-align: center;">Tidak ada data user.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
    <div id="userProfileModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalTitle">Tambah User Baru</h3>
                <button class="close-btn" onclick="closeModal()">&times;</button>
            </div>
            <form id="userProfileForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div id="formMethod"></div>

                <div class="modal-body">
                    <div class="form-section">
                        <h4>Informasi Akun</h4>
                        <div class="form-group">
                            <label for="name">Nama Panggilan</label>
                            <input type="text" id="name" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" class="form-control">
                            <small id="passwordHelp">Kosongkan jika tidak ingin mengubah.</small>
                        </div>
                        <div class="form-group">
                            <label for="role">Role</label>
                            <select id="role" name="role" class="form-control" required>
                                <option value="karyawan">Karyawan</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Status Akun</label>
                            <label class="toggle-switch">
                                <input type="checkbox" id="active" name="active">
                                <span class="slider"></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-section">
                        <h4>Informasi Profil</h4>
                        <div class="form-group">
                            <label>Foto Profil</label>
                            <img id="imagePreview" src="https://ui-avatars.com/api/?name=?" alt="Preview Foto"
                                class="preview-img">
                            <input type="file" name="foto" id="foto" class="form-control-file"
                                onchange="previewImage(event);">
                        </div>
                        <div class="form-group">
                            <label for="kuota_tambahan">Tambah Kuota (Opsional)</label>
                            <p style="margin-top: 5px; margin-bottom: 10px;">Sisa Kuota Saat Ini: <strong id="current_quota_display">0</strong></p>
                            <input type="number" id="kuota_tambahan" name="kuota_tambahan" class="form-control" placeholder="Contoh: 1 (untuk menambah) atau -1 (untuk mengurangi)">
                        </div>
                        <div class="form-group">
                            <label for="nama_lengkap">Nama Lengkap</label>
                            <input type="text" id="nama_lengkap" name="nama_lengkap" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="alamat">Alamat</label>
                            <input type="text" id="alamat" name="alamat" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="jabatan">Jabatan</label>
                            <input type="text" id="jabatan" name="jabatan" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="jenis_kelamin">Jenis Kelamin</label>
                            <select id="jenis_kelamin" name="jenis_kelamin" class="form-control" required>
                                <option value="pria">Pria</option>
                                <option value="wanita">Wanita</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="telepon">Telepon</label>
                            <input type="text" id="telepon" name="telepon" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeModal()">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

</body>

</html>