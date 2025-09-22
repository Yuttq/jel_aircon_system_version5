# JEL Air Conditioning System - Comprehensive Testing Guide

## 🎯 Overview
This guide provides complete testing links and procedures to verify that your JEL Aircon System is 100% functional. Test each section systematically to ensure all features work correctly.

---

## 🔐 **1. AUTHENTICATION & ACCESS CONTROL**

### Admin Login
- **URL**: `http://localhost/jel_aircon_system/login.php`
- **Test**: Login with admin credentials
- **Default Admin**: `admin` / `password` (check database for actual password)
- **Expected**: Redirect to main dashboard

### Customer Portal Login
- **URL**: `http://localhost/jel_aircon_system/customer_portal/login.php`
- **Test**: Login with customer email and phone
- **Expected**: Access to customer dashboard

### Logout Functionality
- **Admin Logout**: `http://localhost/jel_aircon_system/logout.php`
- **Customer Logout**: `http://localhost/jel_aircon_system/customer_portal/logout.php`
- **Expected**: Session cleared, redirect to login

---

## 🏠 **2. MAIN SYSTEM INTERFACES**

### Admin Dashboard
- **URL**: `http://localhost/jel_aircon_system/index.php`
- **Test**: View statistics, recent bookings, quick actions
- **Expected**: Dashboard loads with data cards and navigation

### Admin Panel
- **URL**: `http://localhost/jel_aircon_system/admin_panel.php`
- **Test**: Access comprehensive admin tools
- **Expected**: System statistics, admin tools, recent activity

### Customer Portal Dashboard
- **URL**: `http://localhost/jel_aircon_system/customer_portal/dashboard.php`
- **Test**: Customer view of bookings and services
- **Expected**: Customer-specific dashboard with booking history

### Public Website
- **URL**: `http://localhost/jel_aircon_system/index_public.php`
- **Test**: Public-facing website
- **Expected**: Marketing site with service information

---

## 📋 **3. BOOKING MANAGEMENT**

### Create New Booking (Admin)
- **URL**: `http://localhost/jel_aircon_system/modules/bookings/add.php`
- **Test**: Create booking with customer, service, date/time
- **Expected**: Booking created successfully

### View All Bookings
- **URL**: `http://localhost/jel_aircon_system/modules/bookings/index.php`
- **Test**: List all bookings with filters
- **Expected**: Complete booking list with status indicators

### Edit Booking
- **URL**: `http://localhost/jel_aircon_system/modules/bookings/edit.php?id=X`
- **Test**: Modify existing booking details
- **Expected**: Booking updated successfully

### Booking Calendar View
- **URL**: `http://localhost/jel_aircon_system/modules/bookings/calendar.php`
- **Test**: Calendar interface for bookings
- **Expected**: Visual calendar with booking slots

### Public Booking Form
- **URL**: `http://localhost/jel_aircon_system/book_service.php`
- **Test**: Public customers can book services
- **Expected**: Booking form works without login

---

## 👥 **4. CUSTOMER MANAGEMENT**

### Add New Customer
- **URL**: `http://localhost/jel_aircon_system/modules/customers/add.php`
- **Test**: Create customer profile
- **Expected**: Customer added to database

### View All Customers
- **URL**: `http://localhost/jel_aircon_system/modules/customers/index.php`
- **Test**: List all customers
- **Expected**: Customer directory with search/filter

### Edit Customer
- **URL**: `http://localhost/jel_aircon_system/modules/customers/edit.php?id=X`
- **Test**: Update customer information
- **Expected**: Customer data updated

### Customer Registration (Portal)
- **URL**: `http://localhost/jel_aircon_system/customer_portal/register.php`
- **Test**: Self-registration for customers
- **Expected**: New customer account created

---

## 🔧 **5. TECHNICIAN MANAGEMENT**

### Add Technician
- **URL**: `http://localhost/jel_aircon_system/modules/technicians/add.php`
- **Test**: Create technician profile
- **Expected**: Technician added with user account

### View All Technicians
- **URL**: `http://localhost/jel_aircon_system/modules/technicians/index.php`
- **Test**: List all technicians
- **Expected**: Technician directory with status

### Edit Technician
- **URL**: `http://localhost/jel_aircon_system/modules/technicians/edit.php?id=X`
- **Test**: Update technician details
- **Expected**: Technician information updated

---

## 🛠️ **6. SERVICE MANAGEMENT**

### Add Service
- **URL**: `http://localhost/jel_aircon_system/modules/services/add.php`
- **Test**: Create new service offering
- **Expected**: Service added with pricing

