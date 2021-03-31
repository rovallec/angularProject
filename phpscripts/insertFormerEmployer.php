<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$id_employee = ($request->id_employee);
$idemnization = ($request->idemnization);
$aguinaldo = ($request->aguinaldo);
$bono14 = ($request->bono14);
$igss = ($request->igss);
$taxpendingpayment = ($request->taxpendingpayment);

$sql = "INSERT INTO `formeremployer` VALUES (NULL, $id_employee, '$idemnization', '$aguinaldo', '$bono14', '$igss', '$taxpendingpayment');";

if(mysqli_query($con, $sql)){
  http_response_code(200);
}else{
  http_response_code(400);
  echo(json_encode($sql));
}
?>