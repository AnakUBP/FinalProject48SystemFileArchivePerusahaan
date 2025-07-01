<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login; // Penting: Event yang akan didengarkan
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\ActivityLog; // Import model ActivityLog Anda

class LogSuccessfulLogin
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
     * Method ini akan otomatis berjalan saat event Login terjadi.
     */
    public function handle(Login $event): void
    {
        // $event->user berisi data pengguna yang baru saja login
        $user = $event->user;

        // Buat record baru di ActivityLog
        ActivityLog::create([
            'user_id'       => $user->id,
            'action'        => 'login',
            'description'   => 'Pengguna ' . $user->name . ' berhasil login ke sistem.',
            'loggable_id'   => $user->id,
            'loggable_type' => get_class($user),
        ]);
    }
}