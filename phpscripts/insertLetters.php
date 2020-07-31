<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$idletters = ($request->idletters);
$id_process = ($request->id_process);
$type = ($request->type);
$company = ($request->company);
$patronal_number = ($request->patronal_number);
$emition_date = ($request->emition_date);
$language = ($request->language);
$position = ($request->position);
$department = ($request->department);
$base_salary = ($request->base_salary);
$productivity_salary = ($request->productivity_salary);

$sql = "INSERT INTO `letters` (`idletters`, `id_process`, `type`, `company`, `patronal_number`, `emition_date`, `language`, `position`, `department`, `base_salary`, `productivity_salary`) VALUES (NULL, '$id_process', '$type', '$company', '$patronal_number', '$emition_date', '$language', '$position', '$department', '$base_salary', '$productivity_salary');";
if(mysqli_query($con,$sql)){
    http_response_code(200);
}else{
    http_response_code(400);
}
?>