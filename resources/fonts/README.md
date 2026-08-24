# Fonts for image generation

Montserrat and Plus Jakarta Sans, as TrueType, used by `php artisan brand:banner`
to render social artwork with GD. GD needs TTF; the web build uses the WOFF2
files from `@fontsource/*` instead, so these exist only for image rendering.

They were converted from the WOFF files already shipped in
`node_modules/@fontsource/`, which is a lossless unpack — WOFF1 is an sfnt with
each table zlib-deflated.

Both families are licensed under the **SIL Open Font License 1.1**, which permits
redistribution. Keep this note with them.
