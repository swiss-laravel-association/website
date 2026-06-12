import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { google } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            fonts: [
                google('IBM Plex Sans', {
                    alias: 'sans',
                    weights: [300, 400, 500, 600],
                    styles: ['normal', 'italic'],
                    subsets: ['latin'],
                    display: 'swap',
                    preload: [
                        { weight: 400 },
                        { weight: 500 },
                    ],
                    fallbacks: ['system-ui', 'sans-serif'],
                }),
                google('IBM Plex Mono', {
                    alias: 'sans',
                    weights: [300, 400, 500],
                    styles: ['normal'],
                    // subsets: ['latin'],
                    display: 'swap',
                    preload: [
                        { weight: 400 },
                        { weight: 500 },
                    ],
                    // fallbacks: ['system-ui', 'sans-serif'],
                }),
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
