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
$idinternal_process = ($request->idinternal_process);
$id_user = ($request->id_user);
$proc_name = ($request->proc_name);
$date = ($request->date);
$proc_status = ($request->proc_status);
$notes = ($request->notes);

$sql =  "UPDATE internal_processes SET " .
        "  name = '$proc_name', " .
        "  date = '$date', " .
        "  id_user = '$id_user', " .
        "  status = '$proc_status', " .
        "  notes = '$notes' " .
        "WHERE idinternal_processes = $idinternal_process;";

if(mysqli_query($con,$sql)){    
    $sql2 = "UPDATE services SET " . 
            "  name =  '$name', " .
            "  amount = $amount, " .
            "  max = $max, " .
            "  frecuency = '$frecuency', " .
            "  status = '$status', " .
            "  current = $current " .
            "where idservices = $idservices;";
    if(mysqli_query($con,$sql2)){
        http_response_code(200);
    } else {
      http_response_code(402);
      echo($sql2);
    }
}else{
    http_response_code(400);
    echo($sql);
}
?>