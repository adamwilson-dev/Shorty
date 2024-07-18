import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
    server: {
        host: '0.0.0.0', // Set to '0.0.0.0' to allow access from outside the container
        port: 3000,
        hmr: {
            host: 'localhost',  // This should match the host you will access your app from your browser
        },
    },
});
