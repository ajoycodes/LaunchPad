# LaunchPad

A Product Hunt-style platform where makers share products and the community discovers, upvotes, and discusses what's worth their attention.

Built with Laravel 12, MySQL, Bootstrap 5, Vanilla JS, and Blade templates.

---

## Features

- **Product feed** — Browse by Today / This Week / All Time, filter by category or tag, search by name
- **Product pages** — Screenshots gallery, build-in-public log, comments, upvotes, save to collection
- **Maker profiles** — Portfolio of launched products, earned badges, public collections
- **Launch Calendar** — Preview upcoming scheduled products with live countdown timers
- **Maker Battles** — Head-to-head product voting arena with live percentage bars
- **Collections** — Curated lists of products; follow collections; public / private visibility
- **Maker Dashboard** — Upvote history chart, views per product, build log updates, product management
- **Admin panel** — Dark-sidebar layout; manage products, users, categories, and battles
- **Notifications** — Bell icon with unread badge; upvote / comment / approval notifications
- **Badges** — First Launch, 3 Launches, Top 5 Today, Community Fav (50+ upvotes)
- **Roles** — Admin, Maker, Hunter; ban/unban; role escalation

---

## Requirements

- PHP 8.2+
- Composer
- MySQL 8+
- Node (optional — no npm build step)

---

## Setup

```bash
git clone https://github.com/ajoycodes/LaunchPad.git
cd LaunchPad
composer install
cp .env.example .env
php artisan key:generate
```

Edit `.env` with your database credentials:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=launchpad
DB_USERNAME=launchpad
DB_PASSWORD=launchpad
```

Run migrations and seed demo data:

```bash
php artisan migrate
php artisan db:seed
```

Start the dev server:

```bash
php artisan serve
```

Visit `http://127.0.0.1:8000`.

---

## Demo credentials

| Role  | Email                      | Password   |
|-------|----------------------------|------------|
| Admin | admin@launchpad.test       | password   |
| Maker | alice@launchpad.test       | password   |
| Maker | bob@launchpad.test         | password   |

---

## Scheduled commands

The platform automatically publishes scheduled products when their launch date arrives:

```bash
# Run once manually
php artisan products:publish

# Or let the scheduler run it hourly
php artisan schedule:work
```

---

## Project structure

| Path | Purpose |
|------|---------|
| `app/Http/Controllers/` | Thin controllers — validation + delegation |
| `app/Models/` | Fat models with business logic |
| `resources/views/` | Blade templates |
| `public/css/tokens.css` | Design tokens (colours, spacing, radius) |
| `public/css/app.css` | All component styles |
| `public/css/admin.css` | Admin panel styles |
| `database/seeders/DemoSeeder.php` | Demo data for development |
