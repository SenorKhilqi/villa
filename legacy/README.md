# Legacy Files Archive

This folder contains legacy files kept for reference only.

## ⚠️ DO NOT USE THESE FILES

Use the modern versions instead:
- Database: Use `databases/villa_panjalu_complete.sql`
- Components: Use files with Tailwind/modern CSS

## Contents

### databases/
- `villa_panjalu.sql` - Original base schema (replaced by villa_panjalu_complete.sql)
- `migration_add_payment_features.sql` - Payment features migration (merged into complete.sql)
- `migration_add_refund_details.sql` - Refund details migration (merged into complete.sql)

## Why Kept?

These files are archived for:
- Historical reference
- Rollback capability (if needed)
- Understanding migration history
- Documentation purposes

## Migration Path

```
villa_panjalu.sql (v1.0)
    ↓
+ migration_add_payment_features.sql (v1.5)
    ↓
+ migration_add_refund_details.sql (v2.0)
    ↓
= villa_panjalu_complete.sql (v2.1) ✅ USE THIS
```

---

**Last Updated:** November 8, 2025  
**Status:** Archived
