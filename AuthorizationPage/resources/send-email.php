<?php
require 'class.phpmailer.php';
$mail = new PHPMailer();
$mail->IsSMTP();
$mail->Port = 456;
$mail->Host = 'localhost';
$mail->IsHTML(true);
$mail->Mailer = 'smtp';
$mail->SMTPSecure = 'ssl';
$mail->SMTPAuth = true;
$mail->Username = "alhilly";
$mail->Password = "Getdream980@";
//Sender Info
$mail->From = "alaa@alhilly.com";
$mail->FromName = "Athentication link";