import puppeteer from 'puppeteer-core';
const b = await puppeteer.launch({ executablePath: '/usr/bin/chromium-browser', headless: true, args: ['--no-sandbox','--disable-gpu','--disable-dev-shm-usage'] });
for (const path of process.argv.slice(2)) {
  const p = await b.newPage();
  await p.setViewport({ width: 390, height: 844, isMobile: true, hasTouch: true });
  const c = await p.target().createCDPSession();
  await c.send('Network.emulateNetworkConditions', { offline: false, latency: 150, downloadThroughput: 200000, uploadThroughput: 90000 });
  await c.send('Emulation.setCPUThrottlingRate', { rate: 4 });
  await p.evaluateOnNewDocument(() => { window.__s = []; new PerformanceObserver((l) => l.getEntries().forEach((e) => window.__s.push({ v: e.value.toFixed(3), t: Math.round(e.startTime), src: e.sources.map((s) => { const n = s.node; return n ? (n.tagName || '#text') + '.' + (n.className?.baseVal ?? n.className ?? '').toString().slice(0, 60) + ' ' + JSON.stringify(s.previousRect) + '->' + JSON.stringify(s.currentRect) : '?'; }) }))).observe({ type: 'layout-shift', buffered: true }); });
  await p.goto('http://7hills-gzip-proxy' + path, { waitUntil: 'networkidle0', timeout: 120000 });
  await new Promise((r) => setTimeout(r, 1500));
  console.log('==', path); console.log(JSON.stringify(await p.evaluate(() => window.__s), null, 1).slice(0, 2500));
  await p.close();
}
await b.close();
