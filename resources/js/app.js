import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';

Alpine.plugin(collapse);

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
