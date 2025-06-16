import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js','resources/css/dashboard.css', 'resources/js/dashboard.js','resources/css/template.css', 'resources/js/template.js','resources/css/jeniscuti.css', 'resources/js/jeniscuti.js','resources/js/userprofiles.js','resources/css/userprofiles.css'],
            refresh: true,
        }),
    ],
});
