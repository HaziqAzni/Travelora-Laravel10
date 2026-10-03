# Database setup

This package uses Laravel migrations and seeders rather than a dump of the developer's local database.

1. Create an empty MySQL database.
2. Set its connection details in `.env`.
3. Run `php artisan migrate --seed`.

The migrations create the tables. Seeders load synthetic demo catalogue records and public demo accounts. The catalogue SQL source is `database/seeders/data/demo_content.sql`. Do not use the seeded demo accounts on a public deployment.
