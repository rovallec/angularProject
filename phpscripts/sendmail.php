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
$content = "
<html>
<head><meta http-equiv=Content-Type content='text/html; charset=UTF-8'>
<style type='text/css'>
<!--
span.cls_002{font-family:Times,serif;font-size:12.1px;color:rgb(0,0,0);font-weight:normal;font-style:normal;text-decoration: none}
div.cls_002{font-family:Times,serif;font-size:12.1px;color:rgb(0,0,0);font-weight:normal;font-style:normal;text-decoration: none}
span.cls_003{font-family:Times,serif;font-size:10.1px;color:rgb(0,0,0);font-weight:normal;font-style:normal;text-decoration: none}
div.cls_003{font-family:Times,serif;font-size:10.1px;color:rgb(0,0,0);font-weight:normal;font-style:normal;text-decoration: none}
span.cls_004{font-family:Times,serif;font-size:10.1px;color:rgb(0,0,0);font-weight:bold;font-style:normal;text-decoration: none}
div.cls_004{font-family:Times,serif;font-size:10.1px;color:rgb(0,0,0);font-weight:bold;font-style:normal;text-decoration: none}
span.cls_007{font-family:Times,serif;font-size:9.2px;color:rgb(0,0,0);font-weight:normal;font-style:normal;text-decoration: none}
div.cls_007{font-family:Times,serif;font-size:9.2px;color:rgb(0,0,0);font-weight:normal;font-style:normal;text-decoration: none}
span.cls_006{font-family:Times,serif;font-size:8.0px;color:rgb(0,0,0);font-weight:normal;font-style:normal;text-decoration: none}
div.cls_006{font-family:Times,serif;font-size:8.0px;color:rgb(0,0,0);font-weight:normal;font-style:normal;text-decoration: none}
span.cls_005{font-family:Courier New,serif;font-size:10.1px;color:rgb(0,0,0);font-weight:normal;font-style:normal;text-decoration: none}
div.cls_005{font-family:Courier New,serif;font-size:10.1px;color:rgb(0,0,0);font-weight:normal;font-style:normal;text-decoration: none}
-->
</style>
<script type='text/javascript' src='0738ff94-e374-11eb-a980-0cc47a792c0a_id_0738ff94-e374-11eb-a980-0cc47a792c0a_files/wz_jsgraphics.js'></script>
</head>
<body>
<div style='position:absolute;left:50%;margin-left:-306px;top:0px;width:612px;height:792px;border-style:outset;overflow:hidden'>
<div style='position:absolute;left:0px;top:0px'>
<img src='http://172.18.2.45/assets/paystubs_background.jpg' width=612 height=792></div>
<div style='position:absolute;left:26.88px;top:18.96px' class='cls_002'><span class='cls_002'>NEARSOL, S.A.</span></div>
<div style='position:absolute;left:397.44px;top:18.96px' class='cls_002'><span class='cls_002'>NIT: 10305064-7</span></div>
<div style='position:absolute;left:26.88px;top:35.04px' class='cls_003'><span class='cls_003'>Jornada: 0001  ADMINISTRACION</span></div>
<div style='position:absolute;left:356.16px;top:35.76px' class='cls_003'><span class='cls_003'>Nomina No.: P - 0030062021.1</span></div>
<div style='position:absolute;left:26.88px;top:49.20px' class='cls_003'><span class='cls_003'>Recibo de Pago de Sueldos y Salarios</span></div>
<div style='position:absolute;left:11.04px;top:65.04px' class='cls_003'><span class='cls_003'>Por el Período del</span></div>
<div style='position:absolute;left:106.32px;top:65.76px' class='cls_003'><span class='cls_003'>16/06/2021</span></div>
<div style='position:absolute;left:195.60px;top:65.04px' class='cls_003'><span class='cls_003'>Al</span></div>
<div style='position:absolute;left:221.04px;top:65.76px' class='cls_003'><span class='cls_003'>30/06/2021</span></div>
<div style='position:absolute;left:377.04px;top:65.04px' class='cls_003'><span class='cls_003'>FORMA DE PAGO:</span></div>
<div style='position:absolute;left:11.04px;top:78.72px' class='cls_004'><span class='cls_004'>Código de Empleado</span><span class='cls_003'> 0000STF174</span></div>
<div style='position:absolute;left:194.88px;top:78.72px' class='cls_004'><span class='cls_004'>NIT del Empleado</span><span class='cls_007'> 91465567</span></div>
<div style='position:absolute;left:377.04px;top:77.76px' class='cls_003'><span class='cls_003'>Transferencia</span></div>
<div style='position:absolute;left:439.45px;top:77.76px' class='cls_003'><span class='cls_003'>902559947</span></div>
<div style='position:absolute;left:11.04px;top:92.16px' class='cls_004'><span class='cls_004'>No. Afiliación IGSS</span></div>
<div style='position:absolute;left:106.32px;top:92.64px' class='cls_003'><span class='cls_003'>3001561460101</span></div>
<div style='position:absolute;left:194.88px;top:90.48px' class='cls_003'><span class='cls_003'>Supervisor</span><span class='cls_006'>  ADMINISTRACION</span></div>
<div style='position:absolute;left:377.04px;top:92.64px' class='cls_006'><span class='cls_006'>BAC</span></div>
<div style='position:absolute;left:11.04px;top:109.92px' class='cls_003'><span class='cls_003'>Días del Periodo:</span></div>
<div style='position:absolute;left:155.52px;top:110.64px' class='cls_003'><span class='cls_003'>15</span></div>
<div style='position:absolute;left:199.45px;top:109.92px' class='cls_003'><span class='cls_003'>Dias Descontados:</span></div>
<div style='position:absolute;left:356.40px;top:110.64px' class='cls_003'><span class='cls_003'>0</span></div>
<div style='position:absolute;left:369.60px;top:109.92px' class='cls_003'><span class='cls_003'>Horas Extraordinarias:</span></div>
<div style='position:absolute;left:537.84px;top:110.64px' class='cls_003'><span class='cls_003'>0.00</span></div>
<div style='position:absolute;left:199.44px;top:123.36px' class='cls_003'><span class='cls_003'>Horas Descontadas:</span></div>
<div style='position:absolute;left:343.68px;top:124.08px' class='cls_003'><span class='cls_003'>0.00</span></div>
<div style='position:absolute;left:369.60px;top:123.36px' class='cls_003'><span class='cls_003'>Horas Asueto:</span></div>
<div style='position:absolute;left:537.84px;top:124.08px' class='cls_003'><span class='cls_003'>0.00</span></div>
<div style='position:absolute;left:8.16px;top:141.36px' class='cls_003'><span class='cls_003'>Sueldo Ordinario:</span></div>
<div style='position:absolute;left:325.92px;top:142.08px' class='cls_003'><span class='cls_003'>1,412.55</span></div>
<div style='position:absolute;left:369.60px;top:141.36px' class='cls_003'><span class='cls_003'>IGSS Laboral:</span></div>
<div style='position:absolute;left:504.96px;top:142.08px' class='cls_003'><span class='cls_003'>(</span></div>
<div style='position:absolute;left:529.44px;top:142.08px' class='cls_003'><span class='cls_003'>68.23)</span></div>
<div style='position:absolute;left:8.16px;top:155.04px' class='cls_003'><span class='cls_003'>Sueldo ExtraOrdinario:</span></div>
<div style='position:absolute;left:343.68px;top:155.76px' class='cls_003'><span class='cls_003'>0.00</span></div>
<div style='position:absolute;left:369.60px;top:155.04px' class='cls_003'><span class='cls_003'>Descuentos (Bus/Parqueo):</span></div>
<div style='position:absolute;left:537.84px;top:155.76px' class='cls_003'><span class='cls_003'>0.00</span></div>
<div style='position:absolute;left:8.16px;top:168.48px' class='cls_003'><span class='cls_003'>Sueldo ExtraOrdinario Dias de Asueto:</span></div>
<div style='position:absolute;left:343.68px;top:169.20px' class='cls_003'><span class='cls_003'>0.00</span></div>
<div style='position:absolute;left:369.60px;top:168.48px' class='cls_003'><span class='cls_003'>Otras Deducciones:</span></div>
<div style='position:absolute;left:537.84px;top:169.20px' class='cls_003'><span class='cls_003'>0.00</span></div>
<div style='position:absolute;left:8.16px;top:181.92px' class='cls_003'><span class='cls_003'>Bonificación Incentivo (Decretos 78-89,7-2000 y 37-2001):</span></div>
<div style='position:absolute;left:333.60px;top:182.64px' class='cls_003'><span class='cls_003'>125.00</span></div>
<div style='position:absolute;left:369.60px;top:181.92px' class='cls_003'><span class='cls_003'>Anticipo Sobre Sueldos:</span></div>
<div style='position:absolute;left:537.84px;top:182.64px' class='cls_003'><span class='cls_003'>0.00</span></div>
<div style='position:absolute;left:8.16px;top:195.36px' class='cls_003'><span class='cls_003'>Bonificación por Productividad (Decretos 78-89,7-2000 y 37-2001):</span></div>
<div style='position:absolute;left:325.92px;top:196.08px' class='cls_003'><span class='cls_003'>1,962.45</span></div>
<div style='position:absolute;left:369.60px;top:195.36px' class='cls_003'><span class='cls_003'>ISR:</span></div>
<div style='position:absolute;left:502.56px;top:196.08px' class='cls_003'><span class='cls_003'>(</span></div>
<div style='position:absolute;left:524.40px;top:196.08px' class='cls_003'><span class='cls_003'>100.22)</span></div>
<div style='position:absolute;left:8.16px;top:209.04px' class='cls_003'><span class='cls_003'>Bonificación por Eficiencia (Decretos 78-89,7-2000 y 37-2001) Bonos:</span></div>
<div style='position:absolute;left:333.60px;top:209.76px' class='cls_003'><span class='cls_003'>500.00</span></div>
<div style='position:absolute;left:8.16px;top:222.48px' class='cls_003'><span class='cls_003'>Bonificación por Eficiencia (Decretos 78-89,7-2000 y 37-2001) Ajustes:</span></div>
<div style='position:absolute;left:343.68px;top:223.20px' class='cls_003'><span class='cls_003'>0.00</span></div>
<div style='position:absolute;left:19.44px;top:272.16px' class='cls_004'><span class='cls_004'>Total Devengado:</span></div>
<div style='position:absolute;left:163.92px;top:272.88px' class='cls_004'><span class='cls_004'>4,000.00</span></div>
<div style='position:absolute;left:240.72px;top:282.72px' class='cls_005'><span class='cls_005'>f:</span></div>
<div style='position:absolute;left:19.44px;top:285.60px' class='cls_004'><span class='cls_004'>Total Descuentos:</span></div>
<div style='position:absolute;left:143.76px;top:286.32px' class='cls_004'><span class='cls_004'>(</span></div>
<div style='position:absolute;left:168.24px;top:286.32px' class='cls_004'><span class='cls_004'>168.45)</span></div>
<div style='position:absolute;left:296.16px;top:297.84px' class='cls_005'><span class='cls_005'>RAUL ALEJANDRO OVALLE CASTILLO</span></div>
<div style='position:absolute;left:19.44px;top:301.44px' class='cls_004'><span class='cls_004'>Sueldo Liquido:</span></div>
<div style='position:absolute;left:163.92px;top:302.88px' class='cls_004'><span class='cls_004'>3,831.55</span></div>
<div style='position:absolute;left:9.60px;top:320.64px' class='cls_003'><span class='cls_003'>Observaciones:</span></div>
<div style='position:absolute;left:26.88px;top:406.80px' class='cls_002'><span class='cls_002'>NEARSOL, S.A.</span></div>
<div style='position:absolute;left:397.44px;top:406.80px' class='cls_002'><span class='cls_002'>NIT: 10305064-7</span></div>
<div style='position:absolute;left:26.88px;top:422.64px' class='cls_003'><span class='cls_003'>Jornada: 0001  ADMINISTRACION</span></div>
<div style='position:absolute;left:356.16px;top:423.36px' class='cls_003'><span class='cls_003'>Nomina No.: P - 0030062021.1</span></div>
<div style='position:absolute;left:26.88px;top:437.04px' class='cls_003'><span class='cls_003'>Recibo de Pago de Sueldos y Salarios</span></div>
<div style='position:absolute;left:11.04px;top:452.64px' class='cls_003'><span class='cls_003'>Por el Período del</span></div>
<div style='position:absolute;left:106.32px;top:453.36px' class='cls_003'><span class='cls_003'>16/06/2021</span></div>
<div style='position:absolute;left:195.60px;top:452.64px' class='cls_003'><span class='cls_003'>Al</span></div>
<div style='position:absolute;left:221.04px;top:453.36px' class='cls_003'><span class='cls_003'>30/06/2021</span></div>
<div style='position:absolute;left:377.04px;top:452.64px' class='cls_003'><span class='cls_003'>FORMA DE PAGO:</span></div>
<div style='position:absolute;left:11.04px;top:466.32px' class='cls_004'><span class='cls_004'>Código de Empleado</span><span class='cls_003'> 0000STF174</span></div>
<div style='position:absolute;left:194.88px;top:466.32px' class='cls_004'><span class='cls_004'>NIT del Empleado</span><span class='cls_007'> 91465567</span></div>
<div style='position:absolute;left:377.04px;top:465.36px' class='cls_003'><span class='cls_003'>Transferencia</span></div>
<div style='position:absolute;left:439.45px;top:465.36px' class='cls_003'><span class='cls_003'>902559947</span></div>
<div style='position:absolute;left:11.04px;top:480.00px' class='cls_004'><span class='cls_004'>No. Afiliación IGSS</span></div>
<div style='position:absolute;left:106.32px;top:480.48px' class='cls_003'><span class='cls_003'>3001561460101</span></div>
<div style='position:absolute;left:194.88px;top:478.08px' class='cls_003'><span class='cls_003'>Supervisor</span><span class='cls_006'>  ADMINISTRACION</span></div>
<div style='position:absolute;left:377.04px;top:480.48px' class='cls_006'><span class='cls_006'>BAC</span></div>
<div style='position:absolute;left:11.04px;top:497.76px' class='cls_003'><span class='cls_003'>Días del Periodo:</span></div>
<div style='position:absolute;left:155.52px;top:498.48px' class='cls_003'><span class='cls_003'>15</span></div>
<div style='position:absolute;left:199.45px;top:497.76px' class='cls_003'><span class='cls_003'>Dias Descontados:</span></div>
<div style='position:absolute;left:356.40px;top:498.48px' class='cls_003'><span class='cls_003'>0</span></div>
<div style='position:absolute;left:369.60px;top:497.76px' class='cls_003'><span class='cls_003'>Horas Extraordinarias:</span></div>
<div style='position:absolute;left:537.84px;top:498.48px' class='cls_003'><span class='cls_003'>0.00</span></div>
<div style='position:absolute;left:199.44px;top:511.20px' class='cls_003'><span class='cls_003'>Horas Descontadas:</span></div>
<div style='position:absolute;left:343.68px;top:511.92px' class='cls_003'><span class='cls_003'>0.00</span></div>
<div style='position:absolute;left:369.60px;top:511.20px' class='cls_003'><span class='cls_003'>Horas Asueto:</span></div>
<div style='position:absolute;left:537.84px;top:511.92px' class='cls_003'><span class='cls_003'>0.00</span></div>
<div style='position:absolute;left:8.16px;top:529.20px' class='cls_003'><span class='cls_003'>Sueldo Ordinario:</span></div>
<div style='position:absolute;left:325.92px;top:529.92px' class='cls_003'><span class='cls_003'>1,412.55</span></div>
<div style='position:absolute;left:369.60px;top:529.20px' class='cls_003'><span class='cls_003'>IGSS Laboral:</span></div>
<div style='position:absolute;left:504.96px;top:529.92px' class='cls_003'><span class='cls_003'>(</span></div>
<div style='position:absolute;left:529.44px;top:529.92px' class='cls_003'><span class='cls_003'>68.23)</span></div>
<div style='position:absolute;left:8.16px;top:542.64px' class='cls_003'><span class='cls_003'>Sueldo ExtraOrdinario:</span></div>
<div style='position:absolute;left:343.68px;top:543.36px' class='cls_003'><span class='cls_003'>0.00</span></div>
<div style='position:absolute;left:369.60px;top:542.64px' class='cls_003'><span class='cls_003'>Descuentos (Bus/Parqueo):</span></div>
<div style='position:absolute;left:537.84px;top:543.36px' class='cls_003'><span class='cls_003'>0.00</span></div>
<div style='position:absolute;left:8.16px;top:556.08px' class='cls_003'><span class='cls_003'>Sueldo ExtraOrdinario Dias de Asueto:</span></div>
<div style='position:absolute;left:343.68px;top:557.04px' class='cls_003'><span class='cls_003'>0.00</span></div>
<div style='position:absolute;left:369.60px;top:556.08px' class='cls_003'><span class='cls_003'>Otras Deducciones:</span></div>
<div style='position:absolute;left:537.84px;top:557.04px' class='cls_003'><span class='cls_003'>0.00</span></div>
<div style='position:absolute;left:8.16px;top:569.76px' class='cls_003'><span class='cls_003'>Bonificación Incentivo (Decretos 78-89,7-2000 y 37-2001):</span></div>
<div style='position:absolute;left:333.60px;top:570.48px' class='cls_003'><span class='cls_003'>125.00</span></div>
<div style='position:absolute;left:369.60px;top:569.76px' class='cls_003'><span class='cls_003'>Anticipo Sobre Sueldos:</span></div>
<div style='position:absolute;left:537.84px;top:570.48px' class='cls_003'><span class='cls_003'>0.00</span></div>
<div style='position:absolute;left:8.16px;top:583.20px' class='cls_003'><span class='cls_003'>Bonificación por Productividad (Decretos 78-89,7-2000 y 37-2001):</span></div>
<div style='position:absolute;left:325.92px;top:583.92px' class='cls_003'><span class='cls_003'>1,962.45</span></div>
<div style='position:absolute;left:369.60px;top:583.20px' class='cls_003'><span class='cls_003'>ISR:</span></div>
<div style='position:absolute;left:502.56px;top:583.92px' class='cls_003'><span class='cls_003'>(</span></div>
<div style='position:absolute;left:524.40px;top:583.92px' class='cls_003'><span class='cls_003'>100.22)</span></div>
<div style='position:absolute;left:8.16px;top:596.64px' class='cls_003'><span class='cls_003'>Bonificación por Eficiencia (Decretos 78-89,7-2000 y 37-2001) Bonos:</span></div>
<div style='position:absolute;left:333.60px;top:597.36px' class='cls_003'><span class='cls_003'>500.00</span></div>
<div style='position:absolute;left:8.16px;top:610.08px' class='cls_003'><span class='cls_003'>Bonificación por Eficiencia (Decretos 78-89,7-2000 y 37-2001) Ajustes:</span></div>
<div style='position:absolute;left:343.68px;top:611.04px' class='cls_003'><span class='cls_003'>0.00</span></div>
<div style='position:absolute;left:19.44px;top:660.00px' class='cls_004'><span class='cls_004'>Total Devengado:</span></div>
<div style='position:absolute;left:163.92px;top:660.72px' class='cls_004'><span class='cls_004'>4,000.00</span></div>
<div style='position:absolute;left:240.72px;top:670.56px' class='cls_005'><span class='cls_005'>f:</span></div>
<div style='position:absolute;left:19.44px;top:673.44px' class='cls_004'><span class='cls_004'>Total Descuentos:</span></div>
<div style='position:absolute;left:143.76px;top:674.16px' class='cls_004'><span class='cls_004'>(</span></div>
<div style='position:absolute;left:168.24px;top:674.16px' class='cls_004'><span class='cls_004'>168.45)</span></div>
<div style='position:absolute;left:296.16px;top:685.44px' class='cls_005'><span class='cls_005'>RAUL ALEJANDRO OVALLE CASTILLO</span></div>
<div style='position:absolute;left:19.44px;top:689.28px' class='cls_004'><span class='cls_004'>Sueldo Liquido:</span></div>
<div style='position:absolute;left:163.92px;top:690.72px' class='cls_004'><span class='cls_004'>3,831.55</span></div>
<div style='position:absolute;left:9.60px;top:708.48px' class='cls_003'><span class='cls_003'>Observaciones:</span></div>
</div>

</body>
</html>
";

$mail->MsgHTML($content); 
if(!$mail->Send()) {
  $res = "0";
  var_dump($mail);
} else {
  $res = "1";
}
echo("finish");
?>;