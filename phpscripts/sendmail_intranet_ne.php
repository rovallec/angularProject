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

$id = $request->id;
$date = $request->date;
$type = $request->type;
$status = $request->status;
$description = $request->description;

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

	$sql2 = "SELECT UPPER(profiles.first_name) AS `first_name`, UPPER(profiles.second_name) AS `second_name`, UPPER(profiles.first_lastname) AS `first_lastname`,
				UPPER(profiles.second_lastname) AS `second_lastname`, hires.nearsol_id AS `nearsol_id`, contact_details.email AS `email`, `reporter_contactDetails`.email AS `rep_email`, users.user_name as `user_name`
				FROM employees
				INNER JOIN hires ON hires.idhires = employees.id_hire
				INNER JOIN profiles ON profiles.idprofiles = hires.id_profile
				INNER JOIN contact_details ON contact_details.id_profile = profiles.idprofiles
				INNER JOIN users ON users.idUser = employees.reporter
				INNER JOIN profiles AS `reporter_profile` ON `reporter_profile`.idprofiles = users.id_profile
				INNER JOIN contact_details AS `reporter_contactDetails` ON `reporter_contactDetails`.id_profile = `reporter_profile`.idprofiles
			 WHERE employees.idemployees = $id;";

	if($result = mysqli_query($con, $sql2)){
		while($row = mysqli_fetch_assoc($result))
		{
			$first_name = $row['first_name'];
			$second_name = $row['second_name'];
			$first_lastname = $row['first_lastname'];
			$second_lastname = $row['second_lastname'];
			$nearsol_id = $row['nearsol_id'];
			$email = $row['email'];
			$rep_email = $row['rep_email'];
			$user_name = $row['user_name'];
		}
		$mail->SMTPDebug  = 0;
		$mail->SMTPAuth   = TRUE;
		$mail->SMTPSecure = "tls";
		$mail->Port       = 587;
		$mail->Host       = "smtp.gmail.com";
		$mail->Username   = "tickets@nearsol.us";

		$mail->IsHTML(true);
		$mail->AddAddress('dessiree.rivera@nearsol.gt', 'Dessiree Rivera');
		$mail->AddAddress($rep_email, $user_name);
		$mail->SetFrom("tickets@nearsol.us", "MiNearsol New Request");
		$mail->Subject = "Intranet New Request";
		$content = "<body style='width:100%'>
						<table style='width:100%'>
							<tr>
								<td colspan=2>
									A request has been placed or updated by an internal access trought the Intranet tool.
								</td>
							</tr>
							<tr>
								<td colspan=2 style='color:white;background-color:#FF4237'>NEW INTRANET REQUEST</td>
							</tr>
							<tr>
								<td style='color:white;background-color:#003B71'>
									USER:
								</td>
								<td>
									$first_name $second_name $first_lastname $second_lastname
								</td>
							</tr>
							<tr>
								<td style='color:white;background-color:#003B71'>
									NEARSOL ID:
								</td>
								<td>
									$nearsol_id

								</td>
							</tr>
							<tr>
								<td style='color:white;background-color:#003B71'>
									ACTION:
								</td>
								<td>
									$type
								</td>
							</tr>
							<tr>
								<td style='color:white;background-color:#003B71'>
									DATE:
								</td>
								<td>
									$date
								</td>
							</tr>
							<tr>
								<td style='color:white;background-color:#003B71'>
									STATUS:
								</td>
								<td>
									$status
								</td>
							</tr>
							<tr>
								<td colspan=2 style='color:white;background-color:#FF4237'>DESCRIPTION</td>
							</tr>
							$description
						</table>
					</body>";

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