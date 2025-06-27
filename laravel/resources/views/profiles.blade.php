<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    {{-- FIX: Menyesuaikan path file CSS dan JS agar sesuai dengan standar Vite --}}
    @vite(['resources/css/dashboard.css', 'resources/js/dashboard.js', 'resources/css/profile-page.css'])
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
                    <li><a href="{{ route('dashboard') }}"><i class="fas fa-tachometer-alt"></i>
                            Dashboard</a></li>
                    <li><a href="{{ route('template') }}"><i class="fas fa-file-word"></i> Template</a></li>
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
            <div class="content">
                <h1 class="page-title">Profil Saya</h1>

                <div class="profile-view-card">
                    <!-- Bagian Header Profil -->
                    <div class="profile-header">
                        <img src="{{ $user->profile?->foto ? Storage::url($user->profile->foto) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) }}" alt="Foto Profil" class="profile-avatar">
                        <div class="profile-name-role">
                            <h2>{{ $user->profile?->nama_lengkap ?? $user->name }}</h2>
                            <p>{{ $user->profile?->jabatan ?? 'Jabatan belum diatur' }}</p>
                            <span class="badge badge-{{ $user->role }}">{{ ucfirst($user->role) }}</span>
                        </div>
                    </div>

                    <hr class="profile-divider">

                    <!-- Bagian Detail Informasi -->
                    <div class="profile-details-grid">
                        <div class="detail-item">
                            <span class="detail-label">Email</span>
                            <span class="detail-value">{{ $user->email }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Telepon</span>
                            <span class="detail-value">{{ $user->profile?->telepon ?? '-' }}</span>
                        </div>
                        <div class="detail-item full-width">
                            <span class="detail-label">Alamat</span>
                            <span class="detail-value">{{ $user->profile?->alamat ?? '-' }}</span>
                        </div>

                        {{-- Tampilkan bagian ini hanya jika user adalah karyawan --}}
                        @if($user->role === 'karyawan')
                        <div class="detail-item">
                            <span class="detail-label">Sisa Kuota Cuti</span>
                            <span class="detail-value quota">{{ $user->profile?->sisa_kuota_cuti ?? 'N/A' }} Hari</span>
                        </div>
                        <div class="detail-item signature-item">
                            <span class="detail-label">Tanda Tangan Digital</span>
                            @if ($user->profile?->tanda_tangan)
                                <img src="{{ Storage::url($user->profile->tanda_tangan) }}" alt="Tanda Tangan" class="signature-preview">
                            @else
                                <span class="detail-value-muted">Belum di-upload</span>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>

</html>