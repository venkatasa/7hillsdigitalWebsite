import puppeteer from 'puppeteer-core';
const b = await puppeteer.launch({ executablePath: '/usr/bin/chromium-browser', headless: true, args: ['--no-sandbox','--disable-gpu','--no-zygote','--disable-dev-shm-usage'] });
for (const [w,h] of [[390,844],[1440,900]]) {
  const p = await b.newPage(); await p.setViewport({ width: w, height: h, isMobile: w<500, hasTouch: w<500 });
  await p.goto('http://7hills-staging-nginx/', { waitUntil: 'networkidle0' });
  const r = await p.evaluate((h) => [...document.querySelectorAll('main > section, footer')].map(s => (s.id || s.tagName.toLowerCase()) + ':' + (s.getBoundingClientRect().height / h).toFixed(1)).join('  '), h);
  console.log(w, r); await p.close();
}
await b.close();
