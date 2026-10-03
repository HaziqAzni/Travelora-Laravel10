# Travelora Laravel — Start Here

This is a Laravel travel-booking demo application. It is not a live travel agency booking engine: bookings are saved as demo requests, with no payment processing or verified availability.

## Requirements

- PHP 8.3 or newer with the extensions required by Laravel
- Composer 2
- MySQL 8.0 or newer
- Node.js is not required for the included prebuilt CSS and JavaScript

## Install on Windows / Laragon

1. Extract the ZIP into a folder such as `C:\laragon\www\travelora-laravel`.
2. Create an empty MySQL database named `travelora_laravel` (or choose a different name).
3. In the project folder, copy `.env.example` to `.env` and update `DB_DATABASE`, `DB_USERNAME`, and `DB_PASSWORD` for your own local database.
4. Open a terminal in the project folder and run:

   ```text
   composer install
   php artisan key:generate
   php artisan migrate --seed
   php artisan serve
   ```

5. Visit `http://127.0.0.1:8000` (or configure a Laragon virtual host to point to the project's `public` folder).

## Database included

The database is delivered as Laravel migrations and demo seed data:

- `database/migrations/` creates the schema.
- `database/seeders/` loads the demo catalogue and demo accounts.
- `database/seeders/data/demo_content.sql` contains sample destinations, hotels, flights, packages, and activities.

Run `php artisan migrate --seed` against a new empty database to build and populate it. The ZIP does not contain a dump of the seller's local database.

## Demo sign-ins

- Admin: `admin@travelora.test` / `password123`
- User: `demo@travelora.test` / `password123`

These are public demo credentials. Change or remove them before deploying the app to a public server. The seeded catalogue contains illustrative sample prices and ratings.

For additional details, see `README.md`.
