// Interaction checks for the v3 homepage on STAGING.
import puppeteer from 'puppeteer-core';
const b = await puppeteer.launch({ executablePath: '/usr/bin/chromium-browser', headless: true, protocolTimeout: 120000, args: ['--no-sandbox', '--disable-gpu', '--no-zygote', '--disable-dev-shm-usage'] });
const URL = 'http://7hills-staging-nginx/';
let pass = 0, fail = 0;
const ok = (name, cond, extra = '') => { cond ? pass++ : fail++; console.log(cond ? 'PASS' : 'FAIL', name, extra); };
const wait = (ms) => new Promise((r) => setTimeout(r, ms));
const vis = (p, sel) => p.evaluate((s) => { const el = document.querySelector(s); return !!el && getComputedStyle(el).display !== 'none'; }, sel);
const errors = [];
const page = async (w, h, opts = {}) => {
    const p = await b.newPage();
    await p.setViewport({ width: w, height: h, isMobile: w < 500, hasTouch: w < 500 });
    if (opts.js === false) await p.setJavaScriptEnabled(false);
    if (opts.calm) await p.emulateMediaFeatures([{ name: 'prefers-reduced-motion', value: 'reduce' }]);
    p.on('pageerror', (e) => errors.push(e.message));
    p.on('console', (m) => m.type() === 'error' && errors.push(m.text()));
    await p.goto(URL, { waitUntil: 'networkidle0' });
    return p;
};

// Desktop: Services mega-menu
let p = await page(1440, 900);
await p.hover('[aria-controls=menu-services]'); await wait(400);
ok('services menu opens on hover', await vis(p, '#menu-services'));
await p.hover('#menu-services a[href$="/services/business-applications"]'); await wait(300);
ok('hovering a line shows its services', await p.evaluate(() => [...document.querySelectorAll('#menu-services .menu-pane')].filter((e) => getComputedStyle(e).display !== 'none').map((e) => e.textContent).join('').includes('CRM Systems')));
ok('only the hovered line is highlighted', await p.evaluate(() => [...document.querySelectorAll('#menu-services .menu-line')].filter((a) => a.classList.contains('text-white') && a.classList.contains('bg-white/[0.07]')).length === 1));
await p.mouse.move(700, 880); await wait(500);
ok('menu closes when the pointer leaves', !(await vis(p, '#menu-services')));

// Keyboard: open with Enter, Tab into lines, focus switches pane, Esc returns focus
await p.focus('[aria-controls=menu-services]'); await p.keyboard.press('Enter'); await wait(350);
ok('Enter opens the services menu, aria-expanded true', (await vis(p, '#menu-services')) && await p.evaluate(() => document.querySelector('[aria-controls=menu-services]').getAttribute('aria-expanded') === 'true'));
await p.keyboard.press('Tab'); await p.keyboard.press('Tab'); await wait(250);
ok('tabbing onto a line shows that line', await p.evaluate(() => { const a = document.activeElement; if (!a.classList.contains('menu-line')) return false; const name = a.querySelector('span.flex-1').textContent.trim(); return [...document.querySelectorAll('#menu-services .menu-pane')].filter((e) => getComputedStyle(e).display !== 'none').map((e) => e.querySelector('p').textContent.trim()).includes(name); }));
await p.keyboard.press('Escape'); await wait(300);
ok('Esc closes and returns focus to Services', !(await vis(p, '#menu-services')) && await p.evaluate(() => document.activeElement.getAttribute('aria-controls') === 'menu-services'));

// A service link in the menu opens that service's page (phase 2)
await p.click('[aria-controls=menu-services]'); await wait(350);
await Promise.all([p.waitForNavigation({ waitUntil: 'networkidle0' }), p.click('#menu-services .menu-pane:not([style*="none"]) ul a')]);
ok('menu service link opens its page', /\/services\/[a-z-]+/.test(p.url()) && await p.evaluate(() => !!document.querySelector('h1')), p.url()); await p.goto(URL, { waitUntil: 'networkidle0' });

// Industries dropdown opens the industry page (phase 3); the homepage tab still follows its hash
await p.click('[aria-controls=menu-industries]'); await wait(350);
await Promise.all([p.waitForNavigation({ waitUntil: 'networkidle0' }), p.click('#menu-industries a[href$="/industries/hospitality"]')]);
ok('industry menu link opens its page', p.url().endsWith('/industries/hospitality'), p.url());
await p.goto(URL + '#industry-hospitality', { waitUntil: 'networkidle0' }); await wait(600);
ok('a #industry- hash still selects that homepage tab', await p.evaluate(() => document.getElementById('itab-hospitality').getAttribute('aria-selected') === 'true'));
await p.goto(URL, { waitUntil: 'networkidle0' });

// Proof tabs: arrow keys
await p.focus('#ptab-back-office'); await p.keyboard.press('ArrowRight'); await wait(300);
ok('proof tabs: ArrowRight selects and focuses the next tab', await p.evaluate(() => document.activeElement.id === 'ptab-platform' && document.activeElement.getAttribute('aria-selected') === 'true' && getComputedStyle(document.getElementById('proof-platform')).display !== 'none'));

