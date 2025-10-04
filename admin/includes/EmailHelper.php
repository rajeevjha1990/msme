<?php
require_once __DIR__ . '/phpMailer/PHPMailer.php';
require_once __DIR__ . '/phpMailer/SMTP.php';
require_once __DIR__ . '/phpMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailHelper {
    public $mail;

    public function __construct() {
        $this->mail = new PHPMailer(true);

        // SMTP settings - Veronmoney
        $this->mail->isSMTP();
        $this->mail->Host       = 'smtp.veronmoney.com';
        $this->mail->SMTPAuth   = true;
        $this->mail->Username   = 'no-reply@veronmoney.com';
        $this->mail->Password   = 'Veron!Money01';
        $this->mail->SMTPSecure = 'ssl'; // use ssl for port 465
        $this->mail->Port       = 465;

        $this->mail->setFrom('no-reply@veronmoney.com', 'msme Admin');
        $this->mail->isHTML(true);
    }

    public function send($to, $subject, $body) {
        try {
            $this->mail->clearAllRecipients();
            $this->mail->addAddress($to);
            $this->mail->Subject = $subject;
            $this->mail->Body    = $body;
            $this->mail->AltBody = strip_tags($body);

            return $this->mail->send();
        } catch (Exception $e) {
            // Return the exact error message
            return "Mailer Error: " . $this->mail->ErrorInfo;
        }
    }
}
