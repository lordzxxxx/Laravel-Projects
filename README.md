# Laravel-Projects

A Laravel 12 accommodation booking and messaging platform with role-based dashboards for clients, owners, and admins.

## Product Specification

### 1. System Overview

Project ImpaStay is a multi-tenant SaaS platform designed to manage property rentals, bookings, and user interactions through a centralized system.

The platform supports three main user roles:

- Admin (Central App)
- Unit Owners (multi-tenant accounts with subscriptions)
- Tenants (Tenant App users)

The system operates on a subscription-based model (Basic, Plus, Pro) and includes:

- Centralized administration
- Role-based access control
- Wireless system updates
- Customization capabilities

### 2. System Architecture Concept

#### Multi-Tenant Architecture

- One shared platform serves multiple Unit Owners.
- Each Unit Owner has isolated data for:
  - Listings
  - Bookings
  - Reports
  - Tenant communications
- Admin has full system-wide visibility.
- Tenants interact through a separate Tenant App.

#### Core Components

1. Central App (Admin System)
2. Unit Owner Accounts (Subscription-Based)
3. Tenant App
4. Support and Wireless Update System

### 3. User Roles and Functionalities

#### 3.1 Admin (Central App)

##### Role Description

The Admin manages the entire system and acts as a central controller and intermediary between Unit Owners and Tenants.

##### Core Functions

- Manage all users (Unit Owners and Tenants)
- Monitor system-wide activity 
- View analytics and insights
- Identify most profitable units
- Identify top-performing units
- Compare performance across Unit Owners
- Act as a middleman for communication
- Send and receive messages
- Generate system reports
- Monitor system health
- Manage subscriptions
- View update logs
- Configure system customization

##### Dashboard Features

- Total users (owners + tenants)
- Total bookings
- Monthly revenue analytics
- Subscription breakdown (Basic / Plus / Pro)
- Top-performing units
- Occupancy rate trends
- Reports panel
- Messaging inbox
- System status and health
- Update status and logs

#### 3.2 Unit Owners (Multi-Tenant Users)

##### Role Description

Unit Owners are independent users who manage their own property listings, bookings, and tenant interactions.

Each Unit Owner operates within an isolated environment and is restricted to their own data.

##### Common Functionalities (All Plans)

- Create unit listings
- Edit and update listings
- Archive listings
- Manage bookings
- Cancel bookings (based on conditions)
- Communicate with tenants

##### Subscription Plans

###### A. Basic Plan

- Limited number of unit listings
- Basic booking management
- Basic messaging functionality
- Limited analytics access

###### B. Plus Plan

- Increased listing capacity
- Full booking management
- Messaging with tenants
- Access to insights and reports
- Moderate analytics dashboard

###### C. Pro Plan

- High or unlimited listing capacity
- Advanced booking controls
- Priority communication tools
- Advanced analytics and insights
- Full reporting system
- Performance tracking (best units, best months)

##### Unit Owner Dashboard Features

- Subscription plan indicator (Basic / Plus / Pro)
- Upgrade subscription option
- Listing management panel
- Booking calendar
- Occupancy rate chart
- Revenue summary
- Analytics dashboard (varies by plan)
- Reports section
- Tenant messaging system

#### 3.3 Tenants (Tenant App Users)

##### Role Description

Tenants are end-users who interact with the system through the Tenant App to find and book rental units.

##### Core Functionalities

- Browse available units
- Search and filter listings
- View unit details
- Communicate with Unit Owners
- Book rental units
- Cancel bookings
- View booking history
- Receive notifications

##### Tenant App Features

- Home dashboard
- Property search and filters
- Listing cards and details page
- Booking form and confirmation
- Cancellation option
- Messaging/chat system
- Notifications panel
- Booking history

### 4. Support and Wireless Update System

#### Role Description

This module ensures the system remains updated, customizable, and maintainable.

#### Core Functionalities

- Check for system updates wirelessly
- Receive updates from system provider
- View update logs and release notes
- Install system updates
- Manage system customization
- Configure system settings

#### Key Features

- Update availability detection
- Version control tracking
- Release notes display
- Update installation control
- Customization settings (UI, features, configurations)
- System maintenance tools

### 5. System Workflow

