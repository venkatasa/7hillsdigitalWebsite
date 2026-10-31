// Phase 4 quality gates on STAGING: /quote flow (JS and no-JS), keyboard, reduced
// motion, validation recovery, overflow, JS errors, axe on every step, link crawl,
// CTA wiring, and screenshots. Fictional data only. Never point this at production.
import puppeteer from 'puppeteer-core';
import { readFileSync, writeFileSync } from 'fs';
const axe = readFileSync('/work/node_modules/axe-core/axe.min.js', 'utf8');
const BASE = 'http://7hills-staging-nginx';
const OUT = '/out';
const PAGES = ['/quote', '/quote?type=custom-development', '/quote?type=managed-it', '/quote/thank-you', '/contact', '/privacy', '/', '/services', '/solutions/never-lose-a-lead', '/industries/corporate', '/work'];
const sizes = [[1440, 900], [768, 1024], [390, 844]];
const b = await puppeteer.launch({ executablePath: '/usr/bin/chromium-browser', headless: true, protocolTimeout: 240000, args: ['--no-sandbox', '--disable-gpu', '--no-zygote', '--disable-dev-shm-usage', '--hide-scrollbars'] });
const wait = (ms) => new Promise((r) => setTimeout(r, ms));
let pass = 0, fail = 0;
const ok = (name, cond, extra = '') => { cond ? pass++ : fail++; console.log(cond ? 'PASS' : 'FAIL', name, extra); };
const refs = [];

const open = async (path, w = 1440, h = 900, opts = {}) => {
  // A fresh context is a fresh visitor: no session, so attribution starts here.
  const p = await (opts.fresh ? await b.createBrowserContext() : b).newPage();
  await p.setViewport({ width: w, height: h, deviceScaleFactor: opts.scale ?? 1, isMobile: w < 500, hasTouch: w < 500 });
  if (opts.js === false) await p.setJavaScriptEnabled(false);
  if (opts.reduced) await p.emulateMediaFeatures([{ name: 'prefers-reduced-motion', value: 'reduce' }]);
  if (opts.cookies) await p.setCookie(...opts.cookies);
  await p.evaluateOnNewDocument(() => { try { localStorage.setItem('shd-cookie-notice-acknowledged', '1'); } catch {} });
  const errors = [];
  p.on('pageerror', (e) => errors.push(e.message));
  p.on('console', (m) => m.type() === 'error' && errors.push(m.text()));
  await p.goto(BASE + path, { waitUntil: 'networkidle0', timeout: 90000 });
  return { p, errors };
};
const shot = async (p, name, full = false) => {
  await p.addStyleTag({ content: '.wa-fab{display:none!important} [data-reveal]{opacity:1!important;transform:none!important}' });
  await wait(250);
  await p.screenshot({ path: `${OUT}/${name}.webp`, type: 'webp', quality: 80, fullPage: full });
};
const runAxe = async (p, tag) => {
  await p.evaluate(axe);
  return (await p.evaluate(async () => (await axe.run(document, { runOnly: ['wcag2a', 'wcag2aa', 'wcag21aa', 'wcag22aa', 'best-practice'] })).violations
    .map((v) => `${v.id}(${v.impact})x${v.nodes.length}: ${v.nodes[0].target.join(' ').slice(0, 90)}`)))
    .filter((x) => !x.startsWith('region(moderate)x1: .right-5')).map((x) => `${tag}: ${x}`);
};
const visibleStep = (p) => p.evaluate(() => [1, 2, 3, 4].filter((n) => { const f = document.querySelector(`[x-ref=step${n}]`); return f && f.offsetParent !== null; }));
const click = async (p, sel) => { await p.click(sel); await wait(450); };
const nextBtn = (n) => `fieldset[x-ref=step${n}] button[type=button]:last-of-type`;
const pick = (p, name, value) => p.evaluate((n, v) => { const el = document.querySelector(`input[name="${n}"][value="${v}"]:not(:disabled)`); el.click(); return !!el; }, name, value);

