# Villa Panjalu - Sistem Booking Villa

Aplikasi web untuk booking dan manajemen villa dengan fitur pembayaran otomatis (QRIS) dan sistem refund.

## 🚀 Fitur Utama

- ✅ **User Authentication** - Register, login dengan bcrypt password hashing
- 🏡 **Villa Booking** - Booking villa dengan kalender interaktif
- 💳 **Payment Gateway** - QRIS otomatis via Midtrans
- 💰 **Refund System** - Request refund dengan kebijakan 48 jam
- 👤 **User Dashboard** - Track booking dan refund status
- 🛠️ **Admin Panel** - Manage booking dan approve refund

## 📋 Requirements

- PHP 8.0+
- MySQL 5.7+ / MariaDB 10.3+
- Web Server (Apache/Nginx)
- Midtrans Account (untuk payment gateway)

## 🔧 Instalasi

### 1. Clone Repository
```bash
git clone https://github.com/SenorKhilqi/villa.git
cd villa
```

### 2. Setup Database
```bash
# Import schema
mysql -u root -p < databases/villa_panjalu.sql

# Run migrations untuk payment features
mysql -u root -p villa_panjalu < databases/migration_add_payment_features.sql
mysql -u root -p villa_panjalu < databases/migration_add_refund_details.sql
```

### 3. Konfigurasi Environment
```bash
# Copy environment example
cp .env.example .env

# Edit .env dengan credentials Anda
# - Database credentials
# - Midtrans API keys
# - WhatsApp admin number
```

### 4. Konfigurasi Payment Gateway
Edit `payment_config.php` atau set environment variables:
```php
MIDTRANS_SERVER_KEY=your_server_key
MIDTRANS_CLIENT_KEY=your_client_key
MIDTRANS_IS_PRODUCTION=false  // true untuk production
```

### 5. Setup Webhook (untuk production)
Daftarkan webhook URL di Midtrans Dashboard:
```
https://yourdomain.com/payment_notification.php
```

## 📁 Struktur Project

```
villa/
├── 📚 docs/                    # Documentation
│   ├── README_PAYMENT.md       # Payment gateway setup
│   ├── ADMIN_STATUS_GUIDE.md   # Booking status guide
│   ├── TAILWIND_MIGRATION_GUIDE.md  # Tailwind CSS guide
│   └── MODERNIZATION_SUMMARY.md     # System improvements
│
├── 🗄️ databases/               # Database schemas
│   └── villa_panjalu_complete.sql   # All-in-one schema ⭐
│
├── 📦 legacy/                  # Archived legacy files
│   └── databases/              # Old SQL migrations
│
├── 🎨 css/                     # Stylesheets
│   ├── common.css              # Reusable components
│   ├── navbar.css              # Navigation styles
│   └── style.css               # Base styles
│
├── 📜 js/                      # JavaScript files
│   └── navbar.js               # Navigation interactions
│
├── 🖼️ logo/                    # Assets & images
├── 🏡 bata_dukuh/              # Villa Bata Dukuh images
├── 🏡 kayu_hujung/             # Villa Kayu Hujung images
│
├── 🔧 includes/                # Reusable includes
│   └── head.php                # Tailwind CSS setup
│
├── 🔐 Core Files
│   ├── config.php              # Database configuration
│   ├── auth.php                # Authentication middleware
│   ├── payment_config.php      # Payment gateway config
│   ├── payment_gateway.php     # Midtrans integration
│   └── payment_notification.php # Webhook handler
│
├── 👤 User Pages
│   ├── index.php               # Landing page
│   ├── login.php / register.php
│   ├── booking.php             # Villa booking
│   ├── refund_request.php      # Cancellation request
│   └── villa_*.php             # Villa detail pages
│
└── 👨‍💼 Admin Pages
    └── admin_dashboard.php     # Admin panel
```

## 🎯 Usage

### User Flow
1. **Register/Login** → `register.php` / `login.php`
2. **Browse Villas** → `villa_kami.php`
3. **Booking** → `booking.php` → Pilih QRIS/WhatsApp
4. **Payment** → Scan QR Code → Auto-update status
5. **Refund Request** → `refund_request.php` (jika perlu)

### Admin Flow
1. **Login as Admin** → Username: `admin`, Password: `admin123`
2. **Dashboard** → `admin_dashboard.php`
3. **Tab Bookings** → View semua booking & payment status
4. **Tab Refunds** → Approve/Reject refund requests

## 🔐 Default Credentials

**Admin Account:**
- Username: `admin`
- Password: `admin123`

**Test User:**
- Username: `khilqi`
- Password: `12345678`

⚠️ **PENTING:** Ubah password default setelah instalasi!

## 💳 Payment Gateway Setup

### Sandbox Testing (Development)
1. Daftar di [Midtrans Sandbox](https://dashboard.sandbox.midtrans.com/)
2. Get Server Key & Client Key
3. Set `MIDTRANS_IS_PRODUCTION = false`

### Production
1. Upgrade ke [Midtrans Production](https://dashboard.midtrans.com/)
2. Get Production Keys
3. Set `MIDTRANS_IS_PRODUCTION = true`
4. Setup webhook URL

📖 Detail: Lihat `docs/README_PAYMENT.md`

## 💰 Refund Policy

- **Waktu**: Minimal 48 jam sebelum booking date
- **Jumlah**: 50% dari harga booking
- **Metode**: Dana, OVO, GoPay, Bank Transfer (BCA, Mandiri, BRI, BNI)
- **Proses**: Harus diapprove admin

## 🛠️ Helper Functions (payment_config.php)

```php
// Check if payment configured
isPaymentGatewayConfigured()

// Get base URL
getBaseUrl()

// Format currency
formatCurrency($amount)

// Calculate refund amount
calculateRefundAmount($original_amount)

// Check refund eligibility
isRefundAllowed($booking_date)
```

## 📊 Database Tables

- `users` - User accounts
- `villas` - Villa listings
- `bookings` - Booking records dengan payment & refund status
- `refunds` - Refund requests
- `payment_logs` - Payment webhook logs

## 🐛 Troubleshooting

### Payment Error
```
Error: Midtrans server key is not configured
```
**Fix:** Set `MIDTRANS_SERVER_KEY` di `payment_config.php`

### Webhook Not Working
1. Check webhook URL sudah terdaftar di Midtrans
2. Verify signature key di `payment_notification.php`
3. Check `payment_logs` table untuk debug

### Refund Not Showing
```sql
-- Check if migration ran
SHOW COLUMNS FROM bookings LIKE 'refund%';
SHOW TABLES LIKE 'refunds';
```

## 📚 Dokumentasi Lengkap

- [Payment Gateway Setup](docs/README_PAYMENT.md)
- [Admin Status Guide](docs/ADMIN_STATUS_GUIDE.md)
- [Get Midtrans Credentials](docs/HOW_TO_GET_MIDTRANS_CREDENTIALS.md)
- [Copilot Instructions](.github/copilot-instructions.md)

## 🤝 Contributing

1. Fork repository
2. Create feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Open Pull Request

## 📝 License

This project is for educational purposes.

## 👨‍💻 Developer

**Khilqi**
- GitHub: [@SenorKhilqi](https://github.com/SenorKhilqi)
- WhatsApp: +62 895-0689-2023

## 🙏 Credits

- [Midtrans](https://midtrans.com) - Payment Gateway
- [jQuery UI](https://jqueryui.com) - Date Picker
- [AOS](https://michalsnik.github.io/aos/) - Animations
