<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$platform = ($request->platform);
$id = ($request->id_profile);
$status = ($request->state);

if($platform != 'WAH' && $platform != 'ON SITE'){
	$sql = "UPDATE `employees` LEFT JOIN `hires` ON `hires`.`idhires` = `employees`.`id_hire` SET `client_id` = '$platform', `state` = '$status' WHERE `id_profile` = '$id';";
}else{
		$sql = "UPDATE `employees` LEFT JOIN `hires` ON `hires`.`idhires` = `employees`.`id_hire` SET `platform` = '$platform' WHERE `id_profile` = '$id';";
}



if($result = mysqli_query($con, $sql))
{
	http_response_code(200);
}else{
	http_response_code(404);
}
?>