#### Booking Flow

1. Tenant browses available units
2. Tenant sends booking request
3. Unit Owner reviews and manages booking
4. Booking is confirmed or declined

#### Communication Flow

- Client and owner (booking-related messages and general inbox threads on the tenant app)
- Tenant owner or **tenant admin** can start conversations from **Messages → New conversation** with:
  - Registered **clients** (guests) on that tenant
  - **Team** (other tenant admins and the business owner)
  - **ImpaStay (Central Admin)** — in-app thread via a per-tenant support proxy user; optional email alert to platform staff (see Environment)
- Central (landlord) admin and tenant users where the product exposes shared admin tools

#### Administration Flow

- Admin monitors system analytics
- Admin generates reports
- Admin manages users and subscriptions

#### Update Flow

1. System checks for updates wirelessly
2. Provider releases updates and logs
3. Admin/system applies updates
4. System configuration and customization is adjusted if needed

### 6. Key System Characteristics

- Multi-tenant architecture with shared platform and isolated user data
- Subscription-based access with features by plan
- Centralized administration across the ecosystem
- Role-based access control by user type
- Scalability for multiple Unit Owners
- Customizable settings and configurable features
- Wireless update capability for continuous improvement

## Core Features

- Multi-role authentication and authorization (client, owner, admin)
- Multi-tenant architecture (Spatie multitenancy) with tenant isolation
- Port-based tenant app routing and central app routing split
- Public landing page and guest auth routes (register, login, password reset)
- Accommodation browsing for authenticated users
- Owner property management for listings
- Booking flow with status updates (pending, confirmed, paid, completed, cancelled)
- In-app messaging with reply, read, and archive; inbox lists the **counterparty** per thread
- **Tenant managers** (owner or tenant-scoped admin): **New conversation** at `/messages/create` to message clients, team, or central support (proxy user in the tenant DB)
- Optional `IMPASTAY_CENTRAL_SUPPORT_NOTIFY_EMAIL`: plain-text mail when a tenant manager messages central support
- Shared **client top navbar** styles (`client/partials/top-navbar-styles`) and content offsets for fixed navigation across client pages
- **Responsive UI** (320px–4K): fluid typography/tables/cards via `resources/css/responsive-tokens.css`; unified mobile nav at **768px** with burger menus and symmetric padding
- **Owner portal** full-width shells: dashboard (bookings-only trend chart), My Properties, accommodation edit, messages inbox split layout, room availability calendar
- **Navbar user menu**: Profile / Settings / Logout in a dropdown on admin, owner, and client top bars (Settings removed from main nav links)
- Profile management with additional user details and avatar upload
- Admin dashboards for tenants, bookings, messages, and monitoring
- Admin tenant management: update tenant plan (Basic/Plus/Pro)
- Admin tenant domain control: enable/disable tenant domain access
- Central update channel with tenant owner/admin update checks and downloads
- Persistent update history logs (checked/installed timestamps + status)

## Tech Stack

