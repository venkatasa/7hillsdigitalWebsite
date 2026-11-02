#!/usr/bin/env bash
# Release the revamp to production: RELEASE_PLAN.md §3 as one script.
#
#   bash docs/revamp/design/phase5/release.sh            # the real release
#   bash docs/revamp/design/phase5/release.sh --rehearse # against staging, no git, no gates
#
# It refuses to start unless R3 is applied, R7 has pushed `revamp`, and today's
# 15:50 PBS run shipped the live site and its database. Once the site is down,
# any failure turns both switches off and brings it back up on today's pages.
# Nothing here prints a secret.
set -euo pipefail

TAG=v2026.11.0
if [[ "${1:-}" == "--rehearse" ]]; then
    REHEARSE=1; DIR=/opt/docker/7hillsdigital-revamp; APP=7hills-staging-app; DB=7hills-staging-mysql
    BASE=http://192.168.90.226:8096
else
    REHEARSE=0; DIR=/opt/docker/7hillsdigital; APP=7hills-app; DB=7hills-mysql
    BASE=http://127.0.0.1:8091
fi
STAMP=$(date +%Y%m%d-%H%M%S)
ENVF=$DIR/.env

say()  { printf '\n== %s\n' "$*"; }
fail() { printf '\n!! %s\n' "$*" >&2; exit 1; }
art()  { docker exec "$APP" php artisan "$@"; }
setenv() { # setenv KEY value: replace or append, never echo the file
    if grep -q "^$1=" "$ENVF"; then sed -i -E "s#^$1=.*#$1=$2#" "$ENVF"; else printf '%s=%s\n' "$1" "$2" >> "$ENVF"; fi
}
code() { curl -s -o /dev/null -w '%{http_code}' "$BASE$1"; }

# ---------------------------------------------------------------- gates
say "Gates"
if (( ! REHEARSE )); then
    grep -q '^APP_ENV=production$' "$ENVF" && grep -q '^APP_DEBUG=false$' "$ENVF" \
        || fail "R3 not applied: .env is not APP_ENV=production / APP_DEBUG=false"
    echo "R3 ok"

    git -C "$DIR" remote get-url origin | grep -q '192.168.90.215' || fail "R7: origin still points at the old Gitea"
    git -C "$DIR" fetch -q origin revamp 2>/dev/null || true
    [[ "$(git -C "$DIR" rev-parse revamp)" == "$(git -C "$DIR" rev-parse origin/revamp 2>/dev/null)" ]] \
        || fail "R7: revamp is not pushed to Gitea (origin/revamp differs)"
    echo "R7 ok"

    TODAY=$(date -u +%Y-%m-%d)
    RUN=$(awk -v d="$TODAY" '$0 ~ "===================== "d"T" && /starting/ {buf=""} {buf=buf"\n"$0} END {print buf}' /var/log/pbs-app-backup.log)
    grep -q "^===================== ${TODAY}T.*starting" <<<"$RUN" || fail "R1: no PBS run today yet (it runs 15:50 SAST)"
    grep -q 'proj00-7hillsdigital.pxar: had to backup' <<<"$RUN" || fail "R1: today's run did not upload the live site"
    grep -q "mysqldump   $DB\$" <<<"$RUN" && ! grep -q "WARN: mysqldump failed for $DB\$" <<<"$RUN" \
        || fail "R1: today's run did not dump $DB"
    grep -q 'done OK' <<<"$RUN" || fail "R1: today's run has not finished"
    echo "R1 ok (today's PBS run shipped the site and $DB)"

    git -C "$DIR" merge-base --is-ancestor main revamp || fail "main has moved: revamp no longer fast-forwards it"
fi

say "Staging tests"
T=$(docker exec 7hills-staging-app php artisan test --compact 2>&1 | sed 's/\x1b\[[0-9;]*m//g' | grep -E '^\s*Tests:' || true)
echo "${T:-no test summary}"
grep -q 'passed' <<<"$T" && ! grep -qE 'failed|errors?' <<<"$T" || fail "staging tests are not green"

# ---------------------------------------------------------------- backup
say "Backup"
B=~/backups/7hillsdigital/$STAMP; mkdir -p "$B" && chmod 700 "$B"
# Staging's MySQL container env no longer matches the passwords its volume was
# initialised with, so the rehearsal dumps with the app's own .env credentials,
# passed as MYSQL_PWD (never on the command line). Production dumps as root, as
# checklist §5 does.
if (( REHEARSE )); then
    docker exec -e MYSQL_PWD="$(grep -E '^DB_PASSWORD=' "$ENVF" | cut -d= -f2- | tr -d '"')" "$DB" \
        sh -c 'mysqldump -u"$MYSQL_USER" --single-transaction --routines --triggers --no-tablespaces "$MYSQL_DATABASE"' 2>/dev/null \
        | gzip -9 > "$B/db.sql.gz" || fail "database dump failed"
