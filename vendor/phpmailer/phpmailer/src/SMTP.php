<?php
/**
 * PHPMailer SMTP class.
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
 * PHPMailer SMTP class.
 * @package PHPMailer
 * @author Marcus Bointon (Synchro/coolbru) <phpmailer@synchromedia.co.uk>
 * @author Jim Jagielski (jimjag) <jimjag@gmail.com>
 * @author Andy Prevost (codeworxtech) <codeworxtech@users.sourceforge.net>
 * @author Brent R. Matzelle (original founder)
 */
class SMTP
{
    /**
     * SMTP debug levels.
     */
    const DEBUG_OFF = 0;
    const DEBUG_CLIENT = 1;
    const DEBUG_SERVER = 2;
    const DEBUG_CONNECTION = 3;
    const DEBUG_LOWLEVEL = 4;

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
     * The SMTP debug level.
     * @var int
     */
    public $SMTPDebug = 0;

    /**
     * The SMTP connection timeout.
     * @var int
     */
    public $Timeout = 300;

    /**
     * The SMTP connection.
     * @var resource
     */
    public $smtp_conn;

    /**
     * The SMTP error info.
     * @var string
     */
    public $ErrorInfo = '';

    /**
     * Constructor.
     * @param string $host The SMTP host
     * @param int $port The SMTP port
     * @param string $username The SMTP username
     * @param string $password The SMTP password
     * @param string $secure The SMTP security type
     */
    public function __construct($host = '', $port = 25, $username = '', $password = '', $secure = '')
    {
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
     * Connect to the SMTP server.
     * @return bool True if successful, false otherwise
     */
    public function connect()
    {
        $this->smtp_conn = @fsockopen($this->Host, $this->Port, $errno, $errstr, $this->Timeout);
        
        if (!$this->smtp_conn) {
            $this->ErrorInfo = "Could not connect to SMTP server: $errstr ($errno)";
            return false;
        }

        $response = fgets($this->smtp_conn, 512);
        if (substr($response, 0, 3) != '220') {
            $this->ErrorInfo = "SMTP server error: $response";
            return false;
        }

        return true;
    }

    /**
     * Authenticate with the SMTP server.
     * @return bool True if successful, false otherwise
     */
    public function authenticate()
    {
        if (!$this->smtp_conn) {
            $this->ErrorInfo = "Not connected to SMTP server";
            return false;
        }

        // Send EHLO command
        fputs($this->smtp_conn, "EHLO " . (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost') . "\r\n");
        $response = fgets($this->smtp_conn, 512);

        // Start TLS if required
        if ($this->SMTPSecure === 'tls') {
            fputs($this->smtp_conn, "STARTTLS\r\n");
            $response = fgets($this->smtp_conn, 512);
            if (substr($response, 0, 3) != '220') {
                $this->ErrorInfo = "STARTTLS failed: $response";
                return false;
            }

            if (!stream_socket_enable_crypto($this->smtp_conn, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
                $this->ErrorInfo = "TLS encryption failed";
                return false;
            }

            // Send EHLO again after TLS
            fputs($this->smtp_conn, "EHLO " . (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost') . "\r\n");
            $response = fgets($this->smtp_conn, 512);
        }

        // Authenticate
        fputs($this->smtp_conn, "AUTH LOGIN\r\n");
        $response = fgets($this->smtp_conn, 512);
        if (substr($response, 0, 3) != '334') {
            $this->ErrorInfo = "AUTH LOGIN failed: $response";
            return false;
        }

        fputs($this->smtp_conn, base64_encode($this->Username) . "\r\n");
        $response = fgets($this->smtp_conn, 512);
        if (substr($response, 0, 3) != '334') {
            $this->ErrorInfo = "Username authentication failed: $response";
            return false;
        }

        fputs($this->smtp_conn, base64_encode($this->Password) . "\r\n");
        $response = fgets($this->smtp_conn, 512);
        if (substr($response, 0, 3) != '235') {
            $this->ErrorInfo = "Password authentication failed: $response";
            return false;
        }

        return true;
    }

    /**
     * Send an email.
     * @param string $from The sender email address
     * @param string $to The recipient email address
     * @param string $subject The email subject
     * @param string $body The email body
     * @param bool $isHTML Whether the email is HTML
     * @return bool True if successful, false otherwise
     */
    public function send($from, $to, $subject, $body, $isHTML = false)
    {
        if (!$this->smtp_conn) {
            $this->ErrorInfo = "Not connected to SMTP server";
            return false;
        }

        // Send MAIL FROM
        fputs($this->smtp_conn, "MAIL FROM: <$from>\r\n");
        $response = fgets($this->smtp_conn, 512);
        if (substr($response, 0, 3) != '250') {
            $this->ErrorInfo = "MAIL FROM failed: $response";
            return false;
        }

        // Send RCPT TO
        fputs($this->smtp_conn, "RCPT TO: <$to>\r\n");
        $response = fgets($this->smtp_conn, 512);
        if (substr($response, 0, 3) != '250') {
            $this->ErrorInfo = "RCPT TO failed: $response";
            return false;
        }

        // Send DATA
        fputs($this->smtp_conn, "DATA\r\n");
        $response = fgets($this->smtp_conn, 512);
        if (substr($response, 0, 3) != '354') {
            $this->ErrorInfo = "DATA command failed: $response";
            return false;
        }

        // Send email headers and body
        $headers = "From: $from\r\n";
        $headers .= "To: $to\r\n";
        $headers .= "Subject: $subject\r\n";
        $headers .= "Content-Type: " . ($isHTML ? "text/html" : "text/plain") . "; charset=UTF-8\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "\r\n";

        fputs($this->smtp_conn, $headers . $body . "\r\n.\r\n");
        $response = fgets($this->smtp_conn, 512);
        if (substr($response, 0, 3) != '250') {
            $this->ErrorInfo = "Email sending failed: $response";
            return false;
        }

        return true;
    }

    /**
     * Disconnect from the SMTP server.
     * @return void
     */
    public function disconnect()
    {
        if ($this->smtp_conn) {
            fputs($this->smtp_conn, "QUIT\r\n");
            fclose($this->smtp_conn);
            $this->smtp_conn = null;
        }
    }
}
