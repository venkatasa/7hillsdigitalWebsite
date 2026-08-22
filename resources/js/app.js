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
