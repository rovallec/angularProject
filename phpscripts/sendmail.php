<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$return = [];
$email = '';
$society = '';
$account = '';
$employeer_nit = '';
$idpayments = '';
$start = '';
$end = '';
$nit = '';
$type = '';
$number = '';
$iggs = '';
$user_name = '';
$bank = '';
$days_of_period = '';
$discounted_days = '';
$ot_hours = '';
$discounted_hours = '';
$holidays_hours = '';
$base = '';
$ot = '';
$holidays = '';
$decreto = '';
$bonificaciones = '';
$eficiencia = '';
$ajustes = '';
$igss = '';
$otras_deducciones = '';
$anticipos = '';
$isr = '';
$employee_name = '';
$nearsol_id = '';
$parqueo = '';
$total_cred = '';
$total_deb = '';
$liquido = '';

$id_employee = $request->id_payment;

$sql = "SELECT contact_details.email AS `email`, employees.society, accounts.name AS `account`, if(employees.society='NEARSOL, S.A.', '10305064-7', '0000000-0') AS `employeer_nit`, payments.idpayments, periods.start, periods.end, hires.nearsol_id, profiles.nit,
payment_methods.type, payment_methods.number, profiles.iggs, users.user_name, payment_methods.bank, 15 AS `days_of_period`, payroll_values.discounted_days, payments.ot_hours, payroll_values.discounted_hours,
payments.holidays_hours, payments.base, payments.ot, payments.holidays, `decreto`.amount AS `decreto`, (payments.credits - payments.base - coalesce(payments.ot,0) - coalesce(payments.holidays,0) -
coalesce(`decreto`.amount,0) - coalesce(`eficiencia`.amount,0) - coalesce(`ajustes`.amount,0)) AS `bonificaciones`, `eficiencia`.amount AS `eficiencia`, `ajustes`.amount AS `ajustes`, `igss`.amount AS `igss_amount`, 
`parqueo`.amount AS `parqueo`, (payments.debits - coalesce(`igss`.amount,0) - coalesce(`parqueo`.amount,0) - coalesce(`anticipos`.amount,0) - `isr`.amount) AS `otras_deducciones`,
(coalesce(`anticipos`.amount,0) + (-1*coalesce(`anticipos_cred`.amount,0))) AS `anticipos`, `isr`.amount AS `isr`,
CONCAT(profiles.first_name, ' ', profiles.second_name, ' ', profiles.first_lastname, ' ', profiles.second_lastname) AS `employee_name`, payments.credits, payments.debits, (-1*`anticipos_cred`.amount) AS `anticipos_cred_amount`
FROM payments
INNER JOIN employees ON employees.idemployees = payments.id_employee
INNER JOIN hires ON hires.idhires = employees.id_hire
INNER JOIN profiles ON profiles.idprofiles = hires.id_profile
INNER JOIN payment_methods ON payment_methods.idpayment_methods = payments.id_paymentmethod
INNER JOIN accounts ON accounts.idaccounts = employees.id_account
INNER JOIN periods ON periods.idperiods = payments.id_period
INNER JOIN users ON users.idUser = employees.reporter
INNER JOIN payroll_values ON payroll_values.id_payment = payments.idpayments
INNER JOIN contact_details ON contact_details.id_profile = profiles.idprofiles
LEFT JOIN (SELECT SUM(amount) AS `amount`, id_payment FROM credits WHERE type LIKE '%Bonos Diversos Nearsol%' GROUP BY id_payment) AS `eficiencia` ON `eficiencia`.id_payment = payments.idpayments
LEFT JOIN (SELECT SUM(amount) AS `amount`, id_payment FROM credits WHERE type LIKE '%Bonificacion Decreto%' GROUP BY id_payment) AS `decreto` ON `decreto`.id_payment = payments.idpayments
LEFT JOIN (SELECT SUM(amount) AS `amount`, id_payment FROM credits WHERE type LIKE '%Ajuste%' AND amount > 0 GROUP BY id_payment) AS `ajustes` ON `ajustes`.id_payment = payments.idpayments
LEFT JOIN (SELECT SUM(amount) AS `amount`, id_payment FROM debits WHERE type LIKE '%IGSS%' GROUP BY id_payment) AS `igss` ON `igss`.id_payment = payments.idpayments
LEFT JOIN (SELECT SUM(amount) AS `amount`, id_payment FROM debits WHERE type LIKE '%Parqueo%' OR type LIKE '%Bus%' GROUP BY id_payment) AS `parqueo` ON `parqueo`.id_payment = payments.idpayments
LEFT JOIN (SELECT SUM(amount) AS `amount`, id_payment FROM debits WHERE type LIKE '%Anticipo%' GROUP BY id_payment) AS `anticipos` ON `anticipos`.id_payment = payments.idpayments
LEFT JOIN (SELECT SUM(amount) AS `amount`, id_payment FROM credits WHERE type LIKE '%Ajuste%' AND amount < 0 GROUP BY id_payment) AS `anticipos_cred` ON `anticipos_cred`.id_payment = payments.idpayments
LEFT JOIN (SELECT SUM(amount) AS `amount`, id_payment FROM debits WHERE type LIKE '%ISR%' GROUP BY id_payment) AS `isr` ON `isr`.id_payment = payments.idpayments
where idpayments = $id_employee";

