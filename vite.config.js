import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { local } from 'laravel-vite-plugin/fonts';

// The Latin WOFF2 files from the @fontsource packages, by weight. `fontsource()`
// emitted a separate @font-face for the WOFF fallback of every weight, so browsers
// fetched both formats and the preloaded WOFF2 went unused. Every browser we
// support reads WOFF2.
const woff2 = (pkg, slug, weights) => weights.map((weight) => ({
    src: `node_modules/@fontsource/${pkg}/files/${slug}-latin-${weight}-normal.woff2`,
    weight,
}));
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
             |
             | Only the two faces the first screen paints with are preloaded
             | (headings 800, body 400). Preloading all eight made every phone
             | download them before the hero, which kept mobile LCP near 3 s.
             | The rest load on demand with `swap` and metric-matched fallbacks.
             | Montserrat 900 is gone: nothing on the site uses it.
             */
            fonts: [
                local('Montserrat', {
                    alias: 'display',
                    variants: woff2('montserrat', 'montserrat', [600, 700, 800]),
                    display: 'swap',
                    preload: [{ weight: 800 }],
                    fallbacks: ['ui-sans-serif', 'system-ui', 'sans-serif'],
                }),
                local('Plus Jakarta Sans', {
                    alias: 'sans',
                    variants: woff2('plus-jakarta-sans', 'plus-jakarta-sans', [400, 500, 600, 700]),
                    display: 'swap',
                    preload: [{ weight: 400 }],
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
