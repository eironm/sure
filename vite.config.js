import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    server: { preloadEntries: true },
    plugins: [
        laravel({
            input: [
                'resources/css/panel2.css',
                'resources/css/app.css', 
                'resources/js/app.js',
                'resources/css/mobile.css',
                'resources/css/home-new.css',
                'resources/js/home-new.js'
            ],
            refresh: true,
        }),
    ],
});

