// Review captures of the v3 homepage on STAGING (throwaway container on the staging network).
import puppeteer from 'puppeteer-core';
const b = await puppeteer.launch({ executablePath: '/usr/bin/chromium-browser', headless: true, protocolTimeout: 180000, args: ['--no-sandbox', '--disable-gpu', '--no-zygote', '--disable-dev-shm-usage', '--hide-scrollbars'] });
const URL = 'http://7hills-staging-nginx/';
const sizes = { 1440: [1440, 900, 1], 768: [768, 1024, 1], 390: [390, 844, 2] };
const only = (process.argv[2] || '1440,768,390').split(',');
const wait = (ms) => new Promise((r) => setTimeout(r, ms));

async function open(w, h, s, mobile) {
    const p = await b.newPage();
    await p.setViewport({ width: w, height: h, deviceScaleFactor: s, isMobile: mobile, hasTouch: mobile });
    await p.evaluateOnNewDocument(() => { try { localStorage.setItem('shd-cookie-notice-acknowledged', '1'); } catch {} });
    const errors = [];
    p.on('pageerror', (e) => errors.push(e.message));
    p.on('console', (m) => m.type() === 'error' && errors.push(m.text()));
    p.on('requestfailed', (r) => errors.push('failed: ' + r.url()));
    await p.goto(URL, { waitUntil: 'networkidle0', timeout: 90000 });
    return { p, errors };
}

for (const key of only) {
    const [w, h, s] = sizes[key];
    const mobile = w < 500;
    const { p, errors } = await open(w, h, s, mobile);
    await p.screenshot({ path: `/out/v3-${key}-fold.webp`, type: 'webp', quality: 85 });
    await p.evaluate(async () => { for (let y = 0; y < document.body.scrollHeight; y += 500) { window.scrollTo({ top: y, behavior: 'instant' }); await new Promise((r) => setTimeout(r, 120)); } window.scrollTo({ top: 0, behavior: 'instant' }); });
    await wait(800);
    const m = await p.evaluate(() => ({ height: document.documentElement.scrollHeight, overflowX: document.documentElement.scrollWidth - window.innerWidth, wide: [...document.querySelectorAll('body *')].filter((el) => { const r = el.getBoundingClientRect(); return r.right > window.innerWidth + 1 && getComputedStyle(el).position !== 'fixed' && !el.closest('.snap-row') && !el.closest('[aria-hidden=true]'); }).slice(0, 5).map((el) => el.tagName + '.' + (el.className?.toString?.() || '').slice(0, 60)) }));
    await p.addStyleTag({ content: '[data-reveal]{opacity:1!important;transform:none!important}' }); await wait(400);
    await p.screenshot({ path: `/out/v3-${key}-full.${s > 1 ? 'jpg' : 'webp'}`, type: s > 1 ? 'jpeg' : 'webp', quality: 80, fullPage: true });
    console.log(key, JSON.stringify({ ...m, screens: +(m.height / h).toFixed(1), errors }));

    if (w >= 1024) {
        await p.evaluate(() => window.scrollTo({ top: 0, behavior: 'instant' }));
        await p.hover('[aria-controls=menu-services]'); await wait(500);
        await p.hover('#menu-services a[href*="#line-ai-data-and-automation"]'); await wait(400);
        await p.screenshot({ path: `/out/v3-${key}-menu-services.webp`, type: 'webp', quality: 85 });
        await p.mouse.move(5, 600); await wait(400);
    } else {
        await p.evaluate(() => window.scrollTo({ top: 0, behavior: 'instant' }));
        await p.click('[aria-controls=mobile-sheet]'); await wait(500);
        await p.evaluate(() => document.querySelector('#mobile-sheet button').click()); await wait(500);
        await p.screenshot({ path: `/out/v3-${key}-menu-sheet.webp`, type: 'webp', quality: 85 });
    }
    await p.close();
}
await b.close();
