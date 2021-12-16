<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
echo(json_encode($request));
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
$type = ($request->type);

$sql = "INSERT INTO `internal_processes` (`idinternal_processes`, `id_user`, `id_employee`, `name`, `date`, `notes`, `status`) VALUES (NULL, $id_user, $id_employee, '$proc_name', '$date', '$notes', '$proc_status');";
if(mysqli_query($con,$sql)){
    $id_process = mysqli_insert_id($con);
    $sql2 = "INSERT INTO `services` (`idservices`, `id_process`, `name`, `amount`, `max`, `frecuency`, `status`, `current`, `type`) VALUES (NULL, $id_process, '$name', $amount, $max, '$frecuency', 1, 0, $type);";
    if(mysqli_query($con,$sql2)){
        http_response_code(200);
    }
}else{
    http_response_code(400);
}
?>
