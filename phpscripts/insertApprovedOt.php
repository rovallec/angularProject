<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$id_employee = ($request->id_employee);
$name = ($request->name);
$nearsol_id = ($request->nearsol_id);
$amount = ($request->amount);
$id_period = ($request->id_period);

$sql = "INSERT INTO `approved_ot` (`idapproved_ot;`, `id_period`, `id_employee`, `amount`) VALUES (NULL, '$id_period', '$id_employee', '$amount');";
if(mysqli_query($con,$sql)){
    http_response_code(200);
}else{
    http_response_code(400);
}
?>