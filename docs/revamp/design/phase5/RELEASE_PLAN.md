# Release plan: the revamp to production

**Status:** prepared on staging, 31/10/2026; blockers approved 02/11/2026 (see §1). **Nothing here has been run on
production.** Every step below needs the owner's explicit go-ahead, and the
blockers in §1 must be cleared first.

## 1. Blockers: must be true before release day

The implementation plan makes two of these hard rules ("Launch requires R3 and R1").

| # | Blocker | Why it blocks | Who |
|---|---|---|---|
| R1 | Off-site backups working again (failing since 16/10/2026) | The release migrates the live database. The only backup today is on the same host. | Owner, homelab |
| R3 | Production mode: `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://7hillsdigital.co.za`, secure cookies | Debug is on for a public site. It also matters to the revamp: in `local` mode, unconfirmed services are **not** hidden by environment, only by the preview switch. | Approval, then a `.env` change |
| M2 | Capability matrix filled in (`full-service/06`) | It decides which services, solutions and quote types production shows. Empty means six lines, the "Automation" headline, five solutions. That is safe but thin. | Owner |
| — | Attorney sign-off on the privacy changes (clauses 3, 4 and 10) | `/quote` and attribution are described there; they must not go live ahead of the notice. | Owner, attorney |
| R7 | Gitea remote fixed and `revamp` pushed | The release is tagged and pushed before deploy, so a rollback target exists off this host. | Owner |

### Blocker status, 02/11/2026

The owner approved clearing all five blockers on 02/11/2026. Where each stands:

| # | Status | What is left |
|---|---|---|
| R1 | **Still blocked.** PBS `192.168.90.160` does not answer ping or port 8007. A fresh local backup was taken 02/11 06:14 (`~/backups/7hillsdigital/LATEST`, `SHA256SUMS` written), but it is on the same host. | Bring PBS back (Proxmox console), run `sudo /opt/pbs-backup/trigger.sh`, and confirm the 7hillsdigital archive lands. Interim: copy the `LATEST` folder to a second machine or encrypted disk. **Launch requires R1.** |
| R3 | **Approved, not applied.** Claude's permission settings refused the production `.env` edit, so the owner runs it (below). Rehearsed on staging 31/10. | Commands below. |
| M2 | **Owner accepted the default.** With the matrix empty, production shows the six confirmed lines, the "Automation" headline and five solutions; unconfirmed services stay 404. | Nothing, unless more services are confirmed later. |
| Attorney | **Owner accepted the risk** of going live before attorney review of privacy clauses 3, 4 and 10. CLAUDE.md open decision 2 still stands. | Attorney review, as soon as practical. |
| R7 | **Approved, not applied.** Gitea answers on `.215:3000`; the remote still points at the old `.164`. The push needs the token, which the owner holds. | Commands below. |

Owner commands, in order (each is reversible):

```bash
# R3: production mode. Restore the .env copy to undo.
cd /opt/docker/7hillsdigital && cp -p .env .env.bak-$(date +%Y%m%d-%H%M%S)
sed -i -E 's#^APP_ENV=.*#APP_ENV=production#; s#^APP_DEBUG=.*#APP_DEBUG=false#; s#^APP_URL=.*#APP_URL=https://7hillsdigital.co.za#; s#^LOG_LEVEL=.*#LOG_LEVEL=warning#' .env
grep -q '^SESSION_SECURE_COOKIE=' .env || printf '\nSESSION_SECURE_COOKIE=true\n' >> .env
docker exec 7hills-app php artisan config:clear
docker exec 7hills-app php artisan about --only=environment
```

After R3, `/admin` only signs in over **https://7hillsdigital.co.za** (the session
cookie is `Secure`); plain `http://192.168.90.226:8091/admin` will loop back to the
login page. That is intended.

```bash
# R7: point at the live Gitea and push both branches (prompts for the token).
git -C /opt/docker/7hillsdigital remote set-url origin http://gitadmin@192.168.90.215:3000/gitadmin/7hillsdigital.git
git -C /opt/docker/7hillsdigital push origin main revamp
```

Recommended alongside, not blocking: R2 (MySQL closed to the network), R4 (timezone),
R5 (Cloudflare proxy trust and security headers), R6 (back-office protection).

## 2. What the release changes

| Area | Change | Reversible by |
|---|---|---|
| Code | Merge `revamp` into `main`, tag `v2026.11.0` | `git checkout <previous tag>` |
| Database | Three **additive** migrations: project and attribution columns on `enquiries`, the `enquiry_events` table, outcome columns. No existing column changes; old code runs against the new schema. | Leave in place (harmless), or `migrate:rollback --step=3` |
| `.env` | `REVAMP_HOME_V2=true`, then `REVAMP_LEADS=true`. **Never** `REVAMP_PREVIEW_UNCONFIRMED` | Set back to `false` |
| Assets | `npm run build` (two preloaded fonts instead of nine) | Rebuild at the previous tag |
| `robots.txt` | Adds `Disallow: /admin` and the sitemap line | Previous tag |

## 3. Order on the day

The safety checklist §5, with the revamp's switches in a deliberate order: the
schema first, then the pages, then the new enquiry flow. Each switch can be
turned off on its own.

1. Staging green in production mode (done 31/10: see `PHASE5_REPORT.md` §3).
2. Merge `revamp` → `main`, tag, push to Gitea.
3. Fresh backup per checklist §5 step 3; confirm `SHA256SUMS`, and confirm R1 shipped it off-site.
4. `cp -p .env .env.bak-<stamp>`
5. `php artisan down --render="errors::503" --retry=60`
6. `git stash push -m "pre-release local edits"` first. The live checkout carries
   uncommitted edits to `CLAUDE.md`, `config/clients.php`, `home.blade.php` and
   `LiveWorkTest.php` (the InvoiceShelf fix). Checked 02/11: every one is already in
   `revamp`, so stashing loses nothing, but without it the checkout refuses to run.
   The untracked `Complete Technology solutions.jpg` is not touched.
   Then `git fetch && git checkout v2026.11.0`.
7. `composer install --no-dev -o` and `npm ci && npm run build`
8. `php artisan migrate --force` (expect exactly the three migrations dated 2026_10_31)
9. Set `REVAMP_HOME_V2=true`, keeping `REVAMP_LEADS=false`; `php artisan optimize:clear && php artisan up`
10. Smoke: every URL in `/sitemap.xml` and `/admin/login` → 200; `/services/microsoft-365` → 404
    (gated); live prices on `/packages` match `/admin`. **No test enquiry on production.**
11. Set `REVAMP_LEADS=true`; `php artisan optimize:clear`. Check `/quote` → 200 and that
    the "Get a quote" links now point at it.
12. Watch `storage/logs/laravel.log` for 15 minutes.
13. Owner: submit `/sitemap.xml` in Search Console; update the Business Profile link if needed.

Any failure: checklist §4. Switch off `REVAMP_LEADS`, then `REVAMP_HOME_V2`, and the
site is back to today's pages on the new code. A full code rollback is only needed
if that is not enough.

## 4. The 30-day watch

From the panel and logs, weekly:
- enquiries by type and priority (dashboard "This month"), and time to first contact;
- `404`s in `laravel.log` (old links pointing at moved pages);
- Search Console coverage: the sitemap's URLs indexed, none excluded as "not found".
