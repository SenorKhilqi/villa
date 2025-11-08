# 🚨 Quick Fix: Error "Unknown Merchant server_key/id"

## Masalah
Anda mendapat error: **"Booking berhasil, namun gagal generate QRIS: Unknown Merchant server_key/id"**

## Penyebab
Mismatch antara **credentials type** dan **mode setting**:
- Anda pakai **Production key** (format: `Mid-server-[production-key]`) 
- Tapi mode diset ke **Sandbox** (`MIDTRANS_IS_PRODUCTION = false`)

## Solusi Cepat (Pilih Salah Satu)

### ✅ Opsi 1: Gunakan Sandbox (REKOMENDASI untuk Testing)

1. **Dapatkan Sandbox credentials:**
   - Buka: https://dashboard.sandbox.midtrans.com/
   - Login → Settings → Access Keys
   - Copy **Server Key** (awalan `SB-Mid-server-...`)
   - Copy **Client Key** (awalan `SB-Mid-client-...`)

2. **Edit `payment_config.php`:**
   ```php
   define('MIDTRANS_SERVER_KEY', 'SB-Mid-server-xxxxx'); // Paste Sandbox Server Key
   define('MIDTRANS_CLIENT_KEY', 'SB-Mid-client-xxxxx'); // Paste Sandbox Client Key
   define('MIDTRANS_IS_PRODUCTION', false); // Tetap false
   ```

3. **Test:**
   - Buka: `http://localhost/villa/test_midtrans.php`
   - Harus muncul ✅ "Terkonfigurasi"

### ⚠️ Opsi 2: Gunakan Production (Charge Real Money!)

Jika tetap ingin pakai production key yang sudah ada:

1. **Edit `.env` file:**
   ```env
   MIDTRANS_SERVER_KEY=[YOUR-PRODUCTION-SERVER-KEY]
   MIDTRANS_CLIENT_KEY=[YOUR-PRODUCTION-CLIENT-KEY]
   MIDTRANS_IS_PRODUCTION=true
   ```

2. **⚠️ WARNING:** 
   - Ini akan charge pembayaran REAL
   - User harus bayar dengan uang sungguhan
   - Tidak recommended untuk testing

### 🔧 Opsi 3: Tidak Pakai Midtrans (Manual WhatsApp)

Jika tidak ingin setup Midtrans sama sekali:

1. **Edit `payment_config.php`:**
   ```php
   define('MIDTRANS_SERVER_KEY', 'YOUR_SANDBOX_SERVER_KEY_HERE');
   define('MIDTRANS_CLIENT_KEY', 'YOUR_SANDBOX_CLIENT_KEY_HERE');
   ```

2. **Sistem akan otomatis fallback** ke WhatsApp manual confirmation (seperti sebelumnya)

## Verify Setup

Buka di browser: **`http://localhost/villa/test_midtrans.php`**

Harus muncul:
- ✅ Status: **Terkonfigurasi**
- ✅ Mode: **SANDBOX** atau **PRODUCTION**
- ✅ Format: **Benar**

## Test Booking

1. Login sebagai user
2. Booking villa → Pilih **QRIS**
3. Harus muncul **QR Code modal** (tidak ada error)
4. Scan dengan Midtrans Simulator untuk test payment

## Troubleshooting

**Masih error?**
- ✅ Check apakah key sudah benar (tidak ada spasi extra)
- ✅ Pastikan mode (`MIDTRANS_IS_PRODUCTION`) sesuai dengan jenis key
- ✅ Regenerate key di Midtrans dashboard jika perlu

**Simulator dimana?**
- Web: https://simulator.sandbox.midtrans.com/
- Mobile: https://simulator.sandbox.midtrans.com/ (download link)

## File Terkait

- `payment_config.php` - File konfigurasi
- `test_midtrans.php` - Test page untuk verify setup
- `HOW_TO_GET_MIDTRANS_CREDENTIALS.md` - Tutorial lengkap

---

**Need help?** Baca file: `HOW_TO_GET_MIDTRANS_CREDENTIALS.md`
