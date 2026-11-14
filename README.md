# StayCore — Hotel Management & Booking Platform (PHP Full-Stack)

Odoo-style hotel ecosystem with **20 connected pages**, glassmorphism dark UI, RBAC, Stripe payments, PDF invoices, WebSocket real-time sync, and OTA channel management.

## 🚀 Quick Setup

```bash
# 1. Copy folder into web root (e.g., htdocs/staycore)
# 2. Create database and import schema
mysql -u root -p staycore_db < sql/schema.sql

# 3. Configure DB credentials in config/database.php

# 4. Install PHP dependencies (Stripe + DomPDF)
composer install

# 5. Start WebSocket server (for real-time room status)
php websocket_server.php   # runs on ws://localhost:8080

# 6. Serve the app
php -S localhost:8000 -t staycore
# Visit: http://localhost:8000/staycore/
```

## 🔑 Sample Login Credentials (from `sql/schema.sql`)

| Email | Password | Role |
|-------|----------|------|
| admin@staycore.com | password | Admin |
| manager@staycore.com | password | Manager |
| ravi@staycore.com | password | Staff |
| guest@staycore.com | password | Guest |

## 📄 All 20 Pages

| # | Page | File | Role |
|---|------|------|------|
| 1 | Landing / Home | `pages/index.php` | All |
| 2 | Rooms Catalog | `pages/rooms.php` | All |
| 3 | Room Detail | `pages/room_detail.php` | All |
| 4 | Checkout & Payment (Stripe) | `pages/checkout.php` | Guest |
| 5 | My Bookings | `pages/my_bookings.php` | Guest |
| 6 | Guest Dashboard | `pages/guest_dashboard.php` | Guest |
| 7 | Front Desk Kanban | `pages/front_desk.php` | Staff/Manager/Admin |
| 8 | Housekeeping Hub | `pages/housekeeping.php` | Staff |
| 9 | POS - Restaurant | `pages/pos.php` | Staff/Manager |
| 10 | Inventory | `pages/inventory.php` | Staff/Manager |
| 11 | Staff Schedule | `pages/staff_schedule.php` | Manager/Admin |
| 12 | Invoicing Engine | `pages/invoicing.php` | Manager/Admin |
| 13 | Expense Tracker | `pages/expenses.php` | Manager/Admin |
| 14 | Analytics Dashboard | `pages/analytics.php` | Manager/Admin |
| 15 | AI Assistant | (stubbed in Guest Dashboard) | Guest |
| 16 | OTA Channel Manager | `pages/ota_sync.php` | Manager/Admin |
| 17 | Website CMS | `pages/cms.php` | Manager/Admin |
| 18 | Settings & Tax | `pages/settings.php` | Admin |
| 19 | Admin Control Center | `pages/admin.php` | Admin |
| 20 | Auth | `auth/login.php`, `auth/register.php` | All |

## 🛠️ Advanced Features Implemented

### Stripe Payments (`pages/checkout.php` + `api/payment_confirm.php`)
- Creates a Stripe `PaymentIntent` on checkout
- Stripe Elements card form on frontend
- Server-side payment verification before confirming reservation
- Test card: `4242 4242 4242 4242` (any future date, any CVC)
- Add real keys: `pk_test_...` / `sk_test_...` in `src/Payment.php` instantiation

### PDF Invoices (`src/InvoicePDF.php` + `?pdf=1`)
- Dompdf-based professional invoice PDF
- Download link on every invoice page: `invoice.php?id=X&pdf=1`
- Itemized charges, tourist tax (12%), total, guest & room details

### WebSocket Real-Time Sync (`websocket_server.php` + `src/WebSocket/RoomStatusServer.php`)
- Ratchet WebSocket server on port 8080
- Frontend opens WS connection; room status changes push instantly to all connected dashboards
- Fallback: 30-second AJAX polling if WS unavailable
- Run: `php websocket_server.php`

### OTA Channel Manager (`pages/ota_sync.php` + `src/OTA/`)
- Abstract `Connector` base class
- `BookingCom` and `Airbnb` concrete connectors
- `Manager` class to sync availability & price across all channels
- Demo mode shown; add real API keys via `.env` or config

## 🏗️ Tech Stack
- **Frontend:** HTML5, Tailwind CSS (CDN), custom glassmorphism CSS, vanilla JS, Stripe Elements
- **Backend:** PHP 8+ (PDO/MySQL), session auth + RBAC (Guest/Staff/Manager/Admin)
- **Database:** MySQL / MariaDB
- **Payments:** Stripe PHP SDK
- **PDF:** Dompdf
- **Real-time:** Ratchet WebSocket

## 🔐 Security
- `.htaccess` blocks direct access to `config/`, `sql/`, `api/` folders
- All mutations require login + correct role
- Passwords hashed with `password_hash()`
- Prepared statements throughout (PDO)

## 📞 Next Steps for Production
1. Replace Stripe test keys with live keys; handle `webhook` events
2. Configure SMTP for email confirmations
3. Add rate limiting & CSRF tokens
4. Deploy WebSocket server behind a process manager (Supervisor/systemd)
5. Add HTTPS/WSS for WebSocket in production