<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$idhr_processes = ($request->idhr_processes);
$status = ($request->status);
$notes = ($request->notes);

$notes = str_replace('\'', " ",$notes);

$sql = "UPDATE hr_processes SET notes = '$notes', status = '$status' WHERE idhr_processes = $idhr_process;";

if(mysqli_query($con,$sql)){
	http_response_code(200);
}else{
  http_response_code(400);
	echo($sql);
}
?>
