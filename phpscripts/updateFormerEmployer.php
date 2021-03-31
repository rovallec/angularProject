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
$id_employee = ($request->id_employee);


$sql = "UPDATE formeremployer SET 
        idemnization = '$idemnization',
        aguinaldo = '$aguinaldo',
        bono14 = '$bono14',
        igss = '$igss',
        taxpendingpayment = '$taxpendingpayment'
        WHERE id_employee = $id_employee";

if(mysqli_query($con,$sql)){
  echo(mysqli_insert_id($con));
} else {
  echo($sql);
}
?>