if($result = mysqli_query($con, $sql))
{
	$i = 0;
	while($row = mysqli_fetch_assoc($result))
	{
		$email = $row['email'];
		$society = $row['society'];
		$account = $row['account'];
		$employeer_nit = $row['employeer_nit'];
		$idpayments = $row['idpayments'];
		$start = $row['start'];
		$end = $row['end'];
		$nit = $row['nit'];
		$type = $row['type'];
		$number = $row['number'];
		$iggs = $row['iggs'];
		$user_name = $row['user_name'];
		$bank = $row['bank'];
		$days_of_period = $row['days_of_period'];
		$discounted_days = number_format($row['discounted_days'],2);
		$ot_hours = number_format($row['ot_hours'],2);
		$discounted_hours = number_format($row['discounted_hours'],2);
		$holidays_hours = number_format($row['holidays_hours'],2);
		$base = number_format($row['base'],2);
		$ot = number_format($row['ot'],2);
		$holidays = number_format($row['holidays'],2);
		$decreto = number_format($row['decreto'],2);
		$bonificaciones = number_format($row['bonificaciones'],2);
		$eficiencia = number_format($row['eficiencia'],2);
		$ajustes = number_format($row['ajustes'],2);
		$igss_amount = number_format($row['igss_amount'],2);
		$otras_deducciones = number_format($row['otras_deducciones'],2);
		$anticipos = number_format($row['anticipos'],2);
		$isr = number_format($row['isr'],2);
		$employee_name = $row['employee_name'];
		$nearsol_id = $row['nearsol_id'];
		$parqueo = number_format($row['parqueo'],2);
		$total_cred = number_format($row['credits'] + $row['anticipos_cred_amount'],2);
		$total_deb = number_format($row['debits'],2);
		$liquido = ($row['credits'] - $row['debits']);
	}

		$mail = new PHPMailer();
		$mail->IsSMTP();
		$mail->Mailer = "smtp";

		$mail->SMTPDebug  = 0;
		$mail->SMTPAuth   = TRUE;
		$mail->SMTPSecure = "tls";
		$mail->Port       = 587;
		$mail->Host       = "smtp.gmail.com";
		$mail->Username   = "tickets@nearsol.us";
		$mail->Password   = "2021!N.ti@lf@-Rp0713";

		$mail->IsHTML(true);
		$mail->AddAddress($email, $employee_name);
		$mail->SetFrom("tickets@nearsol.us", "MiNearsol Paystub");
		$mail->Subject = "Recibo de Sueldos Del $start  Al  $end";
		$content = "
		<body>
			<table style='margin-top:25px;width:90%;margin-left:5%'>
				<tbody>
					<tr>
						<td>$society</td>
						<td style='text-align:right'>NIT: $employeer_nit</td>
					</tr>
					<tr style='margin-left:2.5%;width:95%'>
						<td>Jornada: $account</td>
						<td style='text-align:right'>Nomina No.: $idpayments</td>
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
										<td>Por el Periodo del $start</td>
										<td>Al $end</td>
										<td>FORMA DE PAGO</td>
									</tr>
									<tr>
										<td>Codigo de Empleado $nearsol_id</td>
										<td>NIT del empleado $nit</td>
										<td>$type $number</td>
									</tr>
									<tr>
										<td>No. Afiliacion IGSS $iggs</td>
										<td>Supervisor: $user_name</td>
										<td>$bank</td>
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
									<td>$discounted_days</td>
									<td>Horas Extraordinarias:</td>
									<td>$ot_hours</td>
								</tr>
								<tr>
									<td></td>
									<td></td>
									<td>Horas Descontadas:</td>
									<td>$discounted_hours</td>
									<td>Horas Asueto:</td>
									<td>$holidays_hours</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td>
							<table style='width:100%;margin-top:0px;border:solid black 2px'>
								<tr>
									<td>Sueldo Ordinario:</td>
									<td>$base</td>
								</tr>
								<tr>
									<td>Sueldo ExtraOrdinario:</td>
									<td>$ot</td>
								</tr>
								<tr>
									<td>Sueldo extraOrdinario Dias De Asueto:</td>
									<td>$holidays</td>
								</tr>
								<tr>
									<td>Bonificación Incentivo (Decretos 78-89,7-2000 y 37-2001):</td>
									<td>$decreto</td>
								</tr>
								<tr>
									<td>Bonificación por Productividad (Decretos 78-89,7-2000 y 37-2001):</td>
									<td>$bonificaciones</td>
								</tr>
								<tr>
									<td>Bonificación por Eficiencia (Decretos 78-89,7-2000 y 37-2001) Bonos:</td>
									<td>$eficiencia</td>
								</tr>
								<tr>
									<td>Bonificación por Eficiencia (Decretos 78-89,7-2000 y 37-2001) Ajustes:</td>
									<td>$ajustes</td>
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
									<td>($igss)</td>
								</tr>
								<tr>
									<td>Descuentso (Bus/Parqueo):</td>
									<td>($parqueo)</td>
								</tr>
								<tr>
									<td>Otras Deducciones:</td>
									<td>($otras_deducciones)</td>
								</tr>
								<tr>
									<td>Anticipo Sobre Sueldos:</td>
									<td>($anticipos)</td>
								</tr>
								<tr>
									<td>ISR:</td>
									<td>($isr)</td>
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
												<td>$total_cred</td>
											</tr>
											<tr>
												<td style='border-bottom:solid black 3px'>Total Descuentos:</td>
												<td style='border-bottom:solid black 3px'>($total_deb)</td>
											</tr>
											<tr>
												<td>Sueldo Liquido:</td>
												<td>$liquido</td>
											</tr>
										</tbody>
									</table>
								</div>
								<div style='width:66.6%;float:left;margin-left:0.4%'>
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
												<td colspan='2' style='border-top:solid black 2px;text-align:center'>$employee_name</td>
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
		$res = $mail->ErrorInfo;
		var_dump($mail);
		} else {
		$res = "Successfuly Sent " . date("Y-m-d H:i:s");
		}

		$sql2 = "INSERT INTO paystub_details (`idpaystub_deatils`, `id_payment`, `recipent`, `sender`, `subject`, `content`, `result`)
				VALUES (NULL, $idpayments, $email, 'tickets@nearsol.us', 'Recibo de Sueldos Del  $start Al  $end', '$content', '$res')";

		if(mysqli_query($con, $sql2)){
			$id = mysqli_insert_id($con);
			$sql3 = "SELECT * FROM paystub_details WHERE idpaystub_details = $id";
			if($result2 = mysqli_query($con, $sql3)){
				while($row2 = mysqli_fetch_assoc($result2)){
					$return['idpaystub_deatils'] = $row2['idpaystub_deatils'];
					$return['id_payment'] = $row2['id_payment'];
					$return['recipent'] = $row2['recipent'];
					$return['sender'] = $row2['sender'];
					$return['subject'] = $row2['subject'];
					$return['content'] = $row2['content'];
					$return['result'] = $row2['result'];
				}
			}
		}
}
echo(json_encode($return));
?>;