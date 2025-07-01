<?php

namespace App\Console\Commands;

use App\Models\SuratCutiResmi;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class UpdateCutiStatusCommand extends Command
{
    protected $signature = 'cuti:update-status';
    protected $description = 'Memperbarui status pengajuan cuti yang kedaluwarsa atau perlu ditolak secara otomatis.';

    public function handle()
    {
        $this->info('Memulai proses pembaruan status cuti...');
        $this->processAutoRejection();
        $this->processExpiredLeaves();
        $this->info('Proses pembaruan status cuti selesai.');
        return self::SUCCESS;
    }

    /**
     * FIX: Aturan 1: Jika status 'diajukan' melewati tanggal_mulai, ubah jadi 'ditolak'.
     */
    private function processAutoRejection()
    {
        $this->line('-> Mengecek pengajuan yang perlu ditolak otomatis...');
        
        // Cari surat resmi yang statusnya 'diajukan'
        // dan pengajuan terkaitnya sudah melewati tanggal mulai.
        $pendingOverdue = SuratCutiResmi::where('status', 'diajukan')
            ->whereHas('pengajuanCuti', function ($query) {
                $query->where('tanggal_mulai', '<', Carbon::today());
            })
            ->get();

        if ($pendingOverdue->isEmpty()) {
            $this->info('   Tidak ada pengajuan yang perlu ditolak otomatis.');
            return;
        }

        $this->warn("   Ditemukan {$pendingOverdue->count()} pengajuan untuk ditolak.");
        foreach ($pendingOverdue as $surat) {
            // Update statusnya menjadi 'ditolak'
            $surat->update([
                'status'            => 'ditolak',
                'approved_at'       => now(),
                'catatan_approval'  => 'Ditolak otomatis oleh sistem karena melewati tanggal mulai.',
            ]);
        }
    }

    /**
     * FIX: Aturan 2 & 3: Mengubah status menjadi 'kadaluwarsa'.
     */
    private function processExpiredLeaves()
    {
        $this->line('-> Mengecek surat yang perlu diubah menjadi kadaluwarsa...');
        $twoDaysAgo = Carbon::now()->subDays(2);
        
        // Gabungkan kedua kondisi menjadi satu query update untuk efisiensi
        $expiredCount = SuratCutiResmi::where(function ($query) use ($twoDaysAgo) {
                // Aturan 2: status 'ditolak' dan sudah lewat 2 hari
                $query->where(function ($subQuery) use ($twoDaysAgo) {
                    $subQuery->where('status', 'ditolak')
                             ->where('approved_at', '<', $twoDaysAgo);
                })
                // ATAU
                // Aturan 3: status 'disetujui' dan tanggal selesai cuti sudah lewat 2 hari
                ->orWhere(function ($subQuery) use ($twoDaysAgo) {
                    $subQuery->where('status', 'disetujui')
                             ->whereHas('pengajuanCuti', function ($pengajuanQuery) use ($twoDaysAgo) {
                                 $pengajuanQuery->where('tanggal_selesai', '<', $twoDaysAgo);
                             });
                });
            })
            ->update(['status' => 'kadaluwarsa']);

        if ($expiredCount > 0) {
            $this->warn("   {$expiredCount} surat telah diubah statusnya menjadi kadaluwarsa.");
        } else {
            $this->info('   Tidak ada surat yang perlu diubah menjadi kadaluwarsa.');
        }
    }
}
