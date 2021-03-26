<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';
require 'funcionesVarias.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$idpayment = validarDatos($request->idpayments);
$id_employee = validarDatos($request->id_employee);
$id_payment_method = validarDatos($request->id_paymentmethod);
$id_period = validarDatos($request->id_period);
$credits = validateDataToZero($request->credits);
$debits = validateDataToZero($request->debits);
$last_seventh = validateDataToZero($request->last_seventh);
$base_complete = validateDataToZero($request->base_complete);
$productivity_complete = validateDataToZero($request->productivity_complete);
$id_account_py = validateDataToZero($request->id_account_py);

$sql =  "INSERT INTO payments (`idpayments`, `id_employee`, `id_paymentmethod`, `id_period`, `credits`, `debits`, `last_seventh`, `base_complete`, `productivity_complete`, `id_account_py`) VALUES (NULL, $id_employee, $id_payment_method, $id_period, $credits, $debits, $last_seventh, $base_complete, $productivity_complete, $id_account_py);";

if(mysqli_query($con, $sql)){
  echo(mysqli_insert_id($con));
}else{
  http_response_code(404);
  $error = "0|" . mysqli_error($con);
  echo(json_encode($error));
}
?>