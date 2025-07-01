<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Sistem</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/dashboard.css', 'resources/css/laporan.css', 'resources/js/laporan.js'])
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
                    <li><a href="{{ route('dashboard') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                    <li><a href="{{ route('template') }}"><i class="fas fa-file-word"></i> Template</a></li>
                    <li><a href="{{ route('jeniscuti.index') }}"><i class="fas fa-calendar-alt"></i> Jenis Cuti</a></li>
                    <li><a href="{{ route('user-profiles.index') }}"><i class="fas fa-users-cog"></i> User & Profil</a></li>
                    <li><a href="{{ route('manajemen-cuti') }}"><i class="fas fa-envelope-open-text"></i> Kelola Cuti</a></li>
                    <li class="active"><a href="{{ route('laporan') }}"><i class="fas fa-chart-bar"></i> Laporan</a></li>
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
                            <img src="{{ auth()->user()->profile?->foto ? Storage::url(auth()->user()->profile->foto) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) }}"
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
                <div class="page-header">
                    <h1 class="page-title">Laporan & Histori</h1>
                    {{-- Form Filter Bulan dan Tahun --}}
                    <form action="{{ route('laporan') }}" method="GET" class="filter-form-laporan">
                        {{-- Input tersembunyi untuk menyimpan jenis tampilan --}}
                        <input type="hidden" name="view_type" value="{{ $viewType }}">
                        
                        {{-- Dropdown Bulan (hanya tampil di mode bulanan) --}}
                        @if($viewType === 'bulanan')
                        <div class="form-group">
                            <select name="month" class="form-control">
                                @for ($m = 1; $m <= 12; $m++)
                                    <option value="{{ $m }}" {{ $selectedMonth == $m ? 'selected' : '' }}>
                                        {{ Carbon\Carbon::create()->month($m)->format('F') }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        @endif

                        <div class="form-group">
                            <select name="year" class="form-control">
                                @for ($y = date('Y'); $y >= date('Y') - 5; $y--)
                                    <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Tampilkan</button>
                    </form>
                </div>

                <!-- Navigasi Tab -->
                <div class="report-tabs">
                    <a href="{{ route('laporan', ['view_type' => 'bulanan', 'year' => $selectedYear, 'month' => $selectedMonth]) }}" class="tab-button {{ $viewType === 'bulanan' ? 'active' : '' }}">Laporan Bulanan</a>
                    <a href="{{ route('laporan', ['view_type' => 'tahunan', 'year' => $selectedYear]) }}" class="tab-button {{ $viewType === 'tahunan' ? 'active' : '' }}">Laporan Tahunan</a>
                </div>

                <!-- Bagian Diagram Batang -->
                <div class="report-card chart-container">
                    <h3>
                        @if($viewType === 'bulanan')
                            Statistik Pengajuan Harian ({{ Carbon\Carbon::create()->month($selectedMonth)->format('F') }} {{ $selectedYear }})
                        @else
                            Statistik Pengajuan Bulanan (Tahun {{ $selectedYear }})
                        @endif
                    </h3>
                    {{-- FIX: Menggunakan variabel $reportData yang dikirim dari controller --}}
                    @if(!empty($reportData) && $reportData->count() > 0)
                        <canvas id="reportChart" data-chart-type="{{ $viewType }}" data-chart-data="{{ $reportData->toJson() }}" data-year="{{ $selectedYear }}" data-month="{{ $selectedMonth }}"></canvas>
                    @else
                        <p class="text-center">Tidak ada data statistik untuk periode ini.</p>
                    @endif
                </div>
                
                <!-- Bagian Tabel Histori (hanya tampil di mode bulanan) -->
                @if($viewType === 'bulanan')
                <div class="report-card history-container">
                    <h3>Detail Pengajuan Surat ({{ Carbon\Carbon::create()->month($selectedMonth)->format('F') }} {{ $selectedYear }})</h3>
                    <table class="report-table">
                        <thead>
                            <tr>
                                <th>Nomor Surat</th>
                                <th>Nama Karyawan</th>
                                <th>Jenis Cuti</th>
                                <th>Status</th>
                                <th>Tanggal Diproses</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($historiSurat as $surat)
                                <tr>
                                    <td>{{ $surat->nomor_surat }}</td>
                                    <td>{{ $surat->pengajuanCuti->user->name ?? 'N/A' }}</td>
                                    <td>{{ $surat->pengajuanCuti->jenisCuti->nama ?? 'N/A' }}</td>
                                    <td>
                                        <span class="status-badge status-{{ $surat->status }}">{{ ucfirst($surat->status) }}</span>
                                    </td>
                                    <td>{{ $surat->approved_at->format('d M Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada pengajuan pada periode ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="pagination-container">
                        {{ $historiSurat->appends(request()->query())->links() }}
                    </div>
                </div>
                @endif
            </div>
        </main>
    </div>
</body>
</html>
