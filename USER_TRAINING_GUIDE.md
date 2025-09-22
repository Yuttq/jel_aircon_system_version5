# JEL Air Conditioning Services Management System
## User Training Guide

### System Access
- **Main System**: `http://localhost/jel_aircon_system/`
- **Customer Portal**: `http://localhost/jel_aircon_system/customer_portal/`

---

## ADMIN/STAFF TRAINING

### 1. LOGIN PROCESS
1. Go to `http://localhost/jel_aircon_system/`
2. Enter credentials:
   - **Username**: `admin`
   - **Password**: `password`
3. Click "Login"

### 2. DASHBOARD OVERVIEW
The main dashboard shows:
- **Today's Bookings**: Number of bookings scheduled for today
- **Pending Bookings**: Bookings waiting for confirmation
- **Total Customers**: Total number of registered customers
- **Monthly Revenue**: Current month's total revenue

**Quick Actions Panel** provides direct access to:
- New Booking
- Add Customer
- Add Technician
- Add Service
- Record Payment
- View Reports

### 3. CUSTOMER MANAGEMENT

#### Adding New Customers:
1. Click "Add Customer" from Quick Actions or go to Customers menu
2. Fill in required fields:
   - First Name
   - Last Name
   - Email (optional)
   - Phone Number
   - Address
3. Click "Save Customer"

#### Managing Customers:
- **View All**: Click "Customers" in main menu
- **Edit**: Click edit icon next to customer
- **Delete**: Click delete icon (use with caution)

### 4. TECHNICIAN MANAGEMENT

#### Adding Technicians:
1. Click "Add Technician" from Quick Actions
2. Fill in details:
   - First Name, Last Name
   - Email, Phone
   - Specialization (e.g., "AC Repair", "Installation")
3. Click "Save Technician"

#### Managing Technicians:
- View all technicians from main menu
- Edit technician details
- Assign technicians to bookings

### 5. SERVICE MANAGEMENT

#### Adding Services:
1. Click "Add Service" from Quick Actions
2. Enter service details:
   - Service Name
   - Description
   - Price
   - Duration (in minutes)
3. Click "Save Service"

### 6. BOOKING MANAGEMENT

#### Creating New Bookings:
1. Click "New Booking" from Quick Actions
2. Select customer from dropdown
3. Choose service
4. Select date and time
5. Assign technician (optional)
6. Add notes if needed
7. Click "Create Booking"

#### Managing Bookings:
- **View All**: Click "Bookings" in main menu
- **Calendar View**: See bookings in calendar format
- **Status Updates**: Change booking status (pending → confirmed → in-progress → completed)
- **Edit/Delete**: Modify or cancel bookings

### 7. PAYMENT MANAGEMENT

#### Recording Payments:
1. Click "Record Payment" from Quick Actions
2. Select booking from dropdown
3. Enter payment amount
4. Choose payment method (Cash, GCash, Bank Transfer, Card)
5. Add notes if needed
6. Click "Record Payment"

#### Payment Status:
- **Pending**: Payment not yet received
- **Completed**: Payment received
- **Failed**: Payment failed

### 8. REPORTING SYSTEM

#### Available Reports:
- **Bookings Report**: All bookings with filters
- **Customer Report**: Customer information and history
- **Revenue Report**: Financial summaries
- **Technician Report**: Technician performance
- **Services Report**: Service usage statistics

#### Generating Reports:
1. Click "View Reports" from Quick Actions
2. Select report type
3. Choose date range
4. Click "Generate Report"
5. Export as PDF or Excel if needed

---

## CUSTOMER PORTAL TRAINING

### 1. CUSTOMER REGISTRATION
1. Go to `http://localhost/jel_aircon_system/customer_portal/`
2. Click "Register"
3. Fill in registration form:
   - First Name, Last Name
   - Email, Phone
   - Address
   - Password
4. Click "Register"

### 2. CUSTOMER LOGIN
1. Go to customer portal
2. Enter email and password
3. Click "Login"

### 3. CUSTOMER DASHBOARD
Shows:
- Total bookings
- Completed services
- Pending bookings
- Recent booking history

### 4. BOOKING MANAGEMENT (Customer Side)
- **View Bookings**: See all your bookings
- **Booking Details**: View specific booking information
- **Cancel Booking**: Cancel upcoming bookings
- **Service History**: View completed services

### 5. PROFILE MANAGEMENT
- Update personal information
- Change password
- View account details

### 6. FEEDBACK SYSTEM
- Submit feedback for completed services
- Rate services (1-5 stars)
- Add comments about service quality

---

## COMMON TASKS WORKFLOW

### Complete Booking Process:
1. **Admin creates booking** → Customer selected, service chosen, date/time set
2. **Technician assigned** → Admin assigns available technician
3. **Status updates** → Booking moves through: pending → confirmed → in-progress → completed
4. **Payment recorded** → Admin records payment when received
5. **Customer feedback** → Customer submits feedback after service completion

### Daily Operations:
1. Check dashboard for today's bookings
2. Review pending bookings
3. Assign technicians to confirmed bookings
4. Update booking statuses throughout the day
5. Record payments as they come in
6. Generate daily reports

---

## TROUBLESHOOTING

### Common Issues:

**Login Problems:**
- Check username/password
- Clear browser cache
- Try different browser

**Booking Issues:**
- Ensure customer exists before creating booking
- Check technician availability
- Verify service is active

**Payment Issues:**
- Ensure booking exists before recording payment
- Check payment amount matches service price
- Verify payment method is selected

**Email Notifications:**
- Check email configuration in settings
- Verify customer email addresses
- Check spam folders

### Getting Help:
1. Check system logs in `logs/` directory
2. Contact system administrator
3. Review this training guide
4. Check database connection if system is slow

---

## BEST PRACTICES

### For Admins:
- Always backup data before major changes
- Keep customer information updated
- Assign technicians based on specialization
- Update booking statuses promptly
- Generate regular reports
- Monitor system performance

### For Customers:
- Keep profile information current
- Provide accurate contact details
- Submit feedback promptly
- Cancel bookings with advance notice
- Check booking status regularly

---

## SYSTEM MAINTENANCE

### Regular Tasks:
- **Daily**: Check dashboard, update booking statuses
- **Weekly**: Generate reports, backup database
- **Monthly**: Review system performance, clean old data

### Security:
- Change default passwords
- Use strong passwords
- Log out when finished
- Don't share login credentials

---

**Need Help?** Contact your system administrator or refer to the technical documentation.
