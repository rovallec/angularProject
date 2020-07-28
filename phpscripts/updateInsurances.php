<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$id_user = ($request->id_user);
$id_employee = ($request->id_employee);
$id_type = ($request->id_type);
$id_department = ($request->id_department);
$date = ($request->date);
$notes = ($request->notes);
$status = ($request->status);
$idinsurances = ($request->idinsurances);
$id_process = "";
$plan = ($request->plan);
$license = ($request->license);
$cert = ($request->cert);
$contractor = ($request->contractor);
$place = ($request->place);
$reception = "";
$delivered = "";
$in_status = ($request->in_status);

$sql = "UPDATE `insurances` SET `plan` = '$plan', `license` = '$license', `cert` = '$cert', `status` = '$in_status' WHERE `idinsurances` = '$idinsurances';"

if(mysqli_query($con,$sql)){
    http_response_code(200);
}else{
    http_response_code(400);
}
?>