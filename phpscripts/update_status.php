<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");

$request = json_decode($postdata);

$id = ($request->idprofiles);
$status = ($request->status);

$sql = "UPDATE `profiles` SET `status`='{$status}' WHERE `idprofiles`='{$id}' LIMIT 1";

if(mysqli_query($con, $sql)){
	echo $id;
	http_response_code(204);
}else{
	http_response_code(400);
}
?>