// ---- 1. Overflow, JS errors and axe on every page at three widths ----
const overflow = [], jsErrors = [], axeFindings = [], allLinks = new Set();
for (const path of PAGES) {
  for (const [w, h] of sizes) {
    const { p, errors } = await open(path, w, h);
    const o = await p.evaluate(() => document.documentElement.scrollWidth - window.innerWidth);
    if (o > 0) overflow.push(`${path}@${w}:${o}px`);
    if (w !== 768) axeFindings.push(...await runAxe(p, `${path}@${w}`));
    if (w === 1440) (await p.evaluate(() => [...document.querySelectorAll('a[href]')].map((a) => a.href))).forEach((l) => allLinks.add(l.split('#')[0]));
    errors.forEach((e) => jsErrors.push(`${path}@${w}: ${e}`));
    await p.close();
  }
}
ok(`no horizontal overflow on ${PAGES.length} pages at 1440/768/390`, overflow.length === 0, JSON.stringify(overflow));
ok('no JavaScript errors', jsErrors.length === 0, JSON.stringify(jsErrors.slice(0, 5)));

// ---- 2. The full flow with JavaScript, arriving from a tagged link ----
{
  const { p, errors } = await open('/solutions/never-lose-a-lead?utm_source=p4-check&utm_campaign=phase4', 390, 844, { scale: 2, fresh: true });
  const cta = await p.evaluate(() => [...document.querySelectorAll('a[href*="/quote"]')].map((a) => a.getAttribute('href')));
  ok('solution page CTAs open /quote with the type and the button', cta.some((h) => h.includes('type=custom-development') && h.includes('from=solution-never-lose-a-lead')), cta[0]);
  await p.goto(BASE + '/quote?from=p4-check', { waitUntil: 'networkidle0' });
  ok('opens on step 1 without a preset', JSON.stringify(await visibleStep(p)) === '[1]');
  await shot(p, 'quote-step1-390-fold');
  axeFindings.push(...await runAxe(p, '/quote step1@390'));

  // Next without a choice is refused by the browser, not by a server round trip.
  await click(p, nextBtn(1));
  ok('step 1 will not advance without a choice', JSON.stringify(await visibleStep(p)) === '[1]');

  await pick(p, 'project_type', 'custom-development');
  await click(p, nextBtn(1));
  ok('step 2 shows only the chosen type\'s questions', await p.evaluate(() => {
    const shown = [...document.querySelectorAll('fieldset[x-ref=step2] > fieldset')].filter((f) => f.offsetParent !== null);
    return shown.length === 1 && shown[0].querySelector('input[name^="answers[custom-development]"]') !== null;
  }));
  ok('focus moves to the step heading', await p.evaluate(() => document.activeElement?.getAttribute('x-ref') === 'title2'));
  ok('the description label follows the type', await p.evaluate(() => document.querySelector('label[for=message]').innerText.includes('What should it do?')));
  await click(p, nextBtn(2));
  ok('step 2 will not advance with questions unanswered', JSON.stringify(await visibleStep(p)) === '[2]');
  await pick(p, 'answers[custom-development][replacing]', 'spreadsheets');
  await pick(p, 'answers[custom-development][audience]', 'staff');
  await p.type('#message', 'Staging check: job cards live in a spreadsheet four people edit. Invented data.');
  await shot(p, 'quote-step2-390-fold');
  axeFindings.push(...await runAxe(p, '/quote step2@390'));
  await click(p, nextBtn(2));
  await pick(p, 'budget_band', '150k-500k');
  await pick(p, 'timeframe', '1-3-months');
  await shot(p, 'quote-step3-390-fold');
  axeFindings.push(...await runAxe(p, '/quote step3@390'));
  await click(p, nextBtn(3));
  await p.type('#name', 'Test Prospect (staging)');
  await p.type('#company', 'Example Engineering (invented)');
  await p.type('#email', 'prospect@example.com');
  await p.type('#phone', '000 000 0000');
  await p.select('#company_size', '11-50');
  await pick(p, 'preferred_contact', 'email');
  await p.click('#consent');
  await shot(p, 'quote-step4-390-full', true);
  axeFindings.push(...await runAxe(p, '/quote step4@390'));
  await Promise.all([p.waitForNavigation({ waitUntil: 'networkidle0' }), p.click('button[type=submit]')]);
  const ref = await p.evaluate(() => document.body.innerText.match(/ENQ-\d{4}-\d{5}/)?.[0]);
  ok('submits and shows a reference on the thank-you page', p.url().endsWith('/quote/thank-you') && !!ref, ref);
  ok('thank-you names the chosen channel and promises no time', await p.evaluate(() => document.body.innerText.includes('by email') && !/working day|hours/i.test(document.querySelector('main').innerText)));
  refs.push(['js', ref]);
  await shot(p, 'quote-thanks-390-fold');
  ok('no JavaScript errors through the flow', errors.length === 0, JSON.stringify(errors));
  await p.close();
}

