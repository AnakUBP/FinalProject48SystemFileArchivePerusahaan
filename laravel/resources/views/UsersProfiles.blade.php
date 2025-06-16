<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen User & Profil</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    {{-- FIX: Menyesuaikan path file CSS dan JS agar sesuai dengan standar Vite --}}
    @vite(['resources/css/dashboard.css', 'resources/css/user-profiles.css', 'resources/js/user-profiles.js'])
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
            <div class="content">
                <div class="page-title">
                    <h1>Manajemen User & Profil</h1>
                </div>

                {{-- FIX: Menambahkan blok untuk menampilkan pesan sukses atau error --}}
                @if (session('success'))
                    <div class="alert alert-success"
                        style="background-color: #d1f7e8; color: #0d694b; padding: 1rem; border-radius: 5px; margin-bottom: 1rem;">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger"
                        style="background-color: #fdeaea; color: #c73434; padding: 1rem; border-radius: 5px; margin-bottom: 1rem;">
                        <strong>Error:</strong> {{ session('error') }}
                    </div>
                @endif

                <div class="user-profiles-container">
                    {{-- FIX: Menghapus div header yang berulang/duplikat --}}
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
                                        @if(!$user->aktif)
                                            <span class="status-badge status-disabled">Nonaktif</span>
                                        @elseif($user->isOnline())
                                            <span class="status-badge status-online">Online</span>
                                        @else
                                            <span class="status-badge status-offline">Offline</span>
                                        @endif
                                    </td>
                                    <td>{{ $user->created_at->format('d M Y') }}</td>
                                    <td class="user-profiles-actions-cell">
                                        <button class="action-btn edit-btn" onclick="editModal({{ $user->id }})"><i
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

    {{-- Modal --}}
    <div id="userProfileModal" class="modal-user-profile">
        {{-- ... Isi Modal Anda ... --}}
    </div>
</body>

</html>