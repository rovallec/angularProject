<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$id_payment = ($request->id_payment);
$amount = ($request->amount);
$amount_hrs = ($request->amount_hrs);
$amount_ot = ($request->amount_ot);
$amount_holidays = ($request->amount_holidays);

$sql = "INSERT INTO `timekeeping_adjustments` (idtimekeeping_adjustments, id_payment, amount_hrs, amount_ot, amount_holidays) VALUES (null, $id_payment, $amount_hrs, $amount_ot, $amount_holidays);";
if(mysqli_query($con,$sql)){
  echo(mysqli_insert_id($con));
  http_response_code(200);
} else {
  http_response_code(400);
  $error = "0|" . $sql . "|" . mysqli_error($con);
  echo(json_encode($error));
}
?>