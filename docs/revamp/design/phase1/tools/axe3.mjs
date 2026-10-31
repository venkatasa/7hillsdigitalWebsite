import puppeteer from 'puppeteer-core';
import { readFileSync } from 'fs';
const axe = readFileSync('/work/node_modules/axe-core/axe.min.js', 'utf8');
const b = await puppeteer.launch({ executablePath: '/usr/bin/chromium-browser', headless: true, protocolTimeout: 180000, args: ['--no-sandbox','--disable-gpu','--no-zygote','--disable-dev-shm-usage'] });
const run = async (p) => { await p.evaluate(axe); return p.evaluate(async () => (await axe.run(document, { runOnly: ['wcag2a','wcag2aa','wcag21aa','wcag22aa','best-practice'] })).violations.map(v => ({ id: v.id, impact: v.impact, n: v.nodes.length, ex: v.nodes.slice(0,3).map(n => n.target.join(' ')).join(' | ') }))); };
for (const [name, w, h, menu] of [['desktop', 1440, 900, false], ['desktop + services menu open', 1440, 900, true], ['tablet', 768, 1024, false], ['mobile', 390, 844, false], ['mobile + sheet open', 390, 844, 'sheet']]) {
  const p = await b.newPage(); await p.setViewport({ width: w, height: h, isMobile: w < 500, hasTouch: w < 500 });
  await p.evaluateOnNewDocument(() => { try { localStorage.setItem('shd-cookie-notice-acknowledged','1') } catch {} });
  await p.goto('http://7hills-staging-nginx/', { waitUntil: 'networkidle0' });
  await p.evaluate(async () => { for (let y = 0; y < document.body.scrollHeight; y += 700) { window.scrollTo({ top: y, behavior: 'instant' }); await new Promise(r => setTimeout(r, 60)); } window.scrollTo({ top: 0, behavior: 'instant' }); });
  await new Promise(r => setTimeout(r, 900));
  if (menu === true) { await p.click('[aria-controls=menu-services]'); await new Promise(r => setTimeout(r, 400)); }
  if (menu === 'sheet') { await p.click('[aria-controls=mobile-sheet]'); await new Promise(r => setTimeout(r, 400)); await p.evaluate(() => document.querySelector('#mobile-sheet button').click()); await new Promise(r => setTimeout(r, 400)); }
  console.log(name, JSON.stringify(await run(p)));
  await p.close();
}
await b.close();
