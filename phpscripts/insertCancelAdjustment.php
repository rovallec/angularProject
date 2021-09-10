<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$notes = ($request->notes);
$id_process = ($request->id_process);
$date = date('Y-m-d');
$sql = "UPDATE hr_processes SET `notes` = CONCAT(notes, ' | REVERTED BY $notes AT ', now()) WHERE `idhr_processes` = $id_process;";

if(mysqli_query($con,$sql)){
	http_response_code(200);
}else{
  	http_response_code(400);
	echo($sql);
}
?>
