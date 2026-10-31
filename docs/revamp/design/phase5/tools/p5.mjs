// Phase 5 launch QA on STAGING: every sitemap page at 1440/768/390 for overflow,
// JS errors and axe; a same-site link crawl; keyboard, reduced motion, the menu
// and WhatsApp; screenshots of the pages that moved to the new layout; the panel
// pipeline. Fictional data only. Never point this at production.
import puppeteer from 'puppeteer-core';
import { readFileSync } from 'fs';
const axe = readFileSync('/work/node_modules/axe-core/axe.min.js', 'utf8');
const BASE = 'http://7hills-staging-nginx';
const OUT = '/out';
const b = await puppeteer.launch({ executablePath: '/usr/bin/chromium-browser', headless: true, protocolTimeout: 240000, args: ['--no-sandbox', '--disable-gpu', '--no-zygote', '--disable-dev-shm-usage', '--hide-scrollbars'] });
const wait = (ms) => new Promise((r) => setTimeout(r, ms));
let pass = 0, fail = 0;
const ok = (name, cond, extra = '') => { cond ? pass++ : fail++; console.log(cond ? 'PASS' : 'FAIL', name, extra); };

const open = async (path, w = 1440, h = 900, opts = {}) => {
  const p = await b.newPage();
  await p.setViewport({ width: w, height: h, isMobile: w < 500, hasTouch: w < 500 });
  if (opts.reduced) await p.emulateMediaFeatures([{ name: 'prefers-reduced-motion', value: 'reduce' }]);
  await p.evaluateOnNewDocument(() => { try { localStorage.setItem('shd-cookie-notice-acknowledged', '1'); } catch {} });
  const errors = [];
  p.on('pageerror', (e) => errors.push(e.message));
  p.on('console', (m) => m.type() === 'error' && errors.push(m.text()));
  await p.goto(BASE + path, { waitUntil: 'networkidle0', timeout: 90000 });
  return { p, errors };
};
const shot = async (p, name, full = false) => {
  await p.addStyleTag({ content: '[data-reveal]{opacity:1!important;transform:none!important}' });
  await wait(250);
  await p.screenshot({ path: `${OUT}/${name}.webp`, type: 'webp', quality: 80, fullPage: full });
};

// ---- 1. The sitemap ----
const xml = await (await fetch(BASE + '/sitemap.xml')).text();
const paths = [...xml.matchAll(/<loc>([^<]+)<\/loc>/g)].map((m) => new URL(m[1]).pathname);
ok(`sitemap lists ${paths.length} pages`, paths.length > 30);
const extra = ['/demos/realty', '/demos/plant', '/contact/thank-you', '/quote/thank-you'];

// ---- 2. Every page at three widths: overflow, JS errors, axe ----
const overflow = [], jsErrors = [], axeFindings = [];
const links = new Set();
for (const path of [...paths, ...extra]) {
  for (const [w, h] of [[1440, 900], [768, 1024], [390, 844]]) {
    const { p, errors } = await open(path, w, h);
    const ov = await p.evaluate(() => document.documentElement.scrollWidth - document.documentElement.clientWidth);
    if (ov > 0) overflow.push(`${path}@${w}: ${ov}px`);
    errors.forEach((e) => jsErrors.push(`${path}@${w}: ${e.slice(0, 120)}`));
    if (w !== 768) {
      await p.evaluate(axe);
      const v = await p.evaluate(async () => (await axe.run(document, { runOnly: ['wcag2a', 'wcag2aa', 'wcag21aa', 'wcag22aa', 'best-practice'] })).violations
        .map((v) => `${v.id}(${v.impact})x${v.nodes.length}: ${v.nodes[0].target.join(' ').slice(0, 90)}`));
      v.forEach((x) => axeFindings.push(`${path}@${w}: ${x}`));
    }
    if (w === 1440) (await p.$$eval('a[href]', (as) => as.map((a) => a.href))).forEach((l) => links.add(l.split('#')[0]));
    await p.close();
  }
}
ok(`no horizontal overflow on ${paths.length + extra.length} pages x 3 widths`, overflow.length === 0, overflow.join('; '));
ok('no JavaScript errors', jsErrors.length === 0, jsErrors.slice(0, 10).join('; '));
ok('axe: no WCAG or best-practice violations', axeFindings.length === 0, '\n   ' + axeFindings.slice(0, 30).join('\n   '));

