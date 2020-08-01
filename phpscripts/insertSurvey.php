<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$idsupervisor_survey = ($request->idsupervisor_survey);
$id_process = ($request->id_process);
$amount = ($request->amount);
$approved_date = ($request->approved_date);
$notification_date = ($request->notification_date);
$score = ($request->score);

$sql = "INSERT INTO `supervisor_surveis` (`idsupervisor_surveis`, `id_process`, `amount`, `approved_date`, `notification_date`, `notification_date`, `score`) VALUES (NULL, '$id_process', '$amount', '$approved_date', '$notification_date', '$score');";
if(mysqli_query($con,$sql)){
    http_response_code(200);
}else{
    http_response_code(400);
}
?>