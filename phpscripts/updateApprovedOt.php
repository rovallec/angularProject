<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$id_employee = ($request->id_employee);
$amount = ($request->amount);
$id_period = ($request->id_period);

$sql = "UPDATE `approved_ot` SET `amount` = '$amount' WHERE `id_employee` = $id_employee AND `id_period` = $id_period;";
if(mysqli_query($con,$sql)){
    http_response_code(200);
}else{
    http_response_code(400);
}
?>