// ---- 3. Link crawl ----
{
  const broken = [];
  const same = [...links].filter((l) => l.startsWith(BASE));
  for (const l of same) {
    const st = (await fetch(l, { redirect: 'manual' })).status;
    if (st >= 400) broken.push(`${l} ${st}`);
  }
  ok(`all ${same.length} same-site links answer`, broken.length === 0, broken.join('; '));
  const offsite = [...links].filter((l) => !l.startsWith(BASE) && /^https?:/.test(l));
  const allowed = /wa\.me|excellentrealestate\.co\.za|inforegulator\.org\.za|openverse|creativecommons\.org|flickr\.com|wikimedia/;
  ok('off-site links are WhatsApp, the live client, the Regulator or photo credits', offsite.every((l) => allowed.test(l)), offsite.filter((l) => !allowed.test(l)).join('; '));
}

// ---- 4. The moved pages: keyboard, menu, WhatsApp, reduced motion ----
{
  const { p } = await open('/about', 1440, 900);
  await p.keyboard.press('Tab');
  const skip = await p.evaluate(() => document.activeElement.textContent.trim());
  ok('first Tab on /about is the skip link', /Skip to content/.test(skip), skip);
  await p.keyboard.press('Enter');
  await p.keyboard.press('Tab');
  const inMain = await p.evaluate(() => !!document.activeElement.closest('main'));
  ok('skip link moves focus into main', inMain);
  await p.close();
}
{
  const { p } = await open('/about', 390, 844);
  const before = await p.$eval('.wa-float', (e) => getComputedStyle(e).display);
  await p.click('[aria-controls="mobile-sheet"]');
  await wait(500);
  const during = await p.$eval('.wa-float', (e) => getComputedStyle(e).display);
  await shot(p, 'about-390-menu-open');
  await p.keyboard.press('Escape');
  await wait(500);
  const after = await p.$eval('.wa-float', (e) => getComputedStyle(e).display);
  ok('WhatsApp float hides while the mobile menu is open and returns after', before !== 'none' && during === 'none' && after !== 'none', `${before}/${during}/${after}`);
  await p.close();
}
{
  const { p } = await open('/how-it-works', 390, 844, { reduced: true });
  const hidden = await p.$$eval('[data-reveal]', (els) => els.filter((e) => getComputedStyle(e).opacity === '0').length);
  ok('reduced motion: nothing is hidden behind data-reveal', hidden === 0, `${hidden} hidden`);
  await p.close();
}

// ---- 5. Screenshots of the pages that moved to the new layout ----
for (const path of ['/about', '/how-it-works', '/custom-and-it', '/demos', '/contact', '/terms', '/privacy']) {
  const name = path.slice(1);
  for (const [w, h] of [[1440, 900], [768, 1024], [390, 844]]) {
    const { p } = await open(path, w, h);
    await shot(p, `${name}-${w}-fold`);
    if (w !== 768 && ['/about', '/how-it-works', '/custom-and-it', '/demos'].includes(path)) await shot(p, `${name}-${w}-full`, true);
    await p.close();
  }
}

// ---- 6. The panel: pipeline and monthly figures ----
if (process.env.PREVIEW_PASSWORD) {
  const p = await b.newPage();
  await p.setViewport({ width: 1440, height: 900 });
  await p.goto(BASE + '/admin/login', { waitUntil: 'networkidle0' });
  await p.type('input[type="email"]', 'preview@staging.test');
  await p.type('input[type="password"]', process.env.PREVIEW_PASSWORD);
  await Promise.all([p.waitForNavigation({ waitUntil: 'networkidle0' }), p.keyboard.press('Enter')]);
  await p.goto(BASE + '/admin', { waitUntil: 'networkidle0' });
  const dash = await p.evaluate(() => document.body.innerText);
  ok('dashboard shows the monthly lead figures', /New monthly revenue won/.test(dash) && /Time to first contact/.test(dash));
  await shot(p, 'admin-dashboard-1440');
  const id = process.env.ENQ_ID;
  if (id) {
    await p.goto(`${BASE}/admin/enquiries/${id}/edit`, { waitUntil: 'networkidle0' });
    await p.evaluate(() => [...document.querySelectorAll('button')].find((x) => x.closest('[data-field-wrapper]') && /Status|Contacted|New|Quoted/.test(x.innerText))?.scrollIntoView({ block: 'center' }));
    await shot(p, 'admin-enquiry-won-1440', true);
    const t = await p.evaluate(() => document.body.innerText);
    ok('a won enquiry shows once-off and monthly value fields', /Won: monthly/.test(t) && /Won: once-off/.test(t));
  }
  await p.close();
}

console.log(`\n${pass} passed, ${fail} failed`);
await b.close();
