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

$id_employee = $request->idemployees;

$sql = "SELECT * FROM global_variables WHERE idglobal_variables = 1;";

if($result = mysqli_query($con, $sql))
{
	$mail = new PHPMailer();
	$mail->IsSMTP();
	$mail->Mailer = "smtp";

	$i = 0;
	while($row = mysqli_fetch_assoc($result))
	{
		$mail->Password = $row['value'];
	}

	$sql2 = "SELECT employees.idemployees, UPPER(profiles.first_name) AS `first_name`, UPPER(profiles.second_name) AS `second_name`, UPPER(profiles.first_lastname) AS `first_lastname`,
			UPPER(profiles.second_lastname) AS `second_lastname`, hires.nearsol_id, employees.job, accounts.name AS `account_name`, UPPER(employees.society) AS `society`,
			employees.hiring_date, terminations.valid_from, UPPER(terminations.motive) AS `motive`, CONCAT(UPPER(terminations.reason), ' / ',UPPER(terminations.reason)) AS `reason`, UPPER(terminations.access_card) AS `access_card`,
			UPPER(terminations.headsets) AS `headsets`, terminations.bank_check, COALESCE(`vac`.`count`,0) AS `vacations`, COALESCE(`insurance`.`insurance`, 'No Cuenta Con Beneficio') AS `insurance`,
			employees.client_id, UPPER(users.user_name) AS `user_name`, terminations.period_to_pay, UPPER(`first_interview`.user_name) AS `recriuter`, UPPER(terminations.rehireable) AS `rehireable`,
			UPPER(terminations.kind) AS `kind` FROM terminations
			INNER JOIN hr_processes ON hr_processes.idhr_processes = terminations.id_process
			INNER JOIN employees ON employees.idemployees = hr_processes.id_employee
			INNER JOIN hires ON hires.idhires = employees.id_hire
			INNER JOIN profiles ON profiles.idprofiles = hires.id_profile
			INNER JOIN accounts ON accounts.idaccounts = employees.id_account
			LEFT JOIN (SELECT SUM(vacations.count) AS `count`, hr_processes.id_employee 
						FROM vacations 
						INNER JOIN hr_processes ON hr_processes.idhr_processes = vacations.id_process WHERE id_type = 4 AND status != 'DISMISSED'
						GROUP BY id_employee) AS `vac` ON `vac`.id_employee = employees.idemployees
			LEFT JOIN (SELECT 'SI' AS `insurance`, id_employee FROM insurances INNER JOIN hr_processes ON hr_processes.idhr_processes = insurances.id_process) 
						AS `insurance` ON `insurance`.id_employee = employees.idemployees
			INNER JOIN users ON users.idUser = employees.reporter
			LEFT JOIN (SELECT users.user_name, id_profile FROM processes INNER JOIN users ON users.idUser = processes.id_user WHERE name = 'First Interview')
					   	AS `first_interview` ON `first_interview`.id_profile = profiles.idprofiles WHERE idemployees = $id_employee ORDER BY valid_from ASC LIMIT 1";
	if($result = mysqli_query($con, $sql2)){
		while($row = mysqli_fetch_assoc($result))
		{
			$first_name = $row['first_name'];
			$second_name = $row['second_name'];
			$first_lastname = $row['first_lastname'];
			$second_lastname = $row['second_lastname'];
			$nearsol_id = $row['nearsol_id'];
			$job = $row['job'];
			$account_name = $row['account_name'];
			$society = $row['society'];
			$date_joining = $row['hiring_date'];
			$valid_from = $row['valid_from'];
			$reason = $row['reason'];
			$access_card = $row['access_card'];
			$headsets = $row['headsets'];
			$kind = $row['kind'];
			$motive = $row['motive'];
			$bank_check = $row['bank_check'];
			$vacations = $row['vacations'];
			$insurance = $row['insurance'];
			$supervisor = $row['user_name'];
			$period_to_pay = $row['period_to_pay'];
			$first_interview = $row['recriuter'];
			$rehireable = $row['rehireable'];
		}
		$mail->SMTPDebug  = 0;
		$mail->SMTPAuth   = TRUE;
		$mail->SMTPSecure = "tls";
		$mail->Port       = 587;
		$mail->Host       = "smtp.gmail.com";
		$mail->Username   = "tickets@nearsol.us";

		$mail->IsHTML(true);
		$mail->AddAddress('raul.ovalle@nearsol.gt', 'Agent Terms');
		$mail->SetFrom("tickets@nearsol.us", "MiNearsol N.B.P");
		$mail->Subject = "N.B.P $first_name $second_name $first_lastname $second_lastname";
		$content = "<body><table style='margin-left:10px;border-collapse: collapse; border: black 2px solid; width: 50%;text-align: center;'><tr style='border: solid black 1px; background: #0070c0; font-weight: bolder;color:white'><td colspan='2'><u>NOTIFICACION DE BAJA DE PERSONAL</u></td></tr><tr style='border: solid black 1px'><td style='border: solid black 1px; background: #0070c0; font-weight: bolder;color:white'>NOMBRE / NAME</td><td style='border: solid black 1px'>$first_name $second_name $first_lastname $second_lastname</td></tr><tr style='border: solid black 1px'><td style='border: solid black 1px; background: #0070c0; font-weight: bolder;color:white'>COD. DE EMPLEADO</td><td style='border: solid black 1px'>$nearsol_id</td></tr><tr style='border: solid black 1px'><td style='border: solid black 1px; background: #0070c0; font-weight: bolder;color:white'>PUESTO / JOB</td><td style='border: solid black 1px'>$job</td></tr><tr style='border: solid black 1px'><td style='border: solid black 1px; background: #0070c0; font-weight: bolder;color:white'>CUENTA / ACCOUNT</td><td style='border: solid black 1px'>$account_name</td></tr><tr style='border: solid black 1px'><td style='border: solid black 1px; background: #0070c0; font-weight: bolder;color:white'>EMPRESA</td><td style='border: solid black 1px'>$society</td></tr><tr style='border: solid black 1px'><td style='border: solid black 1px; background: #0070c0; font-weight: bolder;color:white'>FECHA DE INGRESO</td><td style='border: solid black 1px'>$date_joining</td></tr><tr style='border: solid black 1px'><td style='border: solid black 1px; background: #0070c0; font-weight: bolder;color:white'>FECHA DE BAJA</td><td style='border: solid black 1px; background: #ffc000; font-weight: bolder;'>$valid_from</td></tr><tr style='border: solid black 1px'><td style='border: solid black 1px; background: #0070c0; font-weight: bolder;color:white'>MOTIVO / REASON</td><td style='border: solid black 1px'>$reason</td></tr><tr style='border: solid black 1px'><td style='border: solid black 1px; background: #0070c0; font-weight: bolder;color:white'>TARJETA DE ACCESSO</td><td style='border: solid black 1px'>$access_card</td></tr><tr style='border: solid black 1px'><td style='border: solid black 1px; background: #0070c0; font-weight: bolder;color:white'>HEADSET</td><td style='border: solid black 1px'>$headsets</td></tr><tr style='border: solid black 1px; background: #0070c0; font-weight: bolder;color:white'><td colspan='2'><u>FACILITIES</u></td></tr><tr style='border: solid black 1px'><td style='border: solid black 1px; background: #0070c0; font-weight: bolder; color:white'>Control De Acceso</td><td style='border: solid black 1px'>Eliminar Accesos</td></tr><tr style='border: solid black 1px; background: #0070c0; font-weight: bolder;color:white'><td colspan='2'><u>CONTABILIDAD Y PAYROLL</u></td></tr><tr style='border: solid black 1px'><td style='border: solid black 1px; background: #0070c0; font-weight: bolder; color:white'>Razon</td><td style='border: solid black 1px'>$kind $motive</td></tr><tr style='border: solid black 1px'><td style='border: solid black 1px; background: #0070c0; font-weight: bolder; color:white'>Fecha entrega cheque</td><td style='border: solid black 1px'>$bank_check</td></tr><tr style='border: solid black 1px'><td style='border: solid black 1px; background: #0070c0; font-weight: bolder; color:white'>Vacaciones Gozadas</td><td style='border: solid black 1px'>$vacations dias</td></tr><tr style='border: solid black 1px'><td style='border: solid black 1px; background: #0070c0; font-weight: bolder; color:white'>Notificacion al seguro</td><td style='border: solid black 1px'>$insurance</td></tr><tr style='border: solid black 1px'><td style='border: solid black 1px; background: #0070c0; font-weight: bolder; color:white'>Supervisor</td><td style='border: solid black 1px'>$supervisor</td></tr><tr style='border: solid black 1px'><td style='border: solid black 1px; background: #0070c0; font-weight: bolder; color:white'>Periodo A Pagar</td><td style='border: solid black 1px'>$period_to_pay</td></tr><tr style='border: solid black 1px; background: #0070c0; font-weight: bolder;color:white'><td colspan='2'><u>RECLUTAMIENTO</u></td></tr><tr style='border: solid black 1px'><td style='border: solid black 1px; background: #0070c0; font-weight: bolder; color:white'>Reclutador/Operaciones</td><td style='border: solid black 1px'>$first_interview</td></tr><tr style='border: solid black 1px'><td style='border: solid black 1px; background: #0070c0; font-weight: bolder; color:white'>Recontratable</td><td style='border: solid black 1px'>$rehireable</td></tr></table></body>";

		$mail->MsgHTML($content); 
		if(!$mail->Send()) {
			$res = $mail->ErrorInfo;
		var_dump($mail);
		} else {
			$res = "Successfuly Sent " . date("Y-m-d H:i:s");
		}	
	}else{
		echo($sql2);
	}
	http_response_code(200);
}else{
	echo($sql);
}
?>