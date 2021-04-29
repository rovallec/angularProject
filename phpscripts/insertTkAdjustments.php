<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$id_payment = ($request->id_payment);
$amount = ($request->amount);

$sql = "INSERT INTO `timekeeping_adjustments` (idtimekeeping_adjustments, id_paymnet, amount) VALUES (null, $id_payment, $amount);";

if(mysqli_query($con,$sql)){
  echo(mysqli_insert_id($con));
  http_response_code(200);
} else {
  http_response_code(400);
  $error = "0|" . $sql . "|" . mysqli_error($con);
  echo(json_encode($error));
}
?>