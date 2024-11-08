import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // 'resources/css/app.css', 'resources/js/app.js',
                'public/assets/css/custom.css',
                'public/assets/js/app.js'
            ],
            refresh: true,
        }),
    ],
});
