import puppeteer from 'puppeteer-core';
import { readFileSync } from 'fs';
const axe = readFileSync('/work/node_modules/axe-core/axe.min.js', 'utf8');
const b = await puppeteer.launch({ executablePath: '/usr/bin/chromium-browser', headless: true, protocolTimeout: 180000, args: ['--no-sandbox','--disable-gpu','--no-zygote','--disable-dev-shm-usage'] });
for (const [name, w, h, s, mobile] of [['desktop', 1440, 900, 1, false], ['mobile', 390, 844, 2, true]]) {
  const p = await b.newPage(); await p.setViewport({ width: w, height: h, deviceScaleFactor: s, isMobile: mobile, hasTouch: mobile });
  const cdp = await p.createCDPSession(); await cdp.send('Network.enable');
  if (mobile) { await cdp.send('Network.emulateNetworkConditions', { offline: false, latency: 150, downloadThroughput: 1.6e6 / 8 * 1024 / 1000 * 1000, uploadThroughput: 750e3 / 8 }); await cdp.send('Emulation.setCPUThrottlingRate', { rate: 4 }); }
  let bytes = 0, reqs = 0, third = []; cdp.on('Network.loadingFinished', e => { bytes += e.encodedDataLength; reqs++; });
  p.on('request', r => { const u = new URL(r.url()); if (!['7hills-staging-nginx'].includes(u.hostname) && !u.protocol.startsWith('data')) third.push(u.hostname); });
  await p.evaluateOnNewDocument(() => { window.__lcp = 0; window.__cls = 0; new PerformanceObserver(l => { for (const e of l.getEntries()) window.__lcp = e.startTime; window.__lcpEl = l.getEntries().at(-1)?.element?.tagName; }).observe({ type: 'largest-contentful-paint', buffered: true }); new PerformanceObserver(l => { for (const e of l.getEntries()) if (!e.hadRecentInput) window.__cls += e.value; }).observe({ type: 'layout-shift', buffered: true }); try { localStorage.setItem('shd-cookie-notice-acknowledged','1') } catch {} });
  await p.goto('http://7hills-staging-nginx/', { waitUntil: 'load', timeout: 120000 });
  await new Promise(r => setTimeout(r, 2500));
  const m = await p.evaluate(() => ({ lcp: Math.round(window.__lcp), lcpEl: window.__lcpEl, cls: +window.__cls.toFixed(3), domNodes: document.getElementsByTagName('*').length, html: document.documentElement.outerHTML.length }));
  console.log(name, mobile ? '(slow 4G + 4x CPU)' : '(no throttling)', JSON.stringify({ ...m, transferredKB: Math.round(bytes / 1024), requestsAtLoad: reqs, thirdParty: [...new Set(third)] }));
  if (name === 'desktop') {
    await p.evaluate(axe);
    const r = await p.evaluate(async () => { const r = await axe.run(document, { runOnly: ['wcag2a', 'wcag2aa', 'wcag21aa', 'best-practice'] }); return r.violations.map(v => ({ id: v.id, impact: v.impact, n: v.nodes.length, ex: v.nodes.slice(0, 2).map(n => n.target.join(' ')).join(' | ') })); });
    console.log('axe violations:', JSON.stringify(r, null, 1));
  }
  await p.close();
}
await b.close();
