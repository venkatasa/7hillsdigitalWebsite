// AI / WhatsApp / technology copy on STAGING (preview off, so it looks like production).
import puppeteer from 'puppeteer-core';
import { readFileSync } from 'fs';
const axe = readFileSync('/work/node_modules/axe-core/axe.min.js', 'utf8');
const BASE = 'http://7hills-staging-nginx';
const OUT = '/out';
const b = await puppeteer.launch({ executablePath: '/usr/bin/chromium-browser', headless: true, protocolTimeout: 240000, args: ['--no-sandbox', '--disable-gpu', '--no-zygote', '--disable-dev-shm-usage', '--hide-scrollbars'] });
const wait = (ms) => new Promise((r) => setTimeout(r, ms));
let pass = 0, fail = 0;
const ok = (name, cond, extra = '') => { cond ? pass++ : fail++; console.log(cond ? 'PASS' : 'FAIL', name, extra); };
const open = async (path, w) => {
  const p = await b.newPage();
  await p.setViewport({ width: w, height: w < 500 ? 844 : 900, isMobile: w < 500, hasTouch: w < 500 });
  await p.evaluateOnNewDocument(() => { try { localStorage.setItem('shd-cookie-notice-acknowledged', '1'); } catch {} });
  const errors = [];
  p.on('pageerror', (e) => errors.push(e.message));
  p.on('console', (m) => m.type() === 'error' && errors.push(m.text()));
  const r = await p.goto(BASE + path, { waitUntil: 'networkidle0', timeout: 90000 });
  await p.addStyleTag({ content: '[data-reveal]{opacity:1!important;transform:none!important}' });
  await wait(300);
  return { p, errors, status: r.status() };
};
const pages = ['/', '/services', '/services/ai-data-and-automation', '/services/whatsapp-automation', '/services/mobile-application-development', '/services/ai-solutions', '/services/custom-software-development', '/quote?type=mobile-app', '/quote?type=ai-automation'];
const problems = [];
for (const path of pages) for (const w of [1440, 390]) {
  const { p, errors, status } = await open(path, w);
  const over = await p.evaluate(() => document.documentElement.scrollWidth - window.innerWidth);
  await p.addScriptTag({ content: axe });
  const v = await p.evaluate(async () => (await axe.run(document, { runOnly: ['wcag2a', 'wcag2aa', 'wcag21aa', 'wcag22aa', 'best-practice'] })).violations.map((x) => `${x.id}(${x.impact})x${x.nodes.length}: ${x.nodes[0].target}`));
  if (status !== 200 || over > 0 || errors.length || v.length) problems.push(`${path}@${w}: status ${status}, overflow ${over}, js ${errors.join('|')}, axe ${v.join('; ')}`);
  await p.close();
}
ok('9 pages x 2 widths: 200, no overflow, no JS errors, no axe violations', problems.length === 0, problems.join('\n   '));

const shots = [
  ['/', 1440, 'home-1440-hero', null], ['/', 390, 'home-390-hero', null],
  ['/', 1440, 'home-1440-services', '#services'], ['/', 390, 'home-390-services', '#services'],
  ['/', 1440, 'home-1440-technologies', '#technologies'], ['/', 390, 'home-390-technologies', '#technologies'],
  ['/services/whatsapp-automation', 1440, 'whatsapp-1440-fold', null], ['/services/whatsapp-automation', 390, 'whatsapp-390-fold', null],
  ['/services/whatsapp-automation', 1440, 'whatsapp-1440-full', 'full'],
  ['/services/mobile-application-development', 1440, 'mobile-1440-fold', null], ['/services/mobile-application-development', 390, 'mobile-390-fold', null],
  ['/services/ai-solutions', 1440, 'ai-1440-fold', null],
  ['/services', 1440, 'services-1440-fold', null],
];
for (const [path, w, name, target] of shots) {
  const { p } = await open(path, w);
  if (target && target !== 'full') { await p.evaluate((s) => document.querySelector(s).scrollIntoView({ block: 'start' }), target); await wait(400); }
  await p.screenshot({ path: `${OUT}/${name}.webp`, type: 'webp', quality: 80, fullPage: target === 'full' });
  await p.close();
}
ok('screenshots written', true, String(shots.length));
console.log(`\n${pass} passed, ${fail} failed`);
await b.close();