else
    docker exec "$DB" sh -c 'mysqldump -uroot -p"$MYSQL_ROOT_PASSWORD" --single-transaction --routines --triggers --no-tablespaces "$MYSQL_DATABASE"' 2>/dev/null \
        | gzip -9 > "$B/db.sql.gz" || fail "database dump failed"
fi
zcat "$B/db.sql.gz" | tail -1 | grep -q 'Dump completed' || fail "database dump incomplete"
(( REHEARSE )) || git -C "$DIR" bundle create "$B/repo.bundle" --all 2>/dev/null
chmod 600 "$B"/*; (cd "$B" && sha256sum -- * > SHA256SUMS)
(( REHEARSE )) || echo "$B" > ~/backups/7hillsdigital/LATEST
cp -p "$ENVF" "$ENVF.bak-$STAMP"
echo "backup $B, env copy .env.bak-$STAMP"

if (( ! REHEARSE )); then
    say "Tag"
    git -C "$DIR" tag -a "$TAG" revamp -m "Revamp release" 2>/dev/null || echo "tag exists"
    git -C "$DIR" push origin "$TAG" || echo "WARN: tag not pushed (needs the Gitea token). Later: git -C $DIR push origin $TAG"
fi

# ---------------------------------------------------------------- down
say "Maintenance mode"
art down --render="errors::503" --retry=60
recover() {
    printf '\n!! Failed at line %s. Switching the revamp off and bringing the site back.\n' "$1" >&2
    setenv REVAMP_LEADS false; setenv REVAMP_HOME_V2 false
    art optimize:clear >/dev/null 2>&1 || true
    art up || true
    printf '!! Site is up on today'"'"'s pages. Full rollback: RELEASE_PLAN.md / checklist §4. Backup: %s\n' "$B" >&2
}
trap 'recover $LINENO' ERR

if (( ! REHEARSE )); then
    say "Code"
    git -C "$DIR" stash push -m "pre-release local edits $STAMP" || true
    git -C "$DIR" checkout -q main
    git -C "$DIR" merge --ff-only revamp
    git -C "$DIR" log --oneline -1
fi

say "Assets"
docker exec "$APP" npm run build >/dev/null
test -f "$DIR/public/build/manifest.json"

say "Migrations"
PENDING=$(art migrate --pretend --force 2>/dev/null | grep -cE '^\s*2026_10_31_1002?[0-9]{2}_' || true)
echo "pending 2026_10_31 migrations: $PENDING"
if (( ! REHEARSE )); then
    UNEXPECTED=$(art migrate:status 2>/dev/null | grep -E 'Pending' | grep -vcE '2026_10_31_100[012]00_' || true)
    (( UNEXPECTED == 0 )) || { echo "unexpected pending migrations: $UNEXPECTED"; false; }
fi
art migrate --force

# ---------------------------------------------------------------- pages on
say "Switch REVAMP_HOME_V2 on"
setenv REVAMP_HOME_V2 true; setenv REVAMP_LEADS false; setenv REVAMP_PREVIEW_UNCONFIRMED false
art optimize:clear >/dev/null
art up

say "Smoke"
BAD=0
URLS=$(curl -s "$BASE/sitemap.xml" | grep -oE '<loc>[^<]+' | sed -E 's#<loc>https?://[^/]+##; s#^$#/#')
[[ $(wc -l <<<"$URLS") -ge 30 ]] || { echo "sitemap too short"; false; }
for u in $URLS /admin/login; do c=$(code "$u"); [[ $c == 200 ]] || { echo "  $c $u"; BAD=1; }; done
[[ $(code /services/microsoft-365) == 404 ]] || { echo "  gated page is visible"; BAD=1; }
(( BAD == 0 ))
echo "$(wc -l <<<"$URLS") sitemap pages + /admin/login: 200; gated page: 404"

# ---------------------------------------------------------------- leads on
say "Switch REVAMP_LEADS on"
setenv REVAMP_LEADS true
art optimize:clear >/dev/null
[[ $(code /quote) == 200 ]]
PAGE=$(curl -s "$BASE/"); grep -q 'href="[^"]*/quote' <<<"$PAGE"
echo "/quote 200 and linked from the home page"

trap - ERR
say "Released. Now watch: docker exec $APP tail -f storage/logs/laravel.log (15 minutes)"
(( REHEARSE )) && echo "(rehearsal: staging switches left on, as before)"
