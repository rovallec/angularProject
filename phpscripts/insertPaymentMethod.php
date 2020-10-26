<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$id_employee = ($request->id_employee);
$type = ($request->type);
$number = ($request->number);
$bank = ($request->bank);

$sql = "INSERT INTO `minearsol`.`payment_methods` (`idpayment_methods`, `id_employee`, `type`, `number`, `bank`) VALUES (NULL, $id_employee, '$type', '$number', '$bank');";

if(mysqli_query($con,$sql)){
    echo(mysqli_insert_id($con));
}
?>