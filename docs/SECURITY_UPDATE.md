# 🔐 Security Update - Environment Variables

## What Changed?

We've removed **hardcoded credentials** from the codebase for security reasons. All sensitive information now uses environment variables.

## Setup Instructions

### 1. Create `.env` file
```bash
# Copy the example file
cp .env.example .env
```

### 2. Add Your Midtrans Credentials
Edit `.env` and replace with your actual credentials:

```env
# For Sandbox (Testing)
MIDTRANS_SERVER_KEY=[YOUR-SANDBOX-SERVER-KEY]
MIDTRANS_CLIENT_KEY=Mid-client-YOUR-CLIENT-KEY
MIDTRANS_IS_PRODUCTION=false

# For Production (Real Payments)
MIDTRANS_SERVER_KEY=[YOUR-PRODUCTION-SERVER-KEY]
MIDTRANS_CLIENT_KEY=Mid-client-YOUR-PRODUCTION-KEY
MIDTRANS_IS_PRODUCTION=true
```

### 3. Get Your Credentials
1. Login to [Midtrans Dashboard](https://dashboard.midtrans.com)
2. Go to **Settings** → **Access Keys**
3. Copy your Server Key and Client Key
4. Paste them into `.env` file

## Files Modified

### `payment_config.php`
- ❌ Before: Hardcoded credentials
- ✅ After: Reads from `.env` file

### `.gitignore`
- ✅ Already ignoring `.env` (credentials stay private)

### `.env.example`
- ✅ Template file with instructions (safe to commit)

## Why This Change?

🔒 **Security Best Practices:**
- Never commit API keys/secrets to git
- GitHub automatically blocks pushes with exposed credentials
- Environment variables keep secrets separate from code
- Each environment (dev/staging/prod) can have different credentials

## Troubleshooting

### Error: "MIDTRANS_SERVER_KEY is empty"
**Solution:** You forgot to create `.env` file
```bash
cp .env.example .env
# Then edit .env with your credentials
```

### Error: "Unknown Merchant server_key/id"
**Solution:** Mismatch between key type and mode
- Sandbox key needs `MIDTRANS_IS_PRODUCTION=false`
- Production key needs `MIDTRANS_IS_PRODUCTION=true`

## Next Steps

1. ✅ Create `.env` locally
2. ✅ Add real credentials
3. ✅ Test payment system
4. ✅ Code is now safe to push to GitHub

**Remember:** Never share your `.env` file or commit it to git! 🔐
