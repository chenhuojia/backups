<?php
$dir=dirname(__FILE__);
require $dir.'/mailer/PHPMailerAutoload.php';

$mail = new PHPMailer;
//$mail->SMTPDebug = 3;                               // Enable verbose debug output

$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'smtp.163.com';  // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->CharSet  = 'UTF-8';
$mail->Username = 'chjdwl@163.com';                 // SMTP username
$mail->Password = 'chjdwl0907';                           // SMTP password
//$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
//$mail->Port = 587;                                    // TCP port to connect to
$mail->setFrom('chjdwl@163.com', '伙佳');
$mail->addAddress('1126089253@qq.com', 'nihao');     // Add a recipient
//$mail->addAddress('ellen@example.com');               // Name is optional
$mail->addReplyTo('chjdwl@163.com', '伙佳');
//$mail->addCC('cc@example.com');
//$mail->addBCC('bcc@example.com');

//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
$mail->isHTML(true);                                  // Set email format to HTML

$mail->Subject = '我是陈伙佳';
$mail->Body    = '<b>这是一份签名</b>';
$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
//$mail->msgHTML($message);
if(!$mail->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Message has been sent';
}