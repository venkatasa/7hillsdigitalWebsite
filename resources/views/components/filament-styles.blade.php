{{--
    Admin panel styling injected as plain CSS through a render hook rather
    than a compiled Filament theme, so restyling the back office never needs
    a Vite build. Colours read from config/brand.php.
--}}
<style>
    :root {
        --shd-primary: {{ config('brand.colors.primary') }};
        --shd-accent: {{ config('brand.colors.accent') }};
        --shd-dark: {{ config('brand.colors.dark') }};
    }

    /* The sidebar carries the brand navy, light and dark alike. */
    .fi-sidebar,
    .fi-topbar > nav {
        --tw-bg-opacity: 1;
    }

    .fi-sidebar-header,
    .fi-sidebar {
        background-color: color-mix(in srgb, var(--shd-primary) 4%, white);
    }

    .dark .fi-sidebar-header,
    .dark .fi-sidebar {
        background-color: var(--shd-dark);
    }

    /* Brand mark on the login screen sits a little more generously. */
    .fi-simple-header .fi-logo {
        margin-inline: auto;
    }

    /* Tighten the dashboard heading so the enquiry numbers lead. */
    .fi-dashboard-page .fi-header-heading {
        font-weight: 700;
        letter-spacing: -0.01em;
    }
</style>
