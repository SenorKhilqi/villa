# Villa Situ Lengkong Panjalu - Payment & Refund System

Sistem booking villa dengan fitur pembayaran QRIS otomatis dan pengembalian dana (refund).

## 🎯 Fitur Baru

### 1. QRIS Payment Otomatis
- Generate QR Code otomatis via Midtrans API
- User scan QR dengan app e-wallet apapun (Dana, OVO, GoPay, ShopeePay, dll)
- Verifikasi pembayaran otomatis via webhook
- QR code expired otomatis setelah 30 menit

### 2. Sistem Refund
- User bisa request refund untuk booking yang sudah dibayar
- Minimal 48 jam sebelum tanggal check-in
- Refund 50% dari total pembayaran (configurable)
- Admin review dan approve/reject refund
- Tracking lengkap di database

## 📋 Instalasi

### 1. Database Setup

Jalankan SQL migrations secara berurutan:

```bash
# 1. Import schema dasar
mysql -u root -p villa_panjalu < databases/villa_panjalu.sql

# 2. Jalankan migration untuk fitur payment & refund
mysql -u root -p villa_panjalu < databases/migration_add_payment_features.sql
```

### 2. Konfigurasi Database

Edit `config.php`:
```php
$host = "localhost";
$user = "root";
$password = "your_password";
$dbname = "villa_panjalu";
```

### 3. Konfigurasi Payment Gateway (Opsional)

