import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';

Alpine.plugin(collapse);

/*
 | Cookie notice.
 |
 | The dismissal is kept in localStorage rather than in a cookie, deliberately:
 | the privacy notice tells visitors exactly which two cookies the site sets,
 | and setting a third one just to remember that somebody closed a banner would
 | make that statement untrue.
 |
 | Wrapped in try/catch because Safari private browsing throws on localStorage.
 | If it throws we simply do not show the notice again this session.
 */
const NOTICE_KEY = 'shd-cookie-notice-acknowledged';

Alpine.store('cookieNotice', {
    open: false,

    init() {
        try {
            this.open = window.localStorage.getItem(NOTICE_KEY) !== '1';
        } catch {
            this.open = false;
        }
    },

    dismiss() {
        this.open = false;

        try {
            window.localStorage.setItem(NOTICE_KEY, '1');
        } catch {
            // Nothing to do — it will reappear next visit, which is harmless.
        }
    },
});

window.Alpine = Alpine;
Alpine.start();

/*
 | Reveal-on-scroll. Purely decorative: the CSS only hides [data-reveal]
 | elements, and anything already in view is revealed on the first frame, so a
 | visitor without JS (or with reduced motion) sees the full page regardless.
 */
const reveal = () => {
    const items = document.querySelectorAll('[data-reveal]');

    if (! items.length) return;

    if (! ('IntersectionObserver' in window)) {
        items.forEach((el) => el.classList.add('is-visible'));

        return;
    }

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (! entry.isIntersecting) return;

                // Stagger siblings so a grid arrives as a wave, not a jolt.
                const delay = Number(entry.target.dataset.revealDelay ?? 0);

                setTimeout(() => entry.target.classList.add('is-visible'), delay);
                observer.unobserve(entry.target);
            });
        },
        { rootMargin: '0px 0px -8% 0px', threshold: 0.05 },
    );

    items.forEach((el) => observer.observe(el));
};

document.addEventListener('DOMContentLoaded', reveal);

/*
 | v2 pointer details — the card spotlight and the hero's depth.
 |
 | Both are decoration. They run only for a fine pointer (mouse or trackpad)
 | and never under prefers-reduced-motion, so touch screens and anyone who
 | asked for less movement get a still, complete page.
 */
const finePointer = window.matchMedia('(hover: hover) and (pointer: fine)').matches;
const calm = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

if (finePointer && ! calm) {
    document.addEventListener('pointermove', (event) => {
        const tile = event.target.closest?.('[data-spotlight]');
        if (! tile) return;
        const box = tile.getBoundingClientRect();
        tile.style.setProperty('--mx', `${event.clientX - box.left}px`);
        tile.style.setProperty('--my', `${event.clientY - box.top}px`);
    }, { passive: true });

    document.querySelectorAll('[data-depth-scene]').forEach((scene) => {
        const layers = scene.querySelectorAll('[data-depth]');
        scene.addEventListener('pointermove', (event) => {
            const box = scene.getBoundingClientRect();
            const x = (event.clientX - box.left) / box.width - 0.5;
            const y = (event.clientY - box.top) / box.height - 0.5;
            layers.forEach((layer) => {
                const d = Number(layer.dataset.depth || 1) * 6;
                layer.style.transform = `translate3d(${(x * d).toFixed(2)}px, ${(y * d).toFixed(2)}px, 0)`;
            });
        }, { passive: true });
        scene.addEventListener('pointerleave', () => layers.forEach((l) => { l.style.transform = ''; }));
    });
}
