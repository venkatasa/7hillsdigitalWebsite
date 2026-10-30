# Production Safety Checklist — 7Hills Digital

**Prepared:** 30/10/2026  **Status:** Findings verified. Remediation awaits approval.
**Rule:** nothing in the production environment, database, Docker configuration
or live source code (`/opt/docker/7hillsdigital`) changes without explicit approval
of the specific item below.

---

## 1. Verified findings

Every finding below was re-checked read-only on 30/10/2026. "How verified" says
what was actually run.

| # | Finding | Severity | How verified | Evidence |
|---|---|---|---|---|
| F1 | **Live source is bind-mounted read-write** into `7hills-app` (and read-only into `7hills-nginx`). Any edit, `git checkout`, `composer` or `npm run build` in `/opt/docker/7hillsdigital` is live immediately. | High (process) | `docker inspect` | `bind /opt/docker/7hillsdigital -> /var/www/html rw=true` |
| F2 | **Debug mode on a public site.** The runtime is `Environment: local`, `Debug Mode: ENABLED`. The site is public at `https://7hillsdigital.co.za` via Cloudflare. Any unhandled exception renders a debug page that can expose environment values (DB and mail credentials, `APP_KEY`). | **Critical** | `php artisan about` in the live container; `curl https://7hillsdigital.co.za` → 200, `server: cloudflare`, same `<title>` | No error was deliberately triggered on production to prove the leak |
| F3 | **MySQL is open to the network with default passwords.** Port `33063` is published on `0.0.0.0` and `[::]`. Accounts `root@%` and `sevenhills@%` accept connections from any host, and **both passwords are still the compose defaults**. | **Critical** on any untrusted network; High on the LAN | `ss -ltn`; `select user,host from mysql.user`; passwords compared inside the container without printing them | Host firewall state could not be read (needs `sudo`) |
| F4 | **Stale Gitea remote.** `origin` → `192.168.90.164:3000`, which does not answer. Gitea 1.27.1 is live at `192.168.90.215:3000`. `main` is **1 commit ahead** of `origin/main` (`94235bb`), and `revamp` has never been pushed. | Medium | `git rev-list`, `curl /api/v1/version` on both hosts | Repointing was blocked by session permissions; left for the owner |
| F5 | **NEW — off-site backups have failed for 14 days.** The nightly `app-backup.sh` on `.226` dumps `7hills-mysql` (and Excellent's DB), then fails to upload: PBS `192.168.90.160:8007` is unreachable. **Last successful run: 15/10/2026.** This affects **every app on this host**, including Excellent Real Estate. | **Critical** (data protection) | `/var/log/pbs-app-backup.log`; `curl https://192.168.90.160:8007` → no connection | 14 consecutive `!! FAILED` entries, 16/10–29/10 |
| F6 | **NEW — the app runs in UTC, not Africa/Johannesburg.** `config/app.php` hard-codes `'timezone' => 'UTC'`, so `APP_TIMEZONE=Africa/Johannesburg` in `.env` is ignored. Timestamps in `/admin` are 2 h behind, and `ENQ-YYYY` rolls over at 02:00 SAST on 1 January. | Low | `php artisan about` → `Timezone: UTC`; `config/app.php:68` | Contradicts CLAUDE.md |
| F7 | **NEW — transport and header hardening is missing.** Session and XSRF cookies lack `Secure`; there is no HSTS, `X-Frame-Options`, `X-Content-Type-Options` or CSP; `og:url` renders `http://` while the canonical renders `https://` (no trusted-proxy configuration for Cloudflare). | Medium | `curl -I https://7hillsdigital.co.za/`; page source | |
| F8 | **NEW — `/admin/login` is reachable from the internet.** That is normal for Filament, but there is no MFA and no network restriction in front of a back office holding prospects' personal data. | Medium | `curl https://7hillsdigital.co.za/admin/login` → 200 | |
| F9 | `APP_URL=http://localhost:8091`. Wrong for mail links and for any URL generated outside a web request (queues, CLI). | Low | `.env` | |
| F10 | Documentation drift. CLAUDE.md says Laravel 12 (runtime is **13.26.1**) and "not yet pushed to Gitea" (it was pushed 24/08/2026); the homelab doc still lists PBS coverage as "to confirm". | Low | `php artisan about`; `git log origin/main` | |

---

## 2. Safeguards — status

| Safeguard | Status | Detail |
|---|---|---|
| **Verified local backup** | ✅ **Available** | `~/backups/7hillsdigital/20261030-152048/` (mode 700/600): `db-sevenhills.sql.gz` (full dump incl. enquiries, `--single-transaction --routines --triggers`), `repo.bundle` (all branches), `project-files.tar.gz` (project incl. `.env`, `storage/`, untracked images; excludes `vendor/`, `node_modules/`), `SHA256SUMS`. `LATEST` points at it. |
| **Backup restore test** | ✅ **Passed** | Dump restored into a throwaway `mysql:8.0` container. **All 13 tables matched production row for row** (incl. enquiries `ENQ-2026-00001`, `-00002`). The container was then removed. `gzip -t`, `tar -t` and `git bundle verify` all pass. |
| **Off-site backup** | ❌ **Failing since 16/10/2026** | See F5. The local backup above lives on the **same host**, so it does not protect against disk or host loss. |
| **Gitea mirror** | ⚠ **Stale** | See F4. `repo.bundle` covers the gap locally. |
| **Separate Git worktree** | ✅ | `/opt/docker/7hillsdigital-revamp` on branch `revamp` (commit `52a6192`). The live checkout stays on `main`. |
| **Isolated staging** | ✅ | Project `7hills-staging`: own containers, network and volume; `127.0.0.1:8096`; MySQL not published; `MAIL_MAILER=log`; its own `APP_KEY` and DB passwords; production catalogue **without enquiry rows**. Baseline **75 tests / 410 assertions green**. |
| **Rollback procedure** | ✅ Documented | §4 below. It has not been rehearsed on production, by design. |

---

## 3. Remediation plan

Order: data protection first, then exposure, then hardening. Each item is a
**separate approval**. Every production change is preceded by a fresh backup
(§5) and a `.env` copy, and is followed by the verification listed.

### R1 — Restore off-site backups (F5) · *homelab, not this repo*
- **Do:** investigate why PBS `192.168.90.160` is down (VM stopped? IP changed?
  datastore full?). Once it's reachable, run `/opt/pbs-backup/trigger.sh` and confirm
  `proj00-7hillsdigital.pxar` and the `7hills-mysql` dump land in PBS. Check
  why the Telegram failure alert went unnoticed.
- **Interim:** copy `~/backups/7hillsdigital/LATEST` to a second machine or an
  encrypted external disk.
- **Risk:** none to the site. **Needs:** root on `.226` and access to the PBS host.

### R2 — Close MySQL to the network and rotate credentials (F3)
- **Do (in this order):**
  1. Confirm nothing uses `33063` from outside. The backup script uses `docker exec`;
     check for external DB tools.
  2. Generate new strong passwords. In the container: `ALTER USER 'sevenhills'@'%'
     IDENTIFIED BY …`, `ALTER USER 'root'@'localhost' IDENTIFIED BY …`, `DROP USER
     'root'@'%'`. (Compose `MYSQL_*` variables only apply on first init, so the
     change must be made in SQL.)
  3. Update `DB_PASSWORD` / `DB_ROOT_PASSWORD` in `.env`. The app reads `.env` per
     request (there is no config cache).
  4. In `docker-compose.yml`, remove the `ports:` block from `mysql` (or bind it to
     `127.0.0.1:33063`), then `docker compose up -d mysql`. The volume persists;
     expect about 10–20 s of DB downtime.
- **Verify:** `ss -ltn` shows no `0.0.0.0:33063`; the site and `/admin` load; a test
  enquiry on **staging** saves; the next backup run dumps successfully.
- **Rollback:** restore the `.env` copy, restore the compose file from git, and
  re-`ALTER USER` to the old password.
- **Rehearse first on staging.**

### R3 — Production mode, correct URL, secure cookies (F2, F9, part of F7)
- **Do:** in `.env`: `APP_ENV=production`, `APP_DEBUG=false`,
  `APP_URL=https://7hillsdigital.co.za`, `SESSION_SECURE_COOKIE=true`,
  `LOG_LEVEL=warning`. Effective immediately (no config cache).
- **Side effects checked:**
  - `User::canAccessPanel()` already returns `true`, so `/admin` keeps working in
    production.
  - `php artisan migrate` will need `--force` from now on.
  - `public/hot` is absent, so Vite already serves built assets.
  - The tests force their own environment.
- **Rehearse:** put staging in production mode and run the smoke list (§6).
- **Verify:** `php artisan about` shows `production` / `OFF`; cookies carry `Secure`;
  an unknown URL returns the plain 404.
- **Rollback:** restore the `.env` copy (instant).

### R4 — Correct timezone (F6) · *code change via a hotfix branch*
- **Do:** `config/app.php` → `'timezone' => env('APP_TIMEZONE', 'Africa/Johannesburg')`.
- **Data note:** timestamps are stored without a zone. The 2 existing enquiries and
  the sessions were written in UTC and will display 2 h earlier unless converted.
  Recommend a one-off migration that adds 2 h to `enquiries.created_at`,
  `updated_at` and `contacted_at` for existing rows, after a backup.
- **Tests:** add one asserting `config('app.timezone') === 'Africa/Johannesburg'`.

### R5 — Trust Cloudflare and add security headers (F7)
- **Do:**
  - `bootstrap/app.php`: `->withMiddleware(fn ($m) => $m->trustProxies(at:
    [Cloudflare IP ranges], headers: …))` so scheme and host are correct. Do not
    use `'*'`, because the origin is also reachable directly on the LAN.
  - In nginx: `X-Content-Type-Options: nosniff`, `X-Frame-Options: SAMEORIGIN`,
    `Referrer-Policy: strict-origin-when-cross-origin`, `Permissions-Policy`.
  - **HSTS** only after confirming every host name serves HTTPS.
  - A strict CSP (`default-src 'self'`) is achievable because the site loads no
    third-party assets. It needs testing against Filament and Alpine; Alpine needs
    `'unsafe-eval'` unless the CSP build is used.
- **Verify:** `og:url` renders `https://`; headers present; every page passes in staging.

### R6 — Protect the back office (F8)
- **Do (choose one):** (a) **Cloudflare Access** in front of `/admin*` (no code
  change); or (b) Filament v4 **app-based MFA** for staff (a code change, with a
  test); ideally both. Also confirm the admin password is not the seeder-generated
  one.

### R7 — Fix the Gitea remote and push (F4) · *owner action*
```bash
git -C /opt/docker/7hillsdigital remote set-url origin http://gitadmin@192.168.90.215:3000/gitadmin/7hillsdigital.git
```
```bash
git -C /opt/docker/7hillsdigital push origin main revamp
```

### R8 — A safer deployment model (F1)
The bind mount stays for now (changing it is a bigger infrastructure change).
Releases are made **only** as tagged commits through the procedure in §5, never
by editing files in place. Longer term: build an image per release (code baked
in, `vendor/` and `public/build/` included) so a deploy is an image swap and a
rollback is the previous tag.

### R9 — Documentation (F10)
Update CLAUDE.md (Laravel 13, Gitea pushed, timezone fix) and the homelab doc
(PBS status, the off-site gap).

---

## 4. Rollback procedures

**Before any change** (always):
```bash
cp -p /opt/docker/7hillsdigital/.env /opt/docker/7hillsdigital/.env.bak-$(date +%Y%m%d-%H%M)
```
and take a fresh backup (§5).

| Situation | Rollback |
|---|---|
| `.env` change misbehaves | `cp -p .env.bak-<stamp> .env`. Effective on the next request. |
| Code release misbehaves, no migrations | `git -C /opt/docker/7hillsdigital checkout <previous-tag>` → `docker exec 7hills-app composer install --no-dev -o` → `docker exec 7hills-app npm run build` (or restore `public/build` from the backup tar) → `docker exec 7hills-app php artisan optimize:clear` |
| Code release ran **additive** migrations | Same as above. The new nullable columns and tables are harmless to old code and can stay. Only `migrate:rollback --step=N` if they block something. |
| Data damaged | `php artisan down` → restore the dump: `gunzip -c <backup>/db-sevenhills.sql.gz \| docker exec -i 7hills-mysql sh -c 'mysql -uroot -p"$MYSQL_ROOT_PASSWORD" sevenhills'` → `php artisan up`. Enquiries received after the backup must be re-checked against the notification emails. |
| Whole project lost | Extract `project-files.tar.gz` to `/opt/docker/`, restore the DB as above, `docker compose up -d`. Code history: `git clone <backup>/repo.bundle`. |
| Compose change misbehaves | `git checkout docker-compose.yml` → `docker compose up -d` |

---

## 5. Release procedure (every deploy from `revamp`)

1. All tests are green on staging; the smoke list (§6) passes on staging in production mode.
2. Merge to `main` and **tag** (`v2026.11.x`); push to Gitea.
3. **Fresh backup:**
   ```bash
   B=~/backups/7hillsdigital/$(date +%Y%m%d-%H%M%S); mkdir -p $B && chmod 700 $B
   docker exec 7hills-mysql sh -c 'mysqldump -uroot -p"$MYSQL_ROOT_PASSWORD" --single-transaction --routines --triggers --no-tablespaces sevenhills' 2>/dev/null | gzip -9 > $B/db-sevenhills.sql.gz
   git -C /opt/docker/7hillsdigital bundle create $B/repo.bundle --all
   tar -C /opt/docker -czf $B/project-files.tar.gz --exclude='7hillsdigital/vendor' --exclude='7hillsdigital/node_modules' 7hillsdigital
   chmod 600 $B/*; (cd $B && sha256sum * > SHA256SUMS); echo $B > ~/backups/7hillsdigital/LATEST
   ```
4. `cp -p .env .env.bak-<stamp>`
5. `php artisan down --render="errors::503" --retry=60` (only if migrations run)
6. `git fetch && git checkout <tag>` in the live checkout
7. `composer install --no-dev -o`, `npm ci && npm run build`
8. `php artisan migrate --force` (additive migrations only)
9. `php artisan optimize:clear && php artisan up`
10. Smoke list (§6) on production; watch `storage/logs/laravel.log` for 15 minutes.
11. Any failure → §4.

---

## 6. Smoke list

`/`, `/packages`, every `/packages/{slug}`, `/how-it-works`, `/custom-and-it`,
`/demos`, every `/demos/{slug}`, `/about`, `/terms`, `/privacy`, `/contact`,
`/admin/login` → 200. Live prices on `/packages` match `/admin`. **Enquiry submit
is tested on staging only** (production enquiries are real evidence; do not
create test ones). After the redesign, add every new route and `/sitemap.xml`.

---

## 7. Approvals requested

| Item | Touches | Recommended timing |
|---|---|---|
| R1 off-site backup | homelab | **Now** |
| R2 MySQL closure + rotation | production DB, compose, `.env` | **Now** (rehearse on staging first) |
| R3 production mode | production `.env` | **Now** (rehearse on staging first) |
| R4 timezone | code + one data migration | With Phase 1 |
| R5 proxies + headers | code + nginx | With Phase 1 |
| R6 back-office protection | Cloudflare or code | Before launch |
| R7 Gitea | owner command | **Now** |
| R8 image-based deploys | infrastructure | After launch |
| R9 docs | docs | With Phase 1 |