Untuk mengaktifkan QRIS otomatis, daftar di [Midtrans](https://dashboard.midtrans.com/):

**Sandbox (Testing):**
```php
// Edit payment_config.php
define('MIDTRANS_SERVER_KEY', 'SB-Mid-server-xxxxxxxxxxxxx');
define('MIDTRANS_CLIENT_KEY', 'SB-Mid-client-xxxxxxxxxxxxx');
define('MIDTRANS_IS_PRODUCTION', false);
```

**Production:**
```php
define('MIDTRANS_SERVER_KEY', 'Mid-server-xxxxxxxxxxxxx');
define('MIDTRANS_CLIENT_KEY', 'Mid-client-xxxxxxxxxxxxx');
define('MIDTRANS_IS_PRODUCTION', true);
```

### 4. Setup Webhook

Configure webhook URL di Midtrans Dashboard → Settings → Configuration:

```
Payment Notification URL: https://yourdomain.com/payment_notification.php
```

### 5. Refund Policy (Opsional)

Edit `payment_config.php` untuk mengatur policy refund:

```php
// User bisa refund berapa jam sebelum check-in?
define('REFUND_ALLOWED_HOURS_BEFORE', 48); // 48 jam = 2 hari

// Persentase refund dari total pembayaran
define('REFUND_PERCENTAGE', 50); // 50%
```

## 🚀 Cara Pakai

### User Flow - Booking dengan QRIS

1. **Login** sebagai user
2. Pilih **Booking Villa**
3. Pilih villa, tanggal, dan **metode pembayaran QRIS**
4. **Submit** → Modal muncul dengan QR Code
5. **Scan QR** dengan app e-wallet
6. **Konfirmasi pembayaran** di app
7. Status booking **otomatis berubah** jadi "Completed"

### User Flow - Request Refund

1. **Login** sebagai user
2. Buka menu **Refund**
3. Pilih booking yang ingin di-refund
4. Isi **alasan refund**
5. **Submit** → Status jadi "Menunggu Review"
6. Admin akan review dan approve/reject

### Admin Flow - Manage Bookings

1. **Login** sebagai admin
2. Buka **Admin Dashboard**
3. Tab **Bookings**: approve/delete bookings manual
4. Tab **Refund Requests**: review dan approve/reject refunds

## 📁 Struktur File Baru

```
villa/
├── payment_config.php              # Konfigurasi payment gateway
├── payment_gateway.php             # Library integrasi Midtrans
├── payment_notification.php        # Webhook handler dari Midtrans
├── refund_request.php              # Halaman user request refund
├── databases/
│   └── migration_add_payment_features.sql  # Database migration
└── .github/
    └── copilot-instructions.md     # Updated dengan payment docs
```

## 🔄 Alur Payment Otomatis

```
User pilih QRIS
    ↓
booking.php generate QR via PaymentGateway::generateQRIS()
    ↓
QR Code ditampilkan di modal
    ↓
User scan & bayar di e-wallet
    ↓
Midtrans kirim notifikasi ke payment_notification.php
    ↓
Webhook verify signature & update payment_status = 'completed'
    ↓
User bisa lihat booking completed di home
```

## 🔄 Alur Refund

```
User request refund (refund_request.php)
    ↓
Insert ke table `refunds` dengan status 'pending'
    ↓
Admin review di admin_dashboard.php?tab=refunds
    ↓
Admin approve → status 'approved'
    ↓
PaymentGateway::processRefund() kirim refund via Midtrans
    ↓
Refund processed → status 'completed'
```

## 🛠 Testing

### Test QRIS di Sandbox

1. Set `MIDTRANS_IS_PRODUCTION = false`
2. Gunakan Midtrans Simulator untuk test payment:
   - Download: [Midtrans Simulator App](https://simulator.sandbox.midtrans.com/)
   - Scan QR code dari booking modal
   - Klik "Pay" di simulator
3. Webhook akan auto-update status

### Test Refund

1. Login sebagai user → booking villa → bayar
2. Buka menu **Refund** → request refund
3. Login sebagai admin → tab **Refund Requests**
4. Approve atau reject refund
5. Check status di user refund page

## 📊 Database Tables Baru

### Tabel `payment_logs`
Log semua event payment untuk audit:
```sql
booking_id, event_type, event_data, created_at
```

### Tabel `refunds`
Tracking detail refund requests:
```sql
id, booking_id, refund_amount, refund_reason, refund_status,
requested_by_user_id, processed_by_admin_id, admin_notes,
requested_at, processed_at
```

### Update Tabel `bookings`
Kolom baru:
- `qr_code_url`: URL QR code dari Midtrans
- `payment_reference`: Order ID untuk tracking
- `paid_at`: Timestamp pembayaran sukses
- `refund_status`: Status refund (none/requested/approved/rejected/completed)
- `refund_amount`: Jumlah refund
- `refund_reason`: Alasan user request refund
- `refund_requested_at`, `refund_processed_at`: Timestamps

## ⚙️ Konfigurasi Lanjutan

### Ganti Payment Gateway

Jika ingin pakai Xendit atau provider lain:
1. Edit `payment_gateway.php`
2. Ubah function `generateQRIS()` untuk call API provider baru
3. Update `payment_notification.php` untuk format webhook baru

### Custom Refund Logic

Edit `refund_request.php`:
```php
// Contoh: refund 100% jika > 7 hari sebelum check-in
$hours = $booking['hours_until_booking'];
if ($hours > 168) { // 7 hari = 168 jam
    $refund_amount = $booking['price']; // 100%
} else {
    $refund_amount = $booking['price'] * 0.5; // 50%
}
```

## 🔒 Security Notes

1. **Webhook Signature**: `payment_notification.php` verify signature dari Midtrans
2. **Prepared Statements**: Semua query pakai prepared statements
3. **Session Guards**: Refund pages hanya bisa diakses logged-in user
4. **Admin Only**: Approve/reject refund hanya bisa admin

## 📞 Support

Untuk pertanyaan atau issue:
- WhatsApp Admin: 6289506892023
- Check logs: `payment_notifications.log`

## 🎓 Resources

- [Midtrans Documentation](https://docs.midtrans.com/)
- [QRIS Standard](https://qris.id/)
- [PHP MySQLi Prepared Statements](https://www.php.net/manual/en/mysqli.prepare.php)

---

**Note**: Fitur QRIS otomatis memerlukan kredensial Midtrans. Tanpa konfigurasi, sistem akan fallback ke WhatsApp manual confirmation.
