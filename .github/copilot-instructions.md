## Purpose

This file gives concise, actionable guidance to an AI coding agent working on the Villa Panjalu PHP site so it can be immediately productive.

## Big picture
- Simple server-rendered PHP site (no framework). Each top-level page (e.g. `index.php`, `booking.php`, `payment.php`) is a standalone PHP file that includes shared pieces (`navbar.php`, `header.php`).
- Data persistence: MySQL / MariaDB; connection configured in `config.php`. Schema and seed data are in `databases/villa_panjalu.sql`.
- Auth: session-based. `login.php` and `register.php` use `password_hash()` / `password_verify()` and prepared statements. `auth.php` enforces session+role checks.
- Booking flow: `booking.php` reads `villas` (SELECT) and `bookings` to build a datepicker (client-side). On submit it inserts into `bookings` and redirects users to WhatsApp for confirmation.

## Key files (entry points & examples)
- `config.php` — database connection object ($conn). Central for all DB operations.
- `databases/villa_panjalu.sql` — full schema (tables: `users`, `villas`, `bookings`). Import this when running locally.
- `login.php`, `register.php` — show auth patterns: prepared statements, password hashing.
- `auth.php` — session/role guard used by pages that require a logged-in user.
- `booking.php` — booking logic example: fetching booked dates, using jQuery UI datepicker, inserting bookings, and setting `payment_status` to `pending`.
- `payment.php` — updates `bookings.payment_status` to `completed` for pending bookings.
- `navbar.php`, `header.php` — shared UI partials; include these to keep layout consistent.

## Project-specific patterns & conventions
- Includes: pages use `include`/`require` for `config.php`, `navbar.php`, `auth.php`. Follow the same pattern when adding pages.
- DB access: prefer prepared statements for any user-supplied values (project already uses `$conn->prepare()` in most places). You may see direct `$conn->query("SELECT * FROM villas")` for safe, read-only queries without user input.
- Sessions: session_start() is used in `auth.php` and `login.php`. Guard functions check `$_SESSION['user_id']` and `$_SESSION['role']`.
- Notifications/UI: many pages echo simple JS alerts for user feedback; keep consistency when adding new messages.
- Payment methods: stored as lowercase strings in DB (`bookings.payment_method`). Supported values: `'qris'`, `'dana'`, `'ovo'`, `'gopay'`. When displaying, use `formatPaymentMethod()` in `admin_dashboard.php` to get proper capitalization (e.g., "QRIS", "Dana", "OVO", "Gopay").

## How to run locally (discoverable from repo contents)
1. Import `databases/villa_panjalu.sql` into a local MySQL/MariaDB instance (phpMyAdmin or mysql CLI).
2. Configure database settings in `config.php` (default in repo: host=localhost, user=root, empty password, dbname=villa_panjalu).
3. Serve the `villa/` folder with PHP (e.g., place in XAMPP htdocs or run `php -S localhost:8000` from the `villa` directory in a shell).

## Editing guidance and examples (concrete)
- Add a new page: create `my_page.php`, at top do `require 'auth.php'; include 'config.php'; include 'navbar.php';` then output HTML. Use prepared statements when accepting POST input.
- Add a DB column: update `databases/villa_panjalu.sql` and apply an ALTER TABLE in migrations; update places selecting that table (e.g., `booking.php` or villa listing pages).
- New booking validation: reuse the booking date check in `booking.php` — fetch booked dates from `bookings` and use `in_array()` on the server side before inserting.
- Add a new payment method: (1) add `<option value="newmethod">Display Name</option>` in `booking.php` dropdown, (2) add case in `formatPaymentMethod()` function in `admin_dashboard.php` for proper display formatting.

## Integration points & external dependencies
- WhatsApp confirmation: `booking.php` builds a `wa.me` URL and redirects users to it after booking. Keep this flow when altering booking logic.
- Front-end: CSS in `css/` and JS in `js/navbar.js`; jQuery and jQuery UI are loaded from CDNs on `booking.php`.

## Security and correctness notes (from code inspection)
- Auth uses `password_hash()` and `password_verify()` — good. Maintain prepared statements when handling user input.
- `config.php` exposes DB credentials for local dev; do not hardcode production secrets. If adding environment-specific logic, prefer reading from environment variables.
- Some read-only queries use `$conn->query()` without preparation (acceptable for static selects), but always use prepared statements for anything containing user input.

## Quick checks for PRs from an AI agent
- Ensure any new SQL that accepts user input uses prepared statements and binds parameters.
- If you change booking/payment flows, run through the booking UX: create a user, place a booking, verify `bookings.payment_status` transitions from `pending` to `completed`.
- Keep layout includes (`navbar.php`, `header.php`) unchanged unless modifying site-wide markup; test pages visually.

## Payment flow deep dive

### Automated QRIS Payment (NEW)
When configured, the system can automatically generate QRIS codes and verify payments:

