<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen User & Profil</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    {{-- FIX: Menyesuaikan path file CSS dan JS agar sesuai dengan standar Vite --}}
    @vite(['resources/css/dashboard.css', 'resources/js/dashboard.js'])
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
                    <li class="active"><a href="{{ route('log.aktivitas') }}"><i class="fas fa-history"></i> Log Aktivitas</a></li>
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
                <div class="container">
                    <h3 class="mb-4">Log Aktivitas</h3>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Waktu</th>
                                    <th>User</th>
                                    <th>Aksi</th>
                                    <th>Deskripsi</th>
                                    <th>Objek</th>
                                </tr>
                            </thead>
                            <tbody id="log-container">
                                @foreach ($logs as $log)
                                    @include('logs._log_item', ['log' => $log])
                                @endforeach
                            </tbody>
                        </table>
                    </div>
            
                    <div id="loader" class="text-center my-3" style="display: none;">
                        <div class="spinner-border" role="status"></div>
                    </div>
                </div>
            </div>
            
            @push('scripts')
            <script>
                let offset = {{ count($logs) }};
                let loading = false;
            
                window.addEventListener('scroll', () => {
                    if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 300 && !loading) {
                        loading = true;
                        document.getElementById('loader').style.display = 'block';
            
                        fetch(`/log-aktivitas/load?offset=${offset}`)
                            .then(res => res.json())
                            .then(data => {
                                document.getElementById('log-container').insertAdjacentHTML('beforeend', data.html);
                                offset += 10;
                                loading = false;
                                if (data.html.trim() === '') {
                                    document.getElementById('loader').innerHTML = '<small>Tidak ada data lagi.</small>';
                                } else {
                                    document.getElementById('loader').style.display = 'none';
                                }
                            });
                    }
                });
            </script>
            </div>
        </main>
    </div>
</body>

</html>