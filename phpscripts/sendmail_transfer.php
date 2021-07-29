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

$id = $request->id_employee;

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

	$sql2 = "SELECT UPPER(profiles.first_name) AS `first_name`, UPPER(profiles.second_name) AS `second_name`, UPPER(profiles.first_lastname) AS `first_lastname`, UPPER(profiles.second_lastname) AS `second_lastname`, hires.nearsol_id,
			UPPER(employees.job) AS `job`, UPPER(accounts.name) AS `from`, UPPER(acc.name) AS `to`, hr_processes.date, UPPER(users.user_name) AS `user_name` FROM hr_processes
				INNER JOIN employees ON employees.idemployees = hr_processes.id_employee
				INNER JOIN accounts ON accounts.idaccounts = SUBSTRING_INDEX(hr_processes.notes, '|', 1)
				INNER JOIN accounts AS acc ON acc.idaccounts = SUBSTRING(hr_processes.notes,LENGTH(SUBSTRING_INDEX(hr_processes.notes,'|',1)) - LENGTH(SUBSTRING_INDEX(hr_processes.notes,'|',2)) + 1)
				INNER JOIN hires ON hires.idhires = employees.id_hire
				INNER JOIN profiles ON profiles.idprofiles = hires.id_profile
                INNER JOIN users ON users.idUser = employees.reporter
			WHERE id_type = 16 AND idemployees = $id ORDER BY idhr_processes DESC LIMIT 1;";
	if($result = mysqli_query($con, $sql2)){
		while($row = mysqli_fetch_assoc($result))
		{
			$first_name = $row['first_name'];
			$second_name = $row['second_name'];
			$first_lastname = $row['first_lastname'];
			$second_lastname = $row['second_lastname'];
			$nearsol_id = $row['nearsol_id'];
			$job = $row['job'];
			$old_account = $row['to'];
			$new_account = $row['from'];
			$date = $row['date'];
			$reporter = $row['user_name'];
		}
		$mail->SMTPDebug  = 0;
		$mail->SMTPAuth   = TRUE;
		$mail->SMTPSecure = "tls";
		$mail->Port       = 587;
		$mail->Host       = "smtp.gmail.com";
		$mail->Username   = "tickets@nearsol.us";

		$mail->IsHTML(true);
		$mail->AddAddress('raul.ovalle@nearsol.gt', 'Raul Ovalle');
		$mail->SetFrom("tickets@nearsol.us", "MiNearsol N.T.P");
		$mail->Subject = "N.T.P $first_name $second_name $first_lastname $second_lastname";
		$content = "<body><table style='margin-left:10px;border-collapse: collapse; border: black 2px solid; width: 50%;text-align: center;'><tr style='border: solid black 1px; background: #0070c0; font-weight: bolder;color:white'><td colspan='2'><u>NOTIFICACION DE TRANSFERENCIA DE PERSONAL</u></td></tr><tr style='border: solid black 1px'><td style='border: solid black 1px; background: #0070c0; font-weight: bolder;color:white'>NOMBRE</td><td style='border: solid black 1px'>$first_name $second_name $first_lastname $second_lastname</td></tr><tr style='border: solid rgb(206, 205, 205) 1px'><td style='border: solid black 1px; background: #0070c0; font-weight: bolder;color:white'>COD. DE EMPLEADO</td><td style='border: solid black 1px'>$nearsol_id</td></tr><tr style='border: solid black 1px'><td style='border: solid black 1px; background: #0070c0; font-weight: bolder;color:white'>PUESTO</td><td style='border: solid black 1px'>$job</td></tr><tr style='border: solid black 1px'><td style='border: solid black 1px; background: #0070c0; font-weight: bolder;color:white'>CUENTA RECURRENTE</td><td style='border: solid black 1px'>$old_account</td></tr><tr style='border: solid black 1px'><td style='border: solid black 1px; background: #0070c0; font-weight: bolder;color:white'>CUENTA A TRANSFERIRSE</td><td style='border: solid black 1px'>$new_account</td></tr><tr style='border: solid black 1px'><td style='border: solid black 1px; background: #0070c0; font-weight: bolder;color:white'>FECHA EFECTIVA DE TRANSFERENCIA</td><td style='border: solid black 1px; background:#9bc2e6'>$date</td></tr><tr style='border: solid black 1px'><td style='border: solid black 1px; background: #0070c0; font-weight: bolder;color:white'>NUEVO PUESTO</td><td style='border: solid black 1px; background: #9bc2e6;'>$job</td></tr><tr style='border: solid black 1px'><td style='border: solid black 1px; background: #0070c0; font-weight: bolder;color:white'>NUEVO SUPERVISOR</td><td style='border: solid black 1px; background:#9bc2e6'>$reporter</td></tr><tr style='border: solid black 1px'><td style='border: solid black 1px; background: #0070c0; font-weight: bolder;color:white'>MOTIVO DE TRANSFERENCIA:</td><td style='border: solid black 1px'>SOLICITUD DEL CLIENTE</td></tr></table></body>";

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