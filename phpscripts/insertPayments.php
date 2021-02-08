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

$sql =  "INSERT INTO payments (`idpayments`, `id_employee`, `id_paymentmethod`, `id_period`) VALUES (NULL, $id_employee, $id_payment_method, $id_period)";

if(mysqli_query($con, $sql)){
    echo("1");
}else{
    echo("0");
    echo($sql);
    http_response_code(404);
}
?>