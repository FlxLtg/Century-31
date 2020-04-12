<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


function sendMail($from, $to, $subject, $body, $e)
{


$mail = new PHPMailer(TRUE);

try {
   
   $mail->setFrom($from);
   $mail->addAddress($to);
   $mail->Subject = $subject;
   $mail->Body = $body;
   
   /* SMTP parameters. */
   
   /* Tells PHPMailer to use SMTP. */
   $mail->isSMTP();
   
   /* SMTP server address. */
   $mail->Host = 'smtp.gmail.com';

   /* Use SMTP authentication. */
   $mail->SMTPAuth = TRUE;
   
   /* Set the encryption system. */
   $mail->SMTPSecure = 'tls';
   
   /* Set the SMTP port. */
   $mail->Port = 587;
  
   /* SMTP authentication username. */
   $mail->Username = 'felixprojetdev@gmail.com';
   
   /* SMTP authentication password. */
   $mail->Password = 'gmrpscousogqnjtj';
   
  /* Enable SMTP debug output. */
   $mail->SMTPDebug = 0;
   
   /* Finally send the mail. */
   $mail->send();
}
catch (Exception $e)
{
   echo $e->errorMessage();
}
catch (\Exception $e)
{
   echo $e->getMessage();
}
  
}  