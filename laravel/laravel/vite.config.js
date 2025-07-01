import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/dashboard.css', 
                'resources/js/dashboard.js', 
                'resources/css/dashboardcontent.css', 
                'resources/js/dashboardcalender.js',
                'resources/css/jeniscuti.css', 
                'resources/js/jeniscuti.js',
                'resources/css/laporan.css', 
                'resources/js/laporan.js',
                'resources/css/manajemen-cuti.css', 
                'resources/js/manajemen-cuti.js',
                'resources/css/profile-page.css',
                'resources/css/riwayat-surat.css',
                'resources/js/riwayat-surat.js',
                'resources/css/template.css', 
                'resources/js/template.js',
                'resources/css/userprofiles.css', 
                'resources/js/userprofiles.js',
                'resources/js/dashboard.js'
            ],
            refresh: true,
        }),
    ],
});
