# Cara Mendapatkan Midtrans Sandbox Credentials

## Step 1: Daftar/Login ke Midtrans Sandbox

1. Buka: **https://dashboard.sandbox.midtrans.com/**
2. Login dengan akun Midtrans Anda
3. Atau register baru jika belum punya akun

## Step 2: Dapatkan Credentials

1. Setelah login, buka menu: **Settings** → **Access Keys**
2. Copy kredensial berikut:
   - **Server Key** (contoh: `SB-Mid-server-xxxxxxxxxxxxxxx`)
   - **Client Key** (contoh: `SB-Mid-client-xxxxxxxxxxxxxxx`)

## Step 3: Masukkan ke Config

Edit file `payment_config.php`:

```php
define('MIDTRANS_SERVER_KEY', 'SB-Mid-server-xxxxxxxxxxxxxxx'); // Paste Server Key Anda
define('MIDTRANS_CLIENT_KEY', 'SB-Mid-client-xxxxxxxxxxxxxxx'); // Paste Client Key Anda
define('MIDTRANS_IS_PRODUCTION', false); // Tetap false untuk sandbox
```

## Step 4: Test dengan Midtrans Simulator

**Cara Test Pembayaran QRIS di Sandbox:**

1. **Buat booking** di website → pilih metode **QRIS**
2. **QR Code akan muncul** di modal
3. **Gunakan Midtrans Simulator** untuk scan QR:
   
   **Option A: Simulator App (Mobile)**
   - Download: https://simulator.sandbox.midtrans.com/
   - Install di HP
   - Scan QR code dari layar komputer
   - Klik "Pay"
   
   **Option B: Simulator Web**
   - Buka: https://simulator.sandbox.midtrans.com/
   - Copy QR code URL atau transaction ID
   - Input di simulator web
   - Klik "Pay"

4. **Webhook otomatis update status** booking jadi "completed"

## Alternatif: Gunakan Production (Tidak Rekomendasiuntuk Testing)

Jika tetap ingin pakai production credentials, edit file `.env`:

```env
MIDTRANS_SERVER_KEY=[YOUR-PRODUCTION-SERVER-KEY]
MIDTRANS_CLIENT_KEY=Mid-client-YOUR-PRODUCTION-CLIENT-KEY
MIDTRANS_IS_PRODUCTION=true
```

⚠️ **Warning**: Production akan charge pembayaran real! Gunakan sandbox untuk testing.

## Verify Setup

Setelah konfigurasi, test dengan:

```php
// Buka file: test_midtrans.php (buat file baru)
<?php
require_once 'payment_config.php';
require_once 'payment_gateway.php';

echo "Midtrans Configured: " . (isPaymentGatewayConfigured() ? "YES" : "NO") . "\n";
echo "Mode: " . (MIDTRANS_IS_PRODUCTION ? "PRODUCTION" : "SANDBOX") . "\n";
echo "API URL: " . MIDTRANS_API_URL . "\n";
echo "Server Key (first 10 chars): " . substr(MIDTRANS_SERVER_KEY, 0, 20) . "...\n";
?>
```

Jalankan di browser: `http://localhost/villa/test_midtrans.php`

## Troubleshooting

**Error: "Unknown Merchant server_key/id"**
- ✅ Pastikan menggunakan **SANDBOX** credentials (awalan `SB-Mid-`)
- ✅ Pastikan `MIDTRANS_IS_PRODUCTION = false`
- ✅ Atau jika pakai production key, set `MIDTRANS_IS_PRODUCTION = true`

**Error: "Access denied"**
- ✅ Check apakah Server Key sudah benar (tanpa spasi extra)
- ✅ Regenerate key di Midtrans dashboard jika perlu

## Links Penting

- **Sandbox Dashboard**: https://dashboard.sandbox.midtrans.com/
- **Production Dashboard**: https://dashboard.midtrans.com/
- **Simulator**: https://simulator.sandbox.midtrans.com/
- **Docs**: https://docs.midtrans.com/

## Mode Tanpa Midtrans (Fallback Manual)

Jika tidak ingin setup Midtrans, biarkan credentials kosong:

```php
define('MIDTRANS_SERVER_KEY', 'YOUR_SANDBOX_SERVER_KEY_HERE');
define('MIDTRANS_CLIENT_KEY', 'YOUR_SANDBOX_CLIENT_KEY_HERE');
```

Sistem akan otomatis fallback ke **WhatsApp manual confirmation** seperti sebelumnya.
