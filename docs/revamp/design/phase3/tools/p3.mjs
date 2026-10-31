// Phase 3 quality gates on STAGING: overflow, JS errors, axe, link crawl,
// keyboard/no-JS behaviour of the FAQ, and screenshots at 1440/768/390.
import puppeteer from 'puppeteer-core';
import { readFileSync } from 'fs';
const axe = readFileSync('/work/node_modules/axe-core/axe.min.js', 'utf8');
const BASE = 'http://7hills-staging-nginx';
const SOL = ['replace-spreadsheets', 'never-lose-a-lead', 'get-paid-on-time', 'automate-approvals', 'connect-your-systems', 'it-that-just-works'];
const IND = ['automotive', 'property', 'recruitment', 'hospitality', 'professional-services', 'corporate'];
const PAGES = ['/solutions', ...SOL.map((s) => '/solutions/' + s), '/industries', ...IND.map((s) => '/industries/' + s), '/work',
  '/packages', '/packages/listings', '/packages/showroom', '/packages/careers', '/packages/stay', '/packages/business'];
const SHOTS = ['/solutions', '/solutions/never-lose-a-lead', '/industries', '/industries/automotive', '/industries/corporate', '/work', '/packages', '/packages/showroom'];
const sizes = [[1440, 900, 1], [768, 1024, 1], [390, 844, 2]];
const b = await puppeteer.launch({ executablePath: '/usr/bin/chromium-browser', headless: true, protocolTimeout: 240000, args: ['--no-sandbox', '--disable-gpu', '--no-zygote', '--disable-dev-shm-usage', '--hide-scrollbars'] });
const wait = (ms) => new Promise((r) => setTimeout(r, ms));
let pass = 0, fail = 0;
const ok = (name, cond, extra = '') => { cond ? pass++ : fail++; console.log(cond ? 'PASS' : 'FAIL', name, extra); };

const open = async (path, w, h, s, opts = {}) => {
  const p = await b.newPage();
  await p.setViewport({ width: w, height: h, deviceScaleFactor: s, isMobile: w < 500, hasTouch: w < 500 });
  if (opts.js === false) await p.setJavaScriptEnabled(false);
  await p.evaluateOnNewDocument(() => { try { localStorage.setItem('shd-cookie-notice-acknowledged', '1'); } catch {} });
  const errors = [];
  p.on('pageerror', (e) => errors.push(e.message));
  p.on('console', (m) => m.type() === 'error' && errors.push(m.text()));
  await p.goto(BASE + path, { waitUntil: 'networkidle0', timeout: 90000 });
  return { p, errors };
};
const settle = (p) => p.evaluate(async () => { for (let y = 0; y < document.body.scrollHeight; y += 600) { window.scrollTo({ top: y, behavior: 'instant' }); await new Promise((r) => setTimeout(r, 70)); } window.scrollTo({ top: 0, behavior: 'instant' }); });

const allLinks = new Set(); const axeFindings = []; const overflow = []; const jsErrors = [];
for (const path of PAGES) {
  for (const [w, h, s] of sizes) {
    const { p, errors } = await open(path, w, h, 1);
    await settle(p); await wait(500);
    const o = await p.evaluate(() => document.documentElement.scrollWidth - window.innerWidth);
    if (o > 0) overflow.push(`${path}@${w}:${o}px`);
    if (w === 1440) {
      (await p.evaluate(() => [...document.querySelectorAll('a[href]')].map((a) => a.href))).forEach((l) => allLinks.add(l.split('#')[0]));
      await p.evaluate(axe);
      const v = await p.evaluate(async () => (await axe.run(document, { runOnly: ['wcag2a', 'wcag2aa', 'wcag21aa', 'wcag22aa', 'best-practice'] })).violations.map((v) => `${v.id}(${v.impact})x${v.nodes.length}: ${v.nodes[0].target.join(' ').slice(0, 90)}`));
      v.filter((x) => !x.startsWith('region(moderate)x1: .right-5')).forEach((x) => axeFindings.push(`${path}: ${x}`));
    }
    if (w === 390) {
      await p.evaluate(axe);
      const v = await p.evaluate(async () => (await axe.run(document, { runOnly: ['wcag2a', 'wcag2aa', 'wcag21aa', 'wcag22aa'] })).violations.map((v) => `${v.id}x${v.nodes.length}: ${v.nodes[0].target.join(' ').slice(0, 90)}`));
      v.forEach((x) => axeFindings.push(`${path}@390: ${x}`));
    }
    errors.forEach((e) => jsErrors.push(`${path}@${w}: ${e}`));
    await p.close();
  }
}
ok(`no horizontal overflow on ${PAGES.length} pages at 1440/768/390`, overflow.length === 0, JSON.stringify(overflow));
ok('no JavaScript errors', jsErrors.length === 0, JSON.stringify(jsErrors.slice(0, 5)));
ok('axe: no WCAG violations (desktop + mobile)', axeFindings.length === 0, '\n   ' + axeFindings.join('\n   '));

// Link crawl: every same-site link from every page answers.
const p0 = (await open('/', 1440, 900, 1)).p;
const broken = [];
for (const l of allLinks) {
  if (!l.startsWith(BASE)) continue;
  const st = await p0.evaluate(async (u) => (await fetch(u, { redirect: 'manual' })).status, l);
  if (st >= 400 || st === 0) broken.push(`${l} ${st}`);
}
ok(`all ${[...allLinks].filter((l) => l.startsWith(BASE)).length} same-site links answer`, broken.length === 0, JSON.stringify(broken));

