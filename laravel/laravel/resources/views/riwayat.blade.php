<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Surat</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        @vite(['resources/css/dashboard.css', 'resources/css/riwayat-surat.css','resources/js/riwayat-surat.js'])
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <img src="/img/logo.svg" alt="Logo Perusahaan" class="logo-perusahaan" />
                <p>{{ ucfirst(auth()->user()->role) }}</p>
            </div>
            <nav class="sidebar-nav">
                <ul>
                    <li><a href="{{ route('dashboard') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                    <li><a href="{{ route('template') }}"><i class="fas fa-file-word"></i> Template</a></li>
                    <li><a href="{{ route('jeniscuti.index') }}"><i class="fas fa-calendar-alt"></i> Jenis Cuti</a></li>
                    <li><a href="{{ route('user-profiles.index') }}"><i class="fas fa-users-cog"></i> User & Profil</a></li>
                    <li><a href="{{ route('manajemen-cuti') }}"><i class="fas fa-envelope-open-text"></i> Kelola Cuti</a></li>
                    <li><a href="{{ route('laporan') }}"><i class="fas fa-chart-bar"></i> Laporan</a></li>
                    <li class="active"><a href="{{ route('riwayat') }}"><i class="fas fa-history"></i> Riwayat Surat</a></li>
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
                            <img src="{{ auth()->user()->profile?->foto ? Storage::url(auth()->user()->profile->foto) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) }}" alt="Profile">
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
                <h1 class="page-title">Riwayat Pengajuan Surat</h1>

                <div class="riwayat-container">
                    <table class="riwayat-table">
                        <thead>
                            <tr>
                                @can('admin')
                                <th>Nama Karyawan</th>
                                @endcan
                                <th>Jenis Surat</th>
                                <th>Tanggal Pengajuan</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($riwayatSurat as $pengajuan)
                                <tr>
                                    @can('admin')
                                    <td>{{ $pengajuan->user->name }}</td>
                                    @endcan
                                    <td>{{ $pengajuan->jenisCuti->nama ?? 'N/A' }}</td>
                                    <td>{{ $pengajuan->created_at->format('d M Y, H:i') }}</td>
                                    <td>
                                        <span class="status-badge status-{{ $pengajuan->suratCutiResmi->status ?? 'diajukan' }}">
                                            {{ ucfirst($pengajuan->suratCutiResmi->status ?? 'Diajukan') }}
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-info" onclick="openDetailModal({{ $pengajuan->id }})">Detail</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada riwayat surat.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{-- Tampilkan link pagination --}}
                    <div class="pagination-container">
                        {{ $riwayatSurat->links() }}
                    </div>
                </div>
            </div>
        </main>
    </div>
    <div id="detailRiwayatModal" class="modal">
        <div class="modal-content wide">
            <div class="modal-header">
                <h3 class="modal-title">Detail Riwayat Pengajuan</h3>
                <button class="close-btn" onclick="closeModal('detailRiwayatModal')">&times;</button>
            </div>
            <div class="modal-body">
                <div id="detailModalBody">
                    {{-- Konten detail akan dimuat di sini oleh JavaScript --}}
                    <p>Memuat detail...</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
