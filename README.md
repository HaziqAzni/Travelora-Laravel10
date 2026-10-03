# Travelora

A travel booking demo web app: browse destinations, hotels, flights, packages and
activities, with live data from open APIs. Built with **Laravel 13** (MVC), Blade,
Eloquent and MySQL.

> The interface design is intentionally a neutral marketplace layout, not copied from
> any brand.

---

## Requirements

| Tool | Version |
|------|---------|
| PHP | **8.3+** (Laravel 13) |
| Composer | 2.x |
| MySQL | 8.0+ |
| Node | optional (assets are pre-built; no build step required) |

---

## Fresh install (from zero)

```bash
# 1. Install PHP dependencies
composer install

# 2. Create your environment file
cp .env.example .env        # Windows: copy .env.example .env

# 3. Generate the application key
php artisan key:generate

# 4. Create an empty MySQL database, then set the credentials in .env
#    DB_DATABASE=travelora_laravel  DB_USERNAME=root  DB_PASSWORD=

# 5. Create the schema and load the realistic demo data
php artisan migrate --seed
```

That is it — **migrations build the whole schema** and seeders load demo content
(13 destinations, 18 hotels, 14 flights, 8 packages, 12 activities).

### Run it

```bash
php artisan serve
# or, with Laragon: point a virtual host at the /public folder and open
# http://travelora-laravel.test
```

### Demo accounts (seeded)

| Role | Email | Password |
|------|-------|----------|
| Admin | `admin@travelora.test` | `password123` |
| User  | `demo@travelora.test`  | `password123` |

Remove `UserSeeder` before a real deployment.

---

## Project structure

```
app/
  Http/Controllers/        # pages, auth, bookings, sync, JSON API
  Http/Middleware/         # SetLocale, EnsureUserIsAdmin
  Models/                  # Destination, Hotel, Flight, Package, Activity, Booking, User
  Support/                 # api.php (open APIs), sync.php (OSM import), helpers.php (i18n)
database/
  migrations/              # full schema
  seeders/                 # DatabaseSeeder, DemoContentSeeder, UserSeeder
resources/views/           # Blade templates (layouts, partials, pages)
lang/                      # en (inline), ms.php, zh.php
public/assets/             # css, js, images (no build step needed)
routes/web.php
```

---

## Data sources & transparency

Live, keyless APIs (all responses are cached on disk with sensible TTLs):

- **OpenStreetMap** — geocoding (Nominatim) and hotel names/addresses (Overpass). Licence: ODbL.
- **Wikipedia REST** — destination descriptions. Licence: CC BY-SA.
- **Open-Meteo** — current weather + 4-day forecast. Licence: CC BY 4.0.
- **Wikidata SPARQL** — country facts (capital, currency, population). Licence: CC0.

**Sample data disclosure.** OpenStreetMap does not provide prices, star ratings or
review counts. When hotels are imported from OSM, those fields are **estimated sample
values** and are labelled as such in the UI (an "Estimated" note on each card, marked
with `hotels.price_is_estimate = true` and `hotels.data_note`). Curated seed data is
hand-written demo content. Do not present any of it as verified commercial data.

**Clear demo labelling.** Listing pages show a "Demo catalogue" notice; flight results
show "Sample fare" instead of seat-scarcity claims; activities no longer promise instant
confirmation or free cancellation; package discounts are not displayed; and the About
page labels its stats and team profiles as illustrative. The footer Terms, Privacy and
Contact links lead to their own pages (not to About).

> **OpenSky Network was removed** from this build. Its terms require written permission
> for commercial/operational use, so the live air-traffic feature is not included.

---

## Booking behaviour (demo)

Bookings are **demo requests**, not confirmed reservations:

- New bookings are stored with `status = pending` and `is_demo = true`.
- No payment is collected and no live availability is checked.
- Travel dates and guest counts are illustrative inputs only.
- The UI states this on the booking modal and on the "My Trips" page.

To make this production-grade you would need real inventory/availability and a payment
provider (e.g. Stripe), plus the associated terms and compliance.

---

## Security

- Login **regenerates the session id** (fixation protection).
- **Logout is POST** with CSRF protection, and **fully invalidates the session**
  (`invalidate()` + `regenerateToken()`).
- Login and signup are **rate limited** (`throttle:6,1` / `throttle:10,1`).
- Data-sync and cache-clear actions require the **`admin` role**
  (`EnsureUserIsAdmin` middleware) — a normal user cannot trigger maintenance.
- **Redirects are restricted to local paths** by `safe_redirect()` (used by the login,
  booking, newsletter and language flows), so they cannot bounce users off-site.
- The public `/api/nearby-hotels` endpoint **validates latitude/longitude ranges** and is
  **rate limited** (`throttle:30,1`) so it can't be used to flood the public OSM service.
- User passwords are hashed with bcrypt.
- The demo login hint on the login page is only rendered when `APP_DEBUG=true`.

---

## Production checklist (before selling / deploying)

- [ ] **Do not ship `.env`** — it is git-ignored. Ship `.env.example` and let the buyer
      create their own `.env`, set real DB credentials and run `php artisan key:generate`.
- [ ] Set `APP_ENV=production`, `APP_DEBUG=false`, and a real `APP_URL`.
- [ ] Remove the demo `UserSeeder` (or change the default passwords) and the seeded
      demo booking.
- [ ] Configure a production mailer and queue/cache driver as needed.
- [ ] Run `php artisan config:cache route:cache view:cache`.
- [ ] Exclude from the package: `.env`, `storage/logs/*`, `storage/framework/cache/*`,
      `node_modules/`, and optionally `vendor/` (buyer runs `composer install`).

---

## Original plain-PHP version

An earlier procedural (no-framework) version of the same app is kept separately for
reference only. This Laravel project is the maintained one.
