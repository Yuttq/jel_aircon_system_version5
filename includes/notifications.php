<?php
require_once 'config.php';
require_once 'notification_config.php';
require_once 'simple_smtp.php';
require_once 'phpmailer_smtp.php';

class NotificationSystem {
    private $conn;
    private $templates;
    
    public function __construct($db_connection) {
        $this->conn = $db_connection;
        global $notification_templates;
        $this->templates = $notification_templates;
    }
    
    /**
     * Send test email for Gmail testing
     */
    public function sendTestEmail($to, $testData) {
        $subject = 'Test Email - JEL Air Conditioning System';
        
        $message = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: linear-gradient(135deg, #2563eb, #1e40af); color: white; padding: 20px; text-align: center; border-radius: 10px 10px 0 0; }
                .content { background: #f8f9fa; padding: 30px; border-radius: 0 0 10px 10px; }
                .test-info { background: white; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #10b981; }
                .footer { text-align: center; margin-top: 20px; color: #666; font-size: 14px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>🧪 Test Email</h1>
                    <p>JEL Air Conditioning System</p>
                </div>
                <div class='content'>
                    <h2>Email Test Successful!</h2>
                    <p>This is a test email to verify that your Gmail SMTP configuration is working correctly.</p>
                    
                    <div class='test-info'>
                        <h3>Test Details:</h3>
                        <p><strong>Customer:</strong> {$testData['customer_name']}</p>
                        <p><strong>Service:</strong> {$testData['service_name']}</p>
                        <p><strong>Date:</strong> {$testData['booking_date']}</p>
                        <p><strong>Time:</strong> {$testData['start_time']}</p>
                        <p><strong>Test ID:</strong> {$testData['booking_id']}</p>
                    </div>
                    
                    <p>If you received this email, your Gmail SMTP configuration is working perfectly! 🎉</p>
                    
                    <p><strong>Next Steps:</strong></p>
                    <ul>
                        <li>✅ Email notifications are working</li>
                        <li>✅ Customers will receive booking confirmations</li>
                        <li>✅ Reminder emails will be sent automatically</li>
                        <li>✅ Status updates will be delivered</li>
                    </ul>
                </div>
                <div class='footer'>
                    <p>This is an automated test email from JEL Air Conditioning System</p>
                    <p>Generated on " . date('Y-m-d H:i:s') . "</p>
                </div>
            </div>
        </body>
        </html>";
        
        return $this->sendEmail($to, $subject, $message, true);
    }

    /**
     * Send email notification using SMTP
     */
    public function sendEmail($to, $subject, $message, $isHtml = true) {
        if (!EMAIL_NOTIFICATIONS || !NOTIFICATION_ENABLED) {
            return false;
        }
        
        try {
            // Use enhanced SMTP with PHPMailer fallback
            if (defined('SMTP_HOST') && defined('SMTP_USERNAME') && defined('SMTP_PASSWORD')) {
                return sendEnhancedSMTPEmail($to, $subject, $message, $isHtml);
            } else {
                return $this->sendEmailWithMail($to, $subject, $message, $isHtml);
            }
        } catch (Exception $e) {
            error_log("Email sending failed: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Send email using PHPMailer (recommended)
     */
    private function sendEmailWithPHPMailer($to, $subject, $message, $isHtml = true) {
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = SMTP_HOST;
            $mail->SMTPAuth = true;
            $mail->Username = SMTP_USERNAME;
            $mail->Password = SMTP_PASSWORD;
            $mail->SMTPSecure = SMTP_ENCRYPTION;
            $mail->Port = SMTP_PORT;
            
            // Recipients
            $mail->setFrom(EMAIL_FROM, EMAIL_FROM_NAME);
            $mail->addAddress($to);
            $mail->addReplyTo(EMAIL_REPLY_TO, EMAIL_FROM_NAME);
            
            // Content
            $mail->isHTML($isHtml);
            $mail->Subject = $subject;
            $mail->Body = $message;
            
            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("PHPMailer Error: " . $mail->ErrorInfo);
            return false;
        }
    }
    
    /**
     * Send email using PHP mail() function (fallback)
     */
    private function sendEmailWithMail($to, $subject, $message, $isHtml = true) {
        $headers = "From: " . EMAIL_FROM_NAME . " <" . EMAIL_FROM . ">\r\n";
        $headers .= "Reply-To: " . EMAIL_REPLY_TO . "\r\n";
        $headers .= "Content-Type: " . ($isHtml ? "text/html" : "text/plain") . "; charset=UTF-8\r\n";
        
        return mail($to, $subject, $message, $headers);
    }
    
    /**
     * Log notification in database
     */
    public function logNotification($bookingId, $type, $subject, $message, $status, $retryCount = 0) {
        try {
            $sql = "INSERT INTO notifications (booking_id, type, email_sent, sms_sent, sent_at, notes) 
                    VALUES (?, ?, ?, ?, NOW(), ?)";
            
            $stmt = $this->conn->prepare($sql);
            $emailSent = ($status === 'sent') ? 1 : 0;
            $smsSent = 0; // SMS not implemented yet
            $notes = "Status: $status, Retry: $retryCount";
            
            return $stmt->execute([$bookingId, $type, $emailSent, $smsSent, $notes]);
        } catch (Exception $e) {
            error_log("Failed to log notification: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Send booking confirmation
     */
    public function sendBookingConfirmation($bookingId) {
        $booking = $this->getBookingDetails($bookingId);
        
        if (!$booking) {
            return false;
        }
        
        $template = $this->loadTemplate('booking_confirmation');
        if (!$template) {
            return false;
        }
        
        // Replace placeholders
        $replacements = [
            '[Customer Name]' => $booking['customer_name'],
            '[Service Type]' => $booking['service_name'],
            '[Date]' => date('F j, Y', strtotime($booking['booking_date'])),
            '[Time]' => date('g:i A', strtotime($booking['start_time'])),
            '[Booking ID]' => $booking['id'],
            '[Business Phone]' => BUSINESS_PHONE,
            '[Business Email]' => BUSINESS_EMAIL,
            '[Business Address]' => BUSINESS_ADDRESS
        ];
        
        $message = str_replace(array_keys($replacements), array_values($replacements), $template);
        $subject = $this->templates['booking_confirmation']['email_subject'];
        
        $sent = $this->sendEmail($booking['customer_email'], $subject, $message);
        $this->logNotification($bookingId, 'booking_confirmation', $subject, $message, $sent ? 'sent' : 'failed');
        
        return $sent;
    }
    
    /**
     * Send booking reminder
     */
    public function sendReminder($bookingId) {
        $booking = $this->getBookingDetails($bookingId);
        
        if (!$booking) {
            return false;
        }
        
        $template = $this->loadTemplate('booking_reminder');
        if (!$template) {
            return false;
        }
        
        // Replace placeholders
        $replacements = [
            '{first_name}' => $booking['customer_name'],
            '{service}' => $booking['service_name'],
            '{time}' => date('g:i A', strtotime($booking['start_time'])),
            '[Your Business Phone]' => BUSINESS_PHONE,
            '[Your Business Email]' => BUSINESS_EMAIL
        ];
        
        $message = str_replace(array_keys($replacements), array_values($replacements), $template);
        $subject = $this->templates['booking_reminder']['email_subject'];
        
        $sent = $this->sendEmail($booking['customer_email'], $subject, $message);
        $this->logNotification($bookingId, 'booking_reminder', $subject, $message, $sent ? 'sent' : 'failed');
        
        // Mark reminder as sent
        $this->markReminderSent($bookingId);
        
        return $sent;
    }
    
    /**
     * Send status update notification
     */
    public function sendStatusUpdate($bookingId, $newStatus) {
        $booking = $this->getBookingDetails($bookingId);
        
        if (!$booking) {
            return false;
        }
        
        $template = $this->loadTemplate('status_update');
        if (!$template) {
            return false;
        }
        
        // Replace placeholders
        $replacements = [
            '[Customer Name]' => $booking['customer_name'],
            '[Service Type]' => $booking['service_name'],
            '[Old Status]' => ucfirst($booking['status']),
            '[New Status]' => ucfirst($newStatus),
            '[Date]' => date('F j, Y', strtotime($booking['booking_date'])),
            '[Time]' => date('g:i A', strtotime($booking['start_time'])),
            '[Business Phone]' => BUSINESS_PHONE,
            '[Business Email]' => BUSINESS_EMAIL
        ];
        
        $message = str_replace(array_keys($replacements), array_values($replacements), $template);
        $subject = $this->templates['status_update']['email_subject'];
        
        $sent = $this->sendEmail($booking['customer_email'], $subject, $message);
        $this->logNotification($bookingId, 'status_update', $subject, $message, $sent ? 'sent' : 'failed');
        
        return $sent;
    }
    
    /**
     * Send technician assignment notification
     */
    public function sendTechnicianAssignment($bookingId, $technicianId) {
        $booking = $this->getBookingDetails($bookingId);
        $technician = $this->getTechnicianDetails($technicianId);
        
        if (!$booking || !$technician) {
            return false;
        }
        
        $template = $this->loadTemplate('technician_assignment');
        if (!$template) {
            return false;
        }
        
        // Replace placeholders
        $replacements = [
            '[Customer Name]' => $booking['customer_name'],
            '[Service Type]' => $booking['service_name'],
            '[Technician Name]' => $technician['name'],
            '[Technician Phone]' => $technician['phone'],
            '[Date]' => date('F j, Y', strtotime($booking['booking_date'])),
            '[Time]' => date('g:i A', strtotime($booking['start_time'])),
            '[Business Phone]' => BUSINESS_PHONE,
            '[Business Email]' => BUSINESS_EMAIL
        ];
        
        $message = str_replace(array_keys($replacements), array_values($replacements), $template);
        $subject = $this->templates['technician_assignment']['email_subject'];
        
        $sent = $this->sendEmail($booking['customer_email'], $subject, $message);
        $this->logNotification($bookingId, 'technician_assignment', $subject, $message, $sent ? 'sent' : 'failed');
        
        return $sent;
    }
    
    /**
     * Send payment confirmation
     */
    public function sendPaymentConfirmation($bookingId, $paymentAmount) {
        $booking = $this->getBookingDetails($bookingId);
        
        if (!$booking) {
            return false;
        }
        
        $template = $this->loadTemplate('payment_confirmation');
        if (!$template) {
            return false;
        }
        
        // Replace placeholders
        $replacements = [
            '[Customer Name]' => $booking['customer_name'],
            '[Service Type]' => $booking['service_name'],
            '[Amount]' => number_format($paymentAmount, 2),
            '[Date]' => date('F j, Y', strtotime($booking['booking_date'])),
            '[Business Phone]' => BUSINESS_PHONE,
            '[Business Email]' => BUSINESS_EMAIL
        ];
        
        $message = str_replace(array_keys($replacements), array_values($replacements), $template);
        $subject = $this->templates['payment_confirmation']['email_subject'];
        
        $sent = $this->sendEmail($booking['customer_email'], $subject, $message);
        $this->logNotification($bookingId, 'payment_confirmation', $subject, $message, $sent ? 'sent' : 'failed');
        
        return $sent;
    }
    
    /**
     * Load email template
     */
    private function loadTemplate($templateName) {
        if (!isset($this->templates[$templateName])) {
            return false;
        }
        
        $templatePath = EMAIL_TEMPLATE_DIR . $this->templates[$templateName]['email_template'];
        
        if (!file_exists($templatePath)) {
            error_log("Email template not found: $templatePath");
            return false;
        }
        
        return file_get_contents($templatePath);
    }
    
    /**
     * Get booking details from database
     */
    private function getBookingDetails($bookingId) {
        try {
            $sql = "SELECT b.*, c.first_name, c.last_name, c.email as customer_email, s.name as service_name,
                           CONCAT(c.first_name, ' ', c.last_name) as customer_name
                    FROM bookings b 
                    JOIN customers c ON b.customer_id = c.id 
                    JOIN services s ON b.service_id = s.id
                    WHERE b.id = ?";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$bookingId]);
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Failed to get booking details: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get technician details from database
     */
    private function getTechnicianDetails($technicianId) {
        try {
            $sql = "SELECT *, CONCAT(first_name, ' ', last_name) as name FROM technicians WHERE id = ?";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$technicianId]);
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Failed to get technician details: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Mark reminder as sent
     */
    private function markReminderSent($bookingId) {
        try {
            $sql = "UPDATE bookings SET reminder_sent = 1 WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$bookingId]);
        } catch (Exception $e) {
            error_log("Failed to mark reminder sent: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get bookings that need reminders
     */
    public function getBookingsForReminder() {
        try {
            $hours = REMINDER_HOURS_BEFORE;
            $sql = "SELECT b.*, c.first_name, c.last_name, c.email as customer_email, s.name as service_name,
                           CONCAT(c.first_name, ' ', c.last_name) as customer_name
                    FROM bookings b 
                    JOIN customers c ON b.customer_id = c.id 
                    JOIN services s ON b.service_id = s.id
                    WHERE b.status IN ('confirmed', 'pending') 
                    AND b.reminder_sent = 0
                    AND DATE(b.booking_date) = DATE(DATE_ADD(NOW(), INTERVAL 1 DAY))
                    AND TIME(b.start_time) BETWEEN TIME(DATE_SUB(NOW(), INTERVAL ? HOUR)) AND TIME(NOW())";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$hours]);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Failed to get bookings for reminder: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Send all pending reminders
     */
    public function sendPendingReminders() {
        if (!AUTO_REMINDERS_ENABLED) {
            return false;
        }
        
        $bookings = $this->getBookingsForReminder();
        $sentCount = 0;
        
        foreach ($bookings as $booking) {
            if ($this->sendReminder($booking['id'])) {
                $sentCount++;
            }
        }
        
        return $sentCount;
    }
}
?>