// Header turns paper after the hero
await p.evaluate(() => window.scrollTo({ top: 2500, behavior: 'instant' })); await wait(400);
ok('header is paper past the hero', await p.evaluate(() => document.querySelector('header').classList.contains('bg-white/85') && ! document.querySelector('header').classList.contains('bg-brand-dark/80')));

// Every in-page link resolves
const dead = await p.evaluate(() => [...document.querySelectorAll('a[href*="#"]')].map((a) => new URL(a.href)).filter((u) => u.pathname === location.pathname).map((u) => u.hash.slice(1)).filter((id) => id && !document.getElementById(id)));
ok('no in-page link points at a missing id', dead.length === 0, JSON.stringify([...new Set(dead)]));
const hrefs = await p.evaluate(() => [...new Set([...document.querySelectorAll('a[href]')].map((a) => a.href).filter((h) => h.startsWith(location.origin)).map((h) => h.split('#')[0]))]);
const broken = [];
for (const h of hrefs) { const r = await p.evaluate(async (u) => (await fetch(u, { redirect: 'manual' })).status, h); if (r >= 400) broken.push(h + ' ' + r); }
ok(`all ${hrefs.length} same-site links answer`, broken.length === 0, JSON.stringify(broken));
await p.close();

// Tablet: still the burger
p = await page(768, 1024);
ok('768px uses the mobile menu', await vis(p, '[aria-controls=mobile-sheet]') && !(await p.evaluate(() => getComputedStyle(document.querySelector('nav[aria-label=Main]')).display !== 'none')));
await p.close();

// Mobile sheet
p = await page(390, 844);
await p.click('[aria-controls=mobile-sheet]'); await wait(450);
ok('mobile: sheet opens, scroll locked, focus inside', await p.evaluate(() => getComputedStyle(document.getElementById('mobile-sheet')).display !== 'none' && document.documentElement.classList.contains('overflow-hidden') && document.getElementById('mobile-sheet').contains(document.activeElement)));
ok('mobile: the sheet actually covers the screen below the header', await p.evaluate(() => { const r = document.getElementById('mobile-sheet').getBoundingClientRect(); const h = document.querySelector('header').getBoundingClientRect(); return r.height > innerHeight * 0.6 && Math.abs(r.top - h.bottom) < 2; }), await p.evaluate(() => JSON.stringify(document.getElementById('mobile-sheet').getBoundingClientRect())));
await p.evaluate(() => document.querySelector('#mobile-sheet button').click()); await wait(450);
await Promise.all([p.waitForNavigation({ waitUntil: 'networkidle0' }), p.evaluate(() => [...document.querySelectorAll('#mobile-sheet a')].find((a) => a.href.endsWith('/services/ai-data-and-automation')).click())]);
ok('mobile: a service line link opens that line page with the sheet closed', p.url().endsWith('/services/ai-data-and-automation') && await p.evaluate(() => getComputedStyle(document.getElementById('mobile-sheet')).display === 'none' && ! document.documentElement.classList.contains('overflow-hidden')));
await p.goto(URL, { waitUntil: 'networkidle0' }); ok('mobile: homepage catalogue lines start collapsed', await p.evaluate(() => getComputedStyle(document.getElementById('line-list-software-development')).display === 'none'));
await p.close();

// Reduced motion
p = await page(1440, 900, { calm: true });
ok('reduced motion: every reveal block is visible', await p.evaluate(() => [...document.querySelectorAll('[data-reveal]')].every((el) => getComputedStyle(el).opacity === '1')));
ok('reduced motion: flow pulses are off', await p.evaluate(() => [...document.querySelectorAll('.flow-link')].every((el) => getComputedStyle(el, '::after').display === 'none')));
await p.close();

// No JavaScript
p = await page(390, 844, { js: false });
const nojs = await p.evaluate(() => ({
    reveal: [...document.querySelectorAll('[data-reveal]')].filter((el) => getComputedStyle(el).opacity !== '1').length,
    lists: [...document.querySelectorAll('.js-collapse')].filter((el) => getComputedStyle(el).display === 'none').length,
    headings: [...document.querySelectorAll('.cat-line h4')].filter((el) => getComputedStyle(el).display === 'none').length,
    panels: [...document.querySelectorAll('[role=tabpanel]')].filter((el) => getComputedStyle(el).display === 'none').length,
    deadTabs: [...document.querySelectorAll('[role=tablist]')].filter((el) => getComputedStyle(el).display !== 'none').length,
}));
ok('no JS: nothing hidden, no dead tab buttons', Object.values(nojs).every((v) => v === 0), JSON.stringify(nojs));
await p.close();

ok('no JavaScript errors in any run', errors.length === 0, JSON.stringify(errors));
console.log(`\n${pass} passed, ${fail} failed`);
await b.close();
