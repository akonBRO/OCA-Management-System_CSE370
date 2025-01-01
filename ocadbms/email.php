<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Include Composer's autoloader
require 'vendor/autoload.php';
require_once('DBconnect.php');

// Function to send email with OTP
function send_otp($to,$subject, $otp) {
    //Create an instance of PHPMailer
    $mail = new PHPMailer(true);

    try {
        //Server settings
        // $mail->SMTPDebug = SMTP::DEBUG_SERVER;               // Enable verbose debug output (uncomment for debugging)
        $mail->isSMTP();                                       // Send using SMTP
        $mail->Host       = 'mail.bracu-oca.online';           // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                              // Enable SMTP authentication
        $mail->Username   = 'verification@bracu-oca.online';   // SMTP username
        $mail->Password   = '@admin2024';                      // SMTP password (replace with the actual password)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;       // Enable SSL encryption
        $mail->Port       = 465;                               // TCP port to connect to (SSL)

        //Recipients
        $mail->setFrom('verification@bracu-oca.online', 'OCA- Unofficial Verification Team'); // Sender email and name
        $mail->addAddress($to);                               // Add recipient email

        // Email content
        $subject = "Your OTP for Account Verification";
        $body = "
            <html>
                <head>
                    <style>
                        body { font-family: Arial, sans-serif; line-height: 1.6; }
                        .header { font-size: 18px; font-weight: bold; margin-bottom: 10px; }
                        .otp { font-size: 24px; font-weight: bold; color: #007bff; }
                        .footer { font-size: 12px; color: #888; margin-top: 20px; }
                    </style>
                </head>
                <body>
                    <p class='header'>Hello,</p>
                    <p>Thank you for registering with us. Please use the One-Time Password (OTP) below to verify your account:</p>
                    <p class='otp'>{$otp}</p>
                    <p>If you did not request this, please ignore this email.</p>
                    <p class='footer'>Regards,<br>OCA- Unofficial Verification Team</p>
                </body>
            </html>
        ";

        $mail->isHTML(true);                                   // Set email format to HTML
        $mail->Subject = $subject;                             // Email subject
        $mail->Body    = $body;                                // Email body content

        $mail->send();                                         // Send the email
        echo 'OTP has been sent successfully!';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";  // Handle errors
    }
}

// Example usage of the send_otp function
if (isset($_REQUEST['to']) && isset($_REQUEST['otp'])) {
    $to = $_REQUEST['to'];
    $otp = $_REQUEST['otp'];
    send_otp($to, $otp);
}
?>
