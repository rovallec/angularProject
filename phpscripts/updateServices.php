<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$idservices = ($request->idservices);
$id_process = ($request->id_process);
$id_employee = ($request->id_employee);
$name = ($request->name);
$amount = ($request->amount);
$max = ($request->max);
$frecuency = ($request->frecuency);
$status = ($request->status);
$current = ($request->current);

if($current != $amount){
    $sql = "UPDATE `services` SET `current` = $current WHERE `id_employee` = $id_employee";
}else{
    $sql = "UPDATE `services` SET `current` = $current, `status` = 0 WHERE `id_employee` = $id_employee";
}

if(mysqli_query($con,$sql)){
    echo(mysqli_insert_id($con));
}
?>