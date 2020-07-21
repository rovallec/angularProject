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
$idinsurances = "";
$id_process = "";
$plan = ($request->plan);
$license = "";
$cert = ($request->cert);
$contractor = ($request->contractor);
$place = ($request->place);
$reception = "";
$delivered = "";
$in_status = ($request->in_status);

$sql = "INSERT INTO `minearsol`.`hr_processes` (`idhr_processes`, `id_user`, `id_employee`, `id_type`, `id_department`, `date`, `notes`, `status`) VALUES (NULL, '$id_user', '$id_employee', '$id_type', '$id_department', '$date', '$notes', '$status');";
if(mysqli_query($con,$sql)){
    $id_processes = mysqli_inset_id($con);
}
$sql2 = "INSERT INTO `minearsol`.`insurances` (`idinsurances`, `id_process`, `plan`, `license`, `cert`, `contractor`, `place`, `reception`, `delivered`, `status`) VALUES (NULL, '$id_processes', '$plan', '$license', '$cert', '$contractor', '$place', '$reception', '$delivered, '$status');";
if(mysqli_query($con,$sql2)){
    http_response_code(200);
}else{
    http_response_code(400);
}
?>