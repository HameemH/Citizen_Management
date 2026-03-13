# Citizen Management

Municipality-focused citizen services platform built with Laravel. The system supports role-based workflows for citizen verification, property lifecycle management, rental approvals, tax assessment/payment, complaints, and revenue analytics.

## Core Features
### 0) Create local `.env` first
```bash
cp .env.example .env
```


### 1) Authentication & Role Access
- Email/password registration and login.
- Role-based access separation (`admin`, `citizen`) with dedicated middleware.
- Citizen-only verified gate for protected municipal property/tax services.
### First-time setup (writes to your local `.env`)
### 2) Citizen Verification (NID Workflow)
docker run --rm --env-file .env -v ${PWD}/.env:/var/www/html/.env citizen-management:latest php artisan key:generate --force
docker run --rm --env-file .env -v ${PWD}/storage:/var/www/html/storage citizen-management:latest php artisan storage:link
docker run --rm --env-file .env citizen-management:latest php artisan migrate --force
```

### Run container
```bash
docker run -d --name citizen-app -p 8000:8000 --env-file .env -v ${PWD}/storage:/var/www/html/storage -v ${PWD}/database:/var/www/html/database citizen-management:latest
- NID prefill from local `fake_nids` registry.
- Admin verification queue with approve/reject actions.
- Verification certificate PDF download (with QR code).
- Public signed verification status URL for QR validation.
- API endpoint for NID status lookup: `GET /api/nid/{nidNumber}`.
### View logs / stop app
### 3) Property Management
docker logs -f citizen-app
docker stop citizen-app
docker rm citizen-app
  - Add property
  - Update property
  - Transfer ownership by target email
- Admin request approval/rejection pipeline.
- Property transfer completion notifications via email.


`--rm` removes a container after it exits. Use `-d --name citizen-app` when you want a long-running reusable container.

## Docker Compose

`docker-compose.yml` is included for `app + mysql`.

### Start
```bash
docker compose up -d --build
```

### First-time setup (Compose)
```bash
docker compose exec app php artisan key:generate --force
docker compose exec app php artisan storage:link
docker compose exec app php artisan migrate --force
```

### Logs
```bash
docker compose logs -f app
```

### Stop
```bash
docker compose down
```

### Reset database volume
```bash
docker compose down -v
```
### 4) Rental Workflow
- Citizens can submit rental requests for available properties.
- Property owners confirm rental terms.
- Admin approves/rejects finalized rental requests.
- System auto-generates rent agreements with agreement number and terms.
- Citizen/admin agreement listing and detail views.

### 5) Tax Assessment & Payment
- Tax assessment generation per property + fiscal year using configurable rates.
- Issue assessments, track due dates, and record manual payments.
- Citizen tax dashboard with outstanding totals and recent payments.
- Stripe Checkout integration for online tax payment.
- Automatic payment reconciliation and citizen receipt view.

### 6) Revenue Dashboard & Exports
- KPIs: issued, collected, outstanding, collection rate, avg days to pay.
- Monthly issued vs collected trend.
- Delinquent owners ranking.
- Aging buckets for overdue assessments.
- Upcoming property valuation tracking.
- CSV exports for tax reconciliation and valuation schedules.

### 7) Complaint Management
- Citizens can submit complaints (optionally linked to owned property) with attachment.
- Admin can filter, review, and update complaint status (`open`, `in_progress`, `resolved`) with reply.

### 8) Overdue Tax Flagging Command
- Artisan command to flag overdue issued assessments and notify owners:
  - `php artisan tax:flag-overdue`
  - `php artisan tax:flag-overdue --dry-run`

## Tech Stack

### Backend
- PHP `^8.2`
- Laravel `^11`
- Eloquent ORM + migrations
- Notification system (mail channel)

### Payments, Docs & Reporting
- Stripe PHP SDK (`stripe/stripe-php`) for Checkout payments
- DomPDF (`barryvdh/laravel-dompdf`) for verification certificate PDF
- Native CSV streaming exports

### Frontend
- Blade templates
- Vite `^5`
- Tailwind CSS `^3`
- Axios

### Data & Runtime
- Default DB: SQLite (configurable to MySQL/MariaDB/PostgreSQL/SQL Server)
- Queue/session/cache drivers configurable via `.env`

## Architecture

The codebase follows a layered Laravel architecture:

1. **Routing Layer** (`routes/web.php`, `routes/api.php`)
	- Defines citizen/admin/web/API entry points.
2. **Middleware Layer** (`admin`, `citizen`, `verified.citizen`)
	- Enforces role and verification access policy.
3. **Controller Layer** (`app/Http/Controllers`)
	- Handles request validation and orchestration.
4. **Service Layer** (`app/Services`)
	- Domain/business logic for tax generation, Stripe checkout, revenue analytics.
5. **Domain Models** (`app/Models`)
	- Eloquent entities for users, properties, rental workflow, taxes, complaints.
6. **Notification Layer** (`app/Notifications`)
	- Event-driven email notifications (transfer complete, overdue tax reminder).
7. **Presentation Layer** (`resources/views`)
	- Blade UI for citizen and admin dashboards.

### Main Domain Entities
- `User`
- `FakeNid`
- `Property`
- `PropertyRequest`
- `RentalRequest`
- `RentAgreement`
- `TaxAssessment`
- `TaxPayment`
- `Complaint`

## Local Setup

### Prerequisites
- PHP 8.2+
- Composer
- Node.js 18+ and npm
- Database server (optional if using SQLite)

### Install & Run
```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan storage:link
npm run build
php artisan serve
```

App runs at: `http://127.0.0.1:8000`

## Environment Notes

Important variables for enabled features:
- `APP_URL`
- `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
- `MAIL_MAILER` and related mail credentials (for notifications)
- `STRIPE_KEY`, `STRIPE_SECRET`, `STRIPE_CURRENCY`
- `STRIPE_SUCCESS_URL`, `STRIPE_CANCEL_URL` (optional overrides)

Tax rate and valuation settings are in `config/tax.php`.

## Docker

Run the project using **one workflow**: Docker Compose.

### Step-by-step commands

1) Create `.env` (first time only):
```bash
cp .env.example .env
```

2) Update DB settings in `.env` for Compose MySQL:
```env
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=citizen_management
DB_USERNAME=citizen
DB_PASSWORD=citizenpass
```

3) Build and start services:
```bash
docker compose up -d --build
```

4) Generate app key:
```bash
docker compose exec app php artisan key:generate --force
```

5) Create storage symlink:
```bash
docker compose exec app php artisan storage:link
```

6) Run database migrations:
```bash
docker compose exec app php artisan migrate --force
```

7) Open the app:
`http://localhost:8000`

### Useful commands

```bash
docker compose logs -f app
docker compose down
docker compose down -v
```
