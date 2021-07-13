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
<body>
	<table style='margin-top:25px;width:90%;margin-left:5%'>
		<tbody>
			<tr>
				<td>NEARSOL, S.A.</td>
				<td style='text-align:right'>NIT: 10305064-7</td>
			</tr>
			<tr style='margin-left:2.5%;width:95%'>
				<td>Jornada: IT</td>
				<td style='text-align:right'>Nomina No.: 1059815</td>
			</tr>
			<tr style='margin-left:2.5%;width:95%'>
				<td>Recibo de Pago de Sueldos y Salarios</td>
				<td></td>
			</tr>
			<tr style='border:solid black 2px; width:100%'>
				<td colspan='2'>
					<table style='width:100%;margin-top:2px;border:solid black 2px'>
						<tbody>
							<tr>
								<td>Por el Periodo del 16/06/2021</td>
								<td>Al 30/06/2021</td>
								<td>FORMA DE PAGO</td>
							</tr>
							<tr>
								<td>Codigo de Empleado 0000STF174</td>
								<td>NIT del empleado 9146556-7</td>
								<td>Transferencia 902559947</td>
							</tr>
							<tr>
								<td>No. Afiliacion IGSS 3001561460101</td>
								<td>Supervisor: Administracion</td>
								<td>BAC</td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
			<tr>
				<td colspan='2'>
					<table style='width:100%;margin-top:0px;border:solid black 2px'>
						<tr>
							<td>Dias del Periodo:</td>
							<td>15</td>
							<td>Dias Descontados:</td>
							<td>0</td>
							<td>Horas Extraordinarias:</td>
							<td>0.00</td>
						</tr>
						<tr>
							<td></td>
							<td></td>
							<td>Horas Descontadas:</td>
							<td>0.00</td>
							<td>Horas Asueto:</td>
							<td>0.00</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td>
					<table style='width:100%;margin-top:0px;border:solid black 2px'>
						<tr>
							<td>Sueldo Ordinario:</td>
							<td>1,412.55</td>
						</tr>
						<tr>
							<td>Sueldo ExtraOrdinario:</td>
							<td>0.00</td>
						</tr>
						<tr>
							<td>Sueldo extraOrdinario Dias De Asueto:</td>
							<td>0.00</td>
						</tr>
						<tr>
							<td>Bonificación Incentivo (Decretos 78-89,7-2000 y 37-2001):</td>
							<td>125.00</td>
						</tr>
						<tr>
							<td>Bonificación por Productividad (Decretos 78-89,7-2000 y 37-2001):</td>
							<td>1,962.45</td>
						</tr>
						<tr>
							<td>Bonificación por Eficiencia (Decretos 78-89,7-2000 y 37-2001) Bonos:</td>
							<td>500.00</td>
						</tr>
						<tr>
							<td>Bonificación por Eficiencia (Decretos 78-89,7-2000 y 37-2001) Ajustes:</td>
							<td>0.00</td>
						</tr>
						<tr>
							<td style='color:white'>.</td>
							<td></td>
						</tr>
					</table>
				</td>
				<td>
					<table style='width:100%;margin-top:0px;border:solid black 2px;height:100%'>
						<tr>
							<td>IGSS Laboral:</td>
							<td>(68.23)</td>
						</tr>
						<tr>
							<td>Descuentso (Bus/Parqueo):</td>
							<td>0.00</td>
						</tr>
						<tr>
							<td>Otras Deducciones:</td>
							<td>0.00</td>
						</tr>
						<tr>
							<td>ANticipo Sobre Sueldos:</td>
							<td>0.00</td>
						</tr>
						<tr>
							<td>ISR:</td>
							<td>(100.22)</td>
						</tr>
						<tr>
							<td style='color:white'>.</td>
							<td></td>
						</tr>
						<tr>
							<td style='color:white'>.</td>
							<td></td>
						</tr>
						<tr>
							<td style='color:white'>.</td>
							<td></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td colspan='2'>
					<div style='position:relative;width:100%'>
						<div style='width:33%;float:left'>
							<table style='width:100%;margin-top:0px;border:solid black 2px;height:100%'>
								<tbody>
									<tr>
										<td>Total Devengado:</td>
										<td>4,000.00</td>
									</tr>
									<tr>
										<td style='border-bottom:solid black 3px'>Total Descuentos:</td>
										<td style='border-bottom:solid black 3px'>(168.45)</td>
									</tr>
									<tr>
										<td>Sueldo Liquido:</td>
										<td>3,832.55</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div style='width:66.7%;position:absolute;left:33.3%'>
							<table style='width:100%;margin-top:0px;border:solid black 2px;height:100%'>
								<tbody>
									<tr>
										<td style='color:white'>f</td>
										<td></td>
										<td></td>
										<td></td>
									</tr>
									<tr>
										<td style='text-align:right;border-bottom:solid white 1px'>f:</td>
										<td></td>
										<td></td>
										<td></td>
									</tr>
									<tr>
										<td></td>
										<td colspan='2' style='border-top:solid black 2px;text-align:center'>RAUL ALEJANDRO OVALLE CASTILLO</td>
										<td></td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</td>
			</tr>
			<tr>
				<td colspan='2'>
					<div style='position:relative;'>
						<table style='width:100%;margin-top:0px;border:solid black 2px;height:100%'>
							<tbody>
								<tr>
									<td>Observaciones:</td>
									<td colspan='4'></td>
								</tr>
								<tr>
									<td style='color:white'>.</td>
									<td colspan='4'></td>
								</tr>
							</tbody>
						</table>
					</div>
				</td>
			</tr>
		</tbody>
	</table>
</body>
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