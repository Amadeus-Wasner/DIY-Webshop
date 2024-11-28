<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    // Servereinstellungen
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'sfserfedsfgsdfd@gmail.com';   // Gmail-Adresse
    $mail->Password   = 'afvf zled mwrp kqwn';         // App-Passwort
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // Absender und Empfänger
    $mail->setFrom('sfserfedsfgsdfd@gmail.com', 'Webshop');
    $mail->addAddress('recipient-email@example.com');  // Empfängeradresse

    // E-Mail-Inhalt
    $mail->isHTML(true);
    $mail->Subject = 'Test E-Mail';
    $mail->Body    = 'Dies ist ein Test.';
    $mail->AltBody = 'Dies ist ein Test in reinem Text.';

    $mail->send();
    echo 'E-Mail wurde erfolgreich gesendet!';
} catch (Exception $e) {
    echo "E-Mail konnte nicht gesendet werden. Fehler: {$mail->ErrorInfo}";
}
