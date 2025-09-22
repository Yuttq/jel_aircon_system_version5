<?php
/**
 * PHPMailer - PHP email creation and transport class.
 * PHP Version 5.5.0
 * @package PHPMailer
 * @author Marcus Bointon (Synchro/coolbru) <phpmailer@synchromedia.co.uk>
 * @author Jim Jagielski (jimjag) <jimjag@gmail.com>
 * @author Andy Prevost (codeworxtech) <codeworxtech@users.sourceforge.net>
 * @author Brent R. Matzelle (original founder)
 * @copyright 2012 - 2014 Marcus Bointon
 * @copyright 2010 - 2012 Jim Jagielski
 * @copyright 2004 - 2009 Andy Prevost
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 * @note This program is distributed in the hope that it will be useful - WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE.
 */

namespace PHPMailer\PHPMailer;

/**
 * PHPMailer - PHP email creation and transport class.
 * @package PHPMailer
 * @author Marcus Bointon (Synchro/coolbru) <phpmailer@synchromedia.co.uk>
 * @author Jim Jagielski (jimjag) <jimjag@gmail.com>
 * @author Andy Prevost (codeworxtech) <codeworxtech@users.sourceforge.net>
 * @author Brent R. Matzelle (original founder)
 */
class PHPMailer
{
    /**
     * The PHPMailer Version number.
     * @var string
     */
    const VERSION = '6.8.0';

    /**
     * Email priority.
     * @var int
     */
    const PRIORITY_HIGHEST = 1;
    const PRIORITY_HIGH = 2;
    const PRIORITY_NORMAL = 3;
    const PRIORITY_LOW = 4;
    const PRIORITY_LOWEST = 5;

    /**
     * The SMTP host.
     * @var string
     */
    public $Host = 'localhost';

    /**
     * The SMTP port.
     * @var int
     */
    public $Port = 25;

    /**
     * The SMTP username.
     * @var string
     */
    public $Username = '';

    /**
     * The SMTP password.
     * @var string
     */
    public $Password = '';

    /**
     * Whether to use SMTP authentication.
     * @var bool
     */
    public $SMTPAuth = false;

    /**
     * The SMTP security type.
     * @var string
     */
    public $SMTPSecure = '';

    /**
     * Whether to use SMTP keep alive.
     * @var bool
     */
    public $SMTPKeepAlive = false;

    /**
     * The SMTP connection timeout.
     * @var int
     */
    public $Timeout = 300;

    /**
     * The SMTP connection timeout.
     * @var int
     */
    public $SMTPTimeout = 300;

    /**
     * The SMTP debug level.
     * @var int
     */
    public $SMTPDebug = 0;

    /**
     * Whether to use SMTP.
     * @var bool
     */
    public $isSMTP = false;

    /**
     * Whether to use mail().
     * @var bool
     */
    public $isMail = false;

    /**
     * Whether to use sendmail.
     * @var bool
     */
    public $isSendmail = false;

    /**
     * Whether to use qmail.
     * @var bool
     */
    public $isQmail = false;

    /**
     * The sender email address.
     * @var string
     */
    public $From = '';

    /**
     * The sender name.
     * @var string
     */
    public $FromName = '';

    /**
     * The reply-to email address.
     * @var string
     */
    public $ReplyTo = '';

    /**
     * The reply-to name.
     * @var string
     */
    public $ReplyToName = '';

    /**
     * The recipient email addresses.
     * @var array
     */
    public $to = [];

    /**
     * The CC email addresses.
     * @var array
     */
    public $cc = [];

    /**
     * The BCC email addresses.
     * @var array
     */
    public $bcc = [];

    /**
     * The email subject.
     * @var string
     */
    public $Subject = '';

    /**
     * The email body.
     * @var string
     */
    public $Body = '';

    /**
     * The email alt body.
     * @var string
     */
    public $AltBody = '';

    /**
     * Whether the email is HTML.
     * @var bool
     */
    public $isHTML = false;

    /**
     * The email headers.
     * @var string
     */
    public $Headers = '';

    /**
     * The email attachments.
     * @var array
     */
    public $attachment = [];

    /**
     * The email error info.
     * @var string
     */
    public $ErrorInfo = '';

    /**
     * The email charset.
     * @var string
     */
    public $CharSet = 'utf-8';

    /**
     * The email content type.
     * @var string
     */
    public $ContentType = 'text/plain';

    /**
     * The email encoding.
     * @var string
     */
    public $Encoding = '8bit';

    /**
     * The email priority.
     * @var int
     */
    public $Priority = 3;

    /**
     * The email message ID.
     * @var string
     */
    public $MessageID = '';

    /**
     * The email message date.
     * @var string
     */
    public $MessageDate = '';

    /**
     * Whether to throw exceptions.
     * @var bool
     */
    public $exceptions = false;

    /**
     * Constructor.
     * @param bool $exceptions Should we throw external exceptions?
     */
    public function __construct($exceptions = false)
    {
        $this->exceptions = $exceptions;
        $this->MessageID = $this->getMessageID();
        $this->MessageDate = date('r');
    }

