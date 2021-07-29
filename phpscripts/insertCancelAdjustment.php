<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$notes = ($request->notes);
$date = date('Y-m-d');
$sql = "UPDATE hr_processes SET `notes` = CONCAT(notes, ' | REVERTED BY $notes AT ', now());";

if(mysqli_query($con,$sql)){
	http_response_code(200);
}else{
  	http_response_code(400);
	echo($sql);
}
?>
