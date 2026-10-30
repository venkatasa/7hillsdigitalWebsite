// Captures genuine screenshots of the STAGING site for public/images/product/.
// Run inside a throwaway container on the staging network only:
//   docker run -d --name 7hills-shooter --user root --network 7hills-staging_staging -v <out>:/out -v <work>:/work nginx:1.27-alpine sleep 3600
//   docker exec 7hills-shooter sh -c "apk add --no-cache chromium nodejs npm font-noto && cd /work && npm i puppeteer-core@24.10.0"
//   docker exec -e PREVIEW_PASSWORD=... 7hills-shooter node /work/shoot.mjs
// Then update public/images/product/manifest.json. Never point this at production or a client back office.
import puppeteer from 'puppeteer-core';

const BASE = 'http://7hills-staging-nginx';
const OUT = '/out';
const PREVIEW_PASSWORD = process.env.PREVIEW_PASSWORD;

const hideChrome = `
  .wa-fab { display: none !important; }
  [x-data*="cookieNotice"], [x-show*="cookieNotice"] { display: none !important; }
  [data-reveal] { opacity: 1 !important; transform: none !important; }
`;

const browser = await puppeteer.launch({
    executablePath: '/usr/bin/chromium-browser',
    headless: true, protocolTimeout: 120000, args: ['--no-sandbox', '--disable-gpu', '--no-zygote', '--disable-dev-shm-usage', '--hide-scrollbars'],
});

async function shot(path, file, { width = 1440, height = 900, scale = 1, full = false, prep } = {}) {
    const page = await browser.newPage();
    await page.setViewport({ width, height, deviceScaleFactor: scale });
    await page.evaluateOnNewDocument(() => {
        try { localStorage.setItem('shd-cookie-notice-acknowledged', '1'); } catch {}
    });
    await page.goto(BASE + path, { waitUntil: 'networkidle0', timeout: 60000 });
    await page.addStyleTag({ content: hideChrome });
    if (prep) await prep(page);
    await new Promise((r) => setTimeout(r, 400));
    await page.screenshot({ path: `${OUT}/${file}.webp`, type: 'webp', quality: 82, fullPage: full });
    await page.close();
    console.log('ok', file);
}

// Public demo tenants — genuine pages, fictional businesses.
for (const slug of ['realty', 'auto', 'careers', 'stay', 'plant']) {
    await shot(`/demos/${slug}`, `demo-${slug}-desktop`, { width: 1440, height: 900 });
    await shot(`/demos/${slug}`, `demo-${slug}-mobile`, { width: 390, height: 844, scale: 2 });
}

// Back office — staging install, fictional data only.
const page = await browser.newPage();
await page.setViewport({ width: 1440, height: 900 });
await page.goto(BASE + '/admin/login', { waitUntil: 'networkidle0' });
await page.type('input[type="email"]', 'preview@staging.test');
await page.type('input[type="password"]', PREVIEW_PASSWORD);
await Promise.all([page.waitForNavigation({ waitUntil: 'networkidle0' }), page.keyboard.press('Enter')]);
const cookies = await page.cookies();
await page.close();

async function adminShot(path, file, opts = {}) {
    await shot(path, file, {
        ...opts,
        prep: async (p) => {
            if (opts.prep) await opts.prep(p);
        },
    });
}

// Re-use the session for each admin page.
const origNewPage = browser.newPage.bind(browser);
browser.newPage = async () => {
    const p = await origNewPage();
    await p.setCookie(...cookies);
    return p;
};

await adminShot('/admin', 'admin-dashboard');
await adminShot('/admin/enquiries', 'admin-enquiries');
await adminShot('/admin/packages', 'admin-packages');
await adminShot('/admin/add-ons', 'admin-add-ons');
await adminShot('/admin/enquiries', 'admin-enquiries-mobile', { width: 390, height: 844, scale: 2 });

await browser.close();
