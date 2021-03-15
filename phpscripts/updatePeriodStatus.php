<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");

$request = json_decode($postdata);

$id = ($request->idprofiles);
$status = ($request->status);

$sql = "UPDATE `periods` SET `status`= $status WHERE `idperiods`={$id}";

if(mysqli_query($con, $sql)){
	echo (json_encode("1|"));
}else{
	$str = "0|" . mysqli_error($con);
	echo (json_encode($str));
}
?>