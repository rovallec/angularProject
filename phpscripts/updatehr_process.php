<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$idhr_process = ($request->idhr_processes);
$status = ($request->status);
$notes = ($request->notes);

$sql = "UPDATE hr_processes SET status = '$status', notes = '$notes' WHERE idhr_processes = $idhr_process;";

if(mysqli_query($con,$sql)){
	http_response_code(204);
}else{
  http_response_code(400);
	echo($sql);
}
?>
