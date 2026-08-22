# 7Hills Digital

Marketing site and sales back office for 7Hills Digital, the technology arm
of 7Hills Holdings.

Laravel 12 · Filament v4 · MySQL 8 · Tailwind v4 · Docker Compose.

See [CLAUDE.md](CLAUDE.md) for the working agreement and the cross-cutting rules.

## Running it

Nothing is installed on the host — PHP, Composer and Node all live in the `app`
image.

```bash
docker compose up -d
```

| | |
|---|---|
| Public site | http://localhost:8091 |
| Back office | http://localhost:8091/admin |
| MySQL | `localhost:33063` |

## First run

```bash
docker compose exec app php artisan migrate --seed
```

The seeder creates the five packages with **placeholder prices** and one admin
user. If `ADMIN_PASSWORD` is not set in `.env` it generates a password and prints
it once — save it, it is not stored anywhere else.

## Everyday commands

```bash
docker compose exec app php artisan test
```

```bash
docker compose exec app npm run build
```

```bash
docker compose exec app ./vendor/bin/pint
```

```bash
docker compose exec app php artisan optimize:clear
```

## Checking outbound mail

```bash
docker compose exec app php artisan mail:check --send=you@example.com
```

Verifies SPF, DKIM and DMARC for the sending domain, checks the SMTP credentials
are set and the host is reachable, then optionally sends a test. Gmail rejects
any domain where neither SPF nor DKIM passes, so run this after any DNS change.

## Setting the real prices

Prices are **not** in the code. Sign in at `/admin`, open **Packages & pricing**,
choose a package and edit its tiers. `PackageSeeder` only sets what a fresh
install starts with.
