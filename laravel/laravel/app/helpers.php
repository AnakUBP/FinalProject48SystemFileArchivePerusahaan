<?php
if (!function_exists('logColor')) {
    function logColor($action)
    {
        return match($action) {
            'login' => 'success',
            'logout' => 'secondary',
            'mengajukan_cuti' => 'primary',
            'memproses_pengajuan' => 'warning',
            'menghapus_pengajuan' => 'danger',
            default => 'info',
        };
    }
}
