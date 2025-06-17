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
                    <li>
                        <a href="{{ route('dashboard') }}">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('template') }}">
                            <i class="fas fa-file-word"></i> Template
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('jeniscuti.index') }}">
                            <i class="fas fa-calendar-alt"></i> Jenis Cuti
                        </a>
                    </li>
                    {{-- FIX: Menggunakan satu link yang benar untuk User Management --}}
                    <li class="active">
                        <a href="{{ route('user-profiles.index') }}">
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
                            {{-- FIX: Menggunakan kolom 'foto' dan URL yang benar --}}
                            <img src="{{ Auth::user()->profile?->foto ? Storage::url(Auth::user()->profile->foto) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) }}"
                                alt="Profile">
                            <span>{{ auth()->user()->name }}</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="dropdown-content">
                            {{-- FIX: Link 'Profile' sekarang membuka modal edit untuk user yang login --}}
                            <a href="#" onclick="editModal({{ Auth::id() }})"><i class="fas fa-user-edit"></i> Edit
                                Profil Saya</a>
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
                    {{-- FIX: Menghapus div header yang berulang/duplikat --}}
                    <div class="user-profiles-header">
                        <button class="btn btn-primary" onclick="openup()">
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
                                    <td>{{ $user->created_at->format('d M Y') }}</td>
                                    <td class="user-profiles-actions-cell">
                                        <button class="action-btn edit-btn" onclick="editup({{ $user->id }})"><i
                                                class="fas fa-edit"></i></button>
                                        <form action="{{ route('user-profiles.destroy', $user->id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="action-btn view-btn"
                                                style="background-color: var(--accent-color);"
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
                        <div class="form-group form-check">
                            <input type="checkbox" id="aktif" name="aktif" class="form-check-input">
                            <label for="aktif" class="form-check-label">Akun Aktif</label>
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
                            <label for="nama_lengkap">Nama Lengkap</label>
                            <input type="text" id="nama_lengkap" name="nama_lengkap" class="form-control">
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