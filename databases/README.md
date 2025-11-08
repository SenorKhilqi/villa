# 📂 Database Folder

## 🎯 Main Database Schema

### villa_panjalu_complete.sql ⭐
**USE THIS FILE FOR NEW INSTALLATIONS**

All-in-one database schema including:
- ✅ Tables: users, villas, bookings, payment_logs, refunds
- ✅ Views: v_active_bookings, v_pending_refunds
- ✅ Triggers: Auto-log payment & refund status changes
- ✅ Indexes: Optimized for performance
- ✅ Sample data: Default users & villas

**Import command:**
```bash
mysql -u root -p < villa_panjalu_complete.sql
```

---

## 📜 Legacy Files

Legacy SQL files have been moved to `../legacy/databases/` for reference.

**Migration history:**
```
v1.0: villa_panjalu.sql (base schema)
  ↓
v1.5: + migration_add_payment_features.sql
  ↓
v2.0: + migration_add_refund_details.sql
  ↓
v2.1: villa_panjalu_complete.sql (current) ✅
```

---

## 🔧 Maintenance

### Reset Database
```bash
# Drop existing database
mysql -u root -p -e "DROP DATABASE IF EXISTS villa_panjalu;"

# Import fresh schema
mysql -u root -p < villa_panjalu_complete.sql
```

### Backup Database
```bash
mysqldump -u root -p villa_panjalu > backup_$(date +%Y%m%d).sql
```

---

**Version:** 2.1  
**Last Updated:** November 8, 2025