1. **Configuration** (`payment_config.php`): Set Midtrans API credentials (sandbox or production).
2. **QRIS Generation** (`booking.php`): If user selects QRIS and gateway is configured, system calls `PaymentGateway::generateQRIS()` to create QR code via Midtrans API.
3. **QR Display**: Modal shows QR code image for user to scan with any e-wallet app. Status is `'awaiting_payment'`.
4. **Payment Verification** (`payment_notification.php`): Webhook receives Midtrans notifications when payment completes. Auto-updates `payment_status` to `'completed'` and sets `paid_at` timestamp.
5. **Payment Logs**: All payment events logged to `payment_logs` table for audit trail.

### Manual Payment Flow (Fallback)
For Dana/OVO/GoPay or when QRIS gateway not configured:

1. **User booking** (`booking.php`): User selects villa, date, and payment method. Booking inserted with `payment_status='pending'`.
2. **WhatsApp redirect**: User redirected to WhatsApp (`wa.me/6289506892023`) with pre-filled message for manual confirmation.
3. **Admin review** (`admin_dashboard.php`): Admin manually approves (changes status to `'completed'`) or deletes bookings.

### Payment Status States
- `'pending'`: Awaiting manual admin approval
- `'awaiting_payment'`: QRIS generated, waiting for user to scan and pay
- `'completed'`: Payment received (auto via webhook or manual admin approval)
- `'expired'`: QRIS payment expired or cancelled
- `'cancelled'`: Booking cancelled by user or admin

## Refund System (NEW)

The system now supports automated refund requests and admin approval workflow:

1. **Refund Eligibility**: Users can request refunds for completed bookings at least 48 hours before check-in date (configurable via `REFUND_ALLOWED_HOURS_BEFORE` in `payment_config.php`).
2. **Refund Amount**: Default is 50% of booking price (configurable via `REFUND_PERCENTAGE`).
3. **User Request** (`refund_request.php`): User fills refund reason, system creates entry in `refunds` table with status `'pending'`.
4. **Admin Review** (`admin_dashboard.php?tab=refunds`): Admin can approve or reject refund requests with optional notes.
5. **Refund Processing**: When approved, system can call `PaymentGateway::processRefund()` to auto-refund via Midtrans, or admin processes manually.

### Refund Status States
- `'none'`: No refund requested (default)
- `'requested'`: User submitted refund request
- `'approved'`: Admin approved, pending processing
- `'rejected'`: Admin rejected with reason
- `'completed'`: Refund processed and completed

### Key Files for Refund
- `refund_request.php`: User-facing refund request page
- `databases/migration_add_payment_features.sql`: Database schema for refunds table
- `payment_config.php`: Refund policy constants (hours, percentage)
- `admin_dashboard.php`: Admin refund approval UI (tab=refunds)

## Payment Gateway Integration

### Setup Steps
1. **Get Midtrans credentials**: Register at https://dashboard.midtrans.com/ (use sandbox for testing)
2. **Configure** `payment_config.php`: Set `MIDTRANS_SERVER_KEY` and `MIDTRANS_CLIENT_KEY`
3. **Database migration**: Run `databases/migration_add_payment_features.sql` to add QRIS and refund columns
4. **Webhook URL**: Configure `https://yourdomain.com/payment_notification.php` in Midtrans dashboard
5. **Test**: Make booking with QRIS, scan code in sandbox app, verify auto-status update

### Payment Gateway Library (`payment_gateway.php`)
- `PaymentGateway::generateQRIS()`: Create QRIS payment and get QR code URL
- `PaymentGateway::checkPaymentStatus()`: Query payment status from Midtrans
- `PaymentGateway::processRefund()`: Submit refund to payment gateway
- `PaymentGateway::verifySignature()`: Validate webhook notifications

### Fallback Mode
If `payment_config.php` credentials not set, system falls back to manual WhatsApp confirmation. Check with `isPaymentGatewayConfigured()`.

## Where to look for more context
- Start with `config.php`, `login.php`, `register.php`, `booking.php`, `payment.php`, and `databases/villa_panjalu.sql` — these reveal the DB model and core flows.
- For payment method handling: see `booking.php` (dropdown options), `admin_dashboard.php` (`formatPaymentMethod()` function), and `databases/villa_panjalu.sql` (`bookings.payment_method` column).
- For QRIS automation: `payment_config.php` (config), `payment_gateway.php` (integration), `payment_notification.php` (webhook), and migration SQL.
- For refunds: `refund_request.php` (user UI), `admin_dashboard.php?tab=refunds` (admin UI), `databases/migration_add_payment_features.sql` (schema).

## Setup Checklist for New Developers

1. **Database**: Import `databases/villa_panjalu.sql` then run `databases/migration_add_payment_features.sql`
2. **Config**: Set DB credentials in `config.php`
3. **Payment (Optional)**: Set Midtrans keys in `payment_config.php` for QRIS automation
4. **Test**: Create user, make booking, test QRIS (if configured) or WhatsApp flow
5. **Admin**: Login as admin, test approve/reject bookings and refunds

If anything in this draft is unclear or you want me to expand examples (e.g., show a concrete prepared-statement template or a small migration patch), tell me which section to expand and I will iterate.
