import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/conges-settings.js',
                'resources/css/conges-settings.css',
                'resources/js/rule-fields.js',

            ],
            refresh: true,
        }),
    ],
});