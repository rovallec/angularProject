<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$date = date('Y/m/d');

for ($i=0; $i < count($request); $i++) { 
	$id_hire = ($request[$i]->id_hire);
	$id_account = ($request[$i]->id_account);
	$reporter = ($request[$i]->reporter);
	$client_id = ($request[$i]->client_id);
	$hiring_date = ($request[$i]->hiring_date);
	$job = ($request[$i]->job);
	$productivity_payment = ($request[$i]->productivity_payment);
	$state = ($request[$i]->state);
	$user = ($request[$i]->id_user);
	$department = ($request[$i]->id_department);

	$sql0="SELECT * FROM `hires` LEFT JOIN `waves` ON `waves`.`idwaves` = `hires`.`id_wave` WHERE `idhires` = $id_hire;";
	if($result = mysqli_query($con, $sql0)){
		while($row = mysqli_fetch_assoc($result)){
			$base_salary = $row['base_payment'];
		}
	}

	$sql = "INSERT INTO `employees`(`idemployees`, `id_hire`, `id_account`, `reporter`, `client_id`, `hiring_date`, `job`, `base_payment`, `state`, `productivity_payment`, `active`) SELECT * FROM ( SELECT null, '$id_hire' AS `1`, '$id_account' AS `2`, '$reporter' AS `3`, '$client_id' AS `4`, '$hiring_date' AS `5`, '$job' AS `6`, '$base_salary' AS `7`, '$state' AS `8`, '$productivity_payment' AS `9`, '1' AS `10`) AS `tmp` WHERE NOT EXISTS (SELECT `id_hire` FROM `employees` WHERE `id_hire` = '$id_hire');";

	$sql1 = "UPDATE `profiles` LEFT JOIN `hires` ON `hires`.`id_profile` = `profiles`.`idprofiles` SET `profiles`.`status` = '$state' WHERE `hires`.`idhires` = $id_hire;";
	if(mysqli_query($con, $sql)){
		$id_employee = mysqli_insert_id($con);
		if(mysqli_query($con,$sql1)){
			$sql2 = "INSERT INTO `hr_processes`(`idhr_processes`, `id_user`, `id_employee`, `id_type`, `id_department`, `date`, `notes`, `status`) VALUES (NULL, '$user', '$id_employee', '1', '$department', '$date', 'Inherent Process', 'CLOSED');";
			if(mysqli_query($con,$sql2)){
			}else{
				http_response_code(404);
			}
		}
	}else{
		http_response_code(400);
	}
}
?>