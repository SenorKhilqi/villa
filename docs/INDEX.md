# 📚 Dokumentasi Villa Panjalu

Daftar lengkap dokumentasi yang tersedia untuk sistem booking Villa Panjalu.

## � NEW! Modernization Complete (Nov 2025)

### [MODERNIZATION_SUMMARY.md](MODERNIZATION_SUMMARY.md) ⭐
**What's New!** - Complete modernization summary
- ✅ Database consolidated (3 files → 1 file)
- ✅ Tailwind CSS setup
- ✅ Common CSS library
- ✅ Migration guide
- 📊 Performance improvements (~30% faster)

### [TAILWIND_MIGRATION_GUIDE.md](TAILWIND_MIGRATION_GUIDE.md)
**Migration Guide** - Step-by-step Tailwind migration
- Class mapping (old CSS → Tailwind)
- Component examples
- Responsive patterns
- Tailwind cheatsheet

---

## �📖 Dokumentasi Utama

### [README.md](../README.md) 
**Start Here!** - Overview project, instalasi, dan penggunaan dasar
- Fitur utama
- Requirements & instalasi
- Struktur project
- Default credentials
- Troubleshooting umum

---

## 🎯 Quick Start Guides

### [QUICK_START_ADMIN.md](QUICK_START_ADMIN.md)
**Untuk Admin** - Panduan cepat mengoperasikan dashboard admin
- ✅ Akses URL penting
- ✅ Status booking & refund
- ✅ Approve/reject refund workflow
- ✅ WhatsApp message templates
- ✅ Database queries untuk monitoring
- ✅ Security checklist

---

## 💳 Payment Gateway

### [README_PAYMENT.md](README_PAYMENT.md)
**Setup Payment** - Panduan lengkap integrasi Midtrans
- Cara daftar Midtrans (Sandbox & Production)
- Konfigurasi Server Key & Client Key
- Setup webhook untuk auto-update
- Testing payment flow
- Error handling & troubleshooting

### [HOW_TO_GET_MIDTRANS_CREDENTIALS.md](HOW_TO_GET_MIDTRANS_CREDENTIALS.md)
**Get Credentials** - Langkah-langkah mendapatkan API keys
- Screenshot step-by-step
- Sandbox vs Production keys
- Where to find credentials di dashboard

### [QUICK_FIX_MIDTRANS_ERROR.md](QUICK_FIX_MIDTRANS_ERROR.md)
**Troubleshooting** - Fix common Midtrans errors
- "Server key not configured"
- Webhook signature invalid
- QR Code generation failed
- Payment status not updating

---

## 🛠️ Admin Reference

### [ADMIN_STATUS_GUIDE.md](ADMIN_STATUS_GUIDE.md)
**Status Reference** - Penjelasan lengkap semua status
- Payment status (pending, awaiting_payment, completed, expired, cancelled)
- Refund status (none, requested, approved, rejected, processed)
- State transitions & workflows
- Action items untuk setiap status

---

## 🤖 Developer Reference

### [.github/copilot-instructions.md](../.github/copilot-instructions.md)
**AI Coding Agent** - Panduan untuk GitHub Copilot & AI agents
- Architecture overview
- Key files & patterns
- Payment automation flow
- Refund system deep dive
- Security considerations
- Common tasks & workflows

---

## 📁 File Pendukung

### [.env.example](../.env.example)
Template environment configuration
```bash
cp .env.example .env
# Edit .env dengan credentials Anda
```

### [.gitignore](../.gitignore)
Git ignore rules untuk keamanan
- `.env` (credentials tidak masuk git)
- Upload files
- Cache & logs
- IDE files

---

## 🗂️ Database

### SQL Files
Lokasi: `../databases/`

1. **🆕 villa_panjalu_complete.sql** - ⭐ ALL-IN-ONE SCHEMA (Recommended!)
   - Complete schema: users, villas, bookings, payment_logs, refunds
   - Views: v_active_bookings, v_pending_refunds
   - Triggers: Auto-log status changes
   - Indexes: Performance optimization
   - **Use this for fresh install!**

2. **villa_panjalu.sql** - Legacy base schema
   - Tables: users, villas, bookings

3. **migration_add_payment_features.sql** - Legacy migration
   - Tables: payment_logs, refunds
   - Columns: qr_code_url, payment_reference, paid_at

4. **migration_add_refund_details.sql** - Legacy migration
   - Columns: refund_method, account_holder_name, account_number

**Note:** For new installations, use `villa_panjalu_complete.sql` only!

---

## 🔍 Quick Links

| Tujuan | Dokumen |
|--------|---------|
| 🚀 **Mulai cepat** | [README.md](../README.md) |
| 👨‍💼 **Admin guide** | [QUICK_START_ADMIN.md](QUICK_START_ADMIN.md) |
| 💳 **Setup payment** | [README_PAYMENT.md](README_PAYMENT.md) |
| 🐛 **Fix errors** | [QUICK_FIX_MIDTRANS_ERROR.md](QUICK_FIX_MIDTRANS_ERROR.md) |
| 📊 **Status codes** | [ADMIN_STATUS_GUIDE.md](ADMIN_STATUS_GUIDE.md) |
| 🤖 **AI coding** | [copilot-instructions.md](../.github/copilot-instructions.md) |

---

## 📞 Support

**Developer:** Khilqi  
**WhatsApp:** +62 895-0689-2023  
**GitHub:** [@SenorKhilqi](https://github.com/SenorKhilqi)  
**Repository:** [github.com/SenorKhilqi/villa](https://github.com/SenorKhilqi/villa)

---

## 📝 Notes

- Semua dokumentasi dalam Bahasa Indonesia
- Screenshots & examples menggunakan sandbox Midtrans
- Untuk production, ganti ke production credentials
- Update docs ini jika ada perubahan fitur

---

**Last Updated:** November 8, 2025  
**Version:** 2.1 (Modern Stack - Tailwind CSS + Consolidated Database)
