<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';
require 'funcionesVarias.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$idpayments = ($request->idpayments);
$type = ($request->type);
$amount = ($request->amount);

$sql = "INSERT INTO `credits` (`idcredits`, `id_payment`, `type`, `amount`) VALUES (null, $idpayments, '$type', '$amount');";

if(mysqli_query($con,$sql)){
  echo(mysqli_insert_id($con));
  http_response_code(200);
} else {
  http_response_code(400);
  $error = "0|" . $sql . "|" . mysqli_error($con);
  echo(json_encode($error));
}
?>