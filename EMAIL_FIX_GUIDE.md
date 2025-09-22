# Email System Fix Guide

## Current Status
❌ **Email tests are failing** - Simple, HTML, and Booking emails all fail
✅ **SMTP Configuration is correct** - All settings are properly configured  
✅ **SMTP Connection works** - Can connect to Gmail's SMTP server

## Root Cause
Your system uses a **custom SMTP implementation** instead of PHPMailer, which can have reliability issues.

## Solutions

### Option 1: Install PHPMailer (Recommended)

1. **Install Composer** (if not already installed):
   - Download from: https://getcomposer.org/download/
   - Run the installer

2. **Install PHPMailer**:
   ```bash
   composer install
   ```

3. **Test the system**:
   - Visit: `http://your-domain/test_email_comprehensive.php`
   - This will test all email functionality

### Option 2: Fix Gmail App Password

If you prefer to keep the custom SMTP implementation:

1. **Check Gmail App Password**:
   - Go to Google Account → Security
   - Click "2-Step Verification" → "App passwords"
   - Generate a new app password for "JEL Air Conditioning System"
   - Update `includes/notification_config.php` with the new password

2. **Verify Settings**:
   ```php
   define('SMTP_HOST', 'smtp.gmail.com');
   define('SMTP_PORT', 587);
   define('SMTP_USERNAME', 'danielbalermo@gmail.com');
   define('SMTP_PASSWORD', 'your-16-character-app-password');
   define('SMTP_ENCRYPTION', 'tls');
   ```

## Testing

### Run Comprehensive Test
Visit: `http://your-domain/test_email_comprehensive.php`

This will test:
- ✅ Configuration check
- ✅ SMTP connection
- ✅ PHPMailer availability
- ✅ Custom SMTP test
- ✅ PHPMailer test (if installed)
- ✅ Enhanced SMTP test
- ✅ Notification system test

### Check Email Delivery
1. Check your inbox: `danielbalermo@gmail.com`
2. Check spam folder
3. Wait 1-5 minutes for delivery

## Files Created/Modified

### New Files:
- `composer.json` - PHPMailer dependency
- `includes/phpmailer_smtp.php` - PHPMailer implementation
- `test_email_comprehensive.php` - Comprehensive test script
- `EMAIL_FIX_GUIDE.md` - This guide

### Modified Files:
- `includes/notifications.php` - Updated to use enhanced email function

## Next Steps

1. **Install PHPMailer** using composer
2. **Run the comprehensive test** to verify everything works
3. **Check your email inbox** for test emails
4. **Test with real bookings** to ensure customer emails work

## Troubleshooting

### If PHPMailer installation fails:
- Ensure you have PHP 7.4+ installed
- Check that `composer` command is available
- Try running `composer install --no-dev` for production

### If emails still don't work:
- Check Gmail app password is correct
- Verify 2-factor authentication is enabled
- Check firewall settings (port 587 should be open)
- Try using port 465 with SSL instead of 587 with TLS

### If emails go to spam:
- Add SPF record to your domain
- Use a professional email address (not Gmail)
- Consider using a dedicated email service like SendGrid or Mailgun