// Every in-page anchor on every page has a target.
const deadAnchors = [];
for (const path of PAGES) {
  await p0.goto(BASE + path, { waitUntil: 'domcontentloaded' });
  const d = await p0.evaluate(() => [...document.querySelectorAll('a[href]')].map((a) => new URL(a.href)).filter((u) => u.hash && u.pathname === location.pathname).map((u) => u.hash.slice(1)).filter((id) => !document.getElementById(id)));
  d.forEach((x) => deadAnchors.push(`${path}#${x}`));
}
ok('no in-page anchor without a target', deadAnchors.length === 0, JSON.stringify(deadAnchors));

// Every service a solution or industry page names lands on a page or an anchor that exists.
const svcLinks = new Set();
for (const path of PAGES.filter((p) => !p.startsWith('/packages'))) {
  await p0.goto(BASE + path, { waitUntil: 'domcontentloaded' });
  (await p0.evaluate(() => [...document.querySelectorAll('a[href*="/services/"]')].map((a) => a.getAttribute('href')))).forEach((h) => svcLinks.add(h));
}
const missing = [];
for (const href of svcLinks) {
  const u = new URL(href, BASE);
  const r = await p0.goto(BASE + u.pathname, { waitUntil: 'domcontentloaded' });
  if (r.status() !== 200 || (u.hash && !(await p0.evaluate((id) => !!document.getElementById(id), u.hash.slice(1))))) missing.push(href);
}
ok(`all ${svcLinks.size} service links from solutions and industries land`, missing.length === 0, JSON.stringify(missing));
await p0.close();

// FAQ: keyboard and no-JS.
let { p } = await open('/solutions/never-lose-a-lead', 1440, 900, 1);
const faqs = await p.$$('details.faq summary');
await faqs[1].focus(); await p.keyboard.press('Enter'); await wait(300);
ok('FAQ: Enter opens a question', await p.evaluate(() => document.querySelectorAll('details.faq')[1].open));
await p.keyboard.press('Space'); await wait(300);
ok('FAQ: Space closes it again', await p.evaluate(() => !document.querySelectorAll('details.faq')[1].open));
ok('FAQ: first answer open by default', await p.evaluate(() => document.querySelector('details.faq').open));
await p.close();
({ p } = await open('/industries/property', 390, 844, 2, { js: false }));
ok('no JS: content visible, nothing stuck hidden', await p.evaluate(() => [...document.querySelectorAll('[data-reveal]')].every((el) => getComputedStyle(el).opacity === '1')));
await p.close();

// Reduced motion.
const rp = await b.newPage(); await rp.setViewport({ width: 1440, height: 900 });
await rp.emulateMediaFeatures([{ name: 'prefers-reduced-motion', value: 'reduce' }]);
await rp.goto(BASE + '/solutions/automate-approvals', { waitUntil: 'networkidle0' });
ok('reduced motion: all visible, flow pulses off', await rp.evaluate(() => [...document.querySelectorAll('[data-reveal]')].every((el) => getComputedStyle(el).opacity === '1') && [...document.querySelectorAll('.flow-link')].every((el) => getComputedStyle(el, '::after').display === 'none')));
await rp.close();

// Menus lead to the new pages.
({ p } = await open('/', 1440, 900, 1));
await p.click('[aria-controls=menu-solutions]'); await wait(400);
await Promise.all([p.waitForNavigation({ waitUntil: 'networkidle0' }), p.click('#menu-solutions a[href*="/solutions/"]')]);
ok('menu: a solution opens its page', /\/solutions\/[a-z-]+$/.test(p.url()), p.url());
await Promise.all([p.waitForNavigation({ waitUntil: 'networkidle0' }), p.click('nav[aria-label=Main] a[href$="/work"]')]);
ok('menu: Our Work opens /work', p.url().endsWith('/work'), p.url());
await p.close();
({ p } = await open('/', 390, 844, 2));
await p.click('button[aria-controls=mobile-sheet], header button[aria-label*="menu" i]'); await wait(500);
const ib = await p.$$('#mobile-sheet button, [x-ref=sheet] button');
let opened = false;
for (const btn of ib) { if ((await btn.evaluate((e) => e.textContent.trim())).startsWith('Industries')) { await btn.click(); opened = true; break; } }
await wait(500);
const ilink = await p.$$eval('a[href*="/industries/"]', (as) => as.filter((a) => a.offsetParent).map((a) => a.getAttribute('href')));
ok('mobile sheet: Industries expands to industry pages', opened && ilink.length >= 6, `${opened} ${ilink.length}`);
await p.close();

// Desktop nav fits at 1024 without wrapping.
({ p } = await open('/', 1024, 800, 1));
const navH = await p.$eval('nav[aria-label=Main]', (n) => n.getBoundingClientRect().height);
ok('desktop nav fits on one line at 1024', navH <= 48, navH + 'px');
await p.close();

// Screenshots.
for (const path of SHOTS) {
  for (const [w, h, s] of sizes) {
    const { p } = await open(path, w, h, s);
    await settle(p); await p.addStyleTag({ content: '[data-reveal]{opacity:1!important;transform:none!important}' }); await wait(500);
    const name = path.slice(1).replace(/\//g, '-');
    await p.screenshot({ path: `/out/${name}-${w}-fold.webp`, type: 'webp', quality: 85 });
    await p.screenshot({ path: `/out/${name}-${w}-full.${s > 1 ? 'jpg' : 'webp'}`, type: s > 1 ? 'jpeg' : 'webp', quality: 80, fullPage: true });
    await p.close();
  }
}
console.log(`\n${pass} passed, ${fail} failed`);
await b.close();
