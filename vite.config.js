import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { fontsource } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
            /*
             | Brand typefaces, per the 7Hills brand guidelines: Montserrat for
             | the logotype and headings, Plus Jakarta Sans for body copy and
             | interfaces.
             |
             | These are fetched at BUILD time and served from our own origin.
             | Never link them from Google Fonts at runtime — a site that sells
             | POPIA compliance should not ship every visitor's IP to a third
             | party to fetch a font. See CLAUDE.md.
             */
            fonts: [
                fontsource('Montserrat', {
                    alias: 'display',
                    weights: [600, 700, 800, 900],
                    subsets: ['latin'],
                    fallbacks: ['ui-sans-serif', 'system-ui', 'sans-serif'],
                }),
                fontsource('Plus Jakarta Sans', {
                    alias: 'sans',
                    weights: [400, 500, 600, 700],
                    subsets: ['latin'],
                    fallbacks: ['Segoe UI', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                }),
            ],
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
