<div align="center">

# 🚀 LaunchPad

**A community-driven product discovery & launch platform.**

Makers ship products. The community discovers, upvotes, discusses, and battles over what's worth their attention — a Product Hunt-style experience built from scratch with Laravel.

[Features](#-features) · [Tech Stack](#-tech-stack) · [Quick Start](#-quick-start) · [Demo Accounts](#-demo-accounts) · [Architecture](#-architecture) · [Commands](#-artisan-commands)

</div>

---

## 📖 Overview

LaunchPad is a full-featured launch community. Makers submit products (with logos, screenshots, tags, and external links), schedule launches on a calendar, and build in public via update logs. Hunters discover products through ranked feeds, upvote, comment, curate collections, and vote in head-to-head "battles." Admins moderate the whole thing from a dedicated dashboard.

It's built with server-rendered Blade, a hand-rolled design system, and progressive-enhancement Vanilla JS — **no SPA framework and no frontend build step**.

---

## ✨ Features

### Discovery
- **Ranked feed** — Today / This Week / All Time tabs, ordered by upvotes
- **Filtering & search** — by category, by tag, or free-text search on name & tagline
- **Launch Calendar** — upcoming scheduled products with live countdown timers
- **Collections** — curated, followable lists of products (public or private)

### Products
- **Rich submission form** — basic info, category, multi-tag selection, logo upload with live preview, up to 5 screenshots, external links (website / demo / GitHub), and an optional "Roast Mode" toggle
- **Product pages** — screenshot gallery, formatted description, tags, threaded discussion, build-in-public log, and upvoting
- **Roast Mode** — an opt-in thread for brutally honest feedback, separate from regular comments
- **Editing** — makers can edit their own products; status flows through `pending → approved` moderation

### Community
- **Upvotes** — one-click, AJAX, with optimistic UI
- **Threaded comments** — replies, maker badges on author's own threads, delete controls
- **Maker profiles** — product portfolio, earned badges, public collections, and lifetime stats
- **Maker Battles** — head-to-head product voting arenas with live percentage bars
- **Badges** — First Launch, 3 Launches, Top 5 Today, and Community Fav (50+ upvotes)
- **Notifications** — bell icon with unread badge for upvotes, comments, and approval/rejection

### Operations
- **Maker Dashboard** — upvote history, views per product, build-log updates, and product management
- **Admin panel** — dark-sidebar layout to manage products, users, categories, and battles; ban/unban users and escalate roles
- **Roles** — Admin, Maker, Hunter, enforced by route middleware
- **Scheduled publishing** — products with a future launch date go live automatically

---

## 🛠 Tech Stack

| Layer | Choice |
|-------|--------|
| Framework | Laravel 12 |
| Language | PHP 8.2+ |
| Database | MySQL 8+ |
| Templating | Blade (server-rendered) |
| Styling | Custom CSS design system + Bootstrap 5 (CDN, scaffolding only) |
| Icons | [Lucide](https://lucide.dev) (no emoji icons) |
| JavaScript | Vanilla JS (progressive enhancement — no build step) |

---

## 🚀 Quick Start

### Prerequisites
- PHP **8.2+**
- Composer
- MySQL **8+**

### 1. Clone & install

```bash
git clone https://github.com/ajoycodes/LaunchPad.git
cd LaunchPad
composer install
cp .env.example .env
php artisan key:generate
```

### 2. Create the database

```sql
CREATE DATABASE launchpad CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Then set your credentials in `.env`:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=launchpad
DB_USERNAME=launchpad
DB_PASSWORD=launchpad
```

> **Tip:** if your MySQL only accepts socket connections, add `DB_SOCKET=/tmp/mysql.sock` (or your socket path) to `.env`.

### 3. Migrate, seed & link storage

```bash
php artisan migrate --seed     # schema + demo data
php artisan storage:link       # expose uploaded logos/screenshots
```

### 4. Serve

```bash
php artisan serve
```

Visit **http://127.0.0.1:8000** 🎉

---

## 🔑 Demo Accounts

Seeded by `DemoSeeder` (~60 users, ~45 products, plus comments, upvotes, battles, and collections). All passwords are `password`.

| Role  | Email                  | Notes                          |
|-------|------------------------|--------------------------------|
| Admin | `admin@launchpad.test` | Full admin panel access        |
| Maker | `alice@launchpad.test` | Has products, badges, collections |
| Maker | `bob@launchpad.test`   | Another maker profile          |
| Hunter| `samhunts@launchpad.test` | Upvoter / commenter         |

---

## 🏗 Architecture

**Thin controllers, fat models.** Controllers handle validation and delegation; business logic (badge awarding, slug generation, vote tallying) lives on the models. Views are organized by feature, with shared pieces extracted into Blade components and partials.

```
app/
├── Http/Controllers/        Request handling (Admin/ subdir for the panel)
├── Http/Middleware/         AdminMiddleware, MakerMiddleware (aliased: admin, maker)
├── Models/                  13 Eloquent models + Concerns/ (shared traits)
└── Console/Commands/        PublishScheduledProducts (products:publish)

resources/views/
├── components/              Reusable Blade components (avatar, product-logo, product-card)
├── partials/                navbar, footer, comment, flash
├── layouts/                 app (public) + admin (dark sidebar)
└── <feature>/               home, products, makers, collections, battles, admin, …

public/css/
├── tokens.css               Design tokens — colors, spacing, radius, shadows
├── app.css                  All public component styles
└── admin.css                Admin panel styles
```

### Data model

| Model | Role |
|-------|------|
| `User` | Accounts with roles (admin/maker/hunter), bios, avatars |
| `Product` | Submissions with status, launch date, logo, links |
| `ProductScreenshot` | Gallery images for a product |
| `ProductUpdate` | Build-in-public log entries |
| `Category` / `Tag` | Taxonomy (tags via `product_tags` pivot) |
| `Upvote` | One per user per product |
| `Comment` | Threaded discussion, with roast flag |
| `Collection` | Curated, followable product lists |
| `Battle` / `BattleVote` | Head-to-head product voting |
| `Badge` | Achievements earned by makers |
| `Notification` | Activity alerts with read state |

---

## ⏱ Scheduled Tasks

Scheduled products are published automatically when their launch date passes.

```bash
# Run the publisher once
php artisan products:publish

# Run the scheduler (publishes hourly)
php artisan schedule:work
```

In production, point cron at Laravel's scheduler:

```cron
* * * * * cd /path/to/LaunchPad && php artisan schedule:run >> /dev/null 2>&1
```

---

## 🧰 Artisan Commands

| Command | Purpose |
|---------|---------|
| `php artisan migrate --seed` | Build schema and load demo data |
| `php artisan db:seed` | Re-seed demo data |
| `php artisan storage:link` | Symlink `storage/` for public uploads |
| `php artisan products:publish` | Publish products whose launch date has arrived |
| `php artisan serve` | Start the local dev server |

---

## 📂 Project Structure

| Path | Purpose |
|------|---------|
| `app/Http/Controllers/` | Thin controllers — validation + delegation |
| `app/Models/` | Eloquent models with business logic |
| `app/Console/Commands/` | Scheduled & CLI commands |
| `resources/views/` | Blade templates, components, and partials |
| `public/css/tokens.css` | Design tokens (colors, spacing, radius) |
| `public/css/app.css` | Public-facing component styles |
| `public/css/admin.css` | Admin panel styles |
| `database/migrations/` | Schema definitions |
| `database/seeders/DemoSeeder.php` | Demo data for development |
| `routes/web.php` | Application routes |
| `routes/console.php` | Scheduler definitions |

---

## 📝 License

No formal license is currently attached — this is a personal portfolio project. If you'd like to reuse it, please reach out.
