// Retake full-page screenshots with lazy images forced to load first.
import puppeteer from 'puppeteer-core';
const b = await puppeteer.launch({ executablePath: '/usr/bin/chromium-browser', headless: true, args: ['--no-sandbox', '--disable-gpu', '--no-zygote', '--disable-dev-shm-usage', '--hide-scrollbars'] });
for (const [path, w, s] of process.argv.slice(2).map((a) => a.split(':')).map(([p, w, s]) => [p, +w, +s])) {
  const p = await b.newPage();
  await p.setViewport({ width: w, height: 900, deviceScaleFactor: s, isMobile: w < 500, hasTouch: w < 500 });
  await p.evaluateOnNewDocument(() => { try { localStorage.setItem('shd-cookie-notice-acknowledged', '1'); } catch {} });
  await p.goto('http://7hills-staging-nginx' + path, { waitUntil: 'networkidle0' });
  await p.evaluate(() => document.querySelectorAll('img[loading=lazy]').forEach((i) => (i.loading = 'eager')));
  await p.evaluate(async () => { for (let y = 0; y < document.body.scrollHeight; y += 500) { window.scrollTo(0, y); await new Promise((r) => setTimeout(r, 120)); } window.scrollTo(0, 0); });
  await p.evaluate(async () => Promise.all([...document.images].map((i) => i.complete ? 0 : new Promise((r) => { i.onload = i.onerror = r; }))));
  await p.addStyleTag({ content: '[data-reveal]{opacity:1!important;transform:none!important}' });
  await new Promise((r) => setTimeout(r, 400));
  const name = path.slice(1).replace(/\//g, '-');
  await p.screenshot({ path: `/out/${name}-${w}-full.${s > 1 ? 'jpg' : 'webp'}`, type: s > 1 ? 'jpeg' : 'webp', quality: 80, fullPage: true });
  await p.close();
}
await b.close();
