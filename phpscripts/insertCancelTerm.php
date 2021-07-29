<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$idhr_process = ($request->idhr_processes);
$date = date('Y-m-d');
$sql = "UPDATE hr_processes 
		INNER JOIN employees ON employees.idemployees = hr_processes.id_employee
		SET notes = CONCAT(`notes`, ' | REVERTED AT ', now()),
			`id_type` = 22,
			employees.termination_date = NULL
		WHERE idhr_processes = $idhr_process;";

$sql2 = "DELETE FROM terminations WHERE id_process = $idhr_process";

if(mysqli_query($con,$sql)){
	if(mysqli_query($con,$sql2)){
		http_response_code(200);
	}else{
		http_response_code(400);
		echo($sql2);		
	}
}else{
  	http_response_code(400);
	echo($sql);
}
?>
