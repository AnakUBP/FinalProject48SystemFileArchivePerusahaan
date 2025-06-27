<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout; // Penting: Event yang akan didengarkan
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\ActivityLog;

class LogSuccessfulLogout
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Logout $event): void
    {
        // $event->user berisi data pengguna yang baru saja logout
        $user = $event->user;

        // Jika ada user yang terdeteksi saat logout, catat lognya
        if ($user) {
            ActivityLog::create([
                'user_id'       => $user->id,
                'action'        => 'logout',
                'description'   => 'Pengguna ' . $user->name . ' berhasil logout dari sistem.',
                'loggable_id'   => $user->id,
                'loggable_type' => get_class($user),
            ]);
        }
    }
}
