<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$platform = ($request->platform);
$employee = ($request->idemployees);

$sql = "UPDATE `employees` SET `platform` = $platform WHERE `idemployees` = $employee";

if($result = mysqli_query($con, $sql))
{
	http_response_code(200);
}else{
	http_response_code(404);
}
?>