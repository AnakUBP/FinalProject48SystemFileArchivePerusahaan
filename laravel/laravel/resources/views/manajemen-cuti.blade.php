<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Surat Cuti</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    {{-- Pastikan path Vite sudah benar --}}
    @vite(['resources/css/dashboard.css','resources/js/dashboard.js', 'resources/css/manajemen-cuti.css', 'resources/js/manajemen-cuti.js'])
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
                    <li class="active"><a href="{{ route('manajemen-cuti') }}"><i class="fas fa-envelope-open-text"></i>Kelola Cuti</a></li>
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
                    <h1 class="page-title">Manajemen Surat Cuti</h1>
                    {{-- FIX: Tombol ini sekarang memanggil fungsi JS untuk membuka modal --}}
                    <button class="btn btn-primary" onclick="openCreateModal()">
                        <i class="fas fa-plus"></i> Buat Pengajuan
                    </button>
                </div>

                <!-- Navigasi Tab Status -->
                <div class="cuti-nav-tabs">
                    <button class="tab-button active" data-pane="diajukan-pane">
                        <i class="fas fa-paper-plane"></i> Diajukan
                        <span class="count-badge">{{ $pengajuanByStatus['diajukan']->count() }}</span>
                    </button>
                    <button class="tab-button" data-pane="disetujui-pane">
                        <i class="fas fa-check-circle"></i> Disetujui
                        <span class="count-badge">{{ $pengajuanByStatus['disetujui']->count() }}</span>
                    </button>
                    <button class="tab-button" data-pane="ditolak-pane">
                        <i class="fas fa-times-circle"></i> Ditolak
                        <span class="count-badge">{{ $pengajuanByStatus['ditolak']->count() }}</span>
                    </button>
                </div>

                <!-- Kontainer untuk Panel Konten -->
                <div class="cuti-content-container">
                    <div id="diajukan-pane" class="cuti-content-pane active">
                        @forelse ($pengajuanByStatus['diajukan'] as $pengajuan)
                            <div class="cuti-card">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $pengajuan->user->name }}</h5>
                                    <p class="card-subtitle">{{ $pengajuan->jenisCuti->nama ?? 'Jenis tidak diketahui' }}</p>
                                    <p class="card-text">
                                        <i class="fas fa-calendar-alt"></i>
                                        {{ \Carbon\Carbon::parse($pengajuan->tanggal_mulai)->format('d M') }} - {{ \Carbon\Carbon::parse($pengajuan->tanggal_selesai)->format('d M Y') }}
                                    </p>
                                </div>
                                <div class="card-footer">
                                    <button class="btn btn-sm btn-info" onclick="openProsesModal({{ $pengajuan->id }})">Proses</button>
                                </div>
                            </div>
                        @empty
                            <p class="empty-message">Tidak ada pengajuan baru.</p>
                        @endforelse
                    </div>

                    <!-- PANEL 2: DISETUJUI -->
                    <div id="disetujui-pane" class="cuti-content-pane">
                         @forelse ($pengajuanByStatus['disetujui'] as $pengajuan)
                            <div class="cuti-card">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $pengajuan->user->name }}</h5>
                                    <p class="card-subtitle">{{ $pengajuan->jenisCuti->nama ?? 'Jenis tidak diketahui' }}</p>
                                    <p class="card-text">
                                       <i class="fas fa-calendar-check"></i>
                                       Disetujui pada {{ \Carbon\Carbon::parse($pengajuan->suratCutiResmi->approved_at)->format('d M Y') }}
                                    </p>
                                </div>
                                <div class="card-footer">
                                    {{-- FIX: Memastikan nama fungsi ini benar --}}
                                    <button class="btn btn-sm btn-info" onclick="openProsesModal({{ $pengajuan->id }})">Proses</button>
                                </div>
                            </div>
                        @empty
                            <p class="empty-message">Tidak ada surat yang disetujui.</p>
                        @endforelse
                    </div>

                    <!-- PANEL 3: DITOLAK -->
                    <div id="ditolak-pane" class="cuti-content-pane">
                        @forelse ($pengajuanByStatus['ditolak'] as $pengajuan)
                            <div class="cuti-card">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $pengajuan->user->name }}</h5>
                                    <p class="card-subtitle">{{ $pengajuan->jenisCuti->nama ?? 'Jenis tidak diketahui' }}</p>
                                     <p class="card-text">
                                       <i class="fas fa-calendar-times"></i>
                                       Ditolak pada {{ \Carbon\Carbon::parse($pengajuan->suratCutiResmi->approved_at)->format('d M Y') }}
                                    </p>
                                </div>
                                <div class="card-footer">
                                    {{-- FIX: Memastikan nama fungsi ini benar --}}
                                    <button class="btn btn-sm btn-info" onclick="openProsesModal({{ $pengajuan->id }})">Proses</button>
                                </div>
                            </div>
                        @empty
                            <p class="empty-message">Tidak ada surat yang ditolak.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </main>
    </div>
    <div id="prosesPengajuanModal" class="modal">
        <div class="modal-content wide"> {{-- Class 'wide' untuk membuatnya lebih lebar --}}
            <div class="modal-header">
                <h3 class="modal-title">Proses Pengajuan Cuti</h3>
                <button class="close-btn" onclick="closeModal('prosesPengajuanModal')">&times;</button>
            </div>
            <form id="prosesForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body two-columns"> {{-- Class 'two-columns' untuk layout grid --}}
                    
                    <!-- KOLOM KIRI: DETAIL PENGAJUAN -->
                    <div class="detail-column">
                        <h4>Detail Pengajuan</h4>
                        <div id="prosesModalBody">
                            {{-- Konten ini akan diisi oleh JavaScript --}}
                            <p>Memuat detail...</p>
                        </div>
                    </div>

                    <!-- KOLOM KANAN: INPUT ADMIN -->
                    <div class="approval-column">
                        <h4>Keputusan Admin</h4>
                        <div class="form-group">
                            <label for="status">Aksi Persetujuan</label>
                            <select name="status" id="status" class="form-control" required>
                                <option value="">-- Pilih aksi --</option>
                                <option value="disetujui">Setujui Pengajuan</option>
                                <option value="ditolak">Tolak Pengajuan</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="catatan_approval">Catatan (Opsional)</label>
                            <textarea name="catatan_approval" id="catatan_approval" class="form-control" rows="8"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan Keputusan</button>
                    <button type="button" class="btn btn-secondary" onclick="closeModal('prosesPengajuanModal')">Batal</button>
                </div>
            </form>
        </div>
    </div>
    <div id="createCutiModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Buat Pengajuan Cuti Baru</h3>
                <button class="close-btn" onclick="closeModal('createCutiModal')">&times;</button>
            </div>
            <form id="createCutiForm" action="{{ route('pengajuan-cuti.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    {{-- Tampilkan dropdown ini hanya jika yang login adalah Admin --}}
                    @if(auth()->user()->role === 'admin')
                    <div class="form-group">
                        <label for="user_id">Pilih Karyawan</label>
                        <select name="user_id" id="user_id" class="form-control" required>
                            <option value="">-- Pilih Karyawan --</option>
                            @foreach($karyawanList as $karyawan)
                                <option value="{{ $karyawan->id }}">{{ $karyawan->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <div class="form-group">
                        <label for="jenis_cuti_id">Jenis Cuti</label>
                        <select name="jenis_cuti_id" id="jenis_cuti_id" class="form-control" required>
                            <option value="">-- Pilih Jenis Cuti --</option>
                             @foreach($jenisCutiList as $jenis)
                                <option value="{{ $jenis->id }}">{{ $jenis->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group-row">
                        <div class="form-group">
                            <label for="tanggal_mulai">Tanggal Mulai</label>
                            <input type="date" id="tanggal_mulai" name="tanggal_mulai" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="tanggal_selesai">Tanggal Selesai</label>
                            <input type="date" id="tanggal_selesai" name="tanggal_selesai" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="alasan">Alasan Cuti</label>
                        <textarea name="alasan" id="alasan" class="form-control" rows="4" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="signature-pad">Tanda Tangan</label>
                        <canvas id="signature-pad" class="signature-canvas"></canvas>
                        <button type="button" id="clear-signature" class="btn btn-sm btn-secondary">Bersihkan</button>
                        {{-- Input tersembunyi untuk menyimpan data base64 tanda tangan --}}
                        <input type="hidden" name="tanda_tangan" id="tanda_tangan_input">
                    </div>

                    {{-- FIX: Input untuk Lampiran Opsional --}}
                    <div class="form-group">
                        <label for="lampiran">Lampiran (Opsional)</label>
                        <input type="file" name="lampiran" id="lampiran" class="form-control">
                        <small>Upload file pendukung(opsional) (PDF, DOCX, JPG, PNG).</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('createCutiModal')">Batal</button>
                    <button type="submit" class="btn btn-primary">Kirim Pengajuan</button>
                </div>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
</body>
</html>