### View All Services
- **URL**: `http://localhost/jel_aircon_system/modules/services/index.php`
- **Test**: List all services
- **Expected**: Service catalog with pricing

### Edit Service
- **URL**: `http://localhost/jel_aircon_system/modules/services/edit.php?id=X`
- **Test**: Update service details
- **Expected**: Service information updated

---

## 💳 **7. PAYMENT MANAGEMENT**

### Record Payment
- **URL**: `http://localhost/jel_aircon_system/modules/payments/add.php`
- **Test**: Record payment for booking
- **Expected**: Payment recorded with status

### View All Payments
- **URL**: `http://localhost/jel_aircon_system/modules/payments/index.php`
- **Test**: List all payments
- **Expected**: Payment history with filters

### Edit Payment
- **URL**: `http://localhost/jel_aircon_system/modules/payments/edit.php?id=X`
- **Test**: Update payment details
- **Expected**: Payment information updated

---

## 📊 **8. REPORTS & ANALYTICS**

### Booking Reports
- **URL**: `http://localhost/jel_aircon_system/modules/reports/bookings.php`
- **Test**: Generate booking analytics
- **Expected**: Booking statistics and charts

### Customer Reports
- **URL**: `http://localhost/jel_aircon_system/modules/reports/customers.php`
- **Test**: Customer analytics
- **Expected**: Customer statistics and trends

### Revenue Reports
- **URL**: `http://localhost/jel_aircon_system/modules/reports/revenue.php`
- **Test**: Financial reports
- **Expected**: Revenue charts and summaries

### Technician Reports
- **URL**: `http://localhost/jel_aircon_system/modules/reports/technicians.php`
- **Test**: Technician performance
- **Expected**: Technician workload and efficiency

### Service Reports
- **URL**: `http://localhost/jel_aircon_system/modules/reports/services.php`
- **Test**: Service popularity
- **Expected**: Service usage statistics

---

## ⚙️ **9. SYSTEM CONFIGURATION**

### System Settings
- **URL**: `http://localhost/jel_aircon_system/settings.php`
- **Test**: Business information and notifications
- **Expected**: Settings saved successfully

### Email Configuration
- **URL**: `http://localhost/jel_aircon_system/admin/email_config.php`
- **Test**: SMTP settings
- **Expected**: Email configuration saved

### Data Migration
- **URL**: `http://localhost/jel_aircon_system/admin/data_migration.php`
- **Test**: Import data from Excel/CSV
- **Expected**: Data imported successfully

### Database Viewer
- **URL**: `http://localhost/jel_aircon_system/admin/view_database.php`
- **Test**: View database contents
- **Expected**: Database tables and data displayed

---

## 🧪 **10. TESTING & MAINTENANCE**

### System Test
- **URL**: `http://localhost/jel_aircon_system/system_test.php`
- **Test**: Comprehensive system health check
- **Expected**: All tests pass (✅)

### Email Test (SMTP)
- **URL**: `http://localhost/jel_aircon_system/test_email_simple.php`
- **Test**: Send test email
- **Expected**: Email sent successfully

### Notification Test
- **URL**: `http://localhost/jel_aircon_system/test_notifications.php`
- **Test**: Test all notification types
- **Expected**: Notifications sent

### Database Fix
- **URL**: `http://localhost/jel_aircon_system/fix_database.php`
- **Test**: Repair database issues
- **Expected**: Database issues resolved

### Scheduled Tasks
- **URL**: `http://localhost/jel_aircon_system/scheduled_tasks.php`
- **Test**: Cron job management
- **Expected**: Scheduled tasks configured

### Notification Logs
- **URL**: `http://localhost/jel_aircon_system/modules/notifications/index.php`
- **Test**: View notification history
- **Expected**: Notification log displayed

---

## 📱 **11. CUSTOMER PORTAL FEATURES**

### Customer Dashboard
- **URL**: `http://localhost/jel_aircon_system/customer_portal/dashboard.php`
- **Test**: Customer overview
- **Expected**: Personal dashboard with bookings

### My Bookings
- **URL**: `http://localhost/jel_aircon_system/customer_portal/bookings.php`
- **Test**: View customer bookings
- **Expected**: Booking list with status

### Booking Details
- **URL**: `http://localhost/jel_aircon_system/customer_portal/booking-details.php?id=X`
- **Test**: Detailed booking view
- **Expected**: Complete booking information

### Cancel Booking
- **URL**: `http://localhost/jel_aircon_system/customer_portal/cancel-booking.php?id=X`
- **Test**: Cancel booking
- **Expected**: Booking cancelled with confirmation

