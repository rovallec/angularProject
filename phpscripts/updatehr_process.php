<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$idhr_process = ($request->idhr_processes);
$status = ($request->status);


$sql = "UPDATE hr_processes SET `status` = '$status' WHERE `idhr_processes` = $idhr_process;";

if(mysqli_query($con,$sql)){
	http_response_code(204);
}else{
  echo($sql);
	http_response_code(400);
}
?>
