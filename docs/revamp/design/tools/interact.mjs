import puppeteer from 'puppeteer-core';
const b = await puppeteer.launch({ executablePath: '/usr/bin/chromium-browser', headless: true, protocolTimeout: 120000, args: ['--no-sandbox','--disable-gpu','--no-zygote','--disable-dev-shm-usage'] });
const URL = 'http://7hills-staging-nginx/';
const ok = (name, cond, extra = '') => console.log(cond ? 'PASS' : 'FAIL', name, extra);
const wait = (ms) => new Promise(r => setTimeout(r, ms));

let p = await b.newPage(); await p.setViewport({ width: 1440, height: 900 });
await p.goto(URL, { waitUntil: 'networkidle0' });
// Tabs: keyboard
await p.focus('#tab-custom-software-development'); await p.keyboard.press('ArrowDown'); await wait(250);
let st = await p.evaluate(() => ({ sel: document.querySelector('[role=tab][aria-selected=true]')?.id, focus: document.activeElement.id, vis: getComputedStyle(document.getElementById('service-business-process-automation')).display }));
ok('tabs: ArrowDown selects and focuses the next tab', st.sel === 'tab-business-process-automation' && st.focus === st.sel && st.vis !== 'none', JSON.stringify(st));
await p.keyboard.press('End'); await wait(200);
ok('tabs: End jumps to the last tab', await p.evaluate(() => document.activeElement.getAttribute('aria-selected') === 'true' && document.activeElement === [...document.querySelectorAll('#showcase [role=tab]')].at(-1)));
// Mega menu: open, Esc closes, focus returns
await p.click('[aria-controls=menu-platforms]'); await wait(300);
ok('menu: opens on click', await p.evaluate(() => getComputedStyle(document.getElementById('menu-platforms')).display !== 'none' && document.querySelector('[aria-controls=menu-platforms]').getAttribute('aria-expanded') === 'true'));
await p.keyboard.press('Escape'); await wait(300);
ok('menu: Esc closes and returns focus', await p.evaluate(() => getComputedStyle(document.getElementById('menu-platforms')).display === 'none' && document.activeElement.getAttribute('aria-controls') === 'menu-platforms'));
// Header turns solid after the hero
await p.evaluate(() => window.scrollTo({ top: 2500, behavior: 'instant' })); await wait(400);
ok('header: paper after the hero', await p.evaluate(() => document.querySelector('header').className.includes('bg-white/85')));
await p.close();

// Mobile sheet
p = await b.newPage(); await p.setViewport({ width: 390, height: 844, isMobile: true, hasTouch: true });
await p.goto(URL, { waitUntil: 'networkidle0' });
await p.click('[aria-controls=mobile-sheet]'); await wait(400);
ok('mobile: sheet opens, page scroll locked, focus inside', await p.evaluate(() => getComputedStyle(document.getElementById('mobile-sheet')).display !== 'none' && document.documentElement.classList.contains('overflow-hidden') && document.getElementById('mobile-sheet').contains(document.activeElement)));
await p.keyboard.press('Escape'); await wait(300);
ok('mobile: Esc closes the sheet and unlocks scroll', await p.evaluate(() => getComputedStyle(document.getElementById('mobile-sheet')).display === 'none' && ! document.documentElement.classList.contains('overflow-hidden')));
await p.close();

// Reduced motion
p = await b.newPage(); await p.setViewport({ width: 1440, height: 900 });
await p.emulateMediaFeatures([{ name: 'prefers-reduced-motion', value: 'reduce' }]);
await p.goto(URL, { waitUntil: 'networkidle0' });
ok('reduced motion: below-the-fold content is visible without scrolling', await p.evaluate(() => [...document.querySelectorAll('[data-reveal]')].every(el => getComputedStyle(el).opacity === '1')));
await p.close();

// No JavaScript
p = await b.newPage(); await p.setJavaScriptEnabled(false); await p.setViewport({ width: 1440, height: 900 });
await p.goto(URL, { waitUntil: 'load' });
const nojs = await p.evaluate(() => ({ panels: [...document.querySelectorAll('#showcase [role=tabpanel]')].filter(el => getComputedStyle(el).display !== 'none').length, total: document.querySelectorAll('#showcase [role=tabpanel]').length, industries: [...document.querySelectorAll('#industries [role=tabpanel]')].filter(el => getComputedStyle(el).display !== 'none').length, reveal: [...document.querySelectorAll('[data-reveal]')].filter(el => getComputedStyle(el).opacity !== '1').length, tablists: [...document.querySelectorAll('[role=tablist]')].filter(el => getComputedStyle(el).display !== 'none').length }));
ok('no JS: every service and industry panel renders, nothing hidden by reveal, no dead tab buttons', nojs.panels === nojs.total && nojs.industries === 6 && nojs.reveal === 0 && nojs.tablists === 0, JSON.stringify(nojs));
await b.close();