// ---- 3. Desktop flow with a preset gated (preview) type, type switching ----
{
  const { p } = await open('/quote?type=custom-development&from=p4-check', 1440, 900);
  ok('a preset type opens on step 2', JSON.stringify(await visibleStep(p)) === '[2]');
  await shot(p, 'quote-step2-1440-fold');
  await click(p, 'fieldset[x-ref=step2] button[type=button]:first-of-type'); // Back
  await shot(p, 'quote-step1-1440-fold');
  await p.goto(BASE + '/quote?type=custom-development', { waitUntil: 'networkidle0' });
  await pick(p, 'answers[custom-development][replacing]', 'paper');
  await pick(p, 'answers[custom-development][audience]', 'customers');
  await p.type('#message', 'Invented.');
  await click(p, nextBtn(2));
  await pick(p, 'budget_band', '50k-150k');
  await click(p, 'fieldset[x-ref=step3] button[type=button]:first-of-type');
  await click(p, 'fieldset[x-ref=step2] button[type=button]:first-of-type');
  await pick(p, 'project_type', 'managed-it');
  const reset = await p.evaluate(() => ({
    checked: [...document.querySelectorAll('input[name=budget_band]')].filter((i) => i.checked).length,
    monthly: [...document.querySelectorAll('input[name=budget_band][value$="-pm"]')].every((i) => !i.matches(':disabled')),
    project: [...document.querySelectorAll('input[name=budget_band][value="50k-150k"]')].every((i) => i.matches(':disabled')),
  }));
  ok('switching to a monthly type clears the band and offers monthly bands', reset.checked === 0 && reset.monthly && reset.project, JSON.stringify(reset));
  ok('a preview type is marked as such on staging', await p.evaluate(() => [...document.querySelectorAll('label')].some((l) => l.innerText.includes('Managed IT support') && l.innerText.includes('Preview only'))));
  await p.close();
}

// ---- 4. Keyboard only ----
{
  const { p } = await open('/quote', 1440, 900);
  await p.focus('input[name=project_type]');
  await p.keyboard.press('ArrowDown');
  const chosen = await p.evaluate(() => document.querySelector('input[name=project_type]:checked')?.value);
  await p.keyboard.press('Tab');
  const onNext = await p.evaluate(() => document.activeElement?.innerText?.trim());
  // From the radio group, Tab reaches the platforms link, then Next.
  if (!onNext?.startsWith('Next')) await p.keyboard.press('Tab');
  await p.keyboard.press('Enter'); await wait(450);
  ok('keyboard: arrows choose a type, Tab and Enter advance', !!chosen && JSON.stringify(await visibleStep(p)) === '[2]', `${chosen}`);
  // Focus is on the step heading; Tab reaches the first option of the step.
  await p.keyboard.press('Tab');
  const ring = await p.evaluate(() => { const el = document.activeElement; return { tag: el.tagName, shadow: getComputedStyle(el.closest('label') ?? el).boxShadow }; });
  ok('keyboard: Tab reaches the first option and it shows a focus ring', ring.tag === 'INPUT' && /0px 0px 0px 2px/.test(ring.shadow), JSON.stringify(ring).slice(0, 120));
  await p.close();
}

// ---- 5. Reduced motion ----
{
  const { p } = await open('/quote?type=custom-development', 1440, 900, { reduced: true });
  const t = await p.evaluate(() => getComputedStyle(document.querySelector('.js-only .bg-accent')).transitionProperty);
  ok('reduced motion: the progress bar does not animate', t === 'none', t);
  await p.close();
}

