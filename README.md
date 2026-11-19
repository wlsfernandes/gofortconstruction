# Fort Construction website

Laravel 13 rebuild of the Fort Construction marketing site, originally prototyped in Base44.
Public pages are server-rendered Blade with Tailwind CSS and Alpine.js. Staff sign in with
Laravel Fortify to manage content and estimate requests at `/admin`.

## Requirements

- PHP 8.3+ with `pdo_pgsql`
- Composer 2
- A current Node.js LTS release and npm (build step only)
- PostgreSQL

## Local setup

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Edit `.env`: set the `DB_*` values for your PostgreSQL database, and `ADMIN_EMAIL` / `ADMIN_PASSWORD`
for the first administrator. Then:

```bash
php artisan migrate
php artisan db:seed            # creates the admin; adds sample content outside production
php artisan storage:link       # makes uploaded images public
npm install
npm run build                  # or `npm run dev` while working on views
php artisan serve
```

Run the tests with `php artisan test` (they use in-memory SQLite and need no database setup).

## Deploying to production

Set these in the server's environment (never commit real values):

| Variable | Value |
| --- | --- |
| `APP_ENV` | `production` |
| `APP_DEBUG` | `false` |
| `APP_URL` | the public `https://` address |
| `APP_KEY` | generate once with `php artisan key:generate --show` |
| `DB_*` | production PostgreSQL credentials |
| `MAIL_MAILER`, `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD`, `MAIL_FROM_ADDRESS` | a real mail provider; the default `log` mailer sends nothing |
| `CONTACT_RECIPIENT_EMAIL` | inbox for estimate requests (defaults to the public support address) |
| `ADMIN_EMAIL`, `ADMIN_PASSWORD` | first administrator; remove after the first seed |

On each deploy:

```bash
composer install --no-dev --optimize-autoloader
npm ci && npm run build
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Once per server:

```bash
php artisan storage:link
php artisan db:seed --class=AdminUserSeeder --force
```

`DatabaseSeeder` never loads the sample projects, posts and reviews in production. They are
placeholders, not real customers, and must not be published as genuine reviews.

### Background work

Estimate-request emails are queued. Keep a worker running under a process manager
(Supervisor, systemd or your host's equivalent):

```bash
php artisan queue:work --tries=3
```

Without a worker, emails wait in the `jobs` table and are never sent.

### Images

Site images live in `public/images` (logo and stock photos from Unsplash, used under the
Unsplash License). Images uploaded in the admin go to `storage/app/public` and are served
through the `public/storage` link, so that directory must be persistent and backed up.