- Laravel 12
- PHP 8.2+
- MySQL or SQLite
- [Spatie Laravel Multitenancy](https://github.com/spatie/laravel-multitenancy) (tenant DB isolation)
- [Spatie Laravel Permission](https://github.com/spatie/laravel-permission) (RBAC on tenant manager flows)
- Vite + Tailwind CSS + Alpine.js
- Pest / PHPUnit for testing

## Requirements

- PHP 8.2 or newer
- Composer
- Node.js 18+ and npm
- Database server (MySQL recommended) or SQLite

## Quick Start

### 1) Clone and install

~~~bash
git clone https://github.com/lordlylexxx/Laravel-Projects.git
cd Laravel-Projects

composer install
npm install
~~~

### 2) Environment setup

~~~bash
cp .env.example .env
php artisan key:generate
~~~

Update your `.env` database values before migrating.

**Optional messaging / platform support**

| Variable | Purpose |
|----------|---------|
| `IMPASTAY_CENTRAL_SUPPORT_NOTIFY_EMAIL` | When set, tenant owners/admins messaging **ImpaStay (Central Admin)** also triggers a plain email to this address (requires working `MAIL_*` settings). |

Related config: `config/impastay.php`.

### 3) Migrate and seed

~~~bash
php artisan migrate --seed
php artisan storage:link
~~~

On the **central (landlord)** database, `db:seed` only creates RBAC, a central admin, and sample owner/client users. It does **not** create tenant registry rows (unless you run `ExistingTenantDatabasesSeeder` separately), **accommodations**, or **bookings**—those live in **tenant** databases. With a tenant made current, run `php artisan db:seed --class=AccommodationSeeder` if you want demo listings in that tenant DB.

### 4) Run in development

Option A (single command, recommended):

~~~bash
composer run dev
~~~

Option B (separate terminals):

~~~bash
php -c scripts/php-upload-limits.ini artisan serve --host=127.0.0.1 --port=8000
php -c scripts/php-upload-limits.ini artisan serve --host=127.0.0.1 --port=8005
npm run dev
~~~

`composer run dev` and the commands above load `scripts/php-upload-limits.ini` so multi-photo accommodation uploads (up to 20 gallery images) do not hit PHP’s default 8MB `post_max_size` limit.

### 5) Multi-tenant local ports (optional)

The project supports running central and tenant app instances on different ports.

- Central app default: `127.0.0.1:8000`
- Tenant apps: `127.0.0.1:8001+` (resolved by tenant `app_port`)

If needed, run multiple `php artisan serve` processes on different ports for local tenant testing.

## Test Accounts

After seeding, you can log in with:

- Admin: admin@impasugong.gov.ph / password
- Owner: sarah.chen@email.com / password
- Client: juan.miguel@email.com / password

## Useful Commands

~~~bash
# run tests
php artisan test

# migrate database
php artisan migrate --force

# code style (if needed)
./vendor/bin/pint

# clear caches
php artisan optimize:clear
~~~

## Project Structure (High Level)

- `app/Models`: User, Tenant, Accommodation, Booking, Message, UpdateLog, Role/Permission (Spatie)
- `app/Http/Controllers`: auth, booking, messaging, dashboards, owner tenant user management
- `app/Http/Middleware`: role, tenant context, tenant manager (owner or tenant admin), client access
- `app/Services/Messaging`: e.g. central-support proxy user helper for tenant-scoped messages
- `app/Multitenancy`: tenant finder and tenant DB switching tasks
- `config/impastay.php`: ImpaStay-specific options (central support notify email)
- `database/migrations` and `database/seeders`: landlord + tenant migrations, sample data
- `resources/views`: Blade for guest/client/owner/admin; shared nav partials under `client/partials`, `owner/partials`, `admin/partials`
- `resources/css/responsive-tokens.css`: viewport-tier CSS variables (typography, tables, cards, page padding)
- `tests/Feature/ResponsiveViewportTest.php`: smoke tests for responsive markup and owner layout shells

### Routes layout

`routes/web.php` is the only file registered in `bootstrap/app.php`. It loads:

**Central hosts** (`CENTRAL_DOMAIN`, `localhost`, `127.0.0.1`, `::1`) → `routes/central.php`:

| File | Middleware | Purpose |
|------|------------|---------|
| `routes/central/shared.php` | `portal.port:any` | Landing, auth, explore, dashboard router, profile, messages |
| `routes/central/admin.php` | `portal.port:admin` | Platform admin (CA): tenants, releases, reports, payments |
| `routes/central/guest.php` | `portal.port:public` | Municipality guest stays/bookings; central owner portal |

`routes/_central_portal_routes.php` is a deprecated shim that `require`s `central.php` for backward compatibility.

**Tenant hosts** (subdomain / custom domain) → `routes/tenant.php` inside the tenant middleware stack:

| File | Purpose |
|------|---------|
| `routes/tenant/public.php` | Landing, browse accommodations (no auth) |
| `routes/tenant/guest.php` | Client bookings, messages, profile, update tickets |
| `routes/tenant/owner.php` | Owner / tenant manager portal (`/owner/*`) |
| `routes/tenant/shared.php` | Notifications, secure media (authenticated) |
| `routes/auth.php` | Tenant login, register, password reset, verify email |

Route names, URIs, and middleware were **not changed** during the split—only file organization.

### Key view partials (UI)

| Area | Partial / asset |
|------|-----------------|
| Mobile nav | `partials/mobile-nav-unified-styles.blade.php`, `partials/nav-burger-toggle.blade.php` |
| Owner shell | `owner/partials/owner-shell-styles.blade.php`, `owner-responsive-styles.blade.php`, `owner-nav-mobile-styles.blade.php` |
| Admin shell | `admin/partials/admin-shell-styles.blade.php`, `admin-responsive-styles.blade.php` |
| Guest shell | `client/partials/guest-shell-styles.blade.php` and per-page guest `*-styles` partials |
| Messages inbox | `messages/partials/messages-inbox-split.blade.php`, `owner/partials/owner-messages-index-styles.blade.php` |
| Availability | `partials/availability-calendar.blade.php`, `owner/partials/owner-availability-card.blade.php` |
| Explore stays | `partials/explore-stays-filters.blade.php`, tiered card tokens in `app.css` |

## Admin Tenant Management

Central admin tenant controls are available in the Tenants page:

- `GET /admin/tenants`
- `PUT /admin/tenants/{tenant}/plan`
- `PUT /admin/tenants/{tenant}/domain-status`

Changing plan updates subscription lifecycle values, and disabling a domain blocks tenant resolution in tenant routing.

## Messaging (tenant host)

Tenant messages routes live in `routes/tenant/guest.php` (see `messages.*` names). The owner portal reuses the same inbox with `owner-messages-*` layout styles on `messages/index.blade.php`.

| Method | Path | Who |
|--------|------|-----|
| GET | `/messages` | Authenticated tenant users (clients; managers use owner-styled inbox) |
| GET | `/messages/create` | Tenant **owner** or **tenant admin** (`tenant.manager`) |
| POST | `/messages` | New thread: managers send `recipient_key` (`central` or `user:{id}`); others use legacy `receiver_id` where applicable |
| GET | `/messages/{message}` | Participant in thread |
| POST | `/messages/{message}/reply` | Participant |

The central-support **proxy** user is created in the tenant database on first use; it is excluded from **Owner → Users** management.

## Update System

Central update endpoints:

- `GET /system-updates/check`
- `GET /system-updates/download`
- `GET /system-updates/checksum`

To publish a companion SHA-256 checksum for the central package:

- `php artisan system-updates:publish-checksum`

Tenant owner/admin update page (tenant host or central public port):

- `GET /owner/settings/updates` (`owner.settings.updates.index`)
- `POST /owner/settings/updates/apply` (`owner.settings.updates.apply`)

Central admin releases UI:

- `GET /admin/system-updates` (`admin.updates.index`)

Persistent update logs are stored in `update_logs` (landlord connection), including check status and install acknowledgement timestamps.

## Technology transfer

Full inventory of database tables, enumerated lists (roles, plans, statuses, permissions), seed catalogs, environment keys, and demo data markers:

- [`docs/technology-transfer-data-inventory.md`](docs/technology-transfer-data-inventory.md)

## Deployment Notes

- Set APP_ENV=production and APP_DEBUG=false in .env
- Run php artisan config:cache and php artisan route:cache
- Ensure storage and bootstrap/cache are writable
- Use a process manager for queue workers if queue processing is enabled

## Responsive QA

The UI targets **320px through 4K UHD (3840×2160)** using shared tokens in `resources/css/responsive-tokens.css` and components in `resources/css/app.css`.

### Layout tokens

| Token | Value | Use |
|-------|-------|-----|
| `--app-content-max` | `80rem` | Default main column (guest, forms) |
| `--app-content-max-wide` | `96rem` | Admin/owner dashboards, data pages |
| `--text-fluid-base` | clamp ~12–16px | Body text (smaller ≤768px; capped ≥1920px) |
| `--app-page-pad-inline` | fluid | Page side padding (tighter on phones) |

### Screen-size scaling (typography, tables, cards)

Tokens in `responsive-tokens.css` (mirrored in `partials/typography-system.blade.php` for Blade-only pages) change at **≤768px**, **≤480px**, and **≥1920px**:

| Token | Use |
|-------|-----|
| `--text-fluid-xs` … `--text-fluid-3xl` | Headings and UI copy |
| `--app-table-font`, `--app-table-pad-y/x`, `--app-table-header-font` | All data tables (use `.app-data-table` + wrapper) |
| `--app-card-pad`, `--app-card-title`, `--app-card-price`, `--app-card-meta` | Listing/dashboard cards |
| `--app-card-media-ratio`, `--app-card-media-max-height` | Explore stay cards (shorter images on phones) |

Prefer `var(--text-fluid-sm)` and `var(--app-table-font)` in new CSS instead of fixed `px`/`rem` so size tracks the viewport.

### Markup patterns

- Centered main: wrap content in `.app-page-shell` or `.app-page-shell--wide` (admin/owner shells apply this on `.main-content`).
- Data tables: wrap `<table>` in `.app-table-responsive` and add class `app-data-table` on the table (horizontal scroll on small screens; fluid cell padding/font).

### Manual device matrix

| Preset | Resolution | Check |
|--------|------------|-------|
| Galaxy S10+ | 360×760 | Explore cards compact; table text ~10–11px |
| iPhone SE | 375×667 | No body horizontal scroll; nav drawer works |
| iPad | 768×1024 | Grids stack; tables scroll inside wrapper |
| Laptop | 1280×800 | Multi-column dashboards |
| 1080p TV | 1920×1080 | Content centered; nav not at screen edges |
| 4K TV | 3840×2160 | Same max content width as laptop; extra side padding only |

### Typography and tables

- Body text stays between ~12px and ~16px equivalent (fluid `clamp` scale).
- Tables keep column alignment; use wrapper scroll, not `display: block` on `<table>`.
- Long cell content uses `word-break` / `.app-data-table__cell-long`.

Run optional smoke tests:

~~~bash
php artisan test --filter=ResponsiveViewport
php artisan test tests/Feature/RoutingTest.php
~~~

`RoutingTest` covers central/tenant landing, login, owner dashboard redirects, and auth guards. Note: `GET /register` on the central app returns **302** to `/register/guest` by design.

## Recent changes (workspace)

Summary of the current uncommitted work in this repository (UI + routes refactor; safe to deploy after your usual QA):

### Routing

- Split monolithic `routes/web.php` and `_central_portal_routes.php` into `routes/central/*` and `routes/tenant/*` (move-only; same route map).
- `php artisan route:list` should still show all named routes; run `php artisan route:clear` if you use route caching.

### Responsive & navigation

- Added `resources/css/responsive-tokens.css` and wired tokens through `app.css`, `typography-system`, and portal shell partials.
- Unified mobile breakpoint at **768px** (nav height `3.5rem`, symmetric horizontal padding).
- Admin, owner, and client navbars: user pill dropdown (Profile / Logout); Settings removed from primary nav where applicable.
- Fixed owner dashboard CSS leak (nested `<style>` in included responsive partials).

### Owner portal (tenant host)

- **Dashboard**: full-width layout; monthly chart shows **bookings only** (revenue removed from chart); improved data tables and mobile card layout; edit links use relative URLs (`route(..., false)`).
- **My Properties** (`/owner/accommodations`): full-width grid + sidebar availability card.
- **Edit accommodation** (`/owner/accommodations/{id}/edit`): multi-section form, listing preview column, sticky save bar.
- **Messages** (`/messages`): owner inbox matches users-page shell; shared `messages-inbox-split` partial for owner and guest.
- **Room availability calendar**: borderless grid, status fills, full-width in sidebar.

### Guest / client portal

- Guest dashboard, stays browse, bookings, messages, and profile pages aligned to minimal full-viewport shells.
- Explore accommodations: tiered stay cards and filter partials.

### Admin / central

- Admin pages inherit responsive table wrappers and shell spacing tokens.
- Central public nav: burger + minimal styles aligned with tribal/portal nav.

### Backend (minor)

- `Owner/DashboardController`: trend API returns bookings series only (no revenue in chart payload).
- `config/about_team.php`: tourism officer image path correction.

### Tests

- `tests/Feature/ResponsiveViewportTest.php`: viewport meta, explore cards, owner dashboard/messages/edit layout markers.

After pulling these changes locally: `npm run build` (or `npm run dev`), hard-refresh the browser, and smoke-test `http://{tenant}.localhost:8000/owner/*` plus central `localhost:8000`.

## License

This project uses the MIT License.