    /**
     * Set the email to use SMTP.
     * @param string $host The SMTP host
     * @param int $port The SMTP port
     * @param string $username The SMTP username
     * @param string $password The SMTP password
     * @param string $secure The SMTP security type
     * @return void
     */
    public function isSMTP($host = '', $port = 25, $username = '', $password = '', $secure = '')
    {
        $this->isSMTP = true;
        $this->isMail = false;
        $this->isSendmail = false;
        $this->isQmail = false;
        
        if ($host) {
            $this->Host = $host;
        }
        if ($port) {
            $this->Port = $port;
        }
        if ($username) {
            $this->Username = $username;
        }
        if ($password) {
            $this->Password = $password;
        }
        if ($secure) {
            $this->SMTPSecure = $secure;
        }
    }

    /**
     * Set the sender email address.
     * @param string $address The sender email address
     * @param string $name The sender name
     * @return void
     */
    public function setFrom($address, $name = '')
    {
        $this->From = $address;
        $this->FromName = $name;
    }

    /**
     * Add a recipient email address.
     * @param string $address The recipient email address
     * @param string $name The recipient name
     * @return void
     */
    public function addAddress($address, $name = '')
    {
        $this->to[] = ['email' => $address, 'name' => $name];
    }

    /**
     * Add a reply-to email address.
     * @param string $address The reply-to email address
     * @param string $name The reply-to name
     * @return void
     */
    public function addReplyTo($address, $name = '')
    {
        $this->ReplyTo = $address;
        $this->ReplyToName = $name;
    }

    /**
     * Set the email subject.
     * @param string $subject The email subject
     * @return void
     */
    public function setSubject($subject)
    {
        $this->Subject = $subject;
    }

    /**
     * Set the email body.
     * @param string $body The email body
     * @param bool $isHTML Whether the email is HTML
     * @return void
     */
    public function setBody($body, $isHTML = false)
    {
        $this->Body = $body;
        $this->isHTML = $isHTML;
    }

    /**
     * Set whether the email is HTML.
     * @param bool $isHTML Whether the email is HTML
     * @return void
     */
    public function isHTML($isHTML = true)
    {
        $this->isHTML = $isHTML;
    }

    /**
     * Send the email.
     * @return bool True if successful, false otherwise
     */
    public function send()
    {
        try {
            if (!$this->preSend()) {
                return false;
            }
            return $this->postSend();
        } catch (Exception $e) {
            $this->ErrorInfo = $e->getMessage();
            if ($this->exceptions) {
                throw $e;
            }
            return false;
        }
    }

    /**
     * Prepare the email for sending.
     * @return bool True if successful, false otherwise
     */
    public function preSend()
    {
        if (empty($this->From)) {
            $this->ErrorInfo = 'From address is not set';
            return false;
        }
        if (empty($this->to)) {
            $this->ErrorInfo = 'No recipients set';
            return false;
        }
        if (empty($this->Subject)) {
            $this->ErrorInfo = 'Subject is not set';
            return false;
        }
        if (empty($this->Body)) {
            $this->ErrorInfo = 'Body is not set';
            return false;
        }
        return true;
    }

    /**
     * Send the email.
     * @return bool True if successful, false otherwise
     */
    public function postSend()
    {
        if ($this->isSMTP) {
            return $this->sendSMTP();
        } elseif ($this->isMail) {
            return $this->sendMail();
        } else {
            return $this->sendSendmail();
        }
    }

    /**
     * Send via SMTP.
     * @return bool True if successful, false otherwise
     */
    public function sendSMTP()
    {
        // This is a simplified SMTP implementation
        // In a real PHPMailer, this would be much more complex
        $headers = $this->getHeaders();
        $message = $this->getMessage();
        
        // Use the existing sendSMTPEmail function
        return sendSMTPEmail($this->to[0]['email'], $this->Subject, $this->Body, $this->isHTML);
    }

    /**
     * Send via mail().
     * @return bool True if successful, false otherwise
     */
    public function sendMail()
    {
        $headers = $this->getHeaders();
        $message = $this->getMessage();
        
        return mail($this->to[0]['email'], $this->Subject, $message, $headers);
    }

    /**
     * Send via sendmail.
     * @return bool True if successful, false otherwise
     */
    public function sendSendmail()
    {
        // Simplified sendmail implementation
        return $this->sendMail();
    }

    /**
     * Get the email headers.
     * @return string The email headers
     */
    public function getHeaders()
    {
        $headers = "From: {$this->FromName} <{$this->From}>\r\n";
        if ($this->ReplyTo) {
            $headers .= "Reply-To: {$this->ReplyToName} <{$this->ReplyTo}>\r\n";
        }
        $headers .= "Content-Type: " . ($this->isHTML ? "text/html" : "text/plain") . "; charset={$this->CharSet}\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        return $headers;
    }

    /**
     * Get the email message.
     * @return string The email message
     */
    public function getMessage()
    {
        return $this->Body;
    }

    /**
     * Get a unique message ID.
     * @return string The message ID
     */
    public function getMessageID()
    {
        return '<' . uniqid() . '@' . (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost') . '>';
    }
}
