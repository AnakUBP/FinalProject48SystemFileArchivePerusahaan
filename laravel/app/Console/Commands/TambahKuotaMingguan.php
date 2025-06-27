<?php
// app/Console/Commands/TambahKuotaMingguan.php
namespace App\Console\Commands;

use App\Models\Profile;
use Illuminate\Console\Command;

class TambahKuotaMingguan extends Command
{
    protected $signature = 'kuota:tambah-mingguan';
    protected $description = 'Menambah 1 kuota cuti untuk semua profil karyawan setiap minggu';

    public function handle()
    {
        $this->info('Memulai proses penambahan kuota cuti mingguan...');

        // Tambah 1 kuota ke semua profil yang terhubung dengan user 'karyawan'
        $updatedCount = Profile::whereHas('user', function ($query) {
            $query->where('role', 'karyawan');
        })->increment('sisa_kuota_cuti');

        $this->info("Proses selesai. {$updatedCount} profil karyawan telah diperbarui.");
        return self::SUCCESS;
    }
}