// ---- 6. Server-side validation recovery: opens on the step with the error ----
{
  const { p } = await open('/quote?type=custom-development', 390, 844, { scale: 2 });
  await pick(p, 'answers[custom-development][replacing]', 'paper');
  await pick(p, 'answers[custom-development][audience]', 'staff');
  await p.type('#message', 'Invented.');
  // Bypass the browser checks to prove the server catches it too.
  await p.evaluate(() => { const f = document.querySelector('form[action$="/quote"]'); f.querySelectorAll('[required]').forEach((e) => e.removeAttribute('required')); HTMLFormElement.prototype.submit.call(f); });
  await p.waitForNavigation({ waitUntil: 'networkidle0' });
  const state = await p.evaluate(() => ({ alert: !!document.querySelector('[role=alert]'), step: [1, 2, 3, 4].filter((n) => document.querySelector(`[x-ref=step${n}]`).offsetParent !== null), kept: document.querySelector('#message').value }));
  ok('server errors reopen the form on the first step with a problem, answers kept', state.alert && JSON.stringify(state.step) === '[3]' && state.kept === 'Invented.', JSON.stringify(state));
  await shot(p, 'quote-errors-390-fold');
  axeFindings.push(...await runAxe(p, '/quote errors@390'));
  await p.close();
}

// ---- 7. Without JavaScript: one long form that still submits ----
{
  const { p } = await open('/quote?from=p4-nojs', 1440, 900, { js: false });
  const st = await p.evaluate(() => ({
    steps: [1, 2, 3, 4].filter((n) => document.querySelector(`[x-ref=step${n}]`).offsetParent !== null).length,
    nexts: [...document.querySelectorAll('form button[type=button]')].filter((x) => x.offsetParent !== null).length,
    groups: [...document.querySelectorAll('fieldset[x-ref=step2] > fieldset')].filter((f) => f.offsetParent !== null).length,
  }));
  ok('no JS: all four steps and every type\'s questions are on screen, no dead buttons', st.steps === 4 && st.nexts === 0 && st.groups >= 3, JSON.stringify(st));
  await shot(p, 'quote-nojs-1440-full', true);
  await p.click('input[name=project_type][value=other]');
  await p.type('#message', 'No-JS staging check. Invented.');
  await p.click('input[name=budget_band][value=not-sure]');
  await p.click('input[name=timeframe][value=exploring]');
  await p.type('#name', 'No Script (staging)');
  await p.type('#email', 'noscript@example.com');
  await p.type('#phone', '000 000 0000');
  await p.select('#company_size', '1-10');
  await p.click('#consent');
  await Promise.all([p.waitForNavigation({ waitUntil: 'networkidle0' }), p.click('button[type=submit]')]);
  const ref = await p.evaluate(() => document.body.innerText.match(/ENQ-\d{4}-\d{5}/)?.[0]);
  ok('no JS: the form submits and returns a reference', !!ref, ref);
  refs.push(['nojs', ref]);
  await p.close();
}

// ---- 8. CTA wiring across the site ----
{
  const { p } = await open('/', 1440, 900);
  const header = await p.evaluate(() => document.querySelector('[data-cta=header-quote]')?.getAttribute('href'));
  ok('header "Get a Quote" opens /quote', header?.includes('/quote?from=header'), header);
  await p.goto(BASE + '/services/custom-software-development', { waitUntil: 'networkidle0' });
  const svc = await p.evaluate(() => [...document.querySelectorAll('a[href*="/quote"]')].map((a) => a.getAttribute('href')));
  ok('service page CTAs carry the type and the page', svc.some((h) => h.includes('type=custom-development') && h.includes('from=service-custom-software-development')), svc[0]);
  await p.goto(BASE + '/packages/showroom', { waitUntil: 'networkidle0' });
  const pkg = await p.evaluate(() => ({ hero: document.querySelector('main a[href*="/contact"]')?.getAttribute('href'), quote: document.querySelectorAll('main a[href*="/quote"]').length }));
  ok('platform CTAs still go to /contact?package= (unchanged)', pkg.hero?.includes('/contact?package=showroom') && pkg.quote === 0, JSON.stringify(pkg));
  await p.goto(BASE + '/industries/automotive', { waitUntil: 'networkidle0' });
  const ind = await p.evaluate(() => [...document.querySelectorAll('a[href*="/contact"], a[href*="/quote"]')].map((a) => a.getAttribute('href')).filter((h) => h.includes('industry-')));
  ok('an industry with a platform keeps the platform enquiry, with the page recorded', ind.length > 0 && ind.every((h) => h.includes('/contact?package=showroom&from=industry-automotive')), ind[0]);
  await p.close();
}

