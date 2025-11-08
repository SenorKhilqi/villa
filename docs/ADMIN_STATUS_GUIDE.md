# Status Guide - Villa Panjalu Admin Dashboard

## 📊 Payment Status (Kolom "Payment Status")

Status pembayaran menunjukkan kondisi transaksi booking:

### 🟡 Pending
- **Warna:** Orange
- **Arti:** Booking baru, menunggu admin approve secara manual
- **Kapan muncul:** Saat user booking dengan Dana/OVO/GoPay (non-QRIS)
- **Action:** Admin bisa klik "Approve" untuk konfirmasi

### 🔵 Awaiting Payment  
- **Warna:** Biru
- **Arti:** QR Code sudah di-generate, menunggu user scan & bayar
- **Kapan muncul:** Saat user pilih QRIS dan QR berhasil di-generate
- **Action:** 
  - Tunggu user bayar (otomatis update via webhook)
  - Atau admin bisa approve manual jika user sudah transfer

### ✅ Completed
- **Warna:** Hijau
- **Arti:** Pembayaran berhasil / sudah dikonfirmasi
- **Kapan muncul:** 
  - Otomatis setelah user bayar QRIS (via webhook)
  - Atau admin approve manual
- **Action:** Tidak ada, booking sudah selesai

### ⚫ Expired
- **Warna:** Abu-abu
- **Arti:** QR Code expired (lebih dari 30 menit tidak dibayar)
- **Kapan muncul:** Midtrans kirim notifikasi expire via webhook
- **Action:** User perlu booking ulang

### 🔴 Cancelled
- **Warna:** Merah
- **Arti:** Pembayaran dibatalkan/ditolak
- **Kapan muncul:** 
  - Payment gateway tolak transaksi
  - Admin cancel booking
- **Action:** User perlu booking ulang

---

## 💰 Refund Status (Kolom "Refund Status")

Status refund menunjukkan apakah user ajukan pengembalian dana:

### - (Dash)
- **Arti:** Tidak ada refund request
- **Status normal** untuk booking yang lancar

### 🟠 Refund Requested
- **Warna:** Kuning/Orange
- **Arti:** User sudah ajukan refund, menunggu admin review
- **Action:** Admin perlu buka tab "Refund Requests" untuk approve/reject

### 🔵 Refund Approved
- **Warna:** Biru
- **Arti:** Admin setujui refund, sedang diproses
- **Proses:** 3-7 hari kerja via payment gateway

### ✅ Refunded
- **Warna:** Hijau
- **Arti:** Refund berhasil, dana sudah dikembalikan ke user
- **Status final**

### 🔴 Refund Rejected
- **Warna:** Merah
- **Arti:** Admin tolak refund request
- **Alasan:** Bisa dilihat di tab "Refund Requests"

---

## 🎯 Kombinasi Status yang Umum

### Scenario 1: Booking Normal (QRIS)
1. User booking → **Awaiting Payment** + **-** (no refund)
2. User bayar → **Completed** + **-**
3. Selesai ✅

### Scenario 2: Booking Manual (Dana/OVO/GoPay)
1. User booking → **Pending** + **-**
2. Admin approve → **Completed** + **-**
3. Selesai ✅

### Scenario 3: User Minta Refund
1. Booking selesai → **Completed** + **-**
2. User request refund → **Completed** + **Refund Requested**
3. Admin approve → **Completed** + **Refund Approved**
4. Refund diproses → **Completed** + **Refunded**
5. Selesai ✅

### Scenario 4: QRIS Expired
1. User booking → **Awaiting Payment** + **-**
2. Tidak bayar 30 menit → **Expired** + **-**
3. User perlu booking ulang

---

## 🔧 Troubleshooting

### "AWAITING_PAYMENT" tapi button "Completed"?
**Sebelum fix:** Bug display - status badge dan button tidak sync
**Setelah fix:** Status badge sekarang akurat:
- `Awaiting Payment` → button "Approve" (bisa manual approve)
- `Completed` → button "Paid" (disabled)

### Kapan perlu manual approve?
- User pilih QRIS tapi **payment gateway tidak dikonfigurasi** → fallback ke `Pending`
- User pilih Dana/OVO/GoPay → selalu `Pending`
- User sudah transfer tapi webhook belum terima → bisa manual approve

### Kenapa ada 2 status (Payment & Refund)?
Karena bisa terjadi:
- Payment = `Completed` (user sudah bayar)
- Refund = `Requested` (user minta refund)

Kedua status independen dan penting untuk tracking!

---

## 📱 Quick Actions

### Sebagai Admin, saya perlu:

**1. Approve booking manual:**
- Tab: Bookings
- Filter: Status = "Pending" atau "Awaiting Payment"
- Action: Klik "Approve"

**2. Review refund requests:**
- Tab: Refund Requests
- Badge merah di tab = ada pending refunds
- Action: Klik "Approve" atau "Reject"

**3. Hapus booking error:**
- Tab: Bookings
- Action: Klik "Delete" (merah)

---

**Catatan:** Setelah update ini, tampilan admin dashboard sekarang lebih jelas dengan 2 kolom status terpisah dan color-coding yang konsisten! 🎨
