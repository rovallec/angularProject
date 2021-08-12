<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$id_employee = ($request->id_employee);
$id_paymentmethod = ($request->id_paymentmethod);
$id_account = ($request->id_account_py);

$sql = "INSERT INTO `minearsol`.`payments` (`id_employee`, `id_paymentmethod`, `id_period`) VALUES ('$id_employee', '$id_paymentmethod', '$id_period', $id_account);";

if(mysqli_query($con,$sql)){  
  echo(json_encode("1"));
}else{  
  $error = $sql . "|" . mysqli_error($con);
  echo(json_encode($error));
}
?>