### Service History
- **URL**: `http://localhost/jel_aircon_system/customer_portal/history.php`
- **Test**: View past services
- **Expected**: Historical service records

### Customer Profile
- **URL**: `http://localhost/jel_aircon_system/customer_portal/profile.php`
- **Test**: Update customer information
- **Expected**: Profile updated successfully

### Feedback
- **URL**: `http://localhost/jel_aircon_system/customer_portal/feedback.php`
- **Test**: Submit service feedback
- **Expected**: Feedback recorded

---

## 📧 **12. EMAIL FUNCTIONALITY**

### Email Templates
- **Booking Confirmation**: `templates/emails/booking_confirmation.html`
- **Booking Reminder**: `templates/emails/booking_reminder.html`
- **Status Update**: `templates/emails/status_update.html`
- **Payment Confirmation**: `templates/emails/payment_confirmation.html`
- **Service Completed**: `templates/emails/service_completed.html`
- **Technician Assignment**: `templates/emails/technician_assignment.html`
- **Booking Cancelled**: `templates/emails/booking_cancelled.html`

### Email Logs
- **Log File**: `emails/email_log.txt`
- **Test**: Check email delivery
- **Expected**: Email logs show successful sends

---

## 🔗 **13. QUICK ACCESS LINKS**

### Development Links
- **URL**: `http://localhost/jel_aircon_system/development_links.php`
- **Test**: Access all system tools
- **Expected**: Complete link directory

### Admin Login
- **URL**: `http://localhost/jel_aircon_system/admin/admin_login.php`
- **Test**: Admin authentication
- **Expected**: Admin access granted

---

## ✅ **14. TESTING CHECKLIST**

### Pre-Testing Setup
- [ ] Database is properly configured
- [ ] Email SMTP settings are configured
- [ ] File permissions are set correctly
- [ ] Sample data is loaded

### Core Functionality Tests
- [ ] Admin login works
- [ ] Customer portal login works
- [ ] Booking creation works
- [ ] Customer management works
- [ ] Technician management works
- [ ] Service management works
- [ ] Payment recording works
- [ ] Reports generate correctly

### Email Tests
- [ ] Booking confirmation emails send
- [ ] Reminder emails work
- [ ] Status update emails work
- [ ] Payment confirmation emails work

### Customer Portal Tests
- [ ] Customer registration works
- [ ] Booking viewing works
- [ ] Booking cancellation works
- [ ] Profile updates work
- [ ] Feedback submission works

### System Health Tests
- [ ] System test passes all checks
- [ ] Database connectivity works
- [ ] File permissions are correct
- [ ] Email configuration works

---

## 🚨 **15. COMMON ISSUES & SOLUTIONS**

### Database Connection Issues
- Check `includes/config.php` for correct database credentials
- Verify MySQL service is running
- Ensure database `jel_aircon` exists

### Email Not Working
- Configure SMTP settings in `admin/email_config.php`
- Test email with `test_email_simple.php`
- Check email logs in `emails/email_log.txt`

### Permission Issues
- Ensure `assets/`, `templates/`, `emails/` directories are writable
- Check file permissions (755 for directories, 644 for files)

### Login Issues
- Verify admin user exists in database
- Check password hashing in `includes/auth.php`
- Clear browser cache and cookies

---

## 📞 **16. SUPPORT RESOURCES**

### Documentation Files
- `PRODUCTION_DEPLOYMENT_CHECKLIST.md` - Deployment guide
- `PHASE_6_DEPLOYMENT_GUIDE.md` - Phase 6 deployment
- `USER_TRAINING_GUIDE.md` - User manual
- `GMAIL_SETUP_GUIDE.md` - Email setup guide
- `IMAGE_INTEGRATION_QUICK_REFERENCE.md` - Image guide

### Default Credentials
- **Admin Username**: `admin`
- **Admin Password**: Check database for hashed password
- **Customer Login**: Use email and phone from booking

---

## 🎯 **FINAL VERIFICATION**

After testing all components:

1. **System Test**: Run `system_test.php` - all tests should pass ✅
2. **Email Test**: Send test email - should receive confirmation
3. **Booking Flow**: Create booking → assign technician → record payment → complete service
4. **Customer Portal**: Register customer → book service → view booking → submit feedback
5. **Reports**: Generate all report types - should show data correctly

**✅ System is 100% functional when all tests pass!**

---

*Last Updated: $(date)*
*System Version: JEL Air Conditioning Management System v1.0*
