<?php
use PHPMailer\PHPMailer\PHPMailer;
require 'vendor/autoload.php';

    // $mail = new PHPMailer();
    // $mail->IsSMTP(); // set mailer to use SMTP
    // $mail->Timeout = 5;
    // $mail->Host = "skc.world";
    // $mail->SMTPAuth = true; // turn on SMTP authentication
    // $mail->Username = "noreply1@skc.world"; // SMTP username
    // $mail->Password = "Noreply@123"; // SMTP password
    // $mail->From = "noreply1@skc.world"; //do NOT fake header. The Username domain and the from domain must be the same.
    // $mail->FromName = "SKC";
    // $mail->AddAddress(stripslashes('newsdtest@yopmail.com')); // Email on which you want to send mail
    // $mail->IsHTML(true);
    // $mail->Subject = 'PHPMailer SMTP message';
    // $mail->Body ='This is a plain text message body';
    // $mail->addAttachment('PaymentReceipt.pdf');
    // if(!$mail->Send())
    // {
    //     echo $mail->ErrorInfo;
    // }
    // else {
    //     echo 'Message sent!';
    // }
/*
$mail = new PHPMailer;
$mail->isSMTP();
$mail->SMTPDebug = 1;
$mail->Host = 'localhost';
$mail->SMTPSecure = 'tls';
$mail->Port = 465;
$mail->SMTPAuth = true;
$mail->Username = 'noreply1@skc.world';
$mail->Password = 'Noreply@123'; 
$mail->setFrom('noreply1@skc.world', 'Your Name');
$mail->addReplyTo('noreply1@skc.world', 'Your Name');
$mail->addAddress('vivek.kumar@detecvision.com', 'Receiver Name');
$mail->Subject = 'PHPMailer SMTP message';
//$mail->msgHTML(file_get_contents('message.html'), __DIR__);
$mail->msgHTML('test massage message');
$mail->AltBody = 'This is a plain text message body';
//$mail->addAttachment('PaymentReceipt.pdf');
if (!$mail->send()) {
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Message sent!';
}*/
/*
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'vendor/autoload.php';

// Instantiation and passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
    $mail->isSMTP();                                            // Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    
    $mail->Username = 'vivek.kumar@detecvision.com';
	$mail->Password = 'vivek123##$$';                              // SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
    $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

    //Recipients
    $mail->setFrom('from@example.com', 'Mailer');
    $mail->addAddress('joe@example.net', 'Joe User');     // Add a recipient
    $mail->addAddress('ellen@example.com');               // Name is optional
    $mail->addReplyTo('info@example.com', 'Information');
    $mail->addCC('cc@example.com');
    $mail->addBCC('bcc@example.com');

    // Attachments
    //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

    // Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Here is the subject';
    $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
*/