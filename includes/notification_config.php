<?php
// Notification Configuration
define('NOTIFICATION_ENABLED', true);
define('EMAIL_NOTIFICATIONS', true);
define('SMS_NOTIFICATIONS', false); // Disabled by default until SMS gateway is configured

// Email Configuration - SMTP Settings
define('SMTP_HOST', 'smtp.gmail.com'); // Change to your SMTP server
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'danielbalermo@gmail.com'); // Your email
define('SMTP_PASSWORD', 'bvzexfdd fbfzhcya'); // Your app password
define('SMTP_ENCRYPTION', 'tls'); // tls or ssl

// Email Settings
define('EMAIL_FROM', 'danielbalermo@gmail.com'); // Must match SMTP_USERNAME
define('EMAIL_FROM_NAME', 'JEL Air Conditioning Services');
define('EMAIL_REPLY_TO', 'danielbalermo@gmail.com');

// SMS Configuration (These would be replaced with actual SMS gateway credentials)
define('SMS_API_KEY', 'your_sms_api_key');
define('SMS_API_SECRET', 'your_sms_api_secret');
define('SMS_FROM_NUMBER', 'JELAirCon');

// Notification Settings
define('REMINDER_HOURS_BEFORE', 24); // Send reminder 24 hours before
define('AUTO_REMINDERS_ENABLED', true);
define('MAX_RETRY_ATTEMPTS', 3);

// Notification Templates
$notification_templates = [
    'booking_confirmation' => [
        'email_subject' => 'Booking Confirmation - JEL Air Conditioning Services',
        'email_template' => 'emails/booking_confirmation.html',
        'sms_template' => 'Your booking for {service} on {date} at {time} is confirmed. Booking ID: {booking_id}'
    ],
    'booking_reminder' => [
        'email_subject' => 'Reminder: Upcoming Service - JEL Air Conditioning',
        'email_template' => 'emails/booking_reminder.html',
        'sms_template' => 'Reminder: {service} tomorrow at {time}. Please be available.'
    ],
    'status_update' => [
        'email_subject' => 'Booking Status Update - JEL Air Conditioning',
        'email_template' => 'emails/status_update.html',
        'sms_template' => 'Your booking status: {service} is now {status}.'
    ],
    'payment_confirmation' => [
        'email_subject' => 'Payment Received - JEL Air Conditioning',
        'email_template' => 'emails/payment_confirmation.html',
        'sms_template' => 'Payment of ₱{amount} received. Thank you!'
    ],
    'technician_assignment' => [
        'email_subject' => 'Technician Assigned - JEL Air Conditioning',
        'email_template' => 'emails/technician_assignment.html',
        'sms_template' => 'Your technician {technician_name} will arrive at {time}.'
    ],
    'booking_cancelled' => [
        'email_subject' => 'Booking Cancelled - JEL Air Conditioning',
        'email_template' => 'emails/booking_cancelled.html',
        'sms_template' => 'Your booking for {service} has been cancelled.'
    ],
    'service_completed' => [
        'email_subject' => 'Service Completed - JEL Air Conditioning',
        'email_template' => 'emails/service_completed.html',
        'sms_template' => 'Your {service} has been completed. Please rate our service.'
    ]
];

// Email Template Directory
define('EMAIL_TEMPLATE_DIR', __DIR__ . '/../templates/');

// Business Information
define('BUSINESS_NAME', 'JEL Air Conditioning Services');
define('BUSINESS_PHONE', '(123) 456-7890');
define('BUSINESS_EMAIL', 'info@jelaircon.com');
define('BUSINESS_ADDRESS', '123 Service Road, City, State 12345');
define('BUSINESS_WEBSITE', 'https://jelaircon.com');
?>