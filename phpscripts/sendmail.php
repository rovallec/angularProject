<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$res = [];

$test = $request->test;

$mail = new PHPMailer();
$mail->IsSMTP();
$mail->Mailer = "smtp";

$mail->SMTPDebug  = 0;
$mail->SMTPAuth   = TRUE;
$mail->SMTPSecure = "tls";
$mail->Port       = 587;
$mail->Host       = "smtp.gmail.com";
$mail->Username   = "tickets@nearsol.us";
$mail->Password   = "Nearsol.2020!";

$mail->IsHTML(true);
$mail->AddAddress("raul.ovalle@nearsol.gt", "Raul Ovalle");
$mail->SetFrom("tickets@nearsol.us", "MiNearsol");
$mail->Subject = "Test is Test Email sent via Gmail SMTP Server using PHP Mailer";
$content = "<b>This is a Test Email sent via Gmail SMTP Server using PHP mailer class.</b><br>Esto se le adiciona como ". $test;

$mail->MsgHTML($content); 
if(!$mail->Send()) {
  $res = "0"
  var_dump($mail);
} else {
  $res = "1";
}
echo("finish");
?>;