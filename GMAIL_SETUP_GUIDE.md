# Gmail SMTP Setup Guide
## Complete Instructions for Email Configuration

### Why Use Gmail SMTP?
- **Reliable**: Gmail's servers are highly reliable
- **Free**: No additional costs for basic usage
- **Secure**: Uses TLS encryption
- **Easy Setup**: Well-documented process

---

## Step-by-Step Gmail Setup

### Step 1: Enable 2-Factor Authentication

1. **Go to your Google Account**
   - Visit: https://myaccount.google.com/
   - Sign in with your Gmail account

2. **Navigate to Security**
   - Click on "Security" in the left sidebar
   - Look for "2-Step Verification"

3. **Enable 2-Step Verification**
   - Click "Get started" or "Turn on"
   - Follow the setup process:
     - Enter your phone number
     - Verify with SMS or call
     - Confirm the setup

### Step 2: Generate App Password

1. **Go to App Passwords**
   - Still in Security section
   - Look for "App passwords" (you may need to search for it)
   - Click on "App passwords"

2. **Create New App Password**
   - Select "Mail" as the app
   - Select "Other" as the device
   - Enter "JEL Air Conditioning System" as the name
   - Click "Generate"

3. **Copy the Generated Password**
   - Google will show a 16-character password
   - **IMPORTANT**: Copy this password immediately
   - It looks like: `abcd efgh ijkl mnop`
   - Remove spaces: `abcdefghijklmnop`

### Step 3: Configure Your System

1. **Go to Email Configuration**
   - Visit: `http://localhost/jel_aircon_system/admin/email_config.php`
   - Login as admin first

2. **Enter Gmail Settings**
   ```
   SMTP Host: smtp.gmail.com
   SMTP Port: 587
   SMTP Username: your-email@gmail.com
   SMTP Password: [the 16-character app password from step 2]
   Encryption: TLS
   ```

3. **Test Email**
   - Enter your email address in the test field
   - Click "Save Configuration"
   - Check your email for the test message

---

## Troubleshooting Common Issues

### Issue 1: "Authentication Failed"
**Solution:**
- Make sure you're using the App Password, not your regular Gmail password
- Verify 2-Factor Authentication is enabled
- Check that the App Password was copied correctly (no spaces)

### Issue 2: "Connection Timeout"
**Solution:**
- Verify SMTP Host: `smtp.gmail.com`
- Verify SMTP Port: `587`
- Check your internet connection
- Try using port `465` with SSL encryption

### Issue 3: "Email Not Received"
**Solution:**
- Check your spam/junk folder
- Verify the email address is correct
- Wait a few minutes (Gmail can be slow)
- Check if Gmail is blocking the email

### Issue 4: "Less Secure App Access"
**Solution:**
- This is outdated - use App Passwords instead
- Make sure 2-Factor Authentication is enabled
- Generate a new App Password

---

## Alternative Email Services

If Gmail doesn't work, try these alternatives:

### 1. Outlook/Hotmail
```
SMTP Host: smtp-mail.outlook.com
SMTP Port: 587
Encryption: TLS
```

### 2. Yahoo Mail
```
SMTP Host: smtp.mail.yahoo.com
SMTP Port: 587
Encryption: TLS
```

### 3. SendGrid (Professional)
- Sign up at sendgrid.com
- Get API key
- Use SMTP settings provided

---

## Testing Your Email Configuration

### Method 1: Use Built-in Test
1. Go to: `http://localhost/jel_aircon_system/admin/test_email.php`
2. Enter your email address
3. Click "Send Test Email"
4. Check your email inbox

### Method 2: Create a Booking
1. Go to: `http://localhost/jel_aircon_system/book_service.php`
2. Create a test booking
3. Check if confirmation email is sent

### Method 3: Check Email Logs
1. Go to: `http://localhost/jel_aircon_system/admin/view_database.php`
2. Look at the "notifications" table
3. Check if emails are being logged

---

## Security Best Practices

### 1. Use App Passwords
- Never use your main Gmail password
- Generate unique App Passwords for each application
- Revoke App Passwords you no longer need

### 2. Regular Updates
- Change App Passwords periodically
- Monitor your Gmail account for suspicious activity
- Keep your system updated

### 3. Backup Configuration
- Save your SMTP settings in a secure location
- Document your email configuration
- Keep a backup of your notification templates

---

## Common Gmail Limits

### Daily Limits
- **Free Gmail**: 500 emails per day
- **Google Workspace**: 2,000 emails per day

### Rate Limits
- **SMTP**: 100 emails per hour
- **API**: 1 billion quota units per day

### Best Practices
- Don't send more than 100 emails per hour
- Use email templates for consistency
- Monitor your usage to avoid limits

---

## Quick Reference

### Gmail SMTP Settings
```
Host: smtp.gmail.com
Port: 587
Security: TLS
Username: your-email@gmail.com
Password: [16-character app password]
```

### Test Email Addresses
- Use your own email for testing
- Create a test Gmail account if needed
- Verify emails are received and not in spam

### Support Contacts
- Gmail Help: https://support.google.com/mail
- Google Account Help: https://support.google.com/accounts
- System Admin: Contact your system administrator

---

## Success Checklist

- [ ] 2-Factor Authentication enabled
- [ ] App Password generated
- [ ] SMTP settings configured
- [ ] Test email sent successfully
- [ ] Email received in inbox
- [ ] Booking confirmation emails working
- [ ] Email logs showing successful sends

**If all items are checked, your email system is working correctly!** ✅
