import puppeteer from 'puppeteer-core';
const b = await puppeteer.launch({ executablePath: '/usr/bin/chromium-browser', headless: true, protocolTimeout: 180000, args: ['--no-sandbox','--disable-gpu','--no-zygote','--disable-dev-shm-usage','--hide-scrollbars'] });
const targets = (process.argv[2] || 'desktop,mobile').split(',');
const sizes = { desktop: [1440, 900, 1], tablet: [820, 1180, 1], mobile: [390, 844, 2] };
for (const t of targets) {
  const [w, h, s] = sizes[t];
  const p = await b.newPage(); await p.setViewport({ width: w, height: h, deviceScaleFactor: s });
  await p.evaluateOnNewDocument(() => { try { localStorage.setItem('shd-cookie-notice-acknowledged', '1'); } catch {} });
  const errors = []; p.on('pageerror', e => errors.push(e.message)); p.on('console', m => m.type() === 'error' && errors.push(m.text()));
  await p.goto('http://7hills-staging-nginx/' + (process.argv[3] || ''), { waitUntil: 'networkidle0', timeout: 90000 });
  await p.evaluate(async () => { for (let y = 0; y < document.body.scrollHeight; y += 600) { window.scrollTo({ top: y, behavior: 'instant' }); await new Promise(r => setTimeout(r, 150)); } window.scrollTo({ top: 0, behavior: 'instant' }); await new Promise(r => setTimeout(r, 800)); });
  await p.addStyleTag({ content: '[data-reveal]{opacity:1!important;transform:none!important}' });
  await new Promise(r => setTimeout(r, 700));
  await p.screenshot({ path: `/out/_review-${t}.png`, fullPage: true });
  await p.screenshot({ path: `/out/_review-${t}-fold.png` });
  const height = await p.evaluate(() => document.documentElement.scrollHeight);
  console.log(t, 'height', height, 'errors', JSON.stringify(errors));
  await p.close();
}
await b.close();
