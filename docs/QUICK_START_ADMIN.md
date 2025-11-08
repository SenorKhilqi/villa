# Quick Start Guide - Villa Panjalu Admin

## 🚀 Akses Cepat

### URLs Penting
```
User Portal:     http://localhost/villa/
Admin Dashboard: http://localhost/villa/admin_dashboard.php
Refund Requests: http://localhost/villa/refund_request.php
Payment Webhook: http://localhost/villa/payment_notification.php
```

### Default Login
```
Admin:
- Username: admin
- Password: admin123

Test User:
- Username: khilqi
- Password: 12345678
```

## 📊 Status Booking

| Status | Keterangan | Tindakan |
|--------|------------|----------|
| 🟡 **pending** | Baru dibuat, belum bayar | Tunggu pembayaran |
| 🔵 **awaiting_payment** | QR Code generated | Tunggu scan QR |
| 🟢 **completed** | Sudah dibayar | Booking selesai |
| 🔴 **expired** | Pembayaran timeout (30 menit) | Booking gagal |
| ⚫ **cancelled** | Dibatalkan user/admin | Booking batal |

## 💰 Status Refund

| Status | Keterangan | Tindakan |
|--------|------------|----------|
| 🟡 **none** | Tidak ada request | - |
| 🔵 **requested** | User minta refund | Review & approve/reject |
| 🟢 **approved** | Refund disetujui | Transfer dana ke user |
| 🔴 **rejected** | Refund ditolak | Sudah selesai |
| ✅ **processed** | Refund sudah ditransfer | Sudah selesai |

## 🛠️ Admin Tasks

### 1. Cek Booking Baru
```
Dashboard → Tab "Bookings" → Filter by "awaiting_payment"
```

### 2. Approve Refund
```
1. Dashboard → Tab "Refund Requests"
2. Klik "Detail" untuk lihat info lengkap
3. Cek:
   - Alasan refund
   - Metode pengembalian (Dana/OVO/Bank)
   - Nama & nomor akun
4. Klik "Approve" atau "Reject"
```

### 3. Transfer Refund
```
Setelah approve:
1. Catat metode, nama, nomor akun dari detail
2. Transfer sesuai jumlah refund
3. Update status ke "processed" (manual)
```

## 💳 Payment Flow

### User Pilih QRIS
```
1. User booking → Pilih QRIS
2. System generate QR Code via Midtrans
3. User scan QR dengan e-wallet
4. Webhook update status auto → "completed"
```

### User Pilih WhatsApp
```
1. User booking → Pilih WhatsApp
2. Redirect ke WhatsApp admin
3. Admin konfirmasi manual
4. Admin update status di dashboard
```

## 🔍 Troubleshooting Admin

### Booking Stuck di "Awaiting Payment"
**Penyebab:** User tidak bayar dalam 30 menit  
**Fix:** Status auto jadi "expired" (cek webhook logs)

### Refund Not Showing
**Penyebab:** Migration belum dijalankan  
**Fix:** 
```bash
php run_refund_details_migration.php
```

### Payment Webhook Error
**Penyebab:** Signature tidak valid  
**Fix:** 
1. Cek `payment_logs` table
2. Verify `MIDTRANS_SERVER_KEY` di `payment_config.php`
3. Test webhook: https://dashboard.sandbox.midtrans.com/

### Can't Approve Refund
**Penyebab:** JavaScript error  
**Fix:**
1. F12 → Console → Cek error
2. Clear browser cache
3. Reload page

## 📱 WhatsApp Template Messages

### Konfirmasi Booking
```
Halo {nama_user},

Booking Anda telah dikonfirmasi:
- Villa: {villa_name}
- Tanggal: {booking_date}
- Harga: Rp{price}

Terima kasih! 🏡
```

### Refund Approved
```
Halo {nama_user},

Refund Anda telah disetujui:
- Jumlah: Rp{refund_amount}
- Metode: {refund_method}
- Nomor: {account_number}

Dana akan ditransfer dalam 1-3 hari kerja.

Terima kasih! 💰
```

### Refund Rejected
```
Halo {nama_user},

Mohon maaf, refund Anda ditolak:
- Alasan: {admin_notes}

Jika ada pertanyaan, silakan hubungi kami.
```

## 📊 Database Query Cepat

### Cek Payment Logs
```sql
SELECT * FROM payment_logs 
WHERE DATE(created_at) = CURDATE()
ORDER BY created_at DESC;
```

### Booking Hari Ini
```sql
SELECT b.id, u.username, v.name, b.payment_status, b.refund_status
FROM bookings b
JOIN users u ON b.user_id = u.id
JOIN villas v ON b.villa_id = v.id
WHERE DATE(b.booking_date) = CURDATE();
```

### Refund Pending
```sql
SELECT r.*, b.booking_date, u.username, v.name
FROM refunds r
JOIN bookings b ON r.booking_id = b.id
JOIN users u ON r.requested_by_user_id = u.id
JOIN villas v ON b.villa_id = v.id
WHERE r.refund_status = 'pending'
ORDER BY r.requested_at DESC;
```

## 🔐 Security Checklist

- [ ] Ganti password admin default
- [ ] Set `MIDTRANS_IS_PRODUCTION = true` untuk production
- [ ] Gunakan HTTPS di production
- [ ] Backup database regular
- [ ] Monitor `payment_logs` untuk aktivitas aneh
- [ ] Restrict access ke `admin_dashboard.php` (by IP jika perlu)

## 📞 Support

**Developer:** Khilqi  
**WhatsApp:** +62 895-0689-2023  
**GitHub:** @SenorKhilqi

---
Last Updated: November 2025