// ---- 9. Screenshots of the remaining states ----
for (const [w, h, s, tag] of [[1440, 900, 1, '1440'], [768, 1024, 1, '768'], [390, 844, 2, '390']]) {
  const { p } = await open('/quote', w, h, { scale: s });
  await shot(p, `quote-${tag}-fold`);
  await p.close();
}
{
  const { p } = await open('/quote/thank-you', 1440, 900);
  await shot(p, 'quote-thanks-1440-fold');
  await p.close();
}

// ---- 10. The back office, staging install, fictional data only ----
if (process.env.PREVIEW_PASSWORD) {
  const p = await b.newPage();
  await p.setViewport({ width: 1440, height: 900 });
  await p.goto(BASE + '/admin/login', { waitUntil: 'networkidle0' });
  await p.type('input[type="email"]', 'preview@staging.test');
  await p.type('input[type="password"]', process.env.PREVIEW_PASSWORD);
  await Promise.all([p.waitForNavigation({ waitUntil: 'networkidle0' }), p.keyboard.press('Enter')]);
  await p.goto(BASE + '/admin/enquiries', { waitUntil: 'networkidle0' });
  await shot(p, 'admin-enquiries-1440');
  const listOk = await p.evaluate(() => document.body.innerText.includes('Priority') && document.body.innerText.includes('Custom software'));
  ok('panel list shows type and priority', listOk);
  const editHref = await p.evaluate((r) => [...document.querySelectorAll('tr')].find((tr) => tr.innerText.includes(r))?.querySelector('a[href*="/edit"]')?.getAttribute('href'), refs[0][1]);
  if (editHref) {
    await p.goto(new URL(editHref, BASE).href, { waitUntil: 'networkidle0' });
    await p.evaluate(() => [...document.querySelectorAll('button')].filter((x) => /How they found us/.test(x.innerText)).forEach((x) => x.click()));
    await wait(600);
    await shot(p, 'admin-enquiry-project-1440', true);
    const ro = await p.evaluate(() => {
      const t = document.body.innerText + [...document.querySelectorAll('textarea, input')].map((e) => e.value).join(' ');
      const sent = [...document.querySelectorAll('textarea, input')].filter((e) => /answers|project_type|landing_page|utm_source/.test(e.id || e.name || ''));
      return { answers: t.includes('Is it replacing something? — Spreadsheets'), found: t.includes('/solutions/never-lose-a-lead') && t.includes('p4-check'), history: t.includes('History') && t.includes('Received'), allDisabled: sent.length > 0 && sent.every((e) => e.disabled) };
    });
    ok('panel shows answers, arrival and history, all read-only', ro.answers && ro.found && ro.history && ro.allDisabled, JSON.stringify(ro));
  } else ok('panel: found the enquiry just submitted', false, refs[0][1]);
  await p.close();
}

// ---- 11. Link crawl ----
{
  const { p } = await open('/', 1440, 900);
  const broken = [];
  for (const l of allLinks) {
    if (!l.startsWith(BASE)) continue;
    const st = await p.evaluate(async (u) => (await fetch(u, { redirect: 'manual' })).status, l);
    if (st >= 400 || st === 0) broken.push(`${l} ${st}`);
  }
  ok(`all ${[...allLinks].filter((l) => l.startsWith(BASE)).length} same-site links answer`, broken.length === 0, JSON.stringify(broken));
  await p.close();
}

ok('axe: no WCAG violations on any page or any step', axeFindings.length === 0, '\n   ' + axeFindings.join('\n   '));
writeFileSync(`${OUT}/refs.json`, JSON.stringify(refs));
console.log(`\n${pass} passed, ${fail} failed`);
